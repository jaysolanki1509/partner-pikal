<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\room_status;
use App\RoomAmenity;
use App\RoomStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class RoomStatusController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
	    $room_status = RoomStatus::all();

        return view('roomstatus.index', array('room_status' => $room_status));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('roomstatus.create', array('action' => 'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $name = Request::get('name');
        $color = Request::get('color');
        $save_continue = Request::get('saveContinue');
        $owner_id = Auth::id();

        $new_status = new RoomStatus();
        $new_status->name = $name;
        $new_status->color = $color;
        $new_status->created_by = $owner_id;
        $new_status->updated_by = $owner_id;
        $result = $new_status->save();

        if($result){
            if ( isset($save_continue) && $save_continue == 'true' ) {
                return Redirect::route('room-status.create')->with('success','The RoomStatus has been saved');
            } else {
                return Redirect::route('room-status.index')->with('success','The RoomStatus has been saved');
            }

        }else{
            return Redirect::route('room-status.index')->with('error','Error in save data, Please try again.');
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
	    $room_status = RoomStatus::find($id);
        if(isset($room_status) && sizeof($room_status)>0) {
            return view('roomstatus.edit', array('action' => 'edit', 'room_status' => $room_status));
        }else{
            return Redirect::route('room-status.index')->with('error','The RoomStatus not found.');
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
        $room_status = RoomStatus::find($id);
        if(isset($room_status) && sizeof($room_status)>0) {

            $name = Request::get('name');
            $color = Request::get('color');
            $owner_id = Auth::id();

            $room_status->name = $name;
            $room_status->color = $color;
            $room_status->updated_by = $owner_id;
            $result = $room_status->save();
            if($result) {
                return Redirect::route('room-status.index')->with('success','The RoomStatus has been updated.');
            }else{
                return Redirect::route('room-status.index')->with('error','Error in update. Please try again.');
            }
        }else{
            return Redirect::route('room-status.index')->with('error','The RoomStatus not found.');
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
        $room_status = RoomStatus::find($id);
        if(isset($room_status) && sizeof($room_status)>0){
            $room_status->delete();
            return "success";
        }else{
            return "error";
        }
	}

}
