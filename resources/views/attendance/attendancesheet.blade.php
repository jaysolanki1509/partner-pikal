<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');

?>
@extends('partials.default')
@section('pageHeader-left')
    Attendance Sheet
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">

                    <div class="col-md-12 attendance-sheet">
                        @if( isset($sess_outlet_id) && $sess_outlet_id != '')

                            <div class="form-group col-md-6">
                                <div class="input-daterange input-group">
                                    {!! Form::text('from_date', isset($fromdate)?$fromdate:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"from_date","readonly"=>"readonly"]) !!}
                                    <span class="input-group-addon">to</span>
                                    {!! Form::text('to_cate', isset($todate)?$todate:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"to_date","readonly"=>"readonly"]) !!}
                                </div>
                            </div>

                            <div class="col-md-3 form-group">
                                {!! Form::select('staff_id', $staff,null, ['class' => 'select2 form-control',"id"=>"staff_id"]) !!}
                            </div>

                            <div class="col-md-2">
                                <div class=" form-group">
                                    <button onclick="getdata()" name="show" class="btn btn-success primary-btn pull-left" id="show_btn" >Show</button>
                                </div>
                            </div>
                        @else
                            <h3 style="text-align: center; color: red;">Please select outlet.</h3>
                        @endif
                    </div>
                </div>

                    <div id="loading_div"></div>

                    <div class="attendance_data" style=" overflow: scroll">
                        No record found.
                    </div>
            </div>
        </div>
    </div>

    <div id="attendance-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Timings</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('page-scripts')
    <script src="/assets/js/new/lib/footable.all-min.js"></script>
    <script>
        $(document).ready(function() {

        });

        function getdata() {

            var from = $('#from_date').val();
            var to = $('#to_date').val();
            var staff_id = $('#staff_id').val();
            if(check30daysDiff(from,to)){
                return;
            }
            processBtn('show_btn','add','Showing...');
            $('#loading_div').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

            $.ajax({
                url:'/attendance-list',
                type:'POST',
                data:'staff_id='+staff_id+'&from_date='+from+'&to_date='+to,
                success:function(data) {

                    $('#loading_div').empty();

                    $('.attendance_data').html(data);
                    processBtn('show_btn','remove','Show');

                }
            })

        }

        function viewAttendanaceDetail(date,staff_id) {

            $('#attendance-modal').modal('show');
            $('#attendance-modal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

            $.ajax({
                url:'/attendance-detail',
                type:'POST',
                data:'staff_id='+staff_id+'&date='+date,
                success:function(data) {

                    $('#attendance-modal .modal-body').html(data);

                }
            })

        }

    </script>

@stop