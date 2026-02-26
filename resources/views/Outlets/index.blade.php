@extends('partials.default')
@section('pageHeader-left')
    Outlet
@stop
@section('pageHeader-right')
    <a href="/printer-bind" class="btn btn-primary">Bind Printer</a>
    <a href="/outlet/create" class="btn btn-primary" title="Add new outlet"><i class="fa fa-plus"></i> Outlet</a>
@stop
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="widget-wrap material-table-widget">
            <div class="widget-container margin-top-0">
                <div class="widget-content">
                    <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="OutletTable">
                        <thead>
                            <tr>
                                <th>{{ trans('Restaurant_Index.Outlet Name') }}</th>
                                <th data-sort-ignore="true" data-hide="phone">Website</th>
                                <th data-sort-ignore="true" data-hide="phone">{{ trans('Restaurant_Index.Address') }}</th>
                                <th>{{ trans('Restaurant_Index.User Name') }}</th>
                                <th data-hide="phone">{{ trans('Restaurant_Index.Active') }}</th>
                                <th data-sort-ignore="true">{{ trans('Restaurant_Index.Actions') }}</th>
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
                                    <td><a class="row-edit" href="/outlet/{{$Outlet->oid or '' }}"><span class="zmdi zmdi-file-text"></span></a>
                                        <a class="row-edit" href="/outlet/{{$Outlet->oid or '' }}/edit">
                                            <span class="zmdi zmdi-edit" ></span>
                                        </a>
                                        {{--|<a href="/outlet/{{$Outlet->oid or ''}}/destroy">{{ trans('Restaurant_Index.Delete') }}</a>--}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="hide-if-no-paging">
                            <tr>
                                <td colspan="6" class="footable-visible">
                                    <div class="pagination pagination-centered"></div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>
            <!-- /.row -->
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
    </script>

@stop