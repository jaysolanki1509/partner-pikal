

    <table class="table foo-data-table" id="ongoing_table">
        <thead>
        <tr>
            <th>Table No</th>
            <th width="550">Order Details</th>
            <th data-hide="phone">Amount</th>
            <th>Created By</th>
            <th>Created At</th>

        </tr>
        </thead>
        <tbody id="table_body">
        @if ( isset($orders) && sizeof($orders) > 0 )
            @foreach( $orders as $ord )
                <tr>
                    <td>{!! $ord->table_no !!}</td>
                    <td width="550">{!! urldecode($itemlist[$ord->order_unique_id]['item']) !!}</td>
                    <td style="text-align: right">{!! number_format($itemlist[$ord->order_unique_id]['price'],2)!!}</td>
                    <td>{!! $ord->username !!}</td>
                    <td>{!! date('Y-m-d H:i:s',strtotime($ord->kot_time)) !!}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
        <input type="text" id="total_kot" name="total_kot" hidden value="{{$total_kot}}">
    </table>
