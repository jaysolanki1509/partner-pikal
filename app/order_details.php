<?php namespace App;

use Aws\CloudFront\Exception\Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\OrderCouponMappers;
use App\PayUMoney;
class order_details extends Model {
    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    public function orderdetails()
    {
        return $this->hasMany('App\OrderItem','order_id');
    }
    public function outletorder()
    {
        return $this->belongsTo('App\Outlet', 'outlet_id', 'id');
    }

    public static function getorderid(){
        $getallorders=order_details::all();
        $last_inserted_id=0;
            foreach($getallorders as $odids){
                $last_inserted_id=$odids->order_id;
            }
            $order_ids=$last_inserted_id+1;

        return $order_ids;
    }

    public static function guid()
    {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }


    public static function getorderidofrestaurant($restaurantid){
        /*$getallorders=order_details::all();
        $ordersrestaurantid=array();
        foreach($getallorders as $getorders){
            if($restaurantid!=$getorders->outlet_id){
                array_push($ordersrestaurantid,$getorders->outlet_id);

            }
            $reset=$getorders->reset;
        }
       // print_r($reset);exit;
        if(isset($reset) && $reset=='true'){
            $neworderid=1;
        }else{
        $getallor=order_details::whereNotIn('outlet_id',$ordersrestaurantid)->whereDate('created_at','>=',gmdate('Y-m-d'))->get();

            $last_inserted_id=0;
            foreach($getallor as $odids){
                $last_inserted_id=$odids->suborder_id;
            }
            $neworderid=$last_inserted_id+1;
        }*/

        $getallor=order_details::where('outlet_id',$restaurantid)->whereDate('created_at','>=',gmdate('Y-m-d'))->get();

        $last_inserted_id=0;
        foreach($getallor as $odids){
            $last_inserted_id=$odids->suborder_id;
        }
        $neworderid=$last_inserted_id+1;


        return $neworderid;
    }


