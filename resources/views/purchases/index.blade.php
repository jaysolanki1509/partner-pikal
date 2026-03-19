<?php use App\Outlet;
use App\MenuTitle;
use App\Menu;
use App\Unit;
use Devfactory\Minify\Minify as Minify;
?>

@extends('partials.default')
@section('pageHeader-left')
    Purchase
@stop

@section('pageHeader-right')
    <a href="/purchase/create" class="btn btn-primary" title="Add new Purchase"><i class="fa fa-plus"></i> Purchase</a>
    <a href="javascript:void(0)" onclick="makePaid()" class="btn btn-primary" title="Make Paid"><i class="fa fa-money"></i> Paid</a>
    <a href="javascript:void(0)" onclick="settleStock('show')" class="btn btn-primary" title="Settle Stock"><i class="fa fa-long-arrow-up fa fa-long-arrow-down"></i><i class="fa fa-long-arrow-down"></i> Stock</a>
    <button type="button" onclick="openImportModal()" class="btn btn-primary" title="Import Purchase"><i class="fa fa-upload"></i> Import</button>
    <a href="/invalid-import-items" class="btn btn-primary" title="Invalid Import Items"><i class="fa fa-remove"></i> Items</a>
@stop
@section('page-styles')

    {!! HTML::style('/assets/css/style.datatables.css') !!}
    {!! HTML::style('/assets/css/dataTables.responsive.css') !!}
    {!! HTML::style('/assets/css/custom.datatable.css') !!}
