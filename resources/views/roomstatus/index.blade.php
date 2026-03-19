@extends('partials.default')
@section('pageHeader-left')
    Room Status
@stop

@section('pageHeader-right')
    <a href="/room-status/create" class="btn btn-primary" title="Add Room Status"><i class="fa fa-plus"></i> RoomStatus</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="RoomStatusTable">
                            <thead>
                                <th style="display: none">id</th>
                                <th>Name</th>
                                <th>Color</th>
                                <th data-sort-ignore="true">Action</th>
                            </thead>
                            <tbody>
                                @if( isset($room_status) && sizeof($room_status) > 0 )
                                    @foreach($room_status as $rs)

                                        <tr class="odd gradeX">
                                            <td style="display: none">{!! $rs->id !!}</td>
                                            <td>{!! $rs->name !!}</td>
                                            <td>{!! $rs->color !!}</td>
                                            <td>
                                                <a href="/room-status/{!! $rs->id !!}/edit" title="Edit">
                                                    <span class="zmdi zmdi-edit" ></span>
                                                </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a onclick="del('{!! $rs->id !!}')" href="#" title="Delete">
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
                text: "You will not be able to recover this RoomStatus Details!",
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
                        url: '/room-status/'+id,
                        type: "DELETE",
                        success: function (data) {
                            if (data == 'success') {
                                swal({
                                    title : "Deleted!",
                                    text : "Your RoomStatus Details has been removed.",
                                    type : "success"
                                },function () {
                                    var route = "/room-status";
                                    window.location.replace(route);
                                });
                            } else {
                                alert('please try again later.');
                            }
                        }
                    });
                } else {
                    swal("Cancelled", "Your RoomStatus Details are safe :)", "error");
                }
            });

        }

    </script>
@stop