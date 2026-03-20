<?php
use Carbon\Carbon;
$no=1;
$admin = \Illuminate\Support\Facades\Auth::user()->created_by;

?>
<table class="table table-bordered" data-sorting="true" data-page-size="100" data-limit-navigation="4" id="reports">
    <input type="text" hidden name="total_orders" id="total_orders" value="{{$total_orders}}" >
    <thead>
    <tr>
        {{--<th data-hide="phone">No</th>--}}
        <th data-sort-ignore="true">Invoice</th>
        <th data-hide="phone">T#</th>
        {{--<th>P#</th>--}}
        <th data-hide="phone">Order Type</th>
        <th data-hide="phone" data-sort-ignore="true">Order Details</th>
        <th data-hide="phone">Discount</th>
        <th data-hide="phone">Amount</th>
        <th data-hide="phone">Payment Mode</th>
        {{--<th>Table Duration</th>--}}
        <th>Order Date</th>
        <th data-sort-ignore="true" class="td-center @if(isset($flag) && $flag == 'order_report')@else hide @endif">Action</th>
    </tr>
    </thead>
    <tbody id="table_body">
        @if( isset($orders) )
        <?php $grand_total = 0;?>
            @foreach ($orders as $order)
                <?php
                if ( isset($from_time) && isset($to_time) && $from_time != "" && $to_time != "" ) {
                    $time = date('H:i:s',strtotime($order->$order_date_field));

                    $date1 = strtotime($time); $date2 = strtotime($from_time); $date3 = strtotime($to_time);
                    if ($date1 >= $date2 && $date1 <= $date3) {

                    } else {
                        continue;
                    }
                }

                //check if order place is custom
                if ( $order->is_custom == 1 ) {
                    $orderplace = \App\OrderPlaceType::find($order->order_place_id);
                    if ( isset($orderplace) && sizeof($orderplace) > 0 ) {
                        $table_no = $orderplace->name."<br>".$order->table_no;
                    } else {
                        $table_no = "Other <br>".$order->table_no;
                    }

                } else {
                    $table_no = $order->table_no;
                }
                ?>
                <tr>
                    {{--<td data-sort-value="{{ $no }}">{!! $no !!}</td>--}}
                    <td>{!! $order->invoice_no !!}</td>
                    <td>{!! $table_no !!}</td>
                    {{--<td>{!! $order->person_no !!}</td>--}}

                    <td>{!! \App\Http\Controllers\Api\v1\ApiController::get_order_type($order->order_type) !!}</td>

                    <td>{!! $itemlist[$order->order_id] !!}</td>
                    @if(isset($order->discount_value))
                        <td style="text-align: right;">{!! number_format((float)($order->discount_value ?? 0) + (float)($order->item_discount_value ?? 0.00), 2) !!}</td>
                    @else
                        <td style="text-align: right;">0.00</td>
                    @endif
                    <td style="text-align: right;">{!! $order->totalprice !!}</td>
                    <?php $grand_total += $order->totalprice; ?>
                    <td>{!! $order->payment_mode !!}</td>
                    {{--<td style="text-align: right;">{!! (new DateTime($order->table_start_date))->diff(new DateTime($order->table_end_date))->format("%H:%I") !!}</td>--}}
                    <td data-sort-value="{{ strtotime($order->$order_date_field) }}">{!! Carbon::parse($order->$order_date_field)->format('d-m-Y  h:i A') !!}</td>
                    <td class="@if(isset($flag) && $flag == 'order_report')@else hide @endif td-center">
                        @if( isset($order->invoice_no) && $order->invoice_no != '')
                            <a class="row-edit" title="Print Order" href="javascript:void(0)" onclick="openOrderView({!! $order->order_id !!},'close')"><span class="zmdi zmdi-local-printshop"></span></a>
                            <br><hr style="margin-top: 5px;margin-bottom: 5px">
                            <a class="row-edit" title="Edit Order" href="javascript:void(0)" onclick="editBill({!! $order->order_id !!},'open')"><span class="zmdi zmdi-edit"></span></a>

                        @else
                            <a class="row-edit" title="Process Order" href="javascript:void(0)" onclick="processBill({!! $order->order_id !!},'open')"><span class="zmdi zmdi-file-text"></span></a>
                        @endif

                        @if( !isset($admin))
                            <br><hr style="margin-top: 5px;margin-bottom: 5px">
                                <a class="row-edit" href="javascript:void(0)" title="Order History" id="order_history_link" onclick="orderHistory({!! $order->order_id !!})"><i class="fa fa-history"></i></a>
                        @endif

                        <br>
                        <hr style="margin-top: 5px;margin-bottom: 5px">
                        
                        <a class="row-edit" href="javascript:void(0)" title="Cancel Order" data-id="{!! $order->invoice_no !!}" data-id="{!! $order->order_id !!}" id="cancel_order_link" data-orderid="{!! $order->order_id !!}" data-toggle="modal" data-target="#cancelOrder"><span class="zmdi zmdi-close-circle"></span></a>
                        @if(isset($order->invoice_no) && $order->invoice_no != '')
                            @if(\App\OutletSetting::checkAppSetting($order->outlet_id,'isRetailMode'))
                                <br><hr style="margin-top: 5px;margin-bottom: 5px">
                                <a class="row-edit" href="/credit-note/create/{{ $order->order_id }}" title="Create Credit Note" data-id="{!! $order->invoice_no !!}" data-id="{!! $order->order_id !!}" id="delete_order_link" data-toggle="modal" >
                                    <span class="zmdi zmdi-assignment-o zmdi-hc-fw"></span>
                                </a>
                            @endif
                        @endif
                        @if($delete_order == 1)
                            <br><hr style="margin-top: 5px;margin-bottom: 5px">
                            <a class="row-edit" href="javascript:void(0)" title="Permanent Delete Order" data-id="{!! $order->invoice_no !!}" onclick="deleteOrder({!! $order->order_id !!})" data-id="{!! $order->order_id !!}" id="delete_order_link" data-toggle="modal" data-target="#deleteOrder"><span style="color: red" class="zmdi zmdi-close-circle"></span></a>
                        @endif
                    </td>
                </tr>
                <?php $no++ ;?>
            @endforeach
        <tr><td colspan="5" style="text-align: right;font-weight: bold">Total</td><td style="text-align: right;font-weight: bold;"><?php echo number_format($grand_total,2);?></td><td colspan="3"></td></tr>
        @else
            <tr><td colspan="10">No orders found.</td></tr>
        @endif
    <tfoot class="hide-if-no-paging">
        <tr>
            <td colspan="6" class="footable-visible">
                <div class="pagination pagination-centered"></div>
            </td>
        </tr>
    </tfoot>

    </tbody>
</table>

<div id="editBill" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" id="edit_add_payment_mode_btn" class="btn btn-primary" onclick="addPaymentMode('#editBill')"><i class="fa fa-plus"></i> Payment Mode</button>
                <button type="button" id="update_btn" class="btn btn-primary" onclick="updateBill()">Update Invoice</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

{{--cancel order modal--}}
<div id="cancelOrder" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cancel Order</h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    Invoice Id: <label id="lbl_invoice_id"></label>
                </div>
                <div style="clear: both;"></div>
                <input type="hidden" id="modal_order_id" value=""/>
                <div class="form-group col-md-12">
                    <select id="cancel_reason" class="form-control" onchange="changeReason(this.value)">
                        @foreach($reasons as $key=>$val)
                            <option value="{!! $key !!}">{!! $val !!}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <textarea placeholder="Enter reason" rows="5" class="form-control" id="reason"></textarea>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="cancelOrder()">Cancel Order</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


{{--print modal--}}
<div id="orderHistoryModal" data-backdrop="static" data-keyboard="false" class="modal fade" role="dialog" >
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Order History</h4>
            </div>
            <div class="modal-body">
                <p></p>
            </div>
            <div class="modal-footer">
                <input type="hidden" value="" id="close_type" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

@include('orderlist.processOrder')