<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use DateTime;

class Booking extends Model {


    use SoftDeletingTrait;
    protected $softDelete = true;
    protected $table = 'bookings';

    /**
     * Laravel 5.1 upgrade: Controls the storage format for Eloquent date fields.
     * Previously done via getDateFormat() override; now use $dateFormat property.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Laravel 5.1 upgrade: Prepare a date for array / JSON serialization.
     * In 5.1, the $dateFormat is also applied during serialization.
     * Override this method to keep JSON output format unchanged for API consumers.
     *
     * @param  \DateTime  $date
     * @return string
     */
    protected function serializeDate(DateTime $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function guests()
    {
        return $this->hasOne('App\Guest','id','guest_id');
    }

    public function booking_rooms()
    {
        return $this->hasMany('App\BookingRooms','booking_id','id');
    }

}
