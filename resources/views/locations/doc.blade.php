@extends('partials.default')
@section('pageHeader-left')
    Document Sign Request
@stop

@section('pageHeader-right')

@stop

@section('content')

    @if(Session::has('success') || Session::has('error'))
        @if( Session::has('success') )
            <span style="text-align: center"><h3>Document sent successfully.</h3></span>
        @else
            <span style="text-align: center">Error in document sending. Please try again later.</span>
        @endif
    @else
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-container">
                    <div class="widget-content">

                        {!! Form::open(['route' => 'docusign.generate', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms','novalidate'=>"novalidate", 'id' => 'docuForm']) !!}


                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::label('name','Name*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('name', null, array('class' => 'col-md-3 form-control','id' => 'name', 'placeholder'=> 'Name','required')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::label('email','Email*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('email', null, array('class' => 'col-md-3 form-control','id' => 'email', 'placeholder'=> 'Email','required')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::label('document','Document*:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('document', 'Document.pdf', array('class' => 'col-md-3 form-control','id' => 'document', 'placeholder'=> 'Document','readonly')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-footer">
                            <div class="col-md-8">
                                <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true" >Send Document</button>

                            </div>
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    @endif


@stop

