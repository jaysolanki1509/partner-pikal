<?php namespace App\Commands;
use App\Commands\Command;
use App\Http\Controllers\Api\v1\Apicontroller;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Log;
use App\order_details;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\DB;
use App\Outlet;
use Illuminate\Support\Facades\Mail;

class MailNotification extends Command {
    use InteractsWithQueue, SerializesModels;
    public function getorderdetails($job,$fields){
        Mail::send('emails.orderdetails', ['orderdetails' => $fields], function($message) use ($fields)
        {
            $outletname=Outlet::where('id','=',$fields['orderdetails']['orderdetails']['outlet_id'])->first();
            $message->from('we@pikal.io', 'Pikal');
            $message->to('raj@savitriya.com', 'Govind');
            $message->subject($outletname->name." - ".Apicontroller::get_order_type($fields['orderdetails']['orderdetails']['order_type'])." - ".$fields['orderdetails']['orderdetails']['suborder_id']);
        });
        $job->delete();
    }

}
