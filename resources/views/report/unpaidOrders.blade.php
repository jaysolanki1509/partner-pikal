<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');
$from_session = Session::get('from_session');
$to_session = Session::get('to_session');

?>
@extends('partials.default')
@section('pageHeader-left')
    Unpaid Orders
@stop

@section('pageHeader-right')
    <a href="/orderslist" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">

                    <div class="form-group col-md-5">
                        <div class="input-daterange input-group">
                            {!! Form::text('from_date', isset($from_session)?$from_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"from_date","readonly"=>"readonly"]) !!}
                            <span class="input-group-addon">to</span>
                            {!! Form::text('to_date', isset($to_session)?$to_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class=" form-group">
                            {!! Form::text('mobile', null, ['class' => 'form-control',"id"=>"mobile",'placeholder'=>'Mobile No.']) !!}
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class=" form-group">
                            <select class="form-control" id="table_no">
                                <option value=""> Select {{ $lable }}</option>
                                @if ( isset($tables) && sizeof($tables) > 0 )
                                    @foreach ( $tables as $tab )
                                        <option value="{{ $tab->table_no }}">{{ $tab->table_no }}</option>
                                    @endforeach
                                @endif

                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class=" form-group">
                            <button type="button" id="show_btn" onclick="fetch_outlet_report()" name="show" class="btn btn-success primary-btn pull-left" id="showbtn" >Show</button>
                        </div>
                    </div>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div class="unpaid_orders_table" style="overflow-x: auto;">
                            Please select filters.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--print modal--}}
    <div id="printModal" data-backdrop="static" data-keyboard="false" class="modal fade" role="dialog" >
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Invoice</h4>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="" id="close_type" />
                    <button type="button" class="btn btn-primary" onclick="print()">Print</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    {{--print modal--}}
    <div id="paidModal" data-backdrop="static" data-keyboard="false" class="modal fade" role="dialog" >
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Paid</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="" id="unpaid_order_id" name="unpaid_order_id">
                    <div class="form-group">
                        <div class="col-md-12">
                            <lable class="form-label bold">Notes</lable>
                        </div>
                        <div class="col-md-12">
                            <textarea name="note" id="note" rows="2" class="form-control" placeholder="Note"></textarea>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                    <div id="payment_modes_div">

                    </div>

                    <div style="clear: both"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="" id="close_type" />
                    <button type="button" id="add_payment_mode_btn" class="btn btn-primary" onclick="addPaymentMode('#paidModal')"><i class="fa fa-plus"></i> Payment Mode</button>
                    <button type="button" class="btn btn-primary" onclick="paidOrder(this,'','paid')">Paid</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

        @stop

        @section('page-scripts')
            <script src="/assets/js/new/lib/payment-modes.js"></script>
            <script>
                $(document).ready(function() {
                    $('#table_no').select2();
                    $('#payment_option_id').select2();
                });

                //paid order
                function paidOrder( ele,order_id, flag ) {

                    if ( flag == 'open') {

                        $('#unpaid_order_id').val('');
                        $('#note').val('');
                        $('#payment_option_id').val(0).change();
                        $('#payment_modes_div').html('<span style="text-align: center">Loading...</span>');

                        $.ajax({
                            url: '/get-order-payment-modes',
                            type: "Post",
                            data: {order_id : order_id},
                            success: function (data) {
                                $('#payment_modes_div').html(data);
                                $('.paid-type').select2();
                            }

                        });

                        $('#paidModal').modal('show');
                        $('#unpaid_order_id').val(order_id);

                    } else {

                        var order_id = $('#unpaid_order_id').val();
                        var note = $('#note').val();
                        var source = $('#payment_option_id').find(':selected').data('source');
                        var payment_option_id = $('#payment_option_id').val();
                        var total = $('#paidModal #bill_amount').text();

                        //check payment mode amount
                        var payment_mode_total = 0;
                        $('#paidModal .paid-value').each(function () {
                            payment_mode_total += parseFloat($(this).val());
                        })

                        if ( payment_mode_total < parseFloat(total) ) {

                            $('#paidModal .paid-value').css('border-color','red');
                            successErrorMessage('Payment mode amount total can not be less than bill amount.','error');
                            return;

                        } else if ( payment_mode_total > parseFloat(total) ) {

                            $('#paidModal .paid-value').css('border-color','red');
                            successErrorMessage('Payment mode amount total can not be greater than bill amount','error');
                            return;
                        }

                        //get payment modes value
                        var py_source_ids = []; var py_option_ids = []; var py_amount =[];var trn_ids = [];

                        var check_repeat_mode = 0;
                        var modes_count = $('#paidModal .paid-value').length;
                        for( var i=0; i< modes_count; i++ ) {

                            //check repeat payment mode
                            if ( py_option_ids.length > 0 ) {

                                for ( j=0; j< py_option_ids.length; j++ ) {

                                    var check_py_option_id = 0;var check_source_id = 0;

                                    if ( $('#paidModal .paid-type').eq(i).val() == py_option_ids[j] ) {
                                        check_py_option_id = 1;
                                    }

                                    if ( $('#paidModal .paid-type').eq(i).find(':selected').data('source') == py_source_ids[j] ) {
                                        check_source_id = 1;
                                    }

                                    if ( check_py_option_id == 1 && check_source_id == 1 ) {
                                        check_repeat_mode = 1;
                                    }
                                }

                            }

                            py_option_ids.push($('#paidModal .paid-type').eq(i).val());
                            py_source_ids.push($('#paidModal .paid-type').eq(i).find(':selected').data('source'));
                            py_amount.push($('#paidModal .paid-value').eq(i).val());
                            trn_ids.push($('#paidModal .transaction-id').eq(i).val());

                        }

                        if ( check_repeat_mode == 1 ) {
                            successErrorMessage('Same Payment mode cant be add multiple time','error');
                            return;
                        }

                        $(ele).text('processing');
                        $(ele).addClass('processing');
                        $(ele).addClass('processing');
                        $(ele).prop('disabled',true);

                        $.ajax({
                            url: '/paid-order',
                            type: "Post",
                            data: {
                                    order_id : order_id,
                                    note : note,
                                    payment_option_id: payment_option_id,
                                    source: source,
                                    payment_option_ids:py_option_ids,
                                    source_ids:py_source_ids,
                                    payment_mode_amount:py_amount,
                                    trn_ids:trn_ids
                            },
                            success: function (data) {

                                $(ele).text('Paid');
                                $(ele).prop('disabled',false);
                                $(ele).removeClass('processing');

                                if ( data == 'success') {

                                    $('#paidModal').modal('hide');
                                    $('#show_btn').click();
                                    successErrorMessage('Order has been paid successfully.','success');

                                } else {

                                    successErrorMessage('There is some error occured. Please try again later','error');
                                    $('#paidModal').modal('hide');

                                }

                            },
                            error:function(error) {

                                $(ele).text('Paid');
                                $(ele).prop('disabled',false);
                                $(ele).removeClass('processing');

                                successErrorMessage('There is some error ocurred','error');
                            }

                        });

                    }



                }

                //open order to view
                function openOrderView( order_id ) {

                    $('#printModal').modal('show');
                    $('#printModal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

                    $.ajax({
                        url: '/printorder',
                        type: "GET",
                        data: {order_id : order_id},
                        success: function (data) {
                            $('#printModal .modal-body').html(data);
                        },
                        error:function(error) {
                            successErrorMessage('There is some error ocurred','error');
                        }

                    });

                }

                function fetch_outlet_report(){

                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    var mobile = $('#mobile').val();
                    var table_no = $('#table_no').val();

                    if(check30daysDiff(from_date,to_date)){
                        return;
                    }
                    if(from_date>to_date){
                        alert("From date must be greater then To date.")
                    }else{

                        processBtn('show_btn','add','Showing...');

                        $.ajax({
                            url: '/unpaid-orders',
                            type: "POST",
                            data: { from_date : from_date, to_date:to_date, mobile:mobile, table_no:table_no },
                            success: function (data) {

                                processBtn('show_btn','remove','Show');

                                if(data!=false){

                                    $('.unpaid_orders_table').html(data);

                                } else {
                                    alert('No details found of your outlet.');
                                }

                            }
                        });
                    }

                }

            </script>

@stop