<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11/2/2015
 * Time: 3:50 PM
 */

namespace App\Http\Controllers;

use App\CancellationReason;
use App\Outlet;
use App\OutletMapper;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;



class CancellationReasonController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['home']]);
    }

    public function index()
    {
        $userid=Auth::user();
        $restaurant=$userid->outlet->lists('id');
        $sess_outlet_id = Session::get('outlet_session');

        if (isset($sess_outlet_id) && $sess_outlet_id != '') {
            $restaurant = [$sess_outlet_id];
        }
        $cancel=CancellationReason::wherein('outlet_id',$restaurant)->get();

        return view('cancellationreason.index',array('cancel'=>$cancel));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $owner_id=Auth::user()->id;
        $outlets = OutletMapper::getOutletsByOwnerId();

        if ( sizeof($outlets) == 2 ) {
            unset($outlets['']);
        }

        return view('cancellationreason.create',array('outlets'=>$outlets,'action'=>'add','get'=>'add','set'=>'add','test'=>'add','create'=>'add','make'=>'add'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $outlet_id =Input::get('outlet_id');
        $sess_outlet_id = Session::get('outlet_session');

        if (isset($sess_outlet_id) && $sess_outlet_id != '') {
            $outlet_id = $sess_outlet_id;
        }
        $cancel=new CancellationReason();
        $cancel->outlet_id= $outlet_id;
        $cancel->reason_of_cancellation=Input::get('reason');
        $success = $cancel->save();
        if($success)
        {
            return Redirect('/cancellationreason')->with('success', 'Cancellation Reason added successfully');
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
        $owner_id=Auth::user()->id;
        $outlets = OutletMapper::getOutletsByOwnerId();

        if ( sizeof($outlets) == 2 ) {
            unset($outlets['']);
        }
        $cancel=CancellationReason::find($id);

        return view('cancellationreason.edit', array('cancel'=>$cancel,'outlets'=>$outlets,'action' => 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $outlet_id = Input::get('outlet_id');

        $sess_outlet_id = Session::get('outlet_session');

        if (isset($sess_outlet_id) && $sess_outlet_id != '') {
            $outlet_id = $sess_outlet_id;
        }

        $user_id=Auth::user()->id;
        $cancel=CancellationReason::find($id);
        $cancel->outlet_id = $outlet_id;
        $cancel->reason_of_cancellation=Input::get('reason');

        $success=$cancel->save();

        if($success){
            return Redirect('/cancellationreason')->with('success', 'Cancellation Reason updated successfully');
        }
        else
        {
            return Redirect('/cancellationreason')->with('error', 'Failed');
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
        CancellationReason::where('id',$id)->delete();
        Session::flash('flash_message', 'Successfully deleted Cancellation Reason!');
        return Redirect::to('cancellationreason');
    }
}