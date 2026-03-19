<?php
use App\Menu;
use App\Unit;
?>

@extends('partials.default')

@section('pageHeader-left')
@section('pageHeader-left')
    {{ trans('RoleIndex.Role') }}
@stop
@section('pageHeader-right')
    <a href="/map_permissions" class="btn btn-primary">Map Permissions</a>
@stop
@section('content')
    <form class="form-horizontal" role="form" method="POST" id="Submit" novalidate="novalidate" action="{{ url('/role') }}" files="true"
          enctype="multipart/form-data" xmlns="http://www.w3.org/1999/html">
        {{--<script src="js/jquery.min.js"></script>--}}
        {{--<script src="js/jquery.validate.min.js"> </script>;--}}
        {{--{!! Form::open(['route' =>'Outlet.store', 'method' => 'patch', 'class' => 'autoValidate', 'files'=> true]) !!}--}}

        <input type="hidden"  name="_token" value="{{ csrf_token() }}">


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('RoleIndex.User') }}</label>
            </div>
            <div class="col-md-4 form">
                    <select class="form-control" name="user_id" >
                        <option selected >{{ trans('RoleIndex.Select User') }}</option>
                        @foreach($owner as $owner)
                            <option value="{{$owner->id or ''}}">{{$owner->user_name or ''}}</option>
                        @endforeach

                    </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('RoleIndex.Role') }}</label>
            </div>
            <div class="col-md-4 form">
                <select class="form-control" name="role_id">
                    <option selected >{{ trans('RoleIndex.Select Role') }}</option>
                    @foreach($role as $role)
                        <option value="{{$role->id or ''}}">{{$role->name or ''}}</option>
                    @endforeach
                </select>
            </div>
        </div>



        <div class="col-md-12 form">
            <div class="col-md-3 form"></div>
            <div class="col-md-9 form">
                <button class="btn btn-primary mr5"  id="Submit" novalidate="novalidate" type="Submit" >{{ trans('RoleIndex.Submit') }}</button>
                <button class="btn btn-default" type="reset">{{ trans('RoleIndex.Reset') }}</button>
            </div>
        </div>
        </div>
    </form>
    @stop





