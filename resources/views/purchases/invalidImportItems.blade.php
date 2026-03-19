@extends('partials.default')
@section('pageHeader-left')
    Invalid Purchase
@stop

@section('pageHeader-right')
    <a href="/purchase" class="btn btn-primary" title="Back"><i class="fa fa-backward"></i> Back</a>
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
                            <table class="table dataTable" id="invalid_purchase_import_Table">
                                <thead >
                                    <tr>
                                        <th></th>
                                        <th title="Invoice No.">Invoice No.</th>
                                        <th title="Vendor">Vendor</th>
                                        <th title="Item">Item</th>
                                        <th title="Qty">Qty</th>
                                        <th title="Rate">Rate</th>
                                        <th title="Invoice Date">Invoice Date</th>
                                        <th title="Reason">Reason</th>
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
        var Invalid_purchase_import_filters = [];

        $(document).ready(function() {

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}', 'success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}', 'error');
                    @endif

            var tbl_id = 'invalid_purchase_import_Table';
            var order = 4;
            var url = '/invalid-import-items';
            var columns = [
                { "mDataProp": "check_col","bSortable": false,"bVisible":false },
                {"mDataProp": "invoice_no"},
                {"mDataProp": "vendor"},
                {"mDataProp": "item"},
                {"mDataProp": "qty"},
                {"mDataProp": "rate"},
                {"mDataProp": "invoice_date"},
                {"mDataProp": "reason"},
                {"mDataProp": "action", "bSortable": false}
            ];

            loadList(tbl_id, url, order, columns, false);

        });

        function warn(ele,id) {
            var temp = confirm("Do you want to remove the entry?");
            if (temp == true) {
                var route = "/invalid-purchase/"+id+"/destroy";
                //ele.('href', route);
                window.location.replace(route);
            }
        }
    </script>
@stop