    public static function insertorderdetails($a,$order_ids,$order,$status,$suborder_id,$invoice_no ='',$service_tax)
    {

        $address = $combime_address = $mobile = $device_id = $name = $outlet_id = $tax_type = $status1 = $local_id = $order_type = $table_no = $person_no = "";
        $total = $round_off = $paid_type = $delivery_charge = $discount = $totalcost_afterdiscount = $suborder_id1 = $order_unique_id = $table_start_date = $table_end_date = $inv_no = "";
        $merged_tables = $merged_order_unique_ids = $invoice = $payment_txn_identifier = $cancel_reason = '';
        $custom_fields = $first_name = $last_name = $email = $city = $state = $country = $gender = $dob = $doa = $spouse_name = $spouse_number = '';
        $item_discount_value = $itemwise_tax = $itemwise_discount = $discount_after_tax = $payment_status = $cancel_order = $user_id = $married = $source_id = $is_custom = $order_place_id = $payment_option_id = 0;
        $customer_email = $discount_type = NULL;
        $order_id = $order_ids;

        if(isset($order['merged_with'])){
            $merged_tables = $order['merged_with'];
        }
        if(isset($order['ref_order_unique_ids'])){
            $merged_order_unique_ids = $order['ref_order_unique_ids'];
        }
        if(isset($order['is_custom'])){
            $is_custom = $order['is_custom'];
        }
        if(isset($order['order_place_id'])){
            $order_place_id = $order['order_place_id'];
        }
        if(isset($order['cancelorder'])){
            $cancel_order = $order['cancelorder'];
        }
        if(isset($order['order_cancel_reason'])){
            $cancel_reason = $order['order_cancel_reason'];
        }
        if(isset($order['address'])){
            $address = $order['address'];
        }
        if(isset($order['simpleaddress'])){
            $combime_address = $order['simpleaddress'];
        }
        if(isset($order['mobile_number'])) {
            $mobile = $order['mobile_number'];
        }
        if(isset($order['device_id'])) {
            $device_id = $order['device_id'];
        }
        if(isset( $order['user_id']) ) {
            $user_id = $order['user_id'];
        }
        if(isset( $order['name']) ) {
            $name = $order['name'];
        }
        if(isset($order['restaurant_id'])) {
            $outlet_id = $order['restaurant_id'];
        }
        if(isset($order['tax_type'])) {
            $tax_type = $order['tax_type'];
        }
        if ( isset($order['item_discount_value'])) {
            $item_discount_value = $order['item_discount_value'];
        }
        if(isset($order['custom_fields'])) {
            $custom_fields = $order['custom_fields'];
            if(isset($order['restaurant_id'])) {
                $outlet = Outlet::find($outlet_id);
                $outlet_custom_fields = json_decode($outlet->custom_bill_print_fields);
                $custom_fields_arr = array();

                if (isset($outlet_custom_fields) && sizeof($outlet_custom_fields) > 0 && isset($custom_fields) && sizeof($custom_fields) > 0) {
                    foreach (json_decode($custom_fields) as $key => $field) { //Actual value comming from app

                        if ($key == "email") {
                            $customer_email = $field->value;
                        }
                    }
                }
            }
        }
        if(isset($status)) {
            $status1 = $status;
        }
        if(isset($order['local_id'])) {
            $local_id = $order['local_id'];
        }
        if(isset($order['order_type'])) {
            $order_type = $order['order_type'];
        }
        if(isset($order['table'])) {
            $table_no = $order['table'];
        }
        if(isset($order['person'])) {
            $person_no = $order['person'];
        }
        if(isset($order['total_price'])) {
            $total = $order['total_price'];
        }
        if(isset($order['payment_txn_identifier'])) {
            $payment_txn_identifier = $order['payment_txn_identifier'];
        }
        if(isset($order['round_off'])){
            $round_off = $order['round_off'];
        }
        if(isset($order['paid_type'])) {
            $paid_type = $order['paid_type'];
        }
        if(isset($order['source_type'])) {
            $source_id = $order['source_type'];
        }
        if(isset($order['payment_provider_id'])) {
            $payment_option_id = $order['payment_provider_id'];
        }
        if(isset($order['discounted_value'])) {
            $discount = $order['discounted_value'];
        }
        if(isset($order['discount_type']) && $order['discount_type'] !="") {
            $discount_type = $order['discount_type'];
        }
        if(isset($order['delivery_charge'])) {
            $delivery_charge = $order['delivery_charge'];
        }
        if(isset($order['totalcost_afterdiscount'])) {
            $totalcost_afterdiscount = $order['totalcost_afterdiscount'];
        }
        if(isset($suborder_id) && $suborder_id!=''){
            $suborder_id1 = $suborder_id;
        }
        if(isset($order['orderuniqueid'])){
            $order_unique_id = $order['orderuniqueid'];
        }
        if(isset($order['start_date'])){
            $table_start_date = date("Y-m-d H:i:s",strtotime($order['start_date']));
        } else {
            $table_start_date = date("Y-m-d H:i:s",strtotime(Carbon::now()));
        }
        if(isset($order['end_date'])){
            $table_end_date = date("Y-m-d H:i:s",strtotime($order['end_date']));
        } else {
            $table_end_date = date("Y-m-d H:i:s",strtotime(Carbon::now()));
        }

        if(isset($order['order_payment_status'])) {
            $payment_status = $order['order_payment_status'];
        }

        $inv_no = '';$invoice = '';
        if(isset($order['invoice']) && $order['invoice'] != ''  ){
            $inv_no = $order['invoice'];
            $invoice = $order['invoice_id'];
        }
        /*if(isset($order['invoice']) && $order['invoice'] != ''  ){
            if(isset($invoice_no) && $invoice_no!=''){
                $inv_arr = explode("_",$invoice_no);
                $inv_no = $inv_arr[1];
                $invoice = $inv_arr[0];
            }
        }*/

        //customer detail
        if(isset($order['customer_name'])){
            $first_name = $order['customer_name'];
        }
        if(isset($order['first_name'])){
            $first_name = $order['first_name'];
        }
        if(isset($order['last_name'])){
            $last_name = $order['last_name'];
        }
        if(isset($order['email'])){
            $email = $order['email'];
        }
        if(isset($order['city'])){
            $city = $order['city'];
        }
        if(isset($order['state'])){
            $state = $order['state'];
        }
        if(isset($order['country'])){
            $country = $order['country'];
        }
        if(isset($order['gender'])){
            $gender = $order['gender'];
        }
        if(isset($order['dob'])){
            $dob = $order['dob'];
        }
        if(isset($order['doa'])){
            $doa = $order['doa'];
        }
        if(isset($order['spouse_name'])){
            $spouse_name = $order['spouse_name'];
        }
        if(isset($order['spouse_number'])){
            $spouse_number = $order['spouse_number'];
        }
        if(isset($order['country'])){
            $country = $order['country'];
        }

        if ( isset($order['discount_after_tax'])) {
            $discount_after_tax = $order['discount_after_tax'];
        }
        if ( isset($order['itemwise_tax'])) {
            $itemwise_tax = $order['itemwise_tax'];
        }
        if ( isset($order['itemwise_discount'])) {
            $itemwise_discount = $order['itemwise_discount'];
        }

        $customer_id = NULL;
        //check customer available or not
        if ( trim($mobile) != '' || trim($first_name) != '' || trim($email) != "" || trim($customer_email) != "" ) {

            $check_customer = Customer::where('mobile_number',$mobile)->where('mobile_number','!=',0)->first();
            if (  isset($check_customer) && sizeof($check_customer) > 0 ) {

                $check_customer->address = $address;
                $check_customer->email = isset($email)?$email:$customer_email;
                $check_customer->first_name = $first_name;
                $check_customer->last_name = $last_name;
                $check_customer->state = $state;
                $check_customer->country = $country;
                $check_customer->city = $state;
                $check_customer->gender = $gender;
                $check_customer->dob = $dob;
                $check_customer->doa = $doa;
                $check_customer->married = $married;
                $check_customer->spouse_name = $spouse_name;
                $check_customer->spouse_number = $spouse_number;
                $check_customer->updated_at = date('Y-m-d H:i:s');
                $check_customer->save();

                $customer_id = $check_customer->id;

            }elseif (isset($email) || isset($customer_email)){

                $check_customer = Customer::where('email',$email)->where("email",$customer_email)->first();
                if (  isset($check_customer) && sizeof($check_customer) > 0 ) {

                    $check_customer->address = $address;
                    $check_customer->email = isset($email)?$email:$customer_email;
                    $check_customer->first_name = $first_name;
                    $check_customer->last_name = $last_name;
                    $check_customer->state = $state;
                    $check_customer->country = $country;
                    $check_customer->city = $state;
                    $check_customer->gender = $gender;
                    $check_customer->dob = $dob;
                    $check_customer->doa = $doa;
                    $check_customer->married = $married;
                    $check_customer->spouse_name = $spouse_name;
                    $check_customer->spouse_number = $spouse_number;
                    $check_customer->updated_at = date('Y-m-d H:i:s');
                    $check_customer->save();

                    $customer_id = $check_customer->id;

                }else {

                    $customer_obj = new Customer();
                    $customer_obj->first_name = $first_name;
                    $customer_obj->last_name = $last_name;
                    $customer_obj->address = $address;
                    $customer_obj->email = isset($email)?$email:$customer_email;
                    $customer_obj->state = $state;
                    $customer_obj->country = $country;
                    $customer_obj->city = $state;
                    $customer_obj->gender = $gender;
                    $customer_obj->dob = $dob;
                    $customer_obj->doa = $doa;
                    $customer_obj->married = $married;
                    $customer_obj->spouse_name = $spouse_name;
                    $customer_obj->spouse_number = $spouse_number;
                    $customer_obj->mobile_number = $mobile;
                    $customer_obj->created_at = date('Y-m-d H:i:s');
                    $customer_obj->updated_at = date('Y-m-d H:i:s');
                    $cust_result = $customer_obj->save();

                    if ( $cust_result ) {
                        $customer_id = $customer_obj->id;
                    }

                }

            } else {

                $customer_obj = new Customer();
                $customer_obj->first_name = $first_name;
                $customer_obj->last_name = $last_name;
                $customer_obj->address = $address;
                $customer_obj->email = isset($email)?$email:$customer_email;
                $customer_obj->state = $state;
                $customer_obj->country = $country;
                $customer_obj->city = $state;
                $customer_obj->gender = $gender;
                $customer_obj->dob = $dob;
                $customer_obj->doa = $doa;
                $customer_obj->married = $married;
                $customer_obj->spouse_name = $spouse_name;
                $customer_obj->spouse_number = $spouse_number;
                $customer_obj->mobile_number = $mobile;
                $customer_obj->created_at = date('Y-m-d H:i:s');
                $customer_obj->updated_at = date('Y-m-d H:i:s');
                $cust_result = $customer_obj->save();

                if ( $cust_result ) {
                    $customer_id = $customer_obj->id;
                }

            }

        }


        if ( isset($order_id) && $order_id != "0" ) {

            order_details::where('order_id',$order_id)->where('invoice_no','')->update([
                'table_no'=>$table_no,
                'user_id'=>$customer_id,
                'person_no'=>$person_no,
                'referance_id'=>$payment_txn_identifier,
                'totalprice'=>$total,
                'round_off'=>$round_off,
                'paid_type'=>$paid_type,
                'totalcost_afterdiscount'=>$totalcost_afterdiscount,
                'discount_type'=>$discount_type,
                'suborder_id'=>$suborder_id1,
                'order_unique_id'=>$order_unique_id,
                'invoice_no'=>$inv_no,
                'payment_option_id'=>$payment_option_id,
                'source_id'=>$source_id,
                'invoice'=>$invoice,
                'payment_status'=>$payment_status,
                'tax_type'=>$tax_type,
                'item_discount_value'=>$item_discount_value,
                'cancelorder'=>$cancel_order,
                'order_place_id'=>$order_place_id,
                'is_custom'=>$is_custom,
                'itemwise_tax'=>$itemwise_tax,
                'itemwise_discount'=>$itemwise_discount,
                'add_discount_after_tax'=>$discount_after_tax,
            ]);

            //delte old payment modes
            OrderPaymentMode::where('order_id',$order_id)->delete();

            $order_details = order_details::where('order_id',$order_id)->first();
            $order_type = $order_details->order_type;
            $discount = $order_details->discount_value;
            $delivery_charge = $order_details->delivery_charge;

        } else {

            $order_details=new order_details();
            $order_details->order_place_id = $order_place_id;
            $order_details->is_custom = $is_custom;
            $order_details->cancelorder = $cancel_order;
            $order_details->user_id = $customer_id;
            $order_details->address = $address;
            $order_details->combine_address=$combime_address;
            $order_details->user_mobile_number = $mobile;
            $order_details->device_id = $device_id;
            $order_details->name = $name;
            $order_details->outlet_id = $outlet_id;
            $order_details->tax_type = $tax_type;
            $order_details->item_discount_value = $item_discount_value;
            $order_details->status = $status1;
            $order_details->local_id = $local_id;
            $order_details->order_type = $order_type;
            $order_details->custom_fields = $custom_fields;
            $order_details->table_no = $table_no;
            $order_details->table_merged_with = $merged_tables;
            $order_details->merged_order_unique_ids = $merged_order_unique_ids;
            $order_details->person_no = $person_no;
            $order_details->totalprice = $total;
            $order_details->referance_id = $payment_txn_identifier;
            $order_details->paid_type = $paid_type;
            $order_details->payment_option_id = $payment_option_id;
            $order_details->source_id = $source_id;
            $order_details->discount_value = $discount;
            $order_details->discount_type = $discount_type;
            $order_details->delivery_charge = $delivery_charge;
            $order_details->totalcost_afterdiscount = $totalcost_afterdiscount;
            $order_details->suborder_id = $suborder_id1;
            $order_details->order_unique_id = $order_unique_id;
            $order_details->table_start_date= $table_start_date;
            $order_details->table_end_date= $table_end_date;
            $order_details->invoice_no = $inv_no;
            $order_details->invoice = $invoice;
            $order_details->round_off = $round_off;
            $order_details->payment_status = $payment_status;
            $order_details->itemwise_tax = $itemwise_tax;
            $order_details->itemwise_discount = $itemwise_discount;
            $order_details->add_discount_after_tax = $discount_after_tax;
            $result = $order_details->save();

            if ( $result ) {

                $order_id = $order_details->order_id;
            }

        }

        $payment_arr = array();
        //check payment modes and add order payment modes
        if ( isset($order['payment_modes']) && sizeof($order['payment_modes']) > 0 ) {

            foreach ( $order['payment_modes'] as $mode ) {

                $py_modes = new OrderPaymentMode();
                $py_modes->order_id = $order_id;
                $py_modes->payment_option_id = $mode['mode_id'];
                $py_modes->source_id = $mode['source_id'];
                $py_modes->transaction_id = $mode['transaction_id'];
                $py_modes->amount = $mode['amount'];
                $py_modes->save();

                $payment_opt = PaymentOption::getPaymentOptionById($mode['mode_id']);
                $source_name = Sources::getSourceNameById($mode['source_id']);
                if($source_name == "") {
                    $payment_str = $payment_opt  . " (" . $mode['amount'] . ")";
                }else {
                    $payment_str = $payment_opt . "-" . $source_name . " (" . $mode['amount'] .")";
                }

                array_push($payment_arr,$payment_str);

            }

        } else {

            $py_modes = new OrderPaymentMode();
            $py_modes->order_id = $order_id;
            $py_modes->payment_option_id = $payment_option_id;
            $py_modes->source_id = $source_id;
            $py_modes->transaction_id = $payment_txn_identifier;
            $py_modes->amount = $total;
            $py_modes->save();

            $payment_opt = PaymentOption::getPaymentOptionById($payment_option_id);
            $source_name = Sources::getSourceNameById($source_id);
            if($source_name == "") {
                $payment_str = $payment_opt  . " (" . $total . ")";
            }else {
                $payment_str = $payment_opt . "-" . $source_name . " (" . $total .")";
            }

            array_push($payment_arr,$payment_str);

        }

        if ( $cancel_order == 1 ) {

            if ( $user_id == 0 ) {
                //get user_id from username
                $user_arr = Owner::where('user_name',$name )->first();
                if ( isset($user_arr) && sizeof($user_arr) > 0 ) {
                    $user_id = $user_arr->id;
                }
            }

            $check_cancel = OrderCancellation::where('order_id',$order_id)->first();

            if ( sizeof($check_cancel) == 0 ) {
                $order_cancel_mapper = new OrderCancellation();
                $order_cancel_mapper->outlet_id = $outlet_id;
                $order_cancel_mapper->order_id = $order_id;
                $order_cancel_mapper->reason = $cancel_reason;
                $order_cancel_mapper->created_by = $user_id;
                $order_cancel_mapper->save();
            }


        } else {

            $histroy = new OrderHistory();
            $histroy->order_id = $order_id;
            $histroy->invoice_no = $inv_no;
            $histroy->owner = $name;
            $histroy->order_type = $order_type;
            $histroy->user_mobile_no = $mobile;
            $histroy->address = $address;
            $histroy->total = $total;
            $histroy->payment_modes = implode(", ",$payment_arr);
            $histroy->discount = $discount;
            $histroy->sub_total = $totalcost_afterdiscount;
            $histroy->round_off = $round_off;
            $histroy->taxes = $tax_type;
            $histroy->delivery_charge = $delivery_charge;
            $histroy->save();

        }

            if (isset($order['provider_name']) && $order['provider_name'] != 'COD') {
                $payment = new PaymentMaster();

                if (isset($order['txn_id']) && $order['txn_id'] != '') {
                    $payment->txn_id = $order['txn_id'];
                }

                if (isset($order['payment_id']) && $order['payment_id'] != '') {
                    $payment->payment_id = $order['payment_id'];
                }

                if (isset($order['provider_name']) && $order['provider_name'] != '') {
                    $payment->provider_name = $order['provider_name'];
                }

                if (isset($order['txn_amount']) && $order['txn_amount'] != '') {
                    $payment->txn_amount = $order['txn_amount'];
                }

                if (isset($order['bank_txn_id']) && $order['bank_txn_id'] != '') {
                    $payment->bank_txn_id = $order['bank_txn_id'];
                }

                if (isset($order['txn_type']) && $order['txn_type'] != '') {
                    $payment->txn_type = $order['txn_type'];
                }

                if (isset($order['currency']) && $order['currency'] != '') {
                    $payment->currency = $order['currency'];
                }

                if (isset($order['gateway_name']) && $order['gateway_name'] != '') {
                    $payment->gateway_name = $order['gateway_name'];
                }

                if (isset($order['response_code']) && $order['response_code'] != '') {
                    $payment->response_code = $order['response_code'];
                }

                if (isset($order['response_msg']) && $order['response_msg'] != '') {
                    $payment->response_msg = $order['response_msg'];
                }

                if (isset($order['bank_name']) && $order['bank_name'] != '') {
                    $payment->bank_name = $order['bank_name'];
                }

                if (isset($order['merchant_id']) && $order['merchant_id'] != '') {
                    $payment->merchant_id = $order['merchant_id'];
                }

                if (isset($order['payment_mode']) && $order['payment_mode'] != '') {
                    $payment->payment_mode = $order['payment_mode'];
                }

                if (isset($order['refund_amount']) && $order['refund_amount'] != '') {
                    $payment->refund_amount = $order['refund_amount'];
                }

                if (isset($order['txn_date']) && $order['txn_date'] != '') {
                    $payment->txn_date = date("Y-m-d", strtotime($order['txn_date']));
                }

                if (isset($order['txn_status']) && $order['txn_status'] != '') {
                    $payment->txn_status = $order['txn_status'];
                }

                if (isset($order['check_valid']) && $order['check_valid'] != '') {
                    $payment->check_valid = $order['check_valid'];
                }

                $payment->save();
            }
            /*$check_status = $order_details->save();
            if ($check_status) {
                if (isset($order_ids) && $order_ids != '') {
                        $invoide_detail->order_id = $order_ids;
                    $oid = $order_ids;
                } else {
                    $oid = $order_details->id;
                    $invoide_detail->order_id = $order_details->id;
                }

                $invoide_detail->save();
            }


        } catch (Exception $e) {
            Log::info($e->getMessage());
        }*/
        //$oid = $order_details->id;
        $oid = $order_id;
        $order_date = $order_details->updated_at;
        $order_transactionid = '';
        $order_payment_id = '';
        $order_transactionstatus = '';
        $order_payumoneyid = '';
        $order_payumoney_amount = '';
        $totalcost = '';
        $afterdiscountvalue = '';
        $discount = '';
        $coupondata = '';
        if ($order['moneystatus'] != "COD") {
            if (isset($order['transaction_id'])) {
                $order_transactionid = $order['transaction_id'];
            }
            if (isset($order['transaction_status'])) {
                $order_transactionstatus = $order['transaction_status'];
            }
            if (isset($order['payment_id'])) {
                $order_payment_id = $order['payment_id'];
            }
            if (isset($order['payumoneyid'])) {
                $order_payumoneyid = $order['payumoneyid'];
            }
            if (isset($order['payumoney_amount'])) {
                $order_payumoney_amount = $order['payumoney_amount'];
            }
            PayUMoney::insertpayudetails($oid, $order_transactionid, $order_transactionstatus, $order_payment_id, $order_payumoneyid, $order_payumoney_amount);
        }
        if (isset($order['coupon_applied']) && $order['coupon_applied'] == 'Yes') {
            if (isset($order['coupondata']['id']) && $order['coupondata']['id'] != '') {
                $coupondata = $order['coupondata']['id'];
            }
            if (isset($order['discounted_value']) && $order['discounted_value']) {
                $discount = $order['discounted_value'];
            }
            if (isset($order['totalcost_afterdiscount']) && $order['totalcost_afterdiscount']) {
                $afterdiscountvalue = $order['totalcost_afterdiscount'];
            }
            if (isset($order['total_price']) && $order['total_price']) {
                $totalcost = $order['total_price'];

            }
            if (isset($order['mobile_number']) && $order['mobile_number']) {
                $usermobile = $order['mobile_number'];
            }
            OrderCouponMappers::insertcoupondetails($oid, $coupondata, $discount, $afterdiscountvalue, $totalcost, $usermobile);
        }
        $order_date = Carbon::parse($order_date)->format('d/m/Y H:i:s');

        return array('id'=>$oid,'order_date'=>$order_date,'discounted_value'=>$discount,'orderdetails'=>$order_details);
    }

