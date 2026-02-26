<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class RoomAmenity extends Model {

	use SoftDeletingTrait;

    public static function getAmenitisByOutletId($outlet_id){

        $room_amenities = RoomAmenity::where("outlet_id",$outlet_id)->lists("name","id");

        return $room_amenities;

    }

}
