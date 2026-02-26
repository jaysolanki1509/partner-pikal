<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomAmenity extends Model {

	use SoftDeletes;

    public static function getAmenitisByOutletId($outlet_id){

        $room_amenities = RoomAmenity::where("outlet_id",$outlet_id)->pluck("name","id");

        return $room_amenities;

    }

}
