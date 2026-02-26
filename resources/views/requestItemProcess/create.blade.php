<?php
    use App\Owner;
?>
@extends('partials.default')
@section('pageHeader-left')
     Process Request
@stop
@section('content')
    <div id="location-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Change Location</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button id="change-btn" type="button" class="btn btn-primary" onclick="change()">Change</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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

    <div class="clearfix"></div>
        <form class="form-horizontal" role="form" method="POST" id="requestItemProcess" novalidate="novalidate" action="{{ url('/requestItemProcess') }}" files="true" enctype="multipart/form-data">
            <div class="col-md-12">
                <div class="col-md-12">
                    <?php  $count=0; ?>
                    @foreach( $item_requests as $item_request )
                        <div class="row well" >
                            <div class="col-md-3">
                                <?php $user_name = Owner::where('id',$item_request->owner_by)->first(); ?>
                                <label class="control-label">User:-</label> &nbsp; {!! $user_name->user_name !!}
                                <input type="hidden" name="user_id" id="user_id" value="{!! $user_name->id !!}" />
                            </div>
                            <div class="col-md-3">
                                {!!Form::select('location_id',$locations,null,array('class'=>'form-control','id'=>'location_id'))!!}
                            </div>
                            <div class="col-md-3">
                                {!!Form::text('satisfied_date',null,array('placeholder'=>'Satisfied Date','class'=>'form-control','id'=>'satisfied_date','readonly','style'=>'background-color:white;cursor:pointer;'))!!}
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="button" class="btn btn-success primary-btn pull-left" onclick="getProcessStockDetail()" id="next_btn">Next</button>
                                </div>
                            </div>
                            <hr class="col-md-12">
                            <?php
                            $categories = \App\ItemRequest::Join('menus','menus.id','=','item_request.what_item_id')
                                    ->Join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                    ->select('item_request.id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id', 'menus.menu_title_id', 'menus.item', 'menu_titles.title')
                                    ->groupBy('menu_titles.title')
                                    ->where('item_request.owner_to','=',$owner_id)
                                    ->where('item_request.owner_by','=',$item_request->owner_by)
                                    ->where('item_request.satisfied','=',"No")->get();
                            ?>
                            <div id="data_div">
                                @foreach($categories as $category)
                                    <div class="col-md-12">
                                        <h3 style="text-align: center;font-weight: bold;">{!! $category->title !!}</h3>
                                    </div>

                                    <?php
                                    $requests = \App\ItemRequest::Join('menus','menus.id','=','item_request.what_item_id')
                                            ->Join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                            ->Join('unit as u','u.id','=','item_request.unit_id')
                                            ->select('item_request.id as item_id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id', 'menus.menu_title_id', 'u.name as unit', 'menu_titles.title')
                                            ->where('item_request.owner_by','=',$item_request->owner_by)
                                            ->where('item_request.owner_to','=',$owner_id)
                                            ->where('menus.menu_title_id','=',$category->menu_title_id)
                                            ->where('item_request.satisfied','=',"No")->get();
                                    ?>

                                    <table id="requestitemprocess" class="table table-striped table-hover" >
                                        <thead>
                                        <th>Requested Date</th>
                                        {{--<th>Requested By</th>--}}
                                        <th>Requested Item</th>
                                        {{--<th>Existing Stock</th>--}}
                                        <th style="align-content: center;">Requested Qty</th>

                                        </thead>

                                        <tbody>

                                        @if(sizeof($requests) > 0)

                                            @foreach($requests as $request)
                                                <tr id="{!! $request->item_id !!}" data-item-id="{!! $request->what_item_id !!}">
                                                    <td>{!! $request->when !!}</td>
                                                    <td>{!! $request->what_item !!}</td>
                                                    {{--<td clasds="text-center">{!! $request->existing_qty !!} {!! $request->unit !!}</td>--}}
                                                    <td class="text-center">{!! $request->qty !!} {!! $request->unit !!}</td>

                                                </tr>
                                                <?php if($count <= $request->item_id){
                                                    $count = $request->item_id;
                                                    $count++;
                                                } ?>
                                            @endforeach

                                        @else
                                            <tr>
                                                <td>You Don't have any request.  </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>

                        </div>
                    @endforeach
                </div>
                {!! Form::hidden('count',$count,array('id'=>'count')) !!}
                {{--<div class="col-md-12">
                    <button type="submit" id="Submit" onclick="return validateForm();" novalidate="novalidate" class="btn btn-primary" style="float: right;">Submit</button>
                </div>--}}
            </div>
        </form>
    {{--</div>--}}

