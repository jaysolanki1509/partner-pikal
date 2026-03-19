@extends('partials.default')


    <div id="menu" class="tab-pane">
        <div class="mb30"></div>
    </div>
    </div>
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
        @include('Outlettypes.form')
    </div>
    <script type="text/javascript">






    </script>
@stop

