<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller {

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
		$units = Unit::all();

        return view('units.index',array('units'=>$units));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('units.create',array('action'=>'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $messages = [
            'unit.required' => 'Unit name is required!',
        ];

        $p = Validator::make(Request::all(), [
            'unit' => 'required',
        ], $messages);
        if (isset($p) && $p->passes()) {
            $units = new Unit();
            $units->name = Request::get('unit');
            $units->save();
            $units_list = Unit::all();
            return view('units.index', array('units' => $units_list));
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
		$unit = Unit::find($id);

        return view('units.create',array('unit'=>$unit,'action'=>'edit'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $unit = Unit::find($id);
        $unit->name = Request::get('unit');
        $unit->save();

        $unit = Unit::all();

        return view('units.index',array('units'=>$unit));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$result = Unit::find($id);
        $remove = 0;
        if(isset($result) && sizeof($result)>0)
            $remove = $result->delete();
        if($remove==1){
            $units_list = Unit::all();
            return view('units.index', array('units' => $units_list));
        }
        $units_list = Unit::all();
        return view('units.index', array('units' => $units_list));
	}

}
