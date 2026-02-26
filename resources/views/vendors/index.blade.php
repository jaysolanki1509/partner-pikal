<?php use App\Outlet;
use App\MenuTitle;
use App\Menu;
use App\Unit;
?>

@extends('partials.default')
@section('pageHeader-left')
    Vendor Details
@stop

@section('pageHeader-right')
    <a href="/vendor/create" class="btn btn-primary"><i class="fa fa-plus"></i> Vendor</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap material-table-widget">
                <div class="widget-container margin-top-0">
                    <div class="widget-content">

                        <div class="table-filter-header">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <span class="tfh-label">Search: </span>
                                            <input class="form-control" id="filter" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table class="table foo-data-table foo-data-table-filterable" data-filter="#filter" data-filter-text-only="true" id="VendorTable" data-page-size="100" data-limit-navigation="4">
                            <thead>
                                <th>Name</th>
                                <th data-hide="phone">Type</th>
                                <th>Contact Number</th>
                                <th>GST Number</th>
                                <th data-hide="phone">Contact Person</th>
                                <th data-sort-ignore="true">Action</th>
                            </thead>

                            <tbody>
                                @foreach($vendors as $vendor)

                                    <tr>
                                        <td>{!! $vendor->name or '' !!}</td>
                                        <td>{!! $vendor->type or '' !!}</td>
                                        <td>{!! $vendor->contact_number or '' !!}</td>
                                        <td>{!! $vendor->vendor_gst or '' !!}</td>
                                        <td>{!! $vendor->contact_person or '' !!}</td>
                                        <td>
                                            <a href="/vendor/{!! $vendor->id !!}/edit"><span class="zmdi zmdi-edit"></span></a>&nbsp;&nbsp;&nbsp;
                                            <a href="#" onclick="del('{!! $vendor->id !!}')" ><span class="zmdi zmdi-close"></span></a>
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
        $(document).ready(function() {

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

            $("#VendorTable").footable({
                phone:767,
                tablet:1024
            }).bind('footable_filtering', function(e) {
                var selected = $('.filter-status').find(':selected').text();
                if (selected && selected.length > 0) {
                    e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                    e.clear = !e.filter;
                }
            });
        });

        function del(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Vendor Details!",
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
                        text : "Your Vendor Details has been removed.",
                        type : "success"
                    },function() {
                        var route = "/vendor/"+id+"/destroy";
                        window.location.replace(route);
                    });
                } else {
                    swal("Cancelled", "Your Vendor Details are safe :)", "error");
                }
            });

        }

    </script>
@stop