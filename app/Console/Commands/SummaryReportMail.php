<?php

namespace App\Console\Commands;

use App\DailySummary;
use App\OrderDetails;
use App\OrderItem;
use App\OrderPaymentMode;
use App\Outlet;
use App\Outlet_Menu_Bind;
use App\OutletMapper;
use App\Owner;
use App\PaymentOption;
use App\SendCloseCounterStatus;
use App\Sources;
use App\users;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Savitriya\Icici_upi\IciciUpiTxn;

class SummaryReportMail extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pikal:summary';

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
        //echo 'here';exit;
        $owners = Owner::all();
        $data = array();

        $to_date = date('Y-m-d H:i:s');
        $from_date =  date('Y-m-d H:i:s', strtotime('-1 day'));
        //$from_date = new Carbon(date('Y-m-d',strtotime('yesterday')).' 03:00:00');
        //$to_date = '2017-01-11 03:00:00';

        if (sizeof($owners) > 0) {
            foreach ($owners as $owner) {

                $outlets = Outlet::where('owner_id', $owner->id)->where('active', '=', 'Yes')
                    ->where('id', '!=', 44)->where('id', '!=', 45)->get(); //not send to chinahut outlet

                if (sizeof($outlets) > 0) {
                    foreach ($outlets as $outlet) {
                        $data['outlet_name'] = $outlet->name;

                        $is_send = SendCloseCounterStatus::where('outlet_id', $outlet->id)
                            ->whereDate('report_date', '=', Carbon::yesterday()->format('Y-m-d'))
                            ->select('is_send', 'created_at')->get();


                        if (sizeof($is_send) == 0) {

                            $total_tax = 0.0;
                            $daily_summarry = new DailySummary();
                            $daily_summarry->report_date = date('Y-m-d', strtotime('-1 day'));
                            $daily_summarry->outlet_id = $outlet->id;

                            $orders = OrderDetails::where('orders.table_end_date', '>=', $from_date)
                                ->where('orders.table_end_date', '<=', $to_date)
                                ->where('outlet_id', '=', $outlet->id)
                                ->where('orders.invoice_no', "!=", '')
                                ->where('cancelorder', '!=', 1);


                            $l_order = $orders->min('orders.totalprice');
                            $h_order = $orders->max('orders.totalprice');
                            $t_sell = $orders->sum('orders.totalcost_afterdiscount');
                            $avg = $orders->avg('orders.totalprice');
                            $t_orders = $orders->count();
                            $g_total = $orders->sum('orders.totalprice');
                            $t_person = $orders->sum('orders.person_no');

                            //today's unique number
                            $today_unique_mobile = OrderDetails::join("users as u", "u.id", "=", "orders.user_id")
                                ->where('orders.table_end_date', '>=', $from_date)
                                ->where('orders.table_end_date', '<=', $to_date)
                                ->where('outlet_id', '=', $outlet->id)
                                ->where('orders.invoice_no', "!=", '')
                                ->where('cancelorder', '!=', 1)
                                ->where('u.mobile_number', '!=', 0)
                                ->distinct()->count('u.mobile_number');

                            //total unique number
                            $total_unique_mobile = OrderDetails::join("users as u", "u.id", "=", "orders.user_id")
                                ->where('outlet_id', '=', $outlet->id)
                                ->where('orders.invoice_no', "!=", '')
                                ->where('cancelorder', '!=', 1)
                                ->where('u.mobile_number', '!=', 0)
                                ->distinct()->count('u.mobile_number');


                            $data['lowest_order'] = number_format($l_order, 0);
                            $data['highest_order'] = number_format($h_order, 0);
                            $data['total_sell'] = number_format($t_sell, 0);
                            $data['gross_total'] = number_format($g_total, 0);
                            $data['total_person'] = $t_person;
                            //	$data['total_orders'] = $orders->count();
                            $data['total_orders'] = $t_orders; //$orderscount;
                            $data['average'] = number_format($avg, 2);
                            $data['total_discount'] = 0;
                            $data['total_nc'] = 0;

                            $daily_summarry->total_orders = $t_orders;
                            $daily_summarry->total_sells = $t_sell;

                            if (sizeof($order_arr = $orders->get()) > 0) {

                                $discount = 0;
                                $nc = 0;
                                $t_cash = 0;
                                $t_prepaid = 0;
                                $t_cheque = 0;
                                $t_unpaid = 0;
                                $t_person_visit = 0;
                                $payment_opt = array();
                                foreach ($order_arr as $or) {

                                    //get discount amount and non chargeable amount
                                    $disc_amt = floatval($or->discount_value + $or->item_discount_value);
                                    $st_amt = floatval($or->totalcost_afterdiscount);
                                    if ($disc_amt == '') {
                                        $disc_amt = 0;
                                    }
                                    if ($or->totalprice == 0) {
                                        $nc += $disc_amt;
                                    } else {
                                        $discount += $disc_amt;
                                    }

                                    //get total cash and prepaid amount
                                    $payment_modes = OrderPaymentMode::where('order_id', $or->order_id)->get();

                                    if (isset($payment_modes) && sizeof($payment_modes) > 0) {
                                        foreach ($payment_modes as $py_mode) {

                                            $check_payment_type = PaymentOption::find($py_mode->payment_option_id);
                                            $source = Sources::find($py_mode->source_id);
                                            $upi_status = false;
                                            if (isset($check_payment_type) && sizeof($check_payment_type) != '') {

                                                if (strtolower($check_payment_type->name) == 'cash') {
                                                    $t_cash += $py_mode->amount;
                                                } else if (strtolower($check_payment_type->name) == 'online') {

                                                    if (isset($source) && sizeof($source) > 0) {

                                                        if (strtolower($source->name) == 'upi') {

                                                            //check payment status
                                                            $check_payment_status = IciciUpiTxn::where('status', '=', 1)->where('bill_no', $or->order_unique_id)->first();

                                                            if (isset($check_payment_status) && sizeof($check_payment_status) > 0) {
                                                                $upi_status = true;
                                                                $t_prepaid += $py_mode->amount;
                                                            } else {
                                                                $t_unpaid += $py_mode->amount;
                                                            }
                                                        } else {
                                                            $t_prepaid += $py_mode->amount;
                                                        }
                                                    } else {
                                                        $t_prepaid += $py_mode->amount;
                                                    }
                                                } else if (strtolower($check_payment_type->name) == 'cheque') {
                                                    $t_cheque += $py_mode->amount;
                                                }

                                                if (isset($source) && sizeof($source) > 0) {

                                                    if (strtolower($source->name) == 'upi') {

                                                        //check upi payment status

                                                        if ($upi_status == true) {

                                                            if (isset($payment_opt[$check_payment_type->name][$source->name])) {
                                                                $payment_opt[$check_payment_type->name][$source->name] += $py_mode->amount;
                                                            } else {
                                                                $payment_opt[$check_payment_type->name][$source->name] = $py_mode->amount;
                                                            }
                                                        } else {

                                                            //if payment status is not success than make it unpaid
                                                            if (isset($payment_opt['UnPaid'])) {
                                                                $payment_opt['UnPaid'] += $py_mode->amount;
                                                            } else {
                                                                $payment_opt['UnPaid'] = $py_mode->amount;
                                                            }
                                                        }
                                                    } else {

                                                        if (isset($payment_opt[$check_payment_type->name][$source->name])) {
                                                            $payment_opt[$check_payment_type->name][$source->name] += $py_mode->amount;
                                                        } else {
                                                            $payment_opt[$check_payment_type->name][$source->name] = $py_mode->amount;
                                                        }
                                                    }
                                                } else {

                                                    if (isset($payment_opt[$check_payment_type->name]['direct'])) {
                                                        $payment_opt[$check_payment_type->name]['direct'] += $py_mode->amount;
                                                    } else {
                                                        $payment_opt[$check_payment_type->name]['direct'] = $py_mode->amount;
                                                    }
                                                }
                                            } else {

                                                $t_unpaid += $py_mode->amount;

                                                if (isset($payment_opt['UnPaid'])) {
                                                    $payment_opt['UnPaid'] +=  $py_mode->amount;
                                                } else {
                                                    $payment_opt['UnPaid'] =  $py_mode->amount;
                                                }
                                            }
                                        }
                                    }

                                    $tax_total = 0;
                                    $json_tax = json_decode($or->tax_type);
                                    if (sizeof($json_tax) > 0 && isset($json_tax)) {
                                        foreach ($json_tax as $tx) {
                                            if (gettype($tx) == 'string')
                                                $tx1 = json_decode($tx);
                                            else
                                                $tx1 = $tx;
                                            foreach ($tx1 as $key1 => $t) {
                                                $tax_total += $t->calc_tax;
                                            }
                                        }
                                    }

                                    $total_tax += $tax_total;
                                    $t_person_visit += $or->person_no;
                                }

                                $daily_summarry->total_bifurcation = json_encode($payment_opt);

                                $data['pay_options'] = $payment_opt;
                                $data['total_discount'] = number_format($discount, 0);
                                $data['total_nc'] = number_format($nc, 0);
                                $data['total_cash'] = number_format($t_cash, 0);
                                $data['total_prepaid'] = number_format($t_prepaid, 0);
                                $data['total_cheque'] = number_format($t_cheque, 0);
                                $data['total_unpaid'] = number_format($t_unpaid, 0);
                                $net_sale = $g_total - ($discount + $nc);
                                $data['net_sale'] = number_format($net_sale, 0);

                                $avg_per_person = 0;
                                if (isset($t_person_visit) && $t_person_visit > 0) {
                                    $avg_per_person = floatval($g_total / $t_person_visit);
                                }
                                $data['avg_per_person'] =  number_format($avg_per_person, 2);

                                $daily_summarry->total_discount = $discount;
                                $daily_summarry->total_nc_order = $nc;
                                $daily_summarry->total_taxes = $total_tax;
                                $daily_summarry->total_online = $t_prepaid;
                                $daily_summarry->total_cash = $t_cash;
                                $daily_summarry->total_cheque = $t_cheque;
                                $daily_summarry->total_unpaid = $t_unpaid;
                                $daily_summarry->gross_total = $g_total;
                                $daily_summarry->gross_average = $avg;
                                $daily_summarry->lowest_order = $l_order;
                                $daily_summarry->highest_order = $h_order;
                                $daily_summarry->total_person_visit = $t_person_visit;
                                $daily_summarry->today_unique_mobiles = $today_unique_mobile;
                                $daily_summarry->total_unique_mobiles = $total_unique_mobile;

                                //	Log::info("Total Orders ==> ".sizeof($orders));
                                $items = OrderItem::join("menus", "menus.id", "=", "order_items.item_id")
                                    ->join("orders", "orders.order_id", "=", "order_items.order_id")
                                    ->select('order_items.id', "menus.item", DB::raw('ifnull(sum(order_items.item_quantity),0) as count'))
                                    //->whereBetween('order_items.created_at', array($from_date, $to_date))
                                    ->where('orders.table_end_date', '>=', $from_date)
                                    ->where('orders.table_end_date', '<=', $to_date)
                                    ->where('orders.outlet_id', '=', $outlet->id)
                                    ->where('orders.cancelorder', '!=', 1)
                                    ->where('orders.invoice_no', "!=", '')
                                    ->groupBy('item_id')
                                    ->get();

                                $unique_items = DB::table("order_items")
                                    ->join("orders", "orders.order_id", "=", "order_items.order_id")
                                    ->select('order_items.id', "order_items.item_name as item", DB::raw('ifnull(sum(order_items.item_quantity),0) as count'))
                                    ->where('orders.table_end_date', '>=', $from_date)
                                    ->where('orders.table_end_date', '<=', $to_date)
                                    ->where('orders.outlet_id', '=', $outlet->id)
                                    ->where('orders.cancelorder', '!=', 1)
                                    ->where('orders.invoice_no', "!=", '')
                                    ->groupBy('order_items.item_name')->get();

                                $active_items = Outlet_Menu_Bind::where('outlet_menu_bind.outlet_id', $outlet->id)
                                    ->join("menus", "menus.id", "=", "outlet_menu_bind.menu_id")
                                    ->where("menus.active", 0)->get();

                                $data['top_selling_item'] = "None";
                                $count = 0;
                                $total_item_sell = 0;
                                foreach ($items as $item) {
                                    if ($item->count > $count) {
                                        $count = $item->count;
                                        $data['top_selling_item'] = ucfirst($item->item);

                                        $daily_summarry->top_selling_item = ucfirst($item->item);
                                        $daily_summarry->top_selling_item_id = $item->id;
                                    }
                                    $total_item_sell += $item->count;
                                }
                                $daily_summarry->total_unique_item_sell = sizeof($unique_items);
                                $daily_summarry->total_item_sell = $total_item_sell;
                                $daily_summarry->active_item = sizeof($active_items);
                                if ($t_person == 0 || $t_person == '') {
                                    $daily_summarry->tot_sale_per_person = 0;
                                } else {
                                    $daily_summarry->tot_sale_per_person = $t_sell / $t_person;
                                }


                                $cancel_order = OrderDetails::leftJoin('order_cancellation_mapper as ocm', 'ocm.order_id', '=', 'orders.order_id')
                                    ->leftJoin('owners as o', 'o.id', '=', 'ocm.created_by')
                                    ->select('orders.*', 'ocm.reason as reason', 'o.user_name as user_name')
                                    ->where('orders.table_end_date', '>=', $from_date)
                                    ->where('orders.table_end_date', '<=', $to_date)
                                    ->where('orders.outlet_id', '=', $outlet->id)
                                    ->where('orders.cancelorder', '=', 1);
                                //print_r($cancel);exit;
                                $c_order_amt = $cancel_order->sum('orders.totalprice');
                                $c_order_cnt = $cancel_order->count();

                                $data['cancel_order'] = $c_order_cnt;
                                $data['cancel_amount'] = number_format($c_order_amt, 0);
                                $data['cancel_order_arr'] = $cancel_order->get();

                                $daily_summarry->cancel_order_count = $c_order_cnt;
                                $daily_summarry->cancel_order_amount = number_format($c_order_amt, 0);
                                $daily_summarry->save();

                                $data['subject'] = $outlet->name . ' ' . date('d-m-Y', strtotime('yesterday'));
                                $emails = array();
                                if (isset($outlet->report_emails) && $outlet->report_emails != '') {
                                    $emails = explode(',', $outlet->report_emails);
                                }

                                /*if (env("APP_ENV") == "production") {
                                    $emails1 = array("dev@savitriya.com");
                                    $allemail = array_merge($emails, $emails1);
                                } else {*/
                                $allemail = $emails;
                                //}
                                $a = Carbon::parse($outlet->start_date)->diff(Carbon::parse($outlet->end_date));
                                //$total_hours = $a->format("%H:%i");
                                $total_hours = Outlet::getTotalHours($outlet->id, $from_date);


                                $mobiles = explode(',', $outlet->contact_no);
                                if (env("APP_ENV") == "production") {
                                    if (isset($mobiles) && sizeof($mobiles) > 0) {
                                        foreach ($mobiles as $mob) {
                                            $send_sms = users::sendStatusMessage($outlet->name, $mob, $outlet->status_sms, $total_hours, $data['total_orders'], $data['total_person'], $data['total_sell'], $data['gross_total'], $data['net_sale'], $data['cancel_order']);
                                        }
                                    }
                                }

                                $status = SendCloseCounterStatus::where('id', $outlet->id)->update(array('sms_count' => sizeof($mobiles), 'is_send' => '1'));

                                if (sizeof($allemail) > 0) {
                                    foreach ($allemail as $email) {
                                        try {
                                            Mail::send('emails.commondailysummaryreport', array('data' => $data), function ($message) use ($data, $email) {
                                                $message->from('we@pikal.io', 'Pikal');
                                                $message->to($email);
                                                $message->subject($data['subject']);
                                            });
                                            //	Log::info('Mail Sent to : ' . $email);

                                        } catch (\Exception $e) {
                                            $message = 'error';
                                            //Log::info('Data : ' . $e->getMessage());
                                        }
                                    }
                                }
                            } else {
                                //	Log::info("No Orders Found.");
                            }
                        }
                    }
                }
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
