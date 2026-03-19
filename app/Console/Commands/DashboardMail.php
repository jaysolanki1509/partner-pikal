<?php namespace App\Console\Commands;

use App\Attendance;
use App\Expense;
use App\invoice_detail;
use App\InvoiceBill;
use App\ItemRequest;
use App\Location;
use App\Menu;
use App\order_details;
use App\Outlet;
use App\OutletMapper;
use App\Owner;
use App\Staff;
use App\Utils;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DashboardMail extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'pikal:dashboardmail';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Dashboard Mail.';

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
       /* $from_time = "00:00";
        $to_time = "23:59";
        $fromdate = date('Y-m-d');
        $todate = date('Y-m-d');

        $today_dt = strtotime($todate);
        $fromdate_dt = strtotime($fromdate);

        $from_date_time = $fromdate . ' ' . $from_time . ':00';
        $to_date_time = $todate . ' ' . $to_time . ':00';*/

        $sod = date('Y:m:d H:i:s');  //start of day
        $som = Carbon::now()->startOfMonth();  //start of month
        $eod = date('Y-m-d H:i:s',strtotime('-1 day'));    //end of day

        $outlets = Outlet::where('active','=','Yes')->where('id','!=',44)->where('id','!=',45)->get(); //not send to chinahut outlet

        foreach ($outlets as $outlet){

            $user_id = $outlet->owner_id;
            $auth_user = $outlet->authorised_users;
            $auth_user_find = Owner::find($auth_user);
            $owner = Owner::find($user_id);

            if(isset($auth_user_find) && sizeof($auth_user_find)) {
                if ($auth_user_find->user_name == 'govind') {

                    $data = array();
                    $date_revenue = 0;
                    $month_revenue = 0;
                    $date_person = 0;
                    $month_person = 0;
                    $yesterdayDate = Carbon::yesterday()->format('d');

                    //Revenue & persons & orders
                    $date_ord = order_details::where('orders.outlet_id', $outlet->id)
                        ->where('orders.table_end_date', '>=', $sod)
                        ->where('orders.table_end_date', '<=', $eod)
                        ->where('orders.cancelorder', '!=', '1')
                        ->where('orders.invoice_no', "!=", '')
                        ->get();

                    $date_tot_order = sizeof($date_ord);

                    foreach ($date_ord as $dorder) {
                        $date_person += $dorder->person_no;
                        $date_revenue += $dorder->totalprice;
                    }

                    $month_ord = order_details::where('orders.outlet_id', $outlet->id)
                        ->where('orders.table_end_date', '>=', $som)
                        ->where('orders.table_end_date', '<=', $eod)
                        ->where('orders.cancelorder', '!=', '1')
                        ->where('orders.invoice_no', "!=", '')
                        ->get();

                    $month_tot_order = sizeof($month_ord);

                    foreach ($month_ord as $morder) {
                        $month_person += $morder->person_no;
                        $month_revenue += $morder->totalprice;
                    }

                    $data['date_tot_order'] = $date_tot_order;
                    $data['date_person'] = $date_person;
                    $data['date_revenue'] = number_format($date_revenue, 2);
                    $data['month_tot_ord'] = $month_tot_order;
                    $data['avg_ord'] = number_format($month_tot_order / $yesterdayDate, 2);
                    $data['month_person'] = $month_person;
                    $data['month_revenue'] = number_format($month_revenue, 2);
                    if ($month_person > 0)
                        $data['revenue_per_person'] = number_format($month_revenue / $month_person, 2);
                    else
                        $data['revenue_per_person'] = 0.00;
                    $data['avg_revenue'] = number_format($month_revenue / $yesterdayDate, 2);
                    $data['avg_person'] = number_format($month_person / $yesterdayDate, 2);

                    //Expense
                    $date = date('Y-m-d', strtotime($sod));

                    $date_expense = Expense::where('expense_for', $outlet->id)
                        ->whereRaw("(status= 'verified' || status = 'paid') && expense_date = '$date'")
                        ->sum('amount');

                    $month_expense = Expense::where('expense_for', $outlet->id)
                        ->whereRaw("(status= 'verified' || status = 'paid')")
                        ->where('expense_date', '>=', $som)
                        ->where('expense_date', '<=', $eod)
                        ->sum('amount');

                    $avg_expense = number_format($month_expense / $yesterdayDate, 2);

                    $data['date_expense'] = number_format($date_expense, 2);
                    $data['month_expense'] = number_format($month_expense, 2);
                    $data['avg_expense'] = $avg_expense;


                    //stock received
                    $day_stock = 0;
                    $month_stock = 0;
                    $locations = Location::where('outlet_id', $outlet->id)->get();
                    $loc_ids = array();
                    if (isset($locations) && sizeof($locations) > 0) {
                        foreach ($locations as $loc) {
                            $loc_ids[] = $loc->id;
                        }
                    }

                    if (isset($loc_ids) && sizeof($loc_ids) > 0) {

                        //get request for location
                        $item_requests_day = ItemRequest::select('item_request.*')
                            ->whereBetween('satisfied_when', array($sod, $eod))
                            ->where('item_request.satisfied', '=', 'Yes')
                            ->whereIn('item_request.location_for', $loc_ids)
                            ->get();
                        $item_requests_month = ItemRequest::select('item_request.*')
                            ->whereBetween('satisfied_when', array($som, $eod))
                            ->where('item_request.satisfied', '=', 'Yes')
                            ->whereIn('item_request.location_for', $loc_ids)
                            ->get();

                        if (isset($item_requests_day) && sizeof($item_requests_day) > 0) {
                            foreach ($item_requests_day as $req) {

                                $satisfy_qty = $req->statisfied_qty;
                                $menu_item = Menu::find($req->what_item_id);

                                if (isset($menu_item->secondary_units) && $menu_item->secondary_units != '') {
                                    $units = json_decode($menu_item->secondary_units);
                                    if (isset($units) && $units != '') {
                                        foreach ($units as $key => $u) {
                                            if ($key == $req->satisfied_unit_id) {
                                                $satisfy_qty = floatval($req->statisfied_qty) * floatval($u);
                                            }
                                        }
                                    }

                                }

                                //get transferred price
                                $day_stock += $satisfy_qty * $req->price;

                            }
                        }

                        if (isset($item_requests_month) && sizeof($item_requests_month) > 0) {
                            foreach ($item_requests_month as $req) {

                                $satisfy_qty = $req->statisfied_qty;
                                $menu_item = Menu::find($req->what_item_id);

                                if (isset($menu_item->secondary_units) && $menu_item->secondary_units != '') {
                                    $units = json_decode($menu_item->secondary_units);
                                    if (isset($units) && $units != '') {
                                        foreach ($units as $key => $u) {
                                            if ($key == $req->satisfied_unit_id) {
                                                $satisfy_qty = floatval($req->statisfied_qty) * floatval($u);
                                            }
                                        }
                                    }
                                }
                                //get transferred price
                                $month_stock += $satisfy_qty * $req->price;

                            }
                        }

                    }

                    $avg_stock = number_format($month_stock / $yesterdayDate, 2);

                    $data['day_stock'] = number_format($day_stock, 2);
                    $data['month_stock'] = number_format($month_stock, 2);
                    $data['avg_stock'] = $avg_stock;

                    //purchase
                    $locations = Location::getLocationByOutletId($outlet->id);
                    $location_arr = array();
                    foreach ($locations as $location) {
                        $location_arr[] = $location->id;
                    }

                    $day_purchase = InvoiceBill::whereIn('location_id', $location_arr)
                        ->where('invoice_date', '=', $date)
                        ->sum('total');

                    $month_purchase = InvoiceBill::whereIn('location_id', $location_arr)
                        ->where('invoice_date', '>=', $som)
                        ->where('invoice_date', '<=', $eod)
                        ->sum('total');

                    $avg_purchase = number_format($month_purchase / $yesterdayDate, 2);

                    $data['day_purchase'] = number_format($day_purchase, 2);
                    $data['month_purchase'] = number_format($month_purchase, 2);
                    $data['avg_purchase'] = $avg_purchase;


                    //StaffCost
                    $staff_cost_day = 0;
                    $staff_cost_month = 0;
                    $salary = array();

                    $staffs = Staff::where('outlet_id', $outlet->id)->get();

                    if (isset($staffs) && sizeof($staffs) > 0) {

                        foreach ($staffs as $stf) {

                            $time = "00:00:00";
                            $attendance = Attendance::where('staff_id', $stf->id)
                                ->whereDate('created_at', '>=', $sod)->get();

                            if (isset($attendance) && sizeof($attendance) > 0) {

                                foreach ($attendance as $att) {

                                    $from = $att->in_time;
                                    $to = $att->out_time;
                                    if (isset($to) && $to != '') {

                                        $date1 = new DateTime($from);
                                        $date2 = new DateTime($to);
                                        $interval = $date1->diff($date2);
                                        $elapsed = $interval->format('%h:%i:%S');

                                        $secs = strtotime($elapsed) - strtotime("00:00:00");
                                        $time = date("H:i:s", strtotime($time) + $secs);

                                    }

                                }

                            }
                            //$data[$date][$stf->id] = $time;

                            //calculate salary
                            if (isset($stf->per_day) && isset($stf->per_day_hours) && $time != '00:00:00' && $stf->per_day_hours != '00:00:00' && $stf->per_day > 0) {

                                $full_time = $half_time = $spent_time = 0;
                                $time_arr = explode(':', $stf->per_day_hours);
                                if (isset($time_arr) && sizeof($time_arr) > 0) {

                                    $full_time = $time_arr[0] * 60;
                                    $full_time = $full_time + $time_arr[1];
                                    $half_time = $full_time / 2;

                                    $spent_arr = explode(':', $time);
                                    $spent_time = $spent_arr[0] * 60;
                                    $spent_time = $spent_time + $spent_arr[1];

                                }

                                if ($spent_time >= $full_time) {

                                    if (!isset($salary[$stf->id]))
                                        $salary[$stf->id] = $stf->per_day;
                                    else
                                        $salary[$stf->id] += $stf->per_day;

                                } elseif ($spent_time >= $half_time && $spent_time != 0) {

                                    if (!isset($salary[$stf->id]))
                                        $salary[$stf->id] = $stf->per_day / 2;
                                    else
                                        $salary[$stf->id] += $stf->per_day / 2;

                                } else {

                                    if (!isset($salary[$stf->id]))
                                        $salary[$stf->id] = 0.00;
                                    else
                                        $salary[$stf->id] += 0.00;

                                }

                            } else {

                                if (!isset($salary[$stf->id]))
                                    $salary[$stf->id] = 0.00;
                                else
                                    $salary[$stf->id] += 0.00;

                            }
                            $staff_cost_day += $salary[$stf->id];
                        }
                    }


                    $date_arr = Utils::createDateRangeArray($som, $eod);

                    foreach ($date_arr as $dt) {
                        $staff_cost_per_day = 0;
                        unset($salary);
                        $salary = array();
                        foreach ($staffs as $stf) {

                            $time = "00:00:00";
                            $attendance = Attendance::where('staff_id', $stf->id)
                                ->whereDate('created_at', '=', $dt)->get();

                            if (isset($attendance) && sizeof($attendance) > 0) {

                                foreach ($attendance as $att) {

                                    $from = $att->in_time;
                                    $to = $att->out_time;
                                    if (isset($to) && $to != '') {

                                        $date1 = new DateTime($from);
                                        $date2 = new DateTime($to);
                                        $interval = $date1->diff($date2);
                                        $elapsed = $interval->format('%h:%i:%S');

                                        $secs = strtotime($elapsed) - strtotime("00:00:00");
                                        $time = date("H:i:s", strtotime($time) + $secs);

                                    }

                                }

                            }
                            //$data[$date][$stf->id] = $time;

                            //calculate salary
                            if (isset($stf->per_day) && isset($stf->per_day_hours) && $time != '00:00:00' && $stf->per_day_hours != '00:00:00' && $stf->per_day > 0) {

                                $full_time = $half_time = $spent_time = 0;
                                $time_arr = explode(':', $stf->per_day_hours);
                                if (isset($time_arr) && sizeof($time_arr) > 0) {

                                    $full_time = $time_arr[0] * 60;
                                    $full_time = $full_time + $time_arr[1];
                                    $half_time = $full_time / 2;

                                    $spent_arr = explode(':', $time);
                                    $spent_time = $spent_arr[0] * 60;
                                    $spent_time = $spent_time + $spent_arr[1];

                                }

                                if ($spent_time >= $full_time) {

                                    if (!isset($salary[$stf->id]))
                                        $salary[$stf->id] = $stf->per_day;
                                    else
                                        $salary[$stf->id] += $stf->per_day;

                                } elseif ($spent_time >= $half_time && $spent_time != 0) {

                                    if (!isset($salary[$stf->id]))
                                        $salary[$stf->id] = $stf->per_day / 2;
                                    else
                                        $salary[$stf->id] += $stf->per_day / 2;

                                } else {

                                    if (!isset($salary[$stf->id]))
                                        $salary[$stf->id] = 0.00;
                                    else
                                        $salary[$stf->id] += 0.00;
                                }

                            } else {

                                if (!isset($salary[$stf->id]))
                                    $salary[$stf->id] = 0.00;
                                else
                                    $salary[$stf->id] += 0.00;

                            }
                            $staff_cost_per_day += $salary[$stf->id];
                        }
                        $staff_cost_month += $staff_cost_per_day;
                    }

                    $avg_staff_cost = number_format($staff_cost_month / $yesterdayDate, 2);

                    $data['day_staff_cost'] = number_format($staff_cost_day, 2);
                    $data['month_staff_cost'] = number_format($staff_cost_month, 2);
                    $data['avg_staff_cost'] = $avg_staff_cost;


                    //cancel order report
                    $cancel_order_amount = order_details::where('table_end_date', '>=', $sod)
                        ->where('table_end_date', '<=', $eod)
                        ->where('outlet_id', '=', $outlet->id)
                        ->where('cancelorder', "1")
                        ->sum('totalprice');

                    $cancel_order_count = order_details::where('table_end_date', '>=', $sod)
                        ->where('table_end_date', '<=', $eod)
                        ->where('outlet_id', '=', $outlet->id)
                        ->where('cancelorder', "1")
                        ->get();


                    $data['cancel_order_amount'] = number_format($cancel_order_amount, 2);
                    $data['cancel_order_count'] = sizeof($cancel_order_count);


                    //Profit or Loss

                    $total = $date_revenue - ($date_expense + $day_stock + $day_purchase + $staff_cost_day);
                    $data['result_value'] = number_format($total,2);

                    if ($total > 0)
                        $data['result'] = 'profit';
                    else
                        $data['result'] = 'loss';

                    //send email
                    $data['subject'] = $outlet->name . ' Dashboard ' . date('d-m-Y', strtotime('yesterday'));
                    $data['outlet_name'] = $outlet->name . ' Dashboard ' . date('d-m-Y', strtotime('yesterday'));
                    $emails = array();
                    if (isset($outlet->report_emails) && $outlet->report_emails != '') {
                        $emails = explode(',', $outlet->report_emails);
                    }

                   /* if (env("APP_ENV") == "production") {
                        $emails1 = array("dev@savitriya.com");
                        $allemail = array_merge($emails, $emails1);
                    } else {*/
                        $allemail = $emails;
                    //}

                    if (sizeof($allemail) > 0) {
                        foreach ($allemail as $email) {
                            try {
                                Mail::send('emails.dashboardreport', array('data' => $data), function ($message) use ($data, $email) {
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
