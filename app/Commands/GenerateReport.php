<?php

namespace App\Commands;

use App\Commands\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Log;
use App\OrderDetails;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\DB;
use App\Outlet;
use Illuminate\Support\Facades\Mail;

class GenerateReport extends Command
{
    use InteractsWithQueue, SerializesModels;
    public function generatereport($job, $fields)
    {
        $restname = $fields['restaurant_name'];
        Mail::send('emails.dailyreport', [], function ($message) use ($restname) {
            $message->from('we@pikal.io', 'Pikal');
            $message->to('raj@savitriya.com', 'Pikal');
            $message->subject('Pikal Report');
            $message->attach(app_path() . '/../storage/exports/' . $restname . '.xls', ['as' => $restname . '.xls', 'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
        });
        $job->delete();
    }
}
