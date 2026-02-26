@extends('partials.default')

@section('pageHeader-left')
   Expense
@stop

@section('pageHeader-right')
    <a href="/expenseApp" class="btn btn-primary" title="Add new Expense"><i class="fa fa-plus"></i> Expense</a>
    <a href="/cash/add" class="btn btn-primary" title="Add Cash"><i class="fa fa-plus"></i> Cash</a>
@stop

@section('page-styles')
    {!! HTML::style('/assets/css/style.datatables.css') !!}
    {!! HTML::style('/assets/css/dataTables.responsive.css') !!}
    {!! HTML::style('/assets/css/custom.datatable.css') !!}
@stop

@section('content')

    @if(isset($day))
        <input type="hidden" value="{{$day}}" id="date">
    @endif
    @if(isset($month))
        <input type="hidden" value="{{$month}}" id="month">
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <div class="table-responsive">
                            <table class="table dataTable" id="expenseTable">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th title="Expense By">Expense By</th>
                                    <th title="Amount">Amount</th>
                                    <th title="Description">Description</th>
                                    <th title="Expense Date">Expense Date</th>
                                    <th title="Type">Type</th>
                                    <th title="Status">Status</th>
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
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /.row -->
    <div id="cancelExpense" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cancel Expense</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        Note: <label id="lbl_invoice_id"></label>
                    </div>
                    <div style="clear: both;"></div>
                    <input type="hidden" id="modal_expense_id" value=""/>
                    <div class="form-group col-md-12">
                        <textarea placeholder="Enter note" rows="5" class="form-control" id="reason"></textarea>
                    </div>
                    <div style="clear: both;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="cancelExpense()">Cancel Expense</button>
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

    <script type="text/javascript">

        $(document).ready(function(){

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

                    var date = $('#date').val(); //if route through dashboard
            var month = $('#month').val(); //if route through dashboard

            var tbl_id = 'expenseTable';
            var order = 4;
            if(date) {
                var url = '/expense/pending?day=' + date;
            }else if(month) {
                var url = '/expense/pending?month=' + month;
            }else {
                var url = '/expense/pending';
            }
            var columns = [
                { "mDataProp": "check_col","bSortable": false,"bVisible":false },
                { "mDataProp": "expense_by" },
                { "mDataProp": "amount" },
                { "mDataProp": "desc" },
                { "mDataProp": "expense_date" },
                { "mDataProp": "type" },
                { "mDataProp": "status" },
                { "mDataProp": "action" ,"bSortable": false},
            ];

            loadList( tbl_id, url, order, columns,false);



        });

        function changeStatus(ele,id) {

            var type = $(ele).data('type');

            if(type == 'canceled'){
                $('#reason').val('');
                $('#reason').css('border-color', '#ccc');
                $('#cancelExpense').modal('show');
                $('#modal_expense_id').val(id);
                return;
            }

            $.ajax({
                url: '/expense/status/'+id,
                type: "POST",
                data: {type: type},
                success: function (data) {
                    if ( data == 'success') {
                        table.draw();
                    }
                }
            });
        }

        function cancelExpense(){
            var id = $('#modal_expense_id').val();
            var reason = $('#reason').val();
            if (reason == '') {
                $('#reason').css('border-color', 'red');
                return;
            }else{
                $.ajax({
                    url: '/expense/note/'+id,
                    type: "POST",
                    data: {note: reason},
                    success: function (data) {
                        if ( data == 'success') {
                            $('#cancelExpense').modal('hide');
                            alert("Expense canceled successfully.");
                        }
                    }
                });
            }

            $.ajax({
                url: '/expense/status/'+id,
                type: "POST",
                data: {type: 'canceled'},
                success: function (data) {
                    if ( data == 'success') {
                        table.draw();
                    }
                }
            });

        }

    </script>
@stop