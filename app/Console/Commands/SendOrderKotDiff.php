<?php namespace App\Console\Commands;


use App\Kot;
use App\order_details;
use App\Outlet;
use App\Owner;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class SendOrderKotDiff extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'foodklub:orderkotdiff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Pikal Summary Report.';

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
    public function fire()
    {
        $owners = Owner::all();
        $data = array();

        $to_date = Carbon::yesterday()->endOfDay();
        $from_date =  Carbon::yesterday()->startOfDay();
        if( sizeof($owners) > 0 ) {
            $all_outlets_diff =array();
            $all_owners_diff =array();
            $isdata = 0;
            foreach ($owners as $owner) {
                $all_owners_diff[$owner->name] = array();
                $outlets = Outlet::where('owner_id', $owner->id)->where('id','!=',44)->where('id','!=',45)->get(); //not send to chinahut outlet

                if (sizeof($outlets) > 0) {
                    foreach ($outlets as $outlet) {
                        $data['outlet_name'] = $outlet->name;
                        $all_outlets_diff[$data['outlet_name']] = array();
                        $outlet_id = $outlet->id;

                        $notmatch = Kot::kotOrderDiff($outlet_id, $from_date, $to_date);
                        if(sizeof($notmatch) > 0)
                            $isdata = 1;

                        array_push($all_outlets_diff[$data['outlet_name']], $notmatch);
                    }
                }
            }

            if($isdata > 0 && $isdata != 0) {
                try {
                    if(env("APP_ENV")=="production") {
                        $email[0] = "raj@savitriya.com";
                        //$email[1] = "dev@savitriya.com";
                    }else{
                        $email[0] = "raj@savitriya.com";
                        //$email[1] = "np@savitriya.com";
                    }

                    $data['all_outlets_diff'] = $all_outlets_diff;
                    $data['subject'] = "Pikal : Kot Order Difference Report";
                    //$data['notmatch'] = $notmatch;
                    Mail::send('emails.kotordersdiff', array('data' => $data), function ($message) use ($data, $email) {
                        $message->from('we@pikal.io', 'Pikal');
                        $message->to($email);
                        $message->subject($data['subject']);
                    });
                    //Log::info('Mail Sent to : ' . $email);

                } catch (\Exception $e) {
                    $message = 'error';
                    //Log::info('Email error : ' . $e->getMessage());
                }
            }else{
                Log::info("No KOT Order DIfference Found.");
            }
        }

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    /*protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }*/

    /**
     * Get the console command options.
     *
     * @return array
     */
    /*protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }*/

}

