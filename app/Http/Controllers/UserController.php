<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\OutletMapper;
use App\Owner;
use App\Role;
use App\User;
use App\Utils;
use Illuminate\Http\Request;
use App\State;
use App\City;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

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
        $user_id=Auth::id();
        $data['users'] = Owner::where('created_by',$user_id)->orWhere('id',$user_id)->get();

		$outs=[];
		foreach($data['users'] as $users) {
			$data['outlets']=DB::table('outlets_mapper')->select('outlets.id','outlets.name')
				->join('outlets','outlets.id','=','outlets_mapper.outlet_id')->where('outlets_mapper.owner_id',$users->id)->get();

			foreach($data['outlets'] as $outlets) {

				if (array_key_exists($users->id, $outs)) {
					$outs[$users->id] = $outs[$users->id] . ", " . $outlets->name;
				} else {
					$outs[$users->id] = $outlets->name;
				}
			}
		}
		$data['outs']=$outs;
		//print "<pre>";print_r($data);exit;
		return view("users.index",$data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$user_id=Auth::user()->id;

		$Outlets=DB::table('outlets')
			->select('outlets.id as oid','outlets.*','outlets_mapper.*')
			->join('outlets_mapper','outlets.id','=','outlets_mapper.outlet_id')
			->where('outlets_mapper.owner_id',$user_id)
			->get();
        $outlet_arr=[];
        $outlet_arr['']='Select Outlet';
        foreach($Outlets as $outlet){
            $outlet_arr[$outlet->oid]=$outlet->name;
        }
            //print_r($outlet_arr);exit;

		$data['action'] = "add";
		$data['states'] = State::all()->pluck('name','id');
		$data['cities'] = City::all()->pluck('name','id');
		$data['outlet_id'] = $outlet_arr;
		$data['outlet_select'] = '';
		$data['roles'] = Role::all()->pluck('name','id');
        //$data['order_type'] = Utils::getOrderType();
		return view("users.create",$data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $messages = [
            'user_name.required' => 'Username is required!',
            'user_name.max:20' => 'Username Must Be less then 20 character!',
            'user_name.unique:owners' => 'Username is Already Taken!',
            'contact_no.min:10' => 'Contact No. must Be 10 digit!',
            'contact_no.required' => 'Contact No. is Required!',
            'email.email' => 'Email must Be in Email Format!',
        ];
        $p = Validator::make($request->all(), [
            'user_name' => 'required|max:20|unique:owners',
            'contact_no' => 'required|min:10',
        ],$messages);
        if (isset($p) && $p->passes())
        {
            $user_id = Auth::user()->id;
            $account_id = Auth::user()->account_id;

            /*$ord_receive = Request::get('order_receive');
            if ( isset($ord_receive) && sizeof($ord_receive) > 0 ) {
                $ord_receive = json_encode($ord_receive);
            }*/

            $owner = new Owner();
            $owner->account_id = $account_id;
            $owner->user_name=Request::get("user_name");
            $owner->email=Request::get("email");
            $owner->password=Hash::make(Request::get('password'));
            $owner->contact_no=Request::get("contact_no");
            $owner->state=Request::get("states");
            $owner->city=Request::get("cities");
            $owner->role_id=Request::get("roles");
            $owner->created_by=$user_id;
            //$owner->order_receive = $ord_receive;
            $user_identifier = Request::get("user_identifier");
            $owner->user_identifier=isset($user_identifier)?$user_identifier:0;

            if( isset($request->web_login) && $request->web_login == 1 )
                $owner->web_login = 1;
            else
                $owner->web_login = 0;

            $is_success = $owner->save();

            $outlet_id  = Request::get('outlet_id');
            $sess_outlet_id = Session::get('outlet_session');

            if (isset($sess_outlet_id) && $sess_outlet_id != '') {
                $outlet_id = $sess_outlet_id;
            }

            $mapper = new OutletMapper();
            $mapper->outlet_id = $outlet_id;
            $mapper->owner_id = $owner->id;
            $mapper->save();

            //DB::table('outlets_mapper')->insert(['outlet_id'=>Request::get('outlet_id'),'owner_id'=>$owner->id]);

            $pass=str_random(6);
            /*$email=Request::get("email");

            $ownerid=$owner->id;
            if($ownerid!=''){
                Mail::send('emails.newpassword', ['password' => $pass,'ownerid'=>$ownerid,'username'=>Request::get("user_name")], function($message) use ($email)
                {
                    $message->from('we@foodklub.in', 'FOODKLUB');
                    $message->to($email, 'FoodKlub');
                    $message->bcc('paragbsharma@gmail.com', 'Parag');
                    $message->subject('Password For Login Into Foodklub');
                });
            }*/

            //return UserController::index()->with('success','User Added Successfully. Credentials sent on New User\'s Email');
            return Redirect('/users')->with('success','New user added successfully.');

        }else{
            return redirect()->back()->withInput($request->all())->withErrors($p->errors());
        }
	}

    public function changepass(){
        return view("users.changepass");
    }

    public function passwordchange(){
        //print_r(Request::all());exit;
        //Loged in user password change
        if(isset(Request::all()['password']) && isset(Request::all()['new_password']) && isset(Request::all()['confirm_new_password']) ){
            $old_pass=Request::all()['password'];
            $new_password = Request::all()['new_password'];
            $conf_password = Request::all()['confirm_new_password'];
            if($new_password!=$conf_password){
                return redirect()->back()->withInput(Request::all())->with('error','New Password and Confirm Password is not matching.');
            }
            $user = Auth::user();
            if(Hash::check($old_pass, $user->password)){
                $user = Auth::user();
                $user->password = Hash::make($new_password);
                $user->save();
            }else{
                return redirect()->back()->withInput(Request::all())->with('error','Your Old password is not correct.');
            }
            //Specific User Password change
        }elseif (isset(Request::all()['password']) && isset(Request::all()['owner_id'])){
            $password = Request::all()['password'];
            $owner_id = Request::all()['owner_id'];
            $user = Owner::find($owner_id);
            $user->password = Hash::make($password);
            $user->save();

            echo "success";exit;

        }else{
            return redirect()->back()->withInput(Request::all())->with('error','Some required fields missing data.');
        }
        return Redirect('/changepass')->with('success','Password updated successfully');

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
        $owner_details = Owner::find($id);

        /*if ( isset($owner_details->order_receive) && $owner_details->order_receive != '' ) {
            $owner_details->order_receive = json_decode($owner_details->order_receive);
        }*/
        $roles = Role::all()->pluck('name','id');
        $states = State::all()->pluck('name','id');
        $cities = City::all()->pluck('name','id');
        $result = OutletMapper::getOutletIdByOwnerId($id);
        $selected_outlet = '';
        if(isset($result) && sizeof($result)>0){
            $selected_outlet = $result[0]->outlet_id;
        }else{
            $selected_outlet = '';
        }

        $user_id=Auth::user()->id;

        $Outlets=DB::table('outlets')
            ->select('outlets.id as oid','outlets.*','outlets_mapper.*')
            ->join('outlets_mapper','outlets.id','=','outlets_mapper.outlet_id')
            ->where('outlets_mapper.owner_id',$user_id)
            ->get();
        $outlet_arr=[];
        $outlet_arr['']='Select Outlet';
        foreach($Outlets as $outlet){
            $outlet_arr[$outlet->oid]=$outlet->name;
        }

        //get order types
        $order_type = Utils::getOrderType();

        return view('users.create',array('order_type'=>$order_type,'outlet_id'=>$outlet_arr,'outlet_select'=>$selected_outlet,'roles'=>$roles,'owner'=>$owner_details,'action'=>'edit','states'=>$states,'cities'=>$cities));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$all = Request::all();


        /*$ord_receive = Request::get('order_receive');
        if ( isset($ord_receive) && sizeof($ord_receive) > 0 ) {
            $ord_receive = json_encode($ord_receive);
        }*/

        $user = User::find($id);
        $user->user_name = $all['user_name'];
        $user->email = $all['email'];
        $user->contact_no = $all['contact_no'];
        $user->role_id = $all['roles'];
        $user->state = $all['states'];
        $user->city = $all['cities'];
        //$user->order_receive = $ord_receive;
        /*if(isset($all['password']))
            $user->password = Hash::make($all['password']);*/
        $user_identifier = Request::get("user_identifier");
        $user->user_identifier=isset($user_identifier)?$user_identifier:0;

        if( isset($all['web_login']) && $all['web_login'] == 1 )
            $user->web_login = 1;
        else
            $user->web_login = 0;

        $user->save();

        $outlet_id  = Request::get('outlet_id');
        $sess_outlet_id = Session::get('outlet_session');

        if (isset($sess_outlet_id) && $sess_outlet_id != '') {
            $outlet_id = $sess_outlet_id;
        }

        $mapped = OutletMapper::where('outlet_id',$outlet_id)->where('owner_id',$user->id)->get();
        if(!isset($mapped) && !sizeof($mapped)>0) { //check whether user & outlet already bind/mapped or not?
            $mapper = new OutletMapper();
            $mapper->outlet_id = $outlet_id;
            $mapper->owner_id = $user->id;
            $mapper->save();
        }

        return Redirect('/users')->with('success', 'User details successfully updated.');
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

	public function checkOrderReceive(){
        $order_receive = Request::get("order_receive");
	    if($order_receive != "null")
	        $order_receive = explode(",",Request::get("order_receive"));
        else
            $order_receive = NULL;

	    $outlet_id = Request::get("outlet_id");
	    $owner_id = Request::get("owner_id");

        if($outlet_id == '' || $owner_id == '')
        {
            return "error1";    //      If error in parameter
        } else {

            $all_mappers = OutletMapper::all();
            $flag = 0;

                foreach ($all_mappers as $all_mapper) {
                    if ($all_mapper->outlet_id == $outlet_id) {
                        if ($all_mapper->owner_id == $owner_id) {
                            $flag++;
                            break;
                        }
                    }
                }

                if ($flag == 0) {

                    $getoutlet_order_receive = OutletMapper::where('outlet_id', "=", $outlet_id)->pluck('order_receive');
                    if (isset($order_receive) && sizeof($order_receive) > 0) {
                        foreach ($order_receive as $key => $value) {
                            foreach ($getoutlet_order_receive as $out_key => $out_val) {
                                if (isset($out_val) && sizeof($out_val) > 0) {
                                    if (in_array($value, json_decode($out_val))) {
                                        $flag = 1;
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                    if ($flag == 0) {
                        $outlet_mapper = new OutletMapper();
                        $outlet_mapper->outlet_id = $outlet_id;
                        $outlet_mapper->owner_id = $owner_id;

                        if (isset($order_receive) && sizeof($order_receive) > 0) {
                            $ord_receive = json_encode($order_receive);
                            $outlet_mapper->order_receive = $ord_receive;
                        }
                        $outlet_mapper->save();

                        return "success";
                    } else {
                        return "error";     //Error : Order received rights with some one else
                    }

                } else {
                    $mapper = OutletMapper::where('outlet_id',$outlet_id)
                                        ->where('owner_id',$owner_id)->first();
                    if (isset($order_receive) && sizeof($order_receive) > 0) {
                        $ord_receive = json_encode($order_receive);
                        $mapper->order_receive = $ord_receive;
                    }else{
                        $mapper->order_receive = NULL;
                    }
                    $mapper->save();
                    return "success1";    //Error : Already mapped with user
                }

        }
    }

}
