<?php use App\Outlet;
use App\MenuTitle;
use App\Menu;
use App\Unit;
use Devfactory\Minify\Minify as Minify;
?>

@extends('partials.default')
@section('pageHeader-left')
    Stock Details
@stop

@section('pageHeader-right')
    @if(isset($locations) && sizeof($locations)>0 )
        <a href="javascript:void(0)" onclick="reservedStock();" class="btn btn-danger" title="Less Then Reserved Stock"><i class="fa fa-battery-quarter" aria-hidden="true"></i>&nbsp;Stock</a>
        <a href="javascript:void(0)" onclick="removeStock('open','');" class="btn btn-danger" title="Remove Stock"><i class="fa fa-remove"></i>&nbsp;Stock</a>
        <a href="javascript:void(0)" onclick="addStock('open','');" class="btn btn-primary" title="Add Stock"><i class="fa fa-plus"></i>&nbsp;Stock</a>
        <a href="/stock-transfer" class="btn btn-primary" title="Stock Transfer"><i class="fa fa-truck"></i>&nbsp;Stock</a>
        <a href="/manually-stock-decrement" class="btn btn-primary" title="Adjust Stock Against Sale | Manually Stock Transfer"><i class="fa fa-exchange"></i>&nbsp; Stock</a>
    @endif
@stop

@section('page-styles')
    {!! HTML::style('/assets/css/style.datatables.css') !!}
    {!! HTML::style('/assets/css/dataTables.responsive.css') !!}
    {!! HTML::style('/assets/css/custom.datatable.css') !!}