    public static function insertConsumerDinein($order_id,$order,$status,$suborder_id,$invoice_no =''){

        $address = $combime_address = $mobile = $device_id = $name = $outlet_id = $tax_type = $status1 = $local_id = $order_type = $table_no = $person_no = "";
        $total = $round_off = $paid_type = $source_id = $discount = $totalcost_afterdiscount = $suborder_id1 = $order_unique_id = $table_start_date = $table_end_date = $inv_no = "";
        $invoice = $payment_txn_identifier = '';


        if(isset($order['address'])){
            $address = $order['address'];
        }
        if(isset($order['simpleaddress'])){
            $combime_address = $order['simpleaddress'];
        }
        if(isset($order['mobile_number'])) {
            $mobile = $order['mobile_number'];
        }
        if(isset($order['device_id'])) {
            $device_id = $order['device_id'];
        }
        if(isset( $order['name']) ) {
            $name = $order['name'];
        }
        if(isset($order['restaurant_id'])) {
            $outlet_id = $order['restaurant_id'];
        }
        if(isset($order['tax_type'])) {
            $tax_type = $order['tax_type'];
        }
        if(isset($status)) {
            $status1 = $status;
        }
        if(isset($order['local_id'])) {
            $local_id = $order['local_id'];
        }
        if(isset($order['order_type'])) {
            $order_type = $order['order_type'];
        }
        if(isset($order['table'])) {
            $table_no = $order['table'];
        }
        if(isset($order['table_person'])) {
            $person_no = $order['table_person'];
        }
        if(isset($order['total_price'])) {
            $total = $order['total_price'];
        }
        if(isset($order['txn_id'])) {
            $payment_txn_identifier = $order['txn_id'];
        }
        if(isset($order['round_off'])){
            $round_off = $order['round_off'];
        }
        if(isset($order['paid_type'])) {
            $paid_type = $order['paid_type'];
        }
        if(isset($order['source_type'])) {
            $source_id = $order['source_type'];
        }
        if(isset($order['discounted_value'])) {
            $discount = $order['discounted_value'];
        }
        if(isset($order['totalcost_afterdiscount'])) {
            $totalcost_afterdiscount = $order['totalcost_afterdiscount'];
        }
        if(isset($suborder_id) && $suborder_id!=''){
            $suborder_id1 = $suborder_id;
        }
        if(isset($order['orderuniqueid'])){
            $order_unique_id = $order['orderuniqueid'];
        }
        if(isset($order['start_date'])){
            $table_start_date = date("Y-m-d H:i:s",strtotime($order['start_date']));
        } else {
            $table_start_date = date("Y-m-d H:i:s",strtotime(Carbon::now()));
        }
        if(isset($order['end_date'])){
            $table_end_date = date("Y-m-d H:i:s",strtotime($order['end_date']));
        } else {
            $table_end_date = date("Y-m-d H:i:s",strtotime(Carbon::now()));
        }

        if(isset($order['invoice']) && $order['invoice'] != ''  ){
            if(isset($invoice_no) && $invoice_no!=''){
                $inv_arr = explode("_",$invoice_no);
                $inv_no = $inv_arr[1];
                $invoice = $inv_arr[0];
            }
        }


        try {

            $pay_option_id = 0;$pay_source_id = 0;$pay_status = 0;
            if(isset($order['provider_name']) && strtolower($order['provider_name']) == 'cod') {
                $check_pay_opt = PaymentOption::whereRaw('LOWER(name) = "cash"')->first();
                if(isset($check_pay_opt) && sizeof($check_pay_opt) > 0 ) {
                    $pay_option_id = $check_pay_opt->id;
                }
            } else if ( isset($order['provider_name']) && strtolower($order['provider_name'])== 'paytm') {
                $check_pay_opt = PaymentOption::whereRaw('LOWER(name) = "online"')->first();
                if(isset($check_pay_opt) && sizeof($check_pay_opt) > 0 ) {
                    $pay_option_id = $check_pay_opt->id;
                }
                $check_pay_src = Sources::whereRaw('LOWER(name) = "paytm"')->first();
                if(isset($check_pay_src) && sizeof($check_pay_src) > 0 ) {
                    $pay_source_id = $check_pay_src->id;
                }
                $pay_status = 1;
            } else if ( isset($order['provider_name']) && strtolower($order['provider_name'])== 'payu' ) {
                $check_pay_opt = PaymentOption::whereRaw('LOWER(name) = "online"')->first();
                if(isset($check_pay_opt) && sizeof($check_pay_opt) > 0 ) {
                    $pay_option_id = $check_pay_opt->id;
                }
                $check_pay_src = Sources::whereRaw('LOWER(name) = "payu"')->first();
                if(isset($check_pay_src) && sizeof($check_pay_src) > 0 ) {
                    $pay_source_id = $check_pay_src->id;
                }
                $pay_status = 1;
            } else if ( isset($order['provider_name']) && strtolower($order['provider_name'])== 'upi') {
                $check_pay_opt = PaymentOption::whereRaw('LOWER(name) = "online"')->first();
                if(isset($check_pay_opt) && sizeof($check_pay_opt) > 0 ) {
                    $pay_option_id = $check_pay_opt->id;
                }
                $check_pay_src = Sources::whereRaw('LOWER(name) = "upi"')->first();
                if(isset($check_pay_src) && sizeof($check_pay_src) > 0 ) {
                    $pay_source_id = $check_pay_src->id;
                }
                $pay_status = 1;
            }

                if ( isset($order_id) && $order_id != '' ) {

                    order_details::where('order_id',$order_id)->update([
                                'table_no'=>$table_no,
                                'person_no'=>$person_no,
                                'totalprice'=>$total,
                                'referance_id'=>$payment_txn_identifier,
                                'paid_type'=>$paid_type,
                                'totalcost_afterdiscount'=>$totalcost_afterdiscount,
                                'invoice_no'=>$inv_no,
                                'invoice'=>$invoice,
                                'round_off'=>$round_off,
                                'source_id'=>$pay_source_id,
                                'payment_option_id'=>$pay_option_id,
                                'payment_status'=>$pay_status
                    ]);

                    $order_details = order_details::where('order_id',$order_id)->first();

                //InvoiceDetail::where('order_id',$order_id)->update(['taxes'=>$tax_type,'total'=>$total,'round_off'=>$round_off,'discount'=>$discount,'sub_total'=>$totalcost_afterdiscount]);

            } else {

                    $order_details=new order_details();
                    $order_details->address = $address;
                    $order_details->combine_address=$combime_address;
                    $order_details->user_mobile_number = $mobile;
                    $order_details->device_id = $device_id;
                    $order_details->name = $name;
                    $order_details->outlet_id = $outlet_id;
                    $order_details->tax_type = $tax_type;
                    $order_details->customer_order = 1;
                    $order_details->status = $status1;
                    $order_details->local_id = $local_id;
                    $order_details->order_type = $order_type;
                    $order_details->table_no = $table_no;
                    $order_details->person_no = $person_no;
                    $order_details->totalprice = $total;
                    $order_details->round_off = $round_off;
                    $order_details->referance_id = $payment_txn_identifier;
                    $order_details->paid_type = $paid_type;
                    $order_details->source_id = $source_id;
                    $order_details->discount_value = $discount;
                    $order_details->totalcost_afterdiscount = $totalcost_afterdiscount;
                    $order_details->suborder_id = $suborder_id1;
                    $order_details->order_unique_id = $order_unique_id;
                    $order_details->table_start_date= $table_start_date;
                    $order_details->table_end_date= $table_end_date;
                    $order_details->invoice_no = $inv_no;
                    $order_details->invoice = $invoice;
                    $order_details->source_id = $pay_source_id;
                    $order_details->payment_option_id = $pay_option_id;
                    $order_details->payment_status = $pay_status;
                    $order_details->customer_order = 1;

                    $result = $order_details->save();

                    if ( $result ) {

                        $order_id = $order_details->order_id;

                        /*$invoide_detail=new invoice_detail();
                        $invoide_detail->order_id = $order_id;
                        $invoide_detail->taxes = $tax_type;
                        $invoide_detail->total = $total;
                        $invoide_detail->round_off = $round_off;
                        $invoide_detail->discount = $discount;
                        $invoide_detail->sub_total = $totalcost_afterdiscount;
                        $invoide_detail->save();*/
                    }

                }

            if(isset($order['provider_name']) && $order['provider_name'] != 'COD') {
                $payment = new PaymentMaster();

                if (isset($order['txn_id']) && $order['txn_id'] != '') {
                    $payment->txn_id = $order['txn_id'];
                }

                if (isset($order['payment_id']) && $order['payment_id'] != '') {
                    $payment->payment_id = $order['payment_id'];
                }

                if (isset($order['provider_name']) && $order['provider_name'] != '') {
                    $payment->provider_name = $order['provider_name'];
                }

                if (isset($order['txn_amount']) && $order['txn_amount'] != '') {
                    $payment->txn_amount = $order['txn_amount'];
                }

                if (isset($order['bank_txn_id']) && $order['bank_txn_id'] != '') {
                    $payment->bank_txn_id = $order['bank_txn_id'];
                }

                if (isset($order['txn_type']) && $order['txn_type'] != '') {
                    $payment->txn_type = $order['txn_type'];
                }

                if (isset($order['currency']) && $order['currency'] != '') {
                    $payment->currency = $order['currency'];
                }

                if (isset($order['gateway_name']) && $order['gateway_name'] != '') {
                    $payment->gateway_name = $order['gateway_name'];
                }

                if (isset($order['response_code']) && $order['response_code'] != '') {
                    $payment->response_code = $order['response_code'];
                }

                if (isset($order['response_msg']) && $order['response_msg'] != '') {
                    $payment->response_msg = $order['response_msg'];
                }

                if (isset($order['bank_name']) && $order['bank_name'] != '') {
                    $payment->bank_name = $order['bank_name'];
                }

                if (isset($order['merchant_id']) && $order['merchant_id'] != '') {
                    $payment->merchant_id = $order['merchant_id'];
                }

                if (isset($order['payment_mode']) && $order['payment_mode'] != '') {
                    $payment->payment_mode = $order['payment_mode'];
                }

                if (isset($order['refund_amount']) && $order['refund_amount'] != '') {
                    $payment->refund_amount = $order['refund_amount'];
                }

                if (isset($order['txn_date']) && $order['txn_date'] != '') {
                    $payment->txn_date = date("Y-m-d", strtotime($order['txn_date']));
                }

                if (isset($order['txn_status']) && $order['txn_status'] != '') {
                    $payment->txn_status = $order['txn_status'];
                }

                if (isset($order['check_valid']) && $order['check_valid'] != '') {
                    $payment->check_valid = $order['check_valid'];
                }

                $payment->save();
            }

        }catch (\Exception $e){
            Log::info($e->getMessage());

        }

        $oid=$order_id;
        $order_date=$order_details->updated_at;
        $order_transactionid='';
        $order_payment_id='';
        $order_transactionstatus='';
        $order_payumoneyid='';
        $order_payumoney_amount='';
        $totalcost='';
        $afterdiscountvalue='';
        $discount='';
        $coupondata='';
        if($order['moneystatus']!="COD") {
            if (isset($order['transaction_id'])) {
                $order_transactionid = $order['transaction_id'];
            }
            if (isset($order['transaction_status'])) {
                $order_transactionstatus = $order['transaction_status'];
            }
            if (isset($order['payment_id'])) {
                $order_payment_id = $order['payment_id'];
            }
            if (isset($order['payumoneyid'])) {
                $order_payumoneyid = $order['payumoneyid'];
            }
            if (isset($order['payumoney_amount'])) {
                $order_payumoney_amount = $order['payumoney_amount'];
            }
            PayUMoney::insertpayudetails($oid, $order_transactionid, $order_transactionstatus, $order_payment_id, $order_payumoneyid, $order_payumoney_amount);
        }
        if(isset($order['coupon_applied']) && $order['coupon_applied']=='Yes'){
            if(isset($order['coupondata']['id']) && $order['coupondata']['id']!=''){
                $coupondata=$order['coupondata']['id'];
            }
            if(isset($order['discounted_value']) && $order['discounted_value']){
                $discount=$order['discounted_value'];
            }
            if(isset($order['totalcost_afterdiscount']) && $order['totalcost_afterdiscount']){
                $afterdiscountvalue=$order['totalcost_afterdiscount'];
            }
            if(isset($order['total_price']) && $order['total_price']){
                $totalcost=$order['total_price'];

            }
            if(isset($order['mobile_number']) && $order['mobile_number']){
                $usermobile=$order['mobile_number'];
            }
            OrderCouponMappers::insertcoupondetails($oid,$coupondata,$discount,$afterdiscountvalue,$totalcost,$usermobile);
        }
        $order_date=Carbon::parse($order_date)->format('d/m/Y H:i:s');
        return array('id'=>$oid,'order_date'=>$order_date,'discounted_value'=>$discount,'orderdetails'=>$order_details);
    }

