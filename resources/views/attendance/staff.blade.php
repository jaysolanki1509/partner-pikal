@extends('partials.default')
@section('pageHeader-left')
    Staff Detail
@stop

@section('pageHeader-right')
    <a href="/staffs/create" class="btn btn-primary" title="Add new Staff"><i class="fa fa-plus"></i> Staff</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">

                        @if( isset($outlet_id) && $outlet_id != '' )
                            <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="staffTable">

                                <thead>
                                    <th class="hide">id</th>
                                    <th>Name</th>
                                    <th data-hide="phone">Per Day</th>
                                    <th>Role</th>
                                    <th data-hide="phone">Shift</th>
                                    <th data-sort-ignore="true">Action</th>
                                </thead>

                                <tbody>
                                    @if(isset($staffs) && sizeof($staffs) > 0 )
                                        @foreach($staffs as $stf)

                                            <tr class="odd gradeX">
                                                <td class="hide">{!! $stf->id !!}</td>
                                                <td>{!! $stf->name !!}</td>
                                                <td>{!! $stf->per_day !!}</td>
                                                <td>{!! $stf->role !!}</td>
                                                <td>{!! $stf->shift !!}</td>
                                                <td>
                                                    <a href="/staffs/{!! $stf->id !!}/edit" title="Edit">
                                                        <span class="zmdi zmdi-edit" ></span>
                                                    </a>
                                                </td>
                                            </tr>

                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        @else
                            <h3 style="text-align:center; color: red;">Please select Outlet.</h3>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('page-scripts')
    <script src="/assets/js/new/lib/footable.all-min.js"></script>
    <script>
        @if( isset($outlet_id) && $outlet_id != '' )
            $(document).ready(function() {

                @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
                @endif
                @if(Session::has('error'))
                    successErrorMessage('{{ Session::get('error') }}','error');
                @endif

            });
        @endif
        function warn(ele,id) {
            var temp = confirm("Do you want to remove the entry?");
            if (temp == true) {
                var route = "/location/"+id+"/destroy";
                //ele.('href', route);
                window.location.replace(route);
            }
        }
    </script>
@stop