@extends('partials.default')
@section('pageHeader-left')
    Bank Details
@stop

@section('pageHeader-right')
    <a href="/banks/create" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Bank</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="BankTable">
                            <thead>
                            <tr>
                                <th>Bank Name</th>
                                <th data-hide="phone">Account No.</th>
                                <th>Account Type</th>
                                <th data-hide="phone">IFSC Code</th>
                                <th data-hide="phone">Address</th>
                                <th data-sort-ignore="true">Action</th>
                            </tr>
                            </thead>
                            @if ( isset($banks) && sizeof($banks) > 0 )

                                <tbody>
                                    @if ( isset($banks) && sizeof($banks) > 0 )
                                        @foreach($banks as $bank)

                                            <tr class="odd gradeX">
                                                <td>{!! $bank->bank_name or '' !!}</td>
                                                <td>{!! $bank->acc_no or '' !!}</td>
                                                <td>{!! $bank->acc_type or '' !!}</td>
                                                <td>{!! $bank->bank_ifsc or '' !!}</td>
                                                <td>{!! $bank->bank_address or '' !!}</td>
                                                <td>
                                                    <a href="#" onclick="del('{!! $bank->id !!}')">
                                                        <span class="zmdi zmdi-close"></span>
                                                    </a>
                                                </td>
                                            </tr>

                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot class="hide-if-no-paging">
                                <tr>
                                    <td colspan="6" class="footable-visible">
                                        <div class="pagination pagination-centered"></div>
                                    </td>
                                </tr>
                                </tfoot>

                            @else
                                <tr>
                                    <td colspan="6">No bank details found.</td>
                                </tr>
                            @endif

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
        });

        function del(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Bank Details!",
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
                        text : "Your Bank Details has been removed.",
                        type : "success"
                    },function() {
                        var route = "/banks/"+id+"/destroy";
                        window.location.replace(route);
                    });
                } else {
                    swal("Cancelled", "Your Bank Details are safe :)", "error");
                }
            });
        }

    </script>
@stop