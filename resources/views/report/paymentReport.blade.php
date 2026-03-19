@extends('partials.default')

@section('pageHeader-left')
    Payment Report
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">

                    <div class="col-md-4 form-group">
                        {!! Form::select('outlet_id', isset($outlets)?$outlets:array(),'all',array('class' => 'select2 form-control','id'=>'outlet_id')) !!}
                    </div>

                    <div class="col-md-6">
                        <div class="input-daterange input-group">
                            {!! Form::text('from_date', \Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"from_date","readonly"=>"readonly"]) !!}
                            <span class="input-group-addon">to</span>
                            {!! Form::text('to_date', \Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control from_date','placeholder'=>"Select Date","id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn primary-btn btn-success" id="show_btn" onclick="getList()" >Show</button>
                    </div>

                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="dataTable_wrapper" id="data-div">
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
            $('#outlet_id').select2({
                placeholder: 'Select an Outlet'
            });

            $('#from_date').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });

            $('#to_date').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });
        });

        //get

        function getList(flag) {

            var from = $('#from_date').val();
            var to = $('#to_date').val();
            var ot_id = $('#outlet_id').val();
            if(check30daysDiff(from,to)){
                return;
            }
            $('#loading_div').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

            processBtn('show_btn','add','Showing...');

            $.ajax({
                url: '/payment-report',
                type: "post",
                data: { from_date:from,to_date:to,ot_id:ot_id},
                success: function (data) {

                    $('#loading_div').empty();

                    $('#data-div').html(data);
                    processBtn('show_btn','remove','Show');
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

                    $('#stat_btn').attr('disabled',false);
                    $('#graph_btn').attr('disabled',false);
                    $('#stat_btn').text('Show Statistics');
                }
            });

        }


    </script>
@stop