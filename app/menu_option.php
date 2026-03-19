<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class menu_option extends Model {



    protected $table = 'menu_options';


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

    public function orderitem()
    {
        return $this->hasMany('App\OrderItem','item_id','id');
    }

    public static function getmenuoptionbyid($id){
        $menud=menu_option::where('id', $id)->get();
        return $menud;

    }

    public static function deletemenuoptionofmenu($id){
        DB::table('menus')->where('outlet_id',$id)->delete();
    }

//    public static function getmenuoptionbymenuid($menuid){
//        $menu=menu_option::where('menu_id',$menuid)->get();
//        return $menu;
//    }
}
