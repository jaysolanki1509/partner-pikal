<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentMaster extends Model {

    protected $table = 'paymentmaster';

    public static function addRecord($txn_id, $payment_id, $provider_name, $txn_amount, $bank_txn_id,
            $txn_type, $currency, $gateway_name, $response_code, $response_msg, $bank_name, $merchant_id, $payment_mode, $refund_amount,
            $txn_date, $txn_status, $check_valid){

        $pm = new PaymentMaster();
        $pm->txn_id = $txn_id;
        $pm->payment_id = $payment_id;
        $pm->provider_name = $provider_name;
        $pm->txn_amount = $txn_amount;
        $pm->bank_txn_id = $bank_txn_id;
        $pm->txn_type = $txn_type;
        $pm->currency = $currency;
        $pm->gateway_name = $gateway_name;
        $pm->response_code = $response_code;
        $pm->response_msg = $response_msg;
        $pm->bank_name = $bank_name;
        $pm->merchant_id = $merchant_id;
        $pm->payment_mode = $payment_mode;
        $pm->refund_amount = $refund_amount;
        $pm->txn_date = $txn_date;
        $pm->txn_status = $txn_status;
        $pm->check_valid = $check_valid;
    }

}
