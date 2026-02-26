<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Room;
use App\RoomStatus;
use App\RoomTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RoomController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $outlet_id = Session::get('outlet_session');
        if(isset($outlet_id) && $outlet_id != "") {
            $room_types = RoomTypes::where('outlet_id', $outlet_id)->pluck('name','id');
            $rooms = Room::where('outlet_id',$outlet_id)->get();
            $room_status = RoomStatus::all()->pluck('name','id');
            return view('rooms.index', array('rooms'=>$rooms,'room_status'=>$room_status,'room_types' => $room_types,));
        }else{
            return view('rooms.index', array('room_types' => ""));
        }
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $outlet_id = Session::get('outlet_session');
        $room_types = RoomTypes::where('outlet_id',$outlet_id)->pluck('name','id');
        $room_types[""]="Select Room Type";
        $room_status = RoomStatus::all()->pluck('name','id');
        $room_status[""] = "Select Room Status";

        return view('rooms.create', array('action'=>'add','room_status' => $room_status, 'room_type'=>$room_types));
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
        $room_type_id = Request::get('room_type_id');
        $room_status_id = Request::get('room_status_id');
        $owner_id = Auth::id();
        $outlet_id = Session::get('outlet_session');

        $room = new Room();
        $room->name = $name;
        $room->outlet_id = $outlet_id;
        $room->description = $description;
        $room->room_type_id = $room_type_id;
        $room->room_status_id = $room_status_id;
        $room->created_by = $owner_id;
        $room->updated_by = $owner_id;
        $result = $room->save();

        if($result){
            if ( isset($save_continue) && $save_continue == 'true' ) {
                return Redirect::route('rooms.create')->with('success','The Room has been saved');
            } else {
                return Redirect::route('rooms.index')->with('success','The Room has been saved');
            }

        }else{
            return Redirect::route('rooms.index')->with('error','Error in save data, Please try again.');
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
        $room = Room::find($id);
        if(isset($room) && sizeof($room)>0) {
            $outlet_id = Session::get('outlet_session');
            $room_types = RoomTypes::where('outlet_id',$outlet_id)->pluck('name','id');
            $room_types[""]="Select Room Type";
            $room_status = RoomStatus::all()->pluck('name','id');
            $room_status[""] = "Select Room Status";

            return view('rooms.edit', array('room'=>$room,'action'=>'edit','room_status' => $room_status, 'room_type'=>$room_types));
        }else{
            return Redirect::route('rooms.index')->with('error','The Room not found.');
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
        $name = Request::get('name');
        $description = Request::get('description');
        $save_continue = Request::get('saveContinue');
        $room_type_id = Request::get('room_type_id');
        $room_status_id = Request::get('room_status_id');
        $owner_id = Auth::id();
        $outlet_id = Session::get('outlet_session');

        $room = Room::find($id);
        if(isset($room) && sizeof($room)>0) {
            $room->name = $name;
            $room->outlet_id = $outlet_id;
            $room->description = $description;
            $room->room_type_id = $room_type_id;
            $room->room_status_id = $room_status_id;
            $room->updated_by = $owner_id;
            $result = $room->save();

            if ($result) {
                if (isset($save_continue) && $save_continue == 'true') {
                    return Redirect::route('rooms.create')->with('success', 'The Room has been updated');
                } else {
                    return Redirect::route('rooms.index')->with('success', 'The Room has been updated');
                }

            } else {
                return Redirect::route('rooms.index')->with('error', 'Error in save data, Please try again.');
            }
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
        $room = Room::find($id);
        if(isset($room) && sizeof($room)>0){
            $user_id = Auth::id();
            $room->deleted_by = $user_id;
            $room->save();
            $room->delete();
            return "success";
        }else{
            return "error";
        }
	}

}
