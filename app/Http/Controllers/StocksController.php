<?php namespace App\Http\Controllers;

use App\Consumption;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\HttpClientWrapper;
use App\Ingredients;
use App\Location;
use App\Menu;
use App\MenuTitle;
use App\order_details;
use App\OrderItem;
use App\OutletMapper;
use App\Owner;
use App\RecipeDetails;
use App\Stock;
use App\StockAge;
use App\StockHistory;
use App\StockLevel;
use App\Unit;
use Aws\CloudFront\Exception\Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class StocksController extends Controller {

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
		$owner_id = Auth::id();
        $admin = Owner::menuOwner();
        $login_user =Auth::id();
        $user = Owner::find($login_user);
        $sess_outlet_id = Session::get('outlet_session');

		if ($request->ajax())
		{
            $input = Input::all();
            $response = array();

            $search = $input['sSearch'];

            $sort = $input['sSortDir_0'];
            $sortCol=$input['iSortCol_0'];
            $sortColName=$input['mDataProp_'.$sortCol];

            $sort_field = 'stocks.updated_at';
            //echo $sort_field;exit;
            //sort by column
            if ( $sortColName == "item" ) {
                $sort_field = 'menus.item';
            } elseif ( $sortColName == "category" ) {
                $sort_field = 'menu_titles.title';
            } elseif ( $sortColName == "stock" ) {
                $sort_field = 'stocks.quantity';
            } elseif ( $sortColName == "location" ) {
                $sort_field = 'locations.name';
            } elseif ( $sortColName == "updated_at" ) {
                $sort_field = 'stocks.updated_at';
            } else {
                $sort_field = 'stocks.updated_at';
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

                    if ( $searchColName == 'item' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND menus.item like '%$search%'";
                        } else {
                            $search_col = "menus.item like '%$search%'";
                        }

                    } else if ( $searchColName == 'category' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND menu_titles.id = '$search'";
                        } else {
                            $search_col = "menu_titles.id = '$search'";
                        }

                    } else if ( $searchColName ==  'stock' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND stocks.quantity like '%$search%'";
                        } else {
                            $search_col = "stocks.quantity like '%$search%'";
                        }

                    } else if ( $searchColName ==  'location' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND locations.id = '$search'";
                        } else {
                            $search_col = "locations.id = '$search'";
                        }

                    } else if ( $searchColName ==  'updated_at' ) {
                        //echo 'here';exit;
                        $from = $search." 00:00:00";
                        $to = $search." 23:59:59";

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND stocks.updated_at BETWEEN '$from' AND '$to'";
                        } else {
                            $search_col = "stocks.updated_at BETWEEN '$from' AND '$to'";
                        }

                    }

                }

            }

            //echo $search_col;exit;

            if ( $search_col == '')$search_col = '1=1';
            $admin = Owner::menuOwner();
            //get users locations
            //$my_location = Location::where('created_by',$admin)->get();


            if(isset($sess_outlet_id) && $sess_outlet_id != '' ){
                $my_location = Location::getLocationByOutletId($sess_outlet_id);
            }
            else if(isset($user->created_by) && $user->created_by != '') {
                $my_location = Location::where('created_by',$user->created_by)->get();
            } else {
                $my_location = Location::where('created_by',$user->id)->get();
            }
            $loc_ids = '';
            if ( isset($my_location) && sizeof($my_location) > 0 ) {
                foreach( $my_location  as $loc ) {
                    if ( isset($loc_ids) && $loc_ids != '' ) {
                        $loc_ids .=",". $loc->id;
                    } else {
                        $loc_ids = $loc->id;
                    }

                }
            }

            $where = 'stocks.location_id in ('. $loc_ids .') AND ';

            $total_records = Stock::leftjoin('menus','menus.id', '=','stocks.item_id')
                ->leftjoin('unit','unit.id','=','menus.unit_id')
                ->leftjoin('menu_titles','menus.menu_title_id','=','menu_titles.id')
                ->leftjoin('locations','locations.id','=','stocks.location_id')
                ->select('stocks.id as id','menu_titles.title as category','stocks.updated_at as updated_at','menus.item as item','menus.id as item_id','unit.name as unit','stocks.quantity as quantity','locations.name as location','locations.id as loc_id')
                ->whereRaw(" $where ($search_col)")
                ->count();

            $stock_result = Stock::leftjoin('menus','menus.id', '=','stocks.item_id')
                ->leftjoin('unit','unit.id','=','menus.unit_id')
                ->leftjoin('menu_titles','menus.menu_title_id','=','menu_titles.id')
                ->leftjoin('locations','locations.id','=','stocks.location_id')
                ->select('stocks.id as id','menu_titles.title as category','stocks.updated_at as updated_at','menus.item as item','menus.id as item_id','unit.name as unit','stocks.quantity as quantity','locations.name as location','locations.id as loc_id')
                ->whereRaw(" $where ($search_col)")
                ->take($input['iDisplayLength'])
                ->skip($input['iDisplayStart'])
                ->orderBy($sort_field, $sort)->get();


            if ( $total_records > 0 ) {

                $i = 0;
                foreach ( $stock_result as $stock ) {

                    $response['result'][$i]['DT_RowId'] = $stock->id;
                    $response['result'][$i]['check_col'] = "";
                    $response['result'][$i]['category'] = $stock->category;
                    $response['result'][$i]['item'] = $stock->item;
                    $response['result'][$i]['location'] = $stock->location;
                    $response['result'][$i]['stock'] = $stock->quantity." ".$stock->unit;
                    $response['result'][$i]['updated_at'] = date('Y-m-d h:i a',strtotime($stock->updated_at));
                    $response['result'][$i]['action'] = '<a href="javascript:void(0)" title="Detail" onclick="showDetail('.$stock->item_id.','.$stock->loc_id.')"><span class="zmdi zmdi-file-text"></span></button>';

                    $i++;
                }


            } else {
                $total_records = 0;
                $response['result'] = array();
            }

            $response['iTotalRecords'] = $total_records;
            $response['iTotalDisplayRecords'] = $total_records;
            $response['aaData'] = $response['result'];

            //$locations = Location::where('created_by',$owner_id)->get();
            $response['locations'] = $my_location;

            $categories = MenuTitle::where('created_by',$admin)->get();
            $response['categories'] = $categories;


            return json_encode($response);
		}

        $outlet_id = Session::get('outlet_session');
        $selected_location = "";

        /*location array*/
        $locations = array('' => 'Select Location');
            $locations_list = Location::where('outlet_id',$outlet_id)->get();

        if( isset($locations_list) && sizeof($locations_list) > 0 ) {
            foreach ( $locations_list as $loc ) {
                if($loc->default_location == 1){
                    $selected_location = $loc->id;
                }
                $locations[$loc->id] = $loc->name;
            }
        }

        $admin_id = Owner::menuOwner();
        $items = Menu::where('created_by',$admin_id)
                ->where('is_inventory_item',1)->get();
        $item_list = array();
        $cnt = 0;
        foreach ($items as $item){
            $item_list[$cnt]['id'] = $item->id;
            $item_list[$cnt]['name'] = $item->item;
            $item_list[$cnt]['order_unit'] = $item->order_unit;
            $cnt++;
        }

        $units = ['' => 'Select Unit'];
        $units_arr = Unit::lists('name','id');

        if ( isset($units_arr) && sizeof($units_arr) > 0 ) {
            foreach( $units_arr as $id=>$uni ) {
                $units[$id] = $uni;
            }
        }

		return view('stocks.index',array('selected_location'=>$selected_location,'locations'=>$locations,'units'=>$units, 'items'=>$item_list));
	}

    public function addStock() {

        $owner_id = Auth::id();
        $item_id = Input::get('item_id');
        $location_id = Input::get('location_id');
        $batch_no = Input::get('batch_no');
        $reason = Input::get('reason');
        $quantity = Input::get('quantity');
        $manufacture_date = Input::get('manufacture_date');
        $unit_id = Input::get('unit_id');

        $check_stock = Stock::where('location_id',$location_id)->where('item_id',$item_id)->first();
        $menu = Menu::join('unit','unit.id','=','menus.unit_id')->where('menus.id',$item_id)->first();

        if ( isset($menu->secondary_units) && $menu->secondary_units != '') {
            $sec_unit = json_decode($menu->secondary_units);
            if ( isset($sec_unit) && sizeof($sec_unit) > 0 ) {
                foreach( $sec_unit as $key=>$qty ) {
                    if ( $key == $unit_id[0]) {
                        $quantity *= $qty;
                    }
                }
            }
        }

        DB::beginTransaction();

        if ( isset($check_stock) && sizeof($check_stock) > 0 ) {

            $check_stock->quantity = $check_stock->quantity + floatval($quantity);
            $result = $check_stock->save();

        } else {

            $add_stock = new Stock();
            $add_stock->item_id = $item_id;
            $add_stock->quantity = $quantity;
            $add_stock->location_id = $location_id;
            $add_stock->created_by = $owner_id;
            $add_stock->updated_by = $owner_id;
            $result = $add_stock->save();
        }

        if ( $result ) {

            if ( isset($batch_no) && $batch_no != '' ) {
                $transaction_id = $batch_no;
            } else {
                $transaction_id = uniqid();
            }

            $stock_history = new StockHistory();
            $stock_history->transaction_id = $transaction_id;
            $stock_history->to_location = $location_id;
            $stock_history->item_id = $item_id;
            $stock_history->type = 'Add';
            $stock_history->quantity = $quantity;
            $stock_history->reason = $reason;
            $stock_history->created_by = $owner_id;
            $stock_history->updated_by = $owner_id;
            $final = $stock_history->save();

            if ( $final ) {

                $response['status'] = 'success';
                $response['msg'] = 'Item quantity added successfully.';
                DB::commit();

                //get expiry date of item
               /* $expiry_date = NULL;
                if ( isset($manufacture_date) && $manufacture_date != '' ) {
                    $item_detail = Menu::find($item_id);
                    if ( isset($item_detail->expiry) && $item_detail->expiry > 0 ) {
                        $expiry_date = date('Y-m-d', strtotime($manufacture_date. ' + '.$item_detail->expiry.' days'));
                    }
                }

                $check_stock = StockAge::where('location_id',$location_id)
                                        ->where('item_id',$item_id)
                                        ->where('transaction_id',$transaction_id)
                                        ->first();

                if ( isset($check_stock) && sizeof($check_stock) > 0 ) {
                    $check_stock->quantity = $check_stock->quantity + $quantity;
                    $check_stock->updated_by = $owner_id;
                    $st_age_result = $check_stock->save();
                } else {
                    $st_age_add = new StockAge();
                    $st_age_add->location_id = $location_id;
                    $st_age_add->item_id = $item_id;
                    $st_age_add->transaction_id = $transaction_id;
                    $st_age_add->expiry_date = $expiry_date;
                    $st_age_add->quantity = $quantity;
                    $st_age_add->created_by = $owner_id;
                    $st_age_add->updated_by = $owner_id;
                    $st_age_result = $st_age_add->save();
                }

                if ( $st_age_result ) {
                    $response['status'] = 'success';
                    $response['msg'] = 'Item quantity added successfully.';
                    DB::commit();
                } else {
                    $response['status'] = 'error';
                    $response['msg'] = 'Some error ocurred, Please try again later';
                    DB::rollBack();
                }*/

            } else {
                $response['status'] = 'error';
                $response['msg'] = 'Some error ocurred, Please try again later';
                DB::rollBack();
            }

        } else {

            $response['status'] = 'error';
            $response['msg'] = 'Some error ocurred, Please try again later';
            DB::rollBack();
        }

        return json_encode($response);
    }

    public function ManuallyStockDecrement(Request $request) {

        if ($request->ajax())
        {
            $from = Input::get('from');
            $to = Input::get('to');
            $outlet_id = Session::get('outlet_session');
            $location_id = Input::get('location_id');
            $flag = Input::get('flag');
            $item_ids = Input::get('item_id');
            $cat_id = Input::get('cat_id');
            $user_id = Auth::id();
            $menu_owner=Owner::menuOwner();

            if( $outlet_id == '' && !isset($outlet_id)) {
                $outlet_id = Input::get('outlet_id');
            }

            if ( $from == '' && $to == '' ) {
                $from = $to = date('Y-m-d');
            }

            $orders = order_details::where('orders.table_start_date','>=', (new Carbon($from))->startOfDay())
                ->where('orders.table_start_date','<=', (new Carbon($to))->endOfDay())
                ->where('orders.outlet_id','=',$outlet_id)
                ->orderBy('orders.created_at', 'desc')
                ->where('orders.cancelorder', '!=', 1)
                ->where('orders.invoice_no', '!=', '')
                ->groupby('orders.order_id')
                ->get();

            $data['orders']=array();
            $stock_arr = array();$selected_itm_arr = array();


            if( isset($item_ids) && $item_ids != 'null' ) {
                $selected_itm_arr = explode(',' ,$item_ids);
            } elseif ( isset($cat_id) && $cat_id != 'null') {
                if ($item_ids == 'null' && $cat_id == 'all'){
                    $selected_itm_arr = Menu::getMenuByUserId($menu_owner)->lists('id');
                }else {
                    $selected_itm_arr = Menu::getmenubymenutitleid($cat_id)->lists('id');
                }
            }

            if ( isset($orders) && sizeof($orders) > 0 ) {

                foreach($orders as $order) {

                    $item_id_arr = OrderItem::where('order_id',$order->order_id)->get();

                    foreach( $item_id_arr as $arr ) {

                        if ( isset($flag) && $flag == 'process' ) {
                            //decrease stock
                            $check_record = $this::onSellDecreaseStock(array('item_id' => $arr->item_id, 'quantity' => $arr->item_quantity, 'order_id' => $order->order_id), $location_id, $user_id, 'manual',$selected_itm_arr);

                        } else if ( isset($flag) && $flag == 'revoke') {
                            //re-add stock mapped with order_id
                            $check_record = $this::manuallyRevokeStock(array('item_id' => $arr->item_id, 'quantity' => $arr->item_quantity, 'order_id' => $order->order_id), $location_id, $user_id);
                        } else {

                            $recipe = RecipeDetails::where('menu_item_id',$arr->item_id)->first();

                            if ( isset($recipe) && sizeof($recipe) > 0 ) {

                                $ingreds = Ingredients::join('menus','menus.id','=','ingredients.ing_item_id')
                                                    ->join('unit as u','u.id','=','menus.unit_id')
                                                    ->where('recipeDetails_id',$recipe->id)->get();

                                if ( isset($ingreds) && sizeof($ingreds) > 0 ) {
                                    foreach( $ingreds as $ing ) {

                                        if ( isset($selected_itm_arr) && sizeof($selected_itm_arr) > 0 ) {
                                            if ( !in_array($ing->ing_item_id,$selected_itm_arr) ) {
                                                continue;
                                            }
                                        }

                                        $check_stock = Stock::where('location_id',$location_id)->where('item_id',$ing->ing_item_id)->first();

                                        $total_qty = intval($arr->item_quantity) * floatval($ing->qty);

                                        $menus = Menu::find($ing->ing_item_id);

                                        if (array_key_exists($ing->ing_item_id,$stock_arr)) {
                                            $stock_arr[$ing->ing_item_id]['decrease_stock'] = $stock_arr[$ing->ing_item_id]['decrease_stock'] + $total_qty;
                                        } else {
                                            $stock_arr[$ing->ing_item_id]['name'] = $menus->item;
                                            if ( isset($check_stock) && sizeof($check_stock) > 0 ) {
                                                $stock_arr[$ing->ing_item_id]['stock'] = $check_stock->quantity;
                                                $stock_arr[$ing->ing_item_id]['unit']= $ing->name;
                                            } else {
                                                $stock_arr[$ing->ing_item_id]['stock'] = 0;
                                                $stock_arr[$ing->ing_item_id]['unit']= $ing->name;;
                                            }
                                            $stock_arr[$ing->ing_item_id]['decrease_stock'] = $total_qty;

                                        }

                                    }
                                }

                            } else {

                                if ( isset($selected_itm_arr) && sizeof($selected_itm_arr) > 0 ) {
                                    if ( !in_array($arr->item_id,$selected_itm_arr) ) {
                                        continue;
                                    }
                                }

                                $check_stock = Stock::where('location_id',$location_id)->where('item_id',$arr->item_id)->first();

                                $total_qty = floatval($arr->item_quantity);

                                $menus = Menu::join('unit as u','u.id','=','menus.unit_id')->where('menus.id',$arr->item_id)->first();

                                if (array_key_exists($arr->item_id,$stock_arr)) {
                                    $stock_arr[$arr->item_id]['decrease_stock'] = $stock_arr[$arr->item_id]['decrease_stock'] + $total_qty;
                                } else {

                                    if ( isset($menus) && sizeof($menus) > 0 ) {
                                        $stock_arr[$arr->item_id]['name'] = $menus->item;
                                        if ( isset($check_stock) && sizeof($check_stock) > 0 ) {
                                            $stock_arr[$arr->item_id]['stock'] = $check_stock->quantity;
                                            $stock_arr[$arr->item_id]['unit']= $menus->name;
                                        } else {
                                            $stock_arr[$arr->item_id]['stock'] = 0;
                                            $stock_arr[$arr->item_id]['unit']= $menus->name;;
                                        }
                                        $stock_arr[$arr->item_id]['decrease_stock'] = $total_qty;
                                    }

                                }

                            }
                        }

                    }

                }

            }
            if ( isset($flag) && ( $flag == 'process' || $flag == 'revoke' ) ) {
                return $check_record;

            } else {
                return view('stocks.manuallydecrementstockview',array('stock_arr'=>$stock_arr));
            }

        }

        $owner_id = Owner::menuOwner();
        //locations
        $locations = Location::getLocations($owner_id);

        //outlets
        $outlets = OutletMapper::getOutletsByOwnerId();
        if ( isset($outlets) && sizeof($outlets) == 2 ) {
            unset($outlets[""]);
        }

        //item and category filters
        $menu_items=Menu::where('created_by',$owner_id)->where('is_inventory_item',0)->lists('item','id');

        $menu_title = MenuTitle::where('created_by', $owner_id)->where('is_inventory_category',0)->get();
        $m_titles = array();
        $m_titles['all'] = 'All Categories';
        foreach($menu_title as $title) {
            $m_titles[$title->id]=$title->title;
        }

        return view('stocks.manuallydecrementstock',array('locations'=>$locations,'outlets'=>$outlets,'items'=>$menu_items, 'category' => $m_titles));


    }

    public static function onSellDecreaseStock( $order_item,$default_location, $owner_id, $flag = NULL,$selected_items = NULL) {

        if ( isset($order_item)) {

            $item_id = $order_item['item_id'];
            $quantity = $order_item['quantity'];
            $order_id = $order_item['order_id'];

            //get item recipe details
            $recipe = RecipeDetails::where('menu_item_id',$item_id)->first();

            if ( isset($recipe) && sizeof($recipe) > 0 ) {
                //get ingredients of recipe
                $ingreds = Ingredients::where('recipeDetails_id',$recipe->id)->get();

                try {

                    if ( isset($ingreds) && sizeof($ingreds) > 0 ) {

                        foreach( $ingreds as $ing ) {

                            if ( isset($selected_items) && sizeof($selected_items) > 0 ) {
                                if ( !in_array($ing->ing_item_id,$selected_items) ) {
                                    continue;
                                }
                            }

                            $total_qty = floatval($quantity) * (floatval($ing->qty) / $recipe->referance);

                            $status = StocksController::decreaseStock($ing->ing_item_id,$order_id,$total_qty,$default_location,$owner_id);

                            if ( $status == 'false') {
                                return 'false';
                            }
                            /*$check_consume = Consumption::where('order_id',$order_id)->where('item_id',$ing->ing_item_id)->first();

                            if ( !isset($check_consume) && sizeof($check_consume) == 0 ) {
                                //check stock available or not

                                $check_stock = Stock::where('location_id',$default_location)->where('item_id',$ing->ing_item_id)->first();


                                if ( isset($check_stock) && sizeof($check_stock) > 0 ) {

                                    $final_qty = floatval($check_stock->quantity) - $total_qty;

                                    $check_stock->quantity = $final_qty;
                                    $result = $check_stock->save();

                                } else {

                                    $final_qty = 0 - $total_qty;
                                    $check_stock = '';
                                    $check_stock = new Stock();
                                    $check_stock->item_id = $ing->ing_item_id;
                                    $check_stock->location_id = $default_location;
                                    $check_stock->created_by = $owner_id;
                                    $check_stock->updated_by = $owner_id;
                                    $check_stock->quantity = $final_qty;
                                    $result = $check_stock->save();

                                }

                                if ( $result ) {

                                    $get_stock = StockAge::where('item_id',$ing->ing_item_id)
                                        ->where('location_id',$default_location)
                                        ->where('quantity','>',0)
                                        ->orderby('expiry_date','asc')
                                        ->get();

                                    if ( isset($get_stock) && sizeof($get_stock) > 0 ) {
                                        $remain_stk = 0;$first_time = true;
                                        foreach( $get_stock as $get_stk ) {

                                            //if stock is less than first batch stock
                                            if ( $get_stk->quantity > $total_qty && $first_time == true ) {

                                                $get_stk->quantity = $get_stk->quantity - $total_qty;
                                                $get_stk->updated_by = $owner_id;
                                                $get_stk->save();

                                                $stock_history = new StockHistory();
                                                $stock_history->transaction_id = $get_stk->transaction_id;
                                                $stock_history->from_location = $default_location;
                                                $stock_history->item_id = $ing->ing_item_id;
                                                $stock_history->type = 'remove';
                                                $stock_history->quantity = $total_qty;
                                                $stock_history->reason = 'item sold';
                                                $stock_history->created_by = $owner_id;
                                                $stock_history->updated_by = $owner_id;
                                                $stock_history->order_id = $order_id;
                                                $result1 = $stock_history->save();

                                                if ( $result1 ) {

                                                    $consume = new Consumption();
                                                    $consume->transaction_id = $get_stk->transaction_id;
                                                    $consume->item_id = $ing->ing_item_id;
                                                    $consume->consume = $total_qty;
                                                    $consume->order_id = $order_id;
                                                    $result2 = $consume->save();

                                                    if ( !$result2 ) {
                                                        DB::rollBack();
                                                        return 'false';
                                                    }

                                                } else {
                                                    DB::rollBack();
                                                    return 'false';
                                                }

                                                break;

                                            } else {

                                                if ( $remain_stk > 0 || $first_time == true ) {
                                                    $first_time = false;

                                                    if ( $get_stk->quantity <= $total_qty ) {

                                                        $total_qty = $total_qty - $get_stk->quantity;
                                                        $remain_stk = $total_qty;

                                                        $removed_stock = $get_stk->quantity;

                                                        $get_stk->quantity = $get_stk->quantity - $get_stk->quantity;
                                                        $get_stk->updated_by = $owner_id;
                                                        $get_stk->save();

                                                        $stock_history = new StockHistory();
                                                        $stock_history->transaction_id = $get_stk->transaction_id;
                                                        $stock_history->from_location = $default_location;
                                                        $stock_history->item_id = $ing->ing_item_id;
                                                        $stock_history->type = 'remove';
                                                        $stock_history->quantity = $removed_stock;
                                                        $stock_history->reason = 'item sold';
                                                        $stock_history->created_by = $owner_id;
                                                        $stock_history->updated_by = $owner_id;
                                                        $stock_history->order_id = $order_id;
                                                        $result1 = $stock_history->save();

                                                        if ( $result1 ) {

                                                            $consume = new Consumption();
                                                            $consume->transaction_id = $get_stk->transaction_id;
                                                            $consume->item_id = $ing->ing_item_id;
                                                            $consume->consume = $removed_stock;
                                                            $consume->order_id = $order_id;
                                                            $result2 = $consume->save();

                                                            if ( !$result2 ) {
                                                                DB::rollBack();
                                                                return 'false';
                                                            }

                                                        } else {
                                                            DB::rollBack();
                                                            return 'false';
                                                        }

                                                    } else {

                                                        $get_stk->quantity = $get_stk->quantity - $total_qty;
                                                        $get_stk->updated_by = $owner_id;
                                                        $get_stk->save();

                                                        $stock_history = new StockHistory();
                                                        $stock_history->transaction_id = $get_stk->transaction_id;
                                                        $stock_history->from_location = $default_location;
                                                        $stock_history->item_id = $ing->ing_item_id;
                                                        $stock_history->type = 'remove';
                                                        $stock_history->quantity = $total_qty;
                                                        $stock_history->reason = 'item sold';
                                                        $stock_history->created_by = $owner_id;
                                                        $stock_history->updated_by = $owner_id;
                                                        $stock_history->order_id = $order_id;
                                                        $result1 = $stock_history->save();

                                                        if ( $result1 ) {

                                                            $consume = new Consumption();
                                                            $consume->transaction_id = $get_stk->transaction_id;
                                                            $consume->item_id = $ing->ing_item_id;
                                                            $consume->consume = $total_qty;
                                                            $consume->order_id = $order_id;
                                                            $result2 = $consume->save();

                                                            if ( !$result2 ) {
                                                                DB::rollBack();
                                                                return 'false';
                                                            }

                                                        } else {
                                                            DB::rollBack();
                                                            return 'false';
                                                        }

                                                        break;
                                                    }

                                                    if ( $total_qty <= 0 ) {
                                                        break;
                                                    }

                                                }
                                            }

                                        }

                                    } else {

                                        $trans_id = uniqid();

                                        $st_age_add = new StockAge();
                                        $st_age_add->location_id = $default_location;
                                        $st_age_add->item_id = $ing->ing_item_id;
                                        $st_age_add->transaction_id = $trans_id;
                                        $st_age_add->quantity = 0 - $total_qty;
                                        $st_age_add->created_by = $owner_id;
                                        $st_age_add->updated_by = $owner_id;
                                        $st_age_result = $st_age_add->save();

                                        if ( $st_age_result) {

                                            $stock_history = new StockHistory();
                                            $stock_history->transaction_id = $trans_id;
                                            $stock_history->from_location = $default_location;
                                            $stock_history->item_id = $ing->ing_item_id;
                                            $stock_history->type = 'remove';
                                            $stock_history->quantity = $total_qty;
                                            $stock_history->reason = 'item sold';
                                            $stock_history->created_by = $owner_id;
                                            $stock_history->updated_by = $owner_id;
                                            $stock_history->order_id = $order_id;
                                            $result1 = $stock_history->save();

                                            if ( $result1 ) {

                                                $consume = new Consumption();
                                                $consume->transaction_id = $trans_id;
                                                $consume->item_id = $ing->ing_item_id;
                                                $consume->consume = $total_qty;
                                                $consume->order_id = $order_id;
                                                $result2 = $consume->save();

                                                if ( !$result2 ) {
                                                    DB::rollBack();
                                                    return 'false';
                                                }

                                            } else {
                                                DB::rollBack();
                                                return 'false';
                                            }

                                        } else {
                                            DB::rollBack();
                                            return 'false';
                                        }


                                    }

                                } else {
                                    DB::rollBack();
                                    return 'false';
                                }

                            }*/

                        }

                    } else {

                        if ( isset($selected_items) && sizeof($selected_items) > 0 ) {
                            if ( !in_array($item_id,$selected_items) ) {
                                return 'true';
                            }
                        }

                        $status = StocksController::decreaseStock($item_id,$order_id,$quantity,$default_location,$owner_id);
                        if ( $status == 'false') {
                            return 'false';
                        }

                    }

                } catch( \Exception $e ) {
                    Log::info($e->getMessage());
                    return 'false';
                }

            } else {

                if ( isset($selected_items) && sizeof($selected_items) > 0 ) {
                    if ( !in_array($item_id,$selected_items) ) {
                        return 'true';
                    }
                }

                $status = StocksController::decreaseStock($item_id,$order_id,$quantity,$default_location,$owner_id);
                if ( $status == 'false') {
                    return 'false';
                }

            }

        }
        return 'true';
    }

    public static function decreaseStock($item_id,$order_id,$total_qty,$default_location,$owner_id) {

        DB::beginTransaction();
        $check_consume = Consumption::where('order_id',$order_id)->where('item_id',$item_id)->first();

        if ( !isset($check_consume) && sizeof($check_consume) == 0 ) {
            //check stock available or not

            $check_stock = Stock::where('location_id',$default_location)->where('item_id',$item_id)->first();


            if ( isset($check_stock) && sizeof($check_stock) > 0 ) {

                $final_qty = floatval($check_stock->quantity) - $total_qty;

                $check_stock->quantity = $final_qty;
                $result = $check_stock->save();

            } else {

                $final_qty = 0 - $total_qty;
                $check_stock = '';
                $check_stock = new Stock();
                $check_stock->item_id = $item_id;
                $check_stock->location_id = $default_location;
                $check_stock->created_by = $owner_id;
                $check_stock->updated_by = $owner_id;
                $check_stock->quantity = $final_qty;
                $result = $check_stock->save();

            }

            if ( $result ) {


                $stock_history = new StockHistory();
                $stock_history->transaction_id = '';
                $stock_history->from_location = $default_location;
                $stock_history->item_id = $item_id;
                $stock_history->type = 'remove';
                $stock_history->quantity = $total_qty;
                $stock_history->reason = 'item sold';
                $stock_history->created_by = $owner_id;
                $stock_history->updated_by = $owner_id;
                $stock_history->order_id = $order_id;
                $result1 = $stock_history->save();

                if ( $result1 ) {

                    $consume = new Consumption();
                    $consume->transaction_id = '';
                    $consume->item_id = $item_id;
                    $consume->consume = $total_qty;
                    $consume->order_id = $order_id;
                    $result2 = $consume->save();

                    if ( !$result2 ) {
                        DB::rollBack();
                        return 'false';
                    }

                } else {
                    DB::rollBack();
                    return 'false';
                }

                /*$get_stock = StockAge::where('item_id',$item_id)
                    ->where('location_id',$default_location)
                    ->where('quantity','>',0)
                    ->orderby('expiry_date','asc')
                    ->get();

                if ( isset($get_stock) && sizeof($get_stock) > 0 ) {
                    $remain_stk = 0;$first_time = true;
                    foreach( $get_stock as $get_stk ) {

                        //if stock is less than first batch stock
                        if ( $get_stk->quantity > $total_qty && $first_time == true ) {

                            $get_stk->quantity = $get_stk->quantity - $total_qty;
                            $get_stk->updated_by = $owner_id;
                            $get_stk->save();

                            $stock_history = new StockHistory();
                            $stock_history->transaction_id = $get_stk->transaction_id;
                            $stock_history->from_location = $default_location;
                            $stock_history->item_id = $item_id;
                            $stock_history->type = 'remove';
                            $stock_history->quantity = $total_qty;
                            $stock_history->reason = 'item sold';
                            $stock_history->created_by = $owner_id;
                            $stock_history->updated_by = $owner_id;
                            $stock_history->order_id = $order_id;
                            $result1 = $stock_history->save();

                            if ( $result1 ) {

                                $consume = new Consumption();
                                $consume->transaction_id = $get_stk->transaction_id;
                                $consume->item_id = $item_id;
                                $consume->consume = $total_qty;
                                $consume->order_id = $order_id;
                                $result2 = $consume->save();

                                if ( !$result2 ) {
                                    DB::rollBack();
                                    return 'false';
                                }

                            } else {
                                DB::rollBack();
                                return 'false';
                            }

                            break;

                        } else {

                            if ( $remain_stk > 0 || $first_time == true ) {
                                $first_time = false;

                                if ( $get_stk->quantity <= $total_qty ) {

                                    $total_qty = $total_qty - $get_stk->quantity;
                                    $remain_stk = $total_qty;

                                    $removed_stock = $get_stk->quantity;

                                    $get_stk->quantity = $get_stk->quantity - $get_stk->quantity;
                                    $get_stk->updated_by = $owner_id;
                                    $get_stk->save();

                                    $stock_history = new StockHistory();
                                    $stock_history->transaction_id = $get_stk->transaction_id;
                                    $stock_history->from_location = $default_location;
                                    $stock_history->item_id = $item_id;
                                    $stock_history->type = 'remove';
                                    $stock_history->quantity = $removed_stock;
                                    $stock_history->reason = 'item sold';
                                    $stock_history->created_by = $owner_id;
                                    $stock_history->updated_by = $owner_id;
                                    $stock_history->order_id = $order_id;
                                    $result1 = $stock_history->save();

                                    if ( $result1 ) {

                                        $consume = new Consumption();
                                        $consume->transaction_id = $get_stk->transaction_id;
                                        $consume->item_id = $item_id;
                                        $consume->consume = $removed_stock;
                                        $consume->order_id = $order_id;
                                        $result2 = $consume->save();

                                        if ( !$result2 ) {
                                            DB::rollBack();
                                            return 'false';
                                        }

                                    } else {
                                        DB::rollBack();
                                        return 'false';
                                    }

                                } else {

                                    $get_stk->quantity = $get_stk->quantity - $total_qty;
                                    $get_stk->updated_by = $owner_id;
                                    $get_stk->save();

                                    $stock_history = new StockHistory();
                                    $stock_history->transaction_id = $get_stk->transaction_id;
                                    $stock_history->from_location = $default_location;
                                    $stock_history->item_id = $item_id;
                                    $stock_history->type = 'remove';
                                    $stock_history->quantity = $total_qty;
                                    $stock_history->reason = 'item sold';
                                    $stock_history->created_by = $owner_id;
                                    $stock_history->updated_by = $owner_id;
                                    $stock_history->order_id = $order_id;
                                    $result1 = $stock_history->save();

                                    if ( $result1 ) {

                                        $consume = new Consumption();
                                        $consume->transaction_id = $get_stk->transaction_id;
                                        $consume->item_id = $item_id;
                                        $consume->consume = $total_qty;
                                        $consume->order_id = $order_id;
                                        $result2 = $consume->save();

                                        if ( !$result2 ) {
                                            DB::rollBack();
                                            return 'false';
                                        }

                                    } else {
                                        DB::rollBack();
                                        return 'false';
                                    }

                                    break;
                                }

                                if ( $total_qty <= 0 ) {
                                    break;
                                }

                            }
                        }

                    }

                } else {

                    $trans_id = uniqid();

                    $st_age_add = new StockAge();
                    $st_age_add->location_id = $default_location;
                    $st_age_add->item_id = $item_id;
                    $st_age_add->transaction_id = $trans_id;
                    $st_age_add->quantity = 0 - $total_qty;
                    $st_age_add->created_by = $owner_id;
                    $st_age_add->updated_by = $owner_id;
                    $st_age_result = $st_age_add->save();

                    if ( $st_age_result) {

                        $stock_history = new StockHistory();
                        $stock_history->transaction_id = $trans_id;
                        $stock_history->from_location = $default_location;
                        $stock_history->item_id = $item_id;
                        $stock_history->type = 'remove';
                        $stock_history->quantity = $total_qty;
                        $stock_history->reason = 'item sold';
                        $stock_history->created_by = $owner_id;
                        $stock_history->updated_by = $owner_id;
                        $stock_history->order_id = $order_id;
                        $result1 = $stock_history->save();

                        if ( $result1 ) {

                            $consume = new Consumption();
                            $consume->transaction_id = $trans_id;
                            $consume->item_id = $item_id;
                            $consume->consume = $total_qty;
                            $consume->order_id = $order_id;
                            $result2 = $consume->save();

                            if ( !$result2 ) {
                                DB::rollBack();
                                return 'false';
                            }

                        } else {
                            DB::rollBack();
                            return 'false';
                        }

                    } else {
                        DB::rollBack();
                        return 'false';
                    }


                }*/

            } else {
                DB::rollBack();
                return 'false';
            }

        }
        DB::commit();
        return 'true';
    }

    public static function onCancelChangeStock($order_id,$default_location) {

        $owner_id = Auth::id();

        if ( isset($order_id) && $order_id != '' ) {
            $ord_item = OrderItem::where('order_id',$order_id)->get();

            if ( isset($ord_item) && sizeof($ord_item) > 0 ) {
                foreach( $ord_item as  $itm ) {

                    //get item recipe details
                    $recipe = RecipeDetails::where('menu_item_id',$itm->item_id)->first();
                    if ( isset($recipe) && sizeof($recipe) > 0 ) {
                        //get ingredients of recipe
                        $ingreds = Ingredients::where('recipeDetails_id',$recipe->id)->get();
                        foreach( $ingreds as $ing ) {

                            $total_qty = intval($itm->item_quantity) * floatval($ing->qty);

                            DB::beginTransaction();
                            //check stock available or not
                            $check_stock = Stock::where('location_id',$default_location)->where('item_id',$ing->ing_item_id)->first();

                            if ( isset($check_stock) && sizeof($check_stock) > 0 ) {

                                $final_qty = floatval($check_stock->quantity) + $total_qty;

                                $check_stock->quantity = $final_qty;
                                $result = $check_stock->save();

                            } else {

                                $final_qty = 0 + $total_qty;
                                $check_stock = '';
                                $check_stock = new Stock();
                                $check_stock->item_id = $ing->ing_item_id;
                                $check_stock->location_id = $default_location;
                                $check_stock->created_by = $owner_id;
                                $check_stock->updated_by = $owner_id;
                                $check_stock->quantity = $final_qty;
                                $result = $check_stock->save();

                            }

                            if ( $result ) {

                                $trans_id = uniqid();

                                $stock_history = new StockHistory();
                                $stock_history->transaction_id = $trans_id;
                                $stock_history->from_location = $default_location;
                                $stock_history->item_id = $ing->ing_item_id;
                                $stock_history->type = 'add';
                                $stock_history->quantity = $total_qty;
                                $stock_history->reason = 'order cancel';
                                $stock_history->created_by = $owner_id;
                                $stock_history->updated_by = $owner_id;
                                $result1 = $stock_history->save();

                                if ($result1) {
                                    DB::commit();
                                } else {
                                    DB::rollBack();
                                }
                            } else {
                                DB::rollBack();
                            }

                        }
                    }

                }
            }
        }

    }

    public static function manuallyRevokeStock( $order_item,$location_id, $owner_id ) {

        DB::beginTransaction();
        if ( isset($order_item)) {

            $order_id = $order_item['order_id'];

            if ( isset($order_id) && $order_id != '' ) {

                $check_record = Consumption::where('order_id',$order_id)->get();

                if ( isset($check_record) && sizeof($check_record) > 0 ) {

                    foreach( $check_record as $record ) {

                        try {

                            $stock = Stock::where('item_id',$record->item_id)->where('location_id',$location_id)->first();

                            if ( isset($stock) && sizeof($stock) > 0 ) {

                                $stock->quantity = floatval($stock->quantity) + floatval($record->consume);
                                $stock->updated_by = $owner_id;
                                $result = $stock->save();

                                if ( $result ) {

                                    $stock_history = new StockHistory();
                                    $stock_history->transaction_id = $record->transaction_id;
                                    $stock_history->to_location = $location_id;
                                    $stock_history->item_id = $record->item_id;
                                    $stock_history->type = 'add';
                                    $stock_history->quantity = $record->consume;
                                    $stock_history->reason = 'revoke';
                                    $stock_history->created_by = $owner_id;
                                    $stock_history->updated_by = $owner_id;
                                    $stock_history->order_id = $order_id;

                                    $result1 = $stock_history->save();

                                    if ( $result1 ) {

                                        /*$st_age_add = StockAge::where('transaction_id',$record->transaction_id)->first();
                                        if ( isset($st_age_result) && sizeof($st_age_add) > 0 ) {

                                            $st_age_add->quantity = $st_age_add->quantity + $record->consume;
                                            $st_age_add->updated_by = $owner_id;
                                            $st_age_result = $st_age_add->save();

                                        } else {

                                            $st_age_add = new StockAge();
                                            $st_age_add->location_id = $location_id;
                                            $st_age_add->item_id = $record->item_id;
                                            $st_age_add->quantity = $record->consume;
                                            $st_age_add->created_by = $owner_id;
                                            $st_age_add->updated_by = $owner_id;
                                            $st_age_result = $st_age_add->save();

                                        }*/

                                        //delete record
                                       $counsume = Consumption::where('id',$record->id)->delete();


                                    } else {
                                        DB::rollBack();
                                        return 'false';
                                    }

                                }

                            } else {

                            }
                        } catch( \Exception $e ) {
                            DB::rollBack();
                            return 'false';
                        }

                    }

                }
            }

        }

        DB::commit();
        return 'true';
    }


    public function StockDetails() {

        $loc_id = Input::get('location_id');
        $item_id = Input::get('item_id');
        $page = Input::get("page");

        if(isset($page) && sizeof($page)){

            $stock_detail = StockHistory::join('menus','menus.id','=','stock_history.item_id')
                ->join('unit','unit.id','=','menus.unit_id')
                ->where('stock_history.item_id',$item_id)
                ->where('stock_history.to_location', $loc_id)
                ->where('type','add')
                ->orwhere('stock_history.from_location',$loc_id)
                ->where('type','remove')
                ->where('stock_history.item_id',$item_id)
                ->select('stock_history.id','stock_history.created_at as date','menus.item as item','unit.name as unit','stock_history.quantity as stk_quantity','stock_history.type','stock_history.reason')
                ->paginate(7);

            return view('stocks.stockDetailsItems',array('stock'=>$stock_detail));
        }
        /*$stock_detail = StockAge::join('menus','menus.id','=','stock_age.item_id')
                                ->join('unit','unit.id','=','menus.unit_id')
                                ->select('stock_age.id','stock_age.transaction_id','menus.item as item','unit.name as unit','stock_age.quantity as stk_quantity','stock_age.expiry_date')
                                ->where('location_id',$loc_id)
                                ->where('stock_age.item_id',$item_id)
                                ->get();*/
        $location = Location::find($loc_id)->name;

        $stock_detail = StockHistory::join('menus','menus.id','=','stock_history.item_id')
                                ->join('unit','unit.id','=','menus.unit_id')
                                ->where('stock_history.item_id',$item_id)
                                    ->where('stock_history.to_location', $loc_id)
                                        ->where('type','add')
                                    ->orwhere('stock_history.from_location',$loc_id)
                                        ->where('type','remove')
                                ->where('stock_history.item_id',$item_id)
                                ->select('stock_history.id','stock_history.created_at as date','menus.item as item','unit.name as unit','stock_history.quantity as stk_quantity','stock_history.type','stock_history.reason')
                                ->paginate(7);

        return view('stocks.stockDetails',array('loc_id'=>$loc_id,'item_id'=>$item_id, 'stock'=>$stock_detail,'location'=>$location));

    }

    public function removeStock(){

        //print_r(Input::all());exit;
        $owner_id = Auth::id();
        $item_id = Input::get('remove_item_id');
        $location_id = Input::get('remove_location_id');
        $reason = Input::get('remove_reason');
        $remove_qty = Input::get('remove_item_qty');
        $unit_id = Input::get('unit_id');
        //$remove_qty = Input::get('satisfy_qty'); // array
        $transaction_id = Input::get('transaction_id'); // array

        DB::beginTransaction();

        //foreach ($remove_qty as $age_id => $removed_qty){

            //$stock_raw = StockAge::find($age_id);

            $stock_history = new StockHistory();
            $stock_history->from_location = $location_id;
           // $stock_history->transaction_id = $stock_raw->transaction_id;
            $stock_history->item_id = $item_id;
            $stock_history->type = 'remove';
            $stock_history->quantity = $remove_qty;
            $stock_history->reason = $reason;
            $stock_history->created_by = $owner_id;
            $stock_history->updated_by = $owner_id;
            $final = $stock_history->save();

            if(!$final) {
                DB::rollBack();
                $response['status'] = 'error';
                $response['msg'] = 'Some error ocurred, Please try again later';
                return json_encode($response);
            }

            $check_stock = Stock::where('location_id',$location_id)->where('item_id',$item_id)->first();

            $result = '';
            if ( isset($check_stock) && sizeof($check_stock) > 0) {
                $menu = Menu::join('unit','unit.id','=','menus.unit_id')->where('menus.id',$item_id)->first();

                if ( isset($menu->secondary_units) && $menu->secondary_units != '') {
                    $sec_unit = json_decode($menu->secondary_units);
                    if ( isset($sec_unit) && sizeof($sec_unit) > 0 ) {
                        foreach( $sec_unit as $key=>$qty ) {
                            if ( $key == $unit_id[0]) {
                                $remove_qty *= $qty;
                            }
                        }
                    }
                }


                $check_stock->quantity = $check_stock->quantity - floatval($remove_qty);
                $result = $check_stock->save();

                /*$stock_raw->quantity = $stock_raw->quantity - floatval($removed_qty);
                $stock_raw->save();*/

            }else{

                $menu = Menu::join('unit','unit.id','=','menus.unit_id')->where('menus.id',$item_id)->first();

                if ( isset($menu->secondary_units) && $menu->secondary_units != '') {
                    $sec_unit = json_decode($menu->secondary_units);
                    if ( isset($sec_unit) && sizeof($sec_unit) > 0 ) {
                        foreach( $sec_unit as $key=>$qty ) {
                            if ( $key == $unit_id[0]) {
                                $remove_qty *= $qty;
                            }
                        }
                    }
                }

                $stock = new Stock();
                $stock->item_id = $item_id;
                $stock->location_id = $location_id;
                $stock->created_by = $owner_id;
                $stock->updated_by = $owner_id;
                $stock->quantity = 0-$remove_qty;
                $result = $stock->save();

                /*DB::rollBack();
                $response['status'] = 'error';
                $response['msg'] = 'Remove Qty should not be 0, blank or character.';
                return json_encode($response);*/
            }
        //}

        if ( $result ) {

            $response['status'] = 'success';
            $response['msg'] = 'Item quantity removed successfully.';
            DB::commit();

        } else {
            $response['status'] = 'error';
            $response['msg'] = 'Some error ocurred, Please try again later';
            DB::rollBack();
        }

        return json_encode($response);
    }

    public function StockTransfer() {

        $httpclient = new HttpClientWrapper();
        $token = $_COOKIE['laravel_session'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $from_loc = Input::get('from_location');
            $to_loc = Input::get('to_location');
            $trans_date = Input::get('transfer_date');
            $trans_qty = Input::get('transfer_qty');
            $cat_id = Input::get('cat_id');
            $item_id = Input::get('item_id');
            $unit_id = Input::get('unit_id');
            $price = Input::get('price');
//print_r($unit_id);exit;
            $param = array(
                'from_loc'=>$from_loc,
                'to_loc'=>$to_loc,
                'trans_date'=>$trans_date,
                'trans_qty'=>$trans_qty,
                'cat_id'=>$cat_id,
                'item_id'=>$item_id,
                'unit_id'=>$unit_id,
                'price'=>$price,
            );

            $stk_trn_detail = $httpclient->send_request('POST',$param,$_SERVER['SERVER_NAME'].'/api/v3/stock-transfer',$token);
            $result = json_decode($stk_trn_detail);


            if ( isset($result) && $result->status == 'success' )
            {
                return 'success';
            } else {
                return 'error';
            }

        } else {

            $stk_trn_detail = $httpclient->send_request('GET','',$_SERVER['SERVER_NAME'].'/api/v3/stock-transfer',$token);
            $result = json_decode($stk_trn_detail);

            if ( isset($result) && $result->status == 'success' )
            {
                return view('stocks.stocktransfer',array('locations'=>$result->locations,'category'=>$result->category));
            }
        }


    }

    public function getTransferItems() {

        $from_loc = Input::get('from_loc_id');
        $cat_id = Input::get('cat_id');

        $param = [
            'cat_id'=>$cat_id,
            'loc_id'=>$from_loc
        ];

        $httpclient = new HttpClientWrapper();
        $token = $_COOKIE['laravel_session'];

        $stk_trn_items = $httpclient->send_request('POST',$param,$_SERVER['SERVER_NAME'].'/api/v3/stock-transfer-items',$token);
        $result = json_decode($stk_trn_items);

        if ( isset($result) && $result->status == 'success' )
        {
            return view('stocks.stockTransferItems',array('items'=>$result->items,'cat_id'=>$cat_id));
        }
    }

    public function ReserveStock(){

        $admin_id = Owner::menuOwner();
        $sess_outlet_id = Session::get('outlet_session');
        $menus = Menu::leftjoin('menu_titles as mt','mt.id', '=','menus.menu_title_id')
            ->join('unit','unit.id','=','menus.unit_id')
            ->select('menus.id as id','menus.item as item','mt.title as title','menus.menu_title_id as cat_id','unit.id as unit_id','unit.name as unit')
            ->where('menus.created_by',$admin_id)
            ->orderBy('menus.menu_title_id','asc')
            ->orderBy('menus.id','asc')->get();
        $arr = array();
        $i = 0;
        foreach( $menus as $res ) {
            //get stock level

            $stock = Stock::join("locations","locations.id","=","stocks.location_id")
                            ->where("locations.outlet_id","=",$sess_outlet_id)
                            ->where("item_id",$res->id)->get();

            if(isset($stock) && sizeof($stock)>0) {

                foreach ($stock as $stk) {

                    $stockLevel = StockLevel::where("location_id",$stk->location_id)
                                    ->where('item_id',$res->id)->first();
                    if(isset($stockLevel) && sizeof($stockLevel)>0) {

                        $reserve_qty = $stockLevel->reserved_qty;
                        $stock_qty = $stk->quantity;

                        if ($reserve_qty > 0 && $reserve_qty >= $stock_qty) {

                            $arr[$i]['stock_qty'] = $stock_qty;
                            $arr[$i]['reserved_qty'] = $stockLevel->reserved_qty;
                            $arr[$i]['location'] = $stk->name;
                            $arr[$i]['item'] = $res->item;
                            $arr[$i]['cat_name'] = $res->title;
                            $arr[$i]['unit'] = $res->unit;
                            $i++;
                        }

                    }
                }
            }
        }

        return view('stocks.reserveStock',array('stock'=>$arr));

    }

}
