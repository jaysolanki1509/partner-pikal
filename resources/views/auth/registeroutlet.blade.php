@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('Auth.Register') }}</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>{{ trans('Auth.Whoops!') }}</strong> {{ trans('Auth.There were some problems with your input.') }}<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" id="register_form" method="POST" novalidate="novalidate" action="{{  url('/signup') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">



                            <div class="form-group">
                                <label class="col-md-4 control-label">{{ trans('Auth.User Name') }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="user_name" value="{{ Input::old('user_name') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">{{ trans('Auth.E-Mail') }}</label>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{{ Input::old('email') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">{{ trans('Auth.Contact No') }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="contact_no" value="{{ Input::old('contact_no') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">{{ trans('Auth.Outlet Name') }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="outlet_name" value="{{ Input::old('outlet_name') }}">
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-md-4 control-label" for="country">{{ trans('Auth.Country') }}</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="countries">
                                        <option selected >{{ trans('Auth.Select Country') }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id or ''}}">{{$country->name or ''}}</option>
                                        @endforeach
                                    </select>

                                    <span class="help-inline"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="state">{{ trans('Auth.State') }}</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="states">
                                        <option selected >{{ trans('Auth.Select State') }}</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id or ''}}">{{$state->name or ''}}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-inline"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="city">{{ trans('Auth.City') }}</label>
                                <div class="col-md-6">

                                    <select class="form-control" name="cities">
                                        <option selected >{{ trans('Auth.Select City') }}</option>
                                        @foreach($cities as $city)
                                            <option  value="{{$city->id or ''}}">{{$city->name or ''}}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-inline"></span>
                                </div>
                            </div>



                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="Submit" id="Submit" novalidate="novalidate" class="btn btn-primary">
                                        {{ trans('Auth.Submit') }}
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


