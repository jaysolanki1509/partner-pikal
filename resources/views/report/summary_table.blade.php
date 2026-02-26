<?php

use Carbon\Carbon;
$no=0;

?>

    <table class="table table-striped table-bordered table-hover" id="reports">
        <thead>
        <tr>
            <th>Date</th>
            <th>Total Orders</th>
            <th>Total Sales</th>
            <th>Total Discount</th>
            <th>Total NC Order</th>
            <th>Total Taxes</th>
            <th>Total Bifurcation</th>
            <th>Online Sales</th>
            <th>Total Cash</th>
            <th>Gross Total</th>
            <th>Gross Average</th>
            <th>Unique Item Sale / Active Item</th>
            <th>Total Item Sale</th>
            <th>Total Person Visit</th>
            <th>Total Sale per Person</th>
            <th>Top Selling Item</th>
            <th>Lowest Order</th>
            <th>Highest Order</th>
            <th>Cancel Order Count</th>
            <th>Cancel Order Amount</th>
        </tr>
        </thead>
        <tbody>
            <?php $total_orders=0;  $total_sells=0; $total_discount=0; $total_nc_order=0;
                $total_taxes=0; $total_online=0; $total_cash=0;
                $gross_total=0; $gross_average=0; $total_item_sell=0; $total_person_visit=0;
                $lowest_order=0; $highest_order=0; $cancel_order_count=0; $cancel_order_amount=0;
                $bifurcation_arr = array();
            ?>
            @foreach($summary as $date=>$sum)
                <?php $total_orders+=$sum['total_orders'];            $total_sells+=$sum['total_sells'];
                      $total_discount+=$sum['total_discount'];        $total_nc_order+=$sum['total_nc_order'];
                      $total_taxes+=$sum['total_taxes'];              $total_online+=$sum['total_online'];
                      $total_cash+=$sum['total_cash'];                $gross_total+=$sum['gross_total'];
                      $gross_average+=$sum['gross_average'];          $total_item_sell+=$sum['total_item_sell'];
                      $total_person_visit+=$sum['total_person_visit']; $lowest_order+=$sum['lowest_order'];
                      $highest_order+=$sum['highest_order'];          $cancel_order_count+=$sum['cancel_order_count'];
                      $cancel_order_amount+=$sum['cancel_order_amount'];

                    //total bifurcation
                    $bifurcation = json_decode($sum['total_bifurcation'],true);

                ?>
                <tr>

                    <td>{{ $date }}</td>
                    <td>{{ $sum['total_orders'] }}</td>
                    <td>{{ $sum['total_sells'] }}</td>
                    <td>{{ number_format($sum['total_discount'],2) }}</td>
                    <td>{{ $sum['total_nc_order'] }}</td>
                    <td>{{ number_format($sum['total_taxes'],2) }}</td>
                    <td>
                        @foreach( $bifurcation as $bfr=>$val )

                            @if( is_array($val))

                                @foreach( $val as $key=>$bf )

                                    @if( isset($bifurcation_arr[$bfr][$key]))
                                        <?php $bifurcation_arr[$bfr][$key] += floatval($bf); ?>
                                    @else
                                        <?php $bifurcation_arr[$bfr][$key] = floatval($bf); ?>
                                    @endif

                                    @if ( $key == 'direct' || $bfr == 'UnPaid')
                                        {{ $bfr }} : {!! $bf !!}<hr style="margin-top: 5px;margin-bottom: 5px;">
                                    @else
                                        {{ $bfr." - ".$key }} : {!! $bf !!}<hr style="margin-top: 5px;margin-bottom: 5px;">
                                    @endif

                                @endforeach

                            @else

                                @if( isset($bifurcation_arr[$bfr]))
                                    <?php $bifurcation_arr[$bfr] += floatval($val); ?>
                                @else
                                    <?php $bifurcation_arr[$bfr] = floatval($val); ?>
                                @endif

                                {{ $bfr }} : {!! $val !!}<hr style="margin-top: 5px;margin-bottom: 5px;">

                            @endif

                        @endforeach
                    </td>

                    <td>{{ $sum['total_online'] }}</td>
                    <td>{{ $sum['total_cash'] }}</td>
                    <td>{{ $sum['gross_total'] }}</td>
                    <td>{{ number_format($sum['gross_average'],2) }}</td>
                    <td>{{ $sum['total_unique_item_sell'] }} / {{ $sum['active_item'] }}</td>
                    <td>{{ $sum['total_item_sell'] }}</td>
                    <td>{{ $sum['total_person_visit'] }}</td>
                    @if( $sum['total_person_visit'] == 0 )
                        <td>0</td>
                    @else
                        <td>{{ number_format($sum['gross_total']/$sum['total_person_visit'],2) }}</td>
                    @endif
                    <td>{{ $sum['top_selling_item'] }}</td>
                    <td>{{ $sum['lowest_order'] or 0 }}</td>
                    <td>{{ $sum['highest_order'] or 0 }}</td>
                    <td>{{ $sum['cancel_order_count'] }}</td>
                    <td>{{ $sum['cancel_order_amount'] }}</td>
                </tr>
                <?php $no++; ?>
            @endforeach
            <?php if($no==0) $no=1;?>
            <tr style="font-weight: bold">
                <td style="text-align: center"><b>Total</b></td>
                <td>{{ $total_orders }}</td>
                <td>{{ $total_sells }}</td>
                <td>{{ number_format($total_discount,2) }}</td>
                <td>{{ $total_nc_order }}</td>
                <td>{{ number_format($total_taxes,2) }}</td>

                <td>
                    @if( isset($bifurcation_arr) && sizeof($bifurcation_arr) > 0 )

                        @foreach( $bifurcation_arr as $bf_key=>$bf_val )

                            @if( is_array($bf_val))

                                @foreach( $bf_val as $t_key=>$t_val )

                                    @if ( $t_key == 'direct' || $bf_key == 'UnPaid')
                                        {{ $bf_key }} : {!! $t_val !!}<hr style="margin-top: 5px;margin-bottom: 5px;">
                                    @else
                                        {{ $bf_key." - ".$t_key }} : {!! $t_val !!}<hr style="margin-top: 5px;margin-bottom: 5px;">
                                    @endif

                                @endforeach

                            @else
                                {{ $bf_key }} : {!! $bf_val !!}<hr style="margin-top: 5px;margin-bottom: 5px;">
                            @endif

                        @endforeach

                    @else
                        0
                    @endif
                </td>

                <td>{{ $total_online }}</td>
                <td>{{ $total_cash }}</td>
                <td>{{ $gross_total }}</td>
                <td>{{ $gross_average }}</td>
                <td style="text-align: center">-</td>
                <td>{{ $total_item_sell }}</td>
                <td>{{ $total_person_visit }}</td>
                <td style="text-align: center">-</td>
                <td style="text-align: center">-</td>
                <td>{{ $lowest_order }}</td>
                <td>{{ $highest_order }}</td>
                <td>{{ $cancel_order_count }}</td>
                <td>{{ $cancel_order_amount }}</td>
            </tr>
        </tbody>
    </table>
