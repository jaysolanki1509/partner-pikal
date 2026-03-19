@extends('partials.default')
@section('pageHeader-left')
    Update Feedback Question
@stop

@section('pageHeader-right')
    <a href="/feedback-question" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    @include('feedbackquestion.form')


@stop

