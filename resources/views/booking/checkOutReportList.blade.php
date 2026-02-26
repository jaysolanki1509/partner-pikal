<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper" style="overflow-x: auto;">
                    <table border="1" class="table table-striped table-bordered table-hover">

                        <tr>
                            <th style="text-align: center" class="col-md-2">Guest Name</th>
                            <th style="text-align: center" class="col-md-2">Stay Duration</th>
                            <th style="text-align: center" class="col-md-2">Room# / Type</th>
                            <th style="text-align: center" class="col-md-2">PAX</th>
                            <th style="text-align: center" class="col-md-2">Price</th>

                        </tr>

                        @foreach ( $data as $dt )
                            <tr>
                                <td>{{ $dt['guest_name'] }}</td>
                                <td>{{ $dt['duration'] }}</td>
                                <td>{{ $dt['room_name'] }}</td>
                                <td>{{ $dt['pax'] }}</td>
                                <td>₹&nbsp;{{ number_format($dt['amount'],2) }}</td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>