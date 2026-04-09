<table class="table table-bordered table-hover"  id="revenue_report_list">
    @if( isset($result) && !empty($result) && $result->status == 'success' )
        @if( $result->report_type == 'sales with consumption' || $result->report_type == 'sales' )
            @if( isset($result->result) && !empty($result->result) )
                <?php $a=0; $cat_id = ''; $cat_tot = 0; $cat_ingred_tot = 0; $grand_tot = 0; $grand_ingred_tot = 0; ?>
                @foreach( $result->result as $re )

                    @if( $a == 0 || $cat_id != $re->itm_cat_id  )
                        @if($cat_id != '' && $cat_id != $re->itm_cat_id)
                            <tr style="background-color: #CCFFCC">
                                <th> Total </th>
                                <td colspan="@if( $result->report_type == 'sales with consumption' || $result->report_type == 'sales' ) {{ $result->report_type == 'sales'?2:2 }}@else 2 @endif " style="text-align: right;">
                                    &#x20b9; {!! number_format($cat_tot,2) !!}
                                    <?php $grand_tot += $cat_tot; ?>
                                </td>
                                @if( $result->report_type == 'sales with consumption' )
                                    <th>Consumption Total</th>
                                    <td style="text-align: right"> &#x20b9; {!! number_format($cat_ingred_tot,2) !!} </td>
                                    <?php $grand_ingred_tot += $cat_ingred_tot; ?>
                                @endif
                            </tr>
                                <?php $cat_tot = 0; $cat_ingred_tot = 0; ?>
                        @endif
                        <?php $cat_id = $re->itm_cat_id;?>
                        <tr>
                            <td colspan="@if( $result->report_type == 'sales with consumption' || $result->report_type == 'sales' ) {{ $result->report_type == 'sales'?3:5 }}@endif "></td>
                        </tr>
                        <thead>
                            <tr><th colspan="@if( $result->report_type == 'sales with consumption' || $result->report_type == 'sales' ) {{ $result->report_type == 'sales'?3:5 }}@else 2 @endif " style="text-align: center;background-color: #428bca;color:#fff;">{!! $re->itm_cat_name !!}</th></tr>
                            <tr style="background-color: #428bca;color:#fff;">
                                <th style="text-align: center"> Item Name </th>
                                <th style="text-align: center">Qty</th>
                                @if( $result->report_type == 'sales' || $result->report_type == 'sales with consumption' )
                                    <th style="text-align: center">Item Price</th>
                                @endif
                                @if( $result->report_type == 'sales with consumption' )
                                    <th style="text-align: center">Consumption</th>
                                    <th style="text-align: center">Consump. Cost</th>
                                @endif

                            </tr>
                        </thead>
                        {{--<tbody id="table_body">--}}
                    @endif

                    <tr>
                        <td style="font-weight: bold">{!! $re->itm_name !!}</td>
                        <td >{!! $re->itm_qty !!}</td>
                        @if( $result->report_type == 'sales' || $result->report_type == 'sales with consumption' )
                            <td style="text-align: right"> &#x20b9; {!! number_format($re->price*$re->itm_qty,2) !!} </td>
                            <?php $cat_tot += $re->price*$re->itm_qty; ?>
                        @endif

                        @if( $result->report_type == 'sales with consumption' )
                            @if( isset($re->ingrd) && !empty($re->ingrd) )
                                <td><?php $tot_price = 0; ?>
                                    @foreach( $re->ingrd as $ing )
                                        {!! $ing->itm_name !!} ( {!! $ing->itm_qty !!} ) ( &#x20b9; {!! number_format($ing->price,2) !!} )<br>
                                        <?php $tot_price += $ing->price; ?>
                                    @endforeach
                                </td>
                            @else
                                <td>-</td>
                            @endif
                            @if( isset($re->ingrd) && !empty($re->ingrd) )
                                <td style="text-align: right">
                                    &#x20b9; {!! number_format($tot_price,2) !!}
                                    <?php $cat_ingred_tot += $tot_price; ?>
                                </td>
                            @else
                                <td style="text-align: right">-</td>
                            @endif
                        @endif
                    </tr>
                    {{--</tbody>--}}
                    <?php $a++;?>
                @endforeach
                    @if( $result->report_type == 'sales' || $result->report_type == 'sales with consumption' )
                        <tr style="background-color: #CCFFCC">
                            <th> Total </th>
                            <td colspan=" {{ $result->report_type == 'sales'?2:2 }} " style="text-align: right;">
                                &#x20b9; {!! number_format($cat_tot,2) !!}
                                <?php $grand_tot += $cat_tot; ?>
                            </td>
                            @if( $result->report_type == 'sales with consumption' )
                                <th>Consumption Total</th>
                                <td style="text-align: right"> &#x20b9; {!! number_format($cat_ingred_tot,2) !!} </td>
                                <?php $grand_ingred_tot += $cat_ingred_tot; ?>
                            @endif
                        </tr>
                        <tr>
                            <td colspan="@if( $result->report_type == 'sales with consumption' || $result->report_type == 'sales' ) {{ $result->report_type == 'sales'?3:5 }}@endif "></td>
                        </tr>
                        <tr style="background-color: #99CC99">
                            <th> Grand Total </th>
                            <th colspan="@if( $result->report_type == 'sales with consumption' || $result->report_type == 'sales' ) {{ $result->report_type == 'sales'?2:2 }}@else 2 @endif " style="text-align: right;">
                                &#x20b9; {!! number_format($grand_tot,2) !!}
                            </th>
                            @if( $result->report_type == 'sales with consumption' )
                                <th>Grand Consumption Total</th>
                                <th style="text-align: right"> &#x20b9; {!! number_format($grand_ingred_tot,2) !!} </th>
                            @endif
                        </tr>
                    @endif
            @else
                <tr><td colspan="2" style="text-align: center">No Records Found</td></tr>
            @endif

        @else
            <thead>
                <tr style="background-color: #428bca;color:#fff;">
                    <th style="text-align: center"> Item Name </th>
                    <th style="text-align: center">Qty</th>
                    @if( $result->report_type == 'consumption wih sales' )
                        <th style="text-align: center">Item Prepared</th>
                    @endif
                </tr>
            </thead>
            <tbody id="table_body">
            @if( isset($result->result) && !empty($result->result) )
                @foreach( $result->result as $re )
                    <tr>
                        <td style="font-weight: bold">{!! $re->name !!}</td>
                        <td >{!! $re->qty !!} {!! $re->unit !!}</td>
                        @if( $result->report_type == 'consumption wih sales' )
                            @if( isset($re->sale) && !empty($re->sale) )
                                <td>
                                    @foreach( $re->sale as $ing )
                                        {!! $ing->item_name !!} ( {!! $ing->item_qty !!} )<br>
                                    @endforeach
                                </td>
                            @else
                                <td>-</td>
                            @endif
                        @endif
                    </tr>
                @endforeach
            @else
                <tr><td colspan="2" style="text-align: center">No Records Found</td></tr>
            @endif
            </tbody>
        @endif

    @else
        <tr><td colspan="2" style="text-align: center">No Records Found</td></tr>
    @endif
</table>
