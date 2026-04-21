<?php

namespace App\Http\Controllers\Api\v1;

use App\CancellationReason;
use App\City;
use App\CouponCodes;
use App\Http\Controllers\Controller;
// use App\Libraries\Image;
use App\Itemreview;
use App\MenuOption;
use App\OrderDetails;
use App\OrderCancellation;
use App\OrderCouponMappers;
use App\OutletMapper;
use App\OutletType;
use App\Owner;
// use App\PayUMoney;
use App\Printsummary;
use App\Reviews;
use App\State;
use App\Termsandcondition;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
use App\Outlet;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\MenuTitle;
use App\Menu;
use App\Status;
use App\CuisineType;
use App\OrderItem;
use App\Timeslot;
use App\Outletlatlong;
use App\OutletCuisineType;
// use App\OutletOutletType;
use App\Outletimage;
// use App\Humanreadableids;
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
use Illuminate\Contracts\Auth\Guard;

class Apicontroller extends Controller
{
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->beforeFilter('csrf', ['on' => '']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $rests = array();
        $Outlet_Outlettype = array();
        $Outlet_cuisinetype = array();
        $Outlet_detail = array();
        $start = Input::get('start');
        $limit = Input::get('limit');
        $location = Input::get('locality');
        $cuistype = Input::get('cuisine_type');
        $resttype = Input::get('restaurant_type');
        $restname = Input::get('restaurant_name');
        $start = Input::get('start');
        $limit = Input::get('limit');
        $costmin = Input::get('min_price');
        $costmax = Input::get('max_price');
        $lat = Input::get('latitude');
        $long = Input::get('longitude');
        $delivery_type = Input::get('order_type');
        $useragent = RequestFacade::header('User-Agent');

        if (isset($start) || isset($limit) || isset($location) || isset($cuistype) || isset($resttype) || isset($restname) || isset($costmin) || isset($costmax) || isset($lat) || isset($long) || isset($delivery_type)) {
            $restdetails = Outlet::searchoutlet($start, $limit, $location, $cuistype, $resttype, $restname, $costmin, $costmax, $lat, $long, $delivery_type);
            $i = 0;
            foreach ($restdetails['restaurantdetails'] as $restcuisine) {
                $restid = $restcuisine->id;

                $restinfo = Outlet::find($restid);

                $restcui = $restinfo->outletcuisinetype->all();
                $restresttype = $restinfo->outlettypemapper->all();
                $j = 0;
                $cuisinetype = array();
                foreach ($restcui as $recui) {

                    $rest = CuisineType::cuisinetypebyid($recui->cuisine_type_id);

                    if (!empty($rest)) {
                        $retype = $rest['type'];
                    } else {
                        $retype = "";
                    }
                    $cuisinetype[$j] = $retype;
                    $j++;
                }
                $k = 0;

                $Outlet_type = array();
                foreach ($restresttype as $resrest) {
                    $cui = OutletType::Outlettypebyid($resrest->outlet_type_id);

                    if (!empty($cui)) {
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
                if (isset($restcuisine->locality) && $restcuisine->locality != '' && $restcuisine->locality != 0) {
                    // print_r($restcuisine->locality);
                    $loca = locality::getlocalitybyid($restcuisine->locality);

                    $locality = $loca->locality;
                } else {
                    $locality = '';
                }
                if (isset($restcuisine->takeaway_cost) && $restcuisine->takeaway_cost != "" && $restcuisine->takeaway_cost != 0) {
                    $minimumordercost = $restcuisine->takeaway_cost;
                } else {
                    $minimumordercost = '';
                }
                // print_r();exit;
                if (isset($restdetails['distancearray'][$restid])) {
                    $Outlet_detail[$i] = array(
                        'restaurant_id' => $restcuisine->id,
                        'name' => ucfirst($restcuisine->name),
                        'cuisine_type' => $cuisinetype,
                        'restaurant_type' => $Outlet_type,
                        'address' => $restcuisine->address,
                        'phone_number' => $restcuisine->contact_no,
                        'webaddress' => $restcuisine->url,
                        'avgcostoftwo' =>  $restcuisine->avg_cost_of_two,
                        'min_order_price' => $minimumordercost,
                        'locality' => $locality,
                        'famous_for' => $restcuisine->famous_for,
                        'restaurant_image' => $Outlet_image,
                        'distance' => round($restdetails['distancearray'][$restid], 1) . " km"

                    );
                } else {
                    $Outlet_detail[$i] = array(
                        'restaurant_id' => $restcuisine->id,
                        'name' => ucfirst($restcuisine->name),
                        'cuisine_type' => $cuisinetype,
                        'restaurant_type' => $Outlet_type,
                        'address' => $restcuisine->address,
                        'phone_number' => $restcuisine->contact_no,
                        'webaddress' => $restcuisine->url,
                        'avgcostoftwo' => $restcuisine->avg_cost_of_two,
                        'min_order_price' => $minimumordercost,
                        'locality' => $locality,
                        'famous_for' => $restcuisine->famous_for,
                        'restaurant_image' => $Outlet_image,
                        'distance' => ''

                    );
                }
                $i++;
            }
            $sortedarray = array();
            foreach ($Outlet_detail as $key => $value) {
                $sortedarray[$key] = $value['distance'];
            }
            array_multisort($sortedarray, SORT_ASC, $Outlet_detail);
            return Response::json(
                array(
                    'status' => 'success',
                    'statuscode' => 200,
                    'count' => count($restdetails),
                    'restaurants' => $Outlet_detail
                ),
                200
            );
            // }
        } else {
            $Outlets = Outlet::where('active', '!=', serialize(0))->get();
            $i = 0;
            foreach ($Outlets as $restcuisine) {
                $restid = $restcuisine->id;
                $restinfo = Outlet::find($restid);

                $restcui = $restinfo->outletcuisinetype;
                $restresttype = $restinfo->outlettypemapper;

                $cuisinetype = array();
                foreach ($restcui as $recui) {
                    $rest = CuisineType::cuisinetypebyid($recui->cuisine_type_id);
                    if (!empty($rest)) {
                        $resttype = $rest['type'];
                    } else {
                        $resttype = "";
                    }
                    if (!in_array($resttype, $cuisinetype)) {
                        array_push($cuisinetype, $resttype);
                    }
                }
                $Outlet_type = array();
                foreach ($restresttype as $resrest) {
                    $cui = OutletType::outlettypebyid($resrest->outlet_type_id);
                    if (!empty($cui)) {
                        $cutype = $cui['type'];
                    } else {
                        $cutype = "";
                    }
                    if (!in_array($cutype, $Outlet_type)) {
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

                if (isset($restcuisine->locality) && $restcuisine->locality != '' && $restcuisine->locality != 0) {
                    $loca = locality::getlocalitybyid($restcuisine->locality);

                    $locality = $loca->locality;
                } else {
                    $locality = '';
                }
                if (isset($restcuisine->takeaway_cost) && $restcuisine->takeaway_cost != "" && $restcuisine->takeaway_cost != 0) {
                    $min_cost = $restcuisine->takeaway_cost;
                } else {
                    $min_cost = '';
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
                    'min_order_price' => $min_cost,
                    'locality' => $locality,
                    'famous_for' => $restcuisine->famous_for,
                    'restaurant_image' => $Outlet_image,
                );
                $i++;
            }
            return Response::json(
                array(
                    'message' => 'List of all added Outlets',
                    'status' => 'success',
                    'statuscode' => 200,
                    'restaurants' => $Outlet_detail
                ),
                200
            );
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
        if (!empty($Outletimage)) {
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
    public function outletmenu(Request $request)
    {
        $restmenu = array();
        // $Outletid = Input::get('restaurant_id');
        $Outletid = $request->input('restaurant_id');
        $menutitle = MenuTitle::getmenutitlebyrestaurantid($Outletid);
        $useragent = RequestFacade::header('User-Agent');
        foreach ($menutitle as $menudetails) {
            $menutitle = $menudetails->title;
            $menud = Menu::getmenubymenutitleid($menudetails->id);
            $i = 0;
            foreach ($menud as $cui) {
                $menuoption = MenuOption::where('menu_id', $cui['id'])->get();
                $cuisinetype = '';
                if (isset($cui->food) && $cui->food != '') {
                    $foodtype = $cui->food;
                } else {
                    $foodtype = '';
                }
                if ($cui->menu_title_id == $menudetails->id) {
                    $restmenu[$menutitle][$i] = array(
                        'item_id' => $cui->id,
                        'item' => $cui->item,
                        'price' => (int)$cui->price,
                        'details' => $cui->details,
                        'cuisinetype' => $cuisinetype,
                        'options' => $cui->options,
                        'foodtype' => $foodtype,
                        'active' => $cui->active,
                        'options' => $menuoption,
                        'like' => $cui->like
                    );
                }
                $i++;
            }
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE || strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE) {
            return view('webview.restaurantmenu', array('restaurantmenu' => $restmenu));
        } else {
            return Response::json(
                array(
                    'message' => 'List of menu',
                    'status' => 'success',
                    'statuscode' => 200,
                    'menu' => $restmenu
                ),
                200
            );
        }
    }
    //for creating url of Outlet gallery
    public function getGallery($id, $size)
    {
        $Image = new Image();
        $response = array();
        $Outletimage = Outletimage::find($id);
        if (!empty($Outletimage)) {
            if ($Outletimage->image_name != '') {
                // Make a new response out of the contents of the file
                // We refactor this to use the image resize function.
                // Set the response status code to 200 OK
                $response = Response::make($Image->resizegalleryimage($Outletimage->image_name, $size), 200);

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
    public function outletinformation(Request $request)
    {
        $restinfo = array();
        $outlet_id = $request->input('restaurant_id');
        $Outlets = Outlet::find($outlet_id);
        $states = State::findstates($Outlets->state_id);
        if (!empty($states)) {
            $statename = $states->name;
        } else {
            $statename = "";
        }
        $cities = City::findcity($Outlets->city_id);
        if (!empty($cities)) {
            $cityname = $cities->name;
        } else {
            $cityname = "";
        }
        $Outlet_cuisine_type = $Outlets->outletcuisinetype->all();
        $i = 0;
        $cuitype = array();
        foreach ($Outlet_cuisine_type as $cuisine_type) {
            $cuisinename = CuisineType::where('id', $cuisine_type->cuisine_type_id)->get();
            //  print_r();exit;
            $cuitype[$i] = $cuisinename[0]->type;
            $i++;
        }
        $Outlet_Outlet_type = $Outlets->outlettypemapper->all();
        $j = 0;
        $resttype = array();
        foreach ($Outlet_Outlet_type as $rest_type) {
            $restname = OutletType::Outlettypebyid($rest_type->outlet_type_id);
            $resttype[$j] = $restname->type;
            $j++;
        }
        $Outlet_latlong = array();
        $rest_latlong = Outletlatlong::getouletlatlongbyoutletid($outlet_id); //get rest_latlong table
        if (!empty($rest_latlong)) {
            $latitude = $rest_latlong[0]->latitude;
            $longitude = $rest_latlong[0]->longitude;
        } else {
            $latitude = '';
            $longitude = '';
        }
        $time = array();
        $timeslots = Timeslot::gettimeslotbyoutletid($outlet_id);
        $k = 0;
        if (!empty($timeslots)) {
            foreach ($timeslots as $timeslot) {
                if ($timeslot->from_time != "" || $timeslot->to_time) {
                    $time[$k] = array(
                        'from_time' => $timeslot->from_time,
                        'to_time' => $timeslot->to_time
                    );
                } else {
                    $time = '';
                }
                $k++;
            }
        }
        if (isset($Outlets->locality) && $Outlets->locality != "" && $Outlets->locality != 0) {
            $loca = locality::getlocalitybyid($Outlets->locality);
            $locality = $loca->locality;
        } else {
            $locality = '';
        }
        $restimages = Outletimage::getoutletimagesbyoutletid($outlet_id);
        $l = 0;
        $allimages = array();
        if (!empty($restimages)) {
            foreach ($restimages as $restimgname) {
                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                    $_SERVER['HTTPS'] = "https";
                } else {
                    $_SERVER['HTTPS'] = "http";
                }
                if ($restimgname->image_name != "") {
                    $Outlet_image = $_SERVER['HTTPS'] . "://" . $_SERVER['HTTP_HOST'] . '/gallery/' . $restimgname->id . '/small';
                    $Outlet_main = $_SERVER['HTTPS'] . "://" . $_SERVER['HTTP_HOST'] . '/gallery/' . $restimgname->id . '/big';
                } else {
                    $Outlet_image = '';
                    $Outlet_main = '';
                }
                $allimages[$l] = array(
                    "thumb" => $Outlet_image,
                    "original" => $Outlet_main
                );
                $l++;
            }
        }
        $restinfo = array(
            'restaurant_id' => $Outlets->id,
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
            'restaurant_type' => $resttype,
            'cuisine_type' => $cuitype,
            'timeslot' => $time,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'restaurant_images' => $allimages
        );
        echo "restinfo <pre>";
        print_r($restinfo);
        echo "</pre>";
        exit;
        return Response::json(
            array(
                'message' => 'Information of Outlet',
                'status' => 'success',
                'statuscode' => 200,
                'restaurant' => $restinfo
            ),
            200
        );
    }

    //order added from here in backend
    public function orderdetails(Request $request)
    {
        $order = '';
        $flag = '';
        $order_from_web = null;
        if (isset($order_from_web)) {
            // $order = $order_from_web['order'];
            $order = json_decode($request->input('order'), true);
            $flag = $order['flag'];
        } else {
            $order = $request->input('order');
        }
        $array = array();
        $restaurant_id = $order['restaurant_id'] ?? null;
        $Outlet = Outlet::Outletbyid($restaurant_id);
        // $Outlet = Outlet::Outletbyid($order['restaurant_id']);
        if (isset($Outlet)) {
            $startingstatus = Status::getallstatusofOutlet($order['restaurant_id']);
            $lastindex = count($startingstatus) - 1;
            if (isset($startingstatus)) {
                if ($order['order_type'] == "dine_in") {
                    $status = $startingstatus[$lastindex]->status;
                } else {
                    $status = $startingstatus[0]->status;
                }
            } else {
                $status = '';
            }
            $order_ids = OrderDetails::getorderid();
            $suborder_id = OrderDetails::getorderidofrestaurant($order['restaurant_id']);
            $a = $Outlet->lists('code');
            if (isset($order['mobile_number'])) {
                DB::table('orders')->where('user_mobile_number', $order['mobile_number'])->update(array('device_id' => $order['device_id']));
            }
            // $saveorder = OrderDetails::insertorderdetails($a, $order_ids, $order, $status, $suborder_id);
            $saveorder = OrderDetails::insertorderdetails($a, $order_ids, $order, $status, $suborder_id, '', '');

            foreach ($order['menu_item'] as $asd) {
                $orderid = OrderItem::insertmenuitemoforders($saveorder['id'], $asd);
            }
            // Queue::push('App\Commands\MailNotification@getorderdetails', array('orderdetails'=>$saveorder));
            $date = $saveorder['order_date'];
            $date = str_replace('/', '-', $date);
            $orddate = date("F j, Y g:i a", strtotime($date));
            Queue::push('App\Commands\OwnerNotification@getownernotification', array('outlet_id' => $order['restaurant_id']));
            if (isset($flag) && $flag == 'webapp_order') {
                return 'success';
            } else {
                return Response::json(
                    array(
                        'message' => 'Order Placed Successfully.Go To My Order To Check Your Status',
                        'status' => 'success',
                        'statuscode' => 200,
                        'local_id' => $order['local_id'],
                        'server_id' => $suborder_id,
                        'order_date' => $orddate,
                        'status' => ucfirst($status)
                    ),
                    200
                );
            }
        } else {
            return Response::json(
                array(
                    'message' => 'Outlet Not found',
                    'status' => 'Failure',
                    'statuscode' => 401,
                ),
                401
            );
        }
    }
    //new mobileend user added from here
    public function addcustomer(Request $request)
    {
        $contact = $request->input('contact_no');
        $pass = $request->input('password');
        if (isset($contact)) {
            //for finding customer by phone_number
            $usercheck = users::findcustomerbyphonenumber($contact);
            $num = users::generateotp();
            if (empty($usercheck)) {
                $id = users::getidofaddedcustomer($contact, $pass, $num);
                users::sendotpbymessage($num, $contact);
                return Response::json(
                    array(
                        'message' => 'User is Added successfully',
                        'userid' => (string) $id,
                        'otp' => $num,
                        'status' => 'NotVerified',
                        'statuscode' => 200,
                    ),
                    200
                );
            } else if ($usercheck->lists('status')[0] == "NotVerified") {
                users::updateotp($contact, $num);
                users::sendotpbymessage($num, $contact);
                return Response::json(
                    array(
                        'message' => 'User Added successfully',
                        'userid' => $usercheck->lists('id')[0],
                        'otp' => $num,
                        'status' => 'NotVerified',
                        'statuscode' => 200,
                    ),
                    200
                );
            } else {
                return Response::json(
                    array(
                        'message' => 'User is Already Registered',
                        'userid' => $usercheck->lists('id')[0],
                        'status' => $usercheck->lists('status')[0],
                        'statuscode' => 431,
                    ),
                    200
                );
            }
        }
    }
    //for the verification of otp sent to user in backend database
    public function verifyotp(Request $request)
    {
        $id = $request->input('user_id');
        $otp = $request->input('user_otp');
        $user = users::selectotp($id);
        if ($user->otp == $otp) {
            users::updateotpstatus($id);
            return Response::json(
                array(
                    'message' => 'User Verified Successfully',
                    'status' => "Verified",
                    'statuscode' => 200,
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'message' => 'Invalid OTP',
                    'status' => "NotVerified",
                    'statuscode' => 432,
                ),
                200
            );
        }
    }
    //for login request from mobileend
    public function login(Request $request)
    {
        $mob = Input::json('user_mobile');
        $pass = Input::json('user_password');
        $user = DB::table('users');

        $avail = $user->where('mobile_number', $mob)->get();
        $pass = $user->where('mobile_number', $mob)->where('password', $pass)->get();
        $optverified = $user->where('mobile_number', $mob)->where('status', 'Verified')->get();
        if (count($avail) == 0) {
            return Response::json(
                array(
                    'message' => 'User Does Not Exists',
                    'statuscode' => 401,
                    'status' => 'Mobile is not registered. Please signup.'
                ),
                200
            );
        } else if (count($pass) == 0) {
            return Response::json(
                array(
                    'message' => 'Invalid Password',
                    'statuscode' => 401,
                    'status' => 'Authentication Failed'
                ),
                200
            );
        } else if (count($optverified) == 0) {
            return Response::json(
                array(
                    'message' => 'OTP Not Verified Sign Up Again',
                    'statuscode' => 405,
                    'status' => 'Authentication Failed'
                ),
                200
            );
        } else {
            if (isset($pass[0]->first_name) && $pass[0]->first_name != "") {
                $firstname = $pass[0]->first_name;
            } else {
                $firstname = '';
            }

            if (isset($pass[0]->last_name) && $pass[0]->last_name != "") {
                $lastname = $pass[0]->last_name;
            } else {
                $lastname = '';
            }
            if (isset($pass[0]->email) && $pass[0]->email != "") {
                $email = $pass[0]->email;
            } else {
                $email = '';
            }

            if (isset($pass[0]->mobile_no) && $pass[0]->mobile_no != "") {
                $contactnumber = $pass[0]->mobile_no;
            } else {
                $contactnumber = '';
            }

            if (isset($pass[0]->gender) && $pass[0]->gender != "") {
                $gender = $pass[0]->gender;
            } else {
                $gender = '';
            }

            return Response::json(
                array(
                    'message' => 'Valid User',
                    'statuscode' => 200,
                    'status' => 'Success',
                    'userid' => (string) $pass[0]->id,
                    'name' => $firstname . ' ' . $lastname,
                    'email' => $email,
                    'contact_number' => $contactnumber,
                    'gender' => $gender
                ),
                200
            );
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updatedetail(Request $request)
    {
        $serverid = $request->input('serverid');
        //$mobile = $request->input('user_mobile');
        $firstname = $request->input('first_name');
        //$lastname = $request->input('last_name');
        $email = $request->input('email');
        //$password = $request->input('password');
        //$gender = $request->input('gender');
        // print_r(\Illuminate\Support\Facades\RequestFacade::get('image'));exit;
        // $image=  RequestFacade::file('image');
        // print_r($image);exit;
        $update = array();
        $user = DB::table('users');
        if (isset($firstname)) {
            $update['first_name'] = $firstname;
        }
        if (isset($lastname)) {
            $update['last_name'] = $lastname;
        }
        if (isset($email)) {
            $update['email'] = $email;
        }
        if (isset($password)) {
            $update['password'] = $password;
        }
        if (isset($gender)) {
            $update['gender'] = $gender;
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
        $affectedRows = $user->where('id', $serverid)->update($update);

        return Response::json(
            array(
                'message' => 'User Data Updated Successfully',
                'statuscode' => 200,
            ),
            200
        );
    }
    //not needed now
    //    public function resendotp(Request $request){
    //        $mobile=Input::json('user_mobile');
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
    public function forgotpassword(Request $request)
    {
        $mobile = $request->input('user_mobile');
        echo "mobile" . $mobile;
        exit;
        if (isset($mobile)) {
            $num = users::generateotp();
            $affectedRows = users::updateotp($mobile, $num);

            users::sendotpoffogotpassword($num, $mobile);
        }
        if ($affectedRows > 0) {
            return Response::json(
                array(
                    'message' => 'OTP For Forgot Password Sent Successfully',
                    'userid' => (string)$affectedRows->id,
                    'otp' => $num,
                    'statuscode' => 200,
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'message' => 'User Does Not Exist',
                    'statuscode' => 401,
                ),
                200
            );
        }
    }

    public function updatepassword(Request $request)
    {
        $mobile = $request->input('user_mobile');
        $getotp = $request->input('otp');
        $password = $request->input('updated_password');
        if (isset($mobile)) {
            $affectedRows = users::updatepassword($mobile, $getotp, $password);
            if ($affectedRows > 0) {
                return Response::json(
                    array(
                        'message' => 'Password Updated Successfully',
                        'userid' => $affectedRows,
                        'statuscode' => 200,
                    ),
                    200
                );
            } else {
                return Response::json(
                    array(
                        'message' => 'Please Add Valid User Contact',
                        'statuscode' => 434,
                    ),
                    200
                );
            }
        }
    }
    public function addresschange(Request $request)
    {
        $mobile = $request->input('user_mobile');
        $address = $request->input('address');
        $locality = $request->input('locality');
        $pincode = $request->input('pincode');
        $addressuniq = $request->input('addressid');

        if (isset($mobile)) {
            if (isset($address)) {
                $adressarray['address'] = $address;
            }
            if (isset($locality)) {
                $adressarray['locality'] = $locality;
            }
            if (isset($pincode)) {
                $adressarray['pincode'] = $pincode;
            }
            if ($addressuniq == '') {
                $id = users::getidofuserinserted($mobile);
                if ($id !== null) {
                    $adressarray['customer_id'] = (string) $id[0]->id;
                    $addressid = address::insertaddress($adressarray);
                    return Response::json(
                        array(
                            'message' => 'Address Added Successfully',
                            'addressid' => (string) $addressid,
                            'statuscode' => 200,
                        ),
                        200
                    );
                } else {
                    return Response::json(
                        array(
                            'message' => 'Please Add Valid User Contact',
                            'statuscode' => 434,
                        ),
                        200
                    );
                }
            } else {
                echo "Else Else <pre>";
                address::updateaddress($addressuniq, $adressarray);
                return Response::json(
                    array(
                        'message' => 'Address Updated Successfully',
                        'addressid' => $addressuniq,
                        'statuscode' => 200,
                    ),
                    200
                );
            }
        }
    }
    public function owneroutlet(Request $request)
    {
        // $username = Input::json('owner_name');
        // $pass = Input::json('owner_pass');
        // $device_id = Input::json('device_id');
        $username = $request->input('owner_name');
        $pass = $request->input('owner_pass');
        $device_id = $request->input('device_id');

        $maxdt = Carbon::now();
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';

        if ($field == 'email') {
            $user = Owner::where('email', $username)->first();
        } else {
            $user = Owner::where('user_name', $username)->first();
        }
        // echo "User <pre>"; print_r($user); echo "</pre>"; exit;
        if ($user) {
            $a = Hash::check($pass, $user->password);
        } else {
            $a = false;
        }
        if ($a == true) {
            if ($field == 'email') {
                if ($this->auth->attempt(array('email' => $username, 'password' => $pass))) {
                    $session_id = ''; // Session::getId();
                } else {
                    $session_id = '';
                }
            } else {
                if ($this->auth->attempt(array('user_name' => $username, 'password' => $pass))) {
                    $session_id = ''; // Session::getId();
                } else {
                    $session_id = '';
                }
            }

            if (isset($device_id) && $device_id != '') {
                DB::table('owners')->where($field, '=', $username)->update(array('device_id' => $device_id));
            }
            $islogin = Owner::where($field, '=', $username)->first();
            //            $where = ['owner_id' => $user->id,'active' => 'Yes'];
            //            $Outlet=Outlet::where($where)->get();
            $where = ['owner_id' => $user->id];
            $mappers = OutletMapper::getOutletIdByOwnerId($where);
            $mapper_arr = [];
            foreach ($mappers as $mapper) {
                $mapper_arr[] = $mapper['outlet_id'];
            }
            // foreach ($mappers as $mapper) {
            //     $mapper_arr[] = $mapper['outlet_id'];
            // }
            $Outlet = Outlet::whereIn('id', $mapper_arr)->where('active', 'Yes')->get();
            //            $Outlet=Outlet::where('owner_id',$user->id)->get();

            $i = 0;
            // foreach ($Outlet as $outlet) {
            //     $outlet[$i];
            //     $i++;
            // }
            $i = $Outlet->count();
            if ($i == 1) {
                $maxdt = [];
                array_push($maxdt, Carbon::now());
                // $restaurant = $user->outlet->lists('id');
                $restaurant = $user->outlet->pluck('id');
                $restaurant_id = $restaurant[0];
                $retname = Outlet::find($restaurant_id);
                $islogin = Owner::where($field, '=', $username)->first();
                $tax = Tax::where('outlet_id', $restaurant_id)->get();
                $star = [];
                $orders = $retname->orderdetail()->where('status', '!=', 'delivered')->where('cancelorder', '!=', 1)->orderBy('created_at', 'asc')->get();

                $totalprice = [];
                $rating = [];
                $uniquepricearray = [];
                $othercount = [];
                $items = [];
                // if (count($orders) > 0) {
                if ($orders->isNotEmpty()) {
                    $forrating = $retname->orderdetail()->where('status', '=', 'delivered')->orderBy('created_at', 'desc')->get();
                    $otheroutletordercount = DB::table('orders')->where('status', '=', 'delivered')->where('outlet_id', '!=', $restaurant_id)->get();
                    $uniqueprice = DB::table('orders')->where('status', '=', 'delivered')->get();
                    if (!empty($uniqueprice)) {
                        foreach ($uniqueprice as $k1 => $v1) {
                            if (!array_key_exists($v1->user_mobile_number, $uniquepricearray)) {
                                //array_push($uniquepricearray,array($order->user_mobile_number=>number_format($totalpr, 2, '.', '')));
                                $uniquepricearray[$v1->user_mobile_number] = $v1->totalprice;
                            }
                            if ($uniquepricearray[$v1->user_mobile_number] < $v1->totalprice) {
                                $uniquepricearray[$v1->user_mobile_number] = $v1->totalprice;
                            }
                        }
                    }

                    if (!empty($otheroutletordercount)) {
                        foreach ($otheroutletordercount as $k => $v) {
                            if (!array_key_exists($v->user_mobile_number, $rating)) {
                                $othercount[$v->user_mobile_number] = 1;
                            } else {
                                $othercount[$v->user_mobile_number] = $othercount[$v->user_mobile_number] + 1;
                            }
                        }
                    }
                    if (!empty($forrating)) {
                        foreach ($forrating as $key => $value) {
                            if (!array_key_exists($value->user_mobile_number, $rating)) {
                                $rating[$value->user_mobile_number] = 1;
                            } else {
                                $rating[$value->user_mobile_number] = $rating[$value->user_mobile_number] + 1;
                            }
                            //$totalprice[$order->order_id]=$totalpr;
                            // echo in_array($order->user_mobile_number,$uniquepricearray);
                        }
                    }
                    foreach ($orders as $key => $order) {
                        $printcount = Printsummary::where('order_id', $order->suborder_id)->where('order_created_at', $order->created_at)->first();
                        if ($printcount) {
                            $order->printcount = $printcount['print_number'];
                        } else {
                            $order->printcount = 0;
                        }
                        if (isset($rating[$order->user_mobile_number])) {
                            $order->rating = $rating[$order->user_mobile_number];
                        } else {
                            $order->rating = '';
                        }
                        if (isset($othercount[$order->user_mobile_number])) {
                            $order->othercount = $othercount[$order->user_mobile_number];
                        } else {
                            $order->othercount = '';
                        }
                        if (isset($uniquepricearray[$order->user_mobile_number])) {
                            $order->maxprice = $uniquepricearray[$order->user_mobile_number];
                        } else {
                            $order->maxprice = '';
                        }
                        $item = OrderItem::where('order_id', $order->order_id)->get();
                        $totalpr = 0;
                        $it = array();
                        foreach ($item as $t) {
                            $itemnew = $t->menuitem;
                            $madt = date("Y-m-d H:i:s", strtotime($order->created_at));
                            array_push($it, array("item" => $itemnew->item, "quantity" => $t->item_quantity, "price" => $itemnew->price, "suborder_id" => $order->suborder_id, "created_at" => $madt, "item_options" => $t->options, "item_options_price" => $t->item_options_price));
                            if (isset($itemnew['price']) && $itemnew['price'] != '') {
                                $totalpr += $t->item_quantity * $itemnew['price'];
                            } else {
                                $totalpr += 0;
                            }
                        }
                        array_push($items, $it);
                        $star[$order->order_id] = DB::table('orders')->where('user_mobile_number', $order->user_mobile_number)->where('status', '=', 'delivered')->count();

                        array_push($totalprice, array($order->suborder_id => $order->totalcost_afterdiscount));

                        if ($key == 0) {
                            $maxdt[0] = $order->created_at;
                        }
                    }
                    // print_r();exit;
                }
                // MENU
                $feedback = DB::table('feedback')->where('outlet_id', '=', $restaurant_id)->get();
                $menu = Apicontroller::getoutletmemu($restaurant_id);
                return Response::json(
                    array(
                        'message' => 'Valid User',
                        'statuscode' => 200,
                        'status' => 'Success',
                        'userid' => (string) $user->id,
                        'orders' => $orders->toArray(),
                        'resid' => (string) $restaurant_id,
                        'items' => $items,
                        'total' => $totalprice,
                        'maxdt' => $maxdt,
                        'rating' => $rating,
                        'maxprice' => $uniquepricearray,
                        'othercount' => $othercount,
                        'ownername' => $islogin->user_name,
                        'outletname' => $retname->name,
                        'outlet_address' => $retname->address,
                        'contact_no' => $retname->contact_no,
                        'tin_number' => $retname->tinno,
                        'servicetax_number' => $retname->servicetax_no,
                        'service_tax' => $retname->service_tax,
                        'vat' => $retname->vat,
                        'vat_date' => $retname->vat_date,
                        'invoice_digit' => $retname->invoice_digit,
                        'outlet_code' => $retname->code,
                        'outlet_count' => $i,
                        'restmenu' => $menu,
                        'feedback' => $feedback
                    ),
                    200
                );
            } else {
                return Response::json(
                    array(
                        'message' => 'List Of all Outlet',
                        'outlet_details' => $Outlet,
                        'statuscode' => 200,
                        'outlet_count' => $i,
                    ),
                    200
                );
            }
        } else {
            return Response::json(
                array(
                    'message' => 'Your outlet is not added in Foodklub.',
                    'statuscode' => 436,
                ),
                200
            );
        }
    }
    public function getoutletmemu($restaurant_id)
    {
        $restmenu = array();
        $Outletid = $restaurant_id;
        $menutitle = MenuTitle::getmenutitlebyrestaurantid($Outletid);

        foreach ($menutitle as $menudetails) {
            $menutitle = $menudetails->title;
            //print_r($menutitle);exit;
            $menud = Menu::getmenubymenutitleid($menudetails->id);

            $i = 0;

            foreach ($menud as $cui) {
                $menuoption = MenuOption::where('menu_id', $cui->id)->get();
                $cuisinetype = '';
                if (isset($cui->food) && $cui->food != '') {
                    $foodtype = $cui->food;
                } else {
                    $foodtype = '';
                }
                if ($cui->menu_title_id == $menudetails->id) {
                    $restmenu[$menutitle][$i] = array(
                        'item_id' => $cui->id,
                        'item' => $cui->item,
                        'price' => (int)$cui->price,
                        'details' => $cui->details,
                        'cuisinetype' => $cuisinetype,
                        'options' => $cui->options,
                        'foodtype' => $foodtype,
                        'active' => $cui->active,
                        'options' => $menuoption,
                        'like' => $cui->like
                    );
                }
                $i++;
            }
        }
        return $restmenu;
    }
    public function ownerlogin(Request $request)
    {
        $username = Input::json('owner_name');
        $pass = Input::json('owner_pass');
        $device_id = Input::json('device_id');
        $restaurant_id = Input::json('resid');
        $input = Input::all();
        // print_r($input);exit;
        $maxdt = Carbon::now();
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';
        if ($field == 'email') {
            $user = Owner::where('email', $username)->first();
        } else {
            $user = Owner::where('user_name', $username)->first();
        }
        if (count($user) > 0) {
            $a = Hash::check($pass, $user->password);
        } else {
            $a = false;
        }
        if ($a == true) {
            if ($field == 'email') {
                if ($this->auth->attempt(array('email' => $username, 'password' => $pass))) {
                    $session_id = ''; // Session::getId();
                } else {
                    $session_id = '';
                }
            } else {
                if ($this->auth->attempt(array('user_name' => $username, 'password' => $pass))) {
                    $session_id = ''; // Session::getId();
                } else {
                    $session_id = '';
                }
            }
            $maxdt = [];
            array_push($maxdt, Carbon::now());
            $retname = Outlet::find($restaurant_id);
            if (isset($device_id) && $device_id != '') {
                $device_id .= ',' . $user->device_id;
                DB::table('owners')->where($field, '=', $username)->update(array('device_id' => $device_id));
            }
            $islogin = Owner::where($field, '=', $username)->first();
            $tax = Tax::where('outlet_id', $restaurant_id)->get();
            $star = [];
            $orders = $retname->orderdetail()->where('status', '!=', 'delivered')->where('cancelorder', '!=', 1)->orderBy('created_at', 'asc')->get();

            $totalprice = [];
            $rating = [];
            $uniquepricearray = [];
            $othercount = [];
            $items = [];
            $printcount = [];
            if (count($orders) > 0) {
                $forrating = $retname->orderdetail()->where('status', '=', 'delivered')->orderBy('created_at', 'desc')->get();
                $otheroutletordercount = DB::table('orders')->where('status', '=', 'delivered')->where('outlet_id', '!=', $restaurant_id)->get();
                $uniqueprice = DB::table('orders')->where('status', '=', 'delivered')->get();
                if (!empty($uniqueprice)) {
                    foreach ($uniqueprice as $k1 => $v1) {
                        if (!array_key_exists($v1->user_mobile_number, $uniquepricearray)) {
                            //array_push($uniquepricearray,array($order->user_mobile_number=>number_format($totalpr, 2, '.', '')));
                            $uniquepricearray[$v1->user_mobile_number] = $v1->totalprice;
                        }
                        if ($uniquepricearray[$v1->user_mobile_number] < $v1->totalprice) {
                            $uniquepricearray[$v1->user_mobile_number] = $v1->totalprice;
                        }
                    }
                }
                if (!empty($otheroutletordercount)) {
                    foreach ($otheroutletordercount as $k => $v) {
                        if (!array_key_exists($v->user_mobile_number, $rating)) {
                            $othercount[$v->user_mobile_number] = 1;
                        } else {
                            $othercount[$v->user_mobile_number] = $othercount[$v->user_mobile_number] + 1;
                        }
                    }
                }
                if (!empty($forrating)) {
                    foreach ($forrating as $key => $value) {

                        if (!array_key_exists($value->user_mobile_number, $rating)) {
                            $rating[$value->user_mobile_number] = 1;
                        } else {
                            $rating[$value->user_mobile_number] = $rating[$value->user_mobile_number] + 1;
                        }
                        //$totalprice[$order->order_id]=$totalpr;

                        // echo in_array($order->user_mobile_number,$uniquepricearray);

                    }
                }
                foreach ($orders as $key => $order) {
                    $printcount = Printsummary::where('order_id', $order->suborder_id)->where('order_created_at', $order->created_at)->first();
                    if (!empty($printcount)) {
                        $order->printcount = $printcount['print_number'];
                    } else {
                        $order->printcount = 0;
                    }
                    if (isset($rating[$order->user_mobile_number])) {
                        $order->rating = $rating[$order->user_mobile_number];
                    } else {
                        $order->rating = '';
                    }
                    if (isset($othercount[$order->user_mobile_number])) {
                        $order->othercount = $othercount[$order->user_mobile_number];
                    } else {
                        $order->othercount = '';
                    }
                    if (isset($uniquepricearray[$order->user_mobile_number])) {
                        $order->maxprice = $uniquepricearray[$order->user_mobile_number];
                    } else {
                        $order->maxprice = '';
                    }
                    $item = OrderItem::where('order_id', $order->order_id)->get();
                    $totalpr = 0;
                    $it = array();
                    foreach ($item as $t) {

                        $itemnew = $t->menuitem;

                        $madt = date("Y-m-d H:i:s", strtotime($order->created_at));

                        array_push($it, array("item" => $itemnew->item, "quantity" => $t->item_quantity, "price" => $itemnew->price, "suborder_id" => $order->suborder_id, "created_at" => $madt, "item_options" => $t->item_options, "item_options_price" => $t->item_options_price));

                        if (isset($itemnew['price']) && $itemnew['price'] != '') {
                            $totalpr += $t->item_quantity * $itemnew['price'];
                        } else {
                            $totalpr += 0;
                        }
                    }
                    array_push($items, $it);
                    $star[$order->order_id] = DB::table('orders')->where('user_mobile_number', $order->user_mobile_number)->where('status', '=', 'delivered')->count();

                    array_push($totalprice, array($order->suborder_id => $order->totalcost_afterdiscount));

                    if ($key == 0) {
                        $maxdt[0] = $order->created_at;
                    }
                }
                // print_r();exit;
            }


            $feedback = DB::table('feedback')->where('outlet_id', '=', $restaurant_id)->get();
            $menu = Apicontroller::getoutletmemu($restaurant_id);

            return Response::json(
                array(
                    'message' => 'Valid User',
                    'statuscode' => 200,
                    'status' => 'Success',
                    'userid' => (string) $user->id,
                    'orders' => $orders->toArray(),
                    'resid' => (string) $restaurant_id,
                    'items' => $items,
                    'total' => $totalprice,
                    'maxdt' => $maxdt,
                    'rating' => $rating,
                    'maxprice' => $uniquepricearray,
                    'othercount' => $othercount,
                    'ownername' => $islogin->user_name,
                    'outletname' => $retname->name,
                    'outlet_address' => $retname->address,
                    'contact_no' => $retname->contact_no,
                    'tin_number' => $retname->tinno,
                    'servicetax_number' => $retname->servicetax_no,
                    'service_tax' => $retname->service_tax,
                    'vat' => $retname->vat,
                    'vat_date' => $retname->vat_date,
                    'outlet_code' => $retname->code,
                    'invoice_digit' => $retname->invoice_digit,
                    'restmenu' => $menu,
                    'feedback' => $feedback
                ),
                200
            );
        } else {
            if (sizeof($user) > 0) {
                return Response::json(
                    array(
                        'message' => 'Please Add Valid PassWord',
                        'statuscode' => 434,
                    ),
                    200
                );
            } else {
                return Response::json(
                    array(
                        'message' => 'Please Add Valid User',
                        'statuscode' => 435,
                    ),
                    200
                );
            }
        }
    }
    public function ownerlogout()
    {
        $restaurantid = Input::get("resid");

        $item = Outlet::where('id', $restaurantid)->get();

        if (sizeof($item) > 0) {
            $ownerid = $item[0]->owner_id;
            DB::table("owners")->where('id', '=', $ownerid)->update(array('islogin' => 0, 'device_id' => null));
            return Response::json(
                array(
                    'message' => 'User Logged Out Successfully',
                    'statuscode' => 200,
                    'status' => 'Success',

                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'message' => 'No Owner Data',
                    'statuscode' => 434,
                    'status' => 'Success',

                ),
                200
            );
        }
    }
    public function autoorders(Request $request)
    {
        $date = $request->input('maxdt');
        // $user=User::find(Auth::user()->id);
        $star = [];
        //  $id=$user->restaurant->lists('id');
        $a = '';
        $b = '';
        //$restaurant_id=$id[0];
        $restaurant_id = $request->input('restaurant_id');
        $retname = Outlet::find($restaurant_id);
        $totalprice = [];
        $orderappend = '';
        $maxdt = [];
        $maxdt[0] = $date;
        $orders = $retname->orderdetail()->where('status', '!=', 'delivered')->where('cancelorder', '!=', 1)->where('created_at', '>', new Carbon($date))->orderBy('created_at', 'desc')->get();
        $items = [];
        $rating = [];
        $uniquepricearray = [];
        $othercount = [];
        if (count($orders) > 0) {
            $totalprice = [];
            foreach ($orders as $key => $order) {
                $item = OrderItem::where('order_id', $order->order_id)->get();
                $totalpr = 0;
                $it = array();
                foreach ($item as $t) {
                    $itemnew = $t->menuitem;
                    // array_push($it, array("item" => $itemnew->item, "quantity" => $t->item_quantity, "price" => $itemnew->price));
                    array_push($it, array(
                        "item" => $itemnew ? $itemnew->item : '',
                        "quantity" => $t->item_quantity,
                        "price" => $itemnew ? $itemnew->price : 0
                    ));
                    if (isset($itemnew['price']) && $itemnew['price'] != '') {
                        $totalpr += $t->item_quantity * $itemnew['price'];
                    } else {
                        $totalpr += 0;
                    }
                }
                array_push($items, $it);
                //$totalprice[$order->order_id]=$totalpr;
                array_push($totalprice, array($order->suborder_id => $order->totalcost_afterdiscount));
                // echo in_array($order->user_mobile_number,$uniquepricearray);
                if ($key == 0) {
                    $maxdt[0] = $order->created_at;
                }
            }
            $forrating = $retname->orderdetail()->where('status', '=', 'delivered')->orderBy('created_at', 'desc')->get();
            $otheroutletordercount = DB::table('orders')->where('status', '=', 'delivered')->where('outlet_id', '!=', $restaurant_id)->get();
            $uniqueprice = DB::table('orders')->where('status', '=', 'delivered')->get();
            foreach ($uniqueprice as $k1 => $v1) {
                if (!array_key_exists($v1->user_mobile_number, $uniquepricearray)) {
                    //array_push($uniquepricearray,array($order->user_mobile_number=>number_format($totalpr, 2, '.', '')));
                    $uniquepricearray[$v1->user_mobile_number] = $v1->totalprice;
                }
                if ($uniquepricearray[$v1->user_mobile_number] < $v1->totalprice) {
                    $uniquepricearray[$v1->user_mobile_number] = $v1->totalprice;
                }
            }
            foreach ($otheroutletordercount as $k => $v) {
                if (!array_key_exists($v->user_mobile_number, $rating)) {
                    $othercount[$v->user_mobile_number] = 1;
                } else {
                    $othercount[$v->user_mobile_number] = $othercount[$v->user_mobile_number] + 1;
                }
            }
            $i = 1;
            foreach ($forrating as $key => $value) {
                if (!array_key_exists($value->user_mobile_number, $rating)) {
                    $rating[$value->user_mobile_number] = 1;
                } else {
                    $rating[$value->user_mobile_number] = $rating[$value->user_mobile_number] + 1;
                }
                //$totalprice[$order->order_id]=$totalpr;
            }
            return Response::json(
                array(
                    'message' => 'Order Data',
                    'statuscode' => 200,
                    'status' => 'Success',
                    'orders' => $orders->toArray(),
                    'resid' => (string) $restaurant_id,
                    'items' => $items,
                    'total' => $totalprice,
                    'maxdt' => $maxdt,
                    'rating' => $rating,
                    'maxprice' => $uniquepricearray,
                    'othercount' => $othercount
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'message' => 'No Order Data',
                    'statuscode' => 434,
                    'status' => 'Success',
                ),
                200
            );
        }
    }

    public function nextstatus(Request $request)
    {
        $currents = $request->input('currentstatus');
        $oid = $request->input('oid');
        $resid = $request->input('restaurant_id');
        $position = $request->input('position');
        $useragent = $request->input('user_agent');
        $getcurrentstatus = Status::where('status', $currents)->where('outlet_id', $resid)->get();
        $order_date = $request->input('order_date');
        $currentstatus = '';
        $status = '';
        $f = array();
        $order_summary = array();
        foreach ($getcurrentstatus as $getstatus) {
            $getnextstatus = Status::where('order', '>', $getstatus->order)->where('outlet_id', $resid)->orderby('order', 'ASC')->first();

            if (isset($getnextstatus) > 0) {
                $ordstat = OrderDetails::where('status', $currents)->where('suborder_id', $oid)->where('outlet_id', $resid)->where('created_at', $order_date)->first();
                OrderDetails::where('status', $currents)->where('suborder_id', $oid)->where('outlet_id', $resid)->where('created_at', $order_date)->update(array('status' => $getnextstatus->status));
                $restname = Outlet::where('id', $ordstat['restaurant_id'])->first();

                $currentstatus = $getnextstatus->status;
                if ($currentstatus == "preparing") {
                    $status = "prepared";
                } else if ($currentstatus == "prepared") {
                    $status = "delivered";
                } else if ($currentstatus == "delivered") {
                    $status = "";
                }
                array_push($f, $ordstat['device_id']);
                array_push($f, $currents);
                array_push($f, $oid);
                array_push($f, $currentstatus);
                array_push($f, $order_date);
            }
        }
        if ($useragent == "android") {
            Queue::push('App\Commands\OrderNotification@getordersnotification', array('fields' => $f));
        } else {
            Queue::push('App\Commands\IOS\OrderNotification@getordersnotification', array('fields' => $f));
        }
        return Response::json(
            array(
                'message' => 'ok',
                'statuscode' => 200,
                'nextstatus' => ucfirst($currentstatus),
                'buttonstatus' => $status = 'delivered' ? ucfirst($status) : "",
                'position' => $position,
                'suborder_id' => $oid,
                'created_at' => $order_date
            ),
            200
        );
    }

    // First Order API
    public function firstorder(Request $request)
    {
        $contact = Input::get('mobile');
        $name = Input::get('name');
        $user_entered_couponcode = Input::get('coupon_code');


        if (isset($contact) && isset($user_entered_couponcode)) {
            $couponordermapper = OrderCouponMappers::where('coupon_applied', $user_entered_couponcode)->where('user_mobile_number', $contact)->first();
            if (sizeof($couponordermapper) > 0) {
                return Response::json(
                    array(
                        'message' => "Coupon Code is already used.",
                        'status' => 'Success',
                        'statuscode' => 436
                    ),
                    200
                );
            } elseif (isset($contact)) {
                //for finding customer by phone_number
                $usercheck = users::findcustomerbyphonenumber($contact);

                $password = users::generateotp();
                if (count($usercheck) == 0) {

                    $id = users::getidofaddedcustomer($contact, $password, $password);
                    users::sendpassword($contact, $password, $name);

                    return Response::json(
                        array(
                            'message' => 'User is Added successfully',
                            'userid' => (string) $id,
                            'password' => $password,
                            'status' => 'NotVerified',
                            'statuscode' => 200,
                            'New User' => 0,
                            'mobile' => $contact
                        ),
                        200
                    );
                } else {

                    users::updatepass($password, $contact);

                    users::sendpassword($contact, $password, $name);

                    return Response::json(
                        array(
                            'message' => 'User details successfully updated',
                            'userid' => $usercheck->lists('id')[0],
                            'password' => $password,
                            'status' => 'NotVerified',
                            'statuscode' => 200,
                            'New User' => 1,
                            'mobile' => $contact
                        ),
                        200
                    );
                }
            }
        }
    }

    public function sendmail(Request $request)
    {
        $mail = $request->input('mail');
        $password = $request->input('password');
        Mail::send('emails.sendpassword', ['password' => $password], function ($message) use ($mail) {
            $message->from('we@pikal.io', 'Pikal');
            $message->to($mail, 'Pikal');
            $message->subject('Pikal Login Details');
        });
        return Response::json(
            array(
                'message' => 'Email sent successfully',
                'password' => $password,
                'statuscode' => 200,
                'mail' => $mail
            ),
            200
        );
    }
    public function addaddress(Request $request)
    {
        $getuserid = $request->input('user_id');
        $getaddress = $request->input('address');
        $getlocality = $request->input('locality');
        $pincode = $request->input('pincode');
        $phonenumber = $request->input('user_mobile_number');
        $address_tag = $request->input('address_tag');
        $state = $request->input('state');
        $city = $request->input('city');
        $country = $request->input('country');
        $landmark = $request->input('landmark');
        $flatnumber = $request->input('flatnumber');

        $address = new address();
        if (isset($phonenumber)) {
            $address->user_mobile_number = $phonenumber;
        }
        if (isset($getaddress)) {
            $address->address = $getaddress;
        }
        if (isset($getlocality)) {
            $address->locality = $getlocality;
        }
        if (isset($state)) {
            $address->state = $state;
        }
        if (isset($city)) {
            $address->city = $city;
        }
        if (isset($country)) {
            $address->country = $country;
        }
        if (isset($landmark)) {
            $address->landmark = $landmark;
        }
        if (isset($flatnumber)) {
            $address->flatnumber = $flatnumber;
        }
        if (isset($pincode)) {
            $address->pincode = $pincode;
        }
        if (isset($address_tag)) {
            $address->address_tag = $address_tag;
        }
        $address->save();
        return Response::json(
            array(
                'message' => 'Address Added successfully',
                'address_id' => $address->id,
                'statuscode' => 200,
                'address_tag' => 'addresstag'
            ),
            200
        );
    }

    public function payuview()
    {
        return view('payu.index');
    }
    public function payusuccess()
    {
        return view('payu.success');
    }
    public function payufailure()
    {
        return view('payu.failure');
    }

    public function addstatusforallrestaurant()
    {
        $outlets = Outlet::all();
        $statusarray = array('received', 'preparing', 'prepared', 'delivered');
        foreach ($outlets as $outlet) {
            $checkstatus = Status::where('outlet_id', $outlet['id'])->get();
            if (sizeof($checkstatus) == 0) {
                for ($i = 0; $i < count($statusarray); $i++) {
                    $status = new status();
                    $status->status = $statusarray[$i];
                    $status->owner_id = $outlet['owner_id'];
                    $status->order = $i + 1;
                    $status->outlet_id = $outlet['id'];
                    $status->save();
                }
            }
        }
    }

    public function logincustomers(Request $request)
    {
        $contact = $request->input('mobile');
        if (isset($contact)) {
            //for finding customer by phone_number
            $usercheck = users::findcustomerbyphonenumber($contact);
            //   $orders=OrderDetails::where('user_mobile_number',$contact)->get();
            $password = users::generateotp();
            users::sendotpbymessage($password, $contact);
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
            if (count($usercheck) == 0) {
                $id = users::getidofaddedcustomer($contact, $password, $password);
                return Response::json(
                    array(
                        'message' => 'User Logged in Successfully',
                        'user_server_id' => (string) $id,
                        'otp' => $password,
                        'status' => 'Success',
                        'statuscode' => 200
                    ),
                    200
                );
            } else {
                $userdata = users::where('mobile_number', $contact)->get();
                $id = $userdata[0]->id;
                return Response::json(
                    array(
                        'message' => 'User Already Exist',
                        'user_server_id' => (string) $id,
                        'otp' => $password,
                        'status' => 'Success',
                        'statuscode' => 200
                    ),
                    200
                );
            }
        }
    }

    public function getallcities()
    {
        $states = State::getallstates();
        $array = array();
        $i = 0;
        foreach ($states as $stateData) {
            $city = City::getcitybystateid($stateData->id);
            foreach ($city as $cty) {
                $array[$i]['city'] = $cty->name . '-' . $stateData->name;
                $i++;
            }
        }
        return Response::json(
            array(
                'message' => 'Cities fetched successfully',
                'city_state' => $array,
                'status' => 'Success',
                'statuscode' => 200
            ),
            200
        );
    }
    public function getlocalitybycity(Request $request)
    {
        $ctyid = $request->input('city');
        if ($ctyid != 'Select City' && $ctyid != 'Select') {
            $cityname = explode('-', $ctyid);

            $city = City::getcitybycityname($cityname[0]);
            $array = array();
            $i = 0;
            $locality = locality::getlocalitybycityid($city[0]['id']);
            foreach ($locality as $lcty) {
                $array[$i]['locality'] = $lcty->locality;
                $i++;
            }
            return Response::json(
                array(
                    'message' => 'Locality fetched successfully',
                    'localities' => $array,
                    'status' => 'Success',
                    'statuscode' => 200
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'message' => 'Select Valid City',
                    'status' => 'Error',
                    'statuscode' => 401
                ),
                200
            );
        }
    }
    public function getlocality()
    {
        $array = array();
        $i = 0;
        $locality = locality::getalllocality();
        foreach ($locality as $lcty) {
            $array[$i]['locality'] = $lcty->locality;
            $i++;
        }
        return Response::json(
            array(
                'message' => 'Locality fetched successfully',
                'localities' => $array,
                'status' => 'Success',
                'statuscode' => 200
            ),
            200
        );
    }
    public function ownerfetchdata(Request $request)
    {
        $username = $request->input('owner_name');
        $pass = $request->input('owner_pass');
        $restaurant_id = $request->input('resid');

        $maxdt = Carbon::now();
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';

        if ($field == 'email') {
            $user = Owner::where('email', $username)->first();
        } else {
            $user = Owner::where('user_name', $username)->first();
        }

        $maxdt = [];
        array_push($maxdt, Carbon::now());

        $retname = Outlet::find($restaurant_id);
        $star = [];
        $orders = $retname->orderdetail()->where('status', '!=', 'delivered')->where('cancelorder', '!=', 1)->orderBy('created_at', 'asc')->get();
        $totalprice = [];
        $rating = [];
        $uniquepricearray = [];
        $othercount = [];
        $items = [];

        if (count($orders) > 0) {
            $forrating = $retname->orderdetail()->where('status', '=', 'delivered')->orderBy('created_at', 'desc')->get();
            $otheroutletordercount = DB::table('orders')->where('status', '=', 'delivered')->where('outlet_id', '!=', $restaurant_id)->get();
            // echo "Res <pre>";
            // print_r($otheroutletordercount);
            // echo "</pre>";
            // exit;
            $uniqueprice = DB::table('orders')->where('status', '=', 'delivered')->get();
            if (sizeof($uniqueprice) > 0) {
                foreach ($uniqueprice as $k1 => $v1) {
                    if (!array_key_exists($v1->user_mobile_number, $uniquepricearray)) {
                        //array_push($uniquepricearray,array($order->user_mobile_number=>number_format($totalpr, 2, '.', '')));
                        $uniquepricearray[$v1->user_mobile_number] = $v1->totalprice;
                    }
                    if ($uniquepricearray[$v1->user_mobile_number] < $v1->totalprice) {
                        $uniquepricearray[$v1->user_mobile_number] = $v1->totalprice;
                    }
                }
            }
            if (!empty($otheroutletordercount)) {
                foreach ($otheroutletordercount as $k => $v) {
                    if (!array_key_exists($v->user_mobile_number, $rating)) {
                        $othercount[$v->user_mobile_number] = 1;
                    } else {
                        $othercount[$v->user_mobile_number] = $othercount[$v->user_mobile_number] + 1;
                    }
                }
            }
            if (!empty($forrating)) {
                foreach ($forrating as $key => $value) {
                    $item = OrderItem::where('order_id', $value->order_id)->get();
                    $totalpr = 0;
                    $it = array();
                    foreach ($item as $t) {

                        $itemnew = $t->menuitem;
                        if (isset($itemnew['price']) && $itemnew['price'] != '') {
                            $totalpr += $t->item_quantity * $itemnew['price'];
                        } else {
                            $totalpr += 0;
                        }
                    }

                    if (!array_key_exists($value->user_mobile_number, $rating)) {
                        $rating[$value->user_mobile_number] = 1;
                    } else {
                        $rating[$value->user_mobile_number] = $rating[$value->user_mobile_number] + 1;
                    }
                    //$totalprice[$order->order_id]=$totalpr;

                    // echo in_array($order->user_mobile_number,$uniquepricearray);

                }
            }

            foreach ($orders as $key => $order) {
                $printcount = Printsummary::where('order_id', $order->suborder_id)->where('order_created_at', $order->created_at)->first();
                if (!empty($printcount)) {
                    $order->printcount = $printcount['print_number'];
                } else {
                    $order->printcount = 0;
                }

                if (isset($rating[$order->user_mobile_number])) {
                    $order->rating = $rating[$order->user_mobile_number];
                } else {
                    $order->rating = '';
                }
                if (isset($othercount[$order->user_mobile_number])) {
                    $order->othercount = $othercount[$order->user_mobile_number];
                } else {
                    $order->othercount = '';
                }
                if (isset($uniquepricearray[$order->user_mobile_number])) {
                    $order->maxprice = $uniquepricearray[$order->user_mobile_number];
                } else {
                    $order->maxprice = '';
                }
                $item = OrderItem::where('order_id', $order->order_id)->get();
                $totalpr = 0;
                $it = array();
                foreach ($item as $t) {
                    // echo "Items <pre>";print_r($t); echo "</pre>"; exit;
                    $itemnew = $t->menuitem;
                    $madt = date("Y-m-d H:i:s", strtotime($order->created_at));
                    // array_push($it, array("item" => $itemnew->item, "quantity" => $t->item_quantity, "price" => $itemnew->price, "suborder_id" => $order->suborder_id, "created_at" => $madt, "item_options" => $t->item_options, "item_options_price" => $t->item_options_price));
                    $it[] = array(
                        "item" => $t->item_name,
                        "quantity" => $t->item_quantity,
                        "price" => $t->item_price,
                        "suborder_id" => $order->suborder_id,
                        "created_at" => $madt,
                        "item_options" => $t->item_options,
                        "item_options_price" => $t->item_options_price
                    );
                    if (isset($itemnew['price']) && $itemnew['price'] != '') {
                        $totalpr += $t->item_quantity * $itemnew['price'];
                    } else {
                        $totalpr += 0;
                    }
                }
                array_push($items, $it);
                $star[$order->order_id] = DB::table('orders')->where('user_mobile_number', $order->phone_number)->count();
                //$totalprice[$order->order_id]=$totalpr;
                array_push($totalprice, array($order->suborder_id => $order->totalcost_afterdiscount));


                if ($key == 0) {
                    $maxdt[0] = $order->created_at;
                }
            }
        }

        return Response::json(
            array(
                'message' => 'Valid User',
                'statuscode' => 200,
                'status' => 'Success',
                'userid' => (string) $user->id,
                'orders' => $orders->toArray(),
                'resid' => (string) $restaurant_id,
                'items' => $items,
                'total' => $totalprice,
                'maxdt' => $maxdt,
                'rating' => $rating,
                'maxprice' => $uniquepricearray,
                'othercount' => $othercount,
                'ownername' => $user->user_name,
                'outletname' => $retname->name
            ),
            200
        );
    }
    public function matchcouponcode(Request $request)
    {
        $flag = $request->input('flag');
        $user_entered_couponcode = '';
        $total_cost = '';
        $mobile_number = '';
        if (isset($flag) && $flag == 'web_app_order') {
            $user_entered_couponcode = Input::get('coupon_code');
            $total_cost = Input::get('total_cost');
            $mobile_number = Input::get('mobile_number');
        } else {
            $user_entered_couponcode = $request->input('coupon_code');
            $total_cost = $request->input('total_cost');
            $mobile_number = $request->input('mobile_number');
        }
        $date = Date("Y-m-d");
        //print_r($user_entered_couponcode.'==='.$total_cost.'==='.$mobile_number.'==='.$flag);exit;
        $coupondata = CouponCodes::where('coupon_code', $user_entered_couponcode)->first();
        if ($mobile_number != '') {
            $couponordermapper = OrderCouponMappers::where('coupon_applied', $user_entered_couponcode)->where('user_mobile_number', $mobile_number)->first();
            if (!empty($couponordermapper)) {
                $msg = 'Coupon code is already used.';
                if (isset($flag) && $flag == 'web_app_order') {
                    return array('message' => $msg, 'status' => 'already');
                } else {
                    return Response::json(
                        array(
                            'message' => $msg,
                            'status' => 'Success',
                            'statuscode' => 436
                        ),
                        200
                    );
                }
            }
        }
        if (!empty($coupondata)) {
            if ($coupondata->min_value > $total_cost) {
                $roundedvalue = round($coupondata->min_value);
                $msg = 'Minimum order price should be equal/more than ' . $roundedvalue;
                if (isset($flag) && $flag == 'web_app_order') {
                    return array('message' => $msg,  'status' => 'minimum');
                } else {
                    return Response::json(
                        array(
                            'message' => $msg,
                            'status' => 'Success',
                            'statuscode' => 432
                        ),
                        200
                    );
                }
            } elseif (strtotime($coupondata->expire_datetime) < strtotime($date)) {
                $msg = 'Code is Expire.';
                if (isset($flag) && $flag == 'web_app_order') {
                    return array('message' => $msg, 'status' => 'expired');
                } else {
                    return Response::json(
                        array(
                            'message' => $msg,
                            'status' => 'Success',
                            'statuscode' => 433
                        ),
                        200
                    );
                }
            } else {
                if ($coupondata->value != '') {
                    $discounted_value = $total_cost - $coupondata->value;
                    $roundedvalue = round($discounted_value);
                    $msg = "Coupon $coupondata->coupon_code applied successfully.  &#8377; $coupondata->value is discounted.";
                    if (isset($flag) && $flag == 'web_app_order') {
                        return array(
                            'message' => $msg,
                            'status' => 'Success',
                            'min_value' => $coupondata->min_value,
                            'coupon_code' => $coupondata->coupon_code,
                            'discounted_value' => $coupondata->value,
                            'coupondata' => $coupondata,
                            'cost_beforediscount' => $total_cost,
                            'status' => 'applied'
                        );
                    } else {
                        return Response::json(
                            array(
                                'message' => $msg,
                                'status' => 'Success',
                                'coupon_code' => $coupondata->coupon_code,
                                'discounted_value' => $coupondata->value,
                                'coupondata' => $coupondata,
                                'cost_beforediscount' => $total_cost,
                                'statuscode' => 200
                            ),
                            200
                        );
                    }
                } elseif ($coupondata->percentage != '') {
                    $discounted_value = ($coupondata->percentage / 100) * $total_cost;
                    if ($discounted_value > $coupondata->max_value) {
                        $msg = "Coupon $coupondata->coupon_code applied successfully. Maximum &#8377; $coupondata->max_value is discounted";
                        if (isset($flag) && $flag == 'web_app_order') {
                            return array(
                                'message' => $msg,
                                'status' => 'Success',
                                'min_value' => $coupondata->min_value,
                                'coupon_code' => $coupondata->coupon_code,
                                'discounted_value' => $coupondata->max_value,
                                'coupondata' => $coupondata,
                                'cost_beforediscount' => $total_cost,
                                'status' => 'applied'
                            );
                        } else {
                            return Response::json(
                                array(
                                    'message' => $msg,
                                    'status' => 'Success',
                                    'coupon_code' => $coupondata->coupon_code,
                                    'discounted_value' => $coupondata->max_value,
                                    'coupondata' => $coupondata,
                                    'cost_beforediscount' => $total_cost,
                                    'statuscode' => 200
                                ),
                                200
                            );
                        }
                    }
                    $roundedvalue = round($discounted_value);
                    if (isset($flag) && $flag == 'web_app_order') {
                        $msg = "Coupon $coupondata->coupon_code applied successfully.  &#8377; $roundedvalue is discounted.";
                        return array(
                            'message' => "Coupon $coupondata->coupon_code applied successfully.  &#8377; $roundedvalue is discounted.",
                            'status' => 'Success',
                            'min_value' => $coupondata->min_value,
                            'coupon_code' => $coupondata->coupon_code,
                            'discounted_value' => $roundedvalue,
                            'coupondata' => $coupondata,
                            'cost_beforediscount' => $total_cost,
                            'status' => 'applied'
                        );
                    } else {
                        return Response::json(
                            array(
                                'message' => "Coupon $coupondata->coupon_code applied successfully.  &#8377; $roundedvalue is discounted.",
                                'status' => 'Success',
                                'coupon_code' => $coupondata->coupon_code,
                                'discounted_value' => $roundedvalue,
                                'coupondata' => $coupondata,
                                'cost_beforediscount' => $total_cost,
                                'statuscode' => 200
                            ),
                            200
                        );
                    }
                }
            }
        } else {
            $msg = 'Coupon code is invalid.Please enter valid coupon code';
            if (isset($flag) && $flag == 'web_app_order') {
                return array('message' => $msg, 'status' => 'invalid');
            } else {
                return Response::json(
                    array(
                        'message' => $msg,
                        'status' => 'Error',
                        'statuscode' => 434
                    ),
                    200
                );
            }
        }
    }
    public function termsandcondition()
    {
        $termsandcondition = Termsandcondition::all();
        return Response::json(array(
            'message' => 'Terms and Condition Fetched Successfully',
            'status' => 'Success',
            'statuscode' => 200
        ), 200);
    }


    public function resetorderid(Request $request)
    {

        $resid = $request->input('resid');
        $setorderid = OrderDetails::where('outlet_id', $resid)->orderBy('created_at', 'desc')->get();
        // echo "Res ID <pre>";print_r($resid);echo "</pre>";exit;
        $i = 0;
        foreach ($setorderid as $orderid) {
            $suborder_id[] = $orderid->suborder_id;
            $i++;
        }
        $resetupdate = OrderDetails::where('suborder_id', $suborder_id[0])->update(array('reset' => 'true'));
        if (count($setorderid) > 0) {
            return Response::json(array(
                'message' => 'Order id reset successfully',
                'status' => 'success',
                'statuscode' => 200,
                'type' => 'reset'
            ), 200);
        } else {
            return Response::json(array(
                'message' => "You don't have any orders",
                'status' => 'error',
                'statuscode' => 434,
                'type' => 'reset'
            ), 200);
        }
    }

    public function generatereport(Request $request)
    {
        // $startdate = date('Y-m-d 00:00:00', strtotime(Input::json('start_date')));
        // $enddate = date('Y-m-d 12:00:00', strtotime(Input::json('end_date')));
        $startdate = $request->input('start_date') ? date('Y-m-d 00:00:00', strtotime($request->input('start_date'))) : null;
        $enddate = $request->input('end_date') ? date('Y-m-d 23:59:59', strtotime($request->input('end_date'))) : null;
        // $userid = Input::json('userid');
        $userid = $request->input('userid');

        $getrestaurant = Outlet::where('owner_id', $userid)->get();
        // echo "Get Restaurent <pre>"; print_r($getrestaurant); echo "</pre>"; 
        // exit;
        $getorders = OrderDetails::where('outlet_id', $getrestaurant[0]->id)->whereBetween('created_at', array($startdate, $enddate))->get();
        // echo "Get Orders <pre>";print_r($getorders);echo "</pre>";
        // exit;
        $result = array();

        $i = 0;
        $totalprice = 0;
        $ordersummary = array();

        foreach ($getorders as $orderdetails) {
            $name = $orderdetails->name;
            // echo "Orders Deails <pre>";print_r($orderdetails);echo "</pre>";
            // exit;
            $orderitem = DB::table('order_items')->where('order_id', $orderdetails->order_id)->get();
            // echo "Orders Items <pre>";print_r($orderitem);echo "</pre>";
            // exit;
            foreach ($orderitem as $orderit) {
                $menuitemname = DB::table('menus')->where('id', $orderit->item_id)->get();
                // echo "Menu Items Name <pre>";print_r($menuitemname);echo "</pre>";
                // exit;
                $result[$i]['Outlet Name'] = $getrestaurant[0]->name;
                $result[$i]['Order Id'] = $orderdetails->suborder_id;

                if (!empty($menuitemname) && isset($menuitemname[0]->item) && $menuitemname[0]->item != '') {
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
                    // $totalprice += $orderit->item_price;
                    $totalprice += (float) $orderit->item_price;
                } else {
                    // $totalprice += $orderit->item_price;
                    // echo "Orders Item Price" . $orderit->item_price; exit;
                    // echo "Total Price" . $totalprice;exit;
                    $totalprice = $totalprice + $orderit->item_price;
                }
                $i++;
            }
        }
        ob_end_clean();
        ob_start();
        Excel::create('foodklub_report', function ($excel) use ($result, $totalprice) {
            $excel->sheet('Sheet1', function ($sheet) use ($result, $totalprice) {
                $sheet->setOrientation('landscape');
                $sheet->fromArray($result);
                $sheet->appendRow(array('Grand Total', $totalprice));
            });
        })->store('xls');
        $restaurant_name = $getrestaurant[0]->name;
        Mail::send('emails.dailyreport', [], function ($message) {
            // $message->from('we@pikal.io', 'Pikal');
            $message->from('js@savitriya.com', 'Pikal');
            $message->to('js@savitriya.com', 'JS');
            $message->subject('Foodklub Report');
            $message->attach(app_path() . '/../storage/exports/foodklub_report.xls', ['as' => 'foodklub_report.xls', 'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
        });
        //Queue::push('App\Commands\GenerateReport@generatereport', array('restaurant_name'=>$restaurant_name));
        return Response::json(array(
            'message' => 'Report sent successfully',
            'status' => 'success',
            'statuscode' => 200,
            'type' => 'report'
        ), 200);
    }

    public function updatemenuitem(Request $request)
    {
        // $resid = Input::json('resid');
        // $menuitemid = Input::json('itemid');
        // $active = Input::json('itemactive');
        // $itemname = Input::json('itemname');
        // $price = Input::json('itemPrice');
        $resid = $request->input('outlet_id');
        $menuitemid = $request->input('menu_title_id');
        $active = $request->input('active');
        $itemname = $request->input('item');
        $price = $request->input('price');

        $update = array();

        if ($active !== null && $active !== '') {
            $update['active'] = $active;
        }

        if ($itemname !== null && $itemname !== '') {
            $update['item'] = $itemname;
        }

        if ($price !== null && $price !== '') {
            $update['price'] = $price;
        }
        // $affectedRows = $menu->where('id', $menuitemid)->where('outlet_id', $resid)->update($update);
        // return Response::json(array(
        //     'message' => 'Menu Updated Successfully',
        //     'status' => 'success',
        //     'statuscode' => 200
        // ), 200);
        // echo "Update Array <pre>"; print_r($update); echo "</pre>"; exit;
        $affectedRows = DB::table('menus')->where('menu_title_id', $menuitemid)->where('outlet_id', $resid)->update($update);
        if ($affectedRows) {
            return Response::json([
                'message' => 'Menu Updated Successfully',
                'status' => 'success',
                'statuscode' => 200
            ], 200);
        } else {
            return Response::json([
                'message' => 'Menu Update Failed or No Changes Made',
                'status' => 'error',
                'statuscode' => 400
            ], 400);
        }
    }
    public function cancellationreason(Request $request)
    {
        $array = array();
        // $resid = Input::json('resid');
        $resid = $request->input('resid');
        $i = 0;
        $cancel = CancellationReason::where('outlet_id', $resid)->get();
        foreach ($cancel as $can) {
            $array[$i]['reason'] = $can->reason_of_cancellation;
            $i++;
        }
        return Response::json(array(
            'message' => 'Cancellation Reason Fetched Successfully',
            'status' => 'success',
            'statuscode' => 200,
            'cancelreasons' => $array
        ), 200);
    }
    public function ordercancellation(Request $request)
    {
        // $resid = Input::json('resid');
        // $order_id = Input::json('order_id');
        // $reason = Input::json('reason');
        // $orderdate = Input::json('order_date');
        $resid = $request->input('resid');
        $order_id = $request->input('order_id');
        $reason = $request->input('reason');
        $orderdate = $request->input('order_date');
        $res = OrderDetails::where('order_id', $order_id)->whereRaw('DATE(created_at) = ?', [$orderdate])->update(['cancelorder' => 1]);
        // echo "OrderDetails <pre>";print_r($res);echo "</pre>";
        // exit;
        if ($res) {
            // echo "IFFFFFF";exit;
            $ordercancellation = new OrderCancellation();
            $ordercancellation->outlet_id = $resid;
            $ordercancellation->order_id = $order_id;
            $ordercancellation->reason = $reason;
            // $ordercancellation->order_date = $orderdate;
            $ordercancellation->save();
            // echo "Order Cancellation <pre>"; print_r($ordercancellation); echo "</pre>"; exit;
            $orderdetails = OrderDetails::where('order_id', $order_id)->get();
            // echo "Order <pre>";print_r($orderdetails);echo "</pre>";exit;
            $cancel = array(
                'device_id' => $orderdetails[0]->device_id,
                'order_id' => $order_id,
                'reason' => $reason,
                'created_at' => $orderdate
            );
            // echo "Order <pre>";print_r($cancel);echo "</pre>";exit;
            Queue::push(
                'App\Commands\CancelOrderNotification@getcancelorder',
                array('cancellation' => $cancel)
            );
            return Response::json(array(
                'message' => 'Order Cancelled Successfully',
                'status' => 'success',
                'statuscode' => 200,
                'type' => 'cancelorder',
                'suborder_id' => $order_id,
                'created_at' => $orderdate
            ), 200);
        } else {
            // echo "Elseeee";exit;
            return Response::json(array(
                'message' => 'Order Cancellation Failed',
                'status' => 'failed',
                'statuscode' => 400
            ), 400);
        }
    }
    public function printsummary(Request $request)
    {
        // $getorderprintsuborder_id = Input::json('suborder_id');
        // $getorderordercreated_at = Input::json('order_created_at');
        $getorderprintsuborder_id = $request->input('suborder_id');
        $getorderordercreated_at = $request->input('order_created_at');
        if (!$getorderprintsuborder_id || !$getorderordercreated_at) {
            return response()->json([
                'message' => 'getorderprintsuborder_id and getorderordercreated_at are required',
                'status' => 'failed',
                'statuscode' => 400
            ], 400);
        }
        $print = DB::table('print_summary')->where('order_id', $getorderprintsuborder_id)->where('order_created_at', $getorderordercreated_at)->first();
        if ($print) {
            $print_count = $print->print_number + 1;
            DB::table('print_summary')->where('order_id', $getorderprintsuborder_id)->where('order_created_at', $getorderordercreated_at)->update(array('print_number' => $print_count, 'order_created_at' => $getorderordercreated_at));
        } else {
            $print_count = 1;
            $printsummary = new Printsummary();
            $printsummary->print_number = $print_count;
            $printsummary->order_id = $getorderprintsuborder_id;
            $printsummary->order_created_at = $getorderordercreated_at;
            $printsummary->save();
        }
        return Response::json(array(
            'message' => 'Print summary added successfully',
            'status' => 'success',
            'statuscode' => 200,
            'printcount' => $print_count,
            'suborder_id' => $getorderprintsuborder_id,
            'created_at' => $getorderordercreated_at
        ), 200);
    }

    public function addreviews(Request $request)
    {
        $usermobile = $request->input('mobile');
        $user_name = $request->input('user_name');
        $rating = $request->input('rating');
        $fav = $request->input('fav');
        $comment = $request->input('comment');
        $resid = $request->input('res_id');

        $review = new Reviews();
        $review->mobile = $usermobile;
        $review->username = $user_name;
        $review->rating = $rating;
        $review->fav = $fav;
        $review->resid = $resid;
        $review->review = $comment;
        $review->save();
        // $reviews=Reviews::skip($start)->take($limit);
        return Response::json(array(
            'message' => 'Reviews added successfully',
            'status' => 'success',
            'statuscode' => 200,
            'review' => $review,
            'addreview' => 'true'
        ), 200);
    }

    public function getreviews(Request $request)
    {
        $resid = $request->input('res_id');
        $mobile_number = $request->input('user_mobile_number');

        $reviews = Reviews::where('resid', $resid)->get();
        if (isset($mobile_number) && $mobile_number != "") {
            $rev = OrderDetails::where('outlet_id', $resid)->where('user_mobile_number', $mobile_number)->get();
        } else {
            $ordercount = 0;
        }
        if (!empty($rev)) {
            $ordercount = 1;
        } else {
            $ordercount = 0;
        }
        foreach ($reviews as $review) {
            $review['formated_created_at'] = date("F j, Y g:i a", strtotime($review['created_at']));
        }
        return Response::json(array(
            'message' => 'Reviews fetched successfully',
            'status' => 'success',
            'statuscode' => 200,
            'review' => $reviews,
            'ordercount' => $ordercount
        ), 200);
    }

    public function addlike(Request $request)
    {
        $resid = $request->input('res_id');
        $itemid = $request->input('item_id');
        $like = $request->input('like');
        $mobilenumber = $request->input('user_mobile_number');

        $pastlike = Itemreview::where('item_id', $itemid)->where('user_mobile_number', $mobilenumber)->orderby('created_at', 'desc')->first();
        $menulike = Menu::where('id', $itemid)->first();
        if ($like == 0) {
            $alllikes = $menulike->like - 1;
        } else {
            $alllikes = $menulike->like + 1;
        }
        if (!empty($pastlike)) {
            if ($pastlike->like == 0) {
                $totallikes = 1;
            } else {
                $totallikes = 0;
            }
        } else {
            $totallikes = 1;
            $alllikes = $menulike->like + 1;
        }
        Menu::where('id', $itemid)->update(array('like' => $alllikes));

        $itemre = new Itemreview();
        $itemre->res_id = $resid;
        $itemre->item_id = $itemid;
        $itemre->like = $totallikes;
        $itemre->user_mobile_number = $mobilenumber;
        $itemre->save();
        return Response::json(array(
            'message' => 'Item liked successfully',
            'status' => 'success',
            'statuscode' => 200,
            'totallikes' => $totallikes
        ), 200);
    }
    public function addrecipes(Request $request)
    {
        // $outlet_id = Input::json('outlet_id');
        // $title = Input::json('title');
        // $recipe = Input::json('recipe');
        // $ingrediants = Input::json('ingrediants');
        // $shop_url = Input::json('shop_url');
        // $ingrediants_url = Input::json('ingrediant_url');
        // $owner = Input::json('owner');
        $outlet_id = $request->input('outlet_id');
        $title = $request->input('title');
        $recipe = $request->input('recipe');
        $ingrediants = $request->input('ingrediants');
        $shop_url = $request->input('shop_url');
        $ingrediants_url = $request->input('ingrediant_url');
        $owner = $request->input('owner');

        if (!$outlet_id || !$title || !$recipe || !$owner) {
            return response()->json([
                'message' => 'Required fields missing',
                'status' => 'failed',
                'statuscode' => 400
            ], 400);
        }

        $getownerid = Owner::where('user_name', $owner)->first();
        if (!$getownerid) {
            return response()->json([
                'message' => 'Owner not found',
                'status' => 'failed',
                'statuscode' => 404
            ], 404);
        }

        $recipes = new Recipe();
        $recipes->owner_id = $getownerid->id;
        $recipes->outlet_id = $outlet_id;
        $recipes->title = $title;
        $recipes->ingrediants = $ingrediants;
        $recipes->recipes = $recipe;
        $recipes->shop_url = $shop_url;
        $recipes->ingrediants_url = $ingrediants_url;
        $recipes->save();
        // foreach ($allrecipe as $recipe) {
        //     $recipe->formated_created_at = date("F j, Y g:i a", strtotime($recipe->created_at));
        // }
        $allrecipe = Recipe::all();

        foreach ($allrecipe as $rec) {
            $rec->formated_created_at = date("F j, Y g:i a", strtotime($rec->created_at));
        }
        return Response::json(array(
            'message' => 'Recipe Added successfully',
            'status' => 'success',
            'statuscode' => 200,
            'recipe' => $recipes
        ), 200);
    }
    public function getrecipes()
    {
        //$owner=Input::json('owner');
        //$getownerid=Owner::where('user_name',$owner)->first();
        $allrecipe = Recipe::orderBy('created_at', 'desc')->get();
        foreach ($allrecipe as $recipe) {
            $recipe->formated_created_at = date("F j, Y g:i a", strtotime($recipe->created_at));
        }
        return Response::json(array(
            'message' => 'All Recipes Listed successfully',
            'status' => 'success',
            'statuscode' => 200,
            'recipe' => $allrecipe
        ), 200);
    }
    public function webcart()
    {
        $itemcount = Input::get('count');
        $item = Input::get('item');
        $itemarray = array();
        array_push($itemarray, $item);
        $_SESSION['cart'] = $itemarray;
        print_r($itemarray);
        exit;
    }

    public static function get_order_type($type)
    {
        $types = array('take_away' => 'Take Away', 'dine_in' => 'Dine In', 'home_delivery' => 'Home Delivery', 'meal_packs' => 'Meal Packs');
        return $types[$type];
    }

    public function pastorders(Request $request)
    {
        // $restaurant_id = Input::json('resid');
        $restaurant_id = $request->input('resid');
        if (!$restaurant_id) {
            return response()->json([
                'message' => 'resid is required',
                'statuscode' => 400,
                'status' => 'failed'
            ], 400);
        }
        // $maxdt = [];
        // array_push($maxdt, Carbon::now());
        $maxdt = [Carbon::now()];

        // $retname = Outlet::find($restaurant_id);
        $retname = Outlet::find($restaurant_id);
        // echo "Orders <pre>";print_r($retname);echo "</pre>";exit;
        if (!$retname) {
            return response()->json([
                'message' => 'Outlet not found',
                'statuscode' => 404,
                'status' => 'failed'
            ], 404);
        }
        $star = [];
        // $rr = Carbon::now()->endOfDay();
        // echo "res <pre>";print_r($rr);echo "</pre>";exit;
        $orders = Outlet::find($restaurant_id)->orderdetail()->where('status', '=', 'delivered')->where('cancelorder', '!=', 1)->where('orders.created_at', '>=', Carbon::now()->startOfDay())->where('orders.created_at', '<=', Carbon::now()->endOfDay())->orderBy('created_at', 'asc')->get();
        // echo "Orders <pre>"; print_r($orders); echo "</pre>"; exit;
        $totalprice = [];
        $rating = [];
        $uniquepricearray = [];
        $othercount = [];
        $items = [];

        if (!empty($orders)) {
            $forrating = $retname->orderdetail()->where('status', '=', 'delivered')->orderBy('created_at', 'desc')->get();
            $otheroutletordercount = DB::table('orders')->where('status', '=', 'delivered')->where('outlet_id', '!=', $restaurant_id)->get();
            $uniqueprice = DB::table('orders')->where('status', '=', 'delivered')->get();
            if (sizeof($uniqueprice) > 0) {
                foreach ($uniqueprice as $k1 => $v1) {
                    if (!array_key_exists($v1->user_mobile_number, $uniquepricearray)) {
                        //array_push($uniquepricearray,array($order->user_mobile_number=>number_format($totalpr, 2, '.', '')));
                        $uniquepricearray[$v1->user_mobile_number] = $v1->totalprice;
                    }
                    if ($uniquepricearray[$v1->user_mobile_number] < $v1->totalprice) {
                        $uniquepricearray[$v1->user_mobile_number] = $v1->totalprice;
                    }
                }
            }
            if (!empty($otheroutletordercount)) {
                foreach ($otheroutletordercount as $k => $v) {
                    if (!array_key_exists($v->user_mobile_number, $rating)) {
                        $othercount[$v->user_mobile_number] = 1;
                    } else {
                        $othercount[$v->user_mobile_number] = $othercount[$v->user_mobile_number] + 1;
                    }
                }
            }
            if (!empty($forrating)) {
                foreach ($forrating as $key => $value) {
                    $item = OrderItem::where('order_id', $value->order_id)->get();
                    $totalpr = 0;
                    $it = array();
                    foreach ($item as $t) {
                        $itemnew = $t->menuitem;
                        if (isset($itemnew['price']) && $itemnew['price'] != '') {
                            $totalpr += $t->item_quantity * $itemnew['price'];
                        } else {
                            $totalpr += 0;
                        }
                    }

                    if (!array_key_exists($value->user_mobile_number, $rating)) {
                        $rating[$value->user_mobile_number] = 1;
                    } else {
                        $rating[$value->user_mobile_number] = $rating[$value->user_mobile_number] + 1;
                    }
                    //$totalprice[$order->order_id]=$totalpr;
                    // echo in_array($order->user_mobile_number,$uniquepricearray);
                }
            }
            // echo "Orders <pre>";print_r($orders);echo "</pre>";exit;
            foreach ($orders as $key => $order) {
                $printcount = Printsummary::where('order_id', $order->suborder_id)->where('order_created_at', $order->created_at)->first();
                if (!empty($printcount)) {
                    $order->printcount = $printcount['print_number'];
                } else {
                    $order->printcount = 0;
                }

                if (isset($rating[$order->user_mobile_number])) {
                    $order->rating = $rating[$order->user_mobile_number];
                } else {
                    $order->rating = '';
                }
                if (isset($othercount[$order->user_mobile_number])) {
                    $order->othercount = $othercount[$order->user_mobile_number];
                } else {
                    $order->othercount = '';
                }
                if (isset($uniquepricearray[$order->user_mobile_number])) {
                    $order->maxprice = $uniquepricearray[$order->user_mobile_number];
                } else {
                    $order->maxprice = '';
                }
                $item = OrderItem::where('order_id', $order->order_id)->get();
                $totalpr = 0;
                $it = array();
                foreach ($item as $t) {
                    $itemnew = $t->menuitem;
                    $madt = date("Y-m-d H:i:s", strtotime($order->created_at));
                    array_push($it, array("item" => $itemnew->item, "quantity" => $t->item_quantity, "price" => $itemnew->price, "suborder_id" => $order->suborder_id, "created_at" => $madt, "item_options" => $t->item_options, "item_options_price" => $t->item_options_price));
                    if (isset($itemnew['price']) && $itemnew['price'] != '') {
                        $totalpr += $t->item_quantity * $itemnew['price'];
                    } else {
                        $totalpr += 0;
                    }
                }
                array_push($items, $it);
                $star[$order->order_id] = DB::table('orders')->where('user_mobile_number', $order->phone_number)->count();
                //$totalprice[$order->order_id]=$totalpr;
                array_push($totalprice, array($order->suborder_id => $order->totalcost_afterdiscount));
                if ($key == 0) {
                    $maxdt[0] = $order->created_at;
                }
            }
        }
        $a = Response::json(
            array(
                'message' => 'Valid User',
                'statuscode' => 200,
                'status' => 'Success',
                'orders' => $orders->toArray(),
                'resid' => (string) $restaurant_id,
                'items' => $items,
                'total' => $totalprice,
                'maxdt' => $maxdt,
                'rating' => $rating,
                'maxprice' => $uniquepricearray,
                'othercount' => $othercount,
                'outletname' => $retname->name
            ),
            200
        );
        return $a;
    }


    public function syncorderadd(Request $request)
    {
        // $orders = Input::json('data');
        // $orders = $request->input('data');
        $orders = json_decode($request->input('data'), true);
        // $serverids = array();
        // echo "Orders <pre>"; print_r($orders); echo "</pre>"; exit;
        $serverids = [];
        for ($i = 0; $i < count($orders); $i++) {
            $order = $orders[$i];

            $array = array();
            // $orders_count=DB::table('orders')->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->where('order_unique_id',$order['orderuniqueid'])->count();
            $orders_count = DB::table('orders')->where('order_unique_id', $order['orderuniqueid'])->count();
            if ($orders_count == 0) {
                $Outlet = Outlet::Outletbyid($order['restaurant_id']);
                if (isset($Outlet)) {
                    $startingstatus = status::getallstatusofOutlet($order['restaurant_id']);
                    $lastindex = count($startingstatus) - 1;
                    if (isset($startingstatus)) {
                        if ($order['order_type'] == "dine_in") {
                            $status = $startingstatus[$lastindex]->status;
                        } else {
                            $status = $startingstatus[0]->status;
                        }
                    } else {
                        $status = '';
                    }

                    $order_ids = OrderDetails::getorderid();

                    $suborder_id = OrderDetails::getorderidofrestaurant($order['restaurant_id']);

                    $tempdata['local_id'] = $order['primary_id'];
                    $tempdata['suborder_id'] = $suborder_id;
                    // array_push($serverids, $tempdata);
                    $serverids[] = $tempdata;
                    $service_tax = 0;
                    $a = $Outlet->pluck('code');

                    // $saveorder = OrderDetails::insertorderdetails($a, $order_ids, $order, $status, $suborder_id);
                    $saveorder = OrderDetails::insertorderdetails($a, $order_ids, $order, $status, $suborder_id, '', $service_tax);

                    // foreach ($order['menu_item'] as $asd) {
                    //     $orderid = OrderItem::insertmenuitemoforders($saveorder['id'], $asd);
                    // }
                    foreach ($order['menu_item'] as $asd) {
                        $orderid = OrderItem::insertmenuitemoforders($saveorder['id'], $asd, $order['restaurant_id']);
                    }
                    // Queue::push('App\Commands\MailNotification@getorderdetails', array('orderdetails'=>$saveorder));

                    // $date = $saveorder['order_date'];
                    // $date = str_replace('/', '-', $date);

                    // $orddate = date("F j, Y g:i a", strtotime($date));
                }
            }
        }
        return Response::json(
            array(
                'message' => 'Order Placed Successfully.Go To My Order To Check Your Status',
                'status' => 'success',
                'statuscode' => 200,
                'server_ids' => $serverids
            ),
            200
        );
    }

    public function closeCounter(Request $request)
    {
        // $outlet_id = Input::json('res_id');
        // $start_date = Carbon::parse(Input::json('start_date'));
        // $end_date = Carbon::parse(Input::json('close_date'));
        // $amount = Input::json('total');
        // $amount_byuser = Input::json('total_byuser') ? Input::json('total_byuser') : 0;
        // $amount_fromdb = Input::json('total_fromdb') ? Input::json('total_fromdb') : 0;
        // $remarks = Input::json('remarks');
        $outlet_id = $request->input('res_id');
        $start_date = Carbon::parse($request->input('start_date'));
        $end_date   = Carbon::parse($request->input('close_date'));
        // echo "Outlet <pre>";print_r($end_date);echo "</pre>";exit;
        $amount = $request->input('total');
        $amount_byuser = $request->input('total_byuser', 0);
        $amount_fromdb = $request->input('total_fromdb', 0);
        $remarks = $request->input('remarks');

        $a = $start_date->diff($end_date);
        $total_hours = $a->format("%H:%i");
        $start_time = $start_date->format("H:i");
        $end_time = $end_date->format("H:i");
        $outlet = Outlet::where('id', $outlet_id)->first();
        // echo "Outlet <pre>"; print_r($outlet); echo "</pre>"; exit;
        //  $emails=explode(',',$outlet->report_emails);
        Queue::push('App\Commands\ReportsMail@sendmails', array('outlet_id' => $outlet_id, 'amount_byuser' => $amount_byuser, 'amount_fromdb' => $amount_fromdb, 'total' => $amount, "total_hours" => $total_hours, "start_time" => $start_time, "end_time" => $end_time, 'start_date' => $request->input('start_date'), 'end_date'   => $request->input('close_date'), 'remark' => $remarks));
        // 'start_date' => Input::json('start_date'), 
        // 'end_date' => Input::json('close_date'), 
        return Response::json(array(
            'message' => 'Counter closed successfully',
            'status' => 'success',
            'statuscode' => 200
        ), 200);
    }
}
