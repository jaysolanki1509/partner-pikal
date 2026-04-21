<?php

namespace App;

use App\Http\Controllers\StocksController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use PhpSpec\Exception\Exception;
class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $fillable = array(
        'quantity',
        'price'
    );
    public function Orderitemdetails()
    {
        return $this->belongsTo('App\OrderDetails', 'order_id', 'id');
    }
    public function menuitem()
    {
        return $this->belongsTo('App\Menu', 'item_id', 'id');
    }
    public static function insertmenuitemoforders($saveorder, $orderitem, $rest_id, $inv_no = NULL)
    {
        if (isset($orderitem['item_unique_id']) && $orderitem['item_unique_id'] != '0') {
            $chck_item = OrderItem::where('item_unique_id', $orderitem['item_unique_id'])->where('order_id', $saveorder)->get();
            if (isset($chck_item) && !empty($chck_item)) {
                return;
            }
        }
        $orderitems = new OrderItem();

        if (isset($saveorder)) {
            $orderitems->order_id = $saveorder;
            $orderitem['order_id'] = $saveorder;
        }
        if (isset($orderitem['quantity'])) {
            $orderitems->item_quantity = $orderitem['quantity'];
        }
        if (isset($orderitem['item'])) {
            $orderitems->item_name = $orderitem['item'];
        }
        if (isset($orderitem['item_unique_id'])) {
            $orderitems->item_unique_id = $orderitem['item_unique_id'];
        }

        $actual_item_price = floatval($orderitem['price']) * floatval($orderitem['quantity']);

        //add option price in main price to calculate tax
        if (isset($orderitem['item_options']) && !empty($orderitem['item_options'])) {
            foreach ($orderitem['item_options'] as $item) {
                $actual_item_price += $item['option_item_price'] * floatval($orderitem['quantity']);
            }
        }
        if (isset($orderitem['item_id'])) {
            $itm_tax = array();
            $disc_arr = array();

            if ($inv_no == "" || $inv_no == null) {
                //get order object
                $order_obj = OrderDetails::where('order_id', $saveorder)->first();

                if ($order_obj->itemwise_tax || $order_obj->itemwise_discount) {

                    $menu = Menu::find($orderitem['item_id']);

                    if ($order_obj->itemwise_discount) {
                        //check if discount calculate before tax or not
                        if ($order_obj->add_discount_after_tax == 0) {

                            if ($menu->discount_value > 0) {
                                $disc_arr['disc_type'] = $menu->discount_type;
                                $disc_arr['disc_value'] = $menu->discount_value;
                                if ($menu->discount_type == 'fixed') {
                                    $disc_arr['disc_calc_amount'] = $menu->discount_value * intval($orderitem['quantity']);
                                    $actual_item_price = $actual_item_price - $disc_arr['disc_calc_amount'];
                                } else {
                                    $disc_value = $actual_item_price * $menu->discount_value / 100;
                                    $disc_arr['disc_calc_amount'] = $disc_value;
                                    $actual_item_price = $actual_item_price - $disc_value;
                                }
                            }
                        }
                    }
                    //check if slab is set or not
                    if ($order_obj->itemwise_tax) {
                        if (isset($menu->tax_slab) && $menu->tax_slab != '') {
                            $outlet_obj = Outlet::find($order_obj->outlet_id);
                            //outlet taxes
                            $taxes = json_decode($outlet_obj->taxes);
                            //total calculated item tax
                            $total_calc_tax = 0;
                            if (isset($taxes) && !empty($taxes)) {
                                foreach ($taxes as $t_key => $t_val) {
                                    if ($menu->tax_slab == $t_key) {
                                        $itm_tax[$t_key] = [];
                                        foreach ($t_val as $tx) {

                                            $cal_tax = $actual_item_price * floatval($tx->taxparc) / 100;

                                            $slab['taxname'] = $tx->taxname;
                                            $slab['taxparc'] = $tx->taxparc;
                                            $slab['value'] = $cal_tax;
                                            array_push($itm_tax[$t_key], $slab);
                                            $total_calc_tax += $cal_tax;
                                        }
                                    }
                                }
                            }
                            $actual_item_price += $total_calc_tax;
                        }
                    }
                    if ($order_obj->itemwise_discount) {
                        //check if discount calculate before tax or not
                        if ($order_obj->add_discount_after_tax == 1) {
                            if ($menu->discount_value > 0) {
                                $disc_arr['disc_type'] = $menu->discount_type;
                                $disc_arr['disc_value'] = $menu->discount_value;

                                if ($menu->discount_type == 'fixed') {
                                    $disc_arr['disc_calc_amount'] = $menu->discount_value * intval($orderitem['quantity']);
                                } else {
                                    $disc_value = $actual_item_price * $menu->discount_value / 100;
                                    $disc_arr['disc_calc_amount'] = $disc_value;
                                }
                            }
                        }
                    }
                }
                //if itemwise tax available
                if (!empty($itm_tax)) {
                    $orderitems->tax_slab = json_encode($itm_tax);
                }

                //if itemwise discount is enabled
                if (!empty($disc_arr)) {
                    $orderitems->item_discount = json_encode($disc_arr);
                }
            } else {
                if (isset($orderitem['tax_slab'])) {
                    $orderitems->tax_slab = $orderitem['tax_slab'];
                }
                if (isset($orderitem['discount'])) {
                    $orderitems->item_discount = $orderitem['discount'];
                }
            }
        }
        if (isset($orderitem['item_id'])) {

            $orderitems->item_id = $orderitem['item_id'];

            $menu_title_detail = Menu::where('menus.id', $orderitem['item_id'])
                ->join('menu_titles', 'menu_titles.id', '=', 'menus.menu_title_id')
                ->select('menus.menu_title_id', 'menu_titles.title')->first();

            if (isset($menu_title_detail) && !empty($menu_title_detail)) {

                $orderitems->category_id = $menu_title_detail->menu_title_id;
                $orderitems->category_name = $menu_title_detail->title;
            } else {

                if (isset($orderitem['titleName']) && $orderitem['titleName'] != '') {
                    $title_detail = MenuTitle::where('title', $orderitem['titleName'])
                        ->where('outlet_id', $rest_id)
                        ->first();

                    if (isset($title_detail) && !empty($title_detail)) {
                        $orderitems->category_id = $title_detail->id;
                        $orderitems->category_name = $title_detail->title;
                    }
                }
            }
        }
        if (isset($orderitem['price'])) {
            $orderitems->item_price = $orderitem['price'];
        }
        if (isset($orderitem['price']) && isset($orderitem['quantity'])) {
            $orderitems->item_total = $orderitem['price'] * $orderitem['quantity'];
        }
        if (isset($orderitem['options'])) {
            $orderitems->item_options = $orderitem['options'];
        }
        if (isset($orderitem['options_price'])) {
            $orderitems->item_options_price = $orderitem['options_price'];
        }
        $result = $orderitems->save();
        if ($result) {
            if (isset($orderitem['item_options']) && !empty($orderitem['item_options'])) {
                foreach ($orderitem['item_options'] as $item) {
                    $option = new OrderItemOption();
                    $option->order_id = $saveorder;
                    $option->order_item_id = $orderitems->id;
                    $option->option_item_id = $item['option_item_id'];
                    $option->qty = $item['option_item_qty'];
                    $option->option_item_price = $item['option_item_price'];
                    $option->save();
                }
            }
        }

        if (isset($orderitem['item_id']) && $orderitem['item_id'] != 0) {
            $outlet_setting = Outlet::find($rest_id);
            if ($outlet_setting->stock_auto_decrement == '1') {
                $default_location = Location::where('outlet_id', $rest_id)->where('default_location', 1)->first();
                if (isset($default_location) && !empty($default_location)) {
                    if (isset($result) && isset($inv_no) && $inv_no != '') {
                        $decrease_stock = StocksController::onSellDecreaseStock($orderitem, $default_location->id, $default_location->created_by);
                    }
                }
            }
        }
    }
    public static function getOrderType($type)
    {
        $ordertypes = array("take_away" => "Take Away", "dine_in" => "Dine In", "home_delivery" => "Home Delivery");
        if ($type != '') {
            return $ordertypes[$type];
        }
    }
}