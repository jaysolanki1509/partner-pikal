<div class="panel panel-default">
    <div class="panel-body">
        <div class="dataTable_wrapper" style="overflow-x: auto;">

            <table class="table table-striped table-bordered table-hover">
                @if(sizeof($cashsale) > 0)
                    <tr>
                        <th class="bold-center">Date</th>
                        <th class="bold-center">Cash at EOD</th>
                        <th class="bold-center">Total Sales</th>
                        <th class="bold-center">Difference</th>
                    </tr>
                <?php $tot_from_user = 0; $tot_from_db = 0; $diff_tot = 0;?>
                    @foreach($cashsale as $cs)
                        <tr>
                            <?php $date = str_replace('/', '-', $cs->start_date); ?>
                            <td class="bold-center">{{ date('Y-m-d', strtotime($date)) }}</td>
                            <td align="right">{{ $cs->total_from_user }}<?php $tot_from_user += $cs->total_from_user ?></td>
                            <td align="right">{{ $cs->total_from_db }}<?php $tot_from_db += $cs->total_from_db ?></td>
                            <td align="right">{{ $cs->total_from_db-$cs->total_from_user }}<?php $diff_tot += ($cs->total_from_db-$cs->total_from_user) ?></td>
                        </tr>
                    @endforeach
                    <tr style=" background-color: #99CC99">
                        <th class="bold-center"> Total </th>
                        <th align="right" >&#x20b9 {{ $tot_from_user }}</th>
                        <th align="right" >&#x20b9 {{ $tot_from_db }}</th>
                        <th align="right" >&#x20b9 {{ $diff_tot }}</th>
                    </tr>
                @else
                    <tr>
                        <td colspan="6">There are no requests for the selected date.</td>
                    </tr>
                @endif
            </table>

        </div>
    </div>
</div>