<?php

namespace App;

use DateTimeZone;
use Illuminate\Http\Request;
use Session;
use DateTime;
use App\ConvertTimeZones;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Config;
use App\Http\Requests;

class HttpClientWrapper
{

    public function send_request($req_type,$param,$url,$token = NULL) {


        ini_set("memory_limit",-1 );
        ob_start();
        $result = '';
        // is cURL installed yet?
        if (!function_exists('curl_init')){
            die('Sorry cURL is not installed!');
        }

        $ch = curl_init();

        if ( isset($token)) {

            $headers = array(
                'laravel_session:'.$token,
                'Content-Type: application/x-www-form-urlencoded',
            );


            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

        //curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);

        if ( isset($param) && $param != '' ) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
        }

        curl_setopt($ch, CURLOPT_URL, $url);

        if ( $req_type == 'DELETE' ) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        if ( $req_type == 'PUT' ) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

        }

        if ( $req_type == 'POST' ){
            curl_setopt($ch, CURLOPT_POST, true);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: laravel_session=".$token));

        $result = curl_exec($ch);
        //print_r($result);exit;
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        ob_end_clean();
        ob_flush();

        if ( !$result) {
            return $httpcode;
        }

        return $result;
    }


 
}
