<div class="panel panel-default">
    <div class="panel-body">
        <div class="dataTable_wrapper" style="overflow-x: auto;">

            <table class="table table-striped table-bordered table-hover">
                @if(sizeof($kotsubtotaldiff) > 0)
                    <tr>
                        <th class="bold-center">Item</th>
                        <th class="bold-center">Price</th>
                        <th class="bold-center">Quantity</th>
                        {{--<th class="bold-center">Reason</th>--}}
                        <th class="bold-center">Date</th>
                        <th class="bold-center">Reason</th>
                    </tr>
                    <?php $tot_from_user = 0; $tot_from_db = 0; $diff_tot = 0;?>
                    @foreach($kotsubtotaldiff as $data)

                        <tr>
                            <td align="left">{{ $data['item_name'] }}</td>
                            <td align="right">{{ $data['price'] }}</td>
                            <td align="right">{{ $data['qty'] }}</td>
                           {{-- <td align="left">{{ $data['reason'] }}</td>--}}
                            <td align="left">{{ $data['date'] }}</td>
                            <td align="left">{{ $data['reason'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">There are no kots for the selected date.</td>
                    </tr>
                @endif
            </table>

        </div>
    </div>
</div>