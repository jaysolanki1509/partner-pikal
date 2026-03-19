<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">

                @if($action=='add')
                    <form class="form-horizontal material-form j-forms" role="form" method="POST" id="coupon_form" action="{{ url('/coupongenerator') }}" enctype="multipart/form-data">
                @else
                    {!! Form::model($coupon,array('route' => array('coupongenerator.update',$coupon->id) ,'method' => 'patch', 'class' => 'form-horizontal material-form j-forms','id'=>'coupon_form')) !!}
                @endif


                    {{--<div class="form-group">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-md-8">--}}
                                {{--<label class="col-md-12 control-label">Select Outlet*</label>--}}
                                {{--<div class="col-md-12">--}}
                                    {{--@if($action=='edit' && isset($coupon->outlet_ids))--}}
                                        {{--{!!Form::select('outlets',$outlets,explode(',',$coupon->outlet_ids),array('id'=>'outlets','class'=>' form-control','multiple'=>'multiple','name'=>'outlets[]'))!!}--}}
                                    {{--@else--}}
                                        {{--{!!Form::select('outlets',$outlets,null,array('class'=>' form-control','id'=>'outlets','multiple'=>'multiple','name'=>'outlets[]'))!!}--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="col-md-12 control-label" >{{ trans('Coupon.Discount Type') }}</label>
                                <div class="col-md-12">
                                    <?php $selected='0'; if(isset($coupon) && $coupon->percentage!='') $selected='1' ?>
                                    <?php if(isset($coupon) && $coupon->value!='') $selected='2' ?>
                                    {!! Form::select('Browser',[''=>'Select Discount Method','1'=>'By Percentage','2'=>'By FixedValue'] , $selected, ['onchange'=>'change_type()' ,'class' => ' form-control ','required', 'id' => 'dis_method']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group hide" id="percentage_div">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="col-md-12 control-label">{{ trans('Coupon.Percentage*') }}</label>
                                <div class="col-md-12">
                                    {!! Form::text('percentage',null, array('class'=>'form-control','id'=>'percentage' ,'required','placeholder'=> 'Please enter Percentage')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group hide" id="fixed_val_div">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="col-md-12 control-label">{{ trans('Coupon.FixedValue*') }}</label>
                                <div class="col-md-12">
                                    {!! Form::text('value',null, array('id'=>'value','class'=>'form-control','size'=>'50' ,'required', 'placeholder'=> 'Please enter Fixed Value')) !!}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="col-md-12 control-label">{{ trans('Coupon.Coupon Code*') }}</label>
                                <div class="col-md-12">
                                    {!! Form::text('coupon_code',null, array('class'=>'form-control','id'=>'coupon_code' ,'placeholder'=> 'Please Enter Coupon Code','required')) !!}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="col-md-12 control-label">Minimum Order Value*</label>
                                <div class="col-md-12">
                                    {!! Form::text('min_value',null, array('id'=>'min_value','class'=>'form-control' ,'placeholder'=> 'Please Enter Minimum Value')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="maximumdiscount_div">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="closing_time" class="col-md-12 control-label">{{ trans('Coupon.Maximum Discount Value*') }}</label>
                                <div class="col-md-12">
                                    {!! Form::text('max_value',null, array('id'=>'max_value','class'=>'form-control' ,'placeholder'=> 'Please Enter Maximum Value')) !!}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="col-md-12 control-label">{{ trans('Coupon.Activated Date*') }}</label>
                                <div class="col-md-12">
                                        {!! Form::text('activated_datetime',null, array('class'=>'form-control','id'=>'activated_date' ,'required','placeholder'=> 'Please enter Coupon Activation Date')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="col-md-12 control-label">{{ trans('Coupon.Expire Date*') }}</label>
                                <div class="col-md-12">
                                    {!! Form::text('expire_datetime',null, array('class'=>'form-control','required','id'=>'expire_date' ,'placeholder'=> 'Please enter Coupon Expire Date')) !!}
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="col-md-12 control-label">{{ trans('Coupon.No Of Users') }}</label>
                                <div class="col-md-12">
                                    {!! Form::text('no_of_users',null, array('class'=>'form-control','id'=>'no_of_users' ,'placeholder'=> 'Please Enter No Of Users')) !!}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-footer">
                        <div class="col-md-8">
                            <button class="btn btn-success primary-btn" type="Submit">{{ trans('Coupon.Submit') }}</button>
                            @if($action=='add')
                                <button class="btn btn-danger primary-btn" type="reset" id="resetBtn" onclick="$(this).closest('form').find('input[type=text], textarea').val('');$('#percentage').hide();$('#maximumdiscount').hide();$('#fixedvalue').hide();">{{ trans('Coupon.Reset') }}</button>
                            @else
                                <button class="btn btn-danger primary-btn" type="button" id="cancel" onclick="location.href='/coupongenerator'">Cancel</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script type="text/javascript">

        $('#resetBtn').click(function(){
            $('#coupon_code').removeAttr('value');
            $('#value').removeAttr('value');
            $('#min_value').removeAttr('value');
            $('#max_value').removeAttr('value');
            $('#activated_date').removeAttr('value');
            $('#expire_date').removeAttr('value');
            $('#no_of_users').removeAttr('value');
            $("#dis_method option:selected").removeAttr("selected");
            $("#outlets option:selected").removeAttr("selected");
        });

        function capitalize(textboxid, str) {
            // string with alteast one character
            if (str && str.length >= 1)
            {
                var Char = str.charAt(0);
                var remainingStr = str.slice(1);
                str = zChar.toUpperCase() + remainingStr;
            }
            document.getElementById(textboxid).value = str;
        }
        
        $('#activated_date').DatePicker({
            format: "yyyy-mm-dd",
            orientation: "auto",
            autoclose: true,
            todayHighlight: true
        });
        $('#expire_date').DatePicker({
            format: "yyyy-mm-dd",
            orientation: "auto",
            autoclose: true,
            todayHighlight: true
        });

        $(document).ready(function()
        {
//            $('#outlets').select2({
//                placeholder: 'Select Outlet'
//            });
            $('#dis_method').select2({
                placeholder: 'Select Discount Type'
            });

            $("#expire_date").change(function () {
                var startDate = document.getElementById("activated_date").value;
                var endDate = document.getElementById("expire_date").value;

                if ((Date.parse(startDate) >= Date.parse(endDate))) {
                    alert("Expire date should be greater than Activated date");
                    document.getElementById("expire_date").value = "";
                }
            });

            $("#activated_date").change(function () {
                if(document.getElementById("expire_date").value!=null){
                    var startDate = document.getElementById("activated_date").value;
                    var endDate = document.getElementById("expire_date").value;
                    if ((Date.parse(startDate) >= Date.parse(endDate))) {
                        alert("Activated date should be less than Expire date");
                        document.getElementById("activated_date").value = "";
                    }
                }
            });

            $('#coupon_code').keyup(function(){
                this.value = this.value.toUpperCase();
            });

            $("#coupon_form").validate({
                rules: {
                    "coupon_code": {
                        required: true
                    },
                    "min_value": {
                        required: true,
                        digits:[0,9]
                    },
                    "max_value": {
                        required: true,
                        digits:[0,9]
                    },
                    "activated_datetime": {
                        date:true,
                        required: true
                    },
                    "expire_datetime":{
                        date:true,
                        required: true
                    },
                    "percentage":{
                        range:[0,100]
                    },
                    "value":{
                        digits:[0,9]
                    },
                    "no_of_users":{
                        digits:[0,9]
                    }
                },
                messages: {
                    "coupon_code": {
                        required: "Coupon Code is required"
                    },
                    "min_value": {
                        required: "Minimum Value is required"
                    },
                    "max_value": {
                        required: "Maximum Value is required"
                    },
                    "activated_datetime": {
                        required: "Valid from Date is required"
                    },
                    "expire_datetime": {
                        required: "Expired Date is required"
                    }
                }
            });
        });

        $('#Submit').click(function() {
            $("#coupon_form").valid();  // This is not working and is not validating the form
        });

        function change_type(){

            if ($('#dis_method option:selected').val() == '1'){
                $('#percentage_div').removeClass('hide');
                $('#fixed_val_div').addClass('hide');
                $('#fixedvalue').val('');
                $('#maximumdiscount_div').removeClass('hide');
            }else if ($('#dis_method option:selected').val() == '2'){
                $('#percentage_div').addClass('hide');
                $('#percentage').val('');
                $('#fixed_val_div').removeClass('hide');
                $('#maximumdiscount_div').addClass('hide');
                $('#maximumdiscount').val('');
            } else {
                $('#percentage').hide();
                $('#percentage').hide('');
                $('#fixedvalue').hide();
                $('#fixedvalue').hide('');
                $('#maximumdiscount').hide();
                $('#maximumdiscount').hide('');
            }
        }

    </script>

@stop




