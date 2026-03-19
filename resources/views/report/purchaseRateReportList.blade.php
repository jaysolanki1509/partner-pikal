
    <table class="table table-striped table-bordered table-hover" id="purchase_rate_report_list" style="overflow-y:scroll ">
        @if( isset($data) && sizeof($data) > 0 )
            <thead>
            <tr>
                @if ( $show_qty == 'true' || $show_total == 'true')
                    <th rowspan="2" width="20%" style="font-weight:bold;text-align: center;vertical-align: middle;">Item Name</th>
                @else
                    <th width="20%" style="font-weight:bold;text-align: center;vertical-align: middle;">Item Name</th>
                @endif
                @foreach($dates as $dt)
                    @if($show_qty == 'true' && $show_total == 'true' )
                        <?php $colspan = 3;?>
                    @elseif ( $show_qty == 'true' )
                        <?php $colspan = 2;?>
                    @elseif ( $show_total == 'true' )
                        <?php $colspan = 2;?>
                    @else
                        <?php $colspan = 1;?>
                    @endif
                     <th colspan="{!! $colspan !!}" style="text-align: center">{!! $dt !!}</th>
                @endforeach
                <th rowspan="2" width="15%" style="text-align:center;vertical-align: middle;">Average Rate</th>
            </tr>
            <tr style="background-color: #428bca;color:#fff;">
                @foreach($dates as $dt)
                    @if ( $show_qty == 'true' || $show_total == 'true')
                        <th style="text-align: center">Rate</th>
                    @endif
                    @if ( $show_qty == 'true' )
                        <th style="text-align: center">Qty</th>
                    @endif
                    @if ( $show_total == 'true' )
                        <th style="text-align: center">Total</th>
                    @endif
                @endforeach

            </tr>
            </thead>
            <tbody id="table_body">

                @foreach( $items as $itm )
                    <tr>
                        <td style="text-align: left">{!! $itm->item !!}</td>
                        <?php $rate = 0;$cnt = 0;?>
                        @foreach( $dates as $dt )
                            <td style="text-align: right">{!! $data[$itm->id][$dt]['rate'] !!}</td>
                            <?php
                                if ( $data[$itm->id][$dt]['rate'] != 'NA') {
                                    $rate += $data[$itm->id][$dt]['rate'];
                                    $cnt++;
                                }
                            ?>
                            @if ( $show_qty == 'true' )
                                <td style="text-align: right">{!! $data[$itm->id][$dt]['qty'] !!}</td>
                            @endif
                            @if ( $show_total == 'true' )
                                <td style="text-align: right">{!! $data[$itm->id][$dt]['total'] !!}</td>
                            @endif
                        @endforeach
                        <td style="text-align: right;font-weight: bold">@if( $cnt > 0 ){!! number_format($rate/$cnt,2) !!} @else {!! 'NA' !!} @endif</td>
                    </tr>
                @endforeach

            </tbody>
        @else
            <tr><td>No Records Found</td></tr>
        @endif
    </table>