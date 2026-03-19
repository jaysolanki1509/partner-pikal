<?php
     $user_name = \Illuminate\Support\Facades\Auth::user()->user_name;
?>
@extends('partials.default')
@section('pageHeader-left')
    Bill Template
@stop
@section('pageHeader-right')
    <a href="/addCustomField" class="btn btn-primary"><i class="zmdi zmdi-wb-auto"></i>Custom Fields</a>
    <a href="/update-bill-template" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a>
@stop
@section('content')

    <div class="col-md-5 col-md-offset-4 col-sm-4 col-sm-offset-3">

            <div class="widget-wrap" style="float: left">
                <div class="widget-container">
                    <div class="widget-content">
                        <form class="j-forms">
                            <div class="login-form-content">

                                @if(isset($line_arr->sequence) && sizeof($line_arr->sequence) > 0 )

                                    <?php $lbl_cnt = 0;$font_size="22px;";$align = "left";?>

                                    @for( $i=0; $i < sizeof($line_arr->sequence); $i++ )

                                        <?php $font_size="22px;";$align = "left"; ?>

                                            @if ( $line_arr->font[$i] == 6 )
                                                <?php $font_size = "10px;"?>
                                            @endif

                                            @if ( $line_arr->align[$i] == 'center')
                                                <?php $align = "text-align:center";?>
                                            @else
                                                <?php $align = "float:left";?>
                                            @endif


                                            @if( $i > 0 )
                                                @if ( $line_arr->sequence[$i] == $line_arr->sequence[$i-1])
                                                    <?php $align = "float:right";?>
                                                @else
                                                    <div class="col-md-12" style="font-size:{!! $font_size !!};">
                                                @endif
                                            @else
                                                <div class="col-md-12" style="font-size:{!! $font_size !!};">
                                            @endif

                                                    @if ( $line_arr->key[$i] == 'outlet_name' )

                                                        <div style="{!! $align !!}">{!! $outlet->name !!}</div>

                                                    @elseif ( $line_arr->key[$i] == 'address' )

                                                        <div style="{!! $align !!}">{!! $outlet->address !!}</div>

                                                    @elseif ( $line_arr->key[$i] == 'customer' )

                                                        <div style="{!! $align !!}">{!! $line_arr->customer !!} : {!! 'Customer Name' !!}</div>

                                                    @elseif ( $line_arr->key[$i] == 'customer_mobile' )

                                                        <div style="{!! $align !!}">{!! 'Mobile' !!} : {!! '98******98' !!}</div>

                                                    @elseif ( $line_arr->key[$i] == 'customer_address' )

                                                        <div style="{!! $align !!}">{!! 'Address' !!} : {!! 'Address Detail...' !!}</div>

                                                    @elseif ( $line_arr->key[$i] == 'bill_lable' )

                                                        <div style="{!! $align !!}">{!! $line_arr->bill_lable !!}</div>

                                                    @elseif ( $line_arr->key[$i] == 'order_type' )

                                                        <div style="{!! $align !!}">{!! "Dine In" !!}</div>

                                                    @elseif( $line_arr->key[$i] == 'user_name' )

                                                        <div style="{!! $align !!}">{!! $line_arr->user_lable !!}{!! $user_name !!}</div>

                                                    @elseif( $line_arr->key[$i] == 'invoice_no' )

                                                        <div style="{!! $align !!}">{!! $line_arr->inv_lable !!}{!! 'NV201707081001' !!}</div>

                                                    @elseif( $line_arr->key[$i] == 'pax' )

                                                        <div style="{!! $align !!}">{!! $line_arr->pax_lable !!}{!! '4' !!}</div>

                                                    @elseif( $line_arr->key[$i] == 'table_no' )

                                                        <div style="{!! $align !!}">{!! 'Table# 4' !!}</div>

                                                    @elseif( $line_arr->key[$i] == 'date' )

                                                        <div style="{!! $align !!}">{!! $line_arr->date_lable !!}{!! date('Y-m-d H:i A') !!}</div>

                                                    @elseif( $line_arr->key[$i] == 'footer_note' )

                                                        <div style="{!! $align !!}">{!! $line_arr->footer_note !!}</div>

                                                    @elseif( $line_arr->key[$i] == 'qr_code' )

                                                        <div style="{!! $align !!}">QR CODE IMAGE</div>

                                                    @elseif( $line_arr->key[$i] == 'tax_detail' )

                                                        @if( isset($outlet->tax_details) && $outlet->tax_details != '')
                                                            <?php
                                                                $tx_detail = json_decode($outlet->tax_details);

                                                                if ( isset($tx_detail[0]) && sizeof($tx_detail[0]) > 0 ) {
                                                                    foreach ( $tx_detail[0] as $tx_key=>$tx ) {?>
                                                                        <div style="{!! $align !!}">
                                                                            {!! $tx_key !!}:{!! $tx."\n" !!}
                                                                        </div><br>
                                                                   <?php }
                                                                }
                                                            ?>
                                                        @endif


                                                    @elseif( $line_arr->key[$i] == 'order_detail' )

                                                        <div>
                                                            <table style="width: 100%;">
                                                                <tr style="border-top: 1px dashed black;border-bottom: 1px dashed black">
                                                                    <th style="width: 60%">Items</th>
                                                                    <th style="width: 10%">Qty</th>
                                                                    <th style="width: 10%">Price</th>
                                                                    <th style="text-align: right">Amount</th>
                                                                </tr>
                                                                <tr style="border-bottom: 1px dashed black">
                                                                    <td>Item One</td><td>2</td><td>125</td><td style="text-align: right">250</td>
                                                                </tr>
                                                                <tr style="border-bottom: 1px dashed black">
                                                                    <td>Item Two</td><td>2</td><td>100</td><td style="text-align: right">200</td>
                                                                </tr>
                                                                <tr style="border-bottom: 1px dashed black">
                                                                    <td>Item Three</td><td>1</td><td>150</td><td style="text-align: right">150</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Sub Total</td><td></td><td></td><td style="text-align: right">600.00</td>
                                                                </tr>
                                                                @if( isset($outlet->default_taxes) && $outlet->default_taxes != '')

                                                                    @if( isset($outlet->taxes) && $outlet->taxes != '' )
                                                                        <?php $taxx = json_decode($outlet->taxes,true);
                                                                            if ( isset($outlet->default_taxes) &&  $outlet->default_taxes != '' ) {
                                                                                $d_taxes = json_decode($outlet->default_taxes,true);
                                                                                $dine_in = $d_taxes[0]['dine_in'];
                                                                            }

                                                                            $tax_total = 0;
                                                                        ?>
                                                                        @foreach( $taxx as $tx=>$tx_child )
                                                                            @if($tx == $dine_in)
                                                                                @foreach ( $tx_child as $child )
                                                                                    <tr>
                                                                                        <td>{{ $child['taxname'] }}&nbsp;{{ $child['taxparc'] }}</td><td></td><td></td><td style="text-align: right">{{number_format($child['taxparc']*6,2)}}</td>
                                                                                    </tr>
                                                                                    <?php $tax_total += $child['taxparc']*6; ?>
                                                                                @endforeach
                                                                            @endif
                                                                        @endforeach
                                                                        <?php $round_of_total = round($tax_total);
                                                                            $round_of_val = number_format(abs($tax_total - $round_of_total), 2, '.', ''); ?>
                                                                    @else
                                                                        <?php $tax_total = 0.00; $round_of_val = 0.00; ?>
                                                                    @endif
                                                                @else
                                                                    <?php $tax_total = 0.00; $round_of_val = 0.00; ?>
                                                                @endif
                                                                <tr style="border-bottom: 1px dashed black"><td colspan="3">Round Off</td><td style="text-align: right">{{$round_of_val}}</td></tr>
                                                                <tr><td colspan="3">Total</td><td style="text-align: right">{{number_format(600+$tax_total+$round_of_val,2)}}</td></tr>
                                                                <tr style="line-height: 5px;"><td colspan="4">*****************************************************************************</td></tr>
                                                            </table>
                                                        </div>

                                                    @elseif( $line_arr->key[$i] == 'dash_line' )

                                                        <div class="col-md-12" style="border-bottom: 1px dashed black"></div>

                                                    @elseif( $line_arr->key[$i] == 'lable' )

                                                        <div style="{!! $align !!}">{!! $line_arr->lable[$lbl_cnt] !!}</div>
                                                        <?php $lbl_cnt++; ?>

                                                    @elseif(isset($custom_fields) && sizeof($custom_fields)>0)

                                                        @foreach($custom_fields as $field)
                                                            @if(isset($field) && sizeof($field)>0)
                                                                @foreach($field as $key=>$val)
                                                                    @if( $line_arr->key[$i] == $key )
                                                                        <div style="{!! $align !!}">{!! isset($val[0]->label)?$val[0]->label:"" !!}
                                                                              @if($val[0]->type == 'date_time')
                                                                                :&nbsp;<?php echo date('Y-m-d h:m:s');?>
                                                                              @elseif($val[0]->type == 'date')
                                                                                  :&nbsp;<?php echo date('Y-m-d');?>
                                                                              @else
                                                                                  :&nbsp;XYZ
                                                                              @endif
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach

                                                    @endif

                                        @if ( isset($line_arr->sequence[$i+1]))
                                            @if ( $line_arr->sequence[$i] != $line_arr->sequence[$i+1])
                                                </div>
                                            @endif
                                        @else
                                            </div>
                                        @endif

                                    @endfor

                                @else
                                    <span id="empty_msg">No custom bill template has been added please add default template.</span>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>

@stop
