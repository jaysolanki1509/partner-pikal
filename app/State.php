<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model {

    //protected $connection = 'my';
    protected $table = 'states';
    protected $fillable = array

    (
        'staid',
        'name'
    );
    public static function getallstates(){
        $getallstates=State::all();
        return $getallstates;
    }
    public static function findstates($stateid){
        $getstates=State::find($stateid);
        return $getstates;
    }

    public static function getstatebyid($id){
        $states=State::where('id','=',$id)->get();
        return $states;
    }

    public static function getstatebyname($name){
        $states=State::where('name','=',$name)->get();
        return $states;
    }
}
