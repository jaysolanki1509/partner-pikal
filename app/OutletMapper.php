<?php namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class OutletMapper extends Model {


    protected $table = 'outlets_mapper';

    protected $fillable = array(
        'outlet_id',
        'owner_id'
    );

    public static function getOutletIdByOwnerId($owner_id){
        $outlet_id = OutletMapper::select('outlet_id')->where('owner_id',$owner_id)->get();
        return $outlet_id;
    }

    public static function getOutletMapperByOwnerId($owner_id){
        $outlet_id = OutletMapper::where('owner_id',$owner_id)->get();
        return $outlet_id;
    }

    public static function getOutletMapperByOutletId($outlet_id){
        $outlet_mapper = OutletMapper::where('outlet_id',$outlet_id)->get();
        return $outlet_mapper;
    }

    public static function getRevenueReport(){

        $data['outlets']=array();
        $outlet_id = '';
        $month_revenue = 0.000;$month_expense = 0.000;

        $sess_outlet_id = Session::get('outlet_session');

        if ( isset($sess_outlet_id) && $sess_outlet_id != '' ) {
            $outlet_id = $sess_outlet_id;
        } else {

            $outlets = Outlet::select('outlets.id as id','outlets.name as name')->join('outlets_mapper','outlets.id','=','outlets_mapper.outlet_id')
                ->where('outlets_mapper.owner_id',Auth::User()->id)->get();


            /*getting outlet ids*/
            $cnt = 0;
            foreach($outlets as $outlet) {

                $data['outlets'][$outlet->id]=$outlet->name;

                if ( $cnt == 0 ) {
                    Session::set('outlet_session',$outlet->id);
                    $outlet_id = $outlet->id;
                }
                $cnt++;

            }
        }

        //get session time
        $from = Utils::getSessionTime(date('Y-m-d'),'from');
        $to = Utils::getSessionTime(date('Y-m-d'),'to');

        /*getting Orders & Pax*/
        $today_ord_pax = order_details::where('orders.outlet_id', $outlet_id)
                            ->where('orders.table_end_date','>=', $from)
                            ->where('orders.table_end_date','<=', $to)
                            ->where('orders.cancelorder','!=','1')
                            ->where('orders.invoice_no',"!=",'')
                            ->get();

        $today_ord = sizeof($today_ord_pax);
        $today_person = 0; $month_person = 0;
        $today_revenue = 0;

        foreach ($today_ord_pax as $order){
            $today_person += $order->person_no;
            $today_revenue += $order->totalprice;
        }

        $data['today_person'] = $today_person;
        $data['today_order'] = $today_ord;
        $data['today_revenue'] = number_format($today_revenue,3);

        $start = Utils::getSessionTime(date('Y-m-d',strtotime(Carbon::yesterday()->startOfMonth())),'from');
        $end = Utils::getSessionTime(date('Y-m-d',strtotime(Carbon::yesterday())),'to');

        $first_date = date('Y-m-d',strtotime($start));
        $month_ord_pax = array();
        if ( $first_date != date('Y-m-d')) {

            $month_ord_pax = order_details::where('outlet_id', $outlet_id)
                                ->where('orders.table_end_date','>=', $start)
                                ->where('orders.table_end_date','<=', $end)
                                ->where('cancelorder','!=','1')
                                ->where('orders.invoice_no',"!=",'')->get();

            foreach ($month_ord_pax as $order){
                $month_person += $order->person_no;
                $month_revenue += $order->totalprice;
            }

        }

        $month_order = sizeof($month_ord_pax);
        $data['month_person'] = $month_person;
        $data['month_order'] = $month_order;
        $data['month_revenue'] = number_format($month_revenue,3);

        $today = Carbon::today();
        $today_expense = Expense::where('expense_for', $outlet_id)
                                ->whereRaw("(status= 'verified' || status = 'paid') && expense_date = '$today'")
                                ->where('type','expense')
                                ->sum('amount');

        $data['today_expense'] = number_format($today_expense,3);

        if ( $first_date != date('Y-m-d')) {
            $start = Utils::getSessionTime(date('Y-m-d',strtotime(Carbon::yesterday()->startOfMonth())),'from');
            $end = date('Y-m-d',strtotime(Carbon::yesterday()))." 23:59:59";
            
            $month_expense = Expense::where('expense_for', $outlet_id)
                ->whereRaw("(status= 'verified' || status = 'paid')")
                ->where('expense_date','>=', $start)
                ->where('expense_date','<=', $end)
                ->where('type','expense')
                ->sum('amount');

        }

        $days = 1;

        if ( date('d', strtotime($first_date)) != date("d")) {
            $days = date('d') - 1 ;
        } else {
            $days = date('d', strtotime(' -1 day'));
        }

        $data['month_expense'] = number_format($month_expense,3);
        $data['avg_revenue'] = number_format(($month_revenue / $days),3);
        $data['avg_expense'] = number_format(($month_expense / $days),3);
        $data['avg_person'] = number_format(($month_person / $days),0);
        $data['avg_order'] = number_format(($month_order / $days),3);
        $pax_no = $month_person>0?$month_person:1;
        $data['avg_pax_rev'] = number_format(( $month_revenue / $pax_no),3);

        $ratings = CustomerFeedback::getFeedbackByOutletId($outlet_id);
        $data['ratings'] = $ratings;

        $data['question_list'] = CustomerFeedback::getQuestionListWithAvgAnswer($outlet_id);

        return $data;
    }
    public static function getDetailReport($flag=null){

        $data=OutletMapper::getRevenueReport();

        $report_type=Request::get("reporttype");
        $outlet_id=Request::get("outlet_id");

        $fromdate = Request::get("from_date");
        $todate = Request::get("to_date");

        $fromdate = date('Y-m-d');
        $todate = date('Y-m-d');

        //if outlet session set
        $outlet_id = Session::get('outlet_session');

        $from_date_time = Utils::getSessionTime($fromdate,'from');
        $to_date_time = Utils::getSessionTime($todate,'to');

        $orders=order_details::join("order_items","order_items.order_id","=","orders.order_id")
            ->join("invoice_details as inv","inv.order_id","=","orders.order_id")
            ->select('orders.*','order_items.item_quantity as Quantity','order_items.item_name as item_name',"inv.total as inv_total","inv.discount as inv_discount")
            ->where('orders.table_start_date','>=',  $from_date_time)
            ->where('orders.table_start_date','<=', $to_date_time)
            ->where('orders.outlet_id','=',$outlet_id)
            ->orderBy('orders.created_at', 'desc')
            ->where('orders.cancelorder', '!=', 1)
            ->where('orders.invoice_no',"!=",'')
            ->get();

        $itemlist=array();
        $data['orders']=array();
        foreach($orders as $order) {
            if (array_key_exists($order->order_id,$itemlist)) {
                if($order->Quantity>1){
                    $itemlist[$order->order_id] = $itemlist[$order->order_id] . ",</br>" .$order->Quantity." x ".strtoupper($order->item_name);
                }else {
                    $itemlist[$order->order_id] = $itemlist[$order->order_id] . ",</br>" ."1 x ".strtoupper($order->item_name);
                }
            } else {
                if($order->Quantity>1){
                    $itemlist[$order->order_id] = $order->Quantity." x ".strtoupper($order->item_name);
                }else {
                    $itemlist[$order->order_id] = "1 x ".strtoupper($order->item_name);
                }
                array_push($data['orders'],$order);
            }
        }

        $data['itemlist']=$itemlist;
        $data['detailed'] = "";

        //$data['fromtime'] = $from_time;
        //$data['totime'] = $to_time;
        $data['fromdate'] = $fromdate;
        $data['todate'] = $todate;
        $data['outlet_id'] = $outlet_id;
        //$data['time_slot_hide'] = $slot_time;
       return $data;
    }

    public static function getOutletsByOwnerId() {

        $user_id = Auth::id();
        $outlets = OutletMapper::join('outlets as ot','ot.id','=','outlets_mapper.outlet_id')
                            ->where('outlets_mapper.owner_id',$user_id)->get();

        $data['']= 'Select Outlet';

        foreach($outlets as $outlet) {
            $data[$outlet->id]=$outlet->name;
        }
        return $data;
    }

    public static function getCategoryOrder($user_id) {

        $owner= Owner::where('id',$user_id)->select('created_by')->first();
        if( isset($owner) && $owner->created_by!=""){
            $menu_owner = $owner->created_by;
        }else{
            $menu_owner = $user_id;
        }

        $category = MenuTitle::where('created_by',$menu_owner)->orderBy('title_order','ASC')->get();

        $cat_arr = array();
        if ( isset($category) && sizeof($category) > 0 ) {
            foreach( $category as $cat ) {

                $cat1 = array();
                $cat1[ucwords($cat->title)] = $cat->title_order;

                array_push($cat_arr,$cat1);
            }
        }

        return $cat_arr;

    }

    public static function getOutletUsers($outlet_id) {

        $admin = Owner::menuOwner();
        $user_id = Auth::id();

        $list = array();
        $list['user_id'][] = '';
        $list['user_name'][] = 'Select User';

        if($admin == $user_id) {

            $userlist = OutletMapper::leftJoin('owners','outlets_mapper.owner_id','=','owners.id')
                ->where('outlets_mapper.outlet_id','=',$outlet_id)->get();

            foreach ($userlist as $users) {
                $list['user_id'][] = $users->owner_id;
                $list['user_name'][] = $users->user_name;
            }

        }else{

            $list['user_id'][] = $user_id;
            $list['user_name'][] = Auth::user()->user_name;

        }

        return $list;
    }


}