<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Menu extends Model {


    protected $table = 'menus';
    use SoftDeletes;
    protected $softDelete = true;

    public function user()
    {
        return $this->belongsTo('App\Owner','user_id','id');
    }

    public function Outletcuisinetype()
    {
        return $this->hasMany('App\OutletCuisineType','outlet_id');
    }
    public function OutletOutlettype()
    {
        return $this->hasMany('App\OutletTypeMapper','outlet_id');
    }

    public function orderitem()
    {
        return $this->hasMany('App\OrderItem','item_id','id');
    }

    public function inventories()
    {
        return $this->hasMany('App\Inventory', 'menu_id', 'id');
    }

    public static function getmenubymenutitleid($id){
        $menud=Menu::where('menu_title_id', $id)->get();
        return $menud;
    }
    public static function getmenubymenutitleidanditemname($title_id,$outlet_id,$itemname){
        $menu=Menu::where('menu_title_id', $title_id)->where('item', $itemname)->where('outlet_id', $outlet_id)->first();
        return $menu;
    }

    public static function getItemsQuanityonLocation($cat_id,$location_id) {

        $menu_owner = Owner::menuOwner();

        if($cat_id == 'all'){

            $menu_owner = Owner::menuOwner();
            $all_menu_titles_id = MenuTitle::getMenuTitleByCreatedBy($menu_owner)->lists('id');

            /*$menu_items_list = Menu::getMenuByUserId($menu_owner)->lists('id');

            $request_false = StockLevel::where('request_item','=','false')
                        ->where('location_id',$location_id)
                        ->wherein('item_id',$menu_items_list)
                        ->lists('item_id');*/

            $menus = Menu::wherein('menus.menu_title_id',$all_menu_titles_id)
                ->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                ->leftjoin('unit as u','u.id','=','menus.unit_id')
                ->leftjoin('stock_level as sl','sl.item_id','=','menus.id')
                ->where('sl.request_item','true')
                ->where('sl.location_id',$location_id)
                ->where('menus.created_by',$menu_owner)
                //->whereNotIn('menus.id',$request_false)
                ->select('menus.id as id','menus.item as item','menus.order_unit as order_unit','menus.secondary_units as other_units','u.name as unit','u.id as unit_id','menus.menu_title_id')
                ->orderby('menus.menu_title_id')
                ->get();

            $menu_arr = array();
            if ( isset($menus) && sizeof($menus) > 0 ) {
                $i = 0;
                foreach( $menus as $menu ) {

                    $menu_arr[$i]['id'] = $menu->id;
                    $menu_arr[$i]['unit'] = $menu->unit;
                    $menu_arr[$i]['unit_id'] = $menu->unit_id;
                    $menu_arr[$i]['order_unit'] = $menu->order_unit;
                    $menu_arr[$i]['title_id'] = $menu->menu_title_id;
                    $menu_arr[$i]['item'] = $menu->item;

                    //get stock detail
                    $stock = Stock::where('item_id',$menu->id)->where('location_id',$location_id)->first();
                    //get open stock detail
                    $open_stock = StockLevel::where('location_id',$location_id)->where('item_id',$menu->id)->first();

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

            /*$menu_items_list = Menu::getMenuByUserId($menu_owner)->lists('id');

            $request_false = StockLevel::where('request_item','=','false')
                ->where('location_id',$location_id)
                ->wherein('item_id',$menu_items_list)->lists('item_id');*/

            $menus = Menu::leftjoin('unit as u','u.id','=','menus.unit_id')
                ->leftjoin('stock_level as sl','sl.item_id','=','menus.id')
                ->where('sl.request_item','true')
                ->where('sl.location_id',$location_id)
                ->where('menus.created_by',$menu_owner)
                ->select('menus.id as id','menus.item as item','menus.order_unit as order_unit','menus.secondary_units as other_units','u.name as unit','u.id as unit_id','sl.opening_qty as opening_stock')
                //->wherenotin('menus.id',$request_false)
                ->where('menus.menu_title_id', $cat_id)->get();

            $menu_arr = array();
            if ( isset($menus) && sizeof($menus) > 0 ) {
                $i = 0;
                foreach( $menus as $menu ) {

                    $menu_arr[$i]['id'] = $menu->id;
                    $menu_arr[$i]['unit'] = $menu->unit;
                    $menu_arr[$i]['unit_id'] = $menu->unit_id;
                    $menu_arr[$i]['order_unit'] = $menu->order_unit;
                    $menu_arr[$i]['item'] = $menu->item;
                    $stock = Stock::where('item_id',$menu->id)->where('location_id',$location_id)->first();
                    //get open stock detail
                    $open_stock = StockLevel::where('location_id',$location_id)->where('item_id',$menu->id)->first();

                    if ( isset($stock) && sizeof($stock) > 0 ) {
                        $menu_arr[$i]['stock'] = $stock->quantity;

                        if ( isset($menu->opening_stock) && $menu->opening_stock != '' ) {
                            $req_stock = $menu->opening_stock - $stock->quantity;
                            $menu_arr[$i]['open_stock'] = $req_stock;
                        } else {
                            $menu_arr[$i]['open_stock'] = '';
                        }

                    } else {
                        $menu_arr[$i]['stock'] = 0;

                        if ( isset($menu->opening_stock) && $menu->opening_stock != '' ) {
                            $menu_arr[$i]['open_stock'] = $menu->opening_stock;
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
        }

    }

    public static function deletemenuofoutlet($id){
        DB::table('menu_titles')->where('outlet_id',$id)->delete();
    }

    public static function getmenubyoutletid($outletid){
        $outlet=Menu::where('outlet_id',$outletid)->get();
        return $outlet;
    }

    public static function getMenuByUserId($user_id){
        $outlet=Menu::where('created_by',$user_id)->get();
        return $outlet;
    }

    // additional
//    get menu item by outletid and item name
    public static function getmenuitembyoutletid($outlet_id,$item){
        $outlet_item = Menu::where('outlet_id',$outlet_id)->where('item',$item)->first();
        return $outlet_item;
    }

    //    get menu item by titleid and outletid
    public static function getmenuitembytitleid($title_id,$outlet_id)
    {
        $title_items = Menu::where('menu_title_id', $title_id)->where('outlet_id', $outlet_id)->first();
        return $title_items;
    }

    public static function getMenuItemsList($outlet_id, $title_id){
        $menuList=Menu::where('menu_title_id', $title_id)->where('outlet_id', $outlet_id)->get();
        return $menuList;
    }
    public static function getMenuItemByTitleIdandMenuId($menu_item_id){
        $menuItem = Menu::where('id', $menu_item_id)->first();
        return $menuItem;
    }
    public static function getMenuAllItemsList($outlet_id){
        $menuList=Menu::where('outlet_id', $outlet_id)->get();
        return $menuList;
    }

    public static function getMenuById($item_id){
        $menu_detail = Menu::find($item_id);
        return $menu_detail;
    }

    public static function getItemIngredPrice($item_id){
        $total_price = 0;
        $ingreds = RecipeDetails::getIngredientsArr($item_id);
        if(isset($ingreds) && sizeof($ingreds)>0) {
            foreach ($ingreds as $ingred) {
                //print_r($ingred['qty']);exit;
                $total_price += isset($ingred['price']) ? $ingred['qty']*$ingred['price'] : 0;
            }
        }else{
            $menu = Menu::find($item_id);
            if (isset($menu))
                $total_price += isset($menu->buy_price) ? $menu->buy_price : 0;
        }

        return $total_price;
    }

    public static function getOtherUnits($id) {

        $other_unit = array();

        $menu = Menu::join('unit','unit.id','=','menus.unit_id')->where('menus.id',$id)->first();
        $other_unit[$menu->unit_id] = $menu->name;

        if ( isset($menu->secondary_units) && $menu->secondary_units != '') {
            $sec_unit = json_decode($menu->secondary_units);
            if ( isset($sec_unit) && sizeof($sec_unit) > 0 ) {
                foreach( $sec_unit as $key=>$un ) {
                    if ( !array_key_exists($key,$other_unit)) {
                        $unit = Unit::find($key);
                        $other_unit[$key] = $unit->name;
                    }
                }
            }
        }
        return $other_unit;

    }

    public static function geOutletMenu($rest_id) {

        $menu=MenuTitle::select('menus.*','menu_titles.title')
            ->join("outlet_menu_bind","outlet_menu_bind.menu_id","=","menu_titles.id")
            ->join("menus","menus.id","=","outlet_menu_bind.item_id")
            ->where('outlet_menu_bind.outlet_id',$rest_id)
            ->whereNull('menus.deleted_at')
            ->groupby("menus.id")
            ->orderBy('menus.item_order', 'asc')
            ->orderBy('menus.item', 'asc')
            ->get();

        $a=array();
        foreach($menu as $m) {

            $cuisine=CuisineType::find($m->cuisine_type_id);

            #TODO: check item is_sale and active outletwise
            $is_sale = 1;$active = 0;
            $itm_setting_arr = ItemSettings::where('outlet_id',$rest_id)->where('item_id',$m->id)->first();
            if( isset($itm_setting_arr) && ($itm_setting_arr->id) ) {
                if ( $itm_setting_arr->is_sale == 0 || $itm_setting_arr->is_active == 1 ) {
                    continue;
                }
            }

            $menuitem_options = ItemOptionGroup::getItemGroupOption($m->id);

            $inner_array=array('item_id'=>$m->id,
                'item'=>$m->item,
                'alias'=>$m->alias,
                'unit_id'=>$m->unit_id,
                'price'=>number_format($m->price,2,'.',''),
                'details'=>$m->details,
                'taxable'=>$m->taxable,
                'discountable'=>$m->discountable,
                'cuisinetype'=>$cuisine,
                'options'=>$m->options,
                'foodtype'=>$m->food,
                'active'=>$active,
                'is_sale'=>$is_sale,
                'like'=>$m->like,
                'menu_title'=>$m->title,
                'item_options'=>$menuitem_options
            );

            if(!array_key_exists($m->title,$a)) {
                $a[$m->title][] = $inner_array;
            } else {
                array_push($a[$m->title],$inner_array);
            }
        }

        return $a;

    }

    #TODO: provide only menu items
    public static function getOutletMenuItems($outlet_id) {

        $menu = Menu::select("menus.id as id","menus.item as item","menus.price as price")
            ->join("outlet_menu_bind","outlet_menu_bind.item_id","=","menus.id")
            ->where('outlet_menu_bind.outlet_id',$outlet_id)
            ->whereNull('menus.deleted_at')
            ->groupby("menus.id")
            ->orderBy('menus.item', 'asc')
            ->get();

        $item_arr = array();$i=0;

        if ( isset($menu) && sizeof($menu) > 0 ) {
            foreach ( $menu as $itm ) {

                #TODO: check item is_sale and active outletwise
                $is_sale = 1;$active = 0;
                $itm_setting_arr = ItemSettings::where('outlet_id',$outlet_id)->where('item_id',$itm->id)->first();

                if( isset($itm_setting_arr) && sizeof($itm_setting_arr) > 0 ) {
                    if ( $itm_setting_arr->is_sale == 0 || $itm_setting_arr->is_active == 1 ) {
                        continue;
                    }
                }

                $item_arr[$i]['id'] = $itm->id;
                $item_arr[$i]['name'] = $itm->item;
                $item_arr[$i]['price'] = $itm->price;
                $i++;
            }
        }

        return $item_arr;

    }

}
