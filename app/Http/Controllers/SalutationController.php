<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Salutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class SalutationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $salutation = Salutation::all();

        return view('salutation.index', array('salutation' => $salutation));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('salutation.create', array('action' => 'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $name = Input::get('name');
        $save_continue = Input::get('saveContinue');
        $owner_id = Auth::id();

        $salutation = new Salutation();
        $salutation->name = $name;
        $salutation->created_by = $owner_id;
        $salutation->updated_by = $owner_id;
        $result = $salutation->save();

        if($result){
            if ( isset($save_continue) && $save_continue == 'true' ) {
                return Redirect::route('salutation.create')->with('success','The Salutation has been saved');
            } else {
                return Redirect::route('salutation.index')->with('success','The Salutation has been saved');
            }

        }else{
            return Redirect::route('salutation.index')->with('error','Error in save data, Please try again.');
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
        $salutation = Salutation::find($id);
        if(isset($salutation) && sizeof($salutation)>0) {
            return view('salutation.edit', array('action' => 'edit', 'salutation' => $salutation));
        }else{
            return Redirect::route('salutation.index')->with('error','The Salutation not found.');
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
        $salutation = Salutation::find($id);
        if(isset($salutation) && sizeof($salutation)>0) {

            $name = Input::get('name');
            $owner_id = Auth::id();

            $salutation->name = $name;
            $salutation->updated_by = $owner_id;
            $result = $salutation->save();
            if($result) {
                return Redirect::route('salutation.index')->with('success','The Salutation has been updated');
            }else{
                return Redirect::route('salutation.index')->with('error','Error in update. Please try again.');
            }
        }else{
            return Redirect::route('booking-status.index')->with('error','The Salutation not found.');
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
        $salutation = Salutation::find($id);
        if(isset($salutation) && sizeof($salutation)>0){
            $salutation->delete();
            return "success";
        }else{
            return "error";
        }
	}

}
