<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class OrderPlaceType extends Model {

    protected $table ='order_place_type';

    use SoftDeletingTrait;

    protected $softDelete = true;

    protected $fillable = array
    (
        'name',
        'outlet_id',
        'created_by',
        'updated_by'
    );


    public static function getOrderPlaceType( $outlet_id ) {

        $places = OrderPlaceType::select('id','name')->where('outlet_id',$outlet_id)->get();
        return $places;
    }


}