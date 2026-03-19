<?php
        use App\Outlet;
        use App\Owner;
?>

@extends('partials.default')
@section('pageHeader-left')
    Bind User with Outlet
@stop

@section('pageHeader-right')
    @if(\Illuminate\Support\Facades\Auth::user()->user_name == "govind")
        <a href="/admin-outlet" class="btn btn-primary"><i class="fa fa-backward"></i> Back </a>
    @else
        <a href="/outlet" class="btn btn-primary"><i class="fa fa-backward"></i> Back </a>
    @endif
@stop

@section('content')

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

    <div class="row" id="order_filter">
        <div class="col-md-12">
            <div class="widget-wrap">
                @if($is_admin == 1)
                    <div class="widget-header block-header clearfix">
                    <form class='form-horizontal material-form j-forms' role="form" method="POST" id="bindOutlet" novalidate="novalidate" action="{{ url('/outletBind') }}" files="true" enctype="multipart/form-data">

                        <div class="form-group col">
                            <div class="form-group col-md-3">
                                <div class="col-md-6">
                                    {!! Form::label('outlet','Outlet:') !!}
                                </div>
                                <div class="col-md-12">
                                    <?php $url = $_SERVER['REQUEST_URI']; $arr = explode("/",$url);  ?>
                                    {!! Form::select('outlet_id', $select_outlets, isset($arr[2])?$arr[2]:null, ['class' => 'select2 form-control', 'id' => 'outlet_id']) !!}
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="col-md-6">
                                    {!! Form::label('user','User:') !!}
                                </div>
                                <div class="col-md-12">
                                    {!! Form::select('owner_id', $select_owners, null, ['class' => 'form-control', 'id' => 'owner_id']) !!}
                                </div>
                            </div>
                            <div class="form-group col-md-5">
                                <div class="col-md-12">
                                    {!! Form::label('user','Order Receive:') !!}
                                </div>
                                <div class="col-md-12">
                                    {!! Form::select('order_receive[]',$order_type,null,array('id'=>'order_receive','class' => 'select2 form-control','multiple')) !!}
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <div class="col-md-6">&nbsp;
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" id="Submit" novalidate="novalidate" class="btn btn-success">Bind</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                @endif
                <div class="widget-container">
                    <div class="widget-content">
                        <h3>Your Outlets:-</h3>
                        @if($is_admin == 0)
                            <label style="color: red">*For change Plese contact your admin. </label>
                        @endif
                        <table class="table table-striped table-hover" id="outletBindTable">
                            <thead>
                                <th>Outlet Name</th>
                                <th>Owner Name</th>
                                <th>Active</th>
                                <th>Order Receive</th>
                                @if($is_admin == 1)
                                    <th>Unbind</th>
                                @endif
                            </thead>
                            <tbody>
                            @foreach($outlet_mappers as $outlet_mapper)
                                <tr>
                                    <?php $outlet = Outlet::find($outlet_mapper->outlet_id) ?>
                                    <?php //$owner = Owner::ownerById($outlet_mapper->owner_id) ?>
                                    <td>{{ $outlet->name }}</td>
                                    <td>{{$outlet_mapper->user_name}}</td>
                                    <td>{{ $outlet->active }}</td>
                                    @if(isset($outlet_mapper->order_receive) && sizeof($outlet_mapper->order_receive)>0)
                                        <?php $ord_rec_arr = json_decode($outlet_mapper->order_receive); $string = ""; ?>
                                                @foreach($order_type as $key=>$value)
                                                    @foreach($ord_rec_arr as $saved_key=>$val)
                                                        @if($key == $val)
                                                            <?php $string .= $value.", "; ?>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                        <td>{{ rtrim($string,', ') }}</td>
                                    @else
                                        <td>NA</td>
                                    @endif
                                    @if($is_admin == 1)
                                        <td><a href="#" onclick="unbind(<?php echo $outlet_mapper->id; ?>)">Unbind</a></td>
                                    @endif
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
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script>

        $("#bindOutlet").submit(function (evt) {
            evt.preventDefault();
            var order_receive = $("#order_receive").val();
            var outlet_id = $("#outlet_id").val();
            var owner_id = $("#owner_id").val();

            $.ajax({
                url: '/checkOrderReceive',
                data: 'order_receive=' + order_receive + '&outlet_id=' + outlet_id + '&owner_id=' + owner_id,
                success: function (data) {
                    switch (data){
                        case "error":
                            showError("Outlet Order receive privilege is already assign to other user!", "Change Order receive!");
                            break;
                        case "error1":
                            showError("Error in parameter!", "Select user and outlet!");
                            break;
                        case "success1":
                            swal({
                                title: "Caution",
                                text: "Already mapped with user!",
                                type: "warning",
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Change User or Outlet!",
                                closeOnConfirm: false
                            }, function (isConfirm) {
                                if (isConfirm) {
                                    swal({
                                        title : "Updated!",
                                        text : "Order Receive updates successfully!",
                                        type : "success"
                                    },function() {
                                        location.reload(true);
                                    });
                                } else {
                                    swal("Cancelled", "Your settings are not changed. :)", "error");
                                }
                            });

                            break;
                        case "error3":
                            showError("No outlets mapped yet!", "Change User or Outlet!");
                            break;
                        case "success":
                            successMsg("Outlet bind successfully!", "Ok!");
                            location.reload(true);
                            break;
                    }

                }
            });
        });

        function showError(error_msg, btn_txt) {
            swal({
                title: "Caution!",
                text: error_msg,
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: btn_txt,
                closeOnConfirm: true
            });
        }

        function successMsg(success_msg, btn_txt) {
            swal({
                title: "Success!",
                text: success_msg,
                type: "success",
                confirmButtonColor: "#43C6DB",
                confirmButtonText: btn_txt,
                closeOnConfirm: true
            });
        }

        $(document).ready(function() {
            $('#outlet_id').select2({
                placeholder: 'Select an Outlet'
            });
            $('#owner_id').select2({
                placeholder: 'Select an Owner'
            });
            $('#order_receive').select2({
                placeholder: 'Select an Order Type'
            });

            $("#bindOutlet").validate({
                rules: {
                    "outlet_id": {
                        required: true
                    },
                    "owner_id": {
                        required: true
                    }
                },
                messages: {
                    "outlet_id": {
                        required: "*Outlet is required"
                    },
                    "owner_id": {
                        required: "*Owner is required"
                    }
                }
            })
        });

        function unbind(unbind_id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able access this Outlet!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    swal("Deleted!", "Your Outlet access has been removed.", "success");
                    var route = "/outletBind/"+unbind_id+"/destroy"
                    window.location.replace(route);
                } else {
                    swal("Cancelled", "Your Outlet access is safe :)", "error");
                }
            });

        }

    </script>

@stop