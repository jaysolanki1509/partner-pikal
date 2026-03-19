
        <div class="widget-wrap material-table-widget">
            <div class="widget-container margin-top-0">
                <div class="widget-content">
                    <table class="table table-striped table-bordered table-hover" data-page-size="100" data-limit-navigation="4">
                        @if( isset($data) && sizeof($data) > 0 )

                            <?php $j=0;?>
                            @foreach( $dates as $dt )

                                @if( $j == 0 )
                                    <tr>
                                        <th style="text-align: center">Date</th>
                                        @foreach( $staffs as $st )
                                            <th style="text-align: center">{!! $st->name !!}</th>
                                        @endforeach
                                    </tr>
                                @endif

                                <tr>
                                    <td style="text-align: center">{!! $dt !!}</td>
                                    @foreach( $staffs as $stf )

                                        <td style="text-align: center;@if( $data[$dt][$stf->id] == '00:00:00' ) color:red @endif">
                                            @if( $data[$dt][$stf->id] == '00:00:00' )
                                                {!! $data[$dt][$stf->id] !!}
                                            @else
                                                <a href="javascript:void(0)" onclick="viewAttendanaceDetail('{!! $dt !!}','{!! $stf->id !!}')">{!! $data[$dt][$stf->id] !!}</a>
                                            @endif
                                        </td>

                                    @endforeach
                                </tr>

                                <?php $j++;?>
                            @endforeach

                            <tr style="background-color: #31b131;color:#fff;">
                                <th style="text-align: center">Total Salary</th>
                                @foreach( $staffs as $st )
                                    <th style="text-align: center">{!! $salary[$st->id] !!}</th>
                                @endforeach
                            </tr>


                        @else
                            <tr>
                                <td colspan="6">There is no data available.</td>
                            </tr>
                        @endif
                    </table>

                </div>
            </div>
        </div>