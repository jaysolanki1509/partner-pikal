<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');
$from_session = Session::get('from_session');
$to_session = Session::get('to_session');

?>
@extends('partials.default')
@section('pageHeader-left')
    Item Sales Report
@stop

@section('pageHeader-right')
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-header block-header clearfix j-forms">
                @if( !isset($sess_outlet_id) || $sess_outlet_id == '')
                    <div class="col-md-4 form-group">
                        {!! Form::select('outlet_id',$outlets,null,array('id' => 'outlet_id','class'=>'form-control' )) !!}
                    </div>
                @endif
                <form id="item_sale_form" method="post" action="/itemreport">
                    <input type="hidden" name="flag" value="excel" >
                    <div class="form-group col-md-5">

                        <div class="input-daterange input-group">
                            {!! Form::text('from_date', isset($from_session)?$from_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"from_date","readonly"=>"readonly"]) !!}
                            <span class="input-group-addon">to</span>
                            {!! Form::text('to_date', isset($to_session)?$to_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>

                    </div>
                    <div class="col-md-4 form-group">
                        {!! Form::select('category',$menu_titles,null,array('id' => 'category','class'=>'form-control','onchange'=>'categorySelect(this.value)')) !!}
                    </div>

                    <div class="@if( !isset($sess_outlet_id) || $sess_outlet_id == '')col-md-5 @else col-md-9 @endif form-group">
                        {!! Form::select('item_id[]',$items,null,array('id' => 'item_id','class'=>'form-control','multiple')) !!}
                    </div>
                </form>
                <div class="col-md-2 form-group pull-left">
                    <button type="button" onclick="getList('report')" name="show" class="btn btn-success primary-btn pull-left" id="showbtn">Show</button>
                </div>
            </div>
            <div class="widget-container">
                <div class="widget-content">
                    <div class="report_table" style="overflow-x: auto;">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@stop

@section('page-scripts')

<script>

    $(document).ready(function() {

        $('#outlet_id').select2({
            placeholder: 'Select Outlet'
        });
        $('#category').select2({
            placeholder: 'All Category'
        });
        $('#item_id').select2({
            placeholder: 'All Item'
        });
    });

    function categorySelect(title) {

        var title_id = title;
        var finalString='';

        $.ajax({
            url: '/MenuItemList',
            data:'title_id='+title_id,
            success: function(data) {
                console.log(data);
                $("#item_id").append();
                var select = $('#item_id');
                select.empty();

                $.each(data, function(key,value) {
                    select.append($("<option></option>")
                            .attr("value", key).text(value));
                });
                //select.multiselect('destroy');
                //select.multiselect();
            }
        });
    }


    function getList(flag) {

        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var outlet = $('#outlet_id').val();
        var values = $("#item_id").val();
        var cat_id = $("#category").val();

        if(check30daysDiff(from_date,to_date)){
            return;
        }

        if ( flag == 'report' ) {

            $('.report_table').empty();
            $('#export_excel').addClass('hide');

            processBtn('showbtn','add','Showing...');

        } else {
            $( "#item_sale_form" ).submit();
            return;
        }

        //console.log(from_date+'==='+to_date)
        if (from_date <= to_date){
            $.ajax({
                url: '/itemreport',
                type: "post",
                data: {flag:flag,item_id:values,from_date: from_date,to_date:to_date,outlet_id:outlet,cat_id:cat_id},
                success: function (data) {
                    //alert(data);

                    $('.report_table').html(data);
                    $('#export_excel').removeClass('hide');

                    processBtn('showbtn','remove','Show');


                }
            });

        } else {

            alert('To date must be greater than from date.');
            return false;

        }

    }


</script>

@stop

