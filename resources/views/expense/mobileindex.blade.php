@extends('app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default res-font">
                <div class="panel-heading res-font">
                    <a href="#">.</a>
                    <a href="/expense/pending" style="float: right">Pending Expenses</a>
                </div>

                <div class="panel-body">
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

                    <div class="col-md-12">&nbsp;</div>

                    <form class="form-horizontal" role="form" method="POST" id="Submit" novalidate="novalidate" action="{{ url('/expense') }}" files="true" enctype="multipart/form-data">
                        <!--{!! Form::hidden('created_date', isset($created_date)?$fromdate:\Carbon\Carbon::now()->format("Y/m/d"), ['class' => 'form-control','placeholder'=>"Select Date","id"=>"created_date","readonly"=>"readonly"]) !!}-->

                        <div class="col-md-12">
                            <div class="col-md-3 form">
                                <label class="rest">Expense For</label>
                            </div>

                            <div class="col-md-4 form">
                                {!! Form::select('expense_for', $Outlet, null, ['onchange' => 'outletBy(this.value)','class' => 'form-control', 'id' => 'expense_for']) !!}
                            </div>
                        </div>

                        <div class="col-md-12 field">
                            <div class="col-md-3 form">
                                <label class="rest">Expense By</label>
                            </div>

                            <div class="col-md-4 form">
                                {!! Form::select('expense_by', array(''=>'Select User'), null, ['class' => 'form-control','required', 'id' => 'expense_by']) !!}
                            </div>
                        </div>

                        <div class="col-md-12 field">
                            <div class="col-md-3 form">
                                <label class="rest">Expense Date</label>
                            </div>

                            <div class="col-md-4 form">
                                {!! Form::text('expense_date', isset($expense_date)?$expense_date:\Carbon\Carbon::now()->format("Y/m/d"), ['class' => 'form-control','placeholder'=>"Select Date","id"=>"expense_date","readonly"=>"readonly"]) !!}
                            </div>
                        </div>

                        <div class="col-md-12 field">
                            <div class="col-md-3 form">
                                <label class="rest">Amount</label>
                            </div>
                            <div class="col-md-4 form">
                                {!! Form::input('number','amount' ,0.00, ['class' => 'form-control','id'=>'qty','min' => 0 ,'decimals' => 2,'symbol' => '₹'] ); !!}
                            </div>
                        </div>

                        <div class="col-md-12 field">
                            <div class="col-md-3 form">
                                <label class="rest">Description</label>
                            </div>

                            <div class="col-md-6 form">
                                {!! Form::textarea('description',null,['class'=>'form-control', 'placeholder'=>"Description about Expense", 'rows' => 3, 'cols' => 20]) !!}
                            </div>
                        </div>

                        <div class="col-md-12 field">
                            <div class="col-md-3 form">
                                <label class="rest">Submit To</label>
                            </div>
                            <div class="col-md-4 form">
                                {!! Form::select('auth_user', array(''=>'Select Authorised User'), null, ['class' => 'form-control','required', 'id' => 'auth_user']) !!}
                            </div>
                        </div>

                        <div class="col-md-12 field">
                            <div class="col-md-3 form"></div>
                            <div class="col-md-6 form">
                                <button class="btn btn-primary mr5" type="Submit">Submit</button>
                                <button class="btn btn-default" type="Reset">Reset</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#expense_date').datepicker({
            dateFormat: "yy/mm/dd",
            maxDate: new Date,
            setdate:new Date

        });

        if($('#expense_for').val()!='')
            outletBy($('#expense_for').val());
    });

    function outletBy(outleid){
        $.ajax({
            dataType: 'JSON',
            url: '/ajax/outletby',
            type: "POST",
            data: {outlet_id : outleid},
            success: function (data) {
                var newOptions = '';
                newOptions += '<option value="">Select User</option>';
                var data_id=""+data.user_id;
                var data_name=data.user_name;
                var owner_id = data_id.split(",");
                for(var i=0; i<owner_id.length; i++) {
                    owner_id[i] = +owner_id[i];
                    newOptions += '<option value="'+owner_id[i]+'">'+data_name[i]+'</option>';
                }

                jQuery ('#expense_by')
                    .find('option')
                    .remove()
                    .end()
                    .append(newOptions);
            },
            error:function(error) {
                var newOptions = '';
                newOptions += '<option value="">Select User</option>';
                jQuery ('#expense_by')
                    .find('option')
                    .remove()
                    .end()
                    .append(newOptions);
            }

        });

        $.ajax({

            url: '/ajax/expenseTo',
            type: "POST",
            data: {outlet_id : outleid},
            success: function (data) {
                var newOptions = '';
                newOptions += '<option value="">Select Authorised User</option>';
                var data_id=""+data.user_id;
                var data_name=data.user_name;
                var owner_id = data_id.split(",");
                for(var i=0; i<owner_id.length; i++) {
                    owner_id[i] = +owner_id[i];
                    newOptions += '<option value="'+owner_id[i]+'">'+data_name[i]+'</option>';
                }
                jQuery ('#auth_user')
                    .find('option')
                    .remove()
                    .end()
                    .append(newOptions);
            },
            error:function(error) {
                var newOptions = '';
                newOptions += '<option value="">Select Authorised User</option>';
                jQuery ('#auth_user')
                    .find('option')
                    .remove()
                    .end()
                    .append(newOptions);
            }

        });

    }
</script>
<style>
    .col-md-12.field {
        margin-top: 5px;
    }
</style>

@endsection