<?php namespace App\Http\Controllers;

use App\Categories;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ItemMaster;
use App\ItemRequest;
use App\Location;
use App\Menu;
use App\MenuTitle;
use App\Outlet;
use App\Outlet_Menu_Bind;
use App\OutletMapper;
use App\Owner;
//use Illuminate\Http\Request;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\CreateRequestItemRequest;

class RequestItemController extends Controller {

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

		//$item_requests = ItemRequest::getPendingRequestedItemsByOwner_ById($user_id);

		$items = ItemRequest::leftJoin('menus','menus.id','=','item_request.what_item_id')
			->leftJoin('menu_titles','menu_titles.id','=','menus.menu_title_id')
			->select('item_request.id','item_request.location_for', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id', 'menus.menu_title_id', 'menus.item', 'menu_titles.title')
			->groupBy('item_request.owner_to')
			->where('item_request.owner_by','=',$user_id)
			->where('item_request.satisfied','=',"No")->get();

		/*$item_details=$pending_requested_items = ItemRequest::where('item_request.owner_by',$user_id)
                        ->where('satisfied',"No")
                        ->leftJoin('owners','item_request.owner_to','=','owners.id')
                        ->leftJoin('menus','menus.id','=','item_request.what_item_id')
                        ->leftJoin('menu_titles','menu_titles.id','=','menus.menu_title_id')
                        ->select('owners.user_name','item_request.id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id', 'menus.menu_title_id', 'menus.item','menus.unit_id', 'menu_titles.title')
                        ->get();
		//print_r($item_details);exit;
        $d = array('data_table' => $item_details, 'items' => $items, 'user_id' => $user_id, 'check_print'=>'check_request_pending');
        $json_data = json_encode($d);*/

		return view('requestItem.index', array('items' => $items, 'user_id' => $user_id));
		if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
			//return view('requestItem.mobileIndex', array('print_data'=>$json_data,'item_requests' => $item_requests, 'items' => $items, 'user_id' => $user_id));
		} else {
			//return view('requestItem.index', array('print_data'=>$json_data,'item_requests' => $item_requests, 'items' => $items, 'user_id' => $user_id));
		}


	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$user_id = Auth::user()->id;
		$admin = Auth::user()->created_by;
		$user_arr = array();

		if ( isset($admin) || $admin != NULL ) {
			$users = Owner::where('created_by', $admin)->orWhere('id',$admin)->get();
		} else {
			$users = Owner::where('id',$user_id)->orWhere('created_by',$user_id)->get();
		}


		$owners = ['' => 'Select User'];
		if ( isset($users) && sizeof($users) > 0 ) {
			foreach( $users as $u ) {
				$owners[$u->id] = $u->user_name;
				$user_arr[] = $u->id;
			}
		}

		//get user's locations
        $user = Owner::find($user_id);
		$locations = ['' => 'Select Location'];

        $sess_outlet_id = Session::get('outlet_session');

        if(isset($sess_outlet_id) && $sess_outlet_id != '' ){
            $all_location = Location::getLocationByOutletId($sess_outlet_id);
        }
        else if(isset($user->created_by) && $user->created_by != '') {
            $all_location = Location::where('created_by',$user->created_by)->get();
        } else {
            $all_location = Location::where('created_by',$user->id)->get();
        }

		if ( isset($all_location) && sizeof($all_location) > 0 ) {
		    $i = 0;
			foreach( $all_location as $location ) {
				$locations[$location->id] = $location->name;
			}
		}

		//get users' category
		$category = MenuTitle::whereIn('created_by',$user_arr)->get();


