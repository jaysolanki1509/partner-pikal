<?php $no=1;?>
<div class="row">

    <div class="col-lg-12">

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="reports">
                        <thead>
                        <th class="text-center">Expense By</th>
                        <th class="text-center">Amount (Rs.)</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Expense Date</th>
                        <th class="text-center">Expense For</th>
                        <th class="text-center">Status</th>
                        </thead>

                        <tbody>
                        @foreach($itemlist as $exp)
                        <tr class="odd gradeX">
                            <td>{!! $exp->user_name !!}</td>
                            <td>{!! $exp->amount !!} </td>
                            <td>{!! $exp->description !!}</td>
                            <td>{!! $exp->expense_date !!}</td>
                            <td>{!! $exp->name !!}</td>
                            <td></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->

            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>