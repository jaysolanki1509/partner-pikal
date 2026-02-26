<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class BookingRooms extends Model {

	use SoftDeletingTrait;
    protected $softDelete = true;

    public function booking()
    {
        return $this->belongsTo('App\Booking','booking_id');
    }

}
