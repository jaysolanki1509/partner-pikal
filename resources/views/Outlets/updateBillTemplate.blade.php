@extends('partials.default')
@section('pageHeader-left')
    Update Bill Template
@stop

@section('page-styles')
    {!! HTML::style('/assets/css/new/jquery-ui-1.12.4.css') !!}

@stop

@section('pageHeader-right')
    <a href="/bill-template" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
    <a href="/addCustomField" class="btn btn-primary"><i class="zmdi zmdi-wb-auto"></i>Custom Fields</a>
    <a href="/update-bill-template?flag=default" class="btn btn-primary"><i class="fa fa-refresh"></i> Set Default Bill</a>
@stop

@section('content')

    <div class="row">

        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-container">
                    <div class="widget-content">

                        <form class="material-form j-forms" id="billtemplate">

                            <div class="bill-headers">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="control-label">Move</label>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="control-label">Line</label>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="control-label">Size</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Align</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Key</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="bill-content column">

                                @if( isset($line_arr->sequence) && sizeof($line_arr->sequence) > 0 )
                                    <?php $lbl_cnt = 0;?>
                                    @for( $i=0; $i < sizeof($line_arr->sequence); $i++ )

                                        <div class="form-group sequence portlet">
                                            <div class="row">
                                                <div class="col-md-1" >
                                                    <label style="margin-top: 10px;"><i class="zmdi zmdi-swap-vertical zmdi-hc-lg"></i></label>
                                                </div>
                                                <div class="col-md-1">
                                                    {!! Form::text('sequence[]', $line_arr->sequence[$i], array('class' => 'form-control line', 'placeholder'=> 'No.','required')) !!}
                                                </div>
                                                <div class="col-md-1">
                                                    {!! Form::select('font[]',array('5'=>'5','6'=>'6'), $line_arr->font[$i], array('class' => 'form-control font','onchange'=>'checkKey(this)')) !!}
                                                </div>
                                                <div class="col-md-2">
                                                    {!! Form::select('align[]',array('left'=>'Left','center'=>'center','right'=>'Right'), $line_arr->align[$i], array('class' => 'form-control align')) !!}
                                                </div>
                                                <div class="col-md-2">
                                                    {!! Form::select('key[]',$keys, $line_arr->key[$i], array('class' => 'form-control key','onchange'=>'checkKey(this)')) !!}
                                                </div>

                                                @if ( $line_arr->key[$i] == 'bill_lable' )

                                                    <div class="col-md-3 dyn-lable">
                                                        <input type="text" name="bill_lable" value="{!! $line_arr->bill_lable !!}" placeholder="Lable" class="form-control">
                                                    </div>

                                                @elseif( $line_arr->key[$i] == 'user_name' )

                                                    <div class="col-md-3 dyn-lable">
                                                        <input type="text" name="user_lable" value="{!! $line_arr->user_lable !!}" placeholder="Lable" class="form-control">
                                                    </div>

                                                @elseif( $line_arr->key[$i] == 'customer' )

                                                    <div class="col-md-3 dyn-lable">
                                                        <input type="text" name="customer" value="{!! $line_arr->customer !!}" placeholder="Lable" class="form-control">
                                                    </div>

                                                @elseif( $line_arr->key[$i] == 'invoice_no' )

                                                    <div class="col-md-3 dyn-lable">
                                                        <input type="text" name="inv_lable" value="{!! $line_arr->inv_lable !!}" placeholder="Lable" class="form-control">
                                                    </div>

                                                @elseif( $line_arr->key[$i] == 'pax' )

                                                    <div class="col-md-3 dyn-lable">
                                                        <input type="text" name="pax_lable" value="{!! $line_arr->pax_lable !!}" placeholder="Lable" class="form-control">
                                                    </div>

                                                @elseif( $line_arr->key[$i] == 'date' )

                                                    <div class="col-md-3 dyn-lable">
                                                        <input type="text" name="date_lable" value="{!! $line_arr->date_lable !!}" placeholder="Lable" class="form-control">
                                                    </div>

                                                @elseif( $line_arr->key[$i] == 'footer_note' )

                                                    <div class="col-md-3 dyn-lable">
                                                        <input type="text" name="footer_note" value="{!! $line_arr->footer_note !!}" placeholder="Notes" class="form-control">
                                                    </div>

                                                @elseif( $line_arr->key[$i] == 'lable' )

                                                    <div class="col-md-3 dyn-lable">
                                                        <input type="text" name="lable[]" value="{!! $line_arr->lable[$lbl_cnt] !!}" placeholder="Lable" class="form-control">
                                                    </div>
                                                    <?php $lbl_cnt++; ?>
                                                @endif

                                                <div class="col-md-1">
                                                    <button class="btn btn-danger" onclick="removeLine(this)"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                    @endfor
                                @else
                                    <span id="empty_msg">No line has been added.</span>
                                @endif

                            </div>

                            <div class="error-content">
                                <span id="error_msg" style="color: red;"></span>
                            </div>

                            <div class="form-footer">
                                <div class="col-md-8">
                                    <button name='cancel' id='cancel' class="btn btn-success primary-btn" onclick="saveTemplate()" type="button" value="true">Save</button>
                                    <button name='add_line' id='add_line'  class="btn btn-success primary-btn" onclick="addLine(this)" novalidate="novalidate" type="button"><i class="fa fa-plus"></i>&nbsp;Line</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@section('page-scripts')
    {!! HTML::script('/assets/js/new/lib/jquery-ui-1.12.1.js') !!}
    <script type="text/javascript">

        $(document).ready(function () {
            $( ".column" ).sortable({
                connectWith: ".column"
            });

        });

        //add line in template
        function addLine(ele) {

            var check_valid = checkValidation();
            if(check_valid == false){
                return;
            }

            $('#empty_msg').hide();

            var html = $('<div class="form-group sequence">' +
                            '<div class="row">'+

                                '<div class="col-md-1" >' +
                                '<label style="margin-top: 10px;"><i class="zmdi zmdi-swap-vertical zmdi-hc-lg"></i></label>' +
                                '</div>' +
                                '<div class="col-md-1">'+
                                    '{!! Form::text('sequence[]', null, array('class' => 'form-control line', 'placeholder'=> 'No.','required')) !!}'+
                                '</div>'+
                                '<div class="col-md-1">'+
                                    '{!! Form::select('font[]',array('5'=>'5','6'=>'6'), null, array('class' => 'form-control font')) !!}'+
                                '</div>'+
                                '<div class="col-md-2">'+
                                    '{!! Form::select('align[]',array('left'=>'Left','center'=>'center','right'=>'Right'), null, array('class' => 'form-control align')) !!}'+
                                '</div>'+
                                '<div class="col-md-2">'+
                                    '{!! Form::select('key[]',$keys, null, array('class' => 'form-control key','onchange'=>'checkKey(this)')) !!}'+
                                '</div>'+
                                '<div class="col-md-1 form-group">'+
                                    '<button class="btn btn-danger" onclick="removeLine(this)"><i class="fa fa-times"></i></button>'+
                                '</div>'+

                            '</div>'+
                        '</div>').appendTo('.bill-content');

            $('.line:last').focus();

        }

        //save template
        function saveTemplate() {

            var check_valid = checkValidation();
            if(check_valid == false){
                return;
            }

            var fields = $('#billtemplate').serialize();

            $.ajax({
                url: '/update-bill-template',
                type: "post",
                data: fields,
                success: function (data) {

                    if ( data == 'success') {
                        successErrorMessage('Bill template updated successfully.','success');
                    } else {
                        successErrorMessage('No line has been added in template.','error');
                    }
                }
            });


        }

        //remove line
        function removeLine(ele) {

            $(ele).closest('.sequence').remove();

            if( $('.sequence').length == 0 ) {
                $('#empty_msg').show();
            }
        }

        function checkKey(key) {

            //remove dyn-lable key value on change key
            $(key).closest('.sequence').find('.dyn-lable').remove();

            var row = $(".sequence").size();

            if(row >= 2) {
                var error = 0; var count = 1;
                $(".key").each(function () {
                    if(row != count) {
                        if ($('.key:last').val() == $(this).val() && !['star_line','dash_line','new_line','lable'].includes($('.key:last').val())) {
                            changeError("Key cannot be repeated");
                            error = 1;
                        }
                    }
                    count++;
                });
                if (error == 1) {
                    return false;
                }else{
                    changeError("");
                }
            }

            var key_value = key.value;
            if ( key_value != '') {

                if ( key_value == 'bill_lable') {

                    $('<div class="col-md-3 dyn-lable">' +
                            '<input type="text" name="bill_lable" value="Retail Invoice" id="value_field" placeholder="Lable" class="form-control">' +
                            '</div>').insertAfter($(key).parent());

                } else if ( key_value == 'invoice_no' ) {

                    $('<div class="col-md-3 dyn-lable">' +
                            '<input type="text" name="inv_lable" value="Invoice#:" id="value_field" placeholder="Lable" class="form-control">' +
                            '</div>').insertAfter($(key).parent());

                } else if ( key_value == 'pax' ) {

                    $('<div class="col-md-3 dyn-lable">' +
                            '<input type="text" name="pax_lable" value="Pax#:" placeholder="Lable" id="value_field" class="form-control">' +
                            '</div>').insertAfter($(key).parent());

                } else if ( key_value == 'user_name' ) {

                    $('<div class="col-md-3 dyn-lable">' +
                            '<input type="text" name="user_lable" value="User:" placeholder="Lable" id="value_field" class="form-control">' +
                            '</div>').insertAfter($(key).parent());

                } else if ( key_value == 'date' ) {

                    $('<div class="col-md-3 dyn-lable">' +
                            '<input type="text" name="date_lable" value="Dt." placeholder="Lable" id="value_field" class="form-control">' +
                            '</div>').insertAfter($(key).parent());

                } else if ( key_value == 'footer_note' ) {

                    $('<div class="col-md-3 dyn-lable">' +
                            '<input type="text" name="footer_note" value="" placeholder="Notes" id="value_field" class="form-control">' +
                            '</div>').insertAfter($(key).parent());
                } else if ( key_value == 'lable' ) {

                    $('<div class="col-md-3 dyn-lable">' +
                            '<input type="text" name="lable[]" value="" placeholder="Lable" id="value_field" class="form-control">' +
                            '</div>').insertAfter($(key).parent());
                } else if ( key_value == 'customer' ) {

                    $('<div class="col-md-3 dyn-lable">' +
                            '<input type="text" name="customer" value="Customer:" placeholder="Customer Name" id="value_field" class="form-control">' +
                            '</div>').insertAfter($(key).parent());
                }

            }
        }

        function checkValidation() {
            var row = $(".sequence").size();

            if(row >= 2) {
                var error = 0; var count = 1;
                $(".key").each(function () {
                    if(row != count ) {
                        if ($('.key:last').val() == $(this).val() && !['star_line','dash_line','new_line','lable'].includes($('.key:last').val())) {
                            changeError("Key cannot be repeated");
                            error = 1;
                        }
                    }
                    count++;
                });
                if (error == 1) {
                    return false;
                }else{
                    changeError("");
                }

                var count = 1;
                $(".line").each(function () {
                    if(row != count) {
                        if ($('.line:last').val() == $(this).val()) {
                            count++;
                        }
                    }

                });
                if (count > 3) {
                    changeError("Line cannot be repeated more then 2 times.");
                    return false;
                }else{
                    changeError("");
                }


            }



            if(row > 0) {
                if ($('.line:last').val() == '') {

                    $('.line:last').css('border-bottom', 'red 1px solid');
                    $('.line:last').focus();
                    return false;
                } else {
                    $('.line:last').css('border-bottom', '#cccccc 1px solid');
                }

                if ($('.key:last').val() == '') {

                    $('.key:last').css('border-bottom', 'red 1px solid');
                    $('.key:last').focus();
                    return false;
                } else {
                    $('.key:last').css('border-bottom', '#cccccc 1px solid');
                }
                if ($('#value_field:last').val() == '') {

                    $('#value_field:last').css('border-bottom', 'red 1px solid');
                    $('#value_field:last').focus();
                    return false;
                } else {

                    if($('.font:last').val() == '5') {

                        if( $('.key:last').val() == 'invoice_no' ) {

                            if ($('#value_field:last').val().length > 24) {

                                $('#value_field:last').css('border-bottom', 'red 1px solid');
                                $('#value_field:last').focus();
                                changeError("Max length must not be less then 9")
                                return false;
                            }
                        }else if ( $('.key:last').val() == 'bill_lable') {

                            if ($('#value_field:last').val().length > 24) {

                                $('#value_field:last').css('border-bottom', 'red 1px solid');
                                $('#value_field:last').focus();
                                changeError("Max length must not be less then 24")
                                return false;
                            }
                        }else if ( $('.key:last').val() == 'user_name') {
                            if ($('#value_field:last').val().length + 16 > 24) {
                                $('#value_field:last').css('border-bottom', 'red 1px solid');
                                $('#value_field:last').focus();
                                changeError("Max length must not be less then 8")
                                return false;
                            }
                        }else if ( $('.key:last').val() == 'date') {

                            if ($('#value_field:last').val().length + 21 > 24) {

                                $('#value_field:last').css('border-bottom', 'red 1px solid');
                                $('#value_field:last').focus();
                                changeError("Max length must not be less then 3")
                                return false;
                            }
                        }else if ( $('.key:last').val() == 'pax') {

                            if ($('#value_field:last').val().length + 4 > 24) {

                                $('#value_field:last').css('border-bottom', 'red 1px solid');
                                $('#value_field:last').focus();
                                changeError("Max length must not be less then 20")
                                return false;
                            }
                        }else if ( $('.key:last').val() == 'lable') {

                            if ($('#value_field:last').val().length > 24) {

                                $('#value_field:last').css('border-bottom', 'red 1px solid');
                                $('#value_field:last').focus();
                                changeError("Max length must not be less then 24")
                                return false;
                            }
                        }




                    }

                    if($('.font:last').val() == '6'){

                        if(  $('.key:last').val() == 'invoice_no' ) {

                            if ($('#value_field:last').val().length + 15 > 48) {

                                $('#value_field:last').css('border-bottom', 'red 1px solid');
                                $('#value_field:last').focus();
                                changeError("Max length must not be less then 33")
                                return false;
                            }
                        }else if ( $('.key:last').val() == 'bill_lable') {

                            if ($('#value_field:last').val().length > 48) {

                                $('#value_field:last').css('border-bottom', 'red 1px solid');
                                $('#value_field:last').focus();
                                changeError("Max length must not be less then 48")
                                return false;
                            }
                        }else if ( $('.key:last').val() == 'user_name') {
                            if ($('#value_field:last').val().length + 16 > 48) {
                                $('#value_field:last').css('border-bottom', 'red 1px solid');
                                $('#value_field:last').focus();
                                changeError("Max length must not be less then 32")
                                return false;
                            }
                        }else if ( $('.key:last').val() == 'date') {

                            if ($('#value_field:last').val().length + 21 > 48) {

                                $('#value_field:last').css('border-bottom', 'red 1px solid');
                                $('#value_field:last').focus();
                                changeError("Max length must not be less then 27")
                                return false;
                            }
                        }else if ( $('.key:last').val() == 'pax') {

                            if ($('#value_field:last').val().length + 4 > 48) {

                                $('#value_field:last').css('border-bottom', 'red 1px solid');
                                $('#value_field:last').focus();
                                changeError("Max length must not be less then 44")
                                return false;
                            }
                        }else if ( $('.key:last').val() == 'lable') {

                            if ($('#value_field:last').val().length > 48) {

                                $('#value_field:last').css('border-bottom', 'red 1px solid');
                                $('#value_field:last').focus();
                                changeError("Max length must not be less then 48")
                                return false;
                            }
                        }

                    }

                    changeError("")
                    $('#value_field:last').css('border-bottom', '#cccccc 1px solid');
                }


            }
        }

        function changeError(msg) {
            $("#error_msg").text(msg);
        }

    </script>
@endsection

