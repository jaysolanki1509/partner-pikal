<?php namespace App\Http\Controllers;
use App\CancellationReason;
use App\City;
use App\Customer;
use App\Http\Controllers\Api\v3\Apicontroller;
use App\Http\Requests;
use App\HttpClientWrapper;
use App\InvoiceDetail;
use App\ItemAttribute;
use App\Kot;
use App\Location;
use App\Menu;
use App\MenuItemOption;
use App\order_details;
use App\order_item_attributes;
use App\OrderCancellation;
use App\OrderHistory;
use App\OrderItem;
use App\OrderItemOption;
use App\OrderPaymentMode;
use App\OutletItemAttributesMapper;
use App\OutletMapper;
use App\OutletSetting;
use App\Owner;
use App\Outlet;
use App\PaymentOption;
use App\Sources;
use App\status;
use App\Timeslot;
use App\User;
use App\Utils;
use Aws\DynamoDb\Model\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Pagination\Paginator;
use App\MenuTitle;
use App\CuisineType;
use App\ItemSettings;
use App\ItemOptionGroup;
use View;
use Illuminate\Support\Facades\DB;
class newordercontroller extends Controller {
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
		$user=Owner::find(Auth::user()->id);
        //$id=$user->Outlet->lists('id');
        $Outlet = OutletMapper::getOutletsByOwnerId();
        unset($Outlet['']);
        $totalOutletunderuser=count($Outlet) - 1;
        $today = new Carbon(date('Y-m-d'));
        //print_r($today);exit;
        /*if(sizeof($id)>0){
            //$outlet_id=$res_id;
            $retname=Outlet::find($id[0]);
            $outlet_id = $id[0];
            $restaurant_id=$id[0];
            $retname=Outlet::find($restaurant_id);
            $star=[];
            $orders = $retname->orderdetail()->where('status','!=','delivered')->where('cancelorder', '!=', 1)->whereDate('created_at','=', date('Y-m-d'))->orderBy('created_at', 'desc')->get();
            //print_r($orders);exit;
            $totalprice=[];
            if(count($orders) > 0) {
            foreach($orders as $key=>$order){
                $item=OrderItem::where('order_id',$order->order_id)->get();
                $totalpr=0;
                foreach($item as $t){
                   $itemnew=$t->menuitem;
                    if(isset($itemnew['price']) && $itemnew['price']!='') {
                        $totalpr+=$t->item_quantity*$itemnew['price'];
                    }
                    else {
                        $totalpr+=0;
                    }
                }
                $star[$order->order_id]=DB::table('orders')->where('user_mobile_number',$order->phone_number)->count();
                $totalprice[$order->order_id]=$totalpr;
                if($key==0){$maxdt=$order->created_at;}
            }
            }
          // print "<pre>";print_r($orders);exit;
            return view("neworder",array("orders"=>$orders))->with('star',$star)->with('rescode',$retname->Outlet_code)->with('totalprice',$totalprice)->with('maxdt',$maxdt)->with('Outlet',$Outlet)->with('totalOutletcount',$totalOutletunderuser)->with('out_id',$outlet_id);
        }*/
        return view("neworder",array("orders"=>array()))->with('star','')->with('rescode','')->with('totalprice','')->with('maxdt','')->with('Outlet',$Outlet)->with('totalOutletcount',$totalOutletunderuser);
	}
    public function getorders(){
        $date= Input::get('dt');
        $res_id = Input::get('rest_id');
        $flag = Input::get('flag');
        $user=Owner::find(Auth::user()->id);
        $star=[];
        $a = '';
        $b = '';
        $orders = array();
        if ( $res_id != '' && $res_id != 'undefined' && $res_id != null ) {
            if ( $res_id == 'all') {
                $maxdt = $date;
                $orders = order_details::join('outlets','outlets.id','=','orders.outlet_id')
                    ->where('status', '!=', 'delivered')
                    ->where('cancelorder', '!=', 1)
                    ->where('outlets.active','Yes')
                    ->whereDate('orders.created_at', '=',  date('Y-m-d'))
                    ->orderBy('orders.created_at', 'desc')
                    ->get();
            } else {
                $retname = Outlet::findOutlet($res_id);
                $maxdt = $date;
                if ($flag == 'selectbox') {
                    $orders = $retname->orderdetail()->where('status', '!=', 'delivered')->where('cancelorder', '!=', 1)->whereDate('created_at', '=', date('Y-m-d'))->orderBy('created_at', 'desc')->get();
                } else {
                    $orders = $retname->orderdetail()->where('status', '!=', 'delivered')->where('cancelorder', '!=', 1)->where('cancelorder', '!=', 1)->where('created_at', '>', new Carbon($date))->orderBy('created_at', 'desc')->get();
                }
            }
            if (count($orders) > 0) {
                $totalprice = [];
                foreach ($orders as $key => $order) {
                    $item = OrderItem::where('order_id', $order->order_id)->get();
                    $totalpr = 0;
                    foreach ($item as $t) {
                        $itemnew = $t->menuitem;
                        if (isset($itemnew['price']) && $itemnew['price'] != '') {
                            $totalpr += $t->item_quantity * $itemnew['price'];
                        } else {
                            $totalpr += 0;
                        }
                    }
                    $totalprice[$order->order_id] = $totalpr;
                    $star[$order->order_id] = DB::table('orders')->where('user_mobile_number', $order->phone_number)->count();
                    if ($key == 0) {
                        $maxdt = $order->created_at;
                    }
                }
                $a = view("orderupdate", array("orders" => $orders))->with('totalprice', $totalprice);
                $b = view("orderupdatenew", array("orders" => $orders))->with('star', $star)->with('totalprice', $totalprice);
                foreach ($orders as $order) {
                    //DB::table('orders')->where('order_id', $order->order_id)->update(array("read" => 1));
                }
                //print_r($orders);exit;
                return (string)$a . "*DIFF" . $b . "*DIFF" . $maxdt . "*DIFF" . count($orders);
            }
            //print_r(count($orders));exit;
            if (isset($flag) && $flag == 'selectbox') {
                return (string)$a . "*DIFF" . $b . "*DIFF" . $maxdt . "*DIFF&Nodata";
            } else {
                return (string)$a . "*DIFF" . $b . "*DIFF" . $maxdt . "*DIFF" . count($orders);
            }
        }else{
                return (string)$a . "*DIFF" . $b . "*DIFF" . $date . "*DIFF&Nodata";
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
    public function printOrder() {
        $order_no = Input::get('order_id');
        $order = order_details::leftJoin('outlets as ot','orders.outlet_id','=','ot.id')->select('orders.*','ot.name as ot_name','ot.city_id as city_id','ot.invoice_title as invoice_title','ot.order_lable as order_lable','ot.taxes as ot_taxes','ot.tax_details as tax_details','ot.company_name as company_name','ot.address as ot_address','ot.servicetax_no as service_tax_no','ot.vat as vat_no','ot.url as url','ot.duplicate_watermark as duplicate_watermark')->where('orders.order_id',$order_no)->first();
        $outlet_id = Session::get('outlet_session');
        $outlet_obj = Outlet::find($outlet_id);
        $order_info = array();
        $order_taxes = json_decode($order->tax_type);
        if ( !isset( $order_taxes) && empty($order_taxes) ) {
            $order_taxes = NULL;
        }
        $order_info['customer'] = '';
        if( isset($order->user_id) && $order->user_id != '' ) {
            $order_info['customer'] = Customer::where('id',$order->user_id)->pluck('first_name');;
        }
        //tax details
        $tax_details = NULL;
        if ( isset($order->tax_details) && empty($tax_details) ) {
            $tax_details = json_decode($order->tax_details);
        }
        $order_info['ot_id'] = $outlet_id;
        $order_info['ot_name'] = $order->ot_name;
        $order_info['invoice_title'] = $order->invoice_title;
        $order_info['ot_address'] = $order->ot_address;
        $order_info['company_name'] = $order->company_name;
        $order_info['or_type'] = $order->order_type;
        $order_info['or_number'] = $order->invoice_no;
        $order_info['user'] = Auth::user()->user_name;
        $order_info['table'] = $order->table_no;
        $order_info['person'] = $order->person_no;
        $order_info['sub_total'] = $order->totalcost_afterdiscount;
        $order_info['total'] = $order->totalprice;
        $order_info['discount'] = $order->discount_value;
        $order_info['round_off'] = abs($order->round_off);
        $order_info['date'] = Carbon::parse($order->table_start_date)->format('d-m-Y');
        $order_info['url'] = $order->url;
        $order_info['taxes'] = $order_taxes;
        $order_info['custom_fields'] = json_decode($order->custom_fields,true);
        $order_info['service_tax_no'] = $order->service_tax_no;
        $order_info['vat_no'] = $order->vat_no;
        $order_info['updated'] = $order->updated;
        $order_info['order_lable'] = $order->order_lable;
        $order_info['delivery_charge'] = $order->delivery_charge;
        $order_info['tax_details'] = $tax_details;
        $order_info['city_name'] = isset($order->city_id)?City::find($order->city_id):"";
        $order_info['discount_after_tax'] = $order->add_discount_after_tax;
        $order_info['discount_type'] = $order->discount_type;
        $order_info['duplicate_watermark'] = $order->duplicate_watermark;
        $order_info['itemwise_tax'] = $order->itemwise_tax;
        $order_info['itemwise_discount'] = $order->itemwise_discount;
        $order_info['item_discount'] = $order->item_discount_value;
        if ( $order->is_custom == 1 ) {
            $orderplace = \App\OrderPlaceType::find($order->order_place_id);
            if ( isset($orderplace) ) {
                $order_info['order_lable'] = $orderplace->name;
            }
        }
        $order_info['is_custom'] = $order->is_custom;
        if( isset($order) ) {
            $order_items = OrderItem::select('order_items.id','order_items.item_price as itm_price','order_items.item_quantity as itm_quantity','order_items.item_name as itm_name','order_items.tax_slab as tax_slab','order_items.item_discount')->where('order_items.order_id',$order->order_id)->get();
            if ( isset($order_items) && !empty($order_items) ) {
                $i = 0; $item_price = 0;
                foreach( $order_items as $item ) {
                    $order_info['item'][$i]['name'] = $item->itm_name;
                    $order_info['item'][$i]['qty'] = $item->itm_quantity;
                    $item_price = $item->itm_price;
                    $itm_options = OrderItemOption::join("menus as m","m.id","=","order_item_options.option_item_id")->select("m.item as item","order_item_options.*")->where('order_item_id',$item->id)->get();
                    if ( isset($itm_options) && !$itm_options->isEmpty() ) {
                        $j=0;
                        foreach ( $itm_options as $opt ) {
                            $order_info['item'][$i]['options'][$j]['name'] = $opt->item;
                            /*$order_info['item'][$i]['options'][$j]['qty'] = $opt->qty;
                            $order_info['item'][$i]['options'][$j]['price'] = $opt->option_item_price;
                            $order_info['item'][$i]['options'][$j]['amount'] = $opt->option_item_price * $opt->qty;*/
                            $item_price += $opt->option_item_price;
                            $j++;
                        }
                    }
                    $order_info['item'][$i]['price'] = $item_price;
                    $order_info['item'][$i]['amount'] = $item_price * $item->itm_quantity;
                    //check itemwise tax
                    $taxes = json_decode($item->tax_slab);
                    if ( isset($taxes) ) {
                        foreach ( $taxes as $t_key=>$t_val ) {
                            $k = 0;
                            foreach ( $t_val as $tx ) {
                                $order_info['item'][$i]['slab'][$k]['tax_name'] = $tx->taxname;
                                $order_info['item'][$i]['slab'][$k]['tax_parc'] = $tx->taxparc;
                                $order_info['item'][$i]['slab'][$k]['tax_val'] = $tx->value;
                                $k++;
                            }
                        }
                    }
                    //check itemwise discount
                    $item_disc = json_decode($item->item_discount);
                    if ( isset($item_disc) ) {
                        $order_info['item'][$i]['discount']['type'] = $item_disc->disc_type;
                        $order_info['item'][$i]['discount']['value'] = $item_disc->disc_value;
                        $order_info['item'][$i]['discount']['amount'] = $item_disc->disc_calc_amount;
                    }
                    $i++;
                }
            }
            $py_modes = OrderPaymentMode::where('order_id',$order->order_id)->get();
            $py_mode_string = "";
            if ( isset($py_modes) && !empty($py_modes) ) {
                $cnt = 0;
                foreach ($py_modes as $py_mode) {
                    $mode_name = '';
                    $mode = PaymentOption::find($py_mode->payment_option_id);
                    if ($py_mode->payment_option_id != 0) {
                        if (isset($mode) && !empty($mode)) {
                            $mode_name .= $mode->name;
                        }
                        if ($py_mode->source_id != 0) {
                            $src = Sources::find($py_mode->source_id);
                            if (isset($src) && !empty($src) ) {
                                $mode_name .= " - " . $src->name;
                            }
                        }
                        if ($py_mode_string != "") {
                            $py_mode_string .= ", " . $mode_name;
                        } else {
                            $py_mode_string .= $mode_name;
                        }
                        if(trim($py_mode->transaction_id) != "" && $outlet_obj->payment_info == 1){
                            $py_mode_string .= "[".$py_mode->transaction_id."]";
                        }
                    } else {
                        if ($py_mode_string != "") {
                            $py_mode_string .= ", Unpaid";
                        } else {
                            $py_mode_string .= "Unpaid";
                        }
                    }
                }
            }
            $order_info['payment_mode'] = $py_mode_string;
        }
        //print_r($order_info);exit;
        if(strtolower($order->ot_name) == 'zucchini')
            return view("billtemplate.printorder_noheader",array("order"=>$order_info));
        else if(strtolower($order->ot_name) == 'udupihome')
            return view("printudporder",array("order"=>$order_info));
        else
            return view("printorder",array("order"=>$order_info));
    }
    public static function cancelOrder( $order_id = Null,$outlet_id= Null,$reason = Null, $user_id = Null) {
        if ( !isset($order_id) && $order_id == '' ) {
            $user_id = Auth::id();
            $order_id = Input::get('order_id');
            $reason = Input::get('reason');
            $outlet_id = Input::get('outlet_id');
            $sess_outlet_id = Session::get('outlet_session');
            if (isset($sess_outlet_id) && $sess_outlet_id != '') {
                $outlet_id = $sess_outlet_id;
            }
            $flag = 'web';
        } else {
            $flag = 'app';
        }
        $order_cancel_mapper = new OrderCancellation();
        $order_cancel_mapper->outlet_id = $outlet_id;
        $order_cancel_mapper->order_id = $order_id;
        $order_cancel_mapper->reason = $reason;
        $order_cancel_mapper->created_by = $user_id;
        $result = $order_cancel_mapper->save();
        $status = '';
        if ( $result ) {
            $order = order_details::where('order_id',$order_id)->update(['cancelorder'=>1]);
            //decrement stock when auto decrement selected
            $outlet_setting = Outlet::find($outlet_id);
            if ( $outlet_setting->stock_auto_decrement == '1') {
                $default_location = Location::where('outlet_id',$outlet_id)->first();
                if ( isset($default_location) && !empty($default_location) ) {
                    $change_stock = StocksController::onCancelChangeStock( $order_id, $default_location->id );
                }
            }
            $data['status'] = 'success';
            $status = 'success';
        } else {
            $data['status'] = 'error';
            $status = 'error';
        }
        if ( $flag == 'web' ) {
            return json_encode($data);
        } else {
            return $status;
        }
    }
    public function deleteOrder() {
        $order_id = Input::get("order_id");
        DB::beginTransaction();
            //Order Item delete
            //OrderItem::where("order_id",$order_id)->forceDelete();
            $order_items = OrderItem::where("order_id",$order_id)->get();
            if ( isset($order_items) && !empty($order_items) ) {
                foreach ( $order_items as  $or_itm ) {
                    //delete order item attributes
                    order_item_attributes::where('order_item_id',$or_itm->id )->forceDelete();
                    //delete order item options
                    OrderItemOption::where('order_item_id',$or_itm->id )->forceDelete();
                }
            }
            $order = order_details::find($order_id);
            if(isset($order) && !empty($order)) {
                //Order Kot Delete
                $kots = Kot::where("order_unique_id", $order->order_unique_id)->forceDelete();
                //Order Delete
                $order->forceDelete();
            }
            else {
                DB::rollBack();
                $data['status'] = 'error';
            }
        DB::commit();
        $data['status'] = 'success';
        return json_encode($data);
    }
    public function processBill() {
        $o_id = Input::get('order_id');
        //get order detail
        $order = order_details::where('order_id',$o_id)->join('outlets as ot','orders.outlet_id','=','ot.id')->select('orders.*','ot.invoice_prefix as invoice_prefix','ot.invoice_date as inv_date','ot.invoice_digit as inv_digit','ot.name as ot_name','ot.taxes as taxes','ot.default_taxes as default_taxes','ot.code as ot_code','ot.order_no_reset as order_reset','ot.payment_options as payment_options')->first();

        $outlet_id = Session::get('outlet_session');
        //get invoice detail
        //$invoice = order_details::where('order_id',$o_id)->first();
        $consumer = array();
        
        if ( isset($order) && !empty($order) ) {
            $invoice_number = $this->generateInvoiceNumber($outlet_id, $o_id, $order->order_type);
            if(isset($order->user_id) && isset($order->user_id)) {
                $check_customer = Customer::find($order->user_id);
                if (isset($check_customer) && isset($check_customer->id) ) {
                    $consumer['mobile_number'] = $check_customer->mobile_number;
                    $consumer['first_name'] = $check_customer->first_name;
                }
            }
            $new_delivery = "";
            $html = "<div id='tax_calculation_div'>";
                if ( $order->itemwise_tax ) {
                    $html .= $this::itemWiseOrderCalculation($order,'process',$order->order_type,$new_delivery);
                } else {
                    $html .= $this::orderCalculation($order,$order->order_type,'process','',$new_delivery);
                }
            $html .= "</div>";
            //$source = Sources::getSourceArray($order->outlet_id);
            //payment options
            //$pay_option = PaymentOption::getOutletPaymentOption($order->outlet_id);
                        /*if( isset($check_upi_status) && sizeof($check_upi_status) > 0 ) {
                            $class = '';
                            $vpa = $check_upi_status->payer_va;
                            $proc_btn = 'disabled';
                            if ($check_upi_status->status == 0) {
                                $status_div = '';
                                $txn_id = $check_upi_status->txnid;
                                $proc_btn = 'disabled';
                                $status_btn_div = '';
                                $pay_status = '<span class="label label-default">Pending</span>';
                            } else if ($check_upi_status->status == 2) {
                                $status_div = '';
                                $txn_id = '';
                                $proc_btn = '';
                                $status_btn_div = 'hide';
                                $pay_status = '<span class="label label-danger">failed</span>';
                            } else {
                                $pay_status = '<span class="label label-success">Confirm</span>';
                                $status_div = '';
                                $txn_id = '';
                                $proc_btn = 'disabled';
                                $status_btn_div = 'hide';
                            }
                        } else {
                            $class = 'hide';
                            $vpa = '';
                            $txn_id = '';
                            $proc_btn = '';
                            $status_div = 'hide';
                            $pay_status = '<span class="label label-default">Pending</span>';
                            $status_btn_div = '';
                        }
                        $html .= '<div class="form-group col-md-12 '.$class.'" id="upi_block" style="margin-top: 15px;">
                                    <div class="col-md-10 padding-zero" >
                                        <input value="'.$vpa.'" type="text" class="form-control" id="proc_vpa" placeholder="Virtual Payment Address">
                                    </div>
                                    <div class="col-md-2 padding-zero" >
                                        <button class="btn btn-primary" onclick="proPayViaUpi()" id="proc_proceed_btn" '.$proc_btn.'>Proceed</button>
                                    </div>
                                    <div class="form-group col-md-12 '.$status_div.'" id="proc_check_status_div" style="text-align: center;margin-top: 10px">
                                        <input type="hidden" id="proc_txn_id" value="'.$txn_id.'">
                                        <div id="status_text" class="col-md-12">
                                            '.$pay_status.'
                                        </div>
                                        <div class="proc_status_btn_div col-md-12 '.$status_btn_div.'" style="margin-top: 5px">
                                            <button class="btn btn-danger" onclick="proCheckPaymentStatus()" id="proc_check_status_btn">Check Status</button>
                                        </div>
                                    </div>
                                </div>
                                <div style="clear:both"></div>';*/
        }
        $order_info = order_details::find($o_id);
        $response['consumer'] = $consumer;
        // $response['pay_options'] = $pay_option;
        //$response['source'] = $source;
        $response['inv_no'] = $invoice_number;
        $response['order_id'] = $o_id;
        $response['order_info'] = isset($order_info)?$order_info:"";
        $response['html'] = $html;
        return $response;
    }
    //itemwise calculation for order
    public static function itemWiseOrderCalculation( $order, $req_type, $order_type, $new_delivery,$disc_type = NULL, $disc_val = NULL, $disc_mode = NULL) {
        $total_tax = 0;$total_disc = 0;
        $total = $final_sub_total = $order->totalcost_afterdiscount;
        $order_items = OrderItem::where('order_id',$order->order_id)->get();
        if ( isset($order_items) && sizeof($order_items) > 0 ) {
            foreach ( $order_items as $itm ) {
                //check if slab is set or not
                if ( isset($itm->tax_slab) && $itm->tax_slab != '') {
                    //item taxes
                    $taxes = json_decode($itm->tax_slab);
                    if ( isset($taxes) && sizeof($taxes) > 0 ) {
                        foreach ( $taxes as $t_key=>$t_val ) {
                            foreach ( $t_val as $tx ) {
                                $total_tax += $tx->value;
                            }
                        }
                    }
                }
                //check if itemwise discount enable or not
                if ( $order->itemwise_discount ) {
                    if ( isset($itm->item_discount) && $itm->item_discount != '') {
                        $disc_arr = json_decode($itm->item_discount);
                        if ( $disc_arr->disc_calc_amount > 0 ) {
                            $total_disc += $disc_arr->disc_calc_amount;
                        }
                    }
                }
            }
        }
        //check discount calculate before tax or after tax
        if ( $order->add_discount_after_tax ) {
            $total += $total_tax;
            $total -= $total_disc;
        } else {
            $total -= $total_disc;
            $total += $total_tax;
        }
        $final_sub_total = $total;
        $extra_disc = 0;
        if ( isset($disc_val) && $disc_val != "" && $disc_val > 0  ) {
            if ( $disc_type == 'fixed' ) {
                $total -= $disc_val;
                $extra_disc = $disc_val;
            } else {
                $disc_amount = $total * $disc_val / 100;
                $extra_disc = $disc_amount;
                $total -= $disc_amount;
            }
        }
        //payment options
        $pay_option = PaymentOption::getOutletPaymentOption($order->outlet_id);
        //check upi payment status and accordingly display portion
        $check_upi_status = DB::table('icici_upi_transaction')->where('bill_no',$order->order_unique_id)->orderBy('txnid', 'desc')->first();
        return view('orderlist.itemWiseOrderCalculation',array(
            'order'=>$order,
            'order_type'=>$order_type,
            'new_delivery'=>$new_delivery,
            'req_type'=>$req_type,
            'total_tax'=>$total_tax,
            'total_disc'=>$total_disc,
            'extra_disc'=>$extra_disc,
            'total'=>$total,
            'pay_option'=>$pay_option,
            'check_upi_status'=>$check_upi_status,
            'final_sub_total'=>$final_sub_total,
            'disc_mode'=>$disc_mode
        ))->render();
    }
    public static function orderCalculation($order,$order_type,$req_type,$selected_tax,$new_delivery, $disc_type = NULL, $disc_val = NULL, $disc_mode = NULL ) {
        $source = Sources::getSourceArray($order->outlet_id);
        //payment options
        $pay_option = PaymentOption::getOutletPaymentOption($order->outlet_id);
        //check upi payment status and accordingly display portion
        $check_upi_status = DB::table('icici_upi_transaction')->where('bill_no',$order->order_unique_id)->orderBy('txnid', 'desc')->first();
        $item_disc = 0;
        $order_items = OrderItem::where('order_id',$order->order_id)->get();
        if ( isset($order_items) && sizeof($order_items) > 0 ) {
            foreach ( $order_items as $itm ) {
                //check if itemwise discount enable or not
                if ( $order->itemwise_discount ) {
                    if ( isset($itm->item_discount) && $itm->item_discount != '') {
                        $disc_arr = json_decode($itm->item_discount);
                        if ( $disc_arr->disc_calc_amount > 0 ) {
                            $item_disc += $disc_arr->disc_calc_amount;
                        }
                    }
                }
            }
        }
        return view('orderlist.orderCalculation',array(
                                                        'order'=>$order,
                                                        'order_type'=>$order_type,
                                                        'req_type'=>$req_type,
                                                        'selected_tax'=>$selected_tax,
                                                        'item_disc'=>$item_disc,
                                                        'source'=>$source,
                                                        'pay_option'=>$pay_option,
                                                        'check_upi_status'=>$check_upi_status,
                                                        'new_delivery'=>$new_delivery,
                                                        'disc_value'=>$disc_val,
                                                        'disc_type'=>$disc_type,
                                                        'disc_mode'=>$disc_mode
                                                    ))->render();
    }
    public function processBillFinal() {
        $ord_id = Input::get('order_id');
        $inv_no = Input::get('invoice_no');
        $mobile = Input::get('mobile');
        $name = Input::get('name');
        $sub_total = Input::get('s_total');
        $total = Input::get('total');
        \Log::info("here  process bill final ::".$total);
        $round_off = Input::get('round_off');
        \Log::info("here process bill final".$round_off);
        $ord_type = Input::get('ord_type');
        $tax = Input::get('tax');
        $discount = Input::get('discount');
        $item_discount = Input::get('item_discount');
        $discount_type = Input::get('discount_type');
        $address = Input::get('address');
        $source_id = Input::get('source_id');
        $paid_type = 0;
        $delivery_charge = Input::get('delivery_charge');
        $custom_fields = Input::get('custom_fields');
        $tax_id = Input::get("tax_id");
        $source_id_arr = Input::get('source_ids');
        $payment_opt_ids = Input::get('payment_option_ids');
        $payment_mode_amounts = Input::get('payment_mode_amount');
        $trn_ids = Input::get('trn_ids');
        $owner = Auth::user()->user_name;
        if ( $ord_type != 'home_delivery' ) {
            $address = '';
        }
        if ( isset($tax) && $tax != '') {
            $tax = '['.json_encode($tax).']';
        } else {
            $tax = '';
        }
        if ( isset($ord_id) ) {
            $order = order_details::join('outlets as ot','orders.outlet_id','=','ot.id')->where('order_id',$ord_id)->first();
            if ( isset($order) && !empty($order) ) {
                $inv = substr($inv_no,-$order->invoice_digit);
            }
            //set payment status
            if ( $paid_type == 0 ) {
                $py_status = 0;
            } else {
                $py_status = 1;
            }
            $custom_fields_final = NULL;
            $sess_outlet_id = Session::get('outlet_session');
            $outlet = Outlet::find($sess_outlet_id);
            $outlet_custom_fields = json_decode($outlet->custom_bill_print_fields);
            $custom_fields_arr = array();
            $customer_email = NULL;
            if(isset($outlet_custom_fields) && sizeof($outlet_custom_fields)>0 && isset($custom_fields) && sizeof($custom_fields)>0) {
                foreach ($custom_fields as $field) {
                    foreach ($outlet_custom_fields as $fields) {
                        $check = 0;
                        foreach ($fields as $key=>$val){ //check whether custom key available in same loop
                            if($key == $field['name']){
                                $check = 1;
                            }
                        }
                        if($check == 1) {
                            $cust_field_name = $field['name'];
                            $temp = $fields->$cust_field_name;
                            if (isset($temp) && sizeof($temp) > 0) {
                                if($field['name'] == "email") {
                                    $customer_email = $field['value'];
                                }
                                $temp_arr['label'] = $temp[0]->label;
                                $temp_arr['value'] = $field['value'];
                                $temp_arr['type'] = $temp[0]->type;
                                break;
                            }
                        }
                    }
                    $custom_fields_arr[$field['name']] = $temp_arr;
                }
                $final_custom_fields = json_encode($custom_fields_arr);
            }else{
                $final_custom_fields = NULL;
            }
            $customer_id = NULL;
            if ( trim($mobile) != 0 || strlen(trim($name)) != 0 ) {
                $check_customer = Customer::where('mobile_number',$mobile)->where('mobile_number','!=',0)->first();
                if (  isset($check_customer) && !empty($check_customer) ) {
                    $check_customer->address = $address;
                    $check_customer->first_name = $name;
                    if (isset($customer_email) && sizeof($customer_email)>0)
                        $check_customer->email = $customer_email;
                    $check_customer->save();
                    $customer_id = $check_customer->id;
                } else {
                    $customer_obj = new Customer();
                    $customer_obj->first_name = $name;
                    $customer_obj->mobile_number = $mobile;
                    if (isset($customer_email) && sizeof($customer_email)>0)
                        $customer_obj->email = $customer_email;
                    $customer_obj->address = $address;
                    $cust_result = $customer_obj->save();
                    if ( $cust_result ) {
                        $customer_id = $customer_obj->id;
                    }
                }
            }elseif (isset($customer_email) && sizeof($customer_email)>0){
                $check_customer = Customer::where('email',$customer_email)->first();
                if (  isset($check_customer) && sizeof($check_customer) > 0 ) {
                    $check_customer->address = $address;
                    if (isset($customer_email) && sizeof($customer_email)>0)
                        $check_customer->email = $customer_email;
                    $check_customer->save();
                    $customer_id = $check_customer->id;
                } else {
                    $customer_obj = new Customer();
                    if (isset($customer_email) && sizeof($customer_email)>0)
                        $customer_obj->email = $customer_email;
                    $customer_obj->address = $address;
                    $cust_result = $customer_obj->save();
                    if ( $cust_result ) {
                        $customer_id = $customer_obj->id;
                    }
                }
            }
            \Log::info("here orderdetails".$total);
            order_details::where('order_id',$ord_id)
                ->update([
                        'invoice_no'=>$inv_no,
                        'invoice'=>intval($inv),
                        'user_id'=>$customer_id,
                        'discount_value'=>$discount,
                        'item_discount_value'=>$item_discount,
                        'discount_type'=>$discount_type,
                        'tax_type'=>$tax,
                        'tax_percent'=>$tax_id,       //Tax id stored in tax value
                        'totalprice'=>$total,
                        'user_mobile_number'=>$mobile,
                        'order_type'=>$ord_type,
                        'address'=>$address,
                        'source_id'=>$source_id,
                        'paid_type'=>'cash',
                        'payment_option_id'=>$paid_type,
                        'payment_status'=>$py_status,
                        'round_off'=>$round_off,
                        'delivery_charge'=>$delivery_charge,
                        'custom_fields'=>$final_custom_fields,
                        'read'=> 1,
                    ]);
            $payment_arr = array();
            if ( isset($payment_opt_ids) && sizeof($payment_opt_ids) > 0 ) {
                OrderPaymentMode::where('order_id',$ord_id)->delete();
                for ( $i=0; $i < sizeof($payment_opt_ids); $i++ ) {
                    $py_modes = new OrderPaymentMode();
                    $py_modes->order_id = $ord_id;
                    $py_modes->payment_option_id = 0;
                    $py_modes->source_id = $source_id_arr[$i];
                    $py_modes->transaction_id = $trn_ids[$i];
                    $py_modes->amount = $payment_mode_amounts[$i];
                    $py_modes->save();
                    $payment_opt = PaymentOption::getPaymentOptionById($payment_opt_ids[$i]);
                    $source_name = Sources::getSourceNameById($source_id_arr[$i]);
                    if($source_name == "") {
                        $payment_str = $payment_opt  . " (" . $payment_mode_amounts[$i].")";
                    }else {
                        $payment_str = $payment_opt . "-" . $source_name . " (" . $payment_mode_amounts[$i].")";
                    }
                    array_push($payment_arr,$payment_str);
                }
            }
            $histroy = new OrderHistory();
            $histroy->order_id = $ord_id;
            $histroy->invoice_no = $inv_no;
            $histroy->owner = $owner;
            $histroy->order_type = $ord_type;
            $histroy->user_mobile_no = isset($mobile)?$mobile:"";
            $histroy->address = isset($address)?$address:"";
            $histroy->total = $total;
            $histroy->payment_modes = implode(", ",$payment_arr);
            $histroy->discount = isset($discount)?$discount:0;
            $histroy->sub_total = $order->totalcost_afterdiscount;
            $histroy->round_off = isset($round_off)?$round_off:0.00;
            $histroy->taxes = $tax;
            $histroy->delivery_charge = isset($delivery_charge)?$delivery_charge:0.00;
            $histroy->save();
            //$order_obj = order_details::where('order_id',$ord_id)->first();
            //$outlet_setting = Outlet::find($order_obj->outlet_id);
            if ( $outlet->stock_auto_decrement == '1') {
                $default_location = Location::where('outlet_id',$sess_outlet_id)->where('default_location',1)->first();
                if ( isset($default_location) && isset($default_location) ) {
                    $ord_items = OrderItem::where('order_id',$ord_id)->get();
                    foreach ( $ord_items as $asd ) {
                        if ( $asd->item_id == 0 ) {
                            continue;
                        }
                        StocksController::onSellDecreaseStock( array('item_id'=>$asd->item_id,'quantity'=>$asd->item_quantity,'order_id'=>$ord_id), $default_location->id, $default_location->created_by );
                    }
                }
            }
            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
        }
        return json_encode($response);
    }
    public function getInvoiceNo() {
        $type = Input::get('type');
        $ord_id = Input::get('ord_id');
        $req_type = Input::get('req_type');
        $selected_tax = Input::get('tax_name');
        $new_delivery = Input::get('new_delivery');
        $discount_type = Input::get('discount_type');
        $discount_val = Input::get('discount_val');
        $outlet_id = Session::get('outlet_session');
        $outlet = Outlet::findOutlet($outlet_id);
        $user_identifier = Auth::user()->user_identifier;
        $order = order_details::where('order_id',$ord_id)->join('outlets as ot','orders.outlet_id','=','ot.id')
            ->select('orders.*','ot.invoice_date as inv_date','ot.invoice_prefix as invoice_prefix','ot.invoice_digit as inv_digit','ot.name as ot_name','ot.taxes as taxes','ot.default_taxes as default_taxes','ot.code as ot_code','ot.order_no_reset as order_reset')
            ->first();
        $invoice_number = $this->generateInvoiceNumber($outlet_id, $ord_id, $type);
        $s_total = number_format($order->totalcost_afterdiscount,2,'.','');
        //check itemwise tax
        $check_itemwise_tax = OutletSetting::checkAppSetting($order->outlet_id,'itemWiseTax');
        $order->discount_after_tax = OutletSetting::checkAppSetting($order->outlet_id,'discountAfterTax');
        //getting tax view when request comes from edit bill
        $tax_view ='';
        if ( $req_type == 'edit' || $req_type == 'reset' ) {
            if(trim($discount_type) == ""){
                $discount_type = $order->discount_type;
            }
            if(trim($discount_val) == ""){
                $discount_type = $order->discount_value;
            }
            if ( $check_itemwise_tax ) {
                $tax_view .= $this::itemWiseOrderCalculation($order,$req_type,$type,$new_delivery,$discount_type, $discount_val);
            } else {
                $tax_view = $this::orderCalculation($order,$type,$req_type,$selected_tax,$new_delivery,$discount_type, $discount_val);
            }
        } else if ( $req_type == 'process' ) {
            if ( $check_itemwise_tax ) {
                $tax_view .= $this::itemWiseOrderCalculation($order,'process',$type,$new_delivery,$discount_type, $discount_val);
            } else {
                $tax_view = $this::orderCalculation($order,$type,$req_type,$selected_tax,$new_delivery,$discount_type, $discount_val);
            }
        }
        $data['order_type'] = $order->order_type;
        $data['invoice_no'] = $invoice_number;
        $data['tax_view'] = $tax_view;
        $data['discount_type'] = $discount_type;
        $data['discount_val'] = $discount_val;
        return $data;
    }
    public function editBill() {
        $o_id = Input::get('order_id');
        //get order detail
        $order = order_details::where('order_id',$o_id)->join('outlets as ot','orders.outlet_id','=','ot.id')->select('orders.*','ot.invoice_date as inv_date','ot.name as ot_name','ot.taxes as taxes','ot.default_taxes as default_taxes')->first();
        $item_arr = array();
        if( isset($order) && !empty($order) ) {
            $order_items = OrderItem::select('order_items.id as item_id','order_items.item_price as itm_price','order_items.item_quantity as itm_quantity','order_items.item_name as itm_name')->where('order_items.order_id',$o_id)->get();
            if ( isset($order_items) && !empty($order_items) ) {
                $i = 0;
                foreach( $order_items as $item ) {
                    $item_arr['item'][$i]['item_id'] = $item->item_id;
                    $item_arr['item'][$i]['name'] = $item->itm_name;
                    $item_arr['item'][$i]['qty'] = $item->itm_quantity;
                    $item_arr['item'][$i]['price'] = $item->itm_price;
                    $item_arr['item'][$i]['amount'] = $item->itm_price * $item->itm_quantity;
                    $i++;
                }
            }
        }
        //check to display upi block or not
        //$check_pay_opt = PaymentOption::find($order->payment_option_id);
        /*$check_upi_status = '';
        if ( isset($check_pay_opt) && strtolower($check_pay_opt->name) == 'upi') {
            $check_upi_status = DB::table('icici_upi_transaction')->where('bill_no',$order->order_unique_id)->orderBy('txnid', 'desc')->first();
        }*/
        if(isset($order->user_id) && !empty($order->user_id)){
            $user = Customer::find($order->user_id);
            $order->customer_name = isset($user->first_name)?$user->first_name:"";
            $order->mobile_number = isset($user->mobile_number)?$user->mobile_number:"";
        }
        //payment options
        $payment_options = PaymentOption::getOutletPaymentOption($order->outlet_id);
        $custom_fields = json_decode($order->custom_fields,true);
        //order payment modes
        $ord_payment_modes = OrderPaymentMode::where('order_id',$o_id)->get();
        $outlet_id = Session::get('outlet_session');
        $delivery_settings = OutletSetting::checkAppSetting($outlet_id,"overwriteDeliveryCharge");
        $itemWiseDiscount = $order->itemwise_discount;
        $itemWiseTax = $order->itemwise_tax;
        return view("editbill",array('custom_fields'=>$custom_fields,
                        /*'check_upi_status'=>$check_upi_status,*/
                        'order'=>$order,'items'=>$item_arr,'pay_option'=>$payment_options,'payment_modes'=>$ord_payment_modes,'delivery_settings'=>$delivery_settings,'itemWiseDiscount'=>$itemWiseDiscount,'itemWiseTax'=>$itemWiseTax));
    }
    public function updateInvoice() {
        $ord_id = Input::get('order_id');
        $inv_no = Input::get('invoice_no');
        $mobile = Input::get('mobile');
        $name = Input::get('name');
        $sub_total = Input::get('s_total');
        $total = Input::get('total');
        $round_off = Input::get('round_off');
        $ord_type = Input::get('ord_type');
        $tax = Input::get('tax');
        $tax_id = Input::get('tax_id');
        $discount = Input::get('discount');
        $address = Input::get('address');
        $payment_option_id = Input::get('paid_type');
        $source_id = Input::get('source');
        $delivery_charge = Input::get('delivery_charge');
        $owner = Auth::user()->user_name;
        $custom_fields = Input::get('custom_fields');
        $source_id_arr = Input::get('source_ids');
        $payment_opt_ids = Input::get('payment_option_ids');
        $payment_mode_amounts = Input::get('payment_mode_amount');
        $trn_ids = Input::get('trn_ids');
        $discount_type = Input::get("discount_type");
        if ( $ord_type != 'home_delivery' ) {
            $address = '';
        }
        if ( isset($tax) && $tax != '') {
            $tax = '['.json_encode($tax).']';
        } else {
            $tax = '';
        }
        if ( isset($ord_id) ) {
            $order = order_details::join('outlets as ot','orders.outlet_id','=','ot.id')->where('order_id',$ord_id)->first();
            if ( isset($order) && !empty($order) ) {
                $inv = substr($inv_no,-$order->invoice_digit);
            }
            if ( $payment_option_id == 0 ) {
                $py_status = 0;
            } else {
                $py_status = 1;
            }
            $customer_id = NULL;
            if ( $mobile != '' || $name != '' ) {
                $check_customer = Customer::where('mobile_number',$mobile)->where('mobile_number','!=',0)->first();
                if (  isset($check_customer) && !empty($check_customer) ) {
                    $check_customer->address = $address;
                    $check_customer->first_name = $name;
                    $check_customer->save();
                    $customer_id = $check_customer->id;
                } else {
                    $customer_obj = new Customer();
                    $customer_obj->first_name = $name;
                    $customer_obj->mobile_number = $mobile;
                    $customer_obj->address = $address;
                    $cust_result = $customer_obj->save();
                    if ( $cust_result ) {
                        $customer_id = $customer_obj->id;
                    }
                }
            }
            $custom_fields_final = NULL;
            $sess_outlet_id = Session::get('outlet_session');
            $outlet = Outlet::find($sess_outlet_id);
            $outlet_custom_fields = json_decode($outlet->custom_bill_print_fields);
            $custom_fields_arr = array();
            if(isset($outlet_custom_fields) && isset($custom_fields) && sizeof($custom_fields)>0) {
                foreach ($custom_fields as $field) {
                    foreach ($outlet_custom_fields as $fields) {
                        $check = 0;
                        foreach ($fields as $key=>$val){ //check whether custom key available in same loop
                            if($key == $field['name']){
                                $check = 1;
                            }
                        }
                        if($check == 1) {
                            $cust_field_name = $field['name'];
                            $temp = $fields->$cust_field_name;
                            if (isset($temp) && !empty($temp)) {
                                $temp_arr['label'] = $temp[0]->label;
                                $temp_arr['value'] = $field['value'];
                                $temp_arr['type'] = $temp[0]->type;
                                break;
                            }
                        }
                    }
                    $custom_fields_arr[$field['name']] = $temp_arr;
                }
                $final_custom_fields = json_encode($custom_fields_arr);
            }else{
                $final_custom_fields = NULL;
            }
            order_details::where('order_id',$ord_id)->update([
                'invoice_no'=>$inv_no,
                'invoice'=>intval($inv),
                'discount_value'=>$discount,
                'discount_type'=>$discount_type,
                'user_id'=>$customer_id,
                'user_mobile_number'=>$mobile,
                'tax_type'=>$tax,
                'tax_percent'=>$tax_id,
                'totalprice'=>$total,
                'order_type'=>$ord_type,
                'address'=>$address,
                'paid_type'=>'cod',
                'round_off'=>$round_off,
                'payment_option_id'=>$payment_option_id,
                'source_id'=>$source_id,
                'updated'=>1,
                'payment_status'=>$py_status,
                'delivery_charge'=>$delivery_charge,
                'custom_fields'=>$final_custom_fields
            ]);
            $payment_arr = array();
            //update payment modes
            if ( isset($payment_opt_ids) && sizeof($payment_opt_ids) > 0 ) {
                //remove old payment modes
                OrderPaymentMode::where('order_id',$ord_id)->delete();
                for ( $i=0; $i < sizeof($payment_opt_ids); $i++ ) {
                    $py_modes = new OrderPaymentMode();
                    $py_modes->order_id = $ord_id;
                    $py_modes->payment_option_id = $payment_opt_ids[$i];
                    $py_modes->source_id = $source_id_arr[$i];
                    $py_modes->transaction_id = $trn_ids[$i];;
                    $py_modes->amount = $payment_mode_amounts[$i];
                    $py_modes->save();
                    $payment_opt = PaymentOption::getPaymentOptionById($payment_opt_ids[$i]);
                    $source_name = Sources::getSourceNameById($source_id_arr[$i]);
                    if($source_name == "") {
                        $payment_str = $payment_opt  . " (" . $payment_mode_amounts[$i].")";
                    }else {
                        $payment_str = $payment_opt . "-" . $source_name . " (" . $payment_mode_amounts[$i].")";
                    }
                    array_push($payment_arr,$payment_str);
                }
            }
            $histroy = new OrderHistory();
            $histroy->order_id = $ord_id;
            $histroy->invoice_no = $inv_no;
            $histroy->owner = $owner;
            $histroy->order_type = $ord_type;
            $histroy->user_mobile_no = isset($mobile)?$mobile:"";
            $histroy->address = isset($address)?$address:"";
            $histroy->total = $total;
            $histroy->discount = isset($discount)?$discount:0.00;
            $histroy->payment_modes = implode(", ",$payment_arr);
            $histroy->sub_total = $order->totalcost_afterdiscount;
            $histroy->round_off = $round_off;
            $histroy->taxes = $tax;
            $histroy->delivery_charge = isset($delivery_charge)?$delivery_charge:"";
            $histroy->save();
            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
        }
        return json_encode($response);
    }
    /**
     * @param Request $request
     * @return View
     */
    public function orderList(Request $request) {
        $flag = Input::get('flag');
        // echo "laravel 5.1.11 <pre>"; print_r($flag); echo "</pre>"; exit;
        //        $all = Input::all();
        //        print_r($all);exit;
        if ($request->ajax() || ( isset($flag) && $flag == 'export') ) {
            $from = Input::get('from_date');
            $to = Input::get('to_date');
            $date_type = Input::get('date_type');
            $order_date_field = 'table_start_date';
            if ( $date_type == 'end_time') {
                $order_date_field = 'table_end_date';
            }
            $outlet_id = Session::get('outlet_session');
            $slot_time = Input::get('time_slot');
            $total_slots = Timeslot::gettimeslotbyoutletid($outlet_id);
            $outlet = Outlet::find($outlet_id);
            $lable = 'Table';
            if ( isset($outlet->order_lable) && $outlet->order_lable != '') {
                $lable = $outlet->order_lable;
            }
            $payment_options = $outlet->payment_options;
            //$sources_array = Sources::lists('name','id');
            $po_array = PaymentOption::lists('name','id');
            $outlet_options_arr = array();
            $outlet_source_arr = array();
            if(isset($payment_options) && !empty($payment_options)) {
                $payment_option_array = json_decode($payment_options, true);
                $outlet_options = array_keys($payment_option_array);
                foreach ($outlet_options as $id => $option_id) {                           //outlet wise data
                    $outlet_options_arr[$option_id] = PaymentOption::find($option_id)['name'];
                    $get_source = json_decode($payment_options, true);
                    $outlet_src_arr[$option_id] = $payment_option_array[$option_id];
                    $outlet_source_arr[$option_id][0] = '';                             //only payment option
                    if (!empty($outlet_src_arr[$option_id]) && $outlet_src_arr[$option_id][0] != $option_id){
                    //if only payment option is selected
                        foreach ($outlet_src_arr[$option_id] as $id => $source_id) {
                            $outlet_source_arr[$option_id][$source_id] = Sources::find($source_id)['name'];
                        }
                    }
                }
            }
                //print_r($po_array);exit;
            $from_time = "";
            $to_time = "";
            if($total_slots != '' && isset($total_slots)) {
                foreach ($total_slots as $slot){
                    if($slot->id == $slot_time){
                        $from_time = $slot->from_time;
                        $to_time = $slot->to_time;
                    }
                }
            }
            if ( $from_time != '' && $to_time != '' ) {
                $from = $from.' '.$from_time.':00';
                $to = $to.' '.$to_time.':59';
            } else {
                $from = Utils::getSessionTime($from,'from');
                $to = Utils::getSessionTime($to,'to');
            }
            /*->join("invoice_details as inv","inv.order_id","=","orders.order_id")*/
            $orders = order_details::join("order_items","order_items.order_id","=","orders.order_id")->select('orders.*','order_items.item_quantity as Quantity','order_items.item_name as item_name')->where('orders.'.$order_date_field,'>=', $from)->where('orders.'.$order_date_field,'<=', $to)->where('orders.outlet_id','=',$outlet_id)->orderBy('orders.'.$order_date_field,'desc')->where('orders.cancelorder', '!=', '1')->get();
            $itemlist=array();
            $itemlist_excel=array();
            $data['orders']=array();
            foreach($orders as $order) {
                if (array_key_exists($order->order_id,$itemlist)) {
                    if($order->Quantity>1){
                        $itemlist[$order->order_id] = $itemlist[$order->order_id] . ",</br>" .$order->Quantity." x ".strtoupper($order->item_name);
                        $itemlist_excel[$order->order_id] = $itemlist_excel[$order->order_id] . ",\n" .$order->Quantity." x ".strtoupper($order->item_name);
                    }else {
                        $itemlist[$order->order_id] = $itemlist[$order->order_id] . ",</br>" ."1 x ".strtoupper($order->item_name);
                        $itemlist_excel[$order->order_id] = $itemlist_excel[$order->order_id] . ",\n" ."1 x ".strtoupper($order->item_name);
                    }
                } else {
                    if($order->Quantity>1){
                        $itemlist[$order->order_id] = $order->Quantity." x ".strtoupper($order->item_name);
                        $itemlist_excel[$order->order_id] = $order->Quantity." x ".strtoupper($order->item_name);
                    }else {
                        $itemlist[$order->order_id] = "1 x ".strtoupper($order->item_name);
                        $itemlist_excel[$order->order_id] = "1 x ".strtoupper($order->item_name);
                    }
                    $mode_name = 'UnPaid';
                    
                    /*if( $order->payment_option_id != 0 ) {
                        $mode = PaymentOption::find($order->payment_option_id);
                        if ( isset($mode) && sizeof($mode) > 0 ) {
                            $pay_mode = $mode->name;
                        }
                        if ( $order->source_id != 0 ) {
                            $src = Sources::find($order->source_id);
                            if ( isset($src) && sizeof($src) > 0 ) {
                                $pay_mode .=" - ".$src->name;
                            }
                        }
                    }*/
                    $py_modes = OrderPaymentMode::where('order_id',$order->order_id)->get();
                    if ( isset($py_modes) && $py_modes->count() > 0 ) {
                        $cnt = 0;$mode_name = '';
                        foreach ( $py_modes as $py_mode ) {
                            $mode = PaymentOption::find($py_mode->payment_option_id);
                            if( $py_mode->payment_option_id != 0 ) {
                                if ( isset($mode) && isset($mode->id) ) {
                                    $mode_name .= $mode->name;
                                }
                                if ( $py_mode->source_id != 0 ) {
                                    $src = Sources::find($py_mode->source_id);
                                    if ( isset($src) && isset($src->id) ) {
                                        $mode_name .=" - ".$src->name;
                                    }
                                }
                            } else {
                                $mode_name .= "Unpaid";
                            }
                            if ( sizeof($py_modes) != 1 ) {
                                $mode_name .=" (".$py_mode->amount.")";
                                if($py_mode->payment_option_id != 0) {
                                    if (in_array(strtolower($mode->name), array('online', 'cheque'))) {
                                        $temp_title = "";
                                        if (trim($py_mode->transaction_id) != "" && $py_mode->transaction_id != "0") {
                                            $temp_title .= 'Transaction Id : ' . $py_mode->transaction_id . '&#013;';
                                        }
                                        if (trim($order->note) != "") {
                                            $temp_title .= 'Note : ' . $order->note;
                                        }
                                        if(trim($temp_title) != "") {
                                            $mode_name .= " <i title='$temp_title' class='fa fa-info-circle'></i>";
                                        }
                                    }
                                }
                            }else{
                                if($py_mode->payment_option_id != 0) {
                                    // Log::info("mode name".$mode["name"]);
                                    // if (in_array(strtolower($mode->name), array('online', 'cheque'))) {
                                        $temp_title = "";
                                        if (trim($py_mode->transaction_id) != "" && $py_mode->transaction_id != "0") {
                                            $temp_title .= 'Transaction Id : ' . $py_mode->transaction_id . '&#10;';
                                        }
                                        if (trim($order->note) != "") {
                                            $temp_title .= 'Note : ' . $order->note;
                                        }
                                        if(trim($temp_title) != "") {
                                            $mode_name .= " <i title='$temp_title' class='fa fa-info-circle'></i>";
                                        }
                                    // }
                                }
                            }
                            $cnt++;
                            if ( sizeof($py_modes) != $cnt ) {
                                $mode_name .= "<hr style='margin-top: 5px;margin-bottom: 5px;'>";
                            }
                        }
                    }
                    $order->payment_mode = $mode_name ;
                    array_push($data['orders'],$order);
                }
            }
            
            $data['total_orders'] = sizeof($data['orders']);
            $data['itemlist']=$itemlist;
            
            if ( isset($flag) && $flag == 'export') {
                $chk_invoice_no = Input::get("invoice_no");
                $chk_datetime = Input::get("datetime");
                $chk_order_no = Input::get("order_no");
                $chk_name = Input::get("name");
                $chk_order_type = Input::get("bill_order_type");
                $chk_item_list = Input::get("item_list");
                $chk_sub_total = Input::get("sub_total");
                $chk_discount = Input::get("discount");
                $chk_tax_amount = Input::get("tax_amount");
                $chk_round_off = Input::get("round_off");
                $chk_bifurcation = Input::get("bifurcation");
                $chk_no_person = Input::get("no_person");
                $chk_final_total = Input::get("fina_total");
                $other_fields = Input::get("other_fields");
                $tax_bifurcation = Input::get("tax_bifurcation");
                $custom_fields = $outlet->custom_bill_print_fields;
                $total_tax = 0;
                $total_discount = 0;
                $total_final = 0;
                $total_person_visit = 0;
                $total_subtotal = 0;
                $excel_data=array();
                $total_cash = 0;
                $total_credit = 0;
                $n=0;
                if ( isset($orders) ) {
                    foreach($data['orders'] as $ord ) {
                        //check records match selected slot
                        if ( isset($from_time) && isset($to_time) && $from_time != '' && $to_time != '' ) {
                            $time = date('H:i:s',strtotime($ord->$order_date_field));
                            $date1 = strtotime($time); $date2 = strtotime($from_time); $date3 = strtotime($to_time);
                            if ($date1 >= $date2 && $date1 <= $date3) {
                            } else {
                                continue;
                            }
                        }
                        $tax_total=0;
                        $json_tax=json_decode($ord->tax_type);
                        if(isset($json_tax))
                        {
                            foreach( $json_tax as $tx ){
                                if(gettype($tx) == 'string')
                                    $tx1 = json_decode($tx);
                                else
                                    $tx1 = $tx;
                                foreach( $tx1 as $key1=>$t){
                                    $tax_total += $t->calc_tax;
                                }
                            }
                        }
                        $total_tax += $tax_total;
                        // $total_discount += $ord->discount_value + $ord->item_discount_value;
                        $total_discount += (float) ($ord->discount_value ?? 0.00) + (float) ($ord->item_discount_value ?? 0.00);
                        $total_final += floatval($ord->totalprice);
                        $total_subtotal += floatval($ord->totalcost_afterdiscount);
                        $total_person_visit += $ord->person_no;
                        if ( $ord->paid_type == 'cod') {
                            $total_cash += $ord->totalprice;
                        } else {
                            $total_credit += $ord->totalprice;
                        }
                        if(isset($chk_invoice_no)) {
                            $excel_data[$n]['Invoice No.'] = $ord->invoice_no;
                        }
                        if(isset($chk_name)) {
                            if (isset($ord->user_id) ) {
                                $customer_detail = Customer::find($ord->user_id);

                                if (isset($customer_detail) && !empty($customer_detail) ) {
                                    $customer_first_name = isset($customer_detail->first_name) ? $customer_detail->first_name : '';
                                    $customer_last_name = isset($customer_detail->last_name) ? $customer_detail->last_name : '';
                                    $excel_data[$n]['Name'] = $customer_first_name . " " . $customer_last_name;
                                } else {
                                    $excel_data[$n]['Name'] = '';
                                }
                            } else {
                                $excel_data[$n]['Name'] = '';
                            }
                        }
                        if(isset($chk_datetime)) {
                            $excel_data[$n]['Timestamp'] = $ord->table_start_date;
                        }
                        if(isset($chk_order_no)) {
                            $excel_data[$n][$lable . ' No.'] = $ord->table_no;
                        }
                        if(isset($chk_no_person)) {
                            $excel_data[$n]['No. Of Person'] = $ord->person_no;
                        }
                        if(isset($chk_order_type)) {
                            $excel_data[$n]['Order Type'] = ReportController::get_order_type($ord->order_type);
                        }
                        if(isset($chk_item_list)) {
                            $excel_data[$n]['Item List'] = ucfirst($itemlist_excel[$ord->order_id]);
                        }
                        if(isset($chk_sub_total)) {
                            $excel_data[$n]['Sub Total'] = number_format($ord->totalcost_afterdiscount, 2);
                        }
                        $tot_discount = 0.00;
                        // $tot_discount = $ord->discount_value + $ord->item_discount_value;
                        $tot_discount = (float)($ord->discount_value ?? 0.00) + (float)($ord->item_discount_value ?? 0.00);
                        if(isset($chk_discount)) {
                            $excel_data[$n]['Discount'] = number_format($tot_discount, 2);
                        }
                        if(isset($chk_tax_amount)) {
                            $excel_data[$n]['Total Tax Amount'] = number_format($tax_total, 2);
                        }
                        if(isset($chk_round_off)) {
                            $excel_data[$n]['Round Off'] = number_format($ord->round_off, 2);
                        }
                        //$excel_data[$n]['Gross Total'] = number_format($ord->totalprice,2);
                        if(isset($chk_bifurcation)) {
                            foreach ($outlet_source_arr as $key => $value) {
                                foreach ($value as $src_id => $src) {
                                    if ($ord->source_id == $src_id && $ord->payment_option_id == $key) {
                                        $excel_data[$n][$po_array[$key] . ' ' . $src] = number_format($ord->totalprice, 2);
                                    } else {
                                        $excel_data[$n][$po_array[$key] . ' ' . $src] = "0.00";
                                    }
                                }
                            }
                        }
                        if(isset($chk_final_total)) {
                            if ($ord->paid_type == 'cod') {
                                $excel_data[$n]['Cash'] = number_format($ord->totalprice, 2);
                                $excel_data[$n]['Online'] = '0.00';
                            } else {
                                $excel_data[$n]['Cash'] = '0.00';
                                $excel_data[$n]['Online'] = number_format($ord->totalprice, 2);
                            }
                        }
                        if(isset($other_fields) && $other_fields != "") {
                            $ord_custom_fields = $ord->custom_fields;
                            if($ord_custom_fields != "") {
                                foreach(json_decode($ord_custom_fields) as $custom_field) {
                                    $excel_data[$n][$custom_field->label] = $custom_field->value;
                                }
                            } else {
                                $outlet_custom_fields = $outlet->custom_bill_print_fields;
                                if($outlet_custom_fields != "") {
                                    foreach(json_decode($outlet_custom_fields) as $custom_field) {
                                        if($custom_field != "") {
                                            foreach($custom_field as $field) {
                                                if($field != "") {
                                                    foreach($field as $fieldObj) {
                                                        $excel_data[$n][$fieldObj->label] = "";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if(isset($tax_bifurcation)) {
                            //get itemwisetax or not?
                            $outlet_settings = OutletSetting::where('outlet_id',$outlet->id)->where('setting_id',30)->first();
                            if(isset($outlet_settings)) {
                                if($outlet_settings->setting_value == 'true') {
                                    $order_items = OrderItem::where("order_id",$ord->order_id)->get();
                                    $outlet_tax_slabs = $outlet->taxes;
                                    if($order_items && $outlet_tax_slabs) {
                                        $item_tax = [];
                                        foreach($order_items as $order_item) {
                                            if($order_item->tax_slab != "") {
                                                foreach(json_decode($order_item->tax_slab) as $taxkey=>$taxslab){
                                                    if(isset($item_tax[$taxkey])){
                                                        $item_tax[$taxkey] += $order_item->item_total;
                                                    } else {
                                                        $item_tax[$taxkey] = 0;
                                                        $item_tax[$taxkey] += $order_item->item_total;;
                                                    }
                                                }
                                            }
                                        }
                                        if(sizeof($item_tax) > 0){
                                            foreach($item_tax as $tax_key=>$itemtax) {
                                                foreach(json_decode($outlet_tax_slabs) as $o_tax=>$tax_slab) {
                                                    if($tax_key == $o_tax) {
                                                        $excel_data[$n][$o_tax." Taxable Rs"] = $itemtax;
                                                        $excel_data[$n][$o_tax." Tax Rs"] = Utils::taxCalculation($itemtax,$tax_slab);
                                                    } else {
                                                        if(!array_key_exists($o_tax,$item_tax)){
                                                            $excel_data[$n][$o_tax." Taxable Rs"] = 0;
                                                            $excel_data[$n][$o_tax." Tax Rs"] = 0;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $tax_type = $ord->tax_type;
                                    if($tax_type != "") {
                                        foreach(json_decode($tax_type) as $taxObj) {
                                            foreach($taxObj as $key=>$tax) {
                                                $excel_data[$n][$key." %"] = $tax->percent;
                                                $excel_data[$n][$key." Rs"] = $tax->calc_tax;
                                            } 
                                        }
                                    }
                                }
                            }
                        }
                        $n++;
                    }
                }
                $n++;
                if(isset($chk_invoice_no)) {
                    $excel_data[$n]['Invoice'] = "";
                }
                if(isset($chk_name)) {
                    $excel_data[$n]['Name'] = "";
                }
                if(isset($chk_datetime)) {
                    $excel_data[$n]['Timestamp'] = "";
                }
                if(isset($chk_order_no)) {
                    $excel_data[$n][$lable . ' No.'] = "";
                }
                if(isset($chk_no_person)) {
                    $excel_data[$n]['No. Of Person'] = $total_person_visit;
                }
                if(isset($chk_order_type)) {
                    $excel_data[$n]['Order Type'] = "";
                }
                if(isset($chk_item_list)) {
                    $excel_data[$n]['Item List'] = "";
                }
                if(isset($chk_sub_total)) {
                    $excel_data[$n]['Sub Total'] = number_format($total_subtotal, 2);
                }
                if(isset($chk_discount)) {
                    $excel_data[$n]['Discount'] = number_format($total_discount, 2);
                }
                if(isset($chk_tax_amount)) {
                    $excel_data[$n]['Total Tax Amount'] = number_format($total_tax, 2);
                }
                if(isset($chk_round_off)) {
                    $excel_data[$n]['Round Off'] = "";
                }
                //$excel_data[$n]['Gross Total'] = number_format($total_final,2);
                if(isset($chk_bifurcation)) {
                    foreach ($outlet_source_arr as $key => $value) {
                        foreach ($value as $src_id => $src) {
                            $excel_data[$n][$po_array[$key] . ' ' . $src] = order_details::where('source_id', $src_id)->where('orders.' . $order_date_field, '>=', $from)
                                ->where('orders.' . $order_date_field, '<=', $to)
                                ->where('orders.outlet_id', '=', $outlet_id)
                                ->where('payment_option_id', $key)->sum('totalprice');
                            if ($excel_data[$n][$po_array[$key] . ' ' . $src] == 0) {
                                $excel_data[$n][$po_array[$key] . ' ' . $src] = "0.00";
                            }
                        }
                    }
                }
                if(isset($chk_final_total)) {
                    $excel_data[$n]['Cash'] = number_format($total_cash, 2);
                    $excel_data[$n]['Online'] = number_format($total_credit, 2);
                }
                if(isset($other_fields) && $custom_fields != "") {
                    $ord_custom_fields = $ord->custom_fields;
                    if($ord_custom_fields != ""){
                        foreach(json_decode($ord_custom_fields) as $custom_field) {
                            $excel_data[$n][$custom_field->label] = "";
                        }
                    }
                }
                if(isset($tax_bifurcation)) {
                    $outlet_settings = OutletSetting::where('outlet_id',$outlet->id)->where('setting_id',30)->first();
                    if(isset($outlet_settings)) {
                        if($outlet_settings->setting_value == 'true') {
                            $outlet_tax_slabs = $outlet->taxes;
                            if($outlet_tax_slabs){
                                foreach(json_decode($outlet_tax_slabs) as $o_tax=>$tax_slab) {
                                    $excel_data[$n][$o_tax." Taxable Rs"] = "";
                                    $excel_data[$n][$o_tax." Tax Rs"] = "";
                                }
                            }
                        } else {
                            $tax_type = $ord->tax_type;
                            if($tax_type != ""){
                                foreach(json_decode($tax_type) as $taxObj) {
                                    foreach($taxObj as $key=>$tax) {
                                        $excel_data[$n][$key." %"] = "";
                                        $excel_data[$n][$key." Rs"] = "";
                                    } 
                                }
                            }
                        }
                    }
                }
                $excel_name = 'Detail_excel_Report_of_'.$from."_to_".$to;
                Excel::create($excel_name, function($excel) use($excel_data) {
                    $excel->sheet('Sheet1', function($sheet) use($excel_data) {
                        $sheet->setOrientation('landscape');
                        $sheet->fromArray($excel_data);
                        //$sheet->setAutoSize(true);
                    });
                    $excel->getActiveSheet()->cells('A1:'.$excel->getActiveSheet()->getHighestColumn()."1",function ($cells){
                        $cells->setBackground('#E04833');
                        $cells->setFontColor('#ffffff');
                    });
        //                    $excel->getActiveSheet()->cells('L1:'.$excel->getActiveSheet()->getHighestColumn().$excel->getActiveSheet()->getHighestRow(), function ($cells){
        //                        $cells->setAlignment('right');
        //                    });
                    $excel->getActiveSheet()->cells('A'.$excel->getActiveSheet()->getHighestRow().':'.$excel->getActiveSheet()->getHighestColumn().$excel->getActiveSheet()->getHighestRow(), function ($cells){
                        $cells->setBackground('#BEFF33');
                        $cells->setFontWeight('bold');
                    });
                    // $excel->getActiveSheet()->cells($excel->getActiveSheet()->getHighestColumn().'2:'.$excel->getActiveSheet()->getHighestColumn().$excel->getActiveSheet()->getHighestRow(), function ($cells){
                    //     $cells->setBackground('#BEFF33');
                    //     $cells->setFontWeight('bold');
                    // });
                    $excel->getActiveSheet()->getStyle('E1:E'.$excel->getActiveSheet()->getHighestRow())
                        ->getAlignment()->setWrapText(true);
                    foreach($excel->getActiveSheet()->getRowDimensions() as $rd) {
                        $rd->setRowHeight(-1);
                    }
                })->download('xls');
            }
            $reasons = ['' => 'Select Reason'];
            $reasons_arr = CancellationReason::where('outlet_id',$outlet_id)->lists('reason_of_cancellation','reason_of_cancellation');
            if ( isset($reasons_arr) && sizeof($reasons_arr) > 0 ) {
                foreach( $reasons_arr as $id=>$res ) {
                    $reasons[$id] = $res;
                }
            }
            $allow_order_delete = Owner::join('accounts','accounts.id',"=","owners.account_id")
                ->select("allow_order_delete")->where("owners.id",Auth::id())->first();
            if($allow_order_delete->allow_order_delete){
                $delete_order = 1;
            }else{
                $delete_order = 0;
            }
            $data['reasons']=$reasons;
            $data['custom_fields'] = json_decode($outlet->custom_bill_print_fields);

            return view('report.detailed_report',array('custom_fields'=>$data['custom_fields'],'total_orders'=> $data['total_orders'],'delete_order'=>$delete_order,'flag'=>'order_report','order_date_field'=>$order_date_field,'from_time'=>$from_time,'to_time'=>$to_time,'orders'=>$data['orders'],'itemlist'=>$data['itemlist'],'reasons'=>$reasons));
        }
        $outlets = OutletMapper::getOutletsByOwnerId();
        if ( sizeof($outlets) == 2 ) {
            unset($outlets['']);
        }
        $day = Input::get('day');
        $month = Input::get('month');
        if(isset($day) && sizeof($day)>0){
            $start_date = date('Y-m-d', strtotime(-$day . " days"));
            $end_date = date('Y-m-d', strtotime(-$day . " days"));
        }else if(isset($month) && sizeof($month)>0){
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-'.$month);
        }else{
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        }
        // echo "laravel 5.1.11 py_mode MODES IF <pre>" ; print_r($outlets); echo "</pre>"; exit;

        return view('orderlist.orders',array('outlets'=>$outlets,'start_date'=>$start_date,'end_date'=>$end_date));
        //return view('orderlist.orders',array('outlets'=>$outlets));
    }
    public function ongoingOrders(Request $request) {
        if ( $request->ajax() )
        {
            $ot_id = Input::get('outlet_id');
            $orders = array();
            if ( $ot_id == 'all' ) {
                $orders = order_details::join('outlets','outlets.id','=','orders.outlet_id')
                    ->join("order_items","order_items.order_id","=","orders.order_id")
                    ->select('orders.*','order_items.item_quantity as Quantity','order_items.item_name as item_name','outlets.name as ot_name','outlets.contact_no as ot_phone','outlets.address as ot_address')
                    ->where('orders.status', '!=', 'delivered')
                    ->where('orders.cancelorder', '!=', 1)
                    ->where('outlets.active','Yes')
                    ->whereDate('orders.created_at', '=',  date('Y-m-d'))
                    ->orderBy('orders.created_at', 'desc')
                    ->get();
            } else {
                $orders = order_details::join('outlets','outlets.id','=','orders.outlet_id')
                                ->join("order_items","order_items.order_id","=","orders.order_id")
                                ->select('orders.*','order_items.item_quantity as Quantity','order_items.item_name as item_name','outlets.name as ot_name','outlets.contact_no as ot_phone','outlets.address as ot_address')
                                ->where('outlets.active','Yes')
                                ->where('orders.status', '!=', 'delivered')
                                ->where('orders.cancelorder', '!=', 1)
                                ->where('outlets.id',$ot_id)
                                ->whereDate('orders.created_at', '=', date('Y-m-d'))
                                ->orderBy('orders.created_at', 'desc')->get();
            }
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
            return view('orderlist.ongoingOrdersList',array('orders'=>$data['orders'],'itemlist'=>$data['itemlist']));
        }
        $outlets = Outlet::where('active','Yes')->lists('name','id');
        return view('orderlist.ongoingOrders',array('outlets'=>$outlets));
    }
    public function ongoingTables(Request $request) {
        if ( $request->ajax() )
        {
            $outlet_id = Input::get('outlet_id');
            $sess_outlet_id = Session::get('outlet_session');
            if (isset($sess_outlet_id) && $sess_outlet_id != '') {
                $outlet_id = $sess_outlet_id;
            }
            $current_date = date('Y-m-d');
            if ( date('H') < 4 ) {
                $current_date = date('Y-m-d', strtotime($current_date .' -1 day'));
            }
            $from = Utils::getSessionTime($current_date,'from');
            $to = Utils::getSessionTime($current_date,'to');
            $kots = Kot::join('owners','owners.id','=','kot.created_by')
                ->select('kot.*','owners.user_name as username')
                ->where('outlet_id',$outlet_id)
                ->where('kot.updated_at','>=', $from)
                ->where('kot.updated_at','<=', $to)
                ->where('kot.status','open')
                ->get();
            $kt_arr = array();$data['orders'] = array();$data['kot'] = array();$data['total_kot'] = 0;
            if ( isset($kots) && sizeof($kots) > 0 ) {
                $i = 0;
                foreach( $kots as $kt ) {
                    if (array_key_exists($kt->order_unique_id,$kt_arr)) {
                        if($kt->quantity>1){
                            $kt_arr[$kt->order_unique_id]['item'] = $kt_arr[$kt->order_unique_id]['item'] . ",</br>" .$kt->quantity." x ".strtoupper($kt->item_name);
                        }else {
                            $kt_arr[$kt->order_unique_id]['item'] = $kt_arr[$kt->order_unique_id]['item'] . ",</br>" ."1 x ".strtoupper($kt->item_name);
                        }
                        $kt_arr[$kt->order_unique_id]['price'] += $kt->price * $kt->quantity;
                    } else {
                        if($kt->quantity>1){
                            $kt_arr[$kt->order_unique_id]['item'] = $kt->quantity." x ".strtoupper($kt->item_name);
                        }else {
                            $kt_arr[$kt->order_unique_id]['item'] = "1 x ".strtoupper($kt->item_name);
                        }
                        $kt_arr[$kt->order_unique_id]['price'] = $kt->price * $kt->quantity;
                        array_push($data['orders'],$kt);
                    }
                    $i++;
                }
                $data['kot'] = $kt_arr;
                $data['total_kot'] = $i;
            }
            return view('orderlist.ongoingTablesList',array('orders'=>$data['orders'],'itemlist'=>$data['kot'], 'total_kot'=>$data['total_kot']));
        }
        $outlets = OutletMapper::getOutletsByOwnerId();
        if ( sizeof($outlets) == 2 ) {
            unset($outlets['']);
        }
        return view('orderlist.ongoingTables',array('outlets'=>$outlets));
    }
    public function addOrder() {
        $owner_id = Auth::id();
        $sess_outlet_id = Session::get('outlet_session');
        $outlet_id = '';$outlet = '';
        if (isset($sess_outlet_id) && $sess_outlet_id != '') {
            $outlet_id = $sess_outlet_id;
        }
        $menu = $category = '';
        if( $outlet_id != '') {
            //menu
            $menu = Menu::geOutletMenu($outlet_id);
            $outlet = Outlet::find($outlet_id);
        }
        $custom_fields = json_decode($outlet->custom_bill_print_fields);
        $attributes = ItemAttribute::join('outlet_item_attributes_mapper','outlet_item_attributes_mapper.attribute_id','=','item_attributes.id')
                                    ->where('created_by',$owner_id)
                                    ->where('outlet_item_attributes_mapper.outlet_id',$outlet_id)
                                    ->select('item_attributes.name', 'item_attributes.id')->get();

        return view('orderlist.neworder',array('attributes'=>$attributes, 'custom_fields'=>$custom_fields, 'menu'=>$menu, 'outlet_id'=>$outlet_id, 'outlet'=>$outlet));
    }
    // configure for udupihome 
    public function addudpOrder($tno,$no_of_person) {
        $owner_id = Auth::id();
        $sess_outlet_id = Session::get('outlet_session');
        $outlet_id = '';$outlet = '';
        if (isset($sess_outlet_id) && $sess_outlet_id != '') {
            $outlet_id = $sess_outlet_id;
        }
        $menu = $category = '';
        if( $outlet_id != '') {
            //menu
            // $menu = Menu::geOutletMenu($outlet_id);
            $is_first_menu = 0;
        $menu=MenuTitle::select('menus.*','menu_titles.title')
            ->join("outlet_menu_bind","outlet_menu_bind.menu_id","=","menu_titles.id")
            ->join("menus","menus.id","=","outlet_menu_bind.item_id")
            ->where('outlet_menu_bind.outlet_id',$outlet_id)
            ->whereNull('menus.deleted_at')
            ->groupby("menus.id")
            ->orderBy('menu_titles.title_order', 'asc')
            ->orderBy('menus.item_order', 'asc')
            ->orderBy('menus.item', 'asc')
            ->get();
        $a=array();
        foreach($menu as $m) {
            $cuisine=CuisineType::find($m->cuisine_type_id);
            #TODO: check item is_sale and active outletwise
            $is_sale = 1;$active = 0;
            $itm_setting_arr = ItemSettings::where('outlet_id',$outlet_id)->where('item_id',$m->id)->first();
            if( isset($itm_setting_arr) && !empty($itm_setting_arr) ) {
                if ( $itm_setting_arr->is_sale == 0 || $itm_setting_arr->is_active == 1 ) {
                    continue;
                }
            }
            $menuitem_options = ItemOptionGroup::getItemGroupOption($m->id);
            $inner_array=array('item_id'=>$m->id,
                'item'=>$m->item,
                'alias'=>$m->alias,
                'unit_id'=>$m->unit_id,
                'price'=>number_format($m->price,2,'.',''),
                'details'=>$m->details,
                'taxable'=>$m->taxable,
                'discountable'=>$m->discountable,
                'cuisinetype'=>$cuisine,
                'options'=>$m->options,
                'foodtype'=>$m->food,
                'active'=>$active,
                'is_sale'=>$is_sale,
                'like'=>$m->like,
                'menu_title'=>$m->title,
                'item_options'=>$menuitem_options
            );
            if(!array_key_exists($m->title,$a)) {
                if(sizeof($a) >= 1) {
                    $a[$m->title][] = [];
                } else {   
                    $a[$m->title][] = $inner_array;
                }
            } else {
                if(sizeof($a) == 1) {
                    array_push($a[$m->title],$inner_array);
                }
            }
        }
            $outlet = Outlet::find($outlet_id);
        }
        $custom_fields = json_decode($outlet->custom_bill_print_fields);
        $attributes = ItemAttribute::join('outlet_item_attributes_mapper','outlet_item_attributes_mapper.attribute_id','=','item_attributes.id')
                                    ->where('created_by',$owner_id)
                                    ->where('outlet_item_attributes_mapper.outlet_id',$outlet_id)
                                    ->select('item_attributes.name', 'item_attributes.id')->get();
        return view('orderlist.newudupiporder',array('attributes'=>$attributes,'menu_12'=>0, 'custom_fields'=>$custom_fields, 'menu'=>$a, 'outlet_id'=>$outlet_id, 'outlet'=>$outlet, 'tableno'=>$tno , 'no_of_person'=>$no_of_person));
    }
    public function getrequest(Request $request){
        $owner_id = Auth::id();
        $category = $request->get('key');
        $pageno = $request->get('pageno');
        $search_txt = $request->get('search_txt');
        $sess_outlet_id = Session::get('outlet_session');
        $outlet_id = '';$outlet = '';
        if (isset($sess_outlet_id) && $sess_outlet_id != '') {
            $outlet_id = $sess_outlet_id;
        }
        $getId = MenuTitle::where('title', $category)->join("outlet_menu_bind","outlet_menu_bind.menu_id","=","menu_titles.id")->where('outlet_menu_bind.outlet_id',$outlet_id)->select('menu_titles.id as id')->first();
        $menu = $category = '';
        if( $outlet_id != '') {
        $page = $request->has('pageno') ? $request->get('pageno') : 1;
        $limit = $request->has('limit') ? $request->get('limit') : 35;
            $menu=MenuTitle::select('menus.*','menu_titles.title')
                    ->join("outlet_menu_bind","outlet_menu_bind.menu_id","=","menu_titles.id")
                    ->join("menus","menus.id","=","outlet_menu_bind.item_id")
                    // ->join("item_settings","menus.id","=","outlet_menu_bind.item_id")
                    // ->where('menu_titles.title', 'like', '%' . $category . '%')
                    ->where('outlet_menu_bind.outlet_id',$outlet_id)
                    ->whereNull('menus.deleted_at')
                    ->groupby("menus.id")
                    ->orderBy('menus.item_order', 'asc')
                    ->orderBy('menus.item', 'asc')
                    ->offset(($page - 1) * $limit)->limit($limit);
                    if ($search_txt) {
                      $menu = $menu->where('menus.item', 'LIKE', '%' . $search_txt . '%');
                    }
                    if ($getId) {
                        $menu = $menu->where('menu_titles.id',$getId->id);
                    }
                    $menu = $menu->get();
        $a=array();
        foreach($menu as $m) {
            $cuisine=CuisineType::find($m->cuisine_type_id);
            #TODO: check item is_sale and active outletwise
            $is_sale = 1;$active = 0;
            $itm_setting_arr = ItemSettings::where('outlet_id',$outlet_id)->where('item_id',$m->id)->first();
            if( isset($itm_setting_arr) && !empty($itm_setting_arr) ) {
                if ( $itm_setting_arr->is_sale == 0 || $itm_setting_arr->is_active == 1 ) {
                    continue;
                }
            }
            $menuitem_options = ItemOptionGroup::getItemGroupOption($m->id);
            $inner_array=array('item_id'=>$m->id,
                'item'=>$m->item,
                'alias'=>$m->alias,
                'unit_id'=>$m->unit_id,
                'price'=>number_format($m->price,2,'.',''),
                'details'=>$m->details,
                'taxable'=>$m->taxable,
                'discountable'=>$m->discountable,
                'cuisinetype'=>$cuisine,
                'options'=>$m->options,
                'foodtype'=>$m->food,
                'active'=>$active,
                'is_sale'=>$is_sale,
                'like'=>$m->like,
                'menu_title'=>$m->title,
                'item_options'=>$menuitem_options
            );
            if(!array_key_exists($m->title,$a)) {
                $a[$m->title][] = $inner_array;
            } else {
                array_push($a[$m->title],$inner_array);
            }
        }
            $outlet = Outlet::find($outlet_id);
        }
        $custom_fields = json_decode($outlet->custom_bill_print_fields);
        $attributes = ItemAttribute::join('outlet_item_attributes_mapper','outlet_item_attributes_mapper.attribute_id','=','item_attributes.id')
                                    ->where('created_by',$owner_id)
                                    ->where('outlet_item_attributes_mapper.outlet_id',$outlet_id)
                                    ->select('item_attributes.name', 'item_attributes.id')->get();
        return view('orderlist.itemsPartial',array('attributes'=>$attributes,'custom_fields'=>$custom_fields, 'menu'=>$a, 'outlet_id'=>$outlet_id, 'outlet'=>$outlet));
    }   
    public function placeOrder( $order = Null ) {
        $sess_outlet_id = Session::get('outlet_session');
        $person_no = $email = $table_no = $ref_id = $name = $cust_name = '';
        $source_id = $payment_option_id = $delivery_charge = $total = 0;
        if( isset($order) && sizeof($order)  > 0 ) {
            $order = $order['order'];
            $outlet_id = $order['outlet_id'];
            $item_attribute = $order['item_attribute'];
            $item_id = $order['item_id'];
            $item_qty = $order['item_qty'];
            $item_name = $order['item_name'];
            $item_price = $order['item_price'];
            $order_date = $order['order_date'];
            $mobile = $order['mobile'];
            $address = $order['address'];
            $order_type = $order['order_type'];
            $paid_type = $order['paid_type'];
            $delivery_charge = $order['delivery_charge'];
            $payment_option_id = $order['payment_option_id'];
            $source_id = $order['source_id'];
            $total = $order['total_price'];
            $table_no = $order['table'];
            $ref_id = $order['ref_id'];
            $cust_name = $order['customer_name'];
        } else {
            $outlet_id = $sess_outlet_id;
            $name = Auth::user()->user_name;
            $item_id = Input::get('item_id');
            $item_qty = Input::get('item_qty');
            $item_name = Input::get('item_name');
            $item_price = Input::get('item_price');
            $item_options = Input::get('item_options');
            $item_attribute = Input::get('item_attribute');
            $order_date = Input::get('order_date');
            $table_no = Input::get('table_no');
            $person_no = Input::get('person_no');
            $order_type = Input::get('order_type');
            $mobile = Input::get('mobile');
            $address = Input::get('address');
            $cust_name = Input::get('name');
            $paid_type = 'cash';
            if ( isset($item_id) && sizeof($item_id) > 0 ) {
                for( $i=0; $i<sizeof($item_id); $i++ ) {
                    $qty = $item_qty[$i];
                    $price = floatval($item_price[$i]);
                    //check item options has been selected for this item or not
                    if ( isset($item_options[$i]) && sizeof($item_options[$i]) > 0 ) {
                        foreach ( $item_options[$i] as $opt ) {
                            $total += $opt['option_price'] * $qty;
                        }
                    }
                    $total += $qty * $price;
                }
            }
        }
        $customer_id = NULL;
        //check customer available or not
        if ( $mobile != '' || $cust_name != '' ) {
            $check_customer = Customer::where('mobile_number',$mobile)->where('mobile_number','!=',0)->first();
            if (  isset($check_customer) && isset($check_customer->id) ) {
                $check_customer->address = $address;
                $check_customer->email = $email;
                $check_customer->first_name = $cust_name;
                $check_customer->updated_at = date('Y-m-d H:i:s');
                $check_customer->save();
                $customer_id = $check_customer->id;
            } else {
                $customer_obj = new Customer();
                $customer_obj->first_name = $cust_name;
                $customer_obj->address = $address;
                $customer_obj->mobile_number = $mobile;
                $customer_obj->created_at = date('Y-m-d H:i:s');
                $customer_obj->updated_at = date('Y-m-d H:i:s');
                $cust_result = $customer_obj->save();
                if ( $cust_result ) {
                    $customer_id = $customer_obj->id;
                }
            }
        }
        if( $outlet_id != '') {
            $startingstatus = status::getallstatusofOutlet($outlet_id);
            $lastindex = count($startingstatus) - 1;
            if (isset($startingstatus)) {
                $status = $startingstatus[$lastindex]->status;
            } else {
                $status = '';
            }
            $itemWiseTax = OutletSetting::checkAppSetting($outlet_id,"itemWiseTax");
            $itemWiseDiscount = OutletSetting::checkAppSetting($outlet_id,"itemWiseDiscount");
            $discAfterTax = OutletSetting::checkAppSetting($outlet_id,"discountAfterTax");
            $guid = order_details::guid();
            DB::beginTransaction();
            $order_details = new order_details();
            $order_details->name = $name;
            $order_details->user_id = $customer_id;
            $order_details->outlet_id = $outlet_id;
            $order_details->status = 'delivered';
            $order_details->order_type = $order_type;
            $order_details->table_no = isset($table_no)?$table_no:0;
            $order_details->person_no = isset($person_no)?$person_no:0;
            $order_details->totalprice = $total;
            $order_details->paid_type = $paid_type;
            $order_details->source_id = $source_id;
            $order_details->payment_option_id = $payment_option_id;
            $order_details->totalcost_afterdiscount = $total;
            $order_details->suborder_id = 0;
            $order_details->order_unique_id = $guid;
            $order_details->table_start_date= $order_date;
            $order_details->table_end_date= $order_date;
            $order_details->payment_status = 0;
            $order_details->user_mobile_number = isset($mobile)?$mobile:"";
            $order_details->address = isset($address)?$address:"";
            $order_details->customer_order = 0;
            $order_details->delivery_charge = $delivery_charge;
            $order_details->referance_id = $ref_id;
            $order_details->cancelorder = "0";
            $order_details->itemwise_tax = $itemWiseTax;
            $order_details->itemwise_discount = $itemWiseDiscount;
            $order_details->add_discount_after_tax = $discAfterTax;
            $result = $order_details->save();
            if ( $result ) {
                $order_id = $order_details->order_id;
                for ( $i=0;$i<sizeof($item_id);$i++ ) {
                    $itm_uq_id = order_details::guid();
                    $orderitems = new OrderItem();
                    $orderitems->order_id = $order_id;
                    $orderitems->item_quantity = $item_qty[$i];
                    $orderitems->item_unique_id = $itm_uq_id;
                    $orderitems->item_id = $item_id[$i];
                    $orderitems->item_name = $item_name[$i];
                    $orderitems->item_price = $item_price[$i];
                    $orderitems->item_total = floatval($item_price[$i]) * intval($item_qty[$i]);
                    $actual_item_price = floatval($item_price[$i]) * intval($item_qty[$i]);
                    //add option price in main price to calculate tax
                    if ( isset($item_options[$i]) && sizeof($item_options[$i]) > 0 ) {
                        foreach ( $item_options[$i] as $opt ) {
                            $actual_item_price += $opt['option_price'] * intval($item_qty[$i]);
                        }
                    }
                    $itm_tax = array();$disc_arr = array();
                    if ( $item_id[$i] != 0 ) {
                        $menu = Menu::where('menus.id', $item_id[$i])
                            ->join('menu_titles', 'menu_titles.id', '=', 'menus.menu_title_id')
                            ->select('menus.id as id','menus.tax_slab as tax_slab','menus.discount_type as discount_type','menus.discount_value as discount_value', 'menus.price as price', 'menus.item as item', 'menus.menu_title_id as cat_id', 'menu_titles.title as category')->first();
                        $orderitems->category_id = $menu->cat_id;
                        $orderitems->category_name = $menu->category;
                        if( $itemWiseTax || $itemWiseDiscount ) {
                            //$item_obj = Menu::find($item_id[$i]);
                            if ( $itemWiseDiscount ) {
                                //check if discount calculate before tax or not
                                if ( $discAfterTax == 0 ) {
                                    if ( $menu->discount_value > 0 ) {
                                        $disc_arr['disc_type'] = $menu->discount_type;
                                        $disc_arr['disc_value'] = $menu->discount_value;
                                        if ( $menu->discount_type == 'fixed' ) {
                                            $disc_arr['disc_calc_amount'] = $menu->discount_value * intval($item_qty[$i]);
                                            $actual_item_price = $actual_item_price - $disc_arr['disc_calc_amount'];
                                        } else {
                                            $disc_value = $actual_item_price * $menu->discount_value / 100;
                                            $disc_arr['disc_calc_amount'] = $disc_value;
                                            $actual_item_price = $actual_item_price - $disc_value;
                                        }
                                    }
                                }
                            }
                            //check if slab is set or not
                            if ( $itemWiseTax ) {
                                if ( isset($menu->tax_slab) && $menu->tax_slab != '') {
                                    $outlet_obj = Outlet::find($outlet_id);
                                    //outlet taxes
                                    $taxes = json_decode($outlet_obj->taxes);
                                    //total calculated item tax
                                    $total_calc_tax = 0;
                                    if ( isset($taxes) && sizeof($taxes) > 0 ) {
                                        foreach ( $taxes as $t_key=>$t_val ) {
                                            if ( $menu->tax_slab == $t_key ) {
                                                $itm_tax[$t_key] = [];
                                                foreach ( $t_val as $tx ) {
                                                    $cal_tax = $actual_item_price * floatval($tx->taxparc) / 100;
                                                    $slab['taxname'] = $tx->taxname;
                                                    $slab['taxparc'] = $tx->taxparc;
                                                    $slab['value'] = $cal_tax;
                                                    array_push($itm_tax[$t_key],$slab);
                                                    $total_calc_tax += $cal_tax;
                                                }
                                            }
                                        }
                                    }
                                    $actual_item_price += $total_calc_tax;
                                }
                            }
                            if ( $itemWiseDiscount ) {
                                //check if discount calculate before tax or not
                                if ( $discAfterTax == 1 ) {
                                    if ( $menu->discount_value > 0 ) {
                                        $disc_arr['disc_type'] = $menu->discount_type;
                                        $disc_arr['disc_value'] = $menu->discount_value;
                                        if ( $menu->discount_type == 'fixed' ) {
                                            $disc_arr['disc_calc_amount'] = $menu->discount_value * intval($item_qty[$i]);
                                        } else {
                                            $disc_value = $actual_item_price * $menu->discount_value / 100;
                                            $disc_arr['disc_calc_amount'] = $disc_value;
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $orderitems->category_id = NULL;
                        $orderitems->category_name = NULL;
                    }
                    //if itemwise tax available
                    if ( sizeof($itm_tax) > 0 ) {
                        $orderitems->tax_slab = json_encode($itm_tax);
                    }
                    //if itemwise discount is enabled
                    if ( sizeof($disc_arr) > 0 ) {
                        $orderitems->item_discount = json_encode($disc_arr);
                    }
                    $result2 = $orderitems->save();
                    if ( !$result2 ) {
                        DB::rollBack();
                        return 'error';
                    } else {
                        //check item options has been selected for this item or not
                        if ( isset($item_options[$i]) && sizeof($item_options[$i]) > 0 ) {
                            foreach ( $item_options[$i] as $opt ) {
                                $option = new OrderItemOption();
                                $option->order_id = $order_id;
                                $option->order_item_id = $orderitems->id;
                                $option->option_item_id = $opt['option_id'];
                                $option->qty = $item_qty[$i];
                                $option->option_item_price = $opt['option_price'];
                                $opt_result = $option->save();
                                if ( !$opt_result ) {
                                    DB::rollBack();
                                    return 'error';
                                }
                            }
                        }
                        //check item attribute has been selected for this item or not
                        if( isset($item_attribute[$i]) && sizeof($item_attribute[$i]) > 0 ) {
                            if( isset($order) && sizeof($order)  > 0 ) {
                                foreach ( $item_attribute[$i] as $attr ) {
                                    $check_attribute = ItemAttribute::join('outlet_item_attributes_mapper as oa','item_attributes.id','=','oa.attribute_id')
                                                                    ->select('item_attributes.*')
                                                                    ->where('oa.outlet_id',$outlet_id)
                                                                    ->where(DB::raw('LOWER(item_attributes.name)'),strtolower($attr))
                                                                    ->first();
                                    if ( isset($check_attribute) && sizeof($check_attribute) > 0 ) {
                                        $oia = new order_item_attributes();
                                        $oia->order_item_id = $orderitems->id;
                                        $oia->attribute_id = $check_attribute->id;
                                        $oia->attribute_name = $check_attribute->name;
                                        $oia->save();
                                    } else {
                                        $add_attr = new ItemAttribute();
                                        $add_attr->name = $attr;
                                        $add_attr->created_by = 0;
                                        $add_attr->updated_by = 0;
                                        $add_attr_result = $add_attr->save();
                                        if ( $add_attr_result ) {
                                            $add_ot_attr_mapper = new OutletItemAttributesMapper();
                                            $add_ot_attr_mapper->attribute_id = $add_attr->id;
                                            $add_ot_attr_mapper->outlet_id = $outlet_id;
                                            $add_ot_attr_mapper->save();
                                            //add order item attribute
                                            $oia = new order_item_attributes();
                                            $oia->order_item_id = $orderitems->id;
                                            $oia->attribute_id = $add_attr->id;
                                            $oia->attribute_name = $add_attr->name;
                                            $oia->save();
                                        }
                                    }
                                }
                            } else {
                                $attr_ids = explode(",",$item_attribute[$i][0]['attr_id']);
                                foreach ($attr_ids as $attr_id) {
                                    $attrname = ItemAttribute::find($attr_id);
                                    //order item attribute add
                                    $oia = new order_item_attributes();
                                    $oia->order_item_id = $orderitems->id;
                                    $oia->attribute_id = $attr_id;
                                    $oia->attribute_name = isset($attrname->name)?$attrname->name:"";
                                    $oia->save();
                                }
                            }
                        }
                    }
                }
                $py_modes = new OrderPaymentMode();
                $py_modes->order_id = $order_id;
                $py_modes->payment_option_id = 0;
                $py_modes->source_id = 0;
                $py_modes->amount = $total;
                $py_modes->save();
                $histroy = new OrderHistory();
                $histroy->order_id = $order_id;
                $histroy->owner = $name;
                $histroy->order_type = $order_type;
                $histroy->total = $total;
                $histroy->sub_total = $total;
                $histroy->user_mobile_no = $mobile;
                $histroy->address = $address;
                $histroy->delivery_charge = $delivery_charge;
                $histroy->save();
                //sent order to firebase to display in partner app
                $data['message'] = 'success';
                if ( $order_type != 'dine_in' ) {
                    $fields = array();
                    $fields['outlet_id'] = $outlet_id;
                    $fields['server_id'] = "$order_id";
                    $fields['order_type'] = $order_type;
                    $fields['order_unique_id'] = $guid;
                    $fields['table_no'] = $table_no;
                    $fields['action'] = 'OnlineOrder';
                    Utils::sendOrderToFirebase($fields);
                    //print_r($data);exit;
                }
                DB::commit();
                if ( $data == 'error') {
                    $data['message'] = 'firebase error';
                    $data['order_id'] = $order_id;
                    return $data;
                } else if ( $data == 'no user' ) {
                    $data['message'] = 'order taker error';
                    $data['order_id'] = $order_id;
                    return $data;
                }
                $data['message'] = 'success';
                $data['order_id'] = $order_id;
                return $data;
            } else {
                DB::rollBack();
                $data['message'] ='error';
                return $data;
            }
        }
    }
    public function orderHistory() {
        $order_id = Input::get('order_id');
        $history = OrderHistory::where('order_id',$order_id)->orderBy('updated_at')->get();
        $history_arr = array();
        if ( isset($history) && sizeof($history) > 0 ) {
            $i = 0;
            foreach ( $history as $hist ) {
                $history_arr[$i]['invoice_no'] = $hist->invoice_no;
                $history_arr[$i]['mobile'] = $hist->user_mobile_no;
                $history_arr[$i]['address'] = $hist->address;
                $history_arr[$i]['order_type'] = $hist->order_type;
                $history_arr[$i]['sub_total'] = $hist->sub_total;
                $history_arr[$i]['discount'] = $hist->discount;
                $history_arr[$i]['taxes'] = "";
                if( isset($hist->taxes) && $hist->taxes != '') {
                    $taxes = json_decode($hist->taxes,true);
                    foreach( $taxes as $tx ) {
                        if(gettype($tx) == 'string') {
                            $tx1 = json_decode($tx);
                        } else {
                            $tx1 = $tx;
                        }
                        foreach( $tx1 as $key1=>$t) {
                            $history_arr[$i]['taxes'] .= ucwords($key1)." ".$t['percent']."%";
                            $history_arr[$i]['taxes'] .= "   &#8377;".number_format($t['calc_tax'],2)."<br>";
                        }
                    }
                }
                $history_arr[$i]['round_off'] = $hist->round_off;
                $history_arr[$i]['total'] = $hist->total;
                if(isset($hist->payment_modes) && sizeof($hist->payment_modes)>0) {
                    $history_arr[$i]['payment_modes'] = $hist->payment_modes;
                } else {
                    $history_arr[$i]['payment_modes'] = 'Not set';
                }
                $history_arr[$i]['delivery_charge'] = $hist->delivery_charge;
                $history_arr[$i]['updated_at'] = $hist->updated_at;
                $history_arr[$i]['updated_by'] = $hist->owner;
                $i++;
            }
        }
        return view('orderlist.orderHistory',array('history'=>$history_arr));
    }
    public function payWithUpi() {
        $vpa = Input::get('vpa');
        $order_id = Input::get('order_id');
        $total = Input::get('total');
        $order = order_details::join('outlets as o','orders.outlet_id','=','o.id')->select('orders.*','o.name as ot_name')->where('orders.order_id',$order_id)->first();
        if ( isset($order) && sizeof($order) > 0 ) {
            $param = array(
                'amount'=>$total,
                'subMerchantName'=>$order->ot_name,
                'subMerchantId'=>$order->outlet_id,
                'payerVa'=>$vpa,
                'note'=>$total." for restaurant",
                'billNumber'=>$order->order_unique_id
            );
            $httpclient = new HttpClientWrapper();
            // $url = 'https://' . $_SERVER['SERVER_NAME'] . '/api/v3/tables-list';
            //send Api call
            $order_status = $httpclient->send_request('POST',$param,'https://staging.foodklub.in/pgi/icici-upi/collect-pay');
            return $order_status;
        } else {
            return json_encode(array('success'=>false,'status'=>'2','error_msg'=>'Order not found'));
        }
    }
    public function upiPaymentStatus() {
        $order_id = Input::get('order_id');
        $txn_id = Input::get('txn_id');
        $order = order_details::join('outlets as o','orders.outlet_id','=','o.id')
            ->select('orders.*','o.name as ot_name')
            ->where('orders.order_id',$order_id)->first();
        if ( isset($order) && sizeof($order) > 0 ) {
            $param = array(
                'subMerchantName'=>$order->ot_name,
                'subMerchantId'=>$order->outlet_id,
                'txnId'=>$txn_id,
                'billNumber'=>$order->order_unique_id
            );
            $httpclient = new HttpClientWrapper();
            //send Api call
            $order_status = $httpclient->send_request('POST',$param,'http://staging.foodklub.in/pgi/icici-upi/transaction-status');
            return $order_status;
        } else {
            return json_encode(array('success'=>false,'status'=>'2','error_msg'=>'Order not found'));
        }
    }
    public function paidOrder() {
        $order_id = Input::get('order_id');
        $note = Input::get('note');
        $payment_option_id = Input::get('payment_option_id');
        $source = Input::get('source');
        $source_id_arr = Input::get('source_ids');
        $payment_opt_ids = Input::get('payment_option_ids');
        $payment_mode_amounts = Input::get('payment_mode_amount');
        $trn_ids = Input::get('trn_ids');
        $result = order_details::where('order_id',$order_id)
                                ->update([
                                        'note'=>$note,
                                        'source_id'=>$source,
                                        'payment_option_id'=>$payment_option_id
                                        ]);
        //update payment modes
        if ( isset($payment_opt_ids) && sizeof($payment_opt_ids) > 0 ) {
            //remove old payment modes
            OrderPaymentMode::where('order_id',$order_id)->delete();
            for ( $i=0; $i < sizeof($payment_opt_ids); $i++ ) {
                $py_modes = new OrderPaymentMode();
                $py_modes->order_id = $order_id;
                $py_modes->payment_option_id = $payment_opt_ids[$i];
                $py_modes->source_id = $source_id_arr[$i];
                $py_modes->transaction_id = $trn_ids[$i];
                $py_modes->amount = $payment_mode_amounts[$i];
                $py_modes->save();
            }
        }
        if ( $result ) {
            return 'success';
        } else {
            return 'error';
        }
    }
    #TODO: get order payment modes
    public function getOrderPaymentModes() {
        $outlet_id = Session::get('outlet_session');
        $order_id = Input::get('order_id');
        $flag = Input::get('flag');
        //payment options
        $payment_options = PaymentOption::getOutletPaymentOption($outlet_id);
        $html = '';
        //payment modes
        $modes = OrderPaymentMode::where('order_id',$order_id)->get();
        $order_amount = order_details::where('order_id',$order_id)->pluck('totalprice');
        return view('orderlist.orderPaymentModes',array('payment_modes'=>$modes,'pay_option'=>$payment_options,'bill_amount'=>$order_amount));
    }
    #TODO: Bill layout
    public function calculateOrderDiscount() {
        $order_id = Input::get('order_id');
        $flag = Input::get('flag');
        $disc_type = Input::get('disc_type');
        $disc_val = Input::get('disc_val');
        $selected_tax = Input::get('selected_tax');
        $disc_mode = Input::get('disc_mode');
        $delivery_charge = Input::get('delivery_charge');
        $order_type = Input::get('order_type');
        //get order detail
        $order = order_details::where('order_id',$order_id)->join('outlets as ot','orders.outlet_id','=','ot.id')->select('orders.*','ot.invoice_prefix as invoice_prefix','ot.invoice_date as inv_date','ot.invoice_digit as inv_digit','ot.name as ot_name','ot.taxes as taxes','ot.default_taxes as default_taxes','ot.code as ot_code','ot.order_no_reset as order_reset','ot.payment_options as payment_options')->first();
            //get calculation block
        $html = "<div id='tax_calculation_div'>";
        \Log::info($order_id);
        \Log::info("place order id..........");	
        if ( $order->itemwise_tax ) {
            $html .= $this::itemWiseOrderCalculation( $order, $flag, $order_type, $delivery_charge,$disc_type, $disc_val, $disc_mode );
        } else {
            $html .= $this::orderCalculation($order,$order_type,$flag,$selected_tax, $delivery_charge, $disc_type, $disc_val, $disc_mode);
        }
        $html .= "</div>";
        return $html;
    }
    public function taxCalculation() {
        $from_date = Input::get("from_date");
        $to_date = Input::get("to_date");
        $outlet_id = Input::get("outlet_id");
        if($from_date == "" || $to_date == "" || $outlet_id == ""){
            print_r("Please enter required data");exit;
        }
        $orders_list = order_details::where("outlet_id",$outlet_id)->where("table_start_date",">=",$from_date." 00:00:00")->where("table_start_date","<=",$to_date." 23:59:59")->lists('order_id');
        $actual_item_price = 0;
        if(isset($orders_list) && !empty($orders_list)) {
            foreach ($orders_list as $order_id) {
                if (isset($order_id) && !empty($order_id)) {
                    $order = order_details::find($order_id);
                    $actual_item_price = 0;
                    if(isset($order) && !empty($order)) {
                        if ($order->itemwise_tax) {
                            $order_items = OrderItem::where('order_id', $order_id)->get();
                            $itm_tax = array();
                            $order_total_tax = 0;
                            if (isset($order_items) && !empty($order_items) ) {
                                foreach ($order_items as $items) {
                                    $menu = Menu::find($items->item_id);
                                    //                            ->join('menu_titles', 'menu_titles.id', '=', 'menus.menu_title_id')
                                    //                            ->select('menus.id as id','menus.tax_slab as tax_slab','menus.discount_type as discount_type',
                                    //                                'menus.discount_value as discount_value', 'menus.price as price', 'menus.item as item',
                                    //                                'menus.menu_title_id as cat_id', 'menu_titles.title as category')->first();
                                    $actual_item_price = (float)$items->item_price * (float)$items->item_quantity;
                                    if (isset($menu->tax_slab) && $menu->tax_slab != '') {
                                        $outlet_obj = Outlet::find($outlet_id);
                                        //outlet taxes
                                        $taxes = json_decode($outlet_obj->taxes);
                                        //total calculated item tax
                                        $total_calc_tax = 0;
                                        if (isset($taxes) && !empty($taxes)) {
                                            foreach ($taxes as $t_key => $t_val) {
                                                if ($menu->tax_slab == $t_key) {
                                                    $itm_tax[$t_key] = [];
                                                    foreach ($t_val as $tx) {
                                                        $cal_tax = $items->item_total * floatval($tx->taxparc) / 100;
                                                        $slab['taxname'] = $tx->taxname;
                                                        $slab['taxparc'] = $tx->taxparc;
                                                        $slab['value'] = $cal_tax;
                                                        array_push($itm_tax[$t_key], $slab);
                                                        $total_calc_tax += $cal_tax;
                                                        $order_total_tax += $cal_tax;
                                                    }
                                                }
                                            }
                                        }
                                        $actual_item_price += $total_calc_tax;
                                    }
                                    if (sizeof($itm_tax) > 0) {
                                        $order_item_obj = OrderItem::find($items->id);
                                        $order_item_obj->tax_slab = json_encode($itm_tax);
                                        $order_item_obj->save();
                                    }
                                }
                            }
                            $total_tax = array();
                            $total_tax["Total Tax"] = ["calc_tax" => $order_total_tax, "percent" => "0"];
                            $order->tax_type = "[" . json_encode($total_tax) . "]";
                            $order->save();
                            print_r($order_id." Tax Updated. ");
                        } else {
                            print_r($order_id."Order-id has not itemwise tax. ");
                        }
                    }
                }
            }
        }
        //[{"Total Tax":{"calc_tax":"3.68","percent":"0"}}]
    }
    public function resetInvoiceNo() {
        $invoice_no = Input::get("invoice_no");
        $outlet_id = Session::get('outlet_session');
        $orders = order_details::where('invoice_no',$invoice_no)->where('outlet_id',$outlet_id)->orderby('table_start_date')->first();
        if(isset($orders) && !empty($orders) ) {
            $outlet = Outlet::find($outlet_id);
            $last_orders = order_details::where('outlet_id',$outlet_id)->where('table_start_date','>=',$orders->table_start_date)->orderby('table_start_date')->get();
            // echo "Hello <pre>"; print_r($last_orders); echo "</pre>"; exit;
            // echo "prints" . $last_orders->count() ; exit;
            if(sizeof($last_orders) && $last_orders->count()>1) {
                $last_invoice_no = ($orders->invoice);
                $code = $outlet->code;
                $user_identifier = Auth::user()->user_identifier;
//                print_r($last_orders);exit;
                foreach ($last_orders as $order) {
                    if($orders->invoice == $last_invoice_no){
                        $last_invoice_no++;
                        continue;
                    }
                    $invoice_digit = $outlet->invoice_digit;
                    $new_invoice_number = $code.''.$user_identifier.''.str_pad($last_invoice_no,$invoice_digit,0,STR_PAD_LEFT);
                    $order->invoice = $last_invoice_no;
                    $order->invoice_no = $new_invoice_number;
                    $order->save();
                    $last_invoice_no++;
                }
                return json_encode(array('success'=>true,'status'=>'1','msg'=>'Invoice no updated successfully.'));
            } else {
                return json_encode(array('success'=>false,'status'=>'2','msg'=>'There is no orders after that invoice.'));
            }
        }else {
            return json_encode(array('success'=>false,'status'=>'2','msg'=>'Order not found'));
        }
    }
    public function generateInvoiceNumber($outlet, $ord_id, $type) {
        $user_identifier = Auth::user()->user_identifier;
        $order = order_details::where('order_id',$ord_id)->join('outlets as ot','orders.outlet_id','=','ot.id')
            ->select('orders.*','ot.invoice_date as inv_date','ot.invoice_prefix as invoice_prefix','ot.invoice_digit as inv_digit','ot.name as ot_name','ot.taxes as taxes','ot.default_taxes as default_taxes','ot.code as ot_code','ot.order_no_reset as order_reset')
            ->first();
        $condition = '1=1';
        $to = (new Carbon(date('Y-m-d')))->endOfDay();
        if ( OutletSetting::checkAppSetting($order->outlet_id,"orderNoReset") == 1) {
            $from = (new Carbon($order->table_start_date))->startOfDay();
            $condition = "orders.table_start_date BETWEEN '$from' AND '$to'";
        } else {
            $from = (new Carbon(date('Y-m-d',strtotime('-2 days'))))->startOfDay();
            $condition = "orders.table_start_date BETWEEN '$from' AND '$to'";
        }
        //check dinein prefix is set or not
        $code = $order->ot_code;
        $prefix_check = json_decode($order->invoice_prefix);
        if ( isset($prefix_check) && sizeof($prefix_check) > 0 ) {
            $condition .=" && order_type='$type'";
            $code = $prefix_check->$type;
        }
        //check last invoice no.
        $check_invoice_no = order_details::where('outlet_id',$order->outlet_id)
            ->whereRaw($condition)
            ->get();
        $inv_digit = $order->inv_digit;
        $invoice_number = "";
        if(isset($outlet->invoice_prefix) && trim($outlet->invoice_prefix) != "") {
            if (isset($check_invoice_no) && sizeof($check_invoice_no) > 0) {
                $max_id = 0;
                foreach ($check_invoice_no as $inv_record) {
                    $inv = $inv_record->invoice;
                    if ($inv > $max_id) {
                        $max_id = $inv;
                    }
                }
                if ($max_id != 0) {
                    $invoice_no = $max_id + 1;
                } else {
                    $invoice_no = 1;
                }
            } else {
                $invoice_no = 1;
            }
            $in_date = '';
            if (OutletSetting::checkAppSetting($order->outlet_id, "invoiceDate") == 1) {
                $in_date = date('Ymd', strtotime($order->table_start_date));
            }
            $invoice_number = $code.''.$in_date.''.$user_identifier.''.str_pad($invoice_no,$inv_digit,0,STR_PAD_LEFT);
        }else{
            if(trim($order->invoice_no) == ""){
                if (isset($check_invoice_no) && sizeof($check_invoice_no) > 0) {
                    $max_id = 0;
                    foreach ($check_invoice_no as $inv_record) {
                        $inv = $inv_record->invoice;
                        if ($inv > $max_id) {
                            $max_id = $inv;
                        }
                    }
                    if ($max_id != 0) {
                        $invoice_no = $max_id + 1;
                    } else {
                        $invoice_no = 1;
                    }
                } else {
                    $invoice_no = 1;
                }
                $in_date = '';
                if (OutletSetting::checkAppSetting($order->outlet_id, "invoiceDate") == 1) {
                    $in_date = date('Ymd', strtotime($order->table_start_date));
                }
                $invoice_number = $code.''.$in_date.''.$user_identifier.''.str_pad($invoice_no,$inv_digit,0,STR_PAD_LEFT);
            }else{
                $invoice_number = $order->invoice_no;
            }
        }
        return $invoice_number;
    }
    public function removeOrderItem() {
        $item_id = Input::get('item_id');
        $order_id = Input::get('order_id');
        $result = array();
        if(isset($item_id) && isset($order_id) && trim($item_id) != "" && trim($order_id) != "") {
            $order_item = OrderItem::find($item_id);
            $order = order_details::find($order_id);
            $final_total = $order->totalcost_afterdiscount - $order_item->item_total;
            $order->totalcost_afterdiscount = $final_total;
            $outlet_id = Session::get('outlet_session');
            $outlet_obj = Outlet::find($outlet_id);
            if(isset($outlet_obj->taxes) && trim($outlet_obj->taxes) != '') {
                $taxes = json_decode($outlet_obj->taxes);
                //total calculated item tax
                $total_calc_tax = 0;
                if (isset($taxes) && sizeof($taxes) > 0) {
                    foreach ($taxes as $t_key => $t_val) {
                        if ($order->tax_percent == $t_key) {
                            $final_tax = 0;
                            foreach ($t_val as $tx) {
                                $cal_tax = $final_total * floatval($tx->taxparc) / 100;
                                $final_tax += $cal_tax;
                            }
                        }
                    }
                }
                $order->totalprice = $final_total + $final_tax;
                $order->save();
                $round_of_total = round($final_total + $final_tax);
                $round_of_val = number_format(abs($final_total + $final_tax - $round_of_total), 2, '.', '');
                $order_payment_mode = OrderPaymentMode::where('order_id',$order_id)->delete();
                $order_payment_mode = new OrderPaymentMode();
                $order_payment_mode->order_id = $order_id;
                $order_payment_mode->payment_option_id = 1;
                $order_payment_mode->source_id = 1;
                $order_payment_mode->amount = $round_of_total;
                $order_payment_mode->save();
            }else {
                OrderPaymentMode::where('order_id',$order_id)->delete();
                $order_payment_mode = new OrderPaymentMode();
                $order_payment_mode->order_id = $order_id;
                $order_payment_mode->payment_option_id = 1;
                $order_payment_mode->source_id = 1;
                $order_payment_mode->amount = $final_total;
                $order_payment_mode->save();
            }
            $order_item->delete();
            $result['status'] = 'success';
        } else {
            $result['status'] = 'error';
        }
        return $result;
    }
    public function editorders($orderid) {
        $o_id = $orderid;
        //get order detail
        $order = order_details::where('order_id',$o_id)->join('outlets as ot','orders.outlet_id','=','ot.id')
            ->join('tables as tb','orders.table_no','=','tb.table_no')
            ->select('orders.*','ot.invoice_date as inv_date','ot.name as ot_name','ot.taxes as taxes','ot.default_taxes as default_taxes','orders.person_no as no_of_person')
            ->first();
        $item_arr = array();
        if( isset($order) && !empty($order) ) {
            $order_items = OrderItem::select('order_items.id as id','order_items.item_id as item_id','order_items.item_price as itm_price','order_items.item_quantity as itm_quantity','order_items.item_name as itm_name')
                ->where('order_items.order_id',$o_id)
                ->get();
            if ( isset($order_items) && !empty($order_items) ) {
                $i = 0;
                foreach( $order_items as $item ) {
                    $item_arr['item'][$i]['item_id'] = $item->item_id;
                    $item_arr['item'][$i]['name'] = $item->itm_name;
                    $item_arr['item'][$i]['qty'] = $item->itm_quantity;
                    $item_arr['item'][$i]['price'] = $item->itm_price;
                    $item_arr['item'][$i]['amount'] = number_format($item->itm_price * $item->itm_quantity,2);
                    $i++;
                }
            }
        }
        //check to display upi block or not
        //$check_pay_opt = PaymentOption::find($order->payment_option_id);
        /*$check_upi_status = '';
        if ( isset($check_pay_opt) && strtolower($check_pay_opt->name) == 'upi') {
            $check_upi_status = DB::table('icici_upi_transaction')->where('bill_no',$order->order_unique_id)->orderBy('txnid', 'desc')->first();
        }*/
        if(isset($order->user_id) && !empty($order->user_id)){
            $user = Customer::find($order->user_id);
            $order->customer_name = isset($user->first_name)?$user->first_name:"";
            $order->mobile_number = isset($user->mobile_number)?$user->mobile_number:"";
        }
        //payment options
        $payment_options = PaymentOption::getOutletPaymentOption($order->outlet_id);
        $custom_fields = json_decode($order->custom_fields,true);
        //order payment modes
        $ord_payment_modes = OrderPaymentMode::where('order_id',$o_id)->get();
        $outlet_id = Session::get('outlet_session');
        $delivery_settings = OutletSetting::checkAppSetting($outlet_id,"overwriteDeliveryCharge");
        $itemWiseDiscount = $order->itemwise_discount;
        $itemWiseTax = $order->itemwise_tax;
        $owner_id = Auth::id();
        $sess_outlet_id = Session::get('outlet_session');
        $outlet_id = '';$outlet = '';
        if (isset($sess_outlet_id) && $sess_outlet_id != '') {
            $outlet_id = $sess_outlet_id;
        }
        $menu = $category = '';
        if( $outlet_id != '') {
            //menu
            $menu = Menu::geOutletMenu($outlet_id);
            $outlet = Outlet::find($outlet_id);
        }
        $attributes = ItemAttribute::join('outlet_item_attributes_mapper','outlet_item_attributes_mapper.attribute_id','=','item_attributes.id')
                        ->where('created_by',$owner_id)
                        ->where('outlet_item_attributes_mapper.outlet_id',$outlet_id)
                        ->select('item_attributes.name', 'item_attributes.id')->get();
        return view("orderlist.editorder",array(
                        'custom_fields'=>$custom_fields,
                        /*'check_upi_status'=>$check_upi_status,*/
                        'outlet_id'=>$o_id,
                        'order'=>$order,
                        'items'=>$item_arr,
                        'pay_option'=>$payment_options,
                        'payment_modes'=>$ord_payment_modes,
                        'delivery_settings'=>$delivery_settings,
                        'itemWiseDiscount'=>$itemWiseDiscount,
                        'itemWiseTax'=>$itemWiseTax,
                        'menu'=>$menu,
                        'attributes'=>$attributes,
                        'custom_fields'=>$custom_fields
                        )
                    );
    }
    public function saveOrder(Request $request, $order = Null ) {
        \Log::info("place order..........");
        $sess_outlet_id = Session::get('outlet_session');
        $person_no = $email = $table_no = $ref_id = $name = $cust_name = '';
        $source_id = $payment_option_id = $delivery_charge = $total = 0;
        if( isset($order) && !empty($order) ) {
            $order = $order['order'];
Log::info("here it if");
            $outlet_id = $order['outlet_id'];
            $item_attribute = $order['item_attribute'];
            $item_id = $order['item_id'];
            $item_qty = $order['item_qty'];
            $item_name = $order['item_name'];
            $item_price = $order['item_price'];
            $order_date = $order['order_date'];
            $mobile = $order['mobile'];
            $address = $order['address'];
            $order_type = $order['order_type'];
            $paid_type = $order['paid_type'];
            $delivery_charge = $order['delivery_charge'];
            $payment_option_id = $order['payment_option_id'];
            $source_id = $order['source_id'];
            $total = $order['total_price'];
            $table_no = $order['table'];
            $ref_id = $order['ref_id'];
            $cust_name = $order['customer_name'];
        } else {
            Log::info("here it else");
            $outlet_id = $sess_outlet_id;
            $name = Auth::user()->user_name;
            $out_id = $request->get('outlet_id');
            $item_id = $request->get('item_id');
            $item_qty = $request->get('item_qty');
            $item_name = $request->get('item_name');
            $item_price = $request->get('item_price');
            $item_options = $request->get('item_options');
            $item_attribute = $request->get('item_attribute');
            $order_date = $request->get('order_date');
            $table_no = $request->get('table_no');
            $person_no = $request->get('person_no');
            $order_type = $request->get('order_type');
            $mobile = $request->get('mobile');
            $address = $request->get('address');
            $cust_name = $request->get('name');
            $paid_type = 'cash';
            if ( isset($item_id) && !empty($item_id) ) {
                for( $i=0; $i<sizeof($item_id); $i++ ) {
                    $qty = $item_qty[$i];
                    $price = floatval($item_price[$i]);
                    //check item options has been selected for this item or not
                    if ( isset($item_options[$i]) && !empty($item_options[$i]) ) {
                        foreach ( $item_options[$i] as $opt ) {
                            $total += $opt['option_price'] * $qty;
                        }
                    }
                    $total += $qty * $price;
                }
            }
        }
        $customer_id = NULL;
        //check customer available or not
        if ( $mobile != '' || $cust_name != '' ) {
            $check_customer = Customer::where('mobile_number',$mobile)->where('mobile_number','!=',0)->first();
            if (  isset($check_customer) && !empty($check_customer) ) {
                $check_customer->address = $address;
                $check_customer->email = $email;
                $check_customer->first_name = $cust_name;
                $check_customer->updated_at = date('Y-m-d H:i:s');
                $check_customer->save();
                $customer_id = $check_customer->id;
            } else {
                $customer_obj = new Customer();
                $customer_obj->first_name = $cust_name;
                $customer_obj->address = $address;
                $customer_obj->mobile_number = $mobile;
                $customer_obj->created_at = date('Y-m-d H:i:s');
                $customer_obj->updated_at = date('Y-m-d H:i:s');
                $cust_result = $customer_obj->save();
                if ( $cust_result ) {
                    $customer_id = $customer_obj->id;
                }
            }
        }
        if( $outlet_id != '') {
            $startingstatus = status::getallstatusofOutlet($outlet_id);
            $lastindex = count($startingstatus) - 1;
            if (isset($startingstatus)) {
                $status = $startingstatus[$lastindex]->status;
            } else {
                $status = '';
            }
            $itemWiseTax = OutletSetting::checkAppSetting($outlet_id,"itemWiseTax");
            $itemWiseDiscount = OutletSetting::checkAppSetting($outlet_id,"itemWiseDiscount");
            $discAfterTax = OutletSetting::checkAppSetting($outlet_id,"discountAfterTax");
            $guid = order_details::guid();
            DB::beginTransaction();
            $order_details = order_details::find($out_id);
            $order_details->name = $name;
            $order_details->user_id = $customer_id;
            $order_details->outlet_id = $outlet_id;
            $order_details->status = 'delivered';
            $order_details->order_type = $order_type;
            $order_details->table_no = isset($table_no)?$table_no:0;
            $order_details->person_no = isset($person_no)?$person_no:0;
            $order_details->totalprice = $total;
            \Log::info("place order..........".$total);
            $order_details->paid_type = $paid_type;
            $order_details->source_id = $source_id;
            $order_details->payment_option_id = $payment_option_id;
            $order_details->totalcost_afterdiscount = $total;
            $order_details->suborder_id = 0;
            $order_details->order_unique_id = $guid;
            $order_details->table_start_date= $order_date;
            $order_details->table_end_date= $order_date;
            $order_details->payment_status = 0;
            $order_details->user_mobile_number = isset($mobile)?$mobile:"";
            $order_details->address = isset($address)?$address:"";
            $order_details->customer_order = 0;
            $order_details->delivery_charge = $delivery_charge;
            $order_details->referance_id = $ref_id;
            $order_details->cancelorder = "0";
            $order_details->itemwise_tax = $itemWiseTax;
            $order_details->itemwise_discount = $itemWiseDiscount;
            $order_details->add_discount_after_tax = $discAfterTax;
            $result = $order_details->save();
            $arr_items = [];
            if ( $result ) {
                $order_id = $order_details->order_id;
                for ( $i=0;$i<sizeof($item_id);$i++ ) {
                    $itm_uq_id = order_details::guid();
                    $orderitems = OrderItem::where('item_id',$item_id[$i])->where('order_id',$order_id)->first();
                    if(isset($orderitems)){
                        $orderitems = OrderItem::where('item_id',$item_id[$i])->where('order_id',$order_id)->first();
                    }else {
                        array_push($arr_items,$item_id[$i]);
                        $orderitems = new OrderItem();
                    }
                    $orderitems->order_id = $order_id;
                    $orderitems->item_quantity = $item_qty[$i];
                    $orderitems->item_unique_id = $itm_uq_id;
                    $orderitems->item_id = $item_id[$i];
                    $orderitems->item_name = $item_name[$i];
                    $orderitems->item_price = $item_price[$i];
                    $orderitems->item_total = floatval($item_price[$i]) * intval($item_qty[$i]);
                    $actual_item_price = floatval($item_price[$i]) * intval($item_qty[$i]);
                    //add option price in main price to calculate tax
                    if ( isset($item_options[$i]) && !empty($item_options[$i]) ) {
                        foreach ( $item_options[$i] as $opt ) {
                            $actual_item_price += $opt['option_price'] * intval($item_qty[$i]);
                        }
                    }
                    $itm_tax = array();$disc_arr = array();
                    if ( $item_id[$i] != 0 ) {
                        $menu = Menu::where('menus.id', $item_id[$i])
                            ->join('menu_titles', 'menu_titles.id', '=', 'menus.menu_title_id')
                            ->select('menus.id as id','menus.tax_slab as tax_slab','menus.discount_type as discount_type','menus.discount_value as discount_value', 'menus.price as price', 'menus.item as item', 'menus.menu_title_id as cat_id', 'menu_titles.title as category')->first();
                        $orderitems->category_id = $menu->cat_id;
                        $orderitems->category_name = $menu->category;
                        if( $itemWiseTax || $itemWiseDiscount ) {
                            //$item_obj = Menu::find($item_id[$i]);
                            if ( $itemWiseDiscount ) {
                                //check if discount calculate before tax or not
                                if ( $discAfterTax == 0 ) {
                                    if ( $menu->discount_value > 0 ) {
                                        $disc_arr['disc_type'] = $menu->discount_type;
                                        $disc_arr['disc_value'] = $menu->discount_value;
                                        if ( $menu->discount_type == 'fixed' ) {
                                            $disc_arr['disc_calc_amount'] = $menu->discount_value * intval($item_qty[$i]);
                                            $actual_item_price = $actual_item_price - $disc_arr['disc_calc_amount'];
                                        } else {
                                            $disc_value = $actual_item_price * $menu->discount_value / 100;
                                            $disc_arr['disc_calc_amount'] = $disc_value;
                                            $actual_item_price = $actual_item_price - $disc_value;
                                        }
                                    }
                                }
                            }
                            //check if slab is set or not
                            if ( $itemWiseTax ) {
                                if ( isset($menu->tax_slab) && $menu->tax_slab != '') {
                                    $outlet_obj = Outlet::find($outlet_id);
                                    //outlet taxes
                                    $taxes = json_decode($outlet_obj->taxes);
                                    //total calculated item tax
                                    $total_calc_tax = 0;
                                    if ( isset($taxes) && !empty($taxes) ) {
                                        foreach ( $taxes as $t_key=>$t_val ) {
                                            if ( $menu->tax_slab == $t_key ) {
                                                $itm_tax[$t_key] = [];
                                                foreach ( $t_val as $tx ) {
                                                    $cal_tax = $actual_item_price * floatval($tx->taxparc) / 100;
                                                    $slab['taxname'] = $tx->taxname;
                                                    $slab['taxparc'] = $tx->taxparc;
                                                    $slab['value'] = $cal_tax;
                                                    array_push($itm_tax[$t_key],$slab);
                                                    $total_calc_tax += $cal_tax;
                                                }
                                            }
                                        }
                                    }
                                    $actual_item_price += $total_calc_tax;
                                }
                            }
                            if ( $itemWiseDiscount ) {
                                //check if discount calculate before tax or not
                                if ( $discAfterTax == 1 ) {
                                    if ( $menu->discount_value > 0 ) {
                                        $disc_arr['disc_type'] = $menu->discount_type;
                                        $disc_arr['disc_value'] = $menu->discount_value;
                                        if ( $menu->discount_type == 'fixed' ) {
                                            $disc_arr['disc_calc_amount'] = $menu->discount_value * intval($item_qty[$i]);
                                        } else {
                                            $disc_value = $actual_item_price * $menu->discount_value / 100;
                                            $disc_arr['disc_calc_amount'] = $disc_value;
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $orderitems->category_id = NULL;
                        $orderitems->category_name = NULL;
                    }
                    //if itemwise tax available
                    if ( !empty($itm_tax) ) {
                        $orderitems->tax_slab = json_encode($itm_tax);
                    }
                    //if itemwise discount is enabled
                    if ( !empty($disc_arr) ) {
                        $orderitems->item_discount = json_encode($disc_arr);
                    }
                    $result2 = $orderitems->save();
                    if ( !$result2 ) {
                        DB::rollBack();
                        return 'error';
                    } else {
                        //check item options has been selected for this item or not
                        if ( isset($item_options[$i]) && !empty($item_options[$i]) ) {
                            foreach ( $item_options[$i] as $opt ) {
                                $option = new OrderItemOption();
                                $option->order_id = $order_id;
                                $option->order_item_id = $orderitems->id;
                                $option->option_item_id = $opt['option_id'];
                                $option->qty = $item_qty[$i];
                                $option->option_item_price = $opt['option_price'];
                                $opt_result = $option->save();
                                if ( !$opt_result ) {
                                    DB::rollBack();
                                    return 'error';
                                }
                            }
                        }
                        //check item attribute has been selected for this item or not
                        if( isset($item_attribute[$i]) && !empty($item_attribute[$i]) ) {
                            if( isset($order) && !empty($order)) {
                                foreach ( $item_attribute[$i] as $attr ) {
                                    $check_attribute = ItemAttribute::join('outlet_item_attributes_mapper as oa','item_attributes.id','=','oa.attribute_id')
                                                                    ->select('item_attributes.*')
                                                                    ->where('oa.outlet_id',$outlet_id)
                                                                    ->where(DB::raw('LOWER(item_attributes.name)'),strtolower($attr))
                                                                    ->first();
                                    if ( isset($check_attribute) && !empty($check_attribute) ) {
                                        $oia = new order_item_attributes();
                                        $oia->order_item_id = $orderitems->id;
                                        $oia->attribute_id = $check_attribute->id;
                                        $oia->attribute_name = $check_attribute->name;
                                        $oia->save();
                                    } else {
                                        $add_attr = new ItemAttribute();
                                        $add_attr->name = $attr;
                                        $add_attr->created_by = 0;
                                        $add_attr->updated_by = 0;
                                        $add_attr_result = $add_attr->save();
                                        if ( $add_attr_result ) {
                                            $add_ot_attr_mapper = new OutletItemAttributesMapper();
                                            $add_ot_attr_mapper->attribute_id = $add_attr->id;
                                            $add_ot_attr_mapper->outlet_id = $outlet_id;
                                            $add_ot_attr_mapper->save();
                                            //add order item attribute
                                            $oia = new order_item_attributes();
                                            $oia->order_item_id = $orderitems->id;
                                            $oia->attribute_id = $add_attr->id;
                                            $oia->attribute_name = $add_attr->name;
                                            $oia->save();
                                        }
                                    }
                                }
                            } else {
                                $attr_ids = explode(",",$item_attribute[$i][0]['attr_id']);
                                foreach ($attr_ids as $attr_id) {
                                    $attrname = ItemAttribute::find($attr_id);
                                    //order item attribute add
                                    $oia = new order_item_attributes();
                                    $oia->order_item_id = $orderitems->id;
                                    $oia->attribute_id = $attr_id;
                                    $oia->attribute_name = isset($attrname->name)?$attrname->name:"";
                                    $oia->save();
                                }
                            }
                        }
                    }
                }
                $py_modes = new OrderPaymentMode();
                $py_modes->order_id = $order_id;
                $py_modes->payment_option_id = 0;
                $py_modes->source_id = 0;
                $py_modes->amount = $total;
                $py_modes->save();
                $histroy = new OrderHistory();
                $histroy->order_id = $order_id;
                $histroy->owner = $name;
                $histroy->order_type = $order_type;
                $histroy->total = $total;
                $histroy->sub_total = $total;
                $histroy->user_mobile_no = $mobile;
                $histroy->address = $address;
                $histroy->delivery_charge = $delivery_charge;
                $histroy->save();
                //sent order to firebase to display in partner app
                $data['message'] = 'success';
                if ( $order_type != 'dine_in' ) {
                    $fields = array();
                    $fields['outlet_id'] = $outlet_id;
                    $fields['server_id'] = "$order_id";
                    $fields['order_type'] = $order_type;
                    $fields['order_unique_id'] = $guid;
                    $fields['table_no'] = $table_no;
                    $fields['action'] = 'OnlineOrder';
                }
                DB::commit();
                if ( $data == 'error') {
                    $data['message'] = 'firebase error';
                    $data['order_id'] = $order_id;
                    $data['item_id'] = $arr_items;
                    return $data;
                } else if ( $data == 'no user' ) {
                    $data['message'] = 'order taker error';
                    $data['order_id'] = $order_id;
                    $data['item_id'] = $arr_items;
                    return $data;
                }
                $data['message'] = 'success';
                $data['order_id'] = $order_id;
                $data['item_id'] = $arr_items;
                return $data;
            } else {
                DB::rollBack();
                $data['message'] ='error';
                return $data;
            }
        }
    }
    public function printKot(Request $request) {
        $order_no = $request->get('order_id');
        $item_id = $request->get('item_id');
        $order = order_details::leftJoin('outlets as ot','orders.outlet_id','=','ot.id')
                                ->select('orders.*','ot.name as ot_name','ot.city_id as city_id','ot.invoice_title as invoice_title','ot.order_lable as order_lable','ot.taxes as ot_taxes','ot.tax_details as tax_details','ot.company_name as company_name','ot.address as ot_address','ot.servicetax_no as service_tax_no','ot.vat as vat_no','ot.url as url','ot.duplicate_watermark as duplicate_watermark')
                                ->where('orders.order_id',$order_no)
                                ->first();
        $outlet_id = Session::get('outlet_session');
        $outlet_obj = Outlet::find($outlet_id);
        $order_info = array();
        // $order_taxes = json_decode($order->tax_type);
        // if ( !isset( $order_taxes) && empty($order_taxes) ) {
        //     $order_taxes = NULL;
        // }
        $order_info['customer'] = '';
        if( isset($order->user_id) && $order->user_id != '' ) {
            $order_info['customer'] = Customer::where('id',$order->user_id)->pluck('first_name');;
        }
        //tax details
        $tax_details = NULL;
        if ( isset($order->tax_details) && !empty($order->tax_details) ) {
            $tax_details = json_decode($order->tax_details);
        }
        $order_info['ot_id'] = $outlet_id;
        $order_info['ot_name'] = $order->ot_name;
        $order_info['invoice_title'] = $order->invoice_title;
        $order_info['ot_address'] = $order->ot_address;
        $order_info['company_name'] = $order->company_name;
        $order_info['or_type'] = $order->order_type;
        $order_info['or_number'] = $order->invoice_no;
        $order_info['user'] = Auth::user()->user_name;
        $order_info['table'] = $order->table_no;
        $order_info['person'] = $order->person_no;
        $order_info['sub_total'] = $order->totalcost_afterdiscount;
        $order_info['total'] = $order->totalprice;
        \Log::info("here total price".$order->totalprice);
        $order_info['discount'] = $order->discount_value;
        $order_info['round_off'] = abs($order->round_off);
        \Log::info("here round off".$order->round_off);
        $order_info['date'] = Carbon::parse($order->table_start_date)->format('d-m-Y');
        $order_info['url'] = $order->url;
        // $order_info['taxes'] = $order_taxes;
        $order_info['custom_fields'] = json_decode($order->custom_fields,true);
        $order_info['service_tax_no'] = $order->service_tax_no;
        $order_info['vat_no'] = $order->vat_no;
        $order_info['updated'] = $order->updated;
        $order_info['order_lable'] = $order->order_lable;
        $order_info['delivery_charge'] = $order->delivery_charge;
        $order_info['tax_details'] = $tax_details;
        $order_info['city_name'] = isset($order->city_id)?City::find($order->city_id):"";
        $order_info['discount_after_tax'] = $order->add_discount_after_tax;
        $order_info['discount_type'] = $order->discount_type;
        $order_info['duplicate_watermark'] = $order->duplicate_watermark;
        $order_info['itemwise_tax'] = $order->itemwise_tax;
        $order_info['itemwise_discount'] = $order->itemwise_discount;
        $order_info['item_discount'] = $order->item_discount_value;
        if ( $order->is_custom == 1 ) {
            $orderplace = \App\OrderPlaceType::find($order->order_place_id);
            if ( isset($orderplace) && !empty($orderplace) ) {
                $order_info['order_lable'] = $orderplace->name;
            }
        }
        $order_info['is_custom'] = $order->is_custom;
        if( isset($order) && !empty($order) ) {
            if(isset($item_id) && !empty($item_id)){
                foreach($item_id as $item){
                    $order_items = OrderItem::select('order_items.id','order_items.item_price as itm_price','order_items.item_quantity as itm_quantity',
                                                    'order_items.item_name as itm_name','order_items.tax_slab as tax_slab',
                                                    'order_items.item_discount')
                                            ->where('order_items.order_id',$order->order_id)
                                            ->where('order_items.item_id',$item)
                                            ->get();
                    }
            }else{
                    $order_items = OrderItem::select('order_items.id','order_items.item_price as itm_price','order_items.item_quantity as itm_quantity',
                                                    'order_items.item_name as itm_name','order_items.tax_slab as tax_slab',
                                                    'order_items.item_discount')
                                            ->where('order_items.order_id',$order->order_id)
                                            ->get();
            }
            if ( isset($order_items) && !empty($order_items) ) {
                $i = 0; $item_price = 0;
                foreach( $order_items as $item ) {
                    $order_info['item'][$i]['name'] = $item->itm_name;
                    $order_info['item'][$i]['qty'] = $item->itm_quantity;
                    $item_price = $item->itm_price;
                    $itm_options = OrderItemOption::join("menus as m","m.id","=","order_item_options.option_item_id")
                                                ->select("m.item as item","order_item_options.*")
                                                ->where('order_item_id',$item->id)
                                                ->get();
                    if ( isset($itm_options) && !empty($itm_options) ) {
                        $j=0;
                        foreach ( $itm_options as $opt ) {
                            $order_info['item'][$i]['options'][$j]['name'] = $opt->item;
                            /*$order_info['item'][$i]['options'][$j]['qty'] = $opt->qty;
                            $order_info['item'][$i]['options'][$j]['price'] = $opt->option_item_price;
                            $order_info['item'][$i]['options'][$j]['amount'] = $opt->option_item_price * $opt->qty;*/
                            $item_price += $opt->option_item_price;
                            $j++;
                        }
                    }
                    $order_info['item'][$i]['price'] = $item_price;
                    $order_info['item'][$i]['amount'] = $item_price * $item->itm_quantity;
                    //check itemwise tax
                    $taxes = json_decode($item->tax_slab);
                    if ( isset($taxes) && !empty($taxes) ) {
                        foreach ( $taxes as $t_key=>$t_val ) {
                            $k = 0;
                            foreach ( $t_val as $tx ) {
                                $order_info['item'][$i]['slab'][$k]['tax_name'] = $tx->taxname;
                                $order_info['item'][$i]['slab'][$k]['tax_parc'] = $tx->taxparc;
                                $order_info['item'][$i]['slab'][$k]['tax_val'] = $tx->value;
                                $k++;
                            }
                        }
                    }
                    //check itemwise discount
                    $item_disc = json_decode($item->item_discount);
                    if ( isset($item_disc) && !empty($item_disc) ) {
                        $order_info['item'][$i]['discount']['type'] = $item_disc->disc_type;
                        $order_info['item'][$i]['discount']['value'] = $item_disc->disc_value;
                        $order_info['item'][$i]['discount']['amount'] = $item_disc->disc_calc_amount;
                    }
                    $i++;
                }
            }
        }
        if(strtolower($order->ot_name) == 'zucchini')
            return view("billtemplate.printorder_noheader",array("order"=>$order_info));
        else
            return view("printkot",array("order"=>$order_info));
    }
    public function closetable(Request $request){
        $order_id = $request->get('orderid');
        $value = $request->get('value');
        if($value == 'card'){
            $paymentmod = 3;
        }else{
            $paymentmod = 1;
        }
        $data = order_details::where('order_id',$order_id)->update(['payment_status'=>1,'payment_option_id'=>$paymentmod,'read'=>1]);
        $payment = OrderPaymentMode::where('order_id',$order_id)->update(['payment_option_id'=>$paymentmod]);
        $result['status'] = 'success';
        $result['data'] = $data;
        $result['payment'] = $payment;
        return $result;
    }
}
