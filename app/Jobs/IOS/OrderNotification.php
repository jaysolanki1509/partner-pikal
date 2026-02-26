<?php namespace App\Jobs\IOS;

use App\Jobs\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Support\Facades\Log;
use App\order_details;

class OrderNotification extends Command implements SelfHandling, ShouldBeQueued {

    use InteractsWithQueue, SerializesModels;

    public function getordersnotification($job,$fields)
    {
        return;
        //Log::info($fields);
        order_details::sendiospushnotification($fields['fields'][0],$fields['fields'][1],$fields['fields'][3],$fields['fields'][2],$fields['fields'][4]);
        $job->delete();
    }



}