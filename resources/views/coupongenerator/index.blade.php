<?php use App\Outlet; ?>
@extends('partials.default')
@section('pageHeader-left')
   {{ trans('Coupon.Coupons') }}
@stop
@section('pageHeader-right')
    <a href="/coupongenerator/create" class="btn btn-primary" title="Add new Coupon">
        <i class="fa fa-plus"></i>&nbsp;Coupon
    </a>
@stop

@section('page-styles')

    {!! HTML::style('/assets/css/style.datatables.css') !!}
    {!! HTML::style('/assets/css/dataTables.responsive.css') !!}

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
                                <th> {{ trans('Coupon.Coupon Code*') }}</th>
                                <th> For Outlet</th>
                                <th data-sort-ignore="true" data-hide="phone"> {{ trans('Coupon.Activated Date') }}</th>
                                <th data-sort-ignore="true" data-hide="phone"> {{ trans('Coupon.Expire Date') }}</th>
                                <th data-sort-ignore="true" data-hide="phone"> {{ trans('Coupon.Minimum Order Value') }}</th>
                                <th> {{ trans('Coupon.Percentage') }}</th>
                                <th> {{ trans('Coupon.Value') }}</th>
                                <th data-sort-ignore="true" data-hide="phone"> {{ trans('Coupon.No Of Users') }}</th>
                                <th data-sort-ignore="true"> {{ trans('Coupon.Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($coupon as $cou)
                                <tr class="odd gradeX">
                                    <td>{{$cou->coupon_code or ''}}</td>
                                    <td><?php $outlet_ids=(explode(",",$cou->outlet_ids));
                                                $outlet="";
                                              foreach($outlet_ids as $outlet_id){
                                                  $outlet.=Outlet::Outletbyid2($outlet_id)->name.", ";
                                              } echo rtrim($outlet, ", "); ?>  </td>
                                    <td>{{$cou->activated_datetime or ''}}</td>
                                    <td>{{$cou->expire_datetime or ''}}</td>
                                    <td>{{$cou->min_value or ''}}</td>
                                    <td>{{$cou->percentage or ''}}</td>
                                    <td>{{$cou->value or ''}}</td>
                                    <td>{{$cou->no_of_users or ''}}</td>
                                    <td><a href="/coupongenerator/{{$cou->id or '' }}/edit" title="Edit">
                                            <span class="zmdi zmdi-edit" ></span>
                                        </a>&nbsp;&nbsp;&nbsp;
                                        <a href="#" onclick="del({{$cou->id or ''}})" title="Delete">
                                            <span class="zmdi zmdi-close" ></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
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
        })
        function del(id) {
            if(id!='') {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this Coupon Details!",
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
                            text: "Your Coupon Details has been removed.",
                            type: "success"
                        }, function () {
                            var route = "/coupongenerator/" + id + "/destroy";
                            window.location.replace(route);
                        });
                    } else {
                        swal("Cancelled", "Your Coupon Details are safe :)", "error");
                    }
                });
            }
        }
    </script>
@stop