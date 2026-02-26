<style>

    #parent {
        border: 1px solid #ddd;
        height: 375px;
    }

</style>
@if( isset($stock) && sizeof($stock) > 0 )
    <div class="table-responsive" id="parent">
        <table id="fixTable" class="table table-striped table-hover" >
            <thead>
                <th>Location</th>
                <th>Category</th>
                <th>Item</th>
                <th>Reserve Qty</th>
                <th>Stock Qty</th>
            </thead>

            <tbody>
            @foreach($stock as $stk )
                <tr>
                    <td>{!! $stk['location'] !!}</td>
                    <td>{!! $stk['cat_name'] !!}</td>
                    <td>{!! $stk['item'] !!}</td>
                    <td>{!! number_format($stk['reserved_qty'],1).' '.$stk['unit'] !!}</td>
                    <td>{!! number_format($stk['stock_qty'],1).' '.$stk['unit'] !!}</td>
                </tr>

            @endforeach

            </tbody>
        </table>
    </div>
@else
    <span>Stock not available.</span>
@endif

<script>
    $(document).ready(function() {
        $("#fixTable").tableHeadFixer();
    });
</script>