<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pikal</title>
    <link rel="icon" type="image/png" href="/bower_components/images/favicon.ico" />
    <link type="text/css" rel="stylesheet" href="/bower_components/css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="/bower_components/css/style.css">
    <script type="text/javascript" src="/bower_components/js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="/bower_components/js/bootstrap.js"></script>

    <style>
        .orderdisp {display: none;}
        .ajaxloader{display: none;}
        .firstorder{display:block;}

        .firstorderlist .order-no {background:#b93121;}
        .firstorderlist {border-color:#b93121;}
        .confirm-order .wating-order .category h3{ text-transform: capitalize; }
        .btn-div {margin-bottom: 10px;}
    </style>


</head>

<?php use App\OrderItem;$j=1;?>

<body>
<div class="full-width-container">

    <input type="hidden" name="cnorder" class="cnorder" value="{{$maxdt}}" />
    <div class="header">
        <div class="logo"><img src="/bower_components/images/logo.png" alt="Pikal" /></div>
        <div class="punchline">
            <h3>ENJOY FOOD </h3>
            <span>- Any Type, Any Time, Any Where</span></div>
    </div>
    <div class="left-container">

        <div class="col-md-12">
            <div class="col-md-2">
                <label class="rest">{{ trans('OrderIndex.Outlet') }}</label>
            </div>
            <div class="col-md-7">
                <select id="rest_id" class="form-control" name="Outlet_name">
                    <option value="all">All</option>
                    @if( sizeof($Outlet) > 0 )
                        @foreach( $Outlet as $key=>$val )
                            <option value="{{ $key or '' }}">{{ $val or '' }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

                <div class="col-md-3 btn-div">
                    {!! Form::button('Submit', array('class' => 'btn btn-primary mr5', 'onclick' => 'selectOutlet();', 'style' => 'background-color: #F5A004;border-color: #F5A004;')) !!}
                </div>
            </div>
        <h3>Ongoing Orders (<span class="orderno"><?php if(isset($orders)) { echo count($orders);} ?></span>)</h3>

        <div class="orders-container">
            @foreach($orders as $order)

                @if($j==1)
                    <div class="order firstorderlist" id="<?php echo $order->order_id;?>">
                @else
                        <div class="order" id="<?php echo $order->order_id;?>">
                @endif
                        @if($order->order_type=="delivery")
                        <img src="/bower_components/images/delivery.png" />
                        @elseif($order->order_type=="dining")
                        <img src="/bower_components/images/dining.png" />
                        @elseif($order->order_type=="take -away")
                        <img src="/bower_components/images/take-away.png" />
                        @endif
                        <div class="order-no">{{$order->suborder_id}}</div>
                    </div>
                <?php $j++;?>
            @endforeach
        </div>



        <div class="table-no-container">
            <h3>Attend Me</h3>

            <ul class="table-no">
                <li>12</li>
                <li>5</li>
                <li>4</li>
                <li>21</li>
            </ul>
        </div>
    </div>

    <?php $i=1;foreach($orders as $order){ //print_r($order);?>
    @if($i==1)
    <div class="right-container orderdisp firstorder" id="<?php echo "order".$order->order_id;?>">
    @else
    <div class="right-container orderdisp" id="<?php echo "order".$order->order_id;?>">
    @endif
        <div class="ajaxloader" id="loader{{$order->suborder_id}}"></div>
        <div class="customer-info-container">
            <div class="customer-info-container-top">
                <div class="category">
                    <h3>{{ucfirst($order->status)." ( ".\App\OrderItem::getOrderType($order->order_type)." )"}}</h3>
                </div>
                <div class="customer"> Regular Customer</br>
                    <?php if(isset($star[$order->order_id]) && $star[$order->order_id]<2) {?>
                        <img src="/bower_components/images/1star.png" alt="Pikal" />
                    <?php } ?>
                    <?php if(isset($star[$order->order_id]) && $star[$order->order_id]>=3 && $star[$order->order_id]<=6) {?>
                        <img src="/bower_components/images/3star.png" alt="Pikal" />
                    <?php } ?>
                    <?php if(isset($star[$order->order_id]) && $star[$order->order_id]>=7) {?>
                        <img src="/bower_components/images/5star.png" alt="Pikal" />
                    <?php } ?>

                </div>
            </div>
            <div class="customer-information">

                <div class="info-raw">
                    <div class="info-raw dark" style="float: left; width:50%;"> <span style="float: left; width:22%;">Name :</span>
                        <p style="float: left; width:78%;">{{ucfirst($order->name)}} </p>
                    </div>
                    <div class="info-raw dark" style="float: left; width:50%;"> <span style="float: left; width:22%;">Phone :</span>
                        <p style="float: left; width:78%;">{{$order->user_mobile_number}}</p>
                    </div>

                </div>

                <div class="info-raw"> <span>Address :</span>
                    <p>{{$order->address}}</p>
                </div>


            </div>
            <div class="total">
                <p>Total RS</p>
                    <span>&#8377; {{number_format($totalprice[$order->order_id],2)}}</span> </div>

             </div>



        <div class="order-information">
            <div class="order-information-table">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $item=OrderItem::where('order_id',$order->order_id)->get();

                        foreach($item as $t){

                        $itemnew=$t->menuitem; ?>
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$itemnew->item}}</td>
                            <td>{{$t->item_quantity}}</td>
                            <td>{{$itemnew->price}}</td>
                        </tr>
                            <?php    $i++;} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="order-status-contaier">
            @if($order->status=="received")

            <a class="confirm-order" href="#"  onclick="changestatus('{{$order->status}}','{{$order->order_id}}','{{$order->outlet_id}}')">Preparing</a>
            @elseif($order->status=="preparing")

            <a class="confirm-order" href="#"  onclick="changestatus('{{$order->status}}','{{$order->order_id}}','{{$order->outlet_id}}')">Prepared</a>
            @elseif($order->status=="prepared")

            <a class="confirm-order" href="#"  onclick="changestatus('{{$order->status}}','{{$order->order_id}}','{{$order->outlet_id}}')">Delivered</a>
            @endif



        </div>
    </div>

    <?php
    DB::table('orders')->where('order_id',$order->order_id)->update(array("read"=>1));}
    ?>

</div>
</body>

</html>
<script>

    $(document).ready(function() {

        $(document).delegate(".order","click",function(e){

            $('.orderdisp').css('display','none');
            $('.order-no').css('background','#5d941d');
            $('.order').css('border-color','#ddd');
            $(this).find('div').css('background','#b93121');
            $(this).css('border-color','#b93121');
            $('#order'+$(this).attr('id')).css('display','block');
        });

        getorders('update');
    });
    function getorders(flag){

        var selected_id = $('#rest_id').val();
        var dt = $('.cnorder').val();
        var flag_val = '';
        var res_id = '';

        if ( selected_id != '' && selected_id != null ) {
            res_id = selected_id;
        }

        $.ajax({
            url: '/neworder/refreshorder',
            cache : false,
            method:'POST',
            data:'dt='+dt+ '&rest_id=' + res_id + '&flag='+flag,
            success: function(data) {
                //console.log(data)
                var t=data.split('*DIFF');
                if(t[0]!=''){

                    if($(".orders-container").children().length == 0){
                        $(".orders-container").html(t[0]);
                    }else {
                        $(".orders-container").html('');
                        $(".orders-container").html(t[0]);
                        /*if (flag === 'selectbox'){
                            //alert('here')
                            $(".orders-container").html('');
                            $(".orders-container").html(t[0]);
                        }else{
                            $(".orders-container div:eq(0)").before(t[0]);
                        }*/
                    }
                    //$('.orderno').text(parseInt($('.orderno').text())+parseInt(t[3]));
                    $('.orderno').text(parseInt(t[3]));
                }
                if(t[1]!=''){
                    $(".full-width-container").append(t[1]);
                }
                if(t[3] == '&Nodata'){
                    $('.orderdisp').css('display','none');
                    $(".orders-container").html('');
                }
                if(flag == 'selectbox'){
                    $('.ajaxloader').css('display','none');
                    $('.cnorder').val(t[2]);
                    if(t[3] != '&Nodata')
                    {
                        $('.orderdisp').css('display','none');
                        $(".orderno").text(t[3]);
                    }else{
                        $('.orderdisp').css('display','none');
                        $(".orderno").text(0);
                    }

                }
                setTimeout(function(){ getorders('update') }, 10000);
            }
        });
    }

    function selectOutlet(){
        var flag = 'selectbox';
        $('.ajaxloader').css('display','block');
        getorders(flag);
    }

    function changestatus(sta,oid,resid){

        var nextstatus='';
        $('#loader'+oid).css('display','block');
        $.ajax({
            url: 'ajax/nextstatus',
            cache : false,
            data:"currentstatus="+sta+"&oid="+oid+"&outlet_id="+resid,
            success: function(data) {
                if(data=="preparing") {nextstatus="Prepared";}
                else if(data=="prepared") {nextstatus="Delivered";}
                else if(data=="delivered") {$('#order'+oid).remove();$('#'+oid).remove();
                    $('.orderno').text(parseInt($('.orderno').text())-1);
                }
                $('#order'+oid).find('.category').find('h3').text(data.substr(0,1).toUpperCase()+data.substr(1));
                $('#order'+oid).find('.order-status-contaier').find('.wating-order').text(data.substr(0,1).toUpperCase()+data.substr(1));
                $('#order'+oid).find('.order-status-contaier').find('.confirm-order').text(nextstatus);
                $('#order'+oid).find('.order-status-contaier').find('.confirm-order').attr('onclick', "changestatus('"+data+"','"+oid+"','"+resid+"')");
                $('#loader'+oid).css('display','none');
            },
            error:function(data) {
                $('#loader'+oid).css('display','none');
            }
        });

    }
</script>