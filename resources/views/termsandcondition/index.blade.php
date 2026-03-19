
@extends('partials.default')
@section('pageHeader-left')
   Terms And Conditions
@stop
@section('pageHeader-right')
    <a href="/termsandcondition/create" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;T&C</a>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="termstable">
                            <thead>
                                <tr>
                                    <th  data-sort-ignore="true">Terms And Conditions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="odd gradeX">
                                    <td>{{$terms or ''}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('page-scripts')
    <script src="/assets/js/new/lib/footable.all-min.js"></script>
    <script>
        $(document).ready(function() {

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

            $('#termstable').DataTable({
            });
        });
    </script>

@stop
