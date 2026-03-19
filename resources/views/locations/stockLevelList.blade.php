<form method="post" id="stock_level" class="form-horizontal material-form j-forms" action="/location/store-stock-level">
    <input type="hidden" name="loc_id" value="{!! $loc_id !!}">
    <div class="form-group">
    @if( isset($arr) && sizeof($arr) > 0 )
        <?php $cat = '';$i=0;?>
        @foreach( $arr as $key=>$val )
            @if( $i==0 || $cat != $val['cat_id'] )
                @if( $i != 0 )
                    </tbody>
                    </table>

                @endif
                <?php $cat = $val['cat_id'];?>
                <table class="table foo-data-table">
                    @if ( $cat_id == '' )
                        <thead>
                            <th colspan="6" style="font-weight: bold; text-align: center;background-color: #e7e7e7">{!! $val['cat_name'] !!}</th>
                        </thead>
                    @endif
                    <thead>
                        <th>Item Name</th>
                        <th>Req. Item</th>
                        <th>Fav. Req. Item</th>
                        <th>Order Qty</th>
                        <th>Reserved Qty</th>
                        <th>Opening Qty</th>
                    </thead>
                    <tbody id="table_body">
            @endif

                    <tr>
                        <td>
                            {!! $val['item'] !!}
                            <input type="hidden" name="item_id[]" value="{!! $val['id'] !!}">
                            <input type="hidden" name="unit_id[]" value="{!! $val['unit_id'] !!}">
                            <input type="hidden" name="cat_id[]" value="{!! $val['cat_id'] !!}">
                        </td>
                        <td>
                            @if(isset($val['request_item']) && $val['request_item'] == 'true')
                                <label class="checkbox">
                                    <input type="checkbox" name="request_item[{!! $val['id'] !!}]" value="1" checked>
                                    <i></i>
                                </label>
                            @else
                                <label class="checkbox">
                                    <input type="checkbox" name="request_item[{!! $val['id'] !!}]" value="1">
                                    <i></i>
                                </label>
                            @endif
                        </td>
                        <td>
                            @if(isset($val['req_fav_item']) && $val['req_fav_item'] == 1)
                                <label class="checkbox">
                                    <input type="checkbox" name="req_fav_item[{!! $val['id'] !!}]" value="1" checked>
                                    <i></i>
                                </label>
                            @else
                                <label class="checkbox">
                                    <input type="checkbox" name="req_fav_item[{!! $val['id'] !!}]" value="1">
                                    <i></i>
                                </label>
                            @endif
                        </td>
                        <td>
                            <div class="col-md-12 input-group">
                                <input class="form-control level-class" type="text" name="order_qty[]" value="@if($val['order_qty'] > 0){!! $val['order_qty'] !!}@else<?php echo '';?>@endif">
                                <span class="input-group-addon" id="sizing-addon1" >{!! $val['unit'] !!}</span>
                            </div>
                        </td>
                        <td>
                            <div class="col-md-12 input-group">
                                <input class="form-control level-class" type="text" name="reserved_qty[]" value="@if($val['reserved_qty'] > 0){!! $val['reserved_qty'] !!}@else<?php echo '';?>@endif">
                                <span class="input-group-addon" id="sizing-addon1" >{!! $val['unit'] !!}</span>
                            </div>
                        </td>
                        <td>
                            <div class="col-md-12 input-group">
                                <input class="form-control level-class" type="text" name="opening_qty[]" value="@if($val['opening_qty'] > 0){!! $val['opening_qty'] !!}@else<?php echo '';?>@endif">
                                <span class="input-group-addon" id="sizing-addon1" >{!! $val['unit'] !!}</span>
                            </div>
                        </td>
                    </tr>
            <?php $i++;?>
            @if( $i == sizeof($arr))
                </tbody>
                </table>
            @endif
        @endforeach
        <div class="col-md-12">
            <hr>
            <button type="submit" class="btn btn-primary pull-right">Submit</button>
        </div>
    </div>

    @endif

</form>