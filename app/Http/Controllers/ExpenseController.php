<?php namespace App\Http\Controllers;

use App\Expense;
use App\ExpenseCategory;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Outlet;
use App\OutletMapper;
use App\Owner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Zend\Http\Header\Date;

class ExpenseController extends Controller {

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
        $outlet_id = Session::get('outlet_session');

        $loggedinuserid=Auth::user()->id;
        $admin_id = Owner::menuOwner();

        $Outlet=Outlet::where('owner_id',$loggedinuserid)->get();
        $outlet_mappers = OutletMapper::getOutletMapperByOwnerId($loggedinuserid);

        $status = Expense::getStatus();
        $exp_category = ExpenseCategory::where('created_by',$admin_id)->lists('name','id');
        $exp_category[''] = 'Select Expense Category';

        //get outlet Users
        $user_list = OutletMapper::getOutletUsers($outlet_id);

       /* if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
            return view('expense.mobileindex',array('action'=>'add'));
        }else{*/
            return view('expense.index',array('user_list'=>$user_list,'action'=>'add','status'=>$status, 'exp_category'=>$exp_category));
        //}
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

    public function addCash()
    {

        $outlet_id = Session::get('outlet_session');

        $user_list = OutletMapper::getOutletUsers($outlet_id);

        return view('expense.addcash',array('action'=>'add','user_list'=>$user_list));
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $loggedinuserid=Auth::user()->id;

        $data=Input::all();

        $outlet_id = Session::get('outlet_session');

        $error="";
        $temp=0;
        if($data['expense_by']==''){
            $error = 'Select user form Expense by';
            return Redirect('/expenseApp')->withInput(Input::all())->with('error', $error);
        }
        else if($data['amount']<1 || $data['amount']==''){
            $error = 'Add Proper Amount';
            return Redirect('/expenseApp')->withInput(Input::all())->with('error', $error);
        }
        else if($data['description']==''){
            $error = 'Add Description';
            return Redirect('/expenseApp')->withInput(Input::all())->with('error', $error);
        }
        else{

            $outlet = Outlet::find($outlet_id);
            if ( isset($outlet) && !empty($outlet) ) {
                if ( !isset($outlet->authorised_users) || $outlet->authorised_users == '' || $outlet->authorised_users == 0 ) {
                    $error = "authorised user not selected for outlet";
                    return Redirect('/expenseApp')->withInput(Input::all())->with('error', $error);
                }
            }

            $type = Input::get('type');
            $exp_cat = Input::get('exp_category');

            $exp = new Expense();
            $exp->expense_for = $outlet_id;
            $exp->type = Input::get('type');
            $exp->created_by = $loggedinuserid;
            $exp->expense_by = Input::get('expense_by');
            $exp->expense_to = $outlet->authorised_users;
            $exp->amount = Input::get('amount');
            $exp->serversync = 0;
            $exp->description = Input::get('description');
            $exp->expense_date = Input::get('expense_date');
            $exp->category_id = $exp_cat;
            $note = Input::get('note');
            if(isset($note)) {
                $exp->notes = $note;
            }
            $status = Input::get('status');

            if ( $type == 'expense' ) {
                $exp->status = $status;
            } else {
                $exp->status = 'verified';
            }

            if($status == '2' || $status == '3'){
                $exp->verified_at = Carbon::now();
            }
            $exp->save();

            return Redirect('/expense/pending')->with('success', 'Expense added successfully');

        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request)
	{
        $user_id = Auth::id();
        $outlet_id = Session::get('outlet_session');

        if ($request->ajax())
        {

            $input = Input::all();
            $response = array();

            $search = $input['sSearch'];

            $sort = $input['sSortDir_0'];
            $sortCol=$input['iSortCol_0'];
            $sortColName=$input['mDataProp_'.$sortCol];

            //sort by column
            if ( $sortColName == "expense_by" ) {
                $sort_field = 'owners.user_name';
            } elseif ( $sortColName == "amount" ) {
                $sort_field = 'expense.amount';
            } elseif ( $sortColName == "desc" ) {
                $sort_field = 'expense.description';
            } elseif ( $sortColName == "status" ) {
                $sort_field = 'expense.status';
            } elseif ( $sortColName == "type" ) {
                $sort_field = 'expense.type';
            } elseif ( $sortColName == "expense_date" ) {
                $sort_field = 'expense.expense_date';
            } else {
                $sort_field = 'expense.expense_date';
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

                    if ( $searchColName == 'expense_by' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND owners.user_name like '%$search%'";
                        } else {
                            $search_col = "owners.user_name like '%$search%'";
                        }

                    } else if ( $searchColName == 'amount' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND expense.amount like '$search'";
                        } else {
                            $search_col = "expense.amount = '$search'";
                        }

                    } else if ( $searchColName ==  'desc' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND expense.description like '%$search%'";
                        } else {
                            $search_col = "expense.description like '%$search%'";
                        }

                    } else if ( $searchColName ==  'status' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND expense.status = '$search'";
                        } else {
                            $search_col = "expense.status = '$search'";
                        }

                    } else if ( $searchColName ==  'type' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND expense.type = '$search'";
                        } else {
                            $search_col = "expense.type = '$search'";
                        }

                    } else if ( $searchColName ==  'expense_date' ) {

                        if(isset($date) && sizeof($date)>0)
                            $search = $date;

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND expense.expense_date like '$search'";
                        } else {
                            $search_col = "expense.expense_date like '$search'";
                        }

                    }

                }

            }

            if(isset($input['day']) && sizeof($input['day'])>0){
                $date = $input['day'];
                if ( isset($search_col) && $search_col != '' ) {
                    $search_col .= " AND expense.expense_date like '$date'";
                } else {
                    $search_col = "expense.expense_date like '$date'";
                }
            }

            if(isset($input['month']) && sizeof($input['month'])>0){
                $first_date = $input['month'];
                $today_date = date('Y-m-d');
                if ( isset($search_col) && $search_col != '' ) {
                    $search_col .= " AND expense.expense_date >= '$first_date' AND expense.expense_date <= '$today_date'";
                } else {
                    $search_col = " expense.expense_date >= '$first_date' AND expense.expense_date <= '$today_date'";
                }
            }

            if ( $search_col == '')$search_col = '1=1';

            $where = '(expense.expense_to = '.$user_id.' or expense.created_by = '.$user_id.') AND expense_for = '.$outlet_id.' AND ';

            $total_records = Expense::leftJoin('owners','owners.id','=','expense.expense_by')
                                    ->leftJoin('outlets','outlets.id','=','expense.expense_for')
                                    ->select('owners.user_name','outlets.name','expense.*')
                                    ->whereNull('expense.deleted_at')
                                    ->whereRaw(" $where ($search_col)")
                                    ->count();

            $expense_result = Expense::leftJoin('owners','owners.id','=','expense.expense_by')
                                ->leftJoin('outlets','outlets.id','=','expense.expense_for')
                                ->select('owners.user_name','outlets.name','expense.*')
                                ->whereNull('expense.deleted_at')
                                ->whereRaw(" $where ($search_col)")
                                ->take($input['iDisplayLength'])
                                ->skip($input['iDisplayStart'])
                                ->orderBy($sort_field, $sort)->get();


            if ( $total_records > 0 ) {

                $i = 0;
                foreach ( $expense_result as $ex ) {

                    $response['result'][$i]['DT_RowId'] = $ex->id;
                    $response['result'][$i]['check_col'] = "";
                    $response['result'][$i]['expense_by'] = $ex->user_name;
                    $response['result'][$i]['amount'] = $ex->amount;
                    $response['result'][$i]['desc'] = $ex->description;
                    $response['result'][$i]['expense_date'] = $ex->expense_date;
                    $response['result'][$i]['type'] = ucwords($ex->type);

                    if ( $ex->type == 'expense' ) {

                        if( $ex->status == 'entered' ) {

                            if( $ex->expense_to == $user_id ) {

                                $response['result'][$i]['action'] = "<a href='javascript:void(0)' onclick='changeStatus(this,$ex->id)' data-type='verified' class='verify-link' title='Verify'><span class='zmdi zmdi-check-square zmdi-hc-fw'></span></a>".
                                    "&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick='changeStatus(this,$ex->id)' title='Pay' data-type='paid'><i class='fa fa-inr'></i></a>".
                                    '&nbsp;&nbsp;&nbsp;&nbsp;<a href="/expense/'.$ex->id.'/edit/" title="Edit"><span class="zmdi zmdi-edit" ></span></a>'.
                                    "&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick='changeStatus(this,$ex->id)' data-type='canceled' title='Cancel'><span class='zmdi zmdi-close'></span></a>";

                            } else {

                                $response['result'][$i]['action'] = '<a href="/expense/'.$ex->id.'/edit/" title="Edit"><span class="zmdi zmdi-edit" ></span></a>';

                            }

                        } else if ( $ex->status == 'verified' ) {

                            if( $ex->expense_to == $user_id ) {

                                $response['result'][$i]['action'] = "<a href='javascript:void(0)' onclick='changeStatus(this,$ex->id)' data-type='paid' title='Pay'><i class='fa fa-inr'></i></a>" .
                                    "&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick='changeStatus(this,$ex->id)' title='Cancel' data-type='canceled'><span class='zmdi zmdi-close'></span></a>";
                            } else {
                                $response['result'][$i]['action'] = '';
                            }

                        } else if ( $ex->status == 'paid' ) {

                            if( $ex->expense_to == $user_id ) {
                                $response['result'][$i]['action'] = "<a href='javascript:void(0)' data-type='canceled' onclick='changeStatus(this,$ex->id)' title='Cancel'><span class='zmdi zmdi-close'></span></a>";
                            } else {
                                $response['result'][$i]['action'] = '';
                            }

                        } else {
                            $response['result'][$i]['action'] = '';
                        }

                    } else {
                        $response['result'][$i]['action'] = '<a href="/cash/'.$ex->id.'/edit/" title="Edit"><span class="zmdi zmdi-edit" ></span></a>';
                    }


                    $response['result'][$i]['status'] = ucwords($ex->status);

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

        $day = Input::get('day');
        if(isset($day) && sizeof($day)>0) {
            $date = date('Y-m-d', strtotime(-$day . " days"));
            return view('expense.show', array('day' => $date));
        }

        $month = Input::get('month');
        if(isset($month) && sizeof($month)>0) {
            $date = date('Y-m-01');
            return view('expense.show', array('month' => $date));
        }

        return view('expense.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $expense = Expense::find($id);

        $loggedinuserid=Auth::user()->id;
        $admin_id = Owner::menuOwner();

        $outlet_mappers = OutletMapper::getOutletMapperByOwnerId($loggedinuserid);
        $select_outlets = ['' => 'Select Outlet'];
        $status = Expense::getStatus();
        $exp_category = ExpenseCategory::where('created_by',$admin_id)->lists('name','id');

        foreach($outlet_mappers as $om)
        {
            $select_outlets[$om->outlet_id] =Outlet::Outletbyid($om->outlet_id)[0]->name;
        }

        $outlet_id= $expense->expense_for;

        $user_list = OutletMapper::getOutletUsers($outlet_id);

        return view('expense.index',array('exp_category'=>$exp_category,'status'=>$status,'expense'=>$expense,'action'=>'edit','Outlet'=>$select_outlets,'user_list'=>$user_list));
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function editCash($id)
    {
        $expense = Expense::find($id);
        $user_list = OutletMapper::getOutletUsers($expense->expense_for);

        return view('expense.addcash',array('expense'=>$expense,'action'=>'edit','user_list'=>$user_list));
    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function update($id){

        $loggedinuserid=Auth::user()->id;
        $outlet_id = Session::get('outlet_session');

        $exp = Expense::find($id);

        $outlet = Outlet::find($outlet_id);
        if ( isset($outlet) && !empty($outlet) ) {
            if ( !isset($outlet->authorised_users) || $outlet->authorised_users == '' || $outlet->authorised_users == 0 ) {
                $error = "authorised user not selected for outlet";
                return Redirect('/expense/'.$id.'/edit')->withInput(Input::all())->with('error', $error);
            }
        }

        $exp->expense_for = $outlet_id;
        $exp->created_by = $loggedinuserid;
        $exp->expense_by = Input::get('expense_by');
        $exp->expense_to = $outlet->authorised_users;
        $exp->amount = Input::get('amount');
        $exp->description = Input::get('description');
        $exp->expense_date = Input::get('expense_date');
        $exp->category_id = Input::get('exp_category');
        $exp->serversync = 0;
        $note = Input::get('note');
        if(isset($note)) {
            $exp->notes = $note;
        }
        $status = Input::get('status');
        $type = Input::get('type');

        if ( $type == 'expense' ) {
            $exp->status = $status;
        }

        if($status == '2' || $status == '3'){
            $exp->verified_at = Carbon::now();
        }
        $exp->save();

        return Redirect('/expense/pending')->with('success', 'Expense updated successfully');

    }

	public function changeStatus($id)
	{
        $type = Input::get('type');
        $result = Expense::where('id', $id)->update(['status' => $type,'serversync'=>0]);
        return 'success';
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
	    //print_r($id);exit;
        Expense::where('id',$id)->delete();
        return Redirect('/expense/pending')->with('success', 'Expense Deleted successfully');
        //return view('expense.show',array('expense'=>$expense));
	}

    public function getUsersByOutlet(){
        $admin = Owner::menuOwner();
        $user_id = Auth::user()->id;
        if($admin == $user_id) {
            $outlet_id = Input::get('outlet_id');
            $userlist = Owner::leftJoin('outlets_mapper', 'outlets_mapper.owner_id', '=', 'owners.id')
                ->where('outlets_mapper.outlet_id', '=', $outlet_id)->get();
            //->lists('owners.user_name','outlets_mapper.owner_id');
            $list = array();
            foreach ($userlist as $users) {
                $list['user_id'][] = $users->owner_id;
                $list['user_name'][] = $users->user_name;
            }
        }else{
            $list['user_id'][] = $user_id;
            $list['user_name'][] = Auth::user()->user_name;
        }
        return $list;
    }

    public function getAuthorisedUsersByOutlet(){

        $outlet_id= Input::get('outlet_id');
        $auth_users_id=Outlet::select('authorised_users')
                    ->where('id','=',$outlet_id)->first();

        if($auth_users_id==null)
            return;
        $ids=$auth_users_id[0]->authorised_users;
        $integerIDs = array_map('intval', explode(',', $ids));
        $list=array();
        for($i=0;$i<sizeof($integerIDs);$i++){
            $data=Owner::select('id','user_name')->where('id','=',$integerIDs[$i])->get();
            $list['user_id'][]=$integerIDs[$i];
            $list['user_name'][]=$data[0]->user_name;
        }
        return $list;
    }

    public function categoryIndex(){

        $admin_id = Owner::menuOwner();
        $category_list = ExpenseCategory::where('created_by',$admin_id)->get();

        return view('expensecategory.index',array('expense_category'=>$category_list));

    }

    public function categoryForm(){

        return view('expensecategory.form',array('action'=>'add'));

    }

    public function expenseCategoryStore(){

        $user_id = Auth::id();
        $category_name = Input::get('category_name');
        $getCategory = ExpenseCategory::where('created_by',$user_id)->where('name',$category_name)->get();

        if(isset($getCategory) && sizeof($getCategory)){
            return Redirect::back()->with('error','Category with same name is already available, please try different name.');
        }

        $save_continue = Input::get('saveContinue');
        $expenseCategory = new ExpenseCategory();
        $expenseCategory->name = $category_name;
        $expenseCategory->created_by = $user_id;
        $expenseCategory->updated_by = $user_id;
        $result = $expenseCategory->save();

        if($result) {
            if (isset($save_continue) && $save_continue == 'true') {
                return view('expensecategory.form', array('action' => 'add', 'success' => 'Expense category added successfully'));
            } else {
                return Redirect('/expense-category-index')->with('success', 'Expense category added successfully');
            }
        }
    }

    public function destroyExpenseCategory($id){
        // ExpenseCategory::where('id',$id)->delete();
        $deleteExpenseCategory = ExpenseCategory::find($id);
        $deleteExpenseCategory->delete();
        return Redirect('/expense-category-index')->with('success', 'Expense category Deleted successfully');
    }

    public function editExpenseCategory($id){

        $expense_category = ExpenseCategory::find($id);
        return view('expensecategory.form',array('expense_category'=>$expense_category,'action'=>'edit','category_id'=>$id));
    }

    public function expenseCategoryUpdate(){

        $category_name = Input::get('category_name');
        $category_id = Input::get('category_id');

        $expenseCategory = ExpenseCategory::find($category_id);
        $expenseCategory->name = $category_name;
        $expenseCategory->updated_by = Auth::id();
        $result = $expenseCategory->save();

        if($result) {
            return Redirect('/expense-category-index')->with('success', 'Expense category updated successfully');
        }
    }

    public function updateNote($id){

        $note = Input::get('note');
        $result = Expense::where('id', $id)->update(['notes' => $note]);
        return 'success';

    }

}
