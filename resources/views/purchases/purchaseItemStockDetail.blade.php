<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="reports">
                        <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Available Stock</th>
                            <th>Settlement Qty</th>
                        </tr>
                        </thead>
                        <tbody id="table_body">
                        @if( isset($stock_arr) && sizeof($stock_arr) > 0 )
                            <?php $display_error_msg = false;?>
                            @foreach( $stock_arr  as $arr )

                                <tr @if( $arr['stock'] < $arr['decrease_stock'] ) <?php $display_error_msg = true;?> style="background-color: #c23321;color: white" @endif>
                                    <td>{!! $arr['name'] !!}</td>
                                    <td style="text-align: right">{!! $arr['stock']." ".$arr['unit'] !!}</td>
                                    <td style="text-align: right">{!! $arr['decrease_stock']." ".$arr['unit'] !!}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" style="text-align: center">No orders available.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    @if( isset($stock_arr) && sizeof($stock_arr) > 0 )
                        @if( $display_error_msg == true ) <span class="pull-left error">Item with red color have not sufficient stock.</span> @endif
                        <button class="pull-right btn btn-primary" title="Add Stock" style="padding:5px !important;margin-left: 5px;" onclick="settleStock('add')" id="add_btn"><i class="fa fa-long-arrow-up"></i> Stock</button>
                        <button class="pull-right btn btn-danger" title="Remove Stock" style="padding:5px !important;" onclick="settleStock('remove')" id="remove_btn"><i class="fa fa-long-arrow-down"></i> Stock</button>

                    @endif
                </div>
            </div>
        </div>
    </div>
</div>