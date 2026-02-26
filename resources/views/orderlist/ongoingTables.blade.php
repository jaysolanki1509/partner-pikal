<?php $sess_outlet_id = Session::get('outlet_session');?>
@extends('partials.default')

@section('pageHeader-left')
    Ongoing Table Details
@stop

@section('pageHeader-right')
    <a href="/orderslist" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">

                        @if( !isset($sess_outlet_id) || $sess_outlet_id == '')

                            <div class="col-md-12 form-group" id="order_filter">
                                <div class="col-md-3 form-group">
                                    <select id="outlet_id" class="form-control">
                                        @if(isset($outlets) && sizeof($outlets) > 0 )
                                            @foreach( $outlets as $ot_id=>$ot_name )
                                                <option value="{!! $ot_id !!}">{!! $ot_name !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-primary" id="show_btn" onclick="getOrders()">Show</button>
                                </div>

                                <div class="col-md-1" id="loading_div"></div>
                            </div>
                        @endif
                        <div style="clear: both"></div>

                        <div id="order_list">
                            <table class="table foo-data-table" id="ongoing_table">
                                <thead>
                                <tr>
                                    <th>Table No.</th>
                                    <th width="550">Order Details</th>
                                    <th data-hide="phone">Amount</th>
                                    <th>Created By</th>
                                </tr>
                                </thead>
                                <tbody id="table_body">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('page-scripts')

    <script src="/assets/js/new/lib/footable.all-min.js"></script>

    <script>

        var loadOrders = true;
        $(document).ready(function() {
            /*$('#ongoing_table').DataTable({
                responsive: true,
                pageLength: 100
            });*/

            $('#outlet_id').select2({
                placeholder: 'Select Outlet'
            });

            getOrders();

            //SSE for update page data
            if(typeof(EventSource) !== "undefined") {
                var source = new EventSource("/update-page-data/kot");
                source.onmessage = function(event) {
                    var kot = event.data.split(":");
                    var total_kot = kot[1];
                    var total_kot_item = $('#total_kot').val();
                    if ( parseInt(total_kot_item) != parseInt(total_kot)) {
                        //source.close();
                        getOrders();
                    }

                };
            } else {
                console.log('SSE not supported');
            }
        });

        function getOrders() {

            if ( loadOrders == true ) {

                var outlet_id = $('#outlet_id').val();

                processBtn('show_btn','add','Showing...');

                $.ajax({
                    url: '/ongoing-tables',
                    type: "post",
                    data: { outlet_id:outlet_id},
                    success: function (data) {
                        processBtn('show_btn','remove','Show');
                        $('#order_list').html(data);

                        $("#ongoing_table").footable({phone:767,tablet:1024});
                    }

                });

            }
        }


    </script>

@stop