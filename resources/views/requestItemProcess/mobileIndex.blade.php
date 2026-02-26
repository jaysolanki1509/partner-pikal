<?php
use App\Owner;
?>
@extends('app')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Process Requested Item <a href="/stock-request-report" style="float: right;">Back</a></div>
                    <div class="panel-body">
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
                                <div class="col-md-6 form">

                                    <table class="table table-striped table-bordered table-hover" id="ProcessRequestTable">
                                        <thead>
                                        <th class="text-center">Requesting User</th>
                                        <th class="text-center">Select</th>
                                        </thead>

                                        <tbody>
                                        {{--<tr>
                                            <td>All</td>
                                            <td class="text-center"><a href="/requestItemProcess/create"><i class="glyphicon glyphicon-arrow-right"></i>
                                                </a></td>
                                        </tr>--}}
                                        @foreach($item_requests as $item_request)
                                            <?php $username=Owner::where('id',$item_request->owner_by)->first();?>
                                            <?php $unit = \App\ItemMaster::getItemsByItemId($item_request->what_item_id) ?>
                                            <tr class="odd gradeX">
                                                <td>{!! $username -> user_name !!}</td>
                                                <td class="text-center"><a href="/requestItemProcess/{!! $username->id !!}/getRequestedItem"><i class="glyphicon glyphicon-arrow-right"></i>
                                                    </a></td>
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

            /*$('#date').datepicker({
             dateFormat: "yy-mm-dd",
             maxDate: new Date,
             setdate:new Date

             });*/

            $('#from_date').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date,
                setdate:new Date

            });

            $('#to_date').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: new Date,
                setdate:new Date
            });


        });

        $(function() {
            $('#showbtn').on('click', function(){
                //alert('here');
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                //console.log(from_date+'==='+to_date)
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