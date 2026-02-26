<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ItemSettings extends Model
{

    protected $table = 'item_settings';

    protected $fillable = array
    (
        'outlet_id',
        'item_id',
        'is_active',
        'is_sale',
        'created_by',
        'updated_by'
    );

}