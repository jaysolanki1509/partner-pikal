<?php
$ids=array();
if(count($orders)>0) {
    ?>
    <style>
        .received{background: #ef9a9a;margin:15px;}
        .preparing{background: #9fa8da;margin:15px;}
        .prepared{background: #a5d6a7;margin:15px;}
        .delievered{background: #ce93d8;margin:15px;}


    </style>

    @foreach($orders as $re)

    <?php if($re['status']=="received") {

        $class="received";
    }
    else if($re['status']=="preparing"){
        $class="preparing";
    }
    else if($re['status']=="prepared"){
        $class="prepared";
    }
    else if($re['status']=="delievered"){
        $class="delievered";
    }
    $oid=$re['order_id'];

    ?>
    <div class="bs-example col-md-4" style="min-height:210px;max-height:210px;overflow: scroll;border-style:solid;border-width: 1px;padding: 0 0 0 0;border-color: #ddd;margin-left:5px;margin-right:5px;margin-bottom:10px;left: 30px;">
        <div class="panel-group" id="accordion{{$oid}}" style="margin:0 0 0 0 !important;">
            <div class="panel panel-default" style="border-bottom-style: none;">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne{{$oid}}">{{$oid}}</a>
                    </h4>
                </div>

                <div id="collapseOne{{$oid}}" class="panel-collapse collapse">
                    <div class="panel-body">
                        <table id="orderdet" class="col-xs-2 col-md-4 getorderdetails ">
                            <tr>
                                <td style="padding:10px;">{{ trans('SearchOrder.UserName') }}  </td>
                                <td>{{$re['name']}}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px;">{{ trans('SearchOrder.Address') }}  </td>
                                <td><address>{{$re['address']}}</address></td>
                            </tr>
                            <tr>
                                <td style="padding:10px;">{{ trans('SearchOrder.Mobile') }}  </td>
                                <td>{{$re['mobile_number']}}</td>
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
                        <a data-toggle="collapse" data-parent="#accordion{{$oid}}new" href="#collapseOne{{$oid}}new" class="coldown">{{ trans('SearchOrder.ITEM') }}</a>
                    </h4>
                </div>

            <div id="collapseOne{{$oid}}new" class="panel-collapse collapse in">
                <div class="panel-body" style="padding: 0 0 0 0;">
                    <table id="orderdet" class='col-xs-2 col-md-4 getorderdetails {{$class}}' style="margin: 0 0 0 0;width:100%">
                        <tr>
                            <th style="padding:10px;">{{ trans('SearchOrder.ITEM') }}</th>
                            <th style="padding:10px;">{{ trans('SearchOrder.Quantity') }}</th>
                            <th style="padding:10px;">{{ trans('SearchOrder.Price') }}</th>
                        </tr>
                        <?php
                        $id=$re['order_id'];
                        $ordstat=\App\OrderItem::where('order_id',"$id")->get();

                        $totalprice=0;

                        foreach($ordstat as $de){
                         $getmenu=\App\Menu::where('id',$de->item_id)->first();
                        // print_r($it);exit;
                        if(isset($getmenu['price']) && $getmenu['price']!=""){
                            $totalprice+=$de->item_quantity*$getmenu['price'];
                        }else{
                            $totalprice='';
                        }?>
                            <tr>
                                <td style="padding:10px;">{{$getmenu['item']}}</td>
                                <td style="padding:10px;text-align: center;">{{$de->quantity}}</td>
                                <td style="padding:10px;text-align: center;">{{$de->quantity*$getmenu['price']}}</td>
                            </tr>

                        <?php  }?>
                        <tr>
                            <td style="padding:10px;"><b>{{ trans('SearchOrder.Total Price:') }}</b></td>
                            <td>&nbsp;</td>
                            <td style="padding:10px;text-align: center;">{{$totalprice}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>

    @endforeach

<?php }

else { ?>

    <div class="alert alert-success fade in" style="margin-top: 290px;">

        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <strong>Oopps!</strong> No Records found........

    </div>


<?php }?>