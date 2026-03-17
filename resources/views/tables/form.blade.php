<?php

use Illuminate\Support\Facades\Session;
$sess_outlet_id = Session::get('outlet_session');

?>
hello {{$result->id}}
@section('pageHeader-right')
    <a href="/tables" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">

                <form id="table_form" class="form-horizontal material-form j-forms">

                    <div class="error">
                        <label id="error" style="color: red"></label>
                    </div>

                    @if($action == 'edit')
                        {!! Form::hidden('table_id',isset($result->id)?$result->id:null,array('id' => 'table_id')) !!}
                    @endif

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="col-md-12 control-label">{{ $order_lable }} Number</label>
                                <div class="col-md-12">
                                    {!! Form::text('table_no',isset($result->table_no)?$result->table_no:null, array('class'=>'form-control','maxlength'=>3,'id' => 'table_no', 'placeholder'=> 'Enter Table Number')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="col-md-12 control-label">Number Of Person</label>
                                <div class="col-md-12">
                                    {!! Form::input('number','no_of_person',isset($result->no_of_person)?$result->no_of_person:null, array('class'=>'form-control','min'=>0,'id' => 'no_of_person', 'placeholder'=> 'Enter Number Of Person')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="table_level_id" class="col-md-12 control-label">{{ $order_lable }} level</label>
                                <div class="col-md-12">
                                    {!! Form::select('table_level_id',$table_level,isset($result->table_level_id)?$result->table_level_id:0, ['class' => 'select2 form-control','required', 'id' => 'table_level_id']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="shape" class="col-md-12 control-label">Shape of {{ $order_lable }}</label>
                                <div class="col-md-12">
                                    {!! Form::select('shape',$shape,isset($result->shape)?$result->shape:null, ['class' => 'select2 form-control','required', 'id' => 'shape']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <div class="col-md-8">

                            @if($action=="add")
                                <a class="btn btn-success primary-btn" id="submitexit">Save and Exit</a>
                                <a class="btn btn-success primary-btn" id="submit">Save and Continue</a>
                            @else
                                <a class="btn btn-success primary-btn" style="position: relative" id="update">Update </a>
                            @endif
                            <a class="btn btn-danger primary-btn" href="/tables" id="cancel">Cancel </a>

                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            $('#outlet_id').select2({
                placeholder: 'Select Outlet'
            });
            $('#shape').select2({
                placeholder: 'Select Shape'
            });

        });
        $('#submitexit').click(function(){
            addtable('exit');
        });

        $('#submit').click(function(){
            addtable('save');
        });

        function addtable(btn) {
            $('#error').text('');
            if($("#table_form").valid()){

                var outlet_id = $('#outlet_id').val();
                var table_no = $('#table_no').val();
                var shape = $('#shape').val();
                var no_of_person = $('#no_of_person').val();
                var table_level_id = $('#table_level_id').val();

                $.ajax({
                    dataType: 'JSON',
                    url: '/tables/create',
                    type: "POST",
                    data: {table_level_id:table_level_id,outlet_id:outlet_id,table_no:table_no,shape:shape,no_of_person:no_of_person},
                    success: function (data) {

                        var message = data['message'];

                        if(data['status'] == 'error'){
                            $('#error').text(message);
                        }else{
                            successErrorMessage(message,'success');

                            if(btn == 'exit'){
                                var route = "/tables";
                                window.location.replace(route);
                            }else{
                                $('#table_no').val('');
                                $('#table_no').val(parseInt(table_no)+1);
                            }
                        }
                    }
                });
            }
        }

        $('#update').click(function(){
            $('#error').text('');
            if($("#table_form").valid()){

                var outlet_id = $('#outlet_id').val();
                var table_no = $('#table_no').val();
                var shape = $('#shape').val();
                var no_of_person = $('#no_of_person').val();
                var table_id = $('#table_id').val();
                var table_level_id = $('#table_level_id').val();

                $.ajax({
                    dataType: 'JSON',
                    url: '/tables/'+table_id+'/update',
                    type: "POST",
                    data: {table_level_id:table_level_id,outlet_id:outlet_id,table_no:table_no,shape:shape,no_of_person:no_of_person},
                    success: function (data) {

                        var message = data['message'];

                        if(data['status'] == 'error'){
                            $('#error').text(message);
                        }else{
                            successErrorMessage(message,'success');

                            var route = "/tables"
                            window.location.replace(route);
                        }
                    }
                });
            }
        });

        $(document).ready(function() {

            $("#table_form").validate({
                rules: {
                    "outlet_id": {
                        required: true
                    },
                    "table_no": {
                        required : true
                    },
                    "no_of_person": {
                        required : true,
                        number: true
                    }/*,
                     "shape": {
                     required : true
                     }*/
                },
                messages: {
                    "outlet_id": {
                        required: "Outlet is require."
                    },
                    "table_no": {
                        required : "{{ $order_lable }} no is require."
                    },
                    "no_of_person": {
                        required : "No of person is require.",
                        number : "Only Number is allowed"
                    }/*,
                     "shape": {
                     required : "Shape is require."
                     }*/
                }
            });

        });

    </script>

@stop