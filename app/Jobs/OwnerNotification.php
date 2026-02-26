<?php namespace App\Jobs;
use App\Jobs\Command;
use App\OutletMapper;
use App\Owner;
use App\ResponseDeviation;
use Exception;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Log;
use App\order_details;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\DB;
use App\Outlet;
use Illuminate\Support\Facades\Mail;

class OwnerNotification extends Command {
    use InteractsWithQueue, SerializesModels;

    public function getownernotification($job,$fields){

        /*$ownerdetails=Outlet::find($fields['outlet_id']);
        $ownerdetails=$ownerdetails->owner_id;
        $device_id=DB::table('owners')->where('id',$ownerdetails)->lists('device_id');*/
        $ownerdetails = OutletMapper::where('outlet_id',$fields['outlet_id'])->lists('owner_id');
        if ( isset($ownerdetails) && sizeof($ownerdetails) > 0 ) {
            $device_id = Owner::whereIn('id',$ownerdetails)->lists('device_id');

            //device id arr
            $device_arr = array();
            if ( isset($device_id) && sizeof($device_id) > 0 ) {
                foreach( $device_id as $dev ) {
                    if ( isset($dev) && $dev != '' ) {
                        $dev_check = explode(",",$dev);
                        foreach( $dev_check as $d ) {
                            array_push($device_arr,$d);
                        }
                    }
                }
            }
            $check = order_details::sendownernotification($device_arr,$fields['order_id'],$fields['outlet_id'],$fields['table_no']);
        }

        //Log::info($getallorder);
        //Log::info($device_id[0]);

        $job->delete();
    }

    #TODO: Attend me notification to outlet owner deviceid
    public function attendNotification($job,$fields) {

        $ownerdetails = OutletMapper::where('outlet_id',$fields['outlet_id'])->lists('owner_id');

        if ( isset($ownerdetails) && sizeof($ownerdetails) > 0 ) {
            $device_id = Owner::whereIn('id',$ownerdetails)->lists('device_id');


            //device id arr
            $device_arr = array();
            if ( isset($device_id) && sizeof($device_id) > 0 ) {
                foreach( $device_id as $dev ) {
                    if ( isset($dev) && $dev != '' ) {
                        $dev_check = explode(",",$dev);
                        foreach( $dev_check as $d ) {
                            array_push($device_arr,$d);
                        }
                    }
                }
            }
            $check = order_details::sendAttendMeNotification($device_arr,$fields['table_no'],$fields['outlet_id']);
        }

        $job->delete();
    }

    public function payBillNotification( $job,$fields ) {

        $ownerdetails = OutletMapper::where('outlet_id',$fields['outlet_id'])->lists('owner_id');

        if ( isset($ownerdetails) && sizeof($ownerdetails) > 0 ) {
            $device_id = Owner::whereIn('id',$ownerdetails)->lists('device_id');

            //device id arr
            $device_arr = array();
            if ( isset($device_id) && sizeof($device_id) > 0 ) {
                foreach( $device_id as $dev ) {
                    if ( isset($dev) && $dev != '' ) {
                        $dev_check = explode(",",$dev);
                        foreach( $dev_check as $d ) {
                            array_push($device_arr,$d);
                        }
                    }
                }
            }
            $check = order_details::sendPaidBillNotification($device_arr,$fields['table_no'],$fields['outlet_id']);
        }

        $job->delete();

    }

    public function sendCampaignDetail( $job, $fields ) {

        $allemail = array('raj@savitriya.com');

        $content = "Hello, \n\n\n\n";
        $content .= 'Outlet Name : '.$fields['outlet_name']."\n";
        $content .= 'Owner Name : '.$fields['owner_name']."\n";
        $content .= 'Mobile : '.$fields['mobile']."\n";
        $content .= 'email : '.$fields['email']."\n";
        $content .= 'Address : '.$fields['address']."\n";

        foreach ($allemail as $email) {
            try {
                Mail::raw($content, function($message) use ($email)
                {
                    $message->from('we@pikal.io', 'Pikal');
                    $message->to($email);
                    $message->subject('New user inquiry from Partner App campaign');
                });

            } catch (\Exception $e) {
                //$message = 'error';
                //Log::info('Data : ' . $e->getMessage());
            }
        }
        $job->delete();
    }

