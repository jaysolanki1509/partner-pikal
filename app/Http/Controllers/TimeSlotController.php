<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\OutletMapper;
use App\ShiftMaster;
use App\Tax;
use App\Timeslot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Outlet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class TimeSlotController extends Controller
{

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
        $user_id=Auth::user()->id;

        $outlet_mappers = OutletMapper::getOutletMapperByOwnerId($user_id);
        $select_outlets = ['' => 'Select Outlet'];

        if($outlet_mappers!=null){
            foreach($outlet_mappers as $outlet)
            {
                $outlet_detail=DB::table('outlets')->select('name')->where('id',$outlet->outlet_id)->first();
                $select_outlets[$outlet->outlet_id] = $outlet_detail->name;
            }
        }

        return view('timeslots.index',array('select_outlets' => $select_outlets));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update()
    {
        $outlet_id = Request::get('outlet_id');

        Timeslot::where('outlet_id',$outlet_id)->delete();

        $count = Request::get('count');

        for($i=0;$i<=$count;$i++){
            $from_time = Request::get('opening_time'.$i);
            if(!isset($from_time)) {
                continue;
            }
            $to_time = Request::get('closing_time'.$i);
            $ts = new Timeslot();
            $ts->outlet_id = $outlet_id;
            $ts->slot_name = Request::get('slot_name'.$i);
            $ts->from_time = $from_time;
            $ts->to_time = $to_time;
            $result = $ts->save();
        }
        if($result){
            $data['status'] = "success";
            return $data;
        }else{
            $data['status'] = "error";
            return $data;
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
        Timeslot::where('id',$id)->delete();
        return Redirect::back()->with('success','Successfully Timeslot Deleted!');
    }

    public static function gettimeslotbyoutletid(){
        $id = Request::get('outlet_id');
        return Timeslot::gettimeslotbyoutletid($id);
    }


}