@extends('partials.default')
@section('pageHeader-left')
    Staff Roles Detail
@stop

@section('pageHeader-right')
    <a href="/staff-roles/create" class="btn btn-primary" title="Add new Role"><i class="fa fa-plus"></i> Staff Role</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="LocationTable">

                            <thead>
                                <th style="display: none">id</th>
                                <th>Role</th>
                                <th data-sort-ignore="true">Action</th>
                            </thead>

                            <tbody>
                                @if(isset($roles) && sizeof($roles) > 0 )
                                    @foreach($roles as $role)

                                        <tr class="odd gradeX">
                                            <td style="display: none">{!! $role->id !!}</td>
                                            <td>{!! $role->name !!}</td>
                                            <td>
                                                <a href="/staff-roles/{!! $role->id !!}/edit" title="Edit">
                                                    <span class="zmdi zmdi-edit" ></span>
                                                </a>
                                            </td>
                                        </tr>

                                    @endforeach
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


            $('#LocationTable').DataTable({
                responsive: true,
                "order": [[ 0, "desc" ]],
                pageLength: 100
            });
        });
        function warn(ele,id) {
            var temp = confirm("Do you want to remove the entry?");
            if (temp == true) {
                var route = "/location/"+id+"/destroy"
                //ele.('href', route);
                window.location.replace(route);
            }
        }
    </script>
@stop