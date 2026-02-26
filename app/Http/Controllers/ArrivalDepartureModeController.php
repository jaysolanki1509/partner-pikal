<?php namespace App\Http\Controllers;

use App\ArrivalDepartureMode;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ArrivalDepartureModeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $outlet_id = Session::get('outlet_session');
        if(isset($outlet_id) && $outlet_id != "") {
            $arrival_departure_mode = ArrivalDepartureMode::where('outlet_id', $outlet_id)->get();
            return view('arrivalDepartureMode.index', array('arrival_departure_mode' => $arrival_departure_mode));
        }else{
            return view('arrivalDepartureMode.index', array('arrival_departure_mode' => ""));
        }
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('arrivalDepartureMode.create',array("action"=>"add"));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $name = Request::get('name');
        $description = Request::get('description');
        $save_continue = Request::get('saveContinue');
        $owner_id = Auth::id();
        $outlet_id = Session::get('outlet_session');

        $amenity = new ArrivalDepartureMode();
        $amenity->name = $name;
        $amenity->outlet_id = $outlet_id;
        $amenity->description = $description;
        $amenity->created_by = $owner_id;
        $amenity->updated_by = $owner_id;
        $result = $amenity->save();

        if($result){
            if ( isset($save_continue) && $save_continue == 'true' ) {
                return Redirect::route('arrival-departure-mode.create')->with('success','The Arrival Departure Mode has been saved');
            } else {
                return Redirect::route('arrival-departure-mode.index')->with('success','The Arrival Departure Mode has been saved');
                return Redirect::route('arrival-departure-mode.index')->with('error','Error in save data, Please try again.');
            }

        }else{
            return Redirect::route('arrival-departure-mode.index')->with('error','Error in save data, Please try again.');
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
        $arrival_departure_mode = ArrivalDepartureMode::find($id);
        if(isset($arrival_departure_mode) && sizeof($arrival_departure_mode)>0) {
            return view('arrivalDepartureMode.edit', array('action' => 'edit', 'arrival_departure_mode' => $arrival_departure_mode));
        }else{
            return Redirect::route('arrival-departure-mode.index')->with('error','The Arrival Departure Mode not found.');
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
        $arrival_departure_mode = ArrivalDepartureMode::find($id);
        if(isset($arrival_departure_mode) && sizeof($arrival_departure_mode)>0) {

            $name = Request::get('name');
            $description = Request::get('description');
            $owner_id = Auth::id();
            $outlet_id = Session::get('outlet_session');

            $arrival_departure_mode->name = $name;
            $arrival_departure_mode->outlet_id = $outlet_id;
            $arrival_departure_mode->description = $description;
            $arrival_departure_mode->updated_by = $owner_id;
            $result = $arrival_departure_mode->save();
            if($result) {
                return Redirect::route('arrival-departure-mode.index')->with('success','The Arrival Departure Mode has been updated.');
            }else{
                return Redirect::route('arrival-departure-mode.index')->with('error','Error in update. Please try again.');
            }
        }else{
            return Redirect::route('arrival-departure-mode.index')->with('error','The Arrival Departure Mode not found.');
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
        $arrival_departure_mode = ArrivalDepartureMode::find($id);
        if(isset($arrival_departure_mode) && sizeof($arrival_departure_mode)>0){
            $user_id = Auth::id();
            $arrival_departure_mode->deleted_by = $user_id;
            $arrival_departure_mode->save();
            $arrival_departure_mode->delete();
            return "success";
        }else{
            return "error";
        }
	}

}
