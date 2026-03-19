<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutletSetting extends Model {

    protected $table = 'outlet_settings';

    use SoftDeletes;
    protected $softDelete = true;

    protected $fillable = array
    (
        'setting_id',
        'outlet_id',
        'setting_value',
        'created_by',
        'updated_by'
    );

    public static function getOutletSettings($outlet_id){

        $settings = OutletSetting::where('outlet_id',$outlet_id)
            ->join('setting_master','setting_master.id','=','outlet_settings.setting_id')
            ->select('outlet_settings.id','outlet_settings.setting_id','outlet_settings.setting_value',
                'setting_master.setting_name','setting_master.setting_default')->get();

        return $settings;
        //$settings = Setting::where('outlet_id',$outlet_id)
    }

    public static function getApiOutletSettings($outlet_id){

        $master_settings = SettingsMaster::getMasterSettings();

        $settings = array();

        $master = SettingsMaster::lists('id');
        for($i=0; $i<sizeof($master); $i++){
            $outlet_setting = OutletSetting::select('setting_value')
                ->where('outlet_id',$outlet_id)->where('setting_id',$master[$i])->first();
            if(isset($outlet_setting) && sizeof($outlet_setting)>0) {
                $set_master = SettingsMaster::where('id',$master[$i])->select('setting_name')->first();
                $settings[$i]['id'] = $master[$i];
                $settings[$i]['name'] = $set_master->setting_name;
                $settings[$i]['setting_value'] = $outlet_setting->setting_value;
            }else{
                $set_master = SettingsMaster::where('id',$master[$i])->select('setting_name','setting_default as setting_value')->first();
                $settings[$i]['id'] = $master[$i];
                $settings[$i]['name'] = $set_master->setting_name;
                $settings[$i]['setting_value'] = $set_master->setting_value;
            }
        }

        if(isset($settings) && sizeof($settings)>0)
            return $settings;
        else
            return $master_settings;

    }

    public static function checkAppSetting($outlet_id, $setting_name){

        $outlet_settings = OutletSetting::where('outlet_id',$outlet_id)
            ->where('setting_value',"=","true")
            ->join('setting_master','setting_master.id','=','outlet_settings.setting_id')
            ->select('outlet_settings.id','outlet_settings.setting_id','outlet_settings.setting_value',
                'setting_master.setting_name','setting_master.setting_default')->get();

        if(isset($outlet_settings) && sizeof($outlet_settings)>0) {
            foreach ($outlet_settings as $setting) {
                if ($setting_name == $setting->setting_name) {
                    $val = 1;
                    break;
                } else {
                    $val = 0;
                }
            }

            return $val;
        }else{
            $val = 0;
            return $val;
        }

    }

}
