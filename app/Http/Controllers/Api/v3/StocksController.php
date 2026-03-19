<?php namespace App\Http\Controllers\Api\v3;

use App\Ingredients;
use App\ItemRequest;
use App\Location;
use App\Menu;
use App\MenuTitle;
use App\order_details;
use App\OrderItem;
use App\Owner;
use App\RecipeDetails;
use App\Stock;
use App\StockAge;
use App\StockHistory;
use App\StockLevel;
use App\Unit;
use App\Utils;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class StocksController extends Controller
{

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->beforeFilter('csrf', ['on' => '']);

    }

    public function StockTransfer() {

        $user_id = Auth::id();
        $admin_id = Owner::menuOwner();

        //transfer stock when click submit
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

            $from_loc = Input::get('from_loc');
            $to_loc = Input::get('to_loc');
            $trans_date = Input::get('trans_date');
            $trans_qty = Input::get('trans_qty');
            $cat_id = Input::get('cat_id');
            $item_id = Input::get('item_id');
            $unit_id = Input::get('unit_id');
            $price = Input::get('price');
            //print_r($item_id);exit;
            if ( isset($item_id) && sizeof($item_id) > 0 ) {

                $error = false;
                DB::beginTransaction();
                $transaction_id = uniqid();
                for($i=0;$i<sizeof($item_id);$i++) {

                    $itm_id = $item_id[$i];
                    $qty = $tr_qty = $trans_qty[$i];
                    $ut_id = $unit_id[$i];
                    $item_price = isset($price[$i])?$price[$i]:0;
                    if ($qty == '') {
                        continue;
                    }

                    try {
                        /*decrease item from location*/
                        $from_loc_stock = Stock::where('location_id', $from_loc)
                            ->where('item_id', $itm_id)
                            ->first();
                        $menu = Menu::join('unit','unit.id','=','menus.unit_id')->where('menus.id',$itm_id)->first();

                        if ( isset($menu->secondary_units) && $menu->secondary_units != '') {
                            $sec_unit = json_decode($menu->secondary_units);
                            if ( isset($sec_unit) && sizeof($sec_unit) > 0 ) {
                                foreach( $sec_unit as $key=>$tran_qty ) {
                                    if ( $key == $ut_id) {
                                        $qty *= $tran_qty;
                                    }
                                }
                            }
                        }

                        if (isset($from_loc_stock) && sizeof($from_loc_stock) > 0) {

                            $remain_qty = $from_loc_stock->quantity - $qty;
                            $stock = Stock::find($from_loc_stock->id);
                            $stock->quantity = $remain_qty;
                            $stock->updated_by = $user_id;
                            $stock->updated_at = $trans_date;
                            $from_loc_result = $stock->save();

                        } else {

                            $stk_add = new Stock();
                            $stk_add->item_id = $itm_id;
                            $stk_add->location_id = $from_loc;
                            $stk_add->created_by = $user_id;
                            $stk_add->updated_by = $user_id;
                            $stk_add->quantity = 0 - $qty;
                            $stk_add->created_at = $trans_date;
                            $stk_add->updated_at = $trans_date;
                            $from_loc_result = $stk_add->save();
                        }

                        $for_loc_stock = Stock::where('location_id', $to_loc)
                            ->where('item_id', $itm_id)
                            ->first();

                        if (isset($for_loc_stock) && sizeof($for_loc_stock) > 0) {

                            $added_qty = $for_loc_stock->quantity + $qty;
                            $for_loc_stock->quantity = $added_qty;
                            $for_loc_stock->updated_by = $user_id;
                            $for_loc_stock->updated_at = $trans_date;
                            $from_loc_result = $for_loc_stock->save();

                        } else {

                            $stk_add = new Stock();
                            $stk_add->item_id = $itm_id;
                            $stk_add->location_id = $to_loc;
                            $stk_add->created_by = $user_id;
                            $stk_add->updated_by = $user_id;
                            $stk_add->quantity = $qty;
                            $stk_add->created_at = $trans_date;
                            $stk_add->updated_at = $trans_date;
                            $from_loc_result = $stk_add->save();
                        }

                        if ($from_loc_result) {

                            $stock_history = new StockHistory();
                            $stock_history->transaction_id = '';
                            $stock_history->from_location = $from_loc;
                            $stock_history->to_location = $to_loc;
                            $stock_history->item_id = $itm_id;
                            $stock_history->type = 'remove';
                            $stock_history->quantity = $qty;
                            $stock_history->reason = 'transfer';
                            $stock_history->created_by = $user_id;
                            $stock_history->updated_by = $user_id;
                            $stock_history->created_at = $trans_date;
                            $stock_history->updated_at = $trans_date;
                            $result1 = $stock_history->save();

                            if ( $result1 ) {

                                $stock_history = new StockHistory();
                                $stock_history->transaction_id = '';
                                $stock_history->from_location = $from_loc;
                                $stock_history->to_location = $to_loc;
                                $stock_history->item_id = $itm_id;
                                $stock_history->type = 'add';
                                $stock_history->quantity = $qty;
                                $stock_history->reason = 'transfer';
                                $stock_history->created_by = $user_id;
                                $stock_history->updated_by = $user_id;
                                $stock_history->created_at = $trans_date;
                                $stock_history->updated_at = $trans_date;
                                $result2 = $stock_history->save();

                                if ( !$result2 ) {
                                    $error = true;
                                    DB::rollBack();
                                    return Response::json(array(
                                        'status' => 'error',
                                        'statuscode' => 434,
                                        434));
                                }

                            } else {

                                $error = true;
                                DB::rollBack();
                                return Response::json(array(
                                    'status' => 'error',
                                    'statuscode' => 434,
                                    434));

                            }

                            /*$get_stock = StockAge::where('item_id', $itm_id)
                                ->where('location_id', $from_loc)
                                ->orderby('expiry_date', 'asc')
                                ->get();

                            if (isset($get_stock) && sizeof($get_stock) > 0) {
                                $remain_stk = 0;
                                $first_time = true;
                                foreach ($get_stock as $get_stk) {

                                    //if stock is less than first batch stock
                                    if ($get_stk->quantity > $qty && $first_time == true) {

                                        $get_stk->quantity = $get_stk->quantity - $qty;
                                        $get_stk->updated_by = $user_id;
                                        $get_stk->updated_at = $trans_date;
                                        $get_stk->save();

                                        $stock_history = new StockHistory();
                                        $stock_history->transaction_id = $get_stk->transaction_id;
                                        $stock_history->from_location = $from_loc;
                                        $stock_history->to_location = $to_loc;
                                        $stock_history->item_id = $itm_id;
                                        $stock_history->type = 'remove';
                                        $stock_history->quantity = $qty;
                                        $stock_history->reason = 'transfer';
                                        $stock_history->created_by = $user_id;
                                        $stock_history->updated_by = $user_id;
                                        $stock_history->created_at = $trans_date;
                                        $stock_history->updated_at = $trans_date;
                                        $result1 = $stock_history->save();

                                        if (isset($get_stk->transaction_id) && $get_stk->transaction_id != '') {

                                            $add_stock = StockAge::where('item_id', $itm_id)
                                                ->where('location_id', $to_loc)
                                                ->where('transaction_id', $get_stk->transaction_id)->first();

                                            if (isset($add_stock) && sizeof($add_stock) > 0) {
                                                $add_stock->quantity = $add_stock->quantity + $qty;
                                                $add_stock->updated_at = $trans_date;
                                                $add_stock->save();
                                            } else {

                                                $add_stock = new StockAge();
                                                $add_stock->transaction_id = $get_stk->transaction_id;
                                                $add_stock->item_id = $itm_id;
                                                $add_stock->location_id = $to_loc;
                                                $add_stock->quantity = $qty;
                                                $add_stock->created_at = $trans_date;
                                                $add_stock->updated_at = $trans_date;
                                                $add_stock->created_by = $user_id;
                                                $add_stock->updated_by = $user_id;
                                                $add_stock->save();
                                            }

                                        } else {

                                            $add_stock = new StockAge();
                                            $add_stock->item_id = $itm_id;
                                            $add_stock->location_id = $to_loc;
                                            $add_stock->quantity = $qty;
                                            $add_stock->created_at = $trans_date;
                                            $add_stock->updated_at = $trans_date;
                                            $add_stock->created_by = $user_id;
                                            $add_stock->updated_by = $user_id;
                                            $add_stock->save();

                                        }

                                        $stock_history = new StockHistory();
                                        $stock_history->transaction_id = $get_stk->transaction_id;
                                        $stock_history->from_location = $from_loc;
                                        $stock_history->to_location = $to_loc;
                                        $stock_history->item_id = $itm_id;
                                        $stock_history->type = 'add';
                                        $stock_history->quantity = $qty;
                                        $stock_history->reason = 'transfer';
                                        $stock_history->created_by = $user_id;
                                        $stock_history->updated_by = $user_id;
                                        $stock_history->created_at = $trans_date;
                                        $stock_history->updated_at = $trans_date;
                                        $result1 = $stock_history->save();

                                        break;

                                    } else {

                                        if ($remain_stk > 0 || $first_time == true) {
                                            $first_time = false;


                                            if ($get_stk->quantity <= $qty) {

                                                $avail_stock = $get_stk->quantity;
                                                $qty = $qty - $get_stk->quantity;
                                                $remain_stk = $qty;

                                                $get_stk->quantity = $get_stk->quantity - $get_stk->quantity;
                                                $get_stk->updated_by = $user_id;
                                                $get_stk->updated_at = $trans_date;
                                                $get_stk->save();

                                                if (isset($get_stk->transaction_id) && $get_stk->transaction_id != '') {

                                                    $add_stock = StockAge::where('item_id', $itm_id)
                                                        ->where('location_id', $to_loc)
                                                        ->where('transaction_id', $get_stk->transaction_id)->first();

                                                    if (isset($add_stock) && sizeof($add_stock) > 0) {
                                                        $add_stock->quantity = $add_stock->quantity + $avail_stock;
                                                        $add_stock->updated_at = $trans_date;
                                                        $add_stock->save();
                                                    } else {

                                                        $add_stock = new StockAge();
                                                        $add_stock->transaction_id = $get_stk->transaction_id;
                                                        $add_stock->item_id = $itm_id;
                                                        $add_stock->location_id = $to_loc;
                                                        $add_stock->quantity = $avail_stock;
                                                        $add_stock->created_at = $trans_date;
                                                        $add_stock->updated_at = $trans_date;
                                                        $add_stock->created_by = $user_id;
                                                        $add_stock->updated_by = $user_id;
                                                        $add_stock->save();

                                                    }

                                                } else {

                                                    $add_stock = new StockAge();
                                                    $add_stock->item_id = $itm_id;
                                                    $add_stock->location_id = $to_loc;
                                                    $add_stock->quantity = $avail_stock;
                                                    $add_stock->created_at = $trans_date;
                                                    $add_stock->updated_at = $trans_date;
                                                    $add_stock->created_by = $user_id;
                                                    $add_stock->updated_by = $user_id;
                                                    $add_stock->save();

                                                }

                                                $stock_history = new StockHistory();
                                                $stock_history->transaction_id = $get_stk->transaction_id;
                                                $stock_history->from_location = $from_loc;
                                                $stock_history->to_location = $to_loc;
                                                $stock_history->item_id = $itm_id;
                                                $stock_history->type = 'remove';
                                                $stock_history->quantity = $avail_stock;
                                                $stock_history->reason = 'transfer';
                                                $stock_history->created_by = $user_id;
                                                $stock_history->updated_by = $user_id;
                                                $stock_history->created_at = $trans_date;
                                                $stock_history->updated_at = $trans_date;
                                                $result1 = $stock_history->save();

                                                if ($result1) {

                                                    $stock_history1 = new StockHistory();
                                                    $stock_history1->transaction_id = $get_stk->transaction_id;
                                                    $stock_history1->from_location = $from_loc;
                                                    $stock_history1->to_location = $to_loc;
                                                    $stock_history1->item_id = $itm_id;
                                                    $stock_history1->type = 'add';
                                                    $stock_history1->quantity = $avail_stock;
                                                    $stock_history1->reason = 'transfer';
                                                    $stock_history1->created_by = $user_id;
                                                    $stock_history1->updated_by = $user_id;
                                                    $stock_history1->created_at = $trans_date;
                                                    $stock_history1->updated_at = $trans_date;
                                                    $result2 = $stock_history1->save();

                                                } else {
                                                    $error = true;
                                                    DB::rollBack();
                                                    return Response::json(array(
                                                        'status' => 'error',
                                                        'statuscode' => 434,
                                                        200));
                                                }

                                            } else {

                                                $get_stk->quantity = $get_stk->quantity - $qty;
                                                $get_stk->updated_by = $user_id;
                                                $get_stk->updated_at = $trans_date;
                                                $get_stk->save();

                                                if (isset($get_stk->transaction_id) && $get_stk->transaction_id != '') {

                                                    $add_stock = StockAge::where('item_id', $itm_id)
                                                        ->where('location_id', $to_loc)
                                                        ->where('transaction_id', $get_stk->transaction_id)->first();

                                                    if (isset($add_stock) && sizeof($add_stock) > 0) {
                                                        $add_stock->quantity = $add_stock->quantity + $qty;
                                                        $add_stock->updated_at = $trans_date;
                                                        $add_stock->save();
                                                    } else {

                                                        $add_stock = new StockAge();
                                                        $add_stock->item_id = $itm_id;
                                                        $add_stock->location_id = $to_loc;
                                                        $add_stock->quantity = $qty;
                                                        $add_stock->created_at = $trans_date;
                                                        $add_stock->updated_at = $trans_date;
                                                        $add_stock->created_by = $user_id;
                                                        $add_stock->updated_by = $user_id;
                                                        $add_stock->save();
                                                    }

                                                } else {

                                                    $add_stock = new StockAge();
                                                    $add_stock->item_id = $itm_id;
                                                    $add_stock->location_id = $to_loc;
                                                    $add_stock->quantity = $qty;
                                                    $add_stock->created_at = $trans_date;
                                                    $add_stock->updated_at = $trans_date;
                                                    $add_stock->created_by = $user_id;
                                                    $add_stock->updated_by = $user_id;
                                                    $add_stock->save();

                                                }

                                                $stock_history = new StockHistory();
                                                $stock_history->transaction_id = $get_stk->transaction_id;
                                                $stock_history->from_location = $from_loc;
                                                $stock_history->to_location = $to_loc;
                                                $stock_history->item_id = $itm_id;
                                                $stock_history->type = 'remove';
                                                $stock_history->quantity = $qty;
                                                $stock_history->reason = 'transfer';
                                                $stock_history->created_by = $user_id;
                                                $stock_history->updated_by = $user_id;
                                                $stock_history->created_at = $trans_date;
                                                $stock_history->updated_at = $trans_date;
                                                $result1 = $stock_history->save();

                                                if ($result1) {

                                                    $stock_history1 = new StockHistory();
                                                    $stock_history1->transaction_id = $get_stk->transaction_id;
                                                    $stock_history1->from_location = $from_loc;
                                                    $stock_history1->to_location = $to_loc;
                                                    $stock_history1->item_id = $item_id;
                                                    $stock_history1->type = 'add';
                                                    $stock_history1->quantity = $qty;
                                                    $stock_history1->reason = 'transfer';
                                                    $stock_history1->created_by = $user_id;
                                                    $stock_history1->updated_by = $user_id;
                                                    $stock_history1->created_at = $trans_date;
                                                    $stock_history1->updated_at = $trans_date;
                                                    $result2 = $stock_history1->save();

                                                } else {
                                                    $error = true;
                                                    DB::rollBack();
                                                    return Response::json(array(
                                                        'status' => 'error',
                                                        'statuscode' => 434,
                                                        200));
                                                }

                                                break;
                                            }

                                            if ($qty <= 0) {
                                                break;
                                            }

                                        }
                                    }

                                }

                            } else {

                                $st_age_add = new StockAge();
                                $st_age_add->location_id = $from_loc;
                                $st_age_add->item_id = $itm_id;
                                $st_age_add->transaction_id = '';
                                $st_age_add->quantity = 0 - $qty;
                                $st_age_add->created_by = $user_id;
                                $st_age_add->updated_by = $user_id;
                                $st_age_add->created_at = $trans_date;
                                $st_age_add->updated_at = $trans_date;
                                $st_age_result = $st_age_add->save();

                                if ($st_age_result) {

                                    $st_age_add1 = new StockAge();
                                    $st_age_add1->location_id = $to_loc;
                                    $st_age_add1->item_id = $itm_id;
                                    $st_age_add1->transaction_id = '';
                                    $st_age_add1->quantity = $qty;
                                    $st_age_add1->created_by = $user_id;
                                    $st_age_add1->updated_by = $user_id;
                                    $st_age_add1->created_at = $trans_date;
                                    $st_age_add1->updated_at = $trans_date;
                                    $st_age_result1 = $st_age_add1->save();

                                    if ($st_age_result1) {

                                        $stock_history = new StockHistory();
                                        $stock_history->from_location = $from_loc;
                                        $stock_history->to_location = $to_loc;
                                        $stock_history->item_id = $itm_id;
                                        $stock_history->type = 'remove';
                                        $stock_history->quantity = $qty;
                                        $stock_history->reason = 'transfer';
                                        $stock_history->created_by = $user_id;
                                        $stock_history->updated_by = $user_id;
                                        $stock_history->created_at = $trans_date;
                                        $stock_history->updated_at = $trans_date;
                                        $result1 = $stock_history->save();

                                        if ($result1) {

                                            $stock_history1 = new StockHistory();
                                            $stock_history1->from_location = $from_loc;
                                            $stock_history1->to_location = $to_loc;
                                            $stock_history1->item_id = $itm_id;
                                            $stock_history1->type = 'add';
                                            $stock_history1->quantity = $qty;
                                            $stock_history1->reason = 'transfer';
                                            $stock_history1->created_by = $user_id;
                                            $stock_history1->updated_by = $user_id;
                                            $stock_history1->created_at = $trans_date;
                                            $stock_history1->updated_at = $trans_date;
                                            $result1 = $stock_history1->save();

                                            if (!$result1) {
                                                $error = true;
                                                DB::rollBack();
                                                return Response::json(array(
                                                    'status' => 'error',
                                                    'statuscode' => 434,
                                                    200));

                                            }

                                        } else {

                                            $error = true;
                                            DB::rollBack();
                                            return Response::json(array(
                                                'status' => 'error',
                                                'statuscode' => 434,
                                                200));
                                        }

                                    } else {

                                        $error = true;
                                        DB::rollBack();
                                        return Response::json(array(
                                            'status' => 'error',
                                            'statuscode' => 434,
                                            200));


                                    }

                                } else {
                                    $error = true;
                                    DB::rollBack();
                                    return Response::json(array(
                                        'status' => 'error',
                                        'statuscode' => 434,
                                        200));

                                }
                            }*/

                        } else {

                            $error = true;
                            DB::rollBack();
                            return Response::json(array(
                                'status' => 'error',
                                'statuscode' => 434,
                                200));

                        }

                    } catch( \Exception $e ){
                        Log::info($e->getMessage());
                        $error = true;
                        DB::rollBack();
                        return Response::json(array(
                            'status' => 'error',
                            'statuscode' => 434,
                            200));
                    }

                    $itemRequest = new ItemRequest();
                    $itemRequest->what_item_id = $itm_id;
                    $itemRequest->unit_id = $ut_id;
                    $itemRequest->satisfied_unit_id = $ut_id;
                    $itemRequest->what_item = $menu->item;
                    $itemRequest->owner_to = $user_id;
                    $itemRequest->owner_by = $user_id;
                    $itemRequest->when = $trans_date;
                    $itemRequest->qty = $tr_qty;
                    $itemRequest->existing_qty = 0;
                    $itemRequest->satisfied = 'Yes';
                    $itemRequest->location_for = $to_loc;
                    $itemRequest->price = $item_price;
                    $itemRequest->type = 'transfer';
                    $itemRequest->satisfied_batch_id = $transaction_id;
                    $itemRequest->satisfied_by = $user_id;
                    $itemRequest->satisfied_when = $trans_date;
                    $itemRequest->statisfied_qty = $tr_qty;
                    $itemRequest->location_from = $from_loc;
                    $success = $itemRequest->save();

                }
                DB::commit();
            }

            return Response::json(array(
                'status' => 'success',
                'statuscode' => 200,
                200));


        } else {

            //$locations = Location::where('created_by',$admin_id)->get();
            $sess_outlet_id = Session::get('outlet_session');
            $login_user =Auth::id();
            $user = Owner::find($login_user);

            /*if(isset($sess_outlet_id) && $sess_outlet_id != '' ){ //if outlet select globally
                $locations = Location::getLocationByOutletId($sess_outlet_id);
            }
            else*/ if(isset($user->created_by) && $user->created_by != '') { // if sub user then created_by
                $locations = Location::where('created_by',$user->created_by)->get();
            } else {                                                // else loged in user
                $locations = Location::where('created_by',$user->id)->get();
            }
            //get users' category
            $category = MenuTitle::where('created_by',$admin_id)->where("is_inventory_category",1)->get();


            return Response::json(array(
                'message' => 'List of menu',
                'status' => 'success',
                'statuscode' => 200,
                'locations' => $locations,
                'category'=>$category,
                200));

        }

    }

    public function StockTransferItems() {

        $loc_id = Input::get('loc_id');
        $cat_id = Input::get('cat_id');

        //$items = Menu::getItemsQuanityonLocation($cat_id,$loc_id);
        $menu_owner = Owner::menuOwner();
        $menu_arr = array();
        if($cat_id == 'all'){

            $menu_owner = Owner::menuOwner();
            $all_menu_titles_id = MenuTitle::getMenuTitleByCreatedBy($menu_owner)->lists('id');

            $menus = Menu::wherein('menus.menu_title_id',$all_menu_titles_id)
                ->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                ->leftjoin('unit as u','u.id','=','menus.unit_id')
                //->leftjoin('stock_level as sl','sl.item_id','=','menus.id')
                //->where('sl.request_item','true')
                //->where('sl.location_id',$loc_id)
                ->where('menus.created_by',$menu_owner)
                ->where('menus.is_inventory_item',1)
                ->select('menus.id as id','menus.item as item','menus.buy_price as buy_price','menus.order_unit as order_unit','menus.secondary_units as other_units','u.name as unit','u.id as unit_id','menus.menu_title_id')
                ->orderby('menus.menu_title_id')
                ->get();


            if ( isset($menus) && sizeof($menus) > 0 ) {
                $i = 0;
                foreach( $menus as $menu ) {

                    $menu_arr[$i]['id'] = $menu->id;
                    $menu_arr[$i]['unit'] = $menu->unit;
                    $menu_arr[$i]['buy_price'] = $menu->buy_price;
                    $menu_arr[$i]['unit_id'] = $menu->unit_id;
                    $menu_arr[$i]['order_unit'] = $menu->order_unit;
                    $menu_arr[$i]['title_id'] = $menu->menu_title_id;
                    $menu_arr[$i]['item'] = $menu->item;

                    //get stock detail
                    $stock = Stock::where('item_id',$menu->id)->where('location_id',$loc_id)->first();
                    //get open stock detail
                    $open_stock = StockLevel::where('location_id',$loc_id)->where('item_id',$menu->id)->first();

                    if ( isset($stock) && sizeof($stock) > 0 ) {

                        $menu_arr[$i]['stock'] = $stock->quantity;

                        if ( isset($open_stock) && sizeof($open_stock) > 0 ) {
                            $req_stock = $open_stock->opening_qty - $stock->quantity;
                            $menu_arr[$i]['open_stock'] = $req_stock;
                        } else {
                            $menu_arr[$i]['open_stock'] = '';
                        }

                    } else {
                        $menu_arr[$i]['stock'] = 0;

                        if ( isset($open_stock) && sizeof($open_stock) > 0 ) {
                            $menu_arr[$i]['open_stock'] = $open_stock->opening_qty;
                        } else {
                            $menu_arr[$i]['open_stock'] = '';
                        }

                    }


                    $j = 0;
                    $menu_arr[$i]['other_unit'][$j]['id'] = $menu->unit_id;
                    $menu_arr[$i]['other_unit'][$j]['name'] = $menu->unit;

                    if( isset($menu->other_units) && $menu->other_units != '' ) {
                        $units = json_decode($menu->other_units);
                        foreach( $units as $key=>$u ) {
                            $menu_arr[$i]['other_unit'][++$j]['id'] = $key;
                            $menu_arr[$i]['other_unit'][$j]['name'] = Unit::find($key)->name;
                        }
                    }

                    $i++;
                }
            }

            return $menu_arr;

        } else {


            $menus = Menu::leftjoin('unit as u','u.id','=','menus.unit_id')
                //->leftjoin('stock_level as sl','sl.item_id','=','menus.id')
                //->where('sl.request_item','true')
                //->where('sl.location_id',$loc_id)
                ->where('menus.created_by',$menu_owner)
                ->where('menus.is_inventory_item',1)
                ->select('menus.id as id','menus.item as item','menus.buy_price as buy_price','menus.order_unit as order_unit','menus.secondary_units as other_units','u.name as unit','u.id as unit_id')
                ->where('menus.menu_title_id', $cat_id)->get();

            if ( isset($menus) && sizeof($menus) > 0 ) {
                $i = 0;
                foreach( $menus as $menu ) {

                    $menu_arr[$i]['id'] = $menu->id;
                    $menu_arr[$i]['unit'] = $menu->unit;
                    $menu_arr[$i]['buy_price'] = $menu->buy_price;
                    $menu_arr[$i]['unit_id'] = $menu->unit_id;
                    $menu_arr[$i]['order_unit'] = $menu->order_unit;
                    $menu_arr[$i]['item'] = $menu->item;
                    $stock = Stock::where('item_id',$menu->id)->where('location_id',$loc_id)->first();
                    //get open stock detail
                    $open_stock = StockLevel::where('location_id',$loc_id)->where('item_id',$menu->id)->first();

                    if ( isset($stock) && sizeof($stock) > 0 ) {
                        $menu_arr[$i]['stock'] = $stock->quantity;

                        if ( isset($open_stock) && sizeof($open_stock) > 0 ) {
                            $req_stock = $open_stock->opening_qty - $stock->quantity;
                            $menu_arr[$i]['open_stock'] = $req_stock;
                        } else {
                            $menu_arr[$i]['open_stock'] = '';
                        }

                    } else {
                        $menu_arr[$i]['stock'] = 0;

                        if ( isset($open_stock) && sizeof($open_stock) > 0 ) {
                            $menu_arr[$i]['open_stock'] = $open_stock->opening_qty;
                        } else {
                            $menu_arr[$i]['open_stock'] = '';
                        }

                    }

                    $j = 0;
                    $menu_arr[$i]['other_unit'][$j]['id'] = $menu->unit_id;
                    $menu_arr[$i]['other_unit'][$j]['name'] = $menu->unit;

                    if( isset($menu->other_units) && $menu->other_units != '' ) {
                        $units = json_decode($menu->other_units);
                        foreach( $units as $key=>$u ) {
                            $menu_arr[$i]['other_unit'][++$j]['id'] = $key;
                            $menu_arr[$i]['other_unit'][$j]['name'] = Unit::find($key)->name;
                        }
                    }
                    $i++;
                }
            }
           // return $menu_arr;
        }

        return Response::json(array(
            'message' => 'menu items',
            'status' => 'success',
            'statuscode' => 200,
            'items' => $menu_arr,
            200));
    }

    public function salesConsumptionReport() {

        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $ot_id = Input::get('ot_id');
        $report_type = Input::get('report_type');

        $result = array();$sales = array();$consumption = array();

        //get all sales of items
        $items = OrderItem::join("orders", "orders.order_id", "=", "order_items.order_id")
            ->join('menus as m', 'm.id', '=', 'order_items.item_id')
            ->leftjoin('menu_titles as mt', 'mt.id', '=', 'order_items.category_id')
            ->join('unit as u', 'm.unit_id', '=', 'u.id')
            ->select('order_items.item_id as item_id','mt.id as cat_id','mt.title as category', 'm.price as price', 'u.name as unit', "order_items.item_name as item", DB::raw('ifnull(sum(order_items.item_quantity),0) as count'))
            ->where('orders.table_start_date', '>=', (new Carbon($from_date))->startOfDay())
            ->where('orders.table_start_date', '<=', (new Carbon($to_date))->endOfDay())
            ->where('orders.outlet_id', '=', $ot_id)
            ->where('orders.invoice_no', "!=", '')
            ->where('orders.cancelorder', '!=', 1)
            ->groupBy('order_items.item_id')
            ->orderBy('mt.id','DESC')
            ->get();

        if (isset($items) && sizeof($items) > 0) {
            $cnt = 0;
            foreach ($items as $itm) {
                $sales[$cnt]['itm_name'] = $itm->item;
                $sales[$cnt]['itm_qty'] = $itm->count . " " . $itm->unit;
                $sales[$cnt]['itm_cat_id'] = $itm->cat_id;
                $sales[$cnt]['price'] = $itm->price;
                $sales[$cnt]['itm_cat_name'] = $itm->category;
                if ( $report_type == 'sales with consumption' || $report_type == 'consumption' || $report_type == 'consumption wih sales' ) {
                    //get consumption of item
                    $recipe = RecipeDetails::where('menu_item_id', $itm->item_id)->first();
                    if (isset($recipe) && sizeof($recipe) > 0) {

                        $ingreds = Ingredients::join('menus as m', 'm.id', '=', 'ingredients.ing_item_id')
                            ->join('unit as u', 'm.unit_id', '=', 'u.id')
                            ->select('ingredients.ing_item_id as item_id', 'u.id as unit_id','u.name as unit', 'm.item as item_name', 'ingredients.qty as qty', 'm.buy_price as price')
                            ->where('recipeDetails_id', $recipe->id)
                            ->get();

                        if (isset($ingreds) && sizeof($ingreds) > 0) {
                            $no = 0;
                            foreach ($ingreds as $ing) {

                                //for sales with consumption
                                $sales[$cnt]['ingrd'][$no]['itm_name'] = $ing->item_name;
                                $ing_qty = $ing->qty * $itm->count / $recipe->referance;

                                $sales[$cnt]['ingrd'][$no]['itm_qty'] = number_format($ing_qty,2) . " " . $ing->unit;
                                //$sales[$cnt]['ingrd'][$no]['price'] = Utils::changeQty($ing->item_id,$ing_qty,$ing->unit_id) * $ing->price;
                                $sales[$cnt]['ingrd'][$no]['price'] = $ing_qty * $ing->price;

                                //for Consumption and Consumption with sales
                                if ( array_key_exists($ing->item_id,$consumption) ) {
                                    $consumption[$ing->item_id]['qty'] = $consumption[$ing->item_id]['qty'] + $ing_qty;
                                    //$consumption[$ing->item_id]['price'] = ($consumption[$ing->item_id]['qty'] + $ing_qty)*$ing->price;
                                    $consumption[$ing->item_id]['sale']['cnt'.$cnt]['item_name'] = $itm->item;
                                    $consumption[$ing->item_id]['sale']['cnt'.$cnt]['item_qty'] = $itm->count;
                                } else {
                                    $consumption[$ing->item_id]['name'] = $ing->item_name;
                                    $consumption[$ing->item_id]['unit'] = $ing->unit;
                                    $consumption[$ing->item_id]['qty'] = $ing_qty;
                                    //$consumption[$ing->item_id]['price'] = $ing_qty*$ing->price;
                                    $consumption[$ing->item_id]['sale']['cnt'.$cnt]['item_name'] = $itm->item;
                                    $consumption[$ing->item_id]['sale']['cnt'.$cnt]['item_qty'] = $itm->count;
                                }

                                $no++;
                            }

                        }

                    }

                }
                $cnt++;
            }
        }

        if ( $report_type == 'sales with consumption' || $report_type == 'sales' ) {
            $result = $sales;
        } else {
            $result = $consumption;
        }

        return Response::json(array(
            'status' => 'success',
            'statuscode' => 200,
            'result' => $result,
            'report_type'=>$report_type,
            200));

    }

    public function stockStatusReport() {

        $flag = Input::get('flag');
        $admin_id = Owner::menuOwner();
        $result = array();

        $from_date1 = '';
        $to_date1 = '';

        if ( $flag == 'params' ) {

            $locations = Location::where('created_by',$admin_id)->lists('name','id');
            $result['locations'] = $locations;

            $categories = MenuTitle::getMenuTitleByUserId($admin_id);
            $result['categories'] = $categories;

            $items = Menu::getMenuByUserId($admin_id);
            $result['items'] = $items;

        } else {

            $loc_id = Input::get('loc_id');
            $item_id = Input::get('item_id');
            $cat_id = Input::get('cat_id');
            $from_date = Input::get('from_date');
            $to_date = Input::get('to_date');

            $base_date = date('Y-m-d', strtotime($from_date . " - 1 day"));
            $base_date = $base_date." 23:59:59";

            $opening_stock = 0;$added_stock = 0;$removed_stock = 0;$closing_stock = 0;$location = '';

            if ( isset($loc_id) && $loc_id != '' ){
                $loc_arr = Location::where('id',$loc_id)->first();
                //$result['location'] = $loc_arr->name;
            } else {
                return Response::json(array(
                    'message' => 'Location not available',
                    'status' => 'error',
                    'statuscode' => 200,
                    'result' => '',
                    200));
            }

            if ( isset($cat_id) && $cat_id != '' ){
                $cat_arr = MenuTitle::where('id',$cat_id)->first();
                //$result['category'] = $cat_arr->title;
            } else {
                return Response::json(array(
                    'message' => 'Category not available',
                    'status' => 'error',
                    'statuscode' => 200,
                    'result' => '',
                    200));
            }



            if ( !isset($item_id) || $item_id == '' ){
                //$item_arr = Menu::join('unit','unit.id','=','menus.unit_id')->select('menus.item','menus.id','unit.name')->where('menus.id',$item_id)->first();
                //$result['item'] = $item_arr->item;
                $itm_arr = Menu::join('unit','unit.id','=','menus.unit_id')->select('menus.item','menus.id','unit.name')->where('menus.menu_title_id',$cat_id)->get();

            } else {
                $itm_arr = Menu::join('unit','unit.id','=','menus.unit_id')->select('menus.item','menus.id','unit.name')->where('menus.id',$item_id)->get();
            }
            $i = 0;
            foreach( $itm_arr as $itm ) {

                $base_stock = StockHistory::where('to_location',$loc_id)->where('item_id',$itm->id)->orderBy('created_at', 'asc')->first();

                if ( isset($base_stock) && sizeof($base_stock) > 0 ) {

                    $result[$i]['item'] = $itm->item;
                    $result[$i]['category'] = $cat_arr->title;
                    $result[$i]['location'] = $loc_arr->name;
                    $opening_stock = $base_stock->quantity;

                    $opening_add = StockHistory::select(DB::raw('ifnull(sum(quantity),0) as count'))
                        ->where('to_location', $loc_id)
                        ->where('id','!=',$base_stock->id)
                        ->where('item_id', $itm->id)
                        ->where('type', 'add')
                        ->where('created_at','>=', Carbon::createFromFormat("Y-m-d H:i:s",$base_stock->created_at))
                        ->where('created_at','<=', Carbon::createFromFormat("Y-m-d H:i:s",$base_date))
                        ->groupBy('item_id')
                        ->first();

                    if ( isset($opening_add) && sizeof($opening_add) > 0 ) {
                        $opening_stock += $opening_add->count;
                    }

                    $opening_remove = StockHistory::select(DB::raw('ifnull(sum(quantity),0) as count'))
                        ->where('from_location', $loc_id)
                        ->where('id','!=',$base_stock->id)
                        ->where('item_id', $itm->id)
                        ->where('type', 'remove')
                        ->where('created_at','>=', Carbon::createFromFormat("Y-m-d H:i:s",$base_stock->created_at))
                        ->where('created_at','<=', Carbon::createFromFormat("Y-m-d H:i:s",$base_date))
                        ->groupBy('item_id')
                        ->first();

                    if ( isset($opening_remove) && sizeof($opening_remove) > 0 ) {
                        $opening_stock = $opening_stock - $opening_remove->count;
                    }

                    $result[$i]['opening_stock'] = $opening_stock." ".$itm->name;

                    $added_stock_arr = StockHistory::select(DB::raw('ifnull(sum(quantity),0) as count'))
                        ->where('from_location', $loc_id)
                        ->where('item_id', $item_id)
                        ->where('type', 'add')
                        ->where('created_at','>=', Carbon::createFromFormat("Y-m-d H:i:s",$from_date." 00:00:00"))
                        ->where('created_at','<=', Carbon::createFromFormat("Y-m-d H:i:s",$to_date." 23:59:59"))
                        ->groupBy('item_id')
                        ->first();

                    if ( isset($added_stock_arr) && sizeof($added_stock_arr) > 0 ) {
                        $added_stock = $added_stock_arr->count;
                    }

                    $result[$i]['added_stock'] = $added_stock." ".$itm->name;

                    $removed_stock_arr = StockHistory::select(DB::raw('ifnull(sum(quantity),0) as count'))
                        ->where('from_location', $loc_id)
                        ->where('item_id', $item_id)
                        ->where('type', 'remove')
                        ->where('created_at','>=', Carbon::createFromFormat("Y-m-d H:i:s",$from_date." 00:00:00"))
                        ->where('created_at','<=', Carbon::createFromFormat("Y-m-d H:i:s",$to_date." 23:59:59"))
                        ->groupBy('item_id')
                        ->first();

                    if ( isset($removed_stock_arr) && sizeof($removed_stock_arr) > 0 ) {
                        $removed_stock = $removed_stock_arr->count;
                    }

                    $result[$i]['removed_stock'] = $removed_stock." ".$itm->name;

                    $result[$i]['closing_stock'] = ($opening_stock + $added_stock) - $removed_stock." ".$itm->name;

                    $result[$i]['date'] = date('d- M -y',strtotime($from_date))." to ".date('d- M -y',strtotime($to_date));
                    $from_date1 = date('d- M -y',strtotime($from_date));
                    $to_date1 = date('d- M -y',strtotime($to_date));
                    $i++;
                }

            }


        }

        return Response::json(array(
            'status' => 'success',
            'statuscode' => 200,
            'result' => $result,
            'from_date'=>$from_date1,
            'to_date'=>$to_date1,
            200));

    }

    public function getRequestByLocation() {

        $loc_id = Input::json('location_id');
        $req_user_id = Input::json('request_user_id');
        $user_id = Input::json('user_id');
        $merge = Input::json('merge');


        //$owner_id = Auth::user()->id;
        $request_arr = array();$wrong_request = array();
        $where = '1=1';

        if ( $loc_id != 0) {
            $where .= " && item_request.location_for = ".$loc_id;
        }
        if ( $req_user_id != 0 ) {
            $where .= " && item_request.owner_by = ".$req_user_id;
        }

        $item_requests = ItemRequest::leftJoin('menus','menus.id','=','item_request.what_item_id')
            ->leftJoin('menu_titles','menu_titles.id','=','menus.menu_title_id')
            ->leftJoin('unit as u','u.id','=','item_request.unit_id')
            ->select('item_request.id as id','u.id as unit_id','u.name as unit','item_request.what_item_id as item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when',DB::raw('sum(item_request.qty) as qty'), 'item_request.existing_qty', 'menus.id as menu_id', 'menus.menu_title_id', 'menus.item', 'menu_titles.title')
            ->where('item_request.owner_to','=',$user_id)
            ->whereRaw($where)
            ->where('item_request.satisfied','=',"No")
            ->orderBy('item_request.owner_by');


        if ( isset($merge) && $merge == 1 ) {
            $item_requests = $item_requests->groupBy('menus.id')->get();
        } else {
            $item_requests = $item_requests->groupBy('item_request.id')->get();
        }


        $i=0;$j=0;
        if ( isset($item_requests) && sizeof($item_requests) > 0 ) {
            foreach( $item_requests as $req ) {

                $check = false;
                $other_units = '';$unit_arr = array();
                $menu = Menu::find($req->item_id);

                if ( isset($menu) && sizeof($menu) > 0 ) {

                    if ( $menu->unit_id == $req->unit_id ) {
                        $check = true;
                    }

                    if( isset($menu->secondary_units) && $menu->secondary_units != '' ) {
                        $units = json_decode($menu->secondary_units);

                        foreach( $units as $key=>$u ) {
                            $check_unit = Unit::find($key);
                            $other_units['id'] = $key;
                            $other_units['name'] = $check_unit->name;
                            $other_units['value'] = $u;
                            array_push($unit_arr,$other_units);

                            if ( $key == $req->unit_id ) {
                                $check = true;
                            }
                        }
                    }

                    if ( !$check ) {

                        $wrong_request[$j]['id'] = $req->id;
                        $wrong_request[$j]['request_user_id'] = $req->owner_by;
                        $wrong_request[$j]['request_user_name'] = Owner::find($req->owner_by)->user_name;
                        $wrong_request[$j]['item_id'] = $req->item_id;
                        $wrong_request[$j]['item_name'] = $req->what_item;
                        $wrong_request[$j]['cat_id'] = $req->menu_title_id;
                        $wrong_request[$j]['unit'] = $req->unit;
                        $wrong_request[$j]['unit_id'] = $req->unit_id;
                        $wrong_request[$j]['other_unit'] = $unit_arr;
                        $wrong_request[$j]['cat_name'] = $req->title;
                        $wrong_request[$j]['when'] = $req->when;
                        $wrong_request[$j]['req_qty'] = $req->qty;
                        $j++;
                        continue;
                    }

                }

                $request_arr[$i]['id'] = $req->id;
                $request_arr[$i]['request_user_id'] = $req->owner_by;
                $request_arr[$i]['request_user_name'] = Owner::find($req->owner_by)->user_name;
                $request_arr[$i]['item_id'] = $req->item_id;
                $request_arr[$i]['item_name'] = $req->what_item;
                $request_arr[$i]['cat_id'] = $req->menu_title_id;
                $request_arr[$i]['unit'] = $req->unit;
                $request_arr[$i]['unit_id'] = $req->unit_id;
                $request_arr[$i]['other_unit'] = $unit_arr;
                $request_arr[$i]['cat_name'] = $req->title;
                $request_arr[$i]['when'] = $req->when;
                $request_arr[$i]['req_qty'] = $req->qty;
                $i++;

            }
        }
        //print_r($request_arr);exit;

        return Response::json(array(
            'message' => 'request data',
            'status' => 'success',
            'statuscode' => 200,
            'result' => $request_arr,
            'item_unsatisfied'=>$wrong_request,
            200));


    }

    //display pending request quantity in application
    public function getPendingItemQty() {

        $user_id = Input::json('user_id');
        $user_to = Input::json('to_user');
        $loc_id = Input::json('location_id');

        $request1 = array();$req1 = array();

        if ( isset($user_id) && isset($user_to) && isset($loc_id)) {

            $request = ItemRequest::select('item_request.what_item_id as id',DB::raw('sum(item_request.qty) as qty'))
                    ->where('owner_to',$user_to)->where('satisfied','No')
                    ->where('location_for',$loc_id)
                    ->where('owner_by',$user_id)
                    ->groupBy('what_item_id')
                    ->get();

            if ( isset($request) && sizeof($request) > 0 ) {

                foreach( $request as $req ) {
                    $req1['what_item_id'] = $req->id;
                    $req1['qty'] = $req->qty;
                    array_push($request1,$req1);
                }
            }
        }

        return Response::json(array(
            'status' => 'success',
            'statuscode' => 200,
            'result' => $request1,
            200));

    }



}