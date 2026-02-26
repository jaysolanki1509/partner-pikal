
@extends('partials.default')
@section('pageHeader-left')
    @if($action=='edit')
        Update Staff Role
    @else
        Add Staff Role
    @endif
@stop

@section('pageHeader-right')
    <a href="/staff-roles" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">


                    @if($action=='edit')
                        {!! Form::model($role,['route' => array('attendance.updatestaffrole',$role->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'staffRoleForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                    @else
                        {!! Form::open(['route' => 'attendance.storestaffrole', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms','novalidate'=>"novalidate", 'id' => 'staffRoleForm']) !!}
                    @endif

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::label('name','Name:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('name', null, array('class' => 'form-control','id' => 'name', 'maxlength'=>"15",'placeholder'=> 'Name','required')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-footer">
                            <div class="col-md-8">
                                @if($action=='edit')
                                    <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                                    {!! HTML::decode(HTML::linkRoute('location.index','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
                                @else
                                    <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true" >Save & Exit</button>
                                    <button name='saveContinue' id='saveContinue' class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true">Save & Continue</button>
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
        <script type="text/javascript">

            $(document).ready(function() {

                @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
                @endif
                @if(Session::has('error'))
                    successErrorMessage('{{ Session::get('error') }}','error');
                @endif

                    $("#staffRoleForm").validate({
                    rules: {
                        "name": {
                            required: true
                        }
                    },
                    messages: {
                        "name": {
                            required: "*Name is required"
                        }
                    }

                });

            });
        </script>
    @stop

@stop



