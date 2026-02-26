<?php namespace App\Http\Controllers\Api\v3;

use App\Account;
use App\Campaign;
use App\CancellationReason;
use App\City;

use App\CouponCodes;
use App\CustomerFeedback;
use App\Events\OrderItemConfirmEvent;
use App\Events\OrderNotificationEvent;
use App\Expense;
use App\ExpenseCategory;
use App\FeedbackQuestion;
use App\Http\Controllers\newordercontroller;
use App\Http\Controllers\StocksController;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//use App\Libraries\Image;
use App\invoice_detail;
use App\ItemOptionGroup;
use App\ItemOptionGroupMapper;
use App\ItemRequest;
use App\Itemreview;
use App\ItemSettings;
use App\Kot;
use App\Location;
use App\LogDetails;
use App\LogLevel;
use App\menu_option;
use App\MenuItemOption;
use App\order_details;
use App\OrderCancellation;
use App\ordercouponmapper;
use App\OrderItemOption;
use App\OrderPaymentMode;
use App\OrderPlaceType;
use App\OutletItemAttributesMapper;
use App\OutletMapper;
use App\OutletSetting;
use App\OutletType;
use App\Owner;
use App\PaymentOption;
use App\payumoney;
use App\Printer;
use App\PrinterItemBind;
use App\Printsummary;
use App\ResponseDeviation;
use App\Reviews;
use App\SendCloseCounterStatus;
use App\Setting;
use App\Sources;
use App\State;
//use Illuminate\Http\Request;
use App\Stock;
use App\StockAge;
use App\StockHistory;
use App\StockLevel;
use App\TableLevel;
use App\Tables;
use App\Termsandcondition;
use App\Unit;
use App\User;
use App\Utils;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use App\Outlet;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\MenuTitle;
use App\Menu;
use App\status;
use App\CuisineType;
use App\OrderItem;
use App\Timeslot;
use App\Outletlatlong;
use App\OutletCuisineType;
use App\OutletOutletType;
use App\Outletimage;
use App\Humanreadableids;
use App\locality;
use Illuminate\Support\Facades\DB;

use App\Libraries\Image as Image;

use App\users;
use App\address;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Maatwebsite\Excel\Facades\Excel;
use App\Tax;
use Carbon\Carbon;
use App\Recipe;
use Symfony\Component\HttpFoundation\Request as RequestHeader;

use Illuminate\Contracts\Auth\Guard;
use Zend\Http\Header\Date;


class Apicontroller extends Controller
{

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $rests=array();
        $Outlet_Outlettype = array();
        $Outlet_cuisinetype = array();
        $Outlet_detail = array();
        $start=Request::get('start');
        $limit=Request::get('limit');

        $location=Request::get('locality');
        $cuistype=Request::get('cuisine_type');
        $resttype=Request::get('restaurant_type');
        $restname=Request::get('restaurant_name');
        $start=Request::get('start');
        $limit=Request::get('limit');
        $costmin=Request::get('min_price');
        $costmax=Request::get('max_price');
        $lat=Request::get('latitude');
        $long=Request::get('longitude');
        $delivery_type=Request::get('order_type');
        $useragent=Request::header('User-Agent');

        if(isset($start) || isset($limit) || isset($location) || isset($cuistype) || isset($resttype) || isset($restname) || isset($costmin) || isset($costmax) || isset($lat) || isset($long) || isset($delivery_type)){


            $restdetails=Outlet::searchoutlet($start,$limit,$location,$cuistype,$resttype,$restname,$costmin,$costmax,$lat,$long,$delivery_type);


            $i = 0;


            foreach ($restdetails['restaurantdetails'] as $restcuisine) {

                $restid = $restcuisine->id;

                $restinfo = Outlet::find($restid);

                $restcui = $restinfo->outletcuisinetype->all();
                $restresttype =$restinfo->outlettypemapper->all();
                $j=0;
                $cuisinetype = array();
                foreach ($restcui as $recui) {

                    $rest=CuisineType::cuisinetypebyid($recui->cuisine_type_id);

                    if (sizeof($rest) > 0) {
                        $retype = $rest['type'];
                    } else {
                        $retype = "";
                    }
                    $cuisinetype[$j]=$retype;
                    $j++;

                }
                $k=0;

                $Outlet_type = array();
                foreach ($restresttype as $resrest) {
                    $cui=OutletType::Outlettypebyid($resrest->outlet_type_id);

                    if (sizeof($cui) > 0) {
                        $cutype = $cui['type'];
                    } else {
                        $cutype = "";
                    }
                    $Outlet_type[$k] = $cutype;

                    $k++;
                }

                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                    $_SERVER['HTTPS'] = "https";
                } else {
                    $_SERVER['HTTPS'] = "http";
                }

                if ($restinfo->outlet_image != "") {
                    $Outlet_image = $_SERVER['HTTPS'] . "://" . $_SERVER['HTTP_HOST'] . '/avtar/' . $restid . '/small';
                } else {
                    $Outlet_image = '';
                }
                if(isset($restcuisine->locality) && $restcuisine->locality!='' && $restcuisine->locality!=0){
                    // print_r($restcuisine->locality);
                    $loca=locality::getlocalitybyid($restcuisine->locality);

                    $locality=$loca->locality;
                }else{
                    $locality='';
                }
                if(isset($restcuisine->takeaway_cost) && $restcuisine->takeaway_cost!="" && $restcuisine->takeaway_cost!=0){
                    $minimumordercost=$restcuisine->takeaway_cost;
                }else{
                    $minimumordercost='';
                }
                // print_r();exit;
                if(isset($restdetails['distancearray'][$restid])){
                    $Outlet_detail[$i] = array(
                        'restaurant_id' => $restcuisine->id,
                        'name' => ucfirst($restcuisine->name),
                        'cuisine_type' => $cuisinetype,
                        'restaurant_type' => $Outlet_type,
                        'address' => $restcuisine->address,
                        'phone_number' => $restcuisine->contact_no,
                        'webaddress' => $restcuisine->url,
                        'avgcostoftwo' =>  $restcuisine->avg_cost_of_two,
                        'min_order_price'=>$minimumordercost,
                        'locality' => $locality,
                        'famous_for' => $restcuisine->famous_for,
                        'restaurant_image' => $Outlet_image,
                        'order_lable'=>$restcuisine->order_lable,
                        'distance'=>round($restdetails['distancearray'][$restid],1)." km"

                    );
                }else{
                    $Outlet_detail[$i] = array(
                        'restaurant_id' => $restcuisine->id,
                        'name' => ucfirst($restcuisine->name),
                        'cuisine_type' => $cuisinetype,
                        'restaurant_type' => $Outlet_type,
                        'address' => $restcuisine->address,
                        'phone_number' => $restcuisine->contact_no,
                        'webaddress' => $restcuisine->url,
                        'avgcostoftwo' => $restcuisine->avg_cost_of_two,
                        'min_order_price'=>$minimumordercost,
                        'locality' => $locality,
                        'famous_for' => $restcuisine->famous_for,
                        'order_lable'=>$restcuisine->order_lable,
                        'restaurant_image' => $Outlet_image,
                        'distance'=>''

                    );
                }
                $i++;
            }
            $sortedarray=array();
            foreach($Outlet_detail as $key=>$value){
                $sortedarray[$key]=$value['distance'];
            }
            array_multisort($sortedarray, SORT_ASC, $Outlet_detail);

