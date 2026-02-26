<?php
use App\Owner;
?>
@extends('app')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="/requestItemProcess">Response</a>
                        <a href="/requestItem/create" style="float: right;">Request</a>
                        {{--<a href="javascript:history.back()">Response</a>--}}
                    </div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong>There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(Session::has('success'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        @if(Session::has('error'))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
                                {{ Session::get('error') }}
                            </div>

                        @endif

                        <div class="col-md-12 form-group" id="order_filter">
                            <form method="post" action="/orderslist">
                                <input type="hidden" value="export" id="flag" name="flag"/>
                                <div class="col-md-12">
                                    <div class="col-md-4 form-group">
                                        {!! Form::select('req_id', array('request'=>'Request','response'=>'Response'),isset($req_id)?$req_id:'request',array('class' => 'form-control','id'=>'req_id')) !!}
                                    </div>
                                    <div class="col-md-4 form-group">
                                        {!! Form::select('user_id', isset($users)?$users:array(),isset($user_id)?$user_id:'all',array('class' => 'form-control','id'=>'user_id')) !!}
                                    </div>
                                    <div class="col-md-4 form-group">
                                        {!! Form::select('location_id', isset($locations)?$locations:array(),isset($location_id)?$location_id:'all',array('class' => 'form-control','id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-4 form-group">
                                        {!! Form::text('from_date', \Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"from_date","readonly"=>"readonly"]) !!}
                                    </div>
                                    <div class="col-md-4 form-group">
                                        {!! Form::text('to_date', \Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"to_date","readonly"=>"readonly"]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-primary" id="show_btn" onclick="getList()" style="margin-left: 5px;">Show</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                            <div style="clear: both"></div>
                            <div class="col-md-12" id="loading_div"></div>

                            <div class="col-lg-12" id="data-list">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="dataTable_wrapper">
                                                    No records found.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <div class="col-md-2">
                            <button type="button"  onclick="printData()" id="check_print" class="btn btn-primary col-md-10" style="float: right; margin-right:0%; " >Print</button>
                        </div>
                        {!! Form::input('hidden','request_data' , isset($print_data) ? $print_data : null, ['disabled','class' => 'form-control','id'=>'request_data' ] ); !!}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>

        $(document).ready(function() {
            $('#CategoriesTable').DataTable({
                responsive: true,
                pageLength: 100
            });

            $('#from_date').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date,
                setdate:new Date

            });

            $('#to_date').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date,
                setdate:new Date
            });
        });

        function printData() {
            window.RequestData.requestData($('#request_data').val());
        }

        function getList() {

            var req_id = $('#req_id').val();
            var from = $('#from_date').val();
            var to = $('#to_date').val();
            var user_id = $('#user_id').val();
            var location_id = $('#location_id').val();

            $('#show_btn').attr('disabled',true);
            $('#show_btn').text('Loading...');
            $('#loading_div').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

            $.ajax({
                url: '/stock-request-report',
                type: "post",
                data: {request_type:req_id, from_date:from,to_date:to,user_id:user_id,location_id:location_id,request:'app'},
                success: function (data) {

                    $('#show_btn').attr('disabled',false);
                    $('#show_btn').text('Show');
                    $('#data-list').html(data);
                    $('#loading_div').empty();

                }
            });

        }

    </script>


@stop