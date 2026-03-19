<?php
use Illuminate\Support\Facades\Input;


date_default_timezone_set('Asia/Kolkata');
$date_time = date("F j, Y, g:i a");

$userIpAddress = Request::getClientIp();
        //DB::table('owners')->where('id',$ownerid)->update(array('password'=>bcrypt($password)));
?>


<p>Hello Admin,</p>
<p>New Registeration done. <p>
        <p>Username : {{$username}}</p>
        <p>Email id : {{$email}}</p>
        <p>Contact no : {{$contact_no}}</p>
<b>Login to your pikal account <a href="{{$_SERVER['HTTP_HOST']}}">{{$_SERVER['HTTP_HOST']}}</a></b>