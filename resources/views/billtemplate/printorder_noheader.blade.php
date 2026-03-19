<div id="invoice_print">

    @if( isset($order['ot_taxes']) && sizeof ($order['ot_taxes']) > 0 )

        <div id="ot_taxes">
            <?php $cnt = 1;?>
            @foreach($order['ot_taxes'] as $otk => $otv )
                <input type="hidden" class="tax_name" id="tax_name_{!! $cnt !!}" value="{!! $otk !!}" />
                <input type="hidden" class="tax_perc" id="tax_perc_{!! $cnt !!}" value="{!! $otv !!}" />
                <?php $cnt++;?>
            @endforeach
        </div>
    @endif
    @if( $order['updated'] == 1 )
        <div style="position: absolute; top: 0px; right: 0px; bottom: 0px; left: 0px; margin: auto; width: 100%; display: block; height: 50px; float: none; text-align: center; text-transform: uppercase; font-weight: bold; font-size: 36px; transform: rotate(-45deg); color: rgb(0, 0, 0); opacity: 0.2;">duplicate</div>
    @endif

    @if( isset($order) && sizeof($order) )

        <table cellpadding="0" cellspacing="0" style=" max-width:100%;
                                                        margin:auto;
                                                        padding:30px 20px;
                                                        padding-top: 130px;
                                                        /*border:1px solid #eee;
                                                        box-shadow:0 0 10px rgba(0, 0, 0, .15);*/
                                                        /*font-size:14px;*/
                                                        line-height:24px;
                                                        font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
                                                        color:#555;   width:100%;
                                                        line-height:inherit;
                                                        text-align:left;
                                                        max-width:370px;">

            <tr>
                <td colspan="4" style="  padding:5px;vertical-align:top;">
                    <table>
                        <tr>
                            <td height="60" class="title" style="vertical-align: bottom; padding: 2px; color: rgb(51, 51, 51); width: 300px; height:40px; font-size: 10px;">
                                Table No.: {!! $order['table'] !!}<br>
                                PAX: {!! $order['person'] !!}
                            </td>

                            <td style="padding:2px 0px 2px 50px;vertical-align:top; text-align: right; width:300px;height:40px; font-size: 10px; ">
                                {!! $order['or_number'] !!}<br><br>
                                Date: {!! $order['date'] !!}<br>
                                Order Type: {!! str_replace('_',' ',ucwords($order['or_type'])) !!}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4"><hr style="margin-bottom: 2px !important;margin-top: 2px !important;"/></td>
            </tr>
            <tr>
                <td colspan="4">
                    <table style="min-height: 350px; display: block;">
                        <tr>
                            <td style="font-size:10px; padding:2px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;
                            font-weight:bold; width:600px;">
                                Items
                            </td>

                            <td style="font-size:10px; padding:2px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;
                                font-weight:bold; width:50px;">Qty</td>

                            <td style="padding:2px;
                            vertical-align:top; font-size:10px;  background:#eee;
                            border-bottom:1px solid #ddd;
                            font-weight:bold; width:50px;"> Price
                            </td>

                            <td style="  padding:2px;
                        vertical-align:top; font-size:10px;  background:#eee;
                        border-bottom:1px solid #ddd;
                        font-weight:bold;">Amount
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4"><hr style="margin-bottom: 2px !important;margin-top: 2px !important;"/></td>
                        </tr>
                        @if( isset($order['item']) && sizeof($order['item']) > 0 )
                            @for( $i=0; $i<sizeof($order['item']); $i++)
                                <tr>
                                    <td style="padding:2px 2px 0px 2px;vertical-align:top;font-size:10px;">{!! $order['item'][$i]['name'] !!}</td>
                                    <td style="padding:2px 2px 0px 2px;vertical-align:top;font-size:10px;">{!! $order['item'][$i]['qty'] !!}</td>
                                    <td style="padding:2px 2px 0px 2px;vertical-align:top;font-size:10px;">{!! $order['item'][$i]['price'] !!}</td>
                                    <td style="padding:2px 2px 0px 2px;vertical-align:top;text-align:right;font-size:10px;">{!! number_format($order['item'][$i]['amount'],2) !!}</td>
                                </tr>
                            @endfor
                        @endif
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="4"><hr style="margin-bottom: 2px !important;margin-top: 2px !important;"/></td>
            </tr>

            <tr class="item" style="height:15px">
                <td colspan="2" style="  padding:0 2px 0 2px; vertical-align:top; font-size:10px;">Sub Total</td>
                <td colspan="2" style="padding:0 2px 0 2px; vertical-align:top;text-align:right; font-size:10px">&#8377; <span id="sub_total">{!! number_format($order['sub_total'],2) !!}</span></td>
            </tr>

            @if($order['discount'] > 0 )
                <tr class="item" style="height:15px">
                    <td style="  padding:0 2px 0 2px;vertical-align:top; font-size:10px;">Discount</td>

                    <td colspan="3" style="padding:0 2px 0 2px;vertical-align:top; text-align:right; font-size:10px">
                        &#8377; {!! number_format($order['discount'],2) !!}
                    </td>
                </tr>
            @endif

            {{--Display taxes--}}

            @if( isset($order['taxes'] ) && sizeof($order['taxes']) > 0 )

                @foreach( $order['taxes'] as $tx )

                    @if(gettype($tx) == 'string')
                        <?php $tx1 = json_decode($tx);?>
                    @else
                        <?php $tx1 = $tx;?>
                    @endif
                    @foreach( $tx1 as $key1=>$t)
                        <tr class="item" style="height:15px">
                            <td style="width:50%;padding:0 2px 0 2px; vertical-align:top;font-size:10px;  ">{!! ucwords($key1) !!} {!! $t->percent.''."%" !!}</td>
                            <td colspan="3" style="padding:0 2px 0 2px; vertical-align:top;text-align:right;font-size:10px;">
                                &#8377; {!! number_format($t->calc_tax,2) !!}
                            </td>
                        </tr>

                    @endforeach
                @endforeach
            @endif

            @if( $order['round_off'] != 0 )
                <tr class="item" style="height:15px">
                    <td style="  padding:0 2px 0 2px; vertical-align:top;  border-bottom:1px solid #eee; font-size:10px;">Round off</td>
                    <td colspan="3" style="font-size:10px;padding:0 2px 0 2px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">&#8377; {!! number_format($order['round_off'],2) !!}</td>
                </tr>
            @endif

            <tr style="height:15px">
                <td style=" font-size: 10px; padding:2px;vertical-align:top;border-top:2px solid #ccc;font-weight:bold;text-align:left; ">
                    Total @if( isset($order['taxes'] ) && sizeof($order['taxes']) > 0 )(Including Tax)@endif: &nbsp; &nbsp;
                </td>
                <td  colspan="3" style=" font-size: 10px; padding:2px;vertical-align:top;width:500px;  border-top:2px solid #ccc;font-weight:bold;text-align:right; ">
                    &#8377; {!! number_format($order['total'],2) !!}
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding:2px 2px 0 2px;font-size:8px;border-top:2px solid #ccc;">
                    @if( isset($order['service_tax_no']) && $order['service_tax_no'] != '' )
                        <b>Service Tax No:</b> {!! $order['service_tax_no'] !!}
                    @endif
                    @if( isset($order['vat_no']) && $order['vat_no'] != '' )
                        <b style="padding-left: 15px;">Vat No:</b> {!! $order['vat_no'] !!}<br>
                    @endif
                </td>
            </tr>
        </table>


    @else
        <span style="text-align:center;">Order detail not found.</span>
    @endif
</div>