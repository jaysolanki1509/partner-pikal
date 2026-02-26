<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Guest extends Model {

	use SoftDeletingTrait;
    protected $softDelete = true;
    protected $table = 'guests';

    public function booking()
    {
        return $this->belongsTo('App\Booking');
    }

}
