<?php use App\Unit; ?>

    <div class="col-md-12">
        <div class="widget-wrap">
            @if(isset($items) && sizeof($items)>0)
                <form class="form-horizontal material-form j-forms" role="form" method="POST" id="requestItem" action="{{ url('/requestItem') }}" >
                    <input name="owner_id" type="hidden" value="" id="owner_id1" />
                    <input name="location_id" type="hidden" value="" id="location_id1" />

                    <input name="req_date" type="hidden" value="" id="req_date1" />
                    <div class="form-footer">
                        <div class="col-md-12">
                            <button type="button" id="Submit" novalidate="novalidate" onclick="submitClick()" class="btn primary-btn btn-success">Submit</button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        {{--<div class="col-md-6">
                            Location : &nbsp;<label name="location_name">{{ $location_name }}</label>
                        </div>
                        <div class="col-md-6">
                            Date : &nbsp;<label name="req_date">{{ $req_date }}</label>
                        </div>--}}
                        <table class="table foo-data-table" id="CategoriesTable">
                            <thead>
                                <th>Item Name</th>
                                @if(isset($items[0]['title_id']))
                                    <th>Category</th>
                                @endif
                                <th>Existing Qty</th>
                                <th></th>
                                <th>Request Qty</th>
                                <th>Unit</th>
                            </thead>

                            <tbody>
                            <?php $i=0; ?>
                            @foreach($items as $item)
                                <tr class="odd gradeX">
                                    @if($i==0)
                                        <input type="hidden" value="{!! $item['id'] !!}" name="min_id">
                                        <?php $i++ ?>
                                    @endif
                                    <td>{!! $item['item'] !!}</td>
                                    @if(isset($item['title_id']))
                                        <td>{!! \App\MenuTitle::getmenutitlebyid($item['title_id'])->title !!}</td>
                                    @endif
                                    <td>{!! $item['stock'] or 0 !!} {!! $item['unit'] !!}</td>
                                    <td><input type="hidden" id="exi_qty-{!! $item['id'] !!}" name="exi_qty-{!! $item['id'] !!}" value="{!! $item['stock'] or '' !!}"></td>
                                    <?php
                                        if ($item['open_stock'] == 0 ) {
                                            $item['open_stock'] = '';
                                        }
                                    ?>
                                    <td>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <input type="number" value="{{ $item['open_stock']<=0?'':$item['open_stock'] }}" id="req_qty-{!! $item['id'] !!}" class="form-control" name="req_qty-{!! $item['id'] !!}" min="0" maxlength="5" size="5" max="100000" placeholder="Request Qty">
                                                </div>

                                                <div class="col-md-4">
                                                    <select name="unit_id-{!! $item['id'] !!}" class="form-control select2">
                                                        @if( isset($item['other_unit']) && sizeof($item['other_unit']))
                                                            @foreach( $item['other_unit'] as $other )
                                                                <option value="{!! $other['id'] !!}" @if( $other['id'] == $item['order_unit']){!! 'selected' !!} @endif>{!! $other['name'] !!}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                                <input type="hidden" value="{!! $item['id'] !!}" name="count">
                            @endforeach
                            {{-- <input type="hidden" value="{!! $user_id !!}" name="owner_id"> --}}
                             <input type="hidden" value="{!! $cate_id !!}" name="cate_id">
                             <tr>
                                 <td></td>
                                 @if(isset($items[0]['title_id']))
                                     <td></td>
                                 @endif
                             </tr>
                             </tbody>
                        </table>
                    </div>
                    <div class="form-footer">
                        <div class="col-md-12">
                            <button type="button" id="Submit" novalidate="novalidate" onclick="submitClick()" class="btn primary-btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            @else
                No item found in Request. Please select from Location->Set Stock Level
            @endif

        </div>
    </div>