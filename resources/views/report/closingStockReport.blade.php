<?php

use Illuminate\Support\Facades\Session;
$from_session = Session::get('from_session');
$to_session = Session::get('to_session');
?>

@extends('partials.default')

@section('pageHeader-left')
    Closing Stock Report
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">
                    <div class="col-md-3 form-group">
                        {!! Form::select('location_id', isset($locations)?$locations:array(),'',array('onchange'=>'removeError()','class' => 'form-control select2','id'=>'location_id')) !!}
                        <label id="location_id-error" class="error hide" for="location_id">Location is Required.</label>
                    </div>
                    <div class="form-group col-md-6">

                        <div class="input-daterange input-group">
                            {!! Form::text('from_date', isset($from_session)?$from_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"from_date","readonly"=>"readonly"]) !!}
                            <span class="input-group-addon">to</span>
                            {!! Form::text('to_cate', isset($to_session)?$to_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>

                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success primary-btn pull-left" id="show_btn" onclick="getList()">Show</button>
                    </div>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div id="data-list" style="overflow-x: auto;">
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
            $('#location_id').select2({
                placeholder: 'Select Location'
            });
        });

        function removeError() {
            $('#location_id-error').addClass('hide');
        }

        function getList() {

            var from = $('#from_date').val();
            var to = $('#to_date').val();
            var location_id = $('#location_id').val();
            if( location_id == '') {
                $('#location_id-error').removeClass('hide');
                //$('#location_id').select2('open');
                return;
            }
            if(check30daysDiff(from,to)){
                return;
            }

            processBtn('show_btn','add','Showing...');

            $.ajax({
                url: '/closing-stock-report',
                type: "post",
                data: { from_date:from,to_date:to,location_id:location_id},
                success: function (data) {

                    processBtn('show_btn','remove','Show');
                    $('#data-list').html(data);

                }
            });

        }


    </script>
@stop