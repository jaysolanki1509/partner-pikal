<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;
class PayUMoney extends Model {

    protected $table ='payumoneys';

    public static function insertpayudetails($order_id,$transaction_id,$transaction_status,$transaction_paymentid,$transaction_payumoneyid,$payumoney_amount){
        $payumoney=new PayUMoney();
        if(isset($order_id) && $order_id!=''){
            $payumoney->order_id=$order_id;
        }
        if(isset($transaction_id) && $transaction_id!=''){
            $payumoney->transaction_id=$transaction_id;
        }
        if(isset($transaction_status) && $transaction_status!=''){
            $payumoney->status=$transaction_status;
        }
        if(isset($transaction_paymentid) && $transaction_paymentid!=''){
            $payumoney->payment_id=$transaction_paymentid;
        }
        if(isset($transaction_payumoneyid) && $transaction_payumoneyid!=''){
            $payumoney->payumoney_id=$transaction_payumoneyid;
        }
        if(isset($payumoney_amount) && $payumoney_amount!=''){
            $payumoney->amount=$payumoney_amount;
        }
        $payumoney->save();
        return;
    }
}