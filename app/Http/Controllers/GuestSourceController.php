<?php namespace App\Http\Controllers;

use App\GuestSource;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Salutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class GuestSourceController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $outlet_id = Session::get('outlet_session');
        if(isset($outlet_id) && $outlet_id != "") {
            $guest_source = GuestSource::where('outlet_id', $outlet_id)->get();
            return view('guestSource.index', array('guest_source' => $guest_source));
        }else{
            return view('guestSource.index', array('guest_source' => ""));
        }
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('guestSource.create',array("action"=>"add"));
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

        $source = new GuestSource();
        $source->name = $name;
        $source->outlet_id = $outlet_id;
        $source->description = $description;
        $source->created_by = $owner_id;
        $source->updated_by = $owner_id;
        $result = $source->save();

        if($result){
            if ( isset($save_continue) && $save_continue == 'true' ) {
                return Redirect::route('guest-source.create')->with('success','The Guest Source has been saved');
            } else {
                return Redirect::route('guest-source.index')->with('success','The Guest Source has been saved');
                return Redirect::route('guest-source.index')->with('error','Error in save data, Please try again.');
            }

        }else{
            return Redirect::route('guest-source.index')->with('error','Error in save data, Please try again.');
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
        $guest_source = GuestSource::find($id);
        if(isset($guest_source) && sizeof($guest_source)>0) {
            return view('guestSource.edit', array('action' => 'edit', 'guest_source' => $guest_source));
        }else{
            return Redirect::route('guest-source.index')->with('error','The Guest Source not found.');
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
        $guest_source = GuestSource::find($id);
        if(isset($guest_source) && sizeof($guest_source)>0) {

            $name = Input::get('name');
            $description = Input::get('description');
            $owner_id = Auth::id();
            $outlet_id = Session::get('outlet_session');

            $guest_source->name = $name;
            $guest_source->outlet_id = $outlet_id;
            $guest_source->description = $description;
            $guest_source->updated_by = $owner_id;
            $result = $guest_source->save();
            if($result) {
                return Redirect::route('guest-source.index')->with('success','The Guest Source has been updated.');
            }else{
                return Redirect::route('guest-source.index')->with('error','Error in update. Please try again.');
            }
        }else{
            return Redirect::route('guest-source.index')->with('error','The Guest Source not found.');
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
        $guest_source = GuestSource::find($id);
        if(isset($guest_source) && sizeof($guest_source)>0){
            $user_id = Auth::id();
            $guest_source->deleted_by = $user_id;
            $guest_source->save();
            $guest_source->delete();
            return "success";
        }else{
            return "error";
        }
	}

}
