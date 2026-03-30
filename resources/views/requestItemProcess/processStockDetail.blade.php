<?php  use App\Unit;$count=0; ?>
@if ( isset($requests) && !empty($requests) )
    @foreach( $requests as $item_request )

        <?php
        $categories = \App\ItemRequest::Join('menus','menus.id','=','item_request.what_item_id')->Join('menu_titles','menu_titles.id','=','menus.menu_title_id')->select('item_request.id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id', 'menus.menu_title_id', 'menus.item', 'menu_titles.title')->groupBy('menu_titles.title')->where('item_request.owner_to','=',$owner_id)->where('item_request.owner_by','=',$item_request->owner_by)->where('item_request.satisfied','=',"No")->get();
        ?>

        <div id="data_div">
            @if( isset($categories) && !empty($categories))

                @foreach($categories as $category)
                    <div class="col-md-12">
                        <h3 style="text-align: center;font-weight: bold;">{!! $category->title !!}</h3>
                    </div>

                    <?php
                    $requests = \App\ItemRequest::Join('menus','menus.id','=','item_request.what_item_id')
                            ->Join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                            ->Join('unit as u','u.id','=','item_request.unit_id')
                            ->select('item_request.id as item_id', 'item_request.what_item_id','item_request.unit_id as req_unit_id','menus.order_unit as order_unit','menus.secondary_units as other_units', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id','menus.unit_id', 'menus.menu_title_id', 'u.name as unit', 'menu_titles.title')
                            ->where('item_request.owner_by','=',$item_request->owner_by)
                            ->where('item_request.owner_to','=',$owner_id)
                            ->where('menus.menu_title_id','=',$category->menu_title_id)
                            ->where('item_request.satisfied','=',"No")->get();
                    ?>
                <div class="dataTable_wrapper">

                    <table id="requestitemprocess" class="table table-striped table-hover" >
                        <thead>
                            <th>Requested Date</th>
                            <th>Requested Item</th>
                            <th>Requested Qty</th>
                            <th>Location</th>
                            <th>Available Qty</th>
                            <th>Satisfied Qty</th>
                            <th></th>
                        </thead>

                        <tbody>

                        @if($requests && !empty($requests))

                            @foreach($requests as $request)
                                <tr id="{!! $request->item_id !!}" data-item-id="{!! $request->what_item_id !!}">
                                    <td>{!! $request->when !!}</td>
                                    <td>{!! $request->what_item !!}</td>
                                    <td class="text-center">{!! $request->qty !!} {!! $request->unit !!}</td>
                                    <td>{!! $loc_text !!}</td>
                                    <?php
                                        $st_detail = \App\Stock::join('menus','menus.id','=','stocks.item_id')->join('unit','unit.id','=','menus.unit_id')->where('item_id',$request->what_item_id)->where('location_id',$loc_id)->first();
                                        if ( isset($st_detail) && !empty($st_detail) ) {
                                            $st_qty = $st_detail->quantity." ".$st_detail->name;
                                        } else {
                                            $st_qty = 0;
                                        }
                                    ?>
                                    <td>{!! $st_qty !!}</td>
                                    <td>
                                        <div class="col-md-8">
                                            <input type="number" placeholder="Satisfied Quantity" name="satisfied_qty[]" class="form-control" value="" min="0"/>
                                        </div>
                                        <div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">
                                            <select name="unit_id[]" class="form-control">
                                                <option value="{!! $request->unit_id !!}">{!! Unit::find($request->unit_id)->name !!}</option>
                                                <?php
                                                if( isset($request->other_units) && $request->other_units != '' ) {
                                                    $units = json_decode($request->other_units);
                                                    if ( isset($units) && $units != '' ) {
                                                        foreach( $units as $key=>$u ) {

                                                            if ( $key == $request->unit_id )continue;
                                                            $un_name = Unit::find($key);
                                                        ?>
                                                            <option value="{!! $key !!}" @if($key == $request->order_unit) {!! 'selected' !!} @endif>{!! $un_name->name !!}</option>
                                                      <?php  }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>


                                    </td>
                                    <td>

                                        <button type="button" class="btn btn-primary" onclick="changeLocation(this,'{!! $request->what_item_id !!}','open')" ><i class="fa fa-edit"></i>
                                            @if($view == 'web')
                                                Location
                                            @endif
                                        </button>
                                        <input type="hidden" name="request_id[]" value="{!!  $request->item_id !!}"/>
                                        <input type="hidden" class="location_class" name="loc_id[]" value="{!! $loc_id !!}">

                                    </td>

                                </tr>
                                <?php if($count <= $request->item_id){
                                    $count = $request->item_id;
                                    $count++;
                                } ?>
                            @endforeach

                        @else
                            <tr>
                                <td>You Don't have any request.  </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                @endforeach

            @endif

        </div>
    @endforeach
    <button type="submit" class="btn btn-primary pull-right" style="padding:5px 50px;">Submit</button>
@endif