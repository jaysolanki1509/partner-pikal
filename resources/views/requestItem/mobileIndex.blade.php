<?php
use App\Owner;
?>
@extends('app')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Your Pending Request
                        {{--<a href="/requestItem/create" style="float: right;">Request</a>--}}
                        {{--<a href="javascript:history.back()" style="float: right;">Back</a>--}}
                        <a href="/requestItem/create" style="float: right;">Back</a>
                    </div>
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

                            @foreach($items as $item)
                                <div class="row well">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <?php $user_name = Owner::where('id',$item->owner_to)->first(); ?>
                                            <label class="control-label">User To:-</label> &nbsp; {!! $user_name->user_name !!}
                                        </div>
                                    </div>
                                    <?php
                                    $categories = DB::table("item_request")
                                        ->Join('menus','menus.id','=','item_request.what_item_id')
                                        ->Join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                        ->select('item_request.id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id', 'menus.menu_title_id', 'menus.item', 'menu_titles.title')
                                        ->groupBy('menu_titles.title')
                                        ->where('item_request.owner_by','=',$user_id)
                                        ->where('item_request.owner_to','=',$item->owner_to)
                                        ->where('item_request.satisfied','=',"No")->get();
                                    ?>
                                    @foreach($categories as $category)
                                    <?php $i=0; ?>
                                        <div class="col-md-12">
                                            <div class="col-md-4">
                                                <label class="control-label">Category:-</label>&nbsp;  {!! $category->title !!}
                                            </div>
                                        </div>

                                        <?php
                                        $requests = DB::table("item_request")
                                            ->Join('menus','menus.id','=','item_request.what_item_id')
                                            ->Join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                            ->select('item_request.id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id', 'menus.menu_title_id', 'menus.item', 'menu_titles.title')
                                            ->where('item_request.owner_by','=',$user_id)
                                            ->where('item_request.owner_to','=',$item->owner_to)
                                            ->where('menus.menu_title_id','=',$category->menu_title_id)
                                            ->where('item_request.satisfied','=',"No")->get();
                                        ?>

                                        <table class="table table-striped table-bordered table-hover" id="StatusTable">
                                            <thead>
                                            <th>Requested Item</th>
                                            <th>Requested To</th>
                                            {{--<th>Current Stock</th>--}}
                                            <th>Requested Qty</th>
                                            {{--<th>Satisfied</th>--}}
                                            </thead>

                                            <tbody>
                                            @foreach($requests as $request)
                                                <?php $username=Owner::where('id',$request->owner_to)->first();?>
                                                <?php $unit = \App\Menu::where('id',$request->what_item_id)->first(); ?>
                                                <tr class="odd gradeX">
                                                    <td>{!! $request -> what_item !!}</td>
                                                    <td>{!! $username -> user_name !!}</td>
                                                    <td>{!! $request -> existing_qty !!} {!! \App\Unit::getUnitbyId($unit->unit_id)->name !!} </td>
                                                    <td>{!! $request -> qty !!} {!! \App\Unit::getUnitbyId($unit->unit_id)->name !!} </td>
                                                    {{--<td>{!! $item_request -> satisfied !!}</td>--}}
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endforeach
                                </div>
                            @endforeach
                        {{--<div class="col-md-2">
                            <button type="button"  onclick="printData()" id="check_print" class="btn btn-primary col-md-10" style="float: right; margin-right:0%; " >Print</button>
                        </div>--}}
                        {!! Form::input('hidden','request_data' , isset($print_data) ? $print_data : null, ['disabled','class' => 'form-control','id'=>'request_data' ] ) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>

        $(document).ready(function() {
            $('#CategoriesTable').DataTable({
                responsive: true,
                pageLength: 100
            });
        });

        function printData() {
            window.RequestData.requestData($('#request_data').val());
        }

    </script>


@stop