@extends('partials.default')
@section('pageHeader-left')
    Satisfied Responses
@stop

@section('pageHeader-right')
    <a href="/requestItemProcess" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop
@section('page-styles')

    {!! HTML::style('/assets/css/style.datatables.css') !!}
    {!! HTML::style('/assets/css/dataTables.responsive.css') !!}
    {!! HTML::style('/assets/css/custom.datatable.css') !!}
@stop

@section('content')
    <style>
        #satisfiedResponseTable tr {
            cursor: pointer;
        }
    </style>
    @if(Session::has('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('error') }}
        </div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="table-responsive">
                                <table class="table dataTable" width="100%" id="satisfiedResponseTable">
                                    <thead >
                                    <tr>
                                        <th></th>
                                        <th title="Transaction ID">Transaction ID</th>
                                        <th title="Location">For Location</th>
                                        <th title="Date">Satisfied Date</th>
                                    </tr>
                                    </thead>

                                    <tfoot>
                                    <tr class="field-input whitebg">
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
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div id="responseDetailMOdal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 70%;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Response Detail</h4>
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

    <script type="text/javascript">

        $(document).ready(function() {

            var tbl_id = 'satisfiedResponseTable';
            var order = 3;
            var url = '/responseItems/setisfiedResponse';
            var columns = [
            { "mDataProp": "check_col","bSortable": false,"bVisible":false },
            { "mDataProp": "transaction_id" },
            { "mDataProp": "for_location" },
            { "mDataProp": "date" }
            ];

            loadList( tbl_id, url, order, columns,false);

            //open bill detail modal
            $('#satisfiedResponseTable tbody').on('click', 'td', function () {

                var $This = $(this);
                var col = $This.parent().children().index($(this));
                var title = $This.closest("table").find("th").eq(col).text();
                var id = $This.parent().attr('id');

                if( id != null ) {

                    $('#responseDetailMOdal').modal('show');
                    $('#responseDetailMOdal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

                    $.ajax({
                        url: '/satisfied-response-list',
                        type: "POST",
                        data: {id: id},
                        success: function (data) {
                            $('#responseDetailMOdal .modal-body').html(data);
                        }
                    });

                }

            });

        });

        function deleteResponse(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Response Details!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: '/responseItem/delete',
                        type: "POST",
                        data: {id: id},
                        success: function (data) {
                            if(data == "success"){
                                swal({
                                    title : "Deleted!",
                                    text : "Your Response Details has been removed.",
                                    type : "success"
                                }, function (isConfirm) {
                                    if (isConfirm) {
                                        var route = "/responseItems/setisfiedResponse"
                                        window.location.replace(route);
                                    }
                                });

                            }else {
                                swal({
                                    title : "Error!",
                                    text : "Error in deleting response.",
                                    type : "warning"
                                });
                            }
                        }
                    });

                } else {
                    swal("Cancelled", "Your Response Details are safe :)", "error");
                }
            });

        }

    </script>
@stop
