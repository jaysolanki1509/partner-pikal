<?php namespace App\Http\Controllers;

use App\Customer;
use App\EmailConfig;
use App\EmailTemplate;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\order_details;
use App\OrderItem;
use App\Owner;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Swift_Mailer;
use Swift_SmtpTransport;

class CustomersController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['home']]);
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$owner_id = Owner::menuOwner();

		$outlet_id = Session::get('outlet_session');

		if ($request->ajax())
		{
			$input = Request::all();
			$response = array();

			$search = $input['sSearch'];

			$sort = $input['sSortDir_0'];
			$sortCol=$input['iSortCol_0'];
			$sortColName=$input['mDataProp_'.$sortCol];

			$sort_field = 'menus.menu_title_id';
			//sort by column
			if ( $sortColName == "name" ) {
				$sort_field = 'users.first_name';
			} elseif ( $sortColName == "mobile" ) {
				$sort_field = 'users.mobile_number';
			} elseif ( $sortColName == "email" ) {
				$sort_field = 'users.email';
			} elseif ( $sortColName == "last_visit" ) {
				$sort_field = 'o.table_end_date';
			} elseif ( $sortColName == "visits" ) {
				$sort_field = 'visits';
			} elseif ( $sortColName == "avg_bill" ) {
				$sort_field = 'avg_bill';
			} else {
				$sort_field = 'o.table_end_date';
				$sort = 'DESC';
			}

			$total_colomns = $input['iColumns'];
			$search_col = '';$query_filter = '';
            $visit_search = 0; $visit_search_val = '';

			for ( $j=0; $j<=$total_colomns-1; $j++ ) {

				if ( $j == 0 )continue;


				if ( isset($input['sSearch_'.$j]) && $input['sSearch_'.$j] != '' ) {

					$search = $input['sSearch_'.$j];
					$searchColName = $input['mDataProp_'.($j-1)];
                    if ($searchColName == "visits"){
                        $visit_search = 1;
                        $visit_search_val = $search;
                    }
                    if ( $j == 5 )continue;

					if ( $searchColName == 'name' ) {

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND users.first_name like '%$search%'";
						} else {
							$search_col = "users.first_name like '%$search%'";
						}

					} else if ( $searchColName == 'mobile') {

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND users.mobile_number like '%$search%'";
						} else {
							$search_col = "users.mobile_number like '%$search%'";
						}

					} else if ( $searchColName == 'visits') {

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND visits like '%$search%'";
						} else {
							$search_col = "visits like '%$search%'";
						}

					} else if ( $searchColName == 'last_visit') {

                        $from = $search." 00:00:00";
                        $to = $search." 23:59:59";

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND o.table_end_date >= '$from' AND o.table_end_date <= '$to'";
                        } else {
                            $search_col = "o.table_end_date >= '$from' AND o.table_end_date <= '$to'";
                        }

					} else if ( $searchColName == 'email') {

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND users.email = '%$search%'";
						} else {
							$search_col = "users.email = '%$search%'";
						}

					}

				}

			}

			//echo $search_col;exit;

			if ( $search_col == '')$search_col = '1=1';

			$where = 'o.outlet_id ='. $outlet_id .' AND users.mobile_number != 0 AND';

			$total_records = Customer::leftjoin('orders as o','o.user_id','=','users.id')
                                    ->selectRaw('o.*, count(o.order_unique_id) as visits, users.*')
                                    ->whereRaw(" $where ($search_col)")
									->groupBy('id');

            $total_records = $total_records->havingRaw('(visits like'.' \'%'.$visit_search_val.'%\')')->get();

            /*if($visit_search == 1 ){
                $total_records_final = array();
                foreach ($total_records as $record){
                    if($record->visits == $visit_search_val){
                     array_push($total_records_final,$record);
                    }
                }

                $total_records = $total_records_final;
            }*/

			$customer_result = Customer::leftjoin('orders as o','o.user_id','=','users.id')
										->whereRaw(" $where ($search_col)")
										->take($input['iDisplayLength'])
										->skip($input['iDisplayStart'])
										->orderBy($sort_field, $sort)
                                        ->selectRaw('o.*, count(o.order_unique_id) as visits, sum(totalprice) as totalbill, sum(totalprice)/count(o.order_unique_id) as avg_bill ,users.*')
										->groupBy('id');

            $customer_result = $customer_result->havingRaw('(visits like'.' \'%'.$visit_search_val.'%\')')->get();

            /*if($visit_search == 1 ){
                $customer_result_final = array();
                foreach ($customer_result as $cresult){
                    if($cresult->visits == $visit_search_val){
                        array_push($customer_result_final,$cresult);
                    }
                }

                $customer_result = $customer_result_final;
            }
            //print_r($customer_result);exit;*/

			if ( sizeof($total_records) > 0 ) {

				$i = 0;
				foreach ( $customer_result as $cust ) {

					$response['result'][$i]['DT_RowId'] = $cust->id;
					$response['result'][$i]['check_col'] = "<input class='checkbox1' name='sel_box' id='$cust->id' onclick=selectRow('$cust->id') type='checkbox' />";;

					$response['result'][$i]['name'] = $cust->first_name." ".$cust->last_name;
					$response['result'][$i]['mobile'] = $cust->mobile_number;
					$response['result'][$i]['email'] = $cust->email;

					$response['result'][$i]['visits'] = $cust->visits;
					$response['result'][$i]['avg_bill'] = number_format($cust->totalbill/($cust->visits!=0?$cust->visits:1),2);
					$response['result'][$i]['last_visit'] = date("Y-m-d", strtotime($cust->table_end_date));

					$response['result'][$i]['action'] = '<a href="/customer/'.$cust->id.'/show" title="Detail"><span class="zmdi zmdi-file-text" ></span></a>';

					$i++;
				}

			} else {
				$total_records = 0;
				$response['result'] = array();
			}

			$response['iTotalRecords'] = sizeof($total_records);
			$response['iTotalDisplayRecords'] = sizeof($total_records);
			$response['aaData'] = $response['result'];

			return json_encode($response);

		}

        $user_id = Owner::menuOwner();
        //$email_template = EmailTemplate::where('created_by',$user_id)->pluck("name","id");
        $email_template[""] = "Select Template";

		return view('customers.index',array('templates'=>$email_template));
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
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$outlet_id = Session::get('outlet_session');

		$orders = order_details::where('outlet_id',$outlet_id)
								->where('user_id',$id)
								->orderBy('table_end_date','DESC')
								->get();

		$customer = Customer::find($id);
		//print_r($orders);exit;
		return view('customers.show',array('orders'=>$orders,'customer'=>$customer));
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

	public function customerItemWiseSale() {

		$outlet_id = Session::get('outlet_session');
		$customer_id = Request::get('customer_id');

		$now = Carbon::yesterday()->endOfDay();
		$first_dom = Carbon::yesterday()->startOfMonth();

		$items = array();

		$items = OrderItem::join("orders", "orders.order_id", "=", "order_items.order_id")
						->select('order_items.item_name as item',DB::raw('ifnull(sum(order_items.item_price*order_items.item_quantity),0) as count'))
						->where('orders.outlet_id', '=', $outlet_id)
						->where('orders.user_id',$customer_id)
						->where('orders.cancelorder', '!=', 1)
						->where('orders.invoice_no',"!=",'')
						->groupBy('order_items.item_id')
						->get();

		$i = 0;
		$final_pichart_data = array();
		$result = array();

		foreach ( $items as $itm){
			$final_pichart_data[$i]['name'] = $itm->item;
			$final_pichart_data[$i++]['y'] = $itm->count;
		}
		if(sizeof($final_pichart_data)>0) {
			$result['chart'] = $final_pichart_data;
		}else{
			$final_pichart_data[0]['name'] = 'No Item Found';
			$final_pichart_data[0]['y'] = 0;
			$result['chart'] = $final_pichart_data;
		}

		return $result;

	}

	public function getCustomerEmail(){

	    $ids = Request::get("ids");
        $emails = array();
        $no_email = 0;
        foreach ($ids as $key=>$id){
            $customer = Customer::find($id);
            if(isset($customer) && sizeof($customer)>0){
                if(isset($customer->email) && sizeof($customer->email) && trim($customer->email)!="") {
                    array_push($emails,$customer->email);
                }else{
                    $no_email++;
                }
            }
        }
        $data['no_email']= $no_email;
        $data['email_array']= $emails;

        return $data;

    }

    public function sendCustomerMail(){

        $customer = explode(",",Request::get("customer"));
        $template = Request::get("template");

        $user_id = Owner::menuOwner();

        $email_config = EmailConfig::where("user_id",$user_id)->first();
        $email_template = EmailTemplate::find($template);

        $data['template'] = $email_template;
        $data['subject'] = $email_template->subject;
        $data['email_config'] = $email_config;

        $result = 0;
        if(isset($customer) && sizeof($customer)>0) {
            // Backup your default mailer
            $backup = Mail::getSwiftMailer();

            // Setup your gmail mailer
            $transport = Swift_SmtpTransport::newInstance($email_config->outgoing_host, $email_config->outgoing_port, $email_config->outgoing_security);
            $transport->setUsername("dev@savitriya.com");
            $transport->setPassword("Moin@007");
            //$transport->setUsername($email_config->email_address);
            //$transport->setPassword(Crypt::decrypt($email_config->email_password));
            $gmail = new Swift_Mailer($transport);
            // Set the mailer as gmail
            Mail::setSwiftMailer($gmail);

            $outlet_id = Session::get('outlet_session');
            foreach ($customer as $index => $email) {
                $data["send_to"] = $email;
                $data["outlet_id"] = $outlet_id;
                $result = Mail::queue('emails.customer', array('data' => $data), function ($message) use ($data, $email) {
                    $message->from($data['email_config']->email_address, $data['email_config']->name);
                    $message->to($email);
                    $message->returnPath("np@savitriya.com");
                    $message->subject($data['subject']);
                });
            }

            // Restore your original mailer
            Mail::setSwiftMailer($backup);
        }

        if($result > 0){
            return "success";
        }else{
            return "error";
        }


    }

}
