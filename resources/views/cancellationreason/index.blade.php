<?php use App\Outlet; ?>
@extends('partials.default')
@section('pageHeader-left')
 {{ trans('Cancellation.Cancellation Reason') }}
@stop
@section('pageHeader-right')
    <a href="/cancellationreason/create" class="btn btn-primary" title="Add new Reason"><i class="fa fa-plus"></i> Reason</a>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">
                        <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="StatusTable">
                            <thead>

                                <tr>
                                    <th>{{ trans('Cancellation.Outlet') }}</th>
                                    <th>{{ trans('Cancellation.Reason Of Cancellation') }}</th>
                                    <th data-sort-ignore="true"> {{ trans('Coupon.Action') }}</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach($cancel as $cancel)
                                    <?php $outlet=Outlet::findOutlet($cancel->outlet_id);?>
                                    <tr class="odd gradeX">
                                        <td>{{$outlet->name or ''}}</td>
                                        <td>{{$cancel->reason_of_cancellation or ''}}</td>
                                        <td>
                                            <a href="/cancellationreason/{{$cancel->id or '' }}/edit" title="Edit">
                                                <span class="zmdi zmdi-edit" ></span>
                                            </a>&nbsp;&nbsp;&nbsp;
                                            <a href="#" onclick="del({{$cancel->id or ''}})">
                                                <span class="zmdi zmdi-close" title="Delete"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="hide-if-no-paging">
                                <tr>
                                    <td colspan="6" class="footable-visible">
                                        <div class="pagination pagination-centered"></div>
                                    </td>
                                </tr>
                            </tfoot>
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
        $(document).ready(function(){
            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif
        })
        function del(id) {
            if(id!='') {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this Reason!",
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
                            title: "Deleted!",
                            text: "Your Reason has been removed.",
                            type: "success"
                        }, function () {
                            var route = "/cancellationreason/" + id + "/destroy";
                            window.location.replace(route);
                        });
                    } else {
                        swal("Cancelled", "Your Reason Details are safe :)", "error");
                    }
                });
            }
        }
    </script>
@stop