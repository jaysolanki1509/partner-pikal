<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model {

    protected $table ='staff';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
        'name',
        'per_day',
        'staff_role_id',
        'staff_shift_id',
        'created_by',
        'updated_by',

    );


}
