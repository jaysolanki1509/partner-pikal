<?php namespace App\Http\Controllers;
//use App\order_details;
use App\Account;
use App\CreditNote;
use App\DailySummary;
use App\Expense;
use App\Http\Controllers\Api\v3\Apicontroller;
use App\invoice_detail;
use App\ItemGroupOption;
use App\ItemOptionGroup;
use App\ItemOptionGroupMapper;
use App\ItemRequest;
use App\ItemSettings;
use App\Kot;
use App\Language;
use App\Menu;
use App\MenuItemOption;
use App\order_details;
use App\order_item_attributes;
use App\order_payment_mode;
use App\OrderCancellation;
use App\OrderHistory;
use App\OrderItem;
use App\OrderItemOption;
use App\Outlet;
use App\Outlet_Menu_Bind;
use App\OutletMapper;
use App\OutletSetting;
use App\PaymentOption;
use App\ResponseDeviation;
use App\SendCloseCounterStatus;
use App\Sources;
use App\Stock;
use App\StockAge;
use App\users;
use App\Utils;
use Aws\CloudFront\Exception\Exception;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\City;
use App\Http\Controllers\Controller;
use App\State;
use Illuminate\Http\Request;
use App\Services\Registrar;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Savitriya\Icici_upi\IciciUpiTxn;
use stdClass;
use View;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Validation\Validator;
use App\Owner;
use Illuminate\Support\Facades\Auth;



class HomeController extends Controller {

    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * The registrar implementation.
     *
     * @var \Illuminate\Contracts\Auth\Registrar
     */
    protected $registrar;


    public function __construct(Guard $auth, Registrar $registrar)
	{
        $this->auth = $auth;
        $this->registrar = $registrar;

        $this->middleware('auth');

	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        $data=OutletMapper::getRevenueReport();

        $data['dates'] = $this::getLastNDays(7, 'd M');
        $data['last_updated'] = date('Y-m-d H:i:s');

        return view('homenew',$data);
	}

    function getLastNDays($days, $format = 'd/m'){
        $m = date("m"); $de= date("d"); $y= date("Y");
        $dateArray = array();
        for($i=0; $i<=$days-1; $i++){
            $dateArray[] =  date($format, mktime(0,0,0,$m,($de-$i),$y));
        }
        return array_reverse($dateArray);
    }

	public function dashboardOrderData(){

        $day = Input::get('day');

        $selected_date = Carbon::now()->subDays($day);
        $sod = Carbon::now()->startOfDay()->subDays($day); //start of day
        $eod = Carbon::now()->endOfDay()->subDays($day);    //end of day

        $select_date = $selected_date->format('Y-m-d');
        $today = Carbon::now()->format('Y-m-d');
        if($select_date == $today){
            $lbl_date = 'Today '.$selected_date->format('(D)');;
        }else{
            $lbl_date = $selected_date->format('d M (D)');
        }
        $data['lbl'] = $lbl_date;
        $data['outlets']=array();
        $outlet_ids = [];

        $sess_outlet_id = Session::get('outlet_session');

        if ( isset($sess_outlet_id) && $sess_outlet_id != '' ) {
            $outlet_ids[] = $sess_outlet_id;
        } else {

            $outlets = DB::table("outlets")
                ->join('outlets_mapper','outlets.id','=','outlets_mapper.outlet_id')
                ->where('outlets_mapper.owner_id',Auth::User()->id)->get();


            /*getting outlet ids*/
            foreach($outlets as $outlet) {

                $data['outlets'][$outlet->id]=$outlet->name;
                $outlet_ids[] = $outlet->outlet_id;

            }
        }

        $date_ord_pax = order_details::whereIn('orders.outlet_id', $outlet_ids)
            ->where('orders.table_end_date','>=', $sod)
            ->where('orders.table_end_date','<=', $eod)
            ->where('orders.cancelorder','!=','1')
            ->where('orders.invoice_no',"!=",'')
            ->get();

        $date_ord = sizeof($date_ord_pax);
        $date_person = 0; $date_revenue = 0;

        foreach ($date_ord_pax as $order){
            $date_person += $order->person_no;
            $date_revenue += $order->totalprice;
        }

        $data['date_person'] = $date_person;
        $data['date_order'] = $date_ord;
        $data['date_revenue'] = number_format($date_revenue,2);

        $date_expense = Expense::whereIn('expense_for', $outlet_ids)
            ->where('expense.expense_date','>=', $sod)
            ->where('expense.expense_date','<=', $eod)
            ->where('verify','1')
            ->sum('expense.amount');

        $data['date_expense'] = number_format($date_expense,2);

        return $data;

    }

    public function dashboardreport(){
        $getdays=Input::get('selecteddates');
        $orddetails=DB::table('order_details');
        if($getdays=='today'){
            $date=date('Y-m-d');
            $starting_time= new \DateTime($date);
            $ending_time= new \DateTime($date);
            $ending_time->modify('+23 hours +59 minutes +59 seconds');
            $starting=get_object_vars($starting_time);
            $ending=get_object_vars($ending_time);
            $orddetails->where('created_at', '>=',new Carbon($starting['date']))->where('created_at', '<=',new Carbon($ending['date']))->orderBy('created_at', 'desc');
            $ord=$orddetails->count();
        }
        elseif($getdays=='weekly'){
            $weekstartdate = date("Y-m-d",strtotime('monday this week'));
            $weekenddate=date("Y-m-d",strtotime("sunday this week"));
            $orddetails->where('created_at', '>=',new Carbon($weekstartdate))->where('created_at', '<=',new Carbon($weekenddate))->orderBy('created_at', 'desc');
            $ord=$orddetails->count();

        }
        else{
            $firstdatemonth = date('Y-m-01 00:00:00');
            $lastdateofmonth  = date('Y-m-t 12:59:59');
            $orddetails->where('created_at', '>=',new Carbon($firstdatemonth))->where('created_at', '<=',new Carbon($lastdateofmonth))->orderBy('created_at', 'desc');
            $ord=$orddetails->count();

        }
        return $ord;

    }
    public function editUser($id)
    {
        $user=Owner::where('id',$id)->first();
        $language=Language::all();

        return view('auth.edituser',array('users'=>$user,'language'=>$language));
    }

    public function updateUser($id)
    {
        Owner::where('id',$id)->update(array('lang'=>Input::get('language')));
        app()->setLocale(Input::get('language'));

        return view('home');
    }

    public function populateInvoiceTable() {
        // exit;
        $orders = order_details::all();

        if ( isset($orders) && sizeof($orders) >  0 ) {
            foreach($orders as $or ) {

                /*$inv_detail = invoice_detail::where('order_id',$or->order_id)->first();
                if ( isset($inv_detail) && sizeof($inv_detail) > 0 ) {

                } else {
                    $inv = new invoice_detail();
                    $inv->order_id = $or->order_id;
                    $inv->sub_total = $or->totalprice;
                    $inv->total = $or->totalprice;
                    $inv->save();
                }*/

            }
            // echo 'DONE';exit;
        }
    }

