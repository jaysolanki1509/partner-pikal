<?php

namespace App\Console\Commands;

use App\Kot;
use App\OrderDetails;
use App\Outlet;
use App\Owner;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SendDuplicateInvoice extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pikal:sendduplicateinvoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
        $data1 = array();

        $to_date = Carbon::yesterday()->endOfDay();
        $from_date =  Carbon::yesterday()->startOfDay();
        $all_outlets_invoice = array();
        $isdata = 0;
        $all_outlets_diff = array();
        if (sizeof($owners) > 0) {
            foreach ($owners as $owner) {
                $all_owners_diff[$owner->name] = array();
                $outlets = Outlet::where('owner_id', $owner->id)->where('id', '!=', 44)->where('id', '!=', 45)->get(); //not send to chinahut outlet

                if (sizeof($outlets) > 0) {
                    foreach ($outlets as $outlet) {
                        $data1['outlet_name'] = $outlet->name;
                        $all_outlets_invoice[$data1['outlet_name']] = array();
                        $outlet_id = $outlet->id;

                        $orders = OrderDetails::join("outlets", "outlets.id", "=", "orders.outlet_id")
                            ->select('invoice_no', DB::raw('count(*) as record'))
                            ->where('orders.table_end_date', '>=', $from_date)
                            ->where('orders.table_end_date', '<=', $to_date)
                            ->where('orders.invoice_no', '!=', '')
                            ->groupby('invoice_no')
                            ->orderby('orders.table_end_date', 'DESC')
                            ->having('record', '>', 1);

                        if ($outlet_id == 'all') {
                            $result = $orders->get();
                        } else {
                            $result = $orders->where('orders.outlet_id', '=', $outlet_id)->get();
                        }

                        $data = array();
                        $i = 0;
                        $total = 0;
                        if (isset($result) && sizeof($result) > 0) {

                            foreach ($result as $ord) {

                                $dup_inv = OrderDetails::join("outlets", "outlets.id", "=", "orders.outlet_id")
                                    ->select('invoice_no', 'outlets.name as name', 'table_end_date as date')
                                    ->where('invoice_no', $ord->invoice_no);

                                if ($outlet_id == 'all') {
                                    $result1 = $dup_inv->get();
                                } else {
                                    $result1 = $dup_inv->where('orders.outlet_id', '=', $outlet_id)->get();
                                }


                                if (isset($result1) && sizeof($result1) > 0) {
                                    foreach ($result1 as $inv) {

                                        $data[$i]['inv_no'] = $inv->invoice_no;
                                        $data[$i]['date'] = $inv->date;
                                        $data[$i]['ot_name'] = $inv->name;
                                        $i++;
                                        $isdata++;
                                    }
                                }
                            }
                        }

                        array_push($all_outlets_invoice[$data1['outlet_name']], $data);
                    }
                }

                if (sizeof($outlets) > 0) {
                    foreach ($outlets as $outlet) {
                        $data['outlet_name'] = $outlet->name;
                        $all_outlets_diff[$data['outlet_name']] = array();
                        $outlet_id = $outlet->id;

                        $notmatch = Kot::kotOrderDiff($outlet_id, $from_date, $to_date);

                        if (sizeof($notmatch) > 0)
                            $isdata = 1;

                        array_push($all_outlets_diff[$data['outlet_name']], $notmatch);
                    }
                }
            }

            if ($isdata > 0 && $isdata != 0) {
                try {
                    if (env("APP_ENV") == "production") {
                        $email[0] = "raj@savitriya.com";
                        //$email[1] = "dev@savitriya.com";
                    } else {
                        $email[0] = "raj@savitriya.com";
                        //$email[1] = "np@savitriya.com";
                    }
                    $data['all_outlets_invoice'] = $all_outlets_invoice;
                    $data['all_outlets_diff'] = $all_outlets_diff;
                    //print_r($data);exit;
                    $data['subject'] = "Pikal : Outlet deviation report";
                    Mail::send('emails.duplicateInvoiceReport', array('data' => $data), function ($message) use ($data, $email) {
                        $message->from('we@pikal.io', 'Pikal');
                        $message->to($email);
                        $message->subject($data['subject']);
                    });
                    //	Log::info('Mail Sent to : ' . $email);

                } catch (\Exception $e) {
                    $message = 'error';
                    //Log::info('Data : ' . $e->getMessage());
                }
            } else {
                Log::info("No KOT Order DIfference or Duplicate Invoice Found.");
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    //	protected function getArguments()
    //	{
    //		return [
    //			['example', InputArgument::REQUIRED, 'An example argument.'],
    //		];
    //	}
    //
    //	/**
    //	 * Get the console command options.
    //	 *
    //	 * @return array
    //	 */
    //	protected function getOptions()
    //	{
    //		return [
    //			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
    //		];
    //	}

}
