<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper" style="overflow-x: auto;">
                    <table border="1" class="table table-striped table-bordered table-hover">

                        <tr>
                            <th style="text-align: center" class="col-md-2">Check-In Date</th>
                            <th style="text-align: center" class="col-md-2">Room No.</th>
                            <th style="text-align: center" class="col-md-2">Guest Name</th>
                            <th style="text-align: center" class="col-md-2">Contact#</th>
                            <th style="text-align: center" class="col-md-2">Email</th>
                            <th style="text-align: center" class="col-md-2">Duration</th>
                            <th style="text-align: center" class="col-md-2">Deposit</th>

                        </tr>

                        @foreach ( $data as $dt )
                            <tr>
                                <td>{{ $dt['check_in_date']  }}</td>
                                <td>{{ $dt['room_name']  }}</td>
                                <td>{{ $dt['guest_name'] }}</td>
                                <td>{{ $dt['duration'] }}</td>
                                <td>{{ $dt['contact'] }}</td>
                                <td>{{ $dt['email'] }}</td>
                                <td>{{ $dt['deposit'] }}</td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>