@stop
@section('content')

    <style>
        #PurchaseTable tr {
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <div class="table-responsive">
                            <table class="table dataTable" id="PurchaseTable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th title="Invoice No.">Invoice No.</th>
                                        <th title="Vendor">Vendor</th>
                                        <th title="Location">Location</th>
                                        <th title="Invoice Date">Invoice Date</th>
                                        <th title="Status">Status</th>
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
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice bill-->
    <div id="billModal" data-backdrop="static" data-keyboard="false" class="modal fade" role="dialog" >
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Invoice</h4>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="" id="bill_id" />
                    <button type="button" class="btn btn-primary" onclick="update()">Edit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div id="invoiceStockModal" data-backdrop="static" data-keyboard="false" class="modal fade" role="dialog" >
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Invoice Stock Detail</h4>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="import_modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Import Purchase Data</h4>
                </div>
                <div class="modal-body ">
                    {!! Form::open(array('method'=>'POST', 'files'=>true,'id'=>'import_form')) !!}
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-6">
                                {!! Form::label('location_id','Location:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::select('location_id',$locations,$selected_location,array('id' => 'location_id','class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('invoice_date','Invoice Date:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('invoice_date', \Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control','placeholder'=>"Select Date","id"=>"invoice_date","readonly"=>"readonly"]) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-6">
                                {!! Form::label('status','Status:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::select('status',array('liability' => 'Liability', 'invoice' => 'Invoice', 'paid' => 'Paid'),'liability',array('id' => 'status','class' => 'col-md-3 form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('invoice_no','Invoice No:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('invoice_no',null,array('id' => 'invoice_no', 'placeholder'=> 'Invoice no','class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('vendor','Vendor:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::select('vendor_id', $vendors, null,array('class'=>'form-control','id' => 'vendor_id','required')) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    {!! Form::label('file','Select File', array('class' => 'col-md-12 control-label')) !!}
                                    {!! Form::file('file', array('class'=>'form-control')) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="label label-danger col-md-12"></span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <a type="button" href="/download_sample_purchase" class="btn btn-default pull-left">Download Sample</a>&nbsp;&nbsp;
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('Restaurant_Show.Close') }}</button>&nbsp;&nbsp;
                        <button type="button" id="import_btn" class="btn btn-primary">Submit</button>
                    </div>
                    {!! Form::close('Close') !!}
                </div>
            </div>
        </div>
    </div>


@stop
@section('page-scripts')
    var selected = [];
    var table = '';
    {!! HTML::script('/assets/js/jquery.dataTables.min.js') !!}
    {!! HTML::script('/assets/js/dataTables.bootstrap.js') !!}
    {!! HTML::script('/assets/js/dataTables.responsive.js') !!}
    {!! HTML::script('/assets/js/datatable.list.js') !!}

    <script>
        var selected = [];
        var table = '';
        var PurchaseTable_filters = [];

        $(document).ready(function() {

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

            var tbl_id = 'PurchaseTable';
            var order = 4;
            var url = '/purchase';
            var columns = [
                { "mDataProp": "check_col","bSortable": false },
                { "mDataProp": "invoice_no" },
                { "mDataProp": "vendor" },
                { "mDataProp": "location" },
                { "mDataProp": "invoice_date" },
                { "mDataProp": "status" },
                { "mDataProp": "action","bSortable":false }
            ];

            loadList( tbl_id, url, order, columns,true);

            $('#invoice_date').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });

            $('#import_btn').click(function(e){

                $('.label-danger').empty();

                e.preventDefault();

                var loc_id = $('#import_form #location_id').val();
                var vendor_id = $('#import_form #vendor_id').val();
                var status = $('#import_form #status').val();
                var inv_no = $('#import_form #invoice_no').val();
                var file = $('#import_form #file').val();
                var error = 0;var err_text = '';
                if ( loc_id == '' ) {
                    err_text = 'Location can not be left blank <br>';
                    error = 1;
                }
                if ( vendor_id == '') {
                    err_text += 'Vendor can not be left blank <br>';
                    error = 1;
                }
                if ( file == '') {
                    err_text += 'Please select file to upload. <br>';
                    error = 1;
                }

                if ( error == 1 ) {
                    $('.label-danger').html(err_text);
                    return;
                }

                var form = $('#import_form');
                var data = new FormData(form[0]);

                $.ajax({
                    url: '/import-purchase',
                    type: "POST",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:'json',
                    success: function (data) {

                        if ( data.status == 'success') {

                            $('#import_modal').modal('hide');
                            table.draw();

                        }

                        successErrorMessage(data.message,data.status);
                    }
                });
            });

            //$('#datetimepicker').datetimepicker();
            //open bill detail modal
            $('#PurchaseTable tbody').on('click', 'td', function () {

                var $This = $(this);
                var col = $This.parent().children().index($(this));
                var title = $This.closest("table").find("th").eq(col).text();
                var id = $This.parent().attr('id');
                if ( title == 'Action' || col == 0) {
                    return;
                }

                if( id != null ) {

                    $('#billModal').modal('show');
                    $('#billModal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

                    $.ajax({
                        url: '/invoice-bill-detail',
                        type: "POST",
                        data: {id: id},
                        success: function (data) {

                            $('#billModal .modal-body').html(data);
                            $('#billModal #bill_id').val(id);
                        }
                    });

                }

            });

        });

        //open import modal
        function openImportModal() {

            document.getElementById('import_form').reset()
            $('#import_modal').modal('show');

        }
        //settle stock
        function settleStock(flag) {

            var ids = selected;

            if ( ids.length == 0 ) {
                alert('Please select one or multiple invoice');
                return;
            }

            if ( flag == 'show') {
                $('#invoiceStockModal').modal('show');
                $('#invoiceStockModal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');
            } else if( flag == 'add' ) {
                $('#invoiceStockModal #add_btn').text('Adding...');
            } else if ( flag == 'remove' ) {
                $('#invoiceStockModal #remove_btn').text('Removing...');
            }

            $.ajax({
                url: '/get-purchase-stock-detail',
                type: "POST",
                data: { ids:ids,flag:flag },
                success: function (data) {

                    if ( flag == 'show' ) {
                        $('#invoiceStockModal .modal-body').html(data);
                    } else {

                        if( data == 'success' ) {
                            if ( flag == 'add') {
                                alert('Stock has been added succesfully.')
                            } else {
                                alert('Stock has been removed succesfully.')
                            }
                            $('#invoiceStockModal').modal('hide');

                        } else {

                            if( flag == 'add' ) {
                                $('#invoiceStockModal #add_btn').html('<i class="fa fa-long-arrow-up"></i> Stock');
                            } else {
                                $('#invoiceStockModal #remove_btn').html('<i class="fa fa-long-arrow-down"></i> Stock');
                            }
                            alert('There is some error, Please try again later.');
                        }
                    }
                }
            });

        }

        //make invoice paid
        function makePaid() {

            var ids = selected;

            if ( ids.length == 0 ) {
                alert('Please select one or multiple invoice');
                return;
            }

            $.ajax({
                url: '/invoice-update',
                type: "POST",
                dataType:'json',
                data: {ids: ids},
                success: function (data) {
                    if ( data.status == 'success') {

                        if ( data.updated == 0 ) {
                            alert('No invoice has been updated');
                        } else {
                            alert(data.updated+' invoice has been updated');
                        }
                        table.draw();

                    } else {
                        alert('There is some error occurred.');
                    }

                }
            });

        }

        //on checkbox click add id in array
        function selectRow(id) {

            var index = $.inArray(id, selected);
            var filter = $('#filter_query').val();

            if ( index === -1 ) {
                selected.push( id );
            } else {
                selected.splice( index, 1 );
                if ( !filter ) {
                    $('#sel_all').attr('checked', false);
                }

            }
        }


        //update bill
        function update() {

            var id = $('#billModal #bill_id').val();

            var route = "/purchase/"+id+"/edit"
            //ele.('href', route);
            window.location.replace(route);
        }

        function warn(ele,id) {
            var temp = confirm("Do you want to remove the entry?");
            if (temp == true) {
                var route = "/purchase/"+id+"/destroy"
                //ele.('href', route);
                window.location.replace(route);
            }
        }
    </script>
@stop