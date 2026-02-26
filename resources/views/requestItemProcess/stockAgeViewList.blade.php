<form id="stock-detail-form">

    <div class="form-group" id="stock-details">

        @if( $flag == 'open')

            <div class="form-group">
                <div class="col-md-6">
                    <lable style="font-weight: bold">Item Name:</lable>
                    <span>{!! $item_name !!}</span>
                </div>

                <div class="col-md-6">
                    <lable style="font-weight: bold">Requested Qty:</lable>
                    <span id="req_qty">{!! $req_qty !!}</span>
                </div>
            </div>

            <div style="clear: both;"></div>
            <div class="form-group" style="margin-top: 10px">
                <div class="col-md-6">

                    <input type="hidden" id="req_id" name="req_id" value="{!! $req_id !!}">
                    {!!Form::select('location_id',$locations,null,array('class'=>'form-control','id'=>'location_id','onchange'=>'showDetail(this,'.$item_id.',"stock")'))!!}

                </div>

                <div class="col-md-6">

                    {!!Form::text('satisfy_date',$req_date,array('class'=>'form-control','id'=>'satisfy_date','readonly'))!!}

                </div>
            </div>

            <div style="clear: both"></div>

            <div class="form-group col-md-12" id="stock-table" style="margin-top: 20px">
                    {{--add content--}}
            </div>
            <div style="clear: both"></div>

        </div>

        @elseif( $flag == 'stock')

            @if( isset($stock) && sizeof($stock) > 0 )

                <table id="stockitemprocess" class="table table-striped table-hover" >

                    <thead>
                        <th>Batch No.</th>
                        <th>Qty</th>
                        <th>Expiry Date</th>
                        <th>Remove Qty</th>
                    </thead>

                    <tbody>

                        <?php $n=1;?>
                        @foreach($stock as $stk )

                            @if( $n==1)
                                <tr hidden>
                                    <td colspan="4">
                                        <input type="hidden" name="item_id" value="{!! $stk->item_id !!}" />
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td>{!! $stk->transaction_id !!}</td>
                                <td>{!! $stk->quantity !!}</td>
                                <td>{!! $stk->expiry_date !!}</td>
                                <td>
                                    <input class="form-control" type="number" placeholder="Satisfied Quantity" id="satisfy_qty-{!! $stk->transaction_id !!}" name="satisfy_qty[{!! $stk->id !!}]" value=""/>
                                    <input type="hidden" name="transaction_id[]" value="{!! $stk->transaction_id !!}" />
                                </td>
                            </tr>

                            <?php $n++;?>
                        @endforeach
                    </tbody>
                </table>
            @else
                <span>Stock not available.</span>
            @endif

            <div style="clear: both;"></div>
            <label id="stockprocess_error" class="col-md-12 error" style="margin-top: 5px;"></label>

    @endif

</form>