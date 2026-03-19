<?php
use App\Owner;
?>
@extends('partials.default')
@section('pageHeader-left')
    Satisfied Items
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


    <div class="col-md-12"><hr></div>
    <div class="clearfix"></div>
    {{--<form class="form-horizontal" role="form" method="POST" id="requestItemProcess" novalidate="novalidate" action="{{ url('/requestItemProcess') }}" files="true" enctype="multipart/form-data">--}}
        <div class="col-md-12">
            <div class="col-md-12">

                @if($selected_user_id == 'All')

                    @if($selected_cate_id == 'All')

                        @foreach($satisfied_items as $satisfied_item)

                            <div class="row well">
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <?php $user_name = Owner::where('id',$satisfied_item->owner_by)->first(); ?>
                                        <label class="control-label">User:-</label> &nbsp; {!! $user_name->user_name !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <label class="control-label">Satisfied Time:-</label> &nbsp; {!! $selected_time !!}
                                    </div>
                                </div>
                                <?php
                                $categories = DB::table("item_request")
                                        ->join('menus','menus.id','=','item_request.what_item_id')
                                        ->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                        ->select('item_request.id as request_id', 'item_request.what_item_id', 'item_request.what_item','item_request.statisfied_qty as statisfied_qty', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id as item_id', 'menus.item', 'menu_titles.id as cate_id' , 'menu_titles.title')
                                        ->groupBy('menu_titles.id')
                                        ->where('item_request.owner_by','=',$satisfied_item->owner_by)
                                        ->where('item_request.updated_at','=',$selected_time)
                                        ->where('item_request.satisfied','=',"Yes")->get();

                                ?>
                                @foreach($categories as $category)
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <label class="control-label">Category:-</label>&nbsp;  {!! $category->title !!}
                                        </div>
                                    </div>

                                    <?php
                                    $requests = DB::table("item_request")
                                            ->join('menus','menus.id','=','item_request.what_item_id')
                                            ->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                            ->select('item_request.id as request_id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty','item_request.statisfied_qty as statisfied_qty', 'menus.id as item_id', 'menus.item', 'menu_titles.id as cate_id' , 'menu_titles.title')
                                            ->where('item_request.owner_to','=',$owner_to)
                                            ->where('item_request.owner_by','=',$satisfied_item->owner_by)
                                            ->where('menu_titles.id','=',$category->cate_id)
                                            ->where('item_request.updated_at','=',$selected_time)
                                            ->where('item_request.satisfied','=',"Yes")->get();
                                    ?>

                                    <table id="requestitemprocess" class="table table-striped table-hover">
                                        <thead>
                                        <th>Requested Date</th>
                                        {{--<th>Requested By</th>--}}
                                        <th>Requested Item</th>
                                        <th>Unit</th>
                                        <th>Existing Stock</th>
                                        <th>Requested Qty</th>
                                        <th>Satisfied Qty</th>
                                        </thead>

                                        <tbody>

                                        @if(sizeof($requests) > 0)

                                            @foreach($requests as $request)
                                                <?php $unit = \App\Menu::getMenuItemByTitleIdandMenuId($request->what_item_id) ?>
                                                <?php $username=\App\Owner::where('id',$request->owner_by)->first();?>
                                                <tr>
                                                    <td>{!! $request->when !!}</td>
                                                    {{--<td>{!! $username->user_name !!}</td>--}}
                                                    <td>{!! $request->what_item !!}</td>
                                                    <td>{!! \App\Unit::getUnitbyId($unit->unit_id)->name !!}</td>
                                                    <td class="text-center">{!! $request->existing_qty !!}</td>
                                                    <td class="text-center">{!! $request->qty !!}</td>
                                                    <td class="text-center">{!! $request->statisfied_qty !!}</td>
                                                </tr>
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
                        @endforeach
                    @else
                        @foreach($satisfied_items as $satisfied_item)
                            <div class="row well">
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <?php $user_name = Owner::where('id',$satisfied_item->owner_by)->first(); ?>
                                        <label class="control-label">User:-</label> &nbsp; {!! $user_name->user_name !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <label class="control-label">Satisfied Time:-</label> &nbsp; {!! $selected_time !!}
                                    </div>
                                </div>
                                <?php
                                $categories = DB::table("item_request")
                                        ->join('menus','menus.id','=','item_request.what_item_id')
                                        ->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                        ->select('item_request.id as request_id', 'item_request.what_item_id','item_request.statisfied_qty as statisfied_qty', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id as item_id', 'menus.item', 'menu_titles.id as cate_id' , 'menu_titles.title')
                                        ->groupBy('menu_titles.id')
                                        ->where('item_request.owner_to','=',$owner_to)
                                        ->where('item_request.owner_by','=',$satisfied_item->owner_by)
                                        ->where('menu_titles.id','=',$selected_cate_id)
                                        ->where('item_request.updated_at','=',$selected_time)
                                        ->where('item_request.satisfied','=',"Yes")->get();
                                ?>
                                @foreach($categories as $category)
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <label class="control-label">Category:-</label>&nbsp;  {!! $category->title !!}
                                        </div>
                                    </div>

                                    <?php
                                    $requests = DB::table("item_request")
                                            ->join('menus','menus.id','=','item_request.what_item_id')
                                            ->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                            ->select('item_request.id as request_id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty','item_request.statisfied_qty as statisfied_qty', 'menus.id as item_id', 'menus.item', 'menu_titles.id as cate_id' , 'menu_titles.title')
                                            ->where('item_request.owner_to','=',$owner_to)
                                            ->where('item_request.owner_by','=',$satisfied_item->owner_by)
                                            ->where('menu_titles.id','=',$category->cate_id)
                                            ->where('item_request.updated_at','=',$selected_time)
                                            ->where('item_request.satisfied','=',"Yes")->get();
                                    ?>

                                        <table id="requestitemprocess" class="table table-striped table-hover">
                                            <thead>
                                            <th>Requested Date</th>
                                            {{--<th>Requested By</th>--}}
                                            <th>Requested Item</th>
                                            <th>Unit</th>
                                            <th>Existing Stock</th>
                                            <th>Requested Qty</th>
                                            <th>Satisfied Qty</th>
                                            </thead>

                                            <tbody>
                                            @foreach($requests as $request)
                                                @if(sizeof($satisfied_item) > 0)

                                                    <?php
                                                    $unit = \App\Menu::getMenuItemByTitleIdandMenuId($request->what_item_id);
                                                    ?>
                                                    <?php $username=\App\Owner::where('id',$request->owner_by)->first();?>
                                                    <tr>
                                                        <td>{!! $request->when !!}</td>
                                                        {{--<td>{!! $username->user_name !!}</td>--}}
                                                        <td>{!! $request->what_item !!}</td>
                                                        <td>{!! \App\Unit::getUnitbyId($unit->unit_id)->name !!}</td>
                                                        <td class="text-center">{!! $request->existing_qty !!}</td>
                                                        <td class="text-center">{!! $request->qty !!}</td>
                                                        <td class="text-center">{!! $request->statisfied_qty !!}</td>
                                                    </tr>

                                                @else
                                                    <tr>
                                                        <td>You Don't have any request.  </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                @endforeach
                            </div>
                        @endforeach
                    @endif

                @else
                    @if($selected_cate_id == 'All')


                        <div class="row well">
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <?php $user_name = Owner::where('id',$selected_user_id)->first(); ?>
                                    <label class="control-label">User:-</label> &nbsp; {!! $user_name->user_name !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <label class="control-label">Satisfied Time:-</label> &nbsp; {!! $selected_time !!}
                                </div>
                            </div>
                            @foreach($satisfied_items as $satisfied_item)
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <label class="control-label">Category:-</label>&nbsp;  {!! $satisfied_item->title !!}
                                    </div>
                                </div>

                                <?php
                                $requests = DB::table("item_request")
                                        ->join('menus','menus.id','=','item_request.what_item_id')
                                        ->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                        ->select('item_request.id as request_id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty','item_request.statisfied_qty as statisfied_qty', 'menus.id as item_id', 'menus.item', 'menu_titles.id as cate_id' , 'menu_titles.title')
                                        ->where('item_request.owner_to','=',$owner_to)
                                        ->where('item_request.owner_by','=',$selected_user_id)
                                        ->where('menu_titles.id','=',$satisfied_item->cate_id)
                                        ->where('item_request.updated_at','=',$selected_time)
                                        ->where('item_request.satisfied','=',"Yes")->get();
                                ?>

                                <table id="requestitemprocess" class="table table-striped table-hover">
                                    <thead>
                                    <th>Requested Date</th>
                                    {{--<th>Requested By</th>--}}
                                    <th>Requested Item</th>
                                    <th>Unit</th>
                                    <th>Existing Stock</th>
                                    <th>Requested Qty</th>
                                    <th>Satisfied Qty</th>
                                    </thead>

                                    <tbody>

                                    @if(sizeof($requests) > 0)

                                        @foreach($requests as $request)
                                            <?php $unit = \App\Menu::getMenuItemByTitleIdandMenuId($request->what_item_id) ?>
                                            <?php $username=\App\Owner::where('id',$request->owner_by)->first();?>
                                            <tr>
                                                <td>{!! $request->when !!}</td>
                                                {{--<td>{!! $username->user_name !!}</td>--}}
                                                <td>{!! $request->what_item !!}</td>
                                                <td>{!! \App\Unit::getUnitbyId($unit->unit_id)->name !!}</td>
                                                <td class="text-center">{!! $request->existing_qty !!}</td>
                                                <td class="text-center">{!! $request->qty !!}</td>
                                                <td class="text-center">{!! $request->statisfied_qty !!}</td>
                                            </tr>
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
                    @else
                        <div class="row well">
                            <div class="col-md-12">
                                <div class="col-md-2">

                                    <?php
                                    $user_name = Owner::where('id',$selected_user_id)->first();
                                    ?>
                                    <label class="control-label">User:-</label> &nbsp; {!! $user_name->user_name !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <label class="control-label">Satisfied Time:-</label> &nbsp; {!! $selected_time !!}
                                </div>
                            </div>
                            @foreach($satisfied_items as $satisfied_item)
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <label class="control-label">Category:-</label>&nbsp;  {!! $satisfied_item->title !!}
                                    </div>
                                </div>


                                <?php
                                $requests = DB::table("item_request")
                                        ->join('menus','menus.id','=','item_request.what_item_id')
                                        ->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                        ->select('item_request.id as request_id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty','item_request.statisfied_qty as statisfied_qty', 'menus.id as item_id', 'menus.item', 'menu_titles.id as cate_id' , 'menu_titles.title')
                                        ->where('item_request.owner_to','=',$owner_to)
                                        ->where('item_request.owner_by','=',$selected_user_id)
                                        ->where('menu_titles.id','=',$satisfied_item->cate_id)
                                        ->where('item_request.updated_at','=',$selected_time)
                                        ->where('item_request.satisfied','=',"Yes")->get();
                                ?>

                                <table id="requestitemprocess" class="table table-striped table-hover">
                                    <thead>
                                    <th>Requested Date</th>
                                    {{--<th>Requested By</th>--}}
                                    <th>Requested Item</th>
                                    <th>Unit</th>
                                    <th>Existing Stock</th>
                                    <th>Requested Qty</th>
                                    <th>Satisfied Qty</th>
                                    </thead>

                                    <tbody>

                                    @if(sizeof($requests) > 0)

                                        @foreach($requests as $request)
                                            <?php $unit = \App\Menu::getMenuItemByTitleIdandMenuId($request->what_item_id) ?>
                                            <?php $username=\App\Owner::where('id',$request->owner_by)->first();?>
                                            <tr>
                                                <td>{!! $request->when !!}</td>
                                                {{--<td>{!! $username->user_name !!}</td>--}}
                                                <td>{!! $request->what_item !!}</td>
                                                <td>{!! \App\Unit::getUnitbyId($unit->unit_id)->name !!}</td>
                                                <td class="text-center">{!! $request->existing_qty !!}</td>
                                                <td class="text-center">{!! $request->qty !!}</td>
                                                <td class="text-center">{!! $request->statisfied_qty !!}</td>
                                            </tr>
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
                    @endif
                @endif
            </div>
        </div>
    {{--</form>--}}
    {{--</div>--}}



@stop