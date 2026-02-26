<form id="stock-detail-form">
<div class="form-group" id="stock-details">
    @if( $flag == 'open')
        <div class="form-group">
            <input type="hidden" id="req_id" value="{!! $req_id !!}">
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
        <div class="form-group col-md-12" style="margin-top: 10px">
                <select id="stk_location_id" onchange="$('#stock_qty').text($(this).find(':selected').data('stock'))" name="stk_location_id" class="form-control">
                    <option value="" data-stock="0">Select Location</option>
                    @if(isset($locations) && sizeof($locations) > 0 )

                        @foreach( $locations as $loc )
                            <option value="{!! $loc->id !!}" data-stock="{!! isset($stock[$loc->id])?$stock[$loc->id]:'0' !!}">{!! $loc->name !!}</option>
                        @endforeach
                    @endif
                </select>
        </div>
        <div class="form-group col-md-12" style="margin-top: 10px">
        <span>Stock Available:</span><span id="stock_qty" style="font-weight: bold;font-size: larger;margin-left: 5px;" >0</span>
        </div>
            <div style="clear: both"></div>

    @endif
</div>

</form>
