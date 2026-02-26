@extends('partials.default')
@section('pageHeader-left')
    Order Place Types
@stop

@section('pageHeader-right')
    <a href="/order-place-types/create" class="btn btn-primary"><i class="fa fa-plus"></i> Order Place</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">

                        <table class="table foo-data-table" id="OrderPlaceTable" data-page-size="100" data-limit-navigation="4">
                            <thead>
                            <th>Name</th>
                            <th data-sort-ignore="true">Action</th>
                            </thead>

                            <tbody>
                            @if ( isset($places) && sizeof($places) > 0 )
                                @foreach ( $places as $pl )

                                    <tr>
                                        <td>{!! $pl->name !!}</td>
                                        <td>
                                            <a class="row-edit" href="/order-place-types/{!! $pl->id !!}/edit" title="Edit"><span class="zmdi zmdi-edit"></span></a>
                                            <a class="row-edit" onclick="del({!! $pl->id !!})" title="Delete" href="#"><span class="zmdi zmdi-close"></span></a>
                                        </td>
                                    </tr>

                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2"> No place found. <a title="Add new Place" class="row-edit" href="/order-place-types/create"> Add from here</a></td>
                                </tr>
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

            $("#OrderPlaceTable").footable({
                        phone:767,
                        tablet:1024
                    })
        });
        function del(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this order place detail!",
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
                        text : "Your order place detail has been removed.",
                        type : "success"
                    },function() {
                        var route = "/order-place-types/"+id+"/destroy";
                        window.location.replace(route);
                    });
                } else {
                    swal("Cancelled", "Your order place details are safe :)", "error");
                }
            });

        }
    </script>
@stop