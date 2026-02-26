<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Credit Note</h4>
</div>
<div class="modal-body">
    <div class="form-group col-md-12 box">
        @if(strtolower($data['status']) == 'open')
            <div class="ribbon_red"><span>{{$data['status']}}</span></div>
        @else
            <div class="ribbon"><span>{{$data['status']}}</span></div>
        @endif
        <table border="1" height="40%" width="100%" id="creditnote" style="background:#ffffff; margin-top: 10px;margin-bottom: 10px;">
            <tr>
                <td colspan="3" class="col-md-6"><h4><b>{{$data['outlet_name']}}</b></h4><div style="clear: both;"></div>{{$data['outlet_address']}}</td>
                <td colspan="2" class="col-md-6" ><h3 align="right">CREDIT NOTE</h3></td>
            </tr>
            <tr>
                <td colspan="3" class="col-md-6">
                    <table width="100%">
                            <tr>    <td width="50%">CN#:</td>           <td><b>{{$data['cn_no']}}</b></td> </tr>
                            <tr>    <td width="50%">Credit Date:</td>   <td><b>{{$data['credit_date']}}</b></td> </tr>
                            <tr>    <td width="50%">Invoice#:</td>      <td><b>{{$data['invoice_no']}}</b></td> </tr>
                            <tr>    <td width="50%">Invoice Date#:</td> <td><b>{{$data['invoice_date']}}</b></td> </tr>
                    </table>
                </td>
                <td colspan="2" class="col-md-6" >Place Of Supply:&nbsp;&nbsp;&nbsp;&nbsp;<b>{{$data['state']}}</b></td>
            </tr>
            <tr>
                <td colspan="5" class="col-md-12">Bill To :&nbsp;&nbsp;&nbsp;&nbsp;<b>{{$data['cust_name']}}</b></td>
            </tr>
            <tr>
                <td class="col-md-1" align="center"><b>#</b></td>
                <td class="col-md-3" align="left"><b>Item</b></td>
                <td class="col-md-2" align="right"><b>Qty</b></td>
                <td class="col-md-2" align="right"><b>Rate</b></td>
                <td class="col-md-4" align="right"><b>Amount</b></td>
            </tr>
            <?php $i = 1; $total_price = 0; ?>
            @foreach($data['item_arr'] as $item)
                <tr>
                    <td class="col-md-1" align="center">{{$i}}</td>
                    <td class="col-md-3" align="left">{{$item->item_name}}</td>
                    <td class="col-md-2" align="right">{{$item->item_qty}}</td>
                    <td class="col-md-2" align="right">{{$item->item_price}}</td>
                    <td class="col-md-4" align="right">{{$item->item_total}}</td>
                </tr>
            @endforeach
            <tr>
                <td rowspan="2" style="vertical-align: top;" align="left" colspan="3" class="col-md-6">
                    Total In Words:<div style="clear: both;"></div>
                    <b>{{$data['amount_words']}}</b>
                </td>
                <td colspan="2" style="height: 25px" align="center" class="col-md-6" >
                    <table width="100%">
                        <tr>
                            <td align="right" width="50%">Sub Total</td> <td align="right">{{$data['sub_total']}}</td>
                        </tr>
                        @if(isset($data['taxes']) && sizeof($data['taxes'])>0)
                            @foreach($data['taxes'] as $taxes)
                                @foreach($taxes as $tax_name=>$tax)
                                <tr>
                                    <td align="right" width="50%">{{$tax_name}}({{$tax->percent}})</td> <td align="right">{{$tax->calc_tax}}</td>
                                </tr>
                                @endforeach
                            @endforeach
                        @endif
                        <tr>
                            <td align="right" width="50%"><b>Total</b></td> <td align="right"><b>{{$data['total']}}</b></td>
                        </tr>
                        <tr>
                            <td align="right" width="50%"><b>Credits Remaining</b></td> <td align="right"><b>{{$data['available_credit']}}</b></td>
                        </tr>

                    </table>
                </td>
            </tr>
            <tr style="height: 100px">
                <td colspan="2" style="vertical-align: bottom;" align="center" class="col-md-6" >
                    <span style="font-size: 10px;">Authorized Signature</span>
                </td>
            </tr>
        </table>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="modal-footer" id="edit_book_btns">
    <button type="button" class="btn btn-default" onclick="printData()" data-dismiss="modal">Print</button>
</div>