@extends('partials.default')
@section('pageHeader-left')
    @if($action=="add")
        Add Item
    @elseif($action == 'show')
        Item Details - {!! ucwords(strtolower($item->item)) !!}
    @else
        Edit Item
    @endif
@stop
@section('pageHeader-right')
    {{--<a href="/menutitle" style="margin-top: -20px!important; margin-right:5px; float:right;" class="btn btn-primary">Edit Category</a>--}}
    <a href="/menu" class="btn btn-primary"><i class="fa fa-backward"></i>&nbsp;Back</a>
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

    @if($action == 'show')
        @include('menus.show')
    @else
        @include('menus.form')
    @endif

@stop
