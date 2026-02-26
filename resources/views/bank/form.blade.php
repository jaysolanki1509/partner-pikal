
@extends('partials.default')
@section('pageHeader-left')
    Bank Details
@stop

@section('pageHeader-right')
    <a href="/banks" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">
                    {!! Form::open(['route' => 'banks.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms','novalidate'=>"novalidate", 'id' => 'bankForm']) !!}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::label('bank_name','Bank Name*', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('bank_name', null, array('class' => 'form-control','id' => 'bank_name', 'placeholder'=> 'Bank Name')) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    {!! Form::label('acc_no','Account Number*', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('acc_no', null, array('class' => 'form-control','id' => 'acc_no', 'placeholder'=> 'Account Number')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::label('acc_type','Account Type*', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12 " >
                                        {!! Form::text('acc_type', null, array('class' => 'form-control','id' => 'acc_type', 'placeholder'=> 'Account Type')) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('bank_ifsc','IFSC:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('bank_ifsc', null, array('class' => 'form-control','id' => 'bank_ifsc', 'placeholder'=> 'IFSC')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::label('bank_address','Bank Address', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        <textarea placeholder="Bank Address" rows="5" class="form-control" id="bank_address" name="bank_address"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-footer">
                            <div class="col-md-12">
                                <button type="submit"  id="add-btn" class="btn btn-success primary-btn" >Add</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script>
        $(document).ready(function() {

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

            $("#bankForm").validate({
                rules: {
                    "bank_name": {
                        required: true
                    },
                    "acc_no": {
                        required: true
                    },
                    "acc_type": {
                        required: true
                    }
                },
                messages: {
                    "bank_name": {
                        required: "Bank Name is Required."
                    },
                    "acc_no": {
                        required: "Account Number is Required."
                    },
                    "acc_type": {
                        required: "Account Type is Required."
                    }
                }

            });
        });

    </script>
@stop