<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Bank extends Model
{

    protected $table = 'bank_master';
    use SoftDeletingTrait;

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