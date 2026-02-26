@extends('app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" style="border:none; box-shadow:none;">
                    <div class="panel-heading res-font"> &nbsp;<a href="/recipeDetails/" style="float: right;">Back</a> </div>
                        <div class="clearfix"></div>
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
                        <?php  $m=0; ?>
                        <div class="row well">
                            <form class="form-horizontal" role="form" method="POST" id="recipeDetails" novalidate="novalidate" action="{{ url('/recipeDetails') }}" files="true" enctype="multipart/form-data">
                                <div class="col-md-12 form padding-left0 padding-right0 custom-div">
                                    <div class="col-md-12 form">
                                        <label class="control-label">Item Name:-</label>
                                    </div>

                                    <div class="col-md-12 form res-input input-padding res-font padding-right0">
                                        {!! Form::select('menu_item', $items, null, ['onchange'=>"get_unit(this.value,'main')",'class' => 'form-control','required', 'id' => 'menu_item']) !!}
                                    </div>
                                </div>

                                <div class="col-md-12 from padding-left0 padding-right0 custom-div">
                                    <div class="col-md-6 form" style="width:40%; float: left;">
                                        <label class="control-label">Qty:-</label>
                                    </div>
                                    <div class="col-md-6 form" style="width:40%; float: left;">
                                        <label class="control-label">Unit:-</label>
                                    </div>
                                    <div class="col-md-6 form res-input input-padding res-font padding-left0" style="width:40%; float: left;">
                                        {!! Form::input('number','referance' ,1, ['class' => 'form-control','required','id'=>'referance','min' => 1,'placeholder'=>'Quantity'] ) !!}
                                    </div>

                                    <div class="col-md-6 form res-input input-padding res-font padding-right0" style="width:40%; float: left;">
                                        {!! Form::text('unit', null, ['class' => 'form-control',"id"=>"unit","readonly"=>"readonly"]) !!}
                                    </div>
                                </div>

                                <div class="col-md-12 padding-left0 padding-right0 custom-div">
                                    <div class="col-md-3 form padding-left0">
                                        <label class="control-label res-font"><strong>Ingredients:-</strong></label>
                                        <label id="warning_text" style="color: #ff0000; display:none; " class="control-label">All fields are required.</label>
                                    </div>
                                </div>
                                <div id="id1" class="col-md-12 form padding-left0 padding-right0 custom-div">
                                    <div class="col-md-12 form">
                                        <label class="control-label">Name:-</label>
                                    </div>
                                    <div class="col-md-12 form padding-left0 padding-right0 custom-div">
                                        <div class="col-md-12 form res-input input-padding res-font padding-left0" style="width: 100%; float: left;">
                                                {!! Form::select('ing_name'.$m, $items, null, ['onchange'=>'get_unit(this.value,'.$m.')','placeholder' => 'Name' ,'class' => 'form-control ing_sel', 'id' => 'ing_name'.$m]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-12 from padding-left0 padding-right0 custom-div">
                                        <div class="col-md-6 form" style="width:35%; float: left;">
                                            <label class="control-label">Qty:-</label>
                                        </div>
                                        <div class="col-md-6 form" style="width:35%; float: left;">
                                            <label class="control-label">Unit:-</label>
                                        </div>
                                        <div class="col-md-3 form res-input padding-left0 input-padding res-font" style="width:35%; float: left;">
                                            <input type="number" class="form-control res-font" value="1" id="ing_qty<?php echo $m?>" name="ing_qty<?php echo $m?>" placeholder="Quantity" min="1">
                                        </div>

                                        <div class="col-md-3 form res-input input-padding res-font padding-right0" style="width:35%; float: left;" id="unit_div" >
                                            <input type="hidden" class="form-control" id="ing_unit<?php echo $m?>" name="ing_unit<?php echo $m?>" >
                                            <input type="text" class="form-control" value="" disabled id="unit<?php echo $m?>" name="unit<?php echo $m ?>"  >
                                        </div>
                                    </div>
                                </div>



                                <div class="col-md-12 padding-left0 padding-right0 custom-div">
                                    <input type=hidden name="count" id="count" value="<?php echo $m?>">

                                        <button type="button" class="btn btn-primary res-font" id="addMore<?php echo $m;?>" style="float: right;">Add More</button>
                                        <button type="submit" id="Submit" novalidate="novalidate" class="btn btn-primary col-md-2 res-font" style="float: left;">Submit</button>

                                </div>
                            </form>
                        </div>
                            <?php $m++; ?>

                            {!!Form::hidden('countf',$m,array('id'=>'countf'))!!}
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>

var someText='';
var n = document.getElementById("countf").value;

$('#addMore'+(n-1)).click(function()
{
    var check=0;
    $(".ing_sel").each(function() {
        if($(this).val()==""){
            alert('please select from selectbox to add more.');
            $(this).css('border-color','red');
            check=1;
            $("#warning_text").show();
        }
        else{
            $(this).css('border-color','#ccc');
            $("#warning_text").hide();
        }
    });
    if(check == 1){
        return false;
    }
    n = document.getElementById("countf").value;
    var o=++n;
    $("#id1").clone().attr('id', 'id'+ o).insertAfter("#id"+(o-1));
    jQuery("#id"+(o)+" select").attr("id","ing_name"+(o-1));
    jQuery("#id"+(o)+" select").attr("name","ing_name"+(o-1));
    jQuery("#id"+(o)+" select").attr("onChange","get_unit(this.value,"+(o-1)+")");
    jQuery("#id"+(o)+" select").attr("selected","");
    jQuery("#id"+(o)+" input[type='number']").attr("id","ing_qty"+(o-1));
    jQuery("#id"+(o)+" input[type='number']").attr("name","ing_qty"+(o-1));
    jQuery("#ing_qty"+(o-1)).val(1);
    jQuery("#id"+(o)+" input[type='hidden']").attr("id","ing_unit"+(o-1));
    jQuery("#id"+(o)+" input[type='hidden']").attr("name","ing_unit"+(o-1));
    jQuery("#id"+(o)+" input[type='hidden']").attr("value"," ");
    jQuery("#id"+(o)+" input[type='text']").attr("id","unit"+(o-1));
    jQuery("#id"+(o)+" input[type='text']").attr("value"," ");
    jQuery("#unit"+(o-1)).val("");
    jQuery("#id"+(o)+" div[id='unit_div']").attr("id","unit_div"+(o-1));
    jQuery("<div class='col-md-1 pull-right'><button type='button' class='btn btn-primary pull-right'  onclick=$('#id"+o+"').children().remove() ><i class='fa fa-times'></i></button><br></div>").insertAfter("#unit_div"+(o-1));

    $('#countf').val(o);
    $('#count').val(o);


    var newDiv = $('<div class="row">').append(someText);
    $(this).before(newDiv);

});

$(document).ready(function() {
    get_unit($('#menu_item').val(),'main');
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
                required: "*Reference Quantity is required"
            },
            "unit_id": {
                required: "*Unit is required"
            },
            "ing_name0": {
                required: "*Ingredients Name is required"
            },
            "ing_qty0": {
                required: "*Ingredients Quantity is required"
            },
            "ing_unit0": {
                required: "*Ingredients Unit is required"
            }
        }
    });

    $('#Submit').click(function(){
        $('.nming').validate();
        check_validate();
    });

//            $('.nming').validate();

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
                $('#menu_item').html(data.list)
                $('#ing_name0').html(data.list)
            }
        });
    }
}

function get_unit(item,place){
    var item_id = item;
    if(item_id!='' && item_id!="select") {
        $.ajax({
            url: '/ajax/ajaxGetItemUnit',
            data:'item_id='+item_id+'&flag='+"menus",
            success: function(data) {
                if(place=="main"){
                    $('#unit').val(data[0].unit_name);
                    $('#unit_id').val(data[0].unit_id);
                }else{
                    $('#unit'+place).val(data[0].unit_name);
                    $('#ing_unit'+place).val(data[0].unit_id);
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
        $("#warning_text").hide();
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