    public function changeTaxFormat()
    {
        // exit;
        ini_set('max_execution_time', 0);
        $orders = order_details::all();
        print "<pre>";
        if (isset($orders) && sizeof($orders) > 0) {
            foreach ($orders as $order) {
                //$singleorder=order_details::find($order->order_id);

                if($order->tax_type!=null && $order->tax_type!="") {
                    echo "order_id = ".$order->order_id;
                    if($order->order_id<3292) {
                        $oldtax = json_decode($order->tax_type);

                        $newarray = array();

                        foreach ($oldtax as $key => $value) {
                            $obj = new stdClass();
                            $obj->$key = $value;

                            $newarray[] = json_encode($obj);

                        }


                        $order_new = order_details::where('order_id', $order->order_id)->update(['tax_type' => json_encode($newarray)]);
                        //$order_new = invoice_detail::where('order_id', $order->order_id)->update(['taxes' => json_encode($newarray)]);


                     }
                }



            }
        }
    }

    public function updateMapper(){
        // exit;
        $outlets=Outlet::lists('name','id');
        //print_r($outlet);exit;

        foreach($outlets as $outlet_id=>$value){
            $outlet_find=OutletMapper::where('outlet_id',$outlet_id)->get();
            if(sizeof($outlet_find)==0){
                print_r($outlet_id."=>saved");
                $map = new OutletMapper();
                $map->outlet_id=$outlet_id;
                $map->owner_id=33;
                $map->save();
            }
        }
    }

    public function populateInvoiceField() {
        // exit;

        ini_set('max_execution_time', 0);

        $orders = order_details::all();
        if(isset($orders) && sizeof($orders) > 0 ) {
            foreach( $orders as  $order ) {
                $outlet = Outlet::find($order->outlet_id);
                if ( isset($order->invoice_no) && $order->invoice_no != '' ) {
                    $inv = substr($order->invoice_no,-3);
                    echo $order->invoice_no."==".intval($inv)."<br>";
                    order_details::where('order_id',$order->order_id)->update(['invoice'=>intval($inv)]);
                }
            }
        }
        // echo 'done';exit;
    }

    public function populateSubTotalField() {
// exit;
        //ini_set('max_execution_time', 0);

        $date = Input::get('date');

        $orders = order_details::where('outlet_id',23)
                                ->where('orders.table_end_date','>=', (new Carbon($date))->startOfDay())
                                ->where('orders.table_end_date','<=', (new Carbon($date))->endOfDay())
                                ->get();

        $n = 0;
        if( isset($orders) && sizeof($orders)>0 ) {

            foreach( $orders as $ord ) {

                $items_sum = OrderItem::where('order_id',$ord->order_id)
                                    ->selectRaw('sum(item_price * item_quantity) as subtotal')
                                    ->groupby('order_id')
                                    ->get();

                if ( $items_sum[0]->subtotal != $ord->totalcost_afterdiscount ) {
                    order_details::where('order_id', $ord->order_id)->update(['totalcost_afterdiscount' => $items_sum[0]->subtotal]);
                    $n++;
                }
                //invoice_detail::where('order_id', $ord->order_id)->update(['sub_total'=> $items_sum]);

            }
        }
        echo $n.' orders has been updated';
    }

    public function populateItemTotal() {
        ini_set('max_execution_time', 0);

        $n = 0;
        $items = OrderItem::whereNull('item_total')->get();

        if(isset($items) && sizeof($items)>0 ) {
            foreach($items as $itm ) {

                $total = $itm->item_price * $itm->item_quantity;

                OrderItem::where('id',$itm->id)->update(['item_total'=>$total]);
                $n++;
            }
        }
        echo $n." columns updated";

    }

    public function updateInvoiceNumber() {
        // exit;
        $orders = order_details::where('outlet_id',23)->orderby('table_end_date')->get();
        if ( isset($orders) && sizeof($orders) > 0 ) {
            $compare_date = '';$cnt = 1;$records = 0;
            foreach( $orders as $ord ) {
                $inv_no = '';
                if ( isset($ord->invoice_no) && $ord->invoice_no != '') {
                    $end_date = date('Y-m-d',strtotime($ord->table_end_date));
                    if ( $end_date == $compare_date) {
                        $cnt++;
                        $inv_no = 'R'.date('Ymd',strtotime($ord->table_end_date)).''.str_pad($cnt,3,0,STR_PAD_LEFT);
                    } else {
                        $compare_date = $end_date;
                        $cnt = 1;
                        $inv_no = 'R'.date('Ymd',strtotime($ord->table_end_date)).''.str_pad($cnt,3,0,STR_PAD_LEFT);
                    }
                    //echo $ord->table_end_date."====".$inv_no."<br>";
                    order_details::where('order_id',$ord->order_id)->update(['invoice_no'=>$inv_no,'invoice'=>$cnt]);
                    $records++;
                }
            }
            echo $records." records has been updated";
        }

    }

    public function populateStockAgeTable() {
        // exit;
        $stocks = Stock::all();

        if ( isset($stocks) && sizeof($stocks) > 0 ) {
            $n=0;
            foreach( $stocks as  $stock ) {

                $st_age = new StockAge();
                $st_age->location_id = $stock->location_id;
                $st_age->item_id = $stock->item_id;
                $st_age->unit_id = $stock->unit_id;
                $st_age->quantity =$stock->quantity;
                $st_age->transaction_id = uniqid();
                $st_age->created_by = $stock->created_by;
                $st_age->updated_by = $stock->created_by;
                $st_age->save();
                $n++;
            }
        }
        echo $n." records inserted";
    }

    public function populateTaxesPP() {
        // exit;
        $from_date_time = '2016-06-01 00:00:00';
        $to_date_time = '2016-06-08 23:59:59';

        $outlet = Outlet::find(23);

        $orders = order_details::join("invoice_details as inv","inv.order_id","=","orders.order_id")
            ->select('orders.*',"inv.total as inv_total","inv.discount as inv_discount","inv.round_off as inv_round_off")
            ->where('orders.table_start_date','>=', Carbon::createFromFormat("Y-m-d H:i:s",$from_date_time))
            ->where('orders.table_start_date','<=', Carbon::createFromFormat("Y-m-d H:i:s",$to_date_time))
            ->where('orders.outlet_id','=',23)
            ->orderBy('orders.created_at', 'desc')
            ->where('orders.cancelorder', '!=', 1)
            ->where('orders.invoice_no', '!=', '')
            ->get();


        $tax = json_decode($outlet->taxes);$n=0;
        foreach( $orders as $ord ) {
            $tr = array();$total = $ord->totalcost_afterdiscount;
            foreach(  $tax as $tx ) {
                foreach( $tx as $k=>$v ) {

                    $tax_arr = array();
                    $sb_total = $ord->totalcost_afterdiscount;
                    $tx_val = $sb_total * $v / 100;
                    $tax_arr[$k] = ['percent'=>$v,'calc_tax'=>$tx_val];

                    $total += $tx_val;

                }
                array_push($tr,$tax_arr);
            }
            if( sizeof($tr) > 0 ) {

                $round_of_total = round($total);
                $round_of_val = number_format(abs($total - $round_of_total),2,'.','');
                $total = number_format($round_of_total,2,'.','');

                order_details::where('order_id',$ord->order_id)->update(['tax_type'=>json_encode($tr),'totalprice'=>$total]);
                //invoice_detail::where('order_id',$ord->order_id)->update(['taxes'=>json_encode($tr),'total'=>$total,'round_off'=>$round_of_val]);
            } else {
                order_details::where('order_id',$ord->order_id)->update(['tax_type'=>'']);
                //invoice_detail::where('order_id',$ord->order_id)->update(['taxes'=>json_encode()]);
            }
            $n++;
        }
        echo $n." records updated";exit;
    }

