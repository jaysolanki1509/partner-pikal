<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model {

	use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'guests';

    public function booking()
    {
        return $this->belongsTo('App\Booking');
    }

}
