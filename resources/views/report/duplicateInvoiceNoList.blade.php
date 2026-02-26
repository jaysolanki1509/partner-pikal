<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper" style="overflow-x: auto;">
                    <table border="1" class="table table-striped table-bordered table-hover">

                        <tr>
                            <th style="text-align: center" class="col-md-2">Date</th>
                            <th style="text-align: center" class="col-md-2">Invoice No.</th>
                            <th style="text-align: center" class="col-md-2">Outlet Name</th>

                        </tr>

                        @if ( $size > 0 )

                            @foreach ( $data as $dt )
                                <tr>
                                    <td>{{ $dt['date']  }}</td>
                                    <td>{{ $dt['inv_no'] }}</td>
                                    <td>{{ $dt['ot_name'] }}</td>

                                </tr>
                            @endforeach

                        @else

                            <tr>
                                <td colspan="3" align="center">No records Found.</td>
                            </tr>

                        @endif

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>