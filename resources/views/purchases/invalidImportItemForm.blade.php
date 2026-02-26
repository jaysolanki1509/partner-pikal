@extends('partials.default')
@section('pageHeader-left')
    Invalid Purchase Edit
@stop

@section('pageHeader-right')
    <a href="/invalid-import-items" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('success') }}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times</button>
            {{ Session::get('error') }}
        </div>
    @endif


    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-container">
                    <div class="widget-content">

                        {!! Form::model($invoice,['route' => array('purchase.updateInvalid',$invoice->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'purchaseForm', 'class' => 'form-horizontal material-form j-forms']) !!}

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">
                                    {!! Form::label('location_id','Location:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('location_id',$locations,$invoice->location_id,array('disabled ','id' => 'location_id','class' => 'form-control','required')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::label('invoice_date','Invoice Date:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('invoice_date', isset($invoice->invoice_date)?$invoice->invoice_date:\Carbon\Carbon::now()->format("Y-m-d"), ['disabled','class' => 'form-control','placeholder'=>"Select Date","id"=>"invoice_date","readonly"=>"readonly"]) !!}
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">
                                    {!! Form::label('status','Status:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('status',array('liability' => 'Liability', 'invoice' => 'Invoice', 'paid' => 'Paid'),isset($invoice->status)?$invoice->status:'liability',array('disabled','id' => 'status','class' => 'col-md-3 form-control')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::label('invoice_no','Invoice No:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('invoice_no',null,array('disabled','id' => 'invoice_no', 'placeholder'=> 'Invoice no','class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::label('vendor','Vendor:', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('vendor_id', $vendors, isset($invoice->vendor_id)?$invoice->vendor_id:'',array('disabled','class'=>'form-control','id' => 'vendor_id','required')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="item_div-0" class="item-div">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! Form::label('item_id','Item:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            {!! Form::select('item_id',$item_list, null,array('class'=>'form-control item-auto item-id','required')) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        {!! Form::label('unit_id','Unit:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            {!! Form::select('unit_id',$units,null,array('class' => 'unit-id form-control','disabled'=>'disabled')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        {!! Form::label('quantity','Qty:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                                {!! Form::input('number','quantity',null,array('min'=>'0','class' => 'quantity col-md-3 form-control', 'placeholder'=> 'Quantity')) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        {!! Form::label('rate','Rate:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            {!! Form::input('number','rate', null, array('min'=>'0','class' => 'rate col-md-3 form-control', 'placeholder'=> 'Rate')) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        {!! Form::label('amount','Amount:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            {!! Form::text('amount', null, array('class' => 'amount col-md-3 form-control', 'placeholder'=> 'Amount',"readonly"=>"readonly")) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group hide">
                                <div class="row">
                                    <div class="col-md-4">
                                        {!! Form::label('manufacture_date[]','Manufacturing Date:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            {!! Form::text('manufacture_date[]', null, array('class' => 'manufacture_date col-md-3 form-control', 'placeholder'=> 'Manufacture Date',"readonly"=>"readonly")) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        {!! Form::label('received_date[]','Received Date:', array('class' => 'col-md-12 control-label')) !!}
                                        <div class="col-md-12">
                                            {!! Form::text('received_date[]', null, array('class' => 'received_date col-md-3 form-control', 'placeholder'=> 'Received Date',"readonly"=>"readonly")) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-footer">
                            <div class="col-md-8">
                                <button name='saveExit' id='saveExit' class="btn primary-btn btn-success" type="Submit" value='true'>Update</button>
                                {!! HTML::decode(HTML::linkRoute('purchase.index','Cancel', array(), array('class'=>'btn btn-danger secondary-btn', 'id'=>''))) !!}
                            </div>
                        </div>


                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            total_amount();

            $('#vendor_id').select2();
            $('#location_id').select2();
            $('#status').select2();
            $(".item-id").select2();

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
                    "item_id": {
                        required: true
                    },
                    "vendor_id":{
                        required: true
                    },
                    'location_id':{
                        required:true
                    },
                    "quantity":{
                        required: true
                    },
                    "rate":{
                        required: true
                    },
                    "unit_id":{
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
                    "item_id": {
                        required: "Item is required"
                    },
                    "vendor_id": {
                        required: "Vendor is required"
                    },
                    'location_id':{
                        required: "Location is required"
                    },
                    "quantity": {
                        required: "Qty is required"
                    },
                    "rate": {
                        required: "Rate is required"
                    },
                    "unit_id": {
                        required: "Unit is required"
                    },
                    "invoice_no": {
                        required: "Invoice Number is required"
                    }
                }

            });

            function total_amount() {
                var qty = $('.quantity').val();
                var price = $('.rate').val();

                $('.amount').val(qty*price);
            }

            /*provide for fix ui in mobile*/

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

                /*make empty*/
                if ($('.item-div').length != 1 ) {
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

            });

            $('.item-auto').on("change", function() {

                // what you would like to happen

                var i_id = $(this).val();
                var drpdown = $(this);

                $.ajax({
                    url: '/get-item-other-units',
                    type: "POST",
                    data: {id: i_id},
                    success: function (data) {

                        var select = drpdown.closest('.item-div').find('.unit-id');

                        var cnt = 1;
                        $('.unit-id').empty(); //remove all child nodes
                        $.each(data, function(key,value) {

                                select.append($("<option></option>")
                                        .attr("value", key).attr("selected", "true").text(value));

                        });
                        select.removeAttr('disabled');
                    }
                });
            });
        }

    </script>
@stop