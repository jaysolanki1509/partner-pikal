<table class="table foo-data-table" id="historytable">
    <thead>
        <th>Invoice#</th>
        <th>Sub Total</th>
        <th>Discount</th>
        <th>Taxes</th>
        <th>Total</th>
        <th>Payment Modes</th>
        <th>Updated By</th>
        <th>Updated At</th>
    </thead>

    <tbody>
        @if(isset($history) && sizeof($history) > 0 )
            @foreach($history as $hist)
                <tr>
                    <td>{!! $hist['invoice_no'] !!}</td>
                    <td>{!! $hist['sub_total'] !!}</td>
                    <td>{!! $hist['discount'] !!}</td>
                    <td>{!! $hist['taxes'] !!}</td>
                    <td>{!! $hist['total'] !!}</td>
                    <td>{!! str_replace(", ","<hr style='margin-top:5px; margin-bottom:5px;'>",$hist['payment_modes']) !!}</td>
                    <td>{!! $hist['updated_by'] !!}</td>
                    <td>{!! $hist['updated_at'] !!}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">Order History not found.</td>
            </tr>
        @endif
    </tbody>
</table>