    public function toConsumerAcceptKotNotification( $job, $fields ) {

        $order_details = order_details::where('order_id',$fields['order_id'])->first();

        if ( isset($order_details) && sizeof($order_details) > 0 ) {
            order_details::toConsumerAcceptKotNotification($fields['order_id'],$fields['kot_items'],$order_details->device_id );
        }

        $job->delete();

    }

    public function upiTransactionStatusChange( $job, $fields ) {

        if ( isset($fields) && sizeof($fields) > 0 ) {
            if ( isset($fields['billNumber'])) {

                //Log::info($fields['billNumber']);
                $device_arr = array();

                $order = order_details::where('order_unique_id',$fields['billNumber'])->first();

                if ( isset($order) && sizeof($order) > 0 ) {
                    //Log::info('get outlet_id '. $order->outlet_id);
                    $ownerdetails = OutletMapper::where('outlet_id',$order->outlet_id)->lists('owner_id');

                    if ( isset($ownerdetails) && sizeof($ownerdetails) > 0 ) {
                        $device_id = Owner::whereIn('id', $ownerdetails)->lists('device_id');

                        //device id arr
                        if (isset($device_id) && sizeof($device_id) > 0) {

                            foreach ($device_id as $dev) {
                                if (isset($dev) && $dev != '') {
                                    $dev_check = explode(",", $dev);
                                    foreach ($dev_check as $d) {
                                        array_push($device_arr, $d);
                                    }
                                }
                            }
                            order_details::UpiTransactionStatusChangeNotification($device_arr,$order->table_no,$fields['txnId'],$fields['billNumber'],$fields['oldStatus'],$fields['oldStatusString'],$fields['status'],$fields['statusString'],$fields['amount'],$fields['playerVa'],$fields['note'],$fields['initDate']);
                        }
                    }
                }
            }

        }

        $job->delete();

    }

    #TODO: send response deviation report
    public function sendresponsedeviation($job,$fields) {

        $tran_id = $fields['transaction_id'];
        $emails = array();

        $outlets = OutletMapper::getOutletMapperByOwnerId($fields['user_id']);
        if ( isset($outlets) && sizeof($outlets) > 0 ) {
            foreach( $outlets as $ot ) {
                $send_report = Outlet::find($ot->outlet_id);
                if ( isset($send_report) && sizeof($send_report) > 0 ) {
                    $report_to = explode(',',$send_report->report_emails);
                    if( isset($report_to) && sizeof($report_to) > 0 ) {
                        foreach( $report_to as $to ) {
                            if ( !in_array($to,$emails,TRUE)) {
                                $emails[] = $to;
                            }
                        }
                    }

                }
            }
        }

        if ( sizeof($emails) > 0 ) {

            $response_items = ResponseDeviation::join('unit as req_unit','req_unit.id','=','response_deviation.request_unit_id')
                                                ->join('unit as res_unit','res_unit.id','=','response_deviation.satisfied_unit_id')
                                                ->join('locations as l','l.id','=','response_deviation.from_location_id')
                                                ->join('locations as l2','l2.id','=','response_deviation.for_location_id')
                                                ->join('owners as o','o.id','=','response_deviation.satisfied_by')
                                                ->select('response_deviation.*','o.user_name as owner','l.name as from_location','l2.name as for_location','req_unit.name as req_unit','res_unit.name as res_unit')
                                                ->where('response_deviation.transaction_id',$tran_id)
                                                ->where('response_deviation.mail_sent',0)
                                                ->get();

            if ( isset($response_items) && sizeof($response_items) > 0 ) {

                foreach ($emails as $email) {
                    try {
                        Log::info('Data : ' . $email);
                        Mail::send('emails.stockResponseDeviation', ['response_items' => $response_items], function($message) use ($email)
                        {
                            $message->from('we@pikal.io', 'Pikal');
                            $message->to($email, 'Pikal');
                            $message->subject('Stock Response Deviation Report');
                        });
                    } catch (\Exception $e) {
                        //$message = 'error';
                        Log::info('Data : ' . $e->getMessage());
                    }
                }

            }

        }

        $job->delete();
    }

}
