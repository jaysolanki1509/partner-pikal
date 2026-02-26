<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Stock extends Model {

    protected $table ='stocks';
    use SoftDeletingTrait;

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
