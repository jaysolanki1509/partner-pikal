<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Kot extends Model {

    protected $table ='kot';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
    );

    static function kotOrderDiff($outlet_id, $from_date, $to_date){

        $kots = Kot::groupBy('kot.order_unique_id')
            ->where('kot.outlet_id',$outlet_id)
            ->whereNull('kot.deleted_at')
            ->where('kot.kot_time','>=', $from_date)
            ->where('kot.kot_time','<=', $to_date)
            ->pluck("kot.order_unique_id");


        $notmatch = array();
        if(isset($kots) && sizeof($kots)>0) {
            foreach ($kots as $index => $order_unique_id) {
                $orders = order_details::where('outlet_id',$outlet_id)
                    ->where('order_unique_id', $order_unique_id)
                    //->where('orders.cancelorder', '!=', 1)
                    ->get();

                if(sizeof($orders)==0){        // If Kot printed but order unique id not found

                    $kot_list = Kot::where("order_unique_id",$order_unique_id)
                        ->whereNull('kot.deleted_at')->get();
                    if(isset($kot_list) && sizeof($kot_list)>0) {

                        foreach ($kot_list as $single_kot) {

                            $kot_info['price'] = $single_kot->price;
                            $kot_info['item_name'] = urldecode($single_kot->item_name);
                            $kot_info['qty'] = $single_kot->quantity;
                            $kot_info['date'] = date_format($single_kot->created_at, 'Y-m-d H:i:s');
                            $kot_info['reason'] = "No Order found for this KOT.";
                            array_push($notmatch, $kot_info);

                        }

                    }
                }else{                      // If kot printed but order not processed and no invoice id found

                    foreach ($orders as $order){

                        if($order->cancelorder != 1) {

                            if (!isset($order->invoice_no) || $order->invoice_no == "") {

                                $kot_list = Kot::where("order_unique_id", $order->order_unique_id)
                                    ->whereNull('kot.deleted_at')->get();

                                if (isset($kot_list) && sizeof($kot_list) > 0) {

                                    foreach ($kot_list as $single_kot) {
                                        $kot_info['price'] = $single_kot->price;
                                        $kot_info['item_name'] = urldecode($single_kot->item_name);
                                        $kot_info['qty'] = $single_kot->quantity;
                                        $kot_info['date'] = date_format($single_kot->created_at, 'Y-m-d H:i:s');
                                        $kot_info['reason'] = "Order not processed.";
                                        array_push($notmatch, $kot_info);
                                    }

                                }

                            }

                        }

                    }

                }
            }
        }

        return $notmatch;

    }


}
