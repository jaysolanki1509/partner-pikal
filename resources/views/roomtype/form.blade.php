<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">
                @if($action=='edit')
                    {!! Form::model($room_type,['route' => array('room-type.update',$room_type->id), 'method' => 'patch', 'id' => 'roomTypeForm', 'class' => 'form-horizontal material-form j-forms']) !!}
                @else
                    {!! Form::open(['route' => 'room-type.store', 'method' => 'post', 'class' => 'form-horizontal material-form j-forms', 'id' => 'roomTypeForm']) !!}
                @endif

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::label('name','Room Type Name*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('name', null, array('class' => 'col-md-3 form-control','id' => 'name', 'placeholder'=> 'Room Type Name','required')) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            {!! Form::label('short_name','Short Name*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('short_name', null, array('class' => 'col-md-3 form-control','id' => 'short_name', 'placeholder'=> 'Room Short Name')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('description','Room Type Description:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::textarea('description', null, array('rows'=>3,'class' => 'col-md-3 form-control','id' => 'color', 'placeholder'=> 'Room Type description')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            {!! Form::label('base_occupancy','Base Occupancy*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::select('base_occupancy', $one_to_fifty,null, array('rows'=>3,'class' => 'col-md-3 form-control','id' => 'base_occupancy', 'required')) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            {!! Form::label('higher_occupancy','Higher Occupancy*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::select('higher_occupancy', $one_to_fifty,null, array('rows'=>3,'class' => 'col-md-3 form-control','id' => 'higher_occupancy', 'required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="col-md-12">&nbsp;</div>
                            <div class="col-md-12">
                                <label class="checkbox">
                                    {!! Form::checkbox('extra_bed_allowed', 1,false,["id"=>"extra_bed_allowed","onchange" => "changeExtraBed()","style"=>"margin-top:0px;"]) !!}
                                    <i></i><span style="font-weight: bold"> Allow Extra Bed? </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 hide" id="extra_bed_1">
                            {!! Form::label('no_of_beds_allowed','No of Extra Beds*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::select('no_of_beds_allowed', $zero_to_fifty,null, array('rows'=>3,'class' => 'col-md-3 form-control','id' => 'no_of_beds_allowed')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2">
                            {!! Form::label('base_price','Base price*:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('base_price', null, array('class' => 'col-md-12   form-control','id' => 'base_price', 'placeholder'=> 'Base price','required')) !!}
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-2">
                            {!! Form::label('higher_price_per_person','Higher price per person:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('higher_price_per_person', null, array('class' => 'col-md-12 form-control','id' => 'higher_price_per_person', 'placeholder'=> 'Higher price per person')) !!}
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-2 hide" id="extra_bed_2">
                            {!! Form::label('extra_bed_price','Extra Bed Price:', array('class' => 'col-md-12 control-label')) !!}
                            <div class="col-md-12">
                                {!! Form::text('extra_bed_price', null, array('class' => 'col-md-12 form-control','id' => 'extra_bed_price', 'placeholder'=> 'Higher price per person')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">

                        <div class="col-md-12">
                            <label  class="control-label col-md-12">Select Amenities:</label>
                        </div>
                        <div class="col-md-12">

                            <div class="unit col-md-12">
                                <div class="inline-group">

                                    @if($action=='add')
                                        @if(sizeof($room_amenities)>0)
                                            @foreach($room_amenities as $id => $amenities)
                                                <label class="checkbox">
                                                    <input style="margin-top:0px;" type="checkbox" name="amenities[]" value={{$id}}>
                                                    <i></i>{{$amenities}}
                                                </label>
                                            @endforeach
                                        @endif
                                    @else
                                        @if(sizeof($room_amenities)>0)

                                            @foreach($room_amenities as $id => $amenities)

                                                <?php $selected=0; ?>
                                                @if(isset($room_type->amenities) && sizeof($room_type->amenities)>0 && $room_type->amenities != 'null')
                                                    @foreach(json_decode($room_type->amenities) as $isselected)
                                                        @if($isselected == $id)
                                                            <?php $selected=1; ?>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                @if($selected==1)
                                                    <label class="checkbox">
                                                        <input style="margin-top:0px;" type="checkbox" name="amenities[]" checked="true" value={{$id}}>
                                                        <i></i>{{$amenities}}
                                                    </label>
                                                @else
                                                    <label class="checkbox">
                                                        <input style="margin-top:0px;" type="checkbox" name="amenities[]" value={{$id}}>
                                                        <i></i>{{$amenities}}
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

                <div class="form-footer">
                    <div class="col-md-8">
                        @if($action=='edit')
                            <button name='saveExit' id='saveExit' class="btn btn-success primary-btn" type="Submit" value='true'>Update</button>
                            {!! HTML::decode(HTML::linkRoute('room-type.index','Cancel', array(), array('class'=>'btn btn-danger primary-btn', 'id'=>''))) !!}
                        @else
                            <button name='saveExit' id='saveExit'  class="btn btn-success primary-btn" type="Submit" value="true" >Save & Exit</button>
                            <button name='saveContinue' id='saveContinue' class="btn btn-success primary-btn" type="Submit" value="true">Save & Continue</button>
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

        function changeExtraBed(){
            var check = $('#extra_bed_allowed').is(':checked');

            if(check == true){
                $("#extra_bed_1").removeClass("hide");
                $("#extra_bed_2").removeClass("hide");
            }else{
                $("#extra_bed_1").addClass("hide");
                $("#extra_bed_2").addClass("hide");

            }
        }

        $(document).ready(function() {
            changeExtraBed();

            $.validator.addMethod('ge', function(value, element, param) {
                return this.optional(element) || parseInt(value) >= parseInt($(param).val());
            }, 'Invalid value');

            $("#roomTypeForm").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "short_name": {
                        required: true
                    },
                    "extra_bed_price": {
                        required: "#extra_bed_allowed:checked",
                        number: true
                    },
                    "higher_price_per_person": {
                        required: true,
                        number: true
                    },
                    "base_price": {
                        required: true,
                        number: true
                    },
                    "higher_occupancy": {
                        ge: '#base_occupancy'
                    }
                },
                messages: {
                    "name": {
                        required: "*Name is required"
                    },
                    "short_name": {
                        required: "*Short Name is required"
                    },
                    "extra_bed_price": {
                        required: "*Extra Bed Price is required",
                        number: "*Price should be in number"
                    },
                    "higher_price_per_person": {
                        required: "*Heigher Price per person is required",
                        number: "*Price should be in number"
                    },
                    "base_price": {
                        required: "*Base Price per person is required",
                        number: "*Price should be in number"
                    },
                    "higher_occupancy": {
                        ge: "*Higher Occupancy is equal or more then Base Occupancy"
                    }
                }

            })

        });


    </script>
@stop