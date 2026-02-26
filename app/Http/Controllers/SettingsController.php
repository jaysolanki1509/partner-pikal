<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Outlet;
use App\OutletMapper;
use App\OutletSetting;
use App\PaymentOption;
use App\Setting;
use App\SettingsMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['home']]);
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $user_id=Auth::user()->id;

        $owner_id = Auth::id();

        $mappers = OutletMapper::getOutletIdByOwnerId($owner_id);

        $outlets = [''=>'Select Outlet'];
        $mapper_arr=array();

        if ( isset($mappers) && sizeof($mappers) > 0 ) {

            foreach ($mappers as $mapper) {
                $mapper_arr[] = $mapper['outlet_id'];
            }

            $outlets_arr = Outlet::whereIn('id', $mapper_arr)->get();

            foreach ($outlets_arr as $ol) {
                $outlets[$ol->id] = $ol->name;
            }
            $settings = '';
            if (sizeof($outlets_arr) == 1) {
                unset($outlets['']);
                $settings = OutletSetting::join('setting_master','setting_master.id','=','outlet_settings.setting_id')
                    ->where('outlet_id',reset($outlets))
                    ->select('setting_master.setting_name','outlet_settings.setting_value')
                    ->get();
            }

            $settings_master = SettingsMaster::select('setting_name','id')->get();

        }

        //print_r($settings);exit;

        return view('settings.index',array('outlets'=>$outlets, 'settings'=>$settings, 'master'=>$settings_master));

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
        if ( $request->ajax() )
        {
            $user_id = Auth::id();
            $data = array();
            $setting_name = Request::get('setting_name');
            $setting_default = Request::get('setting_default');

            if(isset($setting_name) && $setting_name != '' && isset($setting_default) && $setting_default != '') {

                $setting_master = new SettingsMaster;
                $setting_master->setting_name = $setting_name;
                $setting_master->setting_default = $setting_default;
                $setting_master->created_by = $user_id;
                $setting_master->updated_by = $user_id;
                $result = $setting_master->save();

                if ( $result ) {

                    $data['msg'] = 'Setting name added Successfully.';
                    $data['status'] = 'success';

                } else {

                    $data['status'] = 'error';
                    $data['msg'] = 'There is some error. Please try again later.';
                }


            }else{
                $data['status'] = 'error';
                $data['msg'] = 'Name and Default Value fields must be filled.';
            }
            return $data;
        }

        $settings = SettingsMaster::all();//DB::table('setting_master')->get();

        return view('settings.create',array('settings'=>$settings));
	}


	public function getSettings(){

	    $outlet_id = Request::get('outlet_id');
        $master_settings = SettingsMaster::getMasterSettings();

        $settings = array();

        $master = SettingsMaster::lists('id');
        for($i=0; $i<sizeof($master); $i++){
            $outlet_setting = OutletSetting::select('setting_value')
                ->where('outlet_id',$outlet_id)->where('setting_id',$master[$i])->first();
            if(isset($outlet_setting) && sizeof($outlet_setting)>0 && $outlet_setting != '') {
                $settings[$i]['id'] = $master[$i];
                $settings[$i]['setting_value'] = $outlet_setting->setting_value;
            }else{
                $settings[$i]['id'] = $master[$i];
                $settings[$i]['setting_value'] = SettingsMaster::where('id',$master[$i])->select('setting_default as setting_value')->first()->setting_value;
            }
        }

        if(isset($settings) && sizeof($settings)>0)
            return $settings;
        else
            return $master_settings;

    }

	public function storeMaster()
	{



	}

	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
        $user_id = Auth::id();
        $outlet_id = Request::get('outlet_id');
        $setting = Request::get('settings');
        $settings = isset($setting)?$setting:array();

        $outlet_settings = OutletSetting::getOutletSettings($outlet_id);
        //print_r($outlet_settings);exit;
        $master_settings = SettingsMaster::all();
        foreach ($master_settings as $m_setting){
            $temp = 0;
            foreach ($outlet_settings as $o_setting) {
                if($m_setting->id == $o_setting->setting_id){
                    $temp = 1;
                    $one_setting = OutletSetting::where('setting_id', $m_setting->id)->where('outlet_id',$outlet_id)->first();
                    $one_setting->updated_by = $user_id;
                    if(array_key_exists($m_setting->id, $settings)) {
                        $one_setting->setting_value = 'true';
                        $one_setting->save();
                    }else{
                        $one_setting->setting_value = 'false';
                        $one_setting->save();
                    }
                }
            }
            if($temp == 0){
                $new_settings = new OutletSetting();

                if(array_key_exists($m_setting->id, $settings)) {

                    $new_settings->outlet_id = $outlet_id;
                    $new_settings->setting_id = $m_setting->id;
                    $new_settings->setting_value = 'true';

                }else{
                    $new_settings->outlet_id = $outlet_id;
                    $new_settings->setting_id = $m_setting->id;
                    $new_settings->setting_value = 'false';
                }
                $new_settings->created_by = $user_id;
                $new_settings->updated_by = $user_id;
                $new_settings->save();
            }

        }

        $data['msg'] = 'Settings updated Successfully.';
        $data['status'] = 'success';

        return $data;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		SettingsMaster::where('id',$id)->delete();
        OutletSetting::where('setting_id',$id)->delete();

        $data['msg'] = 'Setting removed Successfully.';
        $data['status'] = 'success';

        return Redirect::to('/settings/add');
	}

    public function paymentOptionsIndex() {

        $payment_options = PaymentOption::get();

        return view('settings.paymentOptionsIndex', array('options'=>$payment_options));

    }

    public function paymentOptionsCreate() {

        return view('settings.paymentOptionForm',array('action'=>'add'));

    }

    public function paymentOptionsEdit($id) {

        $pyment_option = PaymentOption::find($id);
        return view('settings.paymentOptionForm',array('option'=>$pyment_option,'action'=>'edit'));

    }

    public function paymentOptionsStore(Request $request) {


        $p = Validator::make($request->all(), [
            'name' => 'required|unique:payment_options'
        ]);

        if (isset($p) && $p->passes())
        {
            $owner_id = Auth::id();
            $save_continue = Request::get('saveContinue');
            $name = Request::get('name');
            $without_source = Request::get('without_source');

            $opt = new PaymentOption();
            $opt->name = $name;
            if(isset($without_source) && sizeof($without_source)>0){
                $opt->without_source = $without_source;
            }
            $opt->created_by = $owner_id;
            $opt->updated_by = $owner_id;
            $result = $opt->save();

            if ( $result ) {

                if ( isset($save_continue) && $save_continue == 'true' ) {
                    return Redirect::route('paymentoptions.create')->with('success','New Payment Option has been added....');
                } else {
                    return Redirect::route('paymentoptions.index')->with('success','New Payment Option has been added....');
                }

            }

        } else {
            return redirect()->back()->withInput(Request::all())->withErrors($p->errors());
        }
    }

    public function paymentOptionsUpdate($id) {


        $p = Validator::make(Request::all(), [
            'name' => 'required|unique:payment_options,name,'.$id
        ]);

        if (isset($p) && $p->passes())
        {
            $owner_id = Auth::id();
            $name = Request::get('name');
            $without_source = Request::get('without_source');

            $payment_option = PaymentOption::find($id);
            $payment_option->updated_by = $owner_id;
            $payment_option->name = $name;
            if(isset($without_source) && sizeof($without_source)>0){
                $payment_option->without_source = $without_source;
            }else{
                $payment_option->without_source = 0;
            }
            $result = $payment_option->save();

            if ( $result ) {
                return Redirect::route('paymentoptions.index')->with('success', 'Payment options has been updated successfully!');
            }

        } else {
            return redirect()->back()->withInput(Request::all())->withErrors($p->errors());
        }

    }

}
