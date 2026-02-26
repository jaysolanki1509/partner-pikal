@extends('partials.default')
@section('pageHeader-left')
    Arrival Departure Mode
@stop

@section('pageHeader-right')
    <a href="/arrival-departure-mode/create" class="btn btn-primary" title="Add Arrival Departure Mode"><i class="fa fa-plus"></i> Arrival Departure Mode</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="">
                            <thead>
                                <th style="display: none">id</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th data-sort-ignore="true">Action</th>
                            </thead>
                            <tbody>
                                @if( isset($arrival_departure_mode) && sizeof($arrival_departure_mode) > 0 )
                                    @foreach($arrival_departure_mode as $adm)

                                        <tr class="odd gradeX">
                                            <td style="display: none">{!! $adm->id !!}</td>
                                            <td>{!! $adm->name !!}</td>
                                            <td>{!! $adm->description !!}</td>
                                            <td>
                                                <a href="/arrival-departure-mode/{!! $adm->id !!}/edit" title="Edit">
                                                    <span class="zmdi zmdi-edit" ></span>
                                                </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a onclick="del('{!! $adm->id !!}')" href="#" title="Delete">
                                                    <span class="zmdi zmdi-close" ></span>
                                                </a>
                                            </td>
                                        </tr>

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">No Arrival Departure Mode found.</td>
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
                text: "You will not be able to recover this Arrival Departure Mode Details!",
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
                        url: '/arrival-departure-mode/'+id,
                        type: "DELETE",
                        success: function (data) {
                            if (data == 'success') {
                                swal({
                                    title : "Deleted!",
                                    text : "Your Arrival Departure Mode Details has been removed.",
                                    type : "success"
                                },function () {
                                    var route = "/arrival-departure-mode";
                                    window.location.replace(route);
                                });
                            } else {
                                alert('please try again later.');
                            }
                        }
                    });
                } else {
                    swal("Cancelled", "Your Arrival Departure Mode Details are safe :)", "error");
                }
            });

        }

    </script>
@stop