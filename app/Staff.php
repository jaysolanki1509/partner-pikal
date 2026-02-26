<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Staff extends Model {

    protected $table ='staff';
    use SoftDeletingTrait;

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
