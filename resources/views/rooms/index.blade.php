@extends('partials.default')
@section('pageHeader-left')
    Rooms
@stop

@section('pageHeader-right')
    <a href="/rooms/create" class="btn btn-primary" title="Add new Rooms"><i class="fa fa-plus"></i> Rooms</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="RoomAmenityTable">
                            <thead>
                                <th style="display: none">id</th>
                                <th>Name</th>
                                <th>Room Type</th>
                                <th>Room Status</th>
                                <th data-sort-ignore="true">Action</th>
                            </thead>
                            <tbody>
                                @if( isset($rooms) && sizeof($rooms) > 0 )
                                    @foreach($rooms as $room)

                                        <tr class="odd gradeX">
                                            <td style="display: none">{!! $room->id !!}</td>
                                            <td>{!! $room->name !!}</td>
                                            <td>{!! $room_types[$room->room_type_id] or "" !!}</td>
                                            <td>{!! $room_status[$room->room_status_id] or "" !!}</td>
                                            <td>
                                                <a href="/rooms/{!! $room->id !!}/edit" title="Edit">
                                                    <span class="zmdi zmdi-edit" ></span>
                                                </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a onclick="del('{!! $room->id !!}')" href="#" title="Delete">
                                                    <span class="zmdi zmdi-close" ></span>
                                                </a>
                                            </td>
                                        </tr>

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">No Room Amenity found.</td>
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
                text: "You will not be able to recover this Room Details!",
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
                        url: '/rooms/'+id,
                        type: "DELETE",
                        success: function (data) {
                            if (data == 'success') {
                                swal({
                                    title : "Deleted!",
                                    text : "Your Room Details has been removed.",
                                    type : "success"
                                },function () {
                                    var route = "/rooms";
                                    window.location.replace(route);
                                });
                            } else {
                                alert('please try again later.');
                            }
                        }
                    });
                } else {
                    swal("Cancelled", "Your Room Details are safe :)", "error");
                }
            });

        }

    </script>
@stop