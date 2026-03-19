<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');

?>
@extends('partials.default')
@section('pageHeader-left')
    Expense
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
                        {!! Form::model($expense,array('route' => array('expenseApp.update',$expense->id),'id'=>"expense_form", 'method' => 'patch', 'class' => 'form-horizontal material-form j-forms')) !!}
                    @else
                        <form class="form-horizontal material-form j-forms" role="form" method="POST" id="expense_form" action="{{ url('/expense') }}">
                    @endif
                        {!! Form::hidden('type', 'expense', ["id"=>"type"]) !!}


                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="col-md-12 control-label">Expense By</label>
                                    <div class="col-md-12">

                                        <select name="expense_by" id="expense_by" class = 'form-control' required>
                                            @for($i=0;$i<sizeof($user_list['user_id']);$i++)
                                                @if( isset($expense->expense_by) && $user_list['user_id'][$i] == $expense->expense_by)
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
                                    <label class="col-md-12 control-label">Expense Date</label>
                                    <div class="col-md-12 form">
                                        {!! Form::text('expense_date', isset($expense->expense_date)?$expense->expense_date:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control','required','placeholder'=>"Select Date","id"=>"expense_date","readonly"=>"readonly"]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="col-md-12 control-label">Expense Category</label>
                                    <div class="col-md-12">
                                        @if($action=='add')
                                            {!! Form::select('exp_category', $exp_category, null, ['class' => 'form-control','required', 'id' => 'exp_category']) !!}
                                        @else
                                            <select name="exp_category" id="exp_category" class = 'form-control' required>
                                                @foreach($exp_category as $key=>$val)
                                                    @if($key == $expense->category_id)
                                                        <option value="{{ $key }}" selected>{{ $val }}</option>
                                                    @else
                                                        <option value="{{ $key }}">{{ $val }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                        <div class="exp_category-error"></div>
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

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="col-md-12 control-label">Status</label>
                                    <div class="col-md-12">
                                        @if($action=='add')
                                            {!! Form::select('status', $status, null, ['class' => 'form-control','required', 'id' => 'status']) !!}
                                        @else
                                            <select name="status" id="status" class = 'form-control' required>
                                                @for($i=1;$i<=sizeof($status);$i++)
                                                    @if($status[$i] == $expense->status)
                                                        <option value="{{ $status[$i] }}" selected>{{ $status[$i] }}</option>
                                                    @else
                                                        <option value="{{$status[$i] }}">{{ $status[$i] }}</option>
                                                    @endif
                                                @endfor
                                            </select>
                                        @endif
                                        <div class="status-error"></div>
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

        $('#status').select2({
            placeholder: 'Select Status'
        });
        $('#expense_by').select2({
            placeholder: 'Select User'
        });
        $('#exp_category').select2({
            placeholder: 'Select Expense Category'
        });

        $('#expense_date').DatePicker({
            format: "yyyy-mm-dd",
            orientation: "auto",
            autoclose: true,
            todayHighlight: true
        });

        $("#expense_form").validate({
            rules: {
                "amount": {
                    required: true
                },
                "expense_by": {
                    required: true
                },
                "expense_date": {
                    required: true
                },
                "description": {
                    required: true
                },
                "exp_category": {
                    required: true
                }
            },
            messages: {

                "amount": {
                    required: "*Amount Must Be Required"
                },
                "expense_date": {
                    required: "*Expense Date Must Be Required"
                },
                "description": {
                    required: "*Description Must Be Required"
                },
                "exp_category": {
                    required: "*Expense Category Must Be Required"
                },
                "expense_by": {
                    required: "*User Must be selected"
                }
            },
            errorPlacement: function (error,element) {
                console.log(error);
                if(element.attr('name') == 'exp_category'){
                    error.appendTo('.exp_category-error');
                }else if(element.attr('name') == 'expense_by'){
                    error.appendTo('.expense_by-error');
                }else if(element.attr('name') == 'status'){
                    error.appendTo('.status-error');
                }else {
                    error.insertAfter(element);
                }
            }
        });
    });

</script>
@stop