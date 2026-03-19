@extends('partials.default')
@section('pageHeader-left')
    Stock Transfer
@stop

@section('pageHeader-right')
    <a href="/stocks" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
    <a onclick="$('#transfer_content').show();$('#CategoriesTable').remove();" class="btn btn-primary">
        <i class="fa fa-refresh" aria-hidden="true"></i>
        Refresh
    </a>
@stop

@section('content')
    <style>
        .error { color : red }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <form class="form-horizontal" role="form" method="POST" id="stock-transfer-form">
                    <div class="widget-header block-header clearfix j-forms">
                        <div class="form-group col-md-4">

                            <div class="col-md-12">
                                <lable>From <i class="fa fa-map-marker fa-6"></i></lable>
                            </div>
                            <div class="col-md-12">
                                <select class="form-control" id="from_location" name="from_location">
                                    <option value="">Select Location</option>
                                    @if( isset($locations) && sizeof($locations) > 0 )
                                        @foreach( $locations as $loc )
                                            <option value="{!! $loc->id !!}">{!! $loc->name !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="from_location-error"></div>
                            </div>

                        </div>

                        <div class="form-group col-md-4">

                            <div class="col-md-12">
                                <lable>To <i class="fa fa-map-marker fa-6"></i></lable>
                            </div>
                            <div class="col-md-12">
                                <select class="form-control" id="to_location" name="to_location">
                                    <option value="">Select Location</option>
                                    @if( isset($locations) && sizeof($locations) > 0 )
                                        @foreach( $locations as $loc )
                                            <option value="{!! $loc->id !!}">{!! $loc->name !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="to_location-error"></div>
                            </div>

                        </div>

                        <div class="form-group col-md-4">

                            <div class="col-md-12">
                                <lable>Transfer <i class="fa fa-calendar fa-6"></i></lable>
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('transfer_date',null, ['class' => 'form-control', 'id'=>'transfer_date', "readonly"=>"readonly"]) !!}
                            </div>

                        </div>

                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="content" id="content" style="overflow-x: auto;">

                                <div class="col-md-12 form" id="transfer_content">
                                    <table class="table table-striped table-bordered table-hover" id="cat_table">
                                        <thead>
                                            <th class="text-center">Categories</th>
                                            <th class="text-center">Select</th>
                                        </thead>

                                        <tbody>
                                            {{--<td>{!! strtoupper('All Categories') !!} </td>
                                            <td class="text-center"><a href="javascript:void(0)" onclick="getItem(this);" data-info="all" title="Get all items"><i class="glyphicon glyphicon-arrow-right"></i>
                                                </a></td>--}}
                                            @foreach($category as $cate)
                                                <tr class="odd gradeX">
                                                    <td>{!! $cate->title !!}</td>
                                                    {{--<td><a href="/delete_cat/{{$cate->id}}" title="Delete" class="btn">X</a>{!! $cate->title !!}</td>
                                                    <td><button type="submit" id="Submit" novalidate="novalidate" value="{!! $cate->id !!}"><i class="glyphicon glyphicon-arrow-right"></i></button></td>--}}
                                                    <td class="text-center">
                                                        <a href="javascript:void(0)" onclick="getItem(this);" data-info="{!! $cate->id !!}" title="Get Item">
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
                </form>
            </div>
        </div>
    </div>

@stop
@section('page-scripts')
    <script>

        $(document).ready(function() {

            $('#transfer_date').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });

            $('#stock-transfer-form select').select2();

        });
        function getItem(id)
        {
            $('.error').remove();
            var cate_id= id.getAttribute('data-info');
            var from_loc_id = $('#from_location').val();
            var to_loc_id = $('#to_location').val();
            var trans_date = $('#transfer_date').val();

            var error = false;
            if ( from_loc_id == '' ){
                $('<p class="error">Please select from location</p>').appendTo('.from_location-error');
                error = true;
            }
            if ( to_loc_id == '' ){
                $('<p class="error">Please select to location</p>').appendTo('.to_location-error');
                error = true;
            }
            if ( to_loc_id == from_loc_id && from_loc_id != ''){
                $('<p class="error">From and to location can not be same.</p>').appendTo('.to_location-error');
                error = true;
            }
            if ( trans_date == '' ){
                $('<p class="error">Please select date</p>').insertAfter('#transfer_date');
                error = true;
            }

            if ( error == true ) {
                return false;
            }

            $('#loader').css('display','block');

                $.ajax({
                    url:'/get-transfer-items',
                    data:'cat_id='+cate_id+'&from_loc_id='+from_loc_id,

                    success:function(data){
                        $('#transfer_content').hide();
                        $('#content').append(data);
                        $('#content select').select2();
                    }
                });

        }

        function submitTransfer(event) {

            var values = $('#stock-transfer-form').serialize();
            $('#sumbmit').text('Transfer...');
            $('#sumbmit').prop('disabled',true);
            console.log(values);
            $.ajax({
                url:'/stock-transfer',
                data:values,
                type:'Post',
                success:function(data){

                    $('#sumbmit').text('Submit');
                    $('#sumbmit').prop('disabled',false);

                    if ( data == 'success') {
                        $('#CategoriesTable').remove();
                        $('#transfer_content').show();
                        alert('Stock has been tranfered successfully.');
                    } else {
                        alert('There is some error occurred, please check your inputs.');
                    }
                }
            })


        }

    </script>

@stop