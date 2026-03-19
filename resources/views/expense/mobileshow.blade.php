@extends('app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default res-font">
                <div class="panel-heading res-font">
                    <a href="/expenseApp">Back</a>
                </div>

                <div class="panel-body">
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

                    <div class="col-md-12">&nbsp;</div>

                    {{--<div class="col-md-12">--}}
                        {{--<div class="col-md-6 form">--}}
                            <table class="table table-striped table-bordered table-hover res-font" id="StatusTable">
                                <thead>
                                <th>Expense By</th>
                                <th>Amount (Rs.)</th>
                                <th>Description</th>
                                <th>Expense Date</th>
                                <th>Expense For</th>
                                <th>Verify</th>
                                </thead>

                                <tbody>
                                @foreach($expense as $exp)
                                    <tr class="odd gradeX">
                                        <td>{!! $exp->user_name !!}</td>
                                        <td>{!! $exp->amount !!} </td>
                                        <td>{!! $exp->description !!}</td>
                                        <td>{!! $exp->expense_date !!}</td>
                                        <td>{!! $exp->name !!}</td>
                                        <td class="text-center"><a href="/expense/verify/{!! $exp->id !!}" data-info=""><i class="glyphicon glyphicon-arrow-right">Verify</i>
                                            </a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{--</div>--}}
                        {{--</div>--}}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#StatusTable').DataTable({
            responsive: true,
            pageLength: 100
        });
    });
</script>
<style>
    .table.table-striped.table-bordered.table-hover.res-font {
        display: inline-block;
        overflow-y: scroll;
        width: 100%;
    }
</style>

@endsection