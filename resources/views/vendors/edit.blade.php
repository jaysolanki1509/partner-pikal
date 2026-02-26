@extends('partials.default')
@section('pageHeader-left')
    Update Vendor
@stop

@section('pageHeader-right')
    <a href="/vendor" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="row well">
        @include('vendors.form')
    </div>

@stop

