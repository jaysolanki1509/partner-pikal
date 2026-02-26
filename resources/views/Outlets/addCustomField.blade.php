
@extends('partials.default')

@section('pageHeader-left')
    Add Custom Field
@stop

@section('pageHeader-right')
    <a href="/bill-template" class="btn btn-primary"><i class="fa fa-eye"></i> Bill Template</a>
    <a href="/update-bill-template" class="btn btn-primary"><i class="fa fa-edit"></i> Edit Template</a>
    @if(isset($custom_fields) && sizeof($custom_fields)>0)
        <a href="#" onclick="removeAllFields()" class="btn btn-primary"><i class="fa fa-remove"></i> Remove All Fields</a>
    @endif
@stop

@section('content')


    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    <div class="form-group">
                        <div class="row">
                            <div class="row parent-div">

                                <div class="col-md-12">

                                    <div class="col-md-3">
                                        <label class="col-md-12 control-label">Key</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-md-12 control-label">Label</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="unit" class="col-md-12 control-label">Type</label>
                                    </div>

                                </div>
                                @if(isset($custom_fields) && sizeof($custom_fields)>0)
                                    <?php $i = 0; ?>
                                    @foreach($custom_fields as $field)
                                        @foreach($field as $key=>$val)
                                            <div class="col-md-12 custom_field" style="margin-top: 10px;">

                                                <div class="col-md-3">
                                                    <div class="col-md-12 form-group">
                                                        {!! Form::text('key' ,$key, ['class' => 'form-control', 'placeholder'=> 'Key','id'=>'key'] ) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="col-md-12 form-group">
                                                        {!! Form::text('label' ,$val[0]->label, ['class' => 'form-control', 'placeholder'=> 'Label','id'=>'label'] ) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="col-md-12">
                                                        {!! Form::select('type',["text"=>"Text","date"=>"Date","date_time"=>"Date Time"],$val[0]->type, ['class' => 'form-control','required', 'id' => 'type']) !!}
                                                    </div>
                                                </div>
                                                <?php $i++; ?>

                                                <div class="col-md-2">
                                                    <div class="col-md-12">
                                                        @if($i == sizeof($custom_fields))
                                                            <button class="btn btn-danger" onclick="removeField(this)"><i class="fa fa-times"></i></button>
                                                            <button class="btn btn-primary" onclick="addNewField(this)"><i class="fa fa-plus"></i></button>
                                                        @elseif($i < sizeof($custom_fields))
                                                            <button class="btn btn-danger" onclick="removeField(this)"><i class="fa fa-times"></i></button>
                                                            <button class="btn btn-primary hide" onclick="addNewField(this)"><i class="fa fa-plus"></i></button>
                                                        @else
                                                            <button class="btn btn-danger" onclick="removeField(this)"><i class="fa fa-times"></i></button>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>

                                        @endforeach
                                    @endforeach
                                @else

                                    <div class="col-md-12 custom_field" style="margin-top: 10px;">

                                        <div class="col-md-3">
                                            <div class="col-md-12 form-group">
                                                    {!! Form::text('key' ,null, ['class' => 'form-control', 'placeholder'=> 'Key','id'=>'key'] ) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="col-md-12 form-group">
                                                    {!! Form::text('label' ,null, ['class' => 'form-control', 'placeholder'=> 'Label','id'=>'label'] ) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="col-md-12">
                                                {!! Form::select('type',["text"=>"Text","date"=>"Date","date_time"=>"Date Time"],null, ['class' => 'form-control','required', 'id' => 'type']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="col-md-12">
                                                <button class="btn btn-primary " onclick="addNewField(this)"><i class="fa fa-plus"></i></button>
                                                <button class="btn btn-danger hide" onclick="removeField(this)"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                @endif
                            </div>

                            <div class="col-md-11">
                                <div class="pull-right" style="margin-top: 10px; ">
                                    <button class="btn btn-success" onclick="storeFields()"  id="save_fields_btn" type="button" > Submit </button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


@stop

@section('page-scripts')
    <script type="text/javascript">

        $(document).ready(function() {
            $('#account_id').select2({
                placeholder: 'Select an Account'
            });
            var count = $('.custom_field').length;
            if(count == 1){
                $('.btn-danger').addClass('hide');
            }
        });

        //open print popup
        function addNewField(ele) {

            if ( $(ele).closest('.custom_field').find('#key').val().trim() == '' ) {

                $(ele).closest('.custom_field').find('#key').css('border','red 1px solid');
                $(ele).closest('.custom_field').find('#key').focus();
                return;
            }else{
                $(ele).closest('.custom_field').find('#key').css('border','#cccccc 1px solid');
            }

            if ( $(ele).closest('.custom_field').find('#label').val().trim() == '' ) {

                $(ele).closest('.custom_field').find('#label').css('border','red 1px solid');
                $(ele).closest('.custom_field').find('#label').focus();
                return;
            }else{
                $(ele).closest('.custom_field').find('#label').css('border','#cccccc 1px solid');
            }

            var child = $(ele).closest('.custom_field').clone();
            $(ele).closest('.parent-div').append(child);

            $(ele).addClass('hide');
            $(ele).parent().find('.btn-danger').removeClass('hide');

            $(ele).closest('.parent-div').find('.btn-danger:last').removeClass('hide');
            $(ele).closest('.parent-div').find('.custom_field:last').find('#key').val('').focus();
            $(ele).closest('.parent-div').find('.custom_field:last').find('#label').val('');

        }

        function storeFields() {
            var fields = [];

            $('.custom_field').each(function(index){

                var key = $(this).find('#key').val();
                var label = $(this).find('#label').val();
                var type = $(this).find('#type').val();

                key_obj = {};

                if ( key != '' && label != '' ) {
                    var tempArray = [];
                    var tempObject = {};
                    tempObject.label = label;
                    tempObject.type = type;

                    tempArray.push(tempObject);
                    key_obj[key] = tempArray;
                    fields.push(key_obj);

                    $(this).closest('.custom_field').find('#key').css('border','#cccccc 1px solid');
                    $(this).closest('.custom_field').find('#label').css('border','#cccccc 1px solid');
                } else {
                    if ( key == '' ) {
                        $(this).closest('.custom_field').find('#key').css('border','red 1px solid');
                        $(this).closest('.custom_field').find('#key').focus();
                        return;
                    }else{
                        $(this).closest('.custom_field').find('#key').css('border','#cccccc 1px solid');
                    }
                    if ( label == '' ) {
                        $(this).closest('.custom_field').find('#label').css('border','red 1px solid');
                        $(this).closest('.custom_field').find('#label').focus();
                        return;
                    }else{
                        $(this).closest('.custom_field').find('#label').css('border','#cccccc 1px solid');
                    }
                }
            });

            var fields_str = JSON.stringify(fields);

            $.ajax({
                type:'post',
                url:'/addCustomField',
                data:{fields:fields_str},
                dataType:'json',
                success:function(data){

                    processBtn('save_fields_btn','remove','Submit');
                    if ( data.status == 'success') {
                        successErrorMessage(data.msg,'success');
                    } else {
                        successErrorMessage(data.msg,'error');
                    }
                }
            });

        }

        function removeField(ele) {

            $(ele).closest('.custom_field').remove();
            $('.custom_field:last').find('.btn-primary:last').removeClass('hide');
            var count = $('.custom_field').length;
            if(count == 1){
                $('.btn-danger').addClass('hide');
            }
        }

        function removeAllFields() {

            $.ajax({
                type:'post',
                url:'/addCustomField',
                data:{fields:""},
                dataType:'json',
                success:function(data){

                    processBtn('save_fields_btn','remove','Submit');
                    if ( data.status == 'success') {
                        successErrorMessage(data.msg,'success');
                        location.reload();
                    } else {
                        successErrorMessage(data.msg,'error');
                    }
                }
            });

        }

    </script>


@stop