<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class CouponCodes extends Model {

    protected $table = 'coupon_codes';
    use SoftDeletingTrait;

    protected $softDelete = true;

    public static function getMyCouponCode(){
        $mycoupons=DB::table('coupon_codes')->where('created_by',Auth::id())->get();
        return $mycoupons;
    }

}
