@extends('partials.default')
@section('pageHeader-left')
    Ingredients for {{$qty1}}{{$ingred_unit}} {{$recipe}}
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

<?php //print_r($recipes);print_r($recipe);exit;?>
    <div class="row well">
        {{--<form class="form-horizontal" role="form" method="POST" id="findRecipe" novalidate="novalidate" action="{{ url('/recipeDetails/find') }}" files="true">--}}


            <div class="col-md-12 hide">
                <div class="col-md-3 form">
                    <label class="control-label">Recipe:-</label>
                </div>
                <div class="col-md-4 form">
                    {!! Form::select('recipe',$recipes, $recipe, ['onchange'=>'getQtyUnit()', 'class' => 'form-control','id'=>'recipe']) !!}

                </div>
            </div>

            {{--<div class="col-md-12">
                <div class="col-md-3 form">
                    <label class="control-label">Minimum Qty:-</label>
                </div>

                <div class="col-md-4 form">
                    <div id="unit_div">
                        {!! Form::label('fix_unit', isset($unit_box) ? $unit_box : 1, ['class' => 'form-control','id' => 'fix_unit']) !!}
                    </div>
                </div>

            </div>--}}

            <div class="col-md-12">
                <div class="col-md-3 form">
                    <label class="control-label">Needed Qty:-</label>
                </div>

                <div class="col-md-9 form">
                    <div class="col-md-3" style="padding-left: 0px;" id="need_unit_div">
                        <div class="col-md-12 input-group">
                            {!! Form::input('number','qty' ,$qty1!=''? $qty1 :1, ['class' => 'form-control','id'=>'qty','min' => $referance!='' ? $referance : 1  ] ); !!}
                            <span id="sizing-addon1" class="input-group-addon">{!! Form::label('','',['id'=>'unit']) !!}</span>
                        </div>
                        {{--{!! Form::input('text','unit' ,isset($needed_unit) ? $needed_unit : null, ['disabled','class' => 'form-control','id'=>'unit' ] ); !!}--}}
                    </div>
                    <div class="col-md-6 input-group">
                        <button type="submit" class="btn btn-primary" id="check" onclick="getQtyUnit()" style="float: left;">Calculate</button>
                    </div>
                </div>

            </div>

            <div class="col-md-12">
                <div class="col-md-12">
                    {{--<button type="submit" id="submit" class="btn btn-primary col-md-2" style="float: right;">Check Recipe</button>--}}
                    {{--<a href="/recipeDetails/find" class="btn btn-primary" style="float: right;">Check Recipe</a>--}}
                </div>
            </div>
        {{--</form>--}}

        {!! Form::input('text','hidden_field','',['hidden','id'=>'hidden_field']); !!}
        <div class="col-md-12"><hr></div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="StatusTable">
                                <thead>
                                <th>Ingredients</th>
                                <th>Qty</th>
                                {{--<th>Unit</th>--}}

                                {{--<th>Action</th>--}}
                                </thead>

                                <tbody id="ingred_table">
                                @if(isset($data_table))
                                       @foreach($data_table as $data_ingred)
                                            <?php //print_r($data_ingred); ?>
                                            <tr>
                                                <td><?php echo $data_ingred->item; ?></td>
                                                <td><?php echo number_format(($data_ingred->qty*$qty1)/$referance,2); ?></td>
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
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>

@stop
@section('page-scripts')
    <script>


        function getrecipe(){
            var outlet_id = $('#outlet_id').val();
            if(outlet_id != '' && outlet_id != "select")
            {
                $.ajax({
                    url: '/ajax/recipeList',
                    data:'outlet_id='+outlet_id,
                    success: function(data) {

                        $('#recipe').html(data.list)
                    }
                });
            }
        }

        function getQtyUnit(){
            var recipe_id = $('#recipe').val();
            var needed_qty = $('#qty').val();
            $('#unit').val('');
            $('#fix_unit').text('');
            if(recipe_id != '' && recipe_id != "select")
            {
                $.ajax({
                    url:'/ajax/ajaxQtyUnit',
                    Type:'POST',
                    data:'recipe_id='+recipe_id+'&needed_qty='+needed_qty,
                    success: function(data) {
                        if($('#hidden_field').val()=='')
                            $('#qty').val(data.qty1);
                        $('#unit').text(data.need_unit_box);
                        $('#title_unit').text(data.need_unit_box);
                        $('#fix_unit').text(data.unit_box);
                        var ingred_count=data.ingred_count;

                        var table='';
                           for(var i=0;i<ingred_count;i++)
                           { var j=0;
                               table+='<tr><td>'+data.table_data[i][j]+'</td><td>'+data.table_data[i][++j]+' '+data.table_data[i][++j]+'</td><tr>';
                           }
                        $('#ingred_table').html(table);
                        $('#hidden_field').val(data.qty1);
                        $('#qty').attr('min',data.qty1);
                    }
                });
            }
        }



        $(document).ready(function() {
            getQtyUnit();
            //$('#qty').val($('#hidden_field').val());


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

    </script>

@stop