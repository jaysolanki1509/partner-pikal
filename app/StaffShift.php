<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffShift extends Model {

    protected $table ='staff_shifts';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
        'name',
        'slots',
        'created_by',
        'updated_by',

    );


}
