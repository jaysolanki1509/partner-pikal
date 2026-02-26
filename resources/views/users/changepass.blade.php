@extends('partials.default')
@section('pageHeader-left')
Change Password
@stop
@section('content')

    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
                {!! Session::get('success') !!}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
                {!! Session::get('error') !!}
        </div>
    @endif
<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">

                {!! Form::open(array('url'=>'passwordchange', 'class'=>'form-horizontal material-form j-forms','id'=>'changepass')) !!}

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="control-label">Old Password</label>
                            <div class="col-md-12">
                                {!! Form::password('password',array('class'=>'form-control', 'placeholder'=>'Old Password','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="control-label">New Password</label>
                            <div class="col-md-12">
                                {!! Form::password('new_password',array('class'=>'form-control', 'placeholder'=>'New Password','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="control-label">Confirm New Password</label>
                            <div class="col-md-12">
                                {!! Form::password('confirm_new_password',array('class'=>'form-control', 'placeholder'=>'Confirm New Password','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <div class="col-md-8">
                        {!! Form::submit('Change Password', array('class'=>'btn btn-success primary-btn')) !!}
                    </div>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

@stop