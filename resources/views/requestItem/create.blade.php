@extends('partials.default')
@section('pageHeader-left')
    Request Item
@stop
@section('pageHeader-right')
    <style>
        .error { color : red }
    </style>
    <a href="/requestItem/create" class="btn btn-primary back_button hide"><i class="fa fa-backward"></i> Back</a>
    <!--<button type="button" style="float:right; margin-left: 5px" class="btn btn-primary" data-toggle="modal" data-target="#attachmenu" data-whatever="">Add Categories</button> -->
    <div class="modal fade" id="attachmenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel"></h4>
                </div>
                <div class="modal-body ">

                    <div class="form-group">
                        <div class="input-group input-group-lg mb15 wid-full">
                            <span class="input-group-addon" style="display:none" ></span>
                            {!! Form::open(array('url'=>'category/add','method'=>'POST')) !!}
                            <div class="control-group">
                                <div class="controls">
                                    {!! Form::label('new_category','New Category :',array('url'=>'menu/importmenuexcel','method'=>'POST', 'files'=>true)) !!}
                                    {!! Form::text('name_category','',array('url'=>'menu/importmenuexcel','method'=>'POST', 'files'=>true)) !!}
                                    <p class="errors">{!!$errors->first('images')!!}</p>
                                    @if(Session::has('error'))
                                        <p class="errors">{!! Session::get('error') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('Restaurant_Show.Close') }}</button>
                    <!--<button type="button" class="btn btn-primary" data-dismiss="modal">{{ trans('Restaurant_Show.Submit') }}</button>-->
                    {!! Form::submit('Add', array('class'=>'btn btn-primary')) !!}
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>
    <a href="/requestItem" class="btn btn-primary" title="Pending Request">Pending Request</a>
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

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-container clearfix j-forms">
                    <div class="col-md-3 form-group">
                        {!! Form::label('Request_to','Request to:') !!}
                        <div class="form-group">
                            @if(Session::has('owner_to'))
                                {!! Form::select('owner_id', $owners, Session::get('owner_to'), ['class' => 'select2 form-control', 'id' => 'owner_id', 'required']) !!}
                            @else
                                {!! Form::select('owner_id', $owners, null, ['class' => 'select2 form-control', 'id' => 'owner_id', 'required']) !!}
                            @endif
                        </div>
                        <div class="owner_id-error"></div>
                    </div>

                    <div class="col-md-3 form-group">
                        {!! Form::label('for_location','For Location:') !!}
                        <div class="form-group">
                            @if(Session::has('location_id'))
                                {!! Form::select('location_id', $locations, Session::get('location_id'), ['class' => 'select2 form-control', 'id' => 'location_id', 'required']) !!}
                            @else
                                {!! Form::select('location_id', $locations, null, ['class' => 'select2 form-control', 'id' => 'location_id', 'required']) !!}
                            @endif
                        </div>
                        <div class="location_id-error"></div>
                    </div>

                    <div class="col-md-3 form-group">
                        {!! Form::label('category_id','Category Name:') !!}
                        <div class="form-group">
                            <select id="category_id" class = 'select2 form-control'>
                                <option value="">Select category</option>
                                <option value="all" data-info="all">All Category</option>
                                @foreach($category as $cate)
                                    <option value="{!! $cate->id !!}" data-info="{!! $cate->id !!}">{!! $cate->title !!}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="request_category-error"></div>
                    </div>

                    <div class="col-md-2 form-group">
                        {!! Form::label('request_date','Request Date:') !!}
                        <div class="form-group">
                            @if(Session::has('req_date'))
                                {!! Form::text('request_date',Session::get('req_date'), ['class' => 'form-control', 'id' => 'request_date', "readonly"=>"readonly"]) !!}
                            @else
                                {!! Form::text('request_date',null, ['class' => 'form-control', 'id' => 'request_date', "readonly"=>"readonly"]) !!}
                            @endif
                        </div>
                        <div class="request_date-error"></div>
                    </div>

                    <div class="col-md-1 form-group">
                        {!! Form::label('request_date','&nbsp;') !!}
                        <div class="form-group">
                            <button name="show" class="btn btn-success primary-btn pull-left" id="showbtn" onclick="getItem();">Show</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

            <div class="widget-container">
                <div class="widget-content">

                    {{--<table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="CategoriesTable">

                        <thead>
                            <th>Categories</th>
                            <th data-sort-ignore="true" class="text-center">Select</th>
                        </thead>

                        <tbody>
                            <td>{!! strtoupper('All Categories') !!} </td>
                            <td class="text-center"><a href="javascript:void(0)" onclick="getItem(this);" data-info="all"><i class="glyphicon glyphicon-arrow-right"></i>
                                </a></td>
                            @foreach($category as $cate)
                                <tr class="odd gradeX">
                                    <td>{!! $cate->title !!}</td>
                                    --}}{{--<td><a href="/delete_cat/{{$cate->id}}" title="Delete" class="btn">X</a>{!! $cate->title !!}</td>
                                    <td><button type="submit" id="Submit" novalidate="novalidate" value="{!! $cate->id !!}"><i class="glyphicon glyphicon-arrow-right"></i></button></td>--}}{{--
                                    <td class="text-center">
                                        <a href="javascript:void(0)" onclick="getItem(this);" data-info="{!! $cate->id !!}">
                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>--}}

            </div>


        </div>
    </div>

@stop
@section('page-scripts')
    <script>

        $(document).ready(function() {
            $('#owner_id').select2({
                placeholder: 'Select Owner'
            });
            $('#location_id').select2({
                placeholder: 'Select Location'
            });
            $('#category_id').select2({
                placeholder: 'Select Category'
            });

            $('#request_date').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });

        });
        function getItem()
        {
            //alert(id.value);return;
            $('.error').remove();
            var owner_id=$('#owner_id').val();
            var cate_id=$("#category_id").val();
            var location_id = $('#location_id').val();
            var req_date = $('#request_date').val();

            var error = false;
            if ( location_id == '' ){
                $('<p class="error">Please select location</p>').appendTo('.location_id-error');
                error = true;
            }
            if ( owner_id == '' ){
                $('<p class="error">Please select user</p>').appendTo('.owner_id-error');
                error = true;
            }
            if ( req_date == '' ){
                $('<p class="error">Please select Date</p>').appendTo('.request_date-error');
                error = true;
            }
            if ( cate_id == '' ){
                $('<p class="error">Please select Category</p>').appendTo('.request_category-error');
                error = true;
            }

            if ( error == true ) {
                return false;
            }

            $('#loader').css('display','block');
            if(cate_id!='' && owner_id != '' && owner_id != "Select User") {
                $.ajax({
                    url:'/ajax/getItems',
                    data:'owner_id='+owner_id+'&cate_id='+cate_id+'&location_id='+location_id+'&req_date='+req_date,
           
                    success:function(data){

                        $(".widget-content").html('');
                        $(".widget-content").html(data);
                        $("#owner_id1").val(owner_id);
                        $("#location_id1").val(location_id);
                        $("#req_date1").val(req_date);
                        $('.back_button').removeClass('hide');

                    }
                })
            }
        }
        
        function submitClick(event) {
            var nodes = document.querySelectorAll("#CategoriesTable input[type=number]");
            var check = 'submit'
            for (i = 0; i < nodes.length; i++) {
                if(nodes[i].value < 0) {
                    nodes[i].style.borderColor = "red";
                    alert("All fields must be greater then or equal to 0");
                    check = 'nosubmit'
                }
            }
            if(check == 'submit'){
                $( "#requestItem" ).submit();
            }
        }

    </script>

@stop