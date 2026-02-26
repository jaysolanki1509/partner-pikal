<?php
        use App\Owner;
?>
@extends('partials.default')
@section('pageHeader-left')
    Requested Item By You
@stop
@section('pageHeader-right')
    <a href="/requestItem/create" class="btn btn-primary">Request</a>
@stop
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong>There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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

    @if($items==null)

        <div class="row">
            <div class="col-md-12">
                <div class="widget-wrap">
                    <div class="widget-header block-header clearfix j-forms">
                        <label class="control-label">No Requests found.</label>
                    </div>
                </div>
            </div>

    @endif


    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">

                @if(isset($items) && sizeof($items)>0)
                    <div class="col-md-12">
                        <button id="delete" onclick="deleteAll()" class="btn btn-primary pull-right">Delete Request</button>
                    </div>

                    @foreach($items as $item)

                        <div class="widget-header block-header clearfix ">
                            <div class="col-md-6">
                                <?php $user_name = Owner::where('id',$item->owner_to)->first(); ?>
                                <label class="control-label">User To:-</label> &nbsp; {!! $user_name->user_name !!}

                            </div>

                            <?php
                            $categories = \App\ItemRequest::Join('menus','menus.id','=','item_request.what_item_id')
                                    ->Join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                    ->select('item_request.id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id', 'menus.menu_title_id', 'menus.item', 'menu_titles.title')
                                    ->groupBy('menu_titles.title')
                                    ->where('item_request.owner_by','=',$user_id)
                                    ->where('item_request.owner_to','=',$item->owner_to)
                                    ->where('item_request.satisfied','=',"No")->get();
                            ?>


                            @foreach($categories as $category)
                                <div class="col-md-12">
                                    <label class="control-label">Category:-</label>&nbsp;  {!! $category->title !!}
                                </div>

                                <?php
                                $requests = \App\ItemRequest::Join('menus','menus.id','=','item_request.what_item_id')
                                        ->Join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                        ->Join('locations','locations.id','=','item_request.location_for')
                                        ->select('item_request.id as req_id', 'item_request.unit_id as req_unit_id', 'locations.name as loc_name','item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id', 'menus.menu_title_id', 'menus.item', 'menu_titles.title')
                                        ->where('item_request.owner_by','=',$user_id)
                                        ->where('item_request.owner_to','=',$item->owner_to)
                                        ->where('menus.menu_title_id','=',$category->menu_title_id)
                                        ->where('item_request.satisfied','=',"No")->get();
                                ?>

                                <div class="widget-container">
                                    <div class="widget-content">
                                        <table class="table table-striped" id="StatusTable">
                                            <thead>
                                                <th>
                                                    <input id="all" onchange="checkAll()" type="checkbox" title="Select all"/>
                                                </th>
                                                <th>Requested Item</th>
                                                <th>Location</th>
                                                <th>Current Stock</th>
                                                <th>Requested Qty</th>
                                                <th>Requested Date</th>
                                                <th>Action</th>
                                            </thead>

                                            <tbody>
                                                @foreach($requests as $request)
                                                    <?php $username=Owner::where('id',$request->owner_to)->first();?>
                                                    <?php $unit = \App\Menu::where('id',$request->what_item_id)->first(); ?>
                                                    <tr class="odd gradeX">
                                                        <td>
                                                            <input class="checkbox" type="checkbox" name="checkboxlist" value="{{ $request->req_id }}" />
                                                        </td>
                                                        <td>{!! $request->what_item !!}</td>
                                                        <td>{!! isset($request->loc_name)?$request->loc_name:'NA' !!}</td>
                                                        <td>{!! $request->existing_qty !!} {!! \App\Unit::getUnitbyId($unit->unit_id)['name'] !!} </td>
                                                        <td>{!! $request->qty !!} {!! \App\Unit::getUnitbyId($request->req_unit_id)['name'] !!} </td>
                                                        <td>{!! $request->when !!} </td>
                                                        <td>
                                                            <a href="/requestItem/{{ $request->req_id }}/edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                                            <a class="btn btn-danger" onclick="warn(this,'{!! $request->req_id !!}')" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="block-body clearfix ">
                        <button id="delete" onclick="deleteAll()" class="btn btn-primary pull-right">Delete Request</button>
                    </div>

                @else

                    No request found.

                @endif

            </div>
        </div>
    </div>
@stop

@section('page-scripts')
    <script>
        function warn(ele,id) {

            swal({
                title: "Caution",
                text: "Do you want to remove the request?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Remove it!",
                cancelButtonText: "No!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    swal({
                        title : "Updated!",
                        text : "Request removed successfully!",
                        type : "success"
                    },function() {
                        var route = "/requestItem/"+id+"/destroy"
                        window.location.replace(route);
                    });
                } else {
                    swal("Cancelled", "Your request Details are safe :)", "error");
                }
            });

        }

        $('#all').change(function () {
            if ($(this).attr("checked")) {
                $('input:checkbox').not(this).prop('checked', this.checked);
                return;
            }else{
                $('input:checkbox').removeAttr('checked');
                return;
            }
        });

        function deleteAll() {

            swal({
                title: "Caution",
                text: "Do you want to remove the request?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Remove it!",
                cancelButtonText: "No!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var checkValues = $('input[name=checkboxlist]:checked').map(function () {
                        return $(this).val();
                    }).get();
                    if (checkValues == "") {
                        swal({
                            title: "Caution!",
                            text: "Please select atlist one request.",
                            type: "warning",
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ok.",
                            closeOnConfirm: true
                        });
                    }

                    $.ajax({
                        url: '/deleteAllRequest',
                        type: "POST",
                        data: {allreq: checkValues},
                        success: function (data) {
                            if (data = "success") {
                                swal({
                                    title: "Success!",
                                    text: "Selected Request deleted Successfully.",
                                    type: "success",
                                    confirmButtonColor: "#43C6DB",
                                    confirmButtonText: "Ok!",
                                    closeOnConfirm: true
                                }, function (isConfirm) {
                                    if (isConfirm) {
                                        var route = "/requestItem";
                                        window.location.replace(route);
                                    }
                                });

                            } else {
                                swal({
                                    title: "Caution!",
                                    text: "Error in Deleting requests.",
                                    type: "warning",
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "Ok.",
                                    closeOnConfirm: true
                                });
                            }
                        }
                    });
                } else {
                    swal("Cancelled", "Your request Details are safe :)", "error");
                }
            });
        }

    </script>
@stop