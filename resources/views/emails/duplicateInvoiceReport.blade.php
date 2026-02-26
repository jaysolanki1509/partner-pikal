<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            height: 20px;
        }
    </style>
</head>

<body>

@if(isset($data['all_outlets_invoice']) && sizeof($data['all_outlets_invoice'])>0)

    <div class="container">
        <table class="table" style="width:100%; border: 1px solid black;">
            <caption style="margin-bottom: 10px;"><b>Duplicate Invoices</b></caption>
            @foreach($data['all_outlets_invoice'] as $outlet_name => $invoice)
                @if(isset($invoice[0]) && sizeof($invoice[0])>0)
                    <thead>
                    <tr>
                        <th colspan="5">{{$outlet_name}}</th>
                    </tr>
                    </thead>

                    <thead>
                    <tr>
                        <th class="col-md-3">Invoice id</th>
                        <th class="col-md-3">Date</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($invoice[0] as $index => $val)
                        <tr>
                            <td class="col-md-6"> {{$val['inv_no']}} </td>
                            <td class="col-md-6"> {{$val['date']}} </td>
                        </tr>
                    @endforeach
                    </tbody>
                @endif
            @endforeach
        </table>
    </div>

@endif

@if(isset($data['all_outlets_diff']) && sizeof($data['all_outlets_diff'])>0 )

    <div class="container">
        <table class="table" style="width:100%; border: 1px solid black;">
            <caption style="margin-bottom: 10px;"><b>KOT vs Orders</b></caption>
            @foreach($data['all_outlets_diff'] as $outlet_name => $kots)
                @if(isset($kots[0]) && sizeof($kots[0])>0)
                    <thead>
                        <tr>
                            <th colspan="5">{{$outlet_name}}</th>
                        </tr>
                    </thead>

                    <thead>
                        <tr>
                            <th class="col-md-3">Item Name</th>
                            <th class="col-md-1">Qty</th>
                            <th class="col-md-2">Price</th>
                            <th class="col-md-3">Date</th>
                            <th class="col-md-3">Reason</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($kots[0] as $index => $val)
                            <tr>
                                <td class="col-md-3"> {{$val['item_name']}} </td>
                                <td class="col-md-3" align="right"> {{$val['qty']}} </td>
                                <td class="col-md-3" align="right"> {{$val['price']}} </td>
                                <td class="col-md-3"> {{$val['date']}} </td>
                                <td class="col-md-3"> {{$val['reason']}} </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            @endforeach
        </table>
        <p>It shows Kot not properly updated in backend.(If KOT dosent have Invoice id.)</p>
    </div>

@endif

</body>
</html>