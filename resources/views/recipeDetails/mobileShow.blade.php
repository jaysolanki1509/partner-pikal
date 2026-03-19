<?php
use App\Outlet;
use App\MenuTitle;
use App\Menu;
use App\Unit;
?>

@extends('app')

@section('content')
    <style>
        .row {
            margin-left: -30px;
            margin-right: -30px;
    </style>

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

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading res-font">Calculate Recipe
                        <a href="/recipeDetails/" style="float: right;">Back</a>
                    </div>

                    <div class="panel-body">
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

                        <div class="row well">
                            <form class="form-horizontal" role="form" method="POST" id="findRecipe" novalidate="novalidate" action="{{ url('/recipeDetails/find') }}" files="true">
                                <div class="col-md-12 form">
                                    <div class="col-md-3 form">
                                        <label class="control-label">Recipe:-</label>
                                    </div>
                                    <div class="col-md-12 form  res-input input-padding res-font" style="width:100%;float: left;">
                                        {!! Form::select('recipe',$recipes, $recipe, ['onchange'=>'getQtyUnit()', 'class' => 'form-control res-font','id'=>'recipe']) !!}
                                        <label class="error" for="recipe" generated="true" style="display: none; color: red;"></label>
                                    </div>
                                </div>
                                    <div class="clearfix"></div>
                                <div class="col-md-12">&nbsp;</div>

                                <div class="col-md-12">
                                    <div class="col-md-3 form" style="width:50%;float: left;">
                                        <label class="control-label">Minimum Qty:-</label>
                                    </div>
                                    <div class="col-md-3 form" style="width:50%;float: left;">
                                        <label class="control-label">Needed Qty:-</label>
                                    </div>
                                    <div class="col-md-6 form res-input input-padding res-font" style="width:50%;float: left;">
                                        <div id="unit_div">
                                            {!! Form::label('fix_unit', isset($unit_box) ? $unit_box : "Minimum", ['class' => 'form-control res-font','id' => 'fix_unit']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6 res-input input-padding res-font" style="width:50%;float: left;" id="need_qty_div" >
                                        <!--<input type="number" id="qty" calss='form-control' value="{!! $qty1 !!}"  min=''> -->
                                        {!! Form::input('number','qty' ,$qty1!=''? $qty1 :1, ['placeholder'=>'qty','class' => 'form-control res-font','id'=>'qty','min' => $referance!='' ? $referance : 1  ] ) !!}
                                        <label class="error" for="qty" generated="true" style="display: none; color: red;"></label>
                                    </div>

                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12">&nbsp;</div>
                                {{--<div class="col-md-12">
                                    <div class="col-md-3 form">
                                        <label class="control-label">Needed Qty:-</label>
                                    </div>

                                    <div class="col-md-9 form">
                                        <div class="col-md-2" style="padding-left: 0px;" id="need_qty_div" >
                                            <!--<input type="number" id="qty" calss='form-control' value="{!! $qty1 !!}"  min=''> -->
                                            {!! Form::input('number','qty' ,$qty1!=''? $qty1 :1, ['placeholder'=>'Needed qty','class' => 'form-control','id'=>'qty','min' => $referance!='' ? $referance : 1  ] ) !!}
                                            <label class="error" for="qty" generated="true" style="display: none; color: red;"></label>
                                        </div>

                                        <div class="col-md-12">&nbsp;</div>

                                        <div class="col-md-3" style="padding-left: 0px;" id="need_unit_div">
                                            {!! Form::input('text','unit' ,isset($needed_unit) ? $needed_unit : null, ['placeholder'=>'Unit','disabled','class' => 'form-control','id'=>'unit' ] ) !!}

                                        </div>
                                    </div>
                                </div>--}}


                                <div class="col-md-12">
                                    <div class="col-md-6" style="width:50%;float: right;">
                                        <button type="submit" class="btn btn-primary res-font" novalidate="novalidate" id="check" style="float: right;">Calculate</button>
                                    </div>

                                </div>
                            </form>
                            <div class="clearfix"></div>
                            <div class="col-md-12"><hr></div>

                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="dataTable_wrapper">
                                            <table class="table table-striped table-bordered table-hover res-font" id="StatusTable">
                                                <thead>
                                                <th>Ingredients</th>
                                                <th>Qty</th>
                                                <th>Unit</th>

                                                {{--<th>Action</th>--}}
                                                </thead>

                                                <tbody id="ingred_table">
                                                @if(isset($data_table))
                                                    @foreach($data_table as $data_ingred)
                                                        <?php //print_r($data_ingred); ?>
                                                        <tr>
                                                            <td><?php echo $data_ingred->item; ?></td>
                                                            <td><?php echo number_format(($data_ingred->qty*$qty1)/$referance,1); ?></td>
                                                            <td><?php echo \App\Unit::getUnitbyId($data_ingred->unit_id)->name; ?></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>

                                        </div>
                                        <!-- /.table-responsive -->
                                        </div>
                                    <!-- /.panel-body -->
                                    </div>
                                <!-- /.panel -->
                                <div class="col-md-2">
                                    <button type="button"  onclick="printData()" id="check_print" class="btn btn-primary col-md-10" style="float: right; margin-right:0%; " >Share</button>
                                </div>
                                {!! Form::input('hidden','recipe_data' , isset($print_data) ? $print_data : null, ['disabled','class' => 'form-control','id'=>'recipe_data' ] ); !!}
                            </div>

                        </div>
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

        function getrecipe(){
            var outlet_id = $('#outlet_id').val();
            if(outlet_id != '' && outlet_id != "select")
            {
                $.ajax({
                    url: '/ajax/recipeList',
                    data:'outlet_id='+outlet_id,
                    success: function(data) {
                        //alert(data);
                        $('#recipe').html(data.list)
                    }
                });
            }
        }

        function getQtyUnit(){
            var recipe_id = $('#recipe').val();
            $('#qty').val('');
            $('#unit').val('');
            $('#fix_unit').text('');
            if(recipe_id != '' && recipe_id != "select")
            {
                $.ajax({
                    url:'/ajax/ajaxQtyUnit',
                    data:'recipe_id='+recipe_id,
                    success: function(data) {

                        $('#qty').val(data.qty1);
                        $('#unit').val(data.need_unit_box);
                        $('#fix_unit').text(data.unit_box);
                        var ingred_count=data.ingred_count;

                        var table='';
                        for(var i=0;i<ingred_count;i++)
                        { var j=0;
                            table+='<tr><td>'+data.table_data[i][j]+'</td><td>'+data.table_data[i][++j]+'</td><td>'+data.table_data[i][++j]+'</td><tr>';
                        }
                        $('#ingred_table').html(table);
                        $('#recipe_data').val(data.print_data);
                        $('#check_print').show();
                        $('#qty').attr('min',data.qty1);
                    }
                });
            }
        }

        $(document).ready(function() {
            $('#StatusTable').dataTable({
                responsive: true,
                pageLength: 100,
                "dom": '<"col-sm-4"<"top"i>><"col-sm-4"<"top pull-left"l>><"col-sm-4"<"top pull-right"f>>rt<"bottom"p><"clear">'
            });
//            alert('hi');
           if( $('#recipe_data').val()!=""){
//               alert('not null');
               $('#check_print').show();
           }else{
//               alert('null');
               $('#check_print').hide();
           }

            $("#findRecipe").validate({
                rules: {
                    "outlet_id": {
                        required: true
                    },
                    "recipe": {
                        required: true
                    }
                },
                messages: {
                    "outlet_id": {
                        required: "Outlet is required"
                    },
                    "recipe": {
                        required: "Recipe Name is required"
                    }

                }
            })
        });

        function printData() {
            window.RecipeData.checkData($('#recipe_data').val());
        }

    </script>

@stop
