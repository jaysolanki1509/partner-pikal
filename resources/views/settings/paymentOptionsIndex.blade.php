@extends('partials.default')
@section('pageHeader-left')
    Payment Option Details
@stop

@section('pageHeader-right')
    <a href="/payment-option/create" class="btn btn-primary" title="Add new Payment Option">
        <i class="fa fa-plus"> Payment Option</i>
    </a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="paymentOptionTable">
                            <thead>
                                <th style="display: none">id</th>
                                <th>Payment Option</th>
                                <th>Without Source</th>
                                <th data-sort-ignore="true">Action</th>
                            </thead>

                            <tbody>
                                @if(isset($options) && sizeof($options) > 0 )
                                    @foreach($options as $opt)

                                        <tr class="odd gradeX">
                                            <td style="display: none">{!! $opt->id !!}</td>
                                            <td>{!! $opt->name !!}</td>
                                            <td>@if($opt->without_source == 1 )
                                                    Yes
                                                @else
                                                    No
                                                @endif
                                            </td>
                                            <td>
                                                <a href="/payment-option/{!! $opt->id !!}/edit" title="Edit">
                                                    <span class="zmdi zmdi-edit" ></span>
                                                </a>
                                            </td>
                                        </tr>

                                    @endforeach
                                @endif
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

            $('#paymentOptionTable').DataTable({
            });
        });

    </script>
@stop