@extends('partials.default')
@section('pageHeader-left')
    Locations Details
@stop

@section('pageHeader-right')
    <a href="/location/stock-level" class="btn btn-primary">Set Stock Level</a>
    <a href="/location/create" class="btn btn-primary"><i class="fa fa-plus"></i> Location</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">

                        <table class="table foo-data-table" id="LocationTable" data-page-size="100" data-limit-navigation="4">
                            <thead>
                            <th>Location</th>
                            <th data-hide="phone">Outlet</th>
                            <th data-hide="phone">Default Location</th>
                            <th data-hide="phone">Created By</th>
                            <th>Action</th>
                            </thead>

                            <tbody>
                                @if(isset($locations) && sizeof($locations) > 0 )
                                    @foreach($locations as $location)
                                        <?php
                                        if ( isset($location->outlet_id) && $location->outlet_id != '' && $location->outlet_id != 0 ) {
                                            $name = \App\Outlet::Outletbyid2($location->outlet_id)['name'];
                                        } else {
                                            $name = '-';
                                        }
                                        $loc_name = $location->name;
                                        if ( isset($location->default_location) && $location->default_location == 1) {
                                            $loc_name = $location->name." (default)";
                                        }
                                        ?>
                                        <tr>
                                            <td>{!! $loc_name !!}</td>
                                            <td>{!! $name !!}</td>
                                            @if( $location->default_location == 1 )
                                                <td style="text-align: center"><i class="fa fa-check fa-6" aria-hidden="true"></i></td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{!! $location->created_by !!}</td>
                                            <td>
                                                <a class="row-edit" href="/location/{!! $location->id !!}/edit"><span class="zmdi zmdi-edit"></span></a>
                                                <a class="row-edit" onclick="del({!! $location->id !!})" href="#"><span class="zmdi zmdi-close"></span></a>
                                            </td>
                                        </tr>

                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot class="hide-if-no-paging">
                                <tr>
                                    <td colspan="6" class="footable-visible">
                                        <div class="pagination pagination-centered"></div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('page-scripts')

    <script src="/assets/js/new/lib/footable.all-min.js"></script>

    <script>
        $(document).ready(function() {

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

            $("#LocationTable").footable({
                phone:767,
                tablet:1024
            })
        });
        function del(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Location Details!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    swal({
                        title : "Deleted!",
                        text : "Your Location Details has been removed.",
                        type : "success"
                    },function() {
                        var route = "/location/"+id+"/destroy";
                        window.location.replace(route);
                    });
                } else {
                    swal("Cancelled", "Your Location Details are safe :)", "error");
                }
            });

        }
    </script>
@stop