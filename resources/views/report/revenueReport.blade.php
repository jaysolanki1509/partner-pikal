<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');
$from_session = Session::get('from_session');
$to_session = Session::get('to_session');
?>
@extends('partials.default')

@section('pageHeader-left')
    Revenue Report
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">
                    @if( !isset($sess_outlet_id) || $sess_outlet_id == '')
                        <div class="col-md-8 form-group">
                            {!! Form::select('outlet_id', isset($outlets)?$outlets:array(),isset($outlet_id)?$outlet_id:null,array('class' => 'form-control','id'=>'outlet_id')) !!}
                        </div>
                    @endif
                    <div class="form-group col-md-8">
                        <div class="input-daterange input-group">
                            {!! Form::text('from_date', isset($from_session)?$from_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"from_date","readonly"=>"readonly"]) !!}
                            <span class="input-group-addon">to</span>
                            {!! Form::text('to_date', isset($to_session)?$to_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>
                    </div>
                    <div class="col-md-2 form-group">
                        <button type="button" class="btn btn-success primary-btn pull-left" id="showbtn" onclick="getList()">Show</button>
                    </div>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div class="revenue_report_table" style="overflow-x: auto;">
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

            getList();
        })

        //get

        function getList() {

            var from = $('#from_date').val();
            var to = $('#to_date').val();
            var outlet_id = $('#outlet_id').val();
            var source = $('#source').prop("checked");
            if(check30daysDiff(from,to)){
                return;
            }
            if( outlet_id == '') {
                $('#outlet_id').select2('open');
                return;
            }

            processBtn('showbtn','add','Showing...');

            $.ajax({
                url: '/revenue-report',
                type: "post",
                data: { from_date:from,to_date:to,outlet_id:outlet_id,source:source},
                success: function (data) {

                    $('.revenue_report_table').html(data);
                    processBtn('showbtn','remove','Show');

                }
            });

        }


    </script>
@stop