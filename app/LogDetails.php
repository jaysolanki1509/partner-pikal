<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;



class LogDetails extends Model {

    protected $table ='log_details';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
        'device_id',
        'outlet_id',
        'owner_id',
        'device_os',
        'app_version',
        'manufacturer',
        'model',
        'path',

    );

    public static function insertLogDetails($logarray){

        //print_r($logarray);exit;
        $log_details = new LogDetails();
        $log_details->owner_id = $logarray['owner_id'];
        $log_details->outlet_id = $logarray['outlet_id'];
        $log_details->device_os = $logarray['os_version'];
        $log_details->app_version = $logarray['app_version'];
        $log_details->path = $logarray['path'];
        $log_details->model = $logarray['model'];
        $log_details->manufacturer = $logarray['manufacturer'];
        $log_details->save();

        if($log_details){
            return "Log successfully received";
        }else{
            return "Log not received";
        }
    }



}