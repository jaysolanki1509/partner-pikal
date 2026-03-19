<?php

use Illuminate\Support\Facades\Session;

$from_session = Session::get('from_session');
$to_session = Session::get('to_session');
?>
@extends('partials.default')

@section('pageHeader-left')
    Duplicate Invoice No. Report
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">

                    <div class="col-md-3 form-group">
                        {!! Form::select('outlet_id', isset($outlets)?$outlets:array(),isset($outlet_id)?$outlet_id:'all',array('class' => 'form-control select2','id'=>'outlet_id')) !!}
                    </div>

                    <div class="col-md-6">
                        <div class="input-daterange input-group">
                            {!! Form::text('from_date',isset($from_session)?$from_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control','placeholder'=>"Select Date","id"=>"from_date","readonly"=>"readonly"]) !!}
                            <span class="input-group-addon">to</span>
                            {!! Form::text('to_date', isset($to_session)?$to_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control','placeholder'=>"Select Date","id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>
                    </div>

                    <div class="col-md-2" >
                        <div class=" form-group">
                            <button name="show" class="btn btn-success primary-btn pull-left" onclick="getList()" id="showbtn">Show </button>
                        </div>
                    </div>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div class="dataTable_wrapper" id="data-list" style="overflow-x: auto;">
                            No records found.
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

        });

        //get

        function getList() {

            var from = $('#from_date').val();
            var to = $('#to_date').val();
            var outlet_id = $('#outlet_id').val();
            if(check30daysDiff(from,to)){
                return;
            }
            //processBtn('showbtn','add','Showing...');

            $.ajax({
                url: '/duplicate-invoiceno-report',
                type: "post",
                data: { from_date:from,to_date:to,outlet_id:outlet_id },
                success: function (data) {
                    $('#loading_div').empty();
                    $('#data-list').html(data);
                    processBtn('showbtn','remove','Show');
                }
            });

        }


    </script>
@stop