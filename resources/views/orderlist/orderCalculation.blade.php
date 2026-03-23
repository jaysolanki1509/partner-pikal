<?php
use App\Outlet;
use App\OutletSetting;
use Illuminate\Support\Facades\Session;
// $s_total = number_format($order->totalcost_afterdiscount, 2, '.', '');
$total = $final_sub_total = $order->totalcost_afterdiscount;
$round_of_total = $total;
$round_of_val = 0.00;
$compare_tax = '';
$check = 0;
$delivery_charge = $order->delivery_charge;
$outlet_id = $order->outlet_id;
$discount_after_tax = $order->add_discount_after_tax;//OutletSetting::checkAppSetting($outlet_id,'discountAfterTax');
$setting = OutletSetting::checkAppSetting($outlet_id,'discountAfterTax');
$taxinclusive = OutletSetting::checkAppSetting($outlet_id,'itemPriceInclusiveOfTax');
    if ($taxinclusive == 1){
        $s_total = number_format(($order->totalcost_afterdiscount * 100) / 105 ,2);
    }
    else {
        $s_total =  number_format($order->totalcost_afterdiscount, 2, '.', '');
    }
//DeliveryCharges
    if($order_type == "home_delivery" && $new_delivery == "" && $req_type != "edit_inv" ){
        //$delivery_charge = number_format($order->delivery_charge, 2, '.', '');
        $outlet = Outlet::find($outlet_id);
        $delivery_charge_arr = json_decode($outlet->delivery_charge);
        if(isset($order->delivery_charge) && $order->delivery_charge == 0){
            
            if ( isset($delivery_charge_arr[0]) && isset($delivery_charge_arr[0]) ) {
                foreach ($delivery_charge_arr[0] as $total_price => $charge){
                    if($total_price == "fixed"){                              //if fixed delivery charges
                        $delivery_charge = number_format($charge, 2, '.', '');
                        break;
                    }else if($total_price > $order->totalcost_afterdiscount){ //if delivery charge between slots
                        $delivery_charge = number_format($charge, 2, '.', '');
                        break;
                    }else{                                                    // last slab always apply if heighest order done
                        $delivery_charge = number_format($charge, 2, '.', '');
                    }
                }
            }
        }
    }else{
        $delivery_charge = number_format((int)$new_delivery, 2, '.', '');
    }
//outlet taxes
$taxes = json_decode($order->taxes);
//outlet default taxes
if( isset($order->default_taxes) && $order->default_taxes != '' ) {
    $default_taxes = json_decode($order->default_taxes,true);
    if ( isset($selected_tax) && $selected_tax != '' ) {
        $compare_tax = $selected_tax;
    } else {
        if ( $order_type == 'dine_in' ) {
            $compare_tax = $default_taxes[0]['dine_in'];
        } else if ( $order_type == 'take_away' ) {
            $compare_tax = $default_taxes[0]['take_away'];
        } else {
            $compare_tax = $default_taxes[0]['home_delivery'];
        }
    }
}
// echo "laravel 5.1.11 <pre>"; print_r($taxes); echo "</pre>"; exit;

?>
@if ( isset($taxes) )
    <div class="col-md-12 mx-auto" style="text-align: center" >
        <select id="outlet_tax">
            <option value="">Select Tax Slab</option>
            @foreach ( $taxes as $t_key=>$t_val )
                <option @if( $t_key == $compare_tax ) selected @endif value="{{ $t_key }}">{{ $t_key }}</option>
            @endforeach
        </select>
    </div>
