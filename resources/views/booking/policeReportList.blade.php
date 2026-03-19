<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper" style="overflow-x: auto;">
                    <table border="1" id="police-report" class="table table-striped table-bordered table-hover">

                        <tr>
                            <th style="text-align: center" class="col-md-1">Guest Name</th>
                            <th style="text-align: center" class="col-md-1">Address</th>
                            <th style="text-align: center" class="col-md-1">Mobile/<br>Email</th>
                            <th style="text-align: center" class="col-md-1">Company Name</th>
                            <th style="text-align: center" class="col-md-1">Stay Duration</th>
                            <th style="text-align: center" class="col-md-1">ID Proof/<br>ID#</th>
                            <th style="text-align: center" class="col-md-1">Room#/PAX/<br>RoomType</th>
                            <th style="text-align: center" class="col-md-2">Guest Details</th>
                        </tr>

                        @foreach ( $data as $dt )
                            <tr>
                                @if(trim($dt['guest_name'])!="")
                                    <td>{{ $dt['guest_name'] }}</td>
                                @else
                                    <td align="center">-</td>
                                @endif

                                @if(trim($dt['address'])!="")
                                    <td>{{ trim($dt['address'])!=""?$dt['address']:"-" }}</td>
                                @else
                                    <td align="center">-</td>
                                @endif

                                @if(trim($dt['contact'])!="")
                                    <td>{{ trim($dt['contact'])!=""?$dt['contact']:"-" }}</td>
                                @else
                                    <td align="center">-</td>
                                @endif

                                @if(trim($dt['company'])!="")
                                    <td>{{ trim($dt['company'])!=""?$dt['company']:"-" }}</td>
                                @else
                                    <td align="center">-</td>
                                @endif

                                @if(trim($dt['duration'])!="")
                                    <td>{{ trim($dt['duration'])!=""?$dt['duration']:"-" }}</td>
                                @else
                                    <td align="center">-</td>
                                @endif

                                @if(trim($dt['id_proof'])!="")
                                    <td>{{ trim($dt['id_proof'])!=""?$dt['id_proof']:"-" }}</td>
                                @else
                                    <td align="center">-</td>
                                @endif

                                @if(trim($dt['guest_details'])!="")
                                    <td>{{ trim($dt['guest_details'])!=""?$dt['room_details']:"-" }}</td>
                                @else
                                    <td align="center">-</td>
                                @endif

                                @if(trim($dt['guest_details'])!="")
                                    <td>{{ trim($dt['guest_details'])!=""?$dt['guest_details']:"-" }}</td>
                                @else
                                    <td align="center">-</td>
                                @endif
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>