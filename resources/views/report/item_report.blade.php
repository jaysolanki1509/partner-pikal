<?php $no=1;?>
<div class="row">

    <div class="col-lg-12">

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="reports">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Item Name</th>
                            <th>Qty</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($items as $item)
                        <tr>
                            <td>{!! $no !!}</td>
                            <td>{!! $item->item !!}</td>
                            <td>{!! $item->count !!}</td>
                        </tr>
                        <?php $no++ ;?>
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