<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TableLevel extends Model {

    protected $table ='table_levels';
    use SoftDeletingTrait;

    protected $softDelete = true;

    protected $fillable = array
    (
        'name',
        'outlet_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at'
    );

    public static function getTableLevels($outlet_id) {

        $table_levels = TableLevel::where('outlet_id',$outlet_id)->get();
        return $table_levels;

    }

}
