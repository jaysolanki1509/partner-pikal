<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');
$from_session = Session::get('from_session');
$to_session = Session::get('to_session');

?>
@extends('partials.default')
@section('pageHeader-left')
    Zoho Unsync Orders Report
@stop

@section('pageHeader-right')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">

                    <div class="form-group col-md-6">
                        <div class="input-daterange input-group">
                            {!! Form::text('from_date', isset($from_session)?$from_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"from_date","readonly"=>"readonly"]) !!}
                            <span class="input-group-addon">to</span>
                            {!! Form::text('to_cate', isset($to_session)?$to_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class=" form-group">
                            <button type="button" name="show" onclick="fetch_outlet_report('show')" class="btn btn-success primary-btn pull-left" id="show_btn" >Show</button>
                            <button type="button" name="sync" onclick="fetch_outlet_report('sync')" class="btn btn-success primary-btn pull-left" id="sync_btn" >Sync</button>
                        </div>
                    </div>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div class="zoho_unsync_orders_table" style="overflow-x: auto;">
                            Please select filters.
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

            fetch_outlet_report('show');

        });

        function fetch_outlet_report(flag){

            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            if(check30daysDiff(from_date,to_date)){
                return;
            }

            if( from_date > to_date ){
                alert("From date must be greater then To date.")
            } else {

                if ( flag == 'show') {
                    processBtn('show_btn','add','Showing...');
                } else {
                    processBtn('sync_btn','add','Syncing...');
                }


                $.ajax({
                    url: '/zoho-unsync-orders',
                    type: "POST",
                    data: {from_date : from_date,to_date : to_date,flag:flag},
                    success: function (data) {

                        if( flag == 'show' ) {

                            $('.zoho_unsync_orders_table').html(data);
                            processBtn('show_btn','remove','Show');

                        } else {

                            $('#show_btn').click();
                            processBtn('sync_btn','remove','Sync');
                            successErrorMessage('Check error message for unsync orders','success');
                        }

                    }
                });
            }
        }

    </script>

@stop