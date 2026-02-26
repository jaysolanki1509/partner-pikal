<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockHistory extends Model {

    protected $table ='stock_history';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
        'from_location',
        'to_location',
        'transaction_id',
        'quantity',
        'unit_id',
        'item_id',
        'type',
        'created_by',
        'updated_by'
    );

}
