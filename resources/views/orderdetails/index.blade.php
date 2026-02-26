@extends('partials.default')

@section('pageHeader-left')

    {{ trans('OrderIndex.Order Details') }}



@stop
@section('pageHeader-right')
<style>
    .orderdisplay{display:none;}
    .modal-body{
        min-height: 250px;
        max-height:500px;
        overflow-y: scroll;

    }
    .modal-lg{
        width:1000px;
    }
    .comcolor{height: 15px;width: 15px;display: inline-block;margin-top: 10px;margin-left: 2px;margin-right: 2px;}
    .colorA{background: #ef9a9a;}
    .colorB{background: #9fa8da;}
    .colorC{background: #a5d6a7;}
    .colorD{background: #ce93d8;}
    .clearsearchdata{margin-top: 10px;}
    .rest{margin-left: -15px;margin-top: 5px; }
    .orderdate{margin-top: 5px; }
    .searchdate{margin-left: 1px;}

    #navigation{border-bottom-style: none;}

    .ajax-loadernew{
        position:absolute;
        top:100px; bottom:0;
        left:-150px; right:0;
        margin:auto;
        z-index:1000;
        opacity: .5;
        cursor: wait;
        display:none;
    }
    @media (max-width: 767px) {
        .nav-tabs > li {
            float:none;
            border:1px solid #dddddd;
        }
        .nav-tabs > li.active > a{
            border:none;
        }
        .nav > li > a:hover, .nav > li > a:focus,
        .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus
        {
            background:none;
            border:none;
        }
    }

</style>

<div >
    <span class="comcolor colorA"></span><span>{{ trans('OrderIndex.Received') }}</span>
    <span class="comcolor colorB"></span><span>{{ trans('OrderIndex.Preparing') }}</span>
    <span class="comcolor colorC"></span><span>{{ trans('OrderIndex.Prepared') }}</span>
    <span class="comcolor colorD"></span><span>{{ trans('OrderIndex.Delivered') }}</span>
</div>
<button type="button"  class="btn btn-primary btn-lg clearsearchdata" data-toggle="modal" data-target="#myModal">
    {{ trans('OrderIndex.Search Order') }}
</button>



@stop

@section('content')

@if(Session::has('success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
    {{ Session::get('success') }}
</div>
@endif
@if($totalOutletcount>1)
<div class="col-md-6">
    <label class="rest">{{ trans('OrderIndex.Outlet') }}</label>
</div>

<div class="col-md-4">
    <select id="rest_id" class="form-control" name="Outlet_name" onchange="getallstatus();">
        <option value="select" selected>{{ trans('OrderIndex.Select') }}</option>
        @foreach($Outlet as $rest)
        <option value="{{$rest->id or ''}}">{{$rest->name or ''}}</option>
        @endforeach
    </select>
</div>
@else
@foreach($Outlet as $rest)
    <div class="col-md-6">
        <input type="hidden" value="{{$rest->id or ''}}" id="rest_id"/>
        <label>{{$rest->name or ''}}</label>
    </div>
@endforeach
@endif

<br><br>

    <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('OrderIndex.Order Search') }}</h4>
                    <div>
                        <span class="comcolor colorA"></span><span>{{ trans('OrderIndex.Received') }}</span>
                        <span class="comcolor colorB"></span><span>{{ trans('OrderIndex.Preparing') }}</span>
                        <span class="comcolor colorC"></span><span>{{ trans('OrderIndex.Prepared') }}</span>
                        <span class="comcolor colorD"></span><span>{{ trans('OrderIndex.Delivered') }}</span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        @include('orderdetails.orderadd')

                        <div id="mydata"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('OrderIndex.Close') }}</button>
                    <button type="button" class="btn btn-primary clearsearch" >{{ trans('OrderIndex.Clear Filter') }}</button>
                    <button type="button" class="btn btn-primary" onclick="getsearchresult()">{{ trans('OrderIndex.Search') }}</button>
                </div>
            </div>
        </div>
    </div>

<div id="status" class="col-sm-4 col-md-12 myclass">

    <ul id="navigation" class="nav nav-tabs nav-line responsive" >

    </ul>
    <div class="tab-content nopadding noborder">
    </div>
    <div><img src="loader.gif" class="ajax-loadernew" id="loader"></div>
</div>

<script>

    @if($totalOutletcount==1)
    $(document).ready(function() {

        var re = document.getElementById('rest_id').value;

        $('#loader').css('display', 'block');
        if (re != '' && re != "select") {
            $.ajax({
                url: 'ajax/getstatus',
                data: "rest_id=" + document.getElementById('rest_id').value,
                success: function (data) {

                    getOutletorderdata(document.getElementById('rest_id').value,'all');

                    $('.getstatus').remove();$('.tab-pane').remove();

                    $('#navigation').append('<li id="all" class="getstatus active"><a onclick="getvalue();" href="#all" value="all" class="ui-tabs-anchor">all</a></li>');
                    $('.tab-content').append('<div id="all-content" class="tab-pane getorderdetails active"><div class="mb30"></div><div class="activity-list vehicle-list"></div>');

                    for (var i = 0; i < data.length; i++) {


                        $('#navigation').append('<li id="' + data[i].status + '" class="getstatus"><a onclick="getvalue();"  order-number="' + data[i].order + '" href="#' + data[i].status + '" value=' + data[i].status + ' class="ui-tabs-anchor">' + data[i].status + '</a></li>');
                        $('.tab-content').append('<div id="' + data[i].status + '-content" class="tab-pane getorderdetails"><div class="mb30"></div><div class="activity-list vehicle-list"></div>');

                    }

                }
            });

        }
        else {
            $('.getstatus').remove();
            $('.tab-pane').remove();
            $('#loader').css('display', 'none');
        }
    });
    @endif
    function getallstatus(){

        var re=document.getElementById('rest_id').value;
        $('#loader').css('display','block');
        if(re!='' && re!="select") {
            $.ajax({
                url: 'ajax/getstatus',
                data:"rest_id="+document.getElementById('rest_id').value,
                success: function(data) {
                  $('.getstatus').remove();$('.tab-pane').remove();
                    getOutletorderdata(document.getElementById('rest_id').value,'all');

                    $('#navigation').append('<li id="all" class="getstatus active"><a onclick="getvalue();" href="#all" value="all" class="ui-tabs-anchor">all</a></li>');
                    $('.tab-content').append( '<div id="all-content" class="tab-pane getorderdetails active"><div class="mb30"></div><div class="activity-list vehicle-list"></div>');

                  for(var i=0;i<data.length;i++){


                        $('#navigation').append('<li data-status="'+data[i].status+'" id="'+data[i].status+'" class="getstatus"><a onclick="getvalue();"  order-number="'+data[i].order+'" href="#'+data[i].status+'" value='+data[i].status+' class="ui-tabs-anchor">'+data[i].status+'</a></li>');
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

            $('#loader').css('display','block');

          //  var dt=$('#datepicker').val();



            $("#"+id+"-content").addClass('active');

        });
        setInterval(function(){getOutletorderdata(document.getElementById('rest_id').value,$('li.active').attr('id'))},10000);
    }


    function getOutletorderdata(b,c){

        var myDate = (new Date());
        var lastTime = moment().subtract(20, 'seconds').toDate();

        $.ajax({
            url: 'ajax/getallorderdetails',
            data: "status=" + c + "&rest_id=" +b+"&lasttime="+lastTime,
            success: function (data) {

                $('.tab-pane').html(data);

                $('.tab-pane').each(function (index, data) {
                    if (!$(this).hasClass("active")) {
                        $(this).empty();
                    }

                });
                $('#loader').css('display', 'none');
                collapsedn();
            }

        });
        myDate = (new Date());
        lastTime = myDate.getHours() + ':' + myDate.getMinutes() + ':'
                + myDate.setSeconds(myDate.getSeconds()-10000);
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
@stop