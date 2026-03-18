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

                                    <tr @if( $arr['stock'] < $arr['decrease_stock'] ) <?php $display_error_msg = true;?> style="background-color: #c23321" @endif>
                                        <td>{!! $arr['name'] !!}</td>
                                        <td>{!! $arr['stock']." ".$arr['unit'] !!}</td>
                                        <td>{!! $arr['decrease_stock']." ".$arr['unit'] !!}</td>
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
                        <button class="pull-right btn btn-primary" style="margin-top:10px !important;padding:5px !important;" onclick="showStock('process',event)" id="process_btn">Decrease Stock</button>
                        <button class="pull-right btn btn-danger" style="margin-top:10px !important;padding:5px !important;margin-right: 5px;" onclick="showStock('revoke',event)" id="revoke_btn">Revoke Stock</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>