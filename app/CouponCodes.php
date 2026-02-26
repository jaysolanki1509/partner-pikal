<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponCodes extends Model {

    protected $table = 'coupon_codes';
    use SoftDeletes;

    protected $softDelete = true;

    public static function getMyCouponCode(){
        $mycoupons=DB::table('coupon_codes')->where('created_by',Auth::id())->get();
        return $mycoupons;
    }

}
