@extends('partials.default')
@section('pageHeader-left')
    Staffing Detail
@stop

@section('pageHeader-right')
    <a href="/staffing/create" class="btn btn-primary" title="Add new Staffing"><i class="fa fa-plus"></i> Staffing</a>
@stop

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        @if( isset($outlet_id) && $outlet_id != '' )
                            <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="staffingTable">

                                <thead>
                                    <th class="hide">id</th>
                                    <th>Shift</th>
                                    <th>Role</th>
                                    <th>Qty</th>
                                    <th data-sort-ignore="true">Action</th>
                                </thead>

                                <tbody>
                                @if(isset($staffing) && sizeof($staffing) > 0 )
                                    @foreach($staffing as $stf)

                                        <tr class="odd gradeX">
                                            <td class="hide">{!! $stf->id !!}</td>
                                            <td>{!! $stf->shift !!}</td>
                                            <td>{!! $stf->role !!}</td>
                                            <td>{!! $stf->qty !!}</td>
                                            <td>
                                                <a href="/staffing/{!! $stf->id !!}/edit" title="Edit">
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
                var route = "/location/"+id+"/destroy"
                //ele.('href', route);
                window.location.replace(route);
            }
        }
    </script>
@stop