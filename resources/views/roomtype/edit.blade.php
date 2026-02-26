@extends('partials.default')
@section('pageHeader-left')
    Update Room Type
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

    <div class="row well">
        @include('roomtype.form')
    </div>

@stop

