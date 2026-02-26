
                    <table class="table table-striped table-bordered table-hover" id="revenue_report_list">
                        @if( isset($data) && sizeof($data) > 0 )
                            <thead>
                            <tr>
                                <th class="bold-center"> Date </th>
                                <th class="bold-center">Sales</th>
                                <th class="bold-center">Expense</th>
                                <th class="bold-center">Purchase</th>
                                <th class="bold-center">Stock Transferred</th>
                                <th class="bold-center">Staff Cost</th>
                                <th class="bold-center">Total</th>
                            </tr>
                            </thead>
                            <tbody id="table_body">
                            <?php $t_sale=0;$t_purchase=0;$t_expense=0;$t_final=0;$t_stock = 0;$t_staff = 0; ?>
                            @foreach($dates as $dt)
                                <tr>
                                    <td class="bold-center">{!! $dt !!}</td>
                                    <td align="right">@if($data[$dt]['sale'] == 0) {!! 'NA' !!} @else {!! number_format($data[$dt]['sale'],2) !!} @endif</td>
                                    <td align="right">@if($data[$dt]['expense'] == 0) {!! 'NA' !!} @else {!! number_format($data[$dt]['expense'],2) !!} @endif</td>
                                    <td align="right">@if($data[$dt]['purchase'] == 0) {!! 'NA' !!} @else {!! number_format($data[$dt]['purchase'],2) !!} @endif</td>
                                    <td align="right">@if($data[$dt]['stock_transferred'] == 0) {!! 'NA' !!} @else {!! number_format($data[$dt]['stock_transferred'],2) !!} @endif</td>
                                    <td align="right">@if($data[$dt]['staff_cost'] == 0) {!! 'NA' !!} @else {!! number_format($data[$dt]['staff_cost'],2) !!} @endif</td>
                                    <td align="right" class="bold">{!! number_format($data[$dt]['sale']-$data[$dt]['expense']-$data[$dt]['purchase']-$data[$dt]['staff_cost']-$data[$dt]['stock_transferred'],2) !!} </td>
                                </tr>
                                <?php
                                $t_sale += $data[$dt]['sale'];
                                $t_purchase += $data[$dt]['purchase'];
                                $t_expense += $data[$dt]['expense'];
                                $t_stock += $data[$dt]['stock_transferred'];
                                $t_staff += $data[$dt]['staff_cost'];
                                $t_final += $data[$dt]['sale']-$data[$dt]['expense']-$data[$dt]['purchase']-$data[$dt]['stock_transferred']-$data[$dt]['staff_cost'];
                                ?>
                            @endforeach
                            <tr style="background-color: #31b131">
                                <td class="bold-center">Total</td>
                                <td align="right" class="bold">{!! number_format($t_sale,2) !!}</td>
                                <td align="right" class="bold">{!! number_format($t_expense,2) !!}</td>
                                <td align="right" class="bold">{!! number_format($t_purchase,2) !!}</td>
                                <td align="right" class="bold">{!! number_format($t_stock,2) !!}</td>
                                <td align="right" class="bold">{!! number_format($t_staff,2) !!}</td>
                                <td align="right" class="bold">{!! number_format($t_final,2) !!}</td>
                            </tr>
                            </tbody>
                        @else
                            <tr><td>No Records Found</td></tr>
                        @endif
                    </table>