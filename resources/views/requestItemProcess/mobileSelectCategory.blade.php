<?php
use App\Owner;
?>
@extends('app')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Select Category <a href="javascript:history.back()" style="float: right;">Back</a></div>
                    <div class="panel-body">
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

                        <div id="cates_id">
                            <div class="col-md-12">
                                <div class="col-md-6 form">

                                    <table class="table table-striped table-bordered table-hover" id="selectCateTable">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop