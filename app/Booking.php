<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model {


	use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'bookings';

    public function guests()
    {
        return $this->hasOne('App\Guest','id','guest_id');
    }

    public function booking_rooms()
    {
        return $this->hasMany('App\BookingRooms','booking_id','id');
    }

}
