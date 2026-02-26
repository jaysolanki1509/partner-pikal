<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Room;
use App\room_amenity;
use App\room_status;
use App\RoomAmenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class RoomAmenityController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $outlet_id = Session::get('outlet_session');
        if(isset($outlet_id) && $outlet_id != "") {
            $room_amenity = RoomAmenity::where('outlet_id', $outlet_id)->get();
            return view('roomamenities.index', array('room_amenity' => $room_amenity));
        }else{
            return view('roomamenities.index', array('room_amenity' => ""));
        }
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('roomamenities.create',array("action"=>"add"));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $name = Input::get('name');
        $description = Input::get('description');
        $save_continue = Input::get('saveContinue');
        $owner_id = Auth::id();
        $outlet_id = Session::get('outlet_session');

        $amenity = new RoomAmenity();
        $amenity->name = $name;
        $amenity->outlet_id = $outlet_id;
        $amenity->description = $description;
        $amenity->created_by = $owner_id;
        $amenity->updated_by = $owner_id;
        $result = $amenity->save();

        if($result){
            if ( isset($save_continue) && $save_continue == 'true' ) {
                return Redirect::route('room-amenity.create')->with('success','The RoomAmenity has been saved');
            } else {
                return Redirect::route('room-amenity.index')->with('success','The RoomAmenity has been saved');
                return Redirect::route('room-amenity.index')->with('error','Error in save data, Please try again.');
            }

        }else{
            return Redirect::route('room-amenity.index')->with('error','Error in save data, Please try again.');
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
        $room_amenity = RoomAmenity::find($id);
        if(isset($room_amenity) && sizeof($room_amenity)>0) {
            return view('roomamenities.edit', array('action' => 'edit', 'room_amenity' => $room_amenity));
        }else{
            return Redirect::route('room-amenity.index')->with('error','The RoomAmenity not found.');
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
        $room_amenity = RoomAmenity::find($id);
        if(isset($room_amenity) && sizeof($room_amenity)>0) {

            $name = Input::get('name');
            $description = Input::get('description');
            $owner_id = Auth::id();
            $outlet_id = Session::get('outlet_session');

            $room_amenity->name = $name;
            $room_amenity->outlet_id = $outlet_id;
            $room_amenity->description = $description;
            $room_amenity->updated_by = $owner_id;
            $result = $room_amenity->save();
            if($result) {
                return Redirect::route('room-amenity.index')->with('success','The RoomAmenity has been updated.');
            }else{
                return Redirect::route('room-amenity.index')->with('error','Error in update. Please try again.');
            }
        }else{
            return Redirect::route('room-amenity.index')->with('error','The RoomAmenity not found.');
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
        $room_amenity = RoomAmenity::find($id);

        if(isset($room_amenity) && sizeof($room_amenity)>0){
            $user_id = Auth::id();
            $room_amenity->deleted_by = $user_id;
            $room_amenity->save();
            $room_amenity->delete();
            return "success";
        }else{
            return "error";
        }
	}

}
