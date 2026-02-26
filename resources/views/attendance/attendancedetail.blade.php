<div class="row">
    <div class="col-md-12">
        <div class="widget-wrap material-table-widget">
            <div class="widget-container margin-top-0">
                <div class="widget-content">

                    <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4">
                        @if( isset($detail) && sizeof($detail) > 0 )
                            <thead>
                                <th>Date</th>
                                <th>In</th>
                                <th>Out</th>
                            </thead>
                            @foreach( $detail as $dl )
                                <tbody>
                                    <tr>
                                        <td>{!! date('Y-m-d',strtotime($dl->created_at)) !!}</td>
                                        <td>{!! date('H:i:s',strtotime($dl->in_time)) !!}</td>
                                        <td>{!! date('H:i:s',strtotime($dl->out_time)) !!}</td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">There is no data available.</td>
                            </tr>
                        @endif
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>