@stop
@section('page-scripts')
    <script>

        function change() {

            var loc_id = $('#location-modal #stk_location_id').val();
            var loc_text = $('#location-modal #stk_location_id option:selected').text();
            var avail_stock = $('#location-modal #stk_location_id option:selected').data('stock');
            var req_id = $('#location-modal #req_id').val();

            if ( loc_id == '') {
                alert('Please select location');
                return;
            }

            $('#'+req_id+' td:eq(3)').text(loc_text);
            $('#'+req_id+' td:eq(6) input.location_class').val(loc_id);
            $('#'+req_id+' td:eq(4)').text(avail_stock);

            $('#location-modal').modal('hide');

        }

        //check item stock on selected location
        function getProcessStockDetail() {

            var loc_id = $('#location_id').val();
            var satisfied_date = $('#satisfied_date').val();
            var user_id = $('#user_id').val();
            var loc_text = $('#location_id option:selected').text();

            if( loc_id == '' || satisfied_date == '' ) {
            swal({
                title: "Warning!",
                text: "please select location and satisfied date!",
                confirmButtonColor: "#DD6B55"
            });
            return;
            } else if ( loc_id == '') {
                alert('Please select location');
                return;
            } else if ( satisfied_date == '' ) {
                alert( 'Please select satisfied date');
                return;
            }

            $('#next_btn').text('Loading...');
            $('#next_btn').attr('disabled',true);

            $.ajax({
                url:'/get-process-stock-detail',
                type:'POST',
                data:'view=web&loc_id='+loc_id+'&satisfied_date='+satisfied_date+'&user_id='+user_id+'&loc_text='+loc_text,
                success:function(data){

                    $('#next_btn').text('Next');
                    $('#next_btn').attr('disabled',false);

                    $('#data_div').html(data);


                }
            })


        }

        function ProcessRequest() {

            $('#stockprocess_error').text('');
            var tot_satisfy = 0;
            var error = 0;
            $('#stockitemprocess tr').each(function() {
                var req_qty = parseFloat($(this).find('td:eq(1)').text());
                var satisfy_qty = $(this).find('td:eq(3)').find('input').val();
                if(req_qty>0)
                    tot_satisfy += parseFloat(satisfy_qty);
                if(req_qty < satisfy_qty){
                    error = 1;
                    $(this).find('td:eq(3)').find('input').css('border-color','red');
                }else{
                    $(this).find('td:eq(3)').find('input').css('border-color','');
                }

            });

            if( error == 1 ){

                $('#stockprocess_error').text('Satisfy Quantity must be less or eqal to Requested Quantity.');
                return;

            } else {

                if( parseFloat($('#req_qty').text()) < tot_satisfy ){
                    $('#stockprocess_error').text('Satisfy Quantity must be less or eqal to Requested Quantity.');
                    return;
                } else {
                    $('#stockprocess_error').text('');
                }

            }

            var data = $('#stock-detail-form').serialize();
            $.ajax({
                url:'/requestprocessitemstockdetail',
                type:'POST',
                data:'flag=process&'+data,
                dataType:'json',
                success:function(data){

                    if (data.status == 'success') {
                        alert(data.msg);
                        $('#process-modal').modal('hide');
                        location.reload();
                    } else {
                        $('#stockprocess_error').text(data.msg);
                    }

                }
            })
        }

        function changeLocation(el,item_id,flag) {

            var req_id = $(el).closest('tr').attr('id');
            var item_name = $(el).closest('tr').find('td:eq(1)').text();
            var req_qty = $(el).closest('tr').find('td:eq(2)').text();
            var item_id = item_id;

            if ( flag == 'open') {
                $('#location-modal').modal('show');
                $('#location-modal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');
            } else {
                $('#location-modal .modal-body').html('<div class="loading" style="text-align:center;"><img src="/loader.gif" /></div>');
            }

            $.ajax({
                url:'/requestprocessitemstockdetail',
                type:'POST',
                data:'req_id='+req_id+'&item_id='+item_id+'&item_name='+item_name+'&req_qty='+req_qty+'&flag='+flag,
                success:function(data){

                    $('#location-modal .modal-body').html(data);

                }
            })

        }

        function validateForm() {

            var error = false;

            //check all satisfy quantity field
            $('.sat_qty').each(function(index){

                if( $(this).val().trim() != '' ) {

                    var location = $('.locations').eq(index).val();
                    if( location == '' ) {
                        $('.locations').eq(index).css('border-color','red');
                        error = "error_location";
                    } else {
                        $('.locations').eq(index).css('border-color','')
                    }

                    if ($('.total_stock').eq(index).text() != '-') {
                        var avail_stock = $('.total_stock').eq(index).text().split(' ');

                        if ( parseInt(avail_stock[0]) < parseInt($('.sat_qty').eq(index).val()) && parseInt(avail_stock[0])=='') {
                            $('.sat_qty').eq(index).css('border-color', 'red');
                            error = "error_stock";
                        } else {
                            $('.sat_qty').eq(index).css('border-color', '');
                        }

                    }
                }

            });

            if ( error == "error_location" ) {
                alert('Please check the field highlighted in red. From location field should not be empty.');
                return false;
            }
            if ( error == "error_stock" ) {
                alert('Please check the field highlighted in red. Satisfied qty must not be greater than Total Stock.');
                return false;
            }

        }

        $(document).ready(function() {

            $('#from_date').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date,
                setdate:new Date

            });
            $('#satisfied_date').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });

            $('#to_date').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date,
                setdate:new Date
            });

            $('.locations').change(function() {
                var selected = $(this).find('option:selected');
                $(this).parent().next().text(selected.data('qty'));

            });


        });

        $(function() {
            $('#showbtn').on('click', function(){

                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();

                if (from_date <= to_date){
                    return true;
                }else{
                    alert('ToDate must be greaterthan FromDate.')
                    return false;
                }

            })
        })
    </script>


@stop