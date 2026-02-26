<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Purchase extends Model {

    protected $table ='purchase';
    use SoftDeletingTrait;

    protected $softDelete = true;
    public $timestamps = false;

    protected $fillable = array
    (
        'invoice_id',
        'item_id',
        'quantity',
        'rate',
    );


}
