<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Outlet;
use App\TableLevel;
use App\Tables;
use Doctrine\DBAL\Schema\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TableLevelController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $outlet_id = Session::get('outlet_session');

        $table_levels = TableLevel::where('outlet_id',$outlet_id)->get();

        $order_lable = 'Table';

        if ( isset($outlet_id) && $outlet_id != '' ) {
            $outlet = Outlet::find($outlet_id);
            if ( isset($outlet) && !empty($outlet) ) {
                if ( isset($outlet->order_lable) && $outlet->order_lable != '' ) {
                    $order_lable = ucwords($outlet->order_lable);
                }
            }
        }
        return view('tablelevels.index', array('tablelevels' => $table_levels,'order_lable'=>$order_lable));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $outlet_id = Session::get('outlet_session');
        $order_lable = 'Table';

        if ( isset($outlet_id) && $outlet_id != '' ) {
            $outlet = Outlet::find($outlet_id);
            if ( isset($outlet) && !empty($outlet) ) {
                if ( isset($outlet->order_lable) && $outlet->order_lable != '' ) {
                    $order_lable = ucwords($outlet->order_lable);
                }
            }
        }
        return view('tablelevels.create',array('order_lable'=>$order_lable,'action'=>'add',));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $outlet_id = Session::get('outlet_session');

        $messages = [
            'name.required' => 'Name is required!',
        ];

        $p = Validator::make($request->all(), [
            'name' => "required",
        ],$messages);


        if (isset($p) && $p->passes())
        {
            $owner_id = Auth::id();

            $order_lable = 'Table';

            if ( isset($outlet_id) && $outlet_id != '' ) {
                $outlet = Outlet::find($outlet_id);
                if ( isset($outlet) && !empty($outlet) > 0 ) {
                    if ( isset($outlet->order_lable) && $outlet->order_lable != '' ) {
                        $order_lable = ucwords($outlet->order_lable);
                    }
                }
            }

            $save_continue = Input::get('saveContinue');
            $name = Input::get('name');

            $check_duplicate = TableLevel::where('name',$name)->where('outlet_id',$outlet_id)->first();

            if ( isset($check_duplicate) && !empty($check_duplicate) ) {
                return redirect()->back()->withInput(Input::all())->with('error',"This $order_lable level has been already added");
            }

            $t_level = new TableLevel();
            $t_level->name = $name;
            $t_level->outlet_id = $outlet_id;
            $t_level->created_by = $owner_id;
            $t_level->updated_by = $owner_id;
            $result = $t_level->save();

            if ( $result ) {
                if ( isset($save_continue) && $save_continue == 'true' ) {
                    return Redirect::route('table-levels.create')->with('success',"New $order_lable level has been added....");
                } else {
                    return Redirect::route('table-levels.index')->with('success',"New $order_lable level has been added....");
                }
            }

        } else {
            return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
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
	    $tablelevel = TableLevel::find($id);

        $outlet_id = Session::get('outlet_session');
        $order_lable = 'Table';

        if ( isset($outlet_id) && $outlet_id != '' ) {
            $outlet = Outlet::find($outlet_id);
            if ( isset($outlet) && !empty($outlet) ) {
                if ( isset($outlet->order_lable) && $outlet->order_lable != '' ) {
                    $order_lable = ucwords($outlet->order_lable);
                }
            }
        }

        return view('tablelevels.edit',array('table_level' => $tablelevel,'order_lable' => $order_lable,'action'=>'edit'));
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

        $p = Validator::make(Input::all(), [
            'name' => 'required',
        ],$messages);

        if (isset($p) && $p->passes())
        {
            $owner_id = Auth::id();
            $name = Input::get('name');
            $outlet_id = Session::get('outlet_session');

            $order_lable = 'Table';

            if ( isset($outlet_id) && $outlet_id != '' ) {
                $outlet = Outlet::find($outlet_id);
                if ( isset($outlet) && !empty($outlet) ) {
                    if ( isset($outlet->order_lable) && $outlet->order_lable != '' ) {
                        $order_lable = ucwords($outlet->order_lable);
                    }
                }
            }

            $check_duplicate = TableLevel::where('id','!=',$id)->where('name',$name)->where('outlet_id',$outlet_id)->first();

            if ( isset($check_duplicate) && !empty($check_duplicate) ) {
                return redirect()->back()->withInput(Input::all())->with('error',"This $order_lable level has been already added");
            }

            $t_level = TableLevel::find($id);
            $t_level->updated_by = $owner_id;
            $t_level->name = $name;
            $t_level->outlet_id = $outlet_id;
            $result = $t_level->save();


            if ( $result ) {
                return Redirect::route('table-levels.index')->with('success', "$order_lable level has been updated successfully!");
            }

        } else {
            return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
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
        $outlet_id = Session::get('outlet_session');
        $order_lable = 'Table';
        if ( isset($outlet_id) && $outlet_id != '' ) {
            $outlet = Outlet::find($outlet_id);
            if ( isset($outlet) && !empty($outlet) > 0 ) {
                if ( isset($outlet->order_lable) && $outlet->order_lable != '' ) {
                    $order_lable = ucwords($outlet->order_lable);
                }
            }
        }

        //update table_level id on delete table level
        Tables::where('table_level_id',$id)->update( array('table_level_id'=>'0'));
        $deleteTableLevel = TableLevel::find($id);
        $deleteTableLevel->delete();
        // TableLevel::where('id',$id)->delete();

        Session::flash('success', "$order_lable level has been deleted successfully!");
        return Redirect::to('table-levels');
	}

}
