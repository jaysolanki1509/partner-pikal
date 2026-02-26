<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');

?>
<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">

                @if($action=='add')
                    <form class="form-horizontal material-form j-forms" role="form" method="POST" id="cancel_reason" novalidate="novalidate" action="{{ url('/cancellationreason') }}" files="true" enctype="multipart/form-data">
                @else
                    {!! Form::model($cancel,array('route' => array('cancellationreason.update',$cancel->id), 'method' => 'patch','id'=>'cancel_reason', 'class' => 'form-horizontal material-form j-forms')) !!}
                @endif
                    @if( !isset($sess_outlet_id) || $sess_outlet_id == '')
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="col-md-12 control-label">Outlet</label>
                                    <div class="col-md-12">
                                        {!! Form::select('outlet_id',$outlets,null,array('id' => 'rest_id','class'=>'select2 form-control','required')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="control-label">{{ trans('Cancellation.Reason Of Cancellation') }}</label>
                                @if($action=='add')
                                    <div class="col-md-12">
                                        <textarea name="reason" id="reason" rows="4" cols="60"></textarea>
                                    </div>
                                @endif
                                @if($action=='edit')
                                    <div class="col-md-12">
                                        <textarea name="reason" id="reason" rows="4" cols="60">@if(isset($cancel->reason_of_cancellation)){{$cancel->reason_of_cancellation}}@endif</textarea>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <div class="col-md-8">
                            <button id="submit" class="btn btn-success primary-btn" type="Submit">{{ trans('Cancellation.Submit') }}</button>
                            <button class="btn btn-danger primary-btn" id="reset" type="reset">{{ trans('Cancellation.Reset') }}</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script type="text/javascript">

        function capitalize(textboxid, str) {
            // string with alteast one character
            if (str && str.length >= 1)
            {
                var Char = str.charAt(0);
                var remainingStr = str.slice(1);
                str = zChar.toUpperCase() + remainingStr;
            }
            document.getElementById(textboxid).value = str;
        }

        $(document).ready(function()
        {

            $("#cancel_reason").validate({
                rules: {
                    "reason": {
                        required: true
                    },"rest_id": {
                        required: true
                    }
                },
                messages: {
                    "reason": {
                        required: "Reason is required."
                    },"rest_id": {
                        required: "Outlet is required."
                    }
                }
            })
        });

        $('#submit').click(function() {
            $("#cancel_reason").valid();  // This is not working and is not validating the form

        });
        $('#reset').click(function(){
            $("#reason").text("");
        });
    </script>
@stop





