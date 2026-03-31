<form id="prepareForm" method="post" class="material-form j-forms">
    <table class="table">
        <thead>
            <tr>
                <th>Ingredient Name</th>
                <th>Qty</th>
                <th>From Location</th>
                <th>Stock Available</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($ingred) && !empty($ingred) )
                @foreach( $ingred as $ing )
                    <tr id="{!! $ing['ingrd_id'] !!}">
                        <td>{!! $ing['ingrd_name'] !!}</td>
                        <td>
                            <input type="hidden" name="item_id[]" value="{!! $ing['ingrd_id'] !!}"/>
                            <input name="quantity[]" type="text" readonly class="form-control loc_qty" value="{!! $ing['qty'] !!} {!! $ing['unit_name'] !!}" />
                        </td>
                        <td>
                            <select class="loc_select form-control select2" name="location[]">
                                <option value="">Select Location</option>
                                @if( isset($locations) && !empty($locations) )
                                    @foreach( $locations as $id => $val )
                                        <?php
                                            $loc_qty = \App\Stock::join('menus','menus.id','=','stocks.item_id')
                                                        ->leftjoin('unit as u','u.id','=','menus.unit_id')->where('item_id',$ing['ingrd_id'])
                                                        ->where('location_id',$id)
                                                        ->first();
                                            if ( isset($loc_qty) && !empty($loc_qty) ) {
                                                $loc_qty = $loc_qty->quantity." ".$loc_qty->name;
                                            } else {
                                                $loc_qty = 0;
                                            }
                                        ?>
                                        <option value="{!! $id !!}" data-stock="{!! $loc_qty !!}">{!! $val !!}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                        <td><input readonly type="text" class="form-control loc_stock" name="loc_stock[]" value="" /></td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <div style="clear: both"></div>
    <hr>
    @if(isset($ingred) && !empty($ingred))
        <div class="col-md-12"><button id="prepare" class="col-md-4 btn btn-primary" style="left:33.3%;" type="button">Prepare</button></div>
    @endif
</form>
