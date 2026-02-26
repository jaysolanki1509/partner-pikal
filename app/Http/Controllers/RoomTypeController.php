<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\RoomAmenity;
use App\RoomTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\room_amenity;
use App\room_types;


class RoomTypeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $outlet_id = Session::get('outlet_session');
        if(isset($outlet_id) && $outlet_id != "") {
            $room_types = RoomTypes::where('outlet_id', $outlet_id)->get();
            return view('roomtype.index', array('room_types' => $room_types));
        }else{
            return view('roomtype.index', array('room_types' => ""));
        }
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
	    $one_to_fifty = RoomTypes::getOnetoFiftyArray();
	    $zero_to_fifty = RoomTypes::getZerotoFiftyArray();
        $outlet_id = Session::get('outlet_session');

        $amenities = RoomAmenity::getAmenitisByOutletId($outlet_id);
        return view('roomtype.create', array('action' => 'add','one_to_fifty'=>$one_to_fifty,'zero_to_fifty'=>$zero_to_fifty,'room_amenities'=>$amenities,'action'=>'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

        $name = Input::get('name');
        $short_name = Input::get('short_name');
        $description = Input::get('description');
        $save_continue = Input::get('saveContinue');
        $base_occupancy = Input::get('base_occupancy');
        $higher_occupancy = Input::get('higher_occupancy');
        $extra_bed_allowed = Input::get('extra_bed_allowed');
        $no_of_beds_allowed = Input::get('no_of_beds_allowed');
        $base_price = Input::get('base_price');
        $higher_price_per_person = Input::get('higher_price_per_person');
        $extra_bed_price = Input::get('extra_bed_price');
        $amenities = Input::get('amenities');
        $owner_id = Auth::id();
        $outlet_id = Session::get('outlet_session');

        $type = new RoomTypes();
        $type->name = $name;
        $type->short_name = $short_name;
        $type->outlet_id = $outlet_id;
        $type->description = $description;
        $type->base_occupancy = $base_occupancy;
        $type->higher_occupancy = $higher_occupancy;
        if(isset($extra_bed_allowed) && sizeof($extra_bed_allowed)>0) {
            $type->extra_bed_allowed = 1;
            $type->no_of_beds_allowed = $no_of_beds_allowed;
            $type->extra_bed_price = $extra_bed_price;
        }
        $type->base_price = $base_price;
        $type->higher_price_per_person = $higher_price_per_person;
        $type->amenities = json_encode($amenities);
        $type->created_by = $owner_id;
        $type->updated_by = $owner_id;
        $result = $type->save();

        if($result){
            if ( isset($save_continue) && $save_continue == 'true' ) {
                return Redirect::route('room-type.create')->with('success','The RoomType has been saved');
            } else {
                return Redirect::route('room-type.index')->with('success','The RoomType has been saved');
            }

        }else{
            return Redirect::route('room-type.index')->with('error','Error in save data, Please try again.');

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
        $room_type = RoomTypes::find($id);
        $one_to_fifty = RoomTypes::getOnetoFiftyArray();
        $zero_to_fifty = RoomTypes::getZerotoFiftyArray();
        $outlet_id = Session::get('outlet_session');
        $amenities = RoomAmenity::getAmenitisByOutletId($outlet_id);
        if(isset($room_type) && sizeof($room_type)>0) {
            return view('roomtype.edit', array('action' => 'edit','one_to_fifty'=>$one_to_fifty,'zero_to_fifty'=>$zero_to_fifty,'room_amenities'=>$amenities, 'room_type' => $room_type));
        }else{
            return Redirect::route('room-type.index')->with('error','The RoomType not found.');
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
        $room_type = RoomTypes::find($id);
        if(isset($room_type) && sizeof($room_type)>0) {

            $name = Input::get('name');
            $owner_id = Auth::id();
            $short_name = Input::get('short_name');
            $description = Input::get('description');
            $save_continue = Input::get('saveContinue');
            $base_occupancy = Input::get('base_occupancy');
            $higher_occupancy = Input::get('higher_occupancy');
            $extra_bed_allowed = Input::get('extra_bed_allowed');
            $no_of_beds_allowed = Input::get('no_of_beds_allowed');
            $base_price = Input::get('base_price');
            $higher_price_per_person = Input::get('higher_price_per_person');
            $extra_bed_price = Input::get('extra_bed_price');
            $amenities = Input::get('amenities');
            $owner_id = Auth::id();
            $outlet_id = Session::get('outlet_session');

            $room_type->name = $name;
            $room_type->short_name = $short_name;
            $room_type->outlet_id = $outlet_id;
            $room_type->description = $description;
            $room_type->base_occupancy = $base_occupancy;
            $room_type->higher_occupancy = $higher_occupancy;
            if(isset($extra_bed_allowed) && sizeof($extra_bed_allowed)>0) {
                $room_type->extra_bed_allowed = 1;
                $room_type->no_of_beds_allowed = $no_of_beds_allowed;
                $room_type->extra_bed_price = $extra_bed_price;
            }else{
                $room_type->extra_bed_allowed = 0;
            }
            $room_type->base_price = $base_price;
            $room_type->higher_price_per_person = $higher_price_per_person;
            $room_type->amenities = json_encode($amenities);
            $room_type->created_by = $owner_id;
            $room_type->updated_by = $owner_id;
            $room_type->updated_by = $owner_id;
            $result = $room_type->save();
            if($result) {
                return Redirect::route('room-type.index')->with('success','RoomType has been updated.');
            }else{
                return Redirect::route('room-type.index')->with('error','Error in update. Please try again.');
            }
        }else{
            return Redirect::route('room-type.index')->with('error','RoomType not found.');
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
        $room_types = RoomTypes::find($id);
        if(isset($room_types) && sizeof($room_types)>0){
            $user_id = Auth::id();
            $room_types->deleted_by = $user_id;
            $room_types->save();
            $room_types->delete();
            return "success";
        }else{
            return "error";
        }
	}

}
