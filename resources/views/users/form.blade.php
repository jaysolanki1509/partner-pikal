<?php

    use App\Roles;
    use App\Owner;
    use App\State;
    use App\Country;

    use Illuminate\Support\Facades\Session;
    $sess_outlet_id = Session::get('outlet_session');

?>
<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">
                @if($action=='add')
                    <form class="form-horizontal material-form j-forms" role="form" method="POST" id="user_form" novalidate="novalidate" action="{{ url('/users') }}" files="true"
                    enctype="multipart/form-data" xmlns="http://www.w3.org/1999/html">
                @else
                    {!! Form::model($owner,array('id'=>'user_form','route' => array('users.update',$owner->id), 'method' => 'patch', 'class' => 'form-horizontal material-form j-forms')) !!}
                @endif

                    <input type="hidden"  name="_token" value="{{ csrf_token() }}">

                    @if( !isset($sess_outlet_id) || $sess_outlet_id == '')
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="outlet" class="col-md-12 control-label"> Select Outlets: </label>
                                    <div class="col-md-12">
                                        {!! Form::select('outlet_id',$outlet_id,$outlet_select,array('id' => 'outlet_id','style'=>'width:100%;','class'=>'form-control select2' )) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="uname" class="col-md-12 control-label">Username*</label>
                                <div class="col-md-12">
                                    {!! Form::text('user_name',null, array('id'=>'user_name', 'class'=>'form-control', 'maxlength'=>"15",'placeholder'=> 'Please Enter Valid User Name','required')) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md-12 control-label">Email</label>
                                <div class="col-md-12">
                                    {!! Form::text('email',null, array('id'=>'email','class'=>'form-control','placeholder'=> 'Email-Id')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($action=='add')
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="col-md-12 control-label">Password*</label>
                                    <div class="col-md-12">
                                        <input type="password" class="form-control" minlength="6" name="password" id="password" placeholder="Password">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-md-12 control-label">Confirm Password*</label>
                                    <div class="col-md-12">
                                        <input type="password" class="form-control" minlength="6" name="confirm_password" id="confirm_password" value="{{ Input::old('confirm_password') }}" placeholder="Confirm Password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-md-12 control-label">Contact Number*</label>
                                <div class="col-md-12">
                                    {!! Form::input('number','contact_no' ,null, ['class' => 'form-control', 'placeholder'=> 'Contact Number', 'required','id'=>'contact_no'] ) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md-12 control-label">User Identifier</label>
                                <div class="col-md-12">
                                        {!! Form::input('number','user_identifier', isset($owner->user_identifier)?$owner->user_identifier:null,array('class' => 'form-control','min' => 1,'max' => 9, 'id' => 'user_identifier')) !!}
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="col-md-12 control-label">Role</label>
                                <div class="col-md-12">
                                    {!! Form::select('roles',$roles,isset($owner->role_id)?$owner->role_id:null,array('class' => 'select2 form-control')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-md-12 control-label">State</label>
                                <div class="col-md-12 ">
                                    {!! Form::select('states',$states,isset($owner->state)?$owner->state:null,array('class' => 'select2 form-control','id'=>'state')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-md-12 control-label">{{ trans('Restaurant_Form.City') }}</label>
                                <div class="col-md-12 ">
                                    {!! Form::select('cities',$cities,isset($owner->city)?$owner->city:null,array('class' => 'select2 form-control' ,'id'=>'city')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="col-md-12">
                                    <div class="inline-group">
                                        <label class="checkbox">

                                            @if($action=='add')
                                                <?php $check = false;?>
                                            @else
                                                <?php
                                                    if ( $owner->web_login == 1 )
                                                        $check = true;
                                                    else
                                                        $check = false;
                                                ?>
                                            @endif

                                            {!! Form::checkbox('web_login','1',$check,['id'=>'web_login']) !!}
                                            <i></i>Enable Web Login

                                        </label>
                                    </div>
                                </div>
                            </div>
                            @if($action != 'add')
                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <div class="inline-group pull-right">
                                            <a href="#" onclick="openModal()"> Change/Show Password </a>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-footer">
                        <div class="col-md-8">
                            <button class="btn btn-success primary-btn" id="Submit" novalidate="novalidate" type="Submit">{{ trans('Restaurant_Form.Submit') }}</button>
                            <input class="btn btn-danger primary-btn" name="reset" id="reset" type="reset">
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div id="changePassword" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change/Show Password</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-10">
                            <label class="col-md-12 control-label">Password*</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control" name="password" id="password1" placeholder="Password">
                                <input type="text" class="hide form-control" minlength="6" name="owner_id" id="owner_id" value="{{isset($owner->id)?$owner->id:""}}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="eye">
                                    <i class="fa fa-eye" id="onlyeye"></i>
                                </button>
                            </div>
                        </div>
                        <div style="clear: both;"></div>
                        <div class="col-md-10">
                            <label class="col-md-12 control-label">Confirm Password*</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control" minlength="6" name="confirm_password" id="confirm_password1" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="changePassword()">Change Password</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script type="text/javascript">

        function changePassword() {
            var password = $("#password1").val();
            if(password.length < 6){
                showError("Password length must be more then 6.","Changing Password");
                return;
            }
            var owner_id = $("#owner_id").val();
            var confirm_pass = $("#confirm_password1").val();
            if(password.trim() == ""){
                document.getElementById("password1").style.borderColor = "red";
                showError("Password cannot be blank.","Changing Password");
                return;
            }else if(confirm_pass.trim() == ""){
                document.getElementById("confirm_password1").style.borderColor = "red";
                showError("Confirm Password cannot be blank.","Changing Password");
                return;
            }else if(password.trim() != confirm_pass.trim()){
                document.getElementById("password1").style.borderColor = "red";
                document.getElementById("confirm_password1").style.borderColor = "red";
                showError("Password and Confirm Password cannot be blank.","Changing Password");
                return;
            }else{

                $.ajax({
                    url: '/passwordchange',
                    type: "POST",
                    data: {password: password, owner_id: owner_id},
                    success: function (data) {
                        if ( data == 'success') {
                            swal({
                                title: "Success!",
                                text: "Password Updated Successfully",
                                type: "success",
                                confirmButtonColor: "#43C6DB",
                                confirmButtonText: "Ok!",
                                closeOnConfirm: true
                            });
                        }else{
                            showError("Error in password update.", "Ok!")
                        }
                    }
                });

            }
        }

        function showError(error_msg, btn_txt) {
            swal({
                title: "Caution!",
                text: error_msg,
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: btn_txt,
                closeOnConfirm: true
            });
        }

        function openModal() {
            document.getElementById("password1").style.borderColor = "gray";
            document.getElementById("confirm_password1").style.borderColor = "gray";
            document.getElementById("password1").setAttribute("value","");
            document.getElementById("confirm_password1").setAttribute("value","");
            $('#changePassword').modal('show');
        }

        function show() {
            var p = document.getElementById('password1');
            p.setAttribute('type', 'text');
            var btn = document.getElementById('onlyeye');
            btn.setAttribute('class','fa fa-eye-slash');
        }

        function hide() {
            var p = document.getElementById('password1');
            p.setAttribute('type', 'password');
            var btn = document.getElementById('onlyeye');
            btn.setAttribute('class','fa fa-eye');
        }

        var pwShown = 0;

        document.getElementById("eye").addEventListener("click", function () {
            if (pwShown == 0) {
                pwShown = 1;
                show();
            } else {
                pwShown = 0;
                hide();
            }
        }, false);

        $(document).ready(function() {
            $('#outlet_id').select2({
                placeholder: 'Select Outlet'
            });

            $('#order_receive').select2({
                placeholder: 'Select Order Type'
            });

            $("#user_form").validate({
                rules: {
                    "user_name": {
                        required: true
                    },
                    "contact_no": {
                        required:true,
                        min: 999999999,
                        max: 999999999999
                    },
                    "password":{
                        required: true
                    },
                    "confirm_password":{
                        required: true,
                        equalTo: "#password"
                    },
                    "for_outlet": {
                        required: true
                    },
                    "contact_no": {
                        required: true
                    },
                    "user_identifier":{
                        min: 1,
                        max: 9
                    }
                },
                messages: {
                    "user_name": {
                        required: "User Name is required"
                    }, "password": {
                        required: "Password is required"
                    }, "confirm_password": {
                        required: "Confirm Password is required",
                        equalTo: "Password and Confirm Password must be same."
                    },
                    "contact_no": {
                        required:"Contact number is required and should be numeric.",
                        max: "Enter valid contact number",
                        min: "Enter valid contact number"
                    },
                    "for_outlet": {
                        required: "For Outlet is required"
                    },
                    "user_identifier": {
                        min: "Minimum 1 allowed",
                        max: "Maximum 9 allowed"
                    }
                }
            })
        });
        $('#Submit').click(function() {
            $("#user_form").valid();  // This is not working and is not validating the form
        });

        $('#reset').click(function() {
            $('#user_name').attr("value","");
            $('#email').attr("value","");
            $('#contact_no').attr("value","");
            $('#user_identifier').attr("value","");
            $('#web_login').attr("checked",false);

        })


    </script>
@stop


