<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');
$from_session = Session::get('from_session');
$to_session = Session::get('to_session');
?>
@extends('partials.default')

@section('pageHeader-left')
    Sales & Consumption Report
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">
                    <input type="hidden" value="export" id="flag" name="flag"/>
                    @if( !isset($sess_outlet_id) || $sess_outlet_id == '')
                        <div class="col-md-4 form-group">
                            {!! Form::select('outlet_id', isset($outlets)?$outlets:array(),isset($outlet_id)?$outlet_id:null,array('class' => 'form-control','id'=>'outlet_id')) !!}
                        </div>
                        <div class="col-md-4 form-group">
                    @else
                        <div class="col-md-5 form-group">
                    @endif
                            {!! Form::select('report_type',array(''=>'Select Report Type','sales'=>'Sales','consumption'=>'Consumption','sales with consumption'=>'Sales with Consumption','consumption wih sales'=>'Consumption with Sales'),null,array('onchange'=>'removeError()','class' => 'form-control select2','id'=>'report_type')) !!}
                            <label id="report_type-error" class="error hide" for="report_type">Report Type is Required.</label>
                        </div>
                    <div class="form-group col-md-5">

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
                        <div id="data-list" style="overflow-x: auto;">
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>

@stop

@section('page-scripts')
    <script type="text/javascript">

        $(document).ready(function() {
            $('#outlet_id').select2({
                placeholder: 'Select Outlet'
            });
            $('#report_type').select2({
                placeholder: 'Select Report Type'
            });

        })

        function removeError() {
            $('#report_type-error').addClass('hide');
        }
        //get

        function getList() {

            var report_type = $('#report_type').val();
            if( report_type == '') {
                $('#report_type-error').removeClass('hide');
                return;
            }

            var from = $('#from_date').val();
            var to = $('#to_date').val();
            var outlet_id = $('#outlet_id').val();
            if(check30daysDiff(from,to)){
                return;
            }

            processBtn('show_btn','add','Showing...');
            $.ajax({
                url: '/sales-consumption-report',
                type: "post",
                data: { from_date:from,to_date:to,outlet_id:outlet_id,report_type:report_type},
                success: function (data) {
                    processBtn('show_btn','remove','Show');
                    $('#data-list').html(data);

                }
            });

        }


    </script>
@stop