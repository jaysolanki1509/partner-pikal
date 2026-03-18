<?php

    $item_arr = old('item_id');
    $item_text_arr = old('item_text');
    $qty_arr = old('quantity');
    $rate_arr = old('rate');
    $unit_id_arr = old('unit_id');
    $unit_name_arr = old('unit_name');
    $amt_arr = old('amount');
    $manufacture_date_arr = old('manufacture_date');

    $vend_id = old('vendor_id');
    $vend_text = old('vendor_text');

?>


<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">

                @if($action=='edit')
                    {!! Form::model($invoice,['route' => array('purchase.update',$invoice->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'purchaseForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                @else
                    {!! Form::open(['route' => 'purchase.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms','novalidate'=>"novalidate", 'id' => 'purchaseForm']) !!}
                @endif

                <div class="form-group">
                    <div class="row">

                        <div class="col-md-6">
                            {!! Form::label('location_id','Location:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::select('location_id',$locations,$selected_location,array('id' => 'location_id','class' => 'form-control','required')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            {!! Form::label('invoice_date','Invoice Date:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('invoice_date', isset($invoice->invoice_date)?$invoice->invoice_date:\Carbon\Carbon::now()->format("Y-m-d"), ['class' => 'form-control','placeholder'=>"Select Date","id"=>"invoice_date","readonly"=>"readonly"]) !!}
                            </div>
                        </div>

                    </div>
                </div>


                <div class="form-group">
                    <div class="row">

                        <div class="col-md-6">
                            {!! Form::label('status','Status:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::select('status',array('liability' => 'Liability', 'invoice' => 'Invoice', 'paid' => 'Paid'),isset($invoice->status)?$invoice->status:'liability',array('id' => 'status','class' => 'col-md-3 form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            {!! Form::label('invoice_no','Invoice No:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('invoice_no',null,array('id' => 'invoice_no', 'placeholder'=> 'Invoice no','class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::label('vendor','Vendor:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::select('vendor_id', $vendors, isset($invoice->vendor_id)?$invoice->vendor_id:'',array('class'=>'form-control','id' => 'vendor_id','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                @if ( isset($item_arr) && sizeof($item_arr) > 0 )
                    @for( $i=0; $i<sizeof($item_arr); $i++ )

                        <div id="item_div-0" class="item-div">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! Form::label('item_id','Item:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            @if( $i == sizeof($item_arr) - 1 )
                                                <input type="text" name="item_id[]" value="{!! $item_arr[$i] !!}" class="item-id item-auto form-control" placeholder="Item" >
                                                <input type="text" name="item_text[]" class="item-text hide" value="{!! $item_text_arr[$i] !!}">
                                            @else
                                                <input type="text" name="item_id[]" value="{!! $item_arr[$i] !!}" class="item-id item-auto hide" placeholder="Item" >
                                                <input type="text" name="item_text[]" class="item-text form-control" value="{!! $item_text_arr[$i] !!}" readonly>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-2">
                                    <div class="col-md-4">
                                        {!! Form::label('unit_id[]','Unit:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            {!! Form::select('unit_id[]',$units,'0',array('class' => 'unit-id form-control','disabled'=>'disabled')) !!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        {!! Form::label('quantity','Quantity:', array('class' => 'col-md-12 control-label')) !!}) !!}
                                        <div class="col-md-12">
                                            <input type="number" min="0" name="quantity[]" value="{!! $qty_arr[$i] !!}" class="quantity col-md-3 form-control" placeholder="Quantity">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        {!! Form::label('rate','Rate:') !!}
                                        <div class="col-md-12">
                                            <input type="number" min="0" name="rate[]" value="{!! $rate_arr[$i] !!}" class="rate col-md-3 form-control" placeholder="Rate">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        {!! Form::label('amount[]','Amount:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            <input type="text" value="{!! $amt_arr[$i] !!}" name="amount[]" class="amount col-md-3 form-control" placeholder="Amount" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! Form::label('manufacture_date[]','Manufacturing Date:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            <input type="text" name="manufacture_date[]" value="{!! $manufacture_date_arr[$i] !!}" class="manufacture_date col-md-3 form-control" placeholder="Manufacturing Date">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('received_date[]','Received Date:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            <input type="text" name="received_date[]" value="{!! isset($received_date_arr[$i])?$received_date_arr[$i]:null !!}" class="received_date col-md-3 form-control" placeholder="Received Date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-12 pull-right">
                                    <button type="button" class="btn @if( $i != sizeof($item_arr) -1 ) hide @endif btn-primary add-item" style="margin-top: 10px;" title="Add Item" onclick="cloneItem(this)">Add Item</button>
                                    <button type="button" class="btn btn-danger romove-item-btn" style="@if( sizeof($item_arr) == 1 )display:none;@endif;margin-top: 10px;"  title="Remove Item" onclick="removeItem(this)"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                        </div>

                    @endfor

                @elseif ( $action == 'edit' )

                    @if ( isset($items) && sizeof($items) > 0 )
                        <?php $i=0; ?>
                        @foreach( $items as $itm )

                            <div id="item_div-0" class="item-div">
                                <input type="hidden" class="purchase-id" name="purchase_id[]" value="{!! $itm->id !!}" />
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8">
                                            {!! Form::label('item_id','Item:', array('class' => 'col-md-12 control-label')) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('item_id[]',$item_list, $itm->item_id,array('class'=>'item-id item-auto form-control', 'placeholder'=> 'Item','required')) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('unit_id[]','Unit:', array('class' => 'col-md-12 control-label')) !!}
                                            <div class="col-md-12">
                                                <?php
                                                    $other_unit = \App\Menu::getOtherUnits($itm->item_id);
                                                    if ( isset($other_unit) && sizeof($other_unit) > 0 ) {?>
                                                        <select name="unit_id[]" class="unit-id form-control">
                                                        @foreach( $other_unit as $key=>$ou )
                                                            <option value="{!! $key !!}" @if($key == $itm->unit_id){!! 'selected' !!}@endif>{!! $ou !!}</option>
                                                        @endforeach
                                                        </select>
                                                    <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {!! Form::label('quantity','Qty:', array('class' => 'col-md-12 control-label'))  !!}
                                            <div class="col-md-12">
                                                <input type="number" min="0" name="quantity[]" value="{!! $itm->quantity !!}" class="quantity col-md-3 form-control" placeholder="Quantity">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('rate','Rate:', array('class' => 'col-md-12 control-label')) !!}
                                            <div class="col-md-12">
                                                <input type="number" min="0" name="rate[]" value="{!! $itm->rate !!}" class="rate col-md-3 form-control" placeholder="Rate">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('amount[]','Amount:', array('class' => 'col-md-12 control-label')) !!}
                                            <div class="col-md-12">
                                                <input type="text" value="{!! $itm->total !!}" name="amount[]" class="amount col-md-3 form-control" placeholder="Amount" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('manufacture_date[]','Manufacturing Date:', array('class' => 'col-md-12 control-label')) !!}
                                            <div class="col-md-12">
                                                <input type="text" disabled name="manufacture_date[]" value="@if(isset($itm->manufacture_date) && $itm->manufacture_date != '0000-00-00'){!! $itm->manufacture_date !!}@endif" class="manufacture_date col-md-3 form-control" placeholder="Manufacturing Date">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('received_date[]','Received Date:', array('class' => 'col-md-12 control-label')) !!}
                                            <div class="col-md-12">
                                                <input type="text" disabled name="received_date[]" value="@if(isset($itm->received_date) && $itm->received_date != '0000-00-00'){!! $itm->received_date !!}@endif" class="received_date col-md-3 form-control" placeholder="Received Date">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <div class="col-md-12 pull-right">
                                        @if( $i == sizeof($items) -1 )
                                            <button type="button" class="btn btn-primary add-item" style="margin-top: 10px;" title="Add Item" onclick="cloneItem(this)">Add Item</button>
                                        @endif
                                        <button type="button" class="btn @if( sizeof($items) == 1 ) hide @endif btn-danger romove-item-btn" style="margin-top: 10px;"  title="Remove Item" onclick="removeItem(this)"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                            </div>

                            <?php $i++;?>
                        @endforeach
                    @endif

                @else

                    <div id="item_div-0" class="item-div">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::label('item_id','Item:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('item_id[]',$items, null,array('class'=>'form-control item-auto item-id','required')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::label('unit_id[]','Unit:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('unit_id[]',$units,'0',array('class' => 'unit-id form-control','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    {!! Form::label('quantity','Qty:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::input('number','quantity[]',null,array('min'=>'0','class' => 'quantity col-md-3 form-control', 'placeholder'=> 'Quantity')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::label('rate','Rate:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::input('number','rate[]', null, array('min'=>'0','class' => 'rate col-md-3 form-control', 'placeholder'=> 'Rate')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::label('amount[]','Amount:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('amount[]', null, array('class' => 'amount col-md-3 form-control', 'placeholder'=> 'Amount',"readonly"=>"readonly")) !!}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::label('manufacture_date[]','Manufacturing Date:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('manufacture_date[]', null, array('class' => 'manufacture_date col-md-3 form-control', 'placeholder'=> 'Manufacture Date',"readonly"=>"readonly")) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    {!! Form::label('received_date[]','Received Date:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('received_date[]', null, array('class' => 'received_date col-md-3 form-control', 'placeholder'=> 'Received Date',"readonly"=>"readonly")) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($action != 'edit')
                            <div class="form-group col-md-12">
                                <div class="col-md-12 pull-right">
                                    <button type="button" class="btn btn-primary add-item" style="margin-top: 10px;" title="Add Item" onclick="cloneItem(this)"><i class="fa fa-plus"></i> Item</button>
                                    <button type="button" class="btn btn-danger romove-item-btn" style="display:none;margin-top: 10px;"  title="Remove Item" onclick="removeItem(this)"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                        @endif

                    </div>


                @endif

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::label('total_amount','Total Amount:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('total_amount',isset($invoice->total)?$invoice->total:'', ['class' => 'form-control','placeholder'=>"Total Amount","id"=>"total_amount","readonly"=>"readonly"]) !!}
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-footer">

                    @if($action=='edit')
                        <button name='saveExit' id='saveExit' class="btn primary-btn btn-success" type="Submit" value='true'>Update</button>
                        {!! HTML::decode(HTML::linkRoute('purchase.index','Cancel', array(), array('class'=>'btn btn-danger secondary-btn', 'id'=>''))) !!}
                    @else
                        <button name='saveExit' id='saveExit'  class="btn primary-btn btn-success"  novalidate="novalidate" type="Submit" value="true" >Save & Exit</button>
                        <button name='saveContinue' id='saveContinue' class="btn primary-btn btn-success"  novalidate="novalidate" type="Submit" value="true">Save & Continue</button>
                        <button id='reset_form' class="btn btn-danger secondary-btn" type="reset">Reset</button>
                    @endif

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

        //$('#vendor_id').select2();
        //$('#location_id').select2();
        //$('#status').select2();
        //$(".item-id,.unit-id").select2();
        /*make select box enable for post value*/
        $('#purchaseForm').submit(function() {
            $('.unit-id').removeAttr('disabled');
        });

        $("#purchaseForm").validate({
            ignore: [],
            rules: {
                "location_id":{
                    required: true
                },
                "item_id[]": {
                    required: true
                },
                "vendor_id":{
                    required: true
                },
                'location_id':{
                    required:true
                },
                "quantity[]":{
                    required: true
                },
                "rate[]":{
                    required: true
                },
                "unit_id[]":{
                    required: true
                },
                "invoice_no" :{
                    required: function(element) {
                        return $('#status').val() == 'invoice'?true:false;
                    }
                }
            },
            messages: {
                "location_id": {
                    required: "Location is required"
                },
                "item_id[]": {
                    required: "Item is required"
                },
                "vendor_id": {
                    required: "Vendor is required"
                },
                'location_id':{
                    required: "Location is required"
                },
                "quantity[]": {
                    required: "Qty is required"
                },
                "rate[]": {
                    required: "Rate is required"
                },
                "unit_id[]": {
                    required: "Unit is required"
                },
                "invoice_no": {
                    required: "Invoice Number is required"
                }
            }

        });

        /*provide for fix ui in mobile*/
        //$('.page-header-right').remove();
        //$('.page-header-left').css('width','100%');

        $('#invoice_date,.manufacture_date,.received_date').each(function(){
            $(this).DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });
        });
        init_autocomplete('load');

        /*populate value in item field*/
        @if( isset($item_arr) && sizeof($item_arr) > 0 )
            @for( $j=0; $j<sizeof($item_arr);$j++)
                var cnt = '<?php echo $j;?>';
                var itm_id = '<?php echo $item_arr[$j];?>';
                var itm_text = '<?php echo $item_text_arr[$j];?>';

            @endfor
        @endif

        $.validator.addMethod("valueNotEquals", function(value, element, arg){
            return arg != value;
        }, "*Outlet is required.");


        //add text of vendor on select for edit and error handling time
        $('.vendor-auto').on("select2-selecting", function(e) {
            var v_text = e.choice.text;
            $('#vendor_text').val(v_text);
        });

        @if( isset($vend_id))
            $("#vendor_id").select2('data', {id:'<?php echo $vend_id;?>' , text: '<?php echo $vend_text;?>'});
        @endif

})


        /*initialize autocomplete*/
    function init_autocomplete(flag) {

        /* item autocomplete */
        if ( flag == 'load') {


        } else {

            //$('.item-div:eq(-1) .item-text').addClass('hide');
            //$('.item-div:eq(-1) .item-id').removeClass('hide');


            /*make empty*/
            if ($('.item-div').length != 1 ) {
                //$(".item-div:last .item-id").select2("val", "");
                //$('.item-div:last .item-id').val('');
                //$('.item-div:last .item-text').val('');
                //$('.item-div:last .item-id').select2('open');
                $(".item-div:last .item-id").val('');
            }
        }

        /*make total of quantity and rate*/
        $('.quantity,.rate').on('keyup input',function(){

            if ($(this).hasClass('rate')) {
                var rate = $(this).val();
                var qty = $(this).closest('.item-div').find('.quantity').val();
            } else {
                var qty = $(this).val();
                var rate = $(this).closest('.item-div').find('.rate').val();
            }


            if ( qty ) {
                var val = rate * qty;
                $(this).closest('.item-div').find('.amount').val(val);
            }
            var rate = $(this).val();
            var qty = $(this).closest('.item-div').find('.quantity').val();
            if(rate == '' && qty == ''){
                $(this).closest('.item-div').find('.amount').val(0);
            }

            /*make total on quantity and rate change*/
            makeTotal();
        });

        $('.item-auto').on("change", function() {

            // what you would like to happen

            //$(e.currentTarget).closest('.item-div').find('.item-id').val(i_id);
            //$(e.currentTarget).closest('.item-div').find('.item-text').val(i_text);
            var i_id = $(this).val();
            var drpdown = $(this);

            $.ajax({
                url: '/get-item-other-units',
                type: "POST",
                data: {id: i_id},
                success: function (data) {

                    var select = drpdown.closest('.item-div').find('.unit-id');

                    //select.empty();
                    select.val(data);
//                    var cnt = 1;
//                    $.each(data, function(key,value) {
//
//                        if ( cnt == 1 ) {
//
//                            //select.append($("<option></option>").attr("value", key).text(value));
//                            select.val(key);
//
//                        } else {
////                            select.append($("<option></option>").attr("value", key).text(value));
//                        }
//                        cnt++;
//                    });
                    select.removeAttr('disabled');
                }
            });
        });
    }

    /*calculate total purchase*/
    function makeTotal() {

        var total = 0;
        $('.amount').each(function(){
            total = total + Number($(this).val());
        });
        $('#total_amount').val(total);
    }

    /*Add new item div */
    function cloneItem(el) {

        $(el).closest('.item-div').find('label.error').remove();

        var item_text = $(el).closest('.item-div').find('.item-id').val();
        var quantity = $(el).closest('.item-div').find('.quantity').val();
        var rate = $(el).closest('.item-div').find('.rate').val();

        if ( quantity == '' || item_text == '' || rate == '' ) {

            if ( quantity == '') {
                $('<label generated="true" class="error">Qty is required</label>').insertAfter($(el).closest('.item-div').find('.quantity'));
            }
            if ( item_text == 'Item') {
                $('<label generated="true" class="error">Item is required</label>').insertAfter($(el).closest('.item-div').find('.item-id'));
            }
            if ( rate == '') {
                $('<label generated="true" class="error">Rate is required</label>').insertAfter($(el).closest('.item-div').find('.rate'));
            }

            return;
        }

        //$('.item-id').select2('destroy');


        var ele = $(el).closest('.item-div').clone(true).insertAfter('.item-div:last');

        var drpdwn = $('.item-div:last').find('.item-id').trigger('event','click');

        //remove purchase id on clone div
        ele.find('.purchase-id').remove();
        ele.find('input.received_date')
                .attr("id", "")
                .removeClass('hasDatepicker')
                .removeData('datepicker')
                .unbind()
                .DatePicker({
                    format: "yyyy-mm-dd",
                    orientation: "auto",
                    autoclose: true,
                    todayHighlight: true
        });
        ele.find('input.manufacture_date')
                .attr("id", "")
                .removeClass('hasDatepicker')
                .removeData('datepicker')
                .unbind()
                /*.datepicker({
                    dateFormat: "yy-mm-dd",
                    maxDate: new Date
                });*/
        /*$('.item-div:eq(-2) .item-text').removeClass('hide');
        $('.item-div:eq(-2) .item-text').prop("readonly", true);//make readonly previous autocomplete
        $('.item-div:eq(-2) .item-id').addClass('hide');*/

        //remove add button from parent
        $(el).addClass('hide');
        $(el).parent().find('.btn-danger').removeClass('hide');

        $('.romove-item-btn').css('display','');

        /*make empty elements*/
        $('.item-div:last .item-id').val('');
        $('.item-div:last .unit-id').val('0');
        $('.item-div:last .quantity').val('');
        $('.item-div:last .rate').val('');
        $('.item-div:last .amount').val('');

        //display remove button
        $(ele).find('.btn-danger').removeClass('hide');

        init_autocomplete('add');
    }

    function removeItem(el) {

        var r = confirm("Are you sure, You want to remove this item?");

        if (r == true) {

            var id = $(el).closest('.item-div').find('.purchase-id').val();
            var loc_id = $('#location_id').val();

            //remove purchase item if available in table
            if ( id ) {

                $.ajax({
                    url:'/remove-purchase-item',
                    type:'POST',
                    data:'id='+id+'&location_id='+loc_id,
                    success:function(data){
                        if ( data == 'error' ) {
                            successErrorMessage('Please try again later.','error');
                            return;
                        }
                    }
                })
            }

            successErrorMessage('Item has been deleted successfully.','success');

            //remove item
            $(el).closest('.item-div').remove();

            $(".item-div").on("click", ".add-item", function(){
                cloneItem(this);
            });

            //remove delte button if only one item is available
            if ($('.item-div').length == 1 ) {
                $('.romove-item-btn').addClass('hide');
            }

            //show add item in last item div
            $('.add-item:last').removeClass('hide');

            makeTotal();

        }

    }


</script>
@stop