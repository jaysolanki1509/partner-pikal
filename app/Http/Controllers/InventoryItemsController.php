<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Menu;
use App\MenuTitle;
use App\Outlet;
use App\OutletMapper;
use App\Owner;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class InventoryItemsController extends Controller {

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
		$owner_id = Auth::id();
		$items = Menu::leftjoin('menu_titles as mt','mt.id', '=','menus.menu_title_id')
			->select('menus.id as id','menus.item as item','mt.title as category','menus.price as price')
			 ->where('menus.created_by',$owner_id)
			->where('menus.is_sell',0)
			->get();

		return view('inventoryitems.index', array('items' => $items));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$login_user = Auth::id();
		$menu_owner = Owner::menuOwner();

		$units = Unit::all()->lists('name','id');

		$menu_title_list = array('' => 'Select Category');
		$menu_title = MenuTitle::where('created_by',$menu_owner)->lists('title','id');
		if( isset($menu_title) && sizeof($menu_title) > 0 ) {
			foreach ( $menu_title as $id => $title ) {
				$menu_title_list[$id] = $title;
			}
		}
		$menu_title_list['custom']="(+ New Category)";

		return view('inventoryitems.create',array('OutletId'=>$login_user,'title'=>$menu_title_list,'units'=>$units,'action'=>'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$menu_owner=Owner::menuOwner();
		$input = Input::all();

		if( $input['custom_title'] != "" && $input['menu_title_id'] == 'custom'){
			if( $input['custom_title'] == "" ) {
				return redirect()->back()->withInput(Input::all())->with('error', 'Custom Title is required');
			}
			$success = MenuTitle::where('created_by',$menu_owner)->where('title',$input['custom_title'])->first();

			if(!$success){
				$menuTitle = new MenuTitle();
				$menuTitle->outlet_id = $menu_owner;
				$menuTitle->created_by = $menu_owner;
				$menuTitle->title = strtolower($input['custom_title']);
				$menuTitle->active = "0";//get activation of Menu_Title at database
				$success = $menuTitle->save();
			}else{
				$menuTitle=MenuTitle::getmenutitlebyid($success->id);
			}

		}else{
			$menuTitle=MenuTitle::getmenutitlebyid($input['menu_title_id']);
			$success=1;
		}

		$menu = new Menu();
		$menu->outlet_id = $menu_owner;
		$menu->created_by = $menu_owner;
		$menu->menu_title_id = $menuTitle->id;
		$menu->item_order = 1;

		if (isset($input['item'])) {
			$items = Menu::getMenuByUserId($menu_owner);
			if(isset($items)){
				$add=0;
				foreach($items as $item){
					if(strtolower($item->item)==strtolower($input['item'])){
						$add=1;
						break;
					}
				}
				if($add!=1){
					$menu->item = ucwords($input['item']);
				}else{
					return redirect()->back()->withInput(Input::all())->with('error', $input['item'].' Item Repeated');
				}
			}
		}
		$menu->alias = $input['alias'];
		$menu->price = $input['price'];
		$menu->unit_id = $input['unit_id'];
		$menu->details = $input['details'];
		$menu->food = $input['food'];
		$menu->moq = $input['moq'];
		$menu->reserved = $input['reserved'];
		$menu->expiry = $input['expiry'];
		$menu->active = 0;
		$menu->is_sell = 0;
		$menu->discountable = 0;
		$menu->taxable = 0;
		$success = $menu->save();

		if ( $success ) {
			return Redirect('/inventory/items')->with('success', 'Menu added successfully ');
		} else {
			return redirect()->back()->withInput(Input::all())->with('error','Failed');
		}


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
		$menu_owner = Owner::menuOwner();
		$item = Menu::where('id',$id)->first();

		$units = Unit::all()->lists('name','id');
		$menu_title_list = MenuTitle::where('created_by',$menu_owner)->lists('title','id');
		$menu_title_list['custom']="(Add new Title)";

		//print_r($selected_outlet);exit;
		return view('inventoryitems.edit',array('item'=>$item,'title'=>$menu_title_list,'units'=>$units,'action'=>'edit'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		$menu_owner=Owner::menuOwner();

		if ( strcmp( Input::get('menu_title_id'),'custom' ) == 0 ){
			if( strcmp(Input::get('custom_title'),"") == 0 ){
				return redirect()->back()->withInput(Input::all())->with('error', 'Custom Title is required');
			}
			$temp_title=MenuTitle::where('created_by',$menu_owner)->where('title',Input::get('custom_title'))->first();
			if(!$temp_title){
				$menuTitle = new MenuTitle();
				$menuTitle->outlet_id = $menu_owner;
				$menuTitle->created_by = $menu_owner;
				$menuTitle->title = Input::get('custom_title');
				$menuTitle->active = "0";//get activation of Menu_Title at database
				$success = $menuTitle->save();
			}else{
				$menuTitle=MenuTitle::getmenutitlebyid($temp_title->id);
			}
		}else{
			$menuTitle=MenuTitle::getmenutitlebyid(Input::get("menu_title_id"));
			$success=1;
		}
		if ( Input::get("item") != "" ) {
			$items = Menu::getMenuByUserId($menu_owner);
			if( isset($items) ){
				$add=0;
				foreach( $items as $item ){
					if( strtolower($item->item) == strtolower(Input::get("item")) && $item->id != $id){
						$add=1;
						break;
					}
				}
				if( $add != 1 ){
					$menu_item = Input::get("item");
				}else{
					return redirect()->back()->withInput(Input::all())->with('error', Input::get("item").' Item Repeated');
				}
			}
		}

		$result = Menu::where('id', $id)
						->update(['menu_title_id' => $menuTitle->id,
							'item' => $menu_item,
							'price' => Input::get("price"),
							'details' => Input::get("details"),
							'unit_id' => Input::get("unit_id"),
							'food' => Input::get("food"),
							'alias' => Input::get("alias"),
							'moq'=>Input::get('moq'),
							'reserved'=>Input::get('reserved'),
							'expiry'=>Input::get('expiry')]);

		if( $result ) {
			return Redirect('/inventoryitems')->with('success', 'Item has been updated successfully ');
		} else {
			return redirect()->back()->withInput(Input::all())->with('error','Check your internet connection, Some error occurred');
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
		Menu::where('id',$id)->delete();
		return Redirect('/inventoryitems')->with('success', 'Item has been deleted successfully ');
	}

}
