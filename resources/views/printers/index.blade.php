
@extends('partials.default')
@section('pageHeader-left')
    Printers Details
@stop

@section('pageHeader-right')
    <a href="/printers/create" class="btn btn-primary" title="Add new Printer"><i class="fa fa-plus"></i>  Printer</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">

                    <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="PrinterTable">
                            <thead>
                                <th>Printer Name</th>
                                <th>Printer Type</th>
                                <th data-sort-ignore="true" data-hide="phone">Mac Address</th>
                                <th data-sort-ignore="true" data-hide="phone">Ip Address</th>
                                <th data-sort-ignore="true">Action</th>
                            </thead>

                            <tbody>
                                @if ( isset($printers) && sizeof($printers) > 0 )
                                    @foreach($printers as $pr)

                                        <tr class="odd gradeX">
                                            <td>{!! $pr->printer_name or '' !!}</td>
                                            <td>{!! $pr->printer_type or '' !!}</td>
                                            <td>{!! $pr->mac_address or '' !!}</td>
                                            <td>{!! $pr->printer_ip or 'NA' !!}</td>
                                            <td>
                                                <a href="/printers/{!! $pr->id !!}/edit" title="Edit">
                                                    <span class="zmdi zmdi-edit" ></span>
                                                </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a href="#" onclick="del('{!! $pr->id !!}')" title="Delete">
                                                    <span class="zmdi zmdi-close" ></span>
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
            @if(Session::has('failure'))
                successErrorMessage('{{ Session::get('failure') }}','error');
            @endif

        })

        function del(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Printer Details!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    swal({
                        title : "Deleted!",
                        text : "Your Printer Details has been removed.",
                        type : "success"
                    },function() {
                        var route = "/printers/"+id+"/destroy";
                        window.location.replace(route);
                    });
                } else {
                    swal("Cancelled", "Your Printer Details are safe :)", "error");
                }
            });

        }


    </script>
@stop