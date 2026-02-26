<?php
    use App\Roles;
    use App\Owner;
    use App\State;
    use App\Country;
    $acc_id = Auth::user()->account_id;
    $account = \App\Account::find($acc_id);
?>

<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">

                @if($action=='add')
                    <form class="form-horizontal material-form j-forms" role="form" method="POST" id="Outlet_form" action="{{ url('/outlet') }}" files="true" enctype="multipart/form-data" novalidate>
                    <input type="hidden"  name="_token" value="{{ csrf_token() }}">
                @else
                    {!! Form::model($Outlet,array('id'=>'Outlet_form','route' => array('outlet.update',$Outlet->id), 'method' => 'patch', 'class' => 'form-horizontal material-form j-forms','novalidate' )) !!}
                @endif
                    <?php $user=Auth::user();?>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('Outlet_name', 'Outlet Name*', array('class' => 'col-md-12 control-label')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('Outlet_name', isset($Outlet->name)?$Outlet->name:'', ['class' => 'form-control','placeholder'=>"Outlet Name"]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if($action=='add')
                                    {!! Form::label('bind_user', 'Bind Owner*', array('class' => 'col-md-12 control-label')) !!}
                                @else
                                    {!! Form::label('bind_user', 'Binded Owners*', array('class' => 'col-md-3 control-label')) !!}
                                    <a class="col-md-8" href="/outletBind/{{$Outlet->id}}"> Change Bind Users </a>
                                @endif

                                <div class="col-md-12">
                                    @if($action=='add')
                                        @if($user->user_name == "govind")
                                            {!! Form::select('bind_user', $admin_user_list,null, ['class' => 'form-control select2',"required",'id'=>"bind_user"]) !!}
                                        @else
                                            {!! Form::select('bind_user', $users,null, ['class' => 'form-control select2',"required",'id'=>"bind_user"]) !!}
                                        @endif
                                    @endif
                                    @if($action=='edit')
                                        @if($user->user_name == "govind")
                                            {!! Form::select('bind_user[]', $admin_user_list,$bind_user, ['class' => 'form-control select2','disabled',"required",'id'=>"bind_user",'multiple']) !!}
                                        @else
                                            {!! Form::select('bind_user[]', $users,$bind_user, ['class' => 'form-control select2',"required",'disabled','id'=>"bind_user",'multiple']) !!}
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-6">

                                    {!! Form::label('address', 'Address*', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        {!! Form::textarea('address', isset($Outlet->address)?$Outlet->address:'', ['rows'=>"3",'class' => 'form-control','placeholder'=>"Address"]) !!}
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    {!! Form::label('company_name', 'Company', array('class' => 'col-md-12 control-label')) !!}
                                    <div class="col-md-12">
                                        {!! Form::text('company_name', isset($Outlet->company_name)?$Outlet->company_name:'', ['class' => 'form-control','placeholder'=>"Company Name"]) !!}
                                    </div>

                                    {!! Form::label('pincode', 'Pincode*', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        {!! Form::text('pincode', isset($Outlet->pincode)?$Outlet->pincode:'', ['class' => 'form-control','placeholder'=>"Pincode"]) !!}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-6">

                                    {!! Form::label('contact_no', 'Contact Number*', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        {!! Form::text('contact_no' ,null, ['class' => 'form-control', 'placeholder'=> 'Contact Number','required','id'=>'contact_no'] ) !!}
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    {!! Form::label('delivery_numbers', 'Delivery Numbers', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        {!! Form::text('delivery_numbers' ,null, ['class' => 'form-control', 'placeholder'=> 'Delivery Numbers','id'=>'delivery_numbers'] ) !!}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">

                                    {!! Form::label('report_emails', 'Send Report To*', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        {!! Form::text('report_emails',null, ['class' => 'form-control','placeholder'=>"Comma separated Email ids","id"=>"report_emails","required"]) !!}
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {!! Form::label('biller_emails', 'Biller emails', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        {!! Form::text('biller_emails',null, ['class' => 'form-control','placeholder'=>"Comma separated Email ids","id"=>"biller_emails"]) !!}
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {!! Form::label('upi_lable', 'UPI', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        {!! Form::text('upi',null, ['class' => 'form-control','placeholder'=>"UPI","id"=>"upi"]) !!}
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">

                                    {!! Form::label('parse_order_email', 'Parse Order Email', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        {!! Form::text('parse_order_email',null, ['class' => 'form-control','placeholder'=>"Email address for parse orders","id"=>"parse_order_email"]) !!}
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {!! Form::label('outlet_code', 'Outlet Code*', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        @if($action=='add')
                                            <input type="text" class="form-control" style="text-transform:uppercase" maxlength="4" name="outlet_code" value="" placeholder="Code (Ex: ABC)">
                                        @endif
                                        @if($action=='edit')
                                            <input type="text" class="form-control" style="text-transform:uppercase" maxlength="4" name="outlet_code" value="@if(isset($Outlet->code)){{$Outlet->code}}@endif" placeholder="Code (Ex: ABC)">
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    {!! Form::label('invoice_digit', 'Invoice Digit*', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        @if($action=='edit' && isset($Outlet->invoice_digit))
                                            {!! Form::select('invoice_digit',array('3' => '3 (Ex. : 001)', '4' => '4 (Ex. : 0001)'),$Outlet->invoice_digit,array('class' => 'form-control','id'=>'invoice_digit')) !!}
                                        @else
                                            {!! Form::select('invoice_digit', array('3' => '3 (Ex. : 001)', '4' => '4 (Ex. : 0001)'),'invoice_digit',array('class' => 'form-control','id'=>'invoice_digit')) !!}
                                        @endif
                                    </div>

                                </div>


                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">

                                    {!! Form::label('users', 'Authorised Users*', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        @if($get=='edit' && isset($Outlet->authorised_users))
                                            {!!Form::select('users',$users,$Outlet->authorised_users,array('class'=>'form-control','name'=>'users[]','required'))!!}
                                        @else
                                            {!!Form::select('users',$users,null,array('id','class'=>'form-control','name'=>'users[]','required'))!!}
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {!! Form::label('avg_cost_of_two', 'Avg Cost Of Two*', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        <input type="number" min="1" pattern="[0-9.]+" class="form-control" name="avg_cost_of_two" value="@if(isset($Outlet->avg_cost_of_two)){{$Outlet->avg_cost_of_two}}@endif" placeholder="Avg Cost Of Two">
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    {!! Form::label('famous_for', 'Famous For', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        {!! Form::text('famous_for' ,isset($Outlet->famous_for)?$Outlet->famous_for:'', ['class' => 'form-control', 'placeholder'=> 'Famous For'] ) !!}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">

                                    {!! Form::label('invoice_title', 'Invoice Title', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        {!! Form::text('invoice_title' ,null, ['class' => 'form-control', 'placeholder'=> 'Invoice Title','id'=>'invoice_title'] ) !!}
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {!! Form::label('order_lable', 'Order Label', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        {!! Form::text('order_lable' ,null, ['class' => 'form-control', 'placeholder'=> 'Order Lable','id'=>'order_lable','maxlength'=>"7"] ) !!}
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {!! Form::label('token_lable', 'Token Label', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        {!! Form::text('token_lable' ,null, ['class' => 'form-control', 'placeholder'=> 'Token Lable','id'=>'token_lable'] ) !!}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4">

                                    {!! Form::label('dine_in_prefix', 'Dine In Order Prefix', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        <input type="text" value="@if(isset($Outlet->invoice_prefix)){!! $Outlet->invoice_prefix->dine_in !!}@endif" class="form-control" id="dine_in_prefix" name="dine_in_prefix"/>
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    {!! Form::label('take_away_prefix', 'Take Away Order Prefix', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        <input type="text" value="@if(isset($Outlet->invoice_prefix)){!! $Outlet->invoice_prefix->take_away !!}@endif" class="form-control" id="take_away_prefix" name="take_away_prefix"/>
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    {!! Form::label('home_delivery_prefix', 'Home Delivery Order Prefix', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        <input type="text" value="@if(isset($Outlet->invoice_prefix)){!! $Outlet->invoice_prefix->home_delivery !!}@endif" class="form-control" id="home_delivery_prefix" name="home_delivery_prefix"/>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-6">

                                    {!! Form::label('countries', 'Country', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        @if($action=='add')
                                            <select class="form-control" name="countries">
                                                <option selected >{{ trans('Restaurant_Form.Select Country') }}</option>
                                                @foreach($countries as $country)
                                                    <option value="{{$country->id or ''}}">{{$country->name or ''}}</option>
                                                @endforeach
                                            </select>
                                        @endif

                                        @if($action=='edit')
                                            <select class="form-control" name="countries">
                                                @foreach($countries as $country)
                                                    @if($Outlet->country_id == $country->id)
                                                        <option value="{{$country->id or ''}}" selected>{{$country->name or ''}}</option>
                                                    @else
                                                        <option value="{{$country->id or ''}}">{{$country->name or ''}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    {!! Form::label('states', 'State', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        @if($action=='add')
                                            <select class="form-control" name="states">
                                                <option selected >{{ trans('Restaurant_Form.Select State') }}</option>
                                                @foreach($states as $state)
                                                    <option value="{{$state->id or ''}}">{{$state->name or ''}}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                        @if($action=='edit')
                                            <select class="form-control" name="states">
                                                @foreach($states as $state)
                                                    @if($Outlet->state_id == $state->id)
                                                        <option value="{{$state->id or ''}}" selected>{{$state->name or ''}}</option>
                                                    @else
                                                        <option value="{{$state->id or ''}}">{{$state->name or ''}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-6">

                                    {!! Form::label('cities', 'City', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        @if($action=='add')
                                            <select class="form-control" name="cities">
                                                <option selected >{{ trans('Restaurant_Form.Select City') }}</option>
                                                @foreach($cities as $city)
                                                    <option  value="{{$city->id or ''}}">{{$city->name or ''}}</option>
                                                @endforeach
                                            </select>
                                        @endif

                                        @if($action=='edit')
                                            <select class="form-control" name="cities">
                                                @foreach($cities as $city)
                                                    @if($Outlet->city_id == $city->id)
                                                        <option value="{{$city->id or ''}}" selected>{{$city->name or ''}}</option>
                                                    @else
                                                        <option value="{{$city->id or ''}}">{{$city->name or ''}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    {!! Form::label('locality', 'Locality', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        @if($action=='add')
                                            <select class="form-control" name="locality">
                                                <option selected >{{ trans('Restaurant_Form.Select Locality') }}</option>
                                                @foreach($locality as $localities)
                                                    <option value="{{$localities->locality_id or ''}}">{{$localities->locality or ''}}</option>
                                                @endforeach
                                            </select>
                                        @endif

                                        @if($action=='edit')
                                            <select class="form-control" name="locality">
                                                @foreach($locality as $localities)
                                                    @if($Outlet->locality == $localities->locality_id)
                                                        <option value="{{$localities->locality_id or ''}}"selected>{{$localities->locality or ''}}</option>
                                                    @else
                                                        <option value="{{$localities->locality_id or ''}}">{{$localities->locality or ''}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-6">

                                    {!! Form::label('email', 'Email-Id', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        <input type="email" class="form-control" name="email_id" value="@if(isset($Outlet->email_id)){{$Outlet->email_id}}@endif" placeholder="Email-Id">
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    {!! Form::label('web_address', 'Website', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        <input type="text" class="form-control" id="web_address" name="web_address" value="@if(isset($Outlet->url)){{$Outlet->url}}@endif" placeholder="Ex. http://www.abc.com">
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-6">

                                    {!! Form::label('mininum_order_price', 'Minimum Order Price', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        <input type="text" id="mininum_order_price" class="form-control" name="mininum_order_price" value="@if(isset($Outlet->takeaway_cost) && $Outlet->takeaway_cost!='' && $Outlet->takeaway_cost!=0){{$Outlet->takeaway_cost}}@endif" placeholder="Minimum Order Price For Home Delivery">
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    {!! Form::label('established_date', 'Established Date', array('class' => 'col-md-12 control-label')) !!}

                                    <div class="col-md-12">
                                        <input  type="text" name="established_date" class="form-control" placeholder="Established Date"  id="established_date" value="@if(isset($Outlet->established_date) && $Outlet->established_date != '0000-00-00' ){{$Outlet->established_date}}@endif">
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-12">
                                    <label class="control-label col-md-12">{{ trans('Restaurant_Form.Outlet Type') }}</label>
                                </div>
                                <div class="col-md-12">

                                    <div class="unit col-md-12">
                                        <div class="inline-group">

                                            @if($get=='add')
                                                @if(sizeof($Outlet_type)>0)

                                                    @foreach($Outlet_type as $Outletype)
                                                        <label class="checkbox">
                                                            <input type="checkbox" name="Outlet_type[]" value={{$Outletype->id}}>
                                                            <i></i>{{$Outletype->type}}
                                                        </label>
                                                    @endforeach

                                                @endif
                                            @endif

                                            @if($get=='edit')

                                                @if(sizeof($Outlet_type)>0)

                                                    @foreach($Outlet_type as $Outletype)
                                                        <?php $selected=0; ?>

                                                        @foreach($selectedOutletType as $isselected)
                                                            @if($isselected->outlet_type_id == $Outletype->id)
                                                                <?php $selected=1; ?>
                                                            @endif
                                                        @endforeach

                                                        @if($selected==1)
                                                            <label class="checkbox">
                                                                <input type="checkbox" name="Outlet_type[]" value={{$Outletype->id}} checked="true">
                                                                <i></i>{{$Outletype->type}}
                                                            </label>
                                                        @else
                                                            <label class="checkbox"><input type="checkbox" name="Outlet_type[]" value={{$Outletype->id}}>
                                                                <i></i>{{$Outletype->type}}
                                                            </label>
                                                        @endif

                                                    @endforeach

                                                @endif
                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-12">
                                    <label  class="control-label col-md-12">{{ trans('Restaurant_Form.Cuisine Types') }}</label>
                                </div>
                                <div class="col-md-12">

                                    <div class="unit col-md-12">
                                        <div class="inline-group">

                                            @if($set=='add')
                                                @if(sizeof($cuisineType_type)>0)
                                                    @foreach($cuisineType_type as $cuisinetype)
                                                        <label class="checkbox">
                                                            <input type="checkbox" name="cuisine_type[]" value={{$cuisinetype->id}}>
                                                            <i></i>{{$cuisinetype->type}}
                                                        </label>
                                                    @endforeach
                                                @endif
                                            @endif

                                            @if($set=='edit')
                                                @if(sizeof($cuisineType_type)>0)

                                                    @foreach($cuisineType_type as $cuisinetype)

                                                        <?php $selected=0; ?>
                                                        @foreach($selectedCuisineType as $isselected)
                                                            @if($isselected->cuisine_type_id == $cuisinetype->id)
                                                                <?php $selected=1; ?>
                                                            @endif
                                                        @endforeach
                                                        @if($selected==1)
                                                            <label class="checkbox">
                                                                <input type="checkbox" name="cuisine_type[]" checked="true" value={{$cuisinetype->id}}>
                                                                <i></i>{{$cuisinetype->type}}
                                                            </label>
                                                        @else
                                                            <label class="checkbox">
                                                                <input type="checkbox" name="cuisine_type[]" value={{$cuisinetype->id}}>
                                                                <i></i>{{$cuisinetype->type}}
                                                            </label>
                                                        @endif

                                                    @endforeach

                                                @endif
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-12">
                                    <label  class="control-label col-md-12">Select SMS Fields</label>
                                </div>
                                <div class="col-md-12">

                                    <div class="unit col-md-12">
                                        <div class="inline-group">

                                            <label class="checkbox">
                                                <input type="checkbox" name="status_sms[]" value="name" @if(isset($Outlet->status_sms) && in_array('name',$Outlet->status_sms)) checked="true" @endif>
                                                <i></i>Restaurant Name
                                            </label>
                                            <label class="checkbox">
                                                <input type="checkbox" name="status_sms[]" value="hours" @if(isset($Outlet->status_sms) && in_array('hours',$Outlet->status_sms)) checked="true" @endif>
                                                <i></i>Hours
                                            </label>
                                            <label class="checkbox">
                                                <input type="checkbox" name="status_sms[]" value="ord" @if(isset($Outlet->status_sms) && in_array('ord',$Outlet->status_sms)) checked="true" @endif>
                                                <i></i>No. Of Orders(ord)
                                            </label>
                                            <label class="checkbox">
                                                <input type="checkbox" name="status_sms[]" value="pax" @if(isset($Outlet->status_sms) && in_array('pax',$Outlet->status_sms)) checked="true" @endif>
                                                <i></i>PAX
                                            </label>
                                            <label class="checkbox">
                                                <input type="checkbox" name="status_sms[]" value="sales" @if(isset($Outlet->status_sms) && in_array('sales',$Outlet->status_sms)) checked="true" @endif>
                                                <i></i>Sales
                                            </label>
                                            <label class="checkbox">
                                                <input type="checkbox" name="status_sms[]" value="gs" @if(isset($Outlet->status_sms) && in_array('gs',$Outlet->status_sms)) checked="true" @endif>
                                                <i></i>Gross Sales(GS)
                                            </label>
                                            <label class="checkbox">
                                                <input type="checkbox" name="status_sms[]" value="ns" @if(isset($Outlet->status_sms) && in_array('ns',$Outlet->status_sms)) checked="true" @endif>
                                                <i></i>Net Sales(NS)
                                            </label>
                                            <label class="checkbox">
                                                <input type="checkbox" name="status_sms[]" value="can" @if(isset($Outlet->status_sms) && in_array('can',$Outlet->status_sms)) checked="true" @endif>
                                                <i></i>Cancelled Order
                                            </label>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <?php $servicetype=array('take_away'=>'Take Away','dine_in'=>'Dine In','home_delivery'=>'Home Delivery','meal_packs'=>'Meal Packs');?>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label  class="control-label col-md-12">Listing Type({{ trans('Restaurant_Show.Service Type') }})</label>
                                </div>
                                <div class="col-md-12">

                                    <div class="unit col-md-12">
                                        <div class="inline-group">

                                            @foreach($servicetype as $key=>$value)

                                                <label class="checkbox">
                                                    <input type="checkbox" name="service_type[]" value={{$key}} @if(isset($Outlet->service_type)  && $Outlet->service_type!="" && is_array($abc) && in_array($key,$abc)) checked="true" @endif>
                                                    <i></i>{{$value}}
                                                </label>

                                            @endforeach

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-12">
                                    <label  class="control-label col-md-12">Enable Ordering</label>
                                </div>
                                <div class="col-md-12">

                                    <div class="unit col-md-12">
                                        <div class="inline-group">

                                            @foreach($servicetype as $key=>$value)

                                                <label class="checkbox">
                                                    <input type="checkbox" name="enable_service_type[]" value={{$key}} @if(isset($Outlet->enable_service_type)  && $Outlet->enable_service_type!="" && is_array($enable_service_type) && in_array($key,$enable_service_type)) checked="true" @endif>
                                                    <i></i>{{$value}}
                                                </label>

                                            @endforeach

                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-6">
                                    <label for="option" class="control-label col-md-12">{{ trans('Restaurant_Form.Active') }}</label>

                                    <div class="col-md-12">
                                        <div class="unit col-md-12">
                                            <div class="inline-group">
                                                <label class="radio">
                                                    <input type="radio" name="active" id="active" onclick="setReadOnly(this)" value="Yes" @if(isset($Outlet['active'])  && $Outlet['active'] == "Yes") checked="true" @endif>
                                                    <i></i>Yes
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" name="active" id="active" onclick="setReadOnly(this)" value="No" @if(isset($Outlet['active'])  && $Outlet['active'] == "No") checked="true" @endif>
                                                    <i></i>No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <label for="session" class="control-label col-md-12">Session Time (Ex. if 2 than record display From time 00+2 and To time 23+2)</label>

                                    <div class="col-md-12">
                                        {!!Form::select('session_time',$session_time_arr,null,array('class'=>'form-control','name'=>'session_time'))!!}
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-12">

                                    @if( isset($account) && $account->enable_inventory == 1 )

                                        <div class="unit col-md-6">
                                            <div class="inline-group">

                                                <label class="checkbox">

                                                    {!! Form::checkbox('stock_auto_decrement','1',null) !!}
                                                    <i></i>Automate Stock Deduction

                                                </label>

                                                <label class="checkbox">

                                                    {!! Form::checkbox('add_stock_on_purchase','1',null) !!}
                                                    <i></i>Add Stock On Purchase

                                                </label>

                                            </div>
                                        </div>

                                    @endif

                                    <div class="form-group">
                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="unit col-md-12">
                                                    <div class="inline-group">

                                                        <label class="checkbox">

                                                            {!! Form::checkbox('zoho_config','1',null) !!}
                                                            <i></i>Enable ZOHO invoice Integration

                                                        </label>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-12">

                                    <div class="col-md-6">

                                        <div class="duplicate_watermark">
                                            <div class="inline-group">

                                                <label class="checkbox">

                                                    {!! Form::checkbox('duplicate_watermark','1',null) !!}
                                                    <i></i>Show Duplicate Watermark in Bill

                                                </label>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="duplicate_watermark col-md-12">
                                            <div class="inline-group">

                                                <label class="checkbox">

                                                    {!! Form::checkbox('payment_info','1',null) !!}
                                                    <i></i>Payment Info

                                                </label>

                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-footer">
                            @if( $action == 'edit')
                                <button class="btn btn-success primary-btn"  id="Submit" type="submit" >Update</button>
                                <button type="button" class="btn btn-danger primary-btn" onclick="location.href='/outlet';">Cancel</button>
                            @else
                                <button class="btn btn-success primary-btn"  id="Submit" type="submit" >{{ trans('Restaurant_Form.Submit') }}</button>
                                <button class="btn btn-danger primary-btn" type="reset">{{ trans('Restaurant_Form.Reset') }}</button>
                            @endif
                        </div>

                    </form>

            </div>
        </div>
    </div>
</div>


@section('page-scripts')

    <script src="/assets/js/new/lib/jquery.validate.js"></script>

    <script type="text/javascript">

        var i=1;
        var someText='';

        $(document).ready(function() {

            /*$('#order_number_increment').click(function(el) {
                if (jQuery(this).prop("checked")) {
                    jQuery("#order_lable").val( "Order" );
                } else {
                    jQuery("#order_lable").val( "Table" );
                }
            });*/

            $('#bind_user').select2({
                placeholder: 'Select Owners'
            });

            $('#established_date').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });

            $('#vat_date').DatePicker({
                format: "yyyy-mm-dd",
                orientation: "auto",
                autoclose: true,
                todayHighlight: true
            });

            $("#Outlet_form").validate({
                rules: {
                    "Outlet_name": {
                        required: true
                    },
                    "address": {
                        required: true
                    },
                    "contact_no": {
                        required: true
                    },
                    "delivery_numbers": {
                        number: true,
                        minlength:10
                    },
                    "pincode": {
                        required: true,
                        number: true,
                        rangelength: [6, 6]
                    },
                    "pan_no": {
                        minlength:10,
                        rangelength: [10, 10]
                    },
                    "report_emails": {
                        required: true
                    },
                    "mininum_order_price": {
                        number: true
                    },
                    "biller_emails": {
                        email: true
                    },
                    "upi": {
                        email: true
                    },
                    "users": {
                        required: true
                    },
                    "parse_order_email": {
                        email: true
                    },
                    "web_address": {
                        url: true
                    },
                    "avg_cost_of_two": {
                        required: true
                    },
                    "outlet_code": {
                        required: true
                    },
                    "dine_in_prefix": {
                        required: function(element){
                            if ( $("#take_away_prefix").val() != "" || $("#home_delivery_prefix").val() != ""){
                               return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    "take_away_prefix": {
                        required: function(element){
                            if ( $("#dine_in_prefix").val() != "" || $("#home_delivery_prefix").val() != ""){
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    "home_delivery_prefix": {
                        required: function(element){
                            if ( $("#dine_in_prefix").val() != "" || $("#take_away_prefix").val() != ""){
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }

                },
                messages: {
                    "Outlet_name": {
                        required: "Outlet Name is required"
                    },
                    "address": {
                        required: "Address is required"
                    },
                    "report_emails": {
                        required: "Report Send To is required"
                    },
                    "contact_no": {
                        required:"Contact number is required"
                    },
                    "upi": {
                        email: "Please enter valid UPI ID"
                    },
                    "pincode": {
                        required: "Pincode is required",
                        number: "Pincode must be a number",
                        rangelength: "Pincode should be 6digits only"
                    },
                    "pan_no": {
                        minlength: "PAN number is not valid",
                        rangelength: "PAN number should be 10 digits only"
                    },
                    "avg_cost_of_two": {
                        required: "Avg Cost Of Two is required"
                    },
                    "outlet_code": {
                        required: "Outlet Code is required"
                    }
                }
            })
        });
        $('#Submit').click(function() {
            $("#Outlet_form").valid();  // This is not working and is not validating the form
        });
    </script>
@stop
