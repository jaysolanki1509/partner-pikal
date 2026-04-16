<?php

namespace App\Commands\IOS;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Support\Facades\Log;
use App\OrderDetails;

class OrderNotification extends Command implements SelfHandling, ShouldBeQueued
{

    use InteractsWithQueue, SerializesModels;

    public function getordersnotification($job, $fields)
    {
        return;
        //Log::info($fields);
        OrderDetails::sendiospushnotification($fields['fields'][0], $fields['fields'][1], $fields['fields'][3], $fields['fields'][2], $fields['fields'][4]);
        $job->delete();
    }
}
