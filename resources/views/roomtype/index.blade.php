@extends('partials.default')
@section('pageHeader-left')
    Room Types
@stop

@section('pageHeader-right')
    <a href="/room-type/create" class="btn btn-primary" title="Add Room Status"><i class="fa fa-plus"></i> RoomType</a>
@stop

@section('content')
    <style>
        .scrollit {
            overflow-y: hidden;
            overflow-x: auto;    /* Trigger vertical scroll    */
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content scrollit">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="RoomStatusTable">
                            <thead>
                                <th style="display: none">id</th>
                                <th>Short Name</th>
                                <th>Room Type</th>
                                <th>Base Occupancy</th>
                                <th>Higher Occupancy</th>
                                <th>Base Price</th>
                                <th>Higher Price / Person</th>
                                <th data-sort-ignore="true">Action</th>
                            </thead>
                            <tbody>
                                @if( isset($room_types) && sizeof($room_types) > 0 )
                                    @foreach($room_types as $rt)

                                        <tr class="odd gradeX">
                                            <td style="display: none">{!! $rt->id !!}</td>
                                            <td>{!! $rt->short_name !!}</td>
                                            <td>{!! $rt->name !!}</td>
                                            <td>{!! $rt->base_occupancy !!}</td>
                                            <td>{!! $rt->higher_occupancy !!}</td>
                                            <td>{!! $rt->base_price !!}</td>
                                            <td>{!! $rt->higher_price_per_person !!}</td>
                                            <td>
                                                <a href="/room-type/{!! $rt->id !!}/edit" title="Edit">
                                                    <span class="zmdi zmdi-edit" ></span>
                                                </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a onclick="del('{!! $rt->id !!}')" href="#" title="Delete">
                                                    <span class="zmdi zmdi-close" ></span>
                                                </a>
                                            </td>
                                        </tr>

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">No Room Status found.</td>
                                    </tr>
                                @endif

                            </tbody>
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
        });

        function del(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this RoomType Details!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: '/room-type/'+id,
                        type: "DELETE",
                        success: function (data) {
                            if (data == 'success') {
                                swal({
                                    title : "Deleted!",
                                    text : "Your RoomType Details has been removed.",
                                    type : "success"
                                },function () {
                                    var route = "/room-type";
                                    window.location.replace(route);
                                });
                            } else {
                                alert('please try again later.');
                            }
                        }
                    });
                } else {
                    swal("Cancelled", "Your RoomType Details are safe :)", "error");
                }
            });

        }

    </script>
@stop