    //set outlet session
    public function setOutletSession() {

        $outlet_id = Input::get('outlet_id');
        Session::set('outlet_session',$outlet_id);

    }

    #TODO: populate itemsettings for outlet
    public function populateItemSettings() {
        return;
        ini_set('max_execution_time', 0);
        $owners = Owner::all();

        if ( isset($owners) && sizeof($owners) > 0 ) {
            foreach ( $owners as $own ) {
                $outlet = Outlet::where('owner_id',$own->id)->get();

                if ( isset($outlet) && sizeof($outlet) > 0 ) {
                    foreach( $outlet as $ot ) {

                        $menu = Menu::where('created_by',$own->id)->get();
                        if( isset($menu) && sizeof($menu) > 0 ) {
                            foreach( $menu as $itm ) {

                                $check_itm = ItemSettings::where('outlet_id',$ot->id)->where('item_id',$itm->id)->first();
                                if ( isset($check_itm) && sizeof($check_itm) > 0) {

                                } else {
                                    $add_itm = new ItemSettings();
                                    $add_itm->outlet_id = $ot->id;
                                    $add_itm->item_id = $itm->id;
                                    $add_itm->is_sale = $itm->is_sell;
                                    $add_itm->is_active = $itm->active;
                                    $add_itm->created_by = $own->id;
                                    $add_itm->updated_by = $own->id;
                                    $add_itm->save();
                                    echo $ot->name." = ".$itm->item."<br>";
                                }

                            }
                        }
                    }
                }
            }

        }

    }

    public function makeItemisSale() {
return;
        $outlet_id = 14;
        //$outlet_id = 16;
        $menu_bind = Outlet_Menu_Bind::where('outlet_id',$outlet_id)->get();
        if ( isset($menu_bind) && sizeof($menu_bind) > 0 ) {
            foreach( $menu_bind as $men ) {
                $item_id = $men->item_id;
                echo 'item : '.$item_id."<br>";
                $check = ItemSettings::where('outlet_id',$outlet_id)->where('item_id',$item_id)->first();
                if ( isset($check) && sizeof($check) > 0 ) {
                    $check->is_active = 0;
                    $check->is_sale = 1;
                    $check->save();
                } else {
                    $add = new ItemSettings();
                    $add->outlet_id = $outlet_id;
                    $add->item_id = $item_id;
                    $add->created_by = 16;
                    $add->updated_by = 16;
                    $add->is_active = 0;
                    $add->is_sale = 1;
                    $add->save();
                }
            }
        }

    }

    public function makeItementryInSettingTable() {
return;
        $user_id = 14;
        $outlet_id = 14;
        //$outlet_id = 16;
        //$menu_bind = Outlet_Menu_Bind::where('outlet_id',$outlet_id)->get();
        $menu = Menu::where('created_by',$user_id)->get();
        if ( isset($menu) && sizeof($menu) > 0 ) {
            foreach( $menu as $men ) {
                $item_id = $men->id;
                echo 'item : '.$item_id."<br>";
                $check = ItemSettings::where('item_id',$item_id)->where('outlet_id',$outlet_id)->first();
                if ( isset($check) && sizeof($check) > 0 ) {
                    $check->is_active = 0;
                    $check->is_sale = 1;
                    $check->save();
                } else {
                    $add = new ItemSettings();
                    $add->outlet_id = $outlet_id;
                    $add->item_id = $item_id;
                    $add->created_by = $user_id;
                    $add->updated_by = $user_id;
                    $add->is_active = 1;
                    $add->is_sale = 1;
                    $add->save();

                    $add_bind = new Outlet_Menu_Bind();
                    $add_bind->outlet_id = $outlet_id;
                    $add_bind->menu_id = $men->menu_title_id;
                    $add_bind->item_id = $men->id;
                    $add_bind->save();
                }
            }
        }

    }

    public function populateResposeDeviation() {
    return;
        $response_items = ItemRequest::where('satisfied','Yes')->get();

        $i = 0 ;
        if( isset($response_items) && sizeof($response_items) > 0 ) {

            foreach( $response_items as $itm ) {

                if ( $itm->statisfied_qty != $itm->qty ) {

                    $res_dev = new ResponseDeviation();
                    $res_dev->transaction_id = $itm->satisfied_batch_id;
                    $res_dev->item_id = $itm->what_item_id;
                    $res_dev->item_name	= $itm->what_item;
                    $res_dev->request_qty = $itm->qty;
                    $res_dev->request_unit_id = $itm->unit_id;
                    $res_dev->satisfied_qty = $itm->statisfied_qty;
                    $res_dev->satisfied_unit_id = $itm->satisfied_unit_id;
                    $res_dev->for_location_id = $itm->location_for;
                    $res_dev->from_location_id = $itm->location_from;
                    $res_dev->request_by = $itm->owner_by;
                    $res_dev->satisfied_by = $itm->satisfied_by;
                    $res_dev->request_when = $itm->when;
                    $res_dev->satisfied_when = $itm->satisfied_when;
                    $res_dev->save();
                    $i++;
                }

            }
        }
        echo $i.' record has been added';

    }

    #TODO: Populate account id in owner table
    public function populateAccountIdOwnerTable() {

        $expense = Expense::all();
        if( isset($expense) && sizeof($expense) > 0 ) {
            foreach( $expense as $exp) {
                if ( $exp->verify == 0) {
                    $exp->status = 1;
                } else {
                    $exp->status = 2;
                    $exp->verified_at = $exp->updated_at;
                }
                $exp->save();
            }
        }

        /*$i=0;
        $owners = Owner::where('created_by',NULL)->get();
        if (isset($owners) && sizeof($owners) > 0 ) {
            foreach( $owners as $ow ) {

                $check = Account::where('name',$ow->user_name)->first();
                if(!isset($check) && sizeof($check) == 0 ) {
                    $account = new Account();
                    $account->name = $ow->user_name;
                    $account->save();

                    $ow->account_id = $account->id;
                    $ow->save();
                    $i++;
                    $emp = Owner::where('created_by',$ow->id)->get();
                    if ( isset($emp) && sizeof($emp) > 0 ) {
                        foreach( $emp as $em ) {
                            $em->account_id = $account->id;
                            $em->save();
                            $i++;
                        }
                    }
                }

            }
        }
        echo $i." records has been updated";*/
    }

