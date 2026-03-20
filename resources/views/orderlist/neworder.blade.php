<?php

$sess_outlet_id = Session::get('outlet_session');
$order_date = \Illuminate\Support\Facades\Input::get('order_date');
if ( isset($order_date)) {
    $start_date = $order_date;
    $end_date = $order_date;
} else {
    $order_date = \Carbon\Carbon::now()->format("Y-m-d");
}

?>
@extends('partials.default')

@section('pageHeader-left')
    Add order
@stop

@section('pageHeader-right')
    <a href="/orderslist" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
    {{--<a href="/add-order" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Order</a>--}}
@stop

@section('content')
    <style>

        #cart_div {
            border:1px solid black;
            min-height: 600px;
        }
        ul.scrollmenu {
            background-color: #EEEEEE !important;
            overflow: auto;
            white-space: nowrap;
        }

        ul.scrollmenu li {
            display: inline-block;
            text-align: center;
        }

        li.active a{
            background-color: #00a3a7 !important;
            color: #000 !important;
        }

        .sticky {
            position: fixed;
            top: 6px;
        }

        .sticky .nav-above {
            position: absolute;
            top:6px;
            left:1em;
            right:1em;
            height:15px;
        }

        nav {
            z-index: 1040;

            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;

            width: 100%;
            padding: 0em 0em;
            margin-left: 0px;

            filter: alpha(opacity=80); /* IE6-IE8 */
            position: relative;
        }

        nav li a:hover,
        nav li a.selected {
            color: #000;
            background: #00a3a7 !important;
        }

        .abc{
            position: fixed;
            top: 60px;
            width: 40%;
        }

    </style>

    <div class="row">

        <div class="col-md-12">

            <div class="widget-wrap ft-left">
                <div class="widget-container">
                    <div class="widget-content">

                        @if( !isset($outlet_id) || $outlet_id == '')
                            <h1>Please select outlet from header.</h1>
                        @else
                            <div class="col-md-7 col-sm-7" id="menus_div" style="padding-left: 0px;">

                                @if( isset($menu) && sizeof($menu) > 0 )
                                    <div class="nav-container">
                                        <nav>
                                            <ul class="nav material-tabs scrollmenu" role="tablist">
                                                <?php $i=1;?>
                                                <li><input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search..."></li>
                                                @foreach( $menu as $key=>$m )

                                                    <li class="@if($i == 1 ) active @endif" onclick="changeSelect(this)"><a href="#{{ preg_replace('/[^A-Za-z0-9\-]/', '-', $key) }}">{{ $key }}</a></li>

                                                    <?php $i++;?>
                                                @endforeach
                                            </ul>

                                        </nav>
                                    </div>

                                    <div class="tab-content" id="table_cont" style="max-height: 450px; overflow-y: scroll;" >

                                        <table class="table table-striped table-hover" id="myUL">
                                            <?php $i=1;?>
                                            @foreach( $menu as $key1=>$cat1 )

                                                    @if( isset($cat1) && sizeof($cat1) > 0 )
                                                        <thead id="{{ preg_replace('/[^A-Za-z0-9\-]/', '-', $key1) }}" class="sec_tr">
                                                            <tr >

                                                                <td colspan="3" style="text-align: center;font-weight: bold;">
                                                                    <section class="sec_class" id="{{ preg_replace('/[^A-Za-z0-9\-]/', '-', $key1) }}"> {{ $key1 }}
                                                                    </section>
                                                                </td>
                                                            </tr>
                                                        @foreach( $cat1 as $m )
                                                            <?php $options = array();?>
                                                            <tr id="{{ $m['item_id'] }}">
                                                                <td>
                                                                    <span class="list-item">{{ $m['item']}}</span>
                                                                    @if ( (isset($m['item_options']) && sizeof($m['item_options']) > 0 ) || (isset($attributes) && sizeof($attributes) > 0) )
                                                                        <br>
                                                                        <a class="custom-item-link" href="javascript:void(0)" style="font-size: 12px;" onclick="openCustomizeModal({{ $m['item_id'] }},'{{ str_replace("'","=",$m['item'])}}','{{ $m['price'] }}',{{  json_encode($m['item_options']) }},{{ json_encode($attributes) }})">
                                                                            <i class="fa fa-plus"></i> Customization
                                                                        </a>
                                                                        <?php $options = $m['item_options'];?>
                                                                    @endif
                                                                </td>
                                                                <td style="text-align: right">{{ $m['price']}}</td>
                                                                <td style="width:1%;"><a href="javascript:void(0)" class="btn btn-circle btn-primary" onclick="additem(this,'list',{{ json_encode($options) }},{{ json_encode($attributes) }})"><i class="fa fa-plus"></i></a></td>
                                                            </tr>
                                                        @endforeach
                                                        </thead>

                                                    @endif
                                                <?php $i++;?>
                                            @endforeach
                                        </table>

                                    </div>
                                @endif
                            </div>

                            {{--cart div--}}
                            <div class="col-md-5 col-sm-5" id="cart_div">

                                <div class="col-md-6" style="padding: 0px !important;margin-top: 10px">
                                    <h4 style="text-align: left;">Your Order</h4>
                                </div>
                                <div class="col-md-6" style="padding: 0px !important;margin-top: 10px">
                                    <input type="text" id="order_date" value="{{ date('Y-m-d H:i:s') }}" class="form-control pull-right">
                                </div>
                                <div class="col-md-6" style="padding: 0px !important;margin-top: 10px">
                                    <button class="btn btn-primary pull-right" onclick="openItem('open')"><i class="fa fa-plus"></i> Open Item</button>
                                </div>
                                <div style="clear: both"></div>

                                <table class="table table-striped table-hover" id="cart-item" style="margin-top: 20px">
                                    <tr id="default-row"><td style="text-align: center">No items added.<td></tr>
                                </table>

                                <table id="summary-table" class="table table-hover table-striped">
                                    <tr>
                                        <td style="font-weight: bolder">Sub Total:</td><td id="new-order-sub-total" style="text-align: right;font-weight: bolder">0.00</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bolder">Total:</td><td id="new_order_total" style="text-align: right;font-weight: bolder">0.00</td>
                                    </tr>
                                </table>
                                <div style="clear: both"></div>

                                <div class="col-md-12" style="padding: 5px 0px;font-weight: bold">
                                    <lable>Order Type</lable>
                                    <select id="order_type" class="form-control" onchange="checkOrderType(this)">
                                        <option value="dine_in">Dine In</option>
                                        <option value="take_away">Take Away</option>
                                        <option value="home_delivery">Home Delivery</option>
                                    </select>
                                </div>

                                <div class="col-md-12" style="padding: 5px 0px;font-weight: bold">
                                    <lable>Mobile</lable>
                                    <input type="text" id="mobile" class="form-control" placeholder="Contact no" value="">
                                </div>
                                <div class="col-md-12" style="padding: 5px 0px;font-weight: bold">
                                    <lable>Name</lable>
                                    <input type="text" id="name" class="form-control" placeholder="Name" value="">
                                </div>
                                <div class="col-md-12 hide" style="padding: 5px 0px;font-weight: bold">
                                    <lable>Address</lable>
                                    <textarea id="address" class="form-control"></textarea>
                                </div>

                                <div class="dine_in_field">
                                    <div class="col-md-6" style="padding: 0px;font-weight: bold">
                                        <lable>@if( isset($outlet) && sizeof(json_decode($outlet,true)) > 0  ) {{ ucwords($outlet->order_lable) }} @else {{ 'Table' }} @endif No.</lable><input type="number" onchange="remove_error('table_no')" id="table_no" required value="" class="form-control">
                                    </div>
                                    <div class="col-md-6" style="padding: 0px;font-weight: bold">
                                        <lable>No. of Person</lable><input type="number" onchange="remove_error('person_no')" id="person_no" required value="" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-2" id="loading_div"></div>

                                <div class="col-md-4 pull-right" style="padding-right:0px;margin-top: 10px; ">
                                    <button id="place_order_btn" class="btn btn-primary pull-right" onclick="placeOrder()" style="margin-bottom: 20px">Process Order</button>
                                </div>
                                <div class="col-md-4 pull-right" style="padding-right:5px;margin-top: 10px;margin-right: 5px ">
                                    <button id="cancel_order_btn" class="btn btn-danger pull-right" onclick="cancelOrder()" style="margin-bottom: 20px">Cancel Order</button>
                                </div>

                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>

    </div>
    <div id="openItemModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Open Item</h4>
                    </div>

                    <div class="modal-body ">
                        <form id="open_item" class="form-horizontal material-form j-forms">
                            <div class="form-group">
                                <div class="col-md-8">
                                    <label class="control-label">Item Name</label>
                                    <input type="text" onchange="remove_error('name')" class="form-control" id="open_item_name" value="">
                                    <label class="name error hide">Please Enter Item Name</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8">
                                    <label class="control-label">Item Price</label>
                                    <input type="text" onchange="remove_error('price')" class="form-control" id="open_item_price" value="">
                                    <label class="price error hide">Please Enter Item Price</label>
                                    <label class="price_numeric error hide">Please Enter Valid Price</label>
                                </div>
                            </div>
                        </form>
                        <div style="clear: both"></div>
                        <div class="modal-footer">
                            <button type="button" id="update_btn" class="btn btn-primary" onclick="openItem('add')"><i class="fa fa-plus"></i> Add</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <div id="customizeItemModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Customize Item</h4>
                </div>

                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <span style="background-color: greenyellow;padding: 9px;">
                        &#x20B9;<span id="option_parent_price"></span><span id="option_parent_actual_price" class="hide"></span>
                    </span>
                    &nbsp;&nbsp;
                    <button type="button" id="update_btn" class="btn btn-primary" onclick="additem(this,'custom','')">
                        <i class="fa fa-plus"></i> Add To Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="itemOptionModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Item Options</h4>
                </div>

                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @include('orderlist.processOrder')

