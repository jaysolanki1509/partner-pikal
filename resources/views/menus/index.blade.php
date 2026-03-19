<?php use App\Outlet;
use App\MenuTitle;
use App\Menu;
use App\Unit;
?>

@extends('partials.default')
@section('pageHeader-left')
    Items
    <style>
        #loading {
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            position: fixed;
            display: block;
            opacity: 0.7;
            background-color: #fff;
            z-index: 99;
            text-align: center;
        }

        #loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #FF9F21;
            border-bottom: 16px solid #FF9F21;
            position: relative;
            top:50%;
            left: 50%;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@stop

@section('pageHeader-right')
    <a href="/menutitle" class="btn btn-primary" title="Category"><i class="fa fa-list"></i>&nbsp;Category</a>
    <a href="/menu/create" class="btn btn-primary" title="Add new Item"><i class="fa fa-plus"></i>&nbsp;Item</a>
    <button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#attachmenu" data-whatever="" title="Import new Menu File"><i class="fa fa-upload"></i>&nbsp;Import</button>
    {{--export menus items --}}
    <div class="pull-right" style="margin-left:5px;">
        <span class="input-group-addon" style="display:none" ></span>
        {!! Form::open(array('url'=>'outlet/exportexcel','method'=>'POST')) !!}
        <div class="control-group">
            <div class="controls">
                <input type="hidden" name="restau_id" value=""/>
            </div>
        </div>
        <button id="<?php \Illuminate\Support\Facades\Auth::id(); ?>" class="btn btn-primary" type="submit" class="exportexcel" title="Save existing Menu File"><i class="fa fa-download"></i>&nbsp;Export</button>
        {!! Form::close() !!}
    </div>
@stop

@section('page-styles')
    {!! HTML::style('/assets/css/style.datatables.css') !!}
    {!! HTML::style('/assets/css/rowReorder.dataTables.min.css') !!}
    {!! HTML::style('/assets/css/responsive.dataTables.min.2.1.1.css') !!}
    {!! HTML::style('/assets/css/custom.datatable.css') !!}

@stop

