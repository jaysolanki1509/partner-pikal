@extends('partials.default')

@section('pageHeader-left')
    Check In Report
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix j-forms">

                    <div class="form-group col-md-8">

                        <div class="input-daterange input-group">
                            {!! Form::text('from_date', isset($from_session)?$from_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"from_date","readonly"=>"readonly"]) !!}
                            <span class="input-group-addon">to</span>
                            {!! Form::text('to_cate', isset($to_session)?$to_session:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control',"id"=>"to_date","readonly"=>"readonly"]) !!}
                        </div>

                    </div>
                    <div class="col-md-2 form-group">
                        <button type="button" class="btn btn-success primary-btn pull-left" id="show_btn" onclick="getList()">Show</button>
                    </div>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div id="data-list" style="overflow-x: auto;">
                            No record found.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {

        });

        function removeError() {
            $('#location_id-error').addClass('hide');
        }
        //get

        function getList() {

            var from = $('#from_date').val();
            var to = $('#to_date').val();
            if(check30daysDiff(from,to)){
                return;
            }
            processBtn('show_btn','add','Showing...');

            $.ajax({
                url: '/hotel-check-in',
                type: "post",
                data: { from_date:from,to_date:to},
                success: function (data) {
                    $('#data-list').html(data);
                    processBtn('show_btn','remove','Show');

                }
            });

        }

    </script>
@stop