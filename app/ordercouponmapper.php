<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ordercouponmapper extends Model {

	//
    protected $table = 'ordercouponmappers';
    public static function insertcoupondetails($order_id,$coupondata,$discount,$afterdiscountvalue,$totalcost,$usermobile=""){
        $ordercoupondata=new ordercouponmapper();
        if(isset($order_id) && $order_id!=''){
            $ordercoupondata->order_id=$order_id;
        }
        if(isset($coupondata) && $coupondata!=''){
            $ordercoupondata->coupon_applied=$coupondata;
        }
        if(isset($discount) && $discount!=''){
            $ordercoupondata->discounted_value=$discount;
        }
        if(isset($afterdiscountvalue) && $afterdiscountvalue!=''){
            $ordercoupondata->total_cost_afterdiscount=$afterdiscountvalue;
        }
        if(isset($totalcost) && $totalcost!=''){
            $ordercoupondata->cost_beforediscount=$totalcost;
        }
        if(isset($usermobile) && $usermobile!=''){
            $ordercoupondata->user_mobile_number=$usermobile;
        }
        $ordercoupondata->save();
        return;
    }

}
