@extends('partials.default')
@section('pageHeader-left')
    Reward Points
@stop

@section('pageHeader-right')
    <a href="/reward-points" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('customer','Select Customer*', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::select('customer',$customers, null, array('class' => 'select2 form-control','id' => 'customer')) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <br> <br>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12" id="customer_data">
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
    <script>

        $("#customer").on("change", function () {
            var customer_id = $("#customer").val();
            $.ajax({
                url: '/getRewardPointsTransaction',
                type: "POST",
                data: {customer_id: customer_id},
                success: function (data) {
                    $("#customer_data").html(data);

                    $('#reward_history').DataTable();
                }
            });
        });

    </script>
@stop