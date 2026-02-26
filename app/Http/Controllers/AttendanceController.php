<?php namespace App\Http\Controllers;

use App\Attendance;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Owner;
use App\Staff;
use App\Staffing;
use App\StaffRole;
use App\StaffShift;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller {

	public $outlet_id = "";

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$user_id = Auth::id();
		$outlet_id = Session::get('outlet_session');

		if( $_SERVER['HTTP_USER_AGENT'] == 'android_app') {
			$outlet_id = Input::get('outlet_id');
		}

		$att_arr = array();

		$staffs = Staff::where('outlet_id',$outlet_id)->get();

		//attendance of staff
		if ( isset($staffs) && sizeof($staffs) > 0 ) {

			foreach ( $staffs as $st ) {

				$attendance = Attendance::where('staff_id',$st->id)
										->whereDate('created_at','=',date('Y-m-d'))
										->orderBy('id','DESC')
										->first();

				if ( isset($attendance) && sizeof($attendance) > 0 ) {

					if ( isset($attendance->out_time) && $attendance->out_time != '' ) {
						$att_arr[$st->id]='out';
					} else {
						$att_arr[$st->id]='in';
					}

				} else {

					$att_arr[$st->id]='';
				}

			}

		}

		return view("attendance.index",array('staffs'=>$staffs,'attendee'=>$att_arr,'outlet_id'=>$outlet_id));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$owner_id = Auth::id();
		$time = date('Y-m-d H:i:s');


		if( $_SERVER['HTTP_USER_AGENT'] != 'android_app') {
			$outlet_id = Session::get('outlet_session');
		} else {
			$outlet_id = $this->outlet_id;
		}

		$staff_id = Input::get('staff_id');
		$type = Input::get('type');

		if( $type == 'out') {
			//check for in time of user on same date
			$check = Attendance::where('staff_id',$staff_id)
				->whereDate('created_at','=',date('Y-m-d'))
				->where('out_time',NULL)
				->orderBy('id','DESC')
				->first();

			if ( isset($check) && sizeof($check) > 0 ) {

				$check->out_time = $time;
				$check->updated_by = $owner_id;
				$result = $check->save();

			} else {
				return 'not found';
			}

		} else {

			$att = new Attendance();
			$att->staff_id = $staff_id;
			$att->outlet_id = $outlet_id;
			$att->created_by = $owner_id;
			$att->updated_by = $owner_id;
			$att->in_time = $time;

			$result = $att->save();

		}

		if ( $result ) {
			return 'success';
		} else {
			return 'error';
		}

	}

	/**
	 * Display the specified resource.
	 *
	 * @return Response
	 */
	public function show(Request $request)
	{
		$owner_id = Auth::id();
		$outlet_id = Session::get('outlet_session');

		if ($request->ajax()) {

			$from = Input::get('from_date');
			$to = Input::get('to_date');
			$staff_id = Input::get('staff_id');

			$report_obj = new ReportController();
			$date_arr = $report_obj->createDateRangeArray($from, $to);

			$data = array();$salary = array();
			if ( isset($date_arr) && sizeof($date_arr) > 0 ) {

				foreach ( $date_arr as $date ) {

					$staffs = array();
					if ( $staff_id == 'all') {
						$staffs = Staff::where('outlet_id',$outlet_id)->get();
					} else {
						$staffs = Staff::where('id',$staff_id)->get();
					}

					if ( isset($staffs) && sizeof($staffs) > 0 ) {

						foreach( $staffs as $stf ) {

							$time = "00:00:00";
							$attendance = Attendance::where('staff_id',$stf->id)->whereDate('created_at','=',$date)->get();
							if ( isset($attendance) && sizeof($attendance) > 0 ) {

								foreach( $attendance as $att ) {

									$from = $att->in_time; $to = $att->out_time;
									if ( isset($to) && $to != '' ) {

										$date1 = new DateTime($from);
										$date2 = new DateTime($to);
										$interval = $date1->diff($date2);
										$elapsed = $interval->format('%h:%i:%S');

										$secs = strtotime($elapsed)-strtotime("00:00:00");
										$time = date("H:i:s",strtotime($time)+$secs);

									}

								}

							}
							$data[$date][$stf->id] = $time;

							//calculate salary
							if ( isset($stf->per_day) && isset($stf->per_day_hours) && $time != '00:00:00' && $stf->per_day_hours != '00:00:00' && $stf->per_day > 0 ) {

								$full_time = $half_time = $spent_time = 0;
								$time_arr = explode(':',$stf->per_day_hours);
								if ( isset($time_arr) && sizeof($time_arr) > 0 ) {

									$full_time = $time_arr[0] * 60;
									$full_time = $full_time + $time_arr[1];
									$half_time = $full_time / 2;

									$spent_arr = explode(':',$time);
									$spent_time = $spent_arr[0] * 60;
									$spent_time = $spent_time + $spent_arr[1];

								}

								if ( $spent_time >= $full_time  ) {

									if(!isset($salary[$stf->id]))
										$salary[$stf->id] = $stf->per_day;
									else
										$salary[$stf->id] += $stf->per_day;

								} elseif( $spent_time >= $half_time && $spent_time != 0 ) {

									if(!isset($salary[$stf->id]))
										$salary[$stf->id] = $stf->per_day / 2;
									else
										$salary[$stf->id] += $stf->per_day / 2;

								} else {

									if(!isset($salary[$stf->id]))
										$salary[$stf->id] = 0.00;
									else
										$salary[$stf->id] += 0.00;

								}

							} else {

								if(!isset($salary[$stf->id]))
									$salary[$stf->id] = 0.00;
								else
									$salary[$stf->id] += 0.00;

							}
							//echo $stf->name."==".$salary[$stf->id];exit;
						}

					}

				}

			}

			return view("attendance.attendancesheetlist",array('staffs'=>$staffs,'dates'=>$date_arr,'salary'=>$salary,'data'=>$data));
		}

		//outlet staff
		$staff_arr = array();
		$staff_arr['all'] = 'All';
		$staff = Staff::where('outlet_id',$outlet_id)->get();
		if ( isset($staff) && sizeof($staff) > 0 ) {
			foreach($staff as $stf ) {
				$staff_arr[$stf->id] = $stf->name;
			}
		}

		return view("attendance.attendancesheet",array('staff'=>$staff_arr));

	}

	public function attendanceDetail() {

		$date = Input::get('date');
		$stf_id = Input::get('staff_id');

		$att_detail = Attendance::where('staff_id',$stf_id)->whereDate('created_at','=',$date)->get();

		return view("attendance.attendancedetail",array('detail'=>$att_detail));

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

	/**
	 * Show staff roles.
	 *
	 * @return Response
	 */
	public function staffRoles()
	{
		$user_id = Auth::id();

		$roles = StaffRole::where('created_by',$user_id)->get();

		return view("attendance.staffrole",array('roles'=>$roles));
	}

	/**
	 * Show form to create staff roles.
	 *
	 * @return view
	 */

	public function createStaffRole() {

		return view("attendance.createstaffrole",array('action'=>'add'));

	}

	/**
	 * Store form for staff tole.
	 *
	 * @return view
	 */

	public function storeStaffRole(Request $request) {

		$messages = [
			'name.required' => 'Name is required!',
		];

		$p = Validator::make($request->all(), [
			'name' => 'required',
		],$messages);


		if (isset($p) && $p->passes())
		{
			$owner_id = Auth::id();
			$save_continue = Input::get('saveContinue');
			$name = Input::get('name');

			$sft_role = new StaffRole();
			$sft_role->name = $name;
			$sft_role->created_by = $owner_id;
			$sft_role->updated_by = $owner_id;
			$result = $sft_role->save();

			if ( $result ) {
				if ( isset($save_continue) && $save_continue == 'true' ) {
					return Redirect::route('attendance.createstaffrole')->with('success','New staff role has been added....');
				} else {
					return Redirect::route('attendance.staffrole')->with('success','New staff role has been added....');
				}

			}

		} else {
			return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
		}


	}


	public function editStaffRole($id) {

		$role = StaffRole::find($id);

		return view("attendance.createstaffrole",array('role'=>$role,'action'=>'edit'));

	}

	public function updateStaffRole($id) {

		$p = Validator::make(Input::all(), [
			'name' => 'required|unique:staff_roles,name,'.$id
		]);

		if (isset($p) && $p->passes())
		{
			$owner_id = Auth::id();
			$name = Input::get('name');

			$sft_role = StaffRole::find($id);
			$sft_role->name = $name;
			$sft_role->updated_by = $owner_id;
			$result = $sft_role->save();

			if ( $result ) {
				return Redirect::route('attendance.staffrole')->with('success','staff role has been udpated successfully');
			} else {
				return redirect()->back()->withInput(Input::all())->with('error','There is some error, Please try again later');
			}

		} else {
			return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
		}


	}

	public function staffShifts()
	{
		$user_id = Auth::id();
		$shifts = StaffShift::where('created_by',$user_id)->get();

		return view("attendance.staffshift",array('shifts'=>$shifts));
	}

	public function createStaffShift() {

		return view("attendance.createstaffshift",array('action'=>'add'));

	}

	public function storeStaffShift(Request $request) {

		$messages = [
			'name.required' => 'Name is required!',
		];

		$p = Validator::make($request->all(), [
			'name' => 'required',
		],$messages);


		if (isset($p) && $p->passes())
		{
			$owner_id = Auth::id();
			$save_continue = Input::get('saveContinue');
			$name = Input::get('name');
			$from = Input::get('from');
			$to = Input::get('to');

			$slots = array();

			if ( isset($from) && sizeof($from) > 0 ) {
				foreach( $from  as $key=>$fr ) {
					if ( isset($fr) && isset($to[$key]) && $fr != '' && $to[$key] != '') {
						$arr['from'] = $fr;
						$arr['to'] = $to[$key];
						array_push($slots,$arr);
					}
				}
			}

			$st_sft = new StaffShift();
			$st_sft->name = $name;
			$st_sft->slots = json_encode($slots);
			$st_sft->created_by = $owner_id;
			$st_sft->updated_by = $owner_id;
			$result = $st_sft->save();

			if ( $result ) {
				if ( isset($save_continue) && $save_continue == 'true' ) {
					return Redirect::route('attendance.createstaffshift')->with('success','New staff shift has been added....');
				} else {
					return Redirect::route('attendance.staffshift')->with('success','New staff shift has been added....');
				}
			}

		} else {
			return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
		}


	}

	public function editStaffShift($id) {

		$shift = StaffShift::find($id);

		return view("attendance.createstaffshift",array('shift'=>$shift,'action'=>'edit'));

	}

	public function updateStaffShift($id) {

		$p = Validator::make(Input::all(), [
			'name' => 'required|unique:staff_shifts,name,'.$id
		]);

		if (isset($p) && $p->passes())
		{
			$owner_id = Auth::id();
			$name = Input::get('name');
			$from = Input::get('from');
			$to = Input::get('to');

			$slots = array();

			if ( isset($from) && sizeof($from) > 0 ) {
				foreach( $from  as $key=>$fr ) {
					if ( isset($fr) && isset($to[$key]) && $fr != '' && $to[$key] != '') {
						$arr['from'] = $fr;
						$arr['to'] = $to[$key];
						array_push($slots,$arr);
					}
				}
			}

			$st_sft = StaffShift::find($id);
			$st_sft->name = $name;
			$st_sft->slots = json_encode($slots);
			$st_sft->updated_by = $owner_id;
			$result = $st_sft->save();

			if ( $result ) {
				return Redirect::route('attendance.staffshift')->with('success','staff shift has been udpated successfully');
			} else {
				return redirect()->back()->withInput(Input::all())->with('error','There is some error, Please try again later');
			}

		} else {
			return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
		}

	}

	public function staffs()
	{
		$user_id = Auth::id();
		$sess_outlet_id = Session::get('outlet_session');

		$outlet_id = '';
		if (isset($sess_outlet_id) && $sess_outlet_id != '') {
			$outlet_id = $sess_outlet_id;
		}

		$staffs = '';
		if ( isset($outlet_id) && $outlet_id != '' ) {
			$staffs = Staff::join('staff_shifts as ss','ss.id','=','staff.staff_shift_id')
				->join('staff_roles as sr','sr.id','=','staff.staff_role_id')
				->select('staff.id as id','staff.name as name','staff.per_day as per_day','sr.name as role','ss.name as shift')
				->where('outlet_id',$outlet_id)
				->get();
		}

		return view("attendance.staff",array('outlet_id'=>$outlet_id,'staffs'=>$staffs));
	}

	public function createStaff() {

		$owner = Owner::menuOwner();

		$shifts = StaffShift::where('created_by',$owner)->get();

		$roles = StaffRole::where('created_by',$owner)->lists('name','id');
		$sft = array();

		if ( isset($shifts) && sizeof($shifts) > 0 ) {
			foreach( $shifts as $sf ) {
                $timing = '';
				if ( isset($sf->slots) && sizeof($sf->slots) > 0 ) {
					$slots = json_decode($sf->slots,true);
					foreach( $slots as $sl ) {
						if ( isset($timing) && $timing != '' ) {
							$timing .=" | ".$sl['from']." to ".$sl['to'];
						} else {
							$timing .= $sl['from']." to ".$sl['to'];
						}

					}
				}
				$sft[$sf->id] = $sf->name." ( ".$timing." )";
			}
		}

		return view("attendance.createstaff",array('shifts'=>$sft,'roles'=>$roles,'action'=>'add'));

	}

	public function storeStaff (Request $request) {

		$messages = [
			'name.required' => 'Name is required!',
			'per_day.required' => 'Per day is required!',
			'per_day_hours.required' => 'Per day hours is required!',
			'staff_role_id.required' => 'Role is required!',
			'staff_shift_id.required' => 'Shift is required!',
		];

		$p = Validator::make($request->all(), [
			'name' => 'required',
			'per_day'=> 'required',
			'per_day_hours'=> 'required',
			'staff_role_id'=> 'required',
			'staff_shift_id'=> 'required',
		],$messages);


		if (isset($p) && $p->passes())
		{
			$owner_id = Auth::id();
			$save_continue = Input::get('saveContinue');
			$name = Input::get('name');
			$per_day = Input::get('per_day');
			$per_day_hours = Input::get('per_day_hours');
			$shift_id = Input::get('staff_shift_id');
			$role_id = Input::get('staff_role_id');

			$sess_outlet_id = Session::get('outlet_session');

			if (isset($sess_outlet_id) && $sess_outlet_id != '') {
				$outlet_id = $sess_outlet_id;
			}


			$staff = new Staff();
			$staff->outlet_id = $outlet_id;
			$staff->name = $name;
			$staff->per_day = $per_day;
			$staff->per_day_hours = $per_day_hours;
			$staff->staff_role_id = $role_id;
			$staff->staff_shift_id = $shift_id;
			$staff->created_by = $owner_id;
			$staff->updated_by = $owner_id;
			$result = $staff->save();

			if ( $result ) {
				if ( isset($save_continue) && $save_continue == 'true' ) {
					return Redirect::route('attendance.staff')->with('success','New staff has been added....');
				} else {
					return Redirect::route('attendance.createstaff')->with('success','New staff has been added....');
				}
			}

		} else {
			return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
		}

	}

	public function editStaff($id) {

		$owner = Owner::menuOwner();
		$staff = Staff::find($id);

		$shifts = StaffShift::where('created_by',$owner)->get();
		$roles = StaffRole::where('created_by',$owner)->lists('name','id');
        $sft = array();

        if ( isset($shifts) && sizeof($shifts) > 0 ) {
            foreach( $shifts as $sf ) {
                $timing = '';
                if ( isset($sf->slots) && sizeof($sf->slots) > 0 ) {
                    $slots = json_decode($sf->slots,true);

                    foreach( $slots as $sl ) {
                        if ( isset($timing) && $timing != '' ) {
                            $timing .=" | ".$sl['from']." to ".$sl['to'];
                        } else {
                            $timing .= $sl['from']." to ".$sl['to'];
                        }

                    }
                }

                $sft[$sf->id] = $sf->name." ( ".$timing." )";
            }
        }

		return view("attendance.createstaff",array('staff'=>$staff,'shifts'=>$sft,'roles'=>$roles,'action'=>'edit'));

	}

	public function updateStaff($id) {

		$messages = [
			'name.required' => 'Name is required!',
			'per_day.required' => 'Per day is required!',
			'per_day_hours.required' => 'Per day hours is required!',
			'staff_role_id.required' => 'Role is required!',
			'staff_shift_id.required' => 'Shift is required!',
		];

		$p = Validator::make(Input::all(), [
			'name' => 'required',
			'per_day'=> 'required',
			'per_day_hours'=> 'required',
			'staff_role_id'=> 'required',
			'staff_shift_id'=> 'required',
		],$messages);

		if (isset($p) && $p->passes())
		{
			$owner_id = Auth::id();

			$sess_outlet_id = Session::get('outlet_session');

			if (isset($sess_outlet_id) && $sess_outlet_id != '') {
				$outlet_id = $sess_outlet_id;
			}

			$name = Input::get('name');
			$per_day = Input::get('per_day');
			$per_day_hours = Input::get('per_day_hours');
			$shift_id = Input::get('staff_shift_id');
			$role_id = Input::get('staff_role_id');


			$staff = Staff::find($id);
			$staff->outlet_id = $outlet_id;
			$staff->name = $name;
			$staff->per_day = $per_day;
			$staff->per_day_hours = $per_day_hours;
			$staff->staff_role_id = $role_id;
			$staff->staff_shift_id = $shift_id;
			$staff->created_by = $owner_id;
			$staff->updated_by = $owner_id;
			$result = $staff->save();

			if ( $result ) {
				return Redirect::route('attendance.staff')->with('success','staff has been udpated successfully');
			} else {
				return redirect()->back()->withInput(Input::all())->with('error','There is some error, Please try again later');
			}

		} else {
			return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
		}

	}

	public function staffing() {

		$user_id = Auth::id();
		$sess_outlet_id = Session::get('outlet_session');

		$outlet_id = '';
		if (isset($sess_outlet_id) && $sess_outlet_id != '') {
			$outlet_id = $sess_outlet_id;
		}

		$staffing = '';
		if ( isset($outlet_id) && $outlet_id != '' ) {
			$staffing = Staffing::join('staff_shifts as ss','ss.id','=','staffing.staff_shift_id')
				->join('staff_roles as sr','sr.id','=','staffing.staff_role_id')
				->select('staffing.id as id','staffing.qty as qty','sr.name as role','ss.name as shift')
				->where('staffing.outlet_id',$outlet_id)
				->get();
		}

		return view("attendance.staffing",array('outlet_id'=>$outlet_id,'staffing'=>$staffing));


	}

	public function createStaffing() {

		$owner = Owner::menuOwner();

		$shifts = StaffShift::where('created_by',$owner)->lists('name','id');
		$roles = StaffRole::where('created_by',$owner)->lists('name','id');

		return view("attendance.createstaffing",array('shifts'=>$shifts,'roles'=>$roles,'action'=>'add'));

	}

	public function storeStaffing (Request $request) {

		$messages = [
			'qty.required' => 'Qty is required!',
			'staff_role_id.required' => 'Role is required!',
			'staff_shift_id.required' => 'Shift is required!',
			'staff_shift_id.required' => 'Shift is required!',
		];

		$p = Validator::make($request->all(), [
			'qty' => 'required',
			'staff_role_id'=> 'required',
			'staff_shift_id'=> 'required',
		],$messages);


		if (isset($p) && $p->passes())
		{
			$owner_id = Auth::id();
			$save_continue = Input::get('saveContinue');
			$qty = Input::get('qty');
			$shift_id = Input::get('staff_shift_id');
			$role_id = Input::get('staff_role_id');

			$sess_outlet_id = Session::get('outlet_session');

			if (isset($sess_outlet_id) && $sess_outlet_id != '') {
				$outlet_id = $sess_outlet_id;
			}


			$stf = new Staffing();
			$stf->outlet_id = $outlet_id;
			$stf->qty = $qty;
			$stf->staff_role_id = $role_id;
			$stf->staff_shift_id = $shift_id;
			$stf->created_by = $owner_id;
			$stf->updated_by = $owner_id;
			$result = $stf->save();

			if ( $result ) {
				if ( isset($save_continue) && $save_continue == 'true' ) {
					return Redirect::route('attendance.createstaffing')->with('success','New staffing detail has been added....');
				} else {
					return Redirect::route('attendance.staffing')->with('success','New staffing detail has been added....');
				}
			}

		} else {
			return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
		}

	}

	public function editStaffing($id) {

		$owner = Owner::menuOwner();
		$staffing = Staffing::find($id);

		$shifts = StaffShift::where('created_by',$owner)->lists('name','id');
		$roles = StaffRole::where('created_by',$owner)->lists('name','id');

		return view("attendance.createstaffing",array('staffing'=>$staffing,'shifts'=>$shifts,'roles'=>$roles,'action'=>'edit'));

	}

	public function updateStaffing($id) {

		$messages = [
			'outlet_id.required' => 'outlet is required!',
			'qty.required' => 'Qty is required!',
			'staff_role_id.required' => 'Role is required!',
			'staff_shift_id.required' => 'Shift is required!',
		];

		$p = Validator::make(Input::all(), [
			'outlet_id' => 'required',
			'qty'=> 'required',
			'staff_role_id'=> 'required',
			'staff_shift_id'=> 'required',
		],$messages);

		if (isset($p) && $p->passes())
		{
			$owner_id = Auth::id();

			$outlet_id = Input::get('outlet_id');
			$qty = Input::get('qty');
			$shift_id = Input::get('staff_shift_id');
			$role_id = Input::get('staff_role_id');


			$staff = Staffing::find($id);
			$staff->qty = $qty;
			$staff->staff_role_id = $role_id;
			$staff->staff_shift_id = $shift_id;
			$staff->updated_by = $owner_id;
			$result = $staff->save();

			if ( $result ) {
				return Redirect::route('attendance.staffing')->with('success','staffing has been udpated successfully');
			} else {
				return redirect()->back()->withInput(Input::all())->with('error','There is some error, Please try again later');
			}

		} else {
			return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
		}

	}



}
