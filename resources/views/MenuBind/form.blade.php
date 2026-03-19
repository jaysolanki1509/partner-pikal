<?php use Zizaco\Entrust\Entrust;
use App\Roles;
use App\Owner;
use App\State;
use App\Country;?>
@if($action=='add')

    <form class="form-horizontal" role="form" method="POST" id="Submit" novalidate="novalidate" action="{{ url('/outlet') }}" files="true"
          enctype="multipart/form-data" xmlns="http://www.w3.org/1999/html">
        {{--<script src="js/jquery.min.js"></script>--}}
        {{--<script src="js/jquery.validate.min.js"> </script>;--}}
        {{--{!! Form::open(['route' =>'Outlet.store', 'method' => 'patch', 'class' => 'autoValidate', 'files'=> true]) !!}--}}
        @else
            {{--<form class="form-horizontal" role="form" method="PUT" action="{{ url('/Outlet/'.$Outlet->id) }}">--}}
            {!! Form::model($Outlet,array('id'=>'Submit','route' => array('outlet.update',$Outlet->id), 'method' => 'patch', 'class' => 'autoValidate')) !!}
        @endif

        <input type="hidden"  name="_token" value="{{ csrf_token() }}">


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label for="closing_time" class="control-label">{{ trans('Restaurant_Form.Outlet Name*') }}</label>
            </div>
            <div class="col-md-9 form">
                <input type="text" class="form-control" name="Outlet_name" value="@if(isset($Outlet->name)){{$Outlet->name}}@endif" placeholder="Please Enter Valid Outlet Name">
            </div>
        </div>


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Restaurant_Form.Address*') }}</label>
            </div>
            <div class="col-md-9 form">
                <textarea class="form-control" rows="3" placeholder="Address" name="address">@if(isset($Outlet->address)){{$Outlet->address}}@endif</textarea>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Restaurant_Form.Pincode*') }}</label>
            </div>
            <div class="col-md-9 form">
                <input type="text" class="form-control" name="pincode" value="@if(isset($Outlet->pincode)){{$Outlet->pincode}}@endif" placeholder="Pincode">
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Restaurant_Form.Contact Number') }}</label>
            </div>
            <div class="col-md-9 form">
                <input type="text" class="form-control" name="contact_no" value="@if(isset($Outlet->contact_no)){{$Outlet->contact_no}}@endif" placeholder="Contact Number">
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Restaurant_Form.Famous For') }}</label>
            </div>
            <div class="col-md-9 form">
                <input type="text" class="form-control" name="famous_for" value="@if(isset($Outlet->famous_for)){{$Outlet->famous_for}}@endif" placeholder="Famous For">
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label for="closing_time" class="control-label">{{ trans('Restaurant_Form.Avg Cost Of Two*') }}</label>
            </div>
            <div class="col-md-9 form">
                <input type="text" class="form-control" name="avg_cost_of_two" value="@if(isset($Outlet->avg_cost_of_two)){{$Outlet->avg_cost_of_two}}@endif" placeholder="Avg Cost Of Two">
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label for="outlet_code" class="control-label">Outlet Code*</label>
            </div>
            <div class="col-md-3 form">
                @if($service=='add')
                    <input type="text" class="form-control" maxlength="4" name="outlet_code" value="" placeholder="Outlet Code(like ABC)">
                @endif
                @if($service=='edit')
                    <input type="text" class="form-control" maxlength="4" name="outlet_code" value="@if(isset($Outlet->code)){{$Outlet->code}}@endif" placeholder="Outlet Code(like ABC)">
                @endif
            </div>
            <div class="col-md-3 form">
                <label for="outlet_code" class="control-label">Invoice Number Digit*</label>
            </div>
            <div class="col-md-3 form">
                {!! Form::select('invoice_digit', array('3' => '3 (Ex. : 001)', '4' => '4 (Ex. : 0001)'),'invoice_digit',array('class' => 'form-control','id'=>'invoice_digit')); !!}
            </div>
        </div>


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Restaurant_Form.Country') }}</label>
            </div>

            <div class="col-md-9 form">
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


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Restaurant_Form.State') }}</label>
            </div>

            <div class="col-md-9 form">
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


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Restaurant_Form.City') }}</label>
            </div>

            <div class="col-md-9 form">
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


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Restaurant_Form.Locality') }}</label>
            </div>

            <div class="col-md-9 form">
                @if($create=='add')
                    <select class="form-control" name="locality">
                        <option selected >{{ trans('Restaurant_Form.Select Locality') }}</option>
                        @foreach($locality as $localities)
                            <option value="{{$localities->locality_id or ''}}">{{$localities->locality or ''}}</option>
                        @endforeach
                    </select>
                @endif

                @if($create=='edit')
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

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Restaurant_Form.Email-Id') }}</label>
            </div>
            <div class="col-md-9 form">
                <input type="text" class="form-control" name="email_id" value="@if(isset($Outlet->email_id)){{$Outlet->email_id}}@endif" placeholder="Email-Id">
            </div>
        </div>


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Restaurant_Form.Web Address') }}</label>
            </div>
            <div class="col-md-9 form">
                <input type="text" class="form-control" name="web_address" value="@if(isset($Outlet->url)){{$Outlet->url}}@endif" placeholder="Web Address">
            </div>
        </div>


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Restaurant_Form.Establishment Date') }}</label>
            </div>
            <div class="col-md-9 form">
                @if($edate='edit')
                        <input  type="text" name="established_date"class="form-control" placeholder="Established Date"  id="established_date" value="@if(isset($established_date)){{$established_date}}@endif">
                    @endif
            </div>
        </div>





        <div class="col-md-12">
            <div class="col-md-3 form">
                <label for="closing_time" class="control-label">{{ trans('Restaurant_Form.Minimum Order Price') }}</label>
            </div>
            <div class="col-md-9 form">
                <input type="text" class="form-control" name="mininum_order_price" value="@if(isset($Outlet->takeaway_cost) && $Outlet->takeaway_cost!='' && $Outlet->takeaway_cost!=0){{$Outlet->takeaway_cost}}@endif" placeholder="Minimum Order Price For Home Delivery">
            </div>
        </div>

        <div class="col-md-12 form">
            <div class="col-md-3 form">
                <label  class="control-label ">{{ trans('Restaurant_Form.ServiceTax No') }}</label>
            </div>
            <div class="col-md-9 form">
                @if($service=='add')
                    <input type="text" class="form-control" name="servicetax_no" placeholder="Please Enter Valid ServiceTax No">
                @endif

                @if($service=='edit')
                    <input type="text" class="form-control" name="servicetax_no" value="@if(isset($Outlet->servicetax_no) && $Outlet->servicetax_no!='' && $Outlet->servicetax_no!=0){{$Outlet->servicetax_no}}@endif" placeholder="Please Enter Valid ServiceTax No">
                @endif
            </div>
        </div>


        <div class="col-md-12 form">
            <div class="col-md-3 form">
                <label  class="control-label ">{{ trans('Restaurant_Form.Tin No') }}</label>
            </div>
            <div class="col-md-9 form">
                @if($tin=='add')
                    <input type="text" class="form-control" name="tinno" placeholder="Please Enter Valid Tin No">
                @endif

                @if($tin=='edit')
                    <input type="text" class="form-control" name="tinno" value="@if(isset($Outlet->tinno)){{$Outlet->tinno}}@endif" placeholder="Please Enter Valid Tin No">
                @endif
            </div>
        </div>


        <div class="col-md-12">
            @if($action='add')
                <?php $i=0; ?>
                <div class="col-md-3 form">
                    <label for="closing_time" class="control-label">{{ trans('Restaurant_Form.From') }}</label>
                </div>
                <div class="col-md-9 form">
                    <div class="col-md-3 input-append bootstrap-timepicker ">

                    </div>
                    <div class="col-md-2">
                        <label for="closing_time" class="control-label"></label>
                    </div>
                    <div class="col-md-3 input-append bootstrap-timepicker ">

                    </div>
                    <div id="myID" class="col-md-4 form">
                        <input type="button"  value="Add Timeslots" class="btn btn-primary mr5">
                    </div>
                </div>
                <?php $i++; ?>
            @endif



            <?php $i=0; ?>
            @foreach($timeslots as $timeslot)
                <div class="col-md-3 form">
                    <label for="closing_time" class="control-label"></label>
                </div>
                <div class="col-md-9 form">
                    <div class="col-md-3 input-append bootstrap-timepicker ">
                        <input name="opening_time<?php echo $i;?>" id="opening_time<?php echo $i;?>" class="add-on timepicker form-control timeslot_op " type="text" class="input-small" value="{{$timeslot->from_time}}" style="width: 50%; margin-right:4px; display: inline;">
                        <span class="add-on"><i class="fa fa-clock-o"></i></span>
                    </div>
                    <div class="col-md-2">
                        <label for="closing_time" class="control-label">{{ trans('Restaurant_Form.To') }}</label>
                    </div>
                    <div class="col-md-3 input-append bootstrap-timepicker ">
                        <input name="closing_time<?php echo $i;?>" id="closing_time<?php echo $i;?>" class="add-on timepicker form-control" type="text" class="input-small" value="{{$timeslot->to_time}}" style="width: 50%; margin-right:4px; display: inline;">
                        <span class="add-on"><i class="fa fa-clock-o"></i></span>
                    </div>
                </div>
                <?php $i++;?>
            @endforeach

        </div>
        {!!Form::hidden('countf',$i,array('id'=>'countf'))!!} <!-- pre added timeslot count -->
        <div id="appendtimeslots">
        </div>

        <div class="col-md-12 form">
            <div class="col-md-3 form">
                <label class="control-label">Authorised Users</label>
            </div>
            <div class="col-md-9 form" style="border:1px solid #ccc;">
                @if($get=='add')
                    {!!Form::select('users',$users,null,array('class'=>'col-md-12 form','multiple'=>'multiple','name'=>'users[]'))!!}
                @endif
                @if($get=='edit')
                    {!!Form::select('users',$users,null,array('class'=>'col-md-12 form','multiple'=>'multiple','name'=>'users[]'))!!}
                @endif
            </div>
        </div>

        <div class="col-md-12 form">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('Restaurant_Form.Outlet Type') }}</label>
            </div>
            <div class="col-md-9 form" style="border:1px solid #ccc;">
                @if($get=='add')
                    @if(sizeof($Outlet_type)>0)
                        @foreach($Outlet_type as $Outletype)
                            <label>{{$Outletype->type}}</label>
                            <input type="checkbox" name="Outlet_type[]" value={{$Outletype->id}}>&nbsp;
                        @endforeach
                    @endif
                @endif


                @if($get=='edit')
                    @if(sizeof($Outlet_type)>0)

                        @foreach($Outlet_type as $Outletype)
                            <?php $selected=0; ?>

                            <label>{{$Outletype->type}}</label>
                                @foreach($selectedOutletType as $isselected)
                                    @if($isselected->outlet_type_id == $Outletype->id)
                                    <?php $selected=1; ?>
                                @endif
                            @endforeach
                            @if($selected==1)
                                <input type="checkbox" name="Outlet_type[]" value={{$Outletype->id}} checked="true">&nbsp;
                            @else
                                <input type="checkbox" name="Outlet_type[]" value={{$Outletype->id}}>&nbsp;
                            @endif
                        @endforeach
                    @endif
                @endif
            </div>
        </div>


        <div class="col-md-12 form">
            <div class="col-md-3 form">
                <label  class="control-label ">{{ trans('Restaurant_Form.Cuisine Types') }}</label>
            </div>
            <div class="col-md-9 form" style="border:1px solid #ccc;">
                @if($set=='add')
                    @if(sizeof($cuisineType_type)>0)
                        @foreach($cuisineType_type as $cuisinetype)
                            <label>{{$cuisinetype->type}}</label>
                            <input type="checkbox" name="cuisine_type[] " value={{$cuisinetype->id}}>&nbsp;
                        @endforeach
                    @endif
                @endif


                @if($set=='edit')
                    @if(sizeof($cuisineType_type)>0)
                        @foreach($cuisineType_type as $cuisinetype)
                            <?php $selected=0; ?>
                            <label>{{$cuisinetype->type}}</label>
                            @foreach($selectedCuisineType as $isselected)
                                @if($isselected->cuisine_type_id == $cuisinetype->id)
                                    <?php $selected=1; ?>
                                @endif
                            @endforeach

                            @if($selected==1)
                                <input type="checkbox" name="cuisine_type[]" value={{$cuisinetype->id}} checked="true">&nbsp;
                            @else
                                <input type="checkbox" name="cuisine_type[]" value={{$cuisinetype->id}}>&nbsp;
                            @endif
                        @endforeach
                    @endif
                @endif
            </div>
        </div>

        <?php $servicetype=array('take_away'=>'Take Away','dine_in'=>'Dine In','home_delivery'=>'Home Delivery','meal_packs'=>'Meal Packs');?>
        <div class="col-md-12 form">
            <div class="col-md-3 form">
                <label  class="control-label ">{{ trans('Restaurant_Form.Service Types') }}</label>
            </div>
            <div class="col-md-9 form" style="border:1px solid #ccc;">
                @if($test=='add')

                    @foreach($servicetype as $key=>$value)
                        {{$value}}  <input type="checkbox" name="service_type[]" value={{$key}} >&nbsp;
                    @endforeach
                    <?php
                    if (isset($_POST['service_type']))
                    {
                        echo $_POST['service_type']; // Displays value of checked checkbox.
                    }
                    ?>
                @endif

                @if($test=='edit')
                    @foreach($servicetype as $key=>$value)
                            @if(isset($Outlet->service_type)  && $Outlet->service_type!="" && is_array($abc) && in_array($key,$abc))
                                {{$value}}   <input type="checkbox" name="service_type[]" value={{$key}} checked="true">&nbsp;
                            @else
                                {{$value}}  <input type="checkbox" name="service_type[]" value={{$key}}>&nbsp;
                            @endif
                    @endforeach
                    @endif
            </div>
        </div>

        <div class="col-md-12 form">
            <div class="col-md-3 form">
                <label  class="control-label ">{{ trans('Restaurant_Form.Service Tax') }}</label>
            </div>
            <div class="col-md-9 form">
                @if($test=='add')
                    <input type="text" class="form-control" name="service_tax" placeholder="Please Enter Valid ServiceTax No">
                @endif

                @if($test=='edit')
                    <input type="text" class="form-control" name="service_tax" value="@if(isset($Outlet->service_tax) && $Outlet->service_tax!='' && $Outlet->service_tax!=0){{$Outlet->service_tax}}@endif" placeholder="Please Enter Valid Service Tax">
                @endif
            </div>
        </div>

        <div class="col-md-12 form">
            <div class="col-md-3 form">
                <label  class="control-label ">{{ trans('Restaurant_Form.VAT') }}</label>
            </div>
            <div class="col-md-9 form">
                @if($test=='add')
                    <input type="text" class="form-control" name="vat" placeholder="Please Enter Valid VAT">
                @endif


                @if($test=='edit')
                    <input type="text" class="form-control" name="vat" value="@if(isset($Outlet->vat)){{$Outlet->vat}}@endif" placeholder="Please Enter Valid VAT">
                @endif
            </div>
        </div>

        <div class="col-md-12 form">
            <div class="col-md-3 form">
                <label  class="control-label ">VAT Date</label>
            </div>
            <div class="col-md-9 form">
                @if($test=='add')
                    {!! Form::text('vat_date', null, ['class' => 'form-control','placeholder'=>"Select Date","id"=>"vat_date","readonly"=>"readonly"]) !!}
                @endif
                @if($test=='edit')
                    <input type="text" class="form-control" readonly="true" name="vat_date" id="vat_date" value="@if(isset($Outlet->vat_date)){{$Outlet->vat_date}}@endif" placeholder="Select Date">
                @endif
            </div>
        </div>

        {{--<div class="col-md-12 form">--}}
            {{--<div class="col-md-3 form">--}}
                {{--<label  class="control-label ">Additional tax</label>--}}
            {{--</div>--}}
            {{--<div class="col-md-9 form" style="border:1px solid #ccc;">--}}
                {{--@if($test=='add')--}}
                    {{--<input type="text" class="form-control" name="additional_tax" placeholder="Please Enter Valid Additional Taxes">--}}
                {{--@endif--}}


                {{--@if($test=='edit')--}}
                    {{--<input type="text" class="form-control" name="additional_tax" value="@if(isset($Outlet->additional_tax)){{$Outlet->additional_tax}}@endif" placeholder="Please Enter Valid Additional Tax">--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}





        <?php $user=Auth::user();?>

        <div class="col-md-12 form">
            <div class="col-md-3 form">
                <label for="option">{{ trans('Restaurant_Form.Active') }}</label>
            </div>

            <div class="col-md-9 form" style="border:1px solid #ccc;">
                @if($make=='add')
                <label for="option">Yes</label>
                <input type="radio" name="active" id="active"value='{{'Yes'}}'>
                <label for="option">No</label>
                <input type="radio" name="active" id="active"value='{{'No'}}'>
                @endif

                @if($make=='edit')
                        <input type=radio name="active" id="active" value="Yes"onclick="setReadOnly(this)" <?php if($Outlet['active'] == 'Yes'){ echo 'checked'; } ?>>Yes
                        <input type=radio name="active" id="active" value="No" onclick="setReadOnly(this)" <?php if($Outlet['active'] == 'No'){ echo 'checked'; } ?>>No
                    @endif
            </div>
        </div>


        <div class="col-md-12 form">
            <div class="col-md-3 form"></div>
            <div class="col-md-9 form">
                <button class="btn btn-primary mr5"  id="Submit" novalidate="novalidate" type="Submit" >{{ trans('Restaurant_Form.Submit') }}</button>
                <button class="btn btn-default" type="reset">{{ trans('Restaurant_Form.Reset') }}</button>
            </div>
        </div>

    </form>

@section('page-scripts')
    <script type="text/javascript">
        var i=1;
        var someText='';
        var z = document.getElementById("countf").value; //get pre added time slots
        $("#myID").click(
                function () {
                    var x=z++;  //when add new features increase id
                    someText = '<div class="">' +
                    '<div class="" id="control'+x+'">'+
                    '<div class="col-md-3 form">'+
                    '</div>'+
                    '<div class="col-md-9 form" style="padding-left:0px;">'+
                    '<div class="col-md-3 input-append bootstrap-timepicker ">'+
                    '<input name="opening_time'+x+'" id="opening_time'+x+'" class="timepicker form-control add-on" type="text" value="@if(isset($Outlet->opening_time)){{$Outlet->opening_time}}@endif" style="width: 50%; margin-right:4px; display: inline;">'+

                    '<span class="add-on"><i class="fa fa-clock-o"></i></span></div>'+
                    '<div class="col-md-2 timepicker-label">'+
                    '<label for="closing_time" class="control-label">{{ trans('Restaurant_Form.To') }}</label>'+
                    '</div>'+
                    ' <div class="col-md-3 input-append bootstrap-timepicker">'+
                    '<input name="closing_time'+x+'" id="closing_time'+x+'" class="timepicker form-control add-on" type="text"  value="@if(isset($Outlet->closing_time)){{$Outlet->closing_time}}@endif" style="width: 50%; margin-right:4px; display: inline;">'+

                    '<span class="add-on"><i class="fa fa-clock-o"></i></span> </div>'+
                    '<input type="button" class="btn btn-primary mr5" onclick="control'+x+'.remove()" value="{{ trans('Restaurant_Form.Delete') }}"><br/>'+

                    '</div>'+


                    '<input type=hidden name="count" value='+x+'></div></div>';
                    var newDiv = $("<div class='col-md-12'>").append(someText);
                    $("#appendtimeslots").append(newDiv);

                    $('#opening_time'+x).timepicker({
                        format: 'HH:mm',
                        minuteStep: 1,
                        pick12HourFormat: false,
                        showMeridian: false
                    });

                    $('#closing_time'+x).timepicker({
                        format: 'HH:mm',
                        minuteStep: 1,
                        pick12HourFormat: false,
                        showMeridian: false
                    });

                    $(".timepicker").timepicker({
                        format: 'HH:mm',
                        minuteStep: 1,
                        pick12HourFormat: false,
                        showMeridian: false
                    });
                }
        )


        $ (function () {
            // var x=z++;

            $('#opening_time0').timepicker({
                format: 'HH:mm',
                minuteStep: 1,
                pick12HourFormat: false,
                showMeridian: false
            });

            $(".timepicker").timepicker({
                format: 'HH:mm',
                minuteStep: 1,
                pick12HourFormat: false,
                showMeridian: false
            });


            $('#closing_time0').timepicker({
                format: 'HH:mm',
                minuteStep: 1,
                pick12HourFormat: false,
                showMeridian: false
            });

            $('#established_date').datepicker({
                format: "yyyy/mm/dd"
            });

        });
    </script>



    <script type="text/javascript">
        $(document).ready(function() {

            $('#vat_date').datepicker({
                dateFormat: "yy/mm/dd",
                maxDate: new Date,
                setdate:new Date

            });

            $("#Submit").validate({
                rules: {
                    "Outlet_name": {
                        required: true
                    },
                    "address": {
                        required: true
                    },
                    "pincode": {
                        required: true,
                        rangelength: [6, 6]
                    },
                    "avg_cost_of_two": {
                        required: true
                    },
                    "outlet_code": {
                        required: true
                    }
                },
                messages: {
                    "Outlet_name": {
                        required: "Outlet Name is required"
                    },
                    "address": {
                        required: "Address is required"
                    },
                    "pincode": {
                        required: "Pincode is required",
                        rangelength: "Pincode should be 6digits only"
                    },
                    "avg_cost_of_two": {
                        required: "Avg Cost Of Two is required"
                    },
                    "outlet_code": {
                        required: "Outlet Code is required"
                    }
                }
            })
        })
            $('#Submit').click(function() {
                $("#Outlet_form").valid();  // This is not working and is not validating the form

                })
    </script>
@stop
