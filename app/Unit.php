<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Unit extends Model {


    protected $table = 'unit';

    protected $fillable = array(
        'name',
    );
    public static function getunit(){
        $units = Unit::get();
        return $units;
    }

    public static function getUnitbyId($unit_id){
        $unit = Unit::where('id',$unit_id)->first();
        return $unit;
    }

    public static function getUnitIdbyName($unit_name){
        $unit = Unit::where('name',$unit_name)->first();
        return $unit;
    }
}
