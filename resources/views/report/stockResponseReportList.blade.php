@if(sizeof($data['locations']) > 0)

    @foreach( $data['locations'] as $loc )
        <table class="table table-striped table-bordered table-hover">
            <tr style="text-align: center;font-weight: bold"><td colspan="{!! (sizeof($data['dates']) * 2 + 1) !!}">{!! ucwords($loc['name']) !!}</td></tr>
            <tr style="font-weight: bold">
                <th></th>
                @foreach($data['dates'] as $key=>$date)
                    <th style="font-weight: bold" colspan="2">{{ $date }}</th>
                @endforeach
            </tr>
            <tr>
                <th style="font-weight: bold">Item</th>
                @foreach($data['dates'] as $key=>$date)
                    <th style="font-weight: bold">Qty</th>
                    <th style="font-weight: bold">Value</th>
                    <?php $price[$date] = 0; ?>
                @endforeach
            </tr>

            @if(sizeof($data['result']) > 0)
                @foreach($data['result'] as $res_key=>$res_arr)
                    <?php $i = 0; ?>
                    <tr>

                        @foreach($data['dates'] as $key=>$date)
                            @if(isset($res_arr[$loc['id']][$date]) && $i==0)
                                <?php $i = 1; ?>
                                <td style="font-weight: bold"> {{ $res_arr[$loc['id']][$date]['name'] }} </td>
                            @endif
                        @endforeach
                        @if($i == 1)
                            @foreach($data['dates'] as $key=>$date)
                                @if (isset($res_arr[$loc['id']][$date]))
                                    <td style="text-align: right"> {{ $res_arr[$loc['id']][$date]['qty']." ".$res_arr[$loc['id']][$date]['unit'] }} </td>
                                    <td style="text-align: right"> {{ number_format($res_arr[$loc['id']][$date]['qty']*$res_arr[$loc['id']][$date]['price'],2) }} </td>
                                    <?php $price[$date] += $res_arr[$loc['id']][$date]['qty']*$res_arr[$loc['id']][$date]['price']; ?>
                                @else
                                    <td style="text-align: center;"> - </td>
                                    <td style="text-align: center;"> - </td>
                                @endif
                            @endforeach
                        @endif
                    </tr>
                @endforeach
                <tr style=" background-color: #99CC99">
                    <td style="font-weight: bold">Total</td>
                    @foreach($data['dates'] as $key=>$date)
                        <td style="text-align: center;font-weight: bold"> - </td>
                        <td style="text-align: right;font-weight: bold">@if(isset($price[$date])) {{ number_format($price[$date],2) }}  @endif </td>
                    @endforeach
                </tr>

            @else
                <tr>
                    <td colspan="6">You Don't have any request.  </td>
                </tr>
            @endif
        </table>
    @endforeach
@endif