    #TODO: change payment mode id according to source_id
    public static function changePaymentModeId() {

        $orders = order_details::where('payment_option_id','!=',1)->get(); $i=0;
        foreach( $orders as $ord ) {

            if ( isset($ord->payment_option_id) && $ord->payment_option_id != 0 ) {

                $py_option = PaymentOption::find($ord->payment_option_id);
                $online = PaymentOption::where('name','Online')->first();

                if ( isset($py_option) && strtolower($py_option->name) == 'upi') {

                    $source = Sources::where('name','UPI')->first();
                    if ( isset($source) && sizeof($source) > 0 ) {
                        order_details::where('order_id',$ord->order_id)->update(['payment_option_id' => $online->id,'source_id'=>$source->id]);
                    }

                } else if ( isset($py_option) &&  strtolower($py_option->name) == 'paytm') {

                    $source = Sources::where('name','Paytm')->first();
                    if ( isset($source) && sizeof($source) > 0 ) {
                        order_details::where('order_id',$ord->order_id)->update(['payment_option_id' => $online->id,'source_id'=>$source->id]);

                    }

                } else if ( isset($py_option) && strtolower($py_option->name) == 'card') {

                    $source = Sources::where('name','Card')->first();
                    if ( isset($source) && sizeof($source) > 0 ) {
                        order_details::where('order_id',$ord->order_id)->update(['payment_option_id' => $online->id,'source_id'=>$source->id]);
                    }

                } else if ( isset($py_option) && strtolower($py_option->name) == 'zomato') {

                    $source = Sources::where('name','Zomato')->first();
                    if ( isset($source) && sizeof($source) > 0 ) {
                        order_details::where('order_id',$ord->order_id)->update(['payment_option_id' => $online->id,'source_id'=>$source->id]);
                    }
                }
            }
            $i++;
        }

        echo $i.' Orders has been updated';

    }

    #TODO: populate total bifurcation in daily summaries table
    public function populateTotalBifurcation() {

        $daily = DailySummary::all();

        if ( isset($daily) && sizeof($daily) > 0 ) {
            $i = 0;
            foreach( $daily as $dl ) {

                $data = array();
                $data['Cash']['direct'] = $dl->total_cash;
                $data['Online']['direct'] = $dl->total_online;

                $dl->total_bifurcation = json_encode($data);
                $dl->save();
                $i++;
            }
            echo $i." record has been updated";
        }

    }

    #TODO: Show account settings
    public function accountSettings(Request $request) {

        if ($request->ajax()) {

            $ac_id = Input::get('account_id');
            $acc_obj = Account::find($ac_id);

            return view('Outlets.settingsFields',array('account'=>$acc_obj));

        }

        $accounts = Account::lists('name','id');
        $accounts[''] = 'Select Account Name';

        return view('Outlets.settings',array('accounts'=>$accounts));

    }

    #TODO: Store account Settings
    public function storeAccountSettings() {

        $account_id = Input::get('account_id');
        $en_inv = Input::get('enable_inventory');
        $active = Input::get('active');
        $cancellation_report = Input::get('enable_cancellation_report');
        $allow_order_delete = Input::get('allow_order_delete');
        $enable_feedback = Input::get('enable_feedback');
        $can_invoice_reset = Input::get('can_invoice_reset');

        $acc_obj = Account::find($account_id);

        if ( isset($en_inv) && $en_inv != '') {
            $acc_obj->enable_inventory = 1;
        } else {
            $acc_obj->enable_inventory = 0;
        }

        if ( isset($active) && $active != '') {
            $acc_obj->active = 1;
        } else {
            $acc_obj->active = 0;
        }

        if ( isset($cancellation_report) && $cancellation_report != '') {
            $acc_obj->enable_cancellation_report = 1;
        } else {
            $acc_obj->enable_cancellation_report = 0;
        }

        if ( isset($allow_order_delete) && $cancellation_report != '') {
            $acc_obj->allow_order_delete = 1;
        } else {
            $acc_obj->allow_order_delete = 0;
        }

        if ( isset($enable_feedback) && $enable_feedback != '') {
            $acc_obj->enable_feedback = 1;
        } else {
            $acc_obj->enable_feedback = 0;
        }

        if ( isset($can_invoice_reset) && $can_invoice_reset != '') {
            $acc_obj->can_invoice_reset = 1;
        } else {
            $acc_obj->can_invoice_reset = 0;
        }

        $acc_obj->save();

        return 'success';

    }

    function setNewTax(){

        $outlet_list = Outlet::all();
        $count = 0;
        foreach ($outlet_list as $outlet){

            if(isset($outlet->taxes) && sizeof($outlet->taxes)>0){

                $new_taxes = array();
                $taxes = json_decode($outlet->taxes);
                $new_tax = array();

                foreach ($taxes as $tax){
                    foreach ($tax as $tax_name=>$perc){
                        $temp_tax['taxname'] = $tax_name;
                        $temp_tax['taxparc'] = $perc;
                    }
                    array_push($new_tax,$temp_tax);
                }

                $new_taxes['tax'] = $new_tax;
                $outlet->taxes = json_encode($new_taxes);
                $outlet->save();
                $count++;
            }
        }

        return 'success '.$count.' outlet taxes updated';

    }

    #TODO: set item alias outletwise
    public function setItemAlias($outlet_id ) {

        $omb_obj = Outlet_Menu_Bind::where('outlet_id',$outlet_id)->get();
        $i = 0;
        if ( isset($omb_obj) && !empty($omb_obj) > 0 ) {

            foreach ( $omb_obj  as $omb ) {

                $menu = Menu::where('id',$omb->item_id)->first();
                //echo $menu->id;exit;
                $alias = '';
                if ( isset($menu) && !empty($menu) > 0 ) {

                    $str = trim(preg_replace('/\s*\([^)]*\)/', '',$menu->item));

                    $itm_arr = explode(" ",$str);
                    if ( isset($itm_arr) && sizeof($itm_arr) > 0 ) {
                        foreach ( $itm_arr as $itm ) {
                            $ch = substr($itm,0,1);
                            if ( $ch == '-' || $ch == '(' || is_numeric($ch)) {
                                continue;
                            }
                            $alias .= $ch;
                        }
                    }
                    if ( isset($menu->alias) && $menu->alias != '') {
                        $menu->alias .=",".$alias;
                    } else {
                        $menu->alias = $alias;
                    }

                    $menu->save();
                    $i++;
                }
            }

        }
        echo $i." items has been updated";
    }

