<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class RoomTypes extends Model {

    use SoftDeletingTrait;

	public static function getOnetoFiftyArray(){

        $one_to_fifty = array();
        for ($i=1; $i<=50; $i++){
            $one_to_fifty[$i] = $i;
        }
	    return $one_to_fifty;
    }

    public static function getZerotoFiftyArray(){

        $zero_to_fifty = array();
        for ($i=0; $i<=50; $i++){
            $zero_to_fifty[$i] = $i;
        }
        return $zero_to_fifty;
    }

    public function rooms()
    {
        return $this->hasMany('App\Room',"room_type_id","id");
    }

}
