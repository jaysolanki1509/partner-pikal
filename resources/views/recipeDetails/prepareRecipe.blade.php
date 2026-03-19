@extends('partials.default')
@section('pageHeader-left')
    Prepare Recipe
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

    <div class="col-md-12">
        <div class="widget-wrap" style="float: left;">
            <div class="widget-header block-header clearfix">
                <form id="pre_rec_filter" class="j-forms">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4 padding-right0">
                                {!! Form::label('Item','Item:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    <select id="recipe_id" name='recipe_id' class="form-control">
                                        <option value="">Select Item</option>
                                        @if( isset($recipes) && sizeof($recipes) > 0 )
                                            @foreach( $recipes as $rec )
                                                <option value="{!! $rec->id !!}" data-unit="{!! $rec->unit_id !!}">{!! $rec->item !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                {!! Form::label('quantity','Qty:', array('class' => 'col-md-12  control-label')) !!}
                                <div class="col-md-12">
                                    <input type="number" class="form-control" min="0" name="qty" value="" id="qty" placeholder="Quantity" >
                                </div>
                            </div>

                            <div class="col-md-2">
                                {!! Form::label('unit_id','Unit:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::select('unit_id',$units,null,array('id' => 'unit_id','class' => 'select2 form-control','disabled'=>'disabled')) !!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                {!! Form::label('for_loc','For Location:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::select('for_loc',$locations,null,array('id' => 'for_loc','class' => 'select2 form-control')) !!}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-footer">
                        <div class="col-md-11">
                            <button type="button" onclick="getRecipe(this)" class="btn btn-success btn-primary pull-right" id="show-recipe">Show Ingredients</button>
                            <button type="reset" onclick="resetform()" class="btn btn-success btn-danger hide pull-right" id="reset-recipe"><i class="fa fa-refresh"></i>&nbsp;Reset</button>
                        </div>
                    </div>

                </form>
            </div>
            <div class="widget-container">
                <div class="widget-content">
                    <div class="recepi-detail" id="recepi-detail">
                        <div style="text-align:center">No Recipe available.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script type="text/javascript">

        $(document).ready(function(){
            $('#recipe_id').select2({
                placeholder: 'Select Item'
            });
            $('#for_loc').select2({
                placeholder: 'Select Location'
            });

            $('#recipe_id').change(function() {
                var selected = $(this).find('option:selected');
                $('#unit_id').val(selected.data('unit')).change();

            });

            $("#pre_rec_filter").validate({
                rules: {
                    "recipe_id": {
                        required: true
                    },
                    "qty":{
                        required: true
                    },
                    'unit_id':{
                        required:true
                    },
                    "for_loc":{
                        required: true
                    }

                },
                messages: {

                    "recipe_id": {
                        required: "Item is required"
                    },
                    "qty": {
                        required: "Quantity is required"
                    },
                    'unit_id':{
                        required: "Unit is required"
                    },
                    "for_loc": {
                        required: "For Location is required"
                    }

                }

            });

        });

        function resetform() {

            $('#reset-recipe').addClass('hide');
            $('#show-recipe').removeClass('hide');

            $('#recipe_id').attr("disabled",false);
            $('#recipe_id').val('').change();
            $('#qty').attr("disabled", false);
            $('#for_loc').attr("disabled", false);
            $('#for_loc').val("").change();
            $('#unit_id').val("").change();

            $("#recipe_id").prop("selectedIndex", "");

            $('#recepi-detail').html('<div style="text-align: center">No ingrediants found</div>');
        }

        //display recipe items
        function getRecipe(el)
        {
            if( $('#pre_rec_filter').valid()) {

                $('.error').remove();
                var recipe_id = $('#recipe_id').val();
                var qty = $('#qty').val();
                var for_loc = $('#for_loc').val();


                if( recipe_id != '' )
                {
                    $(el).text('Loading...');
                    $(el).attr('disabled','disabled');

                    $('#show-recipe').addClass('hide');
                    $('#reset-recipe').removeClass('hide');

                    $('#recipe_id').attr("disabled", true);
                    $('#qty').attr("disabled", true);
                    $('#for_loc').attr("disabled", true);

                    $.ajax({
                        url:'/getRecipe',
                        data:'recipe_id='+recipe_id+'&qty='+qty,
                        success: function(data) {

                            $(el).text('Show Ingredients');
                            $(el).removeAttr('disabled');

                            $('#recepi-detail').html(data);

                            //display item stock on selected location
                            $('.loc_select').change(function() {
                                var selected = $(this).find('option:selected');
                                var stock = '';
                                if ( selected.data('stock') != undefined )
                                    stock = selected.data('stock');

                                $(this).parent().parent().find('.loc_stock').val(stock);

                            });

                            $('#prepare').click(function(){
                               prepare();
                            });
                        }

                    });
                }

            }


        }

        function prepare() {

            var error = false;

            $('.loc_qty').each(function(index){


                var location = $('.loc_select').eq(index).val();

                if( location == '' ) {
                    $('.loc_select').eq(index).css('border-color','red');
                    error = true;
                } else {
                    $('.loc_select').eq(index).css('border-color','')
                }

                var loc_stock = $('.loc_stock').eq(index).val().trim().split(' ');

                if ( loc_stock[0] == '' ) {

                    $('.loc_stock').eq(index).css('border-color','red');
                    error = true;

                } else {

                    var req_stock = $('.loc_qty').eq(index).val().split(' ');

                    if (parseFloat(loc_stock[0]) < parseFloat(req_stock)) {
                        $('.loc_stock').eq(index).css('border-color', 'red');
                        error = true;
                    } else {
                        $('.loc_stock').eq(index).css('border-color', '');
                    }

                }

            });

            var recipe_id = $('#recipe_id').val();
            var qty = $('#qty').val();
            var for_loc = $('#for_loc').val();
            var rec_unit_id = $('#unit_id').val();


            if ( error == true ) {
                $('#error-msg').text('Check error field and stock must be greater than quantity').removeClass('hide');
                return false;
            } else {
                $('#error-msg').addClass('hide');
            }

            var data = $('#prepareForm').serialize();

            $('#prepare').text('Preparing...');
            $('#prepare').attr('disabled','disabled');

            $.ajax({
                url:'/processPrepareItem',
                type:'POST',
                data:'for_loc='+for_loc+'&qty='+qty+'&recipe_id='+recipe_id+'&rec_unit_id='+rec_unit_id+'&'+data,
                dataType:'json',
                success: function(data) {

                    $('#prepare').text('Prepare');
                    $('#prepare').removeAttr('disabled');

                    if (data.error != true ) {
                        window.location = "/stocks";
                    } else {
                        $('#error-msg').text(data.error_msg).removeClass('hide');
                    }

                }

            });

        }


    </script>
@stop
