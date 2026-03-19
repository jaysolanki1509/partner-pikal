

@extends('partials.default')

@section('pageHeader-left')
@section('pageHeader-left')
    Role
@stop
@section('pageHeader-right')
@stop
@section('content')
    <form class="form-horizontal" role="form" method="POST" id="Submit" novalidate="novalidate" action="{{ url('/permission') }}" files="true"
          enctype="multipart/form-data" xmlns="http://www.w3.org/1999/html">
        {{--<script src="js/jquery.min.js"></script>--}}
        {{--<script src="js/jquery.validate.min.js"> </script>;--}}
        {{--{!! Form::open(['route' =>'Outlet.store', 'method' => 'patch', 'class' => 'autoValidate', 'files'=> true]) !!}--}}

        <input type="hidden"  name="_token" value="{{ csrf_token() }}">


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">Role</label>
            </div>
            <div class="col-md-9 form">
                <select class="form-control" id="role_id" name="role_id" onChange="getselectedpermissions();">
                    <option selected >Select Role</option>
                    @foreach($role as $role)
                        <option value="{{$role->id or ''}}">{{$role->display_name or ''}}</option>
                    @endforeach

                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-9 form">
                    @foreach($permission as $permission)
                    <input id={{$permission->id}} type="checkbox" name="permission_id[]" value={{$permission->id}}>{{$permission->display_name}}<br>
                    @endforeach
            </div>
        </div>


        <div class="col-md-12 form">
            <div class="col-md-3 form"></div>
            <div class="col-md-9 form">
                <button class="btn btn-primary mr5"  id="Submit" novalidate="novalidate" type="Submit" >Submit</button>
                <button class="btn btn-default" type="reset">Reset</button>
            </div>
        </div>
        </div>
    </form>
    <script>
        function getselectedpermissions(){
            var getroleid=document.getElementById("role_id").value;
            $("input[name='permission_id[]']:checkbox").prop('checked',false);
            $.ajax({
                url: 'ajax/getselectedpermissions',
                data: "role_id=" + getroleid ,
                success: function (data) {
                    for(var j=0;j<=data.length;j++){
                          document.getElementById(data[j].permission_id).checked=true;
                    }
                }
            });
        }
    </script>
@stop