@stop

@section('page-scripts')

    <script src="/assets/js/new/lib/bootstrap-datetimepicker.js"></script>
    <script src="/assets/js/new/lib/orderProcess.js"></script>
    <script src="/assets/js/new/lib/payment-modes.js"></script>

    <?php
            function isMobile() {
                return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
            }
            if(!isMobile()){ ?>
                <script src="js/jquery.scrollTo-1.4.2-min.js" type="text/javascript"></script>
                <script src="js/waypoints.min.js" type="text/javascript"></script>
                <script src="js/navbar2.js" type="text/javascript"></script>
    <?php       }else{
     ?>
            <style> nav {
                    z-index: 999;
                }
            </style>
      <?php  }
    ?>



    <script type="text/javascript">

        //update option parent item price
        function updateOptionParentPrice(ele) {

            var max_select = $(ele).closest('.opt-grp').find('#max_select').val();
            var item_price = parseFloat($("#customizeItemModal #option_parent_price").text());
            var option_price = parseFloat($(ele).attr("data-item-price"));
            var group_id = $(ele).attr("data-group-id");
            var html = '';

            var selected = 0;
            $('#itemOptionModal .grp-show input').each( function () {
                if ( this.checked ) {
                    selected++;
                }
            });

            if ( selected > max_select ) {
                $(ele).removeAttr('checked');
                successErrorMessage('You can not select more than '+max_select+' option','error');
                return;
            }

            //remove previous options
            $('#customizeItemModal #group-block-'+group_id).empty();

            $('#itemOptionModal .grp-show input').each( function () {

                var item_id = $(this).val();
                var item_name = $(this).data('item-name');

                if ( this.checked ) {
                    html += '<div>- <span class="item-option-chk" id="'+item_id+'" data-option-price="'+option_price+'">' +
                                        item_name+
                                    '</span>' +
                            '</div>';
                }

            });
            $('#customizeItemModal #group-block-'+group_id).html(html);

            if( ele.checked) {
                item_price = item_price + option_price;
            } else {
                item_price = item_price - option_price;
            }

            $("#customizeItemModal #option_parent_price").text(parseFloat(item_price).toFixed(2));

        }

        //open customize model
        function openCustomizeModal( id, name, price, option_arr, attributes_arr ) {

            var price1 = parseFloat(price);
            $('#customizeItemModal .modal-body').empty();

            var html = '<form id="customize_item_form" class="form-horizontal j-forms">' +
                            '<input type="hidden" value="'+id+'" id="parent_item_id">';

            var grp_cnt = option_arr.length;
            var attr_cnt = attributes_arr.length;
            var opt_html = '';

            if( grp_cnt > 0 ) {
                for (var i = 0; i < grp_cnt; i++) {

                    html += '<div class="form-group col-md-12">' +
                                '<button class="btn btn-primary col-md-8" data-group-id="' + option_arr[i].id + '" type="button" onclick="selectOption(this)">' +
                                    option_arr[i].name +
                                '</button>'+
                            '</div>' +
                            '<div id="group-block-'+ option_arr[i].id +'" class="form-group col-md-12">';

                    var opt_length = option_arr[i].options.length;

                    if ( opt_length > 0 ) {

                        opt_html +='<div id="group-'+option_arr[i].id+'" class="hide opt-grp" data-block-id="' + option_arr[i].id + '">';
                            opt_html +='<input type="hidden" id="max_select" value="'+ option_arr[i].max +'">';


                        for (var j = 0; j < opt_length; j++) {

                            var display_checked = "";

                            //check if option is default true or not
                            if ( option_arr[i].options[j].default_option == 1 ) {

                                html += '<div>- <span class="item-option-chk" id="'+option_arr[i].options[j].option_id+'" data-option-price="'+option_arr[i].options[j].option_price+'">' +
                                                    option_arr[i].options[j].option_name+
                                                '</span>' +
                                        '</div>';

                                price1 += parseFloat(option_arr[i].options[j].option_price);
                                display_checked = "checked";
                            }

                            opt_html += '<div class="form-group col-md-12" style="margin-bottom: 0px;">' +
                                            '<label class="checkbox col-md-6" style="padding-bottom: 0px;">' +
                                                '<input '+display_checked+' type="checkbox" data-group-id="' + option_arr[i].id + '" onchange="updateOptionParentPrice(this)" value="' + option_arr[i].options[j].option_id + '" data-item-price="' + option_arr[i].options[j].option_price + '" data-item-name="' + option_arr[i].options[j].option_name + '">' +
                                                    '<i></i>' + option_arr[i].options[j].option_name +
                                            '</label>' +
                                            '<span>' +
                                                '<span class="pull-right">&#x20B9; ' + parseFloat(option_arr[i].options[j].option_price) +
                                                '</span>' +
                                            '</span>' +
                                        '</div>';

                        }

                        opt_html += '</div>';

                        opt_html += '<div style="clear:both"></div>';

                    }

                    if ( opt_html != '' ) {
                        $('#itemOptionModal .modal-body').html(opt_html);
                    }

                    html += '</div>';

                }

            }

            if( grp_cnt > 0 && attr_cnt > 0 ) {
                html += '<br><div class="col-md-12"><hr></div>';
            }

            //display attributes
            if(attr_cnt > 0) {
                html += '<h5 style="font-weight: bold">Select Attributes</h5> <div class="item_attr"> ';
                for (var i = 0; i < attr_cnt; i++) {

                    html += '<div class="form-group col-md-12" style="margin-bottom: 0px;">' +
                                '<label class="checkbox col-md-6" style="padding-bottom: 0px;">' +
                                    '<input type="checkbox" class="item-attr-chk" name="'+ attributes_arr[i].name +'" value="' + attributes_arr[i].id + '" >' +
                                    '<i></i>' + attributes_arr[i].name +
                                '</label>' +
                            '</div>';

                }
                html += '</div>';
            }

            html += '</form><div style="clear: both"></div>';

            $('#customizeItemModal .modal-title').html("Customize <span id='custom_item_title'>"+name.replace("=", "'")+"</span>");
            $('#customizeItemModal .modal-body').html(html);
            $('#customizeItemModal #option_parent_price').text(price1);
            $('#customizeItemModal #option_parent_actual_price').text(price);

            $('#customizeItemModal').modal('show');

        }

        //open group options
        function selectOption(ele) {

            //make all group option hide
            $('#itemOptionModal .opt-grp').each(function () {
                $(this).addClass('hide');
                $(this).removeClass('grp-show');
            });

            var group_id = $(ele).data('group-id');

            //show group option from the option modal when select group
            $('#itemOptionModal #group-'+group_id).removeClass('hide');
            $('#itemOptionModal #group-'+group_id).addClass('grp-show');

            $('#itemOptionModal').modal('show');

        }

        function checkOrderType(ele) {

            var order_type = $(ele).val();

            if ( order_type == 'home_delivery') {
                $('#address').parent().removeClass('hide');
                $(".dine_in_field").addClass('hide');
            } else if( order_type == 'take_away') {
                $('#address').parent().addClass('hide');
                $(".dine_in_field").addClass('hide');
            } else {
                $('#address').parent().addClass('hide');
                $(".dine_in_field").removeClass('hide');
            }
        }

        function remove_error(field_name){

            if(field_name == 'name'){
                $('.name').addClass('hide');
            }

            var price_text = $('#open_item_price').val();
            if($.isNumeric(price_text)){
                $('.price_numeric').addClass('hide');
                return;
            }

            if(field_name == 'price'){
                $('.price').addClass('hide');
                return;
            }

            var table_no = $('#table_no').val();
            if(field_name == 'table_no'){
                if($.isNumeric(table_no)) {
                    $('#table_no').css("border-color", "#cccccc");
                    return;
                }
            }

            var person_no = $('#person_no').val();
            if(field_name == 'person_no'){
                if($.isNumeric(table_no)) {
                    $('#person_no').css("border-color", "#cccccc");
                    return;
                }
            }


        }

        function openItem(flag) {

            if ( flag == 'open') {

                $('#open_item_name').val('');
                $('#open_item_price').val('');

                $('#openItemModal').modal('show');
                $('#open_item_name').focus();

            } else if ( flag == 'add') {

                var item_id = 0;
                var item_name = $('#open_item_name').val();
                var price = $('#open_item_price').val();

                if ( item_name == '' ) {
                    $('.name').removeClass('hide');
                    return;
                }
                if(price == ''){
                    $('.price').removeClass('hide');
                    return;
                }
                if(!$.isNumeric(price)){
                    $('.price_numeric').removeClass('hide');
                    return;
                }

                {{--check already added item--}}
                var check = false;
                $('#cart-item tr').each(function() {
                    var avail_itm = $(this).attr('id');
                    if ( avail_itm == item_id ) {
                        check = true;
                    }
                });

                //check for open item
                if ( item_id == 0 ) {
                    check = false;
                }

                if ( check == true ) {
                    successErrorMessage('Item has been already added','error');
                    return;
                }


                $('#cart-item #default-row').remove()

                var html = '<tr id="'+item_id+'">' +
                                '<td style="width: 2%">' +
                                    '<a href="javascript:void(0)" style="color: red" onclick="removeItem(this)">' +
                                        '<i class="fa fa-close"></i>' +
                                    '</a>' +
                                '</td>' +
                                '<td>' +
                                    '<span class="itm-name">'+item_name+
                                    '</span><br>' +
                                    '<div class="input-group col-sm-5" style="float:left;margin-right: 10px;">'+
                                        '<span class="input-group-btn">'+
                                            '<button type="button" class="btn btn-danger btn-number" onclick="changeNumber(this)" data-type="minus" data-field="quant[2]">'+
                                                '<span class="glyphicon glyphicon-minus"></span>'+
                                            '</button>'+
                                        '</span>'+
                                        '<input style="padding-left:0px;padding-right: 0px; text-align: center;" onchange="changeCalculation(this)" type="text" name="quant[2]" class="form-control input-number" value="1" min="1" max="1000">'+
                                        '<span class="input-group-btn">'+
                                            '<button type="button" class="btn btn-success btn-number" onclick="changeNumber(this)" data-type="plus" data-field="quant[2]">'+
                                                '<span class="glyphicon glyphicon-plus"></span>'+
                                            '</button>'+
                                        '</span>'+
                                    '</div>' +
                                    '<div class="item-price-div" style="margin-top: 5px;"> X <span class="item-price">'+price+'</span><span class="price-without-option hide">'+price+'</span></div>' +
                                '</td>' +
                                '<td style="text-align:right;">'+price+'</td>' +
                            '</tr>';


                $('#cart-item').append(html);
                //make total
                makeSubTotal();

                $('.input-number').focusin(function(){
                    $(this).data('oldValue', $(this).val());
                });

                $(".input-number").keydown(function (e) {
                    // Allow: backspace, delete, tab, escape, enter and .
                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                                // Allow: Ctrl+A
                            (e.keyCode == 65 && e.ctrlKey === true) ||
                                // Allow: home, end, left, right
                            (e.keyCode >= 35 && e.keyCode <= 39)) {
                        // let it happen, don't do anything
                        return;
                    }
                    // Ensure that it is a number and stop the keypress
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
                    }
                });

                $('#openItemModal').modal('hide');

            }
        }

        //place order
        function placeOrder() {

            var error = 0;

            $('input,select,textarea').css('border-color', '');

            var order_date = $('#order_date').val();
            var table_no = $('#table_no').val().trim();
            var person_no = $('#person_no').val().trim();
            var mobile = $('#mobile').val().trim();
            var order_type = $('#order_type').val();
            var address = $('#address').val().trim();
            var name = $('#name').val().trim();

            if (order_type == "dine_in"){
                if (table_no == '') {
                    $('#table_no').css("border-color", "red");
                    $('#table_no').focus();
                    error = 1;
                }
                if (person_no == '') {
                    $('#person_no').css("border-color", "red");
                    $('#person_no').focus();
                    error = 1;
                }
            }
            if ( order_type == 'take_away' || order_type == "home_delivery" ) {

                if ( mobile == '' ) {

                    $('#mobile').css('border-color','red');
                    $('#mobile').focus();
                    error = 1;
                }

            }
            if ( order_type == 'home_delivery' ) {

                if ( address == '' ) {
                    $('#address').css('border-color','red');
                    $('#address').focus();
                    error = 1;
                }

            }

            if ( mobile != '' && isNaN(mobile) ) {
                $('#mobile').css('border-color','red');
                $('#mobile').focus();
                error = 1;
            }

            if ( $('#default-row').length ) {
                swal({
                    title: "Warning?",
                    text: "Please select order item!",
                    confirmButtonColor: "#DD6B55"
                });
                window.location.hash = '#menus_div';
                return;
            }

            item_id = [];item_qty = [];item_name = [];item_price = [];

            var item_options = {};
            var item_attr = {};

            var i = 0;
            $('#cart-item tr').each(function() {

                jsonObj = [];
                jsonObjAttr = [];

                item_id.push($(this).attr('id'));
                item_qty.push($(this).find('.input-number').val());
                item_name.push($(this).find('.itm-name').text());
                item_price.push($(this).find('.price-without-option').text());

                //var main_item_id = $(this).attr('id');

                if ( $(this).find('.item-options').length > 0 ) {

                    $(this).find('.item-options').each(function () {

                        item = {};
                        item['option_id'] = $(this).attr('data-opt-id');
                        item['option_price'] = $(this).attr('data-opt-price');
                        jsonObj.push(item);

                    })
                }
                item_options[i] = jsonObj;

                if ( $(this).find('.item-attr').length > 0 ) {

                    $(this).find('.item-attr').each(function () {

                        final_attr = {};
                        final_attr['attr_id'] = $(this).attr('data-attr-id');
                        jsonObjAttr.push(final_attr);

                    })
                }
                item_attr[i] = jsonObjAttr;
                i++;
            });


            if ( error == 1 ) {
                return;
            }

            //$('#place_order_btn').attr('disabled',true);
            $('#place_order_btn').text('Saving...');
            $('#loading_div').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');


            $.ajax({
                url: '/place-order',
                type: "post",
                data: { item_id:item_id,
                        item_qty:item_qty,
                        item_name:item_name,
                        item_price:item_price,
                        item_options:item_options,
                        item_attribute:item_attr,
                        order_date:order_date,
                        table_no:table_no,
                        person_no:person_no,
                        order_type:order_type,
                        mobile:mobile,
                        address:address,
                        name:name
                    },
                success: function (data) {

                    $('#loading_div').empty();
                    $('#place_order_btn').attr('disabled',false);
                    $('#place_order_btn').text('Place Order');

                    if ( data.message == 'success' || data.message == 'firebase error' ) {

                        $('#cart-item').empty();
                        $('#cart-item').append('<tr id="default-row"><td style="text-align: center">No items added.</td></tr>');

                        //empty fields
                        $('#cart_div #new-order-sub-total').text('0.00');
                        $('#cart_div #new_order_total').text('0.00');
                        $('#cart_div #table_no').val('');
                        $('#cart_div #person_no').val('');

                        var msg = '';
                        if ( data.message == 'success') {
                            msg = 'Order has been added successfully! Do you want to process?';
                        } else if ( data.message == 'firebase error') {
                            msg = 'Order has been added successfully! but not send to order taker';
                        }

                        swal({
                            title: "Added!",
                            text: msg,
                            type: "success",
                            showCancelButton: true,
                            confirmButtonText: "Yes, process it!",
                            cancelButtonText: "No!",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        }, function (isConfirm) {
                            if (isConfirm) {
                                processBill(data.order_id,'open');
                                swal.close();
                                //window.location.href = "/orderslist?order_date="+order_date;
                            } else {
                                swal("Done!", "Ready for new order. :)", "success");
                            }
                        });

                    } else {
                        alert('There is some error occurred. Please try again later.');
                    }

                }
            });

        }

        //cancel order
        function cancelOrder() {

            var sub_total = $('#cart_div #new-order-new-order-sub-total').text();
            if( parseFloat(sub_total) < 1 ) {
                swal({
                    title: "Warning?",
                    text: "Please add item in cart!",
                    confirmButtonColor: "#DD6B55"
                });
                window.location.hash = '#menus_div';
                return;
            }

            var r = confirm("Are you sure you want to cancel this order?");
            if (r == true) {

                $('#cart-item').empty();
                $('#cart-item').append('<tr id="default-row"><td style="text-align: center">No items added.</td></tr>');
                //reset fields
                $('#cart_div #new-order-sub-total').text('0.00');
                $('#cart_div #new_order_total').text('0.00');
                $('#cart_div #table_no').val('');
                $('#cart_div #person_no').val('');

            }

        }

        //remove item
        function removeItem(e) {

            $(e).parent().parent().remove();
            //make total
            makeSubTotal();
        }

        //add item in cart
        function additem( e, flag, option_arr, attributes_arr) {

            var options = "";
            var attr = "";
            //check item is customized or not
            if ( flag ==  'list') {

                var item_id = $(e).parent().parent().attr('id');
                var item_name = $(e).parent().siblings(":first").find('.list-item').text();
                var price = $(e).parent().parent().find('td').eq(1).text();
                var price_without_option = price;

                if ( $(e).parent().parent().find('.custom-item-link').length > 0 ) {

                    openCustomizeModal( item_id, item_name, price, option_arr, attributes_arr);
                    return;

                }

            } else {

                var item_id = $('#customize_item_form #parent_item_id').val();
                var item_name = $('#customizeItemModal #custom_item_title').text();
                var price = parseFloat($("#customizeItemModal #option_parent_price").text()).toFixed(2);
                var price_without_option = parseFloat($("#customizeItemModal #option_parent_actual_price").text()).toFixed(2);

                var allAttrs = [];
                var allAttrsIds = [];
                var flag = 0;
                $('.item_attr :checked').each(function() {

                    if( this.checked ) {

                        allAttrs.push($(this).attr('name'));
                        allAttrsIds.push($(this).val());
                        flag = 1;
                    }

                });

                if(flag == 1){
                    attr += '&nbsp;<span style="font-size: 12px;margin-top: 0px;">(<span class="item-attr" data-attr-id="' + allAttrsIds + '" >' + allAttrs + '</span>)</span>';
                }

                //check selected options
                $('#customizeItemModal .item-option-chk').each(function (ele) {

                        var opt_name = $(this).text();
                        var opt_price = parseFloat($(this).attr('data-option-price'));
                        var opt_id = $(this).attr('id');

                        options += '- <span class="item-options" style="margin-left: 10px;" data-opt-id="'+opt_id+'" data-opt-price="'+opt_price+'">'+opt_name+'</span>' +
                                '<br>';

                })


            }

            {{--check already added item--}}
            var check = false;
            $('#cart-item tr').each(function() {
                var avail_itm = $(this).attr('id');

                if ( avail_itm == item_id ) {

                    if ( options == "" && attr == "" ) {

                        if ( $(this).find('.item-options').length == 0 &&  $(this).find('.item-attr').length == 0) {

                            var itm_qty = $(this).find('.input-number').val();
                            $(this).find('.input-number').val( parseFloat(itm_qty) + 1).change();
                            $('#customizeItemModal').modal('hide');
                            check = true;

                        }

                    }

                }
            });

            if ( check == true ) {
                //successErrorMessage('Item has been already added.','error');
                return;
            }


            $('#cart-item #default-row').remove();
            var html = '<tr id="'+item_id+'">' +
                            '<td style="width: 2%">' +
                                '<a href="javascript:void(0)" style="color: red" onclick="removeItem(this)">' +
                                    '<i class="fa fa-close"></i>' +
                                '</a>' +
                            '</td>' +
                            '<td>' +
                                '<span class="itm-name">'+item_name+'</span>' +
                                '' + attr +
                                '<br>' + options +
                                '<div class="input-group col-sm-5" style="float:left;margin-right: 10px;">'+
                                    '<span class="input-group-btn">'+
                                        '<button type="button" class="btn btn-danger btn-number" onclick="changeNumber(this)" data-type="minus" data-field="quant[2]">'+
                                            '<span class="glyphicon glyphicon-minus"></span>'+
                                        '</button>'+
                                    '</span>'+
                                    '<input style="padding-left:0px;padding-right: 0px; text-align: center;" onchange="changeCalculation(this)" type="text" name="quant[2]" class="form-control input-number" value="1" min="1" max="1000">'+
                                    '<span class="input-group-btn">'+
                                        '<button type="button" class="btn btn-success btn-number" onclick="changeNumber(this)" data-type="plus" data-field="quant[2]">'+
                                            '<span class="glyphicon glyphicon-plus"></span>'+
                                        '</button>'+
                                    '</span>'+
                                '</div>' +
                                '<div class="item-price-div" style="margin-top: 5px;"> X <span class="item-price">'+price+'</span><span class="price-without-option hide">'+price_without_option+'</span></div>' +
                            '</td>' +
                            '<td style="text-align:right;">'+price+'</td>' +
                        '</tr>';
            $('#cart-item').append(html);
            //make total
            makeSubTotal();

            if ( flag !=  'list') {
                $('#customizeItemModal').modal('hide');
            }

            $('.input-number').focusin(function(){
                $(this).data('oldValue', $(this).val());
            });

            $(".input-number").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ((e.keyCode === 110 || e.keyCode === 190) && this.value.split('.').length === 2) {
                    return false;
                }

                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                            // Allow: Ctrl+A
                        (e.keyCode == 65 && e.ctrlKey === true) ||
                            // Allow: home, end, left, right
                        (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }

        //update item price on change item qty
        function changeCalculation(e) {

            var input = $(e);
            var price = parseFloat($(e).parent().parent().find('.item-price').text());

            var currentVal = parseFloat(input.val());

            if ( isNaN(currentVal)) {
                alert('Quantity can not be blank.');
                input.val(1);
                currentVal = 1;
                $(e).focus();
            }

            if ( currentVal > 1000 ) {
                alert('Quantity can not be greater than 1000.');
                $(e).focus();
                input.val(1);
                currentVal = 1;
            }

            var total_price = currentVal * price;

            $(e).parent().parent().parent().find('td').eq(2).text(parseFloat(total_price).toFixed(2));

            //make total
            makeSubTotal();

        }

        //increment and decrment number
        function changeNumber(e) {

            type      = $(e).attr('data-type');
            var input = $(e).parent().parent().find('.input-number');
            var price = parseFloat($(e).parent().parent().parent().find('.item-price').text());

            var currentVal = parseFloat(input.val());

            if (!isNaN(currentVal)) {
                if(type == 'minus') {

                    $(e).parent().parent().find('.btn-success').attr('disabled',false);

                    if(currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                        changeCalculation(input);

                    }
                    if(parseFloat(input.val()) == input.attr('min')) {
                        $(e).attr('disabled', true);
                    }

                } else if(type == 'plus') {

                    //enable minus button
                    $(e).parent().parent().find('.btn-danger').attr('disabled', false);

                    if(currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                        changeCalculation(input);
                    }
                    if(parseFloat(input.val()) == input.attr('max')) {
                        $(e).attr('disabled', true);
                    }

                }
            } else {
                input.val(0);
            }

        }

        //make total
        function makeSubTotal(){

            var subtotal = 0;
            $('#cart-item tr').each(function() {
                var itm_price = parseFloat($(this).find('td').eq(2).text());
                subtotal = subtotal + itm_price;
            });
            $('#new-order-sub-total').text(parseFloat(subtotal).toFixed(2));
            $('#new_order_total').text(parseFloat(subtotal).toFixed(2));
        }

        $(document).ready(function() {

            $('#order_type').select2();
            /*$('#order_date').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });*/

            $('#order_date').datetimepicker({
                format: "YYYY-MM-DD HH:mm:ss"
            });


            var hash = window.location.hash;
            hash && $('ul.nav a[href="' + hash + '"]').tab('show');

            $('.material-tabs a').click(function (e) {
                var abc = $(this).attr('href');
                $(abc).tab('show');
                //$(id).tab('show');
                var scrollmem = $('body').scrollTop();
                //window.location.hash = this.hash;
                $('html,body').scrollTop(scrollmem);
            });

        });

        function myFunction() {
            // Declare variables
            var input, filter, table, tr, a, i;
            input = document.getElementById('myInput');
            filter = input.value.toUpperCase();
            table = $("#myUL");
            tr =  $("#myUL")[0].getElementsByTagName('tr');

            // Loop through all list items, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                a = tr[i].getElementsByTagName("td")[0];

                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

        function changeSelect(ele) {
            $(".active").removeClass("active");
            $(ele).addClass("active");
        }

    </script>
@stop