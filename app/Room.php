<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Room extends Model {

    use SoftDeletingTrait;
    protected $softDelete = true;
    protected $table = 'rooms';

    public function booking()
    {
        return $this->hasOne('App\Booking','id','booking_id');
    }

}
