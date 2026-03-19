
<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">

                @if($action=='edit')
                    {!! Form::model($vendor,['route' => array('vendor.update',$vendor->id),'novalidate'=>"novalidate", 'method' => 'patch', 'id' => 'vendorForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                @else
                    {!! Form::open(['route' => 'vendor.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms','novalidate'=>"novalidate", 'id' => 'vendorForm']) !!}
                @endif

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('name', 'Name*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('name', null, array('class' => 'form-control','id' => 'name', 'placeholder'=> 'Vendor Name','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('type', 'Type*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('type', null, array('class' => 'form-control','id' => 'type', 'placeholder'=> 'Type: Ex.Vegetables, Grocery','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('contact_person', 'Contact Person*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('contact_person', null, array('class' => 'form-control','id' => 'contact_person', 'placeholder'=> 'Contact Person','required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('vendor_gst', 'Vendor GST:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('vendor_gst', null, array('class' => 'form-control','id' => 'vendor_gst', 'placeholder'=> 'GST number')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('contact_number', 'Contact Number*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::input('number','contact_number' ,null, ['class' => 'form-control','id' => 'contact_number', 'placeholder'=> 'Contact Number','required']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('address', 'Address:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::textarea('address', null, array('class' => 'form-control','rows'=>'3','id' => 'address', 'placeholder'=> 'Address')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('countries', 'Country', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                @if($action=='add')
                                    <select class="form-control" name="countries" id="countries">
                                        <option selected >{{ trans('Restaurant_Form.Select Country') }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id or ''}}">{{$country->name or ''}}</option>
                                        @endforeach
                                    </select>
                                @endif

                                @if($action=='edit')
                                    <select class="form-control select2" name="countries" id="countries">
                                        @foreach($countries as $country)
                                            @if($vendor->country_id == $country->id)
                                                <option value="{{$country->id or ''}}" selected>{{$country->name or ''}}</option>
                                            @else
                                                <option value="{{$country->id or ''}}">{{$country->name or ''}}</option>
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
                        <div class="col-md-8">
                            {!! Form::label('states', 'State', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                @if($action=='add')
                                    <select class="form-control select2" name="states" id="states">
                                        <option selected >Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id or ''}}">{{$state->name or ''}}</option>
                                        @endforeach
                                    </select>
                                @endif

                                @if($action=='edit')
                                    <select class="form-control select2" name="states" id="states">
                                        @foreach($states as $state)
                                            @if($vendor->country_id == $country->id)
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
                        <div class="col-md-8">
                            {!! Form::label('city', 'City', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                @if($action=='add')
                                    <select class="form-control select2" name="cities" id="cities">
                                        <option selected >{{ trans('Restaurant_Form.Select City') }}</option>
                                        @foreach($cities as $city)
                                            <option  value="{{$city->id or ''}}">{{$city->name or ''}}</option>
                                        @endforeach
                                    </select>
                                @endif

                                @if($action=='edit')
                                    <select class="form-control select2" name="cities" id="cities">
                                        @foreach($cities as $city)
                                            @if($vendor->city_id == $city->id)
                                                <option value="{{$city->id or ''}}" selected>{{$city->name or ''}}</option>
                                            @else
                                                <option value="{{$city->id or ''}}">{{$city->name or ''}}</option>
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
                        <div class="col-md-8">
                            {!! Form::label('pincode', 'Pincode*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('pincode' ,null, ['class' => 'form-control','id' => 'pincode', 'placeholder'=> 'Pincode',"required"]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <div class="col-md-8">
                        @if($action=='edit')
                            <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                            {!! HTML::decode(HTML::linkRoute('vendor.index','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
                        @else
                            <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true" >Save & Exit</button>
                            <button name='saveContinue' id='saveContinue' class="btn btn-success primary-btn"  novalidate="novalidate" type="Submit" value="true">Save & Continue</button>
                            <button id='reset_form' class="btn btn-danger primary-btn" type="reset">Reset</button>
                        @endif
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
        $('#countries').select2({
            placeholder: 'Select Country'
        });
        $("#vendorForm").validate({
            rules: {
                "name": {
                    required: true
                },
                "contact_number": {
                    required: true
                },"contact_person":{
                    required: true
                },"pincode":{
                    required: true,
                    number: true,
                    rangelength: [6, 6]
                },"countries":{
                    required: true
                },"states":{
                    required: true
                },"cities":{
                    required: true
                }
            },
            messages: {
                "name": {
                    required: "*Name is required"
                },
                "contact_number": {
                    required: "*Contact Number is required and Numeric value"
                },"contact_person":{
                    required: "*Contact Person field is required"
                },"pincode":{
                    required: "*Pincode field is required",
                    rangelength: "Pincode length must be 6 digits"
                },"countries":{
                    required: "*Country field is required"
                },"states":{
                    required: "*State field is required"
                },"cities":{
                    required: "*City field is required"
                }
            }

        });

    });

</script>
@stop