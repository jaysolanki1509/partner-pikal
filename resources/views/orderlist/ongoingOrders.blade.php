@extends('partials.default')

@section('pageHeader-left')
   Ongoing Order Details
@stop

@section('content')

    <div class="col-md-12 form-group" id="order_filter">
        <div class="col-md-3 form-group">
            <select id="outlet_id" class="form-control">
                <option value="all">All</option>
                @if(isset($outlets) && sizeof($outlets) > 0 )
                    @foreach( $outlets as $ot_id=>$ot_name )
                        <option value="{!! $ot_id !!}">{!! $ot_name !!}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-primary" id="show_btn" onclick="getOrders()" style="margin-left: 15px;">Show</button>
        </div>
        <div class="col-md-1" id="loading_div"></div>
    </div>
    <div style="clear: both"></div>

    <div class="col-lg-12" id="order_list">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="ongoing_order_list">
                                <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Outlet</th>
                                    <th>Outlet Contact No.</th>
                                    <th>Order Details</th>
                                    <th>Amount</th>
                                    <th>Customer Name</th>
                                    <th>Customer No.</th>
                                    <th>Customer Address</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
    <script>
        var loadOrders = true;
        $(document).ready(function() {
            $('#ongoing_order_list').DataTable({
                responsive: true,
                pageLength: 100
            });

            getOrders();
        });

        function getOrders() {

            if ( loadOrders == true ) {

                var outlet_id = $('#outlet_id').val();

                $('#show_btn').attr('disabled',true);
                $('#show_btn').text('Loading...');
                $('#loading_div').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

                $.ajax({
                    url: '/ongoing-orders',
                    type: "post",
                    data: { outlet_id:outlet_id},
                    success: function (data) {
                        $('#loading_div').empty();
                        $('#show_btn').attr('disabled',false);
                        $('#show_btn').text('Show');
                        $('#order_list').html(data);

                        setTimeout(function(){ getOrders('update') }, 10000);
                    }

                });

            }
        }

        function changestatus(el,sta,oid,resid){

            $(el).text('Loading..');
            loadOrders = false;

            var nextstatus='';
            $('#loader'+oid).css('display','block');
            $.ajax({
                url: 'ajax/nextstatus',
                cache : false,
                data:"currentstatus="+sta+"&oid="+oid+"&outlet_id="+resid,
                success: function(data) {
                    loadOrders = true;
                    getOrders();
                },
                error:function(data) {
                    $('#loader'+oid).css('display','none');
                }
            });

        }
    </script>

@stop