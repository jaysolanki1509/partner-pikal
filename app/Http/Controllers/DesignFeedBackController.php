<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\order_details;
use App\Outletlatlong;
use App\OutletType;
use App\CuisineType;
use App\OutletTypeMapper;
use App\OutletMapper;
use App\State;
use App\City;
use App\Outlet;
use App\OutletOutletType;
use App\Outletimage;
use App\OutletCuisineType;
use App\Menu;
use App\MenuTitle;
use App\Http\Requests\CreateOutletRequest;
use App\Timeslot;
use App\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Country;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine;
use Illuminate\Support\Facades\Response;
use App\locality;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Owner;
use App\Roles;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\status;
use ZendService\Apple\Apns\Client\Feedback;

//use Kodeine\Acl\Traits\HasRole;

class DesignFeedBackController extends Controller {


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
		//
	}



	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $user_id=Auth::user()->id;

        $outlets = Outlet::all();
        $ownerlist = DB::table('outlets')
                ->leftJoin('outlets_mapper','outlets_mapper.owner_id','=','outlets.owner_id')
                ->where('outlets.owner_id','=',$user_id)
                ->select('outlets.owner_id','outlets.name')->get();
        //print_r($ownerlist[0]->owner_id);exit;

        $outlet_mappers = OutletMapper::getOutletMapperByOwnerId($user_id);
        //print_r($outlet_mappers->select('outlet_id','id')->pluck());exit;

        $select_outlets = ['' => 'Select Outlet'];

        if($outlet_mappers!=null){
            foreach($outlet_mappers as $outlet)
            {
                $outlet_detail=DB::table('outlets')->select('name')->where('id',$outlet->outlet_id)->first();
                $select_outlets[$outlet->outlet_id] = $outlet_detail->name;
            }
        }
        //print_r($user_id);exit;
        return view('feedback.create',array('select_outlets' => $select_outlets, 'outlet_mappers' => $outlet_mappers, 'user_id' => $user_id));

    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        /*if(Request::get('outlet_id')==''){
            return Redirect('/designFeedBack')->with('error', 'Please select Outlet');
        }*/
		//print_r(Request::get('field_name0'));exit;
        $outlet_id=Request::get('outlet_id');
        $sess_outlet_id = Session::get('outlet_session');
        if (isset($sess_outlet_id) && $sess_outlet_id != '') {
            $outlet_id = $sess_outlet_id;
        }
        DB::table('feedback')->where('outlet_id','=',$outlet_id)->delete();
        $total=Request::get('count');
        for($i=0;$i<=$total;$i++){
            $field_name=Request::get('field_name'.$i);
            if($field_name=='')
                continue;
            if(Request::get('field_type'.$i)=='line'){
                $field_type=Request::get('field_type'.$i);
                $field_value=Request::get('line_number'.$i);
                DB::table('feedback')->insert(
                    array('outlet_id' => $outlet_id, 'field_name' => $field_name, 'field_type' => $field_type, 'line_value' => $field_value)
                );
            }elseif(Request::get('field_type'.$i)=='options'){
                $field_type=Request::get('field_type'.$i);
                $field_value=Request::get('options_type'.$i);
                DB::table('feedback')->insert(
                    array('outlet_id' => $outlet_id, 'field_name' => $field_name, 'field_type' => $field_type, 'option_value' => $field_value)
                );
            }
        }

        return Redirect('/designFeedBack')->with('success', 'FeedBack form submitted successfully');
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
		//
	}

	public function getFeedback(){

	    $outlet_id = Request::get('outlet_id');
        $sess_outlet_id = Session::get('outlet_session');
        if (isset($sess_outlet_id) && $sess_outlet_id != '') {
            $outlet_id = $sess_outlet_id;
        }
        $feedbacks = DB::table('feedback')->where('outlet_id',$outlet_id)->get();

        $i = 0;
        $html_table = '<div id="design_table" class="col-md-12">
                    <table border="1" id="feedback_table" style="margin-top: 10px; width: 100%;">
                        <tr>
                            <th class="col-md-4">Field Name</th>
                            <th class="col-md-2">Field Type</th>
                            <th class="col-md-6">Value</th>
                        </tr>';
        foreach ($feedbacks as $feedback){
            if($feedback->field_type == 'options') {
                $html_table .= '<tr>
                                <td>' . $feedback->field_name . '</td>
                                <td>' . $feedback->field_type . '</td>
                                <td>' . $feedback->option_value . '</td>
                            </tr>';
            }else{
                $html_table .= '<tr>
                                <td>' . $feedback->field_name . '</td>
                                <td>' . $feedback->field_type . '</td>
                                <td>' . $feedback->line_value . '</td>
                            </tr>';

            }
        }
        $html_table .= '</table></div>';

        return $html_table;

    }

}
