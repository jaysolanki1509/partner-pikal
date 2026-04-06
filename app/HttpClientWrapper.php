<?php

// namespace App;

// use DateTimeZone;
// use Illuminate\Http\Request;
// use Session;
// use DateTime;
// use App\ConvertTimeZones;
// use Illuminate\Support\Facades\Input;
// use Illuminate\Support\Facades\Config;
// use App\Http\Requests;

// class HttpClientWrapper
// {

//     public function send_request($req_type,$param,$url,$token = NULL) {
//         ini_set("memory_limit",-1 );
//         ob_start();
//         $result = '';
//         // is cURL installed yet?
//         if (!function_exists('curl_init')){
//             die('Sorry cURL is not installed!');
//         }

//         $ch = curl_init();

//         if ( isset($token)) {

//             $headers = array(
//                 'laravel_session:'.$token,
//                 'Content-Type: application/x-www-form-urlencoded',
//             );


//             curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
//         }

//         curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

//         //curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);

//         if ( isset($param) && $param != '' ) {
//             curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
//         }

//         curl_setopt($ch, CURLOPT_URL, $url);

//         if ( $req_type == 'DELETE' ) {
//             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
//         }

//         if ( $req_type == 'PUT' ) {
//             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

//         }

//         if ( $req_type == 'POST' ){
//             curl_setopt($ch, CURLOPT_POST, true);
//         }

//         curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: laravel_session=".$token));

//         $result = curl_exec($ch);
//         //print_r($result);exit;
//         $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//         curl_close($ch);
//         ob_end_clean();
//         ob_flush();

//         if ( !$result) {
//             return $httpcode;
//         }

//         return $result;
//     }


 
// }
namespace App;

use Exception;

class HttpClientWrapper
{
    /**
     * Send an HTTP request
     *
     * @param string $req_type HTTP method: GET, POST, PUT, DELETE
     * @param array $params Optional parameters to send
     * @param string $url URL to request
     * @param string|null $token Optional token for authorization/session
     * @return mixed Response body or HTTP code if failed
     */
    public function send_request($req_type, $params = [], $url, $token = null)
    {
        // Ensure cURL is available
        if (!function_exists('curl_init')) {
            throw new Exception('cURL is not installed!');
        }

        $ch = curl_init();

        // Common cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        // Set headers if token is provided
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
        ];
        if ($token) {
            $headers[] = 'Cookie: laravel_session=' . $token;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Set method and payload
        $req_type = strtoupper($req_type);
        switch ($req_type) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if (!empty($params)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
                }
                break;

            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if (!empty($params)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
                }
                break;

            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($params)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
                }
                break;

            case 'GET':
                if (!empty($params)) {
                    $url .= '?' . http_build_query($params);
                    curl_setopt($ch, CURLOPT_URL, $url);
                }
                break;

            default:
                throw new Exception("Invalid request type: $req_type");
        }

        // Execute request
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: $error, HTTP code: $httpCode");
        }

        curl_close($ch);

        return $result;
    }
}