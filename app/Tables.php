<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tables extends Model {

    protected $table ='tables';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
    );

    protected $rules = [
        'table_no'   => 'required',
        'no_of_person'    => 'required|numeric',
        'occupied_by' => 'required'
    ];


    public static function  getOutletTables($outlet_id) {

        $tables = Tables::where('outlet_id',$outlet_id)->whereNull('deleted_at')->get();

        return $tables;
    }

    public static function  getOutletTablesList($outlet_id) {

        $tables = Tables::where('outlet_id',$outlet_id)->lists('table_no','id');

        return $tables;
    }

}

