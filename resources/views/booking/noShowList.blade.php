<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper" style="overflow-x: auto;">
                    <table border="1" id="police-report" class="table table-striped table-bordered table-hover">
                        @if(sizeof($data)>0)
                            <tr>
                                <th style="text-align: center" class="col-md-1">Created On</th>
                                <th style="text-align: center" class="col-md-1">Res#/Group</th>
                                <th style="text-align: center" class="col-md-2">Guest Name</th>
                                <th style="text-align: center" class="col-md-1">Pax</th>
                                <th style="text-align: center" class="col-md-1">Room#</th>
                                <th style="text-align: center" class="col-md-1">Room Type</th>
                                <th style="text-align: center" class="col-md-1">Company</th>
                                <th style="text-align: center" class="col-md-1">Status</th>
                                <th style="text-align: center" class="col-md-1">Check Out</th>
                                <th style="text-align: center" class="col-md-1">Check In</th>
                                <th style="text-align: center" class="col-md-1">Created By</th>
                            </tr>

                            @foreach ( $data as $dt )
                                <tr>
                                    <td>{{ $dt['created_on'] }}</td>
                                    <td>{{ $dt['booking_type'] }}</td>
                                    <td>{{ $dt['guest_name'] }}</td>
                                    <td>{{ $dt['pax'] }}</td>
                                    <td>{{ $dt['room_name'] }}</td>
                                    <td>{{ $dt['room_type'] }}</td>

                                    @if(trim($dt['company'])!="")
                                        <td>{{ trim($dt['company'])!=""?$dt['company']:"-" }}</td>
                                    @else
                                        <td align="center">-</td>
                                    @endif

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