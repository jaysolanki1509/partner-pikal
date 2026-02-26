
@if( isset($order) && sizeof($order) > 0 )

    <input type="hidden" id="edit_order_id" value="{!! $order->order_id !!}" />
    <div class="col-md-4">
        <input type="radio" name="edit_order_type" value="dine_in" @if( $order->order_type == 'dine_in'){!! 'checked' !!}@endif/>
        <lable>Dine In</lable>
    </div>
    <div class="col-md-4">
        <input type="radio" name="edit_order_type" value="take_away" @if( $order->order_type == 'take_away'){!! 'checked' !!}@endif/>
        <lable>Take Away</lable>
    </div>
    <div class="col-md-4">
        <input type="radio" name="edit_order_type" value="home_delivery" @if( $order->order_type == 'home_delivery'){!! 'checked' !!}@endif/>
        <lable>Home Delivery</lable>
    </div>
    <hr class="col-md-12" style="width:95%">
    <div class="form-group col-md-12 padding-zero" style="margin-bottom: 0px;" >
        <div class="form-group" style="margin-bottom: 0px">
            <label for="invoice_no" style="margin-bottom: 0px">Invoice No.</label>
        </div>
        <div class="form-group">
            {!! Form::text('edit_invoice_no', $order->invoice_no, array('class' => 'form-control','id' => 'edit_invoice_no', 'placeholder'=> 'Invoice Number')) !!}
        </div>
    </div>
    <div class="form-group col-md-6 padding-zero" >
        <div class="form-group" style="margin-bottom: 0px">
            <label for="invoice_no" style="margin-bottom: 0px">Name</label>
        </div>
        <div class="form-group">
            {!! Form::text('edit_name', $order->customer_name, array('class' => 'form-control','id' => 'edit_name', 'placeholder'=> 'Name')) !!}
        </div>
    </div>
    <div class="form-group col-md-6 padding-zero" >
        <div class="form-group" style="margin-bottom: 0px">
            <label for="invoice_no" style="margin-bottom: 0px">Mobile No.</label>
        </div>
        <div class="form-group">
            {!! Form::text('edit_mobile', $order->mobile_number, array('class' => 'form-control','id' => 'edit_mobile', 'placeholder'=> 'Mobile','onkeypress'=>'return isNumber(event)','maxlength'=>'10')) !!}
        </div>
    </div>

    <div class="form-group @if( $order->order_type != 'home_delivery') hide @endif" id="edit_address_div">
        <div class="col-md-12 form-group">
            <textarea name="edit_address" id="edit_address" rows="3" class="form-control" placeholder="Address">{!! $order->address !!}</textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-4 form-group">
            <select id="edit_disc_type" class="form-control select2" value="{{ $order->discount_type }}" placeholder="Discount Type">
                <option value="" >Discount Type</option>
                <option value="percentage" >Percentage</option>
                <option value="fixed">Fixed</option>
            </select>
        </div>
        <div class="col-md-4 form-group">
            <input type="text" class="form-control" id="edit_disc_value" value="" placeholder="Discount Value" onkeypress='return onlyNumbersWithDot(event)' />
        </div>
        <div class="col-md-4 form-group">
            <button type="button" class="btn btn-primary" onclick="calculateDiscount(this,'edit_inv','apply')">Apply</button>
            <button type="button" class="btn btn-danger" onclick="calculateDiscount(this,'edit_inv','remove')">Remove</button>
        </div>
    </div>

    @if($delivery_settings)
        <div class="form-group hide" id="edit_delivery_value">
            <div class="col-md-8 form-group">
                <input type="text" class="form-control" value="{{ $order->delivery_charge }}" id="edit_new_delivery_value" placeholder="Delivery Value" onkeypress='return onlyNumbersWithDot(event)' />
            </div>
            <div class="col-md-4 form-group">
                <button type="button" class="btn btn-primary" onclick="edit_delivery_apply('apply','edit_inv')">Apply</button>
                <button type="button" class="btn btn-danger" onclick="edit_delivery_apply('remove','edit_inv')">Remove</button>
            </div>
        </div>
    @endif

    @if($itemWiseDiscount == 0 && $itemWiseTax == 0)
        <div class="form-group">
            <div class="col-md-7 form-group">
                <input type="text" class="form-control" id="settle_value" placeholder="Settlement value" onkeypress='return onlyNumbersWithDot(event)' />
            </div>
            <div class="col-md-5 form-group">
                <button type="button" class="btn btn-primary" onclick="settleBill('settle')">Settle Invoice</button>
                <button type="button" class="btn btn-danger" onclick="getInvoiceNo('{!! $order->order_type !!}',{!! $order->order_id !!},'reset','')">Reset</button>
            </div>
        </div>
    @endif

    <form id="custom_form">
        @if(isset($custom_fields) && sizeof($custom_fields)>0)
            @foreach($custom_fields as $field_key => $fields)
                <div class="form-group">
                    <div class="col-md-6 form-group">
                        {!! Form::text($field_key, isset($fields["value"])?$fields["value"]:NULL, array('class' => isset($fields["type"])?$fields["type"]." form-control":'form-control','id' => 'name', 'placeholder'=> $fields["label"])) !!}
                    </div>
                </div>
            @endforeach
        @endif
    </form>

    <div style="clear:both"></div>
    <div class="col-md-12">
        <table style="display: block;">
            <tr>
                <td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;
                                font-weight:bold; width:400px;">
                    Items
                </td>

                <td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;
                            font-weight:bold; width:100px;">Qty</td>

                <td style="padding:5px;
                                vertical-align:top;   background:#eee;
                                border-bottom:1px solid #ddd;
                                font-weight:bold; width:100px;"> Price
                </td>

                <td style="  padding:5px;
                            vertical-align:top;   background:#eee;
                            border-bottom:1px solid #ddd;
                            font-weight:bold;">Amount
                </td>
            </tr>
            @if( isset($items['item']) && sizeof($items['item']) > 0 )
                @for( $i=0; $i<sizeof($items['item']); $i++)
                    <tr>
                        <td style="  padding:5px 5px 0px 5px;vertical-align:top;font-size:12px;">{!! $items['item'][$i]['name'] !!}</td>
                        <td style="  padding:5px 5px 0px 5px;vertical-align:top;font-size:12px;">{!! $items['item'][$i]['qty'] !!}</td>
                        <td style=" padding:5px 5px 0px 5px;vertical-align:top;font-size:12px;">{!! $items['item'][$i]['price'] !!}</td>
                        <td style="  padding:5px 5px 0px 5px;vertical-align:top;text-align:right;font-size:12px;">{!! number_format($items['item'][$i]['amount'],2) !!}</td>
                        <td style="margin-left: 5px;padding-top: 5px; cursor: pointer;">
                            <a onclick="removeItemFromOrder({{$order->order_id}},{{$items['item'][$i]['item_id']}})">X</a>
                        </td>
                    </tr>
                @endfor
            @endif
        </table>
    </div>
    <div style="clear:both"></div>
    <div class="form-group" id="edit_calculation" style="margin-top:40px;">

        @if ( isset($order) && sizeof($order) > 0 && $order->itemwise_tax == 0 )
            <?php
                echo $calculation = \App\Http\Controllers\newordercontroller::orderCalculation($order,$order->order_type,'edit_inv',$order->tax_percent,$order->delivery_charge,$order->discount_type,$order->discount_value);
            ?>

        @else
            <?php
                echo $calculation = \App\Http\Controllers\newordercontroller::itemWiseOrderCalculation($order,'edit',$order->order_type,$order->delivery_charge,$order->discount_type,$order->discount_value);
            ?>
        @endif
    </div>
    <div style="clear:both"></div>
    <hr>


    @include('orderlist.orderPaymentModes')

    <div style="clear:both"></div>
@endif