@extends('partials.default')
@section('pageHeader-left')
    Add Terms And Conditions
@stop

@section('pageHeader-right')
    <a href="/termsandcondition" class="btn btn-primary"><i class="fa fa-backward"></i>&nbsp;Back</a>
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

    @include('termsandcondition.form')


@stop

