<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{

    protected $table = 'bank_master';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
        'owner_id',
        'acc_no',
        'bank_name',
        'acc_type',
        'bank_ifsc',
        'bank_address'

    );
}