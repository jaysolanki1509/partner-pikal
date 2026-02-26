
<div class="row">
    <div class="col-lg-12">

            <div class="panel-body">
                <div class="dataTable_wrapper" >
                    @if( isset($orders) && sizeof($orders) > 0 )
                        <table class="table table-striped table-bordered table-hover"  id="payment_report_list">

                            <thead >
                                <tr style="background-color: #428bca;color:#fff;">

                                    @if( $outlet_id == 'all')
                                        <th style="text-align: center">Outlet Name</th>
                                    @endif

                                    <th style="text-align: center">Payment Mode</th>
                                    <th style="text-align: center">Invoice No.</th>
                                    <th style="text-align: center">Status</th>
                                    <th style="text-align: center">Date</th>
                                    <th style="text-align: center">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $success = 0;$pending = 0;$failed = 0;?>
                                @foreach( $orders as $ord )
                                    <tr style="text-align: center">

                                        @if( $outlet_id == 'all')
                                            <td>{{ $ord->outlet_name }}</td>
                                        @endif

                                        <td>Online - UPI</td>
                                        <td>{!! $ord->invoice_no !!}</td>


                                        @if( $ord->upi_status == 0 )
                                            <?php $pending += $ord->totalprice; ?>
                                            <td>Pending</td>
                                        @elseif( $ord->upi_status == 1 )
                                            <?php $success += $ord->totalprice; ?>
                                            <td>Success</td>
                                        @else
                                            <?php $failed += $ord->totalprice; ?>
                                            <td>Failed</td>
                                        @endif

                                        <td>{!! $ord->table_end_date !!}</td>
                                        <td style="text-align: right">{!! $ord->totalprice !!}</td>

                                    </tr>
                                @endforeach
                                @if( $success != 0 )
                                    <tr style="text-align: right; background-color: #31b131; font-weight: bold;">
                                        <td style="text-align: right" @if( $outlet_id == 'all')colspan="5"@else colspan="4" @endif>Total Success</td>
                                        <td>{{ number_format($success,2)}}</td>
                                    </tr>
                                @endif
                                @if( $pending != 0 )
                                    <tr style="text-align: right; background-color:#FFC300; font-weight: bold;">
                                        <td style="text-align: right" @if( $outlet_id == 'all')colspan="5"@else colspan="4" @endif>Total Pending</td>
                                        <td>{{ number_format($pending,2)}}</td>
                                    </tr>
                                @endif
                                @if( $failed != 0 )
                                    <tr style="text-align: right; background-color: #FF5733; font-weight: bold;">
                                        <td style="text-align: right" @if( $outlet_id == 'all')colspan="5"@else colspan="4" @endif>Total Failed</td>
                                        <td>{{ number_format($failed,2)}}</td>
                                    </tr>
                                @endif

                            </tbody>

                        </table>
                    @else
                        <th>No record found.</th>
                    @endif
                </div>
            </div>

    </div>
</div>