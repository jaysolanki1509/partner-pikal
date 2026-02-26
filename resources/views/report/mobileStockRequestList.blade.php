<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper" style="overflow-x: auto;">

                    <table class="table table-striped table-bordered table-hover">
                        @if($data['request_type']=="request")
                            <tr>
                                <th>Date</th>
                                <th>Location For</th>
                                <th>Requested By</th>
                                <th>Item</th>
                                <th>Qty</th>
                                {{--<th>Action</th>--}}
                            </tr>
                        @elseif($data['request_type']=="response")
                            <tr>
                                <th>Date</th>
                                <th>From Location</th>
                                <th>Response From</th>
                                <th>Item</th>
                                <th>Qty</th>
                                {{--<th>Action</th>--}}
                            </tr>
                        @endif
                        @if($data['request_type']=="request")
                            @if(sizeof($data['request']) > 0)
                                @foreach($data['request'] as $request)
                                    <?php $item_name = \App\Owner::ownerById($request->owner_by)->user_name ?>
                                    <tr id="{!! $request->request_id !!}" data-item-id="{!! $request->what_item_id !!}">
                                        <td>{{ $request->when }}</td>
                                        <td>{{ \App\Location::getLocationById($request->location_for)->name }}</td>
                                        <td>{{ $item_name }}</td>
                                        <td>{{ $request->item }}</td>
                                        <td>{{ $request->qty }}</td>
                                        {{--<td>@if($request->satisfied != 'No') Satisfied
                                            @else <button id="req_satisfy" onclick="showDetail(this,'{!! $request->what_item_id !!}','open')" class="btn btn-primary" data-toggle="modal" data-qty="{{ $request->qty }}" data-itemid="{{ $request->id }}" data-requestid="{{ $request->request_id }}" data-itemname="{{ $request->item }}" data-id="" >Satisfy</button></a>
                                            @endif
                                            <input type="hidden" name="request_id[]" value="{!!  $request->request_id !!}"/>
                                        </td>--}}
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">You Don't have any request.  </td>
                                </tr>
                            @endif
                        @elseif($data['request_type']=="response")
                                @if(sizeof($data['request']) > 0)
                                    @foreach($data['request'] as $request)
                                        <?php $item_name = \App\Owner::ownerById($request->owner_by)->user_name ?>
                                        <tr id="{!! $request->request_id !!}" data-item-id="{!! $request->what_item_id !!}">
                                            <td>{{ $request->satisfied_when }}</td>
                                            <td>{{ \App\Location::getLocationById($request->location_from)->name }}</td>
                                            <td>{{ $item_name }}</td>
                                            <td>{{ $request->item }}</td>
                                            <td>{{ $request->statisfied_qty }}</td>
                                            {{--<td>@if($request->satisfied != 'No') Satisfied
                                                @else <button id="req_satisfy" onclick="showDetail(this,'{!! $request->what_item_id !!}','open')" class="btn btn-primary" data-toggle="modal" data-qty="{{ $request->qty }}" data-itemid="{{ $request->id }}" data-requestid="{{ $request->request_id }}" data-itemname="{{ $request->item }}" data-id="" >Satisfy</button></a>
                                                @endif
                                                <input type="hidden" name="request_id[]" value="{!!  $request->request_id !!}"/>
                                            </td>--}}
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">You Don't have any request.  </td>
                                    </tr>
                                @endif
                        @endif
                    </table>
                    {!! Form::input('hidden','request_data' , isset($print_data) ? $print_data : null, ['disabled','class' => 'form-control','id'=>'request_data' ] ) !!}
                </div>
            </div>
        </div>
    </div>
</div>