<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Outlet;
use App\Status;
use App\Http\Requests\CreateStatusRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;


class statuscontroller extends Controller
{

        public function __construct()
        {
                $this->middleware('auth', ['except' => ['home']]);
        }
        /**
         * Display a listing of the resource.
         *
         * @return Response
         **/
        public function index()
        {
                $statustime = array();
                $user_id = Auth::user()->id;
                $Outlets = Outlet::getoutletbyownerid($user_id);
                $status = Status::getstatusbyid($user_id);
                // $outlet_id = Status::where('outlet_id',$Outlets)
                //$Outlet_name = Status::where('Outlet_name',$outlet_id)->get();
                // $Outlet = Outlet::where('created',)
                //$Outlets = Outlet::where('created_by', $user_id)->get();
                //print_r($Outlets);exit;
                //echo "sada";exit;
                return view('status.index', array('status' => $status));
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return Response
         */
        public function create()
        {
                $user_id = Auth::user()->id;
                //      $Outlets = Outlet::getoutletbyownerid($user_id);
                $Outlets = Outlet::all();


                return view('status.create', array('Outlets' => $Outlets, 'action' => 'add'));
        }

        /**
         * Store a newly created resource in storage.
         *
         * @return Response
         */

        public function store(CreateStatusRequest $request)
        {
                //print_r($request->all());exit;

                $status = new status();
                $status->owner_id = Auth::user()->id;
                $status->outlet_id = $request->Outlets;
                //        $status->outlet_id =$request->Outlets;
                $status->order = $request->order;
                $status->status = $request->status;
                $status->status = $request->status;
                //        print_r($request->Outlets);exit;
                //        if(isset($status->outlet_id) && $status->outlet_id!= ''){
                //            $status->outlet_id =$request->Outlets;
                //        }
                //
                //        else{
                //            $status->outlet_id='';
                //        }
                $success = $status->save();
                if ($success) {
                        //$status_id = $status->id;
                        return Redirect('/status')->with('success', 'status added successfully ');
                } else {
                        return Redirect('/status')->with('error', 'Failed');
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
                $status =  Status::find($id);
                $Outlet = Outlet::getfirstoutletbystatus($status->outlet_id);
                $Outlet->Outlet_name = Input::get('Outlet_name');
                return view('status.show', array('status' => $status, 'Outlet' => $Outlet));
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return Response
         */


        public function edit($id)
        {
                $status = Status::find($id);
                //        $Outlets = Outlet::getoutletbyownerid($status->owner_id);
                $Outlets = Outlet::all();
                return view('status.edit', array('Outlets' => $Outlets, 'status' => $status, 'action' => 'edit'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  int  $id
         * @return Response
         */
        public function update($id, CreateStatusRequest $request)
        {
                $status = Status::find($id);
                //        $status->outlet_id = $request->outlet_id;


                $status->order = $request->order;
                $status->status = $request->status;
                $status->owner_id = Auth::user()->id;
                $success = $status->save();

                //print_r($success);exit();
                //$Outlet_name = $request->$Outlet_name;
                if ($success) {

                        //            foreach($Outlet_name as $name){
                        //                $Outlet_name=new Outlet_name();
                        //                $Outlet_name->status_id = $id;
                        //                $Outlet_name->status_id=$name;
                        //                $Outlet_name->save();
                        //            }


                        return Redirect('/status')->with('success', 'status updated successfully ');
                } else {
                        return Redirect('/status')->with('error', 'Failed');
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
                Status::where('id', $id)->delete();

                Session::flash('flash_message', 'Successfully deleted the Restaurant!');
                return Redirect::to('status');
        }
}
