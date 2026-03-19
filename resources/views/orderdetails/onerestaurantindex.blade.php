<?php use App\OrderItem;?>
@extends('partials.default')

@section('pageHeader-left')

    {{ trans('One RestaurantIndex.Order Details') }}



@stop
@section('pageHeader-right')
    <?php if(count($orders)>0) { ?>


<style>
    .received{background: #ef9a9a;margin:15px;}
    .preparing{background: #9fa8da;margin:15px;}
    .prepared{background: #a5d6a7;margin:15px;}
    .delievered{background: #ce93d8;margin:15px;}
    .ajax-loader {
        position:absolute;
        top:0; bottom:0;
        left:0; right:0;
        margin:auto;
        z-index:1000;
        opacity: .5;
        cursor: wait;
        display:none;
    }

</style>

@foreach($orders as $allorder)
    <?php if($allorder->status=="received") {

        $class="received";
    }
    else if($allorder->status=="preparing"){
        $class="preparing";
    }
    else if($allorder->status=="prepared"){
        $class="prepared";
    }
    else if($allorder->status=="delievered"){
        $class="delievered";
    }
    $oid=$allorder->suborder_id;
    ?>

    <div class="bs-example col-md-4" style="min-height:240px;max-height:240px;overflow: scroll;word-wrap:break-word;border-style:solid;border-width: 1px;padding: 0 0 0 0;margin-left:5px;margin-right:5px;margin-bottom:10px;border-color: #ddd;">
        <img src="loader.gif" class="ajax-loader" id="load{{$oid}}">
        <div class="panel-group " id="accordion{{$oid}}" style="margin:0 0 0 0 !important;position: relative;">
        <input type="hidden" id="rest_id" value={{$allorder->outlet_id}}/>

            <div class="panel panel-default" style="border-bottom-style: none;">
                <div class="panel-heading" >
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion{{$oid}}" href="#collapseOne{{$oid}}">{{$oid}}</a>
                    </h4>
                </div>

                <div id="collapseOne{{$oid}}" class="panel-collapse collapse ">
                    <div class="panel-body">

                        <table id="orderdet" class="col-xs-2 col-md-4 getorderdetails ">

                            <tr>
                                <td style="padding:10px;"> {{ trans('One RestaurantIndex.UserName') }}  </td>
                                <td>{{$allorder->name}}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px;">{{ trans('One RestaurantIndex.Address') }}  </td>
                                <td><address>{{$allorder->address}}</address></td>
                            </tr>
                            <tr>
                                <td style="padding:10px;">{{ trans('One RestaurantIndex.Mobile') }}  </td>
                                <td>{{$allorder->mobile_number}}</td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="panel-group" id="accordion{{$oid}}new" style="margin:0 0 0 0 !important;">
            <div class="panel panel-default" style="border-bottom-style: none;">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion{{$oid}}new" href="#collapseOne{{$oid}}new" class="coldown">{{ trans('One RestaurantIndex.ITEM') }}</a>
                    </h4>
                </div>

                <div id="collapseOne{{$oid}}new" class="panel-collapse collapse in" >
                    <div class="panel-body" style="padding: 0 0 0 0;" >
                        <table id="orderdet" class="col-xs-2 col-md-4 getorderdetails {{$allorder->status}}" style="margin: 0 0 0 0;width:100%" >

                            <?php $item=OrderItem::where('order_id','=',$allorder->order_id)->get();
                            $totalprice=0;?>

                            <tr>
                                <th style="padding:10px;">{{ trans('One RestaurantIndex.ITEM') }}</th>
                                <th style="padding:10px;text-align:center;">{{ trans('One RestaurantIndex.Quantity') }}</th>
                                <th style="padding:10px;text-align:center;">{{mb_convert_encoding('&#8377;', 'UTF-8', 'HTML-ENTITIES')}}</th>
                            </tr>

                            @foreach($item as $it)

                                <?php $getmenu=\App\Menu::where('id',$it->item_id)->first();

                                $totalprice+=$it->item_quantity*$getmenu['price'];?>

                                <tr>
                                    <td style="padding:10px;">{{$getmenu['item']}}</td>
                                    <td style="padding:10px;text-align: center;">{{$it->item_quantity}}</td>
                                    <td style="padding:10px;text-align: center;">{{$it->item_quantity*$getmenu['price']}}</td>
                                </tr>

                            @endforeach
                            <tr>
                                <td style="padding:10px;"><b>{{ trans('One RestaurantIndex.Total Price:') }}</b></td><td>&nbsp;</td><td style="padding:10px;text-align: center;">{{$totalprice}}</td></tr>
                            <input type="hidden" id="orderstatusget" value="{{$allorder->status}}">
                            <tr>
                                <td style="padding:10px;">
                                    <?php if($allorder->status!="delievered"){ ?><button class="btn btn-primary" onclick='changestatus("{{$allorder->status}}","{{$oid}}");'>{{ trans('OrderAdd.Next') }}</button><?php } ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endforeach
<script>
    function getallstatus(){

        var re=document.getElementById('rest_id').value;
        $('#loader').css('display','block');
        if(re!='' && re!="select") {
            $.ajax({
                url: 'ajax/getstatus',
                data:"rest_id="+document.getElementById('rest_id').value,
                success: function(data) {

                    getdataofall();
                    $('.getstatus').remove();

                    $('#navigation').append('<li id="all" class="getstatus active"><a onclick="getvalue();" href="#all" value="all" class="ui-tabs-anchor">all</a></li>');
                    $('.tab-content').append( '<div id="all-content" class="tab-pane getorderdetails active"><div class="mb30"></div><div class="activity-list vehicle-list"></div>');

                    for(var i=0;i<data.length;i++){


                        $('#navigation').append('<li id="'+data[i].status+'" class="getstatus"><a onclick="getvalue();"  order-number="'+data[i].order+'" href="#'+data[i].status+'" value='+data[i].status+' class="ui-tabs-anchor">'+data[i].status+'</a></li>');
                        $('.tab-content').append( '<div id="'+data[i].status+'-content" class="tab-pane getorderdetails"><div class="mb30"></div><div class="activity-list vehicle-list"></div>');

                    }

                }
            });
        }
        else {
            $('.getstatus').remove();$('.tab-pane').remove();
            $('#loader').css('display','none');
        }
    }
    function getsearchresult(){

        var dt=$('#datepickernew').val();
        if(($('#searchorderid').val()==null || $('#searchorderid').val()=="") && (dt==null || dt=="")){
            alert("Please Add Either Order Id OR Select Any Date");
        } else {
            $('#loadersearch').css('display','block');
            $.ajax({
                url: 'ajax/searchorder',
                data:"order_id="+$("#searchorderid").val()+"&phone_number="+$("#searchphnnumber").val()+"&name="+$("#searchname").val()+"&status="+$('.orderst').text()+"&address="+$("#searchaddr").val()+"&table="+$("#searchtblnumber").val()+"&ordertype="+$('.ordertp').text()+"&dt="+dt,
                success: function(data) {

                    $('#mydata').html(data);
                    $('#loadersearch').css('display','none');

                }
            });
        }
    }
    function getvalue(){
        var selector = '.nav li';

        $(selector).on('click', function(){
            var id = $(this).attr('id');

            $(".tab-pane").removeClass('active');
            $(selector).removeClass('active');
            $(this).addClass('active');


            $("#"+id+"-content").addClass('active');

        });

    }


    $(document).delegate('.nav li a','click',function () {

        $('#loader').css('display','block');
        var dt=$('#datepicker').val();
        $.ajax({
            url: 'ajax/getallorderdetails',
            data: "status=" + $(this).html()+ "&rest_id=" + document.getElementById('rest_id').value+"&dt="+dt,
            success: function (data) {

                $('.tab-pane').html(data);

                $('.tab-pane').each(function(index,data){
                    if(!$(this).hasClass( "active" )){
                        $(this).empty();
                    }

                });
                $('#loader').css('display','none');
                collapsedn();
            }

        });

    });

    function getdataofall(){
        var dt=$('#datepicker').val();
        var re=document.getElementById('rest_id').value;
        // $('.tab-pane').remove();
        $('#loader').css('display','block');
        $('.tab-content').empty();
        if(re!='' && re!="select") {

            $.ajax({
                url: 'ajax/getallorderdetails',
                data: "status=all" + "&rest_id=" + document.getElementById('rest_id').value+"&dt="+dt,
                success: function (data) {

                    $('.tab-pane').html(data);
                    $('#loader').css('display','none');
                    collapsedn();

                }

            });
        }
    }
    function refreshorder(b){

        var re=document.getElementById('rest_id').value;
        var dt=$('#datepicker').val();
        var st=$('#navigation').find('.active').attr('id');
        if(re!='' && re!="select") {

            $.ajax({
                url: 'ajax/getallorderdetails',
                data: "status="+st + "&rest_id=" + document.getElementById('rest_id').value+"&dt="+dt,
                success: function (data) {

                    $('.tab-pane').html(data);
                    $('#load'+b).css('display','none');
                    collapsedn();

                }

            });
        }
    }

    function searchorder(){
        var val=$('#searchorder').val();
        $.ajax({
            url: 'ajax/searchorder',
            data:"searchval="+val,
            success: function(data){
                $("#status").html(data);collapsedn();
            }
        });

    }


    function changestatus(a,b){

        //var currentstatus=document.getElementById('orderstatusget').value;alert(a);alert(document.getElementById('rest_id').value);
        $('#load'+b).css('display','block');
        $.ajax({
            url: 'ajax/nextstatus',
            data:"currentstatus="+a+"&oid="+b+"&outlet_id="+document.getElementById('rest_id').value,
            success: function(data) {
                refreshorder(b);
            }
        });

    }


    $(document).ready(function(){

        $("#showorder li a").click(function(){
            var selText = $(this).text();
            $(this).parents('.btn-group').find('.dropdown-toggle').html(selText+'<span class="caret"></span>');
            if(($('#searchorderid').val()==null || $('#searchorderid').val()=="") && ($('#datepickernew').val()==null || $('#datepickernew').val()=="")){
                alert("Please Add Either Order Id OR Select Any Date");

                $(this).parents('.btn-group').find('.dropdown-toggle').html('All<span class="caret"></span>');
            } else {
                getsearchresult();

            }

        });

        $("#showordertype li a").click(function(){
            var selText = $(this).text();
            if(selText=="Take Away"){
                $('.orderdisplay').css('display','block');
                $('.ortable').css('display','none');
                $('.table').css('display','none');
            }
            else if(selText=="Home Deleivery"){
                $('.orderdisplay').css('display','block');
                $('.addr').css('display','none');
                $('.ortable').css('display','none');
                $('.table').css('display','none');
            }
            else if(selText=="Dine In"){
                $('.orderdisplay').css('display','block');
                $('.addr').css('display','none');

            }
            else if(selText=="All"){
                $('.orderdisplay').css('display','none');


            }
            $(this).parents('.btn-group').find('.dropdown-toggle').html(selText+'<span class="caret"></span>');
        });
    });
    function collapsedn(){
        $('.coldown').click(function(){
            if($(window).scrollTop() + $(window).height() == $(document).height()) {
                $('html, body').animate({
                    scrollTop: $(document).height()
                }, 'slow');
            }

        });
    }

    $(document).ready(function(){

        $('#datepicker').datepicker({
            todayBtn: "linked",
            language: "en",
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'

        });

        $("#datepicker").datepicker('setDate',new Date);
        $("#datepicker").datepicker().on('changeDate',function(ev){
            getallstatus();
        });
        $('.clearsearchdata').click(function(){
            clearsearchdata();
        });
        $('#datepickernew').datepicker({
            todayBtn: "linked",
            language: "en",
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'

        });
        //$("#datepickernew").datepicker('setDate',new Date);
        $("#datepickernew").datepicker().on('changeDate',function(ev){

        });
        $('.cleardate').click(function(){
            $('#datepickernew').val('').datepicker('update');
        });
        $('.clearsearch').click(function(){
            clearsearchdata();

        });


    });
    function clearsearchdata(){
        $('#mydata').empty();
        $('#datepickernew').val('').datepicker('update');
        $('#searchorderid').val('');
        $('#showorder .orderst').html('All<span class="caret"></span>');
        $('#showordertype .ordertp').html('All<span class="caret"></span>');
        $('#searchname').val('');
        $('#searchphnnumber').val('');
        $('#searchaddr').val('');
        $('#searchtblnumber').val('');
        $('.orderdisplay').css('display','none');

    }

</script>
<?php } else { ?>
<div class="alert alert-success fade in">

    <a href="#" class="close" data-dismiss="alert">&times;</a>

    <strong>Oopps!</strong> No Records found........

</div>
<?php } ?>

@stop