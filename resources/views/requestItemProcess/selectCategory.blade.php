<?php
use App\Owner;
?>
@extends('partials.default')
@section('pageHeader-left')
    Select Category
@stop
@section('pageHeader-right')
    <a href="/responseItems/getSatisfiedItemTime" class="btn btn-primary">Back</a>
@stop
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong>There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('error') }}
        </div>

    @endif


    <div class="row">
        <div class="col-lg-12">
            {{--<div class="panel panel-default">--}}
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="ProcessRequestTable" style="width: 500px;">
                        <thead>
                        <th class="text-center">Select Category</th>
                        <th class="text-center">Go</th>
                        </thead>

                        <tbody>
                        <tr>
                            <td>All</td>
                            <td class="text-center"><a href="/responseItems/{!! $selected_user_id !!}/{!! $selected_time !!}/{!! "All" !!}/getSatisfiedItem"><i class="glyphicon glyphicon-arrow-right"></i>
                                </a></td>
                        </tr>
                        @foreach($categories as $category)
                            <tr class="odd gradeX">
                                <td>{!! $category -> title !!}</td>
                                <td class="text-center"><a href="/responseItems/{!! $selected_user_id !!}/{!! $selected_time !!}/{!! $category->cate_id !!}/getSatisfiedItem"><i class="glyphicon glyphicon-arrow-right"></i>
                                    </a></td>

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
    <!-- /.row -->

@stop