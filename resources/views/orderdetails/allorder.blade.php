<?php use App\OrderItem;
use App\Outlet;?>
@extends('partials.default')

@section('pageHeader-left')

    {{ trans('AllOrder.Order Details') }}



@stop
@section('content')
    <?php if(count($orders)>0) { ?>


<style>
    .received{background: #ef9a9a;margin:15px;}
    .preparing{background: #9fa8da;margin:15px;}
    .prepared{background: #a5d6a7;margin:15px;}
    .delievered{background: #ce93d8;margin:15px;}
    .ajax-loader {
        position:absolute;
        top:0; bottom:0;
        left:0; right:0;
        margin:auto;
        z-index:1000;
        opacity: .5;
        cursor: wait;
        display:none;
    }
</style>

@foreach($orders as $allorder)
    <?php if($allorder->status=="received") {

        $class="received";
    }
    else if($allorder->status=="preparing"){
        $class="preparing";
    }
    else if($allorder->status=="prepared"){
        $class="prepared";
    }
    else if($allorder->status=="delievered"){
        $class="delievered";
    }
    $oid=$allorder->suborder_id;
            $order_id=$allorder->order_id;
    ?>

    <div class="bs-example col-md-4" style="min-height:240px;max-height:240px;overflow: scroll;word-wrap:break-word;border-style:solid;border-width: 1px;padding: 0 0 0 0;margin-left:5px;margin-right:5px;margin-bottom:10px;border-color: #ddd;">
        <img src="loader.gif" class="ajax-loader" id="load{{$oid}}">
        <div class="panel-group " id="accordion{{$oid}}" style="margin:0 0 0 0 !important;position: relative;">

            <div class="panel panel-default" style="border-bottom-style: none;">
                <div class="panel-heading" >
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion{{$order_id}}" href="#collapseOne{{$order_id}}">{{$oid}}</a>
                    </h4>
                </div>

                <div id="collapseOne{{$order_id}}" class="panel-collapse collapse ">
                    <div class="panel-body">

                        <table id="orderdet" class="col-xs-2 col-md-4 getorderdetails ">

                            <tr>
                                <td style="padding:10px;">{{ trans('AllOrder.Name') }}  </td>
                                <td>{{$allorder->name}}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px;">{{ trans('AllOrder.Address') }}  </td>
                                <td><address>{{$allorder->address}}</address></td>
                            </tr>
                            <tr>
                                <td style="padding:10px;">{{ trans('AllOrder.Mobile') }}  </td>
                                <td>{{$allorder->user_mobile_number}}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px;">{{ trans('Restaurant_Index.Outlet Name') }}  </td>
                                <?php $outletname=Outlet::where('id',$allorder->outlet_id)->first();?>
                                <td>{{$outletname['name']}}</td>

                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="panel-group" id="accordion{{$oid}}new" style="margin:0 0 0 0 !important;">
            <div class="panel panel-default" style="border-bottom-style: none;">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion{{$oid}}new" href="#collapseOne{{$oid}}new" class="coldown">{{ trans('AllOrder.ITEM') }}</a>
                    </h4>
                </div>

                <div id="collapseOne{{$oid}}new" class="panel-collapse collapse in" >
                    <div class="panel-body" style="padding: 0 0 0 0;" >
                        <table id="orderdet" class="col-xs-2 col-md-4 getorderdetails {{$allorder->status}}" style="margin: 0 0 0 0;width:100%" >

                            <?php $item=OrderItem::where('order_id','=',$allorder->order_id)->get();

                            $totalprice=0;?>

                            <tr>
                                <th style="padding:10px;">{{ trans('AllOrder.ITEM') }}</th>
                                <th style="padding:10px;text-align:center;">{{ trans('AllOrder.Quantity') }}</th>
                                <th style="padding:10px;text-align:center;">{{mb_convert_encoding('&#8377;', 'UTF-8', 'HTML-ENTITIES')}}</th>
                            </tr>

                            @foreach($item as $it)

                                <?php $getmenu=\App\Menu::where('id',$it->item_id)->first();

                                $totalprice+=$it->item_quantity*$getmenu['price'];?>

                                <tr>
                                    <td style="padding:10px;">{{$getmenu['item']}}</td>
                                    <td style="padding:10px;text-align: center;">{{$it->item_quantity}}</td>
                                    <td style="padding:10px;text-align: center;">{{$it->item_quantity*$getmenu['price']}}</td>
                                </tr>

                            @endforeach
                            <tr>
                                <td style="padding:10px;"><b>{{ trans('AllOrder.Total Price:') }}</b></td><td>&nbsp;</td><td style="padding:10px;text-align: center;">{{$totalprice}}</td>
                            </tr>
                            <input type="hidden" id="orderstatusget" value="{{$allorder->status}}">
                            <tr>

                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endforeach


<?php } ?>
    @stop