    public static function getorderdetailsbyrestaurantid($id){
        $ord_details = DB::table('orders')
            ->select('address', 'user_mobile_number as mobile_number', 'name', 'device_id', 'order_id as id','suborder_id')
            ->where('outlet_id', $id)
            ->groupBy('device_id', 'created_at')
            ->get();
        return $ord_details;
    }

    public static function getorderbystatusandorderid($currentstatus,$orderid){
           $ordstat=order_details::where('status',$currentstatus)->where('order_id',$orderid)->first();
           return $ordstat;
    }

    public static function updateorderstatus($currentstaus,$orderid,$newstatus){
        DB::table('orders')->where('status',$currentstaus)->where('order_id',$orderid)->update(array('status' => $newstatus));
    }


    public static function sendpushnotification($device_id,$currentstatus,$newstatus,$orderid,$order_date,$order_id){
        //$apiKey = "AIzaSyDLQh64Zq1C4L9yCPMAlF3owJxxGtHO2Ig";
        $apiKey = "AIzaSyCf2SG9LH_CPqvV4OslV3TzegLczHWh7pQ";

        $deviceid = array();

        $date = str_replace('/', '-', $order_date);

        $orddate=date("F j, Y g:i a",strtotime($date));

        array_push($deviceid,$device_id);

        // Replace with real client registration IDs
        $registrationIDs = $deviceid;

        $message = "Status is Changed from ". ucfirst($currentstatus) ." to ". ucfirst($newstatus)." status";
        // $userid = $this->authUser_NameSpace->user_id;

        // Set POST variables
        //$url = 'https://android.googleapis.com/gcm/send';
        $url = 'https://fcm.googleapis.com/fcm/send';


        $fields = array(
            'registration_ids'  => $registrationIDs,
            'priority'=>'high',
            'data' => array("message" => $message,
                "status"=>ucfirst($currentstatus),
                "server_id"=>$order_id,
                "type"=>'order-status',
                "order_date"=>$orddate
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
        curl_setopt( $ch, CURLOPT_URL, $url );

        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        // Execute post
        $result = curl_exec($ch);
        $jsonArray = json_decode($result);

        if($jsonArray->canonical_ids != 0 || $jsonArray->failure != 0){
            if(!empty($jsonArray->results))
            {
                for($i = 0 ; $i<count($jsonArray->results) ; $i++){
                    $result = $jsonArray->results[$i];
                    if(isset($result->message_id) && isset($result->registration_id))
                    {
                        // You should replace the original ID with the new value (canonical ID) in your server database
                    }
                    else
                    {
                        if(isset($result->error))
                        {
                            switch ($result->error)
                            {
                                case "NotRegistered":
                                case "InvalidRegistration":
                                    // You should remove the registration ID from your server database
                                    break;
                                case "Unavailable":
                                case "InternalServerError":
                                    // You could retry to send it late in another request.
                                    break;
                                case "MissingRegistration":
                                    // Check that the request contains a registration ID
                                    break;
                                case "InvalidPackageName":
                                    // Make sure the message was addressed to a registration ID whose package name matches the value passed in the request.
                                    break;
                                case "MismatchSenderId":
                                    // Invalid SENDER_ID
                                    break;
                                case "MessageTooBig":
                                    // Check that the total size of the payload data included in a message does not exceed 4096 bytes
                                    break;
                                case "InvalidDataKey":
                                    // Check that the payload data does not contain a key that is used internally by GCM.
                                    break;
                                case "InvalidTtl":
                                    // Check that the value used in time_to_live is an integer representing a duration in seconds between 0 and 2,419,200.
                                    break;
                                case "DeviceMessageRateExceed":
                                    // Reduce the number of messages sent to this device
                                    break;

                            }
                        }
                    }
                }
            }
        }

        curl_close($ch);
    }

    public static function sendiospushnotification($device_id,$currentstatus,$newstatus,$orderid,$order_date){
        // Put your device token here (without spaces):
        //$deviceToken = '0f744707bebcf74f9b7c25d48e3358945f6aa01da5ddb387462c7eaf61bbad78';

// Put your private key's passphrase here:
        $passphrase = 'govind123';

// Put your alert message here:
        $message = "Status is Changed from ". ucfirst($currentstatus) ." to ". ucfirst($newstatus)." status";

////////////////////////////////////////////////////////////////////////////////

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', '/home/web/foodklub/app/FoodKlub_ck_development.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);

        echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'status' => $newstatus,
            'server_id' => "$orderid",
            'sound' => 'default'
            );


// Encode the payload as JSON
        $payload = json_encode($body);

// Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $device_id) . pack('n', strlen($payload)) . $payload;

// Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        if (!$result)
            echo 'Message not delivered' . PHP_EOL;
        else
            echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
        fclose($fp);
    }

