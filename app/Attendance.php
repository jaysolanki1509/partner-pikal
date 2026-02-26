<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use DateTime;

class Attendance extends Model {

    protected $table ='attendance';
    use SoftDeletingTrait;

    protected $softDelete = true;

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
    protected $dates = ['in', 'out', 'deleted_at'];

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

    protected $fillable = array
    (
        'staff_id',
        'in',
        'out',
        'created_by',
        'updated_by'

    );


}
