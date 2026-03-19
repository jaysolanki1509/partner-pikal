<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Outlet;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class TaxController extends Controller {

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
        $tax=Tax::all();

        return view('tax.index',array('tax'=>$tax));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('tax.create',array('action'=>'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        //print_r(Input::get('tax_percentage'));exit;
        $outletdetails=Outlet::Outletbyownerid(Auth::user()->id);

        $tax=new Tax();
        $tax->outlet_id=$outletdetails[0]->id;
        $tax->tax_type=Input::get('tax_name');
        $tax->tax_percent=Input::get('tax_percentage');
        $tax->created_by=Auth::user()->id;
        $success = $tax->save();
        if($success)
        {
            return Redirect('/tax')->with('success', 'Tax added successfully');
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
        $tax=Tax::find($id);

        return view('tax.edit', array('tax'=>$tax,'action' => 'edit'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $outletdetails=Outlet::Outletbyownerid(Auth::user()->id);

        $user_id=Auth::user()->id;
        $tax=Tax::find($id);
        $tax->tax_type=Input::get('tax_name');
        $tax->tax_percent=Input::get('tax_percentage');
        $tax->outlet_id=$outletdetails[0]->id;

        $success=$tax->save();

        if($success){
            return Redirect('/tax')->with('success', 'Tax updated successfully');
        }
        else
        {
            return Redirect('/tax')->with('error', 'Failed');
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
        Tax::where('id',$id)->delete();
        Session::flash('flash_message', 'Successfully deleted Tax!');
        return Redirect::to('tax');
	}

}
