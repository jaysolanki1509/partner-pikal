<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Sources extends Model {

    protected $table = 'sources';

    protected $fillable = array
    (
        'source_name',
        'outlet_id',
        'source_percent',
        'created_by',
        'updated_by'
    );

    public static function getSourceArray($outlet_id){

        $sources = Sources::join('outlet_source_mapper as osm','osm.source_id','=','sources.id')->where('outlet_id',$outlet_id)
                            ->select('sources.id','sources.name')->get();


        return $sources;
    }

    public static function deletesourcebyoutletid($outletid) {
        Sources::where('outlet_id','=',$outletid)->delete();
    }

    public static function getSourceNameById($s_id){

        $source = Sources::find($s_id);

        if(isset($source) && sizeof($source)>0){
            return $source->name;
        }else{
            return "";
        }

    }

}
