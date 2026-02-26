<?php
    use App\Outlet;
    use App\MenuTitle;
    use App\Menu;
    use App\Unit;
?>

@extends('app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default res-font">
                    <div class="panel-heading res-font">
                        <a href="/recipeDetails/create">Add Recipe</a>
                        <a href="/recipeDetails/show" style="float: right">Calculate Recipe</a>
                    </div>

                    <div class="panel-body">
                        @if(Session::has('success'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
                                {{ Session::get('success') }}
                            </div>
                        @endif

                        <div class="col-md-12">&nbsp;</div>

                        {{--<div class="col-md-12">--}}
                            {{--<div class="col-md-6 form">--}}
                                <table class="table table-striped table-bordered table-hover res-font" id="StatusTable">
                                    <thead>
                                        {{--<th>Outlet</th>--}}
                                        {{--<th>Title</th>--}}
                                        <th>Recipe Name</th>
                                        <th>Qty</th>
                                        <th>Action</th>
                                    </thead>

                                    <tbody>
                                        @foreach($recipes as $recipe)
                                            <?php
                                            $outlet = Outlet::findOutlet($recipe->outlet_id);
                                            $menuTitle = MenuTitle::getmenutitleofoutletandid($recipe->outlet_id,$recipe->menu_title_id);
                                            $menuItem = Menu::getMenuItemByTitleIdandMenuId($recipe->menu_item_id);
                                            $unit = Unit::getUnitbyId($recipe->unit_id);
                                            ?>
                                            <tr class="odd gradeX">
                                                {{--<td>{!! $outlet->name or '' !!}</td>--}}
                                                {{--<td>{!! $menuTitle->title or '' !!}</td>--}}
                                                {{--<td>{!! $menuItem->item or '' !!}</td>--}}
                                                <td>{!! $recipe->item !!}</td>
                                                <td>{!! $recipe->referance !!}{!! $unit->name or '' !!}</td>
                                                <td><a class="btn btn-primary" href="/recipeDetails/{!! $recipe->id !!}/destroy"><i class="fa fa-times"></i></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
{!! HTML::script('/assets/js/jquery.dataTables.min.js') !!}
{!! HTML::script('/assets/js/dataTables.bootstrap.js') !!}
{!! HTML::script('/assets/js/dataTables.responsive.js') !!}
{!! HTML::script('/assets/js/datatable.list.js') !!}
    <script>
        $(document).ready(function() {
            $('#StatusTable').DataTable({
                responsive: true,
                pageLength: 100,
                "dom": '<"col-sm-4"<"top"i>><"col-sm-4"<"top pull-left"l>><"top pull-right"f>rt<"bottom"p><"clear">'
            });
        });
    </script>

@endsection
