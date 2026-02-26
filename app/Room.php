<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model {

    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'rooms';

    public function booking()
    {
        return $this->hasOne('App\Booking','id','booking_id');
    }

}
