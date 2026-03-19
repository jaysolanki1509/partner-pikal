

            @foreach($orders as $order)
            <div class="order" id="<?php echo $order->order_id;?>">
                @if($order->order_type=="home delivery")
                <img src="/bower_components/images/delivery.png" />
                @elseif($order->order_type=="dining")
                <img src="/bower_components/images/dining.png" />
                @elseif($order->order_type=="take away")
                <img src="/bower_components/images/take-away.png" />
                @endif
                <div class="order-no">{{$order->suborder_id}}</div>
            </div>

            @endforeach

