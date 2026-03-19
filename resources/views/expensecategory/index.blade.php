@extends('partials.default')
@section('pageHeader-left')
    Expense Category
@stop

@section('pageHeader-right')
    <a href="/add-expense-category" title="Add new Category" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp; Expense Category</a>
    <a href="/expenseApp" title="Add new Expense" class="btn btn-primary"><i class="fa fa-plus"></i> Expense</a>
@stop

@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        @if ( isset($expense_category) && sizeof($expense_category) > 0 )
                            <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="PrinterTable">
                                <thead>
                                    <th>Expense Category Name</th>
                                    <th data-sort-ignore="true">Action</th>
                                </thead>

                                <tbody>
                                    @if ( isset($expense_category) && sizeof($expense_category) > 0 )
                                        @foreach($expense_category as $category)
                                            <tr class="odd gradeX">
                                                <td>{!! $category->name or '' !!}</td>
                                                <td>
                                                    <a href="/expense-category/{!! $category->id !!}/edit" title="Edit">
                                                        <span class="zmdi zmdi-edit"></span>
                                                    </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <a href="#" onclick="warn('{!! $category->id !!}')" title="Delete">
                                                        <span class="zmdi zmdi-close"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        @else
                            <label id="no_data">No Expense Categories found.</label>
                        @endif
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
            $('#BankTable').DataTable({
            });
        });
        function warn(id) {
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Expense Category Details!",
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
                        text : "Your Expense Category Details has been removed.",
                        type : "success"
                    },function() {
                        var route = "/expense-category/"+id+"/destroy";
                        window.location.replace(route);
                    });
                } else {
                    swal("Cancelled", "Your Expense Category Details are safe :)", "error");
                }
            });
        }
    </script>
@stop