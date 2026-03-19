<?php

    use Illuminate\Support\Facades\Session;

    $sess_outlet_id = Session::get('outlet_session');
    $from_session = Session::get('from_session');
    $to_session = Session::get('to_session');

?>
@extends('partials.default')

@section('page-styles')

    <style>
        #item_id_ms{
            height: 34px;
        }
    </style>

@stop

@section('pageHeader-left')
    Adjust Stock Against Sale
@stop

@section('pageHeader-right')
    <a href="/stocks" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')
    @if( isset($outlets) && sizeof($outlets) > 0 )
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-container">
                    <div class="widget-content">

                        <form id="manual_form" class='j-forms'>
                            @if( !isset($sess_outlet_id) || $sess_outlet_id == '')
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8" id="outlet_div">
                                            {!! Form::label('','', array('class' => 'col-md-12 control-label')) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('outlet_id_id',$outlets,null,array('id'=>'outlet_id','class'=>'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6" id="location_div">
                                        {!! Form::label('','', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            {!! Form::select('location_id',$locations,null,array('id'=>'location_id','class'=>'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="category_div">
                                        {!! Form::label('','', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            {!! Form::select('category',$category,null,array('id' => 'category' ,'class'=>'form-control','onchange'=>'categorySelect(this.value)')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! Form::label('','', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            {!! Form::select('item_id[]',$items,null,array('id' => 'item_id','multiple' => 'multiple','class'=>"form-control select2-multiple" )) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                    <div class="col-md-12" id="date_div">
                                        {!! Form::label('','', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="input-daterange input-group">
                                            {!! Form::text('from',\Carbon\Carbon::now()->format("Y-m-d"),array('id'=>'from','class'=>'form-control','readonly','placeholder'=>'From')) !!}
                                            <span class="input-group-addon">to</span>
                                            {!! Form::text('to',\Carbon\Carbon::now()->format("Y-m-d"),array('id'=>'to','class'=>'form-control','readonly','placeholder'=>'To')) !!}
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-footer">
                                <div class="form-group pull-right">
                                    <button class="btn btn-success primary-btn" onclick="showStock('fetch',event)" id="show_stock">Show Stock</button>
                                </div>
                            </div>

                        </form>

                        <div class="widget-container">
                            <div class="widget-content">
                                <div class="dataTable_wrapper" id="data-list" style="overflow-x: auto;">
                                    <div id="stock-list">
                                       No records found.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <label>No Outlets Mapped with you.</label>
    @endif
@stop

@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>

    <script type="text/javascript">

        $(document).ready(function(){
            $('#outlet_id').select2({
                placeholder: 'Select Outlet'
            });
            $('#location_id').select2({
                placeholder: 'Select Location'
            });
            $('#category').select2({
                placeholder: 'Select Category'
            });
            $('#item_id').select2({
                placeholder: 'Select Multiple Items'
            });

            $("#manual_form").validate({
                rules: {
                    "outlet_id": {
                        required: true
                    },
                    "from": {
                        required: true
                    },
                    "to": {
                        required: true
                    },
                    "location_id": {
                        required: true
                    }
                },
                messages: {

                    "outlet_id": {
                        required: "Outlet is required"
                    },
                    "from": {
                        required: "From Date is required"
                    },
                    "to": {
                        required: "To Date is required"
                    },
                    "location_id": {
                        required: "Location is required"
                    }
                }
            })
        });

        function showStock(flag,event) {

            event.preventDefault();

            if ( $('#manual_form').validate ) {

                var from = $('#from').val();
                var to = $('#to').val();
                var ol_id = $('#outlet_id').val();
                var loc_id = $('#location_id').val();
                var cat_id = $('#category').val();
                var item_id = $('#item_id').val();


                if ( flag == 'fetch' ) {
                    $('#show_stock').attr('disabled',true);
                    $('#show_stock').text('Loading...');
                    $('#data_view').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');
                } else if ( flag == 'revoke' ) {
                    $('#process_btn').attr('disabled',true);
                    $('#revoke_btn').attr('disabled',true);
                    $('#revoke_btn').text('Revoking...');
                } else {
                    $('#revoke_btn').attr('disabled',true);
                    $('#process_btn').attr('disabled',true);
                    $('#process_btn').text('Processing...');
                }

                $.ajax({
                    url:'/manually-stock-decrement',
                    type:'POST',
                    data:'item_id='+item_id+'&cat_id='+cat_id+'&location_id='+loc_id+'&outlet_id='+ol_id+'&from='+from+'&to='+to+'&flag='+flag,
                    success:function(data){
                        console.log(data);
                        if ( flag == 'fetch' ) {
                            $('#show_stock').attr('disabled',false);
                            $('#show_stock').text('Show Stock');
                            $('#stock-list').html(data);
                        } else if ( flag == 'revoke' ) {
                            $('#process_btn').attr('disabled',false);
                            $('#revoke_btn').attr('disabled',false);
                            $('#revoke_btn').text('Revoke Stock');
                            if ( data == 'true') {
                                window.location.href = '/stocks';
                            }
                        } else {
                            $('#revoke_btn').attr('disabled',false);
                            $('#process_btn').attr('disabled',false);
                            $('#process_btn').text('Decrease Stock');
                            if ( data == 'true') {
                                window.location.href = '/stocks';
                            }
                        }

                    }
                })
            }

        }

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
                    select.multiselect('destroy');
                    select.multiselect({
                        selectedList: 2,
                        noneSelectedText: "All Items"
                    });
                }
            });
        }

    </script>
@stop
