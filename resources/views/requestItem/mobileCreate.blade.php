@extends('app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading" style="position: fixed; width: 90%; z-index: 9999;">
                        <a href="/stock-request-report">Back</a>
                        <a href="/requestItem" style="float: right">Pending Request</a>
                    </div>
                            <div class="clearfix"></div>
                    <div class="panel-body" style="float: left; width: 100%; margin-top: 50px; padding: 0; ">
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

                        <div id="cates_id">
                            <div class="col-md-12">
                                <div class="col-md-3"></div>
                                <div class="col-md-2 form">
                                    {{--<label class="control-label">Outlet:-</label>--}}
                                </div>

                                {{--<div class="col-md-4 form">--}}
                                {{--{!! Form::select('outlet_id', $select_outlets, null, ['class' => 'form-control', 'id' => 'outlet_id']) !!}--}}
                                {{--@foreach($outlets as $outlet)--}}
                                {{--<input type="hidden" value="{!! $outlet->id or '' !!}" id="outlet_id" name="outlet_id">--}}
                                {{--@endforeach--}}
                                {{--</div>--}}
                            </div>

                            <div class="form-group col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        {!! Form::label('Request_to','Request to:') !!}
                                    </div>
                                    <div class="col-md-12">
                                        @if(Session::has('owner_to'))
                                            {!! Form::select('owner_id', $owners, Session::get('owner_to'), ['class' => 'form-control', 'id' => 'owner_id', 'required']) !!}
                                        @else
                                            {!! Form::select('owner_id', $owners, null, ['class' => 'form-control', 'id' => 'owner_id', 'required']) !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        {!! Form::label('for_location','For Location:') !!}
                                    </div>
                                    <div class="col-md-12">
                                        @if(Session::has('location_id'))
                                            {!! Form::select('location_id', $locations, Session::get('location_id'), ['class' => 'form-control', 'id' => 'location_id', 'required']) !!}
                                        @else
                                            {!! Form::select('location_id', $locations, null, ['class' => 'form-control', 'id' => 'location_id', 'required']) !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        {!! Form::label('request_date','Request Date:') !!}
                                    </div>
                                    <div class="col-md-12">
                                        @if(Session::has('req_date'))
                                            {!! Form::text('request_date',Session::get('req_date'), ['class' => 'form-control', 'id' => 'request_date', "readonly"=>"readonly"]) !!}
                                        @else
                                            {!! Form::text('request_date',date('Y-m-d'), ['class' => 'form-control', 'id' => 'request_date', "readonly"=>"readonly"]) !!}
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="col-md-12 form">
                                    <table class="table table-striped table-bordered table-hover" id="CategoriesTable">
                                        <thead>
                                        <th class="text-center">Categories</th>
                                        <th class="text-center">Select</th>
                                        </thead>

                                        <tbody>
                                        <td>{!! strtoupper('All Categories') !!} </td>
                                        <td class="text-center"><a href="javascript:void(0)" onclick="getItem(this);" data-info="all"><i class="glyphicon glyphicon-arrow-right"></i>
                                            </a></td>
                                        @foreach($category as $cate)
                                            <tr class="odd gradeX">
                                                <td>{!! $cate->title !!}</td>
                                                {{--<td><a href="/delete_cat/{{$cate->id}}" title="Delete" class="btn">X</a>{!! $cate->title !!}</td>
                                                <td><button type="submit" id="Submit" novalidate="novalidate" value="{!! $cate->id !!}"><i class="glyphicon glyphicon-arrow-right"></i></button></td>--}}
                                                <td class="text-center">
                                                    <a href="javascript:void(0)" onclick="getItem(this);" data-info="{!! $cate->id !!}">
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
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
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {

            $('#request_date').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date,
                setdate:new Date
            });

        });
        function getItem(id)
        {
            $('.error').remove();
            var owner_id=$('#owner_id').val();
            var cate_id=id.getAttribute('data-info');
            var location_id = $('#location_id').val();
            var req_date = $('#request_date').val();

            var error = false;
            if ( location_id == '' ){
                $('<p class="error">Please select location</p>').insertAfter('#location_id');
                error = true;
            }
            if ( owner_id == '' ){
                $('<p class="error">Please select user</p>').insertAfter('#owner_id');
                error = true;
            }

            if ( error == true ) {
                return false;
            }

            $('#loader').css('display','block');
            if(cate_id!='' && owner_id != '' && owner_id != "Select User") {
                $.ajax({
                    url:'/ajax/getItems',
                    data:'req_type=mob&owner_id='+owner_id+'&cate_id='+cate_id+'&location_id='+location_id+'&req_date='+req_date,

                    success:function(data){

                        $("#cates_id").html('');
                        $("#cates_id").html(data);
                        $("#owner_id").val(owner_id);
                        $("#location_id").val(location_id);
                        $("#req_date").val(req_date);
                    }
                })
            }
        }

    </script>


@endsection
