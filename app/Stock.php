<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model {

    protected $table ='stocks';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
        'item_id',
        'location_id',
        'quantity',
        'unit_id',
        'created_by',
        'updated_by'
    );

}
