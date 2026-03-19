<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Outletimage extends Model {


    protected $table = 'outlet_images';


    public static function getoutletimagesbyoutletid($id){
        $restimages=Outletimage::where('outlet_id',$id)->get();
        return $restimages;
    }
}
