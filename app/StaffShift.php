<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class StaffShift extends Model {

    protected $table ='staff_shifts';
    use SoftDeletingTrait;

    protected $softDelete = true;

    protected $fillable = array
    (
        'name',
        'slots',
        'created_by',
        'updated_by',

    );


}
