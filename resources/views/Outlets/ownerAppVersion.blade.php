
@extends('partials.default')
@section('pageHeader-left')
   Owner App Version
@stop

@section('content')

    <div class="row" id="outlet">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix">

                    <div class="widget-container">
                        <div class="widget-content">
                            <table class="table table-striped table-hover" id="outletBindTable">

                                <thead>
                                    <th width="50%">Outlet Name</th>
                                    <th>Owner Name</th>
                                    <th>App Version</th>
                                    {{--<th>Role</th>--}}
                                </thead>

                                <tbody>
                                    @if( isset($owner_arr) && sizeof($owner_arr) > 0 )

                                        @foreach( $owner_arr as $ow_arr )
                                            <tr>

                                                <td>{{ $ow_arr['outlet'] }}</td>
                                                <td>{{ $ow_arr['username'] }}</td>
                                                <td>{{ $ow_arr['version'] }}</td>

                                            </tr>
                                        @endforeach

                                    @else
                                        <tr><td> No record found.</td></tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop