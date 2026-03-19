<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ResponseDeviation extends Model {

    protected $table ='response_deviation';


    protected $fillable = array
    (
        'transaction_id',
        'item_id',
        'item_name',
        'request_qty',
        'request_unit_id',
        'satisfied_qty',
        'satisfied_unit_id',
        'for_location_id',
        'from_location_id',
        'request_by',
        'satisfied_by',
        'request_when',
        'satisfied_when',
        'mail_sent'
    );



}
