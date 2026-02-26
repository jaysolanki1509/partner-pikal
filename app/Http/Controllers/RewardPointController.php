<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\RewardPoint;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RewardPointController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

	    $customers = User::all();
        $customers_list = array();

        if(isset($customers) && sizeof($customers) > 0) {
            $customers_list[""] = "Select Contact number";
            foreach ($customers as $customer) {
                if(trim($customer->contact_no) != "") {
                    $customers_list[$customer->id] = $customer->contact_no;
                }
            }
        } else {
            echo "No customers found. Please start taking customer contact numbers while ordering.";exit;
        }

        return view('rewardpoint.index',array('customers'=>$customers_list,'action'=>'add'));
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

        $outlet_id = Session::get('outlet_session');
        $perform = Request::get('perform');

        $rp = new RewardPoint();
        $rp->txn_date=Request::get('txn_date');

        $rp->user_id = Request::get('user_id');

        $rp->outlet_id = $outlet_id;

        $data=$this->getRewardPoints();

        if($perform=='debit')
        {
            $rp->debit = Request::get('reward_points');
            $rp->balance = $data['points'];
            if($rp->debit>$rp->balance)
            {
                Session::flash('error', 'Debit amount must be less or equal to balance amount.');

            }
            $rp->balance = $data['points'] - $rp->debit;

        }
        elseif ($perform=='credit'){

            $rp->credit = Request::get('reward_points');
            $rp->balance = $data['points'] + $rp->credit;
        }

        $rp->desc = Request::get('desc');
        $rp->save();

        Session::flash('success', 'Reward points updated successfully.');
        return redirect()->back();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{

        $customers = User::all();
        $customers_list = array();

        if(isset($customers) && sizeof($customers) > 0) {
            $customers_list[" "] = "Select Contact number";
            foreach ($customers as $customer) {
                if(trim($customer->contact_no) != "") {
                    $customers_list[$customer->id] = $customer->contact_no;
                }
            }
        } else {
            echo "No customers found. Please start taking customer contact numbers while ordering.";exit;
        }

        return view('rewardpoint.show',array('customers'=>$customers_list));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

        $rewardpoint = RewardPoint::find($id);


        return view('rewardpoints.index',['rewardpoint' => $rewardpoint]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request,$id)
	{
        $outlet_id = Session::get('outlet_session');
        $rewardpoint = RewardPoint::findOrFail($id);
        $rewardpoint->update($request->all());

        $editperform = Request::get('perform');

        $rewardpoint = RewardPoint::findOrFail($id);
        $d=$rewardpoint->debit;
        $c=$rewardpoint->credit;
        $rewardpoint->txn_date =Request::get('txn_date');
        $rewardpoint->user_id = Request::get('user_id');
        $rewardpoint->outlet_id = $outlet_id;
        $data = $this->getRewardPoints();

        if($editperform =='debit')
        {

            $rewardpoint->debit = Request::get('reward_points');

            if($d>0)
            {
                $rewardpoint->balance = $data['points']+ $d;
                if($rewardpoint->debit>$rewardpoint->balance)
                {
                    //abort(400, 'Debit amount must be less or equal to balance amount');
                    return Response::json(array(
                        'message' => 'Debit amount must be less or equal to balance amount.',
                        'status' => 'error',
                        'statuscode' => 400,
                        400));
                }
                $rewardpoint->credit=0;
                $rewardpoint->balance = $rewardpoint->balance  - $rewardpoint->debit;
            }
            elseif ($c>0)
            {
                $rewardpoint->balance = $data['points']- $c;

                if($rewardpoint->debit>$rewardpoint->balance)
                {
                    return Response::json(array(
                        'message' => 'Debit amount must be less or equal to balance amount.',
                        'status' => 'error',
                        'statuscode' => 400,
                        400));
                }
                $rewardpoint->credit=0;

                $rewardpoint->balance =$rewardpoint->balance - $rewardpoint->debit;
            }

        }


        else if ($editperform ='credit'){
            $rewardpoint->credit = Request::get('reward_points');
            if($d>0)
            {
                $rewardpoint->debit=0;
                $rewardpoint->balance = $data['points']+ $d;
                $rewardpoint->balance = $rewardpoint->balance  + $rewardpoint->credit;
            }
            elseif ($c>0)
            {
                $rewardpoint->debit=0;

                $rewardpoint->balance = $data['points']- $c;
                $rewardpoint->balance = $rewardpoint->balance  + $rewardpoint->credit;
            }


        }
        $rewardpoint->desc = Request::get('desc');

        $rewardpoint->save();

        return $rewardpoint;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $rewardpoint = RewardPoint::findOrFail($id);
        $rewardpoint->delete();

        return 204;
	}

    public function getRewardPoints() {

        $customer_id = Request::get('user_id');

        if($customer_id != "") {

            $outlet_id = Session::get('outlet_session');
            $c_reward_points = RewardPoint::where('outlet_id', $outlet_id)
                ->where('user_id',$customer_id)->sum('credit');
            $d_reward_points = RewardPoint::where('outlet_id', $outlet_id)
                ->where('user_id',$customer_id)->sum('debit');

            if($c_reward_points == 0){
                $data['status'] = 'success';
                $data['points'] = 0.00;
                return $data;
            } else {
                $total_reward_points = $c_reward_points - $d_reward_points;
                if($total_reward_points <= 0) {
                    $data['status'] = 'success';
                    $data['points'] = 0.00;
                    return $data;
                }else{
                    $data['status'] = 'success';
                    $data['points'] = $total_reward_points;
                    return $data;
                }
            }
        }else{
            $data['status'] = "error";
            $data['message'] ='Customer not selected';
            return $data;
        }
    }

    public function getTransaction() {

        $customer_id = Request::get('customer_id');

        if($customer_id != "") {

            $outlet_id = Session::get('outlet_session');
            $reward_points = RewardPoint::where('outlet_id', $outlet_id)
                ->where('user_id',$customer_id)->get();

            if(isset($reward_points) && sizeof($reward_points) > 0){
                $data['status'] = 'success';
                $data['history'] = $reward_points;
            } else {
                $data['status'] = 'error';
                $data['message'] = 'No Reward Points found for selected mobile number.';
            }
            return view('rewardpoint.history',array('data'=>$data));

        }else{
            $data['status'] = "error";
            $data['message'] ='Customer not selected.';
            return $data;
        }

    }

    public function viewRewardPoints(Request $request) {

        $outlet_id = Session::get('outlet_session');

        if ($request->ajax())
        {

            $input = Request::all();
            $response = array();

            $search = $input['sSearch'];

            $sort = $input['sSortDir_0'];
            $sortCol=$input['iSortCol_0'];
            $sortColName=$input['mDataProp_'.$sortCol];

            //sort by column
            if ( $sortColName == "contact" ) {
                $sort_field = 'users.mobile_number';
            } elseif ( $sortColName == "debit" ) {
                $sort_field = 'reward_points.debit';
            } elseif ( $sortColName == "credit" ) {
                $sort_field = 'reward_points.credit';
            } elseif ( $sortColName == "desc" ) {
                $sort_field = 'reward_points.desc';
            } elseif ( $sortColName == "date" ) {
                $sort_field = 'reward_points.txn_date';
            } else {
                $sort_field = 'reward_points.txn_date';
                $sort = 'DESC';
            }

            $total_colomns = $input['iColumns'];
            $search_col = '';$query_filter = '';

            for ( $j=0; $j<=$total_colomns-1; $j++ ) {

                if ( $j == 0 )continue;

                if ( isset($input['sSearch_'.$j]) && $input['sSearch_'.$j] != '' ) {

                    $search = $input['sSearch_'.$j];
                    $searchColName = $input['mDataProp_'.$j];
                    //echo $searchColName;exit();

                    if ( $searchColName == 'contact' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND users.mobile_number like '%$search%'";
                        } else {
                            $search_col = "users.mobile_number like '%$search%'";
                        }

                    } else if ( $searchColName == 'debit' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND reward_points.debit like '$search'";
                        } else {
                            $search_col = "reward_points.debit = '$search'";
                        }

                    } else if ( $searchColName ==  'desc' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND reward_points.desc like '%$search%'";
                        } else {
                            $search_col = "reward_points.desc like '%$search%'";
                        }

                    } else if ( $searchColName ==  'credit' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND reward_points.credit = '$search'";
                        } else {
                            $search_col = "reward_points.credit  = '$search'";
                        }

                    } else if ( $searchColName ==  'date' ) {

                        if(isset($date) && sizeof($date)>0)
                            $search = $date;

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND reward_points.txn_date like '%$search%'";
                        } else {
                            $search_col = "reward_points.txn_date like '%$search%'";
                        }

                    }

                }

            }

            if ( $search_col == '')$search_col = '1=1';

            $where = '(reward_points.outlet_id = '.$outlet_id.') AND';

            $total_records = RewardPoint::leftJoin('users','users.id','=','reward_points.user_id')
                ->select('users.mobile_number','reward_points.*')
                //->whereNull('reward_points.deleted_at')
                ->whereRaw(" $where ($search_col)")
                ->count();

            $reward_result = RewardPoint::leftJoin('users','users.id','=','reward_points.user_id')
                ->select('users.mobile_number','reward_points.*')
                //->whereNull('reward_points.deleted_at')
                ->whereRaw(" $where ($search_col)")
                ->take($input['iDisplayLength'])
                ->skip($input['iDisplayStart'])
                ->orderBy($sort_field, $sort)->get();


            if ( $total_records > 0 ) {

                $i = 0;
                foreach ( $reward_result as $ex ) {

                    $response['result'][$i]['DT_RowId'] = $ex->id;
                    $response['result'][$i]['check_col'] = "";
                    $response['result'][$i]['contact'] = $ex->mobile_number;
                    $response['result'][$i]['debit'] = $ex->debit;
                    $response['result'][$i]['credit'] = $ex->credit;
                    $response['result'][$i]['desc'] = $ex->desc;
                    $response['result'][$i]['date'] = $ex->txn_date;

                    $i++;
                }


            } else {
                $total_records = 0;
                $response['result'] = array();
            }

            $response['iTotalRecords'] = $total_records;
            $response['iTotalDisplayRecords'] = $total_records;
            $response['aaData'] = $response['result'];

            return json_encode($response);

        }

        $c_reward_points = RewardPoint::where('outlet_id', $outlet_id)->sum('credit');
        $d_reward_points = RewardPoint::where('outlet_id', $outlet_id)->sum('debit');
        $total_reward_points = $c_reward_points - $d_reward_points;

        return view('rewardpoint.view',array('customers'=>$total_reward_points,
            'credit'=>$c_reward_points,'debit'=>$d_reward_points,'total'=>$total_reward_points));

    }
}
