@extends('partials.default')
@section('pageHeader-left')
    Reward Point lists
@stop

@section('pageHeader-right')
    <a href="/reward-points" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('page-styles')
    {!! HTML::style('/assets/css/style.datatables.css') !!}
    {!! HTML::style('/assets/css/dataTables.responsive.css') !!}
    {!! HTML::style('/assets/css/custom.datatable.css') !!}
@stop

@section('content')

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="col-md-6">
                                    <label>Total Credited Points : </label>
                                    <span class="count" style="font-size: xx-large; color: green;"><b>{{ $credit }}</b></span>
                                </div>

                                <div class="col-md-6">
                                    <label>Total Debited Points : </label>
                                    <span class="count" style="font-size: xx-large; color: red;"><b>{{ $debit }}</b></span>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">

                                <div class="table-responsive">
                                    <table class="table dataTable" id="rewardTable">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th title="Contact Number">Contact number</th>
                                                <th title="Debit">Debit</th>
                                                <th title="Credit">Credit</th>
                                                <th title="Description">Desc</th>
                                                <th title="Date">Date</th>
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
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>

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

            var tbl_id = 'rewardTable';
            var order = 3;
            var url = '/reward-points/viewRewardPoints';

            var columns = [
                { "mDataProp": "check_col","bSortable": false,"bVisible":false },
                { "mDataProp": "contact" },
                { "mDataProp": "debit" },
                { "mDataProp": "credit" },
                { "mDataProp": "desc" },
                { "mDataProp": "date" }
            ];

            loadList( tbl_id, url, order, columns,false);


            $('.count').each(function () {
                $(this).prop('Counter', 0).animate({
                    Counter: $(this).text()
                }, {
                    duration: 4000,
                    easing: 'swing',
                    step: function (now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            });
        });

    </script>
@stop