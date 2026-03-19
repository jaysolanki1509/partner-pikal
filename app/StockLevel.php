<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class StockLevel extends Model {


    protected $table = 'stock_level';
    public $timestamps = true;

    protected $fillable = array(
        'location_id',
        'item_id',
        'created_by',
        'updated_by',
        'category_id',
        'opening_qty',
        'reserved_qty',
        'order_qty',
        'unit_id'
    );

}
