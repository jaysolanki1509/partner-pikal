<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper" style="overflow-x: auto;">
                    <table class="table table-striped table-bordered table-hover" id="revenue_report_list">
                        @if(isset($data) && is_array($data) && count($data) > 0)
                            <thead>
                            <tr>
                                <th class="bold-center"> Date </th>
                                @foreach($data['table_head'] as $id=>$header)
                                    <th class="bold-center">{{$header}}</th>
                                @endforeach
                                <th class="bold-center">Total</th>
                            </tr>
                            </thead>
                            <?php $tot_po[100][] = 0; ?>
                            @foreach($dates as $id=>$date)
                                <?php $tot_date[$date] = 0; ?>
                                <tr>
                                    <td class="center">{{$date}}</td>
                                    @foreach($data['payment_option'] as $po_id=>$option)
                                        @if($po_id != 0 )
                                            @foreach($data['source'][$po_id] as $s_id=>$source)
                                                @if(!isset($tot_po[$po_id][$s_id]))
                                                    <?php $tot_po[$po_id][$s_id] = 0; ?>
                                                @endif

                                                @if(isset($data[$date][$po_id][$s_id]) && !empty($data[$date][$po_id][$s_id]))
                                                    <td style="text-align: right">
                                                        {{$data[$date][$po_id][$s_id]}}
                                                        <?php $tot_date[$date] += intval($data[$date][$po_id][$s_id]);
                                                        $tot_po[$po_id][$s_id] += intval($data[$date][$po_id][$s_id]); ?>
                                                    </td>
                                                @else
                                                    <td style="text-align: center">
                                                        <label>-</label>
                                                    </td>
                                                @endif
                                            @endforeach
                                        @else
                                            @if(!isset($tot_po[0][0]))
                                                <?php $tot_po[0][0] = 0; ?>
                                            @endif

                                            @if(isset($data[$date][0][0]) && sizeof($data[$date][0][0])>0)
                                                <td style="text-align: right">
                                                    {{$data[$date][0][0]}}
                                                    <?php $tot_date[$date] += intval($data[$date][0][0]);
                                                    $tot_po[0][0] += intval($data[$date][0][0]); ?>
                                                </td>
                                            @else
                                                <td style="text-align: center">
                                                    <label>-</label>
                                                </td>
                                            @endif
                                        @endif
                                    @endforeach
                                    <td class="bold" style="text-align: right;">{{number_format($tot_date[$date],2)}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th style="text-align: right; background-color: #31b131; font-weight: bold;">Total</th>
                                <?php $g_total = 0; ?>
                                @foreach($data['payment_option'] as $po_id=>$option)
                                    @if($po_id != 0)
                                        @foreach($data['source'][$po_id] as $s_id=>$source)
                                            <td style="text-align: right; background-color: #31b131; font-weight: bold;">
                                                <?php $g_total += $tot_po[$po_id][$s_id]; ?>
                                                {{number_format($tot_po[$po_id][$s_id],2)}}
                                            </td>
                                        @endforeach
                                    @endif
                                @endforeach
                                <td style="text-align: right; background-color: #31b131; font-weight: bold;">{{number_format($tot_po[0][0],2)}}</td>
                                <td style="text-align: right; background-color: #31b131; font-weight: bold;">{{number_format($g_total+$tot_po[0][0],2)}}</td>
                            </tr>
                        @elseif(isset($error))
                            <tr><td>{{ $error }}</td></tr>
                        @else
                            <tr><td>No Records Found</td></tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
