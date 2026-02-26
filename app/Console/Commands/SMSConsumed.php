<?php namespace App\Console\Commands;


use App\SendCloseCounterStatus;
use App\users;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SMSConsumed extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pikal:smsconsumed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Total sms consumed in one day.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire(){

        $from_date = new Carbon(date('Y-m-d',strtotime('yesterday')).' 00:00:00');
        $to_date = new Carbon(date('Y-m-d',strtotime('yesterday')).' 23:59:59');
        $yesterday = date('Y-m-d',strtotime('yesterday'));

        $count = SendCloseCounterStatus::whereDate('created_at','>=', (new Carbon($from_date))->startOfDay())
            ->where('created_at','<=', (new Carbon($to_date))->endOfDay())->sum('sms_count');

        $ch = curl_init('http://ip.shreesms.net/SMSServer/SMSCnt.asp?ID=FOODKB&pw=hello007');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if ( isset($count)) {

            Mail::raw('Total '.$count.' SMS has been consumed on '.$yesterday.'. Total SMS balance remained is '.$response, function($message) use ($yesterday)
            {
                $message->from('we@pikal.io', 'Pikal');
                $message->to('dev@savitriya.com');
                $message->subject('Total SMS consumed on '.$yesterday);
            });
        }

    }

}
