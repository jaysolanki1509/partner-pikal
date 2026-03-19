<?php namespace App\Http\Controllers;

use App\CouponCodes;
use App\Http\Controllers\Api\v1\ApiController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Menu;
use App\menu_option;
use App\MenuTitle;
use App\OutletMapper;
use App\ordercouponmapper;
use App\Owner;
use Illuminate\Support\Facades\Auth;
use App\Outlet;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class AddOrderController extends ApiController {


    public function index()
    {
//        print_r("here");exit;
        /*$owner_id=Auth::user()->id;
        $mappers = OutletMapper::getOutletIdByOwnerId($owner_id);

        foreach($mappers as $mapper)
        {
            $mapper_arr[] = $mapper['outlet_id'];
        }
        $Outlet=Outlet::whereIn('id',$mapper_arr)->get();*/
//        $Outlet=Outlet::getoutletbyownerid($owner_id);
    }

    public function create()
    {
        $owner_id=Auth::user()->id;
//        $Outlet=Outlet::getoutletbyownerid($owner_id);
        $mappers = OutletMapper::getOutletIdByOwnerId($owner_id);
        $mapper_arr=[];
        foreach($mappers as $mapper)
        {
            $mapper_arr[] = $mapper['outlet_id'];
        }
        $Outlet=Outlet::whereIn('id',$mapper_arr)->get();
//        print_r($Outlet);exit;
        $totalOutletunderuser=count($Outlet);
        return view('createorder.createorder',array('Outlet' => $Outlet, 'totalOutletcount' => $totalOutletunderuser));
    }

    public function store()
    {
        $input = Input::all();
        //print_r($input);exit;
        $order = array();
        $count = Input::get('count');
        $menu_item = array();
        $coupon_ary = array();
        $totalcost = '';
        $flag = '';
        if ( isset($count) && $count > 0 ){
            for ($i = 1; $i <= $count; $i++) {
                $item_qty = Input::get('item_qty_' . $i);
                $item_rs = Input::get('item_rs_' . $i);
                $menu = Input::get('menu_item_' . $i);
                $extra_options = Input::get('extra_'.$i.'_options');
                //print_r($extra_options);exit;
                if( isset($item_qty) && isset($item_rs) && $item_rs != 0.00  ){
                    $menu_item[$i]['quantity'] = Input::get('item_qty_' . $i);
                }
                if( isset($item_rs) && $item_rs != 0.00 ){
                    $menu_item[$i]['price'] = Input::get('item_rs_' . $i);

                }
                if( isset($menu) && $menu != 0 ){
                    $flag = 'items';
                    $menu_item[$i]['item_id'] = Input::get('menu_item_' . $i);
                }
                $options_price = '';
                $options = '';
                if ( isset($extra_options) && sizeof( $extra_options ) != 0 ){
                    $n = 1;
                    foreach( $extra_options as $extra ){
                        //print_r($extra);exit;
                        $getprice_data = menu_option::where('id', $extra)->first();
                        if ( sizeof($getprice_data) > 0 ){
                            if( $n == 1){
                                $options_price .= $getprice_data->opt_price;
                                $options .= $getprice_data->opt;
                            }else{
                                $options_price .= ','.$getprice_data->opt_price;
                                $options .= ','.$getprice_data->opt;
                            }
                        }
                    $n++;
                    }
                    $menu_item[$i]['options_price'] = $options_price;
                    $menu_item[$i]['options'] = $options;
                }
                //$totalcost += Input::get('item_rs_' . $i);
            }
            if ( $flag == 'items'){

                $coupon_flag = Input::get('coupon_flag');
                $coupon_code = Input::get('coupon_box');
                if ( $coupon_flag == 'Yes' && isset($coupon_code) ){
                    $coupondata = CouponCodes::where('coupon_code', $coupon_code)->first();
                    $couponordermapper = ordercouponmapper::where('coupon_applied', $coupon_code)->where('user_mobile_number', Input::get('mobile'))->first();
                    //print_r($coupondata);exit;
                    if( sizeof($coupondata) > 0 && sizeof($couponordermapper) == 0 ){
                            //print_r($coupondata->coupon_code);exit;
                            $coupon_ary['id'] = $coupondata->id;
                            $coupon_ary['coupon_code'] = $coupondata->coupon_code;
                            $coupon_ary['updated_at'] = $coupondata->updated_at;
                            $coupon_ary['percentage'] = $coupondata->percentage;
                            $coupon_ary['value'] = $coupondata->value;
                            $coupon_ary['created_at'] = $coupondata->created_at;
                            $coupon_ary['outlet_id'] = $coupondata->outlet_id;
                            $coupon_ary['max_value'] = $coupondata->max_value;
                            $coupon_ary['activated_datetime'] = $coupondata->activated_datetime;
                            $coupon_ary['no_of_users'] = $coupondata->no_of_users;
                            $coupon_ary['expire_datetime'] = $coupondata->expire_datetime;
                            $coupon_ary['min_value'] = $coupondata->min_value;

                        $order['order']['coupon_applied'] = 'Yes';
                        $order['order']['coupondata'] = $coupon_ary;
                    }else{
                        $order['order']['coupon_applied'] = 'No';
                    }

                }else{
                    $order['order']['coupon_applied'] = 'No';
                }
                $order['order']['discounted_value'] = Input::get('discount_input');
                $order['order']['local_id'] = '';
                $order['order']['mobile_number'] = Input::get('mobile');
                $order['order']['name'] = Input::get('name');
                $order['order']['restaurant_id'] = Input::get('outlets');
                $order['order']['device_id'] = '';
                $order['order']['table'] = Input::get('table');;
                $order['order']['totalcost_afterdiscount'] = Input::get('totalcost_afterdiscount');
                $order['order']['moneystatus'] = 'COD';
                $order['order']['total_price'] = Input::get('total_price');
                $order['order']['menu_item'] = $menu_item;
                $order['order']['order_type'] = Input::get('service');
                $order['order']['flag'] = 'webapp_order';
                $order['order']['address'] = Input::get('address');
                $order['order']['simpleaddress'] = Input::get('address');
               // print_r($order);exit;
                //$order_json = json_encode($order);
                $send_orderdetails = \App::make('App\Http\Controllers\Api\v1\ApiController')->orderdetails($order);
                if($send_orderdetails == 'success'){
                    return Redirect('/')->with('success', 'Order Placed Successfully.');
                }else{
                    return Redirect('/createorder/create')->withInput(Input::all())->with('failure', 'Something entered wrong input');
                }
        }else{
                return Redirect('/createorder/create')->withInput(Input::all())->with('failure', 'Please add menu items.');
            }
           // print_r($order_json);exit;
        }else{
            return Redirect('/createorder/create')->withInput(Input::all())->with('failure', 'Please add menu items.');
        }
       //echo $totalcost;exit;

    }

    /*public function getmenu_title()
    {
//        print_r("here");exit;
        $outlet_id = Input::get('rest_id');
        $active = '0';
//        print_r($outlet_id);exit;
        $menu_title = MenuTitle::getmenutitlebyrestaurantidandactive($outlet_id,$active);
//        print_r($menu_title);exit;

        $select_title = '<option value="0" selected >Select Menu Title</option>';
        foreach( $menu_title as $title ){
            $select_title .= '<option value="'.$title->id.'">'.$title->title.'</option>';
        }

//        print_r($select_title);exit;
//print_r($alltitle);exit;
        return array('select_title' => $select_title);
    }

    public function getmenu_item()
    {
//        print_r("here");exit;
//        $outlet_id = Input::get('outlet_id');
        $menu_title_id = Input::get('menu_title');


        $active = '0';
//        print_r($outlet_id);exit;
        $menu_item = Menu::getmenubymenutitleid($menu_title_id);
//        print_r($menu_item);exit;

        $select_item = '<option value="0" selected >Select Menu Item</option>';
        foreach( $menu_item as $item){
            $select_item .= '<option value="'.$item->id.'">'.$item->item.'-'.$item->price.'</option>';
        }

//        print_r($select_item);exit;

        return array('select_item' => $select_item);
    }*/

    public function get_price()
    {
        $input = Input::all();
        $menu_item_id = Input::get('menu_item_id');
        $item_qty = Input::get('item_qty');
        $i = Input::get('i_val');
        $extra = Input::get('extra_array');
        $flag = Input::get('flag');
        $options_list = '';
        $options = array();
        $option_flag = '';
        $tot_extra_price = 0;
        $implode_extra = explode(',', $extra);
      //  print_r($implode_extra);exit;
        $menu_item = Menu::getMenuItemByTitleIdandMenuId($menu_item_id);
        if ( !sizeof($implode_extra) > 0 || $extra == 'undefined' || $extra == '' ){
            $options = menu_option::where('menu_id', $menu_item_id)->get();
        }else {
                $option_flag = 'extra';
                foreach ($implode_extra as $imp) {
                    //print_r($imp);exit;
                    $extra_price = menu_option::where('id', $imp)->first();
                    if (sizeof($extra_price) > 0) {
                        $tot_extra_price += $extra_price->opt_price;
                    }
                }
                $tot_extra_price = $tot_extra_price * $item_qty;

        }
        $select = '';
        if ( sizeof($options) > 0 && ($extra == 'undefined' || $extra == '') ){
            $option_flag = 'option';
            $options_list = '<div class="col-md-2 extra-div_'.$i.'"><select class="form-control sumo-option" id="extra_options_'.$i.'" multiple="true" name="extra_'.$i.'_options[]">';
            foreach( $options as $opt ){
                $options_list .= '<option value="'.$opt->id.'">'.$opt->opt.'</option>';
            }
            $options_list .= '</select></div>';
        }
        //print_r($options_list);exit;
        $price = $menu_item->price;

        $tot_qty_rs = ($price * $item_qty) + $tot_extra_price;
//        print_r($tot_qty_rs);exit;

        $tot_qty_price = 0.00;
        if (is_float($tot_qty_rs)){
            $tot_qty_price = $tot_qty_rs;
        }else{
            $tot_qty_price = $tot_qty_rs;
        }
        $tot_extra_price = 0;
        return array('tot_qty_price' => $tot_qty_price,'price' => $price, 'option_flag' => $option_flag, 'options' => $options_list, 'menu_item_id' => $menu_item_id);

    }
    public function getServiceTypeOutletList()
    {
        //echo 'here';exit;
        $service_type = Input::get('service_type');
        $login_user_id = Auth::id();
        //$response = array();
        if ( isset($service_type) ){
//            $outletList = Outlet::where('owner_id', $login_user_id)->get();
            $mappers = OutletMapper::getOutletIdByOwnerId($login_user_id);

            foreach($mappers as $mapper)
            {
                $mapper_arr[] = $mapper['outlet_id'];
            }
            $outletList=Outlet::whereIn('id',$mapper_arr)->get();
//            print_r($outletList);exit;
            $select_box = '';
            if( sizeof($outletList) > 0 ){
                foreach( $outletList as $list ){
                    if( isset($list->service_type) ){
                        $service_obj = unserialize($list->service_type);
                       // print_r($service_obj);exit;
                        if( sizeof($service_obj) > 0 ){
                                for($x = 1; $x <= sizeof($service_obj); $x++) {
                                    if ($service_obj[$x-1] == $service_type) {
                                        $select_box .= '<option value="' . $list->id . '">' . $list->name . '</option>';
                                    }
                                }
                        }
                    }
                }
                return array('message' => 'success', 'data' => $select_box);
            }else{
                return array('message' => 'error', 'data' => $select_box);
            }
        }else{
            return array('message' => 'error', 'data' => '');
        }
    }


}