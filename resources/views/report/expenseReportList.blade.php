<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper" style="overflow-x: auto;">
                    <table border="1" class="table table-striped table-bordered table-hover">

                        <tr>
                            <th style="text-align: center" class="col-md-2">Date</th>
                            <th style="text-align: center" class="col-md-2">Expense By</th>
                            {{--<th style="text-align: center" class="col-md-2">Outlet</th>--}}
                            <th style="text-align: center" class="col-md-2">Detail</th>
                            <th style="text-align: center" class="col-md-2">Credit</th>
                            <th style="text-align: center" class="col-md-2">Debit</th>
                            <th style=" text-align: center" class="col-md-1">Balance</th>

                        </tr>
                            <?php $total = 0;$total_cash = 0;$total_expense = 0;

                            if( isset($expenses) && sizeof($expenses)>0){?>
                                <tr>
                                    <td>{{ $opening_date }}</td>
                                    <td>{{ '-' }}</td>
                                    <td>{{ 'Opening Balance' }}</td>
                                    <td align="center">{{ '-' }}</td>
                                    <td align="center">{{ '-' }}</td>
                                    <td align="right">{{ number_format($opening_balance,2) }}</td>
                                </tr>

                                @foreach($expenses as $expense)
                                    <tr>
                                        <td>{{ $expense->expense_date }}</td>
                                        <td>{{ \App\Owner::find($expense->expense_by)->user_name }}</td>
                                        {{--<td>{{ \App\Outlet::find($expense->expense_for)->name }}</td>--}}
                                        <td>{{ $expense->description }}</td>

                                        @if( $expense->type == 'cash' )



                                            <td align="right">{{ number_format($expense->amount,2) }}</td>
                                            <td align="center">{{ '-' }}</td>
                                            <?php
                                                    $opening_balance = $opening_balance + $expense->amount;
                                                    $total_cash += $expense->amount;
                                            ?>

                                            <td align="right">{{ number_format($opening_balance,2) }}</td>

                                        @else

                                            <td align="center">{{ '-' }}</td>
                                            <td align="right">{{ number_format($expense->amount,2) }}</td>
                                            <?php
                                                    $opening_balance = $opening_balance - $expense->amount;
                                                    $total_expense += $expense->amount;
                                            ?>

                                            <td align="right">{{ number_format($opening_balance,2) }}</td>

                                        @endif

                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="3" align="left"><b>Total</b></td>
                                    <td align="right">{{ number_format($total_cash,2) }}</td>
                                    <td align="right">{{ number_format($total_expense,2) }}</td>
                                    <td align="right">{{ number_format($opening_balance,2) }}</td>
                                </tr>

                        <?php }else{ ?>

                                <tr>
                                    <td colspan="7" align="center">No Expense Found.</td>
                                </tr>

                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>