<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model {

    use SoftDeletes;
    protected $table = 'campaign';
    protected $softDelete = true;

    protected $fillable = array
    (
        'mobile',
        'verified',
        'otp',
        'outlet_name',
        'owner_name',
        'email',
        'address',
        'path',
        'created_at',
        'updated_at'
    );


}
