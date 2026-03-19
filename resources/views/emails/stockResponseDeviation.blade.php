<table style="width: 100%;max-width: 100%;border: 1px solid #ddd;">
    <tr style="background-color: #428bca;color:#fff;height:35px">
        <th style="text-align: center">Item Name</th>
        <th style="text-align: center">Request Qty</th>
        <th style="text-align: center">Satisfied Qty</th>
        <th style="text-align: center">Satisfied By</th>
        <th style="text-align: center">For Location</th>
        <th style="text-align: center">From Location</th>
        <th style="text-align: center">Request Date</th>
    </tr>
    @foreach( $response_items as $itm )
        <tr style="height: 35px;">
            <td>{{ $itm->item_name }}</td>
            <td>{{ $itm->request_qty.''.$itm->req_unit }}</td>
            <td>{{ $itm->satisfied_qty.''.$itm->res_unit }}</td>
            <td>{{ $itm->owner }}</td>
            <td>{{ $itm->for_location }}</td>
            <td>{{ $itm->from_location }}</td>
            <td>{{ date('d-m-Y',strtotime($itm->request_when)) }}</td>
        </tr>
    @endforeach
</table>