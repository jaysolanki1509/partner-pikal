<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OutletTypeMapper extends Model
{


    protected $table = 'outlet_types_mapper';

    public function outlet()
    {
        return $this->belongsTo('App\outlet', 'outlet_id', 'id');
    }

    public static function getoutlettypemapper($outlet_id){
        $Outlettype=OutletTypeMapper::where('outlet_id',$outlet_id)->get();
       // print_r($outlet_id);exit;
        return $Outlettype;

    }

    public static function deleteoutlettype($id){
        $old_Outlet_type=OutletTypeMapper::where('outlet_id','=',$id)->delete();
    }
}
