<?php namespace App\Jobs;

use App\Jobs\Command;

use App\Services\Registrar;
use App\Utils;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogNotification extends Command implements SelfHandling, ShouldBeQueued {

    use InteractsWithQueue, SerializesModels;

    public function logNotifications($job,$fields)
    {
        Utils::sendLogNotification($fields['fields']['device_id'],$fields['fields']['flag'],$fields['fields']['level']);
        $job->delete();
    }


}
