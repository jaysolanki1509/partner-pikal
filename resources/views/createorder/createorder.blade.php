<?php use App\CuisineType;
use App\Outlet;
use Illuminate\Support\Facades\Auth;?>
@extends('partials.default')
@section('pageHeader-left')
    {{--{{ trans('Menu_Create.Add Menu') }}--}}
    Create Orders
@stop

<style>
    .no-gutter > [class*='col-'] {
        padding-right:0;
        padding-left:0;
    }
    .dotted {border: 1px dotted #ff0000; border-style:dashed; color: #fff; background-color: #fff; }
</style>

@section('content')
    @if(Session::has('failure'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('failure') }}
        </div>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div class="row well" style="border: solid 1px;margin: 5px;padding: 5px;">
    <div class="mb30"></div>

    {!! Form::open(['route' => 'createorder.store', 'method' => 'post', 'class' => 'autoValidate', 'id' => 'createOrderAddForm', 'files'=> true]) !!}

    {!! Form::hidden('_token', '{{ csrf_token() }}') !!}
    {{--{!! Form::hidden('outlet_id',null,array('id' => 'outlet_id')) !!}--}}
    <div class="row order-details">
    <div class="col-md-12">
        <div class="col-md-1 form">
            <label class="control-label">Service Type</label>
        </div>

        <div class="col-md-4 form">
            <select class="form-control" name="service" id="service" onchange="onServiceTypes();">
                <option value="take_away">Take Away</option>
                <option value="dine_in" selected>Dine In</option>
                <option value="home_delivery" >Home Delivery</option>
                <option value="meal_packs" >Meal Packs</option>
            </select>
            {{--{!! Form::hidden('service_type',null,array('id' => 'service_type')) !!}--}}
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-1 form">
            <label class="control-label">Outlet</label>
        </div>
        <div class="col-md-4 form">
                <select class="form-control" name="outlets"  id="outlets">
                </select>
        </div>
    </div>
    <div class="col-md-12" @if ($errors->has('addItems'))has-error @endif>
        <div class="col-md-1 form" id="">
            {!! Form::label('items','Items',array('class' => 'control-label')) !!}
        </div>
        <div id="" class="col-md-9 form">
            <input type="button" id="addItems" name="addItems"  value="Add Items" class="btn btn-primary mr5">
            @if ($errors->has('addItems')) <p class="help-block">{{ $errors->first('addItems') }}</p> @endif
        </div>
    </div>
    <div id="items" class="col-md-12">
    </div>
        {!! Form::hidden('old_menu_item_id', null, array('id' => 'old_menu_item_id'))  !!}
</div>
    <hr style="border-top: 1px solid #BDB8B8;">
    <div class="row customer">
        <div class="col-md-12" style="margin-bottom: 10px;">
            <div class="col-md-2"><h3><b>Customer Details</b></h3></div>
        </div>
        <div class="col-md-12" @if ($errors->has('name'))has-error @endif>
            <div class="col-md-1 form" >
                {!! Form::label('name','Name',array('class' => 'control-label')) !!}
            </div>
            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
            <div class="col-md-9 form" >
                {!! Form::text('name', old('name'),array('class' => 'form-control', 'id' => 'name', 'placeholder'=>'Name')) !!}
            </div>


        </div>

        <div class="col-md-12" @if ($errors->has('mobile'))has-error @endif>
            <div class="col-md-1 form">
                {!! Form::label('mobile','Mobile',array('class' => 'control-label')) !!}
            </div>
            <div class="col-md-9 form">
                {!! Form::text('mobile', old('mobile'),array('class' => 'form-control', 'id' => 'mobile', 'placeholder'=>'Mobile Number')) !!}
            </div>
            @if ($errors->has('mobile')) <p class="help-block">{{ $errors->first('mobile') }}</p> @endif
        </div>
        <div class="col-md-12" id="table-div" style="display: none">
            <div class="col-md-1 form">
                {!! Form::label('table','Table',array('class' => 'control-label')) !!}
            </div>
            <div class="col-md-9 form">
                <input type="number" id="table" class = 'form-control' name="table" min="1" max="100" placeholder="Select Table" >
            </div>
        </div>
        <div class="col-md-12" id="address-div" style="display: none">
            <div class="col-md-1 form">
                {!! Form::label('address','Address*',array('class' => 'control-label')) !!}
            </div>
            <div class="col-md-9 form">
                {!! Form::textarea('address',null,array('class' => 'form-control','id'=>'address','size' => '30x2','placeholder'=> 'Address')) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-3 form">
                <a href="javascript:void(0);" class="coupon_toggle" id="coupon_link">Have a Coupon Code ?</a>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-1 form"></div>
            <div class="col-md-9 " id="coupon_box_div" @if ($errors->has('coupon_box'))has-error @endif>
                <div class="col-md-6 form">
                    {!! Form::text('coupon_box', old('coupon_box'),array('class' => 'form-control', 'id' => 'coupon_box', 'placeholder'=>'Have a Coupon Code ?')) !!}
                    {!! Form::hidden('coupon_flag', 'No', array('id' => 'coupon_flag'))  !!}
                    {{--{!! Form::hidden('coupon_array', null, array('id' => 'coupon_array'))  !!}--}}
                </div>
                @if ($errors->has('coupon_box')) <p class="help-block">{{ $errors->first('coupon_box') }}</p> @endif
                <div class="col-md-3 form">
                    {!! Form::button('Apply', array('class' => 'btn btn-primary mr5', 'onclick' => 'onCouponApply();', 'id' => 'coupon_btn')) !!}
                </div>
            </div>
        </div>
    </div>
        <hr style="border-top: 1px solid #BDB8B8;">
        <div class="row total">
            <div class="col-md-12" style="margin-bottom: 10px;">
                <div class="col-md-3"><h3><b>Total Amount Details</b></h3></div>
            </div>
            <div class="col-md-12">
                <div class="col-md-1 form">
                    {!! Form::label('subtotal','SubTotal',array('class' => 'control-label')) !!}
                </div>
                <div class="col-md-3 form"></div>
                <div class="col-md-2 form">
                    <span class="subtotal" id="subtotal"> 0.00</span>
                    {!! Form::hidden('total_price', 0, array('id' => 'total_price'))  !!}
                </div>
            </div>
            <div class="col-md-12" id="service-tax-div">

                <div class="col-md-2 form">
                    {!! Form::label('service_tax','Service Tax(%0)',array('class' => 'control-label','id' => 'service_tax_label')) !!}
                </div>
                <div class="col-md-2 form"></div>
                <div class="col-md-1 form">
                    <span class="service_tax">+ 0.00</span>
                    {!! Form::hidden('service_tax_hidden', 0, array('id' => 'service_tax_hidden'))  !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-1 form">
                    {!! Form::label('discount','Discount',array('class' => 'control-label')) !!}
                </div>
                <div class="col-md-3 form"></div>
                <div class="col-md-2 form">
                    <span class="discount">- 0.00</span>
                    {!! Form::hidden('discount_input', 0, array('id' => 'discount_input'))  !!}
                    {!! Form::hidden('min_value', 0, array('id' => 'min_value'))  !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-1 form">
                    {!! Form::label('round_off','Round Off',array('class' => 'control-label')) !!}
                </div>
                <div class="col-md-3 form"></div>
                <div class="col-md-2 form">
                    <span class="round_off">+0.00</span>
                </div>
            </div>
            <div class="col-md-12">
                <hr class = "dotted">
                <div class="col-md-2 form">
                    <h4>{!! Form::label('total_amount','Total Amount',array('class' => 'control-label')) !!}</h4>
                </div>
                <div class="col-md-2 form"></div>
                <div class="col-md-2 form">
                    <span class="total_amount" style="font-size: 18px;font-weight: 600;">0.00</span>
                    {!! Form::hidden('totalcost_afterdiscount', 0, array('id' => 'totalcost_afterdiscount'))  !!}
                </div>
            </div>
            {{--<div class="col-md-12">
                <table style="width:45%;">
                    <tr class="col-md-12"><td class="col-md-1">{!! Form::label('subtotal','SubTotal',array('class' => 'control-label')) !!}</td>
                    <td class="col-md-1" style="text-align:center;"><span class="subtotal"> 0.00</span></td></tr>
                    <tr class="col-md-12"><td class="col-md-1">{!! Form::label('discount','Discount',array('class' => 'control-label')) !!}</td>
                    <td class="col-md-1" style="text-align:center;"> <span class="discount">- 0.00</span></td></tr>
                    <tr class="col-md-12"><td class="col-md-1">{!! Form::label('round_off','Round Off',array('class' => 'control-label')) !!}</td>
                    <td class="col-md-1" style="text-align:center;"><span class="round_off">+ 0.00</span></td></tr>
                </table>
            </div>

            <div class="col-md-12">
                <table style="width:45%;">
                    --}}{{--<hr class = "dotted" style="margin-left: 30px;margin-right: 1000px;">--}}{{--
                    <hr class = "dotted">
                    <tr class="col-md-12"><td class="col-md-1"><h4>{!! Form::label('total_amount','Total',array('class' => 'control-label')) !!}</h4></td>
                    <td class="col-md-1" style="text-align:center;"><span class="total_amount">0.00</span></td></tr>
                </table>
            </div>--}}


        <div class="col-md-12 form" style="margin-top: 20px;">
            <div class="col-md-1 form"></div>
                <div class="col-md-10 form">
                    <button class="btn btn-default pull-right" style="margin-left: 5px;" type="reset">{{ trans('Restaurant_Form.Reset') }}</button>
                    <button class="btn btn-primary mr5 pull-right"  id="submit_orderItems_form" novalidate="novalidate" type="Submit" >{{ trans('Restaurant_Form.Submit') }}</button>
                </div>
        </div>
    </div>
    {!! Form::close() !!}
  </div>
@stop
@section('page-scripts')
    <script  src="/assets/js/jquery.sumoselect.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            $("#coupon_box_div").css("display", "none");
            $(".coupon_toggle").click(function() {
                    $("#coupon_box_div").slideDown("fast");
                   // $("#coupon_box").slideUp("fast");
            });
            $("#service-tax-div").css("display", "none");

        });

        $('#outlets').on('change',function(){
            $('.items_div').remove();
            $('#subtotal').text('0.00');
            $('.total_amount').text('0.00');
            $('.discount').text('- 0.00');
            $('#service-tax-div').css("display", "none");
            $("#service_tax_label").text("Service Tax(0%)");
            $("#coupon_box_div").css("display", "none");
            $('#coupon_box').val('');
            $('#discount_input').val(0);
            $('#coupon_flag').val('No');
            $('#min_value').val(0);
            $('#service_tax_hidden').val(0);
            $('.help-block').remove();
            $('.round_off').text('+ '+0.00);
        });


        $(function() {

            onServiceTypes();
            var scntDiv = $('#items');
            var i = $('#items_div').size() + 1;

            $('#addItems').on('click', function () {
                var oid = $('#outlets').val();
                $('#outlet_id').val(oid);
                //console.log(i)
                if (oid != '' && oid != 0) {
                    $.ajax({
                        url: '/ajax/menuitemslist',
                        data: "outlet_id=" + oid + '&flag=menuTitles',
                        success: function (data) {
                            //console.log(data.list)
                            $('<div class="col-md-12 items_div" style="margin-top: 20px"  id="items_div"><div class="col-md-1"></div><div class="col-md-2"><select id="menu_title_'+i+'" class="form-control title"  name = "menu_title_' + i + '"><option value = "0" selected>Select Menu Title</option>'+
                                    '</select></div><div class="col-md-2"><select id="menu_item_'+i+'" class="form-control menuitem" name="menu_item_' + i + '">'+
                                    '<option value="0" selected>Select Menu Item</option></select></div>' +
                                    '<div class="col-md-1"><label class="control-label" for="quantity_' + i + '" >Quantity</label></div>'+
                                    '<div class="col-md-2"><input type="number" style="padding: 6px 5px" class="form-control increament" name="item_qty_'+i+'" id="item_qty_'+i+'" min="1" value="1"/></div>' +
                                    '<div id="options_'+i+'"></div>'+
                                    '<div class="col-md-1"><label class="control-label" for="menu_price_' + i + '" >Price</label></div>'+
                                    '<div class="col-md-2 item-amount"><input type="text" style="padding: 6px 5px" name = "item_rs_' + i + '" class="form-control price" value="0.00"  id="item_rs_' + i + '" disabled/></div>'+
                                    '<input type="button" class="btn btn-primary mr5" id="remove_button" value="Delete"><input type=hidden id="count" class="count" name="count" value='+i+'></div> ').appendTo(scntDiv);
                            $('#menu_title_'+i).html(data.list);
                            if(data.service_tax != '' && data.service_tax != 0){
                                alert(data.service_tax);
                                $('#service-tax-div').css("display", "block");
                                $("#service_tax_label").text("Service Tax("+ data.service_tax +"%)");
                                $('#service_tax_hidden').val(data.service_tax);
                            }else{
                                $('#service-tax-div').css("display", "none");
                                $("#service_tax_label").text("Service Tax(0%)");
                                $('#service_tax_hidden').val(0);
                            }
                            i++;
                            return false;
                        }
                    })
                } else {
                    $('#outlets').parent().find('.help-block').remove();
                    $('#outlets').parent().addClass('has-error');
                    $('<p class="help-block">Please select outlet.</p>').insertAfter('#outlets');
                    $('#menu_item_'+i).html('<option value="0" selected >Select Menu Item</option>');
                    $('#menu_title_'+i).html('<option value="0" selected >Select Menu Category</option>');
                }


            });
            $(document).delegate('#remove_button', 'click', function () {

                //console.log(price);
                if (i > 1) {
                    var price_prev = $(this).parents('#items_div').find('.price').val();
                    //console.log('price_prev'+price_prev);
                    var subtotal = $('#subtotal').text();
                    var discount = $('#discount_input').val();
                    var total_amount = $('.total_amount').text();
                    var service_tax = $('#service_tax_hidden').val();
                    var subtotal_val_incre = 0;
                    var total_val_incre = 0;


                    subtotal_val_incre = parseFloat(subtotal) - parseFloat(price_prev);

                    total_val_incre = (parseFloat(subtotal) - parseFloat(price_prev)) - parseFloat(discount);
                    var tot = parseFloat(subtotal_val_incre);
                    if ( parseFloat(tot) > parseFloat(subtotal_val_incre)){
                        //console.log('tot : '+tot+'t : '+subtotal_val_incre)
                        var tot_diff = parseFloat(tot) - parseFloat(subtotal_val_incre)
                        $('.round_off').text('+ '+tot_diff.toFixed(2));
                    }else{
                        //console.log('tot : '+tot+'==t : '+subtotal_val_incre)
                        var to_diff = parseFloat(subtotal_val_incre) - parseFloat(tot)
                        $('.round_off').text('- '+to_diff.toFixed(2));
                    }
                    $('#subtotal').text(subtotal_val_incre.toFixed(2));
                    $('.total_amount').text(parseFloat(total_val_incre));
                    $('#total_price').val(parseFloat(subtotal_val_incre));
                    $('#totalcost_afterdiscount').val(parseFloat(total_val_incre));

                    $(this).parents('#items_div').remove();
                    //i--;
                }
                return false;
            });

            $(document).delegate('.title','change', function () {
                 //alert( this.id );
                var current_title_id = this.id.split('_');
                var count_val = current_title_id[2];

                var title_id = Number(this.value);
                var outlet_id = $('#outlets').val();
                //console.log(title_id)
                if (title_id != '' && outlet_id != '' && (title_id != 0 || outlet_id != 0) ){

                    $.ajax({
                        url: '/ajax/menuitemslist',
                        data: "title_id=" + title_id + "&outlet_id=" + outlet_id+'&flag=menus',
                        success: function (data) {
                            //console.log(data.list)
                            $('#menu_item_'+count_val).html(data.list);
                            $('.extra-div_'+count_val).remove();
                            $('#item_qty_'+count_val).val(1);
                            $('#item_rs_'+count_val).val(0.00);
                            $('#old_menu_item_id').val('');

                        }
                    })
                }else{
                    //console.log('title')
                    $('#menu_item_'+count_val).html('<option value="0" selected >Select Item</option>');
                    $('#item_qty_'+count_val).val(1);
                    $('#item_rs_'+count_val).val('');

                }
            });
            $(document).delegate('.menuitem','change', function () {
                var arr = this.id.split('_');
                var val = arr[2];
                $('.extra-div_'+val).remove();
                $('#item_qty_'+val).val(1);
            });
            $(document).delegate('.menuitem, .increament, .sumo-option','change', function () {
                var arr = this.id.split('_');
                var val = arr[2];
                var old_menu_item_id = $('#old_menu_item_id').val();
                var menu_title_id = $('#menu_title_'+val).val();
                var menu_item_id = $('#menu_item_'+val).val();
                var test_flag = '';
                if (old_menu_item_id != menu_item_id ){
                    test_flag = 'NotEqual';
                }
                var option_price = $('#extra_options_'+val).val();
                if ( typeof option_price === "undefined" ){
                    option_price = '';
                }
                //console.log(option_price);
                var item_qty = $('#item_qty_'+val).val();
                var price_prev = $('#item_rs_'+val).val();
                var subtotal = $('#subtotal').text();
                var discount = $('#discount_input').val();
                var total_amount = $('.total_amount').text();
                var service_tax = $('#service_tax_hidden').val();
                //console.log('prev_price : '+price_prev+"subtotal : "+subtotal);
                alert(service_tax);
                var price_new = 0;
                if(item_qty == ''){
                    item_qty = 1;
                }
                if(menu_title_id != '' && menu_item_id != '' && menu_title_id != 0 && menu_item_id != 0){

                    $.ajax({
                        url:'/ajax/getprice',
                        data:'menu_title='+menu_title_id+'&menu_item_id='+menu_item_id+'&item_qty='+item_qty+'&i_val='+val+'&extra_array='+option_price+'&flag='+test_flag,
                        success: function(data){

                            if( data.options != '' && data.option_flag == 'option' && data.option_flag != 'extra' ){
                                //console.log('option')
                                $('#options_'+val).html(data.options);
                                $('#extra_options_'+val).SumoSelect({placeholder: 'Select Options'});
                                $('#options_'+val+' p').addClass('form-control');
                                $('#options_'+val+' span').css('opacity', 25.5);
                                $('#options_'+val+' label i').css("background-color", "white");
                            }else if( data.option_flag != 'option' && data.option_flag == 'extra'){
                                if (test_flag == 'NotEqual'){
                                    //console.log('notequal')
                                    if (option_price == ''){
                                        $('#options_'+val).html('');
                                    }
                                }else{
                                    //console.log('equal')
                                    $('#options_'+val).append('');
                                }
                            }else{
                                //console.log('else')
                                $('#options_'+val).html('');
                            }
                            var tot_qty_price = data.tot_qty_price;
                            if(price_prev == '' || price_prev == 'undefined'){
                                price_prev = 0.00;
                            }
                            //console.log('Total_item_price : '+tot_qty_price)
                            var subtotal_val_incre = 0;
                            var total_val_incre = 0;
                            if(parseFloat(price_prev) < tot_qty_price){
                                var diff = parseFloat(tot_qty_price) - parseFloat(price_prev);
                                subtotal_val_incre = parseFloat(subtotal) + diff;
                                //console.log('add diff : '+diff+'subtotal : '+subtotal_val_incre);
                                total_val_incre = (parseFloat(subtotal) + diff) - parseFloat(discount);
                                //console.log('Total Val : '+total_val_incre);
                            }else{
                                var diff = parseFloat(price_prev) - parseFloat(tot_qty_price);
                                subtotal_val_incre = parseFloat(subtotal) - diff;
                                //console.log('sub diff : '+diff+'subtotal : '+subtotal_val_incre);
                                total_val_incre = (parseFloat(subtotal) - diff) - parseFloat(discount);
                            }

                            var tot = Math.round(subtotal_val_incre.toFixed(2));
                            if ( parseFloat(tot) > parseFloat(subtotal_val_incre)){
                                //console.log('tot : '+tot+'t : '+subtotal_val_incre)
                                var tot_diff = parseFloat(tot) - parseFloat(subtotal_val_incre)
                                $('.round_off').text('+ '+tot_diff.toFixed(2));
                            }else{
                                var to_diff = parseFloat(subtotal_val_incre) - parseFloat(tot)
                                $('.round_off').text('- '+to_diff.toFixed(2));
                            }
                            $('#old_menu_item_id').val(menu_item_id);
                            $('#item_rs_'+val).val(tot_qty_price)
                            $('#subtotal').text(Math.round(parseFloat(subtotal_val_incre)).toFixed(2));
                            $('.total_amount').text(Math.round(parseFloat(total_val_incre)).toFixed(2));
                            $('#total_price').val(parseFloat(subtotal_val_incre));
                            $('#totalcost_afterdiscount').val(parseFloat(total_val_incre));

                        }
                    });
                }else{
                    $('#item_qty_'+val).val(1);
                    $('#item_rs_'+val).val('');
                }
            });

        });

        function onServiceTypes() {
            //$('#service').on('change', function () {
                var service = $('#service').val();
                //console.log(outlet_id)
                if (service != '' && service != 'undefined') {
                    $.ajax({
                        url: '/ajax/getServiceTypeOutletList',
                        data: "service_type=" + service,
                        success: function (data) {
                            //console.log(data)
                            $('#outlets').html(data.data);
                            $('.items_div').remove();
                            $('#subtotal').text('0.00');
                            $('.total_amount').text('0.00');
                            $('.discount').text('- 0.00');
                            $('#service-tax-div').css("display", "none");
                            $("#service_tax_label").text("Service Tax(%0)");
                            $("#coupon_box_div").css("display", "none");
                            $('#coupon_box').val('');
                            $('#discount_input').val(0);
                            $('#service_tax_hidden').val(0);
                            $('#coupon_flag').val('No');
                            $('#min_value').val(0);
                            $('.help-block').remove();
                            $('.round_off').text('+ '+0.00);
                            if (service == 'dine_in'){
                                $('#table-div').css("display", "block");
                                $('#address-div').css("display", "none");
                            }
                            if (service == 'home_delivery'){
                                $('#table-div').css("display", "none");
                                $('#address-div').css("display", "block");
                            }
                            if (service == 'meal_packs'){
                                $('#table-div').css("display", "none");
                                $('#address-div').css("display", "none");
                            }
                            if (service == 'take_away'){
                                $('#table-div').css("display", "none");
                                $('#address-div').css("display", "none");
                            }

                        }
                    })
                } else {
                    //console.log('title')
                    $('#outlets').html('<option value="0" selected >Select Outlet</option>');
                }
            //});
       }



        function onCouponApply(){
            var coupon_code = $('#coupon_box').val();
            var mobile = $('#mobile').val();
            var total_amount = $('.total_amount').text();
            //console.log(coupon_code+'==='+mobile+'==='+total_amount)
            var flag = 'web_app_order';
            if ( coupon_code != '' && mobile != '' && (total_amount != '' && total_amount != '0.00') ){

                $.ajax({
                    url:'/api/v1/matchcouponcode',
                    method:'POST',
                    data:'coupon_code='+coupon_code+'&mobile_number='+mobile+'&total_cost='+total_amount+'&flag='+flag,
                    success: function (data) {
                        alert(data.cost_beforediscount)
                        if(data.status == 'applied'){
                            var min_value = data.min_value;
                            var discount_value = parseFloat(data.discounted_value);
                            var discount_before = parseFloat(data.cost_beforediscount);
                            var after_discount = discount_before - discount_value;
                            //console.log('dis : '+discount_value+'dis_before : '+discount_before)
                            $('.total_amount').text(Math.round(after_discount.toFixed(2)));
                            $('#totalcost_afterdiscount').val(Math.round(after_discount.toFixed(2)));
                            $('.discount').text('- '+discount_value.toFixed(2));
                            $('#discount_input').val(discount_value.toFixed(2));
                            $('#min_value').val(min_value);
                            $('#coupon_flag').val('Yes');
                            //$('#coupon_array').val(data.coupondata);
                            after_discount = '';
                            discount_value = '';
                            $('#coupon_box').parent().find('.help-block').remove();
                            $('#coupon_box').parent().addClass('has-error');
                            $('<p class="help-block">'+data.message+'</p>').insertAfter('#coupon_box');
                        }else if(data.status == 'already'){
                            $('#coupon_box').parent().find('.help-block').remove();
                            $('#coupon_box').parent().addClass('has-error');
                            $('<p class="help-block">'+data.message+'</p>').insertAfter('#coupon_box');
                        }else if(data.status == 'minimum'){
                            $('#coupon_box').parent().find('.help-block').remove();
                            $('#coupon_box').parent().addClass('has-error');
                            $('<p class="help-block">'+data.message+'</p>').insertAfter('#coupon_box');
                        }else if(data.status == 'expired'){
                            $('#coupon_box').parent().find('.help-block').remove();
                            $('#coupon_box').parent().addClass('has-error');
                            $('<p class="help-block">'+data.message+'</p>').insertAfter('#coupon_box');
                        }else if(data.status == 'invalid'){
                            $('#coupon_box').parent().find('.help-block').remove();
                            $('#coupon_box').parent().addClass('has-error');
                            $('<p class="help-block">'+data.message+'</p>').insertAfter('#coupon_box');
                        }

                    }
                });

            }else if( coupon_code != '' && mobile == '' && (total_amount != '' && total_amount == '0.00') ){
                $('#coupon_box').parent().find('.help-block').remove();
                $('#coupon_box').parent().addClass('has-error');
                $('<p class="help-block">Please enter mobile number and try again.</p>').insertAfter('#coupon_box');
            }else if( coupon_code == '' && mobile != '' && (total_amount != '' && total_amount == '0.00') ){
                $('#coupon_box').parent().find('.help-block').remove();
                $('#coupon_box').parent().addClass('has-error');
                $('<p class="help-block">Please enter coupon code.</p>').insertAfter('#coupon_box');
            }else if( coupon_code != '' && mobile != '' && (total_amount != '' && total_amount == '0.00') ){
                $('#coupon_box').parent().find('.help-block').remove();
                $('#coupon_box').parent().addClass('has-error');
                $('<p class="help-block">Please order items and then try coupon code.</p>').insertAfter('#coupon_box');
            }
        }

        $('#submit_orderItems_form').click(function() {
            //$("#inventoriesAddForm").valid();  // This is not working and is not validating the form createOrderAddForm
            //alert('here')
            var discount = $('#coupon_box').val();
            var min_value = $('#min_value').val();
            var name = $('#name').val();
            var mobile = $('#mobile').val();
            var count = Number($('#count').val());
            //console.log(count)
            if (discount != ''){
                var subtotal = $('#subtotal').text();
                if ( parseFloat(subtotal) < parseFloat(min_value) ){
                    $('#coupon_box').parent().find('.help-block').remove();
                    $('#coupon_box').parent().addClass('has-error');
                    $('<p class="help-block">Minimum order price should be equal/more than '+min_value+'</p>').insertAfter('#coupon_box');
                    return false;
                }
            }
                if ( name == '' || mobile == '' || (count == 0 || count == '' || isNaN(count)) ){
                    //alert('inside')
                    if (name == ''){
                        $('#name').parent().find('.help-block').remove();
                        $('#name').parent().addClass('has-error');
                        $("<p class='help-block'>Name field can't be empty.</p>").insertAfter('#name');
                    }
                    if (mobile == ''){
                        $('#mobile').parent().find('.help-block').remove();
                        $('#mobile').parent().addClass('has-error');
                        $("<p class='help-block'>Mobile field can't be empty.</p>").insertAfter('#mobile');
                    }
                    if (count == 0 || count == '' || isNaN(count)){
                        $('#addItems').parent().find('.help-block').remove();
                        $('#addItems').parent().addClass('has-error');
                        $("<p class='help-block'>Please add menu items.</p>").insertAfter('#addItems');
                    }
                    return false;
                }else{
                    //alert('hre')
                    $('.price').prop('disabled',false);
                    return true;
                }




        });
    </script>
@stop

