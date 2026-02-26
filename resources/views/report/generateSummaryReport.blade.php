<?php

?>
@extends('partials.default')
@section('pageHeader-left')
    Generate Summary Report
@stop

@section('pageHeader-right')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">

                    <div class="form-group col-md-3">
                        <div class="form-group">
                            <select class="form-control" id="outlet_id">
                                <option value="all">All</option>
                                @foreach( $outlets as $ot )
                                    <option value="{{ $ot->id }}">{{ $ot->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <div class="form-group">
                            {!! Form::text('from_date',\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"from_date","readonly"=>"readonly"]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class=" form-group">
                            <label class="checkbox">
                                {!! Form::checkbox('send_report','1',false,array('id'=>'send_report')) !!}
                                <i></i>Send Report
                            </label>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class=" form-group">
                            <button type="button" id="show_btn" onclick="generate_report()" name="show" class="btn btn-success primary-btn pull-left" id="showbtn" >Show</button>
                        </div>
                    </div>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div class="generate_summary_div" style="overflow-x: auto;">
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

            $('#from_date').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });

            $('#outlet_id').select2();

        });


        function generate_report(){

            var from_date = $('#from_date').val();
            var send_report = $('#send_report:checked').val();
            var outlet_id = $('#outlet_id').val();

            processBtn('show_btn','add','Showing...');

            $.ajax({
                url: '/generate-summary-report',
                type: "POST",
                data: { from_date : from_date, send_report:send_report,outlet_id:outlet_id },
                success: function (data) {

                    processBtn('show_btn','remove','Show');

                    $('.generate_summary_div').html(data);

                }
            });


        }

    </script>

@stop