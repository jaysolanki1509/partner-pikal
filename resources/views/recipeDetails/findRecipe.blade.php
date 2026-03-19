<?php
        use App\Menu;
        use App\Unit;
?>
@extends('partials.default')
@section('pageHeader-left')
    Ingredients For {{$recipe_name}}
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
    <div class="row well">

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="StatusTable">
                                <thead>
                                <th>Ingredients</th>
                                <th>Qty</th>
                                <th>Unit</th>

                                {{--<th>Action</th>--}}
                                </thead>

                                <tbody>
                                @foreach($ingredients as $ingredient)
                                    <tr class="odd gradeX">
                                    <?php
                                            $temp_qty = $ingredient->qty/$referance;
                                            $ingred_qty = $temp_qty*$qty;

                                            $unit_name = Unit::getUnitbyId($ingredient->unit_id);
                                        ?>

                                        {{--<td>Tea</td>--}}
                                        {{--<td>1</td>--}}
                                        {{--<td>Kg</td>--}}
                                        <td>{{$ingredient->name or ''}}</td>
                                        <td>{{$temp_qty or ''}}</td>
                                        <td>{{$unit_name->name or ''}}</td>

                                        {{--<td><a href="/recipeDetails/show">View</a></td>--}}
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
    </div>

    <script>
        $(document).ready(function() {
            $('#StatusTable').DataTable({
                responsive: true,
                pageLength: 100
            });
        });
    </script>

@stop