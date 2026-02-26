<form action="/update/invoice" method="post">
    <table class="table table-striped table-bordered table-hover" id="processInvoiceTable">
        <thead>
            @if($flag == 'paid')
                <th>{!! Form::checkbox('all',null,null, array('id'=>'select_all')) !!}</th>
            @endif
            <th>Date</th>
            <th>Item</th>
            <th>Qty</th>
            <th>Rate</th>
            @if($flag == 'invoiced')
                <th>Invoice</th>
            @endif
        </thead>

        <tbody>
        @if ( isset($purchases) && sizeof($purchases) > 0 )
            @foreach($purchases as $pr)

                <tr class="odd gradeX">
                    @if($flag == 'paid')
                        <td>{!! Form::checkbox('paid['.$pr->id.']', null, null, array('class'=>'checkbox')) !!}</td>
                    @endif
                    <td>{!! $pr->purchase_date or '' !!}</td>
                    <td>{!! $pr->item or '' !!}</td>
                    <td>{!! $pr->quantity or '' !!}</td>
                    <td>{!! $pr->rate or '' !!}</td>
                    @if($flag == 'invoiced')
                        <td>
                            <input type="text" class="form-control" placeholder="Invoice Number" id="invoice[{!! $pr->id !!}]" name="invoice[{!! $pr->id !!}]">
                        </td>
                    @endif
                </tr>
            @endforeach

        @endif
        {!! Form::hidden('flag', isset($flag)?$flag:null, ["id"=>"flag","readonly"=>"readonly"]) !!}

        </tbody>
    </table>
    <div align="right" style="margin-right: 10%">
        <button class="btn btn-primary" type="submit"> Update Bill Details </button>
    </div>
</form>
