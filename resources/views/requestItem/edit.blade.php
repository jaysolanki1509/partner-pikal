@extends('partials.default')
@section('pageHeader-left')
    Edit Request
@stop

@section('pageHeader-right')
    <a href="/requestItem" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    @if(Session::has('error'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
                            {{ Session::get('error') }}
                        </div>
                    @endif
                    @if(Session::has('success'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
                            {{ Session::get('success') }}
                        </div>
                    @endif


                    {!! Form::model($request,['route' => array('requestItem.update',$request->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'requestForm', 'class' => 'form-horizontal material-form j-forms']) !!}

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::label('Item','Item:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        <select name="what_item_id" id="what_item_id" class="form-control">
                                            <option value="">Select Item</option>
                                            @if( isset($item_list) && sizeof($item_list) > 0 )
                                                @foreach( $item_list as $itm )
                                                    <option @if( isset($request->what_item_id) && $request->what_item_id == $itm['id'] ) selected @endif value="{{ $itm['id'] }}" data-item="{{ $itm['name'] }}" data-order-unit="{{ $itm['order_unit'] }}" data-unit-name="{{ $itm['unit_name'] }}" data-unit-id="{{ $itm['unit_id'] }}">{{ $itm['name'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <input type="hidden" name="what_item" id="what_item" value="@if( isset($request->what_item) && $request->what_item != '' ){{$request->what_item}}@endif" >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                    {!! Form::label('existing_qty','Existing Qty:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('existing_qty',null,array('id' => 'existing_qty', 'placeholder'=> 'Existing Qty','class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    {!! Form::label('existing_unit_lbl',' ', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('existing_unit',$unit,isset($request->unit_id)?$request->unit_id:null,array('id' => 'existing_unit','class' => 'col-md-6 form-control','disabled')) !!}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    {!! Form::label('required_qty','Required Qty:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('qty',null,array('id' => 'qty', 'placeholder'=> 'Required Qty','class' => 'col-md-6 form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    {!! Form::label('unit_lbl',' ', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('unit_id',$ot_unit,null,array('id' => 'unit_id','class' => 'col-md-6 form-control')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    {!! Form::label('location_for','Location For:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('location_for',$locations,$request->location_for,array('id' => 'location_for','class' => 'col-md-3 form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('owner_to','Owner To:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('owner_to',$owners,null,array('id' => 'owner_to','class' => 'col-md-3 form-control')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="form-group">
                                <div class="col-md-6">
                                    {!! Form::label('when','Request Date:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('when',null,array('id' => 'when', 'placeholder'=> 'Request date','class' => 'col-md-3 form-control','readonly')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-footer">
                            <div class="col-md-8">
                                <button name='saveExit' id='saveExit' class="btn btn-primary mr5" type="Submit" value='true'>Update</button>
                                <a href="/requestItem" name='cancel' id='cancel' class="btn btn-danger mr5">Cancel</a>
                            </div>
                        </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

    @section('page-scripts')

        <script src="/assets/js/new/lib/jquery.validate.js"></script>
        <script type="text/javascript">

            $(document).ready(function() {

                $('#existing_unit').select2({
                    placeholder: 'Select Unit'
                });
                $('#unit_id').select2({
                    placeholder: 'Select Unit'
                });
                $('#location_for').select2({
                    placeholder: 'Select Location'
                });
                $('#owner_to').select2({
                    placeholder: 'Select Owner'
                });

                $('#what_item_id').select2();

                $('#when').each(function(){
                    $(this).DatePicker({
                        format: "yyyy-mm-dd",
                        orientation: "auto",
                        autoclose: true,
                        todayHighlight: true
                    });
                });


                $('#what_item_id').on("change", function() {

                    var i_id = $(this).val();
                    var item = $(this).find(':selected').data('item');
                    var unit_id = $(this).find(':selected').data('unit-id');
                    var unit_name = $(this).find(':selected').data('unit-name');

                    var order_unit = $(this).find(':selected').data('order-unit');

                    $('#what_item').val(item);
                    //$('#item_text').val(i_text);

                    //change existing units
                    $('#existing_qty').val('');

                    $('#existing_unit').select2('data', null);

                    var ex_sel = $('#existing_unit');
                    ex_sel.empty();
                    ex_sel.append($("<option></option>")
                            .attr("value", unit_id).text(unit_name));

                    ex_sel.val(unit_id);

                    $.ajax({
                        url: '/get-item-other-units',
                        type: "POST",
                        data: {id: i_id},
                        success: function (data) {

                            var select = $('#unit_id');
                            select.empty();

                            var cnt = 1;
                            $.each(data, function(key,value) {

                                if ( cnt == 1 ) {

                                    select.append($("<option></option>")
                                            .attr("value", key).text(value));
                                    select.val(key).change();

                                } else {
                                    select.append($("<option></option>")
                                            .attr("value", key).text(value));
                                }
                                cnt++;
                            });
                            select.removeAttr('disabled');
                        }
                    });

                });


                $("#requestForm").validate({
                    rules: {
                        "location_for":'required',
                        "when":'required',
                        "owner_to":'required',
                        'required_qty':'required',
                        'what_item_id':'required'
                    },
                    messages: {
                        "location_for":'location for is required',
                        "when":'Request date is required',
                        "owner_to":'Owner to is required',
                        'required_qty':'Required qty is required',
                        'what_item_id':'Item is required'
                    }

                })


            })

        </script>
    @stop


@stop

