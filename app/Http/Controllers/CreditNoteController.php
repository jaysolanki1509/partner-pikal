<?php namespace App\Http\Controllers;

use App\CreditNote;
use App\CreditNoteItems;
use App\Customer;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Menu;
use App\order_details;
use App\OutletSetting;
use App\Outlet;
use App\Owner;
use App\State;
use App\User;
use App\Utils;


class CreditNoteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $credit_notes = CreditNote::all();
        $records = []; $i = 0;
        foreach ($credit_notes as $note){

            $records[$i]['id'] = $note->id;
            $records[$i]['cn_date'] = $note->date_of_issue;
            $records[$i]['cn_no'] = $note->cn_no;
            $records[$i]['reference_no'] = $note->reference;
            $user = Customer::find($note->user_id);
            $records[$i]['customer_name'] = $user->first_name;
            $records[$i]['invoice_no'] = $note->invoice_no;
            $records[$i]['status'] = $note->status;
            $records[$i]['amount'] = $note->total;
            $records[$i]['balance'] = $note->available_credit;
            $i++;
        }

        return view('creditnotes.index', array('data'=>$records));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($order_id)
	{
        $outlet_id = Session::get('outlet_session');

        $order_detail = $orders = order_details::join("order_items","order_items.order_id","=","orders.order_id")
                                            ->leftJoin('users as u','u.id','=','orders.user_id')
                                            ->select('orders.*','order_items.item_quantity as qty','order_items.item_name as item_name',
                                                    'order_items.item_price as item_price','order_items.item_total as item_total',
                                                    'order_items.item_id as item_id','order_items.tax_slab as itm_tax','order_items.item_discount as itm_discount',
                                                    'u.first_name as fname','u.last_name as lname')
                                            ->where('orders.order_id',$order_id)
                                            ->get();

        $order_array = array();
        if ( isset($order_detail) && sizeof($order_detail) > 0 ) {

            if ( $order_detail[0]->outlet_id != $outlet_id ) {
                echo "Provided Invoice number is not valid, Please try valid invoice number.";exit;
            }

            $i = 0;
            foreach ( $order_detail as $ord ) {

                if ( $i == 0 ) {

                    $order_array['id'] = $ord->order_id;
                    $order_array['inv_no'] = $ord->invoice_no;
                    $order_array['sub_total'] = $ord->totalcost_afterdiscount;
                    $order_array['total'] = $ord->totalprice;
                    $order_array['customer_name'] = $ord->fname." ".$ord->lname;
                    $order_array['customer_id'] = $ord->user_id;

                }

                $order_array['item'][$i]['name'] = $ord->item_name;
                $order_array['item'][$i]['qty'] = $ord->qty;
                $order_array['item'][$i]['price'] = $ord->item_price;
                $order_array['item'][$i]['total'] = number_format($ord->item_total,2);

                //check itemwise discount
                if ( isset($ord->itm_discount) && $ord->itm_discount != '') {

                    $discount = json_decode($ord->itm_discount);

                    if ( isset($discount) && sizeof($discount) > 0 ) {

                        $order_array['item'][$i]['discount']['type'] = $discount->disc_type;
                        $order_array['item'][$i]['discount']['value'] = $discount->disc_value;

                    }

                } else {
                    $order_array['item'][$i]['discount'] = '';
                }

                //check itemwise tax
                if ( isset($ord->itm_tax) && $ord->itm_tax != '') {
                    $tax = json_decode($ord->itm_tax);

                    if ( isset($tax) && sizeof($tax) > 0 ) {

                        foreach ( $tax as $key=>$tx_arr ) {

                            $order_array['item'][$i]['slab'] = $key;

                            $j=0;
                            foreach ( $tx_arr as $tx ) {
                                $order_array['item'][$i]['taxes'][$j]['tx_name'] = $tx->taxname;
                                $order_array['item'][$i]['taxes'][$j]['tx_parc'] = $tx->taxparc;
                                $j++;
                            }
                        }
                    }

                } else {
                    $order_array['item'][$i]['slab'] = "";
                }

                $i++;
            }
        }

        //state list
        $states = State::all();
        $state_list[""] = "Select State";
        foreach ($states as $state){
            $state_list[$state->id] = '['.$state->state_code.'] '.$state->name;
        }

        $itemwise_tax = OutletSetting::checkAppSetting($outlet_id,'itemWiseTax');
        $itemwise_discount = OutletSetting::checkAppSetting($outlet_id,'itemWiseDiscount');
        //$owner = Owner::menuOwner();
        //$menu = Menu::where('created_by',$owner)->pluck('item','id');

        return view("creditnotes.form",array(
                                            'action'=>"add",
                                            'itemwise_tax'=>$itemwise_tax,
                                            'itemwise_discount'=>$itemwise_discount,
                                            'order_arr'=>$order_array,
                                            'order_id'=>$order_id,
                                            'states'=>$state_list
                                            )
                    );

    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
        print_r("hrer");exit;
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

	public function getCraditNote(){

	    $cn_id = Request::get("cn_id");
        $creditNote = CreditNote::find($cn_id);

        $data = [];
        $outlet_id = Session::get('outlet_session');
        $outlet = Outlet::find($outlet_id);
        $order = order_details::find($creditNote->order_id);
        $state = State::find($outlet->state_id);
        $user = Customer::find($creditNote->user_id);
        $credited_items = CreditNoteItems::where('cn_id',$creditNote->id)->get();

        $data['outlet_name'] = $outlet->name;
        $data['outlet_address'] = $outlet->address;
        $data['status'] = $creditNote->status;
        $data['cn_no'] = $creditNote->cn_no;
        $data['credit_date'] = $creditNote->date_of_issue;
        $data['invoice_no'] = $creditNote->invoice_no;
        $data['invoice_date'] = Carbon::parse($order->table_start_date)->format('Y-m-d');
        $data['state'] = $state->name;
        $data['cust_name'] = $user->first_name." ".$user->last_name;
        $data['item_arr'] = $credited_items;
        $data['taxes'] = json_decode($creditNote->taxes);
        $data['total'] = $creditNote->total;
        $data['sub_total'] = $creditNote->sub_total;
        $data['available_credit'] = $creditNote->available_credit;
        $data['amount_words'] = ucwords(Utils::getIndianCurrency($creditNote->available_credit));

        return view('creditnotes.printView', array('data'=>$data));

    }

}
