<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class MenuTitle extends Model {
    protected $table = 'menu_titles';
    protected $fillable = array(
        'title',
        'created_by',
        'active',
        'outlet_id'
    );
    public static function getmenutitlebyrestaurantid($id){
        $menu=MenuTitle::where('outlet_id', $id)->get();
        return $menu;
    }
    public static function getCategoriesDropdown($id) {
        $cat = MenuTitle::where('created_by',$id)->get();
        $data[''] = 'Select Category';
        foreach($cat as $ct) {
            $data[$ct->id]=$ct->title;
        }
        return $data;
    }
    public static function getOffSaleCategoriesDropdown($id) {
        $cat = MenuTitle::where('created_by',$id)->where('is_sale','=',0)->get();
        $data[''] = 'Select Category';
        foreach($cat as $ct) {
            $data[$ct->id]=$ct->title;
        }
        return $data;
    }
    public static function getMenuTitleByUserId($id){
        $menu=MenuTitle::where('created_by', $id)->get();
        return $menu;
    }
    public static function getMenuTitleByCreatedBy($id){
        $menu=MenuTitle::where('created_by', $id)->get();
        return $menu;
    }
    //    for get menutitle of resturant id and active
    public static function getmenutitlebyrestaurantidandactive($id,$active){
        $menu=MenuTitle::where('outlet_id', $id)->where('active',$active)->get();
        return $menu;
    }
    public static function deletemenutitleofoutlet($id){
        DB::table('menu_titles')->where('outlet_id',$id)->delete();
    }
    public static function getmenutitleofoutletandname($id,$title){
        $menutitlestored=DB::table('menu_titles')->where('outlet_id',$id)->where('title',$title)->first();
        return $menutitlestored;
    }
    public static function getMenuTitleOfUserAndName($id,$title){
        $menutitlestored=DB::table('menu_titles')->where('created_by',$id)->where('title',$title)->first();
        return $menutitlestored;
    }
//    for get menutitle of outlet id and title id
    public static function getmenutitleofoutletandid($id,$menutitle_id){
        $check_menutitlestored = MenuTitle::where('outlet_id',$id)->where('id',$menutitle_id)->first();
        return $check_menutitlestored;
    }
    public static function getmenutitlebyid($id){
        $menu_title = MenuTitle::where('id',$id)->first();
        return $menu_title;
    }
}