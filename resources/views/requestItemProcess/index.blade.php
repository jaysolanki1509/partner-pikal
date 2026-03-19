<?php
use App\Owner;
?>
@extends('partials.default')
@section('pageHeader-left')
    Response
@stop
@section('pageHeader-right')
    <a href="/responseItems/setisfiedResponse" class="btn btn-primary">Satisfied List</a>
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


    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">
                    <div class="widget-container">
                        <div class="widget-content">

                            <table class="table foo-data-table" data-page-size="100" data-limit-navigation="4" id="ProcessRequestTable">

                                <thead>
                                    <th >Requesting User</th>
                                    <th  data-sort-ignore="true" class="text-center">Select</th>
                                </thead>

                                <tbody>

                                    @if( isset($item_requests) && sizeof($item_requests) > 0 )
                                        @foreach($item_requests as $item_request)
                                            <?php $username=Owner::where('id',$item_request->owner_by)->first();?>
                                            <?php $unit = \App\ItemMaster::getItemsByItemId($item_request->what_item_id) ?>
                                            <tr class="odd gradeX">
                                                <td>{!! $username -> user_name !!}</td>
                                                <td class="text-center"><a href="/requestItemProcess/{!! $username->id !!}/getRequestedItem"><i class="glyphicon glyphicon-arrow-right"></i>
                                                    </a></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2">No request available.</td>
                                        </tr>
                                    @endif

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('page-scripts')
    <script>
        function getRequestedItem(id)
        {
            var id=id.getAttribute('data-info');

            $('#loader').css('display','block');
            if(id != '') {
                $.ajax({
                    url:'/ajax/getRequestedItem',
                    data:'id='+id,
                    success:function(data){

                        $("#cates_id").html('');
                        $("#cates_id").html(data);

                    }
                })
            }
        }
    </script>

@stop