    public function generateSummaryReport(Request $request) {

        $name = Auth::user()->user_name;

        if ( $name != 'govind' ) {
            echo "You don't have an access";exit;
        }

        $outlets_name = '';

        if ($request->ajax()) {

            $owners = Owner::all();
            $data = array();

            $send_summary = Input::get('send_report');
            $ot_id = Input::get('outlet_id');

            $from_date = Input::get('from_date');

            //convert to session time
            $from = Utils::getSessionTime($from_date,'from');
            $to = Utils::getSessionTime($from_date,'to');

            if( sizeof($owners) > 0 ){

                foreach( $owners as $owner ){

                    if ( $ot_id != 'all' ) {
                        $outlets = Outlet::where('id',$ot_id)->where('owner_id', $owner->id)->get();
                    } else {
                        $outlets = Outlet::where('owner_id', $owner->id)->get();
                    }


                    if( isset($outlets) && sizeof($outlets) > 0 ){

                        foreach($outlets as $outlet) {

                            $data['outlet_name'] = $outlet->name;

                            $is_send = DailySummary::where('outlet_id',$outlet->id)->where('report_date',$from_date)->first();

                            if ( sizeof($is_send) == 0) {

                                $orders = order_details:: where('orders.table_end_date', '>=', $from)
                                    ->where('orders.table_end_date', '<=', $to)
                                    ->where('outlet_id', '=', $outlet->id)
                                    ->where('orders.invoice_no', "!=", '')
                                    ->where('cancelorder', '!=', 1);


                                if (sizeof($order_arr = $orders->get()) > 0) {

                                    $total_tax = 0.0;
                                    $daily_summarry = new DailySummary();
                                    $daily_summarry->report_date = $from;
                                    $daily_summarry->outlet_id = $outlet->id;

                                    $l_order = $orders->min('orders.totalprice');
                                    $h_order = $orders->max('orders.totalprice');
                                    $t_sell = $orders->sum('orders.totalcost_afterdiscount');
                                    $avg = $orders->avg('orders.totalprice');
                                    $t_orders = $orders->count();
                                    $g_total = $orders->sum('orders.totalprice');
                                    $t_person = $orders->sum('orders.person_no');

                                    $data['lowest_order'] = number_format($l_order, 0);
                                    $data['highest_order'] = number_format($h_order, 0);
                                    $data['total_sell'] = number_format($t_sell, 0);
                                    $data['gross_total'] = number_format($g_total, 0);
                                    $data['total_person'] = $t_person;
                                    //	$data['total_orders'] = $orders->count();
                                    $data['total_orders'] = $t_orders;//$orderscount;
                                    $data['average'] = number_format($avg, 2);
                                    $data['total_discount'] = 0;
                                    $data['total_nc'] = 0;

                                    $daily_summarry->total_orders = $t_orders;
                                    $daily_summarry->total_sells = $t_sell;


                                    $discount = 0;
                                    $nc = 0;
                                    $t_cash = 0;
                                    $t_prepaid = 0;
                                    $t_cheque = 0;
                                    $t_unpaid = 0;
                                    $t_person_visit = 0;
                                    $payment_opt = array();
                                    foreach ($order_arr as $or) {

                                        //get discount amount and non chargeable amount
                                        $disc_amt = floatval($or->discount_value);
                                        $st_amt = floatval($or->totalcost_afterdiscount);
                                        if ($disc_amt == '') {
                                            $disc_amt = 0;
                                        }
                                        if ($disc_amt == $st_amt) {
                                            $nc += $disc_amt;
                                        } else {
                                            $discount += $disc_amt;
                                        }

                                        //get total cash and prepaid amount
                                        $check_payment_type = PaymentOption::find($or->payment_option_id);
                                        $source = Sources::find($or->source_id);
                                        $upi_status = false;
                                        if ( isset($check_payment_type) && sizeof($check_payment_type) != '' ) {

                                            if ( strtolower($check_payment_type->name) == 'cash' ) {
                                                $t_cash += $or->totalprice;
                                            } else if ( strtolower($check_payment_type->name) == 'online' ) {

                                                if ( isset($source) && sizeof($source) > 0 ) {

                                                    if ( strtolower($source->name) == 'upi' ) {

                                                        //check payment status
                                                        $check_payment_status = IciciUpiTxn::where('status','=',1)->where('bill_no',$or->order_unique_id)->first();

                                                        if( isset($check_payment_status) && sizeof($check_payment_status) > 0 ) {
                                                            $upi_status = true;
                                                            $t_prepaid += $or->totalprice;
                                                        } else {
                                                            $t_unpaid += $or->totalprice;
                                                        }

                                                    } else {
                                                        $t_prepaid += $or->totalprice;
                                                    }

                                                } else {
                                                    $t_prepaid += $or->totalprice;
                                                }

                                            } else if ( strtolower($check_payment_type->name) == 'cheque' ) {
                                                $t_cheque += $or->totalprice;
                                            }

                                            if ( isset($source) && sizeof($source) > 0 ) {

                                                if ( strtolower($source->name) == 'upi' ) {

                                                    //check upi payment status

                                                    if( $upi_status == true ) {

                                                        if ( isset($payment_opt[$check_payment_type->name][$source->name])) {
                                                            $payment_opt[$check_payment_type->name][$source->name] +=  $or->totalprice;
                                                        } else {
                                                            $payment_opt[$check_payment_type->name][$source->name] =  $or->totalprice;
                                                        }

                                                    } else {

                                                        //if payment status is not success than make it unpaid
                                                        if ( isset($payment_opt['UnPaid'])) {
                                                            $payment_opt['UnPaid'] +=  $or->totalprice;
                                                        } else {
                                                            $payment_opt['UnPaid'] =  $or->totalprice;
                                                        }

                                                    }

                                                } else {

                                                    if ( isset($payment_opt[$check_payment_type->name][$source->name])) {
                                                        $payment_opt[$check_payment_type->name][$source->name] +=  $or->totalprice;
                                                    } else {
                                                        $payment_opt[$check_payment_type->name][$source->name] =  $or->totalprice;
                                                    }

                                                }


                                            } else {

                                                if ( isset($payment_opt[$check_payment_type->name]['direct'])) {
                                                    $payment_opt[$check_payment_type->name]['direct'] +=  $or->totalprice;
                                                } else {
                                                    $payment_opt[$check_payment_type->name]['direct'] =  $or->totalprice;
                                                }

                                            }

                                        } else {

                                            $t_unpaid += $or->totalprice;

                                            if ( isset($payment_opt['UnPaid'])) {
                                                $payment_opt['UnPaid'] +=  $or->totalprice;
                                            } else {
                                                $payment_opt['UnPaid'] =  $or->totalprice;
                                            }
                                        }

                                        $tax_total = 0;
                                        $json_tax = json_decode($or->tax_type);
                                        if (sizeof($json_tax) > 0 && isset($json_tax)) {
                                            foreach ($json_tax as $tx) {
                                                if (gettype($tx) == 'string')
                                                    $tx1 = json_decode($tx);
                                                else
                                                    $tx1 = $tx;
                                                foreach ($tx1 as $key1 => $t) {
                                                    $tax_total += $t->calc_tax;
                                                }
                                            }
                                        }

                                        $total_tax += $tax_total;
                                        $t_person_visit += $or->person_no;

                                    }

                                    $daily_summarry->total_bifurcation = json_encode($payment_opt);

                                    $data['pay_options'] = $payment_opt;
                                    $data['total_discount'] = number_format($discount, 0);
                                    $data['total_nc'] = number_format($nc, 0);
                                    $data['total_cash'] = number_format($t_cash, 0);
                                    $data['total_prepaid'] = number_format($t_prepaid, 0);
                                    $data['total_cheque'] = number_format($t_cheque, 0);
                                    $data['total_unpaid'] = number_format($t_unpaid, 0);
                                    $net_sale = $g_total - ($discount + $nc);
                                    $data['net_sale'] = number_format($net_sale,0);

                                    $avg_per_person = 0;
                                    if ( isset($t_person_visit) && $t_person_visit > 0 ) {
                                        $avg_per_person = floatval($g_total/$t_person_visit);
                                    }
                                    $data['avg_per_person'] =  number_format($avg_per_person,2);

                                    $daily_summarry->total_discount = $discount;
                                    $daily_summarry->total_nc_order = $nc;
                                    $daily_summarry->total_taxes = $total_tax;
                                    $daily_summarry->total_online = $t_prepaid;
                                    $daily_summarry->total_cash = $t_cash;
                                    $daily_summarry->total_cheque = $t_cheque;
                                    $daily_summarry->total_unpaid = $t_unpaid;
                                    $daily_summarry->gross_total = $g_total;
                                    $daily_summarry->gross_average = $avg;
                                    $daily_summarry->lowest_order = $l_order;
                                    $daily_summarry->highest_order = $h_order;
                                    $daily_summarry->total_person_visit = $t_person_visit;

                                    //	Log::info("Total Orders ==> ".sizeof($orders));
                                    $items = OrderItem::join("menus", "menus.id", "=", "order_items.item_id")
                                        ->join("orders", "orders.order_id", "=", "order_items.order_id")
                                        ->select('order_items.id', "menus.item", DB::raw('ifnull(sum(order_items.item_quantity),0) as count'))
                                        //->whereBetween('order_items.created_at', array($from_date, $to_date))
                                        ->where('orders.table_end_date', '>=', $from)
                                        ->where('orders.table_end_date', '<=', $to)
                                        ->where('orders.outlet_id', '=', $outlet->id)
                                        ->where('orders.cancelorder', '!=', 1)
                                        ->where('orders.invoice_no', "!=", '')
                                        ->groupBy('item_id')
                                        ->get();

                                    $unique_items = DB::table("order_items")
                                        ->join("orders", "orders.order_id", "=", "order_items.order_id")
                                        ->select('order_items.id', "order_items.item_name as item", DB::raw('ifnull(sum(order_items.item_quantity),0) as count'))
                                        ->where('orders.table_end_date', '>=', $from)
                                        ->where('orders.table_end_date', '<=', $to)
                                        ->where('orders.outlet_id', '=', $outlet->id)
                                        ->where('orders.cancelorder', '!=', 1)
                                        ->where('orders.invoice_no', "!=", '')
                                        ->groupBy('order_items.item_name')->get();

                                    $active_items = Outlet_Menu_Bind::where('outlet_menu_bind.outlet_id', $outlet->id)
                                        ->join("menus", "menus.id", "=", "outlet_menu_bind.menu_id")
                                        ->where("menus.active", 0)->get();

                                    $data['top_selling_item'] = "None";
                                    $count = 0;
                                    $total_item_sell = 0;
                                    foreach ( $items as $item) {
                                        if ( $item->count > $count) {
                                            $count = $item->count;
                                            $data['top_selling_item'] = ucfirst($item->item);

                                            $daily_summarry->top_selling_item = ucfirst($item->item);
                                            $daily_summarry->top_selling_item_id = $item->id;
                                        }
                                        $total_item_sell += $item->count;
                                    }
                                    $daily_summarry->total_unique_item_sell = sizeof($unique_items);
                                    $daily_summarry->total_item_sell = $total_item_sell;
                                    $daily_summarry->active_item = sizeof($active_items);
                                    if ( $t_person == 0 || $t_person == '') {
                                        $daily_summarry->tot_sale_per_person = 0;
                                    } else {
                                        $daily_summarry->tot_sale_per_person = $t_sell / $t_person;
                                    }


                                    $cancel_order = order_details::leftJoin('order_cancellation_mapper as ocm', 'ocm.order_id', '=', 'orders.order_id')
                                        ->leftJoin('owners as o', 'o.id', '=', 'ocm.created_by')
                                        ->select('orders.*', 'ocm.reason as reason', 'o.user_name as user_name')
                                        ->where('orders.table_end_date', '>=', $from)
                                        ->where('orders.table_end_date', '<=', $to)
                                        ->where('orders.outlet_id', '=', $outlet->id)
                                        ->where('orders.cancelorder', '=', 1);
                                    //print_r($cancel);exit;
                                    $c_order_amt = $cancel_order->sum('orders.totalprice');
                                    $c_order_cnt = $cancel_order->count();

                                    $data['cancel_order'] = $c_order_cnt;
                                    $data['cancel_amount'] = number_format($c_order_amt, 0);
                                    $data['cancel_order_arr'] = $cancel_order->get();

                                    $daily_summarry->cancel_order_count = $c_order_cnt;
                                    $daily_summarry->cancel_order_amount = number_format($c_order_amt, 0);
                                    $daily_summarry->save();

                                    if ( isset($send_summary) && $send_summary == 1 ) {

                                        $data['subject'] = $outlet->name . ' ' . $from;
                                        $emails = array();
                                        if (isset($outlet->report_emails) && $outlet->report_emails != '') {
                                            $emails = explode(',', $outlet->report_emails);
                                        }

                                        if (env("APP_ENV") == "production") {
                                            $emails1 = array("dev@savitriya.com");
                                            $allemail = array_merge($emails, $emails1);
                                        } else {
                                            $allemail = $emails;
                                        }

                                        $total_hours = Outlet::getTotalHours($outlet->id,$from_date);

                                        $mobiles = explode(',', $outlet->contact_no);
                                        if (env("APP_ENV") == "production") {
                                            if (isset($mobiles) && sizeof($mobiles) > 0) {
                                                foreach ($mobiles as $mob) {
                                                    $send_sms = users::sendStatusMessage($outlet->name, $mob, $outlet->status_sms, $total_hours, $data['total_orders'], $data['total_person'], $data['total_sell'], $data['gross_total'], $data['net_sale'], $data['cancel_order']);
                                                }
                                            }
                                        }

                                        //$status = SendCloseCounterStatus::where('id', $outlet->id)->update(array('sms_count' => sizeof($mobiles), 'is_send' => '1'));

                                        if (sizeof($allemail) > 0) {
                                            foreach ($allemail as $email) {

                                                Mail::send('emails.commondailysummaryreport', array('data' => $data), function ($message) use ($data, $email) {
                                                    $message->from('we@pikal.io', 'Pikal');
                                                    $message->to($email);
                                                    $message->subject($data['subject']);
                                                });

                                            }
                                        }

                                    }
                                    $outlets_name .= " Report generate for ".$outlet->name.'<br>';

                                } else {
                                    //Log::info("No Orders Found.");
                                }

                            }
                        }
                    }
                }
            }

            return $outlets_name;
        }

        $outlet_all = Outlet::all();

        return view('report.generateSummaryReport',array('outlets'=>$outlet_all));

    }

