<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class address extends Model
{
    protected $table = 'address';

    protected $fillable = ['customer_id', 'address', 'locality', 'pincode'];

    public static function insertaddress($adressarray)
    {
        $addressid = DB::table('address')->insertGetId($adressarray);
        return $addressid;
    }
    public static function updateaddress($addressuniq, $adressarray)
    {
        // $affectedRows=DB::table('address')->where('id',$addressuniq)->update($adressarray);
        $affectedRows = DB::table('address')->where('id', $addressuniq)->update($adressarray);
        return $affectedRows;
    }
}
