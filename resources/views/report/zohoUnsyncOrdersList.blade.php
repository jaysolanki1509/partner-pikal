<table class="table foo-data-table" id="unsync_orders">
    <thead>
        <th>Invoice#</th>
        <th>Error Message</th>
        <th>Date</th>
    </thead>

    <tbody>
        @if(isset($orders) && sizeof($orders) > 0 )
            @foreach($orders as $ord)
                <tr>
                    <td>{!! $ord->invoice_no !!}</td>
                    <td>{!! $ord->zoho_sync_msg !!}</td>
                    <td>{!! $ord->table_end_date !!}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">No orders found.</td>
            </tr>
        @endif
    </tbody>
</table>