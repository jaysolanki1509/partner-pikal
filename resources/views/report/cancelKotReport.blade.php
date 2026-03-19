<?php
$no=1;
?>

<div class="row">

    <div class="col-lg-12">

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="disc_table">
                        <thead>
                        <tr>
                            <th>Table No.</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Reason</th>
                            <th>Cancelled By</th>
                            <th>Cancel Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if( isset($kot) && sizeof($kot) > 0 )
                            @foreach ( $kot as $kt )
                                <tr>
                                    <td>{!! $kt['table_no'] !!}</td>
                                    <td>{!! $kt['item_name'] !!}</td>
                                    <td>{!! $kt['quantity'] !!}</td>
                                    <td style="text-align: right">{!! $kt['price'] !!}</td>
                                    <td>{!! $kt['reason'] !!}</td>
                                    <td>{!! $kt['username'] !!}</td>
                                    <td>{!! $kt['created_at'] !!}</td>
                                </tr>
                                <?php $no++ ;?>
                            @endforeach
                        @endif

                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->

            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>