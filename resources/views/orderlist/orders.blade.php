<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

$sess_outlet_id = Session::get('outlet_session');
$order_date = Request::get('order_date');
if ( isset($order_date)) {
    $start_date = $order_date;
    $end_date = $order_date;
} else {
    $order_date = Carbon::now()->format("Y-m-d");
}

?>
@extends('partials.default')

@section('pageHeader-left')
    Order Details
@stop

@section('pageHeader-right')
    <a href="/unpaid-orders" class="btn btn-primary">Unpaid Orders</a>
    <a href="/ongoing-tables" class="btn btn-primary"><i class="fa fa-cutlery"></i>&nbsp;Ongoing Table</a>
    <a href="/add-order" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Order</a>
@stop

@section('content')

    <div class="row" id="order_filter">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix">
                    <form class='j-forms'>

                        @if( !isset($sess_outlet_id) || $sess_outlet_id == '')
                            <div class="col-md-3 form-group">
                                {!! Form::select('outlet_id', isset($outlets)?$outlets:array(),isset($outlet_id)?$outlet_id:null,array('class' => 'form-control','id'=>'outlet_id')) !!}
                            </div>
                        @endif

                        <div id="date_filter" class="form-group @if( !isset($sess_outlet_id) || $sess_outlet_id == '')col-md-4 @else col-md-6 @endif">
                            <a class="btn btn-primary pull-left hide" id="prev_date" onclick="getDayOrders(-1)" style="margin-left: 7px;"> <i class="fa fa-step-backward" aria-hidden="true"></i></a>
                            <div class="input-daterange input-group">
                                {!! Form::text('from_date', $start_date, ['class' => 'form-control',"id"=>"from_date","readonly"=>"readonly"]) !!}
                                <span class="input-group-addon">to</span>
                                {!! Form::text('to_date', $end_date, ['class' => 'form-control',"id"=>"to_date","readonly"=>"readonly"]) !!}
                            </div>
                            <a class="btn btn-primary pull-left hide" id="next_date" onclick="getDayOrders(1)" style="margin-left: 7px;"> <i class="fa fa-step-forward" aria-hidden="true"></i></a>
                        </div>

                        <div class="col-md-3 form-group">
                            {!! Form::select('time_slot',  array('select' => 'All Slots'), null,array('class' => 'form-control','id'=>'time_slot')) !!}
                        </div>
                        <div class="col-md-3 form-group">
                            {!! Form::select('date_type',  array('end_time' => 'End time','start_time' => 'Start time'), null,array('class' => 'form-control','id'=>'date_type')) !!}
                        </div>

                        <div class="form-footer pull-right">
                            <div class="col-md-12">
                                <input type="hidden" id="search_date" value="{{date('Y-m-d', strtotime('0 day', strtotime($start_date)))}}">

                                <a class="btn btn-primary hide" data-target="#export_fields"
                                        data-toggle="modal" onclick="openExportModel()" id="export_btn">Export Excel</a>
                                <button type="submit" class="hide" id="hidden_export"></button>
                                @if(\App\Account::canResetInvoice())
                                    <button type="button" class="btn btn-success" data-target="#reset_invoice_modal"
                                        data-toggle="modal">Reset InvoiceNo</button>
                                @endif
                                <button type="button" class="btn btn-success primary-btn" id="show_btn" onclick="getOrders()">Show</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div id="table_div">
                            <div>No orders found.</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {!! Form::hidden('time_slot_hide', isset($time_slot_hide)?$time_slot_hide:'select', ["id"=>"time_slot_hide"]) !!}

    <div class="modal bs-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="reset_invoice_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Choose Fields</h4>
                </div>


                <div class="modal-body pb30">

                    <div class="row mt30">
                        <label class="col-md-3 control-label">Reset Invoice From</label>
                        <div class="col-md-6">
                            {!! Form::text('invoice_reset', null, ['class' => 'form-control','id'=>'invoice_reset_id','placeholder'=>"InvoiceNumber : XYZ0005"]) !!}
                            <label class="col-md-12 error">*It will set sequence of invoice number as per date and time from start geven number.</label>
                        </div>
                        <div class="col-md-2">
                            <button id="reset_invoice_btn" class="btn btn-success primary-btn">Reset</button>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="modal bs-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="export_fields">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Choose Fields</h4>
                </div>

                <form class='j-forms'>
                    <input type="hidden" value="export" id="flag" name="flag"/>
                    <input type="hidden" name="from_date" id="hidden_from_date">
                    <input type="hidden" name="to_date" id="hidden_to_date">
                    <input type="hidden" name="time_slot" id="hidden_time_slot">
                    <input type="hidden" name="date_type" id="hidden_date_type">

                    <div class="modal-body pb30">

                        <div class="row mt30">
                            <div class="col-sm-1">
                                {!! Form::checkbox('datetime', 1, 1, ['class' => 'field','id'=>'date']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Date-Time</label>


                            <div class="col-sm-1">
                                {!! Form::checkbox('invoice_no', 1, 1, ['class' => 'field','id'=>'invoice_no']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Invoice No.</label>


                            <div class="col-sm-1">
                                {!! Form::checkbox('order_no', 1, 1, ['class' => 'field','id'=>'order_no']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Order No.</label>


                            <div class="col-sm-1">
                                {!! Form::checkbox('name', 1, 1, ['class' => 'field','id'=>'name']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Name</label>

                        </div>

                        <div class="row mt30">
                            <div class="col-sm-1">
                                {!! Form::checkbox('bill_order_type', 1, 1, ['class' => 'field','id'=>'order_type']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Order Type</label>


                            <div class="col-sm-1">
                                {!! Form::checkbox('item_list', 1, 1, ['class' => 'field','id'=>'item_list']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Item List</label>


                            <div class="col-sm-1">
                                {!! Form::checkbox('sub_total', 1, 1, ['class' => 'field','id'=>'sub_total']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Sub Total</label>


                            <div class="col-sm-1">
                                {!! Form::checkbox('discount', 1, 1, ['class' => 'field','id'=>'discount']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Discount</label>

                        </div>

                        <div class="row mt30">
                            <div class="col-sm-1">
                                {!! Form::checkbox('tax_amount', 1, 1, ['class' => 'field','id'=>'tax_amount']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Total Tax Amount
                            </label>


                            <div class="col-sm-1">
                                {!! Form::checkbox('round_off', 1, 1, ['class' => 'field','id'=>'round_off']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Round Off
                            </label>


                            <div class="col-sm-1">
                                {!! Form::checkbox('bifurcation', 1, 1, ['class' => 'field','id'=>'bifurcation']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Amount Bifurcation</label>

                            <div class="col-sm-1">
                                {!! Form::checkbox('no_person', 1, 1, ['class' => 'field','id'=>'no_person']) !!}
                            </div>
                            <label class="col-sm-2 control-label">No of Person</label>

                        </div>

                        <div class="row mt30">
                            <div class="col-sm-1">
                                {!! Form::checkbox('fina_total', 1, 1, ['class' => 'field','id'=>'fina_total']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Final Total</label>

                            <div class="col-sm-1">
                                {!! Form::checkbox('other_fields', 1, 1, ['class' => 'field','id'=>'other_fields']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Other Fields</label>

                            <div class="col-sm-1">
                                {!! Form::checkbox('tax_bifurcation', 1, 1, ['class' => 'field','id'=>'tax_bifurcation']) !!}
                            </div>
                            <label class="col-sm-2 control-label">Tax Bifurcation</label>

                            <div class="col-sm-2 col-sm-offset-9">
                                <button id="export_submit"
                                        type="submit"
                                        class="btn btn-success pl30 pr30">Export</button>
                            </div>
                        </div>

                    </div>

                </form>

            </div>
        </div>
    </div>

@stop

@section('page-scripts')

    <script src="/assets/js/new/lib/footable.all-min.js"></script>
    <script src="/assets/js/new/lib/bootstrap-datetimepicker.js"></script>
    <script src="/assets/js/new/lib/orderProcess.js"></script>
    <script src="/assets/js/new/lib/payment-modes.js"></script>

    <script type="text/javascript">

        $("#reset_invoice_btn").click(function () {
            var invoice_no = $("#invoice_reset_id").val();
            $.ajax({
                url: '/reset_invoice_no',
                type: "POST",
                data: {invoice_no:invoice_no},
                success: function (data) {
                    var decoded_data = JSON.parse(data);
                    if(decoded_data.success) {
                        successErrorMessage(decoded_data.msg,'success');
                        $('#reset_invoice_modal').modal('hide');
                    }else{
                        successErrorMessage(decoded_data.msg,'error');
                        $('#reset_invoice_modal').modal('hide');
                    }
                }
            });
        });

        function openExportModel() {

            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var time_slot = $("#time_slot").val();
            var date_type = $("#date_type").val();

            $("#hidden_from_date").val(from_date);
            $("#hidden_to_date").val(to_date);
            $("#hidden_time_slot").val(time_slot);
            $("#hidden_date_type").val(date_type);

        }

        $(document).ready(function() {

            $('#outlet_id').select2({
                placeholder: 'Select Outlet'
            });
            $('#time_slot').select2({
                placeholder: ''
            });
            $('#date_type').select2({
                placeholder: ''
            });

            //SSE for update page data
            if(typeof(EventSource) !== "undefined") {
                var source = new EventSource("/update-page-data/orders");
                source.onmessage = function(event) {
                    var from_date = $("#from_date").val();
                    var to_date = $("#to_date").val();
                    var today_date = new Date();
                    var formattedDate = moment(today_date).format('YYYY-MM-DD');
                    if(formattedDate == from_date && formattedDate == to_date) {
                        var total_orders = $('#total_orders').val();
                        var server_total_orders = event.data.split(":");
                        var total = server_total_orders[1];

                        if (parseInt(total) != parseInt(total_orders)) {

                            if ( $('.modal').hasClass('in') ) {
                            } else {
                                getOrders();
                            }

                        }
                    }

                };
            } else {
                console.log('SSE not supported');
            }



            var url = document.URL;
            if(url.search("day") > 0){
                $('#next_date').removeClass('hide');
                $('#prev_date').removeClass('hide');
                $('#date_filter').addClass('order_filter');
            }else{
                $('#date_filter').removeClass('order_filter');
            }


            $('#outlet_id').change(function () {
                getSlots('select');         // if outlet select from form
            });

            if($('#main_filter').val()!=''){
                getSlots('session');        // if outlet select globally from titlebar(session)
            }

            function getSlots(id_from) {
                $("#loading_gif").show();
                if(id_from=='select') {
                    var selected_outlet_id = Number($('#outlet_id').val());
                }
                else if (id_from=='session') {
                    var selected_outlet_id = Number($('#main_filter').val());
                }else{
                    var selected_outlet_id = '';        //if match not found
                }
                $.ajax({
                    url: '/ajax/selectslots',
                    type: "POST",
                    data: {outlet_id: selected_outlet_id},
                    success: function (data) {
                        var html='';
                        if(data.length > 0) {
                            $("#time_slot").html('');
                            $("#time_slot").append(new Option('All Slots', 'select'));
                            for (var i = 0; i < data.length; i++) {
                                $("#time_slot").append(new Option(capitalizeFirstLetter(data[i].slot_name) + ' (' + data[i].from_time + ' - ' + data[i].to_time + ') ', data[i].id));
                            }
                        }else{
                            $("#time_slot").html('');
                            $("#time_slot").append(new Option('All Slots', 'select'));
                        }
                        var selected = $("#time_slot_hide").val();
                        $("#time_slot").val(selected);
                    }
                });
            }

            getOrders();
        });

        //pay via Upi for processbill
        function proPayViaUpi() {

            var vpa = $('#proc_vpa').val();
            var order_id = $('#bill_order_id').val();
            var total = parseFloat($('#total').text());

            if ( vpa == '' ) {
                successErrorMessage('Please enter Virtual payment address','error');
                return;
            } else if ( total < 1 ) {
                successErrorMessage('Total amount must be greater than 1','error');
                return;
            }

            var exp = /^([a-zA-Z0-9_.-])+@{1}([a-zA-Z0-9_.-])+/;
            var pattrn = new RegExp(exp);
            var res = pattrn.test(vpa);
            if ( res == false ) {
                successErrorMessage('Virtual payment address is not valid','error');
                $('#proc_vpa').focus();
                return;
            }

            $('#proc_proceed_btn').attr('disabled',true);
            $('#upi_block').prepend('<div class="py_loading" style="text-align:center;"><img src="/loader.gif" /></div>');

            $.ajax({
                url: '/pay-with-upi',
                type: "POST",
                data: {order_id: order_id, vpa:vpa,total:total},
                dataType: 'json',
                success: function (data) {

                    $('.py_loading').remove();

                    if ( data.status == 2 ) {
                        $('#proc_proceed_btn').attr('disabled',false);
                        successErrorMessage(data.error_msg,'error');
                        return;

                    } else {
                        successErrorMessage('Payment request has been send successfully','success');
                        $('#proc_txn_id').val(data.txnid);
                        $('#proc_check_status_div').removeClass('hide');
                        $('.proc_status_btn_div').removeClass('hide');
                    }
                }
            });

        }

        //payvia UPI for edit bill
        function payViaUpi() {

            var vpa = $('#vpa').val();
            var order_id = $('#edit_order_id').val();
            var total = parseFloat($('#edit_total').text());

            if ( vpa == '' ) {
                successErrorMessage('Please enter Virtual payment address','error');
                return;
            } else if ( total < 1 ) {
                successErrorMessage('Total amount must be greater than 1','error');
                return;
            }

            var exp = /^([a-zA-Z0-9_.-])+@{1}([a-zA-Z0-9_.-])+/;
            var pattrn = new RegExp(exp);
            var res = pattrn.test(vpa);
            if ( res == false ) {
                successErrorMessage('Virtual payment address is not valid','error');
                $('#vpa').focus();
                return;
            }

            $('#edit_proceed_btn').attr('disabled',true);
            $('#upi_block').prepend('<div class="py_loading" style="text-align:center;"><img src="/loader.gif" /></div>');

            $.ajax({
                url: '/pay-with-upi',
                type: "POST",
                data: {order_id: order_id, vpa:vpa,total:total},
                dataType: 'json',
                success: function (data) {

                    $('.py_loading').remove();

                    if ( data.status == 2 ) {
                        $('#edit_proceed_btn').attr('disabled',false);
                        successErrorMessage(data.error_msg,'error');
                        return;

                    } else {
                        successErrorMessage('Payment request has been send successfully','success');
                        $('#edit_txn_id').val(data.txnid);
                        $('#check_status_div').removeClass('hide');
                        $('.status_btn_div').removeClass('hide');
                    }
                }
            });

        }

        function proCheckPaymentStatus() {

            var order_id = $('#bill_order_id').val();
            var txn_id = $('#proc_txn_id').val();

            $('#proc_check_status_btn').attr('disabled',true);
            $('#upi_block').prepend('<div class="py_loading" style="text-align:center;"><img src="/loader.gif" /></div>');

            $.ajax({
                url: '/upi-payment-status',
                type: "POST",
                data: {order_id: order_id, txn_id:txn_id},
                dataType: 'json',
                success: function (data) {

                    $('.py_loading').remove();
                    $('#proc_check_status_btn').attr('disabled',false);

                    if ( data.status == 1 ) {
                        $('.proc_status_btn_div').addClass('hide');
                        $('#proc_status_text').html('<span class="label label-success">Confirm</span>');
                    } else if ( data.status == 2 ) {
                        $('#proc_proceed_btn').attr('disabled',true);
                        $('#proc_status_text').html('<span class="label label-danger">Failed</span>');
                        alert('Please proceed payment again by clicking proceed button')
                    } else {
                        $('#proc_status_text').html('<span class="label label-default">Pending</span>');
                    }
                }
            });
        }

        //check UPI payment status
        function checkPaymentStatus() {

            var order_id = $('#edit_order_id').val();
            var txn_id = $('#edit_txn_id').val();

            $('#check_status_btn').attr('disabled',true);
            $('#upi_block').prepend('<div class="py_loading" style="text-align:center;"><img src="/loader.gif" /></div>');

            $.ajax({
                url: '/upi-payment-status',
                type: "POST",
                data: {order_id: order_id, txn_id:txn_id},
                dataType: 'json',
                success: function (data) {

                    $('.py_loading').remove();
                    $('#check_status_btn').attr('disabled',false);

                    if ( data.status == 1 ) {
                        $('.status_btn_div').addClass('hide');
                        $('#status_text').html('<span class="label label-success">Confirm</span>');
                    } else if ( data.status == 2 ) {
                        $('#edit_proceed_btn').attr('disabled',true);
                        $('#status_text').html('<span class="label label-danger">Failed</span>');
                        alert('Please proceed payment again by clicking proceed button')
                    } else {
                        $('#status_text').html('<span class="label label-default">Pending</span>');
                    }
                }
            });

        }

        //get orders
        function getOrders(day) {

            var date_type = $('#date_type').val();
            var from = $('#from_date').val();
            var to = $('#to_date').val();
            var outlet_id = $('#outlet_id').val();
            var time_slot = $('#time_slot').val();
            if(check30daysDiff(from,to)){
                return;
            }

            processBtn('show_btn','add','Showing...');

            $.ajax({
                url: '/orderslist',
                type: "post",
                data: { from_date:from,to_date:to,outlet_id:outlet_id, time_slot:time_slot,date_type:date_type},
                success: function (data) {

                    processBtn('show_btn','remove','Show');
                    $('#table_div').html(data);

                    if ( $('#reports tbody tr:first td').length > 1 ) {
                        $('#export_btn').removeClass('hide');
                    } else {
                        $('#export_btn').addClass('hide');
                    }

                    $("#table_body #cancel_order_link").on("click", function () {
                        $('#reason').val('');
                        $('#cancel_reason').val('');
                        var invoideId = $(this).data('id');
                        var orderId = $(this).data('orderid');
                        $('#modal_order_id').val(orderId);
                        $('#lbl_invoice_id').text(invoideId);
                    });

                    $('input[name="order_type"]').click(function() {

                        if( $(this).attr("value") == "home_delivery" ){
                            $('#address_div').removeClass('hide');
                            $('#add_delivery_value').removeClass('hide');
                        } else {
                            $('#address_div').addClass('hide');
                            $('#add_delivery_value').addClass('hide');
                        }
                        //var tax_name = $(this).closest('.modal-body').find('#outlet_tax').val();
                        var tax_name = "";
                        var ord_id = $('#bill_order_id').val();
                        var new_delivery = "";
                        getInvoiceNo($(this).attr("value"),ord_id,'process',tax_name,new_delivery);

                    });

                    $("#reports").footable({phone:767,tablet:1024});

                    $('#cancel_reason').select2({
                        placeholder: 'Select Reason'
                    });

                    $('#disc_type').select2({
                       placeholder: 'Discount Type'
                    });



                    $('#printModal').on('hidden.bs.modal', function () {

                        // refresh page is user going through process billing to update link in list
                        var check = $('#printModal #close_type').val();

                        if ( check == 'refresh') {
                            getOrders();
                        }

                    })

                }
            });

        }


        function getDayOrders(day) {

            var from = $('#from_date').val();
            var to = $('#to_date').val();

            var from_date = new Date(from);
            var to_date = new Date(to);

            from_date.setDate(from_date.getDate() + day)
            to_date.setDate(to_date.getDate() + day)

            //convert YYYY-mm-dd
            var search_from = from_date.getFullYear()+'-' + (from_date.getMonth()+1) + '-'+from_date.getDate();
            var search_to = to_date.getFullYear()+'-' + (to_date.getMonth()+1) + '-'+to_date.getDate();

            $('#from_date').val(search_from);
            $('#to_date').val(search_to);

            var outlet_id = $('#outlet_id').val();
            var time_slot = $('#time_slot').val();

            processBtn('show_btn','add','Showing...');

            $.ajax({
                url: '/orderslist',
                type: "post",
                data: { from_date:search_from,to_date:search_to,outlet_id:outlet_id, time_slot:time_slot},
                success: function (data) {

                    processBtn('show_btn','remove','Show');

                    $('#table_div').html(data);

                    if ( $('#reports tbody tr:first td').length > 1 ) {
                        $('#export_btn').removeClass('hide');
                    } else {
                        $('#export_btn').addClass('hide');
                    }

                    $("#table_body #cancel_order_link").on("click", function () {
                        $('#reason').val('');
                        $('#cancel_reason').val('');
                        var invoideId = $(this).data('id');
                        var orderId = $(this).data('orderid');
                        $('#modal_order_id').val(orderId);
                        $('#lbl_invoice_id').text(invoideId);
                    });

                    $('input[name="order_type"]').click(function() {

                        if( $(this).attr("value") == "home_delivery" ){
                            $('#address_div').removeClass('hide');
                        } else {
                            $('#address_div').addClass('hide');
                        }
                        var tax_name = $(this).closest('.modal-body').find('#outlet_tax').val();
                        //applyDisc('type');
                        var ord_id = $('#bill_order_id').val();
                        var new_delivery = "";
                        getInvoiceNo($(this).attr("value"),ord_id,'process',tax_name,new_delivery);

                    });

                    $("#reports").footable({phone:767,tablet:1024});

                    /*$('#reports').DataTable({
                        responsive: true,
                        iDisplayLength: 100,
                        pageLength: 100
                    });*/

                    $('#printModal').on('hidden.bs.modal', function () {

                        // refresh page is user going through process billing to update link in list
                        var check = $('#printModal #close_type').val();

                        if ( check == 'refresh') {
                            getOrders();
                        }

                    })

                }
            });

        }



//update bill
        function updateBill() {

            var ord_id = $('#edit_order_id').val();
            var type = $('input[name="edit_order_type"]:checked').val();
            var total = parseFloat($('#edit_total').text());
            var sub_total = parseFloat($('#edit_sub_total').text());
            var inv_no = $('#edit_invoice_no').val();
            var discount = $('#edit_discount').text();
            var mobile = $('#edit_mobile').val();
            var name = $('#edit_name').val();
            var round_off = $('#edit_round_off').text();
            var address = $('#edit_address').val();
            var source = $('#edit_paid_type').find(':selected').data('source');
            var paid_type = $('#edit_paid_type').val();
            var delivery_charge = $('#editBill #delivery_charge').text();
            var custom_field = $('#custom_form').serializeArray();
            var edit_discount_type = $("#edit_disc_type").val();
            var edit_discount_text = "";
            var tax_id = $('#editBill #outlet_tax').val();

            if(edit_discount_type == "percentage"){
                edit_discount_text = $("#edit_disc_value").val()+"%";
            }else if(edit_discount_type == "fixed"){
                edit_discount_text = "fixed";
            }

            if ( inv_no == '' ) {
                successErrorMessage('Please enter invoice number','error');
                $('#edit_invoice_no').focus();
                return;
            }

            //check payment mode amount
            var payment_mode_total = 0;
            $('#editBill .paid-value').each(function () {
                payment_mode_total += parseFloat($(this).val());
            })

            if ( payment_mode_total < parseFloat(total) ) {

                $('#editBill .paid-value').css('border-color','red');
                successErrorMessage('Payment mode amount total can not be less than bill amount.','error');
                return;

            } else if ( payment_mode_total > parseFloat(total) ) {

                $('#editBill .paid-value').css('border-color','red');
                successErrorMessage('Payment mode amount total can not be greater than bill amount','error');
                return;
            }

            if ( $('#editBill #edit_tax_calc .tax_name').length > 0 ) {
                var tax = {};

                for (var i = 0; i < $('#edit_tax_calc .tax_name').length; i++) {
                    //
                    var tax_name = $('#edit_tax_calc #tax_name_' + i).text().trim();
                    var name_wdt = tax_name.length;

                    var tax_par = $('#edit_tax_calc #tax_perc_' + i).text().trim();
                    var par_wdt = tax_par.length;

                    var len = name_wdt - par_wdt;
                    tax_name = tax_name.substring(0, len - 1).trim();

                    var tax_val = $('#edit_tax_calc #tax_val_' + i).text();

                    tax[tax_name] = {calc_tax: tax_val, percent: tax_par};

                }
            }
            else {
                tax = '';
            }

            //get payment modes value
            var py_source_ids = []; var py_option_ids = []; var py_amount =[];var trn_ids = [];

            var check_repeat_mode = 0;
            var modes_count = $('#editBill .paid-value').length;

            for( var i=0; i< modes_count; i++ ) {

                //check repeat payment mode
                if ( py_option_ids.length > 0 ) {

                    for ( j=0; j< py_option_ids.length; j++ ) {

                        var check_py_option_id = 0;var check_source_id = 0;

                        if ( $('#editBill .paid-type').eq(i).val() == py_option_ids[j] ) {
                            check_py_option_id = 1;
                        }

                        if ( $('#editBill .paid-type').eq(i).find(':selected').data('source') == py_source_ids[j] ) {
                            check_source_id = 1;
                        }

                        if ( check_py_option_id == 1 && check_source_id == 1 ) {
                            check_repeat_mode = 1;
                        }
                    }

                }

                py_option_ids.push($('#editBill .paid-type').eq(i).val());
                py_source_ids.push($('#editBill .paid-type').eq(i).find(':selected').data('source'));
                py_amount.push($('#editBill .paid-value').eq(i).val());
                trn_ids.push($('#editBill .transaction-id').eq(i).val());

            }

            if ( check_repeat_mode == 1 ) {
                successErrorMessage('Same Payment mode cant be add multiple time','error');
                return;
            }

            $('#update_btn').attr('disabled',true);
            $('#update_btn').text('Updating...');
            $.ajax({
                url: '/updateinvoice',
                type: "post",
                dataType:'json',
                data: {
                    order_id: ord_id,
                    invoice_no:inv_no,
                    mobile:mobile,
                    name:name,
                    s_total:sub_total,
                    total:total,
                    round_off:round_off,
                    ord_type:type,
                    tax:tax,
                    discount:discount,
                    discount_type:edit_discount_text,
                    address:address,
                    source:source,
                    paid_type:paid_type,
                    delivery_charge:delivery_charge,
                    custom_fields:custom_field,
                    payment_option_ids:py_option_ids,
                    source_ids:py_source_ids,
                    payment_mode_amount:py_amount,
                    trn_ids:trn_ids,
                    tax_id:tax_id
                },
                success: function (data) {
                    $('#update_btn').text('Update Invoice');
                    $('#update_btn').attr('disabled',false);
                    if ( data.status == 'success' ) {
                        $('#editBill').modal('hide');
                        openOrderView(ord_id,'refresh');
                        $('.modal').css('overflow-y','scroll');
                        //$('.modal').css('position','absolute');
                    } else {
                        successErrorMessage('There is some error ocurred','error');

                    }

                }
            });

        }

//apply settle bill on editbill

        function settleBill(flag) {

            $('#tip_text').remove();
            $('#edit_disc_type').val('');
            $('#edit_disc_value').val('');

            var settle_val = $('#settle_value').val();

            if ( settle_val == '' ) {
                successErrorMessage('Please enter settlement value','error');
                $('#settle_value').focus();
                return;
            }
            $('#sub_delivery_div').remove();
            $('#edit_disc_div').remove();

            var type = $('input[name="edit_order_type"]:checked').val();
            var total = parseFloat($('#edit_total').text());
            var sub_total = parseFloat($('#edit_sub_total').text());
            var new_total = 0.00;
            var discount = 0;
            var tip = 0;

            settle_val = parseFloat($('#settle_value').val());


            if ( settle_val < total ) {

                if( $('#edit_tax_calc').length > 0 ) {

                    $('#edit_disc_div').remove();

                    if($("#discount_after_tax").val() == 0) {

                        var tax_total = 0.00;
                        for(var i=0; i<$('#edit_tax_calc .tax_name').length; i++ ) {

                            var tax_perc = parseFloat($('#edit_tax_calc #tax_perc_'+i).text());
                            tax_perc = tax_perc.toFixed(2);

                            tax_total += parseFloat(tax_perc);

                        }

                        var tx_percent = 1 + (parseFloat(tax_total) / 100);

                        new_total = settle_val / parseFloat(tx_percent);
                        new_total = new_total.toFixed(3);

                        discount = sub_total - new_total;

                        $("<div id='edit_disc_div' class='col-md-12' style='width: 100%;'><span class='col-md-6'>Discount</span><span class='col-md-6' style='text-align: right'>&#8377; <span id='edit_discount'>" + parseFloat(discount).toFixed(2) + "</span></span></div>").insertAfter('#editBill #sub_total_div');

                        var new_total1 = new_total;
                        for(var j=0; j < $('#edit_tax_calc .tax_name').length; j++ ) {

                            var tax_perc = parseFloat($('#edit_tax_calc #tax_perc_'+j).text());
                            //tax_perc = tax_perc.toFixed(2);

                            var new_tax = (new_total * tax_perc) / 100;
                            //new_tax = new_tax.toFixed(2);

                            new_total1 = parseFloat(new_total1) + parseFloat(new_tax);
                            $('#edit_tax_calc #tax_val_'+j).text(new_tax.toFixed(2));

                        }

                    }else if($("#discount_after_tax").val() == 1) {

                        var new_total1 = sub_total;
                        for(var j=0; j < $('#edit_tax_calc .tax_name').length; j++ ) {

                            //var tax_perc = parseFloat($('#edit_tax_calc #tax_perc_'+j).text());
                            //tax_perc = tax_perc.toFixed(2);

                            //var new_tax = (sub_total * tax_perc) / 100;
                            //new_tax = new_tax.toFixed(2);
                            var new_tax = $('#edit_tax_calc #tax_val_'+j).text();
                            new_total1 = parseFloat(new_total1) + parseFloat(new_tax);

                        }
                        var discount = new_total1 - settle_val;
                        $("<div id='edit_disc_div' class='col-md-12' style='width: 100%;'><span class='col-md-6'>Discount</span><span class='col-md-6' style='text-align: right'>&#8377; <span id='edit_discount'>" + parseFloat(discount).toFixed(2) + "</span></span></div>").insertAfter('#editBill #edit_tax_calc');
                        new_total1 = new_total1 - discount;
                    }



                    total = parseFloat(new_total1).toFixed(2);

                } else {

                    discount = sub_total - settle_val;
                    total = sub_total - discount;

                    $('#edit_tax_calc').remove();
                    $('#edit_disc_div').remove();

                    $("<div id='edit_disc_div' class='col-md-12' style='width: 100%;'><span class='col-md-6'>Discount</span><span class='col-md-6 pull-right' style='text-align: right'>&#8377; <span id='edit_discount'>"+discount.toFixed(2)+"</span></span></div>").insertAfter('#sub_total_div');
                    $('#edit_total').val(new_total);
                }

            } else if ( settle_val > total ) {

                tip = settle_val - total;
                new_total = settle_val - tip;

                $('<span id="tip_text" style="background-color:#98fb98;padding:5px" class="pull-left">&#8377; '+ tip+' as tip</span>').insertBefore('#update_btn');

            } else {
                successErrorMessage('No changes in bill','error');
                return;
            }

            var round_total = Math.round(total);
            var round_off = Math.abs(total - round_total).toFixed(2);

            $('#edit_round_off').text(round_off);
            $('#edit_total').text(round_total.toFixed(2));

            if ( $('#editBill .paid-value').length == 1 ) {
                $('.paid-value:first').val(round_total.toFixed(2));
            }

        }

//aply discount editbill
        function aplyDisEditBill(flag) {

            $('#tip_text').remove();

            if ( flag == 'remove' ) {
                $('#edit_disc_type').val('');
                $('#edit_disc_value').val('');
                $('#settle_value').val();
            }

            var sub_total = parseFloat($('#edit_sub_total').text());
            var disc_type = $('#edit_disc_type').val();
            var disc_val = $('#edit_disc_value').val();
            var type = $('input[name="edit_order_type"]:checked').val();



            if ( flag == 'apply' ) {

                if ( disc_type == '') {
                    successErrorMessage('Select Discount type','error');
                    $('#edit_disc_type').focus();
                    return;
                }
                if ( disc_val == '' ) {
                    successErrorMessage('Enter Discount Value','error');
                    $('#edit_disc_value').focus();
                    return;
                }

            } else {
                $('#edit_disc_type').val('');
                $('#edit_disc_value').val('');
                $('#settle_value').val('');
                disc_type = '';
                disc_val = '';
            }

            disc_val = parseFloat(disc_val);

            if ( disc_type == 'fixed') {
                if ( disc_val > sub_total ) {
                    successErrorMessage('Discount value can not be greater than sub total','error');
                    $('#edit_disc_value').focus();
                    return;
                }
            } else if ( disc_type == 'percentage' ) {
                if ( disc_val > 100 ) {
                    successErrorMessage('Discount percentage can not be greater than 100','error');
                    $('#edit_disc_value').focus();
                    return;
                }
            }

            $('#edit_disc_div').remove();
            if ( disc_type != '' && disc_val != ''  && $("#discount_after_tax").val() == 0) {

                var parce_val = disc_val;
                if (disc_type == 'fixed') {
                    sub_total = sub_total - disc_val;

                } else {
                    parce_val = sub_total * disc_val / 100;
                    sub_total = sub_total - parce_val.toFixed(2);
                }

                var before_elem = '';
                if( $('#edit_tax_calc').length > 0) {
                    before_elem = 'edit_tax_calc';
                    $("<div id='edit_disc_div' class='col-md-12' style='width: 100%;'><span class='col-md-6'>Discount</span><span style='text-align: right;' class='col-md-6'>&#8377; <span id='edit_discount'>" + parce_val.toFixed(2) + "</span></span></div>").insertBefore('#editBill #' + before_elem);
                }else{
                    before_elem = 'sub_total_div';
                    $("<div id='edit_disc_div' class='col-md-12' style='width: 100%;'><span class='col-md-6'>Discount</span><span style='text-align: right;' class='col-md-6'>&#8377; <span id='edit_discount'>" + parce_val.toFixed(2) + "</span></span></div>").insertAfter('#editBill #' + before_elem);
                }
            }

            var total = sub_total;

            if( $('#edit_tax_calc').length > 0) {
                $('#edit_tax_calc').removeClass('hide');
                for(var i=0; i<$('#edit_tax_calc .tax_name').length; i++ ) {

                    var tax_perc = parseFloat($('#edit_tax_calc #tax_perc_'+i).text());
                    tax_perc = tax_perc.toFixed(2);

                    if ( tax_perc != 0 ) {

                        var new_tax = parseFloat(sub_total * tax_perc / 100);
                        new_tax = new_tax.toFixed(2);

                        $('#edit_tax_calc #tax_val_'+i).text(new_tax);

                    } else {

                        //when itemwise tax enable that time percentage will be zero
                        new_tax = $('#edit_tax_calc #tax_val_'+i).text();
                    }

                    total += parseFloat(new_tax);

                }

            } else {
                $('#edit_tax_calc').addClass('hide');
            }

            if ( disc_type != '' && disc_val != ''  && $("#discount_after_tax").val() == 1) {

                var parce_val = disc_val;
                if (disc_type == 'fixed') {
                    total = total - disc_val;

                } else {
                    parce_val = total * disc_val / 100;
                    total = total - parce_val.toFixed(2);
                }

                if( $('#edit_tax_calc').length > 0) {
                    if($('#sub_delivery_div').length >0) {
                        $("<div id='edit_disc_div' class='col-md-12' style='width: 100%;'><span class='col-md-6'>Discount</span><span style='text-align: right' class='col-md-6'>&#8377; <span id='edit_discount'>" + parce_val.toFixed(2) + "</span></span></div>").insertBefore('#editBill #sub_delivery_div');
                    }else{
                        $("<div id='edit_disc_div' class='col-md-12' style='width: 100%;'><span class='col-md-6'>Discount</span><span style='text-align: right' class='col-md-6'>&#8377; <span id='edit_discount'>" + parce_val.toFixed(2) + "</span></span></div>").insertBefore('#editBill #edit_round_off_div');
                    }
                }else{
                    $("<div id='edit_disc_div' class='col-md-12' style='width: 100%;'><span class='col-md-6'>Discount</span><span class='col-md-6'>&#8377; <span id='edit_discount'>"+parce_val.toFixed(2)+"</span></span></div>").insertAfter('#editBill #sub_total_div');
                }

            }
            if($('#sub_delivery_div').length >0) {

                var delivery = $("#delivery_charge").text();
                total += parseFloat(delivery);

            }

            total = total.toFixed(2);

            var round_total = Math.round(total);

            var round_off = Math.abs(total - round_total).toFixed(2);

            $('#edit_round_off').text(round_off);
            $('#edit_total').text(round_total.toFixed(2));

            if ( $('#editBill .paid-value').length == 1 ) {
                $('#editBill .paid-value:first').val(round_total.toFixed(2));
            }

        }

        //update bill
        function editBill(order_id, flag) {

            $('#tip_text').remove();
            $('#editBill').modal('show');
            $('#editBill .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

            $.ajax({
                url: '/editbill',
                type: "GET",
                data: {order_id : order_id},
                success: function (data) {
                    $('#editBill .modal-body').html(data);

                    $('#edit_disc_type').change(function () {
                        $('#edit_disc_value').val('');
                    });
                    //$("#edit_new_delivery_value").val("");

                    $('#editBill .paid-type').select2();
                    //$('#edit_disc_type').select2();
                    $('#editBill #outlet_tax').select2();

                    var order_type = $('input[name="edit_order_type"]:checked').val();
                    var edit_disc_type = $("#edit_disc_type").val();
                    var edit_disc_value = $("#edit_disc_value").val();

                    if(order_type == "home_delivery"){
                        $('#edit_delivery_value').removeClass('hide');
                    }else{
                        $('#edit_delivery_value').addClass('hide');
                    }

                    $('input[name="edit_order_type"]').click(function(){

                        if( $(this).attr("value") == "home_delivery" ){
                            $('#edit_address_div').removeClass('hide');
                            $('#edit_delivery_value').removeClass('hide');
                        } else {
                            $('#edit_address_div').addClass('hide');
                            $('#edit_delivery_value').addClass('hide');
                        }

                        //var tax_name = $('#editBill #outlet_tax').val();
                        var tax_name = '';
                        var new_delivery = "";
                        //aplyDisEditBill('type');
                        var edit_disc_type = $("#edit_disc_type").val();
                        var edit_disc_value = $("#edit_disc_value").val();

                        getInvoiceNo($(this).attr("value"),order_id,'edit',tax_name,new_delivery,edit_disc_type,edit_disc_value);
                        setTimeout(function() {
                            //your code to be executed after 1 second
                            if( $('input[name="edit_order_type"]:checked').val() == "home_delivery" ) {
                                var delivery_charge = parseFloat($("#editBill #delivery_charge").text());
                                if(delivery_charge > 0){
                                    $('#edit_new_delivery_value').val(delivery_charge);
                                }
                            }else{
                                $("#editBill #sub_delivery_div").addClass("hide");
                            }
                        }, 1000);

                    });

                    $('#editBill #outlet_tax').change(function(){

                        var tax_name = $(this).val();
                        var ord_type = $('input[name="edit_order_type"]:checked').val();
                        var new_delivery = "";
                        getInvoiceNo(ord_type,order_id,'edit',tax_name,new_delivery,edit_disc_type,edit_disc_value);

                    });

                    $('.date_time').datetimepicker({
                        format: "YYYY-MM-DD HH:mm:ss"
                    });

                    $('.date').DatePicker({
                        format: "yyyy-mm-dd",
                        orientation: "auto",
                        autoclose: true,
                        todayHighlight: true
                    });
                },
                error:function(error) {
                    alert('There is some error ocurred.');
                }

            });

        }

        //process order bill
       // inside orderProcess.js

//order history
        function orderHistory( order_id ) {

            $('#orderHistoryModal').modal('show');
            $('#orderHistoryModal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

            $.ajax({
                url: '/order-history',
                type: "POST",
                data: {order_id : order_id},
                success: function (data) {
                    $('#orderHistoryModal .modal-body').html(data);
                },
                error:function(error) {
                    successErrorMessage('There is some error ocurred','error');
                }

            });

        }

//cancel order
        function cancelOrder(){

            var reason = $('#reason').val();
            var outlet_id = $('#outlet_id').val();
            var order_id = $('#modal_order_id').val();

            if( reason != '' ){

                swal({
                    title: "Are you sure?",
                    text: "Do you want to cancel this order?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, Cancel it!",
                    cancelButtonText: "No!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function (isConfirm) {
                    if (isConfirm) {
                        swal({
                            title : "Cancelled!",
                            text : "Your Order has been cancelled.",
                            type : "success"
                        },function() {
                            $.ajax({
                                url: '/cancelorder',
                                type: "POST",
                                data: {order_id: order_id, reason:reason,outlet_id:outlet_id},
                                dataType: 'json',
                                success: function (data) {

                                    if (data.status == 'success') {
                                        $('#cancelOrder').modal('hide');
                                        $("#show_btn").click();
                                    } else {
                                        successErrorMessage('there is some error ocurred plese try again later','error');
                                    }
                                }
                            });
                        });
                    } else {
                        swal("Ok!", "Your Order is safe :)", "error");
                    }
                });

            } else {
                $('#reason').css('border-color','red');
            }
        }

        function deleteOrder(order_id){

            var outlet_id = $('#outlet_id').val();
            var order_id = order_id;

            if( reason != '' ){

                swal({
                    title: "Are you sure?",
                    text: "Do you want to delete this order?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, Delete it!",
                    cancelButtonText: "No!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: '/deleteorder',
                            type: "POST",
                            dataType: 'json',
                            data: {order_id: order_id, outlet_id:outlet_id},
                            success: function (data) {

                                if (data.status == 'success') {
                                    successErrorMessage('Order is Deleted Successfully','success');
                                } else {
                                    successErrorMessage('There is some error occurred please try again later','error');
                                }
                            }
                        });
                        swal({
                            title : "Success!",
                            text : "Your Order has been Deleted.",
                            type : "success"
                        });
                        $("#show_btn").click();
                    } else {
                        swal("Ok!", "Your Order is safe :)", "error");
                    }
                });

            } else {
                $('#reason').css('border-color','red');
            }
        }

// change reason
        function changeReason(value) {
            $('#reason').text('');
            if ( value == 'Select Reaseon') {
                value = ''
            }

            $('#reason').css('border-color','');
            $('#reason').val(value);
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function edit_delivery_apply(flag) {

            if(flag == "apply"){
                var order_type = $('input[name="edit_order_type"]:checked').val();
                var new_delivery = $("#edit_new_delivery_value").val();
                var ord_id = $('#edit_order_id').val();
                var tax_name = $("#outlet_tax").val();
                var edit_disc_type = $("#edit_disc_type").val();
                var edit_disc_value = $("#edit_disc_value").val();

                getInvoiceNo(order_type,ord_id,'edit',tax_name,new_delivery,edit_disc_type,edit_disc_value);
            }else if(flag == "remove"){
                var order_type = $('input[name="edit_order_type"]:checked').val();
                var ord_id = $('#edit_order_id').val();
                var tax_name = $("#outlet_tax").val();;
                $("#edit_new_delivery_value").val("");
                var edit_disc_type = $("#edit_disc_type").val();
                var edit_disc_value = $("#edit_disc_value").val();

                getInvoiceNo(order_type,ord_id,'edit',tax_name,"0.00",edit_disc_type,edit_disc_value);

            }

        }
        
        function removeItemFromOrder(order_id,item_id) {

            swal({
                title: "Are you sure?",
                text: "You want to remove item from bill? It will not be restored",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Remove it!",
                cancelButtonText: "No!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: '/removeOrderItem',
                        type: "POST",
                        dataType: 'json',
                        data: {order_id: order_id, item_id:item_id},
                        success: function (data) {

                            if (data.status == 'success') {
                                successErrorMessage('Order item is Removed Successfully','success');
                                $('#editBill').modal('hide');
                                var delayInMilliseconds = 2000; //1 second

                                setTimeout(function() {
                                    editBill(order_id,'')
                                    //your code to be executed after 1 second
                                }, delayInMilliseconds);
                            } else {
                                successErrorMessage('There is some error occurred please try again later','error');
                            }
                        }
                    });
                    swal({
                        title : "Success!",
                        text : "Your Item has been Removed.",
                        type : "success"
                    });
                } else {
                    swal("Ok!", "Your Item is safe :)", "error");
                }
            });


        }

    </script>
@stop
