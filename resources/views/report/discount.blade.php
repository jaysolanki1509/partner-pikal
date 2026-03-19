<?php

use Carbon\Carbon;
$no=1;

?>

<table class="table table-striped table-bordered table-hover" id="reports">
    <thead>
    <tr>
        <th>No</th>
        <th>Invoice</th>
        <th>Order Type</th>
        <th>Order Details</th>
        <th>Amount</th>
        <th>Discount Amount</th>
        <th>Order Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($orders as $order)
    <tr>
        <td>{!! $no !!}</td>
        <td>{!! $order->invoice_no !!}</td>
        <td>{!! \App\Http\Controllers\Api\v1\ApiController::get_order_type($order->order_type) !!}</td>
        <td>{!! ucfirst($itemlist[$order->order_id]) !!}</td>
        <td>{!! $order->totalprice !!}</td>
        <td>{!! $order->discount_value !!}</td>
        <td>{!! Carbon::parse($order->table_start_date)->format('d-m-Y  h:i A') !!}</td>
    </tr>
    <?php $no++ ;?>
    @endforeach
    </tbody>
</table>