<?php namespace App\Http\Controllers\Api\v1;

use App\CancellationReason;
use App\City;

use App\CouponCodes;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//use App\Libraries\Image;
use App\Itemreview;
use App\menu_option;
use App\order_details;
use App\OrderCancellation;
use App\ordercouponmapper;
use App\OutletMapper;
use App\OutletType;
use App\Owner;
use App\payumoney;
use App\Printsummary;
use App\Reviews;
use App\State;
//use Illuminate\Http\Request;
use App\Termsandcondition;
use Illuminate\Support\Facades\Hash;
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


use Illuminate\Contracts\Auth\Guard;


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
        $restmenu = array();
        $Outletid = Request::get('restaurant_id');
        $menutitle =MenuTitle::getmenutitlebyrestaurantid($Outletid);
        $useragent=Request::header('User-Agent');

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
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE || strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE){
            return view('webview.restaurantmenu',array('restaurantmenu'=>$restmenu));
        }else {
            return Response::json(array(
                    'message' => 'List of menu',
                    'status' => 'success',
                    'statuscode' => 200,
                    'menu' => $restmenu),
                200);
        }
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

    //order added from here in backend
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

        if(isset($Outlet)){
            $startingstatus=status::getallstatusofOutlet($order['restaurant_id']);
            $lastindex= count($startingstatus)-1;
            if(isset($startingstatus)){
                if($order['order_type']=="dine_in") {
                    $status=$startingstatus[$lastindex]->status;
                } else {
                    $status=$startingstatus[0]->status;
                }
            }else{
                $status='';
            }

            $order_ids=order_details::getorderid();

            $suborder_id=order_details::getorderidofrestaurant($order['restaurant_id']);
            $a= $Outlet->pluck('code');
            if(isset($order['mobile_number'])) {
                DB::table('orders')->where('user_mobile_number',$order['mobile_number'])->update(array('device_id'=>$order['device_id']));
            }
            $saveorder=order_details::insertorderdetails($a,$order_ids,$order,$status,$suborder_id);


            foreach($order['menu_item'] as $asd) {
                $orderid=OrderItem::insertmenuitemoforders($saveorder['id'],$asd);
            }

            // Queue::push('App\Commands\MailNotification@getorderdetails', array('orderdetails'=>$saveorder));

            $date = $saveorder['order_date'];
            $date = str_replace('/', '-', $date);

            $orddate=date("F j, Y g:i a",strtotime($date));




            Queue::push('App\Commands\OwnerNotification@getownernotification', array('outlet_id'=>$order['restaurant_id']));
            if( isset($flag) && $flag == 'webapp_order' ){
                return 'success';
            }else{

                return Response::json(array(
                        'message' => 'Order Placed Successfully.Go To My Order To Check Your Status',
                        'status' => 'success',
                        'statuscode' => 200,
                        'local_id' => $order['local_id'],
                        'server_id' => $suborder_id,
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
    public function owneroutlet(){
        $username=Request::json('owner_name');
        $pass=Request::json('owner_pass');
        $device_id=Request::json('device_id');
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
                if ($this->auth->attempt(array('email' => $username, 'password' => $pass))) {
                    $session_id = '';// Session::getId();
                } else {
                    $session_id = '';
                }
            } else {
                if ($this->auth->attempt(array('user_name' => $username, 'password' => $pass))) {
                    $session_id = '';// Session::getId();
                } else {
                    $session_id = '';
                }
            }

            if(isset($device_id) && $device_id!=''){
                DB::table('owners')->where($field,'=',$username)->update(array('device_id'=>$device_id));
            }
            $islogin=Owner::where($field,'=',$username)->first();


//            $where = ['owner_id' => $user->id,'active' => 'Yes'];
//            $Outlet=Outlet::where($where)->get();

            $where = ['owner_id' => $user->id];
            $mappers = OutletMapper::getOutletIdByOwnerId($where);

            foreach($mappers as $mapper)
            {
                $mapper_arr[] = $mapper['outlet_id'];
            }
            $Outlet=Outlet::whereIn('id',$mapper_arr)->where('active','Yes')->get();



//            $Outlet=Outlet::where('owner_id',$user->id)->get();

            $i=0;
            foreach($Outlet as $outlet){
                $outlet[$i];
                $i++;
            }
            if($i==1){
                $maxdt=[];
                array_push($maxdt,Carbon::now());
                $restaurant=$user->outlet->pluck('id');
                $restaurant_id=$restaurant[0];
                $retname = Outlet::find($restaurant_id);
                $islogin=Owner::where($field,'=',$username)->first();
                $tax=Tax::where('outlet_id',$restaurant_id)->get();
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
                    $uniqueprice = DB::table('orders')->where('status', '=', 'delivered')->get();
                    if(sizeof($uniqueprice)>0) {
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

                    if(sizeof($otheroutletordercount)>0){
                        foreach ($otheroutletordercount as $k => $v) {
                            if (!array_key_exists($v->user_mobile_number, $rating)) {
                                $othercount[$v->user_mobile_number] = 1;
                            } else {
                                $othercount[$v->user_mobile_number] = $othercount[$v->user_mobile_number] + 1;
                            }

                        }
                    }
                    if(sizeof($forrating)>0) {
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
                        $item = OrderItem::where('order_id', $order->order_id)->get();
                        $totalpr = 0;
                        $it = array();
                        foreach ($item as $t) {

                            $itemnew = $t->menuitem;
                            $madt=date("Y-m-d H:i:s",strtotime($order->created_at));
                            array_push($it, array("item" => $itemnew->item, "quantity" => $t->item_quantity, "price" => $itemnew->price,"suborder_id"=>$order->suborder_id,"created_at"=>$madt,"item_options"=>$t->options,"item_options_price"=>$t->item_options_price));
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
                $feedback=DB::table('feedback')->where('outlet_id','=',$restaurant_id)->get();
                $menu=Apicontroller::getoutletmemu($restaurant_id);

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
                        'ownername'=>$islogin->user_name,
                        'outletname'=>$retname->name,
                        'outlet_address'=>$retname->address,
                        'contact_no'=>$retname->contact_no,
                        'tin_number'=>$retname->tinno,
                        'servicetax_number'=>$retname->servicetax_no,
                        'service_tax'=>$retname->service_tax,
                        'vat'=>$retname->vat,
                        'vat_date'=>$retname->vat_date,
                        'invoice_digit'=>$retname->invoice_digit,
                        'outlet_code'=>$retname->code,
                        'outlet_count'=>$i,
                        'restmenu'=>$menu,
                        'feedback'=>$feedback
                    ),
                    200);
            }else {
                return Response::json(array(
                        'message' => 'List Of all Outlet',
                        'outlet_details' => $Outlet,
                        'statuscode' => 200,
                        'outlet_count' => $i,
                    ),
                    200);
            }
        }else{
            return Response::json(array(
                    'message' => 'Your outlet is not added in Foodklub.',
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
        // print_r($input);exit;
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
                if ($this->auth->attempt(array('email' => $username, 'password' => $pass))) {
                    $session_id = '';// Session::getId();
                } else {
                    $session_id = '';
                }
            } else {
                if ($this->auth->attempt(array('user_name' => $username, 'password' => $pass))) {
                    $session_id = '';// Session::getId();
                } else {
                    $session_id = '';
                }
            }
            $maxdt=[];
            array_push($maxdt,Carbon::now());
            $retname = Outlet::find($restaurant_id);
            if(isset($device_id) && $device_id!=''){
                $device_id.=','.$user->device_id;
                DB::table('owners')->where($field,'=',$username)->update(array('device_id'=>$device_id));
            }
            $islogin=Owner::where($field,'=',$username)->first();
            $tax=Tax::where('outlet_id',$restaurant_id)->get();
            $star = [];
            $orders = $retname->orderdetail()->where('status', '!=', 'delivered')->where('cancelorder', '!=', 1)->orderBy('created_at', 'asc')->get();

            $totalprice = [];
            $rating = [];
            $uniquepricearray = [];
            $othercount = [];
            $items = [];
            $printcount=[];
            if (count($orders) > 0) {
                $forrating = $retname->orderdetail()->where('status', '=', 'delivered')->orderBy('created_at', 'desc')->get();
                $otheroutletordercount = DB::table('orders')->where('status', '=', 'delivered')->where('outlet_id', '!=', $restaurant_id)->get();
                $uniqueprice = DB::table('orders')->where('status', '=', 'delivered')->get();
                if(sizeof($uniqueprice)>0) {
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

                if(sizeof($otheroutletordercount)>0){
                    foreach ($otheroutletordercount as $k => $v) {
                        if (!array_key_exists($v->user_mobile_number, $rating)) {
                            $othercount[$v->user_mobile_number] = 1;
                        } else {
                            $othercount[$v->user_mobile_number] = $othercount[$v->user_mobile_number] + 1;
                        }

                    }
                }
                if(sizeof($forrating)>0) {
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
                    $item = OrderItem::where('order_id', $order->order_id)->get();
                    $totalpr = 0;
                    $it = array();
                    foreach ($item as $t) {

                        $itemnew = $t->menuitem;

                        $madt=date("Y-m-d H:i:s",strtotime($order->created_at));

                        array_push($it, array("item" => $itemnew->item, "quantity" => $t->item_quantity, "price" => $itemnew->price,"suborder_id"=>$order->suborder_id,"created_at"=>$madt,"item_options"=>$t->item_options,"item_options_price"=>$t->item_options_price));

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


            $feedback=DB::table('feedback')->where('outlet_id','=',$restaurant_id)->get();
            $menu=Apicontroller::getoutletmemu($restaurant_id);

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
                    'ownername'=>$islogin->user_name,
                    'outletname'=>$retname->name,
                    'outlet_address'=>$retname->address,
                    'contact_no'=>$retname->contact_no,
                    'tin_number'=>$retname->tinno,
                    'servicetax_number'=>$retname->servicetax_no,
                    'service_tax'=>$retname->service_tax,
                    'vat'=>$retname->vat,
                    'vat_date'=>$retname->vat_date,
                    'outlet_code'=>$retname->code,
                    'invoice_digit'=>$retname->invoice_digit,
                    'restmenu'=>$menu,
                    'feedback'=>$feedback
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

    public function ownerlogout(){
        $restaurantid=Request::get("resid");

        $item=Outlet::where('id',$restaurantid)->get();

        if(sizeof($item)>0){
            $ownerid=$item[0]->owner_id;
            DB::table("owners")->where('id','=',$ownerid)->update(array('islogin'=>0, 'device_id' => null));
            return Response::json(array(
                    'message' => 'User Logged Out Successfully',
                    'statuscode' => 200,
                    'status'=>'Success',

                ),
                200);
        }else{
            return Response::json(array(
                    'message' => 'No Owner Data',
                    'statuscode' => 434,
                    'status'=>'Success',

                ),
                200);
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
        $resid=Request::json('restaurant_id');
        $position=Request::json('position');
        $useragent=Request::json('user_agent');
        $getcurrentstatus=status::where('status',$currents)->where('outlet_id',$resid)->get();

        $order_date=Request::json('order_date');
        $currentstatus='';
        $status='';
        $f=array();
        $order_summary=array();
        foreach($getcurrentstatus as $getstatus){

            $getnextstatus=status::where('order','>',$getstatus->order)->where('outlet_id',$resid)->orderby('order','ASC')->first();



            if(isset($getnextstatus)>0){

                $ordstat=order_details::where('status',$currents)->where('suborder_id',$oid)->where('outlet_id',$resid)->where('created_at',$order_date)->first();


                order_details::where('status',$currents)->where('suborder_id',$oid)->where('outlet_id',$resid)->where('created_at',$order_date)->update(array('status'=>$getnextstatus->status));

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

            }
        }


        if($useragent=="android"){
            Queue::push('App\Commands\OrderNotification@getordersnotification', array('fields'=>$f));
        }else{
            Queue::push('App\Commands\IOS\OrderNotification@getordersnotification', array('fields'=>$f));
        }
        return Response::json(array(
                'message' => 'ok',
                'statuscode' => 200,
                'nextstatus'=>ucfirst($currentstatus),
                'buttonstatus'=>$status='delivered'?ucfirst($status):"",
                'position'=>$position,
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


        if(isset($contact) && isset($user_entered_couponcode)){
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
        }else{
            $user_entered_couponcode=Request::json('coupon_code');
            $total_cost=Request::json('total_cost');
            $mobile_number=Request::json('mobile_number');
        }
        $date=Date("Y-m-d");

        //print_r($user_entered_couponcode.'==='.$total_cost.'==='.$mobile_number.'==='.$flag);exit;

        $coupondata=CouponCodes::where('coupon_code',$user_entered_couponcode)->first();
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
                            'status' => 'applied');
                    }else{
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
                                'status' => 'applied');
                        }else{
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
                            'status' => 'applied');
                    }else{
                        return Response::json(array(
                                'message' => "Coupon $coupondata->coupon_code applied successfully.  &#8377; $roundedvalue is discounted.",
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
        Excel::create('foodklub_report', function($excel) use($result,$totalprice) {
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
            $message->subject('Foodklub Report');
            $message->attach(app_path().'/../storage/exports/foodklub_report.xls', ['as' => 'foodklub_report.xls', 'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ]);
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
        $reason=Request::json('reason');
        $orderdate=Request::json('order_date');

        order_details::where('suborder_id',$order_id)->where('created_at',$orderdate)->update(array('cancelorder'=>1));
        $ordercancellation =new OrderCancellation();
        $ordercancellation->outlet_id=$resid;
        $ordercancellation->suborder_id=$order_id;
        $ordercancellation->reason=$reason;
        $ordercancellation->order_date=$orderdate;
        $ordercancellation->save();
        $orderdetails=order_details::where('suborder_id',$order_id)->where('created_at',$orderdate)->get();
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


        // $reviews=Reviews::skip($start)->take($limit);


        return Response::json(array(
            'message' => 'Reviews added successfully',
            'status' => 'success',
            'statuscode' => 200,
            'review'=>$review,
            'addreview'=>'true'

        ),200);


    }

    public function getreviews(){

        $resid=Request::json('res_id');
        $mobile_number=Request::json('user_mobile_number');

        $reviews=Reviews::where('resid',$resid)->get();
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
            'ordercount'=>$ordercount

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
        $types=array('take_away'=>'Take Away','dine_in'=>'Dine In','home_delivery'=>'Home Delivery','meal_packs'=>'Meal Packs');
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
        $serverids=array();
        for($i=0;$i<count($orders);$i++) {
            $order=$orders[$i];

            $array=array();
            // $orders_count=DB::table('orders')->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->where('order_unique_id',$order['orderuniqueid'])->count();
            $orders_count=DB::table('orders')->where('order_unique_id',$order['orderuniqueid'])->count();
            if($orders_count==0) {
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

                    $order_ids = order_details::getorderid();

                    $suborder_id = order_details::getorderidofrestaurant($order['restaurant_id']);

                    $tempdata['local_id'] = $order['primary_id'];
                    $tempdata['suborder_id'] = $suborder_id;
                    array_push($serverids, $tempdata);

                    $a = $Outlet->pluck('code');

                    $saveorder = order_details::insertorderdetails($a, $order_ids, $order, $status, $suborder_id);


                    foreach ($order['menu_item'] as $asd) {
                        $orderid = OrderItem::insertmenuitemoforders($saveorder['id'], $asd);
                    }

                    // Queue::push('App\Commands\MailNotification@getorderdetails', array('orderdetails'=>$saveorder));

                    // $date = $saveorder['order_date'];
                    // $date = str_replace('/', '-', $date);

                    // $orddate = date("F j, Y g:i a", strtotime($date));

                }
            }
        }

        return Response::json(array(
                'message' => 'Order Placed Successfully.Go To My Order To Check Your Status',
                'status' => 'success',
                'statuscode' => 200,
                'server_ids' => $serverids
            ),
            200);

    }

    public function closeCounter(Request $request){
        $outlet_id=Request::json('res_id');
        $start_date=Carbon::parse(Request::json('start_date'));
        $end_date=Carbon::parse(Request::json('close_date'));
        $amount=Request::json('total');
        $amount_byuser=Request::json('total_byuser')?Request::json('total_byuser'):0;
        $amount_fromdb=Request::json('total_fromdb')?Request::json('total_fromdb'):0;
        $remarks=Request::json('remarks');

        $a=$start_date->diff($end_date);
        $total_hours = $a->format("%H:%i");
        $start_time= $start_date->format("H:i");
        $end_time= $end_date->format("H:i");

        $outlet=Outlet::where('id',$outlet_id)->first();
        //  $emails=explode(',',$outlet->report_emails);

        Queue::push('App\Commands\ReportsMail@sendmails', array('outlet_id'=>$outlet_id,'amount_byuser'=>$amount_byuser,'amount_fromdb'=>$amount_fromdb,'total'=>$amount,"total_hours"=>$total_hours,"start_time"=>$start_time,"end_time"=>$end_time,'start_date'=>Request::json('start_date'),'end_date'=>Request::json('close_date'),'remark'=>$remarks));
        return Response::json(array(
            'message' => 'Counter closed successfully',
            'status' => 'success',
            'statuscode' => 200
        ),200);
    }

}





