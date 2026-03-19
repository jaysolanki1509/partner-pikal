<?php use App\Outlet; ?>
@extends('partials.default')
@section('pageHeader-left')
 {{ trans('Tax.Tax') }}
@stop
@section('pageHeader-right')
    <a href="/tax/create" class="btn btn-primary">{{ trans('Tax.Add') }}</a>
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
                        <table class="table table-striped table-bordered table-hover" id="TaxTable">
                            <thead>
                            <tr>
                                <th> {{ trans('Tax.Tax Type') }}</th>
                                <th> {{ trans('Tax.Tax Percentage') }}</th>

                                <th>{{ trans('Tax.Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tax as $tax)
                                <tr class="odd gradeX">
                                    <td>{{$tax->tax_type or ''}}</td>
                                    <td>{{$tax->tax_percent or ''}}</td>

                                    <td><a href="/tax/{{$tax->id or '' }}/edit">{{ trans('Tax.Edit') }}</a>|<a href="/tax/{{$tax->id or ''}}/destroy">{{ trans('Tax.Delete') }}</a></td>
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
            $('#TaxTable').DataTable({
                responsive: true,
                pageLength: 100
            });
        });
    </script>
@stop
