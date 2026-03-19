<?php
use App\Owner;
?>
@extends('partials.default')
@section('pageHeader-left')
    Edit Processed Request
@stop
@section('pageHeader-right')
    <a href="/responseItems/setisfiedResponse" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop
@section('content')

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    <form class="form-horizontal material-form j-forms" role="form" method="POST" id="requestItemProcessForm" novalidate="novalidate" action="{{ url('/responseUpdate') }}" files="true" enctype="multipart/form-data">
                        {!!Form::hidden('request_id',$request_id,array('id'=>'request_id'))!!}

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="col-md-12 control-label"><h3>Request From</h3></label>
                                </div>
                            </div>
                        <div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="col-md-12 control-label">Item</label>
                                    <div class="col-md-12">
                                        <select id="item_id" class="form-control select2" name="item_id">
                                            @if(isset($selected_item) && sizeof($selected_item)>0 )
                                                <option value="" >Select Item</option>
                                                @foreach($items as $id=>$item)
                                                    @if($selected_item == $item['id'])
                                                        <option value="{{$item['id']}}" selected>{{$item['item']}}</option>
                                                    @else
                                                        <option value="{{$item['id']}}">{{$item['item']}}</option>
                                                    @endif
                                                @endforeach
                                            @else
                                                <option value="" selected>Select Item</option>
                                                @foreach($process['items'] as $id=>$item)
                                                    <option value="{{$item['id']}}">{{$item['item']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="col-md-12 control-label">Qty</label>
                                    <div class="col-md-12">
                                        {!!Form::text('requested_qty',$process['req_qty'],array('placeholder'=>'Request Qty','class'=>'form-control','id'=>'requested_qty'))!!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-md-12 control-label">Unit</label>
                                    <div class="col-md-12">
                                        {!!Form::select('req_unit',$units,$req_unit,array('class'=>'select2 form-control','id'=>'req_unit'))!!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="col-md-12 control-label">Date</label>
                                    <div class="col-md-12">
                                        {!!Form::text('requested_date',$process['req_date'],array('placeholder'=>'Request Date','class'=>'form-control','id'=>'requested_date','readonly','style'=>'background-color:white;cursor:pointer;'))!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="col-md-12 control-label">Request to</label>
                                    <div class="col-md-12">
                                        {!!Form::select('req_owner_to_id',$owners,$process['requested_user_to_id'],array('class'=>'select2 form-control','id'=>'req_owner_to_id'))!!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-md-12 control-label">Request by</label>
                                    <div class="col-md-12">
                                        {!!Form::select('req_owner_by_id',$owners,$process['requested_user_by_id'],array('class'=>'select2 form-control','id'=>'req_owner_by_id'))!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="col-md-12 control-label">Location</label>
                                        <div class="col-md-12">
                                            {!!Form::select('req_location_id',$locations,$location_for,array('class'=>'select2 form-control','id'=>'req_location_id'))!!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="col-md-12 control-label"><h3>Response From</h3></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="col-md-12 control-label">Response by</label>
                                    <div class="col-md-12">
                                        {!!Form::select('res_owner_id',$owners,$process['responsed_user_id'],array('class'=>'select2 form-control','id'=>'res_owner_id'))!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                        <label class="col-md-12 control-label">Location</label>
                                        <div class="col-md-12">
                                            {!!Form::select('res_location_id',$locations,$location_from,array('class'=>'select2 form-control','id'=>'res_location_id'))!!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="col-md-12 control-label">Date</label>
                                    <div class="col-md-6">
                                        {!!Form::text('satisfied_date',$process['res_date'],array('placeholder'=>'Satisfied Date','class'=>'form-control','id'=>'satisfied_date','readonly','style'=>'background-color:white;cursor:pointer;'))!!}
                                    </div>
                                    <div class="col-md-6 input-append bootstrap-timepicker ">
                                        <input name="time" id="time" value="{{$process['res_time']}}" class="timepicker form-control add-on" type="text"  style="margin-right:4px; display: inline;">
                                        <span class="add-on"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="col-md-12 control-label">Price</label>
                                    <div class="col-md-12">
                                        {!!Form::text('price',$process['price'],array('id'=>'price','placeholder'=>'Request Qty','class'=>'form-control'))!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="col-md-12 control-label">Qty</label>
                                    <div class="col-md-12 form">
                                        {!!Form::text('responsed_qty',$process['res_qty'],array('placeholder'=>'Requested Qty','class'=>'form-control','id'=>'responsed_qty'))!!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-md-12 control-label">Unit</label>
                                    <div class="col-md-12 form">
                                        {!!Form::select('res_unit',$units,$req_unit,array('class'=>'select2 form-control','id'=>'res_unit'))!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-footer">
                            <div class="col-md-8">
                                <button type="submit" id="Submit" onclick="return validateForm();" novalidate="novalidate" class="btn primary-btn btn-success">Update</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@stop
@section('page-scripts')
    <script>

        $(document).ready(function() {

            $('#satisfied_date').DatePicker({
                format:"y-m-d"
            });

            $('#time').timepicker({
                format: 'HH:mm:ss',
                minuteStep: 1,
                pick12HourFormat: false,
                showMeridian: false
            });

            $('#requested_date').DatePicker({
                format:"y-m-d"
            });

            $('.locations').change(function() {
                var selected = $(this).find('option:selected');
                $(this).parent().next().text(selected.data('qty'));

            });

            $("#requestItemProcessForm").validate({
                ignore: [],
                rules: {
                    "item_id": {
                        required: true
                    },
                    "requested_qty":{
                        required: true
                    },
                    "req_unit":{
                        required: true
                    },
                    "requested_date":{
                        required:true
                    },
                    "req_owner_to_id":{
                        required: true
                    },
                    "req_owner_by_id":{
                        required: true
                    },
                    "req_location_id":{
                        required: true
                    },
                    "res_owner_id":{
                        required: true
                    },
                    "res_location_id":{
                        required: true
                    },
                    "satisfied_date":{
                        required: true
                    },
                    "price":{
                        required: true
                    },
                    "responsed_qty":{
                        required: true
                    },
                    "res_unit":{
                        required: true
                    }
                },
                messages: {

                    "item_id": {
                        required: "Select Item required"
                    },
                    "requested_qty": {
                        required: "Requested Qty is required"
                    },
                    "req_unit": {
                        required: "Requested Unit is required"
                    },
                    'requested_date':{
                        required: "Requested Date is required"
                    },
                    "req_owner_to_id": {
                        required: "Requested User to is required"
                    },
                     "req_owner_by_id": {
                        required: "Requested User by is required"
                    },
                     "req_location_id": {
                        required: "Requested Location is required"
                    },
                     "res_owner_id": {
                        required: "Response User is required"
                    },
                     "res_location_id": {
                        required: "Response Location is required"
                    },
                     "satisfied_date": {
                        required: "Satisfied Date is required"
                    },
                     "price": {
                        required: "Price is required"
                    },
                     "responsed_qty": {
                        required: "Response Qty is required"
                    },
                     "res_unit": {
                        required: "Response Unit is required"
                    }
                }
            });

        });
    </script>


@stop