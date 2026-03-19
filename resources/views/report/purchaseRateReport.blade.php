<?php

use Illuminate\Support\Facades\Session;
$from_session = Session::get('from_session');
$to_session = Session::get('to_session');
?>
@extends('partials.default')

@section('pageHeader-left')
    Purchase Rate Report
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">

                    <div class="col-md-4 form-group">
                        {!! Form::select('category_id', isset($categories)?$categories:array(),'all',array('class' => 'form-control','id'=>'category_id','onchange'=>'loadItems(this.value)')) !!}
                    </div>
                    <div class="col-md-4 form-group">
                        {!! Form::select('item_id', array(''=>'All Item'),null,array('class' => 'form-control','id'=>'item_id')) !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::checkbox('show_qty','1',true,['id'=>'show_qty']) !!} Show Qty
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success primary-btn" id="graph_btn" onclick="getList('graph')" >Show Graph</button>
                    </div>


                    <div class="col-md-8 form-group">
                        <div class="input-daterange input-group">
                            {!! Form::text('from_date', isset($from_session)?$from_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"from_date","readonly"=>"readonly"]) !!}
                            <span class="input-group-addon">to</span>
                            {!! Form::text('to_date', isset($to_session)?$to_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>
                    </div>

                    <div class="col-md-2">
                        {!! Form::checkbox('show_total','1',true,['id'=>'show_total']) !!} Show Total
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success primary-btn" id="stat_btn" onclick="getList('statistic')" >Show Statistics</button>
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
    <script type="text/javascript" src='/bower_components/morrisjs/morris.min.js'></script>
    <script type="text/javascript" src='/bower_components/raphael/raphael-min.js'></script>

    <script type="text/javascript">

        $(document).ready(function() {

            $('#category_id').select2({
                placeholder: 'All Categories'
            });
            $('#item_id').select2({
                placeholder: 'All Items'
            });

        });

        //get

        function getList(flag) {

            var from = $('#from_date').val();
            var to = $('#to_date').val();
            var cat_id = $('#category_id').val();
            var item_id = $('#item_id').val();
            var show_qty = false; var show_total = false;
            if(check30daysDiff(from,to)){
                return;
            }
            if ( $('#show_qty').is(':checked')){
                show_qty = true;
            }
            if ( $('#show_total').is(':checked')){
                show_total = true;
            }

            if ( cat_id == '' ) {
                alert('Please select category');
                return;
            }

            if ( flag == 'statistic') {
                processBtn('stat_btn','add','Loading...');
                processBtn('graph_btn','add','Show Graph');
            } else {
                processBtn('graph_btn','add','Loading...');
                processBtn('stat_btn','add','Show Statistics');
            }


            $.ajax({
                url: '/purchase-rate-report',
                type: "post",
                data: { from_date:from,to_date:to,cat_id:cat_id,item_id:item_id,show_qty:show_qty,show_total:show_total,'flag':flag},
                success: function (data) {


                    if ( flag == 'statistic') {

                        $('#data-list').html(data);
                        processBtn('stat_btn','remove','Show Statistics');
                        processBtn('graph_btn','remove','Show Graph');

                    } else {

                        processBtn('stat_btn','remove','Show Statistics');
                        processBtn('graph_btn','remove','Show Graph');
                        $('#data-list').html('');

                        /*display graph code*/
                        var line_arr = [];var item_arr=[];

                        data.dates.forEach(function (entry1) {
                            //console.log(data.records[entry][entry1]['rate']);
                            item_arr = {dates:entry1};
                            data.item_id.forEach(function (entry) {
                                if( data.records[entry][entry1]['rate'] == 'NA') {
                                    item_arr[entry] = 0;
                                } else {
                                    item_arr[entry] = data.records[entry][entry1]['rate'];
                                }
                            });
                            line_arr.push(item_arr);
                        });


                        var rate_graph = new Morris.Line({
                            element: 'data-list',
                            resize: true,
                            data: line_arr,
                            xkey: 'dates',
                            ykeys: data.item_id,
                            parseTime: false,
                            labels: data.item_name,
                            pointStrokeColors: ['red'],
                            behaveLikeLine: true,
                            gridLineColor: 'black',
                            gridTextColor: ['#000'],
                            hideHover: 'auto',
                            xLabelAngle: 30
                        });
                    }
                }
            });
        }

        function loadItems(id) {

            $.ajax({
                url: '/get-item-by-category',
                type: "post",
                data: { cat_id:id },
                success: function (data) {

                    $('#show_btn').attr('disabled',false);
                    $('#show_btn').text('Show');
                    if ( data ) {
                        $('#item_id option').remove();
                        $('#item_id').append(new Option('All Item', ''));
                        data.forEach(function (entry) {
                            $('#item_id').append(new Option(entry.item, entry.id));
                        });
                    }


                }
            });

        }


    </script>
@stop