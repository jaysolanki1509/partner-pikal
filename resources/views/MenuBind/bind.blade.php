<?php
        use App\Outlet;
        use App\Owner;
?>

@extends('partials.default')
@section('pageHeader-left')
    Bind Menu With Outlet
@stop

@section('pageHeader-right')
    <a href="/outlet" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

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

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix">
                    <form class='j-forms' role="form" method="POST" id="bindMenu" action="{{ url('/menuBind') }}">


                            <div class="col-md-6 form" >
                                {!! Form::select('menus', $menu, null, ['class' => 'select2 form-control', 'id' => 'menus']) !!}
                            </div>
                            <div class="col-md-2 form">
                                <button type="submit" id="Submit" novalidate="novalidate" class="btn btn-success">Bind</button>
                            </div>



                        <input type="hidden" name="binddata" id="binddata" value=""/>
                        <div class="col-md-12"><hr></div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="dataTable_wrapper" id="wp">
                                            <table class="table table-striped table-hover" >
                                                <thead>
                                                <th>Item Name</th>
                                                {{--<th>Owner Name</th>--}}
                                                <th>Select All</th>
                                                <th>Outlet Name</th>
                                                </thead>
                                                <tbody id="items_checked">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script>
        $(document).ready(function() {

            $('#account_id').select2({
                placeholder: 'Select a Menu Category'
            });
            var base_url = "{{ Request::root() }}";

            $("#bindMenu").validate({
                rules: {
                    "menus": {
                        required: true
                    }
                },
                messages: {
                    "menus": {
                        required: "*Item Category is required"
                    }
                }
            });

            $("#bindMenu").submit(function(e){
                if($('#menus').val()==0){
                    alert('Select Menu Category.');
                    e.preventDefault(e);
                }
                outside_array = {};
                $('#items_checked > tr').each(function() {

                    var itemid=$(this).find(".outletsingle").val();
                    jsonObj = [];
                    $(this).find(".outlettd").find(".outletmultiple").each(function () {
                    if($(this).prop("checked")) {
                     jsonObj.push($(this).val());
                    }
                    });

                    outside_array [itemid] = jsonObj;

                });

                $('#binddata').val(JSON.stringify(outside_array));
            });

            $("#menus").change(function (e) {

                var title_id=$(this).val();
                var finalString='';
                var myObject = new Object();

                    $.ajax({
                        url: '/ajax/MenuItemList',
                        data:'title_id='+title_id,
                        success: function(data) {

                            var jsonoutput = $.parseJSON(data);

                            $.each(jsonoutput.Items, function(key,value) {
                                var outletname='';
                                $.each(jsonoutput.Outlets, function(key1,value1) {

                                    if ( value.map_outlet_id ) {

                                        var map_o_ids = value.map_outlet_id.split(',');

                                        if( jQuery.inArray(value1.id.toString(),map_o_ids) > -1 ) {
                                            outletname+="<label class='checkbox'><input checked type='checkbox' class='outletmultiple' name='item_selected[]' value='"+value1.id+"' /><i></i>"+value1.name+"</label>&nbsp;";
                                        } else {
                                            outletname+="<label class='checkbox'><input type='checkbox' class='outletmultiple' name='item_selected[]' value='"+value1.id+"' /><i></i>"+value1.name+"</label>&nbsp;";
                                        }

                                    } else {
                                        outletname+="<label class='checkbox'><input type='checkbox' class='outletmultiple' name='item_selected[]' value='"+value1.id+"' /><i></i>"+value1.name+"</label>&nbsp;";
                                    }

                                });

                                /*if all checkbox are selected than display all checkbox as selected*/
                                var sel_all = '';
                                if ( value.map_outlet_id ) {

                                    var cnt_map_ids = value.map_outlet_id.split(',');
                                    if ( jsonoutput.Outlets.length == cnt_map_ids.length ){
                                        sel_all = 'checked';
                                    }
                                }


                                finalString+="<tr id='"+value.id+"'><td>"+value.item+"</td>"+"<td class='text-center'><label class='checkbox'><input "+sel_all+" type='checkbox' class='" +
                                        "outletsingle' name='item_all[]' value='"+value.id+"' /><i></i></label></td>"+"<td class='outlettd'>"+outletname+"</td>";
                            });
                           // alert(finalString);
                            $('#items_checked').html(finalString);

                            $('.outletsingle').click (function (e) {
                                if($(this).prop("checked")){
                                    var itemid=$(this).val();
                                    outside_array = {};
                                    jsonObj = [];
                                    $(this).parent().parent().parent().find(".outlettd").find(".outletmultiple").each(function () {
                                        jsonObj.push( $(this).val());
                                        $(this).prop("checked", true);
                                    });
                                    outside_array [itemid] = jsonObj;
                                } else {
                                    $(this).parent().parent().parent().find(".outlettd").find(".outletmultiple").each(function () {
                                        $(this).prop("checked", false);
                                    });
                                    outside_array = {};
                                }
                            });

                            $('.outletmultiple').click (function (e) {

                                var is_checked=true;
                                $(this).parent().parent().find(".outletmultiple").each(function () {
                                    if(!$(this).is(':checked')){
                                        is_checked=false;
                                    }
                                });
                                if(is_checked){
                                    $(this).parent().parent().parent().find(".outletsingle").prop("checked", true);
                                }else{
                                    $(this).parent().parent().parent().find(".outletsingle").prop("checked", false);
                                }
                            });
                        }
                    });

            });
        });

    </script>

@stop