<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OutletCuisineType extends Model {


    protected $table = 'outlet_cuisine_types';

    public function outlet()
    {
        return $this->belongsTo('App\Outlet', 'outlet_id', 'id');
    }

    public static function getoutletcuisinetype($outletid){
        $cuisinetype=OutletCuisineType::where('outlet_id',$outletid)->get();
        return $cuisinetype;
    }

    public static function deleteoutletcuisinetype($outletid){
        OutletCuisineType::where('outlet_id','=',$outletid)->delete();
    }
}

