<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');

?>
@extends('partials.default')
@section('pageHeader-left')
    @if($action=='edit')
        Update Staffing Detail
    @else
        Add Staffing Detail
    @endif
@stop

@section('pageHeader-right')
    <a href="/staffing" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    @if($action=='edit')
                        {!! Form::model($staffing,['route' => array('attendance.updatestaffing',$staffing->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'staffingForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                    @else
                        {!! Form::open(['route' => 'attendance.storestaffing', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms','novalidate'=>"novalidate", 'id' => 'staffingForm']) !!}
                    @endif

                        @if( isset($sess_outlet_id) && $sess_outlet_id != '')

                            {!! Form::hidden('outlet_id',$sess_outlet_id, array('class' => 'col-md-3 form-control','id' => 'outlet_id')) !!}
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        {!! Form::label('staff_shift_id','Shift:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            {!! Form::select('staff_shift_id',$shifts,null, array('class' => 'form-control','id' => 'staff_shift_id')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        {!! Form::label('staff_role_id','Role:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            {!! Form::select('staff_role_id',$roles,null, array('class' => 'form-control','id' => 'staff_role_id')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        {!! Form::label('qty','Qty:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            {!! Form::text('qty', null, array('class' => 'form-control','id' => 'qty', 'placeholder'=> 'Qty')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="clear: both"></div>


                            <div class="form-footer">
                                <div class="col-md-8">
                                    @if($action=='edit')
                                        <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                                        {!! HTML::decode(HTML::linkRoute('attendance.staffing','Cancel', array(), array('class'=>'btn btn-danger primary-btn'))) !!}
                                    @else
                                        <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true" >Save & Exit</button>
                                        <button name='saveContinue' id='saveContinue' class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true">Save & Continue</button>
                                        <button id='reset_form' class="btn btn-danger primary-btn" type="reset">Reset</button>
                                    @endif
                                </div>
                            </div>

                        @else

                            <h3 style="text-align: center; color: red;" >Please select outlet</h3>

                        @endif

                        {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>



    @section('page-scripts')
        <script src="/assets/js/new/lib/jquery.validate.js"></script>
        <script type="text/javascript">

            $(document).ready(function() {

                @if(Session::has('success'))
                    successErrorMessage('{{ Session::get('success') }}','success');
                @endif
                @if(Session::has('error'))
                    successErrorMessage('{{ Session::get('error') }}','error');
                @endif

                @if( $sess_outlet_id == '' )
                        $('#main_filter').focus();
                @endif

                $("#staffingForm").validate({
                    rules: {
                        "staff_role_id":'required',
                        "staff_shift_id": 'required',
                        "qty": 'required'
                    },
                    messages: {
                        "staff_role_id": {
                            required: "Role is required"
                        },
                        "staff_shift_id": {
                            required: "Shift is required"
                        },
                        "qty": {
                            required: "Qty is required"
                        }
                    }

                })


            })
        </script>
    @stop
@stop



