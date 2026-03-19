
<table class="table table-bordered table-hover">
<?php $count = 0;?>
        <tr>
            <th style="text-align: center" class="col-md-2">Item Name</th>
            <th style="text-align: center" class="col-md-2">Requested Qty</th>
            <th style="text-align: center" class="col-md-2">Satisfied Qty</th>
            <th style="text-align: center" class="col-md-1">Satisfied By</th>
            <th style="text-align: center" class="col-md-2">Satisfied From</th>
            <th style="text-align: center" class="col-md-2">Satisfied Date</th>
        </tr>

        @if( isset($zero_qty) && sizeof($zero_qty)>0 )
            <?php $count = 1;?>
            @foreach($zero_qty as $itm1)
                <tr class="bg-danger">
                    <td >{{ $itm1['item_name'] }}</td>
                    <td style="text-align: right">{{ $itm1['req_qty'] }}</td>
                    <td style="text-align: right">{{ $itm1['res_qty'] }}</td>
                    <td>{{ $itm1['satisfied_by'] }}</td>
                    <td>{{ $itm1['from_location'] }}</td>
                    <td>{{ date('d-m-Y',strtotime($itm1['satisfied_date']))}}</td>
                </tr>
            @endforeach
        @endif
        @if( isset($less_qty) && sizeof($less_qty)>0 )
            <?php $count = 1;?>
            @foreach($less_qty as $itm2)
                <tr class="bg-warning">
                    <td>{{ $itm2['item_name'] }}</td>
                    <td style="text-align: right">{{ $itm2['req_qty'] }}</td>
                    <td style="text-align: right">{{ $itm2['res_qty'] }}</td>
                    <td>{{ $itm2['satisfied_by'] }}</td>
                    <td>{{ $itm2['from_location'] }}</td>
                    <td>{{ date('d-m-Y',strtotime($itm2['satisfied_date']))}}</td>
                </tr>
            @endforeach
        @endif
        @if( isset($more_qty) && sizeof($more_qty)>0 )
            <?php $count = 1;?>
            @foreach($more_qty as $itm3)
                <tr class="bg-info">
                    <td>{{ $itm3['item_name'] }}</td>
                    <td style="text-align: right">{{ $itm3['req_qty'] }}</td>
                    <td style="text-align: right">{{ $itm3['res_qty'] }}</td>
                    <td>{{ $itm3['satisfied_by'] }}</td>
                    <td>{{ $itm3['from_location'] }}</td>
                    <td>{{ date('d-m-Y',strtotime($itm3['satisfied_date']))}}</td>
                </tr>
            @endforeach
        @endif
        @if( $count == 0 )
            <tr>
                <td colspan="6" align="center">No Response Deviation Found.</td>
            </tr>
        @endif

</table>