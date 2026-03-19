<?php use App\Outlet; ?>
@extends('partials.default')
@section('pageHeader-left')
 {{ trans('Cancellation.Cancellation Reason') }}
@stop
@section('pageHeader-right')
    <a href="/recipe/create" class="btn btn-primary">Add Recipe</a>
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
                                <th>Title</th>
                                <th>Ingrediants</th>
                                <th>Recipe</th>
                                <th>Shop url</th>
                                <th>Ingrediants url</th>
                                <th>Action</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($recipe as $recipe)

                                <tr class="odd gradeX">

                                    <td>{{$recipe->title or ''}}</td>
                                    <td>{{$recipe->ingrediants or ''}}</td>
                                    <td>{{$recipe->recipes or ''}}</td>
                                    <td>{{$recipe->shop_url or ''}}</td>
                                    <td>{{$recipe->ingrediants_url or ''}}</td>

                                    <td><a href="/recipe/{{$recipe->id or '' }}/edit">Edit</a>|<a href="/recipe/{{$recipe->id or ''}}/destroy">Delete</a></td>
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
