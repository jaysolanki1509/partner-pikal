@extends('partials.default')
@section('pageHeader-left')
    Guest Source
@stop

@section('pageHeader-right')
    <a href="/guest-source/create" class="btn btn-primary" title="Add Guest Source"><i class="fa fa-plus"></i> Guest Source</a>
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
                                @if( isset($guest_source) && sizeof($guest_source) > 0 )
                                    @foreach($guest_source as $gs)

                                        <tr class="odd gradeX">
                                            <td style="display: none">{!! $gs->id !!}</td>
                                            <td>{!! $gs->name !!}</td>
                                            <td>{!! $gs->description !!}</td>
                                            <td>
                                                <a href="/guest-source/{!! $gs->id !!}/edit" title="Edit">
                                                    <span class="zmdi zmdi-edit" ></span>
                                                </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a onclick="del('{!! $gs->id !!}')" href="#" title="Delete">
                                                    <span class="zmdi zmdi-close" ></span>
                                                </a>
                                            </td>
                                        </tr>

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">No Guest Source found.</td>
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
                text: "You will not be able to recover this Guest Source Details!",
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
                        url: '/guest-source/'+id,
                        type: "DELETE",
                        success: function (data) {
                            if (data == 'success') {
                                swal({
                                    title : "Deleted!",
                                    text : "Your Guest Source Details has been removed.",
                                    type : "success"
                                },function () {
                                    var route = "/guest-source";
                                    window.location.replace(route);
                                });
                            } else {
                                alert('please try again later.');
                            }
                        }
                    });
                } else {
                    swal("Cancelled", "Your Guest Source Details are safe :)", "error");
                }
            });

        }

    </script>
@stop