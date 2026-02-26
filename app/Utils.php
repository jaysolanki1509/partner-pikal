<?php namespace App;

use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Expr\Array_;

class Utils extends Model {

    public static function taxCalculation($amount,$taxslab){

        $total_tax = 0;
        if($taxslab == ""){
            return $total_tax;
        } else {
            foreach($taxslab as $tax) {
                $total_tax += ($tax->taxparc * $amount)/100;
            }
            return $total_tax;
        }

    }

    public static function createDateRangeArray($strDateFrom,$strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }

    public static function tableShapeArray(){

        $shape = array();
        $shape['rect'] = "Rectangle";
        $shape['squre'] = "Squre";
        $shape['round'] = "Round";
        $shape['ovel'] = "Ovel";
        $shape['tringle'] = "Tringle";

        return $shape;

    }


    public static function sendLogNotification($device_id,$flag,$level){


        $apiKey = "AIzaSyCvBJgacwGwZ1ex1wrvgpoh747tJdfaGj8";

        $registrationIDs = explode(',',$device_id);

        if($flag == 'level'){
            $message = "Log level change.";
            $action = 'ChangeLogLevel';

        }
        else if($flag == 'fatch'){
            $message = "Fatch logs.";
            $action = "FetchLog";
        }
        else if($flag == 'delete'){
            $message = "Delete Local logs";
            $action = "DeleteLog";
        }else{
            $message = "Test Notification.";
        }

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'registration_ids'  => $registrationIDs,
            'priority'=>'high',
            'data' => array("message" => $message,
                'action' => $action,
                'log-level' => $level
            )
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

    public static function sendOrderToFirebase( $order_arr ) {

        $url = env('FB_URL');
        $key = env('FB_KEY');

        //$url = 'https://foodklub-test-f7b3b.firebaseio.com/outlet/'.$outlet_id.'/8.json?auth='.$key;

        $check_user = Utils::checkFirebaseOutletUser( $order_arr['outlet_id'] );
        $users_arr = json_decode( $check_user, true );

        $user_id = '';
        if ( isset($users_arr) && sizeof($users_arr) > 0 ) {

            //serach user who accept order type
            foreach ( $users_arr as $arr ) {
                if ( isset($arr['order_receive']) && sizeof($arr['order_receive']) > 0 ) {

                    $order_receive = $arr['order_receive'];

                    if ( isset($order_receive) && $order_receive != '' ) {

                        //get string between []
                        preg_match('#\[(.*?)\]#', $order_receive, $match);
                        $order_receive1 = explode(",",$match[1]);

                        foreach ( $order_receive1 as $or ) {

                            //get only text between ""
                            preg_match('/"([^"]+)"/', $or, $m);

                            if ( strtolower($m[1]) == $order_arr['order_type'] ) {
                                $user_id = $arr['id'];
                                break;
                            }
                        }

                    }
                }
            }

        } else {
            return 'error';
        }

        if ( $user_id != '' ) {

            $order_arr['message'] = '@order';
            $order_arr['timestamp'] = time();
            $order_arr['message-status'] = 0;
            $order_arr['order_from'] = 'pro';

            $url = $url."/cart/".$order_arr['outlet_id']."/".$user_id."/".$user_id."-".$user_id.".json?auth=".$key;

            // Open connection
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);

            // Set the url, number of POST vars, POST data
            curl_setopt( $ch, CURLOPT_URL, $url );

            curl_setopt( $ch, CURLOPT_POST, true );
            //curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $order_arr ) );

            // Execute post
            $result = curl_exec($ch);

            if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
            // Close connection

            curl_close($ch);

            return $result;
        }


    }

    public static function checkFirebaseOutletUser( $outlet_id ) {

        $url = env('FB_URL');
        $key = env('FB_KEY');

        $url = $url."/outlet/".$outlet_id.".json?auth=".$key;

        // Open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);

        // Set the url, number of POST vars, POST data
        curl_setopt( $ch, CURLOPT_URL, $url );

        //curl_setopt( $ch, CURLOPT_POST, true );
        //curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


        //curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        // Execute post
        $result = curl_exec($ch);

        if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
        // Close connection

        curl_close($ch);

        return $result;

    }