            return Response::json(array(
                'status' => 'success',
                'statuscode' => 200,
                'count'=>count($restdetails),
                'restaurants' => $Outlet_detail),
                200);
            // }


        }
        else{

            $Outlets=Outlet::where('active','!=',serialize(0))->get();
            $i = 0;
            foreach ($Outlets as $restcuisine) {
                $restid = $restcuisine->id;
                $restinfo = Outlet::find($restid);

                $restcui = $restinfo->outletcuisinetype;
                $restresttype = $restinfo->outlettypemapper;

                $cuisinetype = array();
                foreach ($restcui as $recui) {

                    $rest = CuisineType::cuisinetypebyid($recui->cuisine_type_id);
                    if (sizeof($rest) > 0) {
                        $resttype = $rest['type'];
                    } else {
                        $resttype = "";
                    }
                    if(!in_array($resttype,$cuisinetype)){
                        array_push($cuisinetype, $resttype);
                    }
                }

                $Outlet_type=array();
                foreach ($restresttype as $resrest) {

                    $cui = OutletType::outlettypebyid($resrest->outlet_type_id);
                    if (sizeof($cui) > 0) {
                        $cutype = $cui['type'];
                    } else {
                        $cutype = "";
                    }
                    if(!in_array($cutype,$Outlet_type)) {
                        array_push($Outlet_type, $cutype);
                    }
                }
                //print_r($cuisinetype);
                $Outlet_cuisinetype['cuisine_type'] = $cuisinetype;
                $Outlet_Outlettype['Outlet_type'] = $Outlet_type;
                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                    $_SERVER['HTTPS'] = "https";
                } else {
                    $_SERVER['HTTPS'] = "http";
                }

                if ($restinfo->outlet_image != "") {
                    $Outlet_image = $_SERVER['HTTPS'] . "://" . $_SERVER['HTTP_HOST'] . '/avtar/' . $restid . '/small';
                } else {
                    $Outlet_image = '';
                }

                if(isset($restcuisine->locality) && $restcuisine->locality!='' && $restcuisine->locality!=0){
                    $loca=locality::getlocalitybyid($restcuisine->locality);

                    $locality=$loca->locality;
                }else{
                    $locality='';
                }
                if(isset($restcuisine->takeaway_cost) && $restcuisine->takeaway_cost!="" && $restcuisine->takeaway_cost!=0){
                    $min_cost=$restcuisine->takeaway_cost;
                }else{
                    $min_cost='';
                }
                $Outlet_detail[$i] = array(
                    'restaurant_id' => $restcuisine->id,
                    'name' => ucfirst($restcuisine->name),
                    'cuisine_type' => $cuisinetype,
                    'restaurant_type' => $Outlet_type,
                    'address' => $restcuisine->address,
                    'phone_number' => $restcuisine->contact_no,
                    'webaddress' => $restcuisine->url,
                    'avgcostoftwo' => $restcuisine->avg_cost_of_two,
                    'min_order_price'=>$min_cost,
                    'locality' => $locality,
                    'famous_for' => $restcuisine->famous_for,
                    'order_lable'=>$restcuisine->order_lable,
                    'restaurant_image' => $Outlet_image,

                );
                $i++;

            }


            return Response::json(array(
                'message' => 'List of all added Outlets',
                'status' => 'success',
                'statuscode' => 200,
                'restaurants' => $Outlet_detail),
                200);

        }
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    //for creating image url
    public function getImage($id, $size)
    {
        $Image = new Image();
        $response = array();
        $Outletimage = Outlet::find($id);

        if (sizeof($Outletimage) > 0) {
            if ($Outletimage->outlet_image != '') {


                // Make a new response out of the contents of the file
                // We refactor this to use the image resize function.
                // Set the response status code to 200 OK
                $response = Response::make($Image->resize($Outletimage->outlet_image, $size), 200);

                // Set the mime type for the response.
                // We now use the Image class for this also.
                $response->header(
                    'content-type',
                    $Image->getMimeType($Outletimage->outlet_image)
                );

                // We return our image here.

            }
        }

        return $response;
    }

    //for Outletmenu
    public function outletmenu()
    {
        $resid = Request::get('restaurant_id');

        $retname = Outlet::find($resid);
        $menu=Apicontroller::getnewMenu($resid,$retname->printer,'consumer');

        //Delivery charge
        $delivery_charge = '';
        if ( isset($retname->delivery_charge) && sizeof($retname->delivery_charge) > 0 ) {
            $delivery_charge = json_decode($retname->delivery_charge);
        }


        return Response::json(array(
            'message' => 'List of menu',
            'status' => 'success',
            'statuscode' => 200,
            'menu' => $menu,
            'outlet_name'=>$retname->name,
            'order_lable'=>$retname->order_lable,
            'outlet_delivery_numbers'=>$retname->delivery_numbers,
            'service_tax' => isset($retname->taxes)?$retname->taxes:null,
            'default_tax'=>$retname->default_taxes,
            'enable_services'=>unserialize($retname->enable_service_type),
            'delivery_charge'=>$delivery_charge),
            200);

        $outlet = Outlet::find($resid);
        $menu=MenuTitle::select('menus.*','menu_titles.title')
            ->join("outlet_menu_bind","outlet_menu_bind.menu_id","=","menu_titles.id")
            ->join("menus","menus.id","=","outlet_menu_bind.item_id")
            ->where('outlet_menu_bind.outlet_id',$resid)
            ->groupby("menus.id")
            ->get();

        $a=array();
        foreach($menu as $m) {

            $cuisine=CuisineType::find($m->cuisine_type_id);
            $inner_array=array('item_id'=>$m->id,
                'item'=>$m->item,
                'price'=>number_format($m->price),
                'details'=>$m->details,
                'cuisinetype'=>$cuisine,
                'options'=>$m->options,
                'foodtype'=>$m->food,
                'active'=>$m->active,
                'like'=>$m->like);
            if(!array_key_exists($m->title,$a)) {
                $a[$m->title][] = $inner_array;
            } else {
                array_push($a[$m->title],$inner_array);
            }
        }

        return Response::json(array(
            'message' => 'List of menu',
            'status' => 'success',
            'statuscode' => 200,
            'menu' => $a,
            'outlet_name'=>$outlet->name,
            'order_lable'=>$outlet->order_lable,
            'outlet_delivery_numbers'=>$outlet->delivery_numbers,
            'service_tax' => isset($outlet->taxes)?$outlet->taxes:null,
            'enable_services'=>unserialize($outlet->enable_service_type)),
            200);
    }

    //for creating url of Outlet gallery
    public function getGallery($id,$size)
    {
        $Image = new Image();
        $response = array();
        $Outletimage = Outletimage::find($id);
        // print_r($Outletimage);exit;

        if (sizeof($Outletimage) > 0) {

            if ($Outletimage->image_name != '') {


                // Make a new response out of the contents of the file
                // We refactor this to use the image resize function.
                // Set the response status code to 200 OK
                $response= Response::make($Image->resizegalleryimage($Outletimage->image_name, $size), 200);

                // Set the mime type for the response.
                // We now use the Image class for this also.
                $response->header(
                    'content-type',
                    $Image->getGalleryMimeType($Outletimage->image_name)
                );

            }
        }

        return $response;
    }

    //information of Outlets
    public function outletinformation()
    {
        $restinfo = array();
        $outlet_id = Request::get('restaurant_id');
        $Outlets =Outlet::find($outlet_id);
        //print_r($Outlets);exit;
        $states = State::findstates($Outlets->state_id);
        if(sizeof($states)>0){
            $statename=$states->name;
        }else{
            $statename="";
        }
        $cities = City::findcity($Outlets->city_id);
        if(sizeof($cities)>0){
            $cityname=$cities->name;
        }else{
            $cityname="";
        }

        $Outlet_cuisine_type=$Outlets->outletcuisinetype->all();

        $i=0;
        $cuitype=array();
        foreach($Outlet_cuisine_type as $cuisine_type){
            $cuisinename=CuisineType::where('id',$cuisine_type->cuisine_type_id)->get();
            //  print_r();exit;
            $cuitype[$i]=$cuisinename[0]->type;
            $i++;
        }


        $Outlet_Outlet_type=$Outlets->outlettypemapper->all();

        $j=0;
        $resttype=array();
        foreach($Outlet_Outlet_type as $rest_type){
            $restname=OutletType::Outlettypebyid($rest_type->outlet_type_id);

            $resttype[$j]=$restname->type;

            $j++;
        }

        $Outlet_latlong = array();
        $rest_latlong=Outletlatlong::getouletlatlongbyoutletid($outlet_id); //get rest_latlong table

        if(sizeof($rest_latlong)>0){
            $latitude=$rest_latlong[0]->latitude;
            $longitude=$rest_latlong[0]->longitude;
        }else{
            $latitude='';
            $longitude='';
        }
        $time=array();
        $timeslots=Timeslot::gettimeslotbyoutletid($outlet_id);
        $k=0;
        if(sizeof($timeslots)>0){
            foreach($timeslots as $timeslot) {
                if ($timeslot->from_time != "" || $timeslot->to_time) {
                    $time[$k] = array('from_time' => $timeslot->from_time,
                        'to_time' => $timeslot->to_time);

                }
                else {
                    $time='';
                }
                $k++;
            }
        }
        if(isset($Outlets->locality) && $Outlets->locality!="" && $Outlets->locality!=0){
            $loca=locality::getlocalitybyid($Outlets->locality);
            $locality=$loca->locality;
        }else{
            $locality='';
        }

        $restimages=Outletimage::getoutletimagesbyoutletid($outlet_id);

        $l=0;
        $allimages=array();
        if(sizeof($restimages)>0){
            foreach($restimages as $restimgname){


                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                    $_SERVER['HTTPS'] = "https";
                } else {
                    $_SERVER['HTTPS'] = "http";
                }
                if ($restimgname->image_name != "") {
                    $Outlet_image =$_SERVER['HTTPS']."://" . $_SERVER['HTTP_HOST'] . '/gallery/' . $restimgname->id . '/small';
                    $Outlet_main = $_SERVER['HTTPS']."://" . $_SERVER['HTTP_HOST'] . '/gallery/' . $restimgname->id . '/big';
                } else {
                    $Outlet_image = '';
                    $Outlet_main='';
                }
                $allimages[$l]=array("thumb"=>$Outlet_image,
                    "original"=>$Outlet_main);
                $l++;
            }
        }

        $restinfo= array(
            'restaurant_id'=>$Outlets->id,
            'address' => $Outlets->address,
            'restaurant_name' => $Outlets->name,
            'web_address' => $Outlets->url,
            'email_id' => $Outlets->email_id,
            'famous_for' => $Outlets->famous_for,
            'locality' => $locality,
            'contact_no' => $Outlets->contact_no,
            'pincode' => $Outlets->pincode,
            'established_date' => $Outlets->established_date,
            'state' => $statename,
            'city' => $cityname,
            'restaurant_type' =>$resttype,
            'cuisine_type' =>$cuitype,
            'timeslot' => $time,
            'latitude'=>$latitude,
            'longitude'=>$longitude,
            'restaurant_images'=>$allimages);


        return Response::json(array(
            'message' => 'Information of Outlet',
            'status' => 'success',
            'statuscode' => 200,
            'restaurant' => $restinfo),
            200);
    }

    //add consumer order
    public function orderdetails($order_from_web=null){
        $order = '';
        $flag = '';
        if( isset($order_from_web) ){
            $order = $order_from_web['order'];
            $flag = $order['flag'];
            //print_r($flag);exit;
        }else{
            $order = Request::json('order');
        }

        $array=array();
        $Outlet=Outlet::Outletbyid($order['restaurant_id']);
        //print_r($Outlet);
        if(isset($Outlet[0]->service_tax))
            $service_tax=($Outlet[0]->service_tax * $order['total_price'])/100;
        else
            $service_tax=0;
        //print_r($service_tax);exit;
        if(isset($Outlet)){

            $startingstatus=status::getallstatusofOutlet($order['restaurant_id']);
            $lastindex= count($startingstatus)-1;
            if(isset($startingstatus)){
                if ( isset($order['order_from'])) {

                    if ($order['order_from'] == "pro") {
                        $status = $startingstatus[$lastindex]->status;
                    } else {
                        $status = $startingstatus[0]->status;
                    }

                } else {

                    if ($order['order_type'] == "dine_in") {
                        $status = $startingstatus[$lastindex]->status;
                    } else {
                        $status = $startingstatus[0]->status;
                    }

                }
            }else{
                $status='';
            }

            $order_id = '';
            if ( isset($order['order_id']) && $order['order_id'] != '') {
                $order_id = $order['order_id'];
            }

            //change for sync issue from partner app
            //$suborder_id=order_details::getorderidofrestaurant($order['restaurant_id']);
            $suborder_id = 0;

            $a= $Outlet->pluck('code');
            if(isset($order['mobile_number'])) {
                DB::table('orders')->where('user_mobile_number',$order['mobile_number'])->update(array('device_id'=>$order['device_id']));
            }

           if ( $order['order_type'] == 'home_delivery') {
                $today_hd_orders = order_details::where('table_start_date','>=',Carbon::today()->startOfDay())
                    ->where('table_start_date','<=',Carbon::today()->endOfDay())
                    ->where('order_type','=','home_delivery')->get()->count();
                $order['table'] = 'H'.($today_hd_orders + 1);
                //$person_no = $order_detail->person_no;
           } else if ( $order['order_type'] == 'take_away') {
                $today_ta_orders = order_details::where('table_start_date','>=',Carbon::today()->startOfDay())
                    ->where('table_start_date','<=',Carbon::today()->endOfDay())
                    ->where('order_type','=','take_away')->get()->count();
                $order['table'] = 'T'.($today_ta_orders + 1);
                //$person_no = $order_detail->person_no;
           }

            try {

                // $invoice_no = order_details::generateinvoicenumber($order['restaurant_id'],$suborder_id);
                $saveorder=order_details::insertConsumerDinein($order_id,$order,$status,$suborder_id,'',$service_tax);

                if ( isset($order['order_id']) && $order['order_id'] != '') {
                    $order_id = $order['order_id'];
                } else {

                    $order_id = $saveorder['id'];
                    //send order email to owner
                    event(new OrderNotificationEvent($saveorder['orderdetails']));


                }
            } catch( \Exception $e) {
                Log::info($e->getMessage());
            }


            $inv_no = NULL;
            if(isset($order['invoice'])){
                $inv_no = $order['invoice'];
            }

            if ( isset($order['menu_item']) && sizeof($order['menu_item']) > 0 ) {
                foreach($order['menu_item'] as $asd) {
                    $orderid=OrderItem::insertmenuitemoforders($order_id,$asd,$order['restaurant_id'],$inv_no);
                }
            }

            // Queue::push('App\Commands\MailNotification@getorderdetails', array('orderdetails'=>$saveorder));

            $date = $saveorder['order_date'];
            $date = str_replace('/', '-', $date);

            $orddate=date("F j, Y g:i a",strtotime($date));

            //Queue::push('App\Commands\OwnerNotification@getownernotification', array('outlet_id'=>$order['restaurant_id'],'order_id'=>$order_id,'table_no'=>$order['table']));
            if( isset($flag) && $flag == 'webapp_order' ){
                return 'success';
            }else{

                return Response::json(array(
                    'message' => 'Order Placed Successfully.Go To My Order To Check Your Status',
                    'status' => 'success',
                    'statuscode' => 200,
                    'local_id' => $order['local_id'],
                    'order_id'=>$order_id,
                    'server_id' => $order_id,
                    'order_date'=>$orddate,
                    'status' => ucfirst($status)
                ),
                    200);
            }


        } else {
            return Response::json(array(
                'message' => 'Outlet Not found',
                'status' => 'Failure',
                'statuscode' => 401,
            ),
                401);
        }

    }

    //new mobileend user added from here
    public function addcustomer(Request $request){
        $contact=Request::json('contact_no');
        $pass=Request::json('password');


        if(isset($contact)){
            //for finding customer by phone_number
            $usercheck=users::findcustomerbyphonenumber($contact);

            $num=users::generateotp();
            if(count($usercheck)==0) {

                $id=users::getidofaddedcustomer($contact,$pass,$num);
                users::sendotpbymessage($num,$contact);

                return Response::json(array(
                    'message' => 'User is Added successfully',
                    'userid'=>(string) $id,
                    'otp'=>$num,
                    'status' => 'NotVerified',
                    'statuscode' => 200,
                ),
                    200);
            }
            else if($usercheck->pluck('status')[0]=="NotVerified"){

                users::updateotp($contact,$num);

                users::sendotpbymessage($num,$contact);

                return Response::json(array(
                    'message' => 'User Added successfully',
                    'userid'=>$usercheck->pluck('id')[0],
                    'otp'=>$num,
                    'status' => 'NotVerified',
                    'statuscode' => 200,
                ),
                    200);

            }
            else {
                return Response::json(array(
                    'message' => 'User is Already Registered',
                    'userid'=>$usercheck->pluck('id')[0],
                    'status' => $usercheck->pluck('status')[0],
                    'statuscode' => 431,
                ),
                    200 );
            }
        }
    }

    //for the verification of otp sent to user in backend database
    public function verifyotp(Request $request){

        $id=Request::json('user_id');
        $otp=Request::json('user_otp');
        $user=users::selectotp($id);

        if($user->otp==$otp){
            users::updateotpstatus($id);
            return Response::json(array(
                'message' => 'User Verified Successfully',
                'status' => "Verified",
                'statuscode' => 200,
            ),
                200);
        }
        else {
            return Response::json(array(
                'message' => 'Invalid OTP',
                'status' => "NotVerified",
                'statuscode' => 432,
            ),
                200 );
        }

    }

    //for login request from mobileend
    public function login(Request $request){

        $mob=Request::json('user_mobile');
        $pass=Request::json('user_password');
        $user=DB::table('users');

        $avail=$user->where('mobile_number',$mob)->get();
        $pass=$user->where('mobile_number',$mob)->where('password',$pass)->get();
        $optverified=$user->where('mobile_number',$mob)->where('status','Verified')->get();
        if(count($avail)==0) {
            return Response::json(array(
                'message' => 'User Does Not Exists',
                'statuscode' => 401,
                'status'=>'Mobile is not registered. Please signup.'
            ),
                200);
        }
        else if(count($pass)==0){
            return Response::json(array(
                'message' => 'Invalid Password',
                'statuscode' => 401,
                'status'=>'Authentication Failed'
            ),
                200);
        }
        else if(count($optverified)==0){
            return Response::json(array(
                'message' => 'OTP Not Verified Sign Up Again',
                'statuscode' => 405,
                'status'=>'Authentication Failed'
            ),
                200);
        }
        else {
            if(isset($pass[0]->first_name) && $pass[0]->first_name!=""){
                $firstname=$pass[0]->first_name;
            }else{
                $firstname='';
            }

            if(isset($pass[0]->last_name) && $pass[0]->last_name!=""){
                $lastname=$pass[0]->last_name;
            }else{
                $lastname='';
            }
            if(isset($pass[0]->email) && $pass[0]->email!=""){
                $email=$pass[0]->email;
            }else{
                $email='';
            }

            if(isset($pass[0]->mobile_no) && $pass[0]->mobile_no!=""){
                $contactnumber=$pass[0]->mobile_no;
            }else{
                $contactnumber='';
            }

            if(isset($pass[0]->gender) && $pass[0]->gender!=""){
                $gender=$pass[0]->gender;
            }else{
                $gender='';
            }

            return Response::json(array(
                'message' => 'Valid User',
                'statuscode' => 200,
                'status'=>'Success',
                'userid'=>(string) $pass[0]->id,
                'name'=>$firstname.' '.$lastname,
                'email'=>$email,
                'contact_number'=>$contactnumber,
                'gender'=>$gender
            ),
                200);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updatedetail(Request $request){

        $serverid=Request::json('serverid');
        //$mobile=Request::json('user_mobile');
        $firstname=Request::json('first_name');
        // $lastname=Request::json('last_name');
        $email=Request::json('email');

        //$password=Request::json('password');
        //$gender=Request::json('gender');
        // print_r(\Illuminate\Support\Facades\Request::get('image'));exit;
        // $image=  Request::file('image');
        // print_r($image);exit;
        $update=array();


        $user=DB::table('users');
        if(isset($firstname)){
            $update['first_name']=$firstname;

        }
        if(isset($lastname)){
            $update['last_name']=$lastname;

        }
        if(isset($email)){
            $update['email']=$email;

        }

        if(isset($password)){
            $update['password']=$password;

        }
        if(isset($gender)){
            $update['gender']=$gender;

        }
//            if(isset($image)){
//                $extension = $image->getClientOriginalExtension();
//
//                $filename  = $image->getFilename() . '.' . $extension;
//                if (!file_exists(public_path('profilepics'))) {
//                    mkdir(public_path('profilepics'), 0777, true);
//                }
//                $path = public_path('profilepics/' . $filename);
//                Image::make($image->getRealPath())->resize(200, 200)->save($path);
//                $update['image'] = $image->getFilename().'.'.$extension;
//                $testimage=$image->getFilename().'.'.$extension;
//            }
        $affectedRows=$user->where('id',$serverid)->update($update);

        return Response::json(array(
            'message' => 'User Data Updated Successfully',
            'statuscode' => 200,
        ),
            200);


    }
    //not needed now
//    public function resendotp(Request $request){
//        $mobile=Request::json('user_mobile');
//        if(isset($mobile)){
//            $user=DB::table('users')->where('mobile_number',$mobile);
//
//            $num= str_random(6);
//            $affectedRows=$user->update(array('otp'=>$num));
//            $id=$user->select('id')->get();
//            if($affectedRows>0){
//                return Response::json(array(
//                        'message' => 'OTP Resend Successfully',
//                        'userid'=>(string) $id[0]['id'],
//                        'otp'=>$num,
//                        'statuscode' => 200,
//                    ),
//                    200);
//            }
//            else {
//                return Response::json(array(
//                        'message' => 'Invalid User',
//                        'statuscode' => 435,
//                    ),
//                    200);
//            }
//        }
//    }
    public function forgotpassword(Request $request){
        $mobile=Request::json('user_mobile');
        if(isset($mobile)){
            $num=users::generateotp();
            $affectedRows=users::updateotp($mobile,$num);

            users::sendotpoffogotpassword($num,$mobile);

        }
        if($affectedRows>0){
            return Response::json(array(
                'message' => 'OTP For Forgot Password Sent Successfully',
                'userid'=>(string)$affectedRows->id,
                'otp'=>$num,
                'statuscode' => 200,
            ),
                200);
        }
        else {
            return Response::json(array(
                'message' => 'User Does Not Exist',
                'statuscode' => 401,
            ),
                200);
        }

    }

    public function updatepassword(Request $request){

        $mobile=Request::json('user_mobile');
        $getotp=Request::json('otp');
        $password=Request::json('updated_password');


        if(isset($mobile)){

            $affectedRows=users::updatepassword($mobile,$getotp,$password);
            if($affectedRows>0){
                return Response::json(array(
                    'message' => 'Password Updated Successfully',
                    'userid'=>(string)$affectedRows->id,
                    'statuscode' => 200,
                ),
                    200);
            }
            else {
                return Response::json(array(
                    'message' => 'Please Add Valid User Contact',
                    'statuscode' => 434,
                ),
                    200);
            }
        }
    }
    public function addresschange(Request $request){
        $mobile=Request::json('user_mobile');
        $address=Request::json('address');
        $locality=Request::json('locality');
        $pincode=Request::json('pincode');
        $addressuniq=Request::json('addressid');
        if(isset($mobile)){
            if(isset($address)){
                $adressarray['address']=$address;
            }
            if(isset($locality)){
                $adressarray['locality']=$locality;
            }
            if(isset($pincode)){
                $adressarray['pincode']=$pincode;
            }
            if($addressuniq==null || $addressuniq==''){
                $id=users::getidofuserinserted($mobile);
                if(count($id)>0){
                    $adressarray['customer_id']=(string) $id[0]->id;

                    $addressid=address::insertaddress($adressarray);
                    return Response::json(array(
                        'message' => 'Address Added Successfully',
                        'addressid'=>(string) $addressid,
                        'statuscode' => 200,
                    ),
                        200);
                }
                else {
                    return Response::json(array(
                        'message' => 'Please Add Valid User Contact',
                        'statuscode' => 434,
                    ),
                        200);
                }
            }
            else {

                address::updateaddress($addressuniq,$adressarray);

                return Response::json(array(
                    'message' => 'Address Updated Successfully',
                    'addressid'=>$addressuniq,
                    'statuscode' => 200,
                ),
                    200);
            }
        }
    }

    //switch outlet or outlet select
    public function owneroutlet(){

        $username=Request::json('owner_name');
        $pass=Request::json('owner_pass');
        $device_id=Request::json('device_id');
        $app_version = Request::json('app_version');

        $maxdt=Carbon::now();
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';

        if($field=='email')
        {
            $user=Owner::where('email',$username)->whereNull('deleted_at')->first();
        }
        else
        {
            $user=Owner::where('user_name',$username)->whereNull('deleted_at')->first();
        }

        //check useris active? account table
        $owner = Owner::where($field, $username)->select('account_id')->first();
        if (isset($owner) && sizeof($owner) > 0){
            $account_id = $owner->account_id;
            $account_status = Account::find($account_id);
            if (isset($account_status) && sizeof($account_status) > 0) {
                $is_active = $account_status->active;
                if ($is_active == 0) {
                    return Response::json(array(
                        'message' => 'Account is not Active, please contact Pikal.',
                        'statuscode' => 435,
                    ),
                        200);
                }
            }
        }

        if(count($user)>0)
        {
            $a=Hash::check($pass, $user->password);
        }
        else
        {
            $a=false;
        }
        if($a==true)
        {
            if($field=='email') {
                if ($this->auth->attempt(array('email' => $username, 'password' => $pass),1)) {
                    $session_id = '';// Session::getId();
                } else {
                    $session_id = '';
                }
            } else {
                if ($this->auth->attempt(array('user_name' => $username, 'password' => $pass),1)) {
                    $session_id = '';// Session::getId();
                } else {
                    $session_id = '';
                }
            }

            //update token

            if ( isset($user->api_token) && $user->api_token != '') {
                $token = $user->api_token;
            } else {
                $token = str_random(64);
                $user->api_token = $token;
                $user->save();
            }



            if(isset($device_id) && $device_id!=''){
                DB::table('owners')->where($field,'=',$username)->update(array('device_id'=>$device_id,'app_version'=>$app_version));
            }


            $where = ['owner_id' => $user->id];
            $mappers = OutletMapper::getOutletIdByOwnerId($where);
            $mapper_arr=[];
            foreach($mappers as $mapper)
            {
                $mapper_arr[] = $mapper['outlet_id'];
            }
            $Outlet=Outlet::whereIn('id',$mapper_arr)->where('active','Yes')->get();

            $i=0;
            foreach($Outlet as $outlet){
                $outlet[$i];
                $i++;
            }

            $AllOutlets=Owner::outletByOwner($user->id);

            if($i==1){

                $maxdt=[];
                array_push($maxdt,Carbon::now());
                // $restaurant=$user->outlet->pluck('id');
                $restaurant_id=$AllOutlets[0]->id;
                $retname = Outlet::find($restaurant_id);
                $islogin=Owner::where($field,'=',$username)->first();

                $outlet_settings = OutletSetting::getApiOutletSettings($restaurant_id);
                $feedback=DB::table('feedback')->where('outlet_id','=',$restaurant_id)->get();
                // $menu=Apicontroller::getoutletmemu($restaurant_id);
                $menu=Apicontroller::getnewMenu($restaurant_id,$retname->printer);

                //send default printers
                $printers = Printer::getOutletPrinters($retname->printer,$restaurant_id);


                //get category order
                $menu_tutle_arr = OutletMapper::getCategoryOrder($user->id);

                //Cancellation Reason
                $reasons = CancellationReason::where('outlet_id',$restaurant_id)
                    ->select('id','reason_of_cancellation as reason')->get();

                $source_list = Sources::getSourceArray($restaurant_id);

                #TODO : for stock request

                //Units
                $units = Unit::all();

                //all locations
                $all_location = array();
                if( isset($user->created_by) && $user->created_by != '') {
                    $all_location = Location::where('created_by',$user->created_by)->get();
                } else {
                    $all_location = Location::where('created_by',$user->id)->get();
                }

                //locations
                $stock_location = Location::where('outlet_id',$restaurant_id)->get();
                $all_location = $stock_location;

                //stock level object
                $loc_arr = array();$stock_level = array();
                if( isset($stock_location) && sizeof($stock_location) > 0 ) {
                    foreach( $stock_location as $loc ) {
                        $loc_arr[] = $loc->id;
                    }

                    //stock_level array
                    $stock_level = StockLevel::whereIn('location_id',$loc_arr)->get();
                }

                $user_arr = array();

//                if ( isset($user->created_by) && $user->created_by != NULL ) {
//                    $user_arr = Owner::where('created_by', $user->created_by)->orWhere('id',$user->created_by)->get();
//                } else {
//                    $user_arr = Owner::where('id',$user->id)->orWhere('created_by',$user->id)->get();
//                }
                $user_arr = OutletMapper::leftJoin('owners','outlets_mapper.owner_id','=','owners.id')
                    ->where('outlets_mapper.outlet_id','=',$restaurant_id)->get();

                //outlet authorised user
                $auth_user_arr = array();$k=0;
                if ( isset($retname->authorised_users) && $retname->authorised_users != '') {
                    $a_users = explode(',',$retname->authorised_users);
                    if ( isset($a_users) && sizeof($a_users) > 0 ) {
                        foreach( $a_users as $key=>$auth ) {
                            $auth_user = Owner::find($auth);
                            $auth_user_arr[$k]['id']= $auth_user->id;
                            $auth_user_arr[$k]['user_name']= $auth_user->user_name;
                            $k++;
                        }
                    }
                }
                //Tables list
                $tables = Tables::getOutletTables($restaurant_id);

                //Item Attributes
                $ot_attributes = OutletItemAttributesMapper::getOutletAttributes($restaurant_id);

                //payment options
                $payment_options = PaymentOption::getOutletPaymentOption($restaurant_id);

                //get expense category
                $exp_category = ExpenseCategory::getExpenseCategory($user->id);

                //get order place types
                $order_place_types = OrderPlaceType::getOrderPlaceType($restaurant_id);

                //Delivery charge
                $delivery_charge = '';
                if ( isset($retname->delivery_charge) && sizeof($retname->delivery_charge) > 0 ) {
                    $delivery_charge = json_decode($retname->delivery_charge);
                }

                //tax details
                $tax_details = '';
                if ( isset($retname->tax_details) && sizeof($retname->tax_details) > 0 ) {
                    $tax_details = json_decode($retname->tax_details);
                }

                //owner order receive
                $order_receive = User::getOwnerOrderReceive($user->id, $restaurant_id);

                //last order sequence
                $last_order_json = order_details::lastOrderSequence($restaurant_id);

                //feedback questions
                $fb_question = FeedbackQuestion::getFbQuestions($restaurant_id);

                //table levels
                $table_levels = TableLevel::getTableLevels($restaurant_id);

                //bill template keys
                $keys = Utils::getBillTemplateKeys($restaurant_id);
                unset($keys['']);

                $account_data = Account::find($owner->account_id);
                $enable_inventory = isset($account_data->enable_inventory)?$account_data->enable_inventory:0;
                $enable_feedback = isset($account_data->enable_feedback)?$account_data->enable_feedback:0;

                //outlet item option groups
                $item_option_group = ItemOptionGroup::getOutletOptiongroups($restaurant_id);

                return Response::json(array(
                    'message' => 'Valid User',
                    'statuscode' => 200,
                    'status'=>'Success',
                    'token'=>$token,
                    'userid'=>(string) $user->id,
                    'user_identifier'=>Owner::find($user->id)->user_identifier,
                    'resid'=>(string) $restaurant_id,
                    'ownername'=>$islogin->user_name,
                    'outletname'=>$retname->name,
                    'invoice_title'=>$retname->invoice_title,
                    'outlet_address'=>$retname->address,
                    'contact_no'=>$retname->contact_no,
                    'delivery_no'=>$retname->delivery_numbers,
                    'upi'=>$retname->upi,
                    'tin_number'=>$retname->tinno,
                    'servicetax_number'=>$retname->servicetax_no,
                    'service_tax'=>$retname->taxes,
                    'default_tax'=>$retname->default_taxes,
                    'vat'=>$retname->vat,
                    'vat_date'=>$retname->vat_date,
                    'invoice_digit'=>$retname->invoice_digit,
                    'outlet_code'=>$retname->code,
                    'outlet_invoice_prefix'=>$retname->invoice_prefix,
                    'invoice_date'=>OutletSetting::checkAppSetting($restaurant_id,"invoiceDate"),
                    'order_no_reset'=>OutletSetting::checkAppSetting($restaurant_id,"orderNoReset"),
                    'outlet_count'=>$i,
                    'restmenu'=>$menu,
                    'auth_users'=>$auth_user_arr,
                    'settings'=>$outlet_settings,
                    'menu_titles'=>$menu_tutle_arr,
                    'printers'=>$printers,
                    'duplicate_kot_count'=>$retname->duplicate_kot_count,
                    'feedback'=>$feedback,
                    'source'=>$source_list,
                    'reasons'=>$reasons,
                    'units'=>$units,
                    'locations'=>$all_location,
                    'users'=>$user_arr,
                    'stock_levels'=>$stock_level,
                    'attributes'=>$ot_attributes,
                    'order_lable'=>$retname->order_lable,
                    'token_lable'=>$retname->token_lable,
                    'session_time'=>$retname->session_time,
                    'order_number_increment'=>OutletSetting::checkAppSetting($restaurant_id,"incrementOrderNo"),
                    'display_no_of_person'=>OutletSetting::checkAppSetting($restaurant_id,"displayNoOfPerson"),
                    'bypass_process_bill'=>OutletSetting::checkAppSetting($restaurant_id,"bypassProcessBill"),
                    'payment_options'=>$payment_options,
                    'expense_category'=>$exp_category,
                    'tables'=>$tables,
                    'table_levels'=>$table_levels,
                    'order_place_types'=>$order_place_types,
                    'delivery_charge'=>$delivery_charge,
                    'tax_details'=>$tax_details,
                    'order_receive'=>$order_receive,
                    'last_order'=>$last_order_json,
                    'feedback_questions'=>$fb_question,
                    'app_layout'=>$retname->app_layout,
                    'custom_fields'=>$retname->custom_bill_print_fields,
                    'dummy_text'=>$retname->bill_template,
                    'bill_keys'=>$keys,
                    'inventory'=>$enable_inventory==0?false:true,
                    'enable_feedback'=>$enable_feedback==0?false:true,
                    'item_option_group'=>$item_option_group,
                    'hotkey_config'=>$retname->hotkey_config
                ),
                    200);
            } else if($i==0){
                return Response::json(array(
                    'message' => 'Your outlet is not added yet.',
                    'statuscode' => 435,
                ),
                    200);
            } else {
                return Response::json(array(
                    'message' => 'List Of all Outlet',
                    'outlet_details' => $Outlet,
                    'token'=>$token,
                    'statuscode' => 200,
                    'outlet_count' => $i,
                ),
                    200);
            }
        }else{
            return Response::json(array(
                'message' => 'Invalid username or password.',
                'statuscode' => 436,

            ),
                200);
        }
    }



    public function getoutletmemu($restaurant_id) {
        $restmenu = array();
        $Outletid = $restaurant_id;
        $menutitle =MenuTitle::getmenutitlebyrestaurantid($Outletid);

        foreach ($menutitle as $menudetails) {
            $menutitle = $menudetails->title;
            //print_r($menutitle);exit;
            $menud = Menu::getmenubymenutitleid($menudetails->id);

            $i = 0;

            foreach ($menud as $cui) {
                $menuoption=menu_option::where('menu_id',$cui->id)->get();
                $cuisinetype='';
                if(isset($cui->food) && $cui->food!=''){
                    $foodtype=$cui->food;
                }else{
                    $foodtype='';
                }
                if ($cui->menu_title_id == $menudetails->id) {
                    $restmenu[$menutitle][$i] = array(
                        'item_id' => $cui->id,
                        'item' => $cui->item,
                        'price' => (int)$cui->price,
                        'details' => $cui->details,
                        'cuisinetype' => $cuisinetype,
                        'options' => $cui->options,
                        'foodtype'=>$foodtype,
                        'active'=>$cui->active,
                        'options'=>$menuoption,
                        'like'=>$cui->like
                    );
                }
                $i++;
            }
        }
        return $restmenu;
    }



    public function ownerlogin(Request $request)
    {
        $username=Request::json('owner_name');
        $pass=Request::json('owner_pass');
        $device_id=Request::json('device_id');
        $restaurant_id=Request::json('resid');
        $input = Request::all();
        // print_r($input);exit;wner
        $maxdt=Carbon::now();
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';

        if($field=='email')
        {
            $user=Owner::where('email',$username)->whereNull('deleted_at')->first();
        }
        else
        {
            $user=Owner::where('user_name',$username)->whereNull('deleted_at')->first();
        }

        //check useris active? account table
        $owner = Owner::where($field, $username)->select('account_id')->first();
        if (isset($owner) && sizeof($owner) > 0){
            $account_id = $owner->account_id;
            $account_status = Account::find($account_id);
            if (isset($account_status) && sizeof($account_status) > 0) {
                $is_active = $account_status->active;
                if ($is_active == 0) {
                    return Response::json(array(
                        'message' => 'Account is not Active, please contact Pikal.',
                        'statuscode' => 435,
                    ),
                        200);
                }
            }
        }

        if(count($user)>0)
        {
            $a=Hash::check($pass, $user->password);
        }
        else
        {
            $a=false;
        }


        if($a==true)
        {
            $maxdt=[];
            array_push($maxdt,Carbon::now());
            $retname = Outlet::find($restaurant_id);
            if(isset($device_id) && $device_id!=''){
                if ( $device_id != $user->device_id ) {
                    $device_id.=','.$user->device_id;
                }
                DB::table('owners')->where($field,'=',$username)->update(array('device_id'=>$device_id));

            }
            $islogin=Owner::where($field,'=',$username)->first();

            $outlet_settings = OutletSetting::getApiOutletSettings($restaurant_id);

            $feedback=DB::table('feedback')->where('outlet_id','=',$restaurant_id)->get();
            //   $menu=Apicontroller::getoutletmemu($restaurant_id);
            $menu=Apicontroller::getnewMenu($restaurant_id,$retname->printer);

            //send default printers
            $printers = Printer::getOutletPrinters($retname->printer,$restaurant_id);

            //get category order
            $menu_tutle_arr = OutletMapper::getCategoryOrder($user->id);

            //cancellation reasons
            $reasons = CancellationReason::where('outlet_id',$restaurant_id)
                ->select('id','reason_of_cancellation as reason')->get();

            //sources
            $source_list = Sources::getSourceArray($restaurant_id);

            //Units
            $units = Unit::all();

            //locations
            $stock_location = Location::where('outlet_id',$restaurant_id)->get();

            //all locations
            $all_location = array();
            if( isset($user->created_by) && $user->created_by != '') {
                $all_location = Location::where('created_by',$user->created_by)->get();
            } else {
                $all_location = Location::where('created_by',$user->id)->get();
            }
            $all_location = $stock_location;

            //stock level object
            $loc_arr = array();$stock_level = array();
            if( isset($stock_location) && sizeof($stock_location) > 0 ) {
                foreach( $stock_location as $loc ) {
                    $loc_arr[] = $loc->id;
                }

                //stock_level array
                $stock_level = StockLevel::whereIn('location_id',$loc_arr)->get();
            }
            Log::info($stock_level);
            Log::info($loc_arr);
            $user_arr = array();

//            if ( isset($user->created_by) && $user->created_by != NULL ) {
//                $user_arr = Owner::where('created_by', $user->created_by)->orWhere('id',$user->created_by)->get();
//            } else {
//                $user_arr = Owner::where('id',$user->id)->orWhere('created_by',$user->id)->get();
//            }
            $user_arr = OutletMapper::leftJoin('owners','outlets_mapper.owner_id','=','owners.id')
                ->where('outlets_mapper.outlet_id','=',$restaurant_id)->get();

            //outlet authorised user
            $auth_user_arr = array();$k=0;
            if ( isset($retname->authorised_users) && $retname->authorised_users != '') {
                $a_users = explode(',',$retname->authorised_users);
                if ( isset($a_users) && sizeof($a_users) > 0 ) {
                    foreach( $a_users as $key=>$auth ) {
                        $auth_user = Owner::find($auth);
                        $auth_user_arr[$k]['id']= $auth_user->id;
                        $auth_user_arr[$k]['name']= $auth_user->user_name;
                        $k++;
                    }
                }
            }
            //Tables list
            $tables = Tables::getOutletTables($restaurant_id);

            //Item Attributes
            $ot_attributes = OutletItemAttributesMapper::getOutletAttributes($restaurant_id);

            //payment options
            $payment_options = PaymentOption::getOutletPaymentOption($restaurant_id);


            //get expense category
            $exp_category = ExpenseCategory::getExpenseCategory($user->id);

            //get order place types
            $order_place_types = OrderPlaceType::getOrderPlaceType($restaurant_id);

            //Delivery charge
            $delivery_charge = '';
            if ( isset($retname->delivery_charge) && sizeof($retname->delivery_charge) > 0 ) {
                $delivery_charge = json_decode($retname->delivery_charge);
            }

            //tax details
            $tax_details = '';
            if ( isset($retname->tax_details) && sizeof($retname->tax_details) > 0 ) {
                $tax_details = json_decode($retname->tax_details);
            }

            //owner order receive
            $order_receive = User::getOwnerOrderReceive($user->id, $restaurant_id);

            //last order sequence
            $last_order_json = order_details::lastOrderSequence($restaurant_id);

            //feedback questions
            $fb_question = FeedbackQuestion::getFbQuestions($restaurant_id);

            //table levels
            $table_levels = TableLevel::getTableLevels($restaurant_id);

            //bill template keys
            $keys = Utils::getBillTemplateKeys($restaurant_id);
            unset($keys['']);

            $account_data = Account::find($owner->account_id);
            $enable_inventory = isset($account_data->enable_inventory)?$account_data->enable_inventory:0;
            $enable_feedback = isset($account_data->enable_feedback)?$account_data->enable_feedback:0;

            //outlet item option groups
            $item_option_group = ItemOptionGroup::getOutletOptiongroups($restaurant_id);

            return Response::json(array(
                'message' => 'Valid User',
                'statuscode' => 200,
                'status'=>'Success',
                'userid'=>(string) $user->id,
                'user_identifier'=>Owner::find($user->id)->user_identifier,
                'resid'=>(string) $restaurant_id,
                'ownername'=>$islogin->user_name,
                'outletname'=>$retname->name,
                'invoice_title'=>$retname->invoice_title,
                'outlet_address'=>$retname->address,
                'contact_no'=>$retname->contact_no,
                'delivery_no'=>$retname->delivery_numbers,
                'upi'=>$retname->upi,
                'tin_number'=>$retname->tinno,
                'servicetax_number'=>$retname->servicetax_no,
                'service_tax'=>$retname->taxes,
                'default_tax'=>$retname->default_taxes,
                'vat'=>$retname->vat,
                'vat_date'=>$retname->vat_date,
                'outlet_code'=>$retname->code,
                'outlet_invoice_prefix'=>$retname->invoice_prefix,
                'invoice_date'=>OutletSetting::checkAppSetting($restaurant_id,"invoiceDate"),
                'order_no_reset'=>OutletSetting::checkAppSetting($restaurant_id,"orderNoReset"),
                'invoice_digit'=>$retname->invoice_digit,
                'restmenu'=>$menu,
                'auth_users'=>$auth_user_arr,
                'settings'=>$outlet_settings,
                'menu_titles'=>$menu_tutle_arr,
                'printers'=>$printers,
                'duplicate_kot_count'=>$retname->duplicate_kot_count,
                'feedback'=>$feedback,
                'source'=>$source_list,
                'reasons'=>$reasons,
                'units'=>$units,
                'locations'=>$all_location,
                'users'=>$user_arr,
                'stock_levels'=>$stock_level,
                'attributes'=>$ot_attributes,
                'order_lable'=>$retname->order_lable,
                'token_lable'=>$retname->token_lable,
                'session_time'=>$retname->session_time,
                'order_number_increment'=>OutletSetting::checkAppSetting($restaurant_id,"incrementOrderNo"),
                'display_no_of_person'=>OutletSetting::checkAppSetting($restaurant_id,"displayNoOfPerson"),
                'bypass_process_bill'=>OutletSetting::checkAppSetting($restaurant_id,"bypassProcessBill"),
                'payment_options'=>$payment_options,
                'expense_category'=>$exp_category,
                'tables'=>$tables,
                'table_levels'=>$table_levels,
                'order_place_types'=>$order_place_types,
                'delivery_charge'=>$delivery_charge,
                'tax_details'=>$tax_details,
                'order_receive'=>$order_receive,
                'last_order'=>$last_order_json,
                'feedback_questions'=>$fb_question,
                'app_layout'=>$retname->app_layout,
                'custom_fields'=>$retname->custom_bill_print_fields,
                'dummy_text'=>$retname->bill_template,
                'bill_keys'=>$keys,
                'inventory'=>$enable_inventory==0?false:true,
                'enable_feedback'=>$enable_feedback==0?false:true,
                'item_option_group'=>$item_option_group,
                'hotkey_config'=>$retname->hotkey_config

            ),
                200);
        }

        else

        {
            if(sizeof($user)>0)
            {
                return Response::json(array(
                    'message' => 'Please Add Valid PassWord',
                    'statuscode' => 434,
                ),
                    200);
            }

            else
            {
                return Response::json(array(
                    'message' => 'Please Add Valid User',
                    'statuscode' => 435,
                ),
                    200);
            }
        }


    }

    /**
     * @param $resid
     * @param null $ot_printer
     * @return array
     */
    public function getnewMenu( $resid, $ot_printer = NULL, $flag = NULL ){

        $menu=MenuTitle::select('menus.*','menu_titles.title')
            ->join("outlet_menu_bind","outlet_menu_bind.menu_id","=","menu_titles.id")
            ->join("menus","menus.id","=","outlet_menu_bind.item_id")
            ->where('outlet_menu_bind.outlet_id',$resid)
            ->whereNull('menus.deleted_at')
            ->groupby("menus.id")
            ->orderBy('menu_titles.title_order', 'asc')
            ->orderBy('menus.item_order', 'asc')
            ->orderBy('menus.item', 'asc')
            ->get();

        $a=array();
        foreach($menu as $m) {

            if ( isset($flag) && $flag == 'consumer' ) {

                $itm_check = ItemSettings::where('outlet_id',$resid)->where('item_id',$m->id)->first();

                if ( isset($itm_check) && sizeof($itm_check) > 0 ) {
                    if ( $itm_check->is_sale == 0 ) {
                        continue;
                    }
                }
            }

            $cuisine=CuisineType::find($m->cuisine_type_id);

            $printer_id = '';
            $print_bind_check = PrinterItemBind::where('outlet_id',$resid)->where('item_id',$m->id)->first();
            if ( isset($print_bind_check) && sizeof($print_bind_check) > 0 ) {
                $printer_id = $print_bind_check->printer_id;
            } else {

                if ( isset($ot_printer) && $ot_printer != '' ) {
                    $check_print = json_decode($ot_printer);
                    if ( isset($check_print) && sizeof($check_print) > 0 ) {
                        $printer_id = $check_print->kot_printer;
                    }
                }


            }

            #TODO: check item is_sale and active outletwise
            $is_sale = 1;$active = 0;
            $itm_setting_arr = ItemSettings::where('outlet_id',$resid)->where('item_id',$m->id)->first();

            if( isset($itm_setting_arr) && sizeof($itm_setting_arr) > 0 ) {
                $is_sale = $itm_setting_arr->is_sale;
                $active = $itm_setting_arr->is_active;
            }

            $other_units = '';$unit_arr = array();
            if( isset($m->secondary_units) && $m->secondary_units != '' ) {
                $units = json_decode($m->secondary_units);
                if ( isset($units) && $units != '') {
                    foreach( $units as $key=>$u ) {
                        $other_units['id'] = $key;
                        $other_units['name'] = Unit::find($key)->name;
                        $other_units['value'] = $u;
                        array_push($unit_arr,$other_units);
                    }
                }

            }

            $path = '';
            if ( isset($m->image) && $m->image != '' ){
                $path = "".$m->image;
            }

            //deprecated in new app after 1.0.100
            //item options
            $item_options = $itemOptinos =  array();

            $item_options_obj = MenuItemOption::where('parent_item_id',$m->id)->get();

            if ( isset($item_options_obj) && sizeof($item_options_obj) > 0 ) {
                foreach ( $item_options_obj as $opt ) {
                    $itemOptinos['id'] = $opt->id;
                    $itemOptinos['parent_item_id'] = $opt->parent_item_id;
                    $itemOptinos['option_item_id'] = $opt->option_item_id;
                    $itemOptinos['option_item_price'] = $opt->option_item_price;
                    array_push($item_options,$itemOptinos);
                }
            }

            //item option groups
            $item_option_groups = ItemOptionGroupMapper::where('item_id',$m->id)->pluck('item_option_group_id');


            $inner_array=array('item_id'=>$m->id,
                'item_code'=>$m->item_code,
                'item'=>ucwords($m->item),
                'item_sequence_number'=>$m->item_order,
                'item_image'=>$path,
                'color'=>$m->color,
                'alias'=>$m->alias,
                'unit_id'=>$m->unit_id,
                'other_unit'=>$unit_arr,
                'order_unit'=>$m->order_unit,
                'price'=>number_format($m->price,2,'.',''),
                'details'=>$m->details,
                'taxable'=>$m->taxable,
                'discountable'=>$m->discountable,
                'discount_type'=>$m->discount_type,
                'discount_value'=>$m->discount_value,
                'cuisinetype'=>$cuisine,
                'options'=>$m->options,
                'foodtype'=>$m->food,
                'active'=>$active,
                'is_sale'=>$is_sale,
                'like'=>$m->like,
                'menu_title'=>$m->title,
                'printer_id'=>$printer_id,
                'item_options'=>$item_options,
                'item_option_groups'=>$item_option_groups,
                'barcode'=>$m->barcode,
                'tax_slab'=>$m->tax_slab);

            if(!array_key_exists($m->title,$a)) {
                $a[ucwords($m->title)][] = $inner_array;
            } else {
                array_push($a[ucwords($m->title)],$inner_array);
            }
        }

        return $a;
    }

    public function ownerlogout(){

        $owner_id =Request::get("owner_id");

        $owner = Owner::where('id',$owner_id)->first();

        if ( isset($owner) && sizeof($owner) > 0 ) {

            $owner->islogin = 0;
            $owner->device_id = NULL;
            $owner->api_token = NULL;
            $owner->save();

            return Response::json(array(
                'message' => 'Owner Logged Out Successfully',
                'statuscode' => 200,
                'status'=>'Success',

            ),
                200);

        } else {

            return Response::json(array(
                'message' => 'No Owner Data',
                'statuscode' => 434,
                'status'=>'Error',

            ),
                434);

        }

    }
    public function autoorders(){

        $date= Request::json('maxdt');

        // $user=User::find(Auth::user()->id);
        $star=[];
        //  $id=$user->restaurant->pluck('id');
        $a='';$b='';
        //$restaurant_id=$id[0];

        $restaurant_id=Request::json('restaurant_id');
        $retname=Outlet::find($restaurant_id);
        $totalprice=[];
        $orderappend='';
        $maxdt =[];
        $maxdt[0]=$date;
        $orders=$retname->orderdetail()->where('status','!=','delivered')->where('cancelorder', '!=', 1)->where('created_at','>',new Carbon($date))->orderBy('created_at', 'desc')->get();
        $items=[];
        $rating=[];
        $uniquepricearray=[];
        $othercount=[];
        if(count($orders) > 0) {
            $totalprice=[];
            foreach($orders as $key=>$order){
                $item=OrderItem::where('order_id',$order->order_id)->get();
                $totalpr=0;
                $it=array();
                foreach($item as $t){

                    $itemnew=$t->menuitem;
                    array_push($it,array("item"=>$itemnew->item,"quantity"=>$t->item_quantity,"price"=>$itemnew->price));
                    if(isset($itemnew['price']) && $itemnew['price']!='') {
                        $totalpr+=$t->item_quantity*$itemnew['price'];
                    }
                    else {
                        $totalpr+=0;
                    }
                }
                array_push($items,$it);
                //$totalprice[$order->order_id]=$totalpr;
                array_push($totalprice, array($order->suborder_id => $order->totalcost_afterdiscount));

                // echo in_array($order->user_mobile_number,$uniquepricearray);

                if($key==0){$maxdt[0]=$order->created_at;}
            }
            $forrating=$retname->orderdetail()->where('status','=','delivered')->orderBy('created_at', 'desc')->get();
            $otheroutletordercount=DB::table('orders')->where('status','=','delivered')->where('outlet_id','!=',$restaurant_id)->get();
            $uniqueprice=DB::table('orders')->where('status','=','delivered')->get();
            foreach($uniqueprice as $k1=>$v1){
                if(!array_key_exists($v1->user_mobile_number,$uniquepricearray)){
                    //array_push($uniquepricearray,array($order->user_mobile_number=>number_format($totalpr, 2, '.', '')));
                    $uniquepricearray[$v1->user_mobile_number]=$v1->totalprice;
                }
                if($uniquepricearray[$v1->user_mobile_number]<$v1->totalprice){
                    $uniquepricearray[$v1->user_mobile_number]=$v1->totalprice;

                }

            }
            foreach($otheroutletordercount as $k=>$v){
                if(!array_key_exists($v->user_mobile_number,$rating)){
                    $othercount[$v->user_mobile_number]=1;
                }else{
                    $othercount[$v->user_mobile_number]=$othercount[$v->user_mobile_number]+1;
                }
            }
            $i=1;
            foreach($forrating as $key=>$value){


                if(!array_key_exists($value->user_mobile_number,$rating)){
                    $rating[$value->user_mobile_number]=1;
                }else{
                    $rating[$value->user_mobile_number]=$rating[$value->user_mobile_number]+1;
                }
                //$totalprice[$order->order_id]=$totalpr;


            }
            return Response::json(array(
                'message' => 'Order Data',
                'statuscode' => 200,
                'status'=>'Success',
                'orders'=>$orders->toArray(),
                'resid'=>(string) $restaurant_id,
                'items'=>$items,
                'total'=>$totalprice,
                'maxdt'=>$maxdt,
                'rating'=>$rating,
                'maxprice'=>$uniquepricearray,
                'othercount'=>$othercount

            ),
                200);

        }
        else {
            return Response::json(array(
                'message' => 'No Order Data',
                'statuscode' => 434,
                'status'=>'Success',

            ),
                200);
        }

    }

    public function nextstatus(){
        $currents=Request::json('currentstatus');
        $oid=Request::json('oid');
        $order_id = Request::json('order_id');
        $resid=Request::json('restaurant_id');

        $useragent=Request::json('user_agent');
        $getcurrentstatus=status::where('status',$currents)->where('outlet_id',$resid)->get();

        $order_date=Request::json('order_date');
        $currentstatus='';
        $status='';
        $f=array();
        $order_summary=array();
        foreach($getcurrentstatus as $getstatus){

            $getnextstatus=status::where('order','>',$getstatus->order)->where('outlet_id',$resid)->orderby('order','ASC')->first();

            if(isset($getnextstatus) && sizeof($getnextstatus) > 0 ){

                $ordstat = order_details::where('order_id',$order_id)->first();


                order_details::where('status',$currents)->where('order_id',$order_id)->update(array('status'=>$getnextstatus->status));

                $restname=Outlet::where('id',$ordstat['restaurant_id'])->first();

                $currentstatus=$getnextstatus->status;
                if($currentstatus=="preparing") {
                    $status="prepared";
                }
                else if($currentstatus=="prepared") {
                    $status="delivered";
                }else if($currentstatus=="delivered") {
                    $status="";
                }


                array_push($f,$ordstat['device_id']);
                array_push($f,$currents);
                array_push($f,$oid);
                array_push($f,$currentstatus);
                array_push($f,$order_date);
                array_push($f,$order_id);

            }
        }


        if($useragent=="android"){
            //Queue::push('App\Commands\OrderNotification@getordersnotification', array('fields'=>$f));
        }else{
            //Queue::push('App\Commands\IOS\OrderNotification@getordersnotification', array('fields'=>$f));
        }
        return Response::json(array(
            'message' => 'ok',
            'statuscode' => 200,
            'nextstatus'=>ucfirst($currentstatus),
            'buttonstatus'=>$status=='delivered'?ucfirst($status):"",
            'suborder_id'=>$oid,
            'created_at'=>$order_date
        ),
            200);

    }

    // First Order API
    public function firstorder(Request $request){

        $contact=Request::get('mobile');
        $name=Request::get('name');
        $user_entered_couponcode=Request::get('coupon_code');


        if(isset($contact) && $user_entered_couponcode != '' ){
            $couponordermapper=ordercouponmapper::where('coupon_applied',$user_entered_couponcode)->where('user_mobile_number',$contact)->first();
            if(sizeof($couponordermapper)>0){
                return Response::json(array(
                    'message' => "Coupon Code is already used.",
                    'status' => 'Success',
                    'statuscode' => 436
                ),
                    200);
            }elseif(isset($contact)){
                //for finding customer by phone_number
                $usercheck=users::findcustomerbyphonenumber($contact);

                $password=users::generateotp();
                if(count($usercheck)==0) {

                    $id=users::getidofaddedcustomer($contact,$password,$password);
                    users::sendpassword($contact,$password,$name);

                    return Response::json(array(
                        'message' => 'User is Added successfully',
                        'userid'=>(string) $id,
                        'password'=>$password,
                        'status' => 'NotVerified',
                        'statuscode' => 200,
                        'New User'=>0,
                        'mobile'=>$contact
                    ),
                        200);
                }
                else {

                    users::updatepass($password,$contact);

                    users::sendpassword($contact,$password,$name);

                    return Response::json(array(
                        'message' => 'User details successfully updated',
                        'userid'=>$usercheck->pluck('id')[0],
                        'password'=>$password,
                        'status' => 'NotVerified',
                        'statuscode' => 200,
                        'New User'=>1,
                        'mobile'=>$contact
                    ),
                        200);

                }
            }
            
        } else {

            //for finding customer by phone_number
            $usercheck=users::findcustomerbyphonenumber($contact);

            $password=users::generateotp();
            if(count($usercheck)==0) {

                $id=users::getidofaddedcustomer($contact,$password,$password);
                users::sendpassword($contact,$password,$name);

                return Response::json(array(
                    'message' => 'User is Added successfully',
                    'userid'=>(string) $id,
                    'password'=>$password,
                    'status' => 'NotVerified',
                    'statuscode' => 200,
                    'New User'=>0,
                    'mobile'=>$contact
                ),
                    200);
            } else {

                users::updatepass($password,$contact);

                users::sendpassword($contact,$password,$name);

                return Response::json(array(
                    'message' => 'User details successfully updated',
                    'userid'=>$usercheck->pluck('id')[0],
                    'password'=>$password,
                    'status' => 'NotVerified',
                    'statuscode' => 200,
                    'New User'=>1,
                    'mobile'=>$contact
                ),
                    200);

            }

        }
    }

    public function sendmail(Request $request)
    {
        $mail=Request::get('mail');
        $password=Request::get('password');
        Mail::send('emails.sendpassword', ['password' => $password], function($message) use ($mail)
        {
            $message->from('we@pikal.io', 'Pikal');
            $message->to($mail, 'Pikal');
            $message->subject('Pikal Login Details');
        });
        return Response::json(array(
            'message' => 'Email sent successfully',
            'password'=>$password,
            'statuscode' => 200,
            'mail'=>$mail
        ),
            200);
    }

    public function addaddress(Request $request)
    {
        $getuserid=Request::json('user_id');
        $getaddress=Request::json('address');
        $getlocality=Request::json('locality');
        $pincode=Request::json('pincode');
        $phonenumber=Request::json('user_mobile_number');
        $address_tag=Request::json('address_tag');
        $state=Request::json('state');
        $city=Request::json('city');
        $country=Request::json('country');
        $landmark=Request::json('landmark');
        $flatnumber=Request::json('flatnumber');


        $address=new address();


        if(isset($phonenumber)) {
            $address->user_mobile_number = $phonenumber;
        }
        if(isset($getaddress)) {
            $address->address = $getaddress;
        }
        if(isset($getlocality)){
            $address->locality=$getlocality;
        }
        if(isset($state)) {
            $address->state = $state;
        }
        if(isset($city)) {
            $address->city = $city;
        }
        if(isset($country)) {
            $address->country = $country;
        }
        if(isset($landmark)) {
            $address->landmark = $landmark;
        }
        if(isset($flatnumber)) {
            $address->flatnumber = $flatnumber;
        }
        if(isset($pincode)) {
            $address->pincode = $pincode;
        }
        if(isset($address_tag)) {
            $address->address_tag = $address_tag;
        }
        $address->save();
        return Response::json(array(
            'message' => 'Address Added successfully',
            'address_id'=>$address->id,
            'statuscode' => 200,
            'address_tag'=>'addresstag'
        ),
            200);
    }

    public function payuview(){
        return view('payu.index');
    }
    public function payusuccess(){

        return view('payu.success');
    }
    public function payufailure(){
        return view('payu.failure');
    }

    public function paytmsuccess(){

        return view('paytm.success');
    }

    public function addstatusforallrestaurant(){
        $outlets=Outlet::all();
        $statusarray=array('received','preparing','prepared','delivered');
        foreach($outlets as $outlet){
            $checkstatus=status::where('outlet_id',$outlet['id'])->get();
            if(sizeof($checkstatus)==0){
                for($i=0;$i<count($statusarray);$i++){
                    $status=new status();
                    $status->status=$statusarray[$i];
                    $status->owner_id=$outlet['owner_id'];
                    $status->order=$i+1;
                    $status->outlet_id=$outlet['id'];
                    $status->save();
                }
            }

        }
    }

    public function logincustomers(){
        $contact=Request::json('mobile');

        if(isset($contact)){
            //for finding customer by phone_number
            $usercheck=users::findcustomerbyphonenumber($contact);
            //   $orders=order_details::where('user_mobile_number',$contact)->get();

            $password=users::generateotp();
            users::sendotpbymessage($password,$contact);
//                $ordarray=array();
//                $i=0;
//                    foreach($orders as $ord){
//                        $orderitems=$ord->orderdetails;
//                        $outlet_name=Outlet::where('id',$ord->outlet_id)->first();
//                        $ordarray[$i][$outlet_name['name']]=array('item_name'=>$orderitems->item_name,
//                                            'item_quantity'=>$orderitems->quantity,
//                                            'item_price'=>$orderitems->item_price);
//
//                    }

            if(count($usercheck)==0) {

                $id=users::getidofaddedcustomer($contact,$password,$password);

                return Response::json(array(
                    'message' => 'User Logged in Successfully',
                    'user_server_id'=>(string) $id,
                    'otp'=>$password,
                    'status' => 'Success',
                    'statuscode' => 200
                ),
                    200);
            }else{
                $userdata=users::where('mobile_number',$contact)->get();
                $id=$userdata[0]->id;
                return Response::json(array(
                    'message' => 'User Already Exist',
                    'user_server_id'=>(string) $id,
                    'otp'=>$password,
                    'status' => 'Success',
                    'statuscode' => 200
                ),
                    200);
            }

        }

    }

    public function getallcities(){
        $states=State::getallstates();
        $array=array();
        $i=0;
        foreach($states as $ssta){
            $city=City::getcitybystateid($ssta->id);
            foreach($city as $cty){
                $array[$i]['city']=$cty->name .'-'. $ssta->name;
                $i++;
            }

        }

        return Response::json(array(
            'message' => 'Cities fetched successfully',
            'city_state'=> $array,
            'status' => 'Success',
            'statuscode' => 200
        ),
            200);
    }


    public function getlocalitybycity(){
        $ctyid=Request::get('city');
        if($ctyid!='Select City' && $ctyid!='Select'){
            $cityname=explode('-', $ctyid);

            $city=City::getcitybycityname($cityname[0]);

            $array=array();
            $i=0;
            $locality=locality::getlocalitybycityid($city[0]['id']);
            foreach($locality as $lcty){

                $array[$i]['locality']=$lcty->locality;
                $i++;
            }

            return Response::json(array(
                'message' => 'Locality fetched successfully',
                'localities'=> $array,
                'status' => 'Success',
                'statuscode' => 200
            ),
                200);
        }else{
            return Response::json(array(
                'message' => 'Select Valid City',
                'status' => 'Error',
                'statuscode' => 401
            ),
                200);
        }
    }
    public function getlocality(){
        $array=array();
        $i=0;
        $locality=locality::getalllocality();
        foreach($locality as $lcty){

            $array[$i]['locality']=$lcty->locality;
            $i++;
        }

        return Response::json(array(
            'message' => 'Locality fetched successfully',
            'localities'=> $array,
            'status' => 'Success',
            'statuscode' => 200
        ),
            200);

    }


    public function ownerfetchdata(Request $request)
    {
        $username=Request::json('owner_name');
        $pass=Request::json('owner_pass');
        $restaurant_id=Request::json('resid');
        $maxdt=Carbon::now();
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';

        if($field=='email')
        {
            $user=Owner::where('email',$username)->first();
        }
        else
        {
            $user=Owner::where('user_name',$username)->first();
        }

        $maxdt=[];
        array_push($maxdt,Carbon::now());

        $retname=Outlet::find($restaurant_id);
        $star=[];
        $orders=$retname->orderdetail()->where('status','!=','delivered')->where('cancelorder', '!=', 1)->orderBy('created_at', 'asc')->get();

        $totalprice=[];
        $rating=[];
        $uniquepricearray=[];
        $othercount=[];
        $items=[];

        if(count($orders) > 0)
        {
            $forrating=$retname->orderdetail()->where('status','=','delivered')->orderBy('created_at', 'desc')->get();
            $otheroutletordercount=DB::table('orders')->where('status','=','delivered')->where('outlet_id','!=',$restaurant_id)->get();
            $uniqueprice=DB::table('orders')->where('status','=','delivered')->get();
            if(sizeof($uniqueprice)>0){
                foreach($uniqueprice as $k1=>$v1){
                    if(!array_key_exists($v1->user_mobile_number,$uniquepricearray)){
                        //array_push($uniquepricearray,array($order->user_mobile_number=>number_format($totalpr, 2, '.', '')));
                        $uniquepricearray[$v1->user_mobile_number]=$v1->totalprice;
                    }
                    if($uniquepricearray[$v1->user_mobile_number]<$v1->totalprice){
                        $uniquepricearray[$v1->user_mobile_number]=$v1->totalprice;

                    }

                }
            }
            if(sizeof($otheroutletordercount)>0){
                foreach($otheroutletordercount as $k=>$v){
                    if(!array_key_exists($v->user_mobile_number,$rating)){
                        $othercount[$v->user_mobile_number]=1;
                    }else{
                        $othercount[$v->user_mobile_number]=$othercount[$v->user_mobile_number]+1;
                    }
                }
            }
            if(sizeof($forrating)>0){
                foreach($forrating as $key=>$value){
                    $item=OrderItem::where('order_id',$value->order_id)->get();
                    $totalpr=0;
                    $it=array();
                    foreach($item as $t){

                        $itemnew=$t->menuitem;
                        if(isset($itemnew['price']) && $itemnew['price']!='') {
                            $totalpr+=$t->item_quantity*$itemnew['price'];
                        }
                        else {
                            $totalpr+=0;
                        }

                    }

                    if(!array_key_exists($value->user_mobile_number,$rating)){
                        $rating[$value->user_mobile_number]=1;
                    }else{
                        $rating[$value->user_mobile_number]=$rating[$value->user_mobile_number]+1;
                    }
                    //$totalprice[$order->order_id]=$totalpr;

                    // echo in_array($order->user_mobile_number,$uniquepricearray);

                }
            }

            foreach($orders as $key=>$order)
            {
                $printcount=Printsummary::where('order_id',$order->suborder_id)->where('order_created_at',$order->created_at)->first();
                if(sizeof($printcount)>0){
                    $order->printcount=$printcount['print_number'];
                }else{
                    $order->printcount=0;
                }

                if(isset($rating[$order->user_mobile_number])){
                    $order->rating=$rating[$order->user_mobile_number];
                }else{
                    $order->rating='';
                }
                if(isset($othercount[$order->user_mobile_number])){
                    $order->othercount=$othercount[$order->user_mobile_number];
                }else{
                    $order->othercount='';
                }
                if(isset($uniquepricearray[$order->user_mobile_number])) {
                    $order->maxprice = $uniquepricearray[$order->user_mobile_number];
                }else{
                    $order->maxprice='';
                }
                $item=OrderItem::where('order_id',$order->order_id)->get();
                $totalpr=0;
                $it=array();
                foreach($item as $t){

                    //$itemnew=$t->menuitem;
                    $madt=date("Y-m-d H:i:s",strtotime($order->created_at));
                    array_push($it,array("item"=>$t->item_name,"quantity"=>$t->item_quantity,"price"=>$t->item_price,"suborder_id"=>$order->suborder_id,"created_at"=>$madt,"item_options"=>$t->item_options,"item_options_price"=>$t->item_options_price));
                    if(isset($t->item_price) && $t->item_price != '') {
                        $totalpr+=$t->item_quantity * $t->item_price;
                    }
                    else {
                        $totalpr+=0;
                    }

                }
                array_push($items,$it);
                $star[$order->order_id]=DB::table('orders')->where('user_mobile_number',$order->phone_number)->count();
                //$totalprice[$order->order_id]=$totalpr;
                array_push($totalprice, array($order->suborder_id => $order->totalcost_afterdiscount));


                if($key==0){$maxdt[0]=$order->created_at;}
            }
        }

        return Response::json(array(
            'message' => 'Valid User',
            'statuscode' => 200,
            'status'=>'Success',
            'userid'=>(string) $user->id,
            'orders'=>$orders->toArray(),
            'resid'=>(string) $restaurant_id,
            'items'=>$items,
            'total'=>$totalprice,
            'maxdt'=>$maxdt,
            'rating'=>$rating,
            'maxprice'=>$uniquepricearray,
            'othercount'=>$othercount,
            'ownername'=>$user->user_name,
            'outletname'=>$retname->name

        ),
            200);



    }
    public function matchcouponcode(){
        $flag = Request::get('flag');
        $user_entered_couponcode = '';
        $total_cost = '';
        $mobile_number = '';
        if( isset($flag) && $flag == 'web_app_order' ){
            $user_entered_couponcode = Request::get('coupon_code');
            $total_cost = Request::get('total_cost');
            $mobile_number = Request::get('mobile_number');
            $outlet = Request::get('res_id');
        }else{
            $user_entered_couponcode=Request::json('coupon_code');
            $total_cost=Request::json('total_cost');
            $mobile_number=Request::json('mobile_number');
            $outlet = Request::json('res_id');
           // $resid=Request::json('res_id');
        }

        $date=Date("Y-m-d");

        //print_r($user_entered_couponcode.'==='.$total_cost.'==='.$mobile_number.'==='.$flag);exit;

        $coupondata=CouponCodes::where('coupon_code',$user_entered_couponcode)
                                ->whereRaw("FIND_IN_SET($outlet,outlet_ids)")->first();
        //print_r($coupondata);exit;
        if(sizeof($coupondata)>0){
            $usage_count = ordercouponmapper::where('coupon_applied',$coupondata->id)->count();
            if ( $usage_count >= $coupondata->no_of_users ) {
                return Response::json(array(
                    'message' => 'Coupon usage limit exceeded.',
                    'status' => 'success',
                    'statuscode' => 435
                ),200);
            }
        }

        if($mobile_number!=''){
            $couponordermapper=ordercouponmapper::where('coupon_applied',$user_entered_couponcode)->where('user_mobile_number',$mobile_number)->first();
            if(sizeof($couponordermapper)>0){
                $msg = 'Coupon code is already used.';
                if( isset($flag) && $flag == 'web_app_order' ){
                    return array('message' => $msg, 'status' => 'already');
                }else{
                    return Response::json(array(
                        'message' => $msg,
                        'status' => 'Success',
                        'statuscode' => 436
                    ),
                        200);
                }

            }
        }
        if(sizeof($coupondata)>0){
            if($coupondata->min_value>$total_cost){
                $roundedvalue=round($coupondata->min_value);
                $msg = 'Minimum order price should be equal/more than '.$roundedvalue;
                if( isset($flag) && $flag == 'web_app_order' ){
                    return array('message' => $msg,  'status' => 'minimum');
                }else{
                    return Response::json(array(
                        'message' => $msg,
                        'status' => 'Success',
                        'statuscode' => 432
                    ),
                        200);
                }


            }
            elseif(strtotime($date) < strtotime($coupondata->activated_datetime)){
                $msg = 'Invalid coupon code.';
                if( isset($flag) && $flag == 'web_app_order' ){
                    return array('message' => $msg, 'status' => 'expired');
                }else{
                    return Response::json(array(
                        'message' => $msg,
                        'status' => 'Success',
                        'statuscode' => 437
                    ),
                        200);
                }


            }
            elseif(strtotime($coupondata->expire_datetime)<strtotime($date)){
                $msg = 'Code is Expire.';
                if( isset($flag) && $flag == 'web_app_order' ){
                    return array('message' => $msg, 'status' => 'expired');
                }else{
                    return Response::json(array(
                        'message' => $msg,
                        'status' => 'Success',
                        'statuscode' => 433
                    ),
                        200);
                }


            }
            else{
                if($coupondata->value!=''){
                    $discounted_value=$total_cost-$coupondata->value;
                    $roundedvalue=round($discounted_value);
                    $msg = "Coupon $coupondata->coupon_code applied successfully.  &#8377; $coupondata->value is discounted.";
                    if( isset($flag) && $flag == 'web_app_order' ){
                        return array(
                            'message' => $msg,
                            'status' => 'Success',
                            'min_value' => $coupondata->min_value,
                            'coupon_code'=>$coupondata->coupon_code,
                            'discounted_value'=>$coupondata->value,
                            'coupondata'=>$coupondata,
                            'cost_beforediscount'=>$total_cost,
                            'status' => 'applied',
                            'coupon_id'=>$coupondata->id);
                    }else{
                        return Response::json(array(
                            'message' => $msg,
                            'status' => 'Success',
                            'coupon_code'=>$coupondata->coupon_code,
                            'discounted_value'=>$coupondata->value,
                            'coupondata'=>$coupondata,
                            'cost_beforediscount'=>$total_cost,
                            'statuscode' => 200,
                            'coupon_id'=>$coupondata->id
                        ),
                            200);
                    }

                }
                elseif($coupondata->percentage!=''){
                    $discounted_value=($coupondata->percentage/100)*$total_cost;
                    if($discounted_value>$coupondata->max_value){
                        $msg = "Coupon $coupondata->coupon_code applied successfully. Maximum &#8377; $coupondata->max_value is discounted";
                        if( isset($flag) && $flag == 'web_app_order' ){
                            return array(
                                'message' => $msg,
                                'status' => 'Success',
                                'min_value' => $coupondata->min_value,
                                'coupon_code'=>$coupondata->coupon_code,
                                'discounted_value'=>$coupondata->max_value,
                                'coupondata'=>$coupondata,
                                'cost_beforediscount'=>$total_cost,
                                'status' => 'applied',
                                'coupon_id'=>$coupondata->id);
                        }else{
                            return Response::json(array(
                                'message' => $msg,
                                'status' => 'Success',
                                'coupon_code'=>$coupondata->coupon_code,
                                'discounted_value'=>$coupondata->max_value,
                                'coupondata'=>$coupondata,
                                'cost_beforediscount'=>$total_cost,
                                'statuscode' => 200,
                                'coupon_id'=>$coupondata->id
                            ),
                                200);
                        }

                    }
                    $roundedvalue=round($discounted_value);
                    if( isset($flag) && $flag == 'web_app_order' ){
                        $msg = "Coupon $coupondata->coupon_code applied successfully.  &#8377; $roundedvalue is discounted.";
                        return array(
                            'message' => "Coupon $coupondata->coupon_code applied successfully.  &#8377; $roundedvalue is discounted.",
                            'status' => 'Success',
                            'min_value' => $coupondata->min_value,
                            'coupon_code'=>$coupondata->coupon_code,
                            'discounted_value'=>$roundedvalue,
                            'coupondata'=>$coupondata,
                            'cost_beforediscount'=>$total_cost,
                            'status' => 'applied',
                            'coupon_id'=>$coupondata->id);
                    }else{
                        return Response::json(array(
                            'message' => "Coupon $coupondata->coupon_code applied successfully.  &#8377; $roundedvalue is discounted.",
                            'status' => 'Success',
                            'coupon_code'=>$coupondata->coupon_code,
                            'discounted_value'=>$roundedvalue,
                            'coupondata'=>$coupondata,
                            'cost_beforediscount'=>$total_cost,
                            'statuscode' => 200,
                            'coupon_id'=>$coupondata->id
                        ),
                            200);
                    }

                }

            }
        }else{
            $msg = 'Coupon code is invalid.Please enter valid coupon code';
            if( isset($flag) && $flag == 'web_app_order' ){
                return array('message' => $msg, 'status' => 'invalid');
            }else{
                return Response::json(array(
                    'message' => $msg,
                    'status' => 'Error',
                    'statuscode' => 434
                ),
                    200);
            }

        }

    }

    public function matchcouponpro(){
        $flag = Request::get('flag');
        $user_entered_couponcode = '';
        $total_cost = '';
        $mobile_number = '';
        if( isset($flag) && $flag == 'web_app_order' ){
            $resid=Request::json('res_id');
            $user_entered_couponcode = Request::get('coupon_code');
            $total_cost = Request::get('total_cost');
        }else{
            $user_entered_couponcode=Request::json('coupon_code');
            $total_cost=Request::json('total_cost');
            $resid=Request::json('res_id');
        }
        $date=Date("Y-m-d");

        $coupondata=CouponCodes::where('coupon_code',$user_entered_couponcode)->first();

        if(sizeof($coupondata)>0){
            if($coupondata->min_value>$total_cost){
                $roundedvalue=round($coupondata->min_value);
                $msg = 'Minimum order price should be equal/more than '.$roundedvalue;
                if( isset($flag) && $flag == 'web_app_order' ){
                    return array('message' => $msg,  'status' => 'minimum');
                }else{
                    return Response::json(array(
                        'message' => $msg,
                        'status' => 'Success',
                        'statuscode' => 432
                    ),
                        200);
                }

            }
            elseif(strtotime($coupondata->expire_datetime)<strtotime($date)){
                $msg = 'Code is Expire.';
                if( isset($flag) && $flag == 'web_app_order' ){
                    return array('message' => $msg, 'status' => 'expired');
                }else{
                    return Response::json(array(
                        'message' => $msg,
                        'status' => 'Success',
                        'statuscode' => 433
                    ),
                        200);
                }
            }
            else{
                if($coupondata->value!=''){
                    $discounted_value=$total_cost-$coupondata->value;
                    $roundedvalue=round($discounted_value);
                    $msg = "Coupon $coupondata->coupon_code applied successfully.  &#8377; $coupondata->value is discounted.";

                        return Response::json(array(
                            'message' => $msg,
                            'status' => 'Success',
                            'coupon_code'=>$coupondata->coupon_code,
                            'discounted_value'=>$coupondata->value,
                            'coupondata'=>$coupondata,
                            'cost_beforediscount'=>$total_cost,
                            'statuscode' => 200
                        ),
                            200);
                }
                elseif($coupondata->percentage!=''){
                    $discounted_value=($coupondata->percentage/100)*$total_cost;
                    if($discounted_value>$coupondata->max_value){
                        $msg = "Coupon $coupondata->coupon_code applied successfully. Maximum &#8377; $coupondata->max_value is discounted";

                            return Response::json(array(
                                'message' => $msg,
                                'status' => 'Success',
                                'coupon_code'=>$coupondata->coupon_code,
                                'discounted_value'=>$coupondata->max_value,
                                'coupondata'=>$coupondata,
                                'cost_beforediscount'=>$total_cost,
                                'statuscode' => 200
                            ),
                                200);
                    }
                    $roundedvalue=round($discounted_value);
                    if( isset($flag) && $flag == 'web_app_order' ){
                        $msg = "Coupon $coupondata->coupon_code applied successfully.  &#8377; $roundedvalue is discounted.";
                        return array(
                            //'message' => "Coupon $coupondata->coupon_code applied successfully.  &#8377; $roundedvalue is discounted.",
                            'message' => "Coupon $coupondata->coupon_code applied successfully. $roundedvalue is discounted.",
                            'status' => 'Success',
                            'min_value' => $coupondata->min_value,
                            'coupon_code'=>$coupondata->coupon_code,
                            'discounted_value'=>$roundedvalue,
                            'coupondata'=>$coupondata,
                            'cost_beforediscount'=>$total_cost,
                            'status' => 'applied');
                    }else{
                        return Response::json(array(
                            'message' => "Coupon $coupondata->coupon_code applied &#8377; $roundedvalue is discounted.",
                            'status' => 'Success',
                            'coupon_code'=>$coupondata->coupon_code,
                            'discounted_value'=>$roundedvalue,
                            'coupondata'=>$coupondata,
                            'cost_beforediscount'=>$total_cost,
                            'statuscode' => 200
                        ),
                            200);
                    }
                }
            }
        }else{
            $msg = 'Coupon code is invalid.Please enter valid coupon code';
            if( isset($flag) && $flag == 'web_app_order' ){
                return array('message' => $msg, 'status' => 'invalid');
            }else{
                return Response::json(array(
                    'message' => $msg,
                    'status' => 'Error',
                    'statuscode' => 434
                ),
                    200);
            }
        }
    }
    public function termsandcondition(){
        $termsandcondition=Termsandcondition::all();
        return Response::json(array(
            'message' => 'Terms and Condition Fetched Successfully',
            'status' => 'Success',
            'statuscode' => 200
        ),200);

    }


    public function resetorderid(){
        $resid=Request::json('resid');


        $setorderid=order_details::where('outlet_id',$resid)->orderBy('created_at', 'desc')->get();
        $i=0;
        foreach($setorderid as $orderid){
            $suborder_id[]=$orderid->suborder_id;
            $i++;
        }

        $resetupdate=order_details::where('suborder_id',$suborder_id[0])->update(array('reset'=>'true'));

        if(count($setorderid)>0){
            return Response::json(array(
                'message' => 'Order id reset successfully',
                'status' => 'success',
                'statuscode' => 200,
                'type'=>'reset'
            ),200);
        }else{
            return Response::json(array(
                'message' => "You don't have any orders",
                'status' => 'error',
                'statuscode' => 434,
                'type'=>'reset'
            ),200);
        }


    }

    public function generatereport(){
        $startdate=date('Y-m-d 00:00:00',strtotime(Request::json('start_date')));
        $enddate=date('Y-m-d 12:00:00',strtotime(Request::json('end_date')));
        $userid=Request::json('userid');

        $getrestaurant=Outlet::where('owner_id',$userid)->get();

        $getorders=order_details::where('outlet_id',$getrestaurant[0]->id)->whereBetween('created_at',array($startdate,$enddate))->get();
        $result=array();

        $i=0;
        $totalprice='';
        $ordersummary=array();

        foreach($getorders as $orderdetails){
            $name=$orderdetails->name;

            $orderitem=DB::table('order_items')->where('order_id',$orderdetails->order_id)->get();
            foreach($orderitem as $orderit){
                $menuitemname=DB::table('menus')->where('id',$orderit->item_id)->get();
                $result[$i]['Outlet Name']=$getrestaurant[0]->name;
                $result[$i]['Order Id']=$orderdetails->suborder_id;

                if(sizeof($menuitemname) && isset($menuitemname[0]->item) && $menuitemname[0]->item!=''){
                    $result[$i]['Item Name']=$menuitemname[0]->item;
                }
                $result[$i]['Item Quantity']=$orderit->item_quantity;
                $result[$i]['Item Price']=$orderit->item_price;
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

        ob_end_clean();
        ob_start();
        Excel::create('pikal_report', function($excel) use($result,$totalprice) {
            $excel->sheet('Sheet1', function($sheet) use($result,$totalprice) {
                $sheet->setOrientation('landscape');
                $sheet->fromArray($result);
                $sheet->appendRow(array('Grand Total',$totalprice));
            });
        })->store('xls');
        $restaurant_name=$getrestaurant[0]->name;
        Mail::send('emails.dailyreport', [], function ($message){
            $message->from('we@pikal.io', 'Pikal');
            $message->to('parag@savitriya.com', 'Parag');
            $message->subject('Pikal Report');
            $message->attach(app_path().'/../storage/exports/pikal_report.xls', ['as' => 'pikal_report.xls', 'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ]);
        });
        //Queue::push('App\Commands\GenerateReport@generatereport', array('restaurant_name'=>$restaurant_name));

        return Response::json(array(
            'message' => 'Report sent successfully',
            'status' => 'success',
            'statuscode' => 200,
            'type'=>'report'
        ),200);
    }

    public function updatemenuitem(){
        $resid=Request::json('resid');
        $menuitemid=Request::json('itemid');
        $active=Request::json('itemactive');
        $itemname=Request::json('itemname');
        $price=Request::json('itemPrice');

        $update=array();


        $menu=DB::table('menus');
        if(isset($active)){
            $update['active']=$active;

        }
        if(isset($itemname)){
            $update['item']=$itemname;

        }
        if(isset($price)){
            $update['price']=$price;

        }



        $affectedRows=$menu->where('id',$menuitemid)->where('outlet_id',$resid)->update($update);

        return Response::json(array(
            'message' => 'Menu Updated Successfully',
            'status' => 'success',
            'statuscode' => 200
        ),200);

    }

    public function cancellationreason(){

        $array=array();
        $resid=Request::json('resid');
        $i=0;
        $cancel=CancellationReason::where('outlet_id',$resid)->get();
        foreach($cancel as $can){

            $array[$i]['reason']=$can->reason_of_cancellation;
            $i++;
        }
        return Response::json(array(
            'message' => 'Cancellation Reason Fetched Successfully',
            'status' => 'success',
            'statuscode' => 200,
            'cancelreasons'=>$array
        ),200);
    }


    public function ordercancellation(){
        $resid=Request::json('resid');
        $order_id=Request::json('order_id');
        $neworder_id=Request::json('neworder_id');
        $reason=Request::json('reason');
        $orderdate=Request::json('order_date');

        order_details::where('order_id',$neworder_id)->update(array('cancelorder'=>1));
        $ordercancellation =new OrderCancellation();
        $ordercancellation->outlet_id=$resid;
        $ordercancellation->order_id=$neworder_id;
        $ordercancellation->reason=$reason;
       // $ordercancellation->order_date=$orderdate;
        $ordercancellation->save();
        $orderdetails=order_details::where('order_id',$neworder_id)->get();
        $cancel=array('device_id'=>$orderdetails[0]->device_id,'order_id'=>$order_id,'reason'=>$reason,'created_at'=>$orderdate);
        Queue::push('App\Commands\CancelOrderNotification@getcancelorder', array('cancellation'=>$cancel));
        return Response::json(array(
            'message' => 'Order Cancelled Successfully',
            'status' => 'success',
            'statuscode' => 200,
            'type'=>'cancelorder',
            'suborder_id'=>$order_id,
            'created_at'=>$orderdate
        ),200);
    }


    public function printsummary(){

        $getorderprintsuborder_id=Request::json('suborder_id');
        $getorderordercreated_at=Request::json('order_created_at');

        $print=DB::table('print_summary')->where('order_id',$getorderprintsuborder_id)->where('order_created_at',$getorderordercreated_at)->first();
        if(count($print)>0){
            $print_count=$print->print_number+1;
            DB::table('print_summary')->where('order_id',$getorderprintsuborder_id)->where('order_created_at',$getorderordercreated_at)->update(array('print_number'=>$print_count,'order_created_at'=>$getorderordercreated_at));
        }else{
            $print_count=1;
            $printsummary=new Printsummary();
            $printsummary->print_number=$print_count;
            $printsummary->order_id=$getorderprintsuborder_id;
            $printsummary->order_created_at=$getorderordercreated_at;
            $printsummary->save();

        }
        return Response::json(array(
            'message' => 'Print summary added successfully',
            'status' => 'success',
            'statuscode' => 200,
            'printcount'=>$print_count,
            'suborder_id'=>$getorderprintsuborder_id,
            'created_at'=>$getorderordercreated_at

        ),200);


    }

    public function addreviews(){
        $usermobile=Request::json('user_mobile');
        $user_name=Request::json('user_name');
        $rating=Request::json('rating');
        $fav=Request::json('fav');
        $comment=Request::json('comment');
        $resid=Request::json('res_id');



        $review=new Reviews();
        $review->mobile=$usermobile;
        $review->username=$user_name;
        $review->rating=$rating;
        $review->fav=$fav;
        $review->resid=$resid;
        $review->review=$comment;
        $review->save();

        $reviews=Reviews::where('resid',$resid)->get();

        foreach($reviews as $review1){
            $review1->formated_created_at=date("F j, Y g:i a",strtotime($review->created_at));
        }
        // $reviews=Reviews::skip($start)->take($limit);


        return Response::json(array(
            'message' => 'Reviews added successfully',
            'status' => 'success',
            'statuscode' => 200,
            'review'=>$reviews,
            'addreview'=>'true'

        ),200);


    }

    public function getreviews(){

        $resid=Request::json('res_id');
        $mobile_number=Request::json('user_mobile_number');

        $reviews=Reviews::where('resid',$resid)->get();
        $rev = array();
        if(isset($mobile_number) && $mobile_number!=""){
            $rev=order_details::where('outlet_id',$resid)->where('user_mobile_number',$mobile_number)->get();
        }else{
            $ordercount=0;
        }
        if(sizeof($rev)>0){
            $ordercount=1;
        }else{
            $ordercount=0;
        }
        foreach($reviews as $review){
            $review->formated_created_at=date("F j, Y g:i a",strtotime($review->created_at));
        }

        return Response::json(array(
            'message' => 'Reviews fetched successfully',
            'status' => 'success',
            'statuscode' => 200,
            'review'=>$reviews,
            'ordercount'=>$ordercount,
            'addreview'=>'false'

        ),200);


    }

    public function addlike(){

        $resid=Request::json('res_id');
        $itemid=Request::json('item_id');
        $like=Request::json('like');
        $mobilenumber=Request::json('user_mobile_number');
        $pastlike=Itemreview::where('item_id',$itemid)->where('user_mobile_number',$mobilenumber)->orderby('created_at', 'desc')->first();
        $menulike=Menu::where('id',$itemid)->first();
        if($like==0){
            $alllikes=$menulike->like-1;
        }else{
            $alllikes=$menulike->like+1;
        }
        if(sizeof($pastlike)>0){
            if($pastlike->like==0){
                $totallikes=1;

            }else{
                $totallikes=0;

            }
        }
        else{
            $totallikes=1;
            $alllikes=$menulike->like+1;
        }

        Menu::where('id',$itemid)->update(array('like'=>$alllikes));

        $itemre=new Itemreview();
        $itemre->res_id=$resid;
        $itemre->item_id=$itemid;
        $itemre->like=$totallikes;
        $itemre->user_mobile_number=$mobilenumber;
        $itemre->save();

        return Response::json(array(
            'message' => 'Item liked successfully',
            'status' => 'success',
            'statuscode' => 200,
            'totallikes'=>$totallikes
        ),200);


    }


    public function addrecipes(){
        $outlet_id=Request::json('outlet_id');
        $title=Request::json('title');
        $recipe=Request::json('recipe');
        $ingrediants=Request::json('ingrediants');
        $shop_url=Request::json('shop_url');
        $ingrediants_url=Request::json('ingrediant_url');
        $owner=Request::json('owner');

        $getownerid=Owner::where('user_name',$owner)->first();


        $recipes=new Recipe();
        $recipes->owner_id=$getownerid->id;
        $recipes->outlet_id=$outlet_id;
        $recipes->title=$title;
        $recipes->ingrediants=$ingrediants;
        $recipes->recipes=$recipe;
        $recipes->shop_url=$shop_url;
        $recipes->ingrediants_url=$ingrediants_url;
        $success = $recipes->save();

        $allrecipe=Recipe::all();
        foreach($allrecipe as $recipe){
            $recipe->formated_created_at=date("F j, Y g:i a",strtotime($recipe->created_at));
        }
        return Response::json(array(
            'message' => 'Recipe Added successfully',
            'status' => 'success',
            'statuscode' => 200,
            'recipe'=>$allrecipe
        ),200);



    }


    public function getrecipes(){

        //$owner=Request::json('owner');
        //$getownerid=Owner::where('user_name',$owner)->first();

        $allrecipe=Recipe::all();
        foreach($allrecipe as $recipe){
            $recipe->formated_created_at=date("F j, Y g:i a",strtotime($recipe->created_at));
        }
        return Response::json(array(
            'message' => 'All Recipes Listed successfully',
            'status' => 'success',
            'statuscode' => 200,
            'recipe'=>$allrecipe
        ),200);



    }
    public function webcart(){
        $itemcount=Request::get('count');
        $item=Request::get('item');
        $itemarray=array();
        array_push($itemarray,$item);
        $_SESSION['cart']=$itemarray;
        print_r($itemarray);exit;



    }

    public static function get_order_type($type){
        $types=array('retail'=>'Retail','take_away'=>'Take Away','dine_in'=>'Dine In','home_delivery'=>'Home Delivery','meal_packs'=>'Meal Packs');
        return $types[$type];
    }

    public function pastorders(){

        $restaurant_id=Request::json('resid');


        $maxdt=[];
        array_push($maxdt,Carbon::now());

        $retname=Outlet::find($restaurant_id);
        $star=[];
        $orders=$retname->orderdetail()->where('status','=','delivered')->where('cancelorder', '!=', 1)
            ->where('orders.created_at','>=', Carbon::now()->startOfDay())
            ->where('orders.created_at','<=', Carbon::now()->endOfDay())
            ->orderBy('created_at', 'asc')->get();

        $totalprice=[];
        $rating=[];
        $uniquepricearray=[];
        $othercount=[];
        $items=[];

        if(count($orders) > 0)
        {
            $forrating=$retname->orderdetail()->where('status','=','delivered')->orderBy('created_at', 'desc')->get();
            $otheroutletordercount=DB::table('orders')->where('status','=','delivered')->where('outlet_id','!=',$restaurant_id)->get();
            $uniqueprice=DB::table('orders')->where('status','=','delivered')->get();
            if(sizeof($uniqueprice)>0){
                foreach($uniqueprice as $k1=>$v1){
                    if(!array_key_exists($v1->user_mobile_number,$uniquepricearray)){
                        //array_push($uniquepricearray,array($order->user_mobile_number=>number_format($totalpr, 2, '.', '')));
                        $uniquepricearray[$v1->user_mobile_number]=$v1->totalprice;
                    }
                    if($uniquepricearray[$v1->user_mobile_number]<$v1->totalprice){
                        $uniquepricearray[$v1->user_mobile_number]=$v1->totalprice;

                    }

                }
            }
            if(sizeof($otheroutletordercount)>0){
                foreach($otheroutletordercount as $k=>$v){
                    if(!array_key_exists($v->user_mobile_number,$rating)){
                        $othercount[$v->user_mobile_number]=1;
                    }else{
                        $othercount[$v->user_mobile_number]=$othercount[$v->user_mobile_number]+1;
                    }
                }
            }
            if(sizeof($forrating)>0){
                foreach($forrating as $key=>$value){
                    $item=OrderItem::where('order_id',$value->order_id)->get();
                    $totalpr=0;
                    $it=array();
                    foreach($item as $t){

                        $itemnew=$t->menuitem;
                        if(isset($itemnew['price']) && $itemnew['price']!='') {
                            $totalpr+=$t->item_quantity*$itemnew['price'];
                        }
                        else {
                            $totalpr+=0;
                        }

                    }

                    if(!array_key_exists($value->user_mobile_number,$rating)){
                        $rating[$value->user_mobile_number]=1;
                    }else{
                        $rating[$value->user_mobile_number]=$rating[$value->user_mobile_number]+1;
                    }
                    //$totalprice[$order->order_id]=$totalpr;

                    // echo in_array($order->user_mobile_number,$uniquepricearray);

                }
            }

            foreach($orders as $key=>$order)
            {
                $printcount=Printsummary::where('order_id',$order->suborder_id)->where('order_created_at',$order->created_at)->first();
                if(sizeof($printcount)>0){
                    $order->printcount=$printcount['print_number'];
                }else{
                    $order->printcount=0;
                }

                if(isset($rating[$order->user_mobile_number])){
                    $order->rating=$rating[$order->user_mobile_number];
                }else{
                    $order->rating='';
                }
                if(isset($othercount[$order->user_mobile_number])){
                    $order->othercount=$othercount[$order->user_mobile_number];
                }else{
                    $order->othercount='';
                }
                if(isset($uniquepricearray[$order->user_mobile_number])) {
                    $order->maxprice = $uniquepricearray[$order->user_mobile_number];
                }else{
                    $order->maxprice='';
                }
                $item=OrderItem::where('order_id',$order->order_id)->get();
                $totalpr=0;
                $it=array();
                foreach($item as $t){

                    $itemnew=$t->menuitem;
                    $madt=date("Y-m-d H:i:s",strtotime($order->created_at));
                    array_push($it,array("item"=>$itemnew->item,"quantity"=>$t->item_quantity,"price"=>$itemnew->price,"suborder_id"=>$order->suborder_id,"created_at"=>$madt,"item_options"=>$t->item_options,"item_options_price"=>$t->item_options_price));
                    if(isset($itemnew['price']) && $itemnew['price']!='') {
                        $totalpr+=$t->item_quantity*$itemnew['price'];
                    }
                    else {
                        $totalpr+=0;
                    }

                }
                array_push($items,$it);
                $star[$order->order_id]=DB::table('orders')->where('user_mobile_number',$order->phone_number)->count();
                //$totalprice[$order->order_id]=$totalpr;
                array_push($totalprice, array($order->suborder_id => $order->totalcost_afterdiscount));


                if($key==0){$maxdt[0]=$order->created_at;}
            }
        }


        $a= Response::json(array(
            'message' => 'Valid User',
            'statuscode' => 200,
            'status'=>'Success',
            'orders'=>$orders->toArray(),
            'resid'=>(string) $restaurant_id,
            'items'=>$items,
            'total'=>$totalprice,
            'maxdt'=>$maxdt,
            'rating'=>$rating,
            'maxprice'=>$uniquepricearray,
            'othercount'=>$othercount,
            'outletname'=>$retname->name

        ),
            200);

        return $a;
    }


    public function syncorderadd(Request $request){


        $orders=Request::json('data');

        $unprocessed_orders = array();
        $serverids = array();
        $order_arr = array();
        $ot_id = $orders[0]['restaurant_id'];

        for($i=0;$i<count($orders);$i++) {
            $order=$orders[$i];

            $array=array();
            // $orders_count=DB::table('orders')->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->where('order_unique_id',$order['orderuniqueid'])->count();

            $orders_count = order_details::where('order_unique_id',$order['orderuniqueid'])->count();

            if($orders_count == 0) {
                $Outlet = Outlet::Outletbyid($order['restaurant_id']);

                if(isset($Outlet[0]->service_tax)){
                    $service_tax=($Outlet[0]->service_tax * $order['total_price'])/100;
                }
                else{
                    $service_tax=0;
                }

                if (isset($Outlet)) {

                    $startingstatus = status::getallstatusofOutlet($order['restaurant_id']);
                    $lastindex = count($startingstatus) - 1;

                    if (isset($startingstatus)) {
                        if ( isset($order['order_from'])) {

                            if ($order['order_from'] == "pro") {
                                $status = $startingstatus[$lastindex]->status;
                            } else {
                                $status = $startingstatus[0]->status;
                            }

                        } else {

                            if ($order['order_type'] == "dine_in") {
                                $status = $startingstatus[$lastindex]->status;
                            } else {
                                $status = $startingstatus[0]->status;
                            }

                        }

                    } else {
                        $status = '';
                    }

                    //$order_ids = order_details::getorderid();
                    $order_ids = $order['order_id_server'];

                    //$suborder_id = order_details::getorderidofrestaurant($order['restaurant_id']);
                    $suborder_id = 1;
                    //$invoice_no = $this->getLastInvoiceNo($order['order_type'],$order['restaurant_id']);
                    $invoice_no = '';

                    $tempdata['local_id'] = $order['primary_id'];
                    $tempdata['suborder_id'] = $suborder_id;
                    array_push($serverids, $tempdata);

                    $a = $Outlet->pluck('code');

                    //$invoice_no = order_details::generateinvoicenumber($order['restaurant_id'],$suborder_id);
                    $saveorder = order_details::insertorderdetails($a, $order_ids, $order, $status, $suborder_id,$invoice_no,$service_tax);
                    $inv_no = NULL;
                    if(isset($order['invoice'])){
                        $inv_no = $order['invoice'];
                    }
                    foreach ($order['menu_item'] as $asd) {
                        $orderid = OrderItem::insertmenuitemoforders($saveorder['id'], $asd,$order['restaurant_id'],$inv_no);
                    }

                    if ( isset($order['feedback']) && $order['feedback'] != '' ) {

                        $owner_obj = Owner::where('user_name',$order['name'])->first();
                        if ( isset($owner_obj) && sizeof($owner_obj) > 0 ) {

                            $f_back = json_decode($order['feedback']);

                            if ( isset($f_back) && sizeof($f_back) > 0 ) {

                                foreach ( $f_back as $feedback ) {

                                    //check feedback value is set or not
                                    if ( $feedback->value <= 0 ) {
                                        continue;
                                    }

                                    $fb_obj = new CustomerFeedback();
                                    $fb_obj->order_id = $saveorder['id'];
                                    $fb_obj->outlet_id = $order['restaurant_id'];
                                    $fb_obj->question_id = $feedback->id;
                                    $fb_obj->value = $feedback->value;
                                    $fb_obj->created_by = $owner_obj->id;
                                    $fb_obj->updated_by = $owner_obj->id;
                                    $fb_obj->save();
                                }

                            }

                        }

                    }

                    //check table merged or not
                    if ( isset($order['merged_with']) && isset($order['ref_order_unique_ids']) && $order['ref_order_unique_ids'] != '' ) {

                        $kot_unique_ids = explode(',',$order['ref_order_unique_ids']);
                        if ( sizeof($kot_unique_ids) > 0 ) {
                            //upadte merged table kot with closed table
                            foreach ( $kot_unique_ids as $k_id ) {
                                Kot::where('order_unique_id',$k_id)->update(['order_unique_id'=>$order['orderuniqueid']]);
                            }
                        }
                    }

                    //make table as close and update status of kot
                    Kot::where('order_unique_id',$order['orderuniqueid'])->update(['status'=>'close']);

                    // Queue::push('App\Commands\MailNotification@getorderdetails', array('orderdetails'=>$saveorder));

                    $order_arr[] = order_details::select('invoice','invoice_no','suborder_id','order_unique_id')->where('order_id',$saveorder['id'])->first();

                    if ( $order['invoice'] == '') {
                        $unprocessed_orders[] = $saveorder['id'];
                    }
                }

            } else {

                //$suborder_id = order_details::getorderidofrestaurant($order['restaurant_id']);
                $suborder_id = 1;

                if ( isset($order['cancelorder']) && $order['cancelorder'] == 1 ) {
                    $order['cancelorder'] = 1;
                } else {
                    $order['cancelorder'] = 0;
                }

                $discount_value = 0.00;
                if ( isset($order['item_discount_value']) && sizeof($order['item_discount_value']) > 0 ) {
                    $discount_value = $order['item_discount_value'];
                }

                //update payment option
                $update = order_details::where('order_unique_id',$order['orderuniqueid'])
                                        ->update(array(
                                                        'cancelorder'=>$order['cancelorder'],
                                                        'tax_type'=>$order['tax_type'],
                                                        'invoice_no'=>$order['invoice'],
                                                        'invoice'=>$order['invoice_id'],
                                                        'suborder_id'=>$suborder_id,
                                                        'payment_status'=>$order['order_payment_status'],
                                                        'payment_option_id' => $order['payment_provider_id'],
                                                        'source_id'=>$order['source_type'],
                                                        'totalprice'=>$order['total_price'],
                                                        'totalcost_afterdiscount'=>$order['totalcost_afterdiscount'],
                                                        'discount_value'=>$order['discounted_value'],
                                                        'discount_type'=>isset($order['discount_type'])?$order['discount_type']:NULL,
                                                        'delivery_charge'=>isset($order['delivery_charge'])?$order['delivery_charge']:0,
                                                        'round_off'=>$order['round_off'],
                                                        'item_discount_value'=>$discount_value
                                        ));

                //update itemwise tax and discount if process from pastorder
                foreach ($order['menu_item'] as $asd) {

                    OrderItem::where('item_unique_id', $asd['item_unique_id'])->update(array(
                                                                                        'tax_slab'=>$asd['tax_slab'],
                                                                                        'item_discount'=>$asd['discount']
                                                                                        ));

                }

                $order_id = order_details::where('order_unique_id',$order['orderuniqueid'])->pluck('order_id');

                if ( $order['cancelorder'] == 1 ) {

                    if ( !isset($order['user_id']) || $order['user_id'] == 0 ) {
                        $user_arr = Owner::where('user_name',$order['name'])->find();
                        if ( isset($user_arr) && sizeof($user_arr) > 0 ) {
                            $order['user_id'] = $user_arr->id;
                        }
                    }

                    if ( !isset($order['order_cancel_reason']) ) {
                        $order['order_cancel_reason'] = 'Cancelled';
                    }

                    $check_cancel = OrderCancellation::where('order_id',$order_id)->first();

                    if ( sizeof($check_cancel) == 0 ) {
                        $order_cancel_mapper = new OrderCancellation();
                        $order_cancel_mapper->outlet_id = $order['restaurant_id'];
                        $order_cancel_mapper->order_id = $order_id;
                        $order_cancel_mapper->reason = $order['order_cancel_reason'];
                        $order_cancel_mapper->created_by = $order['user_id'];
                        $order_cancel_mapper->save();
                    }

                } else {

                    //delte old payment modes
                    OrderPaymentMode::where('order_id',$order_id)->delete();

                    //update payment modes
                    if ( isset($order['payment_modes']) && sizeof($order['payment_modes']) > 0 ) {

                        foreach ( $order['payment_modes'] as $mode ) {

                            $py_modes = new OrderPaymentMode();
                            $py_modes->order_id = $order_id;
                            $py_modes->payment_option_id = $mode['mode_id'];
                            $py_modes->source_id = $mode['source_id'];
                            $py_modes->transaction_id = $mode['transaction_id'];
                            $py_modes->amount = $mode['amount'];
                            $py_modes->save();

                        }

                    } else {

                        //when old app order sync till 98 version
                        $py_modes = new OrderPaymentMode();
                        $py_modes->order_id = $order_id;
                        $py_modes->payment_option_id = $order['payment_provider_id'];
                        $py_modes->source_id = $order['source_type'];
                        $py_modes->transaction_id = $order['payment_txn_identifier'];
                        $py_modes->amount = $order['total_price'];
                        $py_modes->save();

                    }

                }

                //invoice_detail::where('order_id',$order_check->order_id)->update(['taxes'=>$order['tax_type'],'total'=>$order['total_price'],'round_off'=>$order['round_off'],'discount'=>$order['discounted_value'],'sub_total'=>$order['totalcost_afterdiscount']]);

                $tempdata['local_id'] = $order['primary_id'];
                $tempdata['suborder_id'] = $suborder_id;
                array_push($serverids, $tempdata);

                $order_arr[] = order_details::select('invoice','invoice_no','suborder_id','order_unique_id')->where('order_unique_id',$order['orderuniqueid'])->first();
            }
        }

        //if unprocessed order is available than send call to biller
        if ( sizeof($unprocessed_orders) > 0 ) {

            //send order to firebase for displaying in biller screen
            $fields = array();
            $fields['outlet_id'] = $ot_id;
            $fields['order_type'] = 'biller';
            $fields['action'] = 'GenerateBill';

            //Utils::sendOrderToFirebase($fields);


        }

        return Response::json(array(
            'message' => 'Order Placed Successfully.Go To My Order To Check Your Status',
            'status' => 'success',
            'statuscode' => 200,
            'server_ids' => $serverids,
            'sync_orders'=>$order_arr
        ),
            200);

    }


    public function closeCounter(Request $request){

        $outlet_id=Request::json('res_id');
        $amount=Request::json('total');

        $total_cash = Request::json('total_cash');
        $total_online = Request::json('total_online');
        $total_cheque = Request::json('total_cheque');

        $amount_byuser=Request::json('total_byuser')?Request::json('total_byuser'):0;
        //$amount_fromdb=Request::json('total_fromdb')?Request::json('total_fromdb'):0;
        $remarks=Request::json('remarks');

        //check closing hours for set report date
        $hour = date("H");
        if ( $hour < 4 ) {
            $report_date = date('Y-m-d',strtotime('-1 day'));
            $start_date= date('Y-m-d',strtotime('-1 day'))." 04:01:00";
        } else {
            $report_date = date('Y-m-d');
            $start_date = date('Y-m-d')." 04:01:00";
        }

        $end_date= date('Y-m-d H:i:s');

        $amount_fromdb = order_details::where('orders.table_start_date', '>=', $start_date)
                                            ->where('orders.table_end_date', '<=', $end_date)
                                            ->where('outlet_id', '=', $outlet_id)
                                            ->where('orders.invoice_no', "!=", '')
                                            ->where('cancelorder', '!=', 1);


        $result = SendCloseCounterStatus::whereDate('report_date','=',$report_date)
                                            ->where('outlet_id',$outlet_id)->first();

        $last_amount = 0;
        if(isset($result) && sizeof($result)>0){
            $status = SendCloseCounterStatus::find($result->id);
            $last_amount = $status->total_from_db;
            $status->total_cash = $total_cash;
            $status->total_online = $total_online;
            $status->total_cheque = $total_cheque;
            $status->total_from_user = $amount_byuser;
            $status->total_from_db = $amount_fromdb->sum('orders.totalprice');
            $status->remarks = $remarks;
            $status->is_send = 0;
            $status->save();
        }else {
            $sccs = new SendCloseCounterStatus();
            $sccs->outlet_id = $outlet_id;
            $sccs->start_date = $start_date;
            $sccs->close_date = $end_date;
            $sccs->report_date = $report_date;
            $sccs->amount = $amount;
            $sccs->total_cash = $total_cash;
            $sccs->total_online = $total_online;
            $sccs->total_cheque = $total_cheque;
            $sccs->total_from_user = $amount_byuser;
            $sccs->total_from_db = $amount_fromdb->sum('orders.totalprice');
            $sccs->remarks = $remarks;
            $sccs->save();
        }

        return Response::json(array(
            'message' => 'Counter closed successfully',
            'status' => 'success',
            'statuscode' => 200
        ),200);

        //  $emails=explode(',',$outlet->report_emails);

        /*Queue::push('App\Commands\ReportsMail@sendmails', array('outlet_id'=>$outlet_id,'amount_byuser'=>$amount_byuser,'amount_fromdb'=>$amount_fromdb,'total'=>$amount,"total_hours"=>$total_hours,"start_time"=>$start_time,"end_time"=>$end_time,'start_date'=>Request::json('start_date'),'end_date'=>Request::json('close_date'),'remark'=>$remarks));*/

    }

    public function orderUpdate(Request $request) {

        $orders=Request::json('data');

        $serverids=array();$order_arr = array();
        for($i=0;$i<count($orders);$i++) {

            $order=$orders[$i];
            $order_check = order_details::where('order_unique_id',$order['orderuniqueid'])->first();

            if ( $order_check['invoice_no'] == '' ) {

                if ( isset($order_check) && sizeof($order_check) > 0 ) {

                    if ( $order['invoice'] != '') {

                        //$invoice_no = $this->getLastInvoiceNo($order['order_type'],$order_check['outlet_id']);
                        //$inv_arr = explode("_",$invoice_no);

                        order_details::where('order_unique_id',$order['orderuniqueid'])->update([
                            'invoice_no'=>$order['invoice'],//$inv_arr[1],
                            'invoice'=>$order['invoice_id'],//$inv_arr[0],
                            'discount_value'=>$order['discounted_value'],
                            'user_mobile_number'=>$order['mobile_number'],
                            'tax_type'=>$order['tax_type'],
                            'totalprice'=>$order['total_price'],
                            'referance_id'=>$order['payment_txn_identifier'],
                            'totalcost_afterdiscount'=>$order['totalcost_afterdiscount'],
                            'order_type'=>$order['order_type'],
                            'round_off'=>$order['round_off'],
                            'paid_type'=>$order['paid_type'],
                            'payment_option_id'=>$order['payment_provider_id'],
                            'source_id'=>$order['source_type'],
                            'payment_status'=>$order['order_payment_status'],
                            'cancelorder'=>$order['cancelorder']
                            //'address'=>$order['address']
                        ]);


                        /*invoice_detail::where('order_id',$order_check['order_id'])->update([
                            'round_off'=>$order['round_off'],
                            'discount'=>$order['discounted_value'],
                            'taxes'=>$order['tax_type'],
                            'total'=>$order['total_price'],
                            'sub_total'=>$order['totalcost_afterdiscount']
                        ]);*/

                        //make table as close and update status of kot
                        Kot::where('order_unique_id',$order['orderuniqueid'])->update(['status'=>'close']);

                        //Tables::where('outlet_id',$order['restaurant_id'])->where('table_no',$order['table'])->update(['status'=>0,'occupied_by'=>NULL]);

                        $outlet_setting = Outlet::find($order['restaurant_id']);
                        if ( $outlet_setting->stock_auto_decrement == '1') {
                            $default_location = Location::where('outlet_id',$order['restaurant_id'])->where('default_location',1)->first();
                            if ( isset($default_location) && sizeof($default_location) > 0 ) {
                                foreach ($order['menu_item'] as $asd) {
                                    if ( isset($asd['item_id']) && $asd['item_id'] != 0 ) {
                                        $decrease_stock = StocksController::onSellDecreaseStock( array('item_id'=>$asd['item_id'],'quantity'=>$asd['quantity'],'order_id'=>$order_check->order_id), $default_location->id, $default_location->created_by,'manual' );
                                    }
                                }

                            }
                        }

                    }

                }

            }

            $order_arr[] = order_details::select('invoice','invoice_no','suborder_id','order_unique_id')->where('order_unique_id',$order['orderuniqueid'])->first();

        }

        return Response::json(array(
            'message' => 'Order Placed Successfully',
            'status' => 'success',
            'statuscode' => 200,
            'sync_orders'=>$order_arr,

        ),200);
    }

    public function getLastInvoiceNo( $order_type1 = NULL,$res_id1 = NULL ) {

        if ( $order_type1 != NULL && $res_id1 != NULL ) {
            $ord_type = $order_type1;
            $res_id = $res_id1;
        } else {
            $ord_type = Request::json('order_type');
            $res_id = Request::json('res_id');
        }

        $outlet = Outlet::find($res_id);

        $invoice_number = '';
        if ( isset($outlet) && sizeof($outlet) > 0 ) {
            $condition = '1=1';
            $to = (new Carbon(date('Y-m-d')))->endOfDay();
            if ( OutletSetting::checkAppSetting($res_id,"orderNoReset") == 1) {
                $from = (new Carbon(date('Y-m-d')))->startOfDay();
                $condition = "orders.table_start_date BETWEEN '$from' AND '$to'";
            } else {
                $from = (new Carbon(date('Y-m-d',strtotime('-2 days'))))->startOfDay();
                $condition = "orders.table_start_date BETWEEN '$from' AND '$to'";
            }

            //check dinein prefix is set or not
            $code = $outlet->code;
            $prefix_check = json_decode($outlet->invoice_prefix);
            if ( isset($prefix_check) && sizeof($prefix_check) > 0 ) {
                $condition .=" && order_type='$ord_type'";
                $code = $prefix_check->$ord_type;
            }


            //check last invoice no.
            $check_invoice_no = order_details::where('outlet_id',$res_id)
                ->whereRaw($condition)
                ->get();

            $inv_digit = $outlet->invoice_digit;

            if ( isset($check_invoice_no) && sizeof($check_invoice_no) > 0 ) {
                $max_id = 0;
                foreach( $check_invoice_no as $inv_record ) {
                    $inv = (integer)substr($inv_record->invoice_no, -1*$inv_digit);
                    if (  $inv > $max_id ) {
                        $max_id = $inv;
                    }

                }

                if ( $max_id != 0 ) {
                    $invoice_no = $max_id + 1;
                } else {
                    $invoice_no = 1;
                }

            } else {
                $invoice_no = 1;
            }

            $in_date = '';
            if ( OutletSetting::checkAppSetting($res_id,"invoiceDate") == 1) {
                $in_date = date('Ymd');
            }

            $invoice_number = $code.''.$in_date.''.str_pad($invoice_no,$inv_digit,0,STR_PAD_LEFT);
        }

        if ( isset($order_type1) && $order_type1 != NULL && isset($res_id1) && $res_id1 != NULL ) {
            return $invoice_no."_".$invoice_number;
        } else {
            return Response::json(array(
                'invoice_no'=>$invoice_number,
                'status' => 'success',
                'statuscode' => 200
            ),200);
        }

    }

    public function checkConnection() {
        return Response::json(array(
            'status' => 'success',
            'statuscode' => 200
        ),200);
    }

    public function settingsAndPrinters() {

        $outlet_id = Request::json('outlet_id');
        $user_id = Request::json('user_id');
        $user = Owner::find($user_id);
        if(isset($outlet_id) && $outlet_id!='') {
            $outlet_settings = OutletSetting::getApiOutletSettings($outlet_id);
            $ot_printer = Outlet::find($outlet_id);

            //send default printers
            $printers = Printer::getOutletPrinters($ot_printer->printer,$outlet_id);

            //$printers = array();$arr_count = 0;
            if ( isset($ot_printer->printer) && $ot_printer->printer != '' ) {

                //outlet menu
                $menu=Apicontroller::getnewMenu($outlet_id,$ot_printer->printer);

                //locations
                $stock_location = Location::where('outlet_id',$outlet_id)->get();


                //stock level object
                $loc_arr = array();$stock_level = array();
                if( isset($stock_location) && sizeof($stock_location) > 0 ) {
                    foreach( $stock_location as $loc ) {
                        $loc_arr[] = $loc->id;
                    }

                    //stock_level array
                    $stock_level = StockLevel::whereIn('location_id',$loc_arr)->get();
                }

                $menu_tutle_arr = OutletMapper::getCategoryOrder($user_id);

                //Units
                $units = Unit::all();

                //Item Attributes
                $ot_attributes = OutletItemAttributesMapper::getOutletAttributes($outlet_id);

                //outlet authorised user
                $auth_user_arr = array();$k=0;
                if ( isset($ot_printer->authorised_users) && $ot_printer->authorised_users != '') {
                    $a_users = explode(',',$ot_printer->authorised_users);
                    if ( isset($a_users) && sizeof($a_users) > 0 ) {
                        foreach( $a_users as $key=>$auth ) {
                            $auth_user = Owner::find($auth);
                            $auth_user_arr[$k]['id']= $auth_user->id;
                            $auth_user_arr[$k]['name']= $auth_user->user_name;
                            $k++;
                        }
                    }
                }

                $feedback=DB::table('feedback')->where('outlet_id','=',$outlet_id)->get();

                //sources
                $source_list = Sources::getSourceArray($outlet_id);

                //cancellation reasons
                $reasons = CancellationReason::where('outlet_id',$outlet_id)
                                            ->select('id','reason_of_cancellation as reason')
                                            ->get();

                //all locations
                $all_location = array();
                if( isset($user->created_by) && $user->created_by != '') {
                    $all_location = Location::where('created_by',$user->created_by)->get();
                } else {
                    $all_location = Location::where('created_by',$user->id)->get();
                }
                $all_location = $stock_location;
                $user_arr = array();

//                if ( isset($user->created_by) && $user->created_by != NULL ) {
//                    $user_arr = Owner::where('created_by', $user->created_by)->orWhere('id',$user->created_by)->get();
//                } else {
//                    $user_arr = Owner::where('id',$user->id)->orWhere('created_by',$user->id)->get();
//                }
                $user_arr = OutletMapper::leftJoin('owners','outlets_mapper.owner_id','=','owners.id')
                    ->where('outlets_mapper.outlet_id','=',$outlet_id)->get();

                //payment options
                $payment_options = PaymentOption::getOutletPaymentOption($outlet_id);


                //get expense category
                $exp_category = ExpenseCategory::getExpenseCategory($user->id);

                //Tables list
                $tables = Tables::getOutletTables($outlet_id);

                //get order place types
                $order_place_types = OrderPlaceType::getOrderPlaceType($outlet_id);

                //Delivery charge
                $delivery_charge = '';
                if ( isset($ot_printer->delivery_charge) && sizeof($ot_printer->delivery_charge) > 0 ) {
                    $delivery_charge = json_decode($ot_printer->delivery_charge);
                }

                //tax details
                $tax_details = '';
                if ( isset($ot_printer->tax_details) && sizeof($ot_printer->tax_details) > 0 ) {
                    $tax_details = json_decode($ot_printer->tax_details);
                }

                //owner order receive
                $order_receive = User::getOwnerOrderReceive($user->id,$outlet_id);

                //last order sequence
                $last_order_json = order_details::lastOrderSequence($outlet_id);

                //feedback questions
                $fb_question = FeedbackQuestion::getFbQuestions($outlet_id);

                //table levels
                $table_levels = TableLevel::getTableLevels($outlet_id);

                //bill template keys
                $keys = Utils::getBillTemplateKeys($outlet_id);
                unset($keys['']);

                $account_data = Account::find($user->account_id);
                $enable_inventory = isset($account_data->enable_inventory)?$account_data->enable_inventory:0;
                $enable_feedback = isset($account_data->enable_feedback)?$account_data->enable_feedback:0;

                //outlet item option groups
                $item_option_group = ItemOptionGroup::getOutletOptiongroups($outlet_id);

                return Response::json(array(
                    'userid'=>(string) $user->id,
                    'ownername'=>$user->user_name,
                    //'orders'=>$orders->toArray(),
                    'user_identifier'=> Owner::find($user->id)->user_identifier,
                    'resid'=>(string) $outlet_id,
                    'settings' => $outlet_settings,
                    'printers' => $printers,
                    'duplicate_kot_count'=>$ot_printer->duplicate_kot_count,
                    'menu_titles'=>$menu_tutle_arr,
                    'stock_levels'=>$stock_level,
                    'restmenu'=>$menu,
                    'units'=>$units,
                    'attributes'=>$ot_attributes,
                    'order_lable'=>$ot_printer->order_lable,
                    'token_lable'=>$ot_printer->token_lable,
                    'session_time'=>$ot_printer->session_time,
                    'order_number_increment'=>OutletSetting::checkAppSetting($outlet_id,"incrementOrderNo"),
                    'display_no_of_person'=>OutletSetting::checkAppSetting($outlet_id,"displayNoOfPerson"),
                    'bypass_process_bill'=>OutletSetting::checkAppSetting($outlet_id,"bypassProcessBill"),
                    'outletname'=>$ot_printer->name,
                    'invoice_title'=>$ot_printer->invoice_title,
                    'outlet_address'=>$ot_printer->address,
                    'contact_no'=>$ot_printer->contact_no,
                    'delivery_no'=>$ot_printer->delivery_numbers,
                    'upi'=>$ot_printer->upi,
                    'tin_number'=>$ot_printer->tinno,
                    'servicetax_number'=>$ot_printer->servicetax_no,
                    'service_tax'=>$ot_printer->taxes,
                    'vat'=>$ot_printer->vat,
                    'vat_date'=>$ot_printer->vat_date,
                    'outlet_code'=>$ot_printer->code,
                    'outlet_invoice_prefix'=>$ot_printer->invoice_prefix,
                    'invoice_date'=>OutletSetting::checkAppSetting($outlet_id,"invoiceDate"),
                    'order_no_reset'=>OutletSetting::checkAppSetting($outlet_id,"orderNoReset"),
                    'invoice_digit'=>$ot_printer->invoice_digit,
                    'default_tax'=>$ot_printer->default_taxes,
                    'auth_users'=>$auth_user_arr,
                    'feedback'=>$feedback,
                    'source'=>$source_list,
                    'reasons'=>$reasons,
                    'locations'=>$all_location,
                    'users'=>$user_arr,
                    'payment_options'=>$payment_options,
                    'expense_category'=>$exp_category,
                    'tables'=>$tables,
                    'table_levels'=>$table_levels,
                    'order_place_types'=>$order_place_types,
                    'delivery_charge'=>$delivery_charge,
                    'tax_details'=>$tax_details,
                    'order_receive'=>$order_receive,
                    'last_order'=>$last_order_json,
                    'feedback_questions'=>$fb_question,
                    'app_layout'=>$ot_printer->app_layout,
                    'dummy_text'=>$ot_printer->bill_template,
                    'bill_keys'=>$keys,
                    'custom_fields'=>$ot_printer->custom_bill_print_fields,
                    'status' => 'success',
                    'inventory'=>$enable_inventory==0?false:true,
                    'enable_feedback'=>$enable_feedback==0?false:true,
                    'item_option_group'=>$item_option_group,
                    'hotkey_config'=>$ot_printer->hotkey_config,
                    'statuscode' => 200
                ), 200);

            } else {

                return Response::json(array(
                    'msg'=>'Outlet printers not set',
                    'status' => 'error',
                    'statuscode' => 435
                ), 435);

            }




        }
        else{
            return Response::json(array(
                'status' => 'error',
                'msg' => 'Outlet not found.',
                'statuscode' => 404
            ), 404);
        }
    }

    public function getOnlineOrders() {

//      $order_id = Request::json('order_id');
        $order_unique_id = Request::json('order_unique_id');
        $items = Request::json('items');
        $order = array();$table_no ='';$person_no='';

        $order_detail = order_details::where('order_unique_id',$order_unique_id)->first();

        if ( isset($order_detail) && sizeof($order_detail) > 0 ) {

            $outlet = Outlet::find($order_detail->outlet_id);
            //$items = OrderItem::where('order_id',$order_id)->get();

            $items1 = OrderItem::where('order_id',$order_detail->order_id);

            if ( isset($items) && sizeof($items) > 0 ) {
                $items2 = $items1->whereNotIn('item_unique_id',$items)->get();
            } else {
                $items2 = $items1->get();
            }


            //check order from
            $order_from = "pro";
            if ( $order_detail->customer_order == 1 ) {
                $order_from = "consumer";
            }

            foreach( $items2 as $itm1 ) {

                $itm = OrderItem::where('order_id',$order_detail->order_id)->where('item_unique_id',$itm1->item_unique_id)->first();

                $printer_id = '';
                $print_bind_check = PrinterItemBind::where('outlet_id',$order_detail->outlet_id)->where('item_id',$itm->item_id)->first();
                if ( isset($print_bind_check) && sizeof($print_bind_check) > 0 ) {
                    $printer_id = $print_bind_check->printer_id;
                } else {

                    if ( isset($outlet->printer) && $outlet->printer != '' ) {
                        $check_print = json_decode($outlet->printer);
                        if ( isset($check_print) && sizeof($check_print) > 0 ) {
                            $printer_id = $check_print->kot_printer;
                        }
                    }

                }


                $table_no = $order_detail->table_no;
                $person_no = $order_detail->person_no;

                //get item options
                $item_options = OrderItemOption::where('order_id',$order_detail->order_id)
                    ->where('order_item_id',$itm->id)
                    ->get();

                $item_options_arr = array();
                if ( isset($item_options) && sizeof($item_options) > 0 ) {
                    $i = 0;
                    foreach ( $item_options as $opt ) {
                        $item_options_arr[$i]['option_item_id'] = $opt->option_item_id;
                        $item_options_arr[$i]['option_item_qty'] = $opt->qty;
                        $item_options_arr[$i]['option_item_price'] = $opt->option_item_price;
                        $i++;
                    }
                }

                $item_arr = array(
                    "quantity"=>$itm->item_quantity,
                    "itemName"=>$itm->item_name,
                    "titleName"=>$itm->category_name,
                    "itemId"=>$itm->item_id,
                    "itemUniqueId"=>$itm->item_unique_id,
                    "printerId"=>$printer_id,
                    "itemOptions"=>'',
                    "itemPrice"=>$itm->item_price,
                    "item_veg"=>'',
                    "item_active"=>'',
                    "selectedOptions"=>'',
                    "selectedOptionsPrice"=>'',
                    "table_no"=>$table_no,
                    "suborder_id"=>$order_detail->suborder_id,
                    "outlet_id"=>$order_detail->outlet_id,
                    'source_type'=>$order_detail->source_id,
                    'payment_provider_id'=>$order_detail->payment_option_id,
                    'order_payment_status'=>$order_detail->payment_status,
                    'txn_id'=>$order_detail->referance_id,
                    "order_type"=>$order_detail->order_type,
                    "status"=>$order_detail->status,
                    "address"=>$order_detail->address,
                    "contact_no"=>$order_detail->user_mobile_number,
                    "coupon_code"=>'',
                    "discount_value"=>$order_detail->discount_value,
                    "totalprice"=>$order_detail->totalprice,
                    "cancelorder"=>$order_detail->cancelorder,
                    "name"=>$order_detail->name,
                    "paid_type"=>$order_detail->paid_type,
                    "totalcost_afterdiscount"=>$order_detail->totalcost_afterdiscount,
                    "combine_address"=>$order_detail->combine_address,
                    'person'=>$person_no,
                    "rating"=>'',
                    "maxprice"=>'',
                    "othercount"=>'',
                    "round_off"=>'',
                    "order_from"=>$order_from,
                    "delivery_charge"=>$order_detail->delivery_charge,
                    "item_options"=>$item_options_arr
                );
                array_push($order,$item_arr);
            }

            return Response::json(array(
                "table_no"=>$table_no,
                'order_type'=>$order_detail->order_type,
                'order_unique_id'=>$order_detail->order_unique_id,
                'order_id'=>$order_detail->order_id,
                'order' => $order,
                'status' => 'success',
                'statuscode' => 200
            ), 200);

        } else {

            return Response::json(array(
                'message'=>'no record found',
                'status' => 'error',
                'statuscode' => 235
            ), 235);
        }

    }

    public function syncKot() {

        $kot = Request::json('data');
        $outlet_id = Request::json('outlet_id');
        $user_id = Request::json('user_id');
        $order_id = Request::json('order_id');
        $type = Request::json('order_type');
        $response = array();
        $serv_id = '';


        if ( isset($kot) && sizeof($kot) > 0 ) {

            $kot_unique_arr = array();
            foreach( $kot as $kt ) {

                if ( isset($kt['server_id']) && $kt['server_id'] != '' ) {

                    if ( isset($kt['kot_item_is_cancelled']) && $kt['kot_item_is_cancelled'] == 'yes') {

                        $result = Kot::where('id',$kt['server_id'])->update(['price'=>$kt['kot_item_price'],'quantity'=>$kt['kot_item_qty'],'print_count'=>$kt['print_count'],'reason'=>$kt['kot_cancel_reason'],'deleted_at'=>$kt['kot_date_time']]);
                        if ( isset($kt['item_unique_id']) && $kt['item_unique_id'] != 0  && $type == 'dine_in') {
                            //Queue::push('App\Commands\CancelOrderNotification@toConsumerRemoveKotNotification', array('order_id'=>$kt['order_id'],'item_id'=>$kt['kot_item_id'],'item_name'=>$kt['kot_item_name'],'reason'=>$kt['kot_cancel_reason'],'item_unique_id'=>$kt['item_unique_id']));
                        }

                    } else {
                        $result = Kot::where('id',$kt['server_id'])->update(['price'=>$kt['kot_item_price'],'quantity'=>$kt['kot_item_qty'],'print_count'=>$kt['print_count']]);
                    }

                    $serv_id = $kt['server_id'];

                } else {

                    $kot = new Kot();
                    $kot->order_unique_id = $kt['order_unique_id'];
                    $kot->outlet_id = $outlet_id;
                    $kot->created_by = $user_id;
                    $kot->updated_by = $user_id;
                    $kot->kot_id = $kt['kot_id'];
                    $kot->table_no = $kt['table_no'];
                    $kot->person_no = $kt['person_no'];
                    $kot->kot_order_id = $kt['kot_order_id'];
                    $kot->item_id = $kt['kot_item_id'];
                    $kot->item_name = $kt['kot_item_name'];
                    $kot->price = $kt['kot_item_price'];
                    $kot->print_count = $kt['print_count'];
                    $kot->quantity = $kt['kot_item_qty'];
                    $kot->reason = $kt['kot_cancel_reason'];
                    $kot->kot_time = $kt['kot_date_time'];
                    $kot->status = 'open';

                    if ( isset($kt['kot_item_is_cancelled']) && $kt['kot_item_is_cancelled'] == 'yes') {
                        $kot->deleted_at = $kt['kot_date_time'];
                        if ( isset($kt['item_unique_id']) && $kt['item_unique_id'] != 0 ) {
                            //Queue::push('App\Commands\CancelOrderNotification@toConsumerRemoveKotNotification', array('order_id' => $kt['order_id'], 'item_id' => $kt['kot_item_id'], 'item_name' => $kt['kot_item_name'], 'reason' => $kt['kot_cancel_reason'],'item_unique_id'=>$kt['item_unique_id']));
                        }
                    } else {
                        $kot_unique_arr[] = $kt['item_unique_id'];
                    }
                    $result = $kot->save();
                    if ( $result ) {
                        $serv_id = $kot->id;
                    }

                }

                $kot_arr = array('kot_id'=>$kt['kot_id'],'server_id'=>$serv_id);
                array_push($response,$kot_arr);

            }

            #TODO: Check order from customer or partner app
            $check_order = order_details::where('order_id',$order_id)->first();

            if ( isset($check_order) && sizeof($check_order) > 0 ) {
                if( $check_order->customer_order == 1 && $type == 'dine_in' ) {
                    Log::info('order item confimr event call');
                    event(new OrderItemConfirmEvent( $outlet_id, $order_id, $response ));
                }
            }


            if ( isset($kot_unique_arr) && sizeof($kot_unique_arr) > 0 && $order_id != 0 && $type == 'dine_in') {
                //Queue::push('App\Commands\OwnerNotification@toConsumerAcceptKotNotification', array('order_id' => $order_id, 'kot_items' => $kot_unique_arr));
            }
        }

        return Response::json(array(
            'message'=>'Record sync successfully',
            'data'=>$response,
            'status' => 'success',
            'statuscode' => 200
        ), 200);


    }

    #TODO:send attend me notification
    public function sendAttendMe() {

        $table_no = Request::json('table_no');
        $outlet_id = Request::json('outlet_id');

        //Queue::push('App\Commands\OwnerNotification@attendNotification', array('outlet_id'=>$outlet_id,'table_no'=>$table_no));

        return Response::json(array(
            'status' => 'success',
            'statuscode' => 200
        ),200);
    }

    public function consumerPayBillNotification() {

        $table_no = Request::json('table_no');
        $outlet_id = Request::json('outlet_id');

        //Queue::push('App\Commands\OwnerNotification@payBillNotification', array('outlet_id'=>$outlet_id,'table_no'=>$table_no));

        return Response::json(array(
            'status' => 'success',
            'statuscode' => 200
        ),200);

    }

    public function tableAvailability() {

        $table_no = Request::json('table_no');
        $outlet_id = Request::json('outlet_id');
        $no_of_person = Request::json('table_person');
        $user_id = Request::json('user_id');
        $request_from = Request::json('request_from');

        $check_table = Tables::where('outlet_id',$outlet_id)->where('table_no',$table_no)->get();
        if ( isset($check_table) && sizeof($check_table) > 0 ) {

            $check_availability = Tables::where('outlet_id',$outlet_id)->where('table_no',$table_no)->where('status',0)->first();
            if ( isset($check_availability) && sizeof($check_availability) > 0 ) {
                $check_availability->status = 1;
                $check_availability->occupied_by = $user_id;
                $check_availability->request_from = $request_from;
                $check_availability->save();

                return Response::json(array(
                    'message'=>'Table is vacant',
                    'table_status'=>'vacant',
                    'table_no'=> $table_no,
                    'table_person'=> $no_of_person,
                    'status' => 'success',
                    'statuscode' => 200
                ),200);

            } else {

                return Response::json(array(
                    'message'=>'Table is already occupied',
                    'table_status'=>'occupied',
                    'table_no'=> $table_no,
                    'table_person'=> $no_of_person,
                    'status' => 'success',
                    'statuscode' => 200
                ),200);

            }

        }

        return Response::json(array(
            'message'=>'Table is not available',
            'status' => 'error',
            'table_no'=> $table_no,
            'table_person'=> $no_of_person,
            'statuscode' => 500
        ),500);

    }

    public function vacantTable() {

        $table_no = Request::json('table_no');
        $outlet_id = Request::json('outlet_id');
        $user_id = Request::json('user_id');

        $check_availability = Tables::where('outlet_id',$outlet_id)->where('table_no',$table_no)->where('status',1)->first();

        if( isset($check_availability) && sizeof($check_availability) > 0 ) {
            $check_availability->status = 0;
            $check_availability->occupied_by = NULL;
            $check_availability->updated_by = $user_id;
            $check_availability->request_from = NULL;
            $check_availability->save();

            return Response::json(array(
                'message'=>'Table closed',
                'status' => 'success',
                'table_no'=> $table_no,
                'statuscode' => 200
            ),200);

        } else {

            return Response::json(array(
                'message'=>'Table successfully removed',
                'status' => 'success',
                'table_no'=> $table_no,
                'statuscode' => 200
            ),200);
        }

    }

    public function cancelAllOrder() {

        $order_id = Request::json('order_id');
        $reason = Request::json('reason');
        $outlet_id = Request::json('outlet_id');
        $user_id = Request::json('user_id');
        $user_name = Request::json('user_name');
        $from = Request::json('order_from');
        $order_type = Request::json('order_type');
        $data = Request::json('data');

        if ( $from == 'consumer') {

            //cancel order
            $canccel_ord = newordercontroller::cancelOrder($order_id,$outlet_id,$reason,$user_id);

            if ( $canccel_ord == 'success') {

                $order_items = OrderItem::where('order_id',$order_id)->get();

                if ( isset($order_items) && sizeof($order_items) > 0 ) {

                    $itm_arr = array();
                    foreach( $order_items as $itm ) {
                        $itm_arr[] = $itm->item_unique_id;
                    }
                    //Queue::push('App\Commands\CancelOrderNotification@toConsumerCancelOrderNotification', array('order_id'=>$order_id,'order_items'=>$itm_arr,'reason'=>$reason));

                    return Response::json(array(
                        'message'=>'Order cancelled successfully',
                        'status' => 'success',
                        'order_id'=> $order_id,
                        'statuscode' => 200
                    ),200);
                }
            }else {

                return Response::json(array(
                    'message'=>'Please try again, There is some issue',
                    'status' => 'error',
                    'statuscode' => 435
                ),435);

            }

        } else {

            $table_start_date = Request::json('table_start_date');
            $table_end_date = Request::json('table_end_date');
            $order_unique_id = Request::json('order_unique_id');
            $total = Request::json('total');
            $table_no = Request::json('table_no');
            $person_no = Request::json('person_no');
            $local_id = Request::json('local_id');

            $check_order = order_details::where('order_unique_id',$order_unique_id)->first();

            if ( isset($check_order) && sizeof($check_order) > 0 ) {

                $check_order->name = $user_name;
                $check_order->cancelorder = 1;
                $result = $check_order->save();

                $order_id = $check_order->order_id;

            } else {

                $order_details=new order_details();
                $order_details->name = $user_name;
                $order_details->outlet_id = $outlet_id;
                $order_details->status = 'delivered';
                $order_details->local_id = $local_id;
                $order_details->order_type = $order_type;
                $order_details->table_no = $table_no;
                $order_details->person_no = $person_no;
                $order_details->totalprice = $total;
                $order_details->paid_type = 'cod';
                $order_details->totalcost_afterdiscount = $total;
                $order_details->suborder_id = 0;
                $order_details->order_unique_id = $order_unique_id;
                $order_details->table_start_date= $table_start_date;
                $order_details->table_end_date= $table_end_date;
                $order_details->cancelorder = 1;
                $result = $order_details->save();

                $order_id = $order_details->order_id;

                if ( isset($data) && sizeof($data) > 0 ) {
                    foreach ( $data as $dt ) {
                        $orderid = OrderItem::insertmenuitemoforders($order_id, $dt,$outlet_id);
                    }
                }

            }

            if ( $result ) {

                Kot::where('order_unique_id',$order_unique_id)->forcedelete();

                $canccel_ord = newordercontroller::cancelOrder($order_id,$outlet_id,$reason,$user_id);

                return Response::json(array(
                    'message'=>'Order cancelled successfully',
                    'status' => 'success',
                    'order_id'=> $order_id,
                    'statuscode' => 200
                ),200);

            } else {

                return Response::json(array(
                    'message'=>'Please try again, There is some issue',
                    'status' => 'error',
                    'statuscode' => 435
                ),435);

            }

        }

    }

    #TODO: store stock request
    public function storeRequest() {

        $location_id = Request::json('location_id');
        $from_id = Request::json('from_user');
        $to_id = Request::json('to_user');
        $items = Request::json('items');
        $req_date = Request::json('request_date');
        $wrong_request = array();

        if ( isset($items) && sizeof($items) > 0 ){
            foreach( $items as $itm ) {

                $check = false;
                $menu_item = Menu::find($itm['item_id']);

                //check requested item unit is available or not
                if ( isset($menu_item) && sizeof($menu_item) > 0 ) {

                    if ( $menu_item->unit_id == $itm['unit_id'] ) {
                        $check = true;
                    } else {

                        if ( isset($menu_item->secondary_units) && $menu_item->secondary_units != '' ) {

                            $units = json_decode($menu_item->secondary_units);
                            if ( isset($units) && $units != '') {
                                foreach( $units as $key=>$u ) {
                                    if ( $itm['unit_id'] == $key ) {
                                        $check = true;
                                    }
                                }
                            }

                        }

                    }

                    if ( !$check ) {
                        array_push($wrong_request,$itm['item_name']);
                        continue;
                    }

                }

                $itemRequest = new ItemRequest();
                $itemRequest->what_item_id = $itm['item_id'];
                $itemRequest->what_item = $itm['item_name'];
                $itemRequest->owner_to = $to_id;
                $itemRequest->owner_by = $from_id;
                $itemRequest->when = $req_date;
                $itemRequest->unit_id = $itm['unit_id'];
                $itemRequest->qty = $itm['item_req_qty'];
                $itemRequest->existing_qty = $itm['item_existing_qty'];
                $itemRequest->satisfied = 'No';
                $itemRequest->location_for = $location_id;
                $success = $itemRequest->save();

            }
        }

        return Response::json(array(
            'message'=>'Request has been sent successfully.',
            'status' => 'success',
            'items'=>$wrong_request,
            'statuscode' => 200
        ),200);

    }

    public function processRequest() {

        $data = Request::json('data');
        $user_id = Request::json('user_id');
        $loc_id = Request::json('from_location_id');
        $today = Request::json('response_date');

        $success = '';
        $error = false;
        //print_r($input);exit;

        if ( isset($data) && sizeof($data) > 0 ) {

            //transaction_id;
            $transaction_id = uniqid();
            foreach( $data as $dt ) {

                $request_id = $dt['request_id'];
                $satisfy_qty = $total_qty = floatval($dt['satisfied_qty']);
                $unit_id = $dt['unit_id'];

                $processRequest = ItemRequest::find($request_id);
                $menu_item = Menu::find($processRequest->what_item_id);

                //continue for nex loop if both field are blank
                if ( $satisfy_qty == '' || $satisfy_qty == 0 ) {

                    $satisfy_qty = 0;
                    $res_dev = new ResponseDeviation();
                    $res_dev->transaction_id = $transaction_id;
                    $res_dev->item_id = $processRequest->what_item_id;
                    $res_dev->item_name	= $menu_item->item;
                    $res_dev->request_qty = $processRequest->qty;
                    $res_dev->request_unit_id = $processRequest->unit_id;
                    $res_dev->satisfied_qty = $satisfy_qty;
                    $res_dev->satisfied_unit_id = $unit_id;
                    $res_dev->for_location_id = $processRequest->location_for;
                    $res_dev->from_location_id = $loc_id;
                    $res_dev->request_by = $processRequest->owner_by;
                    $res_dev->satisfied_by = $user_id;
                    $res_dev->request_when = $processRequest->when;
                    $res_dev->satisfied_when = $today;
                    $res_dev->save();
                    continue;
                }
                DB::beginTransaction();
                if ( isset($satisfy_qty) && $satisfy_qty != '') {

                    try {

                        $qty = $satisfy_qty;

                        if ( $processRequest->satisfied == 'Yes') {
                            continue;
                        }

                        $other_units = '';
                        if( isset($menu_item->secondary_units) && $menu_item->secondary_units != '' ) {
                            $units = json_decode($menu_item->secondary_units);
                            if ( isset($units) && $units != '' ) {
                                foreach( $units as $key=>$u ) {
                                    if ( $key == $unit_id) {
                                        $qty = floatval($qty) * floatval($u);
                                    }
                                }
                            }

                        }
                        $processRequest->price = Menu::getItemIngredPrice($processRequest->what_item_id);
                        $processRequest->satisfied_unit_id = $unit_id;
                        $processRequest->satisfied_batch_id = $transaction_id;
                        $processRequest->satisfied = 'Yes';
                        $processRequest->type = 'request';
                        $processRequest->satisfied_by = $user_id;
                        $processRequest->satisfied_when = $today;
                        $processRequest->statisfied_qty = $satisfy_qty;
                        $processRequest->location_from = $loc_id;
                        $success = $processRequest->save();

                        if ( $success ) {

                            //add record in response deviation table for check deviation in supply
                            if ( $processRequest->qty != $satisfy_qty ) {

                                $res_dev = new ResponseDeviation();
                                $res_dev->transaction_id = $transaction_id;
                                $res_dev->item_id = $processRequest->what_item_id;
                                $res_dev->item_name	= $menu_item->item;
                                $res_dev->request_qty = $processRequest->qty;
                                $res_dev->request_unit_id = $processRequest->unit_id;
                                $res_dev->satisfied_qty = $satisfy_qty;
                                $res_dev->satisfied_unit_id = $unit_id;
                                $res_dev->for_location_id = $processRequest->location_for;
                                $res_dev->from_location_id = $loc_id;
                                $res_dev->request_by = $processRequest->owner_by;
                                $res_dev->satisfied_by = $user_id;
                                $res_dev->request_when = $processRequest->when;
                                $res_dev->satisfied_when = $today;
                                $res_dev->save();
                            }

                            /*decrease item from location*/
                            $from_loc_stock = Stock::where('location_id', $loc_id)
                                ->where('item_id', $processRequest->what_item_id)
                                ->first();

                            if (isset($from_loc_stock) && sizeof($from_loc_stock) > 0) {

                                $remain_qty = $from_loc_stock->quantity - $qty;
                                $stock = Stock::find($from_loc_stock->id);
                                $stock->quantity = $remain_qty;
                                $stock->updated_by = $user_id;
                                $stock->updated_at = $today;
                                $from_loc_result = $stock->save();

                            } else {

                                $stk_add = new Stock();
                                $stk_add->item_id = $processRequest->what_item_id;
                                $stk_add->location_id = $loc_id;
                                $stk_add->created_by = $user_id;
                                $stk_add->updated_by = $user_id;
                                $stk_add->quantity = 0 - $qty;
                                $stk_add->created_at = $today;
                                $stk_add->updated_at = $today;
                                $from_loc_result = $stk_add->save();
                            }

                            $for_loc_stock = Stock::where('location_id', $processRequest->location_for)
                                ->where('item_id', $processRequest->what_item_id)
                                ->first();

                            if (isset($for_loc_stock) && sizeof($for_loc_stock) > 0) {

                                $added_qty = $for_loc_stock->quantity + $qty;
                                $for_loc_stock->quantity = $added_qty;
                                $for_loc_stock->updated_by = $user_id;
                                $for_loc_stock->updated_at = $today;
                                $from_loc_result = $for_loc_stock->save();

                            } else {

                                $stk_add = new Stock();
                                $stk_add->item_id = $processRequest->what_item_id;
                                $stk_add->location_id = $processRequest->location_for;
                                $stk_add->created_by = $user_id;
                                $stk_add->updated_by = $user_id;
                                $stk_add->quantity = $qty;
                                $stk_add->created_at = $today;
                                $stk_add->updated_at = $today;
                                $from_loc_result = $stk_add->save();
                            }


                            /*if stock avalilable than decrease quantity*/
                            if ( $from_loc_result ) {

                                $stock_history = new StockHistory();
                                $stock_history->transaction_id = $transaction_id;
                                $stock_history->from_location = $loc_id;
                                $stock_history->to_location = $processRequest->location_for;
                                $stock_history->item_id = $processRequest->what_item_id;
                                $stock_history->type = 'remove';
                                $stock_history->quantity = $qty;
                                $stock_history->reason = 'transfer';
                                $stock_history->created_by = $user_id;
                                $stock_history->updated_by = $user_id;
                                $stock_history->created_at = $today;
                                $stock_history->updated_at = $today;
                                $result1 = $stock_history->save();

                                if ( $result1 ) {

                                    $stock_history = new StockHistory();
                                    $stock_history->transaction_id = $transaction_id;
                                    $stock_history->from_location = $loc_id;
                                    $stock_history->to_location = $processRequest->location_for;
                                    $stock_history->item_id = $processRequest->what_item_id;
                                    $stock_history->type = 'add';
                                    $stock_history->quantity = $qty;
                                    $stock_history->reason = 'transfer';
                                    $stock_history->created_by = $user_id;
                                    $stock_history->updated_by = $user_id;
                                    $stock_history->created_at = $today;
                                    $stock_history->updated_at = $today;
                                    $result1 = $stock_history->save();

                                    if ( !$result1 ) {

                                        $error = true;
                                        DB::rollBack();
                                        return Response::json(array(
                                            'message'=>'Please try again later.',
                                            'status' => 'error',
                                            'statuscode' => 401
                                        ),401);

                                    }

                                } else {
                                    $error = true;
                                    DB::rollBack();
                                    return Response::json(array(
                                        'message'=>'Please try again later.',
                                        'status' => 'error',
                                        'statuscode' => 401
                                    ),401);
                                }


                                /*$get_stock = StockAge::where('item_id',$processRequest->what_item_id)
                                    ->where('location_id',$loc_id)
                                    //->where('quantity','>',0)
                                    ->orderby('expiry_date','asc')
                                    ->get();

                                if ( isset($get_stock) && sizeof($get_stock) > 0 ) {
                                    $remain_stk = 0;$first_time = true;
                                    foreach( $get_stock as $get_stk ) {

                                        //if stock is less than first batch stock
                                        if ( $get_stk->quantity > $satisfy_qty && $first_time == true ) {

                                            $get_stk->quantity = $get_stk->quantity - $satisfy_qty;
                                            $get_stk->updated_by = $user_id;
                                            $get_stk->updated_at = $today;
                                            $get_stk->save();

                                            $stock_history = new StockHistory();
                                            $stock_history->transaction_id = $get_stk->transaction_id;
                                            $stock_history->from_location = $loc_id;
                                            $stock_history->to_location = $processRequest->location_for;
                                            $stock_history->item_id = $processRequest->what_item_id;
                                            $stock_history->type = 'remove';
                                            $stock_history->quantity = $satisfy_qty;
                                            $stock_history->reason = 'transfer';
                                            $stock_history->created_by = $user_id;
                                            $stock_history->updated_by = $user_id;
                                            $stock_history->created_at = $today;
                                            $stock_history->updated_at = $today;
                                            $result1 = $stock_history->save();

                                            if ( isset($get_stk->transaction_id) &&  $get_stk->transaction_id != '' ) {

                                                $add_stock = StockAge::where('item_id',$processRequest->what_item_id)
                                                    ->where('location_id',$processRequest->location_for)
                                                    ->where('transaction_id',$get_stk->transaction_id)->first();

                                                if( isset($add_stock) && sizeof($add_stock) > 0 ) {
                                                    $add_stock->quantity = $add_stock->quantity + $satisfy_qty;
                                                    $add_stock->updated_at = $today;
                                                    $add_stock->save();
                                                } else {

                                                    $add_stock = new StockAge();
                                                    $add_stock->transaction_id = $get_stk->transaction_id;
                                                    $add_stock->item_id = $processRequest->what_item_id;
                                                    $add_stock->location_id = $processRequest->location_for;
                                                    $add_stock->quantity = $satisfy_qty;
                                                    $add_stock->created_at = $today;
                                                    $add_stock->updated_at = $today;
                                                    $add_stock->created_by = $user_id;
                                                    $add_stock->updated_by = $user_id;
                                                    $add_stock->save();

                                                }
                                            } else {

                                                $add_stock = new StockAge();
                                                $add_stock->item_id = $processRequest->what_item_id;
                                                $add_stock->location_id = $processRequest->location_for;
                                                $add_stock->quantity = $satisfy_qty;
                                                $add_stock->created_at = $today;
                                                $add_stock->updated_at = $today;
                                                $add_stock->created_by = $user_id;
                                                $add_stock->updated_by = $user_id;
                                                $add_stock->save();

                                            }

                                            $stock_history = new StockHistory();
                                            $stock_history->transaction_id = $get_stk->transaction_id;
                                            $stock_history->from_location = $loc_id;
                                            $stock_history->to_location = $processRequest->location_for;
                                            $stock_history->item_id = $processRequest->what_item_id;
                                            $stock_history->type = 'add';
                                            $stock_history->quantity = $satisfy_qty;
                                            $stock_history->reason = 'transfer';
                                            $stock_history->created_by = $user_id;
                                            $stock_history->updated_by = $user_id;
                                            $stock_history->created_at = $today;
                                            $stock_history->updated_at = $today;
                                            $result1 = $stock_history->save();

                                            break;

                                        } else {

                                            if ( $remain_stk > 0 || $first_time == true ) {
                                                $first_time = false;


                                                if ( $get_stk->quantity <= $satisfy_qty ) {

                                                    $avail_stock = $get_stk->quantity;
                                                    $satisfy_qty = $satisfy_qty - $get_stk->quantity;
                                                    $remain_stk = $satisfy_qty;

                                                    $get_stk->quantity = $get_stk->quantity - $get_stk->quantity;
                                                    $get_stk->updated_by = $user_id;
                                                    $get_stk->updated_at = $today;
                                                    $get_stk->save();

                                                    if ( isset($get_stk->transaction_id) &&  $get_stk->transaction_id != '' ) {

                                                        $add_stock = StockAge::where('item_id',$processRequest->what_item_id)
                                                            ->where('location_id',$processRequest->location_for)
                                                            ->where('transaction_id',$get_stk->transaction_id)->first();

                                                        if( isset($add_stock) && sizeof($add_stock) > 0 ) {
                                                            $add_stock->quantity = $add_stock->quantity + $avail_stock;
                                                            $add_stock->updated_at = $today;
                                                            $add_stock->save();
                                                        } else {

                                                            $add_stock = new StockAge();
                                                            $add_stock->transaction_id = $get_stk->transaction_id;
                                                            $add_stock->item_id = $processRequest->what_item_id;
                                                            $add_stock->location_id = $processRequest->location_for;
                                                            $add_stock->quantity = $avail_stock;
                                                            $add_stock->created_at = $today;
                                                            $add_stock->updated_at = $today;
                                                            $add_stock->created_by = $user_id;
                                                            $add_stock->updated_by = $user_id;
                                                            $add_stock->save();

                                                        }

                                                    } else {

                                                        $add_stock = new StockAge();
                                                        $add_stock->item_id = $processRequest->what_item_id;
                                                        $add_stock->location_id = $processRequest->location_for;
                                                        $add_stock->quantity = $avail_stock;
                                                        $add_stock->created_at = $today;
                                                        $add_stock->updated_at = $today;
                                                        $add_stock->created_by = $user_id;
                                                        $add_stock->updated_by = $user_id;
                                                        $add_stock->save();

                                                    }

                                                    $stock_history = new StockHistory();
                                                    $stock_history->transaction_id = $get_stk->transaction_id;
                                                    $stock_history->from_location = $loc_id;
                                                    $stock_history->to_location = $processRequest->location_for;
                                                    $stock_history->item_id = $processRequest->what_item_id;
                                                    $stock_history->type = 'remove';
                                                    $stock_history->quantity = $avail_stock;
                                                    $stock_history->reason = 'transfer';
                                                    $stock_history->created_by = $user_id;
                                                    $stock_history->updated_by = $user_id;
                                                    $stock_history->created_at = $today;
                                                    $stock_history->updated_at = $today;
                                                    $result1 = $stock_history->save();

                                                    if ( $result1 ) {

                                                        $stock_history1 = new StockHistory();
                                                        $stock_history1->transaction_id = $get_stk->transaction_id;
                                                        $stock_history1->from_location = $loc_id;
                                                        $stock_history1->to_location = $processRequest->location_for;
                                                        $stock_history1->item_id = $processRequest->what_item_id;
                                                        $stock_history1->type = 'add';
                                                        $stock_history1->quantity = $avail_stock;
                                                        $stock_history1->reason = 'transfer';
                                                        $stock_history1->created_by = $user_id;
                                                        $stock_history1->updated_by = $user_id;
                                                        $stock_history1->created_at = $today;
                                                        $stock_history1->updated_at = $today;
                                                        $result2 = $stock_history1->save();

                                                    } else {
                                                        $error = true;
                                                        DB::rollBack();
                                                        return Response::json(array(
                                                            'message'=>'Please try again later.',
                                                            'status' => 'error',
                                                            'statuscode' => 401
                                                        ),401);
                                                    }

                                                } else {

                                                    $get_stk->quantity = $get_stk->quantity - $satisfy_qty;
                                                    $get_stk->updated_by = $user_id;
                                                    $get_stk->updated_at = $today;
                                                    $get_stk->save();

                                                    if ( isset($get_stk->transaction_id) &&  $get_stk->transaction_id != '' ) {

                                                        $add_stock = StockAge::where('item_id',$processRequest->what_item_id)
                                                            ->where('location_id',$processRequest->location_for)
                                                            ->where('transaction_id',$get_stk->transaction_id)->first();

                                                        if( isset($add_stock) && sizeof($add_stock) > 0 ) {
                                                            $add_stock->quantity = $add_stock->quantity + $satisfy_qty;
                                                            $add_stock->updated_at = $today;
                                                            $add_stock->save();
                                                        } else {

                                                            $add_stock = new StockAge();
                                                            $add_stock->item_id = $processRequest->what_item_id;
                                                            $add_stock->location_id = $processRequest->location_for;
                                                            $add_stock->quantity = $satisfy_qty;
                                                            $add_stock->created_at = $today;
                                                            $add_stock->updated_at = $today;
                                                            $add_stock->created_by = $user_id;
                                                            $add_stock->updated_by = $user_id;
                                                            $add_stock->save();
                                                        }

                                                    } else {

                                                        $add_stock = new StockAge();
                                                        $add_stock->item_id = $processRequest->what_item_id;
                                                        $add_stock->location_id = $processRequest->location_for;
                                                        $add_stock->quantity = $satisfy_qty;
                                                        $add_stock->created_at = $today;
                                                        $add_stock->updated_at = $today;
                                                        $add_stock->created_by = $user_id;
                                                        $add_stock->updated_by = $user_id;
                                                        $add_stock->save();

                                                    }

                                                    $stock_history = new StockHistory();
                                                    $stock_history->transaction_id = $get_stk->transaction_id;
                                                    $stock_history->from_location = $loc_id;
                                                    $stock_history->to_location = $processRequest->location_for;
                                                    $stock_history->item_id = $processRequest->what_item_id;
                                                    $stock_history->type = 'remove';
                                                    $stock_history->quantity = $satisfy_qty;
                                                    $stock_history->reason = 'transfer';
                                                    $stock_history->created_by = $user_id;
                                                    $stock_history->updated_by = $user_id;
                                                    $stock_history->created_at = $today;
                                                    $stock_history->updated_at = $today;
                                                    $result1 = $stock_history->save();

                                                    if ( $result1 ) {

                                                        $stock_history1 = new StockHistory();
                                                        $stock_history1->transaction_id = $get_stk->transaction_id;
                                                        $stock_history1->from_location = $loc_id;
                                                        $stock_history1->to_location = $processRequest->location_for;
                                                        $stock_history1->item_id = $processRequest->what_item_id;
                                                        $stock_history1->type = 'add';
                                                        $stock_history1->quantity = $satisfy_qty;
                                                        $stock_history1->reason = 'transfer';
                                                        $stock_history1->created_by = $user_id;
                                                        $stock_history1->updated_by = $user_id;
                                                        $stock_history1->created_at = $today;
                                                        $stock_history1->updated_at = $today;
                                                        $result2 = $stock_history1->save();

                                                    } else {
                                                        $error = true;
                                                        DB::rollBack();
                                                        return Response::json(array(
                                                            'message'=>'Please try again later.',
                                                            'status' => 'error',
                                                            'statuscode' => 401
                                                        ),401);
                                                    }

                                                    break;
                                                }

                                                if ( $satisfy_qty <= 0 ) {
                                                    break;
                                                }

                                            }
                                        }

                                    }

                                } else {

                                    $st_age_add = new StockAge();
                                    $st_age_add->location_id = $loc_id;
                                    $st_age_add->item_id = $processRequest->what_item_id;
                                    $st_age_add->transaction_id = '';
                                    $st_age_add->quantity = 0 - $satisfy_qty;
                                    $st_age_add->created_by = $user_id;
                                    $st_age_add->updated_by = $user_id;
                                    $st_age_add->created_at = $today;
                                    $st_age_add->updated_at = $today;
                                    $st_age_result = $st_age_add->save();

                                    if ( $st_age_result) {

                                        $st_age_add1 = new StockAge();
                                        $st_age_add1->location_id = $processRequest->location_for;
                                        $st_age_add1->item_id = $processRequest->what_item_id;
                                        $st_age_add1->transaction_id = '';
                                        $st_age_add1->quantity = $satisfy_qty;
                                        $st_age_add1->created_by = $user_id;
                                        $st_age_add1->updated_by = $user_id;
                                        $st_age_add1->created_at = $today;
                                        $st_age_add1->updated_at = $today;
                                        $st_age_result1 = $st_age_add1->save();

                                        if ( $st_age_result1) {

                                            $stock_history = new StockHistory();
                                            $stock_history->from_location = $loc_id;
                                            $stock_history->to_location = $processRequest->location_for;
                                            $stock_history->item_id = $processRequest->what_item_id;
                                            $stock_history->type = 'remove';
                                            $stock_history->quantity = $satisfy_qty;
                                            $stock_history->reason = 'transfer';
                                            $stock_history->created_by = $user_id;
                                            $stock_history->updated_by = $user_id;
                                            $stock_history->created_at = $today;
                                            $stock_history->updated_at = $today;
                                            $result1 = $stock_history->save();

                                            if ( $result1 ){

                                                $stock_history1 = new StockHistory();
                                                $stock_history1->from_location = $loc_id;
                                                $stock_history1->to_location = $processRequest->location_for;
                                                $stock_history1->item_id = $processRequest->what_item_id;
                                                $stock_history1->type = 'add';
                                                $stock_history1->quantity = $satisfy_qty;
                                                $stock_history1->reason = 'transfer';
                                                $stock_history1->created_by = $user_id;
                                                $stock_history1->updated_by = $user_id;
                                                $stock_history1->created_at = $today;
                                                $stock_history1->updated_at = $today;
                                                $result1 = $stock_history1->save();

                                                if ( !$result1 ) {
                                                    $error = true;
                                                    DB::rollBack();
                                                    return Response::json(array(
                                                        'message'=>'Please try again later.',
                                                        'status' => 'error',
                                                        'statuscode' => 401
                                                    ),401);

                                                }

                                            } else {

                                                $error = true;
                                                DB::rollBack();
                                                return Response::json(array(
                                                    'message'=>'Please try again later.',
                                                    'status' => 'error',
                                                    'statuscode' => 401
                                                ),401);
                                            }

                                        } else {

                                            $error = true;
                                            DB::rollBack();
                                            return Response::json(array(
                                                'message'=>'Please try again later.',
                                                'status' => 'error',
                                                'statuscode' => 401
                                            ),401);


                                        }

                                    } else {
                                        $error = true;
                                        DB::rollBack();
                                        return Response::json(array(
                                            'message'=>'Please try again later.',
                                            'status' => 'error',
                                            'statuscode' => 401
                                        ),401);

                                    }
                                }*/

                            } else {

                                $error = true;
                                DB::rollBack();
                                return Response::json(array(
                                    'message'=>'Please try again later.',
                                    'status' => 'error',
                                    'statuscode' => 401
                                ),401);
                            }

                        } else {
                            $error = true;
                            DB::rollBack();
                            return Response::json(array(
                                'message'=>'Please try again later.',
                                'status' => 'error',
                                'statuscode' => 401
                            ),401);
                        }



                    } catch( \Exception $e ) {
                        $error = true;
                        DB::rollBack();
                        return Response::json(array(
                            'message'=>$e->getMessage(),
                            'status' => 'error',
                            'statuscode' => 401
                        ),401);
                    }

                }


                DB::commit();
            }
            Queue::push('App\Commands\OwnerNotification@sendresponsedeviation', array('transaction_id'=>$transaction_id,'user_id'=>$user_id));

            return Response::json(array(
                'message'=>'Request processed successfully.',
                'status' => 'success',
                'statuscode' => 200
            ),200);


        } else {

            DB::rollBack();
            return Response::json(array(
                'message'=>'Please try again later.',
                'status' => 'error',
                'statuscode' => 401
            ),401);
        }

    }

    #TODO: Update device_id
    public function updateDeviceId() {

        $user_id = Request::json('user_id');
        $device_id = Request::json('device_id');
        $token_from = Request::json('token_from');

        if( $token_from == 'consumer') {
            User::where('id',$user_id)->update(['device_id'=>$device_id]);
        } else {
            Owner::where('id',$user_id)->update(['device_id'=>$device_id]);
        }

        return Response::json(array(
            'message'=>'Device id updated successfully.',
            'status' => 'success',
            'statuscode' => 200
        ),200);
    }

    #TODO: get Log push notification
    public function getAllOutletPush(){

        $fields['device_id'] = Request::get('device_id');
        $fields['flag'] = Request::get('flag');
        $fields['level'] = Request::get('level');

        $outlet_id = Request::get('outlet_id');
        $owner_id = Request::get('owner_id');
        $level_id = Request::get('level_id');

        $log_level = LogLevel::where('outlet_id',$outlet_id)
                    ->where('owner_id',$owner_id)->get();

        if(isset($log_level) && sizeof($log_level)>0){
            $update_log_level = LogLevel::find($log_level[0]->id);
            $update_log_level->level = $level_id;
            $update_log_level->save();
        }else {
            $log_level = new LogLevel();
            $log_level->outlet_id = $outlet_id > 0 ? $outlet_id : 0;
            $log_level->owner_id = $owner_id > 0 ? $owner_id : 0;
            $log_level->level = $level_id > 0 ? $level_id : 0;
            $log_level->save();
        }

        $send = Queue::push('App\Commands\LogNotification@logNotifications', array('fields'=>$fields));

        if($send && $fields['device_id']!='') {
            return Response::json(array(
                'status' => 'success',
                'statuscode' => $fields['device_id']
            ), 200);
        }else{
            return Response::json(array(
                'status' => 'error',
                'statuscode' => 300
            ), 300);
        }


    }

    public static function uploadLog(){

        $log_array = array();
        $log_array['owner_id'] = Request::get('user_id');
        $log_array['outlet_id'] = Request::get('outlet_id');
        $log_array['app_version'] = Request::get('app_version');
        $log_array['manufacturer'] = Request::get('manufacturer');
        $log_array['model'] = Request::get('model');
        $log_array['os_version'] = Request::get('os_version');

        $dirname = base_path() ."/public/FK_Log_Files";

        if (!file_exists($dirname)) {
            mkdir($dirname, 0777, true);
        }

        foreach($_FILES['file_data']['name'] as $key=>$val){

            $file_name = $_FILES["file_data"]["name"][$key];
            $file_tmp = $_FILES["file_data"]["tmp_name"][$key];

            $file_path = base_path() ."/public/FK_Log_Files/".$file_name;
            $store_file_path = "/FK_Log_Files/".$file_name;
            $log_array['path'] = $store_file_path;

            if(move_uploaded_file($file_tmp, $file_path)){
                $response["status"] = 1;
                $response["message"] = "File uploaded successfully.";
            } else {
                $response["status"] = 0;
                $response["message"] = "Some error occurred. Please try again";
            }
            LogDetails::insertLogDetails($log_array);
        }

        echo json_encode($response);

    }


    public function pastRequest(){

        $user_to = Request::get('user_to');
        $user_from = Request::get('user_from');
        $location_id = Request::get('location_id');

        $from = Request::get('from_date');
        $to = Request::get('to_date');

//
        $items = ItemRequest::leftJoin('menus','menus.id','=','item_request.what_item_id')
            ->leftJoin('unit','unit.id','=','item_request.unit_id')
            ->leftJoin('menu_titles','menu_titles.id','=','menus.menu_title_id')
            ->leftJoin('locations','locations.id','=','item_request.location_for')
            ->leftJoin('owners','owners.id','=','item_request.owner_to')
            ->select('item_request.id', 'item_request.location_for', 'owners.user_name','locations.name as location','menus.unit_id', 'unit.name','item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id', 'menus.menu_title_id', 'menus.item', 'menu_titles.title')
            ->where('item_request.owner_to','=',$user_to)
            ->where('item_request.owner_by','=',$user_from)
            ->where('item_request.location_for','=',$location_id)
            ->where('item_request.when', '<=', (new Carbon($to)));

        if( !isset($from) || $from == '' ) {
            $result = $items->get();
        } else {
            $result = $items->where('item_request.when', '>=', (new Carbon($from)))->get();
        }
            //->where('item_request.when','=',$date)


        $d = array('data' => $result);
        $json_data = json_encode($d);

        return $json_data;

    }

    public function synExpense() {

        $data = Request::json('data');

        $exp_arr = array();
        if ( isset($data) && sizeof($data) > 0 ) {

            foreach( $data as $exp ) {

                $rest = Outlet::find($exp['expense_for']);

                $type = $exp['type'];

                if ( $type == 'cash' ) {
                    $category = 0;
                    $status = 'verified';
                } else {
                    $category = $exp['category_id'];
                    $status = 'entered';
                }
                //check expense for update
                $expense = Expense::where('guid',$exp['guid'])->first();
                if ( isset($expense) && sizeof($expense) > 0 ) {

                    if ( $expense->serversync == 0 && $exp['serversync'] == 0 ) {

                        $serv_date = strtotime($expense->updated_at);
                        $client_date = strtotime($exp['updated_at']);

                        //if client date is greater than replace row on server
                        if ( $client_date > $serv_date ) {

                            $expense->expense_for= $exp['expense_for'];
                            $expense->expense_by= $exp['expense_by'];
                            $expense->category_id = $category;
                            $expense->status = "verified";
                            $expense->verified_at = NULL;
                            $expense->serversync = 1;
                            $expense->amount= $exp['amount'];
                            $expense->description= $exp['description'];
                            $expense->expense_date= $exp['expense_date'];
                            $expense->updated_by = $exp['created_by'];
                            $result = $expense->save();

                            $exp['serversync'] = 1;

                            array_push($exp_arr,$exp);

                        } else {

                            $expense->serversync = 1;
                            $expense->save();

                            $exp_arr1['guid'] = $expense->guid;
                            $exp_arr1['expense_for'] = $expense->expense_for;
                            $exp_arr1['created_by'] = $expense->created_by;
                            $exp_arr1['expense_date'] = $expense->expense_date;
                            $exp_arr1['category_id'] = $expense->category_id;
                            $exp_arr1['created_at'] = date('Y-m-d H:i:s',strtotime($expense->created_at));
                            $exp_arr1['updated_at'] = date('Y-m-d H:i:s',strtotime($expense->updated_at));
                            $exp_arr1['description'] = $expense->description;
                            $exp_arr1['amount'] = $expense->amount;
                            $exp_arr1['status'] = "verified";
                            $exp_arr1['type'] = $expense->type;
                            $exp_arr1['serversync'] = 1;
                            $exp_arr1['verified_at'] = $expense->verified_at	;
                            $exp_arr1['expense_to'] = $expense->expense_to;
                            $exp_arr1['notes'] = $expense->notes;
                            $exp_arr1['expense_by'] = $expense->expense_by;

                            array_push($exp_arr,$exp_arr1);

                        }

                    } else if ( $expense->serversync == 1 && $exp['serversync'] == 0 ) {

                        $expense->expense_for= $exp['expense_for'];
                        $expense->expense_by= $exp['expense_by'];
                        $expense->category_id = $category;
                        $expense->status = "verified";
                        $expense->verified_at = NULL;
                        $expense->serversync = 1;
                        $expense->amount= $exp['amount'];
                        $expense->description= $exp['description'];
                        $expense->expense_date= $exp['expense_date'];
                        $expense->updated_by = $exp['created_by'];
                        $result = $expense->save();

                        $exp['status'] = "verified";
                        $exp['serversync'] = 1;

                        array_push($exp_arr,$exp);

                    } else if ( $expense->serversync == 0 && $exp['serversync'] == 1 ) {

                        $expense->serversync = 1;
                        $expense->save();

                        $exp_arr1['guid'] = $expense->guid;
                        $exp_arr1['expense_for'] = $expense->expense_for;
                        $exp_arr1['created_by'] = $expense->created_by;
                        $exp_arr1['expense_date'] = date('Y-m-d',strtotime($expense->expense_date));
                        $exp_arr1['category_id'] = $expense->category_id;
                        $exp_arr1['created_at'] = date('Y-m-d H:i:s',strtotime($expense->created_at));
                        $exp_arr1['updated_at'] = date('Y-m-d H:i:s',strtotime($expense->updated_at));
                        $exp_arr1['description'] = $expense->description;
                        $exp_arr1['amount'] = $expense->amount;
                        $exp_arr1['type'] = $expense->type;
                        $exp_arr1['status'] = $expense->status;
                        $exp_arr1['serversync'] = 1;
                        $exp_arr1['verified_at'] = date('Y-m-d H:i:s',strtotime($expense->verified_at));
                        $exp_arr1['verified_by'] = $expense->expense_to;
                        $exp_arr1['notes'] = $expense->notes;
                        $exp_arr1['expense_by'] = $expense->expense_by;

                        array_push($exp_arr,$exp_arr1);
                    }

                } else {

                    $auth_user = '';
                    if ( isset($rest->authorised_users) && $rest->authorised_users != 0 ) {
                        $auth_user = $rest->authorised_users;
                    } else {
                        continue;
                    }

                    $exp1 = new Expense();
                    $exp1->expense_for= $exp['expense_for'];
                    $exp1->guid = $exp['guid'];
                    $exp1->expense_by= $exp['expense_by'];
                    $exp1->expense_to= $auth_user;
                    $exp1->category_id = $category;
                    $exp1->status = "verified";
                    $exp1->serversync = 1;
                    $exp1->amount= $exp['amount'];
                    $exp1->type = $type;
                    $exp1->description= $exp['description'];
                    $exp1->expense_date= $exp['expense_date'];
                    $exp1->created_by = $exp['created_by'];
                    $exp1->updated_by = $exp['created_by'];
                    $result = $exp1->save();

                    $exp_arr1['guid'] = $exp['guid'];
                    $exp_arr1['expense_for'] = $exp['expense_for'];
                    $exp_arr1['created_by'] = $exp['created_by'];
                    $exp_arr1['expense_date'] = $exp['expense_date'];
                    $exp_arr1['category_id'] = $category;
                    $exp_arr1['created_at'] = date('Y-m-d H:i:s');
                    $exp_arr1['updated_at'] = date('Y-m-d H:i:s');
                    $exp_arr1['description'] = $exp['description'];
                    $exp_arr1['amount'] = $exp['amount'];
                    $exp_arr1['status'] = "verified";
                    $exp_arr1['type'] = $type;
                    $exp_arr1['serversync'] = 1;
                    $exp_arr1['verified_at'] = '';
                    $exp_arr1['expense_to'] = 0;
                    $exp_arr1['notes'] = '';
                    $exp_arr1['expense_by'] = $exp['expense_by'];

                    array_push($exp_arr,$exp_arr1);

                }

            }
        }

        return Response::json(array(
            'expense' => $exp_arr,
            'status' => 'success',
            'statuscode' => 200
        ),200);

    }

    public function sendCampaignOTP(){

        $contact=Request::json('mobile');
        $otp = Request::json('otp');

        if ( isset($contact) && isset($otp) && $otp != '' ) {

            $otp_check = Campaign::where('mobile',$contact)->where('otp',$otp)->first();

            if ( isset($otp_check) && sizeof($otp_check) > 0 ) {

                //update status
                $otp_check->verified = 1;
                $otp_check->save();

                return Response::json(array(
                    'message' => 'OTP matched successfully',
                    'campaign_id'=>(string) $otp_check->id,
                    'status' => 'verified',
                    'statuscode' => 200
                ),
                    200);

            } else {

                return Response::json(array(
                    'message' => 'OTP did not match with any record',
                    'status' => 'error',
                    'statuscode' => 435
                ),
                    435);
            }

        } else if ( isset($contact)  ) {

            $usercheck = Campaign::where('mobile',$contact)->first();

            $camp_id = '';
            $password=users::generateotp();
            if ( isset($usercheck) && sizeof($usercheck) > 0 ) {

                if ( $usercheck->verified == 1 ) {

                    return Response::json(array(
                        'message' => 'User already verified',
                        'campaign_id'=>(string) $usercheck->id,
                        'status' => 'verified',
                        'statuscode' => 200
                    ),200);

                } else {
                    $usercheck->otp = $password;
                    $usercheck->save();

                    $camp_id = $usercheck->id;
                }

            } else {

                $campaign = new Campaign();
                $campaign->mobile = $contact;
                $campaign->otp = $password;
                $campaign->save();

                $camp_id = $campaign->id;
            }

            //send otp
            users::capmpaignOTP($password,$contact);

            return Response::json(array(
                'message' => 'OTP sent successfully',
                'campaign_id'=>(string) $camp_id,
                'status' => 'success',
                'statuscode' => 200
            ),200);


        } else {

            return Response::json(array(
                'message' => 'Please enter mobile',
                'status' => 'error',
                'statuscode' => 435
            ),435);

        }

    }

    public function storeCampaignDetail() {

        $mobile = Request::get('mobile');
        $ot_name = Request::get('outlet_name');
        $ow_name = Request::get('owner_name');
        $email = Request::get('email');
        $address = Request::get('address');

        $check_mobile = Campaign::where('mobile',$mobile)->where('verified',1)->first();

        if ( isset($check_mobile) && sizeof($check_mobile) > 0 ) {

            $camp_id = $check_mobile->id;

            if ( isset($_FILES['image']) && sizeof($_FILES['image']) > 0 ) {

                $dirname = storage_path()."/Campaign_files";

                if (!file_exists($dirname)) {
                    mkdir($dirname, 0777, true);
                }

                $image_path = '';

                foreach($_FILES['image']['name'] as $key=>$val){

                    $file_name = $_FILES["image"]["name"][$key];
                    $file_tmp = $_FILES["image"]["tmp_name"][$key];

                    $file_path = storage_path()."/Campaign_files/".time()."_".$file_name;


                    if ( isset($image_path) && $image_path != '' ) {
                        $image_path .=", ".$file_path;
                    } else {
                        $image_path = $file_path;
                    }

                    $check_mobile->path = $image_path;
                    $check_mobile->save();

                    move_uploaded_file($file_tmp, $file_path);

                }

                $check_mobile->path = $image_path;
                $check_mobile->save();

                return Response::json(array(
                    'message' => 'Campaign detail updated successfully',
                    'campaign_id'=>(string) $camp_id,
                    'status' => 'success',
                    'statuscode' => 200
                ),200);

            } else {

                $check_mobile->outlet_name = $ot_name;
                $check_mobile->owner_name = $ow_name;
                $check_mobile->address = $address;
                $check_mobile->email = $email;
                $check_mobile->save();

                Queue::push('App\Commands\OwnerNotification@sendCampaignDetail', array('owner_name'=>$ow_name,'outlet_name'=>$ot_name,'mobile'=>$mobile,'email'=>$email,'address'=>$address));

            }

            return Response::json(array(
                'message' => 'Campaign detail updated successfully',
                'campaign_id'=>(string) $camp_id,
                'status' => 'success',
                'statuscode' => 200
            ),
                200);

        } else {

            return Response::json(array(
                'message' => 'Mobile number is not verified',
                'status' => 'error',
                'statuscode' => 435
            ),435);

        }


    }

    public function pinger() {

        $time = time();

        return Response::json(array(
            'timestamp' => $time,
            'status' => 'success',
            'statuscode' => 200
        ),200);

    }

    //List of all cuision in system
    Public function getAllCuisionList(){

        $cuision_types = CuisineType::all()->pluck('type','id');
        $cuision_array = array();
        $i = 0;
        foreach ($cuision_types as $id=>$type){
            $cuision_array[$i]['cuision_id'] = $id;
            $cuision_array[$i]['cuision_lable'] = $type;
            $i++;
        }

        if(isset($cuision_types) && sizeof($cuision_types)>0){
            return Response::json(array(
                'message' => 'List of Cuision',
                'status' => 'success',
                'statuscode' => 200,
                'cuision_types' => $cuision_array,
            ),
                200);
        }else{
            return Response::json(array(
                'message' => 'Error in Getting cuision list',
                'status' => 'error',
                'statuscode' => 500,
                'cuision_types' => '',
            ),
                500);
        }
    }

    public function getBillerOrder() {

        $outlet_id = Request::get('outlet_id');
        $to = date('Y-m-d H:i:s');
        $from = date('Y-m-d H:i:s',strtotime("-1 days"));

        //outlet details
        $outlet = Outlet::find($outlet_id);
        //order details
        $order_detail = order_details::where('orders.table_end_date','>=', $from)
                                        ->where('orders.table_end_date','<=', $to)
                                        ->where('orders.outlet_id','=',$outlet_id)
                                        ->Where(function($query)
                                        {
                                            $query->where('orders.invoice_no', '=', NULL)
                                                ->orWhere('orders.invoice_no', '=', "");
                                        })
                                        ->where('orders.cancelorder', '!=', 1)
                                        ->orderBy('orders.created_at', 'desc')
                                        ->get();

        //order array
        $ord_arr = array();
        if ( isset($order_detail) && sizeof($order_detail) > 0 ) {

            foreach ( $order_detail as $ord ) {

                $items = OrderItem::where('order_id',$ord->order_id)->get();

                //check order from
                $order_from = "pro";
                if ( $ord->customer_order == 1 ) {
                    $order_from = "consumer";
                }

                $item_arr = array();
                //get order items with printer id
                foreach( $items as $itm ) {

                    $printer_id = '';
                    $print_bind_check = PrinterItemBind::where('outlet_id',$outlet_id)->where('item_id',$itm->item_id)->first();
                    if ( isset($print_bind_check) && sizeof($print_bind_check) > 0 ) {
                        $printer_id = $print_bind_check->printer_id;
                    } else {

                        if ( isset($outlet->printer) && $outlet->printer != '' ) {
                            $check_print = json_decode($outlet->printer);
                            if ( isset($check_print) && sizeof($check_print) > 0 ) {
                                $printer_id = $check_print->kot_printer;
                            }
                        }

                    }

                    //get item options
                    $item_options = OrderItemOption::where('order_id',$ord->order_id)
                                                    ->where('order_item_id',$itm->id)
                                                    ->get();

                    $item_options_arr = array();
                    if ( isset($item_options) && sizeof($item_options) > 0 ) {
                        $i = 0;
                        foreach ( $item_options as $opt ) {
                            $item_options_arr[$i]['option_item_id'] = $opt->option_item_id;
                            $item_options_arr[$i]['option_item_qty'] = $opt->qty;
                            $item_options_arr[$i]['option_item_price'] = $opt->option_item_price;
                            $i++;
                        }
                    }

                    $item_detail = array(
                        "quantity"=>$itm->item_quantity,
                        "itemName"=>$itm->item_name,
                        "titleName"=>$itm->category_name,
                        "itemId"=>$itm->item_id,
                        "itemUniqueId"=>$itm->item_unique_id,
                        "printerId"=>$printer_id,
                        "itemPrice"=>$itm->item_price,
                        'item_options'=>$item_options_arr
                    );
                    array_push($item_arr,$item_detail);
                }

                $ord->items = $item_arr;
                $ord->order_from = $order_from;

                array_push($ord_arr,$ord);
            }

        }

        return Response::json(array(
            'message' => 'Order details',
            'status' => 'success',
            'orders' => $ord_arr,
            'statuscode' => 200,
        ),
            200);

    }

    public function sendWaitlistSms() {

        $username = Config::get('app.sms_api_username');
        $password = Config::get('app.sms_api_password');

        $mobile = Request::get('mobile');
        $ot_id = Request::get('outlet_id');

        $customer = Request::get('customer_name');
        $queue_no = Request::get('queue_no');

        if ( isset($customer) && $customer != '') {
        } else {
            $customer = "Patron";
        }

        $outlet = Outlet::find($ot_id);

        $message = "Dear $customer,\nThank you for your patience, you're waitlisted and your waiting # is $queue_no.\n$outlet->name";

        $message = urlencode($message);

        // Prepare data for POST request
        $data = 'Userid=' . $username . '&UserPassword=' . $password . '&PhoneNumber=91' . $mobile . "&Text=" . $message . "&GSM=" . $username;

        // Send the GET request with cURL
        $ch = curl_init('http://ip.shreesms.net/smsserver/SMS10N.aspx?'.$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        //echo $response;
        curl_close($ch);

        return Response::json(array(
            'status' => 'success',
            'statuscode' => 200
        ),200);


    }

    //Hotkey set when outlet is on selfservice mode
    public function saveHotKeyConfig(){

        $outlet_id = Request::get("outlet_id");
        $hotkey_config = Request::get("hotkey_config");

        $outlet = Outlet::find($outlet_id);

        if(isset($outlet) && sizeof($outlet)>0){
            $outlet->hotkey_config = $hotkey_config;
            $result = $outlet->save();
        }else{
            return Response::json(array(
                'message' => 'Outlet not found.',
                'status' => 'error',
                'statuscode' => 500,
            ),
                500);
        }

        if($result){
            return Response::json(array(
                'status' => 'success',
                'statuscode' => 200
            ),200);
        }else{
            return Response::json(array(
                'message' => 'Error in save HotKey Config in outlet.',
                'status' => 'error',
                'statuscode' => 500,
            ),
                500);
        }

    }


}





