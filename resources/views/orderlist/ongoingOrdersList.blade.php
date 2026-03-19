
<div class="row">
    <div class="col-lg-12" id="ongoing_order_div">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="ongoing_order_list">
                        <thead>
                        <tr>
                            <th>Order Id</th>
                            <th>Order Type</th>
                            <th>Outlet</th>
                            <th>Outlet Contact No.</th>
                            <th width="550">Order Details</th>
                            <th>Amount</th>
                            <th>Customer Name</th>
                            <th>Customer No.</th>
                            <th>Customer Address</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="table_body">
                            @if ( isset($orders) && sizeof($orders) > 0 )
                                @foreach( $orders as $ord )

                                    <?php $st_btn = "";?>
                                    @if( $ord->status == 'received' )
                                        <?php $st_btn = "Preparing";?>
                                    @elseif ( $ord->status == 'preparing' )
                                        <?php $st_btn = "Prepared";?>
                                    @elseif ( $ord->status == 'prepared' )
                                        <?php $st_btn = "Delivered";?>
                                    @endif

                                    <tr>
                                        <td>{!! $ord->suborder_id !!}</td>
                                        <td>{!! ucwords(str_replace('_',' ',$ord->order_type)) !!}</td>
                                        <td>{!! $ord->ot_name !!}</td>
                                        <td>{!! $ord->ot_phone !!}</td>
                                        <td width="550">{!! $itemlist[$ord->order_id] !!}</td>
                                        <td>{!! $ord->totalprice !!}</td>
                                        <td>{!! $ord->name !!}</td>
                                        <td>{!! $ord->user_mobile_number !!}</td>
                                        <td>{!! $ord->address !!}</td>
                                        <td>{!! ucwords($ord->status) !!}</td>
                                        <td><button class="btn btn-primary" onclick="changestatus(this,'{!! $ord->status !!}',{!! $ord->order_id !!},{!! $ord->outlet_id !!})">{!! $st_btn !!}</button></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>