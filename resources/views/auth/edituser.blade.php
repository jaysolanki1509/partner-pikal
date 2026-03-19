@extends('partials.default')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('Auth.Change User Language') }}</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>{{ trans('Auth.Whoops!') }}</strong>{{ trans('Auth.There were some problems with your input.') }}<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" id="edituser_form" method="POST" novalidate="novalidate" action="{{  url('/user/updateUser/'.Auth::user()->id) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">






                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-4 control-label">Contact No</label>--}}
                                {{--<div class="col-md-6">--}}
                                    {{--<input type="text" class="form-control" name="contact_no" value="@if(isset($users->contact_no)){{$users->contact_no}}@endif">--}}
                                {{--</div>--}}
                            {{--</div>--}}


                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-4 control-label" for="Gender">Gender</label>--}}
                                {{--<div class="col-md-6">--}}
                                    {{--<select id="itemType_id" name="gender" class="form-control">--}}
                                        {{--<option value="Male">Male</option>--}}
                                        {{--<option value="Female">Female</option>--}}
                                    {{--</select>--}}
                                    {{--<span class="help-inline"></span>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="language">{{ trans('Auth.Language') }}</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="language">
                                        @foreach($language as $language)
                                            @if($users->lang == $language->code)
                                                <option value="{{$language->code or ''}}" selected>{{$language->name or ''}}</option>
                                            @else
                                                <option value="{{$language->code or ''}}">{{$language->name or ''}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="help-inline"></span>
                                </div>
                            </div>

                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-4 control-label" for="state">State</label>--}}
                                {{--<div class="col-md-6">--}}
                                    {{--<select id="itemType_id" name="state" class="form-control">--}}
                                        {{--<option value="Gujarat">Gujarat</option>--}}
                                        {{--<option value="Maharashtra">Maharashtra</option>--}}
                                        {{--<option value="Karnatka">Karnatka</option>--}}
                                    {{--</select>--}}
                                    {{--<span class="help-inline"></span>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-4 control-label" for="city">City</label>--}}
                                {{--<div class="col-md-6">--}}

                                    {{--<select id="itemType_id" name="city" class="form-control">--}}
                                        {{--<option value="Ahmedabad">Ahmedabad</option>--}}
                                        {{--<option value="Surat">Surat</option>--}}
                                        {{--<option value="Mumbai">Mumbai</option>--}}
                                        {{--<option value="Banglore">Banglore</option>--}}
                                    {{--</select>--}}
                                    {{--<span class="help-inline"></span>--}}
                                {{--</div>--}}
                            {{--</div>--}}



                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="Submit" id="Submit" novalidate="novalidate" class="btn btn-primary">
                                        {{ trans('Auth.Update') }}
                                    </button>
                                    <button type="Reset" class="btn btn-primary">
                                        {{ trans('Auth.Reset') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