    public function getLastInvoiceNo( $order_type1 = NULL,$res_id1 = NULL, $user_identifier1 = NULL ) {

        if ( $order_type1 != NULL && $res_id1 != NULL ) {
            $ord_type = $order_type1;
            $res_id = $res_id1;
            $user_identifier = $user_identifier1;
        } else {
            $ord_type = Input::json('order_type');
            $res_id = Input::json('res_id');
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
                    $inv = $inv_record->invoice;
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

            $invoice_number = $code.''.$in_date.''.$user_identifier.''.str_pad($invoice_no,$inv_digit,0,STR_PAD_LEFT);
        }

        if ( isset($order_type1) && $order_type1 != NULL && isset($res_id1) && $res_id1 != NULL ) {
            return ["invoice_number" => $invoice_number, "last_digit" => $invoice_no];
        } else {
            return Response::json(array(
                'invoice_no'=>$invoice_number,
                'status' => 'success',
                'statuscode' => 200
            ),200);
        }

    }


    public function autoProcessIndex(){

        $all_outlets = Outlet::where("active","Yes")->lists("name","id");
        $all_outlets[""] = "Select Outlet";

        return view('Outlets.autoProcessOutlet',array('active_outlets'=>$all_outlets));
    }

    public function getOutletSettings(){

        $outlet_id = Input::get("outlet_id");
        $found = DB::table("outlet_auto_process")->where("outlet_id",$outlet_id)->first();
        if(isset($found) && sizeof($found)){
            return view('Outlets.outletSettings',array('set'=>"true"));
        }else{
            return view('Outlets.outletSettings',array('set'=>"false"));
        }

    }

