<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');
$from_session = Session::get('from_session');
$to_session = Session::get('to_session');

?>
@extends('partials.default')
@section('pageHeader-left')
    Summary Report
@stop

@section('pageHeader-right')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix">
                    <form class='j-forms' method="POST" action="/ajax/summary_report">
                        <input type="hidden" value="export" id="flag" name="flag"/>
                        @if( !isset($sess_outlet_id) || $sess_outlet_id == '')
                            <div class="col-md-8 form-group">
                                {!! Form::select('outlet_id',$outlets,null,array('id' => 'outlet_id','class'=>'form-control' )) !!}
                            </div>
                        @endif
                        <div class="form-group col-md-8">

                            <div class="input-daterange input-group">
                                {!! Form::text('from_date', isset($from_session)?$from_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"from_date","readonly"=>"readonly"]) !!}
                                <span class="input-group-addon">to</span>
                                {!! Form::text('to_date', isset($to_session)?$to_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"to_date","readonly"=>"readonly"]) !!}
                            </div>

                        </div>

                        <div class="col-md-3">
                            <div class=" form-group">
                                <button type="submit" name="export_excel" class="btn btn-success primary-btn" id="export">Export Excel</button>
                                <button type="button" name="show" class="btn btn-success primary-btn" id="showbtn">Show</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div class="summary_report_table" style="overflow-x: auto;">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@stop

@section('page-scripts')

    <script src="/assets/js/new/lib/jquery.dataTables.js"></script>
    <script src="/assets/js/new/lib/dataTables.responsive.js"></script>
    <script src="/assets/js/new/lib/dataTables.tableTools.js"></script>
    <script src="/assets/js/new/lib/dataTables.bootstrap.js"></script>

    <script>
        $(document).ready(function() {
            $('#outlet_id').select2({
                placeholder: 'Select Outlet'
            });

            fetch_outlet_report("show");

            $(document).delegate("#showbtn","click",function(e){
                fetch_outlet_report("show")
            });

            /*$(document).delegate("#export","click",function(e){
                fetch_outlet_report("export")
            });*/

            function fetch_outlet_report(flag){
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
                            url: '/ajax/summary_report',
                            type: "POST",
                            data: {flag : flag,outlet_id : outlet_id,from_date : from_date,to_date : to_date},
                            success: function (data) {
                                if(data!=false){
                                    $('.summary_report_table').html(data);
                                    $('#reports').DataTable({
                                        responsive: true,
                                        pageLength: 100
                                    });
                                }
                                else
                                    alert('No details found of your outlet.');

                                processBtn('showbtn','remove','Show');
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