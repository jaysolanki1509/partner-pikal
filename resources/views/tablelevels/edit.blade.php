@extends('partials.default')
@section('pageHeader-left')
    Update Table Level
@stop

@section('pageHeader-right')
    <a href="/table-levels" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    @include('tablelevels.form')

@stop

