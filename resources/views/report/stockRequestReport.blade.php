<?php

use Illuminate\Support\Facades\Session;
$from_session = Session::get('from_session');
$to_session = Session::get('to_session');
?>
@extends('partials.default')

@section('pageHeader-left')
    Stock Request Report
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">

                    <div class="col-md-3 form-group">
                        {!! Form::select('user_id', isset($users)?$users:array(),isset($user_id)?$user_id:'all',array('class' => 'form-control select2','id'=>'user_id')) !!}
                    </div>
                    <div class="col-md-3 form-group">
                        {!! Form::select('location_id', isset($locations)?$locations:array(),isset($location_id)?$location_id:'all',array('class' => 'form-control select2','id'=>'location_id')) !!}
                    </div>

                    <div class="form-group col-md-4">

                        <div class="input-daterange input-group">
                            {!! Form::text('from_date', isset($from_session)?$from_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"from_date","readonly"=>"readonly"]) !!}
                            <span class="input-group-addon">to</span>
                            {!! Form::text('to_cate', isset($to_session)?$to_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success primary-btn pull-left" id="show_btn" onclick="getList()" >Show</button>
                    </div>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div class="report_table" style="overflow-x: auto;">
                            No record found.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="process-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Process Request</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="ProcessRequest()">Process</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Process Modal-->
    <div id="requestModel" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Satisfy Request</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-6">
                        Requested Item: <label id="lbl_itemname"></label>
                    </div>
                    <div class="form-group col-md-6">
                        Requested Qty: <label id="lbl_qty"></label>
                    </div>
                    <div style="clear: both;"></div>
                    <input type="hidden" id="modal_request_id" value=""/>
                    <input type="hidden" id="item_id" value=""/>
                    <div class="form-group col-md-12">
                        <select id="location" class="col-md-3 form-control" onchange="changeqty(this.value)">
                            <option value="" selected>Select Location</option>
                            @foreach($locations as $key=>$val)
                                @if($key!='all')
                                    <option value="{!! $key !!}">{!! $val !!}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        Existing qty: <label id="lbl_existing_qty"></label>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="number" placeholder="Satisfy Quantity" class="form-control" min="1" id="satisfy_qty">
                    </div>
                    <div style="clear: both;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="satisfyRequest()">Satisfy Request</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@stop

@section('page-scripts')
    <script type="text/javascript">

        $(document).ready(function() {
            $('#user_id').select2({
                placeholder: 'All Users'
            });
            $('#location_id').select2({
                placeholder: 'All Locations'
            });

        });

        //get

        function getList() {

            var from = $('#from_date').val();
            var to = $('#to_date').val();
            var user_id = $('#user_id').val();
            var location_id = $('#location_id').val();
            if(check30daysDiff(from,to)){
                return;
            }
            processBtn('show_btn','add','Showing...');

            $.ajax({
                url: '/stock-request-report',
                type: "post",
                data: { request_type:'request',from_date:from,to_date:to,user_id:user_id,location_id:location_id,request:'web'},
                success: function (data) {
                    processBtn('show_btn','remove','Show');
                    $('.report_table').html(data);
                }
            });

        }

        function showDetail(el,item_id,flag) {

            var req_id = $(el).closest('tr').attr('id');
            var location_id = $('#process-modal #location_id').val();
            var item_name = $(el).closest('tr').find('td:eq(3)').text();
            var req_qty = $(el).closest('tr').find('td:eq(4)').text();

            if ( flag == 'open') {
                $('#process-modal').modal('show');
                $('#process-modal tbody').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');
            } else {
                $('#process-modal .modal-body #stock-table').html('<div class="loading" style="text-align:center;"><img src="/loader.gif" /></div>');
            }

            $.ajax({
                url:'/requestprocessitemstockdetail',
                type:'POST',
                data:'req_id='+req_id+'&location_id='+location_id+'&item_id='+item_id+'&item_name='+item_name+'&req_qty='+req_qty+'&flag='+flag
                ,
                success:function(data){

                    if ( flag == 'open') {
                        $('#process-modal .modal-body').html(data);
                    } else {
                        $('#process-modal .modal-body #stock-table').html(data);
                        if ( $('#process-modal .modal-body #stock-table table').length > 0) {
                            $('#process-modal #process-btn').removeClass('hide');
                        } else {
                            $('#process-modal #process-btn').addClass('hide');
                        }
                    }

                }
            });

        }

        function satisfyClick(el) {
            var request_id = $(el).data('requestid');
            var itemname = $(el).data('itemname');
            var itemid = $(el).data('itemid');
            var qty = $(el).data('qty');
            $('#location').val('');
            $("#modal_request_id").val(request_id);
            $("#lbl_itemname").text(itemname);
            $("#lbl_qty").text(qty);
            $("#item_id").val(itemid);
        }

        function ProcessRequest() {

            $('#stockprocess_error').text('');
            var tot_satisfy = 0;
            var error = 0;
            $('#stockitemprocess tr').each(function() {
                var req_qty = parseFloat($(this).find('td:eq(1)').text());
                var satisfy_qty = $(this).find('td:eq(3)').find('input').val();
                if(req_qty>0)
                    tot_satisfy += parseFloat(satisfy_qty);
                if(req_qty < satisfy_qty){
                    error = 1;
                    $(this).find('td:eq(3)').find('input').css('border-color','red');
                }else{
                    $(this).find('td:eq(3)').find('input').css('border-color','');
                }

            });

            if( error == 1 ){

                $('#stockprocess_error').text('Satisfy Qty must be less or eqal to Requested Qty.');
                return;

            } else {

                if( parseFloat($('#req_qty').text()) < tot_satisfy ){
                    $('#stockprocess_error').text('Satisfy Qty must be less or eqal to Requested Qty.');
                    return;
                } else {
                    $('#stockprocess_error').text('');
                }

            }

            var data = $('#stock-detail-form').serialize();
            $.ajax({
                url:'/requestprocessitemstockdetail',
                type:'POST',
                data:'flag=process&'+data,
                dataType:'json',
                success:function(data){

                    if (data.status == 'success') {
                        alert(data.msg);
                        $('#process-modal').modal('hide');
                        location.reload();
                    } else {
                        $('#stockprocess_error').text(data.msg);
                    }

                }
            })
        }


        function changeqty(location){

            var request_id = $('#modal_request_id').val();
            var item_id = $('#item_id').val();

            //if ( location.val() != '' ) {
            $.ajax({
                url: '/getAvailableStock',
                type: "POST",
                data: {request_id: request_id, item_id:item_id, location_id:location},
                dataType: 'json',
                success: function (data) {

                    if (data > 0) {
                        $('#requestModel').modal('hide');
                        location.reload();
                    } else {
                        alert('there is some error ocurred plese try again later.');
                    }
                }
            });
            //}
            //else {
            //$('#location_id').css('border-color','red');
            // }
        }

        function satisfyRequest() {
            var reason = $('#reason').val();
            var satisfy_qty = $('#satisfy_qty').val();
            var request_id = $('#modal_request_id').val();

            if(location != '' ){

                var check = confirm("Do you want to Satisfy this Request?");

                if ( check ) {
                    $.ajax({
                        url: '/request-satisfy',
                        type: "POST",
                        data: {request_id: request_id, qty:satisfy_qty, loaction_id:location},
                        dataType: 'json',
                        success: function (data) {

                            if (data.status == 'success') {
                                $('#requestModel').modal('hide');
                                location.reload();
                            } else {
                                alert('there is some error ocurred plese try again later.');
                            }
                        }
                    });
                }

            } else {
                $('#reason').css('border-color','red');
            }

        }

    </script>
@stop