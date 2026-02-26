<?php
use App\OrderItem;
use App\Menu;

foreach($orders as $order){ ?>

    <div class="right-container orderdisp" id="<?php echo "order".$order->order_id;?>">
        <div class="ajaxloader" id="loader{{$order->order_id}}"></div>
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
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                        </thead>
                        <tbody>

                    <?php  $i=1; $item=OrderItem::where('order_id',$order->order_id)->get();?>

                           @foreach($item as $a)
                             <?php $itemnew=Menu::where('id',$a->item_id)->get(); ?>
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$itemnew[0]->item}}</td>
                                <td>{{$a->item_quantity}}</td>
                                <td>{{$itemnew[0]->price}}</td>
                            </tr>
                               <?php $i++; ?>
                           @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="order-status-contaier">
            @if($order->status=="received")

            <a class="confirm-order" href="#" onclick="changestatus('{{$order->status}}','{{$order->order_id}}','{{$order->outlet_id}}')">Preparing</a>
            @elseif($order->status=="preparing")
            <!-- <a class="wating-order" href="#" onclick="changestatus('{{$order->status}}','{{$order->order_id}}','{{$order->outlet_id}}')"> Received</a>-->
            <a class="confirm-order" href="#" onclick="changestatus('{{$order->status}}','{{$order->order_id}}','{{$order->outlet_id}}')">Prepared</a>
            @elseif($order->status=="prepared")
            <!--  <a class="wating-order" href="#" onclick="changestatus('{{$order->status}}','{{$order->order_id}}','{{$order->outlet_id}}')"> Preparing</a>-->
            <a class="confirm-order" href="#" onclick="changestatus('{{$order->status}}','{{$order->order_id}}','{{$order->outlet_id}}')">Delivered</a>
            @endif
        </div>
    </div>
    <?php
    }
?>