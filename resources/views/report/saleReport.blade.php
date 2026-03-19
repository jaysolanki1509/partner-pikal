<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');
$from_session = Session::get('from_session');
$to_session = Session::get('to_session');

?>
@extends('partials.default')
@section('pageHeader-left')
    Sales Report
@stop

@section('pageHeader-right')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">
                    <form class='j-forms'>
                        <input type="hidden" value="export" id="flag" name="flag"/>
                        <div class="form-group col-md-5">
                            <div class="input-daterange input-group">
                                {!! Form::text('from_date', isset($from_session)?$from_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"from_date","readonly"=>"readonly"]) !!}
                                <span class="input-group-addon">to</span>
                                {!! Form::text('to_date', isset($to_session)?$to_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"to_date","readonly"=>"readonly"]) !!}
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class=" form-group">
                                <button type="button" id="show_btn" onclick="fetch_sales_report()" name="show" class="btn btn-success primary-btn pull-left" id="showbtn" >Show</button>
                                <button type="submit" class="btn btn-success primary-btn pull-left hide" id="export_btn">Export Excel</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="widget-container">
                    <div class="widget-content">
                        <div class="sales_table" style="overflow-x: auto;">
                            Please select date.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('page-scripts')

    <script>
        $(document).ready(function() {

        });

        function fetch_sales_report(){

            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if(check30daysDiff(from_date,to_date)){
                return;
            }

            if(from_date>to_date){
                alert("From date must be greater then To date.")
            }else{

                processBtn('show_btn','add','Showing...');

                $.ajax({
                    url: '/sales-report',
                    type: "POST",
                    data: { from_date : from_date, to_date:to_date },
                    success: function (data) {

                        processBtn('show_btn','remove','Show');
                        $('.sales_table').html(data);

                        console.log('herer '+ $('.sales_table').find('#total_records').length);

                        if ( $('.sales_table').find('#total_records').length ) {
                            $('#export_btn').removeClass('hide');
                        } else {
                            $('#export_btn').addClass('hide');
                        }

                    }
                });
            }

        }

    </script>

@stop