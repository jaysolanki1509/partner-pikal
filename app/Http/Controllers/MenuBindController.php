<?php namespace App\Http\Controllers;

use App\CuisineType;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\MenuTitle;
use App\Outlet_Menu_Bind;
use App\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Outlet;
use App\OutletMapper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Menu;
use App\MenuOption;

class MenuBindController extends Controller {

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
		$menu_owner=Owner::menuOwner();

		$menus=DB::table("menu_titles")->where('created_by',$menu_owner)->lists('title','id');
		$menus[0] = 'Select Category';
		ksort($menus);

		return view('MenuBind.bind',array( 'user_id' => $user_id,'menu'=>$menus));

		/*$menu=DB::table("menu_titles")
			->select('menus.*','menu_titles.title')
			  ->join("outlet_menu_bind","outlet_menu_bind.menu_id","=","menu_titles.id")
			  ->join("menus","menus.id","=","outlet_menu_bind.item_id")
			  ->where('outlet_menu_bind.outlet_id','1')
			  ->groupby("menus.id")
			  ->get();

		$a=array();
		foreach($menu as $m) {

			$cuisine=CuisineType::find($m->cuisine_type_id);
			$inner_array=array('item_id'=>$m->id,
				'item'=>$m->item,
				'price'=>$m->price,
				'details'=>$m->details,
				'cuisinetype'=>$cuisine,
				'options'=>$m->options,
				'foodtype'=>$m->food,
				'active'=>$m->active,
				'like'=>$m->like);
			if(!array_key_exists($m->title,$a)) {
				$a[$m->title][] = $inner_array;
			} else {
				array_push($a[$m->title],$inner_array);
			}
		}

		print "<pre>";print_r($a); */

	}



	public function getoutletmemu($restaurant_id) {
		$restmenu = array();
		$Outletid = $restaurant_id;
		$menutitle =MenuTitle::getmenutitlebyrestaurantid($Outletid);

		foreach ($menutitle as $menudetails) {
			$menutitle = $menudetails->title;
			//print_r($menutitle);exit;
			$menud = Menu::getmenubymenutitleid($menudetails->id);

			$i = 0;

			foreach ($menud as $cui) {
				$menuoption=MenuOption::where('menu_id',$cui->id)->get();
				$cuisinetype='';
				if(isset($cui->food) && $cui->food!=''){
					$foodtype=$cui->food;
				}else{
					$foodtype='';
				}
				if ($cui->menu_title_id == $menudetails->id) {
					$restmenu[$menutitle][$i] = array(
						'item_id' => $cui->id,
						'item' => $cui->item,
						'price' => (int)$cui->price,
						'details' => $cui->details,
						'cuisinetype' => $cuisinetype,
						'options' => $cui->options,
						'foodtype'=>$foodtype,
						'active'=>$cui->active,
						'options'=>$menuoption,
						'like'=>$cui->like
					);
				}
				$i++;
			}
		}
		return $restmenu;
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

		//print "<pre>";print_r($_REQUEST);
		$a=json_decode($_REQUEST['binddata']);

		$user_id = Auth::id();
		$menu_owner=Owner::menuOwner();
		//get mapped outlets
		$outlet_mappers = OutletMapper::getOutletMapperByOwnerId($user_id);
		$ol_id = array();
		if ( isset($outlet_mappers) && sizeof($outlet_mappers) > 0 ) {

			foreach( $outlet_mappers as $ot ) {
				$ol_id[] = $ot->outlet_id;
			}

			$menuid=$_REQUEST['menus'];
			foreach($a as $b=>$d) {

				//if user unbind item from outlet than unbind it
				$diff = array_diff($ol_id,$d);

				if( isset($diff) && sizeof($diff) > 0 ) {
					foreach( $diff as $ol_unbind_id ) {
						//check menu item bind or not if bind then unbind this item
						$ol_result = Outlet_Menu_Bind::where('outlet_id',$ol_unbind_id)
													->where('item_id',$b)
													->where('menu_id',$menuid)
													->count();

						if ( $ol_result > 0 ) {

							Outlet_Menu_Bind::where('outlet_id',$ol_unbind_id)
								->where('item_id',$b)
								->where('menu_id',$menuid)
								->delete();
						}
					}
				}

				if( isset($d) && sizeof($d) > 0 ) {
					//if menu item not bind than bind it with outlet
					foreach ($d as $c) {

						$count = DB::table("outlet_menu_bind")->where('outlet_id', $c)->where('item_id', $b)->where('menu_id', $menuid)->count();
						if ($count == 0) {
							$menu_bind = new Outlet_Menu_Bind();
							$menu_bind->item_id = $b;
							$menu_bind->menu_id = $menuid;
							$menu_bind->outlet_id = $c;
							$menu_bind->save();
						}

					}
				}
			}
			return Redirect('/menuBind')->with('success','Outlets have been successfully bound with the menu.');

		} else {
			return Redirect('/menuBind')->with('error','There is no outlet mapped');
		}

        //return view('MenuBind.bind',array('success' => 'Outlets are successfully bind with menu.'));
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

	public function ajaxMenuItemsList(){

		$title_id = Input::get('title_id');
		$user_id = Auth::id();
		$menu_owner=Owner::menuOwner();
		//getting menu items of menu title
		$menuItems = '';
		if( isset($title_id) ){
            $menuItems=Menu::leftJoin('outlet_menu_bind as omb','omb.item_id','=','menus.id')
							->select('menus.*',DB::raw("group_concat(omb.outlet_id SEPARATOR ',') as map_outlet_id"))
							->where('menus.menu_title_id', $title_id )
							->where('menus.created_by', $menu_owner)
							->groupBy('menus.id')
							->get();
		}

		//getting outlets mapped with user
		$outlet_mappers = OutletMapper::getOutletMapperByOwnerId($user_id);
		$select_outlets=[];
		foreach($outlet_mappers as $outlet)
		{
			$out=Outlet::find($outlet->outlet_id);
			array_push($select_outlets,$out);
			//$select_outlets[$out->id] = $out->name;
		}

		//getting item bind with outlets
		$bind_items = '';


		//$outlets=DB::table('outlets')->select('id','name')->get();
		$menus=array('Items' => $menuItems,'Outlets'=>$select_outlets);
		return json_encode($menus);


	}

}