@endif
    @if ( $req_type == 'process' )
        <input type="hidden" value="{{$discount_after_tax}}" id="discount_after_tax" >
        <div id="sub_total_div" class="col-md-12" style="width: 100%;">
            <span class='col-md-6' style="float: left">Sub Total</span>
            <span class='col-md-6' style='float: right; text-align: right;'>
                &#8377; <span id='sub_total'>{{ $s_total }}</span>
            </span>
        </div>
        @if( $order->itemwise_discount == 1 && $item_disc > 0 )
            <div id='item_disc_div' class='col-md-12' style='width: 100%; float: left;'>
                <span class='col-md-6' style='float:left;'>Item Discount</span>
                <span class='col-md-6' style='text-align: right; float:right;'>
                    &#8377; <span id='item_discount'>{{ number_format($item_disc, 2, '.', '') }}</span>
                </span>
            </div>
            <?php
                //decrease itemwise discount from sub total
                $s_total = $s_total - $item_disc;
                $total = $final_sub_total = $total - $item_disc;
            ?>
        @endif
        @if ( $disc_mode == "" || $disc_mode == 'apply')
            @if( isset($disc_value) && $disc_value > 0 && $discount_after_tax == 0 )
                <?php
                    $extra_disc = 0;
                    if ( $disc_type == 'fixed' ) {
                        $s_total -= $disc_value;
                        $extra_disc = $disc_value;
                    } else {
                        $disc_amount = $s_total * $disc_value / 100;
                        $extra_disc = $disc_amount;
                        $s_total -= $disc_amount;
                    }
                    $total -= $extra_disc;
                ?>
                <div id='disc_div' class='col-md-12' style='width: 100%; float: left;'>
                    <span class='col-md-6' style='float:left;'>Discount</span>
                    <span class='col-md-6' style='text-align: right; float:right;'>
                        &#8377; <span id='discount'>{{ number_format($extra_disc, 2, '.', '') }}</span>
                    </span>
                </div>
            @endif
        @endif
        @if( isset($order->default_taxes) && $order->default_taxes != '' )
            @if ( $compare_tax != '' )
                @if ( isset($taxes) )
                    <div id='tax_calc' class="col-md-12" style="width: 100%;">
                        @foreach ( $taxes as $t_key=>$t_val )
                            @if ( $t_key == $compare_tax )
                                <?php $i = 0;?>
                                @foreach ( $t_val as $tx )
                                {{ logger("here tex") }}
                                {{ logger(json_encode($tx)) }}
                                @if ($taxinclusive == 1)
                                <?php
                                    $cal_tax = $s_total * floatval($tx->taxparc) / 100;
                                    $show_cal_tax = number_format($s_total * floatval($tx->taxparc) / 100, 2, '.', '');
                                    $total = $s_total + $cal_tax;
                                ?>
                                @else
                                <?php
                                    $cal_tax = $s_total * floatval($tx->taxparc) / 100;
                                    $show_cal_tax = number_format($s_total * floatval($tx->taxparc) / 100, 2, '.', '');
                                    $total += $cal_tax;
                                ?>
                                @endif
                                    <span class='col-md-6 tax_name' id='tax_name_{{ $i }}' style="float: left; width: 70%;">
                                    {{ ucwords($tx->taxname) }}&nbsp;<span id="tax_perc_{{ $i }}">{{ $tx->taxparc }}</span>%
                                </span>
                                    <span class='col-md-6' style='float: right; text-align: right; width: 30%;'>
                                    &#8377; <span id='tax_val_{{ $i }}'>{{ $show_cal_tax }}</span>
                                </span>
                                    <?php $i++; ?>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                @endif
            @endif
        @endif
        @if ( $disc_mode == "" || $disc_mode == 'apply')
            @if( isset($disc_value) && $disc_value > 0 && $discount_after_tax == 1 )
                <?php
                    $extra_disc = 0;
                    if ( $disc_type == 'fixed' ) {
                        $total -= $disc_value;
                        $extra_disc = $disc_value;
                    } else {
                        $disc_amount = $total * $disc_value / 100;
                        $extra_disc = $disc_amount;
                        $total -= $disc_amount;
                    }
                ?>
                @if($extra_disc > 0)
                    <div id='disc_div' class='col-md-12' style='width: 100%; float: left;'>
                        <span class='col-md-6' style='float:left;'>Discount</span>
                        <span class='col-md-6' style='text-align: right; float:right;'>
                            &#8377; <span id='discount'>{!! number_format($extra_disc, 2, '.', '') !!}</span>
                        </span>
                    </div>
                @endif
            @endif
        @endif
        @if($order_type == "home_delivery" && $delivery_charge != 0.00)
            <?php $total += $delivery_charge; ?>
            <div id="sub_delivery_div" class="col-md-12" style="width: 100%;">
                <span class='col-md-6' style="float: left;">Delivery Charge</span>
                <span class='col-md-6' style='float:right; text-align: right'>
                     <span id='delivery_charge'>{!! $delivery_charge !!}</span>
                </span>
            </div>
        @endif
        @if($setting == 0)
        <?php
            $round_of_total = $total;
        ?>
        @else
        <?php
            $round_of_total = round($total);
            $round_of_val = number_format(abs($total - $round_of_total), 2, '.', '');
        ?>
        @endif
        @if( $round_of_val > 0 )
            <div class="col-md-12" style="width: 100%;">
                <span class='col-md-6' style="float: left;">Round Off</span>
                <span class='col-md-6' style='float: right; text-align: right;'>
                    <span id='round_off' >{{ abs($round_of_val) }}</span>
                </span>
            </div>
        @endif
        <hr class='col-md-12' style='margin-top: 5px;margin-bottom: 5px;width: 95%'>
        <div class="col-md-12" style="float: left;width: 100%;">
            <span class='col-md-6' style='font-weight: bold; float: left'>Total</span>
            <span class='col-md-6' style='float: right;font-weight: bold; text-align: right;'>
                &#8377; <span id='total'>{{ number_format($round_of_total, 2, '.', '') }}</span>
            </span>
        </div>
        <input type="hidden" id="final_sub_total" value="{{ $final_sub_total }}">
        <div style="clear:both"></div>
        <hr>
        <div class="form-group payment_div" style="width: 100%">
            <div class="col-md-4">
                <select class="form-control paid-type" id="paid_type" onchange="checkPaymentOption(this)">';
                    @if ( isset($pay_option) )
                    @foreach( $pay_option as $opt )
                    @if ( $opt['source_name'] )
                    <?php $s_name = $opt['mode_name'].' - '.$opt['source_name'];?>
                    @else
                    <?php $s_name = $opt['mode_name'];?>
                    @endif
                            @if( isset($check_upi_status) && strtolower($opt['source_name']) == 'Online - UPI')
                                <option data-source="{{ $opt['source_id'] }}" value="{{ $opt['mode_id'] }}" selected>{{ $s_name }}</option>
                            @else
                                <option data-source="{{ $opt['source_id'] }}" value="{{ $opt['mode_id'] }}" >{{ $s_name }}</option>
                            @endif
                        @endforeach
                    @else
                        <option value="1" >Cash</option>
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <input class="form-control paid-value" type="text" value="{{ number_format($round_of_total, 2, '.', '') }}" readonly>
            </div>
            <div class="col-md-4 hide" style="margin-bottom: 10px">
                <input class="form-control transaction-id" type="text" value="" placeholder="Transaction Id" >
            </div>
            <div class="col-md-1" style="padding-left: 0px">
                <a href="javascript:void(0)" class="btn btn-circle btn-danger hide" onclick="removePaymentMode(this)">
                    <i class="fa fa-remove"></i>
                </a>
            </div>
            <div style="clear:both"></div>
        </div>
        <div style="clear:both"></div>
    @else
        <div id="sub_total_div" class="col-md-12" style="width: 100%;">
            <span class='col-md-6' style="float: left">Sub Total</span>
            <span class='col-md-6' style='float: right; text-align: right;'>
                &#8377; <span id='edit_sub_total'>{{ $s_total }}</span>
            </span>
        </div>
        <input type="hidden" value="{{$order->add_discount_after_tax}}" id="discount_after_tax" >
        @if( $order->itemwise_discount == 1 && $item_disc > 0 )
            <?php
                $s_total -= $item_disc;
                $final_sub_total = $s_total;
            ?>
            <div id="item_edit_disc_div" class="col-md-12" style="width: 100%;">
                <span class='col-md-6' style="float: left">Item Discount</span>
                <span class='col-md-6' style='float: right; text-align: right;'>
                    &#8377; <span id='item_edit_discount'>{!! number_format($item_disc,2,'.','') !!}</span>
                </span>
            </div>
        @endif
        @if (($disc_mode == "" || $disc_mode == 'apply') && $order->add_discount_after_tax == 0)
            @if( isset($disc_value) && $disc_value > 0 )
                <?php
                $extra_disc = 0;
                if ( $disc_type == 'fixed' ) {
                    $extra_disc = $disc_value;
                    if($disc_mode == 'apply')
                        $s_total -= $disc_value;
                } else {
                    $disc_amount = $s_total * $disc_value / 100;
                    $extra_disc = $disc_amount;
                    if($disc_mode == 'apply')
                        $s_total -= $disc_amount;
                }
                ?>
                <?php
                    if($req_type != 'edit') {
                        $s_total -= $order->discount_value;
                    }
                ?>
                <div id="edit_disc_div" class="col-md-12" style="width: 100%;">
                    <span class='col-md-6' style="float: left">Discount</span>
                    <span class='col-md-6' style='float: right; text-align: right;'>
                        &#8377; <span id='edit_discount'>{!!  number_format($extra_disc,2,'.','') !!}</span>
                    </span>
                </div>
            @elseif($order->add_discount_after_tax == 0 && isset($order->discount_value) && trim($order->discount_value) != "")
                <div id="edit_disc_div" class="col-md-12" style="width: 100%;">
                    <span class='col-md-6' style="float: left">Discount</span>
                    <span class='col-md-6' style='float: right; text-align: right;'>
                        &#8377; <span id='edit_discount'>{!!  number_format($order->discount_value,2,'.','') !!}</span>
                    </span>
                </div>
            @endif
        @endif
        <?php
            $total = $round_of_total = $s_total;
        ?>
        @if( isset($order->default_taxes) && $order->default_taxes != '' )
            @if ( $compare_tax != '' )
                @if ( isset($taxes) && !empty($taxes) )
                    <div id='edit_tax_calc' class="col-md-12" style="width: 100%;">
                        @foreach ( $taxes as $t_key=>$t_val )
                            @if ( $t_key == $compare_tax )
                                <?php $i = 0;?>
                                @foreach ( $t_val as $tx )
                                    <?php
                                    $cal_tax = $s_total * floatval($tx->taxparc) / 100;
                                    $show_cal_tax = number_format($s_total * floatval($tx->taxparc) / 100, 2, '.', '');
                                    $total += $cal_tax;
                                    ?>
                                    <span class='col-md-6 tax_name' id='tax_name_{{ $i }}' style="float: left; width: 70%;">
                                            {{ ucwords($tx->taxname) }}&nbsp;<span id="tax_perc_{{ $i }}">{{ $tx->taxparc }}</span>%
                                        </span>
                                    <span class='col-md-6' style='float: right; text-align: right; width: 30%;'>
                                            &#8377; <span id='tax_val_{{ $i }}'>{{ $show_cal_tax }}</span>
                                        </span>
                                    <?php $i++; ?>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                @endif
            @endif
        @endif
        @if (($disc_mode == "" || $disc_mode == 'apply') && $order->add_discount_after_tax == 1)
            @if( isset($disc_value) && $disc_value > 0 )
                <?php
                $extra_disc = 0;
                if ( $disc_type == 'fixed' ) {
                    $total -= $disc_value;
                    $extra_disc = $disc_value;
                } else {
                    $disc_amount = $total * $disc_value / 100;
                    $extra_disc = $disc_amount;
                    $total -= $disc_amount;
                }
                ?>
                <div id="edit_disc_div" class="col-md-12" style="width: 100%;">
                    <span class='col-md-6' style="float: left">Discount</span>
                    <span class='col-md-6' style='float: right; text-align: right;'>
                            &#8377; <span id='edit_discount'>{!!  number_format($extra_disc,2,'.','') !!}</span>
                        </span>
                </div>
            @elseif($order->add_discount_after_tax == 1 && isset($order->discount_value)
                            && trim($order->discount_value) != "" && $order->discount_value > 0)
                <div id='disc_div' class='col-md-12' style='width: 100%; float: left;'>
                    <span class='col-md-6' style='float:left;'>Discount</span>
                    <span class='col-md-6' style='text-align: right; float:right;'>
                        &#8377; <span id='discount'>{!! number_format($order->discount_value, 2, '.', '') !!} </span>
                    </span>
                </div>
            @endif
        @endif
        @if($order_type == "home_delivery" && $delivery_charge != 0.00 && $check == 0)
            <?php $total += $delivery_charge; ?>
            <div id="sub_delivery_div" class="col-md-12" style="width: 100%;float: left;">
                <span class='col-md-6'>Delivery Charge</span>
                <span class='col-md-6' style='text-align: right'>
                    &#8377; <span id='delivery_charge'>{!! $delivery_charge !!}</span>
                </span>
            </div>
        @endif
        <?php
            $round_of_total = round($total);
            $round_of_val = number_format(abs($total - $round_of_total), 2, '.', '');
        ?>
        <div id="edit_round_off_div" class="col-md-12" style="float: left;width: 100%;">
            <span class='col-md-6' >Round Off</span>
            <span class='col-md-6' style='text-align: right'>
                &#8377; <span id='edit_round_off'>{{ abs($round_of_val) }}</span>
            </span>
        </div>
        <hr class='col-md-12' style='margin-top: 5px;margin-bottom: 5px;width: 95%'>
        <div class="col-md-12" style="float: left;width: 100%;">
            <span class='col-md-6' style='font-weight: bold'>Total</span>
            <span class='col-md-6' style='text-align: right;font-weight: bold'>
                &#8377; <span id='edit_total'>{!! number_format($round_of_total,2,'.','') !!}</span>
            </span>
        </div>
        <input type="hidden" id="final_sub_total" value="{{ $final_sub_total }}">
        <div style="clear: both;"></div>
    @endif
