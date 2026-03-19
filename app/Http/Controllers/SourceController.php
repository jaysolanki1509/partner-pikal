<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Outlet;
use App\OutletMapper;
use App\Printer;
use App\Sources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class SourceController extends Controller {

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

        $sources = Sources::all();

        return view('source.index',array('sources'=>$sources));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

        $source_name = Input::get('source_name');
        $response = array();

        if ( isset($source_name) && $source_name != '' ) {

            $source_obj = new Sources();
            $source_obj->name = $source_name;
            $source_obj->created_by = Auth::id();
            $source_obj->updated_by = Auth::id();
            $result = $source_obj->save();

            if ( $result ) {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'error';
            }

        } else {
            $response['status'] = 'empty';
        }

        return json_encode($response);
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        Sources::where('id',$id)->delete();
        Session::flash('success', 'Source detail has been deleted successfully!');
        return Redirect::to('source');
	}

}
