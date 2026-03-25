
@extends('partials.default')
@section('pageHeader-left')
    Expenes Category
@stop

@section('pageHeader-right')
    <a href="/expense-category-index" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
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
    @if(isset($success) &&  ($success))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ $success }}
        </div>
    @endif

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    @if($action=="add")
                        <form action='/expensecategory/store' method='post' class='form-horizontal material-form j-forms' id='expense_form' >
                    @else
                        <form action='/expensecategory/update' method='post' class='form-horizontal material-form j-forms' id='expense_form' >
                        {!! Form::hidden('category_id', $category_id, array('class' => 'col-md-3 form-control','id' => 'category_id')) !!}
                    @endif

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::label('category_name','Expense Category:', array('class' => 'col-md-12 control-label')) !!}
                                    @if($action=="add")
                                        <div class="col-md-12">
                                            {!! Form::text('category_name', null, array('class' => 'form-control','id' => 'category_name', 'placeholder'=> 'Expense Category Name')) !!}
                                        </div>
                                    @else
                                        <div class="col-md-12">
                                            {!! Form::text('category_name', $expense_category->name, array('class' => 'form-control','id' => 'category_name', 'placeholder'=> 'Expense Category Name')) !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-footer">
                            <div class="col-md-8">
                                @if($action=="add")
                                    <div class="col-md-12">
                                        <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn" type="submit" novalidate="novalidate" class="btn btn-primary"> Save & Exit</button>
                                        <button name='saveContinue' id='saveContinue' class="btn btn-success primary-btn" novalidate="novalidate" type="Submit" value="true">Save & Continue</button>
                                        <button id='reset_form' class="btn btn-danger primary-btn" type="reset">Reset</button>
                                    </div>
                                @else
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success primary-btn"> Update </button>
                                        <a href="/expense-category-index" class="btn btn-danger primary-btn"> Cancel </a>
                                    </div>
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
    <script>
        $(document).ready(function() {

            $("#expense_form").validate({
                rules: {
                    "category_name": {
                        required: true
                    }
                },
                messages: {
                    "category_name": {
                        required: "Expense Category name is required."
                    }
                }

            });

        });

    </script>
@stop