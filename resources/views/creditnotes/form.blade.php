@extends('partials.default')
@section('pageHeader-left')
    Credit Notes
@stop

@section('pageHeader-right')
    <a href="/credit_notes" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')
    <style>td {
            padding: 10px;
        }</style>

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    @if($action=='edit')
                        {!! Form::model($creditnote,array('route' => array('creditnote.update',$creditnote->id),'id'=>"creditnoteForm", 'method' => 'patch', 'class' => 'form-horizontal material-form j-forms')) !!}
                    @else
                        {!! Form::open(['route' => 'credit-note.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms', 'id' => 'creditnoteForm']) !!}
                    @endif

                        <input type="hidden" id="order_id" value="{{ $order_id }}">
                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">
                                    {!! Form::label('customer_name','Customer Name*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('user_name',$order_arr['customer_name'], array('class' => 'form-control','id' => 'user_id')) !!}
                                        {!! Form::hidden('user_id',$order_arr['customer_id'], array('class' => 'form-control','id' => 'user_id')) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('place_of_supply','Place of Supply*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('place_of_supply', $states,null, array('class' => 'form-control select2','id' => 'place_of_supply')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">
                                    {!! Form::label('invoice_no','Invoice No*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('invoice_no',$order_arr['inv_no'], array('disabled','class' => 'form-control','id' => 'invoice_no')) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('reason','Reason*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('reason','Sales return', array('class' => 'form-control','id' => 'reason')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">
                                    {!! Form::label('credit_note','Credit Note#:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('credit_note',null, array('class' => 'form-control','id' => 'credit_note')) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('date_of_issue','Date Of issue:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('date_of_issue',date('Y-m-d'), array('readonly','class' => 'form-control','id' => 'date_of_issue')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">
                                    {!! Form::label('reference','Reference#:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('reference',null, array('class' => 'form-control','id' => 'reference')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div id="item_table_calculation">

                        </div>

                        <div class="form-group">
                            <table class="table table-condensed table-bordered">
                                <?php $column = 5;?>
                                <tr id="head">
                                    <th>Item Details </th>
                                    <th class="text-right">Quantity</th>
                                    <th class="text-right">Rate</th>
                                    @if($itemwise_discount)
                                        <th class="text-right">Discount</th>
                                        <?php $column++;?>
                                    @endif
                                    @if($itemwise_tax)
                                        <th class="text-right">Tax</th>
                                        <?php $column++;?>
                                    @endif
                                    <th class="text-right">Amount</th>
                                    <th></th>
                                </tr>

                                @for( $i = 0; $i < sizeof($order_arr['item']); $i++ )

                                    <tr class="item cn_item">
                                        <td>{!! Form::text('items[]', $order_arr['item'][$i]['name'], array('readonly','class' => 'form-control item')) !!}</td>

                                        <td>
                                            {!! Form::text('qty[]',$order_arr['item'][$i]['qty'], array('style'=>"text-align: right",'class' => 'form-control qty','placeholder'=>"Qty")) !!}
                                            <input type="hidden" class="original_qty" value="{{ $order_arr['item'][$i]['qty'] }}">
                                        </td>

                                        <td>
                                            {!! Form::text('rate[]',$order_arr['item'][$i]['price'], array('readonly','style'=>"text-align: right",'class' => 'form-control rate','placeholder'=>"Rate")) !!}
                                        </td>

                                        @if($itemwise_discount)
                                            <td class="text-right">
                                                @if( isset($order_arr['item'][$i]['discount']) && $order_arr['item'][$i]['discount'] != "" )
                                                    <input type="hidden" class="disc-type" value="{{ $order_arr['item'][$i]['discount']['type'] }}">
                                                    @if ( $order_arr['item'][$i]['discount']['type'] == 'fixed' )
                                                        <span class="disc-value">{{ $order_arr['item'][$i]['discount']['value'] }}</span>&#8377;
                                                    @else
                                                        <span class="disc-value">{{ $order_arr['item'][$i]['discount']['value'] }}</span>%
                                                    @endif
                                                @endif
                                            </td>
                                        @endif

                                        @if($itemwise_tax)
                                            <td>
                                                {{ $order_arr['item'][$i]['slab'] }}
                                                @if( isset($order_arr['item'][$i]['slab']) && $order_arr['item'][$i]['slab'] != '' )
                                                    @foreach( $order_arr['item'][$i]['taxes'] as $tx )
                                                        <input type="hidden" class="tax_name" value="{{ $tx['tx_name'] }}">
                                                        <input type="hidden" class="tax_parc" value="{{ $tx['tx_parc'] }}">
                                                    @endforeach
                                                @endif
                                            </td>

                                        @endif

                                        <td>
                                            {!! Form::text('total[]',$order_arr['item'][$i]['total'], array('readonly','style'=>"text-align: right",'class' => 'form-control amount','placeholder'=>"price")) !!}
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-danger" onclick="removeItem(this)"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                @endfor


                                    <tr align="right" class="sub-total">
                                        <td colspan="{{ $column - 2 }}" style="font-weight: 700">Sub Total:</td>
                                        <td><span id="sub_total">{{ $order_arr['sub_total'] }}</span></td>
                                        <td></td>
                                    </tr>

                                    <tr align="right" class="total">
                                        <td colspan="{{ $column - 2 }}" style="font-weight: 700">Final Total:</td>
                                        <td><span id="final_total" style="font-weight: 700">{{ $order_arr['total'] }}</span></td>
                                        <td></td>
                                    </tr>

                                </table>

                            </div>

                            <div class="form-footer">
                                <button class="btn btn-success primary-btn"  id="submit_btn" type="submit">Save</button>
                                <a href="/credit-note" class="btn btn-danger primary-btn">Cancel </a>
                            </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@stop
@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif


            $('#date_of_issue').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });

            updateTotal();

        });

        //on quantity change
        $('.qty').on('keyup', function() {

            var qty = $(this).val();
            var rate = $(this).closest('tr').find('.rate').val();
            var amount = parseFloat(qty) * parseFloat(rate);

            //update to 2 decimal format
            var updated_amt = amount.toFixed(2);
            $(this).closest('tr').find('.amount').val(updated_amt);

            updateTotal();
        });

        //update total calculation
        function updateTotal() {

            var itm_total = 0;var total = 0;var tx_arr = []; var cnt = 0;var taxes = [];
            var discount = 0;
            $('.cn_item').each(function () {

                var amount = parseFloat($(this).find('.amount').val());
                itm_total += amount
                total += amount

                //discount calculation
                var disc_type = $(this).find('.disc-type').val();
                var disc_value = $(this).find('.disc-value').text();

                if ( disc_type ) {

                    if ( disc_type == 'fixed' ) {
                        discount += parseFloat(disc_value);
                        amount -= parseFloat(disc_value);;
                        total -= parseFloat(disc_value);
                    } else {
                        var calc_disc = parseFloat(disc_value) * parseFloat(amount) / 100;
                        discount += parseFloat(calc_disc);
                        amount -= parseFloat(calc_disc);
                        total -= parseFloat(calc_disc);
                    }

                }

                //tax calculation
                var tx_length = $(this).find('.tax_name').length;

                if ( tx_length > 0 ) {

                    for ( i = 0; i < tx_length; i++ ) {

                        var tx_name = $(this).find('.tax_name:eq('+i+')').val();
                        var tx_parc = $(this).find('.tax_parc:eq('+i+')').val();
                        var tx_val = parseFloat(amount) * parseFloat(tx_parc) / 100;

                        if ( !tx_arr[tx_name+'('+tx_parc+'%)'] ) {
                            tx_arr[tx_name+'('+tx_parc+'%)'] = 0;
                            taxes.push(tx_name+'('+tx_parc+'%)');
                        }

                        tx_arr[tx_name+'('+tx_parc+'%)'] += parseFloat(tx_val);
                        total += parseFloat(tx_val);
                    }

                }

            })

            var round_total = Math.round(total);
            var round_value = Math.abs(round_total - total);

            var sub_total = itm_total.toFixed(2);
            var updated_final_total = round_total.toFixed(2);

            $('#sub_total').text(sub_total);
            $('#final_total').text(updated_final_total);

            var tr = '.sub-total';

            //remove inserted tr
            $('.discount-tr').remove();
            $('.tax-tr').remove();
            $('.round-off-tr').remove();

            //display discount field
            if ( discount > 0 ) {
                $('<tr align="right" class="discount-tr"><td colspan="{{ $column - 2 }}" style="font-weight:700">Discount</td><td>'+discount.toFixed(2)+'</td><td></td></tr>').insertAfter(tr);
                tr = '.discount-tr';
            }

            //display tax fields
            if ( taxes.length > 0 ) {

                for ( var k in tx_arr ){
                    $('<tr align="right" class="tax-tr"><td colspan="{{ $column - 2 }}" style="font-weight:700">'+ k +'</td><td>'+tx_arr[k].toFixed(2)+'</td><td></td></tr>').insertAfter(tr);
                }
            }

            if ( round_value > 0 ) {
                $('<tr align="right" class="round-off-tr"><td colspan="{{ $column - 2 }}" style="font-weight:700">Round Off</td><td>'+round_value.toFixed(2)+'</td><td></td></tr>').insertBefore('.total');
            }

        }

        //remove item
        function removeItem(el) {

            $(el).closest('tr').remove();
            updateTotal();

        }


    </script>
@stop