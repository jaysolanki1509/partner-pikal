<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');
$from_session = Session::get('from_session');
$to_session = Session::get('to_session');

?>
@extends('partials.default')
@section('pageHeader-left')
Detailed Discount Report
@stop

@section('pageHeader-right')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">
                    @if( !isset($sess_outlet_id) || $sess_outlet_id == '')
                        <div class="col-md-3 form-group">
                            {!! Form::select('outlet_id',$outlets,null,array('id' => 'outlet_id','class'=>'form-control select2' )) !!}
                        </div>
                    @endif

                    <div class="form-group col-md-8">

                        <div class="input-daterange input-group">
                            {!! Form::text('from_date', isset($from_session)?$from_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"from_date","readonly"=>"readonly"]) !!}
                            <span class="input-group-addon">to</span>
                            {!! Form::text('to_cate', isset($to_session)?$to_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>

                    </div>

                    <div class="col-md-2">
                        <div class=" form-group">
                            <button name="show" class="btn btn-success primary-btn pull-left" id="showbtn" >Show </button>
                        </div>
                    </div>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div class="discount_report_table" style="overflow-x: auto;">
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

        $('#outlet_id').select2({
            placeholder: 'Select Outlet'
        });

        fetch_outlet_report();

        $(document).delegate("#showbtn","click",function(e){
            fetch_outlet_report()
        });

        function fetch_outlet_report(){

            var outlet_id = $('#outlet_id').val();

            if(outlet_id!=0){

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

                        url: '/ajax/detail_discount_report',
                        type: "POST",
                        data: {outlet_id : outlet_id,from_date : from_date,to_date : to_date},
                        success: function (data) {

                            processBtn('showbtn','remove','Show');

                            if(data!=false){
                                $('.discount_report_table').html(data);
                                /*$('#disc_table').DataTable({
                                    responsive: true,
                                    pageLength: 100
                                });*/
                            } else {
                                alert('No details found of your outlet.');
                            }

                        }
                    });
                }
            }else{
                alert("Please Select an Outlet");
            }
        }
    });

</script>

@stop