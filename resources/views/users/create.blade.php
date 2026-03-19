@extends('partials.default')
@section('pageHeader-left')
    @if($action=='add')
        Add User
    @else
        Edit User
    @endif
@stop
@section('pageHeader-right')
    <a href="/users" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
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

    <div class="row">
        @include('users.form')
    </div>
@stop

