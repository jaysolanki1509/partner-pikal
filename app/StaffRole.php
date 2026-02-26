<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class StaffRole extends Model {

    protected $table ='staff_roles';
    use SoftDeletingTrait;

    protected $softDelete = true;

    protected $fillable = array
    (
        'name',
        'created_by',
        'updated_by',

    );


}