		if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
			return view('requestItem.mobileCreate', array('owners' => $owners,'locations'=>$locations,'category' => $category));
		} else {
            return view('requestItem.create', array('owners' => $owners,'locations'=>$locations,'category' => $category));
		}

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CreateRequestItemRequest $request)
	{
		//
//		print_r("Store Request");exit;

		$user_id=Auth::user()->id;
		$input = Request::all();
        $location_id = $input['location_id'];
        $owner_to = $input['owner_id'];
        $req_date = $input['req_date'];
		$success = 0;

		if ( isset($input['cate_id'])) {

		    if($input['cate_id'] == 'all') {
                $menu_owner = Owner::menuOwner();
                $all_menu_titles_id = MenuTitle::getMenuTitleByCreatedBy($menu_owner)->lists('id');
                $items = Menu::wherein('menu_title_id', $all_menu_titles_id)->get();
            }else{
                $items = Menu::where('menu_title_id', $input['cate_id'])->get();
            }

			if ( isset($items) && sizeof($items) > 0 ) {
				foreach( $items as $itm ) {
					if (isset($input['req_qty-' . $itm->id]) && $input['req_qty-' . $itm->id] != '') {

						$itemRequest = new ItemRequest();
						$itemRequest->what_item_id = $itm->id;
						$itemRequest->unit_id = $input['unit_id-' . $itm->id];
						$itemRequest->what_item = $itm->item;
						$itemRequest->owner_to = $owner_to;
						$itemRequest->owner_by = $user_id;
						$itemRequest->when = $req_date." ".date('H:i:s');
						$itemRequest->qty = $input['req_qty-'. $itm->id];
						$itemRequest->existing_qty = $input['exi_qty-' . $itm->id];
						$itemRequest->satisfied = 'No';
						$itemRequest->location_for = $location_id;
						$success = $itemRequest->save();
					}

				}
				if($success!=1){
					return Redirect('/requestItem/create')->with('error', 'You did not Enter any values in any fields.')->with('location_id',$location_id)->with('owner_to',$owner_to)->with('req_date',$req_date);
				}
				if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
					return Redirect('/requestItem/create')->with('success', 'Item Request added successfully ')->with('location_id',$location_id)->with('owner_to',$owner_to)->with('req_date',$req_date);
				} else {
					return Redirect('/requestItem/create')->with('success', 'Item Request added successfully ')->with('location_id',$location_id)->with('owner_to',$owner_to)->with('req_date',$req_date);
				}
			}
		} else {
			if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
				return Redirect('/requestItem/create')->with('error', 'Item Request Not Added ')->with('location_id',$location_id)->with('owner_to',$owner_to);
			} else {
				return Redirect('/requestItem/create')->with('error', 'Item Request Not Added ')->with('location_id',$location_id)->with('owner_to',$owner_to);
			}

		}


        /*$success=0;
		if(isset($input['count'])) {
			$count = $input['count'];
            $min_id=$input['min_id'];
//			$current_date = Carbon::now();
			$today = date('Y-m-d');

			for ($i = $min_id; $i <= $count; $i++) {
				//$item = ItemMaster::getItemsByItemId($i);
				$item = Menu::where('id','=',$i)->first();
				$itemRequest = new ItemRequest();
				if (isset($input['req_qty' . $i])) {
					if ($input['req_qty' . $i] != "") {
						$itemRequest->what_item_id = $item->id;
						$itemRequest->what_item = $item->item;
						$itemRequest->owner_to = $input['owner_id'];
						$itemRequest->owner_by = $user_id;
						$itemRequest->when = $today;
						$itemRequest->qty = $input['req_qty' . $i];
						$itemRequest->existing_qty = $input['exi_qty' . $i];
						$itemRequest->satisfied = 'No';
						$success = $itemRequest->save();
					}
				}
			}
            if($success!=1){
                return Redirect('/requestItem/create')->with('error', 'You did not Enter any values in any fields.');
            }
			if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
				return Redirect('/requestItem/create')->with('success', 'Item Request added successfully ');
			} else {
				return Redirect('/requestItem/create')->with('success', 'Item Request added successfully ');
			}

		} else {
			if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
				return Redirect('/requestItem/create')->with('error', 'Item Request Not Added ');
			} else {
				return Redirect('/requestItem/create')->with('error', 'Item Request Not Added ');
			}

		}*/



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
//		print_r("Show");exit;
		return view('requestItem.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

		$user_id = Auth::user()->id;
		$admin = Auth::user()->created_by;
		$user_arr = array();

		if ( isset($admin) || $admin != NULL ) {
			$users = Owner::where('created_by', $admin)->orWhere('id',$admin)->get();
		} else {
			$users = Owner::where('id',$user_id)->orWhere('created_by',$user_id)->get();
		}

		$owners = ['' => 'Select User'];
		if ( isset($users) && sizeof($users) > 0 ) {
			foreach( $users as $u ) {
				$owners[$u->id] = $u->user_name;
				$user_arr[] = $u->id;
			}
		}

		//get user's locations
		$user = Owner::find($user_id);
		$locations = ['' => 'Select Location'];

		$sess_outlet_id = Session::get('outlet_session');

		if(isset($sess_outlet_id) && $sess_outlet_id != '' ){
			$all_location = Location::getLocationByOutletId($sess_outlet_id);
		}
		else if(isset($user->created_by) && $user->created_by != '') {
			$all_location = Location::where('created_by',$user->created_by)->get();
		} else {
			$all_location = Location::where('created_by',$user->id)->get();
		}

		if ( isset($all_location) && sizeof($all_location) > 0 ) {

			foreach( $all_location as $location ) {
				$locations[$location->id] = $location->name;
			}
		}

		$request = ItemRequest::join('menus as m','m.id','=','item_request.what_item_id')
								->join('unit as u','u.id','=','m.unit_id')
								->select('item_request.*','m.item as item','u.id as unit_id','u.name as unit_name','m.secondary_units as other_units')
								->where('item_request.id',$id)->first();

		//order_units
		$unit_arr = Unit::all()->lists('name','id');

		if ( isset($request) && sizeof($request) > 0 ) {

			$ot_unit[$request->unit_id] = $request->unit_name;
			if( isset($request->other_units) && $request->other_units != '' ) {
				$ounits = json_decode($request->other_units);
				foreach( $ounits as $key=>$u ) {
					$ot_unit[$key] = Unit::find($key)->name;
				}
			}
		}

        $user_id = Auth::id();
        $admin = Auth::user()->created_by;

        $user_arr[] = $user_id;

        if( isset($admin)) {
            $user_arr[] = $admin;

        }

        $item_list = Menu::leftjoin('menu_titles','menu_titles.id', '=','menus.menu_title_id')
            ->leftjoin('unit','unit.id','=','menus.unit_id')
            ->whereIn('menus.created_by',$user_arr)
            ->select('menus.id as id','menus.item as item','menus.secondary_units as other_units','menus.order_unit as order_unit','menu_titles.title as category','menus.unit_id as unit_id','unit.name as unit_name')
			->get();

        $item_arr = array();
        if(isset($item_list) && sizeof($item_list)>0){
			$cnt = 0;
            foreach ($item_list as $item) {

                $item_arr[$cnt]['id'] = $item->id;
				$item_arr[$cnt]['name'] = $item->item;
				$item_arr[$cnt]['unit_id'] = $item->unit_id;
				$item_arr[$cnt]['unit_name'] = $item->unit_name;
				$item_arr[$cnt]['order_unit'] = $item->order_unit;


				$cnt++;
            }
        }


		return view('requestItem.edit',array('item_list'=>$item_arr,'request'=>$request,'locations'=>$locations,'owners'=>$owners,'unit'=>$unit_arr,'ot_unit'=>$ot_unit));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$messages = [
			'what_item_id.required' => 'Item is required!',
			'what_item.required' => 'Item is required!',
			'unit_id.required' => 'Required unit is required',
			'when.required'=>'Request date is required',
			'location_for.required'=>'Location for is required'
		];

		$p = Validator::make(Input::all(), [
			'what_item_id' => 'required',
			'what_item' => 'required',
			'unit_id' => 'required',
			'when' => 'required',
			'location_for' => 'location_for'
		],$messages);

		$request = ItemRequest::find($id);
		$request->what_item_id = Input::get('what_item_id');
		$request->what_item = Input::get('what_item');
		$request->qty = Input::get('qty');
		$request->unit_id = Input::get('unit_id');
		$request->existing_qty = Input::get('existing_qty');
		$request->when = Input::get('when')." ".date('H:i:s');
		$request->location_for = Input::get('location_for');
		$request->owner_to = Input::get('owner_to');
		$resutl = $request->save();

		if ( $resutl) {
			return Redirect('/requestItem')->with('success', 'Item Request updated successfully ');
		} else {
			return redirect()->back()->withInput(Input::all())->withErrors($p->errors());

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
		ItemRequest::where('id',$id)->delete();
		Session::flash('success', 'Request has been deleted successfully!');
		return Redirect::to('requestItem');
	}

	public function ajaxOwnerList()
	{
		$outlet_id = Input::get('outlet_id');

		$outlet_mappers = OutletMapper::getOutletMapperByOutletId($outlet_id);

		foreach($outlet_mappers as $outlet_mapper)
		{
			$outlet_mapper_arr[] = $outlet_mapper['owner_id'];
		}

		$owners = Owner::whereIn('id',$outlet_mapper_arr)->get();

		$categories = Categories::all();
		if( sizeof($owners) > 0 && $owners != ''){
			$owner_select = '<option value="" selected >Select Owner</option>';
			$cate_select = '<option value="" selected >Select Categories</option>';

			foreach( $owners as $owner ){
				//$menu_item = Menu::getMenuItemByTitleIdandMenuId($recipe->menu_item_id);
				$owner_select .= '<option value="'.$owner->id.'">'.$owner->user_name.'</option>';
			}

			foreach( $categories as $cate)
			{
				$cate_select .= '<option value="'.$cate->id.'">'.$cate->title.'</option>';
			}

			return array('owner_select' => $owner_select, 'cate_select' => $cate_select);

		}
		/*else{
			$owner_select = '<option value="0" selected >No Recipe In This This Outlet</option>';
			$cate_select = '<option value="0" selected >No Recipe In This This Outlet</option>';
			return array('owner_select' => $owner_select, 'cate_select' => $cate_select);
		}*/

	}

	public function ajaxItems()
	{
		$cate_id = Input::get('cate_id');

		$items = ItemMaster::getItemsByCategoryId($cate_id);

		$cate_items = '';
		foreach($items as $item)
		{
//			$count = $item->id;
			$cate_items .= '<div class="col-md-12">
								<div class="col-md-2"></div>
								<div class="col-md-2">
									<label class="control-label">'.$item->item_name.'</label>
								</div>

								<div class="col-md-2">
									<label class="control-label">'.$item->unit.'</label>
								</div>

								<div class="col-md-2">
									<label class="control-label">'.$item->current_stock.'</label>
								</div>

								<div class="col-md-2">
									<input type="number" id="req_qty'.$item->id.'" name="req_qty'.$item->id.'" placeholder="Request Qty">
								</div>
								<input type="hidden" value="'.$item->id.'" name="count">
							</div>';
		}

		return array('cate_items' => $cate_items);
	}

	public static function getItem()
	{
		$user_id = Input::get('owner_id');
		$cate_id = Input::get('cate_id');
		$location_id = Input::get('location_id');
        $location_name = Location::getLocationById($location_id)->name;
		$req_date = Input::get('req_date');

		//$items = Menu::getmenubymenutitleid($cate_id);
		$items = Menu::getItemsQuanityonLocation($cate_id,$location_id);


		if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
			return view('requestItem.mobileItems',array('user_id'=>$user_id,'cate_id'=>$cate_id, 'location_name'=>$location_name, 'items' => $items,'req_date'=>$req_date));
		} else {
            return view('requestItem.items',array('user_id'=>$user_id,'cate_id'=>$cate_id, 'location_name'=>$location_name, 'items' => $items,'req_date'=>$req_date));
		}

	}

    public function addCategory(){
        $cat_name=Input::get("name_category");
        $display_number=DB::table('categories')->max('display_order');
        //print_r($display_number);exit;
        DB::table('categories')->insert(
            ['title' => $cat_name,'display_order'=> $display_number+1]
        );
        return Redirect('/requestItem/create')->with('success', 'Item Added.');
    }

    public function deleteCategory($id){
        $del_cat_id=$id;
        if(DB::table('item_master')->where('catagory_id','=',$del_cat_id)->get()!=null)
            DB::table('item_master')->where('catagory_id','=',$del_cat_id)->delete();
        DB::table('categories')->where('id', '=', $del_cat_id)->delete();
        return Redirect('/requestItem/create')->with('success', 'Item Deleted.');
    }

    public function destroyAll(){

        $allRequest = Input::get("allreq");
        if(isset($allRequest) && sizeof($allRequest)>0){
            foreach ($allRequest as $key=>$req_id){
                ItemRequest::where('id',$req_id)->delete();
            }
            echo "success";
        }else{
            echo "error";
        }
    }

}
