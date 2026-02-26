
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper" >
                    <table class="table table-striped table-bordered table-hover"  id="revenue_report_list">
                        @if( isset($result) && sizeof($result) > 0 && $result->status == 'success' )

                            <thead >
                                <tr style="background-color: #428bca;color:#fff;">
                                    {{--<th style="text-align: center">Date</th>
                                    <th style="text-align: center">Location</th>
                                    <th style="text-align: center">Category</th>--}}
                                    <th style="text-align: center">Item</th>
                                    <th style="text-align: center">Opening Stock <br> on {!! $result->from_date !!}</th>
                                    <th style="text-align: center">Stock Added</th>
                                    <th style="text-align: center">Stock removed</th>
                                    <th style="text-align: center">Closing Stock <br> on {!! $result->to_date !!}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if( isset($result->result) && sizeof($result->result) > 0 )

                                    @foreach( $result->result as $res )
                                        <?php //print_r($res->date);exit;?>
                                        <tr style="text-align: center">
                                            {{--<td>{!! $res->date !!}</td>
                                           <td>{!! $res->location !!}</td>
                                            <td>{!! $res->category !!}</td>--}}
                                            <td style="text-align: left">{!! $res->item !!}</td>
                                            <td style="text-align: right">{!! $res->opening_stock !!}</td>
                                            <td style="text-align: right">{!! $res->added_stock !!}</td>
                                            <td style="text-align: right">{!! $res->removed_stock !!}</td>
                                            <td style="text-align: right">{!! $res->closing_stock !!}</td>
                                        </tr>
                                    @endforeach
                                @else

                                @endif
                            </tbody>
                        @else
                            <th>No record found.</th>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>