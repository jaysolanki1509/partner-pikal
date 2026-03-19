<?php namespace App\Console\Commands;

use App\DailySummary;
use App\order_details;
use App\OrderItem;
use App\OrderPaymentMode;
use App\Outlet;
use App\Outlet_Menu_Bind;
use App\PaymentOption;
use App\SendCloseCounterStatus;
use App\Sources;
use App\users;
use Aws\Common\Enum\Time;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Savitriya\Icici_upi\IciciUpiTxn;

class SendCounterStatus extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pikal:counterstatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Counter Close Status.';

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

        //Log::info('Mail Sent to : hellolloooo');
        $today = date('Y-m-d');
        $hour_check = date("H");

        if ( $hour_check < 4 ) {
            $today = date('Y-m-d',strtotime('-1 day'));
        }

        $outlets = SendCloseCounterStatus::whereDate('report_date','=',$today)->where('is_send',0)->get();

        if ( isset($outlets) && sizeof($outlets) > 0 ) {
            foreach( $outlets as $ot ) {

                $start_date = Carbon::parse($ot->start_date);
                $end_date = Carbon::parse($ot->end_date);
                $outlet_id = $ot->outlet_id;
                $remarks = $ot->remarks;
                $amount_byuser = $ot->total_from_user;
                $user_cash = $ot->total_cash;
                $user_online = $ot->total_online;
                $user_cheque = $ot->total_cheque;

                $a=$start_date->diff($end_date);
                $total_hours1 = Outlet::getTotalHours($outlet_id,$start_date);
                //$start_time= $start_date->format("H:i");
                //$end_time= $end_date->format("H:i");

                //check current time
                if ( $hour_check < 4 ) {
                    $from_date =  date('Y-m-d',strtotime('-1 day')).' 04:01:00';
                    $to_date = date('Y-m-d').' 04:00:00';
                } else {
                    $from_date = new Carbon(date('Y-m-d').' 04:01:00');
                    $to_date = date('Y-m-d',strtotime('1 day')).' 04:00:00';
                }

                $start_time = order_details::where('outlet_id',$outlet_id)
                            ->orderBy('order_id','asc')
                            ->wherebetween('table_end_date',array($from_date,$to_date))->first();

                if(isset($start_time) && sizeof($start_time)>0 ){
                    $start_time1 = new \DateTime($start_time->table_start_date);
                    $start_time = $start_time1->format("H:i");
                    $end_time1 = new \DateTime($ot->updated_at);
                    $end_time = $end_time1->format("H:i");
                }else{
                    $start_time = '00:00';
                    $end_time = '00:00';
                }

                $diff = ((new \DateTime($start_time))->diff(new \DateTime($end_time)));
                $total_hours = $diff->format("%H:%I");
                $outlet=Outlet::where('id',$outlet_id)->first();

                $total_tax = 0.0;
                $data['outlet_name'] = $outlet->name;
                $data['start_date'] = $start_date;
                $data['close_date'] = $end_date;
                $data['total_hours'] = $total_hours;
                $data['start_time'] = $start_time;
                $data['end_time'] = $end_time;
                $data['remarks'] = $remarks;



                $orders = order_details::where('orders.table_end_date','>=', $from_date)
                    ->where('orders.table_end_date','<=', $to_date)
                    ->where('orders.outlet_id','=',$outlet->id)
                    ->where('orders.invoice_no',"!=",'')
                    ->where('orders.cancelorder', '!=', 1);

                //$orderscount = order_details::where('table_start_date','>=', (new Carbon($from_date))->startOfDay())
                    //->where('table_start_date','<=', (new Carbon($to_date))->endOfDay())->where('outlet_id','=',$outlet->id)->count();

                $t_sell = $orders->sum('orders.totalcost_afterdiscount');
                $g_total = $orders->sum('orders.totalprice');
                $avg = $orders->avg('orders.totalprice');
                $h_order = $orders->max('orders.totalprice');
                $l_order = $orders->min('orders.totalprice');

                //today's unique number
                $today_unique_mobile = order_details::join("users as u", "u.id", "=", "orders.user_id")
                                                    ->where('orders.table_end_date', '>=', $from_date)
                                                    ->where('orders.table_end_date', '<=', $to_date)
                                                    ->where('outlet_id', '=', $outlet->id)
                                                    ->where('orders.invoice_no', "!=", '')
                                                    ->where('cancelorder', '!=', 1)
                                                    ->where('u.mobile_number','!=',0)
                                                    ->distinct()->count('u.mobile_number');

                //total unique number
                $total_unique_mobile = order_details::join("users as u", "u.id", "=", "orders.user_id")
                                                    ->where('outlet_id', '=', $outlet->id)
                                                    ->where('orders.invoice_no', "!=", '')
                                                    ->where('cancelorder', '!=', 1)
                                                    ->where('u.mobile_number','!=',0)
                                                    ->distinct()->count('u.mobile_number');

                $data['total_sell'] = number_format($t_sell,0);
                $data['gross_total'] = number_format($g_total,0);
                $data['total_person'] = $orders->sum('orders.person_no');
                $data['lowest_order'] = number_format($l_order,0);
                $data['highest_order'] = number_format($h_order,0);

                $data['total_orders'] = $orders->count();
                //$data['total_orders'] = $orderscount;
                $data['average'] =  number_format($avg, 2);
                $data['total_discount'] = 0;
                $data['total_nc'] = 0;

                $Camount = preg_replace('/,/', '', $data['gross_total']);
                $Camount = preg_replace('/\s*/', '', $Camount);

                $data['total_byuser']= number_format($amount_byuser,0);
                $data['total_diff']= number_format($Camount - $amount_byuser,0);
                $data['user_cash'] = number_format($user_cash,0);
                $data['user_online'] = number_format($user_online,0);
                $data['user_cheque'] = number_format($user_cheque,0);

                if( sizeof($order_arr = $orders->get()) > 0  ){

                    $discount = 0 ;$nc = 0;$t_cash = 0;$t_prepaid = 0;$t_cheque = 0;$t_unpaid = 0;$t_person_visit = 0;$payment_opt = array();
                    foreach( $order_arr as $or ) {

                        //get discount amount and non chargeable amount
                        $disc_amt = floatval($or->discount_value) + floatval($or->item_discount_value);
                        $st_amt = floatval($or->totalcost_afterdiscount);
                        if ( $disc_amt == '') {
                            $disc_amt = 0;
                        }
                        if ( $disc_amt == $st_amt ) {
                            $nc += $disc_amt;
                        } else {
                            $discount += $disc_amt;
                        }

                        //get total cash and prepaid amount
                        $payment_modes = OrderPaymentMode::where('order_id',$or->order_id)->get();

                        if ( isset($payment_modes) && sizeof($payment_modes) > 0 ) {
                            foreach ( $payment_modes as $py_mode ) {

                                $check_payment_type = PaymentOption::find($py_mode->payment_option_id);
                                $source = Sources::find($py_mode->source_id);
                                $upi_status = false;
                                if ( isset($check_payment_type) && sizeof($check_payment_type) != '' ) {

                                    if ( strtolower($check_payment_type->name) == 'cash' ) {
                                        $t_cash += $py_mode->amount;
                                    } else if ( strtolower($check_payment_type->name) == 'online' ) {

                                        if ( isset($source) && sizeof($source) > 0 ) {

                                            if ( strtolower($source->name) == 'upi' ) {

                                                //check payment status
                                                $check_payment_status = IciciUpiTxn::where('status','=',1)->where('bill_no',$or->order_unique_id)->first();

                                                if( isset($check_payment_status) && sizeof($check_payment_status) > 0 ) {
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

                                    } else if ( strtolower($check_payment_type->name) == 'cheque' ) {
                                        $t_cheque += $py_mode->amount;
                                    }

                                    if ( isset($source) && sizeof($source) > 0 ) {

                                        if ( strtolower($source->name) == 'upi' ) {

                                            //check upi payment status
                                            if( $upi_status == true ) {

                                                if ( isset($payment_opt[$check_payment_type->name][$source->name])) {
                                                    $payment_opt[$check_payment_type->name][$source->name] +=  $py_mode->amount;
                                                } else {
                                                    $payment_opt[$check_payment_type->name][$source->name] =  $py_mode->amount;
                                                }

                                            } else {

                                                //if payment status is not success than make it unpaid
                                                if ( isset($payment_opt['UnPaid'])) {
                                                    $payment_opt['UnPaid'] +=  $py_mode->amount;
                                                } else {
                                                    $payment_opt['UnPaid'] =  $py_mode->amount;
                                                }

                                            }

                                        } else {

                                            if ( isset($payment_opt[$check_payment_type->name][$source->name])) {
                                                $payment_opt[$check_payment_type->name][$source->name] +=  $py_mode->amount;
                                            } else {
                                                $payment_opt[$check_payment_type->name][$source->name] = $py_mode->amount;
                                            }

                                        }


                                    } else {

                                        if ( isset($payment_opt[$check_payment_type->name]['direct'])) {
                                            $payment_opt[$check_payment_type->name]['direct'] += $py_mode->amount;
                                        } else {
                                            $payment_opt[$check_payment_type->name]['direct'] = $py_mode->amount;
                                        }

                                    }

                                } else {
                                    $t_unpaid += $or->totalprice;

                                    if ( isset($payment_opt['UnPaid'])) {
                                        $payment_opt['UnPaid'] += $py_mode->amount;
                                    } else {
                                        $payment_opt['UnPaid'] = $py_mode->amount;
                                    }
                                }

                            }
                        }


                        //get total cash and prepaid amount
                        /*if ( strtolower($or->paid_type) == 'cod' ) {
                            $t_cash += $or->totalprice;
                        } else {
                            $t_prepaid += $or->totalprice;
                        }*/

                        $tax_total=0;
                        $json_tax=json_decode($or->tax_type);
                        if(sizeof($json_tax)>0 && isset($json_tax))
                        {
                            foreach( $json_tax as $tx ){
                                if(gettype($tx) == 'string')
                                    $tx1 = json_decode($tx);
                                else
                                    $tx1 = $tx;
                                foreach( $tx1 as $key1=>$t){
                                    $tax_total += $t->calc_tax;
                                }
                            }
                        }

                        $total_tax += $tax_total;
                        $t_person_visit += $or->person_no;

                    }

                    #TODO: Add summary detail in table

                    $daily_summarry = DailySummary::where('report_date',$ot->report_date)->where('outlet_id',$outlet_id)->first();
                    if ( isset($daily_summarry) && sizeof($daily_summarry) > 0 ) {
                    } else {
                        $daily_summarry = new DailySummary();
                    }

                    $daily_summarry->total_bifurcation = json_encode($payment_opt);

                    $data['pay_options'] = $payment_opt;
                    $data['total_discount'] = number_format($discount,0);
                    $data['total_nc'] = number_format($nc,0);
                    $data['total_cash'] = number_format($t_cash,0);
                    $data['cash_diff'] = number_format($user_cash-$t_cash,2);
                    $data['total_prepaid'] = number_format($t_prepaid,0);
                    $data['prepaid_diff'] = number_format($user_online-$t_prepaid,2);
                    $data['total_cheque'] = number_format($t_cheque,0);
                    $data['cheque_diff'] = number_format($user_cheque-$t_cheque,2);
                    $data['total_unpaid'] = number_format($t_unpaid,0);
                    $net_sale = $g_total - ($discount + $nc);
                    $data['net_sale'] = number_format($net_sale,0);

                    if ( $t_person_visit < 1 ) {
                        $avg_per_person = 'N/A';
                        $data['avg_per_person'] = 'N/A';

                    } else {

                        $avg_per_person = floatval($g_total/$t_person_visit);
                        $data['avg_per_person'] =  number_format($avg_per_person,2);

                    }



                    // Log::info("Total Orders ==> ".sizeof($orders));
                    $items = OrderItem::join("orders", "orders.order_id", "=", "order_items.order_id")
                        ->join('menus','menus.id','=','order_items.item_id')
                        ->select('order_items.id', "menus.item", DB::raw('ifnull(sum(order_items.item_quantity),0) as count'))
                        ->where('orders.table_end_date','>=', $from_date)
                        ->where('orders.table_end_date','<=', $to_date)
                        ->where('orders.outlet_id','=',$outlet->id)
                        ->where('orders.cancelorder', '!=', 1)
                        ->where('orders.invoice_no',"!=",'')
                        ->groupBy('order_items.item_name')
                        ->get();

                    $data['top_selling_item'] = "None";
                    $count = 0;$total_item_sell = 0;
                    foreach ($items as $item) {
                        if ($item->count > $count) {
                            $count = $item->count;
                            $data['top_selling_item'] = ucfirst($item->item);
                            $daily_summarry->top_selling_item = ucfirst($item->item);
                            $daily_summarry->top_selling_item_id = $item->id;
                        }
                        $total_item_sell += $item->count;
                    }

                    $active_items = Outlet_Menu_Bind::where('outlet_menu_bind.outlet_id', $outlet->id)
                        ->join("menus", "menus.id", "=", "outlet_menu_bind.menu_id")
                        ->where("menus.active", 0)->get();

                    $cancel_order = order_details::leftJoin('order_cancellation_mapper as ocm','ocm.order_id','=','orders.order_id')
                        ->leftJoin('owners as o','o.id','=','ocm.created_by')
                        ->select('orders.*','ocm.reason as reason','o.user_name as user_name')
                        ->where('orders.table_end_date','>=', $from_date)
                        ->where('orders.table_end_date','<=', $to_date)
                        ->where('orders.outlet_id','=',$outlet->id)
                        ->where('orders.cancelorder', '=', 1);

                    $cancel_amt = $cancel_order->sum('orders.totalprice');
                    $data['cancel_order'] = $cancel_order->count();
                    $data['cancel_amount'] = number_format($cancel_amt,0);
                    $data['cancel_order_arr'] = $cancel_order->get();

                    $data['subject'] = $outlet->name.' '.date('d-m-Y',strtotime($ot->report_date));
                    $emails = array();
                    if (isset($outlet->report_emails) && $outlet->report_emails != ''){
                        $emails = explode(',',$outlet->report_emails);
                    }

                    //$emails1 = array();
                    /*if(env("APP_ENV")=="production") {
                        $emails1 = array("dev@savitriya.com");
                        $allemail = array_merge($emails, $emails1);
                    }else{*/
                        $allemail = $emails;
                    //}

                    $mobiles = explode(',',$outlet->contact_no);
                    if(env("APP_ENV")=="production") {
                        if (isset($mobiles) && sizeof($mobiles) > 0) {
                            foreach ($mobiles as $mob) {
                                $send_sms = users::sendStatusMessage($outlet->name, $mob, $outlet->status_sms, $total_hours1, $data['total_orders'], $data['total_person'], $data['total_sell'], $data['gross_total'], $data['net_sale'], $data['cancel_order']);
                            }
                        }
                    }

                    $daily_summarry->today_unique_mobiles = $today_unique_mobile;
                    $daily_summarry->total_unique_mobiles = $total_unique_mobile;
                    $daily_summarry->report_date = $ot->report_date;
                    $daily_summarry->outlet_id = $outlet->id;
                    $daily_summarry->total_orders = $data['total_orders'];
                    $daily_summarry->total_sells = $t_sell;
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
                    $daily_summarry->total_unique_item_sell = sizeof($items);
                    $daily_summarry->total_item_sell = $total_item_sell;
                    $daily_summarry->active_item = sizeof($active_items);
                    $daily_summarry->tot_sale_per_person = $avg_per_person;
                    $daily_summarry->cancel_order_count = $data['cancel_order'];
                    $daily_summarry->cancel_order_amount = $cancel_amt;
                    $daily_summarry->save();

                    $status = SendCloseCounterStatus::where('id', $ot->id)->update(array('sms_count' => sizeof($mobiles), 'is_send' => '1'));

                    if ( sizeof($allemail) > 0 ){
                        foreach($allemail as $email){
                            //try {
                            Mail::send('emails.dailysummaryreport', array('data' => $data), function ($message) use ($data, $email) {
                                $message->from('we@pikal.io', 'Pikal');
                                $message->to($email);
                                $message->subject($data['subject']);
                            });
                            //Log::info('Mail Sent to : ' . $email);

                            //} catch (Throwable $e) {
                            // $message = 'error';
                            //Log::info('Data : ' . $e->getMessage());
                            //}
                        }
                    }

                }else{
                    //Log::info("No Orders Found.");
                }

            }
        }

    }

}
