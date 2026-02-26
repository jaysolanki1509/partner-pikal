@extends('partials.default')
@section('pageHeader-left')
    Reward Points
@stop

@section('pageHeader-right')
    <a href="/reward-points/show" class="btn btn-primary"><i class="fa fa-eye"></i> Customer Points</a>
    <a href="/reward-points/viewRewardPoints" class="btn btn-primary"><i class="fa fa-file"></i> All Points</a>
@stop

@section('content')

    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('error') }}
        </div>

    @endif

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">
                    {!! Form::open(['route' => 'reward-points.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms', 'id' => 'rewardForm']) !!}
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('customer','Select Customer*', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::select('user_id',$customers, null, ['data-placeholder'=>"Select Mobile number", 'class' => 'select2 form-control','id' => 'user_id','required']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('c_reward_points','Current Reward Points*', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('c_reward_points', null, array('disabled','class' => 'form-control','id' => 'c_reward_points', 'placeholder'=> 'Reward Points','required')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::label('perform','Perform*', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12 " >
                                    {!! Form::select('perform', ['credit'=>"Credit",'debit'=>"Debit"], null,array('class' => 'select2 form-control','id' => 'perform','required')) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                {!! Form::label('reward_points','Reward Points:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('reward_points', null, array('class' => 'form-control','id' => 'reward_points', 'placeholder'=> 'Reward Points','required')) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                {!! Form::label('date','Transaction Date:', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('txn_date', date('Y-m-d H:i:s'), array('class' => 'form-control','id' => 'txn_date', 'placeholder'=> 'Transaction Date','required')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('description','Description', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    <textarea placeholder="Description" rows="5" class="form-control" id="desc" name="desc"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <div class="col-md-12">
                            <button type="submit" id="add-btn" class="btn btn-success primary-btn" >Add</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-scripts')

    <script src="/assets/js/new/lib/bootstrap-datetimepicker.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $("#customer").select2({
                placeholder:"Select Mobile Number"
            });

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

            $('#txn_date').datetimepicker({
                format: "YYYY-MM-DD HH:mm:ss"
            });

        });

        $("#user_id").on("change", function () {
            var customer_id = $("#user_id").val();

            $.ajax({
                url: '/getRewardPoints',
                type: "POST",
                data: {user_id: customer_id},
                success: function (data) {
                    if ( data.status == 'success' ) {
                        $("#c_reward_points").val(data.points);
                    } else {
                        alert(data.message);
                    }
                }
            });
        });

    </script>
@stop