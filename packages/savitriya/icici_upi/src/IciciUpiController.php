<?php
namespace Savitriya\Icici_upi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Savitriya\Icici_upi\ConfigException;
use Savitriya\Icici_upi\InvalidInputException;
use Savitriya\Icici_upi\IciciUpiTxn;
use Savitriya\Icici_upi\IciciUpiTxnLogs;
use Savitriya\Icici_upi\UpiTransactionInitiatedEvent;
use Savitriya\Icici_upi\UpiTransactionStatusChangeEvent;
use Savitriya\Icici_upi\IciciUpi;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class IciciUpiController extends Controller {

    public function collectPay(Request $request) {
        $inputs = array();
        $inputs['subMerchantId'] = $request->input ('subMerchantId');
        $inputs['subMerchantName'] = $request->input ('subMerchantName');
        $inputs['terminalId'] = $request->input ('terminalId');
        $inputs['billNumber'] = $request->input ('billNumber');
        $inputs['amount'] = $request->input ('amount');
        $inputs['payerVa'] = $request->input ('payerVa');
        $inputs['note'] = $request->input ('note');

        try {
            $upi = new IciciUpi();
            $response = $upi->collectPay($inputs);

            $output = array();
            $output['billno'] = $response['billNumber'];
            $output['txnid'] = $response['merchantTranId'];
            $output['status'] = 0;

            if ($response['response'] == '92')
                $output['success'] = true;
            else
                $output['success'] = false;
            $output['error_code'] = 0;
            $output['error_msg'] = "";
            $output['upi_response_code'] = $response['response'];
            $output['upi_response_msg'] = $response['message'];

            return response(json_encode($output), 200)->header('Content-Type', 'application/json');
        }
        catch (\Exception $ex) {
            $error_code = $ex->getCode();
            $error_msg = $ex->getMessage();

            $output = array();
            $output['success'] = false;
            $output['error_code'] = $error_code;
            $output['error_msg'] = $error_msg;
            $output['upi_response_code'] = "";
            $output['upi_response_msg'] = "";

            return response(json_encode($output), $error_code)->header('Content-Type', 'application/json');
        }
    }

    public function transacrtionStatus (Request $request) {
        $inputs = array();
        $inputs['subMerchantId'] = $request->input ('subMerchantId');
        $inputs['terminalId'] = $request->input ('terminalId');
        $inputs['txnId'] = trim ($request->input ('txnId'));
        $inputs['billNo'] = trim ($request->input ('billNumber'));

        if ($inputs['billNo'] != '' && $inputs['txnId'] == '') {
            try {
                $txnObj = IciciUpiTxn::where('bill_no', '=', $inputs['billNo'])->firstOrFail();
                $inputs['txnId'] = $txnObj->txnid;
                unset ($inputs['billNo']);
            }
            catch (ModelNotFoundException $ex) {
                $error_code = $ex->getCode();
                $error_msg = $ex->getMessage();

                if ($error_code == 0)
                    $error_code = 412;

                $output = array();
                $output['success'] = false;
                $output['error_code'] = $error_code;
                $output['error_msg'] = "Invalid Bill No. No transaction found using bill no \"{$inputs['billNo']}\"";
                $output['upi_response_code'] = "";
                $output['upi_response_msg'] = "";

                return response(json_encode($output), $error_code)->header('Content-Type', 'application/json');
            }
        }

        try {
            $upi = new IciciUpi();
            $response = $upi->transacrtionStatus ($inputs);

            $output = array();
            $output['billno'] = $response['billNumber'];
            $output['txnid'] = $response['merchantTranId'];
            $status = strtolower($response['status']);

            if ($status == 'pending')
                $output['status'] = 0;
            else if ($status == 'success')
                $output['status'] = 1;
            else if ($status == 'failure')
                $output['status'] = 2;

            $output['success'] = ($response['success'] == 'true' ? true : false);
            $output['error_code'] = 0;
            $output['error_msg'] = "";
            $output['upi_response_code'] = $response['response'];
            $output['upi_response_msg'] = $response['message'];

            return response(json_encode($output), 200)->header('Content-Type', 'application/json');
        }
        catch (\Exception $ex) {
            $error_code = $ex->getCode();
            $error_msg = $ex->getMessage();

            $output = array();
            $output['success'] = false;
            $output['error_code'] = $error_code;
            $output['error_msg'] = $error_msg;
            $output['upi_response_code'] = "";
            $output['upi_response_msg'] = "";

            return response(json_encode($output), $error_code)->header('Content-Type', 'application/json');
        }
    }

    public function iciciUpiCallback (Request $request) {
        $bodyContent = $request->getContent();

        try {
            $upi = new IciciUpi();
            $response = $upi->processIciciUpiCallback ($bodyContent);
        }
        catch (\Exception $ex) {

        }

        return response("Requested Accepted", 200)->header('Content-Type', 'text/plain');
    }
}