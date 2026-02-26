<div id="summary_div" style="margin-top: 20px;">
    <div class="col-md-12 form-group">

        <div class="col-md-3 form-group">
            {!! Form::label('total_order', "Total Orders",array('id'=>'total_order')) !!}
        </div>
        <div class="col-md-4 form-group">
            {!!  isset($order_count)?$order_count  :"0" !!}
        </div>
    </div>
    <div class="col-md-12 form-group">
        <div class="col-md-3 form-group">
            {!! Form::label('total_person', "No. of Persons Visited",array('id'=>'total_person')) !!}
        </div>
        <div class="col-md-4 form-group">
            {!!  isset($total_person)?$total_person  :"0" !!}
        </div>
    </div>
    <div class="col-md-12 form-group">
        <div class="col-md-3 form-group">
            {!! Form::label('total_sells', "Total Sales",array('id'=>'total_sells')) !!}
        </div>
        <div class="col-md-4 form-group">
            {!!  isset($total_sell)?$total_sell  :"0" !!}
        </div>
    </div>
    <div class="col-md-12 form-group">
        <div class="col-md-3 form-group">
            {!! Form::label('total_discount', "Total Discounts",array('id'=>'total_sales')) !!}
        </div>
        <div class="col-md-4 form-group">
            {!!  isset($total_discount)?$total_discount  :"0" !!}
        </div>
    </div>
    <div class="col-md-12 form-group">
        <div class="col-md-3 form-group">
            {!! Form::label('total_nc', "Total Non Chargeable Orders",array('id'=>'total_nc')) !!}
        </div>
        <div class="col-md-4 form-group">
            {!!  isset($total_nc)?$total_nc  :"0" !!}
        </div>
    </div>
    <div class="col-md-12 form-group">
        <div class="col-md-3 form-group">
            {!! Form::label('total_cash', "Total Cash",array('id'=>'total_cash')) !!}
        </div>
        <div class="col-md-4 form-group">
            {!!  isset($total_cash)?$total_cash  :"0" !!}
        </div>
    </div>
    <div class="col-md-12 form-group">
        <div class="col-md-3 form-group">
            {!! Form::label('total_prepaid', "Total Prepaid Amount",array('id'=>'total_prepaid')) !!}
        </div>
        <div class="col-md-4 form-group">
            {!!  isset($total_prepaid)?$total_prepaid  :"0" !!}
        </div>
    </div>
    <div class="col-md-12 form-group">
        <div class="col-md-3 form-group">
            {!! Form::label('gross_total', "Gross Total",array('id'=>'gross_total')) !!}
        </div>
        <div class="col-md-4 form-group">
            {!!  isset($total)?$total  :"0" !!}
        </div>
    </div>

    <div class="col-md-12 form-group">

        <div class="col-md-3 form-group">
            {!! Form::label('total_average', "Average",array('id'=>'total_average')) !!}
        </div>
        <div class="col-md-4 form-group">

            {!!  isset($average)?$average  :"0.00" !!}
        </div>
    </div>

    <div class="col-md-12 form-group">

        <div class="col-md-3 form-group">
            {!! Form::label('top_sell', "Top Selling Item",array('id'=>'top_sell')) !!}
        </div>
        <div class="col-md-4 form-group">

            {!!  isset($item)?$item  :"None" !!}
        </div>
    </div>

    <div class="col-md-12 form-group">

        <div class="col-md-3 form-group">
            {!! Form::label('lowest_order', "Lowest Order",array('id'=>'lowest_order')) !!}
        </div>
        <div class="col-md-4 form-group">

            {!!  isset($lowest_order)?$lowest_order  :"0" !!}

        </div>
    </div>

    <div class="col-md-12 form-group">

        <div class="col-md-3 form-group">
            {!! Form::label('highest_order', "Highest Order",array('id'=>'highest_order')) !!}
        </div>
        <div class="col-md-4 form-group">

            {!!  isset($highest_order)?$highest_order  :"0" !!}
        </div>
    </div>


    <div class="col-md-12 form-group">

        <div class="col-md-3 form-group">
            {!! Form::label('cancel_order', "Cancelled Orders",array('id'=>'cancel_order')) !!}
        </div>
        <div class="col-md-4 form-group">

            {!!  isset($cancel_order)?$cancel_order  :"0" !!}
        </div>
    </div>


    <div class="col-md-12 form-group">

        <div class="col-md-3 form-group">
            {!! Form::label('cancel_amount', "Cancelled Orders Amount",array('id'=>'cancel_amount')) !!}
        </div>
        <div class="col-md-4 form-group">

            {!!  isset($cancel_amount)?$cancel_amount  :"0" !!}
        </div>
    </div>


    <div class="control-group">
        <div class="controls">

            @if(Session::has('error'))
            <p class="errors">{!! Session::get('error') !!}</p>
            @endif
        </div>
    </div>
</div>
<!--         <div class="col-md-6">
            <button id=exportsummaryreport class="btn btn-primary" type="submit" class="exportsummaryreport">{{ trans('home.Export Summary Report') }}</button>
            <button id=exportdetailedreport class="btn btn-primary" type="submit" class="exportdetailedreport" style="display:none;">{{ trans('home.Export Detailed Report')}}</button>
        </div>

    </div> -->