<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PrinterItemBind extends Model {


    protected $table = 'printer_item_bind';
    public $timestamps = true;

    protected $fillable = array(
        'outlet_id',
        'item_id',
        'created_by',
        'updated_by',
        'category_id',
        'printer_id',
        'mac_address'
    );

}
