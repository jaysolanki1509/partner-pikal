<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');

?>
@extends('partials.default')
@section('pageHeader-left')
    @if($action=='edit')
        Update Staff Detail
    @else
        Add Staff Detail
    @endif
@stop

@section('pageHeader-right')
    <a href="/staffs" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    @if($action=='edit')
                        {!! Form::model($staff,['route' => array('attendance.updatestaff',$staff->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'staffForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                    @else
                        {!! Form::open(['route' => 'attendance.storestaff', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms','novalidate'=>"novalidate", 'id' => 'staffForm']) !!}
                    @endif

                    @if( isset($sess_outlet_id) && $sess_outlet_id != '')

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::label('name','Name:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('name', null, array('class' => 'form-control','id' => 'name', 'maxlength'=>'15','placeholder'=> 'Name','required')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::label('per_day','Per Day:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('per_day', null, array('class' => 'form-control','id' => 'per_day', 'maxlength'=>'4','placeholder'=> 'Per Day')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8 ">
                                    {!! Form::label('per_day_hours','Per Day Hours:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12 bootstrap-timepicker">
                                        {!! Form::text('per_day_hours', null, array('class' => 'form-control timepicker','id' => 'per_day_hours', 'placeholder'=> 'Per Day Hours','readonly')) !!}
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
                                    {!! Form::label('staff_shift_id','Shift:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('staff_shift_id',$shifts,null, array('class' => 'col-md-3 form-control','id' => 'staff_shift_id')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-footer">
                            <div class="col-md-8">
                                @if($action=='edit')
                                    <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                                    {!! HTML::decode(HTML::linkRoute('attendance.staff','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
                                @else
                                    <button name='saveContinue' id='saveContinue' class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true">Save & Exit</button>
                                    <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true" >Save & Continue</button>
                                    <button id='reset_form' class="btn btn-danger primary-btn" type="reset">Reset</button>
                                @endif
                            </div>
                        </div>

                    @else

                        <h3 style="text-align: center; color: red;">Please select outlet</h3>

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

                $('.timepicker').timepicker({
                    format: 'HH:mm:ss',
                    defaultTime: 'current',
                    pick12HourFormat: false,
                    showMeridian: false,
                    minuteStep: 1
                });


                $("#staffForm").validate({
                    rules: {
                        "name": 'required',
                        "staff_role_id":'required',
                        "staff_shift_id": 'required',
                        "per_day": {required:true,number: true,}
                    },
                    messages: {
                        "name": {
                            required: "Name is required"
                        },
                        "staff_role_id": {
                            required: "Role is required"
                        },
                        "staff_shift_id": {
                            required: "Shift is required"
                        },
                        "per_day": {
                            required: "Per day is required"
                        }
                    }
                });
            });
        </script>
    @stop

@stop



