@extends('partials.default')
@section('pageHeader-left')
    {{ trans('Restaurant_Index.Outlet') }}
@stop
@section('pageHeader-right')
    <a href="/outlet/create" class="btn btn-primary">{{ trans('Restaurant_Index.Add') }}</a>
    <a href="/outletBind" class="btn btn-primary">Bind</a>

    <a href="/menuBind" class="btn btn-primary">Menu Bind</a>@stop
@section('content')

<div class="row">

                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="OutletTable">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('Restaurant_Index.Outlet Name') }}</th>
                                            <th>{{ trans('Restaurant_Index.Web Address') }}</th>
                                            <th>{{ trans('Restaurant_Index.Address') }}</th>
                                            <th>{{ trans('Restaurant_Index.User Name') }}</th>
                                            <th>{{ trans('Restaurant_Index.Active') }}</th>
                                            <th>{{ trans('Restaurant_Index.Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($Outlets as $Outlet)
                                        <?php $name=ucfirst($Outlet->name);
                                        $username=\App\Owner::where('id',$Outlet->owner_id)->first();?>
                                        <tr class="odd gradeX">
                                            <td>{{$name or ''}}</td>
                                            <td><a href={{$Outlet->url}}>{{$Outlet->url or ''}}</a></td>

                                            <td>{{$Outlet->address or ''}}</td>
                                            <td>{{$username->user_name}}</td>
                                            <td>{{$Outlet->active or ''}}</td>
                                            <td><a href="/outlet/{{$Outlet->id or '' }}">{{ trans('Restaurant_Index.Show') }}</a>|<a href="/outlet/{{$Outlet->id or '' }}/edit">{{ trans('Restaurant_Index.Edit') }}</a>|<a href="/outlet/{{$Outlet->id or ''}}/destroy">{{ trans('Restaurant_Index.Delete') }}</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
    @stop
@section('page-scripts')
             <script>
                $(document).ready(function() {


                    @if(Session::has('success'))
                                    successErrorMessage('{{ Session::get('success') }}','success');
                    @endif
                    @if(Session::has('error'))
                        successErrorMessage('{{ Session::get('error') }}','error');
                    @endif

                    $('#OutletTable').DataTable({
                        responsive: true,
                        pageLength: 100
                    });
                });
                </script>
@stop