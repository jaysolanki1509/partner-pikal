<?php

namespace App\Console\Commands;

use App\DailySummary;
use App\OrderDetails;
use App\OrderItem;
use App\Outlet;
use App\Outlet_Menu_Bind;
use App\Owner;
use App\PaymentOption;
use App\SendCloseCounterStatus;
use App\Sources;
use App\users;
use Aws\Common\Enum\Time;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Savitriya\Icici_upi\IciciUpiTxn;

class SendAllOutletSummary extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pikal:alloutletsummary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All Outlet Summary list mail.';

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

        $date = Carbon::yesterday()->format("Y-m-d");
        $isdata = 0;
        $all_outlets_summary = array();
        $all_outlets_summary_total = array();
        $all_outlets_summary_total['name'] = "Total";
        $all_outlets_summary_total['total_orders'] = 0;
        $all_outlets_summary_total['sub_total'] = 0;
        $all_outlets_summary_total['final_total'] = 0;
        $all_outlets_summary_total['cancel_orders'] = 0;
        $all_outlets_summary_total['total_person_visit'] = 0;
        $all_outlets_summary_total['month_total'] = 0;
        $all_outlets_summary_total['average_per_day'] = 0;
        $all_outlets_summary_total['today_unique_mobile'] = 0;
        $all_outlets_summary_total['total_unique_mobile'] = 0;
        if (sizeof($owners) > 0) {
            foreach ($owners as $owner) {

                $outlets = Outlet::where('owner_id', $owner->id)->get();
                $summary = array();
                if (sizeof($outlets) > 0) {

                    foreach ($outlets as $outlet) {

                        if ($outlet->active == "Yes" && $outlet->id != 45 && $outlet->id != 44) {        //45->chinahut //44->chinahut-parcel

                            $data['outlet_name'] = $outlet->name;
                            $data['outlet_status'] = $outlet->outlet_status;
                            $month_start = new DateTime("first day of last month");
                            $month_end = new DateTime("last day of last month");

                            $current_date = date('d');
                            if ($current_date == 1) {
                                $from_date = $month_start->format('Y-m-d');
                                $end_date = $month_end->format('Y-m-d');
                                $no_of_days = $month_end->format('d');
                                $month_lable = "Last Month";
                            } else {
                                $from_date = date('Y-m-01');
                                $end_date = $date;
                                $no_of_days = Carbon::yesterday()->format("d");
                                $month_lable = "Current Month";
                            }

                            $month_total = DailySummary::where('report_date', '>=', $from_date)
                                ->where('report_date', '<=', $end_date)
                                ->where("outlet_id", $outlet->id)
                                ->sum('gross_total');


                            $summary = DailySummary::where("report_date", $date)
                                ->where("outlet_id", $outlet->id)->first();

                            if (isset($summary) && sizeof($summary) > 0) {

                                $all_outlets_summary[$outlet->id]['flag'] = 1;
                                $all_outlets_summary[$outlet->id]['name'] = $data['outlet_name'];
                                $all_outlets_summary[$outlet->id]['outlet_status'] = $data['outlet_status'];

                                if (isset($summary->total_orders)) {
                                    $all_outlets_summary[$outlet->id]['total_orders'] = $summary->total_orders;
                                    $all_outlets_summary_total['total_orders'] += $summary->total_orders;
                                } else {
                                    $all_outlets_summary[$outlet->id]['total_orders'] =  "";
                                }

                                if (isset($summary->total_sells)) {
                                    $all_outlets_summary[$outlet->id]['sub_total'] = $summary->total_sells;
                                    $all_outlets_summary_total['sub_total'] += $summary->total_sells;
                                } else {
                                    $all_outlets_summary[$outlet->id]['sub_total'] = 0;
                                }

                                if (isset($summary->gross_total)) {
                                    $all_outlets_summary[$outlet->id]['final_total'] = $summary->gross_total;
                                    $all_outlets_summary_total['final_total'] += $summary->gross_total;
                                } else {
                                    $all_outlets_summary[$outlet->id]['final_total'] = 0;
                                }

                                if (isset($summary->cancel_order_count)) {
                                    $all_outlets_summary[$outlet->id]['cancel_orders'] = $summary->cancel_order_count;
                                    $all_outlets_summary_total['cancel_orders'] += $summary->cancel_order_count;
                                } else {
                                    $all_outlets_summary[$outlet->id]['cancel_orders'] = 0;
                                }

                                if (isset($summary->total_person_visit)) {
                                    $all_outlets_summary[$outlet->id]['total_person_visit'] = $summary->total_person_visit;
                                    $all_outlets_summary_total['total_person_visit'] += $summary->total_person_visit;
                                } else {
                                    $all_outlets_summary[$outlet->id]['total_person_visit'] = 0;
                                }

                                if (isset($summary->total_person_visit)) {
                                    $all_outlets_summary[$outlet->id]['month_total'] = $month_total;
                                    $all_outlets_summary_total['month_total'] += $month_total;
                                } else {
                                    $all_outlets_summary[$outlet->id]['month_total'] = 0;
                                }

                                if ($month_total > 0) {
                                    $all_outlets_summary[$outlet->id]['average_per_day'] = number_format($month_total / $no_of_days, 2);;
                                    $all_outlets_summary_total['average_per_day'] += $month_total / $no_of_days;
                                } else {
                                    $all_outlets_summary[$outlet->id]['average_per_day'] = 0;
                                }

                                $all_outlets_summary[$outlet->id]['today_unique_mobile'] = $summary->today_unique_mobiles;
                                $all_outlets_summary_total['today_unique_mobile'] += $summary->today_unique_mobiles;

                                $all_outlets_summary[$outlet->id]['total_unique_mobile'] = $summary->total_unique_mobiles;
                                $all_outlets_summary_total['total_unique_mobile'] += $summary->total_unique_mobiles;
                                $isdata++;
                            } else {

                                $all_outlets_summary[$outlet->id]['month_total'] = $month_total;
                                $all_outlets_summary_total['month_total'] += $month_total;
                                $all_outlets_summary[$outlet->id]['average_per_day'] = number_format($month_total / $no_of_days, 2);
                                $all_outlets_summary_total['average_per_day'] += $month_total / $no_of_days;
                                $all_outlets_summary[$outlet->id]['flag'] = 0;
                                $all_outlets_summary[$outlet->id]['outlet_status'] = $outlet->outlet_status;
                                $all_outlets_summary[$outlet->id]['name'] = $data['outlet_name'];
                                $isdata++;
                            }
                        }
                    }
                }
            }
        }

        if ($isdata > 0 && $isdata != 0) {
            try {
                if (env("APP_ENV") == "production") {
                    $email[0] = "raj@savitriya.com";
                    // $email[1] = "dev@savitriya.com";
                    // $email[2] = "ar@tapikal.com";
                } else {
                    Log::info('local environment exit');
                    //return;
                    $email[0] = "raj@savitriya.com";
                    //                    $email[1] = "np@savitriya.com";
                }

                $data['all_outlets_summary'] = $all_outlets_summary;
                $data['all_outlets_summary_totle'] = $all_outlets_summary_total;
                $data['month_lable'] = $month_lable;

                $data['subject'] = "Pikal : All Outlet Summary";
                Mail::send('emails.allOutletSummary', array('data' => $data), function ($message) use ($data, $email) {
                    $message->from('we@pikal.io', 'Pikal');
                    $message->to($email);
                    $message->subject($data['subject']);
                });
            } catch (\Exception $e) {
                $message = 'error';
                Log::info('Data : ' . $e->getMessage());
            }
        } else {
            Log::info("No Outlet Summary found.");
        }
    }
}
