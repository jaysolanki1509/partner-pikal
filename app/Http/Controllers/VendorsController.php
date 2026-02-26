<?php namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\State;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class VendorsController extends Controller {

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
        $owner_id = Auth::id();
        $vendors = Vendor::where('created_by',$owner_id)->get();

        return view('vendors.index', array('vendors' => $vendors));

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $countries=Country::all();
        $states=State::all();
        $cities=City::all();
		return view('vendors.create',array('countries' => $countries,'states' => $states,'cities' => $cities,'vendor'=>'','action'=>'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{

        $v = Validator::make($request->all(), [
            'name' => 'required',
            //'contact_number' => 'required|numeric|digits_between:10,12',
            'pincode' => 'required|numeric|digits_between:6,7'
        ]);


        if (isset($v) && $v->passes())
        {
            $owner_id = Auth::user()->id;
            $save_continue = Request::get('saveContinue');
            $city = Request::get('cities');
            $country = Request::get('countries');
            $states = Request::get('states');
            $vendor_gst = trim(Request::get('vendor_gst'));
            $address = Request::get('address');

            $vendor = new Vendor();
            $vendor->name = Request::get('name');
            $vendor->address = isset($address)?$address:"";
            $vendor->type = Request::get('type');
            $vendor->contact_person = Request::get('contact_person');
            $vendor->contact_number = Request::get('contact_number');
            $vendor->vendor_gst = strlen($vendor_gst)>0?$vendor_gst:NULL;
            if(isset($city) && sizeof($city)>0) {
                $vendor->city_id = Request::get('cities');
            }
            if(isset($country) && sizeof($country)>0) {
                $vendor->country_id = Request::get('countries');
            }
            if(isset($states) && sizeof($states)>0) {
                $vendor->state_id = Request::get('states');
            }
            $vendor->pincode = Request::get('pincode');
            $vendor->created_by = $owner_id;
            $vendor->updated_by = $owner_id;
            $result = $vendor->save();

            if ( $result ) {

                if ( isset($save_continue) && $save_continue == 'true' ) {
                    return Redirect::route('vendor.create')->with('success','New Vendor has been added');
                } else {
                    return Redirect::route('vendor.index')->with('success','New Vendor has been added');
                }

            }

        } else {

            return redirect()->back()->withInput(Request::all())->withErrors($v->errors());
        }

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
        if ( isset($id)) {

            $owner_id = Auth::user()->id;
            $vendor = Vendor::find($id);
            $countries=Country::all();
            $states=State::all();
            $cities=City::all();

            if ( isset($vendor) && sizeof($vendor) > 0 ) {

                return view('vendors.edit',array('countries' => $countries,'states' => $states,'cities' => $cities,'vendor'=>$vendor,'action'=>'edit'));

            }
        }
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

        $v = Validator::make(Request::all(), [
            'name' => 'required',
            //'contact_number' => 'required|numeric|digits_between:10,11',
        ]);

        if (isset($v) && $v->passes())
        {
            $owner_id = Auth::user()->id;
            $vendor_gst = trim(Request::get('vendor_gst'));

            $vendor = Vendor::find($id);
            $vendor->name = Request::get('name');
            $vendor->address = Request::get('address');
            $vendor->type = Request::get('type');
            $vendor->contact_person = Request::get('contact_person');
            $vendor->contact_number = Request::get('contact_number');
            $vendor->city_id = Request::get('cities');
            $vendor->vendor_gst = strlen($vendor_gst)>0?$vendor_gst:NULL;
            $vendor->country_id = Request::get('countries');
            $vendor->state_id = Request::get('states');
            $vendor->pincode = Request::get('pincode');
            $vendor->updated_by = $owner_id;
            $result = $vendor->save();

            if ( $result ) {
                return Redirect::route('vendor.index')->with('success', 'Vendor information updated successfully!');
            }

        } else {
            return redirect()->back()->withInput(Request::all())->withErrors($v->errors());
        }

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        Vendor::where('id',$id)->delete();
        Session::flash('success', 'Vendor has been deleted successfully!');
        return Redirect::to('vendor');
	}

}
