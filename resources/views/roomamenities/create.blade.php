@extends('partials.default')
@section('pageHeader-left')
Add Room Amenity
@stop

@section('pageHeader-right')
    <a href="/room-amenity  " class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')
@if(Session::has('error'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
    {{ Session::get('error') }}
</div>
@endif
@if(Session::has('success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
    {{ Session::get('success') }}
</div>
@endif

    @include('roomamenities.form')

@stop

