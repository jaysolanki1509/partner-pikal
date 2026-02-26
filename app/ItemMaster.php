<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ItemMaster extends Model {


    protected $table = 'item_master';

    protected $fillable = array(
        'catagory_id',
        'item_name',
        'display_order',
        'current_stock',
        'unit',
    );

    public static function getItemsByCategoryId($cate_id)
    {
        $items = ItemMaster::where('catagory_id',$cate_id)->get();
        return $items;
    }

    public static function getItemsByItemId($item_id)
    {
        $item = ItemMaster::where('id',$item_id)->first();
        return $item;
    }
}