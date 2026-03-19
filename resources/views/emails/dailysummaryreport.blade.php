<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Pikal Report</title>
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
<style type="text/css">

    * { -webkit-text-size-adjust: none; }

    .ReadMsgBody {width: 100%; background-color: #ffffff;}
    .ExternalClass {width: 100%; background-color: #ffffff;}
    body	 {width: 100%; background-color: #ffffff; margin:0; padding:0; -webkit-font-smoothing: antialiased;font-family: Georgia, Times, serif}
    table {border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }




    @media only screen and (max-width: 640px)  {
        body[yahoo] .deviceWidth {width:440px!important; }
        body[yahoo] {text-align: center !important;}

        .

        body[yahoo]{
            display:block;
            width:100%;
        }
    }

    img{ max-width: 100%; height: auto; width: auto; /* ie8 */ }
    table [class=table] {margin:0 auto;}
    .table [class=table] {
        margin-bottom: 0 !important;
        max-width: 100%;
        width: 100%;}



    @media (max-width: 768px){

        .none{ display:none;}
        table {width:100% !important; margin:0 auto !important;}
        .table-1 {width:98% !important; margin:0 auto !important;
            margin: 0 10px;
        }
        img{ max-width: 97%; height: auto; width: auto; /* ie8 */ }
    }

    @media (max-width: 640px){
        table {width:100% !important; margin:0 auto !important;}
        .table-1 {width:100% !important; margin:0 auto !important;

        }
    }

    @media (max-width: 480px){
        table {width:100% !important; margin:0 auto !important;}
        .table-1 {margin:0 auto !important;
            width: 95% !important;
        }
        .table-2 {margin:0 auto !important;
            margin: 6px 7px !important;
            width: 95% !important;
        }
        .amount {font-size:1.2em !important;}
        .amount-2  {font-size:1.2em !important;}
        .amount > img {
            height: auto;
            width: 12%;
        }
        .amount-3 {font-size:1.1em !important;}
        .amount-4 {font-size:0.9em !important;}
        .text {font-size:0.9em !important;}
        .text-1	{font-size:0.7em !important;}
        .cancel-order {font-size:1.5em !important;}
        .cancel-order_text {
            font-size: 0.9em !important;
            text-align: center;
        }
        .cancel-order_text-1 {font-size: 0.9em !important;
            text-align: center;
        }
    }

    @media (max-width: 360px){
        table {width:100% !important; margin:0 auto !important;}
        .table-1 {margin:0 auto !important;
            width: 95% !important;
        }
        .table-2 {margin:0 auto !important;
            margin: 6px 7px !important;
            width: 95% !important;
        }
        .amount {font-size:1.2em !important;}
        .amount-2  {font-size:1.2em !important;}
        .amount > img {
            height: auto;
            width: 12%;
        }
        .amount-3 {font-size:1.1em !important;}
        .amount-4 {font-size:0.9em !important;}
        .text {font-size:0.9em !important;}
        .text-1	{font-size:0.7em !important;}
        .cancel-order {font-size:1.5em !important;}
        .cancel-order_text {
            font-size: 0.9em !important;
            text-align: center;
        }
        .cancel-order_text-1 {font-size: 0.9em !important;
            text-align: center;
        }
    }



</style>
<table class="table" border="0" align="center" cellspacing="0" cellpadding="0" style="border:1px solid #cccccc; width:700px; background-color:#cfcfcf; display:block;">
    <tr>
        <td width="700" height="20"></td>
    </tr>
    <tr>
        <td>
            <table class="table-2 deviceWidth" border="0" align="center" valign="middle" cellspacing="0" cellpadding="0" style="border:1px solid #cccccc; width:662px; background-color:#ffffff;">
                <tr>
                    <td>
                        <table class="table-1" border="0" align="center" cellspacing="0" cellpadding="0" style="width:642px; background-color:#ffffff;">
                            <tr>
                                <td style="font-size:22px;font-family: 'Lato', sans-serif; border-bottom:1px solid #eee;" align="center" width="642" valign="middle" height="50">{{ $data['outlet_name'] }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:16px;font-family: 'Lato', sans-serif; line-height:22px;border-bottom:1px solid #eee;" align="center" width="642" valign="middle" height="40">
                                    Outlet Open Duration -
                                    <strong> {{ $data['total_hours'] }} Hours</strong>
                                    | Started at
                                    <strong> {{ $data['start_time'] }} </strong>
                                    and Closed at
                                    <strong> {{ $data['end_time'] }} </strong>
                                </td>
                            </tr>
                            <tr>
                                <td width="642" height="10"></td>
                            </tr>
                            <tr>
                                <td width="642" align="center" height="41" bgcolor="#ef4e31" style="font-size:14px; color:#ffffff;font-family: 'Lato', sans-serif; text-align:center; text-transform:uppercase; font-style:italic;">Enjoy Food - Any Type, Any Time &amp; Any Where</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="662" height="8"></td>
                </tr>
                <tr>
                    <td><table class="table-1" style="width:642px;" cellspacing="0" cellpadding="0" border="0" align="center">
                            <tbody>
                            <tr>
                                <td>
                                    <table class="table-1" style="width:204px; background-color:#e3ffe9; height:233px;" cellspacing="0" cellpadding="0" border="0" align="center">
                                        <tbody>
                                            <tr>
                                                <td width="204" height="85" align="center"><img src="http://partner.pikal.io/images/order.png" alt="order-img" width="57" height="51"></td>
                                            </tr>
                                            <tr>
                                                <td height="76" style="color:#666666; font-size:14px; vertical-align: middle; font-family: 'Lato', sans-serif;" width="204" align="center"> TOTAL <br>
                                                    <span class="amount-2" style="color:#333333; font-size:24px; font-family: 'Lato', sans-serif; border-bottom:1px solid #86b8a4; padding-bottom: 15px;"> ORDERS </span></td>
                                            </tr>
                                            <tr>
                                                <td height="72" class="amount" style="color:#00a332; font-size:42px; vertical-align:middle; font-family: 'Lato', sans-serif; " width="204" align="center" > {{ $data['total_orders'] }} </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="11"></td>
                                <td>
                                    <table class="table-1" style="width:212px; background-color:#fff5d8; height:233px;" cellspacing="0" cellpadding="0" border="0" align="center">
                                        <tbody>
                                            <tr>
                                                <td width="212" height="85" align="center"><img src="http://partner.pikal.io/images/sale.png" alt="sale-img" width="60" height="42"></td>
                                            </tr>
                                            <tr>
                                                <td height="76" style="color:#666666; font-size:14px; vertical-align: middle; font-family: 'Lato', sans-serif" width="212" align="center"> TOTAL <br>
                                                    <span class="amount-2" style="color:#333333; font-size:24px; font-family: 'Lato', sans-serif; border-bottom:1px solid #d2be7f; padding-bottom: 15px;"> Sales </span></td>
                                            </tr>
                                            <tr>
                                                <td height="72" class="amount" style="color:#ff2700; font-size:42px; vertical-align:middle; font-family: 'Lato', sans-serif; " width="212" align="center">&#8377; {{ $data['total_sell'] }} </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="11"></td>
                                <td>
                                    <table class="table-1" style="width:204px; background-color:#dbf9ff; height:233px;" cellspacing="0" cellpadding="0" border="0" align="center">
                                        <tbody>
                                            <tr>
                                                <td width="204" height="85" align="center"><img src="http://partner.pikal.io/images/cash.png" alt="cash-img" width="40" height="47"></td>
                                            </tr>
                                            <tr>
                                                <td height="76" style="color:#666666; font-size:14px; vertical-align: middle; font-family: 'Lato', sans-serif;" width="204" align="center"> TOTAL <br>
                                                    <span class="amount-2" style="color:#333333; font-size:24px; font-family: 'Lato', sans-serif; border-bottom:1px solid #91c0cf; padding-bottom: 15px; "> Cash </span></td>
                                            </tr>
                                            <tr>
                                                <td height="72" class="amount" style="color:#0096ff; font-size:42px; vertical-align:middle; font-family: 'Lato', sans-serif; " width="204" align="center">&#8377; {{ $data['total_cash'] }} </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="662" height="10"></td>
                </tr>
                <tr>
                    <td>
                        <table class="table-1" border="0" align="center" cellspacing="0" cellpadding="0" style="width:642px; border-bottom:1px solid #dddddd;">
                            <tr>
                                <td>
                                    <table class="table-1" border="0" align="center" cellspacing="0" cellpadding="0" style="width:210px; height:80px; border-right:1px solid #dddddd; margin-bottom:2px;">
                                        <tr>
                                            <td height="32" class="amount-4" width="210" align="center" style="color:#888888; font-size:16px; font-family: 'Lato', sans-serif;vertical-align: bottom;"> Online </td>
                                        </tr>
                                        <tr>
                                            <td height="48" class="amount-3" width="210" align="center" style="color:#333333; font-size:24px; font-family: 'Lato', sans-serif; vertical-align: middle;">&#8377; {{ $data['total_prepaid'] }} </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table class="table-1" border="0" align="center" cellspacing="0" cellpadding="0" style="width:222px; height:80px; border-right:1px solid #dddddd; margin-bottom:2px;">
                                        <tr>
                                            <td height="32" class="amount-4" width="222" align="center" style="color:#888888; font-size:16px; font-family: 'Lato', sans-serif;vertical-align: bottom;"> Cheque </td>
                                        </tr>
                                        <tr>
                                            <td height="48" class="amount-3" width="222" align="center" style="color:#333333; font-size:24px; font-family: 'Lato', sans-serif;vertical-align: middle;">&#8377; {{ $data['total_cheque'] }} </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table class="table-1" border="0" align="center" cellspacing="0" cellpadding="0" style="width:210px; height:80px; margin-bottom:2px;">
                                        <tr>
                                            <td height="32" class="amount-4"  width="210" align="center" style="color:#888888; font-size:16px; font-family: 'Lato', sans-serif;vertical-align: bottom;"> UnPaid </td>
                                        </tr>
                                        <tr>
                                            <td height="48" class="amount-3"  width="210"align="center" style="color:#333333; font-size:24px; font-family: 'Lato', sans-serif;vertical-align: middle;">&#8377; {{ $data['pay_options']['UnPaid'] or 0.00 }} </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="30"></td>
                </tr>

                <tr>
                    <td>
                        <table class="table-1" style="width:642px;" cellspacing="0" cellpadding="0" border="0" align="center">
                            <tbody>
                            <tr>
                                <td class="cancel-order_text" style="font-size:14px; color:#ffffff; font-family: 'Lato', sans-serif; background-color:#8dc63f; border-right:1px solid #dddddd; " width="100" valign="middle" height="50" align="center"> Total Discount </td>
                                <td class="cancel-order_text" style="font-size:14px; color:#ffffff; font-family: 'Lato', sans-serif; background-color:#8dc63f; border-right:1px solid #dddddd; " width="209" valign="middle" align="center"> Total Non Chargeable Order </td>
                                {{--<td class="cancel-order_text" style="font-size:14px; color:#ffffff; font-family: 'Lato', sans-serif; background-color:#8dc63f; border-right:1px solid #dddddd;" width="125" valign="middle" align="center"> Net Sales </td>--}}
                                <td class="cancel-order_text" style="font-size:14px; color:#ffffff; font-family: 'Lato', sans-serif; background-color:#8dc63f; border-right:1px solid #dddddd; " width="123" valign="middle" align="center"> Gross Total </td>
                            </tr>
                            <tr>
                                <td class="cancel-order_text-1" style="font-size:18px;  color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;border-left:1px solid #dddddd;" width="87" valign="middle" height="50" align="center">&#8377; {{$data['total_discount']}}</td>
                                <td class="cancel-order_text-1" style="font-size:18px;  color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;" width="239" valign="middle" height="50" align="center">&#8377; {{$data['total_nc']}} </td>
                                {{--<td class="cancel-order_text-1" style="font-size:18px;  color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;" width="95" valign="middle" height="50" align="center">&#8377; {{$data['net_sale']}} </td>--}}
                                <td class="cancel-order_text-1" style="font-size:18px; color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;" width="123" valign="middle" height="50" align="center">&#8377; {{$data['gross_total']}} </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="642" height="10"></td>
                </tr>


                <tr>
                    <td>
                        <table class="table-1" style="width:642px;" cellspacing="0" cellpadding="0" border="0" align="center">
                            <tbody>
                                <tr>
                                    <td class="cancel-order_text" style="font-size:16px; color:#ffffff; font-family: 'Lato', sans-serif; background-color:#337ab7; border-right:1px solid #dddddd; " width="161" valign="middle" height="50" align="center"> Payment Method</td>
                                    <td class="cancel-order_text" style="font-size:16px; color:#ffffff; font-family: 'Lato', sans-serif; background-color:#337ab7; border-right:1px solid #dddddd; " width="160" valign="middle" align="center"> User </td>
                                    <td class="cancel-order_text" style="font-size:16px; color:#ffffff; font-family: 'Lato', sans-serif; background-color:#337ab7; border-right:1px solid #dddddd;" width="160" valign="middle" align="center"> System </td>
                                    <td class="cancel-order_text" style="font-size:16px; color:#ffffff; font-family: 'Lato', sans-serif; background-color:#337ab7; border-right:1px solid #dddddd; " width="161" valign="middle" align="center"> Difference </td>
                                </tr>
                                <tr>
                                    <td class="cancel-order_text-1" style="font-size:18px;  color:#333333; font-family: 'Lato', sans-serif; background-color:#eee; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;border-left:1px solid #dddddd;" width="161" valign="middle" height="50" align="center">Cash</td>
                                    <td class="cancel-order_text-1" style="font-size:18px;  color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;" width="160" valign="middle" height="50" align="center">&#8377; {{ $data['user_cash'] }} </td>
                                    <td class="cancel-order_text-1" style="font-size:18px;  color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;" width="160" valign="middle" height="50" align="center">&#8377; {{ $data['total_cash'] }} </td>
                                    <td class="cancel-order_text-1" style="font-size:18px; color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;" width="161" valign="middle" height="50" align="center">&#8377; {{ $data['cash_diff'] }} </td>
                                </tr>
                                <tr>
                                    <td class="cancel-order_text-1" style="font-size:18px;  color:#333333; font-family: 'Lato', sans-serif; background-color:#eee; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;border-left:1px solid #dddddd;" width="161" valign="middle" height="50" align="center">Online</td>
                                    <td class="cancel-order_text-1" style="font-size:18px;  color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;" width="160" valign="middle" height="50" align="center">&#8377; {{ $data['user_online'] }} </td>
                                    <td class="cancel-order_text-1" style="font-size:18px;  color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;" width="160" valign="middle" height="50" align="center">&#8377; {{ $data['total_prepaid'] }} </td>
                                    <td class="cancel-order_text-1" style="font-size:18px; color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;" width="161" valign="middle" height="50" align="center">&#8377; {{ $data['prepaid_diff'] }} </td>
                                </tr>
                                <tr>
                                    <td class="cancel-order_text-1" style="font-size:18px;  color:#333333; font-family: 'Lato', sans-serif; background-color:#eee; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;border-left:1px solid #dddddd;" width="161" valign="middle" height="50" align="center">Cheque</td>
                                    <td class="cancel-order_text-1" style="font-size:18px;  color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;" width="160" valign="middle" height="50" align="center">&#8377; {{ $data['user_cheque'] }} </td>
                                    <td class="cancel-order_text-1" style="font-size:18px;  color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;" width="160" valign="middle" height="50" align="center">&#8377; {{ $data['total_cheque'] }} </td>
                                    <td class="cancel-order_text-1" style="font-size:18px; color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;" width="161" valign="middle" height="50" align="center">&#8377; {{ $data['cheque_diff'] }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="20"></td>
                </tr>

                <tr>
                    <td>
                        <table class="table-1" border="0" align="center" cellspacing="0" cellpadding="0" style="width:642px; border-bottom:1px solid #dddddd;">
                            <tbody>
                                <?php $i=0; ?>
                                @if( isset($data['pay_options']) && sizeof($data['pay_options']) > 0 )
                                    @foreach( $data['pay_options'] as $key=>$val)
                                        @if( is_array($val))
                                            @foreach( $val as $ky=>$sr)
                                                @if($i%3 == 0)
                                                    <tr>
                                                @endif
                                                    <td>
                                                        <table class="table-1" border="0" align="center" cellspacing="0" cellpadding="0" bgcolor="#eee" style="width:214px;border-collapse: collapse; height:100px; border-right:1px solid #dddddd; margin-bottom:0px;">
                                                            <tbody>
                                                                @if( $ky == 'direct')
                                                                    <tr>
                                                                        <td height="52" class="amount-4" width="214" align="center" style="color:#888888; font-size:16px; line-height:20px; font-family: 'Lato', sans-serif; vertical-align:bottom;"> {{ $key }} <br/>
                                                                        </td>
                                                                    </tr>
                                                                @else
                                                                    <tr>
                                                                        <td height="52" class="amount-4" width="214" align="center" style="color:#888888; font-size:16px; line-height:20px; font-family: 'Lato', sans-serif; vertical-align:bottom;"> {{ $key }} <br/>
                                                                            {{ $ky }}
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                <tr>
                                                                    <td height="48" class="amount-3" width="214" align="center" style="color:#333333; font-size:24px; font-family: 'Lato', sans-serif;vertical-align: middle;">&#8377; {{ number_format($sr,2) }} </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                @if($i%3 == 2)
                                                    </tr>
                                                    <?php $i++ ?>
                                                @else
                                                    <?php $i++ ?>
                                                @endif
                                            @endforeach
                                        @else
                                            @if($i%3 == 0)
                                                <tr>
                                            @endif
                                                <td>
                                                    <table class="table-1" border="0" align="center" cellspacing="0" cellpadding="0" bgcolor="#eee" style="width:214px; border-collapse: collapse; height:100px; border-right:1px solid #dddddd; margin-bottom:0px;">
                                                        <tbody>
                                                            <tr>
                                                                <td height="52" class="amount-4" width="214" align="center" style="color:#888888; font-size:16px; line-height:20px; font-family: 'Lato', sans-serif; vertical-align:bottom;"> {{ $key }} <br/>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td height="48" class="amount-3" width="214" align="center" style="color:#333333; font-size:24px; font-family: 'Lato', sans-serif;vertical-align: middle;">&#8377; {{ number_format($val,2) }} </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            @if($i%3 == 2)
                                                </tr>
                                                <?php $i++ ?>
                                            @else
                                                <?php $i++ ?>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td width="662" height="30">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <table class="table-1" border="0" align="center" valign="top" cellspacing="0" cellpadding="0" style="width:642px; border-bottom:1px solid #d5d5d5;">
                            <tr>
                                <td>
                                    <table class="table-1" border="0" align="center" cellspacing="0" cellpadding="0" style="width:310px; margin-bottom:2px; border: 1px solid #a2a2a2;">
                                        <tr>
                                            <td width="15" style="background-color:#eeeeee;"></td>
                                            <td class="text" align="left" valign="middle" width="195" style="background-color:#eeeeee; height:50px; color:#666666; font-size:14px; font-family: 'Lato', sans-serif;"> Highest Order </td>
                                            <td class="text-1" align="center" valign="middle" width="100" style="background-color:#d5d5d5; height:50px; color:#333333; font-size:16px; font-family: 'Lato', sans-serif; ">&#8377; {{ $data['highest_order'] }} </td>
                                        </tr>
                                        <tr>
                                            <td width="15" style="background-color:#ffffff;"></td>
                                            <td class="text" align="left" valign="middle" width="195" style="background-color:#ffffff; height:50px; color:#666666; font-size:14px; font-family: 'Lato', sans-serif;"> Lowest Order </td>
                                            <td class="text-1" align="center" valign="middle" width="100" style="background-color:#eeeeee; height:50px; color:#333333; font-size:16px; font-family: 'Lato', sans-serif;">&#8377; {{$data['lowest_order']}} </td>
                                        </tr>
                                        <tr>
                                            <td width="15" style="background-color:#eeeeee;"></td>
                                            <td class="text" align="left" valign="middle" width="195" style="background-color:#eeeeee; height:50px; color:#666666; font-size:14px; font-family: 'Lato', sans-serif;"> Cancelled Order </td>
                                            <td class="text-1" align="center" valign="middle" width="100" style="background-color:#d5d5d5; height:50px; color:#333333; font-size:16px; font-family: 'Lato', sans-serif;"> {{$data['cancel_order']}} </td>
                                        </tr>
                                        <tr>
                                            <td width="15" style="background-color:#ffffff;"></td>
                                            <td class="text" align="left" valign="middle" width="195" style="background-color:#ffffff; height:50px; color:#666666; font-size:14px; font-family: 'Lato', sans-serif;">Cancelled Order Amount</td>
                                            <td class="text-1" align="center" valign="middle" width="100" style="background-color:#eeeeee; height:50px; color:#333333; font-size:16px; font-family: 'Lato', sans-serif; ">&#8377; {{$data['cancel_amount']}} </td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="4%"></td>
                                <td>
                                    <table class="table-1" border="0" align="center" cellspacing="0" valign="top" cellpadding="0" style="width:310px; margin-bottom:2px; border: 1px solid #a2a2a2;">
                                        <tr>
                                            <td width="15" style="background-color:#eeeeee;"></td>
                                            <td class="text" align="left" valign="middle" width="195" style="background-color:#eeeeee; height:50px; color:#666666; font-size:14px; font-family: 'Lato', sans-serif;"> Top Selling Item</td>
                                            <td class="text-1" align="center" valign="middle" width="100" style="background-color:#d5d5d5; height:50px; color:#333333; font-size:16px; font-family: 'Lato', sans-serif;">{{$data['top_selling_item']}} </td>
                                        </tr>
                                        <tr>
                                            <td width="15" style="background-color:#fff;"></td>
                                            <td class="text" align="left" valign="middle" width="195" style="background-color:#fff; height:50px; color:#666666; font-size:14px; font-family: 'Lato', sans-serif;"> No. Of
                                                Persons Visited </td>
                                            <td class="text-1" align="center" valign="middle" width="100" style="background-color:#eeeeee; height:50px; color:#333333; font-size:16px; font-family: 'Lato', sans-serif;">{{$data['total_person']}} </td>
                                        </tr>
                                        <tr>
                                            <td width="15" style="background-color:#eeeeee;"></td>
                                            <td class="text" align="left" valign="middle" width="195" style="background-color:#eeeeee; height:50px; color:#666666; font-size:14px; font-family: 'Lato', sans-serif;"> Average
                                                Per Order</td>
                                            <td class="text-1" align="center" valign="middle" width="100" style="background-color:#d5d5d5; height:50px; color:#333333; font-size:16px; font-family: 'Lato', sans-serif; ">&#8377; {{$data['average']}}</td>
                                        </tr>
                                        <tr>
                                            <td width="15" style="background-color:#fff;"></td>
                                            <td class="text" align="left" valign="middle" width="195" style="background-color:#fff; height:50px; color:#666666; font-size:14px; font-family: 'Lato', sans-serif;"> Average
                                                Per Person </td>
                                            <td class="text-1" align="center" valign="middle" width="100" style="background-color:#eee; height:50px; color:#333333; font-size:16px; font-family: 'Lato', sans-serif;">&#8377; {{$data['avg_per_person']}} </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" width="642" height="25" style="border-bottom:1px solid #d5d5d5;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="642" height="10"></td>
                </tr>
                @if( isset($data['cancel_order_arr']) && sizeof($data['cancel_order_arr']) > 0 )
                    <tr>
                        <td>
                            <table class="table-1" border="0" align="center" cellspacing="0" cellpadding="0" style="width:642px;">
                                <tr>
                                    <td width="3%" style="background-color:#ffffff;"></td>
                                    <td class="cancel-order" colspan="4" align="center" valign="middle" style="font-size:24px;color:#ef4e31; font-family: 'Lato', sans-serif; background-color:#ffffff; height:60px;"> Canceled Order Details </td>
                                </tr>
                                <tr>
                                    <td width="13" style="background-color:#ef4e31;"></td>
                                    <td class="cancel-order_text" align="left" width="87" height="50" valign="middle" style="font-size:14px; color:#ffffff; font-family: 'Lato', sans-serif; background-color:#ef4e31; border-right:1px solid #dddddd;"> Invoice No. </td>
                                    <td class="cancel-order_text" align="center" width="239" valign="middle" style="font-size:14px; color:#ffffff; font-family: 'Lato', sans-serif; background-color:#ef4e31; border-right:1px solid #dddddd;"> Reason </td>
                                    <td class="cancel-order_text" align="center" width="95" valign="middle" style="font-size:14px; color:#ffffff; font-family: 'Lato', sans-serif; background-color:#ef4e31; border-right:1px solid #dddddd;"> Amount </td>
                                    <td class="cancel-order_text" align="center" width="123" valign="middle" style="font-size:14px; color:#ffffff; font-family: 'Lato', sans-serif; background-color:#ef4e31; border-right:1px solid #dddddd;"> Cancel By </td>
                                </tr>
                                @foreach($data['cancel_order_arr'] as $co )
                                    <tr>
                                        <td width="13" style="background-color:#ffffff; border-left:1px solid #dddddd; border-bottom:1px solid #dddddd;"></td>
                                        <td class="cancel-order_text-1" align="left" width="87" height="50" valign="middle" style="font-size:15px; color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;"> {!! $co->invoice_no !!} </td>
                                        <td class="cancel-order_text-1" align="center" width="239" height="50" valign="middle" style="font-size:15px; color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;"> {!! $co->reason !!} </td>
                                        <td class="cancel-order_text-1" align="center" width="95" height="50" valign="middle" style="font-size:15px; color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;">&#8377; {!! $co->totalprice !!} </td>
                                        <td class="cancel-order_text-1" align="center" width="123" height="50" valign="middle" style="font-size:15px; color:#333333; font-family: 'Lato', sans-serif; background-color:#ffffff; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;"> {!! $co->user_name !!} </td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td width="642" height="30"></td>
                    </tr>
                @endif
            </table>
        </td>
    </tr>
    <tr>
        <td width="642" height="50" valign="middle" style="font-size:12px; color:#666666; font-family: 'Lato', sans-serif;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*Total Sales = Total of item price without tax and discount. <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*Gross Total = Total Sales + Taxes - ( Discount + Total Non Chargeable Order ) .
        </td>
    </tr><tr>
        <td align="center" width="642" height="50" valign="middle" style="font-size:12px; color:#666666; font-family: 'Lato', sans-serif;"> Powered by Pikal. </td>
    </tr>
</table>
</body>
</html>