<?php
namespace Savitriya\Icici_upi;

use Savitriya\Icici_upi\ConfigException;
use Savitriya\Icici_upi\InvalidInputException;
use Savitriya\Icici_upi\IciciUpiTxn;
use Savitriya\Icici_upi\IciciUpiTxnLogs;
use Savitriya\Icici_upi\UpiTransactionInitiatedEvent;
use Savitriya\Icici_upi\UpiTransactionStatusChangeEvent;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class IciciUpi {
    protected $mApiUrl;
    protected $mApiCollectPayEndpoint;
    protected $mApiTransactionStatusEndpoint;
    protected $mMerchantId;
    protected $mMerchantName;
    protected $terminalId;
    protected $iciciPublicKey;
    protected $foodklubPrivateKey;
    protected $apiUserAgent = "Foodklub Web App 1.0";
    protected $debug;
    protected $requestHeaders;
    protected $responseHeaders;
    protected $requestParams;
    protected $encryptedRequestParams;
    protected $encryptedResponse;
    protected $response;

    public function __construct ($debug = false) {
        $this->mApiUrl = config('icici_upi.api_url');
        $this->mApiCollectPayEndpoint = config('icici_upi.api_endpoint_collect_pay');
        $this->mApiTransactionStatusEndpoint = config('icici_upi.api_endpoint_transaction_status');
        $this->mMerchantId = config('icici_upi.merchant_id');
        $this->mMerchantName = config('icici_upi.merchant_name');
        $this->terminalId = config('icici_upi.terminal_id');
        $this->iciciPublicKey = config('icici_upi.icici_public_key');
        $this->foodklubPrivateKey = config('icici_upi.foodklub_private_key');
        $this->debug = $debug;

        if (!isset ($this->mApiUrl) || $this->mApiUrl == '')
            throw new ConfigException ("ICICI UPI API URL is missing", 500);
        if (!isset ($this->mApiCollectPayEndpoint) || $this->mApiCollectPayEndpoint == '')
            throw new ConfigException ("ICICI UPI Collect Pay Endpoint is missing", 500);
        if (!isset ($this->mApiTransactionStatusEndpoint) || $this->mApiTransactionStatusEndpoint == '')
            throw new ConfigException ("ICICI UPI Transaction Status Check Endpoint is missing", 500);
        if (!isset ($this->mMerchantId) || $this->mMerchantId == '')
            throw new ConfigException ("ICICI UPI Merchant ID is missing", 500);
        if (!isset ($this->mMerchantName) || $this->mMerchantName == '')
            throw new ConfigException ("ICICI UPI Merchant Name is missing", 500);
        if (!isset ($this->iciciPublicKey) || $this->iciciPublicKey == '')
            throw new ConfigException ("ICICI Public Key is missing", 500);
        if (!isset ($this->foodklubPrivateKey) || $this->foodklubPrivateKey == '')
            throw new ConfigException ("Foodklub Private Key is missing", 500);
        if (!file_exists($this->iciciPublicKey))
            throw new ConfigException ("ICICI Public Key does not exists", 500);
        if (!file_exists($this->foodklubPrivateKey))
            throw new ConfigException ("Foodklub Private Key does not exists", 500);
    }

    protected function encrypt ($string) {
        $success = openssl_public_encrypt ($string, $encryptedString, file_get_contents($this->iciciPublicKey));
        if (!$success)
            throw new ConfigException ("Failed to encrypt using ICICI Public Key", 500);

        return base64_encode($encryptedString);
    }

    protected function decrypt ($string) {
        $success = openssl_private_decrypt (base64_decode($string), $decryptedString, file_get_contents($this->foodklubPrivateKey));
        if (!$success)
            throw new ConfigException ("Failed to decrypt using Foodklub Private Key", 500);

        return $decryptedString;
    }

    protected function request ($requestEndpoint, $requestParams) {
        $s = curl_init();

        curl_setopt($s, CURLOPT_URL, $this->mApiUrl . '/' . $requestEndpoint);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($s, CURLOPT_POST, true);
        curl_setopt($s, CURLOPT_POSTFIELDS, $requestParams);
        curl_setopt($s, CURLOPT_USERAGENT, $this->apiUserAgent);
        curl_setopt($s, CURLOPT_ENCODING, "");
        curl_setopt($s, CURLOPT_HTTPHEADER, array(
            "Content-Type: text/plain",
            'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: en-US,en;q=0.8,hi;q=0.6',
            'Cache-Control: no-cache'
        ));

        curl_setopt($s, CURLOPT_HEADER, true);
        curl_setopt($s, CURLINFO_HEADER_OUT, true);

        $full_response = curl_exec($s);

        $request_headers = curl_getinfo($s, CURLINFO_HEADER_OUT);
        $header_size = curl_getinfo($s, CURLINFO_HEADER_SIZE);
        $response_headers = substr($full_response, 0, $header_size);
        $response = substr($full_response, $header_size);

        curl_close($s);

        if ($this->debug) {
            $this->requestHeaders = $request_headers;
            $this->responseHeaders = $response_headers;
        }

        $this->encryptedResponse = $response;
        return $this->encryptedResponse;
    }

    public function collectPay ($inputs) {
        $subMerchantId = (int) $inputs['subMerchantId'];
        $subMerchantName = trim($inputs['subMerchantName']);
        $terminalId = trim ($inputs['terminalId']);
        $billNumber = trim ($inputs['billNumber']);
        $in_amount = $inputs['amount'];
        $payerVa = trim ($inputs['payerVa']);
        $note = trim ($inputs['note']);

        if (!isset ($subMerchantId) || $subMerchantId <= 0)
            throw new InvalidInputException ("Sub-Merchant ID is missing or invalid. Sub-Merchant ID must me numeric and non-zero value.", 412);
        if (!isset ($subMerchantName) || $subMerchantName == '')
            throw new InvalidInputException ("Sub-Merchant Name is missing.", 412);
        if (strlen ($subMerchantName) > 50)
            throw new InvalidInputException ("Sub-Merchant Name cannot be more than 50 character.", 412);
        if (!isset ($billNumber) || $billNumber == '')
            throw new InvalidInputException ("Bill Number is missing.", 412);
        if (strlen ($billNumber) > 50)
            throw new InvalidInputException ("Bill Number cannot be more than 50 characters.", 412);
        if (!isset ($payerVa) || $payerVa == '')
            throw new InvalidInputException ("Player VA is missing.", 412);
        if (strpos($payerVa, '@') === false || strpos($payerVa, '@') === 0 || strpos($payerVa, '@') === (strlen ($payerVa) - 1))
            throw new InvalidInputException ("Invalid Payer VA. Payer VA must contain @ character, example foodklub@icici", 412);
        if (strlen ($payerVa) > 255)
            throw new InvalidInputException ("Player VA cannot be more than 255 characters.", 412);
        if (strlen ($note) > 50)
            throw new InvalidInputException ("Note cannot be more than 50 characters.", 412);

        $amount = number_format ($in_amount, 2, '.', '');
        if ($amount <= 0)
            throw new InvalidInputException ("Invalid amount {$in_amount}. Amount must be decimal and non-zero value.", 412);
        if ($amount > 9999999999999999.99)
            throw new InvalidInputException ("Invalid amount {$in_amount}. Amount cannot exceed 9999999999999999.99.", 412);

        if (!(isset ($terminalId) && $terminalId != ''))
            $terminalId = $this->terminalId;
        $terminalId = (int) $terminalId;
        if ($terminalId <= 0 || strlen($terminalId) != 4)
            throw new InvalidInputException ("Invalid Terminal ID. Terminal ID must be number and should be exact 4 characters.", 412);

        $collectPayTime = @strtotime('+1 hour');
        $collectByDate = @date('d/m/Y h:m A', $collectPayTime);

        $txn = $this->logTransaction ($subMerchantId, $subMerchantName, $terminalId, $billNumber, $amount, $payerVa, $note, $collectPayTime);
        if ($txn->txnid <= 0)
            throw new ServerException ("Cannot create Transaction. Please contact Savitriya Support.", 503);

        $params = array (
            'merchantId' => $this->mMerchantId,
            'merchantName' => $this->mMerchantName,
            'subMerchantId' => $subMerchantId,
            'subMerchantName' => $subMerchantName,
            'terminalId' => $terminalId,
            'merchantTranId' => $txn->txnid,
            'billNumber' => $billNumber,
            'amount' => $amount,
            'payerVa' => $payerVa,
            'note' => $note,
            'collectByDate' => $collectByDate,
        );

        $paramJson = json_encode ($params);
        $encryptedRequestParams = $this->encrypt ($paramJson);

        if ($this->debug) {
            $this->requestParams = $paramJson;
            $this->encryptedRequestParams = $encryptedRequestParams;
        }

        $encryptedResponse = $this->request ($this->mApiCollectPayEndpoint, $encryptedRequestParams);
        $this->response = $this->decrypt($encryptedResponse);

        $response_assoc = json_decode ($this->response, true);
        $response_assoc['billNumber'] = $billNumber;

        if (!($response_assoc['response'] == '52' || $response_assoc['response'] == '5002')) {
            // ICICI rejected this transaction
            $txn->status = 2;
            $txn->save();
        }

        $this->logTransactionResponse ($txn->txnid, 'collectpay', $this->response);
        $this->logTransactionLogs ($txn->txnid, $txn->status, $response_assoc);

        // Fire Transaction Initiated Event
        $event = new UpiTransactionInitiatedEvent();
        $event->txnId = $txn->txnid;
        $event->billNumber = $billNumber;
        $event->status = $txn->status;
        if ($txn->status == 0)
            $event->statusString = 'pending';
        else if ($txn->status == 1)
            $event->statusString = 'accepted';
        else if ($txn->status == 2)
            $event->statusString = 'rejected';
        $event->amount = $amount;
        $event->note = $note;
        $event->initDate = $txn->collect_date;
        $event->success = $response_assoc['success'];
        $event->successMessage = $response_assoc['message'];
        event ($event);

        return $response_assoc;
    }

    public function transacrtionStatus ($inputs) {
        $subMerchantId = (int) $inputs['subMerchantId'];
        $terminalId = trim ($inputs['terminalId']);
        $txnId = (int) $inputs['txnId'];

        if (!isset ($subMerchantId) || $subMerchantId <= 0)
            throw new InvalidInputException ("Sub-Merchant ID is missing or invalid. Sub-Merchant ID must me numeric and non-zero value.", 412);

        if (!(isset ($terminalId) && $terminalId != ''))
            $terminalId = $this->terminalId;
        $terminalId = (int) $terminalId;
        if ($terminalId <= 0 || strlen($terminalId) != 4)
            throw new InvalidInputException ("Invalid Terminal ID. Terminal ID must be number and should be exact 4 characters.", 412);

        if ($txnId <= 0)
            throw new InvalidInputException ("Invalid Transaction ID. Transaction ID must be numeric and non-zero value.", 412);

        $txnObj = null;
        try {
            $txnObj = IciciUpiTxn::findOrFail($txnId);
        }
        catch (ModelNotFoundException $ex) {
            throw new InvalidInputException ("Invalid Transaction ID. Transaction ID {$txnId} does not exists.", 412);
        }

        $params = array (
            'merchantId' => $this->mMerchantId,
            'subMerchantId' => $subMerchantId,
            'terminalId' => $terminalId,
            'merchantTranId' => $txnId,
        );

        $paramJson = json_encode ($params);
        $encryptedRequestParams = $this->encrypt ($paramJson);

        if ($this->debug) {
            $this->requestParams = $paramJson;
            $this->encryptedRequestParams = $encryptedRequestParams;
        }

        $encryptedResponse = $this->request ($this->mApiTransactionStatusEndpoint, $encryptedRequestParams);
        $this->response = $this->decrypt($encryptedResponse);

        $response_assoc = json_decode ($this->response, true);
        $response_assoc['billNumber'] = $txnObj->bill_no;
        $response_assoc['BankRRN'] = $response_assoc['OriginalBankRRN'];

        $this->logTransactionResponse ($txnId, 'txnstatus', $this->response);
        $this->logTransactionLogs ($txnId, $response_assoc['status'], $response_assoc);

        if ($txnObj != null) {
            $oldStatus = $txnObj->status;

            $status = strtolower($response_assoc['status']);
            if ($status == 'pending')
                $txnObj->status = 0;
            else if ($status == 'success')
                $txnObj->status = 1;
            else if ($status == 'failure' || $status == 'reject')
                $txnObj->status = 2;
            $txnObj->save();

            if($oldStatus != $txnObj->status) {
                // Fire Transaction Status Change Event
                $event = new UpiTransactionStatusChangeEvent();
                $event->txnId = $txnObj->txnid;
                $event->billNumber = $txnObj->bill_no;

                $event->status = $txnObj->status;
                if ($txnObj->status == 0)
                    $event->statusString = 'pending';
                else if ($txnObj->status == 1)
                    $event->statusString = 'accepted';
                else if ($txnObj->status == 2)
                    $event->statusString = 'rejected';

                $event->oldStatus = $oldStatus;
                if ($oldStatus == 0)
                    $event->oldStatusString = 'pending';
                else if ($oldStatus == 1)
                    $event->oldStatusString = 'accepted';
                else if ($oldStatus == 2)
                    $event->oldStatusString = 'rejected';

                $event->amount = $txnObj->amount;
                $event->note = $txnObj->note;
                $event->initDate = $txnObj->collect_date;
                event ($event);
            }
        }

        return $response_assoc;
    }

    public function processIciciUpiCallback ($encryptedContent) {
        $decryptedContent = $this->decrypt($encryptedContent);
        $this->response = $decryptedContent;

        $jsonObj = json_decode($decryptedContent, true);

        $txnId = (int) $jsonObj['merchantTranId'];

        $this->logTransactionResponse ($txnId, 'callback', $this->response);
        $this->logTransactionLogs ($txnId, $jsonObj['TxnStatus'], $jsonObj);

        // finally update transaction
        try {
            $txnObj = IciciUpiTxn::findOrFail($txnId);
            $oldStatus = $txnObj->status;

            $status = strtolower($jsonObj['status']);
            if ($status == 'pending')
                $txnObj->status = 0;
            else if ($status == 'success')
                $txnObj->status = 1;
            else if ($status == 'failure' || $status == 'reject')
                $txnObj->status = 2;
            $txnObj->save();

            if ($oldStatus != $txnObj->status) {
                // Fire Transaction Status Change Event
                $event = new UpiTransactionStatusChangeEvent();
                $event->txnId = $txnObj->txnid;
                $event->billNumber = $txnObj->bill_no;

                $event->status = $txnObj->status;
                $event->statusString = $status;

                $event->oldStatus = $oldStatus;
                if ($oldStatus == 0)
                    $event->oldStatusString = 'pending';
                else if ($oldStatus == 1)
                    $event->oldStatusString = 'accepted';
                else if ($oldStatus == 2)
                    $event->oldStatusString = 'rejected';

                $event->amount = $txnObj->amount;
                $event->note = $txnObj->note;
                $event->initDate = $txnObj->collect_date;
                event ($event);
            }
        }
        catch (ModelNotFoundException $ex) {
            //throw new InvalidInputException ("Invalid Transaction ID. Transaction ID {$txnId} does not exists.", 412);
        }
    }

    private function logTransaction ($subMerchantId, $subMerchantName, $terminalId, $billNumber, $amount, $payerVa, $note, $collectPayTime) {
        $txn = new IciciUpiTxn();
        $txn->merchant_id = $this->mMerchantId;
        $txn->merchant_name = $this->mMerchantName;
        $txn->sub_merchant_id = $subMerchantId;
        $txn->sub_merchant_name = $subMerchantName;
        $txn->terminal_id = $terminalId;
        $txn->bill_no = $billNumber;
        $txn->amount = $amount;
        $txn->payer_va = $payerVa;
        $txn->note = $note;
        $txn->collect_date = @gmdate('Y-m-d H:i:s', $collectPayTime);
        $txn->status = 0;
        $txn->save();

        return $txn;
    }

    private function logTransactionResponse ($txnId, $type, $response) {
        $txnResponse = new IciciUpiResponse();
        $txnResponse->txnid = $txnId;
        $txnResponse->request = $type;
        $txnResponse->response = $response;
        $txnResponse->save();
    }

    private function logTransactionLogs ($txnId, $status, $response_assoc) {
        $txnLog = new IciciUpiTxnLogs();
        $txnLog->txnid = $txnId;
        $txnLog->response = $response_assoc['response'];
        $txnLog->merchant_id = $response_assoc['merchantId'];
        $txnLog->sub_merchant_id = $response_assoc['subMerchantId'];
        $txnLog->terminal_id = $response_assoc['terminalId'];
        $txnLog->success = $response_assoc['success'];
        $txnLog->message = $response_assoc['message'];
        $txnLog->bank_rrn = $response_assoc['BankRRN'];
        $txnLog->status = $status;
        $txnLog->save();

    }

    public function getRequestHeaders () {
        return $this->requestHeaders;
    }

    public function getRequestParams () {
        return $this->requestParams;
    }

    public function getEncryptedRequestParams () {
        return $this->encryptedRequestParams;
    }

    public function getResponseHeaders() {
        return $this->responseHeaders;
    }

    public function getRawResponse () {
        return $this->encryptedResponse;
    }

    public function getResponse () {
        return $this->response;
    }

}
