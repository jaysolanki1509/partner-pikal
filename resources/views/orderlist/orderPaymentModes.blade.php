@if( isset($bill_amount) && $bill_amount != "")

    <div class="form-group">
        <div class="col-md-3">
            <lable class="form-label bold">Bill Amount:</lable>
        </div>
        <div class="col-md-9">
            <span id="bill_amount">{{ $bill_amount }}</span>
        </div>

    </div>
    <div style="clear:both"></div>

@endif

@foreach( $payment_modes as $mode )

    <?php $option_name = '';?>
    <div class="form-group payment_div" style="width: 100%">

        <div class="col-md-4" style="margin-bottom: 10px">
            <select class="form-control paid-type" onchange="checkPaymentOption(this)">
                @if (isset($pay_option) && sizeof($pay_option))
                    @foreach ($pay_option as $po)
                        <option value ="{!! $po['mode_id']!!}" data-source="{!! $po['source_id'] !!}" @if( $mode->payment_option_id == $po['mode_id'] && $mode->source_id == $po['source_id']){!! 'selected' !!}<?php $option_name = strtolower($po['mode_name']); ?>@endif>{!! $po['mode_name'] !!}@if($po['source_name']){!!' - '.$po['source_name'] !!}@endif</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-3" style="margin-bottom: 10px">
            <input class="form-control paid-value" type="text" value="{{ number_format($mode->amount, 2, '.', '') }}" @if(sizeof($payment_modes) == 1 ) readonly @endif>
        </div>
        <div class="col-md-4 @if( $option_name == 'cash' || $option_name == 'unpaid' || $option_name == 'complimentary' ) hide @endif" style="margin-bottom: 10px">
            <input class="form-control transaction-id" type="text" value="{{ $mode->transaction_id }}" placeholder="Transaction Id" >
        </div>
        <div class="col-md-1" style="padding-left: 0px">
            <a href="javascript:void(0)" class="btn btn-circle btn-danger @if(sizeof($payment_modes) == 1 ) hide @endif" onclick="removePaymentMode(this)">
                <i class="fa fa-remove"></i>
            </a>
        </div>
        <div style="clear:both"></div>
    </div>

@endforeach