    public function storeOutletSettings(){

        $active = Input::get('active');
        $outlet_id =Input::get("outlet_id");
        if(isset($active) && sizeof($active)>0){

            DB::table('outlet_auto_process')->insert(
                ['outlet_id' => $outlet_id,
                    "created_at" =>  Carbon::now(),
                    "updated_at" => Carbon::now()]
            );
            return "success";
        }else{
            DB::table('outlet_auto_process')->where("outlet_id",$outlet_id)->delete();
            return "success";
        }
        return "error";

    }

    /**
     *
     */
    public function autoProcessOrders(){

        $outlet_id = Input::get("outlet_id");

        $orders = order_details::join("outlets","outlets.id","=","orders.outlet_id")
                                ->where("outlet_id", "=", $outlet_id)
                                ->where("invoice_no", "=", "")
                                ->where("cancelorder", "=", 0)
                                ->select("orders.*","outlets.default_taxes","outlets.taxes")
                                ->get();
        $count = 0;
        foreach ($orders as $order){
            $owner = Owner::where('user_name',$order->name)->first();
            $user_identifier = $owner->user_identifier;
            $invoice_arr = $this->getLastInvoiceNo($order->order_type, $outlet_id, $user_identifier);
            $order_obj = order_details::find($order->order_id);
            $taxes = $this->calculateTaxes($order, $order->order_type);

            if(isset($order_obj) && sizeof($order_obj)>0) {
                $order_obj->invoice_no = $invoice_arr['invoice_number'];
                $order_obj->invoice = $invoice_arr['last_digit'];
                $order_obj->payment_option_id = 1;
                $order_obj->discount_value = 0.0;
                if($order->order_type == "home_delivery"){
                    $delivery_charge = $this->calculateDeliveryCharge($order->outlet_id,$order->totalcost_afterdiscount);
                    $order_obj->delivery_charge = $delivery_charge;
                    $order_obj->totalprice = $order->totalcost_afterdiscount + $delivery_charge;
                }
                if(isset($taxes) && sizeof($taxes)>0){
                    $order_obj->tax_type = '['.$taxes["tax_type"].']';
                    $order_obj->totalprice = $taxes["totalprice"];
                    $order_obj->round_off = $taxes["round_off"];
                }
                $order_obj->save();
                $count++;
            }
        }
        print_r($count." Order updated.");
    }

    public function calculateDeliveryCharge($outlet_id,$price){

        $delivery_charge = 0;//number_format($order->delivery_charge, 2, '.', '');
        $outlet = Outlet::find(Session::get('outlet_session'));
        $delivery_charge_arr = json_decode($outlet->delivery_charge);

        if(isset($delivery_charge_arr) && $delivery_charge_arr != NULL){
            foreach ($delivery_charge_arr[0] as $total_price => $charge){

                if($total_price == "fixed"){                              //if fixed delivery charges
                    $delivery_charge = number_format($charge, 2, '.', '');
                    break;
                }else if($total_price > $price){ //if delivery charge between slots
                    $delivery_charge = number_format($charge, 2, '.', '');
                    break;
                }else{                                                    // last slab always apply if heighest order done
                    $delivery_charge = number_format($charge, 2, '.', '');
                }
            }

            return $delivery_charge;
        }

    }

    public function calculateTaxes($order, $order_type){

        //outlet taxes
        $taxes = json_decode($order->taxes);
        //outlet default taxes
        if( isset($order->default_taxes) && $order->default_taxes != '' ) {

            $default_taxes = json_decode($order->default_taxes,true);

            if ( isset($selected_tax) && $selected_tax != '' ) {
                $compare_tax = $selected_tax;
            } else {

                if ( $order_type == 'dine_in' ) {
                    $compare_tax = $default_taxes[0]['dine_in'];
                } else if ( $order_type == 'take_away' ) {
                    $compare_tax = $default_taxes[0]['take_away'];
                } else {
                    $compare_tax = $default_taxes[0]['home_delivery'];
                }
            }

        }
        $total = $order->totalcost_afterdiscount;
        $round_val = 0.0;
        $tax_type = array();
        $final_tax = array();
        if( isset($order->default_taxes) && $order->default_taxes != '' ) {
            if ($compare_tax != '') {

                if (isset($taxes) && sizeof($taxes) > 0) {
                    foreach ($taxes as $t_key => $t_val) {
                        if ($t_key == $compare_tax) {
                            $i = 0;

                            foreach ($t_val as $tx) {
                                $cal_tax = $order->totalcost_afterdiscount * floatval($tx->taxparc) / 100;
                                $show_cal_tax = number_format($order->totalcost_afterdiscount * floatval($tx->taxparc) / 100, 2, '.', '');
                                $total += $cal_tax;
                                if(isset($tax_type[ucwords($tx->taxname)]) && sizeof($tax_type[ucwords($tx->taxname)])>0) {
                                    array_push($tax_type[ucwords($tx->taxname)], ["calc_tax" => $cal_tax, "percent" => $tx->taxparc]);
                                    array_push($final_tax, $tax_type);
                                }else{
                                    $tax_type[ucwords($tx->taxname)] = ["calc_tax" => $cal_tax, "percent" => $tx->taxparc];
                                    array_push($final_tax, $tax_type);
                                }

                            }
                        }
                    }
                    $round_of_total = round($total);
                    $round_of_val = number_format(abs($total - $round_of_total), 2, '.', '');
                    $round_val = abs($round_of_val);

                }
            }
        }

        return ['round_off' => $round_val, "totalprice" => $total, "tax_type" => json_encode($tax_type)];
    }

