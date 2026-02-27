<div id="invoice_print">

        @if( $order['updated'] == 1 && $order['duplicate_watermark'] == 1)
            <div style="position: absolute; top: 0px; right: 0px; bottom: 0px; left: 0px; margin: auto; width: 100%; display: block; height: 50px; float: none; text-align: center; text-transform: uppercase; font-weight: bold; font-size: 36px; transform: rotate(-45deg); color: rgb(0, 0, 0); opacity: 0.2;">duplicate</div>
        @endif
        
        @if( isset($order) )

        <table cellpadding="0" cellspacing="0" style=" max-width:100%;
                                                        margin:auto;
                                                        padding:30px;
                                                        /*border:1px solid #eee;
                                                        box-shadow:0 0 10px rgba(0, 0, 0, .15);*/
                                                        /*font-size:14px;*/
                                                        line-height:24px;
                                                        font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
                                                        color:#555;   width:100%;
                                                        line-height:inherit;
                                                        text-align:left;
                                                        max-width:600px;">
            <tr><td colspan="4" style="  padding:5px;vertical-align:top;text-align: center;font-weight:100;font-size:12px;">CASH MEMO / RETAIL INVOICE</td></tr>
            <tr>
                <td colspan="4" style=" vertical-align:top;text-align: center;font-weight:bold;font-size:25px;">
                    @if( isset($order['invoice_title']) && $order['invoice_title'] != '' )
                        {!! $order['invoice_title'] !!}
                    @else
                        {!! $order['ot_name'] !!}
                    @endif
                </td>
            </tr>
            <tr><td colspan="4" style=" vertical-align:top;text-align: center;font-weight:bold;font-size:18px;">{!! $order['company_name'] or '' !!}</td></tr>
            <tr>
                <td colspan="4" style="  padding:5px;vertical-align:top;">
                    <table>
                        <tr>
                            <td height="60" class="title" style="vertical-align: top; padding: 5px; color: rgb(51, 51, 51); width: 282px; height:80px; font-size: 12px;">
                                {!! nl2br($order['ot_address']) !!}<br>
                                Date: {!! $order['date'] !!}<br>
                            </td>

                            <td style="padding:5px 0px 5px 50px;vertical-align:top; width:300px;height:80px; font-size: 12px; ">

                                @if( isset($order['customer']) && $order['customer'] != '' )
                                    <b>Name:</b> {!! $order['customer'] !!}<br>
                                @endif
                                Invoice No.:</b> {!! $order['or_number'] !!}<br>
                                Paid Via :</b> {!! $order["payment_mode"] !!}<br>
                                {!! $order['order_lable'] !!} No.: {!! $order['table'] !!}<br>
                                @if( $order['ot_id'] != 82)
                                    Order Type:</b> {!! str_replace('_',' ',ucwords($order['or_type'])) !!}
                                @endif

                            </td>
                        </tr>
                        @if(isset($order['custom_fields']) )
                            <?php $i=0; ?>
                            @foreach($order['custom_fields'] as $custom)
                                @if($i%2 == 0)<tr>@endif
                                    @if($i%2 == 0)
                                        <td class="title" style="vertical-align: top; padding: 0px 0px 0px 5px; color: rgb(51, 51, 51); width: 282px; height:0px; font-size: 12px;">
                                    @else
                                        <td style="padding:0px 0px 0px 50px;vertical-align:top; width:300px;height:0px; font-size: 12px; ">
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
                    <table style="min-height: 300px; display: block;">
                        <tr>
                            <td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;
                            font-weight:bold; width:400px;">
                                Items
                            </td>

                            <td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;
                        font-weight:bold; width:100px;">Qty</td>

                            <td style="padding:5px;
                            vertical-align:top;   background:#eee;
                            border-bottom:1px solid #ddd;
                            font-weight:bold; width:100px;"> Price
                            </td>

                            <td style="  padding:5px;
                        vertical-align:top;   background:#eee;
                        border-bottom:1px solid #ddd;
                        font-weight:bold;">Amount
                            </td>
                        </tr>
                        @if( isset($order['item']) )
                            @for( $i=0; $i<sizeof($order['item']); $i++)
                                <tr>
                                    <td class="bill-item">{!! $order['item'][$i]['name'] !!}</td>
                                    <td class="bill-item">{!! $order['item'][$i]['qty'] !!}</td>
                                    <td class="bill-item">{!! number_format($order['item'][$i]['price'],2) !!}</td>
                                    <td class="bill-item" style="text-align: right">{!! number_format($order['item'][$i]['amount'],2) !!}</td>
                                </tr>

                                @if( ( isset($order['item'][$i]['slab']) ) || ( isset($order['item'][$i]['discount']) ) )
                                    <tr style="line-height:initial;">
                                        <td class="bill-item-tax">
                                            <?php $j=0;?>
                                            @if ( isset($order['item'][$i]['slab']) )
                                                @for( $j=0; $j<sizeof($order['item'][$i]['slab']); $j++)
                                                    @if ( $j > 0 ) | @endif
                                                        {!! $order['item'][$i]['slab'][$j]['tax_name'] !!}({!! $order['item'][$i]['slab'][$j]['tax_parc'] !!}%) : {!! $order['item'][$i]['slab'][$j]['tax_val'] !!}
                                                @endfor
                                            @endif

                                            @if ( isset($order['item'][$i]['discount']) )
                                                @if ( $j > 0 ) | @endif
                                                Discount @if($order['item'][$i]['discount']['type'] == 'percentage')({{ $order['item'][$i]['discount']['value'] }}%) @endif : {{ $order['item'][$i]['discount']['amount'] }}
                                            @endif

                                        </td>
                                        <td class="bill-item"></td>
                                        <td class="bill-item"></td>
                                        <td class="bill-item" style="text-align: right"></td>
                                    </tr>
                                @endif

                                @if( isset($order['item'][$i]['options']) )
                                    @for( $j=0; $j<sizeof($order['item'][$i]['options']); $j++)
                                        <tr>
                                            <td class="bill-option-item">{!! $order['item'][$i]['options'][$j]['name'] !!}</td>
                                            <td class="bill-item"></td>
                                            <td class="bill-item"></td>
                                            <td class="bill-item" style="text-align: right"></td>
                                        </tr>
                                    @endfor
                                @endif

                            @endfor
                        @endif
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="4"><hr style="margin-bottom: 10px !important;margin-top: 10px !important;"/></td>
            </tr>

            <tr class="item" style="height:15px">
                <td class='col-md-6' colspan="2" style="padding:0 5px 0 5px; vertical-align:top; font-size:12px;">Sub Total</td>
                <td class='col-md-6' colspan="2" style="padding:0 5px 0 5px; vertical-align:top;text-align:right; font-size:12px"><span id="sub_total">{!! number_format($order['sub_total'],2) !!}</span></td>
            </tr>

            @if($order['item_discount'] > 0  && $order['discount_after_tax'] == 0 )
                <tr class="item" style="height:15px">
                    <td class='col-md-6' style="  padding:0 5px 0 5px;vertical-align:top; font-size:12px;">Item Discount</td>
                    <td class='col-md-6' colspan="3" style="padding:0 5px 0 5px;vertical-align:top; text-align:right; font-size:12px">
                        {!! number_format($order['item_discount'],2) !!}
                    </td>
                </tr>
            @endif
            @if($order['discount'] > 0  && $order['discount_after_tax'] == 0 && $order['itemwise_tax'] == 0 )
                <tr class="item" style="height:15px">
                    @if(isset($order['discount_type']) 
                        && $order['discount_type'] != "fixed"  && trim($order['discount_type']) != "")
                        <td class='col-md-6' style="  padding:0 5px 0 5px;vertical-align:top; font-size:12px;">Discount ({{$order['discount_type']}})</td>
                    @else
                        <td class='col-md-6' style="  padding:0 5px 0 5px;vertical-align:top; font-size:12px;">Discount</td>
                    @endif
                    <td class='col-md-6' colspan="3" style="padding:0 5px 0 5px;vertical-align:top; text-align:right; font-size:12px">
                        {!! number_format($order['discount'],2) !!}
                    </td>
                </tr>
            @endif

            {{--Display taxes--}}

            @if( isset($order['taxes'] ) )

                @foreach( $order['taxes'] as $tx )

                    @if(gettype($tx) == 'string')
                        <?php $tx1 = json_decode($tx);?>
                    @else
                        <?php $tx1 = $tx;?>
                    @endif

                    @foreach( $tx1 as $key1=>$t)
                            <tr class="item" style="height:15px">
                                {{--when itemwise tax enable--}}
                                @if( $t->percent != "0" )
                                    <td style="width:50%;padding:0 5px 0 5px; vertical-align:top;font-size:12px;  ">{!! ucwords($key1) !!} {!! $t->percent.''."%" !!}</td>
                                @else
                                    <td style="width:50%;padding:0 5px 0 5px; vertical-align:top;font-size:12px;  ">{!! ucwords($key1) !!}</td>
                                @endif
                                <td colspan="3" style="padding:0 5px 0 5px; vertical-align:top;text-align:right;font-size:12px;">
                                    {!! number_format($t->calc_tax,2) !!}
                                </td>
                            </tr>

                    @endforeach

                @endforeach
            @endif

            @if($order['item_discount'] > 0  && $order['discount_after_tax'] == 1 )
                <tr class="item" style="height:15px">
                    <td class='col-md-6' style="  padding:0 5px 0 5px;vertical-align:top; font-size:12px;">Item Discount</td>
                    <td class='col-md-6' colspan="3" style="padding:0 5px 0 5px;vertical-align:top; text-align:right; font-size:12px">
                        {!! number_format($order['item_discount'],2) !!}
                    </td>
                </tr>
            @endif

            @if( $order['discount'] > 0  && ( $order['discount_after_tax'] == 1 || $order['itemwise_tax'] == 1 ) )
                <tr class="item" style="height:15px">
                    @if(isset($order['discount_type'])
                        && $order['discount_type'] != "fixed" && trim($order['discount_type']) != "")
                        <td class='col-md-6' style="  padding:0 5px 0 5px;vertical-align:top; font-size:12px;">Discount ({{$order['discount_type']}})</td>
                    @else
                        <td class='col-md-6' style="  padding:0 5px 0 5px;vertical-align:top; font-size:12px;">Discount</td>
                    @endif

                    <td class='col-md-6' colspan="3" style="padding:0 5px 0 5px;vertical-align:top; text-align:right; font-size:12px">
                        {!! number_format($order['discount'],2) !!}
                    </td>
                </tr>
            @endif

            @if($order['delivery_charge'] > 0 )
                <tr class="item" style="height:15px">
                    <td class='col-md-6' colspan="3" style="padding:0 5px 0 5px; vertical-align:top; font-size:12px;">Delivery Charge</td>
                    <td class='col-md-6' colspan="2" style="padding:0 5px 0 5px; vertical-align:top;text-align:right; font-size:12px">&#8377; <span id="sub_total">{!! number_format($order['delivery_charge'],2) !!}</span></td>
                </tr>
            @endif

            @if( $order['round_off'] != 0 )
                <tr class="item" style="height:15px">
                    <td class='col-md-6' style="  padding:0 5px 0 5px; vertical-align:top;  border-bottom:1px solid #eee; font-size:12px;">Round off</td>
                    <td class='col-md-6' colspan="3" style="font-size:12px;padding:0 5px 0 5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">{!! number_format($order['round_off'],2) !!}</td>
                </tr>
            @endif

            <tr style="height:15px">
                <td style="  padding:5px;vertical-align:top;border-top:2px solid #ccc;font-weight:bold;text-align:left; ">
                    Total @if( isset($order['taxes'] ) )(Including Tax)@endif: &nbsp; &nbsp;
                </td>
                <td  colspan="3" style="  padding:5px;vertical-align:top;width:500px;  border-top:2px solid #ccc;font-weight:bold;text-align:right; ">
                    {!! number_format($order['total'],2) !!}
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding:5px 5px 0 5px;font-size:12px;border-top:2px solid #ccc;">
                    <?php $i=1; ?>
                    @if( isset($order['tax_details'][0]) && $order['tax_details'][0] != '' )
                        @foreach( $order['tax_details'][0] as $tx_fld=>$tx_val)
                            <b style="font-weight: 700">{!! $tx_fld !!}:</b> {!! $tx_val !!}&nbsp;&nbsp;&nbsp;&nbsp;
                            @if( $i%3 == 0 )
                               <br>
                            @endif
                            <?php $i++;?>
                        @endforeach
                    @endif
                </td>
            </tr>
            <tr>
                @if(isset($order['city_name']->name) )
                    <td class='col-md-6' colspan="4" style="text-align: center">Subject To {{$order['city_name']->name}} Jurisdiction</td>
                @else
                    <td class='col-md-6' colspan="4" style="text-align: center">Subject To Local Jurisdiction</td>
                @endif
            </tr>
        </table>


@else
    <span style="text-align:center;">Order detail not found.</span>
@endif
</div>