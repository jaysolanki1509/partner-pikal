@extends('partials.default')
@section('pageHeader-left')
   {{ trans('Cancellation.Edit Cancellation Reason') }}
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

    <div class="row well">
        @include('cancellationreason.form')
    </div>


<script>
    $('#activated_date').datepicker({
        format: "yyyy/mm/dd"
    });
    $('#expire_date').datepicker({
        format: "yyyy/mm/dd"
    });
</script>

@stop



