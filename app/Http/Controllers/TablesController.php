<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\HttpClientWrapper;
use App\Outlet;
use App\OutletMapper;
use App\Owner;
use Carbon\Carbon;
use App\TableLevel;
use App\Utils;
use App\order_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Tables;
use App\Timeslot;
use Illuminate\Database\Query\Builder ;

class TablesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$httpclient = new HttpClientWrapper();
		$token = $_COOKIE['laravel_session'];
		$url = 'https://' . $_SERVER['SERVER_NAME'] . '/api/v3/tables-list';
		$tables_list = $httpclient->send_request('get', '', $url, $token);
		$result = json_decode($tables_list);

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


		if ( isset($result) && $result['status'] === 'success' ) {
			if ( isset($result->data['message'])) {
				$result->data['message'] = str_replace('Table',$order_lable,$result->data['message']);
			}
			return view('tables.index',array('tables'=>$result->data,'order_lable'=>$order_lable));
		} else {
			return view('tables.index',array('tables'=>array(),'order_lable'=>$order_lable));
		}

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		$httpclient = new HttpClientWrapper();
		$token = $_COOKIE['laravel_session'];

		$url = 'https://' . $_SERVER['SERVER_NAME'] . '/api/v3/tables/create';
		$data = $httpclient->send_request('get', '', $url, $token);
		$result = json_decode($data);


        $shape[''] = 'Select Shape';
        if(isset($result) && !empty($result) ) {
            $shape = array_merge($shape, (array)$result->data->shape);
        }else{
            $shape = array();
        }

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

        $table_level[0] = "Select $order_lable Level";
		$levels = TableLevel::where('outlet_id',$outlet_id)->get();
        if ( isset($levels) && sizeof($levels) > 0 ) {
            foreach ( $levels as $lev ) {
                $table_level[$lev->id] = $lev->name;
            }
        }

		return view('tables.create',array(
		                                'shape' => $shape,
                                        'order_lable'=>$order_lable,
                                        'table_level'=>$table_level,
                                        'action'=>'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$form_data = Input::all();
		$httpclient = new HttpClientWrapper();
		$token = $_COOKIE['laravel_session'];
        $sess_outlet_id = Session::get('outlet_session');
        $outlet_id = Input::get('outlet_id');

        if (isset($sess_outlet_id) && $sess_outlet_id != '') {
            $outlet_id = $sess_outlet_id;
        }

        $form_data['outlet_id'] = $outlet_id;
		$url = 'https://' . $_SERVER['SERVER_NAME'] . '/api/v3/tables/create';
		$data = $httpclient->send_request('post', '',$form_data, $url, $token);
	
		$result = json_decode($data);

		$order_lable = 'Table';

		if ( isset($outlet_id) && $outlet_id != '' ) {
			$outlet = Outlet::find($outlet_id);
			if ( isset($outlet) && sizeof($outlet) > 0 ) {
				if ( isset($outlet->order_lable) && $outlet->order_lable != '' ) {
					$order_lable = ucwords($outlet->order_lable);
				}
			}
		}


		if ( isset($result->message)) {
			$result->message = str_replace('Table',$order_lable,$result->message);
		}

        //print_r($result);exit;
		return (array)$result;

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
        $httpclient = new HttpClientWrapper();
        $token = $_COOKIE['laravel_session'];

		$url = 'https://' . $_SERVER['SERVER_NAME'] . '/api/v3/tables/'.$id.'/edit';
		$data1 = $httpclient->send_request('get', '', $url, $token);
        // $data1 = $httpclient->send_request('get','',$_SERVER['SERVER_NAME'].'/api/v3/tables/'.$id.'/edit',$token);
        $result1 = json_decode($data1);
		
		$tableListurl = 'https://' . $_SERVER['SERVER_NAME'] . '/api/v3/tables/create';
		$data = $httpclient->send_request('get', '', $tableListurl, $token);
		
        // $data = $httpclient->send_request('get','',$_SERVER['SERVER_NAME'].'/api/v3/tables/create',$token);
        $result = json_decode($data);

		$outlet_id = Session::get('outlet_session');
		$order_lable = 'Table';

		if ( isset($outlet_id) && $outlet_id != '' ) {
			$outlet = Outlet::find($outlet_id);
			if ( isset($outlet) && sizeof($outlet) > 0 ) {
				if ( isset($outlet->order_lable) && $outlet->order_lable != '' ) {
					$order_lable = ucwords($outlet->order_lable);
				}
			}
		}

        $table_level[0] = "Select $order_lable Level";
        $levels = TableLevel::where('outlet_id',$outlet_id)->get();
        if ( isset($levels) && sizeof($levels) > 0 ) {
            foreach ( $levels as $lev ) {
                $table_level[$lev->id] = $lev->name;
            }
        }

        $shape[''] = 'Select Shape';
        $shape = array_merge($shape,(array)$result->data->shape);

        return view('tables.create',array('result' => $result1->data,'table_level'=>$table_level,'shape' => $shape ,'order_lable' => $order_lable,'action'=>'edit'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $form_data = Input::all();
        $form_data['table_id'] = $id;

        $httpclient = new HttpClientWrapper();
        $token = $_COOKIE['laravel_session'];

		$sess_outlet_id = Session::get('outlet_session');

		if (isset($sess_outlet_id) && $sess_outlet_id != '') {
			$outlet_id = $sess_outlet_id;
		}

		$order_lable = 'Table';

		if ( isset($outlet_id) && $outlet_id != '' ) {
			$outlet = Outlet::find($outlet_id);
			if ( isset($outlet) && sizeof($outlet) > 0 ) {
				if ( isset($outlet->order_lable) && $outlet->order_lable != '' ) {
					$order_lable = ucwords($outlet->order_lable);
				}
			}
		}

		$form_data['outlet_id'] = $outlet_id;
		$url = 'https://' . $_SERVER['SERVER_NAME'] . '/api/v3/tables/update';
		$data = $httpclient->send_request('post', '', $url,$form_data, $token);
        // $data = $httpclient->send_request('post', $form_data,$_SERVER['SERVER_NAME'].'/api/v3/tables/update',$token);
        $result = json_decode($data);

		if ( isset($result->message)) {
			$result->message = str_replace('Table',$order_lable,$result->message);
		}

        return (array)$result;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $httpclient = new HttpClientWrapper();
        $token = $_COOKIE['laravel_session'];

        $data = $httpclient->send_request('get','',$_SERVER['SERVER_NAME'].'/api/v3/tables/'.$id.'/destroy',$token);
        $result = json_decode($data);

		$outlet_id = Session::get('outlet_session');

		$order_lable = 'Table';

		if ( isset($outlet_id) && $outlet_id != '' ) {
			$outlet = Outlet::find($outlet_id);
			if ( isset($outlet) && sizeof($outlet) > 0 ) {
				if ( isset($outlet->order_lable) && $outlet->order_lable != '' ) {
					$order_lable = ucwords($outlet->order_lable);
				}
			}
		}

		if ( isset($result->message)) {
			$result->message = str_replace('Table',$order_lable,$result->message);
		}

        if($result->status == 'success') {

            Session::flash('success', $result->message);
            return Redirect::to('tables');

        }else{

            Session::flash('error', $result->message);
            return Redirect::to('tables');
        }
	}

	public function unoccupy($id)
	{
        $httpclient = new HttpClientWrapper();
        $token = $_COOKIE['laravel_session'];

        $data = $httpclient->send_request('get','',$_SERVER['SERVER_NAME'].'/api/v3/tables/'.$id.'/unoccupy',$token);
        $result = json_decode($data);

		$outlet_id = Session::get('outlet_session');

		$order_lable = 'Table';

		if ( isset($outlet_id) && $outlet_id != '' ) {
			$outlet = Outlet::find($outlet_id);
			if ( isset($outlet) && sizeof($outlet) > 0 ) {
				if ( isset($outlet->order_lable) && $outlet->order_lable != '' ) {
					$order_lable = ucwords($outlet->order_lable);
				}
			}
		}

		if ( isset($result->message)) {
			$result->message = str_replace('Table',$order_lable,$result->message);
		}

        if($result->status == 'success') {

            Session::flash('success', $result->message);
            return Redirect::to('tables');

        }else{

            Session::flash('error', $result->message);
            return Redirect::to('tables');
        }
	}
	public function tableindex()
	{
		$httpclient = new HttpClientWrapper();
		$token = $_COOKIE['laravel_session'];

		$sess_outlet_id = Session::get('outlet_session');
		\Log::info($sess_outlet_id);

		$order_lable = 'Table';
		$total_slots = Timeslot::gettimeslotbyoutletid($sess_outlet_id);
		$date = Carbon::now()->format('Y-m-d');

		$order_date_field = 'table_start_date';

		$from = Carbon::now()->format('Y-m-d');
		$to = Carbon::now()->format('Y-m-d');

		$from_time = "";
		$to_time = "";

		if($total_slots != '' && isset($total_slots)) {

			foreach ($total_slots as $slot){
				if($slot->id == $slot_time){
					$from_time = $slot->from_time;
					$to_time = $slot->to_time;
				}
			}

		}

		if ( $from_time != '' && $to_time != '' ) {
			$from = $from.' '.$from_time.':00';
			$to = $to.' '.$to_time.':59';
		} else {
			$from = Utils::getSessionTime($from,'from');
			$to = Utils::getSessionTime($to,'to');
		}

		$tables = Tables::select('tables.*')
					->groupBy('tables.table_no')
                 	->where('tables.outlet_id', $sess_outlet_id)
					->where('tables.deleted_at', "=", NULL)->get();
					
		foreach ($tables as $key=>$value){
			$orders = order_details::select(DB::raw('Max(orders.totalprice) as totalprice'),DB::raw('Max(orders.round_off) as round_off'),DB::raw('Max(orders.table_no) as tableno'),DB::raw('Max(orders.table_start_date) as startdate'),DB::raw('Max(orders.order_id) as order_id'),DB::raw('Max(orders.person_no) as person_no'),DB::raw('Max(orders.read) as status'))
						->where('orders.'.$order_date_field,'>=', $from)
						->where('orders.'.$order_date_field,'<=', $to)
						->where('orders.cancelorder', '!=', '1')
						->where('orders.table_no', $value['table_no'])
						->where('orders.payment_status', '!=', '1')
						->where('orders.outlet_id', $sess_outlet_id)
						->groupBy('orders.table_no')
						->orderBy('orders.'.$order_date_field, 'Asc')
						->first();

						

				if(isset($orders) != NULL) {
					$formatted_dt1=Carbon::parse($orders->startdate);
					$formatted_dt2=Carbon::now();
					$date_diff = $formatted_dt1->diff($formatted_dt2)->format('%H:%I:%S');
					// $date_diff=$formatted_dt1->diff($formatted_dt2)->format('%H:%I:%S');
					$tables [$key]['startdate'] = $date_diff;
					// $tables [$key]['startdate'] = $orders->startdate;
					$tables [$key]['person_no'] = $orders->person_no;
					$tables [$key]['totalprice'] = $orders->totalprice;
					$tables [$key]['tableno'] = $orders->tableno;
					$tables [$key]['order_id'] = $orders->order_id;
					$tables [$key]['status'] = $orders->status;
				}else {
					$tables [$key]['startdate'] = '';
					$tables [$key]['person_no'] = '';
					$tables [$key]['totalprice'] = '';
					$tables [$key]['tableno'] = '';
					$tables [$key]['order_id'] = '';
					$tables [$key]['status'] = '';
				}
		}
			
		$tables_list = $tables;
		$result = json_decode($tables_list);

		if ( isset($outlet_id) && $outlet_id != '' ) {
			$outlet = Outlet::find($outlet_id);
			if ( isset($outlet) && !empty($outlet)) {
				if ( isset($outlet->order_lable) && $outlet->order_lable != '' ) {
					$order_lable = ucwords($outlet->order_lable);
				}
			}
		}

		if ( isset($result)  ) {
			if ( isset($result->data['message'])) {
				$result->data['message'] = str_replace('Table',$order_lable,$result->data['message']);
			}
			return view('orderlist.tableindex',array('tables'=>$result,'order_lable'=>$order_lable));
		} else {
			return view('orderlist.tableindex',array('tables'=>array(),'order_lable'=>$order_lable));
		}
	}
}

