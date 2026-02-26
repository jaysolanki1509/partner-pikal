<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class StockAge extends Model {

    protected $table ='stock_age';
    use SoftDeletingTrait;

    protected $softDelete = true;

    protected $fillable = array
    (
        'item_id',
        'location_id',
        'quantity',
        'unit_id',
        'track_id',
        'expiry_date',
        'created_by',
        'updated_by'
    );

}
