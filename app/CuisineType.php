<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CuisineType extends Model {


    protected $table ='cuisine_types';

    protected $fillable = array(
        'item',
        'price'
    );
    public function user()
    {
        return $this->belongsTo('App\Owner','user_id','id');
    }

    public function Outletcuisinetype()
    {
        return $this->hasMany('App\OutletCuisineType','outlet_id');
    }
    public function OutletOutlettype()
    {
        return $this->hasMany('App\OutletTypeMapper','outlet_id');
    }


    public static function cuisinetypebyid($id){
        $rest = \App\CuisineType::where('id', $id)->first();
        return $rest;
    }


    public static function getcuisinetypebyname($name){
        $cuisine_typeid = CuisineType::where('type', $name)->first();
        return $cuisine_typeid;
    }

}
