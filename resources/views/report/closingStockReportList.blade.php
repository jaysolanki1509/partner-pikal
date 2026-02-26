

    @if(sizeof($data['locations']) > 0)
        @foreach( $data['locations'] as $loc )
            <table class="table table-striped table-bordered table-hover">
                <tr style="text-align: center;font-weight: bold"><td colspan="{!! (sizeof($data['dates']) * 2 + 1) !!}">{!! ucwords($loc['name']) !!}</td></tr>
                <tr>
                    <th style="font-weight: bold">Item</th>
                    @foreach($data['dates'] as $key=>$date)
                        <th style="font-weight: bold">{{ $date }}</th>
                    @endforeach
                </tr>

                @if(sizeof($data['result']) > 0)
                    @foreach($data['result'] as $res_key=>$res_arr)
                        <?php $i = 0; ?>
                        <tr>
                            @foreach($data['dates'] as $key=>$date)
                                @if(isset($res_arr[$loc['id']][$date]) && $i==0 )
                                    <?php $i = 1; ?>
                                    <td> {{ $res_arr[$loc['id']][$date]['name'] }} </td>
                                @endif
                            @endforeach
                            @if($i == 1)
                                @foreach($data['dates'] as $key=>$date)
                                    @if (isset($res_arr[$loc['id']][$date]))
                                        <td style="text-align: right"> {{ $res_arr[$loc['id']][$date]['qty']." ".$res_arr[$loc['id']][$date]['unit'] }} </td>
                                    @else
                                        <td style="text-align: center;"> - </td>
                                    @endif

                                @endforeach
                            @endif
                        </tr>
                    @endforeach

                @else
                    <tr>
                        <td colspan="6">No records available.  </td>
                    </tr>
                @endif
            </table>
        @endforeach
    @endif