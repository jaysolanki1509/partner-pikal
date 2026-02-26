<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Outlet_Menu_Bind extends Model
{


    protected $table ='outlet_menu_bind';

    protected $fillable = array

    (
        'outlet_id',
        'menu_id',
        'item_id'
    );

    public static function getmenubymenutitleid($id){
        $menud=Outlet_Menu_Bind::where('menu_id', $id)->get();
        return $menud;
    }
    public static function addMenuItem($outlet_id,$menu_id,$item_id){
        $result = Outlet_Menu_Bind::insert(
            ['outlet_id' => $outlet_id, 'menu_id' => $menu_id, 'item_id'=> $item_id]
        );
        return $result;
    }
    public static function getItemsByOutid($outlet_id){
        $result = Outlet_Menu_Bind::where('outlet_id',$outlet_id)->get();
        return $result;
    }
}
