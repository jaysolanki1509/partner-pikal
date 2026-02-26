<table border="1" id="disc_table" class="table data-tbl table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Invoice</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if ( isset($list) && sizeof($list) > 0 )
            @foreach ( $list as $ord )
                <tr id="{{ $ord['id'] }}">
                    <td><a href="javascript:void(0)" onclick="openOrderView({{ $ord['id'] }})">{{ $ord['inv_no'] }}</a></td>
                    <td>{{ $ord['total'] }}</td>
                    <td>{{ $ord['date'] }}</td>
                    <td><button class="btn btn-primary" onclick="paidOrder(this,{{ $ord['id'] }},'open')">Paid</button></td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4"> No orders found.</td>
            </tr>
        @endif
    </tbody>
</table>