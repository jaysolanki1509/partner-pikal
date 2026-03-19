{{--process modal--}}
<?php use App\OutletSetting; ?>
<div id="processBill" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Process Billing</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="bill_order_id" value="" />
                <div class="col-md-4">
                    <input type="radio" name="order_type" value="dine_in" checked/>
                    <lable>Dine In</lable>
                </div>
                <div class="col-md-4">
                    <input type="radio" name="order_type" id="take_away" value="take_away" />
                    <lable>Take Away</lable>
                </div>
                <div class="col-md-4">
                    <input type="radio" name="order_type" id="home_delivery" value="home_delivery" />
                    <lable>Home Delivery</lable>
                </div>
                <hr class="col-md-12" style="width:95%">
                <div class="form-group">
                    <div class="col-md-12 form-group" style="margin-bottom: 0px">
                        <label for="invoice_no" style="margin-bottom: 0px">Invoice No.</label>
                    </div>
                    <div class="col-md-12 form-group">
                        {!! Form::text('invoice_no', null, array('class' => 'form-control','id' => 'invoice_no', 'placeholder'=> 'Invoice Number')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 form-group">
                        {!! Form::text('name', null, array('class' => 'form-control','id' => 'name', 'placeholder'=> 'Name')) !!}
                    </div>
                    <div class="col-md-6 form-group">
                        {!! Form::text('mobile', null, array('class' => 'form-control','id' => 'mobile', 'placeholder'=> 'Mobile','onkeypress'=>'return isNumber(event)','maxlength'=>'10')) !!}
                    </div>
                </div>
                <div class="form-group hide" id="address_div">
                    <div class="col-md-12 form-group">
                        <textarea name="address" id="address" rows="3" class="form-control" placeholder="Address"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4 form-group">
                        <select id="disc_type" class="form-control" placeholder="Discount Type">
                            <option value="" >Discount Type</option>
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="text" class="form-control" id="disc_value" placeholder="Discount Value" onkeypress='return onlyNumbersWithDot(event)' />
                    </div>
                    <div class="col-md-4 form-group">
                        <button type="button" class="btn btn-primary" onclick="calculateDiscount( this, 'process', 'apply' )">Apply</button>
                        <button type="button" class="btn btn-danger" onclick="calculateDiscount( this, 'process', 'remove' )">Remove</button>
                    </div>
                </div>
                <?php
                        $sess_outlet_id = Session::get('outlet_session');
                        $delivery_setting = OutletSetting::checkAppSetting($sess_outlet_id,"overwriteDeliveryCharge");?>
                @if($delivery_setting)
                    <div class="form-group" id="add_delivery_value">
                        <div class="col-md-8 form-group">
                            <input type="text" class="form-control" id="new_delivery_value" placeholder="Delivery Value" onkeypress='return onlyNumbersWithDot(event)' />
                        </div>
                        <div class="col-md-4 form-group">
                            <button type="button" class="btn btn-primary" onclick="delivery_apply('apply','process')">Apply</button>
                            <button type="button" class="btn btn-danger" onclick="delivery_apply('remove','process')">Remove</button>
                        </div>
                    </div>
                @endif

                <form id="custom_form">
                    @if(isset($custom_fields) && sizeof($custom_fields)>0)
                        @foreach($custom_fields as $fields)
                            @foreach($fields as $key=>$val)
                                <div class="form-group">
                                    <div class="col-md-6 form-group">
                                        {!! Form::text($key, null, array('class' => 'form-control '.$val[0]->type,$val[0]->type=="date"||$val[0]->type=="date_time"?"":"", 'id' => $key, 'placeholder'=> $val[0]->label)) !!}
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @endif
                </form>
                <div style="clear:both"></div>
                <div class="form-group" id="calculation" style="margin-top:20px;">
                </div>
                <div style="clear:both"></div>

            </div>
            <div class="modal-footer">
                <button type="button" onclick="addPaymentMode('#processBill')" id="process_add_payment_mode_btn" class="btn btn-primary"><i class="fa fa-plus"></i> Payment Mode</button>
                <button type="button" id="process_btn" class="btn btn-primary" onclick="processBill(0,'process')">Process Bill</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

{{--print modal--}}
<div id="printModal" data-backdrop="static" data-keyboard="false" class="modal fade" role="dialog" >
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invoice</h4>
            </div>
            <div class="modal-body">
                <p></p>
            </div>
            <div class="modal-footer">
                <input type="hidden" value="" id="close_type" />
                <button type="button" class="btn btn-primary" onclick="print()">Print</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>