    public static function sendownernotification($device_id,$order_id,$outlet_id,$table_no){
        $apiKey = "AIzaSyCf2SG9LH_CPqvV4OslV3TzegLczHWh7pQ";

        $deviceid = array();

        //$deviceid = explode(",",$device_id);
        //array_push($deviceid,$device_id);
        Log::info($device_id);
        // Replace with real client registration IDs
        $registrationIDs = $device_id;

        $message = "Table no. ".$table_no." has ordered new items.";
        // $userid = $this->authUser_NameSpace->user_id;

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'registration_ids'  => $registrationIDs,
            'priority'=>'high',
            'data' => array("message" => $message,
                'server_id'=>1,
                'order_id'=>$order_id,
                'outlet_id'=>"$outlet_id",
                'table_no'=>$table_no
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
        curl_setopt( $ch, CURLOPT_URL, $url );

        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        // Execute post
        $result = curl_exec($ch);

        if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
        // Close connection

        curl_close($ch);

        return true;
    }
    public static function sendAttendMeNotification($device_id,$table_no,$outlet_id){

        $apiKey = "AIzaSyCf2SG9LH_CPqvV4OslV3TzegLczHWh7pQ";

        //$deviceid = array();

        //$deviceid = explode(",",$device_id);
        //array_push($deviceid,$device_id);
        Log::info($device_id);
        // Replace with real client registration IDs
        $registrationIDs = $device_id;

        $message = "Table No. ".$table_no." is calling";
        // $userid = $this->authUser_NameSpace->user_id;

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'registration_ids'  => $registrationIDs,
            'priority'=>'high',
            'data' => array("message" => $message,
                'type'=>'attendme',
                'outlet_id'=>"$outlet_id",
                'table_no'=>$table_no
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
        curl_setopt( $ch, CURLOPT_URL, $url );

        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        // Execute post
        $result = curl_exec($ch);

        if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
        // Close connection

        curl_close($ch);

        return true;

    }

    public static function sendPaidBillNotification($device_id,$table_no,$outlet_id) {
        $apiKey = "AIzaSyCf2SG9LH_CPqvV4OslV3TzegLczHWh7pQ";

        $deviceid = array();

        //$deviceid = explode(",",$device_id);
        //array_push($deviceid,$device_id);
        //Log::info($deviceid);
        // Replace with real client registration IDs
        $registrationIDs = $device_id;

        $message = "Table No. ".$table_no." is requesting bill";
        // $userid = $this->authUser_NameSpace->user_id;

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'registration_ids'  => $registrationIDs,
            'priority'=>'high',
            'data' => array("message" => $message,
                'type'=>'attendme',
                'outlet_id'=>"$outlet_id",
                'table_no'=>$table_no
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
        curl_setopt( $ch, CURLOPT_URL, $url );

        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        // Execute post
        $result = curl_exec($ch);

        if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
        // Close connection

        curl_close($ch);

        return true;
    }

    public static function sendcancelnotification($device_id,$reason,$order_id,$order_date){
        $apiKey = "AIzaSyCf2SG9LH_CPqvV4OslV3TzegLczHWh7pQ";

        $deviceid = array();
        $date = str_replace('/', '-', $order_date);
        $orddate=date("F j, Y g:i a",strtotime($date));
        array_push($deviceid,$device_id);
        //print_r($device_id);exit;
        // Replace with real client registration IDs
        $registrationIDs = $deviceid;

        $message = "Your order $order_id is cancelled due to $reason";
        // $userid = $this->authUser_NameSpace->user_id;

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'registration_ids'  => $registrationIDs,
            'priority'=>'high',
            'data' => array("message" => $message,
                'server_id'=>$order_id,
                "status"=>'Cancelled',
                "order_date"=>$orddate
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
        curl_setopt( $ch, CURLOPT_URL, $url );

        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        // Execute post
        $result = curl_exec($ch);
        echo $result;
        if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
        // Close connection

        curl_close($ch);
    }

    public static function toConsumerRemoveKotNotification($order_id,$item_id,$item_name,$item_unique_id,$reason,$device_id) {

        $apiKey = "AIzaSyCf2SG9LH_CPqvV4OslV3TzegLczHWh7pQ";

        $registrationIDs = explode(',',$device_id);

        $message = $item_name." is removed from your order";
        // $userid = $this->authUser_NameSpace->user_id;

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'registration_ids'  => $registrationIDs,
            'priority'=>'high',
            'data' => array("message" => $message,
                'server_id'=>$order_id,
                "status"=>'Cancelled',
                "item_id"=>$item_id,
                "item_unique_id"=>$item_unique_id,
                "type"=>"item-reject",
                "reason"=>$reason
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
        curl_setopt( $ch, CURLOPT_URL, $url );

        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        // Execute post
        $result = curl_exec($ch);
        echo $result;
        if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
        // Close connection

        curl_close($ch);

    }

    public static function toConsumerCancelOrderNotification($order_id,$items,$reason,$device_id) {

        $apiKey = "AIzaSyCf2SG9LH_CPqvV4OslV3TzegLczHWh7pQ";

        $registrationIDs = explode(',',$device_id);

        $message = "Your order has been cancelled";
        // $userid = $this->authUser_NameSpace->user_id;

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'registration_ids'  => $registrationIDs,
            'priority'=>'high',
            'data' => array("message" => $message,
                'server_id'=>$order_id,
                "status"=>'Cancelled',
                "order_items"=>$items,
                "reason"=>$reason,
                "type"=>"cancel-order"
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
        curl_setopt( $ch, CURLOPT_URL, $url );

        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        // Execute post
        $result = curl_exec($ch);
        echo $result;
        if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
        // Close connection

        curl_close($ch);

    }

    public static function toConsumerAcceptKotNotification( $order_id, $order_items, $device_id ) {

        $apiKey = "AIzaSyCf2SG9LH_CPqvV4OslV3TzegLczHWh7pQ";

        $registrationIDs = explode(',',$device_id);

        $message = "Your order has been confirmed";
        // $userid = $this->authUser_NameSpace->user_id;

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'registration_ids'  => $registrationIDs,
            'priority'=>'high',
            'data' => array("message" => $message,
                'server_id'=>$order_id,
                "status"=>'Confirmed',
                "order_items"=>$order_items,
                "type"=>"order-confirmed"
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
        curl_setopt( $ch, CURLOPT_URL, $url );

        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        // Execute post
        $result = curl_exec($ch);
        //echo $result;
        if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
        // Close connection

        curl_close($ch);

    }

    public static function UpiTransactionStatusChangeNotification($device_id,$table_no,$txnId,$billNumber,$oldStatus,$oldStatusString,$status,$statusString,$amount,$playerVa,$note,$initDate ) {

        $apiKey = "AIzaSyCf2SG9LH_CPqvV4OslV3TzegLczHWh7pQ";

        $deviceid = array();

        //$deviceid = explode(",",$device_id);
        //array_push($deviceid,$device_id);
        Log::info($device_id);
        // Replace with real client registration IDs
        if ( isset($device_id) && sizeof($device_id) > 0 ) {
            $registrationIDs = $device_id;

            // Set POST variables
            $url = 'https://fcm.googleapis.com/fcm/send';

            $fields = array(
                'registration_ids'  => $registrationIDs,
                'priority'=>'high',
                'data' => array(
                    'txnId'=>$txnId,
                    'billNumber'=>$billNumber,
                    'oldStatus'=>$oldStatus,
                    'oldStatusString'=>$oldStatusString,
                    'status'=>$status,
                    'statusString'=>$statusString,
                    'amount'=>$amount,
                    'playerVa'=>$playerVa,
                    'note'=>$note,
                    'initDate'=>$initDate,
                    'table_no'=>$table_no,
                    'action'=>'UPIPayment'
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
            curl_setopt( $ch, CURLOPT_URL, $url );

            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

            // Execute post
            $result = curl_exec($ch);

            if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
            // Close connection

            curl_close($ch);

            return true;
        }

    }

    public static function searchorder($date,$order_id,$phone_number,$name,$status,$address,$table,$ordertype,$array){
        $orddetails=DB::table('orders');
        if($date!=null && $date!=''){
            $starting_time= new \DateTime($date);
            $ending_time= new \DateTime($date);
            $ending_time->modify('+23 hours +59 minutes +59 seconds');
            $starting=get_object_vars($starting_time);
            $ending=get_object_vars($ending_time);
            $orddetails->where('created_at', '>=',new Carbon($starting['date']))->where('created_at', '<=',new Carbon($ending['date']))->whereIn('outlet_id',$array)->orderBy('created_at', 'desc');
        }
        if(isset($order_id) && $order_id!=""){
            $orddetails->Where('order_id','like','%'.$order_id);
        }
        if(isset($phone_number) && $phone_number!=""){
            $orddetails->where('mobile_number','=',$phone_number);
        }
        if(isset($name) && $name!=""){
            $orddetails->where('name','like',"%".$name."%");
        }
        if(isset($status) && $status!="" && $status!="All"){
            $orddetails->where('status','like',"%".$status."%");
        }
        if(isset($address) && $address!=""){
            $orddetails->where('address','like',"%".$address."%");
        }
        if(isset($table) && $table!=""){
            $orddetails->where('table_no','=',$table);
        }
        if(isset($ordertype) && $ordertype!=""){
            $orddetails->where('order_type','=',$ordertype);
        }

        $ord=$orddetails->get();
        return $ord;
    }

    public static function getordernotification($datetime,$arrayofrestids){
        $orddetails=order_details::where('created_at', '>=',new Carbon($datetime))->where('read','!=',1)->whereIn('outlet_id',$arrayofrestids)->orderBy('created_at', 'desc')->get();
        return $orddetails;
    }

    public static function updateorderreadstatus(){
        DB::table('orders')->update(array('read'=>1));
    }

    public static function generateinvoicenumber($outlet_id,$order_id) {

        $outlet = Outlet::Outletbyid2($outlet_id);

        $code = $outlet->code;
        $inv_digit = $outlet->invoice_digit;
        $date = date('Ymd');
        $number = sprintf("%0".$inv_digit."d", $order_id);

        $inv_no = $code."".$date."".$number;

        return $inv_no;

    }

    public static function getLastOrderTime(){
        $outlet_ids = array();

        $outlet = OutletMapper::getOutletMapperByOwnerId(Auth::id());
        $sess_outlet_id = Session::get('outlet_session');

        if(isset($sess_outlet_id) && $sess_outlet_id != '') {
            $outlet_ids[0] = $sess_outlet_id;
        }else if( isset($outlet) && sizeof($outlet) > 0 ) {
            foreach ($outlet as $o_key => $o_val) {
                $outlet_ids[] = $o_val->id;
            }
        }
        $maxOrder = 0;
        $maxOrder = order_details::whereIn('outlet_id',$outlet_ids)->max('order_id');
        if(isset($maxOrder) && $maxOrder>0){
            $time = order_details::where('order_id',$maxOrder)->get();
            $last_order_time = $time[0]['table_end_date'];
        }else{
            $last_order_time = 'No Orders Found';
        }
        return $last_order_time;
    }

    public static function lastOrderSequence($outlet_id){

        $outlet = Outlet::find($outlet_id);
        $outlet_settings = OutletSetting::checkAppSetting($outlet_id,"orderNoReset");

        if(isset($outlet) && sizeof($outlet)>0){
            $invoice_array = array();
            if($outlet->invoice_prefix != ""){
                $invoice_prefix = json_decode($outlet->invoice_prefix);
                foreach ($invoice_prefix as $type=>$prefix) {

                    $condition = '1=1';
                    $to = (new Carbon(date('Y-m-d')))->endOfDay();
                    if (OutletSetting::checkAppSetting($outlet_id,"orderNoReset")  == 1) {
                        $from = (new Carbon(date('Y-m-d', strtotime('0 days'))))->startOfDay();
                        $condition = "orders.table_start_date BETWEEN '$from' AND '$to'";
                    } else {
                        $from = (new Carbon(date('Y-m-d', strtotime('-2 days'))))->startOfDay();
                        $condition = "orders.table_start_date BETWEEN '$from' AND '$to'";
                    }


                    $code = $outlet->ot_code;
                    $prefix_check = json_decode($outlet->invoice_prefix);
                    if (isset($prefix_check) && sizeof($prefix_check) > 0) {
                        $condition .= " && order_type='$type'";
                        $code = $prefix_check->$type;
                    }

                    $check_invoice_no = order_details::where('outlet_id', $outlet_id)
                        ->whereRaw($condition)
                        ->get();

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

                    $invoice_array[$type] = $invoice_no;

                }

            } elseif(isset($outlet->code) && sizeof($outlet->code) > 0 ) {

                $condition = '1=1';
                $to = (new Carbon(date('Y-m-d')))->endOfDay();

                if ( OutletSetting::checkAppSetting($outlet_id,"orderNoReset")  == 1 ) {
                    $from = (new Carbon(date('Y-m-d', strtotime('0 days'))))->startOfDay();
                    $condition = "orders.table_end_date BETWEEN '$from' AND '$to'";
                } else {
                    $from = (new Carbon(date('Y-m-d', strtotime('-2 days'))))->startOfDay();
                    $condition = "orders.table_end_date BETWEEN '$from' AND '$to'";
                }

                $check_invoice_no = order_details::where('outlet_id',$outlet_id)
                    ->whereRaw($condition)
                    ->get();

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

                $invoice_array["last_invoice"] = $invoice_no;

            }
        }else{
            $invoice_array["error"] = "No outlet found";

        }

        return $invoice_array;
    }

    #TODO: Sync orders to ZOHO
    public static function syncZohoOrders( $outlet_id, $from, $to ) {

        $ot = Outlet::where('id',$outlet_id)->first();

        $email = $ot->zoho_username;
        $password = $ot->zoho_password;
        $token = '';

        if ( isset($email) && isset($password) && $email != '' && $password != '' ) {

            if ( isset($ot->zoho_token) && $ot->zoho_token != '' ) {

                $token = $ot->zoho_token;

            } else {

                $token_string = Utils::getZohoAuthToken($email,$password);
                if (isset($token_string) && $token_string != '') {

                    $token_str = explode(PHP_EOL, $token_string);
                    if (isset($token_str[2]) && sizeof($token_str) > 0) {
                        $token_arr = explode('=', $token_str[2]);
                        $token = $token_arr[1];
                    }

                    //update zoho token in database
                    if( $token != '') {
                        $ot->zoho_token = $token;
                        $ot->save();
                    }

                }

            }


            $orders = order_details::where('orders.table_end_date', '>=', $from)
                ->where('orders.table_end_date', '<=', $to)
                ->where('orders.outlet_id', '=', $ot->id)
                ->orderBy('orders.order_id', 'desc')
                ->where('orders.cancelorder', '!=', 1)
                ->where('orders.invoice_no', '!=', '')
                ->where('orders.zoho_sync', '!=', 1)
                ->orderBy('orders.order_id', 'desc')
                ->get();

            if (isset($orders) && sizeof($orders) > 0) {

                $i = 0;
                foreach ($orders as $ord) {

                    $url = "https://invoice.zoho.com/api/v3/invoices";
                    $data = array();

                    $order_items = OrderItem::join('menus as m','m.id','=','order_items.item_id')
                        ->select('order_items.*','m.item_code as item_code')
                        ->where('order_id', $ord->order_id)->get();

                    if (isset($order_items) && sizeof($order_items) > 0) {

                        $itm_arr = array();
                        $data['line_items'] = array();

                        $check_item_code = 0;$itm_name = '';$discount = 0;

                        //devide discount according to number of items
                        $discount_length = sizeof($order_items);
                        if ( $ord->discount_value > 0 ) {
                            $discount = $ord->discount_value / $discount_length;
                        }
                        foreach ($order_items as $itm) {

                            if ( $itm->item_code == '') {

                                $check_item_code = 1;
                                if ( $itm_name == '') {
                                    $itm_name = $itm->item_name;
                                } else {
                                    $itm_name .=", ".$itm->item_name;
                                }

                            }

                            if ( $discount > 0 ) {
                                $itm_arr['discount'] = $discount;
                            }

                            $itm_arr['name'] = $itm->item_code;
                            $itm_arr['description'] = $itm->item_name;
                            $itm_arr['rate'] = $itm->item_price;
                            $itm_arr['quantity'] = $itm->item_quantity;
                            $itm_arr['item_total'] = $itm->item_total;

                            array_push($data['line_items'], $itm_arr);
                        }

                        //if itemcode not available than return to next order
                        if ( $check_item_code == 1 ) {
                            $ord->zoho_sync_msg = "Item code not available for ".$itm_name;
                            $ord->save();
                            continue;
                        }
                    }

                    $data['invoice_number'] = $ord->invoice_no;
                    $data['date'] = date('Y-m-d', strtotime($ord->table_end_date));
                    $data['due_date'] = date('Y-m-d', strtotime($ord->table_end_date));

                    //get payment mode
                    $pay_mode = 'UnPaid';
                    if ( isset($ot->payment_option_identifier) && $ot->payment_option_identifier != '') {

                        $identifier_arr = json_decode($ot->payment_option_identifier,true);
                        if ( isset($identifier_arr) && sizeof($identifier_arr) > 0 ) {

                            $payment_mode = OrderPaymentMode::where('order_id',$ord->order_id)->get();
                            if ( isset($payment_mode) && sizeof($payment_mode) > 0 ) {

                                if ( isset($identifier_arr['zoho_payment_ids'][$payment_mode[0]->payment_option_id][$payment_mode[0]->source_id])) {
                                    $data['customer_id'] = $identifier_arr['zoho_payment_ids'][$payment_mode[0]->payment_option_id][$payment_mode[0]->source_id];
                                } else {
                                    $ord->zoho_sync_msg = "Payment option or Source identifier not added";
                                    $ord->save();
                                    continue;
                                }

                            }

                        } else {
                            $ord->zoho_sync_msg = "Payment option or Source identifier not added";
                            $ord->save();
                            continue;
                        }

                    } else {
                        $ord->zoho_sync_msg = "Payment option or Source identifier not added";
                        $ord->save();
                        continue;
                    }

                    if ( $token == '') {
                        $ord->zoho_sync_msg = "Authorization error";
                        $ord->save();
                        break;
                    }


                    $data1 = array(
                        "authtoken" => $token,
                        "organization_id" => $ot->zoho_organization_id,
                        "JSONString" => json_encode($data),
                        "send" => 'false'
                    );

                    // Open connection
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);

                    // Set the url, number of POST vars, POST data
                    curl_setopt($ch, CURLOPT_URL, $url);

                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data1));

                    // Execute post
                    $result = curl_exec($ch);

                    if (curl_errno($ch)) {
                        $ord->zoho_sync_msg = curl_error($ch);
                        $ord->save();
                        continue;
                    }
                    // Close connection
                    curl_close($ch);
                    //echo $result;
                    if (isset($result) && $result != '') {

                        $check_result = json_decode($result);

                        //update zoho sync value
                        if ($check_result->code == 0) {
                            $ord->zoho_sync = 1;
                            $ord->zoho_sync_msg = $check_result->message;
                            $ord->save();

                        } else {
                            $ord->zoho_sync_msg = $check_result->message;
                            $ord->save();
                        }

                    } else {
                        $ord->zoho_sync_msg = "There is some error occurred.";
                        $ord->save();
                    }

                    $i++;
                    if ($i == 98) {
                        sleep(60);
                    }

                }
            }

        }

    }

}