@section('content')

    <div class="row">
        <div class="col-md-12 widget-wrap">
            <div id="loading" class="hide">
                <div id="loader"></div>
            </div>
            <div class="col-md-12">
                <a href="#" onclick="changeSetting('all','is_sale')" class="btn btn-primary pull-right" title="All Set Sale" style="margin-left: 10px;">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    Sale</a>
                <a href="#" onclick="changeSetting('all','is_active')" class="btn btn-primary pull-right" title="All Set Active" style="margin-left: 10px;">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    Active</a>
                <a href="#" onclick="changeSetting('all','is_bind')" class="btn btn-primary pull-right" title="All Set Bind">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    Bind</a>
            </div>
            <div style="clear:both"></div>
            <div class="material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <div class="table-responsive">
                            <div class="result"></div>
                            <table  class="table dataTable" id="itemTable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th title="Drag">Drag</th>
                                        <th title="Image">Image</th>
                                        <th title="Category">Category</th>
                                        <th title="Item Code">Item Code</th>
                                        <th title="Item">Item Name</th>
                                        <th title="Price">Price</th>
                                        <th title="Last Purchase Price">LPP</th>
                                        <th title="Visibility">Visibility</th>
                                        <th title="Action">Action</th>
                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr class="field-input whitebg">
                                        <th style="text-align: center"></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: center"></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attachmenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel"></h4>
                </div>
                <div class="modal-body ">
                    <div class="form-group">
                        {!! Form::open(array('url'=>'menu/importmenuexcel','method'=>'POST', 'files'=>true)) !!}
                        <div class="input-group input-group-lg mb15 wid-full">
                            <span class="input-group-addon" style="display:none" ></span>
                            <div class="control-group">
                                <div class="controls">
                                    <input type="hidden" name="user_id" value="<?php \Illuminate\Support\Facades\Auth::user()->id; ?>"/>
                                    {!! Form::file('file1', array('multiple'=>true,'required')) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('Restaurant_Show.Close') }}</button>
                        {!! Form::submit(trans('Restaurant_Show.Submit'), array('class'=>'btn btn-primary')) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('page-scripts')

    {!! HTML::script('/assets/js/jquery.dataTables.1.10.13.min.js') !!}
    {!! HTML::script('/assets/js/dataTables.bootstrap.js') !!}
    {!! HTML::script('/assets/js/jquery.dataTables.rowReorder.min.js') !!}
    {!! HTML::script('/assets/js/dataTables.responsive.min.2.1.1.js') !!}
    {!! HTML::script('/assets/js/datatable.list.js') !!}



    <script type="text/javascript">

        var check_response = 0;

        function warn(ele,id) {
            var temp = confirm("Are you sure you want to delete this item?");
            if (temp == true) {
                $.ajax({
                    url: '/menu/destroy',
                    type: "POST",
                    data: {id: id},
                    success: function (data) {
                        if ( data == 'success' ) {
                            table.draw();
                        } else {
                            alert('please try again later.');
                        }
                    }
                });
            }
        }

        var selected = [];
        var table = '';
        var PurchaseTable_filters = [];

        $(document).ready(function() {

            drawTable();
            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

        });

        function drawTable() {
            var tbl_id = 'itemTable';
            var order = 2;
            var url = '/menu';
            var columns = [
                { "mDataProp": "drag","bSortable": false},
                { "mDataProp": "check_col","bSortable": false },
                { "mDataProp": "image","bSortable": false},
                { "mDataProp": "title"},
                { "mDataProp": "item_code" },
                { "mDataProp": "item" },
                { "mDataProp": "price" },
                { "mDataProp": "lpp" },
                { "mDataProp": "visibility" ,"bSortable":false },
                { "mDataProp": "action","bSortable":false }
            ];

            loadList( tbl_id, url, order, columns, true);

        }

        function del(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Menu Details!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    swal({
                        title : "Deleted!",
                        text : "Your Menu Details has been removed.",
                        type : "success"
                    },function () {
                        $.ajax({
                            url: '/menu/destroy',
                            type: "POST",
                            data: {id: id},
                            success: function (data) {
                                $('#'+id).remove();
                                //location.reload();
                            }
                        });
                    });
                } else {
                    swal("Cancelled", "Your Menu Details are safe :)", "error");
                }
            });

        }

        function changeSetting(ele,flag) {

            if(ele == "all"){
                $('#loading').removeClass('hide');

                var all_items = [];
                var value = 1;

                $('.checkbox1:checked').each(function() {
                    var $this = $(this);
                    all_items.push(($this.attr("id")));
                });
                if(all_items.length == 0){
                    alert("Please select at least one Item");
                    $('#loading').addClass('hide');
                    return 0;
                }
                $.ajax({
                    url: 'change-item-settings',
                    type: "post",
                    data: {item_id: all_items, flag: flag, value: value,qty:"selected"},
                    success: function (data) {
                        check_response = 0;

                        if (data == 'true') {
                            table.draw();
                            $('#loading').addClass('hide');
                        }
                    }
                });


            }else {

                var item_id = $(ele).closest('tr').attr('id');
                var value = '';

                if ($(ele).hasClass('label-success')) {

                    if (flag == 'is_active') {
                        value = 1;
                    } else {
                        value = 0;
                    }

                } else {

                    if (flag == 'is_active') {
                        value = 0;
                    } else {
                        value = 1;
                    }
                }

                if (check_response == 0) {

                    check_response = 1;

                    $.ajax({
                        url: 'change-item-settings',
                        type: "post",
                        data: {item_id: item_id, flag: flag, value: value,qty:"single"},
                        success: function (data) {

                            check_response = 0;

                            if (data == 'true') {

                                if (flag == 'is_active') {

                                    if (value == 0) {
                                        $(ele).removeClass('label-default');
                                        $(ele).addClass('label-success');
                                    } else {
                                        $(ele).removeClass('label-success');
                                        $(ele).addClass('label-default');
                                    }

                                } else if (flag == 'is_sale' || flag == 'is_bind') {

                                    if (value == 1) {
                                        $(ele).removeClass('label-default');
                                        $(ele).addClass('label-success');
                                    } else {
                                        $(ele).removeClass('label-success');
                                        $(ele).addClass('label-default');
                                    }

                                }

                                successErrorMessage('Item setting updated successfully.', 'success');

                            } else {
                                successErrorMessage('There is some error ocurred', 'error');
                            }

                        }
                    });

                }
            }

        }

    </script>
@stop