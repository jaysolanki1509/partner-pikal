@extends('partials.default')
@section('pageHeader-left')
    {{ trans('Restaurant_Index.Add Outlet') }}
@stop

@section('pageHeader-right')
	<?php $url = $_SERVER['REQUEST_URI'];
		$pos = strpos($url, "admin-outlet");
		if($pos){
	?>
		<a href="/admin-outlet" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
	<?php } else { ?>
		<a href="/outlet" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
	<?php } ?>
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
	@include('Outlets.form')
</div>

@stop

