@extends('partials.default')
@section('pageHeader-left')
    {{ trans('Coupon.Add Coupons') }}
@stop

@section('pageHeader-right')
    <a href="/coupongenerator" class="btn btn-primary"><i class="fa fa-backward"></i>&nbsp;Back</a>
@stop

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('error') }}
        </div>
    @endif

    @include('coupongenerator.form')

@stop

