<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\OrderPlaceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrderPlaceTypeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$owner_id = Auth::id();
		$outlet_id = Session::get('outlet_session');

		$places = OrderPlaceType::where('outlet_id',$outlet_id)->get();

		return view('orderplacetypes.index', array('places' => $places));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('orderplacetypes.create',array('action'=>'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$messages = [
			'name.required' => 'Name is required!',
		];

		$p = Validator::make($request->all(), [
			'name' => 'required',
		],$messages);


		if (isset($p) && $p->passes())
		{
			$owner_id = Auth::id();
			$save_continue = Request::get('saveContinue');
			$name = Request::get('name');

			$outlet_id = Session::get('outlet_session');

			$place = new OrderPlaceType();
			$place->name = $name;
			$place->outlet_id = $outlet_id;
			$place->created_by = $owner_id;
			$place->updated_by = $owner_id;
			$result = $place->save();

			if ( $result ) {
				if ( isset($save_continue) && $save_continue == 'true' ) {
					return Redirect::route('order-place-types.create')->with('success','New Order Place has been added....');
				} else {
					return Redirect::route('order-place-types.index')->with('success','New Order Place has been added....');
				}

			}

		} else {
			return redirect()->back()->withInput(Request::all())->withErrors($p->errors());
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
		$place = OrderPlaceType::find($id);

		return view('orderplacetypes.edit',array('place'=>$place,'action'=>'edit'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$messages = [
			'name.required' => 'Name is required!',
		];

		$p = Validator::make(Request::all(), [
			'name' => 'required',
		],$messages);

		if (isset($p) && $p->passes())
		{
			$owner_id = Auth::id();
			$name = Request::get('name');

			$place = OrderPlaceType::find($id);
			$place->updated_by = $owner_id;
			$place->name = $name;
			$result = $place->save();

			if ( $result ) {
				return Redirect::route('order-place-types.index')->with('success', 'Order place has been updated successfully!');
			}

		} else {
			return redirect()->back()->withInput(Request::all())->withErrors($p->errors());
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
		OrderPlaceType::where('id',$id)->delete();
		Session::flash('success', 'Order place has been deleted successfully!');
		return Redirect::to('order-place-types');
	}

}