@stop
@section('content')
    <style>
        #StockTable tr {
            cursor: pointer;
        }
    </style>

        <div class="row">
            <div class="col-md-12">
                <div class="widget-wrap material-table-widget">
                    <div class="widget-container margin-top-0">
                        <div class="widget-content">
                            @if(isset($locations) && sizeof($locations)>0 )
                                <div class="table-responsive">
                                    <table class="table dataTable" id="StockTable">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th title="Category">Category</th>
                                            <th title="Item">Item</th>
                                            <th title="Stock">Stock</th>
                                            <th title="Location">Location</th>
                                            <th title="Updated At">Updated At</th>
                                            <th title="Action">Action</th>
                                        </tr>
                                        </thead>

                                        <tfoot>
                                        <tr class="field-input whitebg">
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @else
                                <div class="col-md-12">
                                    Please add Location for your outlet first. Click <a href="/location">here</a> to add.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- /.row -->
        <div id="stock_add_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Stock</h4>
                    </div>
                    <div class="modal-body">
                        <form id="stock_add_form" class="j-forms">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <select id="location_id" name="location_id" class="form-control">
                                        @foreach($locations as $key=>$val)
                                            <option value="{!! $key !!}">{!! $val !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::text('batch_no',null, array('class'=>'form-control','id'=>'batch_no','placeholder'=> 'Batch No.')) !!}
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::text('manufacture_date',null, array('class'=>'form-control','id'=>'manufacture_date','placeholder'=> 'Manufacture Date')) !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <select id="item_id" name="item_id" class="form-control">
                                        <option value="" data-order-unit="">Select Item</option>
                                        @foreach( $items as $itm )
                                            <option value="{{ $itm['id'] }}" data-order-unit="{{ $itm['order_unit'] }}">{{ $itm['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="number" min="0" id="quantity" name="quantity" placeholder="Quantity" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::select('unit_id',$units,null,array('class' => 'add_unit_id form-control','disabled'=>'disabled')) !!}
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group col-md-12">
                                    <textarea placeholder="Enter reason" rows="5" class="form-control" id="reason" name="reason"></textarea>
                                </div>
                            </div>

                            <div class="form-group col-md-12 stock-error-div">
                            </div>

                            <div style="clear: both;"></div>

                            <div class="modal-footer">
                                <button type="button" id="add_btn" class="btn btn-primary" onclick="addStock()">Add</button>
                                <button type="button" id="cancel_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="removeStock_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Remove Stock</h4>
                    </div>
                    <div class="modal-body">
                        <form id="stock_remove_form" class="j-forms">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <select id="remove_item_id" name="remove_item_id" class="form-control">
                                        <option value="" data-order-unit="">Select Item</option>
                                        @foreach( $items as $itm )
                                                <option value="{{ $itm['id'] }}" data-order-unit="{{ $itm['order_unit'] }}">{{ $itm['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <select id="remove_location_id" name="remove_location_id" class="form-control" >{{--onchange="removeItemDetail(this.value)">--}}
                                        @foreach($locations as $key=>$val)
                                            @if($key == $selected_location)
                                                <option selected value="{!! $key !!}">{!! $val !!}</option>
                                            @else
                                                <option value="{!! $key !!}">{!! $val !!}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="number" min="0" id="remove_item_qty" name="remove_item_qty" placeholder="Quantity" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::select('unit_id[]',$units,null,array('class' => 'remove_unit_id form-control')) !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <textarea placeholder="Enter reason" rows="5" class="form-control" id="remove_reason" name="remove_reason"></textarea>
                                </div>
                            </div>

                            <div id="loading" style="text-align:center; display: none" ><img src="/loader.gif" /></div>

                            <div class="form-group col-md-12 error-div">
                                <span id="remove_error"></span>
                            </div>
                            <div style="clear: both;"></div>

                            <div class="modal-footer">
                                <button type="button" id="remove_btn" class="btn btn-danger" onclick="removeItemStock('adjust')">Remove</button>
                                <button type="button" id="cancel_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div id="stock-detail-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Stock Detail</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="reserve-stock-detail-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Reserve Stock Detail</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

@stop
@section('page-scripts')

    {!! HTML::script('/assets/js/jquery.dataTables.min.js') !!}
    {!! HTML::script('/assets/js/dataTables.bootstrap.js') !!}
    {!! HTML::script('/assets/js/dataTables.responsive.js') !!}
    {!! HTML::script('/assets/js/datatable.list.js') !!}
    <script src="/assets/js/new/lib/jquery.validate.js"></script>

    <script>

        function reservedStock(){

            $('#reserve-stock-detail-modal').modal('show');
            $('#reserve-stock-detail-modal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');
            $.ajax({
                url:'/reserve-stock',
                type:'POST',
                success:function(data){
                    $('#reserve-stock-detail-modal .modal-body').html(data);
                }
            })

        }

        var selected = [];

        $(document).ready(function() {

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

            $('#remove_item_id').select2();
            $('#remove_location_id').select2();
            $('.remove_unit_id').select2();

            $('#item_id').select2();
            $('#location_id').select2();
            $('.add_unit_id').select2();

            $('#stock_add_form #manufacture_date').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });


            $('#item_id').on("change", function() {
                // what you would like to happen
                var i_id = $(this).val();
                var order_unit = $(this).select2().find(":selected").data("order-unit");


                var select = $('.add_unit_id');

                $.ajax({
                    url: '/get-item-other-units',
                    type: "POST",
                    data: {id: i_id},
                    success: function (data) {

                        select.empty();

                        var cnt = 1;
                        $.each(data, function(key,value) {
                            if ( key == order_unit ) {
                                select.append($("<option></option>")
                                        .attr("value", key).prop('selected',true).text(value));

                                select.select2().select2('val',key);

                            } else {

                                if ( cnt == 1 ) {

                                    select.append($("<option></option>")
                                            .attr("value", key).text(value));
                                    select.select2().select2('val',key);

                                } else {
                                    select.append($("<option></option>")
                                            .attr("value", key).text(value));
                                }

                            }
                            select.removeAttr('disabled');
                            cnt++;

                        });

                    }
                });

            });

            $('#remove_item_id').on("change", function() {
                // what you would like to happen
                var i_id = $(this).val();
                var order_unit = $(this).select2().find(":selected").data("order-unit");

                var location_id = $('#remove_location_id').val();
                if(location_id) {
                    $('#remove_error').text('');
                    $('#remove_error').css('border-color', '');
                    $('#stock-table').html('');
                    $('#remove_location_id').val('');
                }

                var select = $('.remove_unit_id');
                if ( i_id ) {
                    $.ajax({
                        url: '/get-item-other-units',
                        type: "POST",
                        data: {id: i_id},
                        success: function (data) {

                            select.empty();
                            //select.select2("val", "");

                            var cnt = 1;
                            $.each(data, function(key,value) {
                                if ( key == order_unit ) {
                                    select.append($("<option></option>")
                                            .attr("value", key).text(value));

                                    select.select2().select2('val',key);

                                } else {

                                    if ( cnt == 1 ) {

                                        select.append($("<option></option>")
                                                .attr("value", key).text(value));
                                        select.select2().select2('val',key);

                                    } else {
                                        select.append($("<option></option>")
                                                .attr("value", key).text(value));
                                    }


                                }
                                select.removeAttr('disabled');
                                cnt++;
                            });

                        }
                    });
                }else{
                    select.addAttr('disabled');
                }

            });


            var tbl_id = 'StockTable';
            var order = 5;
            var url = '/stocks';
            var columns = [
                { "mDataProp": "check_col","bSortable": false,"bVisible":false },
                { "mDataProp": "category" },
                { "mDataProp": "item" },
                { "mDataProp": "stock" },
                { "mDataProp": "location" },
                { "mDataProp": "updated_at" },
                { "mDataProp": "action" },
            ];

            loadList( tbl_id, url, order, columns,false);

        });

        function showDetail(item_id,location_id){

            $('#stock-detail-modal').modal('show');
            $('#stock-detail-modal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');
            $.ajax({
                url:'/stock-detail',
                type:'POST',
                data:'item_id='+item_id+'&location_id='+location_id,
                success:function(data){
                    $('#stock-detail-modal .modal-body').html(data);
                }
            })

        }

        function removeItemDetail(el) {
            var item_id = $('#remove_item_id').val();
            var flag = 'stock';
            if(remove_location_id != ''){
                $('#remove_error').text('');
            }

            $.ajax({
                url:'/requestprocessitemstockdetail',
                type:'POST',
                data:'req_id='+''+'&location_id='+el+'&item_id='+item_id+'&item_name='+''+'&req_qty='+''+'&flag='+flag,
                success:function(data){
                    $("#loading").show();
                    if ( flag == 'stock') {

                        $('#removeStock_modal .modal-body #stock-table').html(data);
                        if ( $('#removeStock_modal .modal-body #stock-table table').length > 0) {
                            $('#removeStock_modal #remove_btn').removeClass('hide');
                        } else {
                            $('#removeStock_modal #remove_btn').addClass('hide');
                        }
                    }
                    $("#loading").hide();
                }
            })
        }


        function addStock(flag,check) {

            $('label.error').remove();
            $('#stock_add_form').find('.error').removeClass('error');

            if ( flag == 'open') {

                $('#stock_add_form #item_id').val('');
                $('#stock_add_form #unit_id').val('');
                $('#stock_add_form #unit_name').text('');
                $('#stock_add_form #batch_no').text('');
                $('#stock_add_form #location_id').val('');
                $('#stock_add_form #reason').val('');
                $('#stock_add_form #quantity').val('');
                $('#stock_add_form #manufacture_date').val('');
                $('#stock_add_form .select2-chosen').text('Item');

                $('#stock_add_modal').modal('show');

            } else {

                $('.stock-error-div').empty();
                var error = 0;

                var item_id = $('#item_id').val();
                var unit_id = $('.add_unit_id').val();
                var location_id = $('#location_id').val();
                var batch_no = $('#batch_no').val();
                var reason = $('#reason').val();
                var quantity = $('#quantity').val();
                var manufacture_date = $('#manufacture_date').val();

                if(item_id == ''){

                    $('.stock-error-div').append('<span class="col-md-12 error-view">Item is required.</span>');
                    error = 1;
                }

                if ( location_id == '' ) {
                    $('.stock-error-div').append('<span class="col-md-12 error-view">Location is required</span>');
                    error = 1;
                }

                if ( quantity == '' || quantity < 0 || !isNumber(quantity)) {
                    $('.stock-error-div').append('<span class="col-md-12 error-view">Remove quantity must be numeric and greater then 0.</span>');
                    error = 1;
                }

                if ( reason == '' ) {

                    $('.stock-error-div').append('<span class="col-md-12 error-view">Reason is required.</span>');
                    error = 1;

                }


                if( error == 1 ){
                    return;
                } else {
                    $('.stock-error-div').empty();

                }


                $('#add_btn').text('Adding...');

                $('#add_btn').prop('disabled',true);
                $('#cancel_btn').prop('disabled',true);

                $.ajax({
                    url:'/add-stock',
                    Type:'POST',
                    dataType:'json',
                    data:'batch_no='+batch_no+'&manufacture_date='+manufacture_date+'&item_id='+item_id+'&unit_id='+unit_id+'&location_id='+location_id+'&reason='+reason+'&quantity='+quantity,
                    success:function(data){

                        $('#add_btn').prop('disabled',false);
                        $('#cancel_btn').prop('disabled',false);

                        $('#add_btn').text('Add');

                        if ( data.status == 'success' ) {
                            $('#stock_add_modal').modal('hide');
                            table.draw();
                        } else if ( data.status == 'error' ) {
                            alert(data.msg);
                        } else {
                            alert(data.msg);
                        }

                    }
                })


            }

        }

        function removeStock(flag) {

            $('label.error').remove();
            $('#stock_adjust_form').find('.error').removeClass('error');

            if ( flag == 'open') {

                $('.error-div').empty();

                $('#remove_item_id').val("");
                $("#remove_location_id").val("");
                $(".remove_unit_id").val("");


                $('#remove_reason').val('');
                $('.remove_unit_id')
                        .find('option')
                        .remove()
                        .end()
                        .append('<option value="">Select Unit</option>')
                        .val('');
                $('.remove_unit_id').prop('disabled', 'disabled');
                $('#remove_item_qty').val('');
                $('#remove_unit_id1').val('');
                $('#removeStock_modal').modal('show');


            } else {

                var item_id = $('#remove_item_id').val();
                var unit_id = $('#remove_unit_id').val();
                var location_id = $('#remove_location_id').val();
                var reason = $('#remove_reason').val();
                var quantity = $('#remove_quantity').val();

                $('#remove_btn').text('Removing...');
                $('#remove_btn').prop('disabled',true);
                $('#cancel_btn').prop('disabled',true);
                var data = $('#stock_remove_form').serialize();

                $.ajax({
                    url:'/remove-stock',
                    Type:'POST',
                    dataType:'json',
                    data:'falg=remove&'+data,
                    success:function(data){

                        $('#remove_btn').prop('disabled', false);
                        $('#cancel_btn').prop('disabled',false);
                        $('#remove_btn').text('Remove');

                        if ( data.status == 'success' ) {
                            $('#removeStock_modal').modal('hide');
                            alert(data.msg);
                            table.draw();
                        } else if ( data.status == 'error' ) {
                            $('#remove_error').text(data.msg);
                        } else {
                            $('#remove_error').text(data.msg);
                        }
                    }
                });
            }
        }

        function removeItemStock(flag) {

            $('.error-div').empty();
            var tot_satisfy = 0;
            var error = 0;
            var reason = $('#remove_reason').val();
            var item_id = $('#remove_item_id').val();
            var remove_location_id = $('#remove_location_id').val();
            var remove_item_qty = $('#remove_item_qty').val();

            if(item_id == ''){

                $('.error-div').append('<span class="col-md-12 error-view">Item is required.</span>');
                error = 1;
            }

            if ( remove_location_id == '' ) {
                $('.error-div').append('<span class="col-md-12 error-view">Location is required</span>');
                error = 1;
            }

            if ( remove_item_qty == '' || remove_item_qty < 0 || !isNumber(remove_item_qty)) {
                $('.error-div').append('<span class="col-md-12 error-view">Remove quantity must be numeric and greater then 0.</span>');
                error = 1;
            }

            if ( reason == '' ) {

                $('.error-div').append('<span class="col-md-12 error-view">Reason is required.</span>');
                error = 1;

            }


            if( error == 1 ){
                return;
            } else {

                $('.error-div').empty();
                removeStock('close')

            }
        }


    </script>
@stop