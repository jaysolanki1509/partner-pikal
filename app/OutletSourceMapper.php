<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OutletSourceMapper extends Model {

    protected $table = 'outlet_source_mapper';

    protected $fillable = array
    (
        'source_id',
        'outlet_id',
        'created_by',
    );

    public static function deletesourcebyoutletid($outletid) {

        $check_record = OutletSourceMapper::where('outlet_id','=',$outletid)->first();
        if ( isset($check_record) && sizeof($check_record) > 0 ) {
            OutletSourceMapper::where('outlet_id','=',$outletid)->delete();
        }
    }

}
