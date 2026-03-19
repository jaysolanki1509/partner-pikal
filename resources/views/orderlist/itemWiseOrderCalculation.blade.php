<?php

    use App\Outlet;
    //DeliveryCharges
    $outlet_id = $order->outlet_id;
    $check = 0;
    $delivery_charge = 0.00;

    if($order_type == "home_delivery" && $new_delivery == ""){

        //$delivery_charge = number_format($order->delivery_charge, 2, '.', '');
        $outlet = Outlet::find($outlet_id);
        $delivery_charge_arr = json_decode($outlet->delivery_charge);

        if(isset($order->delivery_charge) && $order->delivery_charge == 0){
            if ( isset($delivery_charge_arr[0]) && sizeof($delivery_charge_arr[0]) > 0 ) {
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

?>

<hr>
@if ( $req_type == 'process' )

    <input type="hidden" value="{{ $order->add_discount_after_tax }}" id="discount_after_tax" >

    <div id="sub_total_div" class="col-md-12" style="width: 100%;">
        <span class='col-md-6' style="float: left">Sub Total</span>
        <span class='col-md-6' style='float: right; text-align: right;'>
                &#8377; <span id='sub_total'>{{ $order->totalcost_afterdiscount }}</span>
        </span>
    </div>

    @if ( $order->add_discount_after_tax == 0 && $total_disc > 0 )
        <div id='item_disc_div' class='col-md-12' style='width: 100%; float: left;'>
            <span class='col-md-6' style='float:left;'>Item Discount</span>
            <span class='col-md-6' style='text-align: right; float:right;'>
                &#8377; <span id='item_discount'>{{ number_format($total_disc, 2, '.', '') }}</span>
            </span>
        </div>
    @endif

    @if( $total_tax != 0 )
        <div id='tax_calc' class="col-md-12" style="width: 100%;">
            <span class='col-md-6 tax_name' id='tax_name_0' style="float: left; width: 70%;">
                   Total Tax&nbsp;<span id="tax_perc_0" class="hide">0</span>
            </span>
            <span class='col-md-6' style='float: right; text-align: right; width: 30%;'>
                &#8377; <span id='tax_val_0'>{{ number_format($total_tax, 2, '.', '') }}</span>
            </span>
        </div>
    @endif

    @if ( $order->add_discount_after_tax == 1 && $total_disc > 0 )
        <div id='item_disc_div' class='col-md-12' style='width: 100%; float: left;'>
            <span class='col-md-6' style='float:left;'>Item Discount</span>
            <span class='col-md-6' style='text-align: right; float:right;'>
                &#8377; <span id='item_discount'>{{ $total_disc }}</span>
            </span>
        </div>
    @endif

    @if ( $disc_mode == "" || $disc_mode == 'apply')

            @if ( $extra_disc > 0 )
                <div id='disc_div' class='col-md-12' style='width: 100%; float: left;'>
                    <span class='col-md-6' style='float:left;'>Discount</span>
                    <span class='col-md-6' style='text-align: right; float:right;'>
                        &#8377; <span id='discount'>{{ number_format($extra_disc, 2, '.', '') }}</span>
                    </span>
                </div>
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

    <?php
        $round_of_total = round($total);
        $round_of_val = number_format(abs($total - $round_of_total), 2, '.', '');
    ?>

    <div class="col-md-12" style="width: 100%;">
        <span class='col-md-6' style="float: left;">Round Off</span>
        <span class='col-md-6' style='float: right; text-align: right;'>
                        &#8377; <span id='round_off' >{{ abs($round_of_val) }}</span>
        </span>
    </div>

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

                @if ( isset($pay_option) && sizeof($pay_option) > 0 )

                    @foreach( $pay_option as $opt )

                        @if ( $opt['source_name'] )
                            <?php $s_name = $opt['mode_name'].' - '.$opt['source_name'];?>
                        @else
                            <?php $s_name = $opt['mode_name'];?>
                        @endif

                        @if( isset($check_upi_status) && sizeof($check_upi_status) > 0 && strtolower($opt['source_name']) == 'Online - UPI')
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

@else

    <input type="hidden" value="{{ $order->add_discount_after_tax }}" id="discount_after_tax" >

    <?php
        $total = $order->totalcost_afterdiscount;
    ?>

    <div id="sub_total_div" class="col-md-12" style="width: 100%;">
        <span class='col-md-6' style="float: left">Sub Total</span>
        <span class='col-md-6' style='float: right; text-align: right;'>
                &#8377; <span id='edit_sub_total'>{{ $order->totalcost_afterdiscount }}</span>
            </span>
    </div>

    @if ( $order->add_discount_after_tax == 0 && $total_disc > 0 )
        <?php
                $total -= $total_disc;
        ?>
        <div id='edit_item_disc_div' class='col-md-12' style='width: 100%; float: left;'>
            <span class='col-md-6' style='float:left;'>Item Discount</span>
            <span class='col-md-6' style='text-align: right; float:right;'>
                &#8377; <span id='edit_item_discount'>{{ $total_disc }}</span>
            </span>
        </div>
    @endif

    @if( $total_tax != 0 )
        <?php
            $total += $total_tax;
        ?>
        <div id='edit_tax_calc' class="col-md-12" style="width: 100%;">
            <span class='col-md-6 tax_name' id='tax_name_0' style="float: left; width: 70%;">
                   Total Tax&nbsp;<span id="tax_perc_0" class="hide">0</span>
            </span>
            <span class='col-md-6' style='float: right; text-align: right; width: 30%;'>
                &#8377; <span id='tax_val_0'>{{ number_format($total_tax, 2, '.', '') }}</span>
            </span>
        </div>
    @endif

    @if ( $order->add_discount_after_tax == 1 && $total_disc > 0 )
        <?php
            $total -= $total_disc;
        ?>
        <div id='edit_item_disc_div' class='col-md-12' style='width: 100%; float: left;'>
            <span class='col-md-6' style='float:left;'>Item Discount</span>
            <span class='col-md-6' style='text-align: right; float:right;'>
                &#8377; <span id='edit_item_discount'>{{ $total_disc }}</span>
            </span>
        </div>
    @endif

    @if ( $disc_mode == "" || $disc_mode == 'apply')

        @if ( $extra_disc > 0 )

            <?php
                //when apply discount in edit order
                $total -= $extra_disc;
            ?>
            <div id="edit_disc_div" class="col-md-12" style="width: 100%;">
                <span class='col-md-6' style="float: left">Discount</span>
                <span class='col-md-6' style='float: right; text-align: right;'>
                    &#8377; <span id='edit_discount'>{!!  number_format($extra_disc,2,'.','') !!}</span>
                </span>
            </div>

        @else

            @if ( isset($order->discount_value) && $order->discount_value > 0 )
                <?php
                    //when click on edit button and discount is available
                    $total -= $order->discount_value;
                ?>
                <div id="edit_disc_div" class="col-md-12" style="width: 100%;">
                    <span class='col-md-6' style="float: left">Discount</span>
                    <span class='col-md-6' style='float: right; text-align: right;'>
                        &#8377; <span id='edit_discount'>{!!  number_format($order->discount_value,2,'.','') !!}</span>
                    </span>
                </div>
            @endif
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

@endif

<div style="clear:both"></div>