    #TODO: Update page data using SSE
    public function updatePageData($flag) {

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header("Connection: keep-alive");

        $outlet_id = Session::get('outlet_session');

        $date = date('Y-m-d');
        $hour_check = date("H");

        if ( $hour_check < 4 ) {
            $date = date('Y-m-d',strtotime('-1 day'));
        }

        $from = Utils::getSessionTime($date,'from');
        $to = Utils::getSessionTime($date,'to');

        if($flag == "dashboard") {

            $orders = order_details::where('table_end_date', '>=', $from)
                ->where('table_end_date', '<=', $to)
                ->where('outlet_id', '=', $outlet_id)
                ->where('invoice_no', "!=", '')
                ->where('cancelorder', '!=', 1)
                ->sum('totalprice');

            echo "retry: 5000\n";
            echo "data: total:$orders\n\n";

        }else if($flag == 'kot'){

            $kots = Kot::join('owners','owners.id','=','kot.created_by')
                ->select('kot.*','owners.user_name as username')
                ->where('outlet_id',$outlet_id)
                ->where('kot.updated_at','>=', $from)
                ->where('kot.updated_at','<=', $to)
                ->where('kot.status','open')
                ->get();

            $tot_kots = sizeof($kots);

            echo "retry: 5000\n";
            echo "data: kot:$tot_kots\n\n";

        }else if($flag == 'orders'){

            $orders = order_details::where('orders.table_end_date','>=', $from)
                ->where('orders.table_end_date','<=', $to)
                ->where('orders.outlet_id','=',$outlet_id)
                ->where('orders.cancelorder', '!=', 1)
                ->get();

            $tot_orders = sizeof($orders);

            echo "retry: 5000\n";
            echo "data: orders:$tot_orders\n\n";

        }

        ob_flush();
        flush();

    }

    //populate order payment
    function add_order_payment_mode(){

        ini_set('max_execution_time', 0);
        $all_orders = order_details::all();
        $added_order = 0;
        $skip_order = 0;
        foreach ($all_orders as $order){

            $find_order = order_payment_mode::where('order_id',$order->order_id)->get();
            if(sizeof($find_order)>0 ) {
                $skip_order++;
            }else{
                $order_payment_mode = new order_payment_mode();
                $order_payment_mode->order_id = $order->order_id;
                $order_payment_mode->payment_option_id = $order->payment_option_id;
                $order_payment_mode->source_id = $order->source_id;
                $order_payment_mode->amount = $order->totalprice;
                $order_payment_mode->transaction_id = $order->referance_id;
                $order_payment_mode->save();
                $added_order++;
            }

        }
        print_r($added_order. " Orders are added & ".$skip_order." Orders are skiped because of same order id.");

    }

    function add_menu_item_option_to_option_groups(){

        $menu_options = MenuItemOption::join('menus as m','m.id','=','menuitem_options.parent_item_id')->orderBy('parent_item_id')->get();

        if ( isset($menu_options) && sizeof($menu_options) >  0) {
            $item_id = 0;$group_id = 0;$cnt=0;
            foreach ( $menu_options as $opt ) {

                if ( $opt->parent_item_id != $item_id ) {
                    $item_id = $opt->parent_item_id;

                    $outlet_mappers = OutletMapper::where('owner_id',$opt->outlet_id)->get();

                    $group = new ItemOptionGroup();
                    $group->outlet_id = $outlet_mappers[0]->outlet_id;
                    $group->name = 'Customize item';
                    $group->max = 10;
                    $group->created_by = $opt->created_by;
                    $group->updated_by = $opt->created_by;

                    $result = $group->save();

                    if ( $result ) {

                        $group_id = $group->id;

                        $mapper = new ItemOptionGroupMapper();
                        $mapper->item_id = $item_id;
                        $mapper->item_option_group_id = $group_id;
                        $mapper->created_by = $opt->created_by;
                        $mapper->updated_by = $opt->created_by;
                        $mapper->save();
                    }
                    $cnt++;
                }

                $options = new ItemGroupOption();
                $options->item_option_group_id = $group_id;
                $options->option_item_id = $opt->option_item_id;
                $options->option_item_price = $opt->option_item_price;
                $options->default_option = 0;
                $options->created_by = $opt->created_by;
                $options->updated_by = $opt->created_by;
                $options->save();

            }

            echo "total ". $cnt;
        }

    }

    public function deleteOrdersBeforeDate($date) {

        $sess_outlet_id = Session::get('outlet_session');

        if(isset($sess_outlet_id) && trim($sess_outlet_id) != "") {

            $order_count = order_details::where('outlet_id',$sess_outlet_id)->where('table_end_date','<=',$date)->count();
            $order_arr = order_details::where('outlet_id',$sess_outlet_id)->where('table_end_date','<=',$date)->lists('order_id');
            $order_cancellation_mapper_count = OrderCancellation::where('outlet_id',$sess_outlet_id)->where('created_at','<=',$date)->count();
            $order_history_count = OrderHistory::whereIn('order_id',$order_arr)->count();
            $order_item_option_count = OrderItemOption::whereIn('order_id',$order_arr)->count();
            $order_invoice_count = invoice_detail::whereIn('order_id',$order_arr)->count();
            $order_items_arr = OrderItem::whereIn('order_id',$order_arr)->lists('id');
            $order_items_count = OrderItem::whereIn('order_id',$order_arr)->count();
            $order_items_attr_count = order_item_attributes::whereIn('order_item_id',$order_items_arr)->count();
            $order_payment_mode_count = order_payment_mode::whereIn('order_id',$order_arr)->count();

            invoice_detail::whereIn('order_id',$order_arr)->delete();
            print_r($order_invoice_count." ---> Order Invoice deleted successfully<br/>");
            OrderCancellation::where('outlet_id',$sess_outlet_id)->where('created_at','<=',$date)->delete();
            print_r($order_cancellation_mapper_count." ---> Cancelled order deleted successfully<br/>");
            OrderHistory::whereIn('order_id',$order_arr)->delete();
            print_r($order_history_count." ---> Order history deleted successfully<br/>");
            order_item_attributes::whereIn('order_item_id',$order_items_arr)->delete();
            print_r($order_items_attr_count." ---> Order item attributes deleted successfully<br/>");
            OrderItemOption::whereIn('order_id',$order_arr)->delete();
            print_r($order_item_option_count." ---> Order item options deleted successfully<br/>");
            order_payment_mode::whereIn('order_id',$order_arr)->delete();
            print_r($order_payment_mode_count." ---> Order payment modes deleted successfully<br/>");
            OrderItem::whereIn('order_id',$order_arr)->delete();
            print_r($order_items_count." ---> Order items deleted successfully<br/>");
            order_details::where('outlet_id',$sess_outlet_id)->where('table_end_date','<=',$date)->delete();
            print_r($order_count." ---> Order deleted successfully<br/>");


        } else {

            print_r("Please select outlet.");

        }

    }

}
