<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model {

    protected $table ='attendance';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
        'staff_id',
        'in',
        'out',
        'created_by',
        'updated_by'

    );


}
