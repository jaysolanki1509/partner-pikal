@foreach($stock as $stk )
    <tr @if($stk->type=='add')style="background-color: #DAF7A6;" @else style="background-color: #FADBD8;" @endif>

        <td>@if($stk->type=='add')
                <i class="fa fa-plus" style="color: #2ca02c" aria-hidden="true"></i>
            @else
                <i class="fa fa-minus" style="color: #9f191f" aria-hidden="true"></i>
            @endif{!! number_format($stk->stk_quantity,1).' '.$stk->unit !!}</td>
        <td>{!! $stk->reason !!}</td>
        <td>{!! $stk->date or 'NA' !!}</td>
    </tr>
@endforeach