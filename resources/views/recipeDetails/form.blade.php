<?php  $m=0; ?>
<div class="row well">

    @if($action=='edit')
        {!! Form::model($recipe,['route' => array('recipeDetails.update',$recipe->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'recipeDetails', 'class' => 'autoValidate']) !!}
    @else
        {!! Form::open(['route' => 'recipeDetails.store', 'method' => 'post', 'class' => 'autoValidate', 'id' => 'recipeDetails']) !!}
    @endif

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">Item Name:-</label>
            </div>

            <div class="col-md-3 form">
                <?php if($items!=null) $item=$items; else $item=array('select'=>'Select'); ?>
                {!! Form::select('menu_item_id', $item, null, ['onchange'=>'getUnit(this,"main")','class' => 'form-control','required', 'id' => 'menu_item_id']) !!}

            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">Reference Qty:-</label>
            </div>

            <div class="col-md-4 form">
                <div class="col-md-5" style="margin-left: -15px">
                    {!! Form::input('number','referance' ,1, ['class' => 'form-control','required','id'=>'referance','min' => 1,'placeholder'=>'Qty'] ) !!}
                </div>

                <div class="col-md-5">
                    {!! Form::hidden('unit_id', null, ['class' => 'form-control',"id"=>"unit_id"]) !!}
                    @if($action == 'add' )
                        {!! Form::text('unit', null, ['class' => 'form-control',"id"=>"unit","readonly"=>"readonly"]) !!}
                    @else
                        {!! Form::text('unit', $unit->name, ['class' => 'form-control',"id"=>"unit","readonly"=>"readonly"]) !!}
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-12"><hr></div>
        <ul class="nav nav-tabs">
            <li class="ingred-li active"><a href="#ingredients" onclick="openTab('ingred-tab','ingred-li','method-li')">Ingredients</a></li>
            <li class="method-li"><a href="#recipe" onclick="openTab('method-tab','method-li','ingred-li')">Recipe</a></li>
        </ul>
        <div class="col-md-12"><hr></div>

        <div class="tab-content nopadding noborder">

            <div id="ingred-tab" class="tab-pane active">

                <div class="col-md-10">
                    <div class="form">
                        <label id="warning_text" style="color: #ff0000; display:none; " class="control-label">All fields are required.</label>
                    </div>
                </div>
                <div class="col-md-12">

                    <div class="col-md-3">
                        <label class="control-label">Name:-</label>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Qty:- </label>
                    </div>
                    <div class="col-md-4 form">
                        <label class="control-label">Unit:- </label>
                    </div>
                </div>

                @if($action == 'add' )
                    <div class="col-md-12 ing-div">
                        <div class="col-md-3 form">
                            {!! Form::select('ing_name[]', $item, null, ['onchange'=>'getUnit(this,0)','class' => 'form-control ing_sel ing-name']) !!}
                        </div>

                        <div class="col-md-3 form">
                            <input type="number" class="form-control ing-qty" value="1" name="ing_qty[]" placeholder="Qty" min="0">
                        </div>

                        <div class="col-md-2 form">
                            <div id="unit_div">
                                <input type="hidden" class="form-control ing-unit-id" name="ing_unit_id[]" >
                                <input type="text" class="form-control ing-unit" value="" disabled name="ing_unit[]"  >
                            </div>
                        </div>
                    </div>
                @else
                    @if( isset($recipe_items) && sizeof($recipe_items) > 0 )
                        <?php $i=0;?>
                        @foreach( $recipe_items as $rec )

                            <div class="col-md-12 ing-div">
                                <div class="col-md-3 form">
                                    {!! Form::select('ing_name[]', $item, $rec->ing_item_id, ['onchange'=>'getUnit(this,0)','class' => 'form-control ing_sel ing-name']) !!}
                                </div>
                                <div class="col-md-3 form">
                                    <input type="number" class="form-control ing-qty" value="{!! $rec->qty !!}" name="ing_qty[]" placeholder="Qty" min="0">
                                </div>

                                <div class="col-md-2 form">
                                    <div id="unit_div">
                                        <input type="hidden" value="{!! $rec->unit_id !!}" class="form-control ing-unit-id" name="ing_unit_id[]" >
                                        <input type="text" class="form-control ing-unit" value="{!! $rec->name !!}" disabled name="ing_unit[]"  >
                                    </div>
                                </div>
                                @if( $i > 0 )
                                    <button onclick='javascript:$(this).closest(".ing-div").remove()' class="btn btn-primary" type="button"><i class="fa fa-times"></i></button>
                                @endif
                            </div>
                            <?php $i++;?>
                        @endforeach
                    @endif
                @endif
                <div class="col-md-8">
                    <button type="button" class="btn btn-primary pull-right" id="addMore" style="margin-left: 10px;"><i class="fa fa-plus"></i>&nbsp;Ingredients</button>
                </div>
            </div>

            <div id="method-tab" class="tab-pane">

                <div class="col-md-12">
                    <div class="col-md-2 form">
                        <label class="control-label">Recipe:-</label>
                    </div>
                    <div class="col-md-7 form">
                        <?php if($items!=null) $item=$items; else $item=array('select'=>'Select'); ?>
                        @if($action == 'add' )
                            {!! Form::textarea('ingred_method', null, ['class' => 'form-control',"id"=>"ingred_method", "rows"=>"7"]) !!}
                        @else
                            {!! Form::textarea('ingred_method', null, ['class' => 'form-control',"id"=>"ingred_method", "rows"=>"7"]) !!}
                        @endif
                    </div>
                </div>

            </div>

            <div style="clear: both"></div>
            <div class="col-md-9">
                @if($action == 'add' )
                    <button type="submit" id="Submit" novalidate="novalidate" class="btn btn-primary  pull-right" style="margin-left: 15px;">Submit</button>
                @else
                    <button type="submit" id="Submit" novalidate="novalidate" class="btn btn-primary  pull-right" style="margin-left: 15px;">Update</button>
                @endif

            </div>
        </div>

    </form>
</div>

<?php $m++; ?>


{!!Form::hidden('countf',$m,array('id'=>'countf'))!!}
@stop

@section('page-scripts')
    <script>

        function openTab(id,li,rmli) {
            var i;
            var x = document.getElementsByClassName("tab-pane");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            $('.'+rmli).removeClass('active');
            document.getElementById(id).style.display = "block";
            $("."+li).addClass('active');
            $("#"+id).addClass('active');
        }

        $('#addMore').click(function()
        {
            $('.ing-div:last').find('.ing-name').css('border-color','');
            $('.ing-div:last').find('.ing-qty').css('border-color','');

            var name = $('.ing-div:last').find('.ing-name').val();
            var qty = $('.ing-div:last').find('.ing-qty').val();
            if ( name == '' || qty == '' ) {
                if ( name == '') {
                    $('.ing-div:last').find('.ing-name').css('border-color','red');
                    $('.ing-div:last').find('.ing-name').focus();
                }
                if ( qty == '') {
                    $('.ing-div:last').find('.ing-qty').css('border-color','red');
                    $('.ing-div:last').find('.ing-qty').focus();
                }
                return;
            }
            $('.ing-div:first').clone().insertAfter('.ing-div:last');
            $('.ing-div:last').find('.ing-name').val('');
            $('.ing-div:last').find('.ing-qty').val(1);
            $('.ing-div:last').find('.ing-unit').val('');
            $('.ing-div:last').append('<button onclick=javascript:$(this).closest(".ing-div").remove() class="btn" class="btn-danger" type="button"><i class="fa fa-times"></i></button>');

        });

        $(document).ready(function() {
            //getUnit($('#menu_item_id').val(),'main');
            $("#recipeDetails").validate({
                rules: {
                    "outlet_id": {
                        required: true
                    },
                    "recipe_name": {
                        required: true
                    },
                    "referance": {
                        required: true
                    },
                    "unit_id": {
                        required: true
                    },
                    "ing_name0": {
                        required: true
                    },
                    "ing_qty0": {
                        required: true
                    },
                    "ing_unit0": {
                        required: true
                    }
                },
                messages: {
                    "outlet_id": {
                        required: "*Outlet is required"
                    },
                    "recipe_name": {
                        required: "*Recipe Name is required"
                    },
                    "referance": {
                        required: "*Reference Qty is required"
                    },
                    "unit_id": {
                        required: "*Unit is required"
                    },
                    "ing_name0": {
                        required: "*Ingredients Name is required"
                    },
                    "ing_qty0": {
                        required: "*Ingredients Qty is required"
                    },
                    "ing_unit0": {
                        required: "*Ingredients Unit is required"
                    }
                }
            })

            $('#Submit').click(function(){
                $('.nming').validate();
                check_validate();
            });

        });

        function getmenu_title(){

            var outlet_id=$('#Outlet_id').val();
            var flag = "menuTitles";

            $('#loader').css('display','block');
            if(outlet_id!='' && outlet_id!="select") {
//                console.log("inside")
                $.ajax({
                    url: '/ajax/ajaxMenuList',
                    data:'outlet_id='+outlet_id+'&flag='+"menuTitles",
                    success: function(data) {
                        $('#menu_title').html(data.list);
                    }
                });
            }
        }

        function getmenu_item(){
            var count = $('#countf').val();
            for(var i=2;i<=count;i++){
                $("#id"+i).remove();
            }
            $('#countf').val(1);
            $('#count').val(1);
            $('#unit').val('');
            $('#unit_id').val('');
            var outlet_id = $('#outlet_id').val();
            //console.log(outlet_id);
            $('#loader').css('display','block');
            if(outlet_id!='' && outlet_id!="select") {
                $.ajax({
                    url: '/ajax/ajaxOutletItems',
                    data:'outlet_id='+outlet_id+'&flag='+"menus",
                    success: function(data) {
                        console.log(data)
                        $('#menu_item_id').html(data.list)
                        $('#ing_name0').html(data.list)
                    }
                });
            }
        }

        function getUnit(el,place){

            var item_id = el.value;

            if(item_id!='' && item_id!="select") {
                $.ajax({
                    url: '/ajax/ajaxGetItemUnit',
                    data:'item_id='+item_id+'&flag='+"menus",
                    success: function(data) {
                        if(place=="main"){
                            $('#unit').val(data[0].unit_name);
                            $('#unit_id').val(data[0].unit_id);
                            $("ing_name"+place).css('border-color','#ccc');
                            $("#warning_text").hide();
                        }else{
                            $(el).closest('.ing-div').find('.ing-unit').val(data[0].unit_name);
                            $(el).closest('.ing-div').find('.ing-unit-id').val(data[0].unit_id);
                            $("ing_name"+place).css('border-color','#ccc');
                            $("#warning_text").hide();
                        }
                    }
                });
            }
        }

        function check_validate(){
            var check=0;
            $(".ing_sel").each(function() {
                if($(this).val()==""){
                    //alert('please select from selectbox to add more.');
                    $(this).css('border-color','red');
                    check=1;
                    $("#warning_text").show();
                }
                else{
                    $("#warning_text").hide();
                    $(this).css('border-color','#ccc');
                }
            });
            if(check==0){
                document.getElementById('recipeDetails').submit();
            }
            if(check==1){
                $("#recipeDetails").submit(function(e){
                    e.preventDefault();
                });
            }
        }

    </script>

    @stop