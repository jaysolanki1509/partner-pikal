<?php namespace App\Http\Controllers;

use App\booking_status;
use App\BookingStatus;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\room_status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class BookingStatusController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $booking_status = BookingStatus::all();

        return view('bookingstatus.index', array('booking_status' => $booking_status));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('bookingstatus.create', array('action' => 'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $name = Input::get('name');
        $color = Input::get('color');
        $save_continue = Input::get('saveContinue');
        $owner_id = Auth::id();

        $new_status = new BookingStatus();
        $new_status->name = $name;
        $new_status->color = $color;
        $new_status->created_by = $owner_id;
        $new_status->updated_by = $owner_id;
        $result = $new_status->save();

        if($result){
            if ( isset($save_continue) && $save_continue == 'true' ) {
                return Redirect::route('booking-status.create')->with('success','The BookingStatus has been saved');
            } else {
                return Redirect::route('booking-status.index')->with('success','The BookingStatus has been saved');
            }
        }else{
            return Redirect::route('booking-status.index')->with('error','Error in save data, Please try again.');
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
        $booking_status = BookingStatus::find($id);
        if(isset($booking_status) && sizeof($booking_status)>0) {
            return view('bookingstatus.edit', array('action' => 'edit', 'booking_status' => $booking_status));
        }else{
            return Redirect::route('booking-status.index')->with('error','The BookingStatus not found.');
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
        $booking_status = BookingStatus::find($id);
        if(isset($booking_status) && sizeof($booking_status)>0) {

            $name = Input::get('name');
            $color = Input::get('color');
            $owner_id = Auth::id();

            $booking_status->name = $name;
            $booking_status->color = $color;
            $booking_status->updated_by = $owner_id;
            $result = $booking_status->save();
            if($result) {
                return Redirect::route('booking-status.index')->with('success','The BookingStatus has been updated.');
            }else{
                return Redirect::route('booking-status.index')->with('error','Error in update. Please try again.');
            }
        }else{
            return Redirect::route('booking-status.index')->with('error','The BookingStatus not found.');
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
        $booking_status = BookingStatus::find($id);
        if(isset($booking_status) && sizeof($booking_status)>0){
            $booking_status->delete();
            return "success";
        }else{
            return "error";
        }
	}

}
