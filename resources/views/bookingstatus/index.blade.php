@extends('partials.default')
@section('pageHeader-left')
    Booking Status
@stop

@section('pageHeader-right')
    <a href="/booking-status/create" class="btn btn-primary" title="Add Booking Status"><i class="fa fa-plus"></i> BookingStatus</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="BookingStatusTable">
                            <thead>
                                <th style="display: none">id</th>
                                <th>Name</th>
                                <th>Color</th>
                                <th data-sort-ignore="true">Action</th>
                            </thead>
                            <tbody>
                                @if( isset($booking_status) && sizeof($booking_status) > 0 )
                                    @foreach($booking_status as $bs)

                                        <tr class="odd gradeX">
                                            <td style="display: none">{!! $bs->id !!}</td>
                                            <td>{!! $bs->name !!}</td>
                                            <td>{!! $bs->color !!}</td>
                                            <td>
                                                <a href="/booking-status/{!! $bs->id !!}/edit" title="Edit">
                                                    <span class="zmdi zmdi-edit" ></span>
                                                </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a onclick="del('{!! $bs->id !!}')" href="#" title="Delete">
                                                    <span class="zmdi zmdi-close" ></span>
                                                </a>
                                            </td>
                                        </tr>

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">No Booking Status found.</td>
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
                text: "You will not be able to recover this BookingStatus Details!",
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
                        url: '/booking-status/'+id,
                        type: "DELETE",
                        success: function (data) {
                            if (data == 'success') {
                                swal({
                                    title : "Deleted!",
                                    text : "Your BookingStatus Details has been removed.",
                                    type : "success"
                                },function () {
                                    var route = "/booking-status";
                                    window.location.replace(route);
                                });
                            } else {
                                alert('please try again later.');
                            }
                        }
                    });
                } else {
                    swal("Cancelled", "Your BookingStatus Details are safe :)", "error");
                }
            });

        }

    </script>
@stop