<?php namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Ingredients;
use App\ItemOptionGroup;
use App\ItemOptionGroupMapper;
use App\ItemSettings;
use App\Menu;
use App\MenuItemOption;
use App\OrderCancellation;
use App\OrderItem;
use App\Outlet;
use App\MenuTitle;
use App\Outlet_Menu_Bind;
use App\OutletMapper;
use App\OutletSetting;
use App\Owner;
use App\RecipeDetails;
use App\Unit;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\CuisineType;
use App\Http\Requests\CreateMenuRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\menu_option;
use App\Http\Requests\CreateMenuOptionRequest;



class MenuController extends Controller {

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
        $menu_owner=Owner::menuOwner();
        $user_id=$menu_owner;
        $acc_id = Auth::user()->account_id;
        $account = Account::find($acc_id);

        $outlet_id = Session::get('outlet_session');


        if ($request->ajax())
        {
            $input = Input::all();
            $response = array();

            $search = $input['sSearch'];

            $sort = $input['sSortDir_0'];
            $sortCol=$input['iSortCol_0'];
            $sortColName=$input['mDataProp_'.$sortCol];

            $sort_field = 'menus.menu_title_id';
            //sort by column
            if ( $sortColName == "title" ) {
                $sort_field = 'mt.title';
            } elseif ( $sortColName == "item" ) {
                $sort_field = 'menus.item';
            } elseif ( $sortColName == "price" ) {
                $sort_field = 'menus.price';
            } elseif ( $sortColName == "lpp" ) {
                $sort_field = 'menus.buy_price';
            } elseif ( $sortColName == "item_code" ) {
                $sort_field = 'menus.item_code';
            } else {
                $sort_field = 'menus.menu_title_id';
                $sort = 'ASC';
            }

            $total_colomns = $input['iColumns'];
            $search_col = '';$query_filter = '';

            for ( $j=0; $j<=$total_colomns; $j++ ) {

                if ( $j == 0 )continue;

                if ( isset($input['sSearch_'.$j]) && $input['sSearch_'.$j] != '' ) {

                    $search = $input['sSearch_'.$j];
                    $searchColName = $input['mDataProp_'.($j-1)];
                    //echo $searchColName;exit();

                    if ( $searchColName == 'item' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND menus.item like '%$search%'";
                        } else {
                            $search_col = "menus.item like '%$search%'";
                        }

                    } else if ( $searchColName == 'item_code' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND menus.item_code like '%$search%'";
                        } else {
                            $search_col = "menus.item_code like '%$search%'";
                        }

                    } else if ( $searchColName == 'title') {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND menus.menu_title_id = '$search'";
                        } else {
                            $search_col = "menus.menu_title_id = '$search'";
                        }

                    } else if ( $searchColName == 'lpp') {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND menus.buy_price = '$search'";
                        } else {
                            $search_col = "menus.buy_price = '$search'";
                        }

                    } else if ( $searchColName ==  'price' ) {

                        if ( isset($search_col) && $search_col != '' ) {
                            $search_col .= " AND menus.price = '$search'";
                        } else {
                            $search_col = "menus.price = '$search'";
                        }

                    }

                }

            }

          // echo $search_col;exit;

            if ( $search_col == '')$search_col = '1=1';

            $where = 'menus.created_by ='. $user_id .' AND ';

            $total_records = Menu::leftjoin('menu_titles as mt','mt.id', '=','menus.menu_title_id')
                ->select('menus.id as id','menus.item as item','menus.item_order as item_order','menus.item_code as item_code','mt.title as title','menus.price as price','menus.buy_price as lpp')
                ->whereRaw(" $where ($search_col)")
                ->orderBy('item_order')
                ->count();

            $menu_result = Menu::leftjoin('menu_titles as mt','mt.id', '=','menus.menu_title_id')
                ->select('menus.id as id','menus.active as active','menus.item_code as item_code','menus.item as item','menus.item_order as item_order','mt.title as title','menus.price as price','menus.image as image','menus.buy_price as lpp')
                ->whereRaw(" $where ($search_col)")
                ->take($input['iDisplayLength'])
                ->skip($input['iDisplayStart'])
                ->orderBy($sort_field, $sort)
                ->orderBy('menus.item_order')
                ->get();


            if ( $total_records > 0 ) {

                $i = 0;
                foreach ( $menu_result as $menu ) {

                    if ( $menu->active == 1 ) {
                        $cat_name = "<i>".$menu->title."</i>";
                        $item_name = "<i>".$menu->item."</i>";
                        $item_code = "<i>".isset($menu->item_code)?$menu->item_code:"NA"."</i>";
                        $price = "<i>".number_format($menu->price,2,'.','')."</i>";
                        $lpp = "<i>".number_format($menu->lpp)."</i>";
                    } else {
                        $cat_name = $menu->title;
                        $item_name = $menu->item;
                        $item_code = isset($menu->item_code)?$menu->item_code:"NA";
                        $price = number_format($menu->price,2,'.','');
                        $lpp = number_format($menu->lpp);
                    }

                    $response['result'][$i]['DT_RowId'] = $menu->id;

                    if ( isset($menu->image)) {
                        $response['result'][$i]['image'] = '<image src="' . $menu->image . '" width="48px" alt="Item-Image" height="48px"/>';
                    } else {
                        $response['result'][$i]['image'] = '<image src="/pikal-icon.png" alt="Item-Image" width="48px" height="48px"/>';
                    }

                    $response['result'][$i]['check_col'] = "<input class='checkbox1' name='menu_box' id='$menu->id' onclick=selectRow('$menu->id') type='checkbox' />";
                    $response['result'][$i]['drag'] = '<i class="zmdi zmdi-swap-vertical zmdi-hc-lg"></i>';
                    $response['result'][$i]['title'] = $cat_name;
                    $response['result'][$i]['item'] = (strlen($item_name) > 13) ? substr($item_name,0,20).'...' : $item_name;
                    $response['result'][$i]['item_code'] = $item_code;
                    $response['result'][$i]['price'] = $price;
                    $response['result'][$i]['lpp'] = $lpp;

                    $visibility = '';
                    $itm_setting = ItemSettings::where('item_id',$menu->id)->where('outlet_id',$outlet_id)->first();
                    if ( isset($itm_setting) && sizeof($itm_setting) > 0 ) {

                        if ( $itm_setting->is_sale == 1 ) {
                            $visibility .='<span class="label label-success" style="cursor: pointer;" onclick=changeSetting(this,"is_sale")>Is Sale</span><br>';
                        } else {
                            $visibility .='<span class="label label-default" style="cursor: pointer;" onclick=changeSetting(this,"is_sale")>Is Sale</span><br>';
                        }

                        if ( $itm_setting->is_active == 0 ) {
                            $visibility .='<span class="label label-success" style="cursor: pointer;" onclick=changeSetting(this,"is_active")>Is Active</span><br>';
                        } else {
                            $visibility .='<span class="label label-default" style="cursor: pointer;" onclick=changeSetting(this,"is_active")>Is Active</span><br>';
                        }

                        /*if ( $itm_setting->is_bind == 1 ) {
                            $visibility .='<span class="label label-success" onclick=changeSetting(this,"is_bind",0)>Bind</span><br>';
                        } else {
                            $visibility .='<span class="label label-default" onclick=changeSetting(this,"is_bind",1)>Bind</span><br>';
                        }*/

                    } else {

                        $visibility = '<span class="label label-default" style="cursor: pointer;" onclick=changeSetting(this,"is_sale")>Is Sale</span><br>
                                        <span class="label label-default" style="cursor: pointer;" onclick=changeSetting(this,"is_active")>Is Active</span><br>';

                    }

                    //check item has been bind or not
                    $itm_bind = Outlet_Menu_Bind ::where('item_id',$menu->id)->where('outlet_id',$outlet_id)->first();

                    if ( isset($itm_bind) && sizeof($itm_bind) > 0 ) {

                        $visibility .='<span class="label label-success" style="cursor: pointer;" onclick=changeSetting(this,"is_bind")>Is Bind</span>';

                    } else {

                        $visibility .='<span class="label label-default" style="cursor: pointer;" onclick=changeSetting(this,"is_bind")>Is Bind</span>';

                    }


                    $response['result'][$i]['visibility'] = $visibility;

                    if( isset($account) && $account->enable_inventory == 1 ) {
                        $response['result'][$i]['action'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/menu/' . $menu->id . '/edit" title="Edit"><span class="zmdi zmdi-edit" ></span></a><br><hr style="margin-top: 5px;margin-bottom: 5px">' .
                            '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="menu/' . $menu->id . '/show" title="Detail"><span class="zmdi zmdi-file-text"></span></a><br><hr style="margin-top: 5px;margin-bottom: 5px">' .
                            '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" title="Delete" onclick="warn(this,' . $menu->id . ')"><span class="zmdi zmdi-close"></span></a>';
                    }else{
                        $response['result'][$i]['action'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/menu/' . $menu->id . '/edit" title="Edit"><span class="zmdi zmdi-edit" ></span></a><br><hr style="margin-top: 5px;margin-bottom: 5px">' .
                            '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" title="Delete" onclick="warn(this,' . $menu->id . ')"><span class="zmdi zmdi-close"></span></a>';
                    }
                    $is_recipe = RecipeDetails::where('menu_item_id',$menu->id)->first();
                    /*if(isset($is_recipe) && sizeof($is_recipe)>0) {
                        $response['result'][$i]['action'] .= '&nbsp;<a class="btn btn-primary" href="/recipeDetails/'.$is_recipe->id.'/edit"><i class="fa fa-file-text-o"></i></a>';
                    }*/

                    $i++;
                }


            } else {
                $total_records = 0;
                $response['result'] = array();
            }

            $categories = MenuTitle::where('created_by',$user_id)
                                    ->orderBy('title_order')->get();
            $response['categories'] = $categories;

            $response['iTotalRecords'] = $total_records;
            $response['iTotalDisplayRecords'] = $total_records;
            $response['aaData'] = $response['result'];
	    
  	    return json_encode($response);

        }

        return view('menus.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function create()
    {
        $login_user=Auth::id();
        $menu_owner=Owner::menuOwner();

        $myOutlets_id=OutletMapper::getOutletMapperByOwnerId($login_user)->lists("outlet_id");
        $myOutlets=Outlet::whereIn('id',$myOutlets_id)->lists('name','id');
        $units=Unit::all()->lists('name','id');
        $tax_slot_array[''] = 'Select Tax Slot';

        $menu_title_list = array('' => 'Select Category');
        $menu_title = MenuTitle::where('created_by',$menu_owner)->lists('title','id');
        if( isset($menu_title) && sizeof($menu_title) > 0 ) {
            foreach ( $menu_title as $id => $title ) {
                $menu_title_list[$id] = $title;
            }
        }
        $outlet_id = Session::get('outlet_session');
        if(isset($outlet_id) && $outlet_id->count() > 0) {
            $outlet = Outlet::find($outlet_id);
            if(isset($outlet) && $outlet->id) {
                $taxes = json_decode($outlet->taxes);
                if(isset($taxes) && !empty($taxes)){
                    foreach ($taxes as $tax_title=>$child_tax){
                        $tax_slot_array[$tax_title] = $tax_title;
                    }
                }else{
                    $tax_slot_array[""] = 'No Taxes';
                }
            }else{
                print_r("No Outlet Found");exit;
            }
        }else{
            print_r("Please Enter Outlet Data");exit;
        }

        //get outlet item option groups
        $option_groups = ItemOptionGroup::where('outlet_id',$outlet_id)->get();

        //check itemwise discount enable or not
        $check_itemwise_dis = OutletSetting::checkAppSetting( $outlet_id, "itemWiseDiscount" );

        //check itemwise tax enable or not
        $check_itemwise_tax = OutletSetting::checkAppSetting( $outlet_id, "itemWiseTax" );

        return view('menus.create', array(
                                            'itemwise_tax'=>$check_itemwise_tax,
                                            'itemwise_dis'=>$check_itemwise_dis,
                                            'tax_slab'=>$tax_slot_array,
                                            'option_groups'=>$option_groups,
                                            'OutletId'=>$login_user,
                                            'title'=>$menu_title_list,
                                            'units'=>$units,'action'=>'add',
                                            'myoutlets'=>$myOutlets
                                        ));

    }

    public function importmenuexcel(){

        $error_string='';
        if (Input::hasFile('file1')) {
            $menu_owner=Owner::menuOwner();

            $file = Input::file('file1');
            $outlet_id=$menu_owner;
            $type =($file->getMimeType());

            if ($type == 'application/vnd.ms-office' || $type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ){
                $path = $file->getRealPath();
                //MenuTitle::deletemenutitleofoutlet($outlet_id);
                //Menu::deletemenuofoutlet($outlet_id);
                Excel::selectSheets('Sheet1')->load($path, function($reader) use($outlet_id,$error_string){
                    // Getting all results
                    $outlet = Outlet::where('id',Session::get('outlet_session'))->get();

                    $login_user =Auth::id();
                    $owner= Owner::where('id',$login_user)->select('created_by')->first();
                    if($owner->created_by!=""){
                        $menu_owner=$owner->created_by;
                    }else{
                        $menu_owner=$login_user;
                    }

                    $results = $reader->get();
                    $title="";

                    $menutitleAll = MenuTitle::getMenuTitleByUserId($menu_owner);

                    $menuAll = Menu::where('created_by',$menu_owner)->get();

                    $title_check="";
                    foreach($menutitleAll as $menutitleOne)
                    {
                        $count = 0;
                        foreach($results as $result_title)
                        {
                            $tmp_stored_title = $menutitleOne->title;
                            $tmp_sheet_title = $result_title->title;
                            $title_check=$result_title->title;
                            if(strtolower($tmp_stored_title) == strtolower($tmp_sheet_title)){
                                $count ++;
                                $check_menutitlestored = MenuTitle::where('created_by',$menu_owner)->where('id',$menutitleOne->id)->first();
                                $check_menutitlestored->active = 0;
                                $check_menutitlestored->title_order = $result_title->title_order;
                                $check_menutitlestored->save();
                            }
                        }
                        if($count<1 && $title_check==""){
                            return Redirect::back()->with('error','File is uploaded with wrong data format');
                        }
                        if($count < 1){
                            //$check_menutitlestored = MenuTitle::getmenutitleofoutletandid($outlet_id,$menutitleOne->id);
                            $check_menutitlestored = MenuTitle::where('created_by',$menu_owner)->where('id',$menutitleOne->id)->first();
                            $check_menutitlestored->active = 1;
                            $check_menutitlestored->save();
                        }
                    }

                    $item_check="";
                    foreach($menuAll as $menuOne)
                    {
                        $count = 0;
                        foreach($results as $result_item)
                        {
                            $tmp_storedmenu_item = $menuOne->item_code;
                            $tmp_sheet_item = $result_item->item_code;
                            $item_code_check= $result_item->item_code;
                            $item_check= $result_item->item;

                            if(strtolower($tmp_storedmenu_item) == strtolower($tmp_sheet_item) && trim($result_item->item_code) != ""){
                                $count ++;
                                $outlet_item = Menu::where('created_by',$menu_owner)->where('item_code',$menuOne->item_code)->first();
                                $outlet_item->item_order = $result_item->item_order;
                                $outlet_item->active = 0;
                                $outlet_item->save();
                            }
                        }

                        if($count<1 && $item_check=="" && $item_code_check==""){
                            return Redirect::back()->with('error','File is uploaded with wrong data format');
                        }
                        if($count < 1){
                            //$outlet_item = Menu::getmenuitembyoutletid($outlet_id,$menuOne->item);
                            $outlet_item = Menu::where('created_by',$menu_owner)->where('item_code',$menuOne->item_code)->first();
                            $outlet_item->active = 1;
                            $outlet_item->save();
                        }
                        else{
                            //$outlet_item = Menu::getmenuitembyoutletid($outlet_id,$menuOne->item);
                        }

                    }
                    foreach($results as $result)
                    {
                        if(trim($result->title)=='' || trim($result->item)=='' || trim($result->item_code)=='' || trim($result->unit) == '' ){
                            return Redirect::back()->with('error','Some Items will not add because of blank values.');
                        }
                        $menu = new Menu();
                        $menutitle=new MenuTitle();
                        // $menutitlestored=MenuTitle::getmenutitleofoutletandname($outlet_id,$result->title);
                        $menutitlestored=DB::table('menu_titles')->where('created_by',$menu_owner)->where('title',$result->title)->first();

                        $item_code = $result->item_code;

                        //  $outlet_item = Menu::getmenuitembyoutletid($outlet_id,$item);
                        $outlet_item = Menu::where('created_by',$menu_owner)->where('item_code',$item_code)->first();

                        $unit_arr = Unit::getUnitIdbyName($result->unit);
                        if ( isset($unit_arr) && sizeof($unit_arr) > 0 ) {
                            $unit_id = $unit_arr->id;
                        } else {
                            $unit_obj = new Unit();
                            $unit_obj->name = $result->unit;
                            $unit_obj->save();
                            $unit_id = $unit_obj->id;
                        }

                        if(sizeof($outlet_item)<1){
                            if ( sizeof($menutitlestored) > 0 ){
                                $title=$menutitlestored->id;
                            }else{

                                // Compare menu item if same manu item but title is different than update title.

                                if(sizeof($outlet_item)>0){
                                    // $menutitle = MenuTitle::getmenutitleofoutletandid($outlet_id,$outlet_item->menu_title_id);
                                    $menutitle =MenuTitle::where('created_by',$menu_owner)->where('id',$outlet_item->menu_title_id)->first();

                                    $menutitle->created_by = $menu_owner;
                                    $menutitle->title = $result->title;
                                    $menutitle->title_order = $result->title_order;
                                    $menutitle->active = 0;
                                    $success = $menutitle->save();
                                    $title=$menutitle->id;
                                }else{
                                    if( isset($result->title) && $result->title!="" ){
                                        $menutitle->outlet_id = $outlet_id;
                                        $menutitle->created_by = $menu_owner;
                                        $menutitle->title=$result->title;
                                        $menutitle->title_order = $result->title_order;
                                        $menutitle->active = 0;
                                        $success= $menutitle->save();
                                        $title=$menutitle->id;
                                    }
                                }

                            }
                            if ( $title != "") {

                                // Compare Menu item if item is already there than it not writen again.

                                // $check_item = Menu::getmenubymenutitleidanditemname($title,$outlet_id,$result->item);
                                $check_item = Menu::where('menu_title_id', $title)->where('item_code', $result->item_code)->where('created_by',$menu_owner)->first();


                                if ( sizeof($check_item) > 0 ){

                                    $check_item->menu_title_id = $title;
                                    $check_item->outlet_id = $outlet_id;
                                    $check_item->item = $result->item;
                                    if(isset($result->image) && trim($result->image) != "" ) {
                                        $check_item->image = $result->image;
                                    }else{
                                        $check_item->image = NULL;
                                    }
                                    if(is_numeric($result->price)) {
                                        $check_item->price = $result->price;
                                        $check_item->buy_price = isset($result->purchase_price)?$result->purchase_price:$result->price;
                                    }else{
                                        $error_string.=$result->item.", ";
                                        continue;
                                    }

                                    if($result->alias != "")
                                        $check_item->alias = $result->alias;
                                    else
                                        $check_item->alias = "";

                                    //add items
                                    if(isset($result->details))
                                        $check_item->details = $result->details;
                                    else
                                        $check_item->details = "";

                                    if(isset($result->foodtype)){
                                        if(strcmp(strtolower($result->foodtype),"veg")==0)
                                            $check_item->food="veg";
                                        elseif(strcmp(strtolower($result->foodtype),"nonveg")==0)
                                            $check_item->food="nonveg";
                                    }

                                    if(isset($result->item_order)){
                                        $check_item->item_order = $result->item_order;
                                    }

                                    //add taxable
                                    if(isset($result->tax_slab)) {
                                        $check_tax = 0;
                                        if (isset($outlet)) {
                                            $tax_obj = json_decode($outlet[0]->taxes);
                                            if (isset($tax_obj) && sizeof($tax_obj) > 0) {
                                                $check_tax = 0;
                                                foreach ($tax_obj as $tax_title => $child_tax) {
                                                    if (strtolower($tax_title) == strtolower($result->tax_slab)) {
                                                        $outlet_item->tax_slab = $tax_title;
                                                        $check_tax = 1;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        if($check_tax == 0){
                                            $check_item->tax_slab = '';
                                        }
                                    }
                                    else
                                        $check_item->tax_slab = '';

                                    //add taxable
                                    if(isset($result->hsn_sac_code))
                                        $check_item->hsn_sac_code = $result->hsn_sac_code;
                                    else
                                        $check_item->hsn_sac_code = '';

                                    if(isset($result->item_code))
                                        $check_item->item_code = $result->item_code;
                                    else
                                        $check_item->item_code = '';

                                    if(trim($result->discount_type) != "" &&
                                        (strtolower($result->discount_type) == 'fixed' ||
                                            (strtolower($result->discount_type) == 'percentage'))) {
                                        $check_item->discount_type = strtolower($result->discount_type);
                                    }
                                    else {
                                        $check_item->discount_type = "";
                                    }

                                    if($result->discount_value != "")
                                        $check_item->discount_value = $result->discount_value;
                                    else
                                        $check_item->discount_value = "";

                                    if($result->barcode != "")
                                        $check_item->barcode = $result->barcode;
                                    else
                                        $check_item->barcode = "";

                                    $check_item->unit_id = $unit_id;
                                    $check_item->created_by = $menu_owner;
                                    //$menu->cuisine_type_id = $cuisine_typeid['id'];
                                    $check_item->options = $result->options;
                                    $check_item->active=0;
                                    $check_item->save();
                                }else{
                                    $menu->menu_title_id = $title;
                                    $menu->outlet_id = $outlet_id;
                                    $menu->item = $result->item;
                                    if(isset($result->image) && trim($result->image) != "" ) {
                                        $menu->image = $result->image;
                                    }else{
                                        $menu->image = NULL;
                                    }
                                    if(is_numeric($result->price)){
                                        $menu->price = $result->price;
                                        $menu->buy_price = isset($result->purchase_price)?$result->purchase_price:$result->price;;
                                    }
                                    else{
                                        $error_string.=$result->item.", ";
                                        continue;
                                    }

                                    if($result->alias != "")
                                        $menu->alias = $result->alias;
                                    else
                                        $menu->alias = "";

                                    if(isset($result->foodtype)){
                                        //print_r(strcmp("asdasd","nonveg"));exit;
                                        if(strcmp(strtolower($result->foodtype),"veg")==0)
                                            $menu->food="veg";
                                        elseif(strcmp(strtolower($result->foodtype),"nonveg")==0)
                                            $menu->food="nonveg";
                                    }


                                    if(isset($result->details))
                                        $menu->details = $result->details;
                                    else
                                        $menu->details = "";

                                    if(isset($result->item_order)){
                                        $menu->item_order = $result->item_order;
                                    }
                                    if($result->barcode != "")
                                        $menu->barcode = $result->barcode;
                                    else
                                        $menu->barcode = "";

                                    //add taxable
                                    if(isset($result->tax_slab)) {
                                        $check_tax = 0;
                                        if (isset($outlet)) {
                                            $tax_obj = json_decode($outlet[0]->taxes);
                                            if (isset($tax_obj) && sizeof($tax_obj) > 0) {
                                                $check_tax = 0;
                                                foreach ($tax_obj as $tax_title => $child_tax) {
                                                    if (strtolower($tax_title) == strtolower($result->tax_slab)) {
                                                        $menu->tax_slab = $tax_title;
                                                        $check_tax = 1;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        if($check_tax == 0){
                                            $menu->tax_slab = '';
                                        }
                                    }
                                    else
                                        $menu->tax_slab = '';


                                    //add taxable
                                    if(isset($result->hsn_sac_code))
                                        $menu->hsn_sac_code = $result->hsn_sac_code;
                                    else
                                        $menu->hsn_sac_code = '';

                                    if(isset($result->item_code))
                                        $menu->item_code = $result->item_code;
                                    else
                                        $menu->item_code = '';

                                    if(trim($result->discount_type) != "" &&
                                        (strtolower($result->discount_type) == 'fixed' ||
                                            (strtolower($result->discount_type) == 'percentage')))
                                        $menu->discount_type = strtolower($result->discount_type);
                                    else
                                        $menu->discount_type = "";

                                    if($result->discount_value != "")
                                        $menu->discount_value = $result->discount_value;
                                    else
                                        $menu->discount_value = "";

                                    $menu->unit_id = $unit_id;
                                    $menu->created_by = $menu_owner;
                                    //$menu->cuisine_type_id = $cuisine_typeid['id'];
                                    $menu->options = $result->options;
                                    $menu->active=0;
                                    $menu->save();

                                }
                            }

                            //$cuisine_typeid=CuisineType::where('type',$result->cuisine_type)->first();

                        }else{
                            if ( sizeof($menutitlestored) > 0 ){
                                $title=$menutitlestored->id;
                            } else {
                                if( isset($result->title) && $result->title!="" ){
                                    $menutitle->outlet_id = $outlet_id;
                                    $menutitle->title=$result->title;
                                    $menutitle->title_order=$result->title_order;
                                    $menutitle->active = 0;
                                    $menutitle->created_by = $menu_owner;
                                    $success= $menutitle->save();
                                    $title=$menutitle->id;
                                }
//                                }
                            }
                            $outlet_item->menu_title_id = $title;
                            $outlet_item->item = $result->item;
                            $outlet_item->unit_id = $unit_id;
                            if(isset($result->image) && trim($result->image) != "" ) {
                                $outlet_item->image = $result->image;
                            }else{
                                $outlet_item->image = NULL;
                            }
                            if(is_numeric($result->price)) {
                                $outlet_item->price = $result->price;
                                $outlet_item->buy_price = isset($result->purchase_price)?$result->purchase_price:$result->price;;
                            }else{
                                $error_string.=$result->item.", ";
                                $outlet_item->active=1;
                            }

                            if($result->alias != "")
                                $outlet_item->alias = $result->alias;
                            else
                                $outlet_item->alias = "";

                            if(isset($result->foodtype)){
                                if(strcmp(strtolower($result->foodtype),"veg")==0)
                                    $outlet_item->food="veg";
                                elseif(strcmp(strtolower($result->foodtype),"nonveg")==0)
                                    $outlet_item->food="nonveg";
                            }

                            if(isset($result->details))
                                $outlet_item->details = $result->details;
                            else
                                $outlet_item->details = "";

                            if(isset($result->item_order)){
                                $outlet_item->item_order = $result->item_order;
                            }

                            //add taxable
                            if(isset($result->tax_slab)) {
                                $check_tax = 0;
                                if (isset($outlet)) {
                                    $tax_obj = json_decode($outlet[0]->taxes);
                                    if (isset($tax_obj) && sizeof($tax_obj) > 0) {
                                        $check_tax = 0;
                                        foreach ($tax_obj as $tax_title => $child_tax) {
                                            if (strtolower($tax_title) == strtolower($result->tax_slab)) {
                                                $outlet_item->tax_slab = $tax_title;
                                                $check_tax = 1;
                                                break;
                                            }
                                        }
                                    }
                                }
                                if($check_tax == 0){
                                    $outlet_item->tax_slab = '';
                                }
                            }
                            else
                                $outlet_item->tax_slab = '';

                            if(trim($result->discount_type) != "" &&
                                (strtolower($result->discount_type) == "fixed" ||
                                    (strtolower($result->discount_type) == "percentage"))) {
                                $outlet_item->discount_type = strtolower($result->discount_type);

                            }
                            else {
                                $outlet_item->discount_type = "";
                            }

                            if($result->discount_value != "")
                                $outlet_item->discount_value = $result->discount_value;
                            else
                                $outlet_item->discount_value = "";

                            //add taxable
                            if(isset($result->hsn_sac_code))
                                $outlet_item->hsn_sac_code = $result->hsn_sac_code;
                            else
                                $outlet_item->hsn_sac_code = '';

                            if(isset($result->item_code))
                                $outlet_item->item_code = $result->item_code;
                            else
                                $outlet_item->item_code = '';

                            if(isset($result->barcode))
                                $outlet_item->barcode = $result->barcode;
                            else
                                $outlet_item->barcode = '';

                            $outlet_item->save();

                        }

                    }
                    if($error_string!=null)
                        return Redirect("/uploadItemMaster")->with('error',rtrim($error_string,', ')." have invalid price");

                });
                //print_r("out".$error_string);exit;
                //  return Redirect('/outlet/'.$outlet_id."#menu")->with('success','Record Added successfully');
                return Redirect("/uploadItemMaster")->with('success','Menu Added successfully');
            }else{
                return Redirect::back()->with('error','Only ".xls" file is acceptable');
            }
        }
    }


    public function store(CreateMenuRequest $request)
    {
        //print_r($request->all());exit;
        //$OutletId = $_REQUEST['id'];

        $input = $request->all();
        $in = $request->all();
        $save_continue = Input::get('saveContinue');
        $rules = array('mimes:jpeg,bmp,png','max:100'); //mimes:jpeg,bmp,png and for max size max:10000
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput(Input::all())->withErrors($validator);
        }

        $menu = new Menu();
        $totalItems = 0;
        $menu_owner=Owner::menuOwner();
        $tax_slab = Input::get("tax_slab");
        $hsc_sac_code = Input::get("hsn_sac_code");
        $discount_type = Input::get("discount_type");
        $discount_value = Input::get("discount_value");


        if($request->get('custom_title') != "" && $request->get('title0')=='custom'){
            if($request->get('custom_title')==""){
                return redirect()->back()->withInput(Input::all())->with('error', 'Custom Title is required');
            }
            $success=MenuTitle::where('created_by',$menu_owner)->where('title',$request->get('custom_title'))->first();

            if(!$success){
                $menuTitle = new MenuTitle();
                $menuTitle->outlet_id = $menu_owner;
                $menuTitle->created_by = $menu_owner;
                $menuTitle->title = strtolower($input['custom_title']);
                //$menuTitle->food = $request->Food; //get activation of Food at database
                $menuTitle->active = "0";//get activation of Menu_Title at database
                $success = $menuTitle->save();
            }else{
                $menuTitle=MenuTitle::getmenutitlebyid($success->id);
            }
        }else{
            //$menuTitle=MenuTitle::getmenutitleofoutletandname($OutletId,$input['title0']);
            $menuTitle=MenuTitle::getmenutitlebyid($request->get('title0'));
            $success=1;
            //print_r($menuTitle->id);exit;
        }


        if ($success)
        {
            //print_r($menuTitle);exit;
            if (array_key_exists('countf', $input))
            {
                $totalItems = $input['countf'];
            }
            //print_r($totalItems);exit;
            for ($i = 0; $i <= $totalItems; $i++)
            {
                if ( isset($input['price' . $i]) && $input['price' . $i] != '') {
                    if( !is_numeric($input['price' . $i])){
                        return redirect()->back()->withInput(Input::all())->with('error', $input['item' . $i].' Price must be Numeric.');
                    }
                }

                $menu = new Menu();
                $menu->outlet_id = $menu_owner;
                $menu->created_by = $menu_owner;
                $menu->menu_title_id = $menuTitle->id;
                $menu->tax_slab = isset($tax_slab)?$tax_slab:"";
                $menu->discount_type = isset($discount_type)?$discount_type:"";
                $menu->discount_value = isset($discount_value)?$discount_value:0;
                $menu->hsn_sac_code = isset($hsc_sac_code)?$hsc_sac_code:"";
                //set session for item category
                Session::set('item_category',$menuTitle->id);

                $menu->item_order = $input['item_order'];
                if (isset($input['item_code'])) {
                    $items = Menu::getMenuByUserId($menu_owner);
                    if(isset($items)){
                        $add=0;
                        foreach($items as $item){
                            if(strtolower($item->item_code)==strtolower($input['item_code'])){
                                $add=1;
                                break;
                            }
                        }
                        if($add!=1){
                            $menu->item_code = $input['item_code'];
                        }else{
                            return redirect()->back()->with('error', $input['item_code'].' Item code is Repeated');
                        }
                    }
                }

                $sec_unit_id = isset($input['secondary_unit'])?$input['secondary_unit']:NULL;
                $sec_unit_val = isset($input['secondary_value'])?$input['secondary_value']:NULL;
                $secondary_units = '';

                if ( isset($sec_unit_id) && isset($sec_unit_val) && !empty($sec_unit_id) && !empty($sec_unit_val) ) {
                    foreach( $sec_unit_id as $key=>$sec_id ) {
                        if ( isset($sec_id) && isset($sec_unit_val[$key]) && $sec_unit_val[$key] != '') {
                            $secondary_units[$sec_id] = $sec_unit_val[$key];
                        }
                    }
                    if ( isset($secondary_units) && !empty($secondary_units) && is_array($secondary_units) ) {
                        $menu->secondary_units = json_encode($secondary_units);
                    }
                }

                if (isset($input['order_unit']) ) {
                    $menu->order_unit = $input['order_unit'];
                } else {
                    $menu->order_unit = NULL;
                }

                if (isset($input['item' . $i]) ) {
                    $menu->item = $input['item' . $i];
                } else {
                    return redirect()->back()->with('error', 'Item name is Required.');
                }

                if (isset($input['alias' . $i])) {
                    $menu->alias = $input['alias' . $i];
                }
                if (isset($input['is_inventory_item'])) {
                    $menu->is_inventory_item = 1;
                }
                if (isset($input['price' . $i])) {
                    $menu->price = $input['price' . $i];
                    $menu->buy_price = $input['price' . $i];
                }
                if (isset($input['buy_price' . $i])) {
                    $menu->buy_price = $input['buy_price' . $i];
                }
                if (isset($input['unit_id' . $i])) {
                    $menu->unit_id = $input['unit_id' . $i];
                }

                if (isset($input['details' . $i])) {
                    $menu->details = $input['details' . $i];
                }
                if (isset($input['cuisine_type' . $i])) {
                    $menu->cuisine_type_id = $input['cuisine_type' . $i];
                }

                if (isset($input['color']) && $input['color'] != "#000000 ") {
                    $menu->color = $input['color'];
                }

                if (isset($input['options' . $i])) {
                    $menu->options = $input['options' . $i];
                }
                if (isset($input['food' . $i])) {
                    $menu->food = $input['food' . $i];
                }
                if (isset($input['barcode'])) {
                    $menu->barcode = $input['barcode'];
                }

                $menu->brand = isset($input['brand'])?$input['brand']:NULL;

                $menuStored = $menu->save();

                if(sizeof($request->bind)>0){
                    $outlet_ids=$request->bind;
                    foreach ($outlet_ids as $ot_id=>$value){
                        Outlet_Menu_Bind::addMenuItem($ot_id,$menuTitle->id,$menu->id);
                    }
                }
                $myOutlets_id=OutletMapper::getOutletMapperByOwnerId($menu_owner)->lists("outlet_id");

                if(sizeof($request->sale)>0 || sizeof($request->act)>0){
                    $act = Input::get('act');
                    $sales = Input::get('sale');
                    foreach ($myOutlets_id as $ot_id=>$value){
                        if( isset($act) ) {
                            $active = array_key_exists( $value , Input::get('act'))?0:1;
                        }else{
                            $active = 1;
                        }

                        if( isset($sales) ) {
                            $sale = array_key_exists($value, Input::get('sale')) ? 1 : 0;
                        }else{
                            $sale = 0;
                        }
                        ItemSettings::insert(
                            ['outlet_id' => $value, 'item_id'=> $menu->id, 'is_active'=> $active, 'is_sale'=> $sale]
                        );
                    }
                }

                if ($menuStored)
                {

                    $option_groups = Input::get('option_groups');

                    //remove item option groups
                    ItemOptionGroupMapper::where('item_id',$menu->id)->delete();

                    if ( isset($option_groups) && sizeof($option_groups) > 0 ) {
                        foreach ( $option_groups as $group ) {

                            $mapper = new ItemOptionGroupMapper();
                            $mapper->item_id = $menu->id;
                            $mapper->item_option_group_id = $group;
                            $mapper->created_by = $menu_owner;
                            $mapper->updated_by = $menu_owner;
                            $mapper->save();

                        }
                    }

                    //store image
                    $path = NULL;
                    if (isset($input['image'])) {

                        if (!file_exists(base_path().'/public/images/menuimage')) {
                            try{
                                mkdir(base_path() .'/public/images/menuimage', 0777, true);
                            } catch ( \Exception $e) {
                                echo $e->getMessage();exit;
                            }
                        }

                        $imageName = $menu->id . '.' .
                            $input['image']->getClientOriginalExtension();

                        $path = '/images/menuimage/'.$imageName;

                        $input['image']->move(
                            base_path() . '/public/images/menuimage/', $imageName
                        );

                        $menu_obj = Menu::find($menu->id);
                        $http_str = explode ( "://" , $_SERVER['HTTP_REFERER']);
                        $menu_obj->image = $http_str[0]."://".$_SERVER['HTTP_HOST']."/".$path;
                        $menu_obj->save();

                    }

                    $totaloptions = 0;
                    /*if ($OutletId != '' )
                    {*/
                    if (array_key_exists('coutmenuoption', $in))
                    {
                        $totaloptions = $in['coutmenuoption'];
                    }
                    for ($j = 1; $j <= $totaloptions; $j++)
                    {
                        //print_r($input['options'.$i]);
                        if(isset($in['opt' .$i. $j]) && $in['opt' .$i. $j]!='') {
                            $menu_option = new menu_option();
                            $menu_option->outlet_id = $menu_owner;

                            //                                $menu_option->opt=$request->opt;
                            //                                $menu_option->opt_price=$request->opt_price;

                            $menu_option->menu_id = $menu->id;

                            if (isset($in['opt' . $i . $j])) {
                                $menu_option->opt = $in['opt' . $i . $j];
                            }
                            if (isset($in['opt_price' . $i . $j]) && $in['opt_price' . $i . $j] != "") {
                                $menu_option->opt_price = $in['opt_price' . $i . $j];
                            }
                            $optionStored = $menu_option->save();
                        }
                    }
                }
            }

            if (!$menuStored) {
                return Redirect('/uploadItemMaster')->with('error', 'Failed');
            }
            if ( isset($save_continue) && $save_continue == 'true' ) {
                return Redirect::route('menu.create')->with('success','New Item has been added....');
            } else {
                return Redirect::route('menu.index')->with('success','New Item has been added....');
            }
                //return Redirect('/uploadItemMaster')->with('success', 'Menu added successfully ');
        }
           // }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */

    public function show($id)
    {
        $menu_owner=Owner::menuOwner();
        $item_show=Menu::join('unit as u','u.id','=','menus.unit_id')
                        ->select('menus.*','u.id as u_id','u.name as u_name')
                        ->where('menus.id',$id)->first();
        //print_r($item);exit;
        $myOutlets_id=OutletMapper::getOutletMapperByOwnerId($menu_owner)->lists("outlet_id");
        $myOutlets=Outlet::whereIn('id',$myOutlets_id)->lists('name','id');
        $units=Unit::all()->lists('name','id');
        $menu_title_list = MenuTitle::where('created_by',$menu_owner)->lists('title','id');
        $menu_title_list['custom']="(Add new Title)";
        $selected_outlet=Outlet_Menu_Bind::where('item_id',$id)->lists("outlet_id");
        $active = ItemSettings::where('item_id',$id)->where('is_active',1)->lists('outlet_id');
        $sale = ItemSettings::where('item_id',$id)->where('is_sale',1)->lists('outlet_id');

        //ingredients
        $items=array();
        $temp=Menu::join('unit as u','u.id','=','menus.unit_id')
                    ->select('menus.*','u.id as u_id','u.name as u_name')
                    ->where('menus.created_by',$menu_owner)
                    ->where('menus.is_inventory_item',1)
                    ->orderBy('menus.item')->get();//lists('item','id');

        $items[0]['name']='Select';
        $items[0]['id'] = '';
        $items[0]['u_id'] = '';
        $items[0]['u_name'] = '';

        if ( isset($temp) && sizeof($temp) > 0 ) {
            $cnt = 1;
            foreach($temp as $item){
                $items[$cnt]['id']= $item->id;
                $items[$cnt]['name'] = $item->item;
                $items[$cnt]['u_id'] = $item->u_id;
                $items[$cnt]['u_name'] = $item->u_name;
                $cnt++;
            }
        }

        $recipe = RecipeDetails::where('menu_item_id',$id)->first();

        $ing_action = '';
        if ( isset($recipe) && sizeof($recipe) > 0 ) {
            $ing_action = 'edit';
        } else {
            $ing_action = 'add';
        }

        //recipe
        $method = "";
        $recipe_items = array();
        $unit = array();
        if(isset($recipe) && sizeof($recipe)>0) {
            if (isset($recipe->ingred_method) && sizeof($recipe->ingred_method) > 0) {
                $method = $recipe->ingred_method;
            }

            $recipe_items = array();
            if (isset($recipe) && sizeof($recipe) > 0) {
                $recipe_items = Ingredients::join('menus','menus.id','=','ingredients.ing_item_id')
                                ->join('unit as u', 'u.id', '=', 'menus.unit_id')
                                ->where('recipeDetails_id', $recipe->id)->get();
            }
        }
        //print_r($method);exit;
        return view('menus.create',array('selected_outlet'=>$selected_outlet,'ing_action'=>$ing_action,'item'=>$item_show,'Itemid'=>$id,'title'=>$menu_title_list,'units'=>$units,'myoutlets'=>$myOutlets,'action'=>'show','ingred_method' => $method,'recipe'=>$recipe,'recipe_items'=>$recipe_items,'items'=>$items,'unit'=>$unit,'actives'=>$active,'sales'=>$sale));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $logged_in = Auth::id();
        $menu_owner=Owner::menuOwner();
        $item=Menu::where('id',$id)->first();
        $tax_slot_array[''] = 'Select Tax Slot';

        $myOutlets_id=OutletMapper::getOutletMapperByOwnerId($logged_in)->lists("outlet_id");
        $myOutlets=Outlet::whereIn('id',$myOutlets_id)->lists('name','id');

        $units=Unit::all()->lists('name','id');

        $menu_title_list=MenuTitle::where('created_by',$menu_owner)->lists('title','id');
        //$menu_title_list['custom']="(Add new Title)";
        $selected_outlet=Outlet_Menu_Bind::where('item_id',$id)->lists("outlet_id");
        $active = ItemSettings::where('item_id',$id)->where('is_active',1)->lists('outlet_id');
        $sale = ItemSettings::where('item_id',$id)->where('is_sale',1)->lists('outlet_id');

        $sec_unit = '';
        if( isset($item->secondary_units) ) {
            $sec_unit = json_decode($item->secondary_units,true);
        }

        $outlet_id = Session::get('outlet_session');
        if(isset($outlet_id) && sizeof($outlet_id)>0) {
            $outlet = Outlet::find($outlet_id);
            if(isset($outlet) && sizeof($outlet)>0) {
                $taxes = json_decode($outlet->taxes);
                if(isset($taxes) && sizeof($taxes)>0){
                    foreach ($taxes as $tax_title=>$child_tax){
                        $tax_slot_array[$tax_title] = $tax_title;
                    }
                }else{
                    $tax_slot_array[""] = 'No Taxes';
                }
            }else{
                print_r("No Outlet Found");exit;
            }
        }else{
            print_r("Please Enter Outlet Data");exit;
        }

        //get outlet item option groups
        $option_groups = ItemOptionGroup::where('outlet_id',$outlet_id)->get();

        $selected_option_group = ItemOptionGroupMapper::where('item_id',$id)->lists('item_option_group_id');

        //check itemwise discount enable or not
        $check_itemwise_dis = OutletSetting::checkAppSetting( $outlet_id, "itemWiseDiscount" );

        //check itemwise tax enable or not
        $check_itemwise_tax = OutletSetting::checkAppSetting( $outlet_id, "itemWiseTax" );

        return view('menus.create',array('tax_slab'=>$tax_slot_array,
                                        'itemwise_tax'=>$check_itemwise_tax,
                                        'itemwise_dis'=>$check_itemwise_dis,
                                        'option_groups'=>$option_groups,
                                        'selected_option_groups'=>$selected_option_group,
                                        'selected_outlet'=>$selected_outlet,
                                        'item'=>$item,
                                        'secondary_units'=>$sec_unit,
                                        'Itemid'=>$id,'title'=>$menu_title_list,
                                        'units'=>$units,
                                        'myoutlets'=>$myOutlets,
                                        'action'=>'edit',
                                        'actives'=>$active,
                                        'sales'=>$sale));
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
        $encoded_image = null;

        if(strcmp(Input::get('title0'),'custom')==0){
            if(strcmp(Input::get('custom_title'),"")==0){
                return redirect()->back()->withInput(Input::all())->with('error', 'Custom Title is required');
            }
            $temp_title=MenuTitle::where('created_by',$menu_owner)->where('title',Input::get('custom_title'))->first();
            if(!$temp_title){
                $menuTitle = new MenuTitle();
                $menuTitle->outlet_id = $menu_owner;
                $menuTitle->created_by = $menu_owner;
                $menuTitle->title = Input::get('custom_title');
                //$menuTitle->food = $request->Food; //get activation of Food at database
                $menuTitle->active = "0";//get activation of Menu_Title at database
                $success = $menuTitle->save();
            }else{
                $menuTitle=MenuTitle::getmenutitlebyid($temp_title->id);
            }
        }else{
            $menuTitle=MenuTitle::getmenutitlebyid(Input::get("title0"));
            $success=1;
        }
        if (Input::get("item0") != "" && Input::get("item_code") != "") {
            $items = Menu::getMenuByUserId($menu_owner);
            if(isset($items)){
                $add=0;
                foreach($items as $item){
                    if(strtolower($item->item_code)==strtolower(Input::get("item_code")) && $item->id!=$id){
                        $add=1;
                        break;
                    }
                }
                if($add!=1){
                    $menu_item = Input::get("item0");
                    $item_code = Input::get("item_code");
                }else{
                    return redirect()->back()->with('error', Input::get("item_code").' Item code is Repeated');
                }
            }
        }else{
            return redirect()->back()->with('error', 'Item code and Item name is Required.');
        }

        $sec_unit_id = Input::get('secondary_unit');
        $sec_unit_val = Input::get('secondary_value');
        $units = '';$secondary_units = array();

        if ( isset($sec_unit_id) && isset($sec_unit_val) && sizeof($sec_unit_id) && sizeof($sec_unit_val) ) {
            foreach( $sec_unit_id as $key=>$sec_id ) {
                if ( isset($sec_id) && isset($sec_unit_val[$key]) && $sec_unit_val[$key] != '') {
                    $secondary_units[$sec_id] = $sec_unit_val[$key];
                }
            }
            if ( isset($secondary_units) && sizeof($secondary_units) > 0 && is_array($secondary_units)) {
                $units = json_encode($secondary_units);
            }
        }
        $ord_unit = Input::get('order_unit');
        $tax_slab = Input::get("tax_slab");
        $hsc_sac_code = Input::get("hsn_sac_code");
        $discount_type = Input::get("discount_type");
        $discount_value = Input::get("discount_value");

        if ( $ord_unit == '') {
            $ord_unit = NULL;
        }

        if ( $item_code == '') {
            $item_code = NULL;
        }

        $buy_price = Input::get("buy_price0");
        if(!isset($buy_price) && !sizeof($buy_price)>0)
            $buy_price = Input::get("price0");

        $is_inventory_item = Input::get('is_inventory_item');

        $menu_obj = Menu::find($id);
        $menu_obj->menu_title_id = $menuTitle->id;
        $menu_obj->item = $menu_item;
        $menu_obj->price = Input::get("price0");
        $menu_obj->buy_price = $buy_price;
        $menu_obj->secondary_units = $units;
        $menu_obj->order_unit = $ord_unit;
        $menu_obj->item_code = $item_code;
        $menu_obj->details = Input::get("details0");
        $menu_obj->unit_id = Input::get("unit_id0");
        $menu_obj->active = Input::get("active0");
        $menu_obj->food = Input::get("food0");
        $menu_obj->item_order = Input::get('item_order');
        $menu_obj->brand = Input::get('brand');
        $menu_obj->is_sell = Input::get('is_sell');
        $menu_obj->is_inventory_item = isset($is_inventory_item)?1:0;
        $menu_obj->alias = Input::get("alias0");
        $menu_obj->barcode = Input::get("barcode");
        $menu_obj->tax_slab = isset($tax_slab)?$tax_slab:"";
        $menu_obj->hsn_sac_code = isset($hsc_sac_code)?$hsc_sac_code:"";
        $menu_obj->discount_type = isset($discount_type)?$discount_type:"";
        $menu_obj->discount_value = isset($discount_value)?$discount_value:0;
        $color = Input::get("color");
        if (isset($color) && $color != "#000000 ") {
            $menu_obj->color =$color;
        }

        //store image
        $path = NULL;
        if (isset(Input::all()['image'])) {

            if (!file_exists(base_path().'/public/images/menuimage')) {
                try{
                    mkdir(base_path() .'/public/images/menuimage', 0777, true);
                } catch ( \Exception $e) {

                }

            }

            $imageName = $id . '.' .
                Input::all()['image']->getClientOriginalExtension();

            $path = '/images/menuimage/'.$imageName;

            Input::all()['image']->move(
                base_path() . '/public/images/menuimage/', $imageName
            );

            $http_str = explode ( "://" , $_SERVER['HTTP_REFERER']);
            $menu_obj->image = $http_str[0]."://".$_SERVER['HTTP_HOST']."".$path;

        }

        $menu_obj->save();

        $option_groups = Input::get('option_groups');

        //remove item option groups
        ItemOptionGroupMapper::where('item_id',$menu_obj->id)->delete();

        if ( isset($option_groups) && sizeof($option_groups) > 0 ) {
            foreach ( $option_groups as $group ) {

                $mapper = new ItemOptionGroupMapper();
                $mapper->item_id = $id;
                $mapper->item_option_group_id = $group;
                $mapper->created_by = $menu_owner;
                $mapper->updated_by = $menu_owner;
                $mapper->save();

            }
        }

        //outlet menu bind
        Outlet_Menu_Bind::where('item_id',$id)->delete();
        if(sizeof(Input::get("bind"))>0){
            $outlet_ids=Input::get("bind");
            foreach ($outlet_ids as $ot_id=>$value){
                $result = Outlet_Menu_Bind::addMenuItem($value,$menuTitle->id,$id);
            }
        }
        $myOutlets_id=OutletMapper::getOutletMapperByOwnerId($menu_owner)->lists("outlet_id");

        if(sizeof(Input::get('sale'))>0 || sizeof(Input::get('act'))>0){
            $act = Input::get('act');
            $sales = Input::get('sale');
            foreach ($myOutlets_id as $ot_id=>$value){
                if( isset($act) ) {
                    $active = in_array( $value , Input::get('act'))?0:1;
                }else{
                    $active = 1;
                }

                if( isset($sales) ) {
                    $sale = in_array($value, Input::get('sale')) ? 1 : 0;
                }else{
                    $sale = 0;
                }
                $result = ItemSettings::where('outlet_id',$value)
                    ->where('item_id',$id)
                    ->get();
                if( sizeof($result) > 0 ) {
                    ItemSettings::where('outlet_id', $value)
                        ->where('item_id', $id)
                        ->update(
                            ['is_active' => $active, 'is_sale' => $sale]
                        );
                }
                else{
                    if( isset($act) ) {
                        $active = in_array( $value , Input::get('act'))?0:1;
                    }else{
                        $active = 1;
                    }

                    if( isset($sales) ) {
                        $sale = in_array($value, Input::get('sale')) ? 1 : 0;
                    }else{
                        $sale = 0;
                    }
                    ItemSettings::insert(
                        ['outlet_id' => $value, 'item_id'=> $id, 'is_active'=> $active, 'is_sale'=> $sale]
                    );
                }
            }
        }else{
            foreach ($myOutlets_id as $ot_id=>$value){
                $act = Input::get('act');
                $sales = Input::get('sale');
                $result = ItemSettings::where('outlet_id', $value)->where('item_id', $id)->get();
                if( sizeof($result) > 0 ) {
                    ItemSettings::where('outlet_id', $value)
                        ->where('item_id', $id)
                        ->update(
                            ['is_active' => 1, 'is_sale' => 0]
                        );
                }
                else{
                    if( isset($act) ) {
                        $active = in_array( $value , Input::get('act'))?0:1;
                    }else{
                        $active = 1;
                    }
                    if( isset($sales) ) {
                        $sale = in_array($value, Input::get('sale')) ? 1 : 0;
                    }else{
                        $sale = 0;
                    }
                    ItemSettings::insert(
                        ['outlet_id' => $value, 'item_id'=> $id, 'is_active'=> $active, 'is_sale'=> $sale]
                    );
                }
            }
        }

        return Redirect('/menu')->with('success', 'Item updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
        $id = Input::get('id');

        $check = Menu::where('id',$id)->delete();
        //Outlet_Menu_Bind::where('item_id',$id)->delete();
        if ( $check ) {
            return 'success';
        } else {
            return 'error';
        }

    }

    public function removeItemOption() {

        $id = Input::get('item_id');
        $item_option_id = Input::get('item_option_id');

        $check = MenuItemOption::where('parent_item_id',$id)
                                ->where('option_item_id',$item_option_id)
                                ->delete();
        if ( $check ) {
            return 'success';
        } else {
            return 'error';
        }

    }

    public function ajaxMenuItemsList(){
        //echo Input::get('flag');exit;
        $title_id = Input::get('title_id');
        $outlet_id = Input::get('outlet_id');
        $user_id = Auth::id();
        $flag = Input::get('flag');
        if (isset($flag)){
            $menuItems = '';
            $menuTitle = '';
            $service_tax = '';
            if(isset($title_id) && isset($user_id) && $flag == 'menus'){
                //$menuItems = Menu::getMenuItemsList($outlet_id, $title_id);
                $menuItems = Menu::where('menu_title_id', $title_id)->where('created_by', Auth::id())->get();

            }
            if(isset($outlet_id) && $flag == 'menuTitles'){
                $menuTitle = MenuTitle::getMenuTitleByUserId(Auth::id());
                $outlet_service_tax = Outlet::where('id', $outlet_id)->first();
                if( sizeof($outlet_service_tax) ){
                    if(isset($outlet_service_tax->service_tax)){
                        $service_tax = $outlet_service_tax->service_tax;
                    }

                }
            }
            //print_r($menuItems);exit;

            if( sizeof($menuItems) > 0 && $menuItems != ''){
                $select_box = '<option value="0" selected >Select Menu Item</option>';
                foreach( $menuItems as $items ){
                    $select_box .= '<option value="'.$items->id.'">'.$items->item.'</option>';
                }
                //print_r($select_box);exit;
                return array('list' => $select_box);

            }
            if( sizeof($menuTitle) > 0 && $menuTitle != ''){
                $select_box = '<option value="0" selected >Select Menu Category</option>';
                foreach( $menuTitle as $title ){
                    $select_box .= '<option value="'.$title->id.'">'.$title->title.'</option>';
                }
                return array('list' => $select_box, 'service_tax' => $service_tax);
            }

        }

    }

    public function ajaxOutletItemsList(){
        //echo 'here';exit;
        $outlet_id = Input::get('outlet_id');
        $flag = Input::get('flag');
        if (isset($flag)){
            $menuItems = '';
            $service_tax = '';
            $menuItems=Menu::where('outlet_id', $outlet_id)->get();
            if(isset($outlet_id) && $flag == 'menuTitles'){
                    $menuTitle = MenuTitle::getmenutitlebyrestaurantid($outlet_id);
                    $outlet_service_tax = Outlet::where('id', $outlet_id)->first();
                    if( sizeof($outlet_service_tax) ){
                        if(isset($outlet_service_tax->service_tax)){
                            $service_tax = $outlet_service_tax->service_tax;
                        }

                    }
            }
            //print_r($menuItems);exit;

            if( sizeof($menuItems) > 0 && $menuItems != ''){
                $select_box = '<option value="0" selected >Select Menu Item</option>';
                foreach( $menuItems as $items ){
                    $select_box .= '<option value="'.$items->id.'">'.$items->item.'</option>';
                }
                //print_r($select_box);exit;
                return array('list' => $select_box);

            }


        }

    }

    public function ajaxItemTitleList(){
        $owner_id=Auth::user()->id;
        $outlet_id = Input::get('outlet_id');
        $outlet_title=DB::table('menu_titles')->where('outlet_id','=',$outlet_id)->lists('title','id');

        return $outlet_title;
    }

    public function updateItemCategory(){

        ini_set('max_execution_time', 0);

        $order_item = OrderItem::all();
        $success = '';
        foreach($order_item as $item){

            $menu_title_detail = Menu::leftjoin('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                    ->select('menus.menu_title_id as menu_title_id','menu_titles.title as title','menus.item as item')
                                    ->where('menus.id', $item->item_id)
                                    ->first();

            if ( isset($menu_title_detail) && sizeof($menu_title_detail) > 0 ) {

                $update_item = OrderItem::find($item->id);

                if ( isset($update_item) && sizeof($update_item) > 0 ) {
                    $update_item->category_id = $menu_title_detail->menu_title_id;
                    $update_item->category_name = $menu_title_detail->title;
                    $update_item->item_name = $menu_title_detail->item;
                    $success = $update_item->save();
                }

            }


        }

        if($success)
            print_r("success");
        else
            print_r("Error");
    }

    public function indexTitle(){

        $login_user=Auth::id();
        $menu_owner=Owner::menuOwner();
        $myOutlets_id=OutletMapper::getOutletMapperByOwnerId($menu_owner)->lists("outlet_id");
        $myOutlets=Outlet::whereIn('id',$myOutlets_id)->lists('name','id');
        $units=Unit::all()->lists('name','id');

        //$menu_title_list = array('' => 'Select Category');
        $menu_title_list = array();
        $menu_title = MenuTitle::where('created_by',$menu_owner)
                    ->orderBy('title_order')
                    ->lists('title','id');
        if( isset($menu_title) && sizeof($menu_title) > 0 ) {
            foreach ( $menu_title as $id => $title ) {
                $menu_title_list[$id] = $title;
            }
        }
        //$menu_title_list['custom']="(Add new Category)";

        return view('menus.edit',array('titles'=>$menu_title_list,'action'=>'add','myoutlets'=>$myOutlets));

    }

    public function titleForm(){
        return view('menus.form_menutitle',array('action'=>'add'));
    }

    public function titleEdit($id){

        $login_user=Auth::id();
        $menu_owner=Owner::menuOwner();

        $menu_title = MenuTitle::find($id);
//        print_r($menu_title);exit;
        if(isset($menu_title) && sizeof($menu_title)>0){
            $title = $menu_title->title;
            $is_sale = $menu_title->is_sale;
            $active = $menu_title->active;
            $title_order = $menu_title->title_order;
            $is_inventory_category = $menu_title->is_inventory_category;
            return view('menus.form_menutitle',array('is_inventory_category'=>$is_inventory_category,'title_order' => $title_order,'cat_id'=>$id,'title' => $title,'action' => 'edit','is_sale' => $is_sale,'active' => $active));
        }else{
            return Redirect::back()->with('error','Category not found.');
        }

    }

    public function check_menu_title(){

        $title_id = Input::get("title_id");
        $title_details = MenuTitle::find($title_id);
        $result = array();

        return $title_details;

    }

    public function title_change(){

        $btn_click = Input::get("btn_click");
        $admin_id = Owner::menuOwner();

        if($btn_click == "add"){

            $custom_title = Input::get("custom_title");
            $available_title = MenuTitle::where('created_by',$admin_id)->get();
            foreach ($available_title as $title){
                if(strtolower($title->title) == strtolower($custom_title)){
                    return 0; // title is already available
                }
            }
            $is_inventory_category = Input::get('is_inventory_category');

            $new_title = new MenuTitle();
            $new_title->title = $custom_title;
            $new_title->outlet_id = $admin_id;
            $new_title->created_by = $admin_id;
            $new_title->active = Input::get("active");
            if($is_inventory_category == "false") {
                $new_title->is_inventory_category = 0;
            } else{
                $new_title->is_inventory_category = 1;
            }
            $new_title->is_sale = Input::get("is_sale");
            $new_title->title_order = Input::get("title_order");
            $new_title->save();

            return 1;

        }elseif($btn_click == "update"){

            $edited_title = Input::get("edited_title");
            $item_id = Input::get("item_id");
            $available_title = MenuTitle::where('created_by',$admin_id)->get();
            foreach ($available_title as $title){
                if(strtolower($title->title) == strtolower($edited_title)){
                    if($item_id != $title->id)
                        return 0; // title is already available
                }
            }
            $active = Input::get("active");
            $is_sale = Input::get("is_sale");
            $title_order = Input::get("title_order");
            $is_inventory_category = Input::get("is_inventory_category");
            if($is_inventory_category == "false") {
                $is_inventory_category_select = 0;
            } else{
                $is_inventory_category_select = 1;
            }

            $saved = DB::table('menu_titles')
                    ->where('id', $item_id)
                    ->update(['active' => $active,
                            'title' => $edited_title,
                            'is_sale' => $is_sale,
                            'title_order' => $title_order,
                            'is_inventory_category' => $is_inventory_category_select]);

            return 1;
        }

    }

    public function getItemsByCategoryId() {

        $cat_id = Input::get('cat_id');
        $item_arr[''] = 'Select Item';
        $items = Menu::select('id','item')->where('menu_title_id',$cat_id)->get();

        return $items;
    }

    public function getMenuItem() {

        $title_id = Input::get('title_id');
        $menu_owner=Owner::menuOwner();

        $menuItems = '';
        if((isset($title_id) && $title_id == 'all') || (isset($title_id) && $title_id == '0')){

            $menuItems=Menu::leftJoin('outlet_menu_bind as omb','omb.item_id','=','menus.id')
                ->select('menus.*',DB::raw("group_concat(omb.outlet_id SEPARATOR ',') as map_outlet_id"))
                ->where('menus.created_by', $menu_owner)
                ->groupBy('menus.id')
                ->get();

        }else if( isset($title_id) ){
            $menuItems=Menu::leftJoin('outlet_menu_bind as omb','omb.item_id','=','menus.id')
                ->select('menus.*',DB::raw("group_concat(omb.outlet_id SEPARATOR ',') as map_outlet_id"))
                ->where('menus.menu_title_id', $title_id )
                ->where('menus.created_by', $menu_owner)
                ->groupBy('menus.id')
                ->get();
        }else{
            return $error = 'Category not selected';
        }

        $items =array();
        $items['0'] = 'All Items';
        foreach ($menuItems as $menuItem) {
            $items[$menuItem->id] = $menuItem->item;
        }

       return $items;

    }

    //get other unit of item
    public function getItemOtherUnits() {

        $id = Input::get('id');
        $menu = Menu::find($id);
        if(isset($menu->order_unit) && trim($menu->order_unit) != ""){
            return $menu->order_unit;
        }else{
            return $menu->unit_id;
        }

    }

    //update menu item sequence
    public function updateMenuSequence() {

        $item_ids = Input::get('ids');
        $sel_id = Input::get('selected_id');

        if ( isset($item_ids) && sizeof($item_ids) > 1 ) {

            $i = 1;$size = sizeof($item_ids);

            foreach ( $item_ids as $id ) {

                $item = Menu::find($id);

                if ( $item_ids[0] == $sel_id ) {

                    if ( $i == 1 ) {
                        $item->item_order = $item->item_order - ( $size - 1 );
                    } else {
                        $item->item_order = $item->item_order + 1;
                    }

                } else {

                    if ( $size == $i ) {
                        $item->item_order = $item->item_order + ($size - 1);
                    } else {
                        $item->item_order = $item->item_order - 1;
                    }

                }

                $item->save();
                $i++;

            }

        }

        return 'success';

    }

    public function changeItemSettings() {

        $status = false;$result = '';
        $outlet_id = Session::get('outlet_session');
        $user_id = Auth::id();

        $item_id = Input::get('item_id');
        $flag = Input::get('flag');
        $value = Input::get('value');
        $qty = Input::get('qty');

        if($qty == 'selected'){

            foreach ($item_id as $item){

                if ($flag != 'is_bind') {

                    //get item setting
                    $setting = ItemSettings::where('outlet_id', $outlet_id)->where('item_id', $item)->first();

                    if ($flag == 'is_active') { //In active 0 means true..

                        if (isset($setting) && sizeof($setting) > 0) {

                            $setting->is_active = 0;
                            $result = $setting->save();
                        } else {

                            $item_setting = new ItemSettings();
                            $item_setting->outlet_id = $outlet_id;
                            $item_setting->item_id = $item;
                            $item_setting->is_active = 0;
                            $item_setting->is_sale = 0;
                            $item_setting->created_by = $user_id;
                            $item_setting->updated_by = $user_id;
                            $result = $item_setting->save();

                        }

                    } else if ($flag == 'is_sale') {

                        if (isset($setting) && sizeof($setting) > 0) {

                            $setting->is_sale = $value;
                            $result = $setting->save();

                        } else {

                            $item_setting = new ItemSettings();
                            $item_setting->outlet_id = $outlet_id;
                            $item_setting->item_id = $item;
                            $item_setting->is_active = 0;
                            $item_setting->is_sale = $value;
                            $item_setting->created_by = $user_id;
                            $item_setting->updated_by = $user_id;
                            $result = $item_setting->save();

                        }

                    }

                } else {

                    if ($value == 0) {
                        $result = Outlet_Menu_Bind::where('item_id', $item)->where('outlet_id', $outlet_id)->delete();
                    } else {
                        $menu = Menu::find($item);
                        $result = Outlet_Menu_Bind::addMenuItem($outlet_id, $menu->menu_title_id, $item);
                    }
                }
            }
        }

        if($qty == 'single') {
            if ($flag != 'is_bind') {

                //get item setting
                $setting = ItemSettings::where('outlet_id', $outlet_id)->where('item_id', $item_id)->first();

                if ($flag == 'is_active') {

                    if (isset($setting) && sizeof($setting) > 0) {

                        $setting->is_active = $value;
                        $result = $setting->save();

                    } else {

                        $item_setting = new ItemSettings();
                        $item_setting->outlet_id = $outlet_id;
                        $item_setting->item_id = $item_id;
                        $item_setting->is_active = $value;
                        $item_setting->is_sale = 0;
                        $item_setting->created_by = $user_id;
                        $item_setting->updated_by = $user_id;
                        $result = $item_setting->save();

                    }

                } else if ($flag == 'is_sale') {

                    if (isset($setting) && sizeof($setting) > 0) {

                        $setting->is_sale = $value;
                        $result = $setting->save();

                    } else {

                        $item_setting = new ItemSettings();
                        $item_setting->outlet_id = $outlet_id;
                        $item_setting->item_id = $item_id;
                        $item_setting->is_active = 0;
                        $item_setting->is_sale = $value;
                        $item_setting->created_by = $user_id;
                        $item_setting->updated_by = $user_id;
                        $result = $item_setting->save();

                    }

                }

            } else {

                if ($value == 0) {
                    $result = Outlet_Menu_Bind::where('item_id', $item_id)->where('outlet_id', $outlet_id)->delete();
                } else {
                    $menu = Menu::find($item_id);
                    $result = Outlet_Menu_Bind::addMenuItem($outlet_id, $menu->menu_title_id, $item_id);;
                }
            }
        }


        if ( $result ) {
            return 'true';
        } else {
            return 'false';
        }
    }

    function imageDestroy($id){

        $menuItem = Menu::find($id);
        $menuItem->image = null;
        $menuItem->save();
        return Redirect::to('/menu/'.$id.'/edit');
    }

    function updateItemCode($id){

        $owner = Owner::where('account_id',$id)->whereNull('created_by')->first();
        if(isset($owner) && sizeof($owner)>0) {
            $all_items = Menu::where('created_by', $owner->id)->get();
            if(isset($all_items) && sizeof($all_items)>0) {

                $start = 1; $count = 0;
                foreach ($all_items as $item) {

                    if(isset($item) && sizeof($item)>0) {
                        $item->item_code = str_pad($start++, 4, "0", STR_PAD_LEFT);
                        $item->save();
                        $count++;
                    }else{
                        print_r( $item->item . " Item not found");
                    }

                }
            }else{
                print_r("Menu not found");exit;
            }

            print_r($count." Records Updated");

        }else{
            print_r("Owner Not Found");exit;
        }

    }

}
