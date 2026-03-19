<?php use App\Outlet; ?>
@extends('partials.default')
@section('pageHeader-left')
    {{ trans('Status.Status') }}
@stop
@section('pageHeader-right')
    <a href="/status/create" class="btn btn-primary">{{ trans('Status.Add') }}</a>
@stop
@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="StatusTable">
                            <thead>
                            <tr>
                                <th>{{ trans('Status.Outlet Name*') }}</th>
                                <th>{{ trans('Status.Order Flow') }}</th>
                                <th>{{ trans('Status.Status') }}</th>
                             <th>{{ trans('Status.Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($status as $status)

                                <?php $restname=Outlet::where('id',$status->outlet_id)->first();?>

                                <tr class="odd gradeX">
                                    <td>{{$restname['name'] or ''}}</td>
                                    <td>{{$status->order or ''}}</td>
                                    <td>{{$status->status or ''}}</td>
                                    <td><a href="/status/{{$status->id or '' }}">{{ trans('Status.Show') }}</a>|<a href="/status/{{$status->id or '' }}/edit">{{ trans('Status.Edit') }}</a>|<a href="/status/{{$status->id or ''}}/destroy">{{ trans('Status.Delete') }}</a></td>
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

    <script>
        $(document).ready(function() {
            $('#StatusTable').DataTable({
                responsive: true,
                pageLength: 100
            });
        });
    </script>
@stop
