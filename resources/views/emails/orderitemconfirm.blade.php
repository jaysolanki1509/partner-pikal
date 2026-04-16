<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Customer Order Detail</title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- So that mobile will display zoomed in -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- enable media queries for windows phone 8 -->
    <meta name="format-detection" content="telephone=no">
    <!-- disable auto telephone linking in iOS -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
</head>

<body>

    <?php

    use Illuminate\Support\Facades\Log;

    $order = \App\OrderDetails::where('order_id', $order_id)->first();

    ?>

    @if( isset($order) && sizeof($order) > 0 )

    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
        <tbody>
            <tr>
                <td align="center" valign="top">
                    <div id="m_-891093777296483278template_header_image">
                    </div>
                    <span class="HOEnZb">
                        <font color="#888888"></font>
                    </span>
                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="m_-891093777296483278template_container" style="background-color:#fdfdfd;border:1px solid #dcdcdc;border-radius:3px!important">
                        <tbody>
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="m_-891093777296483278template_header" style="background-color:#557da1;border-radius:3px 3px 0 0!important;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
                                        <tbody>
                                            <tr>
                                                <td id="m_-891093777296483278header_wrapper" style="padding:36px 48px;display:block">
                                                    <h1 style="color:#ffffff;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left">Order Item Confirmed</h1>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    <span class="HOEnZb">
                                        <font color="#888888"></font>
                                    </span>
                                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="m_-891093777296483278template_body">
                                        <tbody>
                                            <tr>
                                                <td valign="top" id="m_-891093777296483278body_content" style="background-color:#fdfdfd">
                                                    <span class="HOEnZb">
                                                        <font color="#888888"></font>
                                                    </span>
                                                    <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td valign="top" style="padding:48px">
                                                                    <div id="m_-891093777296483278body_content_inner" style="color:#737373;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left">

                                                                        <p style="margin:0 0 16px">Customer order items has been confirmed. The KOT details is as follows:</p>

                                                                        <h2 style="color:#557da1;display:block;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:16px 0 8px;text-align:left">
                                                                            <a class="m_-891093777296483278link" href="javascript:void(0)" style="color:#557da1;font-weight:normal;text-decoration:underline" target="_blank">
                                                                                Order #{{ $order_id }}</a>
                                                                        </h2>

                                                                        <table class="m_-891093777296483278td" cellspacing="0" cellpadding="6" style="width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#737373;border:1px solid #e4e4e4" border="1">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="m_-891093777296483278td" scope="col" style="text-align:left;color:#737373;border:1px solid #e4e4e4;padding:12px">Item</th>
                                                                                    <th class="m_-891093777296483278td" scope="col" style="text-align:left;color:#737373;border:1px solid #e4e4e4;padding:12px">Quantity</th>
                                                                                    <th class="m_-891093777296483278td" scope="col" style="text-align:left;color:#737373;border:1px solid #e4e4e4;padding:12px">Price</th>
                                                                                    <th class="m_-891093777296483278td" scope="col" style="text-align:left;color:#737373;border:1px solid #e4e4e4;padding:12px">Amount</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @if( isset($kot) && sizeof($kot) > 0 )

                                                                                @foreach( $kot as $kt )
                                                                                <?php
                                                                                Log::info($kt['server_id']);
                                                                                $kt_row = \App\Kot::where('id', $kt['server_id'])->withTrashed()->first();
                                                                                ?>
                                                                                <!-- if kot is cancelled than row display in red color -->
                                                                                <tr @if( !is_null($kt_row->deleted_at)) style="background-color:red" @endif class="m_-891093777296483278order_item">

                                                                                    <td class="m_-891093777296483278td" style="text-align:left;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word; @if( !is_null($kt_row->deleted_at)) color:white; @else color:#737373; @endif padding:12px">
                                                                                        {{ urldecode($kt_row->item_name)}} <br><small></small>
                                                                                    </td>
                                                                                    <td class="m_-891093777296483278td" style="text-align:right;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif; @if( !is_null($kt_row->deleted_at)) color:white; @else color:#737373; @endif padding:12px">
                                                                                        {{ $kt_row->quantity }}
                                                                                    </td>
                                                                                    <td class="m_-891093777296483278td" style="text-align:right;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif; @if( !is_null($kt_row->deleted_at)) color:white; @else color:#737373; @endif padding:12px"><span class="m_-891093777296483278woocommerce-Price-amount amount">
                                                                                            <span class="m_-891093777296483278woocommerce-Price-currencySymbol">&#8377;</span>{{ $kt_row->price }}</span>
                                                                                    </td>
                                                                                    <td class="m_-891093777296483278td" style="text-align:right;vertical-align:middle;border:1px solid #eee;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif; @if( !is_null($kt_row->deleted_at)) color:white; @else color:#737373; @endif padding:12px"><span class="m_-891093777296483278woocommerce-Price-amount amount">
                                                                                            <span class="m_-891093777296483278woocommerce-Price-currencySymbol">&#8377;</span>{{ $kt_row->price*$kt_row->quantity }}</span>
                                                                                    </td>

                                                                                </tr>
                                                                                @endforeach

                                                                                @endif
                                                                            </tbody>

                                                                        </table>

                                                                        <h2 style="color:#557da1;display:block;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:16px 0 8px;text-align:left">
                                                                            Customer details
                                                                        </h2>

                                                                        <ul>
                                                                            <li>
                                                                                <strong>Name:</strong>
                                                                                <span class="m_-891093777296483278text" style="color:#505050;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
                                                                                    <a href="mailto:harsh@savitriya.com" target="_blank">
                                                                                        {{ $order->name }}
                                                                                    </a>
                                                                                </span>
                                                                            </li>
                                                                            <li>
                                                                                <strong>Mobile:</strong>
                                                                                <span class="m_-891093777296483278text" style="color:#505050;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
                                                                                    {{ $order->user_mobile_number }}
                                                                                </span>
                                                                            </li>
                                                                        </ul>
                                                                        <span class="HOEnZb">
                                                                            <font color="#888888"></font>
                                                                        </span>

                                                                        <table id="m_-891093777296483278addresses" cellspacing="0" cellpadding="0" style="width:100%;vertical-align:top" border="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="m_-891093777296483278td" valign="top" width="50%">
                                                                                        <h3 style="color:#557da1;display:block;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:16px;font-weight:bold;line-height:130%;margin:16px 0 8px;text-align:left">Address</h3>

                                                                                        <p class="m_-891093777296483278text" style="color:#505050;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;margin:0 0 16px">
                                                                                            {{ $order->address }}
                                                                                        </p>

                                                                                        <span class="HOEnZb">
                                                                                            <font color="#888888">
                                                                                            </font>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <span class="HOEnZb">
                                                                            <font color="#888888"></font>
                                                                        </span>
                                                                    </div>
                                                                    <span class="HOEnZb">
                                                                        <font color="#888888"></font>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <span class="HOEnZb">
                                                        <font color="#888888"></font>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <span class="HOEnZb">
                                        <font color="#888888"></font>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="10" cellspacing="0" width="600" id="m_-891093777296483278template_footer">
                                        <tbody>
                                            <tr>
                                                <td style="color:red">* KOT with red color has been cancelled </td>
                                            </tr>
                                            <tr>
                                                <td valign="top" style="padding:0">
                                                    <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2" valign="middle" id="m_-891093777296483278credit" style="padding:0 48px 48px 48px;border:0;color:#99b1c7;font-family:Arial;font-size:12px;line-height:125%;text-align:center">
                                                                    <p>Pikal</p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    @endif

</body>

</html>