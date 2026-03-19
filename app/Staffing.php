<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staffing extends Model {

    protected $table ='staffing';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
        'outlet_id',
        'staff_role_id',
        'staff_shift_id',
        'qty',
        'created_by',
        'updated_by',

    );


}
