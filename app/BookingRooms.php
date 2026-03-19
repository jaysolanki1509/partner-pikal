<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingRooms extends Model {

	use SoftDeletes;
    protected $softDelete = true;

    public function booking()
    {
        return $this->belongsTo('App\Booking','booking_id');
    }

}
