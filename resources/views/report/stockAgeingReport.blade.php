@extends('partials.default')

@section('pageHeader-left')
    Stock Ageing Report
@stop

@section('content')

    <div class="form-group" id="purchase_rate_filter">
        <div class="col-md-10">

            <div class="col-md-6 form-group">
                {!! Form::select('location_id', isset($locations)?$locations:array(),'all',array('class' => 'form-control','id'=>'location_id','onchange'=>'loadItems(this.value)')) !!}
            </div>
            <div class="col-md-6 form-group">
                {!! Form::select('item_id', isset($items)?$items:array(),'all',array('class' => 'form-control','id'=>'item_id')) !!}
            </div>

            <div class="col-md-6 form-group">
                {!! Form::text('from_date', \Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"from_date","readonly"=>"readonly"]) !!}
            </div>
            <div class="col-md-6 form-group">
                {!! Form::text('to_date', \Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"to_date","readonly"=>"readonly"]) !!}
            </div>

        </div>
        <div class="col-md-2" style="top:50px;">
            <button type="button" class="btn btn-primary" id="stat_btn" onclick="getList()">Show</button>
        </div>

    </div>
    <div style="clear: both"></div>
    <hr>
    <div class="col-md-12" id="loading_div"></div>

    <div class="col-lg-12" id="data-list">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="dataTable_wrapper" id="data-div" style="width:100%;overflow-y: auto;_overflow: auto;">
                            Select filters for result.
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
        })

        //get

        function getList(flag) {

            var from = $('#from_date').val();
            var to = $('#to_date').val();
            var cat_id = $('#category_id').val();
            var item_id = $('#item_id').val();
            var show_qty = false; var show_total = false;
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
                $('#stat_btn').attr('disabled',true);
                $('#graph_btn').attr('disabled',true);
                $('#stat_btn').text('Loading...');
            } else {
                $('#stat_btn').attr('disabled',true);
                $('#graph_btn').attr('disabled',true);
                $('#graph_btn').text('Loading...');
            }

            $('#loading_div').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

            $.ajax({
                url: '/purchase-rate-report',
                type: "post",
                data: { from_date:from,to_date:to,cat_id:cat_id,item_id:item_id,show_qty:show_qty,show_total:show_total,'flag':flag},
                success: function (data) {

                    $('#loading_div').empty();
                    if ( flag == 'statistic') {

                        $('#stat_btn').attr('disabled',false);
                        $('#graph_btn').attr('disabled',false);
                        $('#stat_btn').text('Show Statistics');

                        $('.dataTable_wrapper').html(data);

                    } else {

                        $('#stat_btn').attr('disabled',false);
                        $('#graph_btn').attr('disabled',false);
                        $('#graph_btn').text('Show Graph');
                        $('.dataTable_wrapper').html('');

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
                            element: 'data-div',
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