
<?php  $m=0; ?>
<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">

                <div class="row">
                    <ul class="nav nav-tabs material-tabs">
                        <li class="detail-li active"><a href="#detail" onclick="openTab('detail-tab','detail-li','method-li','ingred-li')">Details</a></li>
                        <li class="ingred-li"><a href="#ingred" onclick="openTab('ingred-tab','ingred-li','method-li','detail-li')">Ingredients</a></li>
                        <li class="method-li"><a href="#method" onclick="openTab('method-tab','method-li','ingred-li','detail-li')">Recipe</a></li>
                    </ul>

                    <div class="tab-content" style="padding-top: 15px;">

                        <div id="detail-tab" class="tab-pane active">

                            <dl class="dl-horizontal">

                                <dt>Category</dt>
                                <dd>
                                    @if(isset($title[$item->menu_title_id]))
                                        {!! $title[$item->menu_title_id] !!}
                                    @else
                                        N/A
                                    @endif
                                </dd>

                                <dt>Alias</dt>
                                <dd>
                                    @if($item->alias != '')
                                        {!! $item->alias !!}
                                    @else
                                        N/A
                                    @endif
                                </dd>

                                <dt>Unit</dt>
                                <dd>
                                    {!! $units[$item->unit_id] !!}
                                </dd>

                                <dt>Price</dt>
                                <dd>
                                    @if($item->price != '')
                                        {!! $item->price !!}
                                    @else
                                        N/A
                                    @endif
                                </dd>

                                <dt>Brand</dt>
                                <dd>
                                    @if($item->brand != '')
                                        {!! $item->brand !!}
                                    @else
                                        N/A
                                    @endif
                                </dd>

                                <dt>Details</dt>
                                <dd>
                                    @if($item->details != '')
                                        {!! $item->details !!}
                                    @else
                                        N/A
                                    @endif
                                </dd>

                                <dt>Item Sequence</dt>
                                <dd>
                                    @if($item->item_order != '')
                                        {!! $item->item_order !!}
                                    @else
                                        N/A
                                    @endif
                                </dd>

                                <dt>Food</dt>
                                <dd>
                                    {!! $item->food !!}
                                </dd>

                                <dt>Active</dt>
                                <dd>
                                    @if($item->active=="1")
                                        No
                                    @else
                                        Yes
                                    @endif
                                </dd>

                                <dt>Is Sale</dt>
                                <dd>
                                    @if($item->is_sell=="1")
                                        No
                                    @else
                                        Yes
                                    @endif
                                </dd>

                                <dt>Item Image</dt>
                                <dd>
                                    @if($action=="add")
                                        <image src="/images/menu_default.png" width="48px" height="48px"/>
                                    @else
                                        @if(isset($item->image))
                                            <image src="data:image/png;base64,{!! $item->image !!}" width="48px" height="48px"/>
                                        @else
                                            <image src="/images/menu_default.png" width="48px" height="48px"/>
                                        @endif
                                    @endif
                                </dd>

                                <dt>Item Visibility</dt>
                                <dd>
                                    <table class="table table-striped table-bordered table-hover">
                                        <tr>
                                            <td style="text-align: center"><b>Outlet</b></td>
                                            <td style="text-align: center"><b>IsSale</b></td>
                                            <td style="text-align: center"><b>IsActive</b></td>
                                            <td style="text-align: center"  ><b>Bind</b></td>
                                        </tr>
                                        @if($action=="add")
                                            @foreach($myoutlets as $key=>$myoutlet)
                                                <tr>
                                                    <td> {!! $myoutlet !!} </td>
                                                    <td style="text-align: center">{!! Form::checkbox('sale['.$key.']', 1,true,['class'=>'outletmultiple']) !!}</td>
                                                    <td style="text-align: center">{!! Form::checkbox('act['.$key.']', 0,true,['class'=>'outletmultiple']) !!}</td>
                                                    <td style="text-align: center">{!! Form::checkbox('bind['.$key.']', 1,true,['class'=>'outletmultiple']) !!}</td>
                                                </tr>
                                            @endforeach
                                        @else

                                            @foreach($myoutlets as $key=>$myoutlet)
                                                <tr>
                                                    <td> {!! $myoutlet !!} </td>
                                                    <?php $bind=0; $active=0; $sale=0?>
                                                    @foreach($selected_outlet as $outlets)
                                                        @if($key==$outlets)
                                                            <?php $bind=1; ?>
                                                        @endif
                                                    @endforeach
                                                    @foreach($actives as $outlets)
                                                        @if($key==$outlets)
                                                            <?php $active=1; ?>
                                                        @endif
                                                    @endforeach
                                                    @foreach($sales as $outlets)
                                                        @if($key==$outlets)
                                                            <?php $sale=1; ?>
                                                        @endif
                                                    @endforeach
                                                    <td style="text-align: center">
                                                        @if($sale==1)
                                                            <i class="fa fa-check"></i>
                                                        @else
                                                            <i class="fa fa-close"></i>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center">
                                                        @if($active==0)
                                                            <i class="fa fa-check"></i>
                                                        @else
                                                            <i class="fa fa-close"></i>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center">
                                                        @if($bind==1)
                                                            <i class="fa fa-check"></i>
                                                        @else
                                                            <i class="fa fa-close"></i>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                        @endif

                                    </table>
                                </dd>

                            </dl>
                            <a class="btn btn-primary pull-right" href="/menu/{!! $item->id !!}/edit"> Edit </a>

                        </div>

                        <div id="ingred-tab" class="tab-pane" style="padding-top: 10px;">
                            @if($ing_action=='edit')
                                {!! Form::model($recipe,['route' => array('recipeDetails.update',$recipe->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'ingredientsForm', 'class' => 'autoValidate']) !!}
                            @else
                                {!! Form::open(['route' => 'recipeDetails.store', 'method' => 'post', 'class' => 'autoValidate', 'id' => 'ingredientsForm']) !!}
                            @endif
                            {!! Form::hidden('menu_item_id',$Itemid) !!}
                            <input type="hidden" name="flag" id="flag" value="item_master">

                            {{--<div class="col-md-12">
                                <div class="col-md-2 form">
                                    <label for="title" class="form">Name:- </label>
                                </div>
                                <div class="col-md-5 form">
                                    {!! $item->item !!}
                                    <input type="hidden" name="menu_item_id" id="menu_item_id" value="{!! $item->id !!}">
                                    <input type="hidden" name="flag" id="flag" value="item_master">
                                </div>
                                <div class="form">
                                    <label id="warning_text" style="color: #ff0000; display:none; " class="control-label">All fields are required.</label>
                                </div>
                            </div>--}}
                            <div class="col-md-12" style="margin-top: 10px;">
                                <div class="col-md-2 form">
                                    <label class="control-label">Reference Qty:-</label>
                                </div>

                                <div class="col-md-4 form ">
                                    <div class="col-md-5 input-group" style="margin-left: -15px">
                                        {!! Form::input('number','referance' ,null, ['class' => 'form-control','required','id'=>'referance','min' => 1,'placeholder'=>'Qty'] ) !!}
                                        <span class="input-group-addon" id="sizing-addon1" >{!! $item->u_name !!}</span>
                                        {!! Form::hidden('unit_id', $item->u_id, ['class' => 'form-control',"id"=>"unit_id"]) !!}
                                    </div>
                                </div>
                                @if( isset($recipe_items) && sizeof($recipe_items) > 0 )
                                    <div class="col-md-6 form">
                                        <a class="btn btn-danger pull-right" style="margin-left: 10px;" onclick="warnIngredients(this,'{!! $recipe->id !!}')"><i class="fa fa-trash-o"></i>&nbsp;Delete</a>
                                        <a class="btn btn-primary pull-right" href="/recipeDetails/{{$recipe->id}}/show"><i class="fa fa-calculator"></i>&nbsp;Calculate</a>
                                    </div>
                                @endif
                            </div>
                            <hr class="col-md-12">
                            @if($ing_action == 'add' )
                                <div class="col-md-12 ing-div">
                                    <div class="col-md-3 form">
                                        <select name="ing_name[]" class="form-control ing_sel ing-name" onchange="getUnit(this,0)">
                                            @foreach( $items as $it )
                                                <option value="{!! $it['id'] !!}" data-unit-id="{!! $it['u_id'] !!}" data-unit-name="{!! $it['u_name'] !!}" >{!! $it['name'] !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 form input-group" style="float: left !important">
                                        <input type="number" class="form-control ing-qty" value="" name="ing_qty[]" placeholder="Qty" min="0">
                                        <span class="input-group-addon ing-unit" id="sizing-addon1" ></span>
                                        <input type="hidden" class="form-control ing-unit-id " name="ing_unit_id[]" value="">
                                    </div>

                                </div>
                            @else
                                @if( isset($recipe_items) && sizeof($recipe_items) > 0 )
                                    <?php $i=0;?>

                                    @foreach( $recipe_items as $rec )
                                        <div class="col-md-12 ing-div">
                                            <div class="col-md-3 form">
                                                <select name="ing_name[]" class="form-control ing_sel ing-name" onchange="getUnit(this,0)">
                                                    @foreach( $items as $it )
                                                        <option value="{!! $it['id'] !!}" data-unit-id="{!! $it['u_id'] !!}" data-unit-name="{!! $it['u_name'] !!}" @if( $it['id'] == $rec->ing_item_id){!! 'selected' !!}@endif>{!! $it['name'] !!}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 form input-group" style="float: left !important">
                                                <input type="number" class="form-control ing-qty" value="{!! $rec->qty !!}" name="ing_qty[]" placeholder="Qty" min="0">
                                                <span class="input-group-addon ing-unit" id="sizing-addon1" >{!! $rec->name !!}</span>
                                                <input type="hidden" value="{!! $rec->unit_id !!}" class="form-control ing-unit-id" name="ing_unit_id[]" >
                                            </div>

                                            @if( $i > 0 )
                                                <div class="col-md-3">
                                                    <button onclick='javascript:$(this).closest(".ing-div").remove()' class="btn btn-danger" type="button"><i class="fa fa-times"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <?php $i++;?>
                                    @endforeach

                                @else
                                    <div class="col-md-12 ing-div">
                                        <div class="col-md-3 form">
                                            <select name="ing_name[]" class="form-control ing_sel ing-name" onchange="getUnit(this,0)">
                                                @foreach( $items as $it )
                                                    <option value="{!! $it['id'] !!}" data-unit-id="{!! $it['u_id'] !!}" data-unit-name="{!! $it['u_name'] !!}" >{!! $it['name'] !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 form input-group" style="float: left !important">
                                            <input type="number" class="form-control ing-qty" value="" name="ing_qty[]" placeholder="Qty" min="0">
                                            <span class="input-group-addon ing-unit" id="sizing-addon1" ></span>
                                            <input type="hidden" class="form-control ing-unit-id " name="ing_unit_id[]" value="">
                                        </div>

                                    </div>
                                @endif
                            @endif
                            <div class="col-md-12" style="margin-left: 5px;">
                                <button type="button" class="btn btn-primary" id="addMore" style="margin-left: 10px;"><i class="fa fa-plus"></i></button>
                            </div>

                            <hr class="col-md-12">
                            <div class="col-md-12">
                                <input type="button" id="submit_ingred" class="btn btn-primary pull-right" value="Submit">
                            </div>


                            {!! Form::close() !!}
                        </div>

                        <div id="method-tab" class="tab-pane" style="padding-top: 10px;">

                            <div class="col-md-12" style="margin-top: 10px;">
                                @if($ing_action != 'add')
                                    {!! Form::model($recipe,['route' => array('recipeDetails.update',$recipe->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'recipeForm', 'class' => 'autoValidate']) !!}
                                @else
                                    {!! Form::open(['route' => 'recipeDetails.store', 'method' => 'post', 'class' => 'autoValidate', 'id' => 'recipeForm']) !!}
                                @endif
                                {!! Form::hidden('menu_item_id',$Itemid) !!}
                                <input type="hidden" name="flag" id="flag" value="method">

                                @if($ingred_method != '')
                                    <div class="col-md-8 form">
                                        {!! Form::textarea('ingred_method', $ingred_method, ['class' => 'form-control ingred_method',"id"=>"ingred_method", "rows"=>"7"]) !!}
                                    </div>
                                    <div class="col-md-4 form">
                                        <a class="btn btn-danger pull-right" onclick="warnRecipe(this,'{!! $recipe->id !!}')">Delete</a>
                                    </div>
                                @else
                                    <div class="col-md-8 form">
                                        {!! Form::textarea('ingred_method', null, ['class' => 'form-control ingred_method',"id"=>"ingred_method", "rows"=>"7"]) !!}
                                    </div>
                                @endif
                            </div>
                            <hr class="col-md-12">
                            <div class="col-md-12">
                                <button id="submit_recipe" novalidate="novalidate" class="btn btn-primary  pull-right" style="margin-left: 15px;">Submit</button>
                            </div>
                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!--<div class="row no-gutter">
            <div class="col-md-9">
                <button type="button" class="btn btn-primary" id="addoption<?php /*echo $m;*/?>"  style="margin-bottom:5px;float: right; margin-right: -75px;"> Add Option</button>
            </div>
        </div>-->

<?php $m++; ?>
<input type="hidden" name="countf" id="countf" value="{!! $m !!}">
<input type="hidden" name="coutmenuoption" id="coutmenuoption" value="{!! $m !!}">


{{--<button type="button" class="btn btn-primary" id="addSection" style="float: right;">Add More</button><br>--}}
</div>


@section('page-scripts')

    <script type="text/javascript">

        $('#submit_ingred').click(function (evt) {

            $('.ing-div:last').find('.ing-name').css('border-color','');
            $('.ing-div:last').find('.ing-qty').css('border-color','');
            var ref = $('#referance').val();
            if(ref == '' || ref < 1) {
                $('#referance').css('border-color','red');
                evt.preventDefault();
                return;
            }else{
                $('#referance').css('border-color','');
            }
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
                evt.preventDefault();
                return;
            }else{
                $('#ingredientsForm').submit();
            }

        });

        $('#submit_recipe').click(function (evt) {
            if($('#ingred_method').val() == '') {
                $('#ingred_method').css('border-color', 'red');
                evt.preventDefault();
            }else{
                $('#recipeForm').submit();
            }
        });

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
            $('.ing-div:last').find('.ing-qty').val('');
            $('.ing-div:last').find('.ing-unit').text('');
            $('.ing-div:last').append('<div class="col-md-3"><button onclick=javascript:$(this).closest(".ing-div").remove() class="btn btn-danger" type="button"><i class="fa fa-times"></i></button></div>');

        });

        function customTitle(menu_title){
            if(menu_title=="custom"){
                $("#custom_title0").show();
            }else{
                $("#custom_title0").hide();
            }
        }

        function myFunction(show) {
            var show_div = show.value;
            if(typeof show_div == 'undefined') {
                show_div = show.val();
            }
            //console.log(show_div);
            if(show_div == 0) {
                $(".is_sell_items").show();
            }
            else {
                $(".is_sell_items").hide();
            }
        }

        $(".btn-primary").click(function (evt) {
            if($("input:radio[name='is_sell']:checked").val() == 1){
                if($("#price0").val() == '') {
                    alert("Price is required.");
                    $("#price0").focus()
                    evt.preventDefault();
                }
            }
        });

        function openTab(id,li,rmli1,rmli2) {
            var i;
            var x = document.getElementsByClassName("tab-pane");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            $('.'+rmli1).removeClass('active');
            $('.'+rmli2).removeClass('active');
            document.getElementById(id).style.display = "block";
            $("."+li).addClass('active');
            $("#"+id).addClass('active');
        }


        function changeUnit() {

            $('#moq_unit').text($( "#unit_id0 option:selected" ).text());
            $('#reserved_unit').text($( "#unit_id0 option:selected" ).text());
        }
        /*$("#update_title").change(function() {
         if(this.checked) {
         alert(this.value);
         $("#btn_update_title").show();
         }
         else{
         alert("hi"+this.value);
         $("#btn_update_title").hide();
         }
         });*/

        $(document).ready(function() {

            //alert($("#title0").val());
            $(".is_sell_items").show();
            @if($action != "add")
                @if($item->is_sell=='0')
                    $(".is_sell_items").show();
            @endif
        @endif
        myFunction($("input:radio[name='is_sell']:checked"));

            changeUnit();
            customTitle($("#title0").val());
            function customTitle(menu_title){
                if(menu_title=="custom"){
                    $("#custom_title0").show();
                }else{
                    $("#custom_title0").hide();
                }
            }

            $('#expiry_date').datepicker({
                format: "yyyy/mm/dd"
            });

        });

        var someText1='';
        var n = document.getElementById("countf").value;
        var m = document.getElementById("coutmenuoption").value;

        $('#addItem'+(n-1)).click(function ()
        {
            var o=n++;
            var unit=$("#unit_id0").html();
            //alert($("#unit_id").html());
            someText1 = '<div  class="container" id="control'+o+'">'+
                    '<div class="row no-gutter"> <div class="col-md-7">'+
                    '<button type="button" class="btn btn-danger romove-item-btn" style="margin-top: 10px;margin-bottom:5px;float: right;" onclick="control'+o+'.remove()"><i class="fa fa-times"></i></button><br>'+
                    '</div></div>'+

                    '<div class="row">'+
                    '<div class="col-md-2 form">'+
                    '<label  for="item">{{ trans('Menu_Create.Item') }}</label></div><div class="col-md-5">'+
                    '<div class="col-md-9" style="padding-left: 0px"><input type="text" name="item'+o+'" required="required" id="item'+o+'" placeholder="Enter Item" class="form-control"></div>'+
                    '<div class="col-md-3" style="padding-left: 0px"><input type="text" name="alias'+o+'" id="alias'+o+'" placeholder="Alias" class="form-control"></div>'+

                    '</div></div><div class="clearfix form"></div>' +

                    '<div class="row"><div class="col-md-2 form">'+
                    '<label for="details">{{ trans('Menu_Create.Details') }}</label>'+
                    '</div>'+
                    '<div class="col-md-5 ">'+
                    '<input type="text" id="details'+o+'" name="details'+o+'" placeholder="Enter Details" class="col-md-6 form-control" >'+
                    '</div></div> <div class="clearfix form"></div>'+

                    '<div class="row"><div class="col-md-2 form">'+
                    '<label  for="item">Unit</label></div><div class="col-md-2">' +
                    '<select id="unit_id'+o+'" name="unit_id'+o+'" required class="col-md-10 form-control">'+
                    unit+
                    '</select>'+
                    '</div> <div class="col-md-1 form">'+
                    '<label for="price" style="margin: 0;padding: 8px 0 0;">{{ trans('Menu_Create.Price') }}</label></div>'+
                    '<div class="col-md-2 form">'+
                    '<input type="number" name="price'+o+'" required="required" id="item'+o+'" placeholder="Enter Price" class="form-control">'+
                    '</div></div> <div class="clearfix form"></div>'+



                    '<div class="row"><div class="col-md-2 form">'+
                    '<label for="option">{{ trans('Menu_Create.Food') }}</label>'+
                    '</div>'+
                    '<div class="col-md-10 ">'+
                    '{{'Veg'}}' +'<input type="radio" name="food'+o+'" value={{'veg'}} checked="true"> &nbsp;' +
                    '{{'Nonveg'}}' +'<input type="radio" name="food'+o+'" value={{'nonveg'}}> &nbsp;' +
                    '</div></div>'+

                    '<div class="row"><div class="col-md-2 form">'+
                    '<label for="option">{{ trans('Menu_Create.Active') }}</label>'+
                    '</div>'+
                    '<div class="col-md-10 ">'+
                    '{{'Yes'}}' +'<input type="radio" name="active'+o+'" value={{'0'}} checked="true"> &nbsp;' +
                    '{{'No'}}' +'<input type="radio" name="active'+o+'" value={{'1'}}> &nbsp;' +
                    '</div></div>'+

                    /*'<div class="row">'+
                     '<button type="button" class="btn btn-primary" id="addoption'+(m-1)+'" style="margin-bottom:5px;float: right; margin-right: 200px">Add Option</button><br>'+
                     '</div>'+*/

                    '<input type=hidden name="countf" value='+o+'>'+'<input type=hidden name="countmenuoption" value='+(m-1)+'>'
            var newDiv = $('<div class="row">').append(someText1);
            $(this).before(newDiv);

            $('#addoption'+(m-1)).click(function ()
            {
                var x = m++;
                alert("in : "+x);
                someText = '<div  class="container" id="control' + x + '">' +
                        '<div class="row">' +
                        '<button type="button" class="btn btn-primary" style="float: right; " onclick="control'+x+'.remove()"><i class="fa fa-times"></i></button><br>' +
                        '</div>' +

                        '<div class="row"><div class="col-md-2 form">' +
                        '<label for="option">{{ trans('Menu_Create.Option') }}</label>' +
                        '</div>' +
                        '<div class="col-md-5 ">' +
                        '<input type="text" id="option'+[o] +[x] + '" name="opt'+[o] +[x] + '" placeholder="Enter Options" class="col-md-9" >' +
                        '</div></div>' +

                        '<div class="row">' + '' +
                        '<div class="col-md-2 form">' +
                        '<label  for="price">{{ trans('Menu_Create.Price') }}</label></div>' +
                        '<div class="col-md-5 ">' +
                        '<input type="text" name="opt_price'+[o] +[x] + '"  id="item'+[o] +[x] +'" placeholder="Enter Price" class="col-md-9">' +
                        '</div></div>' +

                        '<input type=text name="coutmenuoption" value='+ x + '>'
                var newDiv = $('<div class="row">').append(someText);
                $(this).before(newDiv);
            });
        });

        var someText = '';

        $('#addoption'+(m-1)).click(function ()
        {
            var o = m++;
            someText = '<div  class="container" id="control' + o + '">' +
                    '<div class="row"><div class="col-md-7">' +
                    '<button type="button" class="btn btn-danger romove-item-btn" style="margin-top: 10px;margin-bottom:5px;float: right;" onclick="control' + o + '.remove()"><i class="fa fa-times"></i></button><br>' +
                    '</div></div>' +

                    '<div class="row"><div class="col-md-2 form">' +
                    '<label for="option">{{ trans('Menu_Create.Option') }}</label>' +
                    '</div>' +
                    '<div class="col-md-5 form">' +
                    '<input type="text" id="option' +[0]+[o] + '" name="opt' +[0]+[o] + '" placeholder="Enter Options" class="form-control" required="required">' +
                    '</div></div>' +


                    '<div class="row">' + '' +
                    '<div class="col-md-2 form">' +
                    '<label  for="price">{{ trans('Menu_Create.Price') }}</label></div>' +
                    '<div class="col-md-5 form">' +
                    '<input type="text" name="opt_price' +[0]+[o] + '"  id="item' +[0]+[o] + '" placeholder="Enter Price" class="form-control" required="required">' +
                    '</div></div>' +

                    '<input type=hidden name="coutmenuoption" value='+ o + '>'
            var newDiv = $('<div class="row">').append(someText);
            $(this).before(newDiv);
        });

        /*$('.outletsingle').click (function (e) {alert($(this).prop("checked"));
         $(this).parent().parent().find(".outlettd").find(".outletmultiple").each(function() {
         $(this).prop("checked",true);
         });
         });*/

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
            var unit_id = $(el).find(':selected').data('unit-id')
            var unit_name = $(el).find(':selected').data('unit-name');

            $(el).closest('.ing-div').find('.ing-unit').text(unit_name);
            $(el).closest('.ing-div').find('.ing-unit-id').text(unit_id);
            $("ing_name"+place).css('border-color','#ccc');
            $("#warning_text").hide();
        }

        function warnIngredients(ele,id) {
            var temp = confirm("Do you want to remove Ingredients?");
            if (temp == true) {
                var route = "/recipeDetails/"+id+"/destroyIngredients"
                //ele.('href', route);
                window.location.replace(route);
            }
        }

        function warnRecipe(ele,id) {
            var temp = confirm("Do you want to remove Recipe?");
            if (temp == true) {
                var route = "/recipeDetails/"+id+"/destroyRecipe"
                //ele.('href', route);
                window.location.replace(route);
            }
        }
    </script>

@stop