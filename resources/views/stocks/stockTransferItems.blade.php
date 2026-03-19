<?php use App\Unit;?>

    @if(isset($items) && sizeof($items)>0)
        <table class="table table-hover" id="CategoriesTable">
            <thead>
            <th>Item Name</th>
            @if(isset($items[0]->title_id))
                <th>Category</th>
            @endif
            <th>Existing Qty</th>
            <th></th>
            <th>Transfer Qty</th>
            <th>Price</th>
            <th>Unit</th>
            </thead>

            <tbody>
            <?php $i=0; ?>
            @foreach($items as $item)
                <tr class="odd gradeX">

                    <td>{!! $item->item !!}<input type="text" class="hide" value="{!! $item->id !!}" name="item_id[]"></td>
                    @if(isset($item->title_id))
                        <td>{!! \App\MenuTitle::getmenutitlebyid($item->title_id)->title !!}</td>
                    @endif
                    <td>{!! $item->stock or 0 !!} {!! $item->unit !!}{{--<input type="text" class="hide" id="unit_id[]" name="unit_id[]" value="{!! $item->unit_id !!}">--}}</td>
                    <td><input type="text" class="hide" id="exi_qty[]" name="exi_qty[]" value="{!! $item->stock or '' !!}"></td>
                    <?php
                        if ($item->open_stock == 0 ) {
                            $item->open_stock = '';
                        }
                    ?>
                    <td>
                        <input type="number" value="{{ $item->open_stock<=0?'':$item->open_stock }}" id="transfer_qty[]" class="form-control" name="transfer_qty[]" min="0" maxlength="5" size="5" max="100000" placeholder="Transfer Qty">
                    </td>
                    <td>
                        <input type="number" value="{{ $item->buy_price<=0?'':$item->buy_price }}" id="price[]" class="form-control" name="price[]" min="0" maxlength="5" size="6" max="100000" placeholder="Price">
                    </td>
                    <td>
                        <select class="form-control" name="unit_id[]" class="unit_id[]" id="unit_id[]">
                            @foreach($item->other_unit as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </td>

                </tr>
            @endforeach
            {{-- <input type="hidden" value="{!! $user_id !!}" name="owner_id"> --}}
            <input type="text" class="hide" value="{!! $cat_id !!}" name="cate_id">
            <tr>
                <td></td>
                @if(isset($items[0]->title_id))
                    <td></td>
                @endif
                <td></td>
                <td></td>
                <td>
                    <button type="button" onclick="$('#transfer_content').show();$('#CategoriesTable').remove();" class="btn btn-primary col-md-4 pull-right" style="margin-left: 5px;">Cancel</button>
                    <button type="button" id="Submit" novalidate="novalidate" onclick="submitTransfer()" class="btn btn-primary col-md-4 pull-right" style="margin-left: 5px">Submit</button>
                </td>
            </tr>
            </tbody>
        </table>
    @else
        <div id="CategoriesTable">
            No Item is selected for Inventory.
        </div>
    @endif