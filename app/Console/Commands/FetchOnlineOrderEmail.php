<?php namespace App\Console\Commands;

use App\Http\Controllers\newordercontroller;
use App\Menu;
use App\order_details;
use App\Outlet;
use App\PaymentOption;
use App\Sources;
use Aws\CloudFront\Exception\Exception;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\HtmlDom;

class FetchOnlineOrderEmail extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
        protected $name = 'pikal:fetchOnlineOrderEmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Online Order Email.';

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

        /* connect to gmail */
        $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
        $username = 'np@savitriya.com';
        $password = 'Nitin@123';

        try {
            /* try to connect */
            $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
        }catch ( \Exception $e){
            echo $e->getMessage();exit;
        }

        /* grab emails */
        $emails = imap_search($inbox,'UNSEEN');

        /* if emails are returned, cycle through each... */
        if($emails) {

            /* put the newest emails on top */
            rsort($emails);

            /* for every email... */
            $k=0;
            foreach($emails as $email_number) {

                if ( $k == 1 ) {
                    break;
                }

                $flag = $mail_subject = $from = $date = $message = '';

                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox,$email_number,0);

                /* output the email header information */
                $flag = $overview[0]->seen ? 'read' : 'unread'."\n";
                $mail_subject = $overview[0]->subject."\n";
                $from = $overview[0]->from."\n";
                $date = $overview[0]->date."\n";

                //if flag is read than change flag
                if ( $flag == 'read') {
                    continue;
                }

                if (!preg_match('/ New online order received /',$mail_subject))
                    continue;

                //match order date
                $ord_date = date('Y-m-d',strtotime($date));
                $today = date('Y-m-d');

                if ( $ord_date != $today ) {
                    continue;
                }

                //get email address from email from
                preg_match('#\<(.*?)\>#', $from, $match);
                //$from_email = $match[1];
                $from_email = "np@savitriya.com";

                $outlet = Outlet::where('parse_order_email',$from_email)->first();

                //check outlet bind with from email
                if ( isset($outlet) && sizeof($outlet) > 0 ) {

                    $message = imap_fetchbody($inbox, $email_number,2,FT_PEEK);
                    $message = trim(quoted_printable_decode($message));

                    $html = new HtmlDom($message);

                    $pay_mode = $customer_name = $mobile = $address = $ref_id = $date = "";
                    $delivery_charge = $total = $item_cnt= 0;
                    $item_name = $item_id = $item_price = $item_qty = $item_total = $item_attributes = array();

                    $cnt = 1;
                    $tables_size = sizeof($html->find('table'));

                    foreach ( $html->find('table') as $table ) {

                        if ( $cnt == 5 ) {

                            foreach ( $table->find('tr') as $tr ) {

                                if ( strtolower($tr->find('td',0)->plaintext) == "customer name:") {
                                    $customer_name = $tr->find('td',1)->plaintext;
                                }
                                if ( strtolower($tr->find('td',0)->plaintext) == "contact no.:") {
                                    $mobile = $tr->find('td',1)->plaintext;
                                }
                                if ( strtolower($tr->find('td',0)->plaintext) == "customer address:") {
                                    $address = $tr->find('td',1)->plaintext;
                                }
                            }

                        }

                        if ( $cnt == 6 ) {

                            $tr1 = $table->find('tr',1);
                            $orderid_arr = explode(':',trim($tr1->find('td',0)->plaintext));

                            if ( strtolower($orderid_arr[0]) == 'order id') {
                                $ref_id = $orderid_arr[1];
                            }

                            $tr2 = $table->find('tr',2);
                            $date_arr = explode(':',trim($tr2->find('td',0)->plaintext));

                            if ( strtolower($date_arr[0]) == 'date') {
                                $date = $date_arr[1];
                            }
                        }

                        if ( $cnt == 7 ) {

                            $no_of_item = sizeof($table->children(0)->children());

                            //iterate tr of tbody
                            for ( $i = 0; $i < $no_of_item; $i++ ) {

                                $td_size = sizeof($table->children(0)->children($i)->children());

                                //iterate td of tr
                                for ( $j = 0; $j < $td_size; $j++ ) {

                                    $td = $table->children(0)->children($i)->children($j);

                                    if ( $j == 0 ) {

                                        //item name
                                        $check_customize = $td->find('table',0);
                                        if ( isset($check_customize) && $check_customize != '') {

                                            $itm_name_arr = explode("<br>",$td->innertext());
                                            $item_name[$i] = trim($itm_name_arr[0]);

                                            //get customization
                                            if ( sizeof($check_customize->children(0)->children()) > 0 ) {

                                                $custome_tr_size = sizeof($check_customize->children(0)->children());

                                                for ( $c = 0; $c < $custome_tr_size;  $c++ ) {

                                                    $attribute = explode(",",trim($check_customize->children(0)->children($c)->children(2)->plaintext));
                                                    $attr_cnt = 0;
                                                    foreach ( $attribute as $attr ) {
                                                        $item_attributes[$i][$attr_cnt] = $attr;
                                                        $attr_cnt++;
                                                    }
                                                }
                                            }

                                        } else {
                                            $item_name[$i] = trim($td->plaintext);
                                        }

                                        //get item id from item name
                                        $item_obj = Menu::where(DB::raw('LOWER(item)'),strtolower($item_name[$i]))->first();
                                        if ( isset($item_obj) && sizeof($item_obj) > 0 ) {
                                            $item_id[$i] = $item_obj->id;
                                        } else {
                                            $item_id[$i] = 0;
                                        }

                                    }

                                    if ( $j == 1 ) {

                                        $td2 = $td->plaintext;
                                        $price_arr = explode("_",preg_replace("/[^0-9]+/", "_", $td2));

                                        $item_qty[$i] = $price_arr[1];
                                        $item_price[$i] = $price_arr[2];
                                        $item_total[$i] = floatval($price_arr[1]) * floatval($price_arr[2]);

                                    }

                                }
                            }

                        }

                        if ( $cnt == $tables_size ) {

                            foreach ( $table->find('tr') as $tr ) {

                                $pay_mode = trim(strtolower($tr->find('td',0)->plaintext));
                                $total = filter_var(trim($tr->find('td',1)->plaintext), FILTER_SANITIZE_NUMBER_FLOAT);
                            }

                        }

                        if ( $cnt > 7 ) {

                            $check_children = $table->children(0);

                            if ( sizeof($check_children) ) {

                                $no_of_tr = sizeof($table->children(0)->children());

                                //iterate tr of tbody
                                for ( $i = 0; $i < $no_of_tr; $i++ ) {

                                    $td_size = sizeof($table->children(0)->children($i)->children());

                                    //iterate td of tr
                                    for ( $j = 0; $j < $td_size; $j++ ) {

                                        if ( strtolower(trim($table->children(0)->children($i)->children($j)->plaintext)) == "delivery charge" ) {
                                            $delivery_charge = filter_var(trim($table->children(0)->children($i)->children($j+1)->plaintext), FILTER_SANITIZE_NUMBER_INT);
                                        }

                                    }

                                }

                            }

                        }

                        $cnt++;
                    }

                    $lines = explode("\n",$message);

                        if ( isset($lines) && sizeof($lines) > 0 ) {

                            /*foreach ( $lines as $ln ) {

                                $ln = trim($ln);

                                if ( trim($ln) == "" || empty($ln) || strlen($ln) == 0 ) {
                                    continue;
                                }

                                if ( preg_match("~\bCustomer Name\b~",$ln) ) {
                                    $get_customer = explode(":",$ln);
                                    $customer_name = trim($get_customer[1]);
                                    continue;
                                }

                                if ( preg_match("~\bContact No\b~",$ln) ) {
                                    $get_mobile = explode(":",$ln);
                                    $mobile = trim(preg_replace("/\<[^)]+\>/","",$get_mobile[1]));
                                    continue;
                                }

                                if ( preg_match("~\bCustomer Address\b~",$ln) ) {
                                    $get_address = explode(":", $ln);
                                    $address = trim($get_address[1]);
                                    continue;
                                }

                                if ( preg_match("~\bOrder ID\b~",$ln) ) {
                                    $get_orderid = explode(":", $ln);
                                    $ref_id = trim($get_orderid[1]);
                                    $flag = 'date';
                                    continue;
                                }

                                if ( isset($flag) && $flag == 'date' ) {
                                    $flag = "item";
                                    $get_date = explode(":", $ln);
                                    $date = trim($get_date[1]);
                                    continue;
                                }

                                if ( preg_match("~\bDelivery Charge\b~",$ln) ) {
                                    $flag = "delivery_charge";
                                    $get_charge = explode("=E2=82=B9",$ln);
                                    $delivery_charge = trim($get_charge[1]);
                                    continue;
                                }

                                if ( isset($flag) && $flag == 'item' ) {
                                    $item_name[$item_cnt] = trim($ln);

                                    $item_obj = Menu::where(DB::raw('LOWER(item)'),strtolower($ln))->first();
                                    if ( isset($item_obj) && sizeof($item_obj) > 0 ) {
                                        $item_id[$item_cnt] = $item_obj->id;
                                    } else {
                                        $item_id[$item_cnt] = 0;
                                    }
                                    $flag = 'price';
                                    continue;
                                }

                                if ( isset($flag) && $flag == 'price') {

                                    $get_price = explode("=E2=82=B9",$ln);
                                    if ( isset($get_price) && sizeof($get_price) > 0 ) {
                                        preg_match_all('!\d+!', $get_price[0], $qty);
                                        preg_match_all('!\d+!', $get_price[1], $price);
                                        preg_match_all('!\d+!', $get_price[2], $itm_total);
                                    }

                                    $item_qty[$item_cnt] = $qty[0][0];
                                    $item_price[$item_cnt] = $price[0][0];
                                    $item_total[$item_cnt] = $itm_total[0][0];
                                    $item_cnt++;

                                    $flag = 'item';
                                    continue;
                                }

                                if ( preg_match("~\bPaid by\b~",$ln) ) {
                                    $flag = 'paid';
                                    continue;
                                }

                                if ( isset($flag) && $flag == 'paid') {
                                    $flag = "";
                                    $get_payment = explode("=E2=82=B9",$ln);
                                    $pay_mode = trim($get_payment[0]);
                                    $total = trim($get_payment[1]);
                                    continue;
                                }

                            }*/

                            //place order on server
                            $order_arr = array();
                            if ( sizeof($item_total) > 0 && sizeof($item_qty) > 0 && sizeof($item_id) > 0 && sizeof($item_name) > 0 && $pay_mode != '' && $customer_name != '' && $mobile != '' && $address != '' && $ref_id != '' && $date != '' ) {

                                //table name
                                $today_hd_orders = order_details::where('table_start_date','>=',Carbon::today()->startOfDay())
                                    ->where('table_start_date','<=',Carbon::today()->endOfDay())
                                    ->where('order_type','=','home_delivery')->get()->count();

                                //get source id
                                $source = Sources::where('name','Zomato')->first();

                                //get payment option id
                                $pay_mode = 'Online';
                                if ( $pay_mode == 'COD') {
                                    $pay_mode = 'Cash';
                                }
                                $paymen_option = PaymentOption::where('name',$pay_mode)->first();

                                $order_arr['order']['table'] = 'Delivery'.($today_hd_orders + 1);
                                $order_arr['flag'] = 'zomato order';
                                $order_arr['order']['outlet_id'] = $outlet->id;
                                $order_arr['order']['total_price'] = $total;
                                $order_arr['order']['item_id'] = $item_id;
                                $order_arr['order']['item_name'] = $item_name;
                                $order_arr['order']['item_qty'] = $item_qty;
                                $order_arr['order']['item_price'] = $item_price;
                                $order_arr['order']['item_total'] = $item_total;
                                $order_arr['order']['order_type'] = 'home_delivery';
                                $order_arr['order']['mobile'] = $mobile;
                                $order_arr['order']['address'] = $address;
                                $order_arr['order']['order_date'] = $date;
                                $order_arr['order']['delivery_charge'] = $delivery_charge;
                                $order_arr['order']['ref_id'] = $ref_id;
                                $order_arr['order']['customer_name'] = $customer_name;
                                $order_arr['order']['payment_option_id'] = $paymen_option->id;
                                $order_arr['order']['source_id'] = $source->id;
                                $order_arr['order']['customer_name'] = $customer_name;
                                $order_arr['order']['paid_type'] = 'cash';
                                $order_arr['order']['item_attribute'] = $item_attributes;


                                if ( isset($order_arr) && sizeof($order_arr) > 0 ) {

                                    $placeOrder = new newordercontroller();
                                    $result = $placeOrder->placeOrder($order_arr);

                                    if ( $result['message'] == 'success') {
                                        Log::info('Order placed success fully');
                                    } elseif ( $result['message'] == 'firebase error') {
                                        Log::info('Order placed successfully but receiver not online');
                                    } else {
                                        Log::info('There is some error.');
                                    }
                                }


                            }

                        }

                    $k++;

                } else {
                    continue;
                }

            }

            //echo $flag."=".$subject."=".$from."=".$date."=".$message;
        }

        /* close the connection */
        imap_close($inbox);
    }



}

