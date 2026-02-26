<table border="1" class="table data-tbl table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>Invoice No.</th>
        <th>Date</th>
        <th>Item Code</th>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total</th>
        <th>Payment Mode</th>
        <th>Category</th>
    </tr>
    </thead>
    <tbody>
    @if ( isset($data) && !empty($data) )
        @foreach ( $data as $dt )
            <tr>
                <td>{{ isset($dt['Invoice No']) ? $dt['Invoice No'] : "" }}</td>
                <td>{{ isset($dt['Date']) ? $dt['Date'] : "" }}</td>
                <td>{{ $dt['Item Code'] }}</td>
                <td>{{ $dt['Item Name'] }}</td>
                <td>{{ $dt['Quantity'] }}</td>
                <td align="right">{{ $dt['Price'] }}</td>
                <td>{{ $dt['Total'] }}</td>
                <td>{{ $dt['Payment Mode'] }}</td>
                <td>{{ $dt['Category'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td> {{ number_format($grandTotal,2) }}</td>
            <td></td>
            <td></td>
        </tr>
    @else
        <tr>
            <td colspan="4"> No sales found.</td>
        </tr>
    @endif
    </tbody>
</table>
@if ( $size > 0 )
    <span id="total_records"></span>
@endif
