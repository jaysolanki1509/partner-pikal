<table id="detail-table" class="table table-striped table-hover" >

    <thead>
        <th>Request Date</th>
        <th>Item</th>
        <th>Request Qty</th>
        <th>Satisfied Qty</th>
        <th>From Location</th>
        <th>To Location</th>
        <th>Owner To</th>
        @if(!isset(Auth::user()->created_by) || Auth::user()->created_by == '')
            <td>Action</td>
        @endif
    </thead>

    <tbody>
        @if ( isset($data) && sizeof($data) > 0 )
            @foreach($data as $dt )
                <tr>
                    <td>{!! $dt['when'] !!}</td>
                    <td>{!! $dt['item'] !!}</td>
                    <td>{!! $dt['req_qty'] !!}</td>
                    <td>{!! $dt['sat_qty'] !!}</td>
                    <td>{!! $dt['from'] !!}</td>
                    <td>{!! $dt['for'] !!}</td>
                    <td>{!! $dt['user'] !!}</td>
                    <td>
                        <a href="/responseItem/{{$dt['id']}}/edit" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
                        <a href="#" title="Delete" onclick="deleteResponse({{$dt['id']}})"><i class="fa fa-close"></i></a>
                    </td>

                </tr>
            @endforeach
        @endif
    </tbody>

</table>