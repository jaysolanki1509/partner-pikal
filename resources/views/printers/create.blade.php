@extends('partials.default')
@section('pageHeader-left')
    Add Printer Details
@stop

@section('pageHeader-right')
    <a href="/printers" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
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

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    @include('printers.form')
                </div>
            </div>
        </div>
    </div>
@stop