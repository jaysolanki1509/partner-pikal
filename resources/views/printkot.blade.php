<div id="invoice_print">

        @if( $order['updated'] == 1 && $order['duplicate_watermark'] == 1)
            <div style="position: absolute; top: 0px; right: 0px; bottom: 0px; left: 0px; margin: auto; width: 100%; display: block; height: 50px; float: none; text-align: center; text-transform: uppercase; font-weight: bold; font-size: 36px; transform: rotate(-45deg); color: rgb(0, 0, 0); opacity: 0.2;">duplicate</div>
        @endif

        @if( isset($order) && !empty($order) )

        <table cellpadding="0" cellspacing="0" width="100%" style=" font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif,Times New Roman;">
             <tr>
                <td colspan="2" style=" vertical-align:top;text-align: center;font-weight:bold;font-size:25px; text-transform: uppercase;">
                    @if( isset($order['invoice_title']) && $order['invoice_title'] != '' )
                        {!! $order['invoice_title'] !!}
                    @else
                        {!! $order['ot_name'] !!}
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2" style="  padding:5px;vertical-align:top;">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td height="60" class="title" style="vertical-align: top; padding: 5px; color: rgb(0, 0, 0); width:50%; height:10% font-size: 12px; font-family: 'Times New Roman';white-space: nowrap;">
                                {{-- {!! nl2br($order['ot_address']) !!}<br> --}}
                                Date: {!! $order['date'] !!}<br>
                                @if( $order['ot_id'] != 82)
                                Order Type:</b> {!! str_replace('_',' ',ucwords($order['or_type'])) !!}
                            @endif
                            </td>

                            <td style="padding:5px 0px 5px 50px;vertical-align:top; width:50%; height:10% font-size: 12px; font-family: 'Times New Roman';">

                                @if( isset($order['customer']) && isset($order['customer'][0]) && $order['customer'][0] != '')
                                    <b>Name:</b> {{ $order['customer'][0] }}<br>
                                @endif
                                {{-- Invoice No.:</b> {!! $order['or_number'] !!}<br> --}}
                                {{-- Paid Via :</b> {!! $order["payment_mode"] !!}<br> --}}
                                {!! $order['order_lable'] !!} No.: {!! $order['table'] !!}<br>
                               

                            </td>
                        </tr>
                        @if(isset($order['custom_fields']) && !empty($order['custom_fields']))
                            <?php $i=0; ?>
                            @foreach($order['custom_fields'] as $custom)
                                @if($i%2 == 0)<tr>@endif
                                    @if($i%2 == 0)
                                        <td class="title" style="vertical-align: top; padding: 0px 0px 0px 5px; color: rgb(0, 0, 0); width: 282px; height:0px; font-size: 12px; font-family: 'Times New Roman';">
                                    @else
                                        <td style="padding:0px 0px 0px 50px;vertical-align:top; width:300px;height:0px; font-size: 12px; font-family: 'Times New Roman';">
                                    @endif
                                        {{$custom['label']}} : {{$custom['value']}}
                                    </td>
                                    <?php $i++; ?>
                                @if($i%2 == 0)</tr>@endif
                            @endforeach
                        @endif
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid rgb(0, 0, 0);border-top: 1px solid rgb(0, 0, 0);
                            font-weight:bold; width:400px;">
                                Items
                            </td>

                            <td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid rgb(0, 0, 0);border-top: 1px solid rgb(0, 0, 0);
                        font-weight:bold; width:100px;">Qty</td>

                    
                        </tr>
                        @if( isset($order['item']) && !empty($order['item']) )
                            @for( $i=0; $i<sizeof($order['item']); $i++)
                                <tr>
                                    <td class="bill-item">{!! $order['item'][$i]['name'] !!}</td>
                                    <td class="bill-item">{!! $order['item'][$i]['qty'] !!}</td>
                                </tr>
                            @endfor
                        @endif
                    </table>
                </td>
            </tr>
        </table>


@else
    <span style="text-align:center;">Order detail not found.</span>
@endif
</div>