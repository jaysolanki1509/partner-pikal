<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');

?>
@extends('partials.default')
@section('pageHeader-left')
    Cash
@stop
@section('pageHeader-right')
    <a href="/expense/pending" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
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
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong>There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    @if($action=='edit')
                        {!! Form::model($expense,array('route' => array('expenseApp.update',$expense->id),'id'=>"cash_form", 'method' => 'patch', 'class' => 'form-horizontal material-form j-forms')) !!}
                    @else
                        <form class="form-horizontal material-form j-forms" role="form" method="POST" id="cash_form" action="{{ url('/expense') }}">
                    @endif
                            {!! Form::hidden('type', 'cash', ["id"=>"type"]) !!}
                            {!! Form::hidden('exp_category', '0', ["id"=>"exp_category"]) !!}


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label class="col-md-12 control-label">User</label>
                                        <div class="col-md-12 form">

                                            <select name="expense_by" id="expense_by" class = 'form-control' required>
                                                @for ( $i=0; $i < sizeof($user_list['user_id']); $i++ )
                                                    @if( isset($expense->expense_by) && $user_list['user_id'][$i] == $expense->expense_by )
                                                        <option value="{{$user_list['user_id'][$i]}}" selected>{{ $user_list['user_name'][$i] }}</option>
                                                    @else
                                                        <option value="{{$user_list['user_id'][$i]}}">{{ $user_list['user_name'][$i] }}</option>
                                                    @endif
                                                @endfor
                                            </select>
                                            <div class="expense_by-error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label class="col-md-12 control-label">Date</label>
                                        <div class="col-md-12 form">
                                            {!! Form::text('expense_date', isset($expense->expense_date)?$expense->expense_date:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control','required','placeholder'=>"Select Date","id"=>"expense_date","readonly"=>"readonly"]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label class="col-md-12 control-label">Amount</label>
                                        <div class="col-md-12">
                                            {!! Form::input('number','amount' ,null, ['id'=>'amount','placeholder'=>'0','class' => 'form-control','id'=>'qty','min' => 0 ,'decimals' => 2,'symbol' => '₹'] ) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label class="col-md-12 control-label">Description</label>
                                        <div class="col-md-12">
                                            {!! Form::textarea('description',null,['id'=>'description','class'=>'form-control','required','placeholder'=>"Description about Expense", 'rows' => 3, 'cols' => 20]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-footer">
                                <div class="col-md-8">
                                    @if($action == 'edit')
                                        <button class="btn btn-success primary-btn" type="submit">Update</button>
                                        <a class="btn btn-danger primary-btn" href="/expense/pending">Cancel</a>
                                    @else
                                        <button class="btn btn-success primary-btn" type="submit">Submit</button>
                                        <button class="btn btn-danger primary-btn" type="Reset" value="Reset" >Reset</button>
                                    @endif
                                </div>
                            </div>

                        </form>
                </div>
            </div>
        </div>
    </div>

@stop

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


            $('#expense_by').select2({
                placeholder: 'Select User'
            });


            $('#expense_date').DatePicker({
                format: "yyyy-mm-dd",

                autoclose: true,
                todayHighlight: true
            });


            $("#cash_form").validate({
                rules: {
                    "amount": {
                        required: true
                    },
                    "expense_date": {
                        required: true
                    },
                    "description": {
                        required: true
                    },
                    "expense_by": {
                        required: true
                    }
                },
                messages: {

                    "amount": {
                        required: "*Amount Must Be Required"
                    },
                    "expense_date": {
                        required: "*Date Must Be Required"
                    },
                    "description": {
                        required: "*Description Must Be Required"
                    },
                    "expense_by": {
                        required: "*User Must be selected"
                    }
                },
                errorPlacement: function (error,element) {
                    console.log(error);
                    if(element.attr('name') == 'expense_by'){
                        error.appendTo('.expense_by-error');
                    }else {
                        error.insertAfter(element);
                    }
                }
            });
        });

    </script>
@stop