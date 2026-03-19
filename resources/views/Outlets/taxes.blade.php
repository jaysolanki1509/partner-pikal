@extends('partials.default')
@section('pageHeader-left')
    Taxes
@stop

@section('pageHeader-right')
    <a href="/outlet" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap ft-left" style="width: 100%;">
                <div class="widget-container">
                    <div class="widget-content">
                        <div id="taxes" class="col-md-8 material-form j-forms">
                            <div id="tax_content">
                                @if ( isset($taxx) && $taxx != '' )
                                    <?php
                                    $tax_size = sizeof($taxx);
                                    $tax_cnt = 1;
                                    ?>
                                    @foreach( $taxx as $tx=>$tx_child )
                                        <div class="row parent-tax">
                                            <div class="col-md-12">
                                                <lable class="control-label">Tax label</lable>
                                                <button style="margin-left:10px;margin-bottom:5px" class="btn btn-danger rm-tax @if($tax_size == 1) hide @endif" onclick="removeTaxLable(this)"><i class="fa fa-times"></i></button>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control tax-label" placeholder="Tax Label" value="{{ $tx }}">
                                            </div>
                                            <?php
                                            $child_size = sizeof($tx_child);
                                            $child_cnt = 1;
                                            ?>
                                            @foreach ( $tx_child as $child )
                                                <div class="col-md-12 child-tax">
                                                    <div class="col-md-5 form-group">
                                                        <div class="col-md-12">
                                                            <lable class="control-label">Tax name</lable>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control tax-name" placeholder="Tax Label" value="{{ $child['taxname'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 form-group">
                                                        <div class="col-md-12">
                                                            <lable class="control-label">Tax percentage</lable>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control tax-perc" placeholder="Tax Label" value="{{ $child['taxparc'] }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 form-group">
                                                        <button style="margin-top:27px" class="btn btn-primary @if($child_cnt != $child_size) hide @endif" onclick="addTax(this,'child')"><i class="fa fa-plus"></i></button>
                                                        <button style="margin-top:27px" class="btn btn-danger @if($child_cnt == $child_size) hide @endif" onclick="removeTax(this)"><i class="fa fa-times"></i></button>

                                                    </div>
                                                </div>
                                                <?php $child_cnt++;?>
                                            @endforeach

                                        </div>
                                        <?php $tax_cnt++;?>
                                    @endforeach
                                @else
                                    <h4>No tax slab has been added, Please click on add slab.</h4>
                                @endif
                            </div>

                            <hr>
                            <span class="err-msg pull-left" style="color: red"></span>
                            <div class="form-footer pull-right">
                                <button class="btn btn-success" onclick="addTax(this,'parent')"  id="add_tax_btn" type="button" ><i class="fa fa-plus"></i> Tax Slab</button>
                                <button class="btn btn-success" onclick="saveTax()"  id="save_tax_btn" type="button" > Submit </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('page-scripts')

    <script src="/assets/js/new/lib/bootbox.js"></script>

    <script type="text/javascript">

        function removeTaxLable(ele) {

            $(ele).closest('.parent-tax').remove();

            if ( $('#taxes .parent-tax').length == 0 ) {
                $('#tax_content').html('<h4>No tax slab has been added, Please click on add slab.</h4>');
            }

        }


        function saveTax() {

            var taxes = {};

            var error = 0;

            //reset error
            $('.err-msg').text('');
            $('.tax-name,.tax-perc,.tax-label').closest('.form-group').css('border-bottom','none');

            $('.parent-tax').each(function(index){
                //tax label value
                var tax_label = $(this).find('.tax-label').val();

                if ( tax_label != '' ) {

                    taxes[tax_label] = [];

                    $(this).find('.child-tax').each(function(){

                        var tax_name = $(this).find('.tax-name').val().trim();
                        var tax_parc = $(this).find('.tax-perc').val().trim();

                        if ( tax_name != '' && tax_parc != '' ) {
                            taxes[tax_label].push({taxname:tax_name,taxparc:tax_parc});
                        } else {
                            if ( tax_name == '' ) {
                                $(this).find('.tax-name').closest('.form-group').css('border-bottom','red 1px solid');
                            }
                            if ( tax_parc == '' ) {
                                $(this).find('.tax-perc').closest('.form-group').css('border-bottom','red 1px solid');
                            }

                            error = 1;
                        }

                    });

                } else {
                    $(this).find('.tax-label').closest('.form-group').css('border-bottom','red 1px solid');
                    error = 1;
                }

            });
            //display error message
            if( error == 1 ) {
                $('.err-msg').text('Please fill highlighted fields.');
                return;
            }

            var tax_str = JSON.stringify(taxes);

            var outlet_id = $('#main_filter').val();

            processBtn('save_tax_btn','add','Submit...');

            $.ajax({
                type:'post',
                url:'/update-taxes',
                data:{taxes:tax_str},
                dataType:'json',
                success:function(data){

                    processBtn('save_tax_btn','remove','Submit');
                    if ( data.status == 'success') {
                        //successErrorMessage(data.msg,'success');

                        bootbox.confirm("Taxes has been updated, Please update default taxes.", function (result) {

                            if ( result === true ) {
                                window.location.href = '/outlet/'+outlet_id+'#taxes'
                            } else {
                                successErrorMessage('Your default order taxes has not been udpate.','error');
                            }
                        });


                    } else {
                        successErrorMessage(data.msg,'error');
                    }

                }
            });
        }

        function addTax(ele,flag) {

            $('.err-msg').text('');
            var error = 0;
            if ( flag == 'child') {

                //checking validation
                if ( $(ele).closest('.child-tax').find('.tax-name').val().trim() == '' ) {

                    $(ele).closest('.child-tax').find('.tax-name').closest('.form-group').css('border-bottom','red 1px solid');
                    $(ele).closest('.child-tax').find('.tax-name').focus();
                    error = 1;

                } else {
                    $(ele).closest('.child-tax').find('.tax-name').closest('.form-group').css('border-bottom','none');
                }

                if ( $(ele).closest('.child-tax').find('.tax-perc').val().trim() == '' ) {

                    $(ele).closest('.child-tax').find('.tax-perc').closest('.form-group').css('border-bottom','red 1px solid');
                    $(ele).closest('.child-tax').find('.tax-perc').focus();
                    error = 1;

                } else {
                    $(ele).closest('.child-tax').find('.tax-perc').closest('.form-group').css('border-bottom','none');
                }

                if ( error == 1 ) {
                    $('.err-msg').text('Please fill highlighted fields.');
                    return;
                }


                var child = $(ele).closest('.child-tax').clone();
                $(ele).closest('.parent-tax').append(child);

                //hide add button and show remove button from clone div
                $(ele).addClass('hide');
                $(ele).parent().find('.btn-danger').removeClass('hide');


                //changes in cloned div
                $(ele).closest('.parent-tax').find('.child-tax:last').find('.tax-name').val('').focus();
                $(ele).closest('.parent-tax').find('.child-tax:last').find('.tax-perc').val('');

            } else {

                if ( $('.parent-tax').length > 0 ) {

                    //checking validation
                    if ( $('.parent-tax:last').find('.tax-label').val().trim() == '' ) {

                        $('.parent-tax:last').find('.tax-label').closest('div').css('border-bottom','red 1px solid');
                        $('.parent-tax:last').find('.tax-label').focus();
                        error = 1;

                    } else {
                        $('.parent-tax:last').find('.tax-label').closest('div').css('border-bottom','none');
                    }

                    if ( $('.parent-tax:last').find('.child-tax:last').find('.tax-name').val().trim() == '' ) {
                        $('.parent-tax:last').find('.child-tax:last').find('.tax-name').closest('.form-group').css('border-bottom','red 1px solid');
                        $('.parent-tax:last').find('.child-tax:last').find('.tax-name').focus();
                        error = 1;

                    } else {
                        $('.parent-tax:last').find('.child-tax:last').find('.tax-name').closest('.form-group').css('border-bottom','none');
                    }

                    if ( $('.parent-tax:last').find('.child-tax:last').find('.tax-perc').val().trim() == '' ) {

                        $('.parent-tax:last').find('.child-tax:last').find('.tax-perc').closest('.form-group').css('border-bottom','red 1px solid');
                        $('.parent-tax:last').find('.child-tax:last').find('.tax-perc').focus();
                        error = 1;

                    } else {
                        $('.parent-tax:last').find('.child-tax:last').find('.tax-perc').closest('.form-group').css('border-bottom','none');
                    }

                }

                if ( error == 1 ) {
                    $('.err-msg').text('Please fill highlighted fields.');
                    return;
                }

                var html = '<div class="row parent-tax">'+
                                '<div class="col-md-12">'+
                                    '<lable class="control-label">Tax label</lable>'+
                                    '<button style="margin-left:10px;margin-bottom:5px" class="btn btn-danger rm-tax" onclick="removeTaxLable(this)"><i class="fa fa-times"></i></button>'+
                                '</div>'+
                                '<div class="col-md-12">'+
                                    '<input type="text" class="form-control tax-label" placeholder="Tax Label" value="">'+
                                '</div>'+
                                '<div class="col-md-12 child-tax">'+
                                    '<div class="col-md-5 form-group">'+
                                        '<div class="col-md-12">'+
                                            '<lable class="control-label">Tax name</lable>'+
                                        '</div>'+
                                        '<div class="col-md-12">'+
                                            '<input type="text" class="form-control tax-name" placeholder="Tax Label" value="">'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="col-md-5 form-group">'+
                                        '<div class="col-md-12">'+
                                            '<lable class="control-label">Tax percentage</lable>'+
                                        '</div>'+
                                        '<div class="col-md-12">'+
                                            '<input type="text" class="form-control tax-perc" placeholder="Tax Label" value="">'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="col-md-2 form-group">'+
                                        '<button style="margin-top:27px" class="btn btn-primary" onclick=addTax(this,"child")><i class="fa fa-plus"></i></button>'+
                                        '<button style="margin-top:27px" class="btn btn-danger hide" onclick="removeTax(this)"><i class="fa fa-times"></i></button>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';


                if ( $('.parent-tax').length == 0 ) {
                    $('#tax_content').html(html);
                } else {
                    $(html).insertAfter('.parent-tax:last');
                }

                $(html).find('.tax-label').focus();

            }

        }

        function removeTax(ele) {

            $(ele).closest('.child-tax').remove();
        }

    </script>

@stop