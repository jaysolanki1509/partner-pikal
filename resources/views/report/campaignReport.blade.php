@extends('partials.default')
@section('pageHeader-left')
    Campaign Report
@stop

@section('pageHeader-right')
@stop
@section('page-styles')

    {!! HTML::style('/assets/css/style.datatables.css') !!}
    {!! HTML::style('/assets/css/dataTables.responsive.css') !!}
    {!! HTML::style('/assets/css/custom.datatable.css') !!}
@stop
@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif


    <div id="image_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Images</h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <div class="table-responsive">

                            <table class="table dataTable" id="CampaignTable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th title="Owner Name">Owner Name</th>
                                        <th title="Outlet Name">Outlet Name</th>
                                        <th title="Contact No.">Contact No.</th>
                                        <th title="Email">Email</th>
                                        <th title="Address">Address</th>
                                        <th title="Status">Status</th>
                                        <th title="Date">Date</th>
                                        <th title="Date">Action</th>
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
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>

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
    <!-- /.row -->

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

            var tbl_id = 'CampaignTable';
            var order = 7;
            var url = '/campaign-report';
            var columns = [
                { "mDataProp": "check_col","bSortable": false,"bVisible":false },
                { "mDataProp": "owner_name" },
                { "mDataProp": "outlet_name" },
                { "mDataProp": "mobile","bSortable": false },
                { "mDataProp": "email","bSortable": false  },
                { "mDataProp": "address","bSortable": false  },
                { "mDataProp": "verified" },
                { "mDataProp": "updated_at"},
                { "mDataProp": "get_image"}
            ];

            loadList( tbl_id, url, order, columns,false);

        });

        function getImage(camp_id){
            $('#image_modal .modal-body').html('');
            $('#image_modal').modal('show');

            $.ajax({
                url:'/get-camp-image',
                type:'POST',
                data:'camp_id='+camp_id,
                success:function(data){
                    $('#image_modal .modal-body').html(data);
                }
            })

        }


    </script>
@stop