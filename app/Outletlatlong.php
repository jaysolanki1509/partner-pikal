<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Outletlatlong extends Model {


    protected $table ='outlet_latlong';

    public function outlet()
    {
        return $this->belongsTo('App\Outlet');
    }
    public static function getouletlatlongbyoutletid($id){
        $rest_latlong=Outletlatlong::where('outlet_id','=',$id)->get();
        return $rest_latlong;
    }

}