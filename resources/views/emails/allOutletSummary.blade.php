<!DOCTYPE html>
<html lang="en">
    <head>
        <title>All Outlet Summary</title>
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

    <div class="container">

        <table class="table" style="width:100%; border: 1px solid black;">
            <caption style="margin-bottom: 10px;"><b>Outlets Summary List</b></caption>
                @if(isset($data['all_outlets_summary']) && sizeof($data['all_outlets_summary'])>0)
                    <thead>
                        <tr>
                            <th class="col-md-3">Outlet Name</th>
                            <th class="col-md-1">Status</th>
                            <th class="col-md-1">Total Orders</th>
                            <th class="col-md-2">Sub Total</th>
                            <th class="col-md-2">Final Total</th>
                            <th class="col-md-2">Cancel Orders</th>
                            <th class="col-md-2">Total Person Visit</th>
                            <th class="col-md-2">Today Unique Mobiles</th>
                            <th class="col-md-2">Total Unique Mobiles</th>
                            <th class="col-md-2">Average Per Day</th>
                            <th class="col-md-2">{{$data['month_lable']}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($data['all_outlets_summary'] as $index => $outlet_details)
                            @if($outlet_details['outlet_status'] == "demo")
                               <?php continue; ?>
                            @endif
                            @if($outlet_details["flag"]==1)
                                <tr>
                                    <td class="col-md-3"> {{$outlet_details['name']}} </td>
                                    <td class="col-md-1"> {{$outlet_details['outlet_status']}} </td>
                                    <td class="col-md-1" align="right"> {{$outlet_details['total_orders']}} </td>
                                    <td class="col-md-2" align="right"> {{number_format($outlet_details['sub_total'],2)}} </td>
                                    <td class="col-md-2" align="right"> {{number_format($outlet_details['final_total'],2)}} </td>
                                    <td class="col-md-2" align="right"> {{$outlet_details['cancel_orders']}} </td>
                                    <td class="col-md-2" align="right"> {{$outlet_details['total_person_visit']}} </td>
                                    <td class="col-md-2" align="right"> {{$outlet_details['today_unique_mobile']}} </td>
                                    <td class="col-md-2" align="right"> {{$outlet_details['total_unique_mobile']}} </td>
                                    <td class="col-md-2" align="right"> {{$outlet_details['average_per_day']}} </td>
                                    <td class="col-md-2" align="right"> {{number_format($outlet_details['month_total'],2)}} </td>
                                </tr>
                            @else
                                <tr style="background-color: #dca7a7">
                                    <td class="col-md-3"> {{$outlet_details['name']}} </td>
                                    <td class="col-md-1"> {{$outlet_details['outlet_status']}} </td>
                                    <td class="col-md-9" colspan="7" align="center"> No Orders Found </td>
                                    <td class="col-md-3" align="right"> {{$outlet_details['average_per_day']}} </td>
                                    <td class="col-md-3" align="right"> {{number_format($outlet_details['month_total'],2)}} </td>
                                </tr>
                            @endif
                        @endforeach

                        <tr style="background-color: #99CC99">
                            <td style="font-weight: bold" class="col-md-3"> {{$data['all_outlets_summary_totle']['name']}} </td>
                            <td style="font-weight: bold" class="col-md-1"> NA </td>
                            <td style="font-weight: bold" class="col-md-1" align="right"> {{$data['all_outlets_summary_totle']['total_orders']}} </td>
                            <td style="font-weight: bold" class="col-md-2" align="right"> {{number_format($data['all_outlets_summary_totle']['sub_total'],2)}} </td>
                            <td style="font-weight: bold" class="col-md-2" align="right"> {{number_format($data['all_outlets_summary_totle']['final_total'],2)}} </td>
                            <td style="font-weight: bold" class="col-md-2" align="right"> {{$data['all_outlets_summary_totle']['cancel_orders']}} </td>
                            <td style="font-weight: bold" class="col-md-2" align="right"> {{$data['all_outlets_summary_totle']['total_person_visit']}} </td>
                            <td style="font-weight: bold" class="col-md-2" align="right"> {{$data['all_outlets_summary_totle']['today_unique_mobile']}} </td>
                            <td style="font-weight: bold" class="col-md-2" align="right"> {{$data['all_outlets_summary_totle']['total_unique_mobile']}} </td>
                            <td style="font-weight: bold" class="col-md-2" align="right"> {{number_format($data['all_outlets_summary_totle']['average_per_day'],2)}} </td>
                            <td style="font-weight: bold" class="col-md-2" align="right"> {{number_format($data['all_outlets_summary_totle']['month_total'],2)}} </td>
                        </tr>

                    </tbody>
                @endif
        </table>
    </div>

    </body>
</html>