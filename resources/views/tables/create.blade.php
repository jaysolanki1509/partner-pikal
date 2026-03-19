@extends('partials.default')
@section('pageHeader-left')
    @if($action == 'add')
        Add {{ $order_lable }} Details
    @elseif($action == 'edit')
        Edit {{ $order_lable }} Details
    @endif
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

        @include("tables.form")

@stop