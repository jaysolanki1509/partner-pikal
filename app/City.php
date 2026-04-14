<?php 

namespace App;
use Illuminate\Database\Eloquent\Model;
class City extends Model {
    protected $table = 'cities';
    protected $fillable = array
    (
        'staid',
        'name'
    );
    public static function getllcities(){
        $city=City::all();
        return $city;
    }
    public static function findcity($cityid){
        $getcity=City::find($cityid);
        return $getcity;
    }
    public static function getcitybyid($id){
        $cities=City::where('id','=',$id)->get();
        return $cities;
    }
    public static function getcitybycityname($name){
        $cities=City::where('name','=',$name)->get();
        return $cities;
    }
    public static function getcitybystateid($id){
        $cities=City::where('staid','=',$id)->get();
        return $cities;
    }
}