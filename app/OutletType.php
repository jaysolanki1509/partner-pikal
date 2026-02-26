<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OutletType extends Model {


    protected $table = 'outlet_types';

    public function outlet()
    {
        return $this->hasOne('App\Outlet', 'id', 'problems');
    }

    public static function Outlettypebyid($id){
        $cui = \App\OutletType::where('id', $id)->first();
        return $cui;
    }
}
