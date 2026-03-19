<?php $date = \Carbon\Carbon::now()->format("1995-m-d"); ?>
<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">
                @if($action=='edit')
                    {!! Form::model($guests,['route' => array('guest-source.update',$guests->id), 'method' => 'patch', 'id' => 'guestsForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                @else
                    {!! Form::open(['route' => 'guest-source.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms', 'id' => 'guestsForm']) !!}
                @endif

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-1">
                            {!! Form::label('guest_id','Guest Id*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('guest_id', null, array('readonly','class' => 'col-md-3 form-control','id' => 'guest_id')) !!}
                            </div>
                        </div>

                        <div class="col-md-1">
                            {!! Form::label('salutation_id','Salutation*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::select('salutation_id',$salutations, null, array('rows'=>4,'class' => 'col-md-3 form-control','required')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            {!! Form::label('first_name','First Name*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('first_name', null, array('class' => 'col-md-3 form-control','id' => 'first_name','place_holder' => 'First Name')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            {!! Form::label('second_name','Second Name*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('second_name', null, array('class' => 'col-md-3 form-control','id' => 'second_name','place_holder' => 'Second Name')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-1">
                            {!! Form::label('gender','Gender*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::select('gender', ["male"=>"Male","female"=>"Female"],null, array('class' => 'col-md-3 form-control','id' => 'gender')) !!}
                            </div>
                        </div>

                        <div class="col-md-2">
                            {!! Form::label('dob','Birth-date:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('dob', $date, array('class' => 'col-md-3 form-control','id' => 'dob','place_holder' => 'Birth-date',"readonly"=>"readonly")) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            {!! Form::label('email','Email:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('email', null, array('class' => 'col-md-3 form-control','id' => 'email','place_holder' => 'Email')) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            {!! Form::label('mobile','Mobile*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('mobile', null, array('class' => 'col-md-3 form-control','id' => 'mobile','place_holder' => 'Mobile')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">

                        <div class="col-md-5">
                            {!! Form::label('id_proof','Id Proof*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('id_proof', null, array('class' => 'col-md-3 form-control','id' => 'id_proof','place_holder' => 'ID Proof')) !!}
                            </div>
                        </div>

                        <div class="col-md-5">
                            {!! Form::label('id_number','ID Number:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('id_number', null, array('class' => 'col-md-3 form-control','id' => 'id_number','place_holder' => 'ID Number')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <div class="col-md-10">
                        @if($action=='edit')
                            <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                            {!! HTML::decode(HTML::linkRoute('guest-source.index','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
                        @else
                            <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn" type="Submit" value="true" >Save & Exit</button>
                            <button name='saveContinue' id='saveContinue' class="btn btn-success primary-btn" type="Submit" value="true">Save & Continue</button>
                            <button id='reset_form' class="btn btn-danger primary-btn" type="reset">Reset</button>
                        @endif
                    </div>
                </div>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script src="/assets/js/new/lib/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {
            $('#dob').DatePicker({
                format: "yyyy-mm-dd"
            });

            $("#guestSourceForm").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "description": {
                        required: true
                    }
                },
                messages: {
                    "name": {
                        required: "*Name is required"
                    },
                    "description": {
                        required: "*Description is required"
                    }
                }

            })

        })
    </script>
@stop