    public static function getGUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
            return $uuid;
        }
    }

    public static function getOrderType() {

        $order_type = array();

        $order_type['dine_in'] = "Dine In";
        $order_type['take_away'] = "Take Away";
        $order_type['home_delivery'] = "Home Delivery";
        $order_type['biller'] = "Biller";

        return $order_type;
    }

    #TODO: Convert time to session time
    public static function getSessionTime( $date, $flag ) {

        $outlet_id = Session::get('outlet_session');
        $outlet_obj = Outlet::find($outlet_id);

        $from_time = "00:00:00";
        $to_time = "23:59:59";
        
        if ( $flag == 'from') {

            $date_val = $date." ".$from_time;
            if ( isset($outlet_obj) && sizeof($outlet_obj) > 0 ) {
                $date_val = date('Y-m-d H:i:s', strtotime($date_val . "+$outlet_obj->session_time hours"));
            }

        } else {

            $date_val = $date." ".$to_time;
            if ( isset($outlet_obj) && sizeof($outlet_obj) > 0 ) {
                $date_val = date('Y-m-d H:i:s', strtotime($date_val . "+$outlet_obj->session_time hours"));
            }

        }

        return $date_val;
    }

    #TODO: outlet bill template keys
    public static function getBillTemplateKeys($outlet_id = NULL) {

        if ( isset($outlet_id) && $outlet_id != '') {
            $outlet_id = $outlet_id;
        } else {
            $outlet_id = Session::get('outlet_session');
        }


        $keys = array(
                    ''=>'Select Key',
                    'outlet_name'=>'Outlet Name',
                    'address'=>'Address',
                    'customer'=>'Customer',
                    'customer_mobile'=>'Mobile',
                    'customer_address'=>'Customer Address',
                    'bill_lable'=>'Bill Lable',
                    'order_type'=>'Order Type',
                    'user_name'=>'Username',
                    'table_no'=>'Table No.',
                    'date'=>'Date',
                    'invoice_no'=>'Invoice No.',
                    'pax'=>'Pax',
                    'order_detail'=>'Order Detail',
                    'tax_detail'=>'Tax Detail',
                    'footer_note'=>'Footer Note',
                    'qr_code'=>'QR Code',
                    'lable'=>'Lable',
                    'new_line'=>'New Line',
                    'dash_line'=>'-------',
                    'star_line'=>'*******',
                    );

        //check custom field if available than add in array
        $custom_field_json = Outlet::where('id',$outlet_id)->pluck('custom_bill_print_fields');

        if ( isset($custom_field_json) && $custom_field_json != '' ) {
            $custom_field_arr = json_decode($custom_field_json);

            if ( isset($custom_field_arr) && sizeof($custom_field_arr) > 0 ) {
                //print_r($custom_field_arr[0]);exit;
                foreach ( $custom_field_arr as $key=>$fields ) {
                    if ( isset($fields) && sizeof($fields) > 0 ) {
                        foreach ( $fields as $field_key=>$arr ) {
                            $keys[$field_key] = $arr[0]->label;
                        }
                    }
                }
            }
        }

        return $keys;
    }

    static function printFormate($default_string){
        $unformatted_string = explode(' ',$default_string);

        $new_str = "";
        $final_str = "";
        $new_line_arr = array(10);
        $new_line = implode(array_map("chr", $new_line_arr));

        for ($i = 0; $i < sizeof($unformatted_string); $i++) {
            $formatted_str = "";
            if (($i + 1) < sizeof($unformatted_string) && (strlen($new_str) + strlen($unformatted_string[$i + 1])) < 30) {
                $formatted_str = $unformatted_string[$i]." ".$unformatted_string[$i + 1];
                $i++;
            } else {
                $formatted_str = $unformatted_string[$i];
            }

            $new_str .= " ".$formatted_str;
            if(strlen($new_str) >= 30) {
                $final_str .= $new_str;
                $final_str .= $new_line;
                $new_str = "";
            }

        }
        $final_str .= $new_str;

        return $final_str;

    }

    #TODO: get zoho authtoken
    public static function getZohoAuthToken($email,$password) {

        $url = "https://accounts.zoho.com/apiauthtoken/nb/create?SCOPE=ZohoInvoice/invoiceapi&EMAIL_ID=apudhami@gmail.com&PASSWORD=Idli9nvp";

        // Open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);

        // Set the url, number of POST vars, POST data
        curl_setopt( $ch, CURLOPT_URL, $url );

        curl_setopt( $ch, CURLOPT_POST, true );
        //curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


        //curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        // Execute post
        $result = curl_exec($ch);

        if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
        // Close connection

        curl_close($ch);

        return $result;

    }

    //Conver number to words
    public static function getIndianCurrency($number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise ;
    }

    public static function changeQty($item_id, $primary_qty, $unit_id) {

        $purchase_qty = $primary_qty;
        $menu = Menu::find($item_id);
        if(isset($menu->secondary_units) && sizeof($menu->secondary_units)>0 && trim($menu->secondary_units) != "") {
            $sec_unit = json_decode($menu->secondary_units);
            $converted_qty = 0;
            foreach ($sec_unit as $s_unit_id=>$convers_val){
                if($unit_id != $s_unit_id){
                    $converted_qty = $converted_qty*$primary_qty;
                    break;
                }
            }
            //return $converted_qty;
        }

        return $purchase_qty;

    }

}
