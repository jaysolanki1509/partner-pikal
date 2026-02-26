<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper" style="overflow-x: auto;">
                    <table border="1" id="police-report" class="table table-striped table-bordered table-hover">
                        @if(sizeof($data)>0)
                            <tr>
                                <th style="text-align: center" class="col-md-1">Room Type</th>
                                <th style="text-align: center" class="col-md-1">Total Rooms</th>
                                <th style="text-align: center" class="col-md-2">Room Revenue</th>
                                <th style="text-align: center" class="col-md-1">Sold Rooms</th>
                                <th style="text-align: center" class="col-md-1">Sold Rooms%</th>
                                <th style="text-align: center" class="col-md-1">Single Sold</th>
                                <th style="text-align: center" class="col-md-1">Groop Sold</th>
                                <th style="text-align: center" class="col-md-1">Pax</th>
                                <th style="text-align: center" class="col-md-1">ARR</th>
                                <th style="text-align: center" class="col-md-1">ARP</th>
                            </tr>

                            @foreach ( $data as $dt )
                                <tr>
                                    <td>{{ $dt['created_on'] }}</td>
                                    <td>{{ $dt['booking_type'] }}</td>
                                    <td>{{ $dt['guest_name'] }}</td>
                                    <td>{{ $dt['pax'] }}</td>
                                    <td>{{ $dt['room_name'] }}</td>
                                    <td>{{ $dt['room_type'] }}</td>
                                    <td>{{ $dt['status'] }}</td>
                                    <td>{{ $dt['check_in'] }}</td>
                                    <td>{{ $dt['check_out'] }}</td>
                                    <td>{{ $dt['created_by'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <lable>No records found</lable>
                        @endif

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>