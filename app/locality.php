<?php 

namespace App;
use Illuminate\Database\Eloquent\Model;
class locality extends Model {
    protected $table = 'locality';
    protected $fillable = array(
        'locality_id',
        'locality'
    );

    public  static  function findlocality($localityid){
        $getlocality=locality::find($localityid);
        return $getlocality;
    }

    public static function getlocalitybyid($id){
        $loc=locality::where('locality_id','=',$id)->first();
        return $loc;
    }

    public static function getlocalitybycityid($id){
        $loc=locality::where('city_id','=',$id)->get();
        return $loc;
    }
    public static function getlocalitybylocalityname($name){
        $loc=locality::where('locality','=',$name)->get();

        return $loc;
    }
    public static function getalllocality(){
        $locality=locality::all();
        return $locality;
    }
}