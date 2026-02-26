<?php use App\Menu; ?>
@extends('partials.default')
@section('pageHeader-left')
Item Master Upload
@stop
@section('page-styles')

{!! HTML::style('/assets/css/style.datatables.css') !!}
{!! HTML::style('/assets/css/dataTables.responsive.css') !!}
{!! HTML::style('/assets/css/custom.datatable.css') !!}
@stop
@section('content')
<link rel="stylesheet" href="../css/jquery-ui.css">
<style>
    body {
        padding: 30px 0px;
    }

    #lightbox .modal-content {
        display: inline-block;
        text-align: center;
    }


    #lightbox .close {
        opacity: 1;
        color: rgb(255, 255, 255);
        background-color: rgb(25, 25, 25);
        padding: 5px 8px;
        border-radius: 30px;
        border: 2px solid rgb(255, 255, 255);
        position: absolute;
        top: -15px;
        right: -55px;

        z-index:1032;
    }
    .container
    {
        position: absolute;
        top: 10%; left: 10%; right: 0; bottom: 0;
    }
    .action
    {
        width: 400px;
        height: 30px;
        margin: 10px 0;
    }
    .cropped>img
    {
        margin-right: 10px;
    }
    #map {
        width: 100%;
        height: 400px;
    }


</style>
@if(Session::has('error'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
    {{ Session::get('error') }}
</div>
@elseif(Session::has('success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
    {{ Session::get('success') }}
</div>
@endif


    <div id="menu" class="tab-pane">

        <div class="clearfix"></div>
        <div class="form pull-right" style="width:100%; margin:20px 0;">

            <a href="{{ action('MenuController@create') }}" class="btn btn-primary" style="float: left; margin:0 10px;">{{ trans('Restaurant_Show.Add Menu') }}</a>

            <div class="media" style="float:left;margin-top:0px; margin-right:10px;">
                <button type="button" style="float:right" class="btn btn-primary" data-toggle="modal" data-target="#attachmenu" data-whatever="">{{ trans('Restaurant_Show.Import Menu Excel') }}</button>
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
                                        {!! Form::open(array('url'=>'menu/importmenuexcel','method'=>'POST', 'files'=>true)) !!}
                                        <div class="control-group">
                                            <div class="controls">
                                                <input type="hidden" name="user_id" value="<?php \Illuminate\Support\Facades\Auth::user()->id; ?>"/>
                                                {!! Form::file('file1', array('multiple'=>true)) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('Restaurant_Show.Close') }}</button>
                                <!--<button type="button" class="btn btn-primary" data-dismiss="modal">{{ trans('Restaurant_Show.Submit') }}</button>-->
                                {!! Form::submit(trans('Restaurant_Show.Submit'), array('class'=>'btn btn-primary')) !!}
                                {!! Form::close() !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="input-group input-group-lg mb15 wid-full" style="float:left;">
                <span class="input-group-addon" style="display:none" ></span>
                {!! Form::open(array('url'=>'outlet/exportexcel','method'=>'POST')) !!}
                <div class="control-group">
                    <div class="controls">
                        <input type="hidden" name="restau_id" value=""/>

                    </div>
                </div>
                <button id="<?php \Illuminate\Support\Facades\Auth::id(); ?>" class="btn btn-primary" type="submit" class="exportexcel">{{ trans('Restaurant_Show.Export Menu Excel') }}</button>
            </div>


        </div>
        <div class="clearfix"></div>
        <div class="activity-list vehicle-list">
            <div class="media">
                <div>
                    <div>
                        {{--<a data-toggle="modal" data-target="#{{$cuisinetype->cuisine_type_id}}" data-whatever="">{{$cuisinetype->cuisine_type_id}}</a>--}}
                    </div>
                </div>

                <div class="dataTable_wrapper">
                    <table class="table dataTable table-bordered responsive" id="ItemMasterTable">
                        <thead >
                            <tr style="background:#428bca;">
                                <th style="color:#fff;"></th>
                                <th style="color:#fff;" title="Category">Category</th>
                                <th style="color:#fff;" title="Alis">Alias</th>
                                <th style="color:#fff;" title="Item">Item</th>
                                <th style="color:#fff;" title="Rate">Rate</th>
                                <th style="color:#fff;" title="Action">Action</th>
                            </tr>
                        </thead>

                        <tfoot>
                        @foreach($item_master as $item)
                            <tr class="field-input whitebg">
                                <td> </td>
                                <td>{{$item->title}}</td>
                                <td>{{$item->alias}}</td>
                                <td>{{$item->item}}</td>
                                <td>{{$item->price}}</td>
                                <td> </td>
                            </tr>
                        @endforeach
                        </tfoot>
                    </table>
                </div>

                <FIELDSET class="fieldsset">
                    <div id="accordion">
                        @foreach($Outletsectioname as $sectionname)

                        @if($sectionname->active == 0)
                        <p><b>{{$sectionname->title}}</b></p>

                        <div class="center">
                            @foreach($menuitems as $items)
                            @if($sectionname->id == $items->menu_title_id)
                            <h5>
                                @if($items->active == 0)
                                <div class="col-sm-8"><p>{{$items['item']}}</p></div>
                                @else
                                <div class="col-sm-8"><p>{{$items['item']}}&nbsp;(InActive)</p></div>
                                @endif
                                @if(isset($items['price']) && $items['price']!=0 && $items['price']!="")
                                <div class="col-sm-2"><p>{{mb_convert_encoding('&#8377;', 'UTF-8', 'HTML-ENTITIES') . " " .$items['price']}}</p></div>
                                @else
                                <div class="col-sm-2"><p></p>
                                </div>
                                @endif
                            </h5>
                            @endif
                            @endforeach
                        </div>
                        @endif
                        @endforeach
                    </div>
                </FIELDSET>

                {{--<FIELDSET class="fieldsset">--}}
                    {{--<div id="accordion">--}}
                        {{--@foreach($Outletsectioname as $sectionname)--}}
                        {{--@if($sectionname['active']== '1')--}}
                        {{--<p><b>{{$sectionname->title}}</b></p>--}}
                        {{--<div class="center">--}}
                            {{--@foreach($menuitems as $items)--}}
                            {{--@if($sectionname->id == $items->menu_title_id)--}}
                            {{--<h5>--}}
                                {{--@if(isset($items['active']) && $items['active']== '1')--}}
                                {{--<div class="col-sm-8"><p>{{$items['item']}}</p></div>--}}
                                {{--@if(isset($items['price']) && $items['price']!=0 && $items['price']!="")--}}
                                {{--<div class="col-sm-2"><p>{{mb_convert_encoding('&#8377;', 'UTF-8', 'HTML-ENTITIES') . " " .$items['price']}}</p></div>--}}
                                {{--@else--}}
                                {{--<div class="col-sm-2"><p></p>--}}
                                    {{--</div>--}}
                                {{--@endif--}}

                                {{--@elseif($items['active']=='0')--}}
                                {{--<p>{{" "}}--}}
                                    {{--</p>--}}
                                {{--@endif--}}
                                {{--</h5>--}}
                            {{--@endif--}}
                            {{--@endforeach--}}
                            {{--</div>--}}
                        {{--@elseif($sectionname['active']=='0')--}}

                        {{--@endif--}}
                        {{--@endforeach--}}
                        {{--</div>--}}
                    {{--</FIELDSET>--}}

            </div>
        </div>
    </div>
@stop
@section('page-scripts')

    {!! HTML::script('/assets/js/jquery.dataTables.min.js') !!}
    {!! HTML::script('/assets/js/dataTables.bootstrap.js') !!}
    {!! HTML::script('/assets/js/dataTables.responsive.js') !!}
    {!! HTML::script('/assets/js/datatable.list.js') !!}

    <script>
        /*$(document).ready(function() {
            $('#ItemMasterTable').DataTable({
                responsive: true
            });
        });*/

        var selected = [];
        var table = '';
        var PurchaseTable_filters = [];

        $(document).ready(function() {

            var tbl_id = 'ItemMasterTable';
            var order = 6;
            var url = '/menu';
            var columns = [
                { "mDataProp": "check_col","bSortable": false,"bVisible":false },
                { "mDataProp": "category" },
                { "mDataProp": "alias" },
                { "mDataProp": "item" },
                { "mDataProp": "rate" },
                { "mDataProp": "action","bSortable":false }
            ];

            loadList( tbl_id, url, order, columns);

        });
    </script>

    <script src="/js/CropBox.js"></script>
    <script src="/js/jquery-ui.js"></script>
    {{--<script src="/js/jquery-ui.min.js"></script>--}}
    <script>
        $(function() {
            $( "#accordion" ).accordion();
//            var newDiv = "";
//            $('.fieldsset').append(newDiv);
        });
    </script>

@stop
