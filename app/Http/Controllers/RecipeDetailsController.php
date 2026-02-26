<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Ingredients;
use App\Location;
use App\Menu;
use App\Outlet;
use App\OutletMapper;
use App\RecipeDetails;
use App\Stock;
use App\StockHistory;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\CreateRecipeDetailsRequest;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Auth\Guard;

class RecipeDetailsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['home']]);
    }
    public function index()
    {
//    print_r("here");exit;
        if(Auth::user()->user_name == 'govind' || Auth::id() != '' ) {

            $owner_id = Auth::user()->id;
            $recipeDetails = RecipeDetails::getRecipeDetailsbyOwnerid($owner_id);

            if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
                return view('recipeDetails.mobileIndex', array('recipes' => $recipeDetails));
            } else {
                return view('recipeDetails.index', array('recipes' => $recipeDetails));
            }
        } else {
            echo "Access Denied";
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
//    print_r("create");exit;
        /*$owner_id=Auth::user()->id;
        $mappers = OutletMapper::getOutletIdByOwnerId($owner_id);

        foreach($mappers as $mapper)
        {
            $mapper_arr[] = $mapper['outlet_id'];
        }
        $Outlet_details = Outlet::whereIn('id',$mapper_arr)->get();*/
//    $Outlet_details=Outlet::getoutletbyownerid($owner_id);
        $items=array();
        $temp=Menu::where('created_by',Auth::id())->get();//lists('item','id');
        $items['']='Select';
        foreach($temp as $item){
            $items[$item->id]=$item->item;
        }
        $unit = Unit::getunit();

        if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
            return view('recipeDetails.mobileCreate',array('items' => $items, 'units' => $unit));
        } else {
            return view('recipeDetails.create',array('action'=>'add','items' => $items, 'units' => $unit));
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateRecipeDetailsRequest $request)
    {

        $owner_id=Auth::user()->id;
        $user=Auth::user();

        $Outlet_details=Outlet::getoutletbyownerid($owner_id);
        $input = Input::all();


            $recipeDetails = new RecipeDetails();
            if (isset($owner_id)) {
                $recipeDetails->owner_id = $owner_id;
            }

            if (isset($input['outlet_id'])) {
                $recipeDetails->outlet_id = "";
            }

            if (isset($input['menu_title'])) {
                $recipeDetails->menu_title_id = $input['menu_title'];
            }

            if (isset($input['menu_item_id'])) {
                $recipeDetails->menu_item_id = $input['menu_item_id'];
            }

            $recipeDetails->recipe_name = Menu::where('id', $input['menu_item_id'])->first()->item;

            if (isset($input['referance'])) {
                $recipeDetails->referance = $input['referance'];
            }

            if (isset($input['ingred_method'])) {
                $recipeDetails->ingred_method = $input['ingred_method'];
            }

            $success = $recipeDetails->save();

            if ($success && isset($input['ing_qty'])) {
                $ing_name = $input['ing_name'];
                $ing_qty = $input['ing_qty'];

                if (isset($ing_name) && sizeof($ing_name) > 0) {
                    foreach ($ing_name as $key => $ing) {
                        if ( isset($ing) && $ing != '' ) {
                            $ingredients = new Ingredients();
                            $ingredients->recipeDetails_id = $recipeDetails->id;
                            $ingredients->ing_item_id = $ing;
                            $ingredients->qty = $ing_qty[$key];
                            $ingredientsStored = $ingredients->save();
                        }
                    }
                }

                if (!$ingredientsStored) {
                    return Redirect('/menu/' . $input['menu_item_id'] . '/show?flag=ingredients')->with('error', 'Failed');
                } else {
                    return Redirect('/menu/' . $input['menu_item_id'] . '/show?flag=ingredients')->with('success', 'Ingredient added successfully ');

                }

            }else{
                return Redirect('menu/'.$input['menu_item_id'].'/show')->with('success', 'Recipe added successfully ');
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
    //print_r($id);exit;
        $owner_id=Auth::user()->id;

        $temp=RecipeDetails::where('id',$id)->lists('menu_item_id');
        $items=[];
        foreach($temp as $item){
            $items[$item]=Menu::where('id',$item)->first()->item;
        }
        //$recipes=array_fill_keys(array(''),'Select item')+$items;
        $referance_qty = RecipeDetails::where('id',$id)->get()[0]->referance;
        //print_r();exit;
        $recipe_name= RecipeDetails::where('id',$id)->get()[0]->recipe_name;
        $referance_qty = RecipeDetails::where('id',$id)->get()[0]->referance;
        $recipe_item_unit = RecipeDetails::where('recipeDetails.id',$id)->join('menus','menus.id','=','recipeDetails.menu_item_id')->get()[0]->unit_id;
        $unit = Unit::getunit();

        //return view('recipeDetails.show',array('outlet_details' => $Outlet_details, 'units' => $unit));

        if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
            return view('recipeDetails.mobileShow',array('recipes' => $items,'outlet_id' => '','recipe' => '','qty1' => '','recipe_name' => "", 'ingredients' => '', 'ingred_name' => '', 'ingred_qty' => '', 'ingred_unit' => '', 'qty' => '', 'referance' => '','unit' => $unit));
        } else {
            return view('recipeDetails.show',array('recipes' => $items,'outlet_id' => '','recipe' => $recipe_name,'qty1' => $referance_qty,'recipe_name' => "", 'ingredients' => '', 'ingred_name' => '', 'ingred_qty' => '', 'ingred_unit' => Unit::getUnitbyId($recipe_item_unit)->name, 'qty' => '', 'referance' => '','unit' => $unit));
        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $items=array();
        $temp=Menu::where('created_by',Auth::id())->get();//lists('item','id');
        $items['']='Select';
        foreach($temp as $item){
            $items[$item->id]=$item->item;
        }
        $recipe = RecipeDetails::find($id);
        $method = $recipe->ingred_method;
        $unit = Unit::find(Menu::find($recipe->menu_item_id)->unit_id);

        $recipe_items = array();
        if ( isset($recipe) && sizeof($recipe) > 0 ) {
            $recipe_items = Ingredients::join('menus','menus.id','=','ingredients.ing_item_id')
                            ->join('unit as u','u.id','=','menus.unit_id')
                            ->where('recipeDetails_id',$id)->get();
        }

        return view('recipeDetails.edit',array('action'=>'edit','ingred_method' => $method,'recipe'=>$recipe,'recipe_items'=>$recipe_items,'items'=>$items,'unit'=>$unit));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $owner_id=Auth::id();

        $input = Input::all();

        $recipeDetails = RecipeDetails::find($id);
        if (isset($owner_id)) {
            $recipeDetails->owner_id = $owner_id;
        }

        if (isset($input['outlet_id'])) {
            $recipeDetails->outlet_id = "";
        }

        if (isset($input['menu_title'])) {
            $recipeDetails->menu_title_id = $input['menu_title'];
        }

        if (isset($input['menu_item_id'])) {
            $recipeDetails->menu_item_id = $input['menu_item_id'];
        }

        $recipeDetails->recipe_name = Menu::where('id', $input['menu_item_id'])->first()->item;

        if (isset($input['referance'])) {
            $recipeDetails->referance = $input['referance'];
        }

        if (isset($input['ingred_method'])) {
            $recipeDetails->ingred_method = $input['ingred_method'];
        }

        $success = $recipeDetails->save();

        if ($success && isset($input['ing_qty'])) {
            $ing_name = $input['ing_name'];
            $ing_qty = $input['ing_qty'];

            Ingredients::where('recipeDetails_id',$id)->delete();

            if (isset($ing_name) && sizeof($ing_name) > 0) {
                foreach ($ing_name as $key => $ing) {
                    if ( isset($ing) && $ing != '' ) {
                        $ingredients = new Ingredients();
                        $ingredients->recipeDetails_id = $recipeDetails->id;
                        $ingredients->ing_item_id = $ing;
                        $ingredients->qty = $ing_qty[$key];
                        $ingredientsStored = $ingredients->save();
                    }
                }
            }

            if ( isset($input['flag']) && $input['flag'] == 'item_master') {

                if (!$ingredientsStored) {
                    return Redirect('/menu/'.$input["menu_item_id"]."/show?flag=ingredients")->with('error', 'Failed');
                } else {
                    return Redirect('/menu/'.$input['menu_item_id']."/show?flag=ingredients")->with('success', 'Ingredients updated successfully ');
                }

            }else{

                if (!$ingredientsStored) {
                    return Redirect('/recipeDetails/'.$id.'/edit')->with('error', 'Failed');
                }
                if (preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])) {
                    return Redirect('/recipeDetails')->with('success', 'Ingredients added successfully ');
                } else {
                    return Redirect('/recipeDetails')->with('success', 'Ingredients updated successfully ');
                }
            }

        }else if ( isset($input['flag']) && $input['flag'] == 'method') {

            if (!$success) {
                return Redirect('/menu/' . $input["menu_item_id"] . "/show#recipe")->with('error', 'Failed');
            } else {
                return Redirect('/menu/' . $input['menu_item_id'] . "/show#recipe")->with('success', 'Recipe updated successfully ');
            }

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
//    print_r("Delete");exit;
        RecipeDetails::where('id',$id)->delete();
        Session::flash('success', 'Successfully deleted the Recipe!');
        return Redirect::to('recipeDetails');
    }

    public function ajaxRecipeList()
    {
        $outlet_id = Input::get('outlet_id');
        $recipes = RecipeDetails::getRecipeDetailsByOutletId($outlet_id);
        //print_r($recipes);exit;

        if( sizeof($recipes) > 0 && $recipes != ''){
            $select_box = '<option value="" selected >Select Item</option>';
            foreach( $recipes as $recipe ){
                //$menu_item = Menu::getMenuItemByTitleIdandMenuId($recipe->menu_item_id);
                $select_box .= '<option value="'.$recipe->id.'">'.$recipe->item.'</option>';
            }
            return array('list' => $select_box);

        }else{
            $select_box = '<option value="0" selected >No Recipe In This This Outlet</option>';
            return array('list' => $select_box);
        }
    }

    public function ajaxQtyUnit()
    {
        $recipe_id = Input::get('recipe_id');
        $recipe = RecipeDetails::getRecipeDetailsById($recipe_id);
        //print_r($recipe_id);
        $needed_qty = Input::get('needed_qty');

        //print_r($needed_qty);exit;
        $unit = Unit::getUnitbyId(Menu::find($recipe->menu_item_id)->unit_id);
        //print_r($recipe);exit;
//            $recipeDetails = RecipeDetails::getRecipeDetailsById($recipe);
//        print_r($recipeDetails);exit;
        $need_unit_box = '';
        $unit_box = '';
        $ingred_count=0;
        $table_data = array();
        $ingred_table= array();
        if( sizeof($recipe) > 0 && $recipe != ''){
            $unit_box .= ''.$recipe->referance.' '. $unit->name.'';
            $need_qty_box = $needed_qty;
            $need_unit_box = $unit->name;
            $ingredients = Ingredients::getIngredientsByRecipeId($recipe->id);
            //print_r($ingredients);exit;
            foreach($ingredients as $ingredient)
            {
                $ingred= array();

                $temp=array();
                $ingred_name = $ingredient->item;

                $temp_qty = $ingredient->qty/$recipe->referance;
                $ingred_qtys = $temp_qty*$needed_qty;

                if(is_numeric($ingred_qtys)){
                    $ingred_qty = number_format($ingred_qtys,1);
                } else {
                    $ingred_qty = number_format($ingred_qtys,1);
                }

                $unit = Unit::getUnitbyId(Menu::find($ingredient->ing_item_id)->unit_id);
                $ingred_unit = $unit->name;
                array_push($temp,$ingred_name,$ingred_qty,$ingred_unit);
                array_push($table_data,$temp);

                $ingred['i_name']=$ingred_name;
                $ingred['i_qty']=$ingred_qty;
                $ingred['i_unit']=$ingred_unit;
                array_push($ingred_table,$ingred);

                $ingred_count++;
            }
        }else{
            $need_unit_box = '';
            $need_qty_box = '';
            $unit_box = '';
        }
        $recipe_name = $recipe->recipe_name;
        $d = array('data_table' => $ingred_table, 'recipe_name' => $recipe_name, 'qty' => $recipe->referance, 'unit' => $need_unit_box,'check_print' => 'check_recipe');
        $json_data = json_encode($d);

        return array('print_data'=>$json_data,'qty1'=>$recipe->referance,'need_qty_box' => $need_qty_box,'ingred_count'=>$ingred_count,'need_unit_box' => $need_unit_box, 'unit_box' => $unit_box, 'table_data' => $table_data);

    }

    public function checkRecipe()
    {
        $outlet_id = Input::get('outlet_id');
        $recipe = Input::get('recipe_id');
        $qty = Input::get('qty');

        $recipeDetails = RecipeDetails::getRecipeDetailsById($recipe);

        $recipe_name = Menu::getMenuItemByTitleIdandMenuId($recipeDetails->menu_item_id);
        $ingredients = Ingredients::getIngredientsByRecipeId($recipe);
        $table_data = '';
        foreach($ingredients as $ingredient)
        {
            $ingred_name = $ingredient->item;
            $temp_qty = $ingredient->qty/$recipeDetails->referance;
            $ingred_qtys = $temp_qty*$qty;
            if(is_numeric($ingred_qtys)){
                $ingred_qty = number_format($ingred_qtys,1);
            } else {
                $ingred_qty = number_format($ingred_qtys,1);
            }


            $unit = Unit::getUnitbyId(Menu::find($ingredient->ing_item_id)->unit_id);
            $ingred_unit = $unit->name;

            $table_data .= '<tr>
                        <td>'.$ingred_name.'</td>
                        <td>'.$ingred_qty.'</td>
                        <td>'.$ingred_unit.'</td>
                     </tr>';
        }
        return array('table_data' => $table_data);
    }

    public function findRecipe()
    {
//    print_r("here");exit;
        $qty = Input::get('qty');
        $recipes=array_fill_keys(array(''),'No Items');

        $recipe_id = Input::get('recipe');
        //print_r($recipe_id);exit;
        if($recipe_id==""){
            return Redirect::back()->withInput(Input::all())->with('error','Recipe Item is require');
        }else{
            $recipes=RecipeDetails::where('owner_id',Auth::id())->join('menus','menus.id','=','recipeDetails.menu_item_id')
                ->select('menus.item','menus.id')->get();

            $rec = [];
            foreach($recipes as $recip){
                $rec[$recip->id]= $recip->item;
            }
            //print_r($rec);exit;

            $recipes = array_fill_keys(array(''),'Select item')+$rec;
            //$recipe = RecipeDetails::getRecipeDetailsById($recipe_id);
            $recipeDetails = RecipeDetails::getRecipeDetailsById($recipe_id);
            //print_r($recipeDetails->id);exit;
            $unit1 = Unit::getUnitbyId(Menu::find($recipeDetails->menu_item_id)->unit_id);
            $recipe_name = $recipeDetails->recipe_name;
            $ingredients = Ingredients::getIngredientsByRecipeId($recipeDetails->id);
            $ingred_table= array();
            foreach($ingredients as $ingredient)
            {
                $ingred= array();
                $ingred_name = $ingredient->item;
                $temp_qty = $ingredient->qty/$recipeDetails->referance;
                $ingred_qtys = $temp_qty*$qty;
                if(is_numeric($ingred_qtys)){
                    $ingred_qty = number_format($ingred_qtys,1);
                } else {
                    $ingred_qty = number_format($ingred_qtys,1);
                }
                $unit = Unit::getUnitbyId(Menu::find($ingredient->ing_item_id)->unit_id);
                $ingred_unit = $unit->name;

                $ingred['i_name']=$ingred_name;
                $ingred['i_qty']=$ingred_qty;
                $ingred['i_unit']=$ingred_unit;
                array_push($ingred_table,$ingred);
            }

        }

        if(!isset($qty))
            $qty='';
        $owner_id=Auth::user()->id;
        //$unit = Unit::getunit();
        $d = array('data_table' => $ingred_table, 'recipe_name' => $recipe_name, 'qty' => $qty, 'unit' => $unit1->name, 'check_print' => 'check_recipe');
        $json_data = json_encode($d);

        if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
            return view('recipeDetails.mobileShow',array('print_data'=>$json_data,'data_table'=>$ingredients,'needed_unit' =>  $unit1->name,'unit_box'=>$recipeDetails->referance.' '. $unit1->name,'recipes' => $recipes,'unit' => $unit1->name,'recipe' => $recipe_id,'qty1' => $qty, 'ingredients' => $ingredients, 'qty' => $qty, 'referance' => $recipeDetails->referance));
        } else {
            return view('recipeDetails.show',array('print_data'=>$json_data,'data_table'=>$ingredients,'needed_unit' =>  $unit1->name,'unit_box'=>$recipeDetails->referance.' '. $unit1->name,'recipes' => $recipes,'unit' => $unit1->name,'recipe' => $recipe_id,'qty1' => $qty, 'ingredients' => $ingredients, 'qty' => $qty, 'referance' => $recipeDetails->referance));
        }

    }

    public function ajaxGetItemUnit(){
        $item_id=Input::get("item_id");
        $unit=DB::table('menus')
            ->leftJoin('unit','unit.id','=','menus.unit_id')
            ->select('unit.name as unit_name','menus.unit_id')
            ->where('menus.id','=',$item_id)->get();
        return $unit;
    }

    public function PrepareRecipe() {

        $owner_id = Auth::id();

        $recipes = RecipeDetails::where('owner_id',Auth::id())
                                ->join('menus','menus.id','=','recipeDetails.menu_item_id')
                                ->select('menus.item','menus.id','menus.unit_id')->get();


        $locations = [ '' => 'Select Location'];
        $locations_list = Location::where('created_by',$owner_id)->lists('name','id');

        if( isset($locations_list) && sizeof($locations_list) > 0 ) {
            foreach ( $locations_list as $loc => $loc_val ) {
                $locations[$loc] = $loc_val;
            }
        }

        /*Unit array*/
        $units = ['' => 'Select Unit'];
        $unit_list = Unit::lists('name','id')->all();
        if( isset($unit_list) && sizeof($unit_list) > 0 ) {
            foreach ( $unit_list as $u => $u_val ) {
                $units[$u] = $u_val;
            }
        }


        return view('recipeDetails.prepareRecipe',array('recipes'=>$recipes,'locations'=>$locations,'units'=>$units));

    }

    public function getRecipe() {

        $owner_id = Auth::id();
        $rec_id = Input::get('recipe_id');
        $qty = Input::get('qty');
        $ingrd = array();

        $rec_detail = RecipeDetails::getRecipeDetailsById($rec_id);
        $ingredients = Ingredients::getIngredientsByRecipeId($rec_detail->id);

        if ( isset($ingredients) && sizeof($ingredients) > 0 ) {
            $i = 0;
            foreach($ingredients as $ingredient)
            {
                $ingrd[$i]['ingrd_id'] = $ingredient->ing_item_id;
                $ingrd[$i]['ingrd_name'] = $ingredient->item;
                $ingrd[$i]['qty'] = number_format(floatval($ingredient->qty) * floatval($qty) / floatval($rec_detail->referance),3);
                $ingrd[$i]['unit_id'] = Menu::find($ingredient->ing_item_id)->unit_id;
                $ingrd[$i]['unit_name'] = $ingredient->unit_name;

                $i++;
            }

        }

        //locations
        $locations = Location::where('created_by',$owner_id)->lists('name','id');

        return view('recipeDetails.prepareRecipeItems',array('ingred'=>$ingrd,'locations'=>$locations));

    }

    public function processPrepareItem() {

        $input = Input::all();
        $owner_id = Auth::id();
        $response = array();

        $error = false;$error_msg = '';

        $rec_id = Input::get('recipe_id');
        $rec_qty = Input::get('qty');
        $for_loc = Input::get('for_loc');

        if ( isset($rec_id) && $rec_id != '' && isset($rec_qty) && $rec_qty != '' && isset($for_loc) && $for_loc != '') {

            DB::beginTransaction();

            //transaction_id
            $transaction_id = uniqid();
            if ( isset($input['quantity']) && sizeof($input['quantity']) > 0 ) {

                for( $i=0; $i<sizeof($input['quantity']); $i++ ) {

                    $decr_qty = explode(' ',$input['quantity'][$i]);

                    /*decrease item from location*/
                    $from_loc_stock = Stock::where('location_id', $input['location'][$i])
                        ->where('item_id', $input['item_id'][$i])
                        ->first();


                    if ( isset($from_loc_stock) && sizeof($from_loc_stock) > 0 ) {

                        $remain_qty = floatval($from_loc_stock->quantity) - floatval($decr_qty[0]);
                        $stock = Stock::find($from_loc_stock->id);
                        $stock->quantity = $remain_qty;
                        $stock->updated_by = $owner_id;
                        $from_loc_result = $stock->save();

                    } else {

                        $stk = new Stock();
                        $stk->item_id = $input['item_id'][$i];
                        $stk->quantity = 0 - floatval($decr_qty[0]);
                        $stk->location_id = $input['location'][$i];
                        $stk->created_by = $owner_id;
                        $stk->updated_by = $owner_id;
                        $from_loc_result = $stk->save();
                    }

                    if ( $from_loc_result ) {

                        $stk_hist_rem = new StockHistory();
                        $stk_hist_rem->transaction_id = $transaction_id;
                        $stk_hist_rem->from_location = $input['location'][$i];
                        $stk_hist_rem->to_location = $for_loc;
                        $stk_hist_rem->item_id = $input['item_id'][$i];
                        $stk_hist_rem->quantity = $decr_qty[0];
                        $stk_hist_rem->type = 'stock removed on item prepared';
                        $stk_hist_rem->created_by = $owner_id;
                        $stk_hist_rem->updated_by = $owner_id;
                        $stk_hist_rem_result = $stk_hist_rem->save();

                        if ( !$stk_hist_rem_result ) {
                            $response['error_msg'] = 'Some error occurred';
                            $response['error'] = true;
                            DB::rollBack();
                            break;
                        }

                    } else {
                        $response['error_msg'] = 'Some error occurred';
                        $response['error'] = true;
                        DB::rollBack();
                        break;
                    }

                }

                /*add quantity in for_location*/
                $for_loc_stock = Stock::where('location_id', $for_loc)
                    ->where('item_id', $rec_id)
                    ->first();

                if ( isset($for_loc_stock) && sizeof($for_loc_stock) >  0 ) {

                    $new_qty = floatval($rec_qty) + floatval($for_loc_stock->quantity);
                    $stock1 = Stock::find($for_loc_stock->id);
                    $stock1->quantity = $new_qty;
                    $stock1->updated_by = $owner_id;
                    $for_loc_result = $stock1->save();

                } else {

                    $stock1 = new Stock();
                    $stock1->item_id = $rec_id;
                    $stock1->quantity = $rec_qty;
                    $stock1->location_id = $for_loc;
                    $stock1->created_by = $owner_id;
                    $stock1->updated_by = $owner_id;
                    $for_loc_result = $stock1->save();
                }

                if ( $for_loc_result ) {

                    $stk_hist_add = new StockHistory();
                    $stk_hist_add->transaction_id = $transaction_id;
                    $stk_hist_add->from_location = NULL;
                    $stk_hist_add->to_location = $for_loc;
                    $stk_hist_add->item_id = $rec_id;
                    $stk_hist_add->quantity = $rec_qty;
                    $stk_hist_add->type = 'Stock added on item prepared';
                    $stk_hist_add->created_by = $owner_id;
                    $stk_hist_add->updated_by = $owner_id;
                    $check_status = $stk_hist_add->save();

                    if ( $check_status ) {
                        DB::commit();
                        $response['error_msg'] = 'Item successfully prepared';
                        $response['error'] = false;
                    } else {
                        DB::rollBack();
                        $response['error_msg'] = 'Some input are wrong, Please check';
                        $response['error'] = true;
                    }


                } else {
                    DB::rollBack();
                    $response['error_msg'] = 'Some input are wrong, Please check';
                    $response['error'] = true;
                }

            } else {
                $response['error_msg'] = 'No ingredients available';
                $response['error'] = true;
            }

        } else {
            $response['error_msg'] = 'Please fill all required field';
            $response['error'] = true;
        }

        return json_encode($response);
    }

    public function destroyRecipe($id){

        $recipe = RecipeDetails::find($id);
        $ingred_result = Ingredients::where('recipeDetails_id',$id)->get();
        if(!isset($ingred_result)){
            $recipe_destroy = RecipeDetails::where('id',$id)->delete();
        }else{
            $recipe->ingred_method = '';
            $recipe_destroy = $recipe->save();
        }
        if (!$recipe_destroy) {
            return Redirect('/menu/'.$recipe->menu_item_id."/show")->with('error', 'Failed');
        } else {
            return Redirect('/menu/'.$recipe->menu_item_id."/show")->with('success', 'Recipe deleted successfully ');
        }

    }

    public function destroyIngredients($id){

        $recipe = RecipeDetails::find($id);
        if($recipe->ingred_method != ''){
            $ingred_destroy = Ingredients::where('recipeDetails_id',$id)->delete();
        }else{
            $ingred_destroy = RecipeDetails::where('id',$id)->delete();
        }
        if (!$ingred_destroy) {
            return Redirect('/menu/'.$recipe->menu_item_id."/show")->with('error', 'Failed');
        } else {
            return Redirect('/menu/'.$recipe->menu_item_id."/show")->with('success', 'Recipe deleted successfully ');
        }

    }


}