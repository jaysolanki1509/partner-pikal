
<div class="mb30"></div>
<?php  $m=0; ?>
<div class="col-md-12">
    @if($action=="add")
        {!! Form::open(['id'=>'inventoryitem_form','route' =>['inventoryitems.store'], 'method' => 'post', 'files'=> true]) !!}
    @else
        {!! Form::model($item,['id'=>'inventoryitem_form','route' => array('inventoryitems.update',$item->id), 'method' => 'patch', 'class' => 'autoValidate']) !!}
    @endif

    <div class="container">

        <div class="row no-gutter form">
            <div class="col-md-2 form">
                <label for="title" class="form">Category</label>
            </div>
            <div class="col-md-5 form">
                {!! Form::select('menu_title_id',$title ,null, array('class'=>'form-control','onchange'=>'customTitle(this.value)','id' => 'title','required')) !!}
                <input type="text" style="display: none;" class="col-md-8 form-control" name="custom_title" id="custom_title" placeholder="Enter Menu Title">
            </div>
            <!--<div class="col-md-5 form"><button type="button" class="btn btn-primary" id="btn_update_title" style="float: left;"> Update</button></div>-->
        </div>


        <div class="col-md-12">
            <hr>
        </div>

        <div class="row no-gutter">
            <div class="col-md-2 form">
                <label for="item"> {{ trans('Menu_Create.Item') }}</label>
            </div>
            <div class="col-md-5">
                <div class="col-md-9" style="padding-left: 0px">
                    {!! Form::text('item',null, array('class'=>'form-control','id' => 'item', 'placeholder'=> 'Enter Item','required')) !!}
                </div>
                <div class="col-md-3" style="padding-left: 0px">
                    {!! Form::text('alias',null, array('class'=>'form-control','id' => 'alias', 'placeholder'=> 'Alias')) !!}
                </div>
            </div>
        </div>
        <div class="clearfix form"></div>

        <div class="row no-gutter">
            <div class="col-md-2 form">
                <label for="price" class="form">Unit</label>
            </div>
            <div class="col-md-2">
                {!! Form::select('unit_id',$units,null, ['onchange'=>'changeUnit()','class' => 'col-md-9 form-control','required', 'id' => 'unit_id']) !!}
            </div>

            <div class="col-md-1 form">
                <label for="price" class="form" style="margin: 0;padding: 8px 0 0;"> {{ trans('Menu_Create.Price') }}</label>
            </div>
            <div class="col-md-2 form">
                {!! Form::input('number','price',null, array('class'=>'col-md-2 form-control','min'=>0,'id' => 'price', 'placeholder'=> 'Enter Price','required')) !!}
            </div>
        </div>


        <div class="row no-gutter">
            <div class="col-md-2 form">
                <label for="details"> {{ trans('Menu_Create.Details') }}</label>
            </div>
            <div class="col-md-5">
                {!! Form::text('details',null, array('class'=>'col-md-6 form-control','id' => 'details', 'placeholder'=> 'Enter Details')) !!}
            </div>
        </div>

        <div class="clearfix form"></div>

        <div class="row no-gutter">
            <div class="col-md-2 form">
                <label for="moq" class="form" style="margin: 0;padding: 8px 0 0;">MOQ</label>
            </div>
            <div class="col-md-5 input-group" style="padding-right: 15px;">
                {!! Form::text('moq',null, ['class' => 'col-md-9 form-control', 'id' => 'moq','style'=>'margin-left:15px;', 'placeholder'=> 'Minimum Order Quantity']) !!}
                <span id="moq_unit" class="input-group-addon" style="padding-left: 30px; padding-right: 50px;"></span>
            </div>
        </div>
        <div class="clearfix form"></div>

        <div class="row no-gutter">
            <div class="col-md-2 form">
                <label for="reserved" class="form" style="margin: 0;padding: 8px 0 0;">Reserved</label>
            </div>
            <div class="col-md-5 input-group" style="padding-right: 15px;">
                {!! Form::text('reserved',null, array('class'=>'col-md-2 form-control','min'=>0,'style'=>'margin-left:15px;','id' => 'reserved', 'placeholder'=> 'Reserved Quantity')) !!}
                <span id="reserved_unit" class="input-group-addon" style="padding-left: 30px; padding-right: 50px;"></span>
            </div>
        </div>
        <div class="clearfix form"></div>

        <div class="row no-gutter">
            <div class="col-md-2 form">
                <label class="control-label">Expiry Date</label>
            </div>
            <div class="col-md-5">
                {!! Form::text('expiry',null, array('class'=>'col-md-2 form-control','id' => 'expiry', 'placeholder'=> 'Expiry Date')) !!}
            </div>
        </div>
        <div class="clearfix form"></div>

        <div class="row no-gutter">
            <div class="col-md-2 form">
                <label for="option"> {{ trans('Menu_Create.Food') }}</label>
            </div>
            <div class="col-md-10 form">
                @if($action=="add")
                    {!! Form::radio('food','veg',true) !!}
                    <label for="option">Veg</label>
                    {!! Form::radio('food','nonveg') !!}
                    <label for="option">NonVeg</label>
                @else
                    @if($item->food=="nonveg")

                        {!! Form::radio('food', 'veg') !!}
                        <label for="option">Veg</label>

                        {!! Form::radio('food','nonveg',true) !!}
                        <label for="option">NonVeg</label>

                    @else
                        {!! Form::radio('food','veg',true) !!}
                        <label for="option">Veg</label>
                        {!! Form::radio('food','nonveg') !!}
                        <label for="option">NonVeg</label>

                    @endif
                @endif
            </div>
        </div>
        <div class="clearfix form"></div>

        @if($action=="add")
            <div class="col-md-7">
                <button type="submit" class="btn btn-primary"  style="float: right;"> Submit</button>
            </div>
        @else
            <div class="col-md-7">
                <button type="submit" class="btn btn-primary"  style="float: right;"> Update </button>
            </div>
        @endif
        {!! Form::close() !!}

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

        function customTitle(menu_title){
            if(menu_title=="custom"){
                $("#custom_title").show();
            }else{
                $("#custom_title").hide();
            }
        }

        function changeUnit() {

            $('#moq_unit').text($( "#unit_id option:selected" ).text());
            $('#reserved_unit').text($( "#unit_id option:selected" ).text());
        }


        $(document).ready(function() {
            //alert($("#title0").val());
            changeUnit($('#unit_id'));
            customTitle($("#menu_title_id").val());

            function customTitle(menu_title){
                if(menu_title=="custom"){
                    $("#custom_title").show();
                }else{
                    $("#custom_title").hide();
                }
            }

            $('#expiry').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date
            });

            $("#inventoryitem_form").validate({
                ignore: [],
                rules: {
                    "title": {
                        required: true
                    },
                    "item":{
                        required: true
                    },
                    "price":{
                        required: true
                    }
                },
                messages: {

                    "title": {
                        required: "Title is required"
                    },
                    "item": {
                        required: "Item is required"
                    },
                    "price": {
                        required: "Price is required"
                    }
                }
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


    </script>

@stop