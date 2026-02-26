<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Consumption extends Model {


    protected $table = 'consumptions';
    public $timestamps = true;

    protected $fillable = array(
        'transaction_id',
        'item_id',
        'consume',
        'order_id',
        'unit_id'
    );

}
