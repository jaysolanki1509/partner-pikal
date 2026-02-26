<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class SettingsMaster extends Model
{

    protected $table = 'setting_master';

    use SoftDeletingTrait;
    protected $softDelete = true;

    protected $fillable = array
    (
        'setting_name',
        'setting_default',
        'created_by',
        'updated_by'
    );

    public static function getMasterSettings(){

        return SettingsMaster::select('id','setting_name','setting_default as setting_value')->get();

    }

}