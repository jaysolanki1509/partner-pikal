<?php use App\Outlet;
use App\MenuTitle;
use App\Menu;
use App\Unit;
?>

@extends('partials.default')
@section('pageHeader-left')
    Ingredients Details
@stop

@section('pageHeader-right')
    <a href="/recipeDetails/create" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Recipe</a>
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
                                <!--<th>Outlet</th>-->
                                {{--<th>Title</th>--}}
                                <th>Recipe Name</th>
                                <th>Qty</th>
                                <th>Action</th>
                            </thead>

                            <tbody>
                                @foreach($recipes as $recipe)
                                    <?php
                                            $outlet = Outlet::findOutlet($recipe->outlet_id);                                       

                                    ?>
                                    <tr class="odd gradeX">
                                        <!--<td>{!! $outlet->name or '' !!}</td>-->
                                        {{--<td>{!! $menuTitle->title or '' !!}</td>--}}
                                        {{--<td>{!! $menuItem->item or '' !!}</td>--}}
                                        <td onclick="item_click({{ $recipe->id }})"><a href="/recipeDetails/{{ $recipe->id }}/show">{!! $recipe->item !!}</a></td>
                                        <td>{!! $recipe->referance !!}{!! $recipe->unit_name or '' !!}</td>
                                        <td>
                                            &nbsp;<a class="btn btn-primary" href="/recipeDetails/{!! $recipe->id !!}/edit"><i class="fa fa-pencil"></i></a>
                                            &nbsp;<a class="btn btn-danger" onclick="warn(this,'{!! $recipe->id !!}')" href="#"><i class="fa fa-times"></i></a>
                                        </td>
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
            $('#StatusTable').DataTable({
                responsive: true,
                pageLength: 100
            });
        });

        function warn(ele,id) {
            var temp = confirm("Do you want to remove the entry?");
            if (temp == true) {
                var route = "/recipeDetails/"+id+"/destroy"
                //ele.('href', route);
                window.location.replace(route);
            }
        }
    </script>
@stop