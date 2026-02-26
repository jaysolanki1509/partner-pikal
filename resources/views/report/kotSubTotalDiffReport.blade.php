<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');
$from_session = Session::get('from_session');
$to_session = Session::get('to_session');

?>
@extends('partials.default')
@section('pageHeader-left')
    KOT vs Order Difference Report
@stop

@section('pageHeader-right')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">


                    <div class="col-md-4 form-group">
                        {!! Form::select('outlet_id',$outlets,null,array('id' => 'outlet_id','style'=>'width:100%;','class'=>'form-control' )) !!}
                    </div>

                    <div class="form-group col-md-6">
                        <div class="input-daterange input-group">
                            {!! Form::text('from_date', isset($from_session)?$from_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"from_date","readonly"=>"readonly"]) !!}
                            <span class="input-group-addon">to</span>
                            {!! Form::text('to_cate', isset($to_session)?$to_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>
                    </div>
                    <div class="col-md-2 form-group">
                        <button name="show" class="btn btn-success primary-btn pull-left" id="showbtn">Show</button>
                    </div>
                </div>

                <div class="widget-container">
                    <div class="widget-content">
                        <div class="kotsubtotaldiff_report_table" style="overflow-x: auto;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('page-styles')
    <style>
        .dataTable_wrapper {
            overflow-x:scroll;
        }
    </style>
@stop

@section('page-scripts')

    <script>
        $(document).ready(function() {
            $('#outlet_id').select2({
                placeholder: 'Select Outlet'
            });

            $(document).delegate("#showbtn","click",function(e){
                fetch_outlet_report()
            });

            function fetch_outlet_report(){
                var outlet_id = $('#outlet_id').val();
                if(outlet_id!=""){
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    if(check30daysDiff(from_date,to_date)){
                        return;
                    }
                    if(from_date>to_date){
                        alert("From date must be greater then To date.")
                    }else{
                        processBtn('showbtn','add','Showing...');
                        $.ajax({
                            url: '/kot-vs-orders-diff',
                            type: "POST",
                            data: {outlet_id : outlet_id,from_date : from_date,to_date : to_date},
                            success: function (data) {
                                if(data!=false){
                                    $('.kotsubtotaldiff_report_table').html(data);
                                }
                                else
                                    alert('No details found of your outlet.');
                                processBtn('showbtn','remove','Show');
                            }
                        });
                    }
                }else{
                    swal({
                        title: "Caution!",
                        text: "Select Outlet.",
                        type: "warning",
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "ok.",
                        closeOnConfirm: true
                    });
                }
            }

        });

    </script>

@stop