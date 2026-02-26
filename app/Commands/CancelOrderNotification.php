<?php namespace App\Commands;
use App\Commands\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Log;
use App\order_details;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\DB;
use App\Outlet;

class CancelOrderNotification extends Command {
    use InteractsWithQueue, SerializesModels;
    public function getcancelorder($job,$fields){
        Log::info($fields);
        order_details::sendcancelnotification($fields['cancellation']['device_id'],$fields['cancellation']['reason'],$fields['cancellation']['order_id'],$fields['cancellation']['created_at']);
        $job->delete();
    }

    public function toConsumerRemoveKotNotification( $job, $fields ) {

        $order = order_details::where('order_id',$fields['order_id'])->first();

        if ( isset($order) && sizeof($order) > 0 ) {
            order_details::toConsumerRemoveKotNotification($fields['order_id'],$fields['item_id'],$fields['item_name'],$fields['item_unique_id'],$fields['reason'],$order->device_id);
        }

        $job->delete();

    }

    public function toConsumerCancelOrderNotification( $job, $fields ) {

        $order = order_details::where('order_id',$fields['order_id'])->first();

        if ( isset($order) && sizeof($order) > 0 ) {
            order_details::toConsumerCancelOrderNotification($fields['order_id'],$fields['order_items'],$fields['reason'],$order->device_id);
        }

        $job->delete();

    }

}