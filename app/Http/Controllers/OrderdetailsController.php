<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderDetails;
use App\Outlet;
use App\Menu;
use App\OutletMapper;
use App\Status;
use App\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Piyushpatil\Androidpushnotification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderdetailsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['home']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        //$resttime=array();
        $owner_id = Auth::user()->id;

        /*$Outlet = DB::table("outlets")
            ->join('outlets_mapper','outlets.id','=','outlets_mapper.outlet_id')
            ->where('outlets_mapper.owner_id',Auth::User()->id)->get();*/
        //        $Outlet=Outlet::getoutletbyownerid($owner_id);

        $mappers = OutletMapper::getOutletIdByOwnerId($owner_id);

        foreach ($mappers as $mapper) {
            $mapper_arr[] = $mapper['outlet_id'];
        }
        $Outlet = Outlet::whereIn('id', $mapper_arr)->get();
        //        print_r($Outlet);exit;


        $totalOutletunderuser = count($Outlet);
        //print_r($totalOutletunderuser);exit;


        $allorder = array();

        $allstatus = array();

        $i = 0;
        $j = 0;
        foreach ($Outlet as $restid) {
            $retname = Outlet::findoutlet($restid->id);

            $ord_details = OrderDetails::getorderdetailsbyrestaurantid($restid->id);

            foreach ($ord_details as $ord_details) {
                $allorder[$i] = array(
                    'address' => $ord_details->address,
                    'mobile_number' => $ord_details->mobile_number,
                    'restaurant_name' => $retname->name,
                    'name' => $ord_details->name,
                    'id' => $ord_details->id
                );
                $i++;
            }
        }
        if (Input::get('type') == "ajax") {
            return $allorder;
        } else {
            return view('orderdetails.index', array('order' => $allorder, 'status' => $allstatus, 'Outlet' => $Outlet, 'totalOutletcount' => $totalOutletunderuser));
        }
    }
    public function getstatus()
    {
        $outlet_id = Input::get('rest_id');
        //        print_r($outlet_id);exit;
        $allstatus = array();
        $status = Status::getallstatusofOutlet($outlet_id);


        $i = 0;
        $count = 0;
        foreach ($status as $sta) {

            $allstatus[$i] = array(
                "status" => $sta->status,
                "order" => $sta->order
            );
            $i++;
        }

        return $allstatus;
    }
    public function nextstatus()
    {

        $currents = Input::get('currentstatus');
        $oid = Input::get('oid');
        $outlet_id = Input::get('outlet_id');
        $getcurrentstatus = Status::getstatusbyname($currents);
        $getnextstatus1 = Status::where('status', $currents)->where('outlet_id', $outlet_id)->first();

        if (isset($getnextstatus1) && sizeof($getnextstatus1) > 0) {
            $getnextstatus = Status::getstatusbyitssequenceandoutletid($getnextstatus1->order, Input::get('outlet_id'));

            if (isset($getnextstatus) && sizeof($getnextstatus) > 0) {

                $ordstat = OrderDetails::where('order_id', $oid)->first();
                // print_r($ordstat);exit;
                DB::table('orders')->where('status', $currents)->where('order_id', $oid)->update(array('status' => $getnextstatus->status));

                $restname = Outlet::where('id', $ordstat['restaurant_id'])->first();
                // print_r($restname);exit;
                // $ordstat=DB::update('update order_details set status = $getnextstatus->status where status = ?', [$currents]);

                $currentstatus = $getnextstatus->status;
                $apiKey = "AIzaSyCf2SG9LH_CPqvV4OslV3TzegLczHWh7pQ";

                $deviceid = array();


                array_push($deviceid, $ordstat['device_id']);

                // Replace with real client registration IDs
                $registrationIDs = $deviceid;

                $message = "Status is Changed from " . $currents . " to " . $currentstatus . " status";
                // $userid = $this->authUser_NameSpace->user_id;

                // Set POST variables
                $url = 'https://fcm.googleapis.com/fcm/send';

                $fields = array(
                    'registration_ids'  => $registrationIDs,
                    'data' => array(
                        "message" => $message,
                        "status" => $currentstatus,
                        "server_id" => $ordstat['suborder_id']
                    ),
                );

                $headers = array(
                    'Authorization: key=' . $apiKey,
                    'Content-Type: application/json'
                );

                // Open connection
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);

                // Set the url, number of POST vars, POST data
                curl_setopt($ch, CURLOPT_URL, $url);

                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);



                OrderDetails::updateorderstatus($currents, $oid, $getnextstatus->status);

                $restname = Outlet::Outletbyid($ordstat['outlet_id']);

                $currentstatus = $getnextstatus->status;

                OrderDetails::sendpushnotification($ordstat['device_id'], $currents, $currentstatus, $oid, $ordstat['created_at'], $oid);
            }
        }

        return $currentstatus;
    }
    public function searchorder()
    {
        $order_id = Input::get('order_id');
        $phone_number = Input::get('phone_number');
        $name = Input::get('name');
        $status = Input::get('status');
        $address = Input::get('address');
        $table = Input::get('table');
        $ordertype = Input::get('ordertype');
        $date = Input::get('dt');


        $restloggedinuser = Outlet::getoutletbyownerid(Auth::user()->id);


        $array = array();
        foreach ($restloggedinuser as $restidsarray) {
            array_push($array, $restidsarray->id);
        }
        $ord = OrderDetails::searchorder($date, $order_id, $phone_number, $name, $status, $address, $table, $ordertype, $array);
        return view('orderdetails.searchorder', array("orders" => $ord));
    }

    public function getordernotification()
    {
        $user_id = Input::get('user_id');
        $datetime = date("Y-m-d H:i:s", time() - 30);
        // $allOutletofloggedinuser=Outlet::Outletbyownerid($user_id);

        $allOutletofloggedinuser = DB::table('outlets')
            ->select('outlets.id as oid', 'outlets.*', 'outlets_mapper.*')
            ->join('outlets_mapper', 'outlets.id', '=', 'outlets_mapper.outlet_id')
            ->where('outlets_mapper.owner_id', $user_id)
            ->get();
        $arrayofrestids = array();
        if (isset($allOutletofloggedinuser))
            foreach ($allOutletofloggedinuser as $getrestids) {
                array_push($arrayofrestids, $getrestids->id);
            }

        $orddetails = OrderDetails::getordernotification($datetime, $arrayofrestids);

        return $orddetails;
    }
    public function updateorderdetailstable()
    {
        OrderDetails::updateorderreadstatus();
        return 'success';
    }
    public function getallorderdetails()
    {
        $outlet_id = Input::get('rest_id');
        //  print_r($outlet_id);exit;
        $status = Input::get('status');

        // $date=date('Y-m-d');
        $getlastupdatedtime = Input::get('lasttime');
        //        print_r($getlastupdatedtime);exit;
        //$date=date('Y-m-d');
        $datetime = date('Y-m-d H:i:s', strtotime($getlastupdatedtime));


        if ($status == 'all') {
            $orddetails = OrderDetails::where('outlet_id', $outlet_id)->where('created_at', '>', new Carbon($datetime))->orderBy('created_at', 'desc')->get();
        } else {
            $orddetails = OrderDetails::where('outlet_id', $outlet_id)->where('status', $status)->where('created_at', '>', new Carbon($datetime))->orderBy('created_at', 'desc')->get();
        }


        $retname = Outlet::findOutlet($outlet_id);



        $j = 0;
        $allord = array();
        $getdata = array();
        $count = 0;
        $totalcount = 0;
        if ($status == 'all') {
            $orders = $retname->orderdetail()->where('created_at', '>=', new Carbon($datetime))->orderBy('created_at', 'desc')->get();
        } else {
            $orders = $retname->orderdetail()->where('created_at', '>=', new Carbon($datetime))->orderBy('created_at', 'desc')->where('status', $status)->get();
        }

        return view('orderdetails.order', array("orders" => $orders))->with('rescode', $retname->Outlet_code);
    }


    public function status() {}
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
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    public function currentorderdetails()
    {
        $outlet_id = Input::get('outlet_id');



        $order_id = Input::get('order_id');
        $retname = Outlet::findOutlet($outlet_id);
        //$date=date('Y-m-d');
        $getlastupdatedtime = Input::get('lasttime');
        //$date=date('Y-m-d');
        $datetime = date('Y-m-d H:i:s', strtotime($getlastupdatedtime));
        $orders = $retname->orderdetail()->where('order_id', '=', $order_id)->where('created_at', '>=', new Carbon($datetime))->orderBy('created_at', 'desc')->get();

        return view('orderdetails.currentorder', array("orders" => $orders))->with('rescode', $retname->Outlet_code);
    }



    public function allorders()
    {

        $orddetails = DB::table('orders')->orderBy('created_at', 'desc')->get();
        return view('orderdetails.allorder', array("orders" => $orddetails));
    }
}
