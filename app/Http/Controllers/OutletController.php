<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\CuisineType;
use App\Http\Requests;
use App\Http\Requests\CreateOutletRequest;
use App\locality;
use App\Location;
use App\LogDetails;
use App\LogLevel;
use App\Menu;
use App\MenuTitle;
use App\OrderDetails;
use App\Outlet;
use App\OutletCuisineType;
use App\Outletimage;
use App\Outletlatlong;
use App\OutletMapper;
use App\OutletOutletType;
use App\OutletSetting;
use App\OutletSourceMapper;
use App\OutletType;
use App\OutletTypeMapper;
use App\Owner;
use App\PaymentOption;
use App\Printer;
use App\PrintOption;
use App\Roles;
use App\SettingsMaster;
use App\Sources;
use App\State;
use App\Status;
use App\Timeslot;
use App\Unit;
use App\User;
use App\users;
use App\Utils;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Imagine;
use Maatwebsite\Excel\Facades\Excel;
use Zend\Validator\Sitemap\Loc;

//use Kodeine\Acl\Traits\HasRole;

class OutletController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['home']]);
    }

    public function index()
    {


        $user_id = Auth::user()->id;

        $Outlets = DB::table('outlets')
            ->select('outlets.id as oid', 'outlets.*', 'outlets_mapper.*')
            ->join('outlets_mapper', 'outlets.id', '=', 'outlets_mapper.outlet_id')
            ->where('outlets_mapper.owner_id', $user_id)
            ->get();
        //  print "<pre>";;print_r($Outlets);exit;
        return view('Outlets.index', array('Outlets' => $Outlets));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $locality = locality::all();
        $Outlet_type = OutletType::all();
        $cuisineType_type = CuisineType::all();
        $service_type = Outlet::all();
        $owner = Owner::where('user_name', Auth::user()->user_name)->first();

        $created_by = Auth::user()->created_by;
        $logged_in_user = Auth::user();
        //$users[''] = 'Select User';
        if ($logged_in_user->user_name == "govind") {
            $admin_user_list = Owner::lists('user_name', 'id');
            $users = Owner::lists('user_name', 'id');
        } else if ($created_by == '') {
            $users = DB::table('owners')->where('created_by', Auth::id())->lists('user_name', 'id');
            $users[Auth::id()] = Auth::user()->user_name;
        } else {
            $users = DB::table('owners')->where('created_by', $created_by)->lists('user_name', 'id');
            $users[$created_by] = DB::table('owners')->where('id', $created_by)->first()->user_name;
        }
        $users[''] = 'Select User';
        $admin_user_list[''] = 'Select Owner';
        //print_r($users);exit;
        $active = Outlet::all();
        $servicetax_no = Outlet::all();
        $tinno = Outlet::all();
        $timeslots = Timeslot::gettimeslotbyoutletid('');

        //payment options
        $payment_options = PaymentOption::get();

        $session_time_arr = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12');

        //sources
        $sources = Sources::all();

        return view('Outlets.create', array('admin_user_list' => $admin_user_list, 'session_time_arr' => $session_time_arr, 'payment_options' => $payment_options, 'users' => $users, 'timeslots' => $timeslots, 'countries' => $countries, 'Outlet_type' => $Outlet_type, 'cuisineType_type' => $cuisineType_type, 'service_type' => $service_type, 'active' => $active, 'servicetax_no' => $servicetax_no, 'tinno' => $tinno, 'states' => $states, 'cities' => $cities, 'locality' => $locality, 'user' => $owner, 'action' => 'add', 'get' => 'add', 'set' => 'add', 'tin' => 'add', 'pan_no' => 'add', 'service' => 'add', 'test' => 'add', 'create' => 'add', 'make' => 'add', 'sources' => $sources));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateOutletRequest $request)
    {

        //get invoice prefix
        $dine_in_pre = Input::get('dine_in_prefix');
        $take_away_pre = Input::get('take_away_prefix');
        $home_delivery_pre = Input::get('home_delivery_prefix');
        /*$sources = Input::get('source');*/
        $status_sms = Input::get('status_sms');

        if (isset($dine_in_pre) && $dine_in_pre != '') {
            $prefix_arr['dine_in'] = $dine_in_pre;
        }
        if (isset($take_away_pre) && $take_away_pre != '') {
            $prefix_arr['take_away'] = $take_away_pre;
        }
        if (isset($home_delivery_pre) && $home_delivery_pre != '') {
            $prefix_arr['home_delivery'] = $home_delivery_pre;
        }
        $prefix_arr_json = '';
        if (isset($prefix_arr) && sizeof($prefix_arr) > 0) {
            $prefix_arr_json = json_encode($prefix_arr);
        }

        $all_payment_option = PaymentOption::all()->lists("name", "id");
        $po_id = array();
        $online_id = "";
        foreach ($all_payment_option as $id => $name) {
            if (in_array(strtolower($name), ["online", "cash"])) {
                array_push($po_id, $id);
                if (strtolower($name) == "online") {
                    $online_id = $id;
                }
            }
        }
        $all_source = Sources::all()->lists("name", "id");
        $source_array = array();
        foreach ($all_source as $id => $name) {
            if (in_array(strtolower($name), ["payu", "paytm", "upi"])) {
                array_push($source_array, "$id");
            }
        }
        $payment_option = array();

        foreach ($po_id as $key => $value) {
            if ($value == $online_id) {
                $payment_option[$value] = $source_array;
            } else {
                $payment_option[$value] = [""];
            }
        }
        $outlet_payment_option = NULL;
        if (isset($po_id) && sizeof($po_id) > 0 && isset($source_array) && sizeof($source_array) > 0) {
            $outlet_payment_option = json_encode($payment_option);
        }

        $user_id = $request->bind_user;

        $Outlet = new Outlet();
        $Outlet->owner_id = $user_id;
        $Outlet->name = $request->Outlet_name;
        $Outlet->invoice_title = $request->invoice_title;
        $Outlet->code = strtoupper($request->outlet_code);
        $Outlet->invoice_prefix = $prefix_arr_json;
        $Outlet->company_name = ucwords($request->company_name);
        $Outlet->invoice_digit = $request->invoice_digit;
        $Outlet->country_id = $request->countries;
        $Outlet->state_id = $request->states;
        $Outlet->city_id = $request->cities;
        $Outlet->pincode = $request->pincode;
        $Outlet->address = $request->address;
        $Outlet->locality = $request->locality;
        $Outlet->takeaway_cost = isset($request->mininum_order_price) ? $request->mininum_order_price : "";
        $Outlet->famous_for = isset($request->famous_for) ? $request->famous_for : "";
        $Outlet->contact_no = $request->contact_no;
        $Outlet->delivery_numbers = $request->delivery_numbers;
        $Outlet->email_id = isset($request->email_id) ? $request->email_id : "";
        $Outlet->parse_order_email = $request->parse_order_email;
        $Outlet->url = isset($request->web_address) ? $request->web_address : "";
        $Outlet->status_sms = json_encode($status_sms);
        $Outlet->report_emails = rtrim($request->report_emails, ",");
        $Outlet->biller_emails = rtrim($request->biller_emails, ",");
        $Outlet->established_date = date('Y-m-d', strtotime($request->established_date));
        $Outlet->avg_cost_of_two = $request->avg_cost_of_two;
        $Outlet->payment_options = $outlet_payment_option;

        if (trim($request->upi) != "") {
            $Outlet->upi = $request->upi;
        }

        if ($request->stock_auto_decrement == 1)
            $Outlet->stock_auto_decrement = 1;
        else
            $Outlet->stock_auto_decrement = 0;

        if ($request->duplicate_watermark == 1) {
            $Outlet->duplicate_watermark = 1;
        } else {
            $Outlet->duplicate_watermark = 0;
        }

        if ($request->payment_info == 1) {
            $Outlet->payment_info = 1;
        } else {
            $Outlet->payment_info = 0;
        }

        if ($request->zoho_config == 1)
            $Outlet->zoho_config = 1;
        else
            $Outlet->zoho_config = 0;

        if ($request->add_stock_on_purchase == 1)
            $Outlet->add_stock_on_purchase = 1;
        else
            $Outlet->add_stock_on_purchase = 0;

        if ($request->users != null)
            $Outlet->authorised_users = implode(",", $request->users);

        $Outlet->active = $request->active;
        if (isset($Outlet->active) == '') {
            $Outlet->active = 'No';
        }

        $Outlet->service_type = serialize($request->service_type);
        if (isset($Outlet->service_type) == '') {
            $Outlet->service_type = '';
        }

        $Outlet->enable_service_type = serialize($request->enable_service_type);
        if (isset($Outlet->enable_service_type) == '') {
            $Outlet->enable_service_type = '';
        }

        if (trim($request->order_lable) != '') {
            $Outlet->order_lable = $request->order_lable;
        } else {
            $Outlet->order_lable = 'Table';
        }

        if (trim($request->token_lable) != '') {
            $Outlet->token_lable = $request->token_lable;
        } else {
            $Outlet->token_lable = 'Order';
        }

        $Outlet->session_time = $request->session_time;

        $success = $Outlet->save();


        if ($success) {
            $OutletId = $Outlet->id;

            if (isset($OutletId)) {

                //map outlet with loggedin user
                $outlet_mapper = new OutletMapper();
                $outlet_mapper->outlet_id = $Outlet->id;
                $outlet_mapper->owner_id = $user_id;
                $outlet_mapper->save();

                //map location with outlet
                $location_obj = new Location();
                $location_obj->name = 'location';
                $location_obj->outlet_id = $OutletId;
                $location_obj->default_location = 1;
                $location_obj->created_by = $user_id;
                $location_obj->updated_by = $user_id;
                $location_obj->save();
            }

            $rest_types = $request->Outlet_type;
            $cuisine_type = $request->cuisine_type;
            if (isset($request->opening_time) && $request->opening_time != '') {
                $Outlet->opening_time = $request->opening_time;
            }
            if (isset($request->closing_time) && $request->closing_time != '') {
                $Outlet->closing_time = $request->closing_time;
            }
            if (($request->count + $request->countf) == 0) {
                $no = 0;
            } else {
                $no = $request->count;
            }


            for ($i = 0; $i <= $no; $i++) {
                $fnm = Input::get('opening_time' . $i);
                $fval = Input::get('closing_time' . $i);

                if (isset($fnm) || isset($fval)) {
                    $timeslot = new Timeslot();
                    $timeslot->outlet_id = $Outlet->id;
                    $timeslot->from_time = $fnm;
                    $timeslot->to_time = $fval;
                    $timeslot->save();
                }
            }

            if (sizeof($rest_types) > 0) {
                foreach ($rest_types as $rest_type) {
                    $Outlet_type = new OutletTypeMapper();
                    $Outlet_type->outlet_id = $Outlet->id;
                    $Outlet_type->outlet_type_id = $rest_type;
                    $Outlet_type->save();
                }
            }
            if (sizeof($cuisine_type) > 0) {
                foreach ($cuisine_type as $cui_type) {
                    $cuisi_type = new OutletCuisineType();
                    $cuisi_type->outlet_id = $Outlet->id;
                    $cuisi_type->cuisine_type_id = $cui_type;
                    $cuisi_type->save();
                }
            }
            $Outletname = $request->Outlet_name;

            $statusarray = array('received', 'preparing', 'prepared', 'delivered');

            $checkstatus = Status::where('outlet_id', $Outlet->id)->get();
            if (sizeof($checkstatus) == 0) {
                for ($i = 0; $i < count($statusarray); $i++) {
                    $status = new status();
                    $status->status = $statusarray[$i];
                    $status->owner_id = $user_id;
                    $status->order = $i + 1;
                    $status->outlet_id = $Outlet->id;
                    $status->save();
                }
            }

            //            Mail::send('emails.outletadd', [],function($message) use($useremail,$Outletname)
            //            {
            //                $message->from('we@foodklub.in', 'FOODKLUB');
            //                $message->to($useremail, 'FOODKLUB');
            //                $message->bcc('parag@savitriya.com', 'Parag');
            //                $message->bcc('govind@savitriya.com', 'Govind');
            //                $message->bcc('moin@savitriya.com', 'Moin');
            //
            //                $message->subject("$Outletname");
            //            });

            /*Mail::send('emails.outletadd', [],function($message) use($useremail,$Outletname)
            {
                $message->from('we@foodklub.in', 'FOODKLUB');
                $message->to($useremail, 'FOODKLUB');
                $message->bcc('parag@savitriya.com', 'Parag');
                $message->bcc('govind@savitriya.com', 'Govind');
                $message->bcc('moin@savitriya.com', 'Moin');

                $message->subject("$Outletname");
            });*/

            $outlet_id = Session::get('outlet_session');
            if (!isset($outlet_id) && trim($outlet_id) == "") {
                Session::put('outlet_session', $Outlet->id);
            }
            return Redirect('/outlet')->with('success', 'Outlet added successfully');
        } else {
            return Redirect('/outlet')->with('error', 'Failed');
        }
    }

    // Outlet Bind Function

    public function bindOutlet()
    {

        $user_id = Auth::user()->id;
        $logged_in_user = Auth::user();
        $isAdmin = 1;
        $created_by = Auth::user()->created_by;
        if (isset($created_by) && $created_by > 0) {
            $isAdmin = 0;
        }

        if ($logged_in_user->user_name == "govind") {
            $owners = Owner::all();
        } else {
            $owners = Owner::where('created_by', $user_id)->orWhere('id', $user_id)->get();
        }
        $select_owners = ['' => 'Select Owner'];
        $all_users = array();
        foreach ($owners as $owner) {
            $select_owners[$owner->id] = $owner->user_name;
            array_push($all_users, $owner->id);
        }

        //$outlet_mappers = OutletMapper::getOutletMapperByOwnerId($user_id);
        $outlet_mappers = OutletMapper::join("owners", "owners.id", "=", "outlets_mapper.owner_id")
            ->whereIn('owner_id', $all_users)
            ->select("outlets_mapper.*", "owners.user_name as user_name")->get();

        $select_outlets = ['' => 'Select Outlet'];

        $order_type = Utils::getOrderType();

        if (isset($outlet_mappers) && sizeof($outlet_mappers) > 0) {
            foreach ($outlet_mappers as $om) {
                $select_outlets[$om->outlet_id] = Outlet::find($om->outlet_id)['name'];
            }
        }

        return view('Outlets.bind', array('is_admin' => $isAdmin, 'order_type' => $order_type, 'select_outlets' => $select_outlets, 'select_owners' => $select_owners, 'outlet_mappers' => $outlet_mappers, 'user_id' => $user_id));
    }

    public function bindAll()
    {
        $outlets = Outlet::all();
        foreach ($outlets as $outlet) {
            $outlet_id = $outlet->id;
            $owner_id = $outlet->owner_id;

            $outlet_mapper = new OutletMapper();
            $outlet_mapper->outlet_id = $outlet_id;
            $outlet_mapper->owner_id = $owner_id;
            $outlet_mapper->save();
        }
    }


    // Store Outlet Bind Function

    public function storeBindOutlet()
    {
        $outlet_id = Input::get('outlet_id');
        $owner_id = Input::get('owner_id');
        $receive_order = Input::get("order_receive");

        if ($outlet_id == '' || $owner_id == '') {
            return Redirect('/outletBind')->with('error', 'Something Wrong In Your Input');
        } else {

            $all_mappers = OutletMapper::all();
            $mapper_count = 0;

            foreach ($all_mappers as $all_mapper) {
                if ($all_mapper->outlet_id == $outlet_id) {
                    if ($all_mapper->owner_id == $owner_id) {
                        $mapper_count++;
                    }
                }
            }

            if ($mapper_count == 0) {
                $outlet_mapper = new OutletMapper();
                $outlet_mapper->outlet_id = $outlet_id;
                $outlet_mapper->owner_id = $owner_id;

                if (isset($receive_order) && sizeof($receive_order) > 0) {
                    $ord_receive = json_encode($receive_order);
                    $outlet_mapper->order_receive = $ord_receive;
                }
                $outlet_mapper->save();

                $owner = Owner::find($owner_id);
                if (isset($owner) && $owner->created_by != "") {
                    $locations = Location::where('outlet_id', $outlet_id)->lists('id');
                    if (sizeof($locations) > 0)
                        foreach ($locations as $index => $location_id) {
                            $get_location = Location::find($location_id);
                            $get_location->created_by = $owner_id;
                            $get_location->updated_by = $owner_id;
                            $get_location->save();
                        }
                }

                return Redirect('/outletBind')->with('success', 'Outlet Bind successfully');
            } else {
                return Redirect('/outletBind')->with('error', 'This Outlet Is Already bound with the selected User');
            }
        }
    }

    public function destroyOutletBind($id)
    {

        OutletMapper::where('id', $id)->delete();

        return Redirect('/outletBind')->with('success', 'Outlet Unbind successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     **/


    public function show($id)
    {
        $Outlet = Outlet::find($id);

        if (isset($Outlet->tinno) && $Outlet->tinno != '') {
            $tinno = $Outlet->tinno;
        } else {
            $tinno = '';
        }


        if (isset($Outlet->servicetax_no) && $Outlet->servicetax_no != '') {
            $servicetax_no = $Outlet->servicetax_no;
        } else {
            $servicetax_no = '';
        }

        if (isset($Outlet->active) && $Outlet->active != '') {
            $active = $Outlet->active;
        } else {
            $active = '';
        }
        //$active = $Outlet->active;
        if (isset($Outlet->service_type)) {
            $service_type = unserialize($Outlet->service_type);
        } else {
            $service_type = array();
        }

        $Outletmenu = [];
        $Outletinformation = [];
        $Outlettype = OutletTypeMapper::getoutlettypemapper($id);
        if (sizeof($Outlettype) > 0) {
            $Outlet_type = $Outlet->outlettypemapper;
        } else {
            $Outlet_type = array();
        }
        $cuisinetype = OutletCuisineType::getoutletcuisinetype($id);
        if (sizeof($cuisinetype) > 0) {
            $cuisinetype = $Outlet->outletcuisinetype;
        } else {
            $cuisinetype = array();
        }

        if (isset($Outlet->outlet_image)) {
            $image = 'uploads/profileimage/' . $Outlet->outlet_image;
        } else {
            $image = '';
        }
        $menuitems = Menu::getmenubyoutletid($id);
        //        print_r($menuitems);exit;
        $Outletsectioname = MenuTitle::getmenutitlebyrestaurantid($id);
        $timeslot = Timeslot::gettimeslotbyoutletid($id);

        if (isset($Outlet->country_id)) {
            $countries = Country::where('id', '=', $Outlet->country_id)->get();
        } else {
            $countries = array();
        }

        if (isset($Outlet->state_id)) {
            $states = State::getstatebyid($Outlet->state_id);
        } else {
            $states = array();
        }
        if (isset($Outlet->city_id)) {
            $cities = City::getcitybyid($Outlet->city_id);
        } else {
            $cities = array();
        }


        if (isset($Outlet->locality) && $Outlet->locality != "" && $Outlet->locality != 0) {
            $locality = locality::getlocalitybyid($Outlet->locality);
            //            print_r($locality);exit;
        } else {
            $locality = array();
        }


        if (isset($Outlet->established_date) && $Outlet->established_date != '0000-00-00') {
            $established_date = $Outlet->established_date;
        } else {
            $established_date = '';
        }
        $rest_images = Outletimage::getoutletimagesbyoutletid($id);
        $rest_latlong = Outletlatlong::getouletlatlongbyoutletid($id);

        $owner_id = Owner::menuOwner();
        $printers_list = Printer::where('created_by', $owner_id)->lists('printer_name', 'id');
        $printers = array();
        $printers[''] = 'Select Printers';
        foreach ($printers_list as $key => $value) {
            $printers[$key] = $value;
        }

        /*$settings = OutletSetting::join('setting_master','setting_master.id','=','outlet_settings.setting_id')
            ->where('outlet_id',$id)
            ->select('outlet_settings.setting_id','setting_master.setting_name','outlet_settings.setting_value')
            ->get();*/
        $master = SettingsMaster::lists('id');
        for ($i = 0; $i < sizeof($master); $i++) {
            $outlet_setting = OutletSetting::select('setting_value')
                ->where('outlet_id', $id)->where('setting_id', $master[$i])->first();
            if (isset($outlet_setting) && !empty($outlet_setting) && $outlet_setting != '') {
                $settings[$i]['id'] = $master[$i];
                switch (SettingsMaster::find($master[$i])->setting_name) {
                    case "feedbackPrint":
                        $settings[$i]['setting_name'] = "Print Feedback";
                        break;
                    case 'kotPrint':
                        $settings[$i]['setting_name'] = "Print KOT";
                        break;
                    case 'processbillPrint':
                        $settings[$i]['setting_name'] = "Print bill on table closure";
                        break;
                    case 'billPrint':
                        $settings[$i]['setting_name'] = "Print bill from past orders";
                        break;
                    case 'multipleKotPrint':
                        $settings[$i]['setting_name'] = "Print Multiple KOT";
                        break;
                    case 'mobileMandatory':
                        $settings[$i]['setting_name'] = "Mandatory Mobile Number";
                        break;
                    case 'duplicateKotPrint':
                        $settings[$i]['setting_name'] = "Allow Duplicate KOT Print";
                        break;
                    case 'orderNoReset':
                        $settings[$i]['setting_name'] = "Order No Reset";
                        break;
                    case 'incrementOrderNo':
                        $settings[$i]['setting_name'] = "Increment Order No";
                        break;
                    case 'bypassProcessBill':
                        $settings[$i]['setting_name'] = "Bypass Process Bill(Old feature)";
                        break;
                    case 'invoiceDate':
                        $settings[$i]['setting_name'] = "Add Invoice Date";
                        break;
                    case 'displayNoOfPerson':
                        $settings[$i]['setting_name'] = "Show No Of Person";
                        break;
                    case 'skipKotPrint':
                        $settings[$i]['setting_name'] = "Skip Kot Print";
                        break;
                    case 'skipBillPrint':
                        $settings[$i]['setting_name'] = "Skip Bill Print";
                        break;
                    case 'discountAfterTax':
                        $settings[$i]['setting_name'] = "Discount after Tax";
                        break;
                    case 'beepOnKot':
                        $settings[$i]['setting_name'] = "Beep Sound On KOT Print";
                        break;
                    default:
                        $settings[$i]['setting_name'] = SettingsMaster::find($master[$i])->setting_name;
                }
                $settings[$i]['setting_org_name'] = SettingsMaster::find($master[$i])->setting_name;
                $settings[$i]['setting_value'] = $outlet_setting->setting_value;
            } else {
                $settings[$i]['id'] = $master[$i];
                switch (SettingsMaster::find($master[$i])->setting_name) {
                    case "feedbackPrint":
                        $settings[$i]['setting_name'] = "Print Feedback";
                        break;
                    case 'kotPrint':
                        $settings[$i]['setting_name'] = "Print KOT";
                        break;
                    case 'processbillPrint':
                        $settings[$i]['setting_name'] = "Print bill from past orderss";
                        break;
                    case 'billPrint':
                        $settings[$i]['setting_name'] = "Print bill on table closure";
                        break;
                    case 'multipleKotPrint':
                        $settings[$i]['setting_name'] = "Print Multiple KOT";
                        break;
                    case 'mobileMandatory':
                        $settings[$i]['setting_name'] = "Mandatory Mobile Number";
                        break;
                    case 'duplicateKotPrint':
                        $settings[$i]['setting_name'] = "Allow Duplicate KOT Print";
                        break;
                    case 'orderNoReset':
                        $settings[$i]['setting_name'] = "Order No Reset";
                        break;
                    case 'incrementOrderNo':
                        $settings[$i]['setting_name'] = "Increment Order No.";
                        break;
                    case 'bypassProcessBill':
                        $settings[$i]['setting_name'] = "Bypass Process Bill(Old feature)";
                        break;
                    case 'invoiceDate':
                        $settings[$i]['setting_name'] = "Invoice Date";
                        break;
                    case 'displayNoOfPerson':
                        $settings[$i]['setting_name'] = "Show No Of Person";
                        break;
                    default:
                        $settings[$i]['setting_name'] = SettingsMaster::find($master[$i])->setting_name;
                }
                //$settings[$i]['setting_name'] = SettingsMaster::find($master[$i])->setting_name;
                $settings[$i]['setting_org_name'] = SettingsMaster::find($master[$i])->setting_name;
                $settings[$i]['setting_value'] = SettingsMaster::where('id', $master[$i])->select('setting_default as setting_value')->first()->setting_value;
            }
        }
        $sources = Sources::all()->lists('name', 'id');
        $payment_options = PaymentOption::all()->lists('name', 'id');
        $payment_options_without_source = PaymentOption::where("without_source", 1)->lists('id');
        // print_r($payment_options_without_source->toArray());exit;

        //Delivery charge slabs
        $delivery_charges = '';
        if (isset($Outlet->delivery_charge) && $Outlet->delivery_charge != '') {
            $delivery_charges = json_decode($Outlet->delivery_charge);
        }

        //Tax details slabs
        $tax_details = '';

        if (isset($Outlet->tax_details) && $Outlet->tax_details != '') {
            $tax_details = json_decode($Outlet->tax_details);
        }

        $zoho_username = isset($Outlet->zoho_username) ? $Outlet->zoho_username : "";
        $zoho_password = isset($Outlet->zoho_password) ? $Outlet->zoho_password : "";
        $zoho_org_id = isset($Outlet->zoho_organization_id) ? $Outlet->zoho_organization_id : "";
        $zoho_ids = isset($Outlet->payment_option_identifier) ? json_decode($Outlet->payment_option_identifier, "false") : "";

        $dup_kot_count = isset($Outlet->duplicate_kot_count) ? $Outlet->duplicate_kot_count : 0;

        // echo "laravel 5.1.11 <pre>"; print_r($payment_options_without_source); echo "</pre>"; exit;
        return view('Outlets.show', array(
            'sources' => $sources,
            'payment_options' => $payment_options,
            'payment_options_without_source' => $payment_options_without_source->toArray(),
            'outlet' => $Outlet,
            'tinno' => $tinno,
            'settings' => $settings,
            'servicetax_no' => $servicetax_no,
            'service_type' => $service_type,
            'Outlettype' => $Outlet_type,
            'cuisinetypes' => $cuisinetype,
            'menuitems' => $menuitems,
            'Outletimages' => $rest_images,
            'image' => $image,
            'Outlet_latlong' => $rest_latlong,
            'Outletsectioname' => $Outletsectioname,
            'timeslot' => $timeslot,
            'states' => $states,
            'cities' => $cities,
            'locality' => $locality,
            'established_date' => $established_date,
            'active' => $active,
            'countries' => $countries,
            'printers' => $printers,
            'dp_kot_count' => $dup_kot_count,
            'delivery_charge' => $delivery_charges,
            'tax_details' => $tax_details,
            'zoho_username' => $zoho_username,
            'zoho_password' => $zoho_password,
            'zoho_ids' => $zoho_ids,
            'zoho_org_id' => $zoho_org_id
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $Outlet = Outlet::find($id);

        //check taxes
        if (isset($Outlet->taxes) && $Outlet->taxes != '') {
            $Outlet->taxes = json_decode($Outlet->taxes);
        } else {
            $Outlet->taxes = NULL;
        }

        //check status sms
        if (isset($Outlet->status_sms) && $Outlet->status_sms != '') {
            $Outlet->status_sms = json_decode($Outlet->status_sms);
        } else {
            $Outlet->status_sms = NULL;
        }

        if (isset($Outlet->invoice_prefix) && $Outlet->invoice_prefix != '') {
            $Outlet->invoice_prefix = json_decode($Outlet->invoice_prefix);
        } else {
            $Outlet->invoice_prefix = NULL;
        }

        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $locality = locality::all();
        $Outlet_type = OutletType::all();
        $selectedOutletType = OutletTypeMapper::getoutlettypemapper($id);
        //$users=DB::table('owners')->lists('user_name','id');
        $logged_in_user = Auth::user();
        $created_by = Auth::user()->created_by;
        $outlet_mapper = array();
        $outlet_mapper = OutletMapper::where("outlet_id", $id)->lists("owner_id");

        if ($logged_in_user->user_name == "govind") {
            $admin_user_list = Owner::lists('user_name', 'id');
            $users = Owner::lists('user_name', 'id');
        } else if ($created_by == '') {
            $users = DB::table('owners')->where('created_by', Auth::id())->lists('user_name', 'id');
            $users[Auth::id()] = Auth::user()->user_name;
            $admin_user_list[Auth::id()] = Auth::user()->user_name;
        } else {
            $users = DB::table('owners')->where('created_by', $created_by)->lists('user_name', 'id');
            $users[$created_by] = DB::table('owners')->where('id', $created_by)->first()->user_name;
            $admin_user_list[$created_by] = DB::table('owners')->where('id', $created_by)->first()->user_name;
        }
        $users[''] = 'Select User';

        $cuisineType_type = CuisineType::all();
        $selectedCuisineType = OutletCuisineType::getoutletcuisinetype($id);

        $service_types = Outlet::all();
        $abc = unserialize($Outlet->service_type);
        $enable_service_type = unserialize($Outlet->enable_service_type);
        /*$enable_payment_options = unserialize($Outlet->payment_options);*/
        $selectedservice_type = Outlet::Outletbyid($id);
        $active = Outlet::all();
        $selectedactive = Outlet::Outletbyid($id);

        if (isset($Outlet->established_date) && $Outlet->established_date == '0000-00-00') {
            $established_date = '';
        } else {
            $established_date = $Outlet->established_date;
        }

        $timeslots = Timeslot::gettimeslotbyoutletid($id);

        if (isset($Outlet->takeaway_cost) && $Outlet->takeaway_cost == '0') {
            $takeaway_cost = '';
        } else {
            $takeaway_cost = $Outlet->takeaway_cost;
        }

        //sources
        /*$sources = Sources::all();*/

        //payment options
        $payment_options = PaymentOption::get();

        //get selected sources
        $sel_sources = OutletSourceMapper::where('outlet_id', $id)->lists('source_id');

        //session time array
        $session_time_arr = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12');

        //print_r($users);exit;
        return view('Outlets.edit', array(
            'payment_options' => $payment_options,
            'sel_source' => $sel_sources,
            'users' => $users,
            'admin_user_list' => $admin_user_list,
            'bind_user' => $outlet_mapper,
            'Outlet' => $Outlet,
            'takeaway_cost' => $takeaway_cost,
            'established_date' => $established_date,
            'timeslots' => $timeslots,
            'Outlet_type' => $Outlet_type,
            'service_types' => $service_types,
            'selectedservice_type' => $selectedservice_type,
            'abc' => $abc,
            'enable_service_type' => $enable_service_type,
            'selectedOutletType' => $selectedOutletType,
            'selectedCuisineType' => $selectedCuisineType,
            'active' => $active,
            'selectedactive' => $selectedactive,
            'cuisineType_type' => $cuisineType_type,
            'states' => $states,
            'cities' => $cities,
            'locality' => $locality,
            'countries' => $countries,
            'session_time_arr' => $session_time_arr,
            'action' => 'edit',
            'get' => 'edit',
            'set' => 'edit',
            'test' => 'edit',
            'create' => 'edit',
            'make' => 'edit',
            'edate' => 'edit',
            'pan_no' => 'edit',
            'service' => 'edit',
            'tin' => 'edit'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     **/


    public function update($id, CreateOutletRequest $request)
    {
        //get invoice prefix
        $dine_in_pre = trim(Input::get('dine_in_prefix'));
        $take_away_pre = Input::get('take_away_prefix');
        $home_delivery_pre = Input::get('home_delivery_prefix');
        $sources = Input::get('source');
        $status_sms = Input::get('status_sms');
        $bind_user = Input::get('bind_user');


        if (isset($dine_in_pre) && $dine_in_pre != '') {
            $prefix_arr['dine_in'] = $dine_in_pre;
        }
        if (isset($take_away_pre) && $take_away_pre != '') {
            $prefix_arr['take_away'] = $take_away_pre;
        }
        if (isset($home_delivery_pre) && $home_delivery_pre != '') {
            $prefix_arr['home_delivery'] = $home_delivery_pre;
        }
        $prefix_arr_json = '';
        if (isset($prefix_arr) && sizeof($prefix_arr) > 0) {
            $prefix_arr_json = json_encode($prefix_arr);
        }

        $user_id = Auth::user()->id;
        $Outlet = Outlet::find($id);
        $Outlet->name = $request->Outlet_name;
        $Outlet->invoice_title = $request->invoice_title;
        $Outlet->code = strtoupper($request->outlet_code);
        $Outlet->invoice_prefix = $prefix_arr_json;
        $Outlet->company_name = ucwords($request->company_name);
        $Outlet->invoice_digit = $request->invoice_digit;
        $Outlet->country_id = $request->countries;
        $Outlet->state_id = $request->states;
        $Outlet->city_id = $request->cities;
        $Outlet->pincode = $request->pincode;
        $Outlet->address = $request->address;
        $Outlet->locality = $request->locality;
        $Outlet->famous_for = $request->famous_for;
        $Outlet->contact_no = $request->contact_no;
        $Outlet->delivery_numbers = $request->delivery_numbers;
        $Outlet->email_id = $request->email_id;
        $Outlet->parse_order_email = $request->parse_order_email;
        $Outlet->url = $request->web_address;
        $Outlet->report_emails = rtrim($request->report_emails, ",");
        $Outlet->biller_emails = rtrim($request->biller_emails, ",");
        //$Outlet->upi = $request->upi;//isset($request->upi)?$request->upi:"";
        $Outlet->avg_cost_of_two = $request->avg_cost_of_two;
        $Outlet->takeaway_cost = $request->mininum_order_price;
        $Outlet->established_date = date('Y-m-d', strtotime($request->established_date));
        $Outlet->status_sms = json_encode($status_sms);
        $Outlet->servicetax_no = $request->servicetax_no;

        if (trim($request->upi) != "") {
            $Outlet->upi = $request->upi;
        }

        if ($request->stock_auto_decrement == 1)
            $Outlet->stock_auto_decrement = 1;
        else
            $Outlet->stock_auto_decrement = 0;

        if ($request->add_stock_on_purchase == 1)
            $Outlet->add_stock_on_purchase = 1;
        else
            $Outlet->add_stock_on_purchase = 0;

        if ($request->zoho_config == 1)
            $Outlet->zoho_config = 1;
        else
            $Outlet->zoho_config = 0;

        if ($request->users != null)
            $Outlet->authorised_users = implode(",", $request->users);

        if (sizeof($request->service_type) > 0)   /// checked into unchecked and update
        {
            $Outlet->service_type = serialize($request->service_type);
        } else {
            $Outlet->service_type = '';
        }

        if ($request->duplicate_watermark == 1) {
            $Outlet->duplicate_watermark = 1;
        } else {
            $Outlet->duplicate_watermark = 0;
        }

        if ($request->payment_info == 1) {
            $Outlet->payment_info = 1;
        } else {
            $Outlet->payment_info = 0;
        }

        if (sizeof($request->enable_service_type) > 0)   /// checked into unchecked and update
        {
            $Outlet->enable_service_type = serialize($request->enable_service_type);
        } else {
            $Outlet->enable_service_type = '';
        }
        $Outlet->active = $request->active;
        if (isset($Outlet->active) == '') {
            $Outlet->active = 'No';
        }

        if (trim($request->order_lable) != '') {
            $Outlet->order_lable = $request->order_lable;
        } else {
            $Outlet->order_lable = 'Table';
        }

        if (trim($request->token_lable) != "") {
            $Outlet->token_lable = $request->token_lable;
        } else {
            $Outlet->token_lable = 'Order';
        }

        $Outlet->session_time = $request->session_time;

        $success = $Outlet->save();
        if ($success) {
            $OutletId = $Outlet->id;
            $old_Outlet_type = OutletTypeMapper::deleteoutlettype($Outlet->id);
            $old_cuisine_type = OutletCuisineType::deleteoutletcuisinetype($Outlet->id);
            $old_timeslot = Timeslot::deletetimeslotbyoutletid($OutletId);

            //delete old sources for this outlet
            $ot_source_mapper = OutletSourceMapper::deletesourcebyoutletid($OutletId);


            $rest_types = $request->Outlet_type;
            $cuisine_type = $request->cuisine_type;

            if (($request->count + $request->countf) == 0) {
                $no = 0;
            } else {
                $no = $request->count + $request->countf;
            }

            for ($i = 0; $i <= $no; $i++) {
                $fnm = Input::get('opening_time' . $i);

                $fval = Input::get('closing_time' . $i);


                if (isset($fnm) || isset($fval)) {
                    $timeslot = new Timeslot();
                    $timeslot->outlet_id = $Outlet->id;
                    $timeslot->from_time = $fnm;
                    $timeslot->to_time = $fval;
                    $timeslot->save();
                }
            }
            if (isset($rest_types)) {
                foreach ($rest_types as $rest_type) {
                    $Outlet_type = new OutletTypeMapper();
                    $Outlet_type->outlet_id = $id;
                    $Outlet_type->outlet_type_id = $rest_type;
                    $Outlet_type->save();
                }
            }
            if (isset($cuisine_type)) {
                foreach ($cuisine_type as $cui_type) {
                    $cuisi_type = new OutletCuisineType();
                    $cuisi_type->outlet_id = $id;
                    $cuisi_type->cuisine_type_id = $cui_type;
                    $cuisi_type->save();
                }
            }
            $useremail = Auth::user()->email;
            $Outletname = $request->Outlet_name;

            //            if(isset($request->active) && $request->active=='Yes'){
            //                Mail::send('emails.outletactive', [],function($message) use($useremail,$Outletname)
            //                {
            //                    $message->from('we@foodklub.in', 'FOODKLUB');
            //                    $message->to($useremail, 'FOODKLUB');
            //                    $message->bcc('parag@savitriya.com', 'Parag');
            //                    $message->bcc('govind@savitriya.com', 'Govind');
            //                    $message->bcc('moin@savitriya.com', 'Moin');
            //
            //                    $message->subject($Outletname);
            //                });
            //            }
            return Redirect('/outlet')->with('success', 'Outlet Updated successfully');
        } else {
            return Redirect('/outlet')->with('error', 'Failed');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Outlet::where('id', $id)->delete();
        OutletMapper::where('outlet_id', $id)->delete();
        Session::flash('flash_message', 'Successfully deleted the Restaurant!');
        return Redirect::to('outlet');
    }

    public function addlocation($id)
    {

        $getpreviouslatlong = Outlet::where('id', $id)->get();

        //updated if previously added or added if the Outlet is new Outlet latitude longitude
        if (sizeof($getpreviouslatlong) > 0) {
            $Outlet_latlong = Outlet::find($id);
            $Outlet_latlong->lat = Input::get('latitude');
            $Outlet_latlong->long = Input::get('longitude');
            $Outlet_latlong->save();
        }

        return \Redirect::route('outlet.show', [$id])->with('message', 'Latitude Longitude saved correctly!!!');
    }

    public function importOutletexcel()
    {
        if (Input::hasFile('file1')) {
            $file = Input::file('file1');

            $type = ($file->getMimeType());
            if ($type == 'application/vnd.ms-office' || $type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $type == 'application/zip') {
                $path = $file->getRealPath();

                Excel::selectSheets('Sheet1')->load($path, function ($reader) {
                    // Getting all results
                    $results = $reader->get();

                    $title = "";
                    $outlet_id = "";
                    foreach ($results as $result) {
                        $existingOutlets = Outlet::getoutletbyname($result['restaurant_name']);

                        if (sizeof($existingOutlets) <= 0) {
                            $Outlet = new Outlet();
                            $Outlet->name = $result['restaurant_name'];
                            $Outlet->owner_id = Auth::user()->id;
                            $Outlet->save();
                            $outlet_id = $Outlet['id'];
                        } else {
                            $outlet_id = $existingOutlets->id;
                        }
                        $menu = new Menu();
                        $menutitle = new MenuTitle();
                        $menutitlestored = MenuTitle::getmenutitleofoutletandname($outlet_id, $result['title']);
                        if (isset($result['title']) && $result['title'] != "") {
                            if (sizeof($menutitlestored) <= 0) {
                                $menutitle->outlet_id = $outlet_id;
                                $menutitle->title = $result['title'];
                                $success = $menutitle->save();
                                $title = $menutitle->id;
                            } else {
                                $title = $menutitlestored->id;
                            }

                            if ($success) {
                                if (isset($result['cuisine_type']) && $result['cuisine_type'] != "") {
                                    $cuisine_typeid = CuisineType::getcuisinetypebyname($result['cuisine_type']);
                                    $cuisine_typeid = $cuisine_typeid['id'];
                                } else {
                                    $cuisine_typeid = '';
                                }
                                $menu->menu_title_id = $title;
                                $menu->outlet_id = $outlet_id;
                                $menu->item = $result->item;
                                $menu->price = $result->price;
                                $menu->cuisine_type_id = $cuisine_typeid;
                                $menu->options = $result->options;
                                $menu->active = 'No';    // added active field
                                $menu->save();
                            }
                        }
                    }
                });

                return Redirect('/outlet')->with('success', 'Record Added successfully');
            } else {
                return Redirect::back()->with('failure', 'Only ".xls" file is acceptable');
            }
        }
    }

    public function exportexcel()
    {

        $login_user = Auth::id();
        $owner = Owner::where('id', $login_user)->select('created_by')->first();
        if ($owner->created_by != "") {
            $menu_owner = $owner->created_by;
        } else {
            $menu_owner = $login_user;
        }

        $restname = Owner::where('id', $menu_owner)->first();

        $result = array();
        $menutitle = DB::table('menu_titles')->where('created_by', $menu_owner)->get();
        $i = 0;

        foreach ($menutitle as $menu_title) {

            $menu = Menu::join('unit as un', 'un.id', '=', 'menus.unit_id')
                ->leftjoin('unit as un1', 'un1.id', '=', 'menus.order_unit')
                ->select('menus.*', 'un.name as unit', 'un1.name as order_unit_name')
                ->where('menus.menu_title_id', $menu_title->id)
                ->get();

            foreach ($menu as $men) {

                if (isset($menu_title->title_order))
                    $result[$i]['title_order'] = $menu_title->title_order;
                else
                    $result[$i]['title_order'] = 1;
                $result[$i]['title'] = $menu_title->title;
                if (isset($men->item_order))
                    $result[$i]['item_order'] = $men->item_order;
                else
                    $result[$i]['item_order'] = 1;

                if ($men->item_code == '') {
                    $result[$i]['item_code'] = $i;
                } else {
                    $result[$i]['item_code'] = $men->item_code;
                }

                $result[$i]['item'] = $men->item;
                $result[$i]['price'] = number_format($men->price, 2);
                $result[$i]['purchase price'] = number_format($men->buy_price, 2);
                $result[$i]['alias'] = $men->alias;

                if (isset($men->unit))
                    $result[$i]['unit'] = $men->unit;
                else
                    $result[$i]['unit'] = '';

                if (isset($men->order_unit_name))
                    $result[$i]['order_unit'] = $men->order_unit_name;
                else
                    $result[$i]['order_unit'] = '';

                $other_unit = '';
                if (isset($men->secondary_units) && $men->secondary_units != '') {
                    $unts = json_decode($men->secondary_units);
                    $other_unit = '';
                    foreach ($unts as $key => $u) {
                        $check_unit = Unit::find($key);
                        if (isset($other_unit) && $other_unit != '') {
                            $other_unit .= ", " . $check_unit->name . "=" . $u;
                        } else {
                            $other_unit = $check_unit->name . "=" . $u;
                        }
                    }
                }
                $result[$i]['other_units'] = $other_unit;

                if (isset($men->details))
                    $result[$i]['details'] = $men->details;
                else
                    $result[$i]['details'] = "";

                if (isset($men->tax_slab))
                    $result[$i]['tax_slab'] = $men->tax_slab;
                else
                    $result[$i]['tax_slab'] = "";

                if (isset($men->hsn_sac_code))
                    $result[$i]['hsn_sac_code'] = $men->hsn_sac_code;
                else
                    $result[$i]['hsn_sac_code'] = "";

                if (isset($men->discount_type))
                    $result[$i]['discount_type'] = $men->discount_type;
                else
                    $result[$i]['discount_type'] = "";

                if (isset($men->discount_value))
                    $result[$i]['discount_value'] = $men->discount_value;
                else
                    $result[$i]['discount_value'] = "";

                $foodtype = $men->food;
                if (strcmp($foodtype, 'veg') == 0)
                    $result[$i]['foodtype'] = 'veg';
                elseif (strcmp($foodtype, 'nonveg') == 0)
                    $result[$i]['foodtype'] = 'nonveg';
                else
                    $result[$i]['foodtype'] = '';

                if (isset($men->image) || $men->image != "")
                    $result[$i]['image'] = $men->image;
                else
                    $result[$i]['image'] = "";

                if (isset($men->barcode) || $men->barcode != "")
                    $result[$i]['barcode'] = $men->barcode;
                else
                    $result[$i]['barcode'] = "";


                $i++;
            }
        }
        ob_end_clean();
        ob_start();
        Excel::create($restname['user_name'] . '\'s', function ($excel) use ($result) {
            $excel->sheet('Sheet1', function ($sheet) use ($result) {

                $sheet->setOrientation('landscape');
                $sheet->fromArray($result);
            });
        })->download('xls');

        $out = ob_get_contents();

        error_log($out);

        //Cleaning the ouput buffer
        ob_end_clean();
    }


    public function exportdetailexcel()
    {

        $restname = Outlet::all();

        $result = array();
        //$j=0;
        $i = 0;
        foreach ($restname as $restid) {
            $name = $restid->name;

            $menutitle = DB::table('menu_titles')->where('outlet_id', $restid->id)->get();


            foreach ($menutitle as $menu_title) {
                $menu = DB::table('menus')->where('menu_title_id', $menu_title->id)->get();

                foreach ($menu as $men) {
                    $result[$i]['restaurant_name'] = $name;
                    $result[$i]['title'] = $menu_title->title;
                    $result[$i]['item'] = $men->item;
                    $result[$i]['price'] = $men->price;

                    $i++;
                }
            }
        }

        ob_end_clean();
        ob_start();
        Excel::create("All Restaurants Excel", function ($excel) use ($result) {
            $excel->sheet('Sheet1', function ($sheet) use ($result) {

                $sheet->setOrientation('landscape');
                $sheet->fromArray($result);
            });
        })->download('xls');

        $out = ob_get_contents();

        error_log($out);

        //Cleaning the ouput buffer
        ob_end_clean();
    }


    //    public function addlocation(){
    //        $Outlet_latlong=new Outletlatlong();
    //        $Outlet_latlong->outlet_id=Input::get('outlet_id');
    //        $Outlet_latlong->latitude=Input::get('latitude');
    //        $Outlet_latlong->longitude=Input::get('longitude');
    //        $Outlet_latlong->save();
    //        //    return "added Outlet Location";
    //        return view('Outlets.show',array('Outlet_latlong'=>$Outlet_latlong));
    //    }

    public function importoutletotherdetails()
    {
        if (Input::hasFile('outletfile')) {
            $file = Input::file('outletfile');

            $type = ($file->getMimeType());
            if ($type == 'application/vnd.ms-office' || $type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $type == 'application/zip') {
                $path = $file->getRealPath();

                Excel::selectSheets('Sheet1')->load($path, function ($reader) {
                    // Getting all results
                    $results = $reader->get();

                    $title = "";
                    $outlet_id = "";
                    foreach ($results as $result) {
                        $getstatebyname = State::getstatebyname($result['state']);
                        $getcitybyname = City::getcitybycityname($result['city']);
                        $getlocalitybyname = locality::getlocalitybylocalityname($result['locality']);
                        $existingOutlets = Outlet::getoutletbyname($result['restaurant_name']);

                        if (count($existingOutlets) > 0) {
                            $outletdetails = Outlet::findOutlet($existingOutlets->id);
                            $outletdetails->address = $result['address'];
                            if (sizeof($getstatebyname) > 0) {
                                $outletdetails->state_id = $getstatebyname[0]->id;
                            } else {
                                $outletdetails->state_id = 0;
                            }
                            if (sizeof($getcitybyname) > 0) {
                                $outletdetails->city_id = $getcitybyname[0]->id;
                            } else {
                                $outletdetails->city_id = 0;
                            }
                            if (sizeof($getlocalitybyname) > 0) {
                                $outletdetails->locality = $getlocalitybyname[0]->locality_id;
                            } else {
                                $locality = new locality();
                                $locality->city_id = $getcitybyname[0]->id;
                                $locality->locality = $result['locality'];
                                $locality->save();
                                $outletdetails->locality = $locality->id;
                            }
                            $outletdetails->pincode = $result['pincode'];
                            $outletdetails->famous_for = $result['famous_for'];
                            $outletdetails->contact_no = $result['contact_no'];
                            $outletdetails->email_id = $result['email_id'];
                            $outletdetails->url = $result['website'];
                            $outletdetails->established_date = $result['establishment_date'];
                            $outletdetails->avg_cost_of_two = $result['avg_cost_for_two'];
                            $outletdetails->save();

                            $outlet_id = $outletdetails->id;
                            $explodeddata = explode(',', $result['cuisine_type']);

                            $old_Outlet_type = OutletTypeMapper::deleteoutlettype($outlet_id);
                            $old_cuisine_type = OutletCuisineType::deleteoutletcuisinetype($outlet_id);

                            $i = 0;
                            foreach ($explodeddata as $explcui) {
                                $cuisine_data = DB::table('cuisine_types')->where('type', 'like', $explcui)->get();

                                if (sizeof($cuisine_data) > 0) {
                                    $cuisi_type = new OutletCuisineType();
                                    $cuisi_type->outlet_id = $outlet_id;
                                    $cuisi_type->cuisine_type_id = $cuisine_data[0]->id;
                                    $cuisi_type->save();
                                }
                                $i++;
                            }
                            $explodedouttypedata = explode(',', $result['rest_type']);
                            $j = 0;
                            foreach ($explodedouttypedata as $exploutlettype) {
                                $outlet_type_data = DB::table('outlet_types')->where('type', 'like', $exploutlettype)->get();

                                if (sizeof($outlet_type_data) > 0) {
                                    $cuisi_type = new OutletTypeMapper();
                                    $cuisi_type->outlet_id = $outlet_id;

                                    $cuisi_type->outlet_type_id = $outlet_type_data[0]->id;
                                    $cuisi_type->save();
                                }
                                $j++;
                            }
                        }
                    }
                });

                return Redirect('/outlet')->with('success', 'Record Added successfully');
            } else {
                return Redirect::back()->with('failure', 'Only ".xls" file is acceptable');
            }
        }
    }

    public function dailyreport()
    {
        $reporttype = Input::get('reporttype');
        $userid = Input::get('userid');
        $fromdate = date('Y-m-d', strtotime(Input::get('from_date')));
        $todate = date('Y-m-d', strtotime(Input::get('to_date')));

        $getrestaurant = Outlet::where('owner_id', $userid)->get();

        $getorders = OrderDetails::where('outlet_id', $getrestaurant[0]->id)->whereBetween('created_at', array($fromdate, $todate))->get();
        $result = array();

        $i = 0;
        $totalprice = '';
        if ($reporttype == 'summaryreport') {
            foreach ($getorders as $orderdetails) {
                $name = $orderdetails->name;

                $result[$i]['Outlet Name'] = $getrestaurant[0]->name;
                $result[$i]['Order Id'] = $orderdetails->suborder_id;
                $result[$i]['Customer Name'] = $name;
                $result[$i]['Customer Mobile Number'] = $orderdetails->user_mobile_number;

                $result[$i]['Total Price'] = $orderdetails->totalprice;
                $result[$i]['Payment Type'] = $orderdetails->paidtype;
                $result[$i]['Discount'] = $orderdetails->discount_value;
                $result[$i]['Cost After Discount'] = $orderdetails->totalcost_afterdiscount;
                $result[$i]['Status'] = $orderdetails->status;
                $result[$i]['Created At'] = date('g:ia \o\n l jS F Y', strtotime($orderdetails->created_at));

                $i++;
            }
        } elseif ($reporttype == 'detailedreport') {
            foreach ($getorders as $orderdetails) {
                $name = $orderdetails->name;
                $orderitem = DB::table('order_items')->where('order_id', $orderdetails->order_id)->get();
                foreach ($orderitem as $orderit) {
                    $menuitemname = DB::table('menus')->where('id', $orderit->item_id)->get();
                    $result[$i]['Outlet Name'] = $getrestaurant[0]->name;
                    $result[$i]['Order Id'] = $orderdetails->suborder_id;
                    if (sizeof($menuitemname) && isset($menuitemname[0]->item) && $menuitemname[0]->item != '') {
                        $result[$i]['Item Name'] = $menuitemname[0]->item;
                    }
                    $result[$i]['Item Quantity'] = $orderit->item_quantity;
                    $result[$i]['Item Price'] = $orderit->item_price;
                    $result[$i]['Customer Name'] = $name;
                    $result[$i]['Customer Mobile Number'] = $orderdetails->user_mobile_number;
                    $result[$i]['Total Price'] = $orderdetails->totalprice;
                    $result[$i]['Payment Type'] = $orderdetails->paidtype;
                    $result[$i]['Discount'] = $orderdetails->discount_value;
                    $result[$i]['Cost After Discount'] = $orderdetails->totalcost_afterdiscount;
                    $result[$i]['Status'] = $orderdetails->status;
                    $result[$i]['Created At'] = date('g:ia \o\n l jS F Y', strtotime($orderdetails->created_at));
                    if ($orderdetails->discount_value != '' && $orderdetails->discount_value != 0) {
                        $totalprice += $orderit->item_price;
                    } else {
                        $totalprice += $orderit->item_price;
                    }
                    $i++;
                }
            }
        }


        ob_end_clean();
        ob_start();
        Excel::create("All Restaurants Excel", function ($excel) use ($result, $totalprice) {
            $excel->sheet('Sheet1', function ($sheet) use ($result, $totalprice) {

                $sheet->setOrientation('landscape');
                $sheet->fromArray($result);
                $sheet->appendRow(array('Grand Total', $totalprice));
            });
        })->download('xls');
        $out = ob_get_contents();
        error_log($out);

        //Cleaning the ouput buffer
        ob_end_clean();
    }

    public function updatePrinters()
    {

        $outlet_id = Input::get('outlet_id');
        $kot_printer = Input::get('kot_printer');
        $duplicate_kot_printer = Input::get('duplicate_kot_printer');
        $bill_printer = Input::get('bill_printer');
        $res_printer = Input::get('response_printer');
        $dup_kot_count = Input::get('duplicate_kot_count');

        $outlet = Outlet::findOutlet($outlet_id);
        $printer = array();
        $printer['kot_printer'] = $kot_printer;
        $printer['duplicate_kot_printer'] = $duplicate_kot_printer == "" ? NULL : $duplicate_kot_printer;
        $printer['bill_printer'] = $bill_printer;
        $printer['response_printer'] = $res_printer;

        $outlet->printer = json_encode($printer);
        $outlet->duplicate_kot_count = $dup_kot_count;
        $result = $outlet->save();

        if ($result) {
            $data["status"] = "success";
            return $data;
        } else {
            $data["status"] = "error";
            return $data;
        }
    }

    //Get all outletlist for sending push notification
    public function getAllOutletList()
    {

        $outlets = Outlet::all();
        $outlet_arr = array();
        $outlet_arr[] = 'Select Outlet';
        foreach ($outlets as $outlet) {
            $outlet_arr[$outlet->id] = $outlet->name;
        }

        return $outlet_arr;
    }

    //For sending push notification
    public function pushLog(Request $request)
    {

        if ($request->ajax()) {
            $owner_id = Input::get('owner_id');
            $owner_data = Owner::find($owner_id);
            $owner_arr = array();
            if (isset($owner_data) && sizeof($owner_data) > 0) {
                $owner_arr['owner_id'] = $owner_data->id;
                $owner_arr['device_id'] = $owner_data->device_id;
                $owner_arr['owner_name'] = $owner_data->user_name;
            }
            $level = ['OFF', 'FATAL', 'ERROR', 'WARN', 'INFO', 'DEBUG', 'TRACE', 'ALL'];

            $get_level = LogLevel::where('owner_id', $owner_id)->get();
            $selected_level = isset($get_level) && sizeof($get_level) > 0 ? $get_level[0]->level : 0;

            return view('logPushList', array('devices' => $owner_data, 'levels' => $level, 'selected_level' => $selected_level));
        }
        $outlets = $this->getAllOutletList();

        return view('logPush', array('outlets' => $outlets));
    }

    //Available Log list
    public function loglist()
    {

        $logdetails = LogDetails::join('outlets', 'outlets.id', '=', 'log_details.outlet_id')
            ->join('owners', 'owners.id', '=', 'log_details.owner_id')
            ->select('log_details.*', 'outlets.name', 'owners.user_name')->get();


        return view('loglist', array('logdetails' => $logdetails));
    }


    public function updatePaymentMode()
    {

        $all_payment_detail = Input::all();

        $outlet_id = Input::get('outlet_id');

        unset($all_payment_detail['outlet_id']);

        $final_payment_opt = array();
        foreach ($all_payment_detail as $opt => $src) {
            if (is_array($src)) {
                $final_payment_opt[$opt] = $src;
            } else {
                $final_payment_opt[$opt] = [""];
            }
        }

        $ot = Outlet::find($outlet_id);
        $ot->payment_options = json_encode($final_payment_opt);
        $result = $ot->save();

        if ($result) {
            return 'success';
        } else {
            return 'error';
        }
    }

    public function updatePaymentId()
    {

        $all_payment_detail = Input::all();

        $outlet_id = Input::get('outlet_id');
        $zoho_username = Input::get("zoho_username");
        $zoho_password = Input::get("zoho_password");
        $zoho_org_id = Input::get("zoho_organization_id");

        unset($all_payment_detail['outlet_id']);
        unset($all_payment_detail['zoho_username']);
        unset($all_payment_detail['zoho_password']);
        unset($all_payment_detail['zoho_organization_id']);
        $result = 0;

        if (isset($all_payment_detail) && sizeof($all_payment_detail) > 0 && isset($zoho_username) && isset($zoho_password) && isset($zoho_org_id)) {

            $ot = Outlet::find($outlet_id);
            $ot->payment_option_identifier = json_encode($all_payment_detail);
            $ot->zoho_username = $zoho_username;
            $ot->zoho_password = $zoho_password;
            $ot->zoho_organization_id = $zoho_org_id;
            $result = $ot->save();
        }

        if ($result) {
            return 'success';
        } else {
            return 'error';
        }
    }

    public function getTaxes()
    {

        $outlet_id = Session::get('outlet_session');

        $Outlet = Outlet::find($outlet_id);

        $taxx = '';
        if (isset($Outlet->taxes) && $Outlet->taxes != '') {
            $taxx = json_decode($Outlet->taxes, true);
            //print_r($taxx);exit;
        }

        return view('Outlets.taxes', array('taxx' => $taxx));
    }


    public function updateTaxes()
    {

        $outlet_id = Session::get('outlet_session');


        $taxes = Input::get('taxes');

        $tx_arr = json_decode($taxes, true);

        if (isset($tx_arr) && sizeof($tx_arr) > 0) {
            $ot = Outlet::find($outlet_id);
            $ot->default_taxes = NULL;
            $ot->taxes = $taxes;
            $result = $ot->save();
        } else {
            return Response::json(
                array(
                    'status' => 'success',
                    'msg' => 'Please add any taxes',
                    'statuscode' => 200,
                ),
                200
            );
        }

        if ($result) {
            return Response::json(
                array(
                    'status' => 'success',
                    'msg' => 'Taxes has been updated successfully',
                    'statuscode' => 200,
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => 'error',
                    'msg' => 'There is some error occurred, Please try again later',
                    'statuscode' => 201,
                ),
                201
            );
        }
    }

    public function updateOrderTypeTaxes()
    {

        $outlet_id = Input::get('outlet_id');
        $dine_in = Input::get('dine_in');
        $take_away = Input::get('take_away');
        $home_delivery = Input::get('home_delivery');


        if ($dine_in != 'select' || $take_away != 'select' || $home_delivery != 'select') {

            $taxes = [];
            $temp_arr = array();

            if ($dine_in != 'select') {
                $temp_arr['dine_in'] = $dine_in;
            } else {
                $temp_arr['dine_in'] = NULL;
            }

            if ($take_away != 'select') {
                $temp_arr['take_away'] = $take_away;
            } else {
                $temp_arr['take_away'] = NULL;
            }

            if ($home_delivery != 'select') {
                $temp_arr['home_delivery'] = $home_delivery;
            } else {
                $temp_arr['home_delivery'] = NULL;
            }

            array_push($taxes, $temp_arr);

            $outlet_obj = Outlet::find($outlet_id);
            $outlet_obj->default_taxes = json_encode($taxes);
            $result = $outlet_obj->save();

            if ($result) {
                return 'success';
            } else {
                return 'error';
            }
        } else {
            $outlet_obj = Outlet::find($outlet_id);
            $outlet_obj->default_taxes = NULL;
            $result = $outlet_obj->save();

            if ($result) {
                return 'success';
            } else {
                return 'error';
            }
        }
    }

    public function storeDeliveryCharge()
    {

        $outlet_id = Input::get('outlet_id');
        $charges = Input::get('charges');

        $del_charge = array();

        $ot = Outlet::find($outlet_id);

        if (isset($charges) && sizeof($charges) > 0) {

            $chr_arr = array();
            foreach ($charges as $ord_amt => $chr) {
                $chr_arr[$ord_amt] = $chr[0]['del_charge'];
            }

            array_push($del_charge, $chr_arr);
            krsort($del_charge);

            $ot->delivery_charge = json_encode($del_charge, JSON_NUMERIC_CHECK);

            $result = $ot->save();
        } else {

            if ($ot->delivery_charge == '') {
                return Response::json(
                    array(
                        'status' => 'error',
                        'msg' => 'Please add any charges',
                        'statuscode' => 200,
                    ),
                    200
                );
            } else {

                $ot->delivery_charge = NULL;
                $result = $ot->save();

                return Response::json(
                    array(
                        'status' => 'success',
                        'msg' => 'Delivery charges has been removed successfully.',
                        'statuscode' => 200,
                    ),
                    200
                );
            }
        }

        if ($result) {

            return Response::json(
                array(
                    'status' => 'success',
                    'msg' => 'Delivery charges has been updated successfully',
                    'statuscode' => 200,
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => 'error',
                    'msg' => 'There is some error occurred, Please try again later',
                    'statuscode' => 201,
                ),
                201
            );
        }
    }

    #TODO: store tax detial like pan no. and gst no.
    public function storeTaxDetail()
    {

        $outlet_id = Input::get('outlet_id');
        $tax_details = Input::get('tx_detail');
        $tx_details = array();

        $ot = Outlet::find($outlet_id);

        if (isset($tax_details) && sizeof($tax_details) > 0) {

            $tx_detail_arr = array();
            foreach ($tax_details as $tx_fld => $tx_val) {
                $tx_detail_arr[$tx_fld] = $tx_val[0]['tx_value'];
            }

            array_push($tx_details, $tx_detail_arr);
            krsort($tx_details);

            $ot->tax_details = json_encode($tx_details, JSON_NUMERIC_CHECK);

            $result = $ot->save();
        } else {

            if ($ot->tax_details == '') {
                return Response::json(
                    array(
                        'status' => 'error',
                        'msg' => 'Please add any tax detail',
                        'statuscode' => 200,
                    ),
                    200
                );
            } else {

                $ot->tax_details = NULL;
                $result = $ot->save();

                return Response::json(
                    array(
                        'status' => 'success',
                        'msg' => 'Tax details has been removed successfully.',
                        'statuscode' => 200,
                    ),
                    200
                );
            }
        }

        if ($result) {

            return Response::json(
                array(
                    'status' => 'success',
                    'msg' => 'Tax details has been updated successfully',
                    'statuscode' => 200,
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => 'error',
                    'msg' => 'There is some error occurred, Please try again later',
                    'statuscode' => 201,
                ),
                201
            );
        }
    }

    public function ownerAppVersion()
    {

        $owners = Owner::all();

        $owner_arr = array();
        if (isset($owners) && sizeof($owners) > 0) {
            $i = 0;
            foreach ($owners as $ow) {

                $mapper = OutletMapper::join('outlets as ot', 'ot.id', '=', 'outlets_mapper.outlet_id')
                    ->where('ot.active', 'Yes')
                    ->where('outlets_mapper.owner_id', $ow->id)
                    ->select('ot.name as ot_name', 'outlets_mapper.order_receive')
                    ->get();

                $ot_name = '';
                if (isset($mapper) && sizeof($mapper) > 0) {

                    foreach ($mapper as $mp) {

                        if (isset($ot_name) && $ot_name != '') {
                            $ot_name .= ", " . $mp->ot_name;
                        } else {
                            $ot_name = $mp->ot_name;
                        }
                    }

                    $owner_arr[$i]['username'] = $ow->user_name;
                    $owner_arr[$i]['version'] = $ow->app_version;
                    $owner_arr[$i]['outlet'] = $ot_name;

                    $i++;
                }
            }
        }

        $order_type = Utils::getOrderType();
        return view('Outlets.ownerAppVersion', array('owner_arr' => $owner_arr, "order_type" => $order_type));
    }

    public function updateAppLayout()
    {

        $outlet_id = Input::get("outlet_id");
        $app_layout = Input::get("app_layout");
        $result = false;
        if (isset($outlet_id) && sizeof($outlet_id) > 0) {

            $outlet = Outlet::find($outlet_id);
            if (isset($outlet) && sizeof($outlet) > 0) {

                if ($app_layout == "tabbed_layout")
                    $outlet->app_layout = "tabbed_layout";
                else if ($app_layout == "category_layout")
                    $outlet->app_layout = "category_layout";
                $result = $outlet->save();
            }
        }

        if ($result) {

            return Response::json(
                array(
                    'status' => 'success',
                    'msg' => 'App Layout has been updated successfully',
                    'statuscode' => 200,
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => 'error',
                    'msg' => 'There is some error occurred, Please try again later',
                    'statuscode' => 201,
                ),
                201
            );
        }
    }


    #TODO: Bill layout
    public function billTemplate()
    {

        $outlet_id = Session::get('outlet_session');
        $outlet = Outlet::find($outlet_id);

        $line_arr = array();
        if (isset($outlet->bill_template_json) && $outlet->bill_template_json) {
            $line_arr = json_decode($outlet->bill_template_json);
        }
        //print_r($line_arr);exit;
        $keys = Utils::getBillTemplateKeys();

        $custom_fields = json_decode($outlet->custom_bill_print_fields);

        return view('Outlets.billTemplate', array('custom_fields' => $custom_fields, 'outlet' => $outlet, 'keys' => $keys, 'line_arr' => $line_arr));
    }

    #TODO: Edit Bill layout
    public function editBillTemplate()
    {

        $flag = Input::get("flag");
        $outlet_id = Session::get('outlet_session');
        $outlet = Outlet::find($outlet_id);

        $line_arr = array();
        if (isset($flag) && sizeof($flag) > 0) {
            $line_arr = json_decode('{"sequence":["1","2","3","3","4","5","5","6","6","7","8","9","10","11"],"font":["5","6","6","6","6","6","6","6","6","5","6","6","5","6"],"align":["center","center","left","left","left","left","left","left","left","center","left","left","center","center"],"key":["outlet_name","address","bill_lable","order_type","dash_line","user_name","date","invoice_no","pax","table_no","order_detail","tax_detail","qr_code","footer_note"],"bill_lable":"Retail Invoice","user_lable":"User:","date_lable":"Dt.","inv_lable":"Invoice#:","pax_lable":"Pax#:","footer_note":"Install Pikal on your Mobile  Powered By : www.pikal.io   Thank you. Please Visit Again."}');
        } else {

            $line_arr = array();
            if (isset($outlet->bill_template_json) && $outlet->bill_template_json) {
                $line_arr = json_decode($outlet->bill_template_json);
            }
        }

        $keys = Utils::getBillTemplateKeys();

        return view('Outlets.updateBillTemplate', array('keys' => $keys, 'line_arr' => $line_arr));
    }

    #TODO: Update Bill layout
    public function updateBillTemplate()
    {

        $fields = Input::all();
        //print_r($fields);exit;
        $outlet_id = Session::get('outlet_session');

        $template = '';
        $lable_cnt = 0;

        $custom_fields = [];

        //check custom field if available than add in array
        $custom_field_json = Outlet::find($outlet_id)->pluck('custom_bill_print_fields');

        if (isset($custom_field_json) && $custom_field_json != '') {
            $custom_field_arr = json_decode($custom_field_json);

            if (isset($custom_field_arr) && sizeof($custom_field_arr) > 0) {
                //print_r($custom_field_arr[0]);exit;
                foreach ($custom_field_arr as $cust_key => $cust_fields) {
                    if (isset($cust_fields) && sizeof($cust_fields) > 0) {
                        foreach ($cust_fields as $field_key => $cust_arr) {
                            $custom_fields[] = $field_key;
                        }
                    }
                }
            }
        }

        //print_r($custom_fields);exit;

        //get print option object
        $print = new PrintOption();

        if (isset($fields['sequence']) && sizeof($fields['sequence']) > 0) {

            $size = sizeof($fields['sequence']);

            for ($i = 0; $i < $size; $i++) {

                if (isset($fields['key'][$i])) {

                    if ($i > 0) {

                        if ($fields['sequence'][$i] != $fields['sequence'][$i - 1]) {
                            $print->newLine();
                        } else {

                            //check fontsize for previous line
                            $font_size = $fields['font'][$i - 1];

                            $chr_size = 48;
                            if ($font_size == 5) {
                                $chr_size = 24;
                            }

                            $current_line_lenght = $chr_size / 2;
                            if ($fields['key'][$i] == 'invoice_no') {

                                $current_line_lenght = 14;
                                $current_line_lenght += strlen($fields['inv_lable']);
                            } elseif ($fields['key'][$i] == 'user_name') {

                                $current_line_lenght = 15;
                                $current_line_lenght += strlen($fields['user_lable']);
                            } elseif ($fields['key'][$i] == 'date') {

                                $current_line_lenght = 21;
                                $current_line_lenght += strlen($fields['date_lable']);
                            } elseif ($fields['key'][$i] == 'pax') {

                                $current_line_lenght = 8;
                                $current_line_lenght += strlen($fields['pax_lable']);
                            } elseif ($fields['key'][$i] == 'bill_lable') {

                                $current_line_lenght = strlen($fields['bill_lable']);
                            } elseif ($fields['key'][$i] == 'order_type') {

                                $current_line_lenght = 14;
                            }
                            //calculate tab size
                            $till_current_line = $chr_size - $current_line_lenght;

                            $print->printTab($till_current_line, 0);
                        }
                    }

                    //align text
                    if ($fields['key'][$i] != 'new_line') {
                        if ($fields['align'][$i] == 'left') {

                            $print->alignLeft();
                        } else if ($fields['align'][$i] == 'right') {

                            $print->alignRight();
                        } else if ($fields['align'][$i] == 'center') {

                            $print->alignCenter();
                        }
                    }


                    //check field and print
                    if (in_array($fields['key'][$i], $custom_fields)) {

                        $print->fontSize($fields['font'][$i]);
                        $print->setText("{{" . $fields['key'][$i] . "}}");
                    } else if ($fields['key'][$i] == 'outlet_name') {

                        $print->fontSize($fields['font'][$i]);
                        //chaeck if sentence print on same line or not
                        if (isset($fields['sequence'][$i - 1]) && ($fields['sequence'][$i] == $fields['sequence'][$i - 1])) {
                            $print->setText("\t{{" . $fields['key'][$i] . "}}");
                        } else {
                            $print->setText("{{" . $fields['key'][$i] . "}}");
                        }
                    } else if ($fields['key'][$i] == 'address') {

                        $print->fontSize($fields['font'][$i]);
                        //chaeck if sentence print on same line or not
                        if (isset($fields['sequence'][$i - 1]) && ($fields['sequence'][$i] == $fields['sequence'][$i - 1])) {
                            $print->setText("\t{{" . $fields['key'][$i] . "}}");
                        } else {
                            $print->setText("{{" . $fields['key'][$i] . "}}");
                        }
                    } else if ($fields['key'][$i] == 'bill_lable') {

                        $print->fontSize($fields['font'][$i]);
                        //chaeck if sentence print on same line or not
                        if (isset($fields['sequence'][$i - 1]) && ($fields['sequence'][$i] == $fields['sequence'][$i - 1])) {
                            $print->setText("\t" . $fields['bill_lable']);
                        } else {
                            $print->setText($fields['bill_lable']);
                        }
                    } else if ($fields['key'][$i] == 'order_type') {

                        $print->fontSize($fields['font'][$i]);
                        if (isset($fields['sequence'][$i - 1]) && ($fields['sequence'][$i] == $fields['sequence'][$i - 1])) {
                            $print->setText("\t{{" . $fields['key'][$i] . "}}");
                        } else {
                            $print->setText("{{" . $fields['key'][$i] . "}}");
                        }
                    } else if ($fields['key'][$i] == 'user_name') {

                        $print->fontSize($fields['font'][$i]);
                        if (isset($fields['sequence'][$i - 1]) && ($fields['sequence'][$i] == $fields['sequence'][$i - 1])) {
                            $print->setText("\t" . $fields['user_lable'] . " {{user_name}}");
                        } else {
                            $print->setText($fields['user_lable'] . " {{" . $fields['key'][$i] . "}}");
                        }
                    } else if ($fields['key'][$i] == 'date') {

                        $print->fontSize($fields['font'][$i]);
                        if (isset($fields['sequence'][$i - 1]) && ($fields['sequence'][$i] == $fields['sequence'][$i - 1])) {
                            $print->setText("\t" . $fields['date_lable'] . " {{" . $fields['key'][$i] . "}}");
                        } else {
                            $print->setText($fields['date_lable'] . " {{" . $fields['key'][$i] . "}}");
                        }
                    } else if ($fields['key'][$i] == 'invoice_no') {

                        $print->fontSize($fields['font'][$i]);
                        if (isset($fields['sequence'][$i - 1]) && ($fields['sequence'][$i] == $fields['sequence'][$i - 1])) {
                            $print->setText("\t" . $fields['inv_lable'] . " {{" . $fields['key'][$i] . "}}");
                        } else {
                            $print->setText($fields['inv_lable'] . " {{" . $fields['key'][$i] . "}}");
                        }
                    } else if ($fields['key'][$i] == 'pax') {

                        $print->fontSize($fields['font'][$i]);
                        if (isset($fields['sequence'][$i - 1]) && ($fields['sequence'][$i] == $fields['sequence'][$i - 1])) {
                            $print->setText("\t" . $fields['pax_lable'] . " {{" . $fields['key'][$i] . "}}");
                        } else {
                            $print->setText($fields['pax_lable'] . " {{" . $fields['key'][$i] . "}}");
                        }
                    } else if ($fields['key'][$i] == 'table_no') {

                        $print->fontSize($fields['font'][$i]);
                        if (isset($fields['sequence'][$i - 1]) && ($fields['sequence'][$i] == $fields['sequence'][$i - 1])) {
                            $print->setText("\t{{" . $fields['key'][$i] . "}}");
                        } else {
                            $print->setText("{{" . $fields['key'][$i] . "}}");
                        }
                    } else if ($fields['key'][$i] == 'order_detail') {

                        $print->fontSize($fields['font'][$i]);
                        if (isset($fields['sequence'][$i - 1]) && ($fields['sequence'][$i] == $fields['sequence'][$i - 1])) {
                            $print->setText("\t{{" . $fields['key'][$i] . "}}");
                        } else {
                            $print->setText("{{" . $fields['key'][$i] . "}}");
                        }
                    } else if ($fields['key'][$i] == 'tax_detail') {

                        $print->fontSize($fields['font'][$i]);
                        $print->setText("{{" . $fields['key'][$i] . "}}");
                    } else if ($fields['key'][$i] == 'footer_note') {

                        $print->fontSize($fields['font'][$i]);
                        //$print->justify();
                        $print->setText(Utils::printFormate($fields['footer_note']));
                    } else if ($fields['key'][$i] == 'qr_code') {

                        $print->fontSize($fields['font'][$i]);
                        $print->setText("{{" . $fields['key'][$i] . "}}");
                    } else if ($fields['key'][$i] == 'lable') {

                        $print->fontSize($fields['font'][$i]);
                        //$print->justify();
                        $print->setText($fields['lable'][$lable_cnt]);
                        $lable_cnt++;
                    } else if ($fields['key'][$i] == 'customer') {

                        $print->fontSize($fields['font'][$i]);
                        if (isset($fields['sequence'][$i - 1]) && ($fields['sequence'][$i] == $fields['sequence'][$i - 1])) {
                            $print->setText("\t" . $fields['customer'] . " {{" . $fields['key'][$i] . "}}");
                        } else {
                            $print->setText($fields['customer'] . " {{" . $fields['key'][$i] . "}}");
                        }
                    } else if ($fields['key'][$i] == 'customer_mobile') {

                        $print->fontSize($fields['font'][$i]);
                        if (isset($fields['sequence'][$i - 1]) && ($fields['sequence'][$i] == $fields['sequence'][$i - 1])) {
                            $print->setText("\t" . "Mobile: " . " {{" . $fields['key'][$i] . "}}");
                        } else {
                            $print->setText("Mobile: " . "{{" . $fields['key'][$i] . "}}");
                        }
                    } else if ($fields['key'][$i] == 'customer_address') {

                        $print->fontSize($fields['font'][$i]);
                        if (isset($fields['sequence'][$i - 1]) && ($fields['sequence'][$i] == $fields['sequence'][$i - 1])) {
                            $print->setText("\t" . " {{" . $fields['key'][$i] . "}}");
                        } else {
                            $print->setText("{{" . $fields['key'][$i] . "}}");
                        }
                    } else if ($fields['key'][$i] == 'dash_line') {

                        $print->addLineSeperator();
                    } else if ($fields['key'][$i] == 'star_line') {

                        $print->addStarSeperator();
                    } else if ($fields['key'][$i] == 'new_line') {
                    }
                }
            }
            $template = $print->getTemplate();
        }

        $outlet = Outlet::find($outlet_id);

        if (isset($template) && $template != '') {

            $outlet->bill_template = $template;
            $outlet->bill_template_json = json_encode($fields);
            $outlet->save();

            return 'success';
        } else {
            return 'empty';
        }
    }


    function addCustomField(Request $request)
    {

        $outlet_id = Session::get('outlet_session');

        if ($request->ajax()) {

            $fields = Input::get("fields");
            $result = false;

            if (isset($outlet_id) && sizeof($outlet_id) > 0) {

                $outlet = Outlet::find($outlet_id);
                if (isset($outlet) && sizeof($outlet) > 0) {
                    $outlet->custom_bill_print_fields = isset($fields) ? $fields : NULL;
                    $result = $outlet->save();
                }
            }


            if ($result) {

                return Response::json(
                    array(
                        'status' => 'success',
                        'msg' => 'Custom fields has been updated successfully',
                        'statuscode' => 200,
                    ),
                    200
                );
            } else {
                return Response::json(
                    array(
                        'status' => 'error',
                        'msg' => 'There is some error occurred, Please try again later',
                        'statuscode' => 201,
                    ),
                    201
                );
            }
        }
        if (isset($outlet_id) && sizeof($outlet_id) > 0) {

            $outlet = Outlet::find($outlet_id);
            if (isset($outlet) && sizeof($outlet) > 0) {
                if (isset($outlet->custom_bill_print_fields) && sizeof($outlet->custom_bill_print_fields) > 0)
                    return view('Outlets.addCustomField', array('custom_fields' => json_decode($outlet->custom_bill_print_fields)));
                else
                    return view('Outlets.addCustomField');
            }
        }

        return view('Outlets.addCustomField');
    }

    #TODO : admin outlet setting
    public function adminOutlet(Request $request)
    {

        if ($request->ajax()) {

            $outlet_id = Input::get('outlet_id');
            $Outlet = Outlet::find($outlet_id);

            if (isset($Outlet->tinno) && $Outlet->tinno != '') {
                $tinno = $Outlet->tinno;
            } else {
                $tinno = '';
            }

            if (isset($Outlet->servicetax_no) && $Outlet->servicetax_no != '') {
                $servicetax_no = $Outlet->servicetax_no;
            } else {
                $servicetax_no = '';
            }

            if (isset($Outlet->active) && $Outlet->active != '') {
                $active = $Outlet->active;
            } else {
                $active = '';
            }
            //$active = $Outlet->active;
            if (isset($Outlet->service_type)) {
                $service_type = unserialize($Outlet->service_type);
            } else {
                $service_type = array();
            }

            $Outletmenu = [];
            $Outletinformation = [];
            $Outlettype = OutletTypeMapper::getoutlettypemapper($outlet_id);
            if (sizeof($Outlettype) > 0) {
                $Outlet_type = $Outlet->outlettypemapper;
            } else {
                $Outlet_type = array();
            }
            $cuisinetype = OutletCuisineType::getoutletcuisinetype($outlet_id);
            if (sizeof($cuisinetype) > 0) {
                $cuisinetype = $Outlet->outletcuisinetype;
            } else {
                $cuisinetype = array();
            }

            if (isset($Outlet->outlet_image)) {
                $image = 'uploads/profileimage/' . $Outlet->outlet_image;
            } else {
                $image = '';
            }
            $menuitems = Menu::getmenubyoutletid($outlet_id);
            //        print_r($menuitems);exit;
            $Outletsectioname = MenuTitle::getmenutitlebyrestaurantid($outlet_id);
            $timeslot = Timeslot::gettimeslotbyoutletid($outlet_id);

            if (isset($Outlet->country_id)) {
                $countries = Country::where('id', '=', $Outlet->country_id)->get();
            } else {
                $countries = array();
            }

            if (isset($Outlet->state_id)) {
                $states = State::getstatebyid($Outlet->state_id);
            } else {
                $states = array();
            }
            if (isset($Outlet->city_id)) {
                $cities = City::getcitybyid($Outlet->city_id);
            } else {
                $cities = array();
            }


            if (isset($Outlet->locality) && $Outlet->locality != "" && $Outlet->locality != 0) {
                $locality = locality::getlocalitybyid($Outlet->locality);
                //            print_r($locality);exit;
            } else {
                $locality = array();
            }


            if (isset($Outlet->established_date) && $Outlet->established_date != '0000-00-00') {
                $established_date = $Outlet->established_date;
            } else {
                $established_date = '';
            }
            $rest_images = Outletimage::getoutletimagesbyoutletid($outlet_id);
            $rest_latlong = Outletlatlong::getouletlatlongbyoutletid($outlet_id);

            $owner_id = OutletMapper::getOutletUsers($outlet_id);

            $printers_list = Printer::whereIn('created_by', $owner_id['user_id'])->lists('printer_name', 'id');

            $printers = array();
            $printers[''] = 'Select Printers';
            foreach ($printers_list as $key => $value) {
                $printers[$key] = $value;
            }

            /*$settings = OutletSetting::join('setting_master','setting_master.id','=','outlet_settings.setting_id')
                ->where('outlet_id',$id)
                ->select('outlet_settings.setting_id','setting_master.setting_name','outlet_settings.setting_value')
                ->get();*/
            $master = SettingsMaster::lists('id');
            for ($i = 0; $i < sizeof($master); $i++) {
                $outlet_setting = OutletSetting::select('setting_value')
                    ->where('outlet_id', $outlet_id)->where('setting_id', $master[$i])->first();
                if (isset($outlet_setting) && sizeof($outlet_setting) > 0 && $outlet_setting != '') {
                    $settings[$i]['id'] = $master[$i];
                    switch (SettingsMaster::find($master[$i])->setting_name) {
                        case "feedbackPrint":
                            $settings[$i]['setting_name'] = "Print Feedback";
                            break;
                        case 'kotPrint':
                            $settings[$i]['setting_name'] = "Print KOT";
                            break;
                        case 'processbillPrint':
                            $settings[$i]['setting_name'] = "Print bill on table closure";
                            break;
                        case 'billPrint':
                            $settings[$i]['setting_name'] = "Print bill from past orders";
                            break;
                        case 'multipleKotPrint':
                            $settings[$i]['setting_name'] = "Print Multiple KOT";
                            break;
                        case 'mobileMandatory':
                            $settings[$i]['setting_name'] = "Mandatory Mobile Number";
                            break;
                        case 'duplicateKotPrint':
                            $settings[$i]['setting_name'] = "Allow Duplicate KOT Print";
                            break;
                        case 'orderNoReset':
                            $settings[$i]['setting_name'] = "Order No Reset.";
                            break;
                        case 'incrementOrderNo':
                            $settings[$i]['setting_name'] = "Increment Order No.";
                            break;
                        case 'bypassProcessBill':
                            $settings[$i]['setting_name'] = "Bypass Process Bill.";
                            break;
                        case 'invoiceDate':
                            $settings[$i]['setting_name'] = "Add Invoice Date.";
                            break;
                        case 'displayNoOfPerson':
                            $settings[$i]['setting_name'] = "Show No Of Person.";
                            break;
                        case 'skipKotPrint':
                            $settings[$i]['setting_name'] = "Skip Kot Print.";
                            break;
                        case 'skipBillPrint':
                            $settings[$i]['setting_name'] = "Skip Bill Print.";
                            break;
                        case 'discountAfterTax':
                            $settings[$i]['setting_name'] = "Discount after Tax.";
                            break;
                        case 'beepOnKot':
                            $settings[$i]['setting_name'] = "Beep Sound On KOT Print.";
                            break;
                        default:
                            $settings[$i]['setting_name'] = SettingsMaster::find($master[$i])->setting_name;
                    }
                    $settings[$i]['setting_org_name'] = SettingsMaster::find($master[$i])->setting_name;
                    $settings[$i]['setting_value'] = $outlet_setting->setting_value;
                } else {
                    $settings[$i]['id'] = $master[$i];
                    switch (SettingsMaster::find($master[$i])->setting_name) {
                        case "feedbackPrint":
                            $settings[$i]['setting_name'] = "Print Feedback";
                            break;
                        case 'kotPrint':
                            $settings[$i]['setting_name'] = "Print KOT";
                            break;
                        case 'processbillPrint':
                            $settings[$i]['setting_name'] = "Print bill from past orderss";
                            break;
                        case 'billPrint':
                            $settings[$i]['setting_name'] = "Print bill on table closure";
                            break;
                        case 'multipleKotPrint':
                            $settings[$i]['setting_name'] = "Print Multiple KOT";
                            break;
                        case 'mobileMandatory':
                            $settings[$i]['setting_name'] = "Mandatory Mobile Number";
                            break;
                        case 'duplicateKotPrint':
                            $settings[$i]['setting_name'] = "Allow Duplicate KOT Print";
                            break;
                        case 'orderNoReset':
                            $settings[$i]['setting_name'] = "Order No Reset.";
                            break;
                        case 'incrementOrderNo':
                            $settings[$i]['setting_name'] = "Increment Order No.";
                            break;
                        case 'bypassProcessBill':
                            $settings[$i]['setting_name'] = "Bypass Process Bill.";
                            break;
                        case 'invoiceDate':
                            $settings[$i]['setting_name'] = "Invoice Date.";
                            break;
                        case 'displayNoOfPerson':
                            $settings[$i]['setting_name'] = "Show No Of Person.";
                            break;
                        default:
                            $settings[$i]['setting_name'] = SettingsMaster::find($master[$i])->setting_name;
                    }
                    //$settings[$i]['setting_name'] = SettingsMaster::find($master[$i])->setting_name;
                    $settings[$i]['setting_org_name'] = SettingsMaster::find($master[$i])->setting_name;
                    $settings[$i]['setting_value'] = SettingsMaster::where('id', $master[$i])->select('setting_default as setting_value')->first()->setting_value;
                }
            }

            $sources = Sources::all()->lists('name', 'id');
            $payment_options = PaymentOption::all()->lists('name', 'id');
            $payment_options_without_source = PaymentOption::where("without_source", 1)->lists('id');

            //Delivery charge slabs
            $delivery_charges = '';
            if (isset($Outlet->delivery_charge) && $Outlet->delivery_charge != '') {
                $delivery_charges = json_decode($Outlet->delivery_charge);
            }

            //Tax details slabs
            $tax_details = '';

            if (isset($Outlet->tax_details) && $Outlet->tax_details != '') {
                $tax_details = json_decode($Outlet->tax_details);
            }

            $zoho_username = isset($Outlet->zoho_username) ? $Outlet->zoho_username : "";
            $zoho_password = isset($Outlet->zoho_password) ? $Outlet->zoho_password : "";
            $zoho_org_id = isset($Outlet->zoho_organization_id) ? $Outlet->zoho_organization_id : "";
            $zoho_ids = isset($Outlet->payment_option_identifier) ? json_decode($Outlet->payment_option_identifier, "false") : "";

            $dup_kot_count = isset($Outlet->duplicate_kot_count) ? $Outlet->duplicate_kot_count : 0;

            return view('Outlets.showfields', array(
                'sources' => $sources,
                'payment_options' => $payment_options,
                'payment_options_without_source' => $payment_options_without_source,
                'outlet' => $Outlet,
                'tinno' => $tinno,
                'settings' => $settings,
                'servicetax_no' => $servicetax_no,
                'service_type' => $service_type,
                'Outlettype' => $Outlet_type,
                'cuisinetypes' => $cuisinetype,
                'menuitems' => $menuitems,
                'Outletimages' => $rest_images,
                'image' => $image,
                'Outlet_latlong' => $rest_latlong,
                'Outletsectioname' => $Outletsectioname,
                'timeslot' => $timeslot,
                'states' => $states,
                'cities' => $cities,
                'locality' => $locality,
                'established_date' => $established_date,
                'active' => $active,
                'countries' => $countries,
                'printers' => $printers,
                'dp_kot_count' => $dup_kot_count,
                'delivery_charge' => $delivery_charges,
                'tax_details' => $tax_details,
                'zoho_username' => $zoho_username,
                'zoho_password' => $zoho_password,
                'zoho_ids' => $zoho_ids,
                'zoho_org_id' => $zoho_org_id
            ));
        }

        $outlets[] = "Select Outlet";
        $outlets_arr = Outlet::lists('name', 'id');

        $result_outlet = array_add($outlets_arr, "", "Select Outlet");

        return view('Outlets.adminoutlet', array('outlets' => $result_outlet));
    }


    public function storeOutletStatus()
    {

        $outlet_id = Input::get("outlet_id");
        $outlet_status = Input::get("outlet_status");
        $result = false;
        if (isset($outlet_id) && sizeof($outlet_id) > 0) {

            $outlet = Outlet::find($outlet_id);
            if (isset($outlet) && sizeof($outlet) > 0) {

                if ($outlet_status == "trial")
                    $outlet->outlet_status = "trial";
                else if ($outlet_status == "active")
                    $outlet->outlet_status = "active";
                else if ($outlet_status == "demo")
                    $outlet->outlet_status = "demo";
                $result = $outlet->save();
            }
        }

        if ($result) {

            return Response::json(
                array(
                    'status' => 'success',
                    'msg' => 'Outlet Status has been updated successfully',
                    'statuscode' => 200,
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => 'error',
                    'msg' => 'There is some error occurred, Please try again later',
                    'statuscode' => 201,
                ),
                201
            );
        }
    }
}
