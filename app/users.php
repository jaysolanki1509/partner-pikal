<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class users extends Model {
    protected $table = 'users';
    protected $fillable = ['first_name','last_name', 'email', 'password','mobile_number','state','city','country','gender','otp','status','device_id'];
    //addcustomer function is for adding customer if he/she is not registered from mobile
    // new app users
    public static function getidofaddedcustomer($contact,$pass,$num){
        $id = DB::table('users')->insertGetId(
            array('mobile_number' => $contact, 'password' => $pass,'otp'=>$num,'status'=>'NotVerified')
        );
        return $id;
    }
    // function for generating otp
    public static function generateotp(){
        $num= rand(000000,999999);
        return $num;
    }

    public static function findcustomerbyphonenumber($number){
        $usercheck=users::where('mobile_number',$number)->get();
        return $usercheck;
    }

    public static function sendotpbymessage($num,$contact){
        $message="Dear Customer,Your OTP for Signup is $num";
        $username = Config::get('app.sms_api_username');
        $password = Config::get('app.sms_api_password');

// Message details
        $numbers = $contact;
        //$numbers = '9016572923';
        $message = urlencode($message);

// Prepare data for POST request
        $data = 'Userid=' . $username . '&UserPassword=' . $password . '&PhoneNumber=91' . $numbers . "&Text=" . $message . "&GSM=" . $username;

// Send the GET request with cURL
        $ch = curl_init('http://ip.shreesms.net/smsserver/SMS10N.aspx?'.$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        //echo $response;
        curl_close($ch);
    }

    public static function capmpaignOTP($num,$contact){

        $message="Dear Customer,Your OTP for registration is $num";
        $username = Config::get('app.sms_api_username');
        $password = Config::get('app.sms_api_password');

// Message details
        $numbers = $contact;
        //$numbers = '9016572923';
        $message = urlencode($message);

// Prepare data for POST request
        $data = 'Userid=' . $username . '&UserPassword=' . $password . '&PhoneNumber=91' . $numbers . "&Text=" . $message . "&GSM=" . $username;

// Send the GET request with cURL
        $ch = curl_init('http://ip.shreesms.net/smsserver/SMS10N.aspx?'.$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        //echo $response;
        curl_close($ch);
    }


    public static function sendotpoffogotpassword($num,$contact){
        $message="Dear Customer,Your OTP for Forgot Password is $num";
        $username = Config::get('app.sms_api_username');
        $password = Config::get('app.sms_api_password');

    // Message details
        $numbers = $contact;
        //$numbers = '9016572923';
        $message = urlencode($message);


        // Prepare data for POST request
        $data = 'Userid=' . $username . '&UserPassword=' . $password . '&PhoneNumber=91' . $numbers . "&Text=" . $message . "&GSM=" . $username;

    // Send the GET request with cURL
        $ch = curl_init('http://ip.shreesms.net/smsserver/SMS10N.aspx?'.$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        //echo $response;
        curl_close($ch);
    }
    //update otp
    public static function updateotp($contact,$num){
        $updatedvalue=DB::table('users')->where('mobile_number',$contact)->update(array('otp'=>$num));
        return $updatedvalue;
    }

    //for updating password after call of forgot password
    public static function updatepassword($contact,$otp,$password){
        $updatedpassword=DB::table('users')->where('mobile_number',$contact)->where('otp',$otp)->update(array('password'=>$password));
        return $updatedpassword;
    }

    //for verifing user status if there otp is verified or not
    public static function selectotp($id){
        $user=DB::table('users')->select('otp')->where('id',$id)->first();
        return $user;
    }

    //for updating the status of user otp to verified
    public static function updateotpstatus($id){
        DB::table('users')->where('id',$id)->update(['status' => 'Verified']);
    }

    //get id of user
    public static function getidofuserinserted($mobile)
    {
        $id = DB::table('users')->where('mobile_number', $mobile)->select('id')->get();
        return $id;
    }

    // Mobile Side Login
    public static function sendpassword($contact,$pass,$name){
       // $message="Welcome To Foodklub $name,Your Password For First Time Login is $pass";
        $message="Dear Customer,Your OTP For First Time Login is $pass";
        $username = Config::get('app.sms_api_username');
        $password = Config::get('app.sms_api_password');


        $numbers = $contact;
        //$numbers = '9016572923';
        $message = urlencode($message);

        // Prepare data for POST request
        $data = 'Userid=' . $username . '&UserPassword=' . $password . '&PhoneNumber=91' . $numbers . "&Text=" . $message . "&GSM=" . $username;

        // Send the GET request with cURL
        $ch = curl_init('http://ip.shreesms.net/smsserver/SMS10N.aspx?'.$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        //echo $response;
        curl_close($ch);
    }

    public static function sendStatusMessage($outlet_name,$contacts,$sms_config,$hours,$total_orders,$pax,$sale,$gs,$ns,$can){
        // $message="Welcome To Foodklub $name,Your Password For First Time Login is $pass";
        $message = '';
        $sms_format = json_decode($sms_config);
        if ( isset($sms_format) && sizeof($sms_format) > 0 ) {

            $username = Config::get('app.sms_api_username');
            $password = Config::get('app.sms_api_password');

            if ( in_array('name',$sms_format)) {
                $message = $outlet_name;
            }
            if ( in_array('hours',$sms_format)) {
                if ( $message == '')
                    $message ="HOURS:".$hours;
                else
                    $message .=",HOURS:".$hours;
            }
            if ( in_array('ord',$sms_format)) {
                if ( $message == '')
                    $message ="ORD:".$total_orders;
                else
                    $message .=",ORD:".$total_orders;
            }
            if ( in_array('pax',$sms_format)) {
                if ( $message == '')
                    $message ="PAX:".$pax;
                else
                    $message .=",PAX:".$pax;
            }
            if ( in_array('sales',$sms_format)) {
                if ( $message == '')
                    $message ="SALES:".$sale;
                else
                    $message .=",SALES:".$sale;
            }
            if ( in_array('gs',$sms_format)) {
                if ( $message == '')
                    $message ="GS:".$gs;
                else
                    $message .=",GS:".$gs;
            }
            /*if ( in_array('ns',$sms_format)) {
                if ( $message == '')
                    $message ="NS:".$ns;
                else
                    $message .=",NS:".$ns;
            }*/
            if ( in_array('can',$sms_format)) {
                if ( $message == '')
                    $message ="CAN:".$can;
                else
                    $message .=",CAN:".$can;
            }

            // Message details
            $numbers = $contacts;
            //$numbers = '9016572923';
            $message = urlencode($message);

            // Prepare data for POST request
            $data = 'Userid=' . $username . '&UserPassword=' . $password . '&PhoneNumber=91' . $numbers . "&Text=" . $message . "&GSM=" . $username;

            // Send the GET request with cURL
            $ch = curl_init('http://ip.shreesms.net/smsserver/SMS10N.aspx?'.$data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            //echo $response;
            curl_close($ch);

        }


    }

    public static function updatepass($pass,$contact){

        $updatedvalue=DB::table('users')->where('mobile_number',$contact)->update(array('password'=>$pass,'otp'=>$pass));
       // return $updatedvalue;
    }

}
