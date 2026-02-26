
@extends('partials.default')
@section('pageHeader-left')
   Unit Master
@stop
@section('pageHeader-right')
    <a href="/unit/create" class="btn btn-primary" title="Add new Unit">
        <i class="fa fa-plus"></i>&nbsp;Unit
    </a>
@stop
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="unitstable" >
                            <thead>
                                <tr>
                                    <th>Units</th>
                                    <th data-sort-ignore="true">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $unit)

                                    <tr class="odd gradeX">
                                        <td>{{$unit->name or ''}}</td>
                                        <td>
                                            <a href="/unit/{{ $unit->id }}/edit" title="Edit">
                                                <span class="zmdi zmdi-edit" ></span>
                                            </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="#" onclick="del('{!! $unit->id !!}')" title="Delete">
                                                <span class="zmdi zmdi-close-circle"></span>
                                            </a>
                                        </td>
                                    </tr>

                                @endforeach
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

        $(document).ready(function () {

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
                text: "You will not be able to recover this Unit Details!",
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
                        text : "Your Unit Details has been removed.",
                        type : "success"
                    },function() {
                        var route = "/unit/"+id+"/destroy";
                        window.location.replace(route);
                    });
                } else {
                    swal("Cancelled", "Your Unit Details are safe :)", "error");
                }
            });

        }
    </script>

@stop
