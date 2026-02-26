
@extends('partials.default')

@section('pageHeader-left')
    Manage Outlet
@stop

@section('pageHeader-right')
    <a href="/admin-outlet/outlet/create" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Outlet</a>
    <a href="/outletBind" class="btn btn-primary">Bind User</a>

@stop


@section('content')

    <div class="col-md-12">
        <div class="widget-wrap">
            <div class="widget-container">
                <div class="widget-content">

                    <form class='form-horizontal material-form j-forms' id="accountSettingsForm">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::select('outlet_id', isset($outlets)?$outlets:array(),null,array('class' => 'select2 form-control','id'=>'outlet_id','onchange'=>'showSettings()')) !!}
                                </div>
                                <div class="col-md-4">
                                    <a href="#" id="edit_outlet" title="Edit Outlet" class="btn btn-primary hide"><i class="fa fa-edit"></i>&nbsp;Outlet</a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="widget-container" id="data_div">
                                        <div class="widget-content">
                                            <div class="setting-body"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>


@stop

@section('page-scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVgjlIb0ZR6_wmxgr5FCpbVImDaXntxpo&amp;sensor=false&amp;libraries=places"></script>

    <script type="text/javascript">

        $(document).ready(function() {
            $('#outlet_id').select2({
                placeholder: 'Select an Outlet'
            });
        });

        //open print popup
        function showSettings() {

            var outlet_id = $('#outlet_id').val();

            if (outlet_id == '') {
                $('.panel-body').html('<div class="col-md-12" id="loading_div" style="text-align: center;font-weight: bold">Please select outlet</div>');
                return;
            }

            $("#edit_outlet").attr("href", "/outlet/"+outlet_id+"/edit");

            $.ajax({
                url:'/admin-outlet',
                type: "POST",
                data: {outlet_id : outlet_id},
                success: function (data) {

                    $('#edit_outlet').removeClass("hide");

                    $('.setting-body').html(data);

                    $(function() {
                        $( "#accordion" ).accordion();
                        $('.tax-type').select2();
                    });

                    var latlng = new google.maps.LatLng(23.0300, 72.5800);
                    var mapOptions = {
                        zoom: 16,
                        componentRestrictions:{country: 'in'} ,// set the map zoom level
                        center: latlng, // set the center region
                        disableDefaultUI: true,
                        zoomControl: true, // whether to show the zoom control beside
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    var input = document.getElementById('address');
                    var map = new google.maps.Map(document.getElementById('map'), mapOptions);
                    autocomplete = new google.maps.places.Autocomplete(input, mapOptions);
                    var marker = new google.maps.Marker({
                        map: map, // refer to the map you've just initialise
                        position: latlng, // set the marker position (is based on latitude & longitude)
                        draggable: false // allow user to drag the marker
                    });


                    var geocoder = new google.maps.Geocoder();

                    function geocoding(keyword) {
                        geocoder.geocode({address: keyword}, function(results, status) {
                            if(status == google.maps.GeocoderStatus.OK) {
                                map.setCenter(results[0].geometry.location);
                                marker.setPosition(results[0].geometry.location);

                                // ADD THIS
                                $.ajax({
                                    method: "POST",
                                    url: "/outlet/addlocation/"+outlet_id,
                                    headers: {
                                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: { outlet_id:$("#outlet_id").val(),latitude: results[0].geometry.location.lat(), longitude: results[0].geometry.location.lng() }
                                })
                                        .done(function( msg ) {

                                        });

                            } else {
                                alert('Location not found.');
                            }
                        });
                    }


                    $('.ddprinter').select2({
                        placeholder: 'Select Printer'
                    });
                    var $lightbox = $('#lightbox');
                    getSittings();
                    checkSlots();

                    $('.material-tabs a').click(function (e) {
                        var abc = $(this).attr('href');
                        $(abc).tab('show');
                        var scrollmem = $('body').scrollTop();
                        $('html,body').scrollTop(scrollmem);
                    });

                    $('[data-target="#lightbox"]').on('click', function(event) {
                        var $img = $(this).find('img'),
                                src = $img.attr('src'),
                                alt = $img.attr('alt'),
                                css = {
                                    'maxWidth': $(window).width() - 100,
                                    'maxHeight': $(window).height() - 100
                                };

                        $lightbox.find('.close').addClass('hidden');
                        $lightbox.find('img').attr('src', src);
                        $lightbox.find('img').attr('alt', alt);
                        $lightbox.find('img').css(css);
                    });

                    $lightbox.on('shown.bs.modal', function (e) {
                        var $img = $lightbox.find('img');

                        $lightbox.find('.modal-dialog').css({'width': $img.width()});
                        $lightbox.find('.close').removeClass('hidden');
                    });

                    $(function(){
                        var hash = window.location.hash;
                        hash && $('ul.nav a[href="' + hash + '"]').tab('show');

                        $('.material-tabs a').click(function (e) {
                            $(this).tab('show');
                            var scrollmem = $('body').scrollTop();
                            window.location.hash = this.hash;
                            $('html,body').scrollTop(scrollmem);
                        });
                    });
                    $(window).load(function() {
                        var options =
                        {
                            thumbBox: '.thumbBox',
                            spinner: '.spinner',
                            imgSrc: 'avatar.png'
                        };
                        var cropper = $('.imageBox').cropbox(options);
                        $('#file').on('change', function(){
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                options.imgSrc = e.target.result;
                                cropper = $('.imageBox').cropbox(options);
                            };
                            reader.readAsDataURL(this.files[0]);
                            this.files = [];
                        });
                        $('#btnCrop').on('click', function(){
                            var img = cropper.getDataURL();
                            $('.cropped').append('<img src="'+img+'">');
                            $('#raw_data').val(img);
                            $('#file').append(img);
                        });
                        $('#btnZoomIn').on('click', function(){
                            cropper.zoomIn();
                        });
                        $('#btnZoomOut').on('click', function(){
                            cropper.zoomOut();
                        });
                    });

                    jQuery("#findaddress").on('click',function(){

                        geocoding(document.getElementById('address').value);


                    });

                    //timeslot script
                    var i=1;
                    var someText='';
                    var z = document.getElementById("countf").value; //get pre added time slots
                    $("#btn_myid").click(
                            function () {
                                var x=z++;  //when add new features increase id
                                someText = '' +
                                        '<div class="">' +
                                        '<div class="" id="control'+x+'">'+
                                        '<div class="col-md-12 form" style="padding-left:0px;">'+
                                        '<div class="col-md-2 timepicker-label">'+
                                        '<label for="closing_time" class="control-label">Session</label>'+
                                        '</div>'+
                                        '<div class="col-md-2 input-append">'+
                                        '<input name="slot_name'+x+'" id="slot_name'+x+'" class="form-control" type="text" value="@if(isset($outlet['slot_name'])){{$outlet->slot_name}}@endif" placeholder="Name">'+
                                        '</div>'+
                                        '<div class="col-md-1 timepicker-label">'+
                                        '<label for="closing_time" class="control-label">{{ trans('Restaurant_Form.From') }}</label>'+
                                        '</div>'+
                                        '<div class="col-md-2 bootstrap-timepicker">'+
                                        '<input name="opening_time'+x+'" id="opening_time'+x+'" class="timepicker form-control" type="text" value="@if(isset($outlet['opening_time'])){{$outlet['opening_time']}}@endif" placeholder="From">'+
                                        '<span class="add-on"><i class="fa fa-clock-o"></i></span>' +
                                        '</div>'+
                                        '<div class="col-md-1 timepicker-label">'+
                                        '<label for="closing_time" class="control-label">{{ trans('Restaurant_Form.To') }}</label>'+
                                        '</div>'+
                                        '<div class="col-md-2 bootstrap-timepicker">'+
                                        '<input name="closing_time'+x+'" id="closing_time'+x+'" class="timepicker form-control" type="text"  value="@if(isset($outlet['closing_time'])){{$outlet['closing_time']}}@endif" placeholder="To">'+
                                        '<span class="add-on"><i class="fa fa-clock-o"></i></span> ' +
                                        '</div>'+
                                        '<input type="button" class="btn btn-danger" onclick="control'+x+'.remove()" value="{{ trans('Restaurant_Form.Delete') }}"><br/>'+
                                        '</div>'+
                                        '<input type=hidden name="count" value='+x+'>' +
                                        '</div>' +
                                        '</div>';
                                var newDiv = $("<div class='col-md-12'>").append(someText);
                                $("#appendtimeslots").append(newDiv);

                                $('#opening_time'+x).timepicker({
                                    showMeridian:false
                                });
                                $('#closing_time'+x).timepicker({
                                    showMeridian:false
                                });
                            }
                    );

                },
                error:function(error) {
                    alert('There is some error ocurred.');
                }

            });

        }

        function updateSettings() {

            var data = $("#updateSetting").serialize();

            $.ajax({
                url:'/update-printers',
                type:'POST',
                data:data,
                success:function(data){

                    if ( data.status == 'success' ) {
                        successErrorMessage('Settings has been udpate.','success');
                    } else if ( data.status == 'error' ) {
                        successErrorMessage('There is some error ocurred, Please try again later.','error');
                    } else {
                        successErrorMessage('Please fill all the field.','error');
                    }
                }
            });

        }

        //upate valid settings
        function checkValidSettings(el) {

            var disc_after_tax = $("#appsettings").find("[data-name='discountAfterTax']").prop('checked');
            var itemwise_tax = $("#appsettings").find("[data-name='itemWiseTax']").prop('checked');
            var itemwise_disc = $("#appsettings").find("[data-name='itemWiseDiscount']").prop('checked');

            if ( el.checked ) {

                if ( $(el).data('name') == 'itemWiseDiscount' ) {

                    if ( disc_after_tax == true && itemwise_tax == false ) {
                        successErrorMessage("If itemwise discount is enable than discount after tax setting will not work.","error");
                        $(el).attr('checked', false);
                    }

                } else if ( $(el).data('name') == 'discountAfterTax' ) {
                    if ( itemwise_disc == true && itemwise_tax == false ) {
                        successErrorMessage("If itemwise discount is enable than discount after tax setting will not work.","error");
                        $(el).attr('checked', false);
                    }
                }

            } else {

                if ( $(el).data('name') == 'itemWiseTax' ) {
                    if ( disc_after_tax == true && itemwise_disc == true ) {
                        successErrorMessage("If itemwise discount is enable than discount after tax setting will not work.","error");
                        $(el).attr('checked', true);
                    }
                }

            }

        }

        //remove charge slab
        function removeTaxDetail(el) {

            $(el).parent().parent().remove();

            if ( $('#tax_details_field').children().length == 0 ) {
                $('#tax_details_field').html('<span id="tx_detail_msg">No tax details added.</span>');
            }

        }

        function addTaxDetailSlab() {

            if ( $('#tax_details_field .tx-field-div').length > 0 ) {

                var tx_field = $('#tax_details_field .tx-field-div:last').find('.tx-field').val().trim();
                var tx_value = $('#tax_details_field .tx-field-div:last').find('.tx-value').val().trim();

                var error = false;
                if ( tx_field == '' ) {
                    $('#tax_details_field .tx-field-div:last').find('.tx-field').css('border-color','red').focus();
                    error = true;
                } else {
                    $('#tax_details_field .tx-field-div:last').find('.tx-field').css('border-color','');
                }

                if ( tx_value == '' ) {
                    $('#tax_details_field .tx-field-div:last').find('.tx-value').css('border-color','red').focus();
                    error = true;
                } else {
                    $('#tax_details_field .tx-field-div:last').find('.tx-value').css('border-color','');
                }

                if ( error == true ) {
                    return;
                }
            }

            var content =   '<div class="tx-field-div">'+
                    '<div class="col-md-4 form-group">'+
                    '<input type="text" placeorder="Field lable" class="form-control tx-field" value="">'+
                    '</div>'+
                    '<div class="col-md-1 form-group">'+
                    '<span> = </span>'+
                    '</div>'+
                    '<div class="col-md-4 form-group">'+
                    '<input type="text" placeorder="Field value" class="form-control tx-value" value="">'+
                    '</div>'+
                    '<div class="col-md-3 form-group">'+
                    '<button class="btn btn-danger rm-tax" onclick="removeTaxDetail(this)"><i class="fa fa-times"></i></button>'+
                    '</div>'+
                    '</div>';

            $('#tax_details_field #tx_detail_msg').remove();
            $('#tax_details_field').append(content);

        }

        function saveTaxDetail() {

            var tx_detail = {}; var cnt = 0;
            var outlet_id = $("#outlet_id").val();
            $('#tax_details_field .tx-field-div').each(function () {
                var tx_field = $(this).find(".tx-field").val().trim();
                var tx_value = $(this).find(".tx-value").val().trim();

                if ( tx_field != '' || tx_value != '' ) {
                    tx_detail[tx_field] = [];
                    tx_detail[tx_field].push({tx_value:tx_value});
                    cnt++;
                }
            });

            processBtn('save_tx_btn','add','Submit...');

            $.ajax({
                type:'post',
                url:'/store-tax-details',
                data:{tx_detail:tx_detail,outlet_id:outlet_id},
                dataType:'json',
                success:function(data){

                    processBtn('save_tx_btn','remove','Submit');
                    if ( data.status == 'success') {

                        successErrorMessage('Tax details has been udpate.','success');

                    } else {
                        successErrorMessage(data.msg,'error');
                    }

                }
            });

        }

        function addFixChargeSlab() {
            $("#fixed_rate").each(function() {
                if ($(this).prop('checked') == true) { //do something } });
                    var content =   '<div class="field-div">'+
                            '<div class="col-md-4 form-group">'+
                            '<input type="text" placeorder="Order Amount" class="form-control order_amt" disabled value="Fixed">'+
                            '</div>'+
                            '<div class="col-md-1 form-group">'+
                            '<span> = </span>'+
                            '</div>'+
                            '<div class="col-md-4 form-group">'+
                            '<input type="text" placeorder="Delivery Charge" class="form-control delivery_charge" value="">'+
                            '</div>'+
                            '<div class="col-md-3 form-group">'+
                            '</div>'+
                            '</div>';

                    $('.field-div').remove();
                    $('#charge_field #empty_msg').remove();
                    $('#charge_field').append(content);
                    $('#add_charge_btn').prop('disabled', true);
                }else{
                    $('.field-div').remove();
                    $('#add_charge_btn').prop('disabled', false);
                    $('#charge_field').html('<span id="empty_msg">No Charge slab added.</span>');
                    //alert($(this).prop('checked'));
                }
            });
        }

        function addChargeSlab() {

            if ( $('#charge_field .field-div').length > 0 ) {

                var ord_amt = $('#charge_field .field-div:last').find('.order_amt').val().trim();
                var charge = $('#charge_field .field-div:last').find('.delivery_charge').val().trim();

                var error = false;
                if ( ord_amt == '' ) {
                    $('#charge_field .field-div:last').find('.order_amt').css('border-color','red').focus();
                    error = true;
                } else {
                    $('#charge_field .field-div:last').find('.order_amt').css('border-color','');
                }

                if ( charge == '' ) {
                    $('#charge_field .field-div:last').find('.delivery_charge').css('border-color','red').focus();
                    error = true;
                } else {
                    $('#charge_field .field-div:last').find('.delivery_charge').css('border-color','');
                }

                if ( error == true ) {
                    return;
                }
            }

            var content =   '<div class="col-md-12 field-div">'+
                    '<div class="col-md-4 form-group">'+
                    '<input type="text" placeorder="Order Amount" class="form-control order_amt" value="">'+
                    '</div>'+
                    '<div class="col-md-1 form-group">'+
                    '<span> = </span>'+
                    '</div>'+
                    '<div class="col-md-4 form-group">'+
                    '<input type="text" placeorder="Delivery Charge" class="form-control delivery_charge" value="">'+
                    '</div>'+
                    '<div class="col-md-3 form-group">'+
                    '<button class="btn btn-danger rm-tax" onclick="removeCharge(this)"><i class="fa fa-times"></i></button>'+
                    '</div>'+
                    '</div>';

            $('#charge_field #empty_msg').remove();
            $('#charge_field').append(content);

        }

        //remove charge slab
        function removeCharge(el) {

            $(el).parent().parent().remove();

            if ( $('#charge_field').children().length == 0 ) {
                $('#charge_field').html('<span id="empty_msg">No Charge slab added.</span>');
            }

        }

        //store Delivery charge slab
        function savecharge() {

            var charges = {}; var cnt = 0;
            var outlet_id = $("#outlet_id").val();
            $('#charge_field .field-div').each(function () {
                var ord_val = $(this).find(".order_amt").val().trim();
                var del_charge = $(this).find(".delivery_charge").val().trim();

                if ( ord_val != '' || del_charge != '' ) {
                    charges[ord_val] = [];
                    charges[ord_val].push({del_charge:del_charge});
                    cnt++;
                }
            });
            var override = $("#override_charge").prop('checked');

            processBtn('charge_btn','add','Submit...');

            $.ajax({
                type:'post',
                url:'/store-delivery-charge',
                data:{charges:charges, override_charge:override, outlet_id:outlet_id},
                dataType:'json',
                success:function(data){

                    processBtn('charge_btn','remove','Submit');
                    if ( data.status == 'success') {

                        successErrorMessage('Delivery charge has been udpate.','success');

                    } else {
                        successErrorMessage(data.msg,'error');
                    }

                }
            });


        }

        function saveTax() {

            var dinein = $('#dine_in').val();
            var take_away = $('#take_away').val();
            var home_delivery = $('#home_delivery').val();
            var outlet_id = $('#outlet_id').val();

            if ( dinein != 'select' || take_away != 'select' || home_delivery != 'select') {

                processBtn('save_tax_btn','add','Submit...');

                $.ajax({
                    type:'post',
                    url:'/update-order_type-taxes',
                    data:{dine_in:dinein,take_away:take_away,home_delivery:home_delivery, outlet_id:outlet_id},
                    success:function(data){

                        processBtn('save_tax_btn','remove','Submit');

                        if ( data == 'success') {
                            successErrorMessage('Taxes has been updated successfully','success');
                        } else {
                            successErrorMessage('There is some error occurred, Please try again later','error');
                        }

                    }
                });

            } else {
                processBtn('save_tax_btn','add','Submit...');

                $.ajax({
                    type:'post',
                    url:'/update-order_type-taxes',
                    data:{dine_in:dinein,take_away:take_away,home_delivery:home_delivery, outlet_id:outlet_id},
                    success:function(data){

                        processBtn('save_tax_btn','remove','Submit');

                        if ( data == 'success') {
                            successErrorMessage('Taxes has been updated successfully','success');
                        } else {
                            successErrorMessage('There is some error occurred, Please try again later','error');
                        }

                    }
                });
            }

        }

        function Addimage(){
            document.getElementById('addimages').style.display="block";
        }
        function exportexcel(getrestaurantid){
            $.ajax({
                method: "POST",
                url: "/outlet/exportexcel",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: { outlet_id:getrestaurantid}
            })
                    .done(function( msg ) {

                        window.open('http://parag.foodklub.com','_blank' );
                    });
        }

        //Update payment mode
        function updatePaymentId() {
            var check = 1;
            $('#paymentOptionidform input').each(function() {
                if ($(this).val() == '') {
                    swal({
                        title: "Error",
                        text: "All Input fields must be filled!",
                        type: "warning",
                        confirmButtonColor: "#43C6DB",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true
                    });
                    check = 0;
                }
            });
            if(check) {
                $("input").removeAttr("disabled");
                var data = $('#paymentOptionidform').serialize();

                $('#payment_id_btn').attr('disabled', true);
                $('#payment_id_btn').text('Updating...');

                $.ajax({
                    type: 'post',
                    url: '/update-payment-id',
                    data: data,
                    success: function (data) {
                        $('#payment_mode_btn').attr('disabled', false);
                        $('#payment_mode_btn').text('Submit');
                        swal({
                            title: "Success",
                            text: "Payment ids have been updated successfully!",
                            type: "success",
                            confirmButtonColor: "#43C6DB",
                            confirmButtonText: "Ok!",
                            closeOnConfirm: true
                        });
                    },
                    error: function () {
                        swal({
                            title: "Error",
                            text: "Payment options doesnot saved!",
                            type: "warning",
                            confirmButtonColor: "#43C6DB",
                            confirmButtonText: "Ok!",
                            closeOnConfirm: true
                        });
                    }
                });
                $('#payment_id_btn').text('Update');
                $('#payment_id_btn').attr('disabled', false);
            }
        }

        //Update payment mode
        function updatePaymentMode() {
            $("input").removeAttr("disabled");
            var data = $('#paymentOptionform').serialize();

            $('#payment_mode_btn').attr('disabled',true);
            $('#payment_mode_btn').text('Updating...');

            $.ajax({
                type:'post',
                url:'/update-payment-mode',
                data:data,
                success:function(data){
                    $('#payment_mode_btn').attr('disabled',false);
                    $('#payment_mode_btn').text('Submit');
                    swal({
                        title: "Success",
                        text: "Payment options has been updated successfully!",
                        type: "success",
                        confirmButtonColor: "#43C6DB",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true
                    });
                },
                error:function () {
                    swal({
                        title: "Error",
                        text: "Payment options has doesnot saved!",
                        type: "warning",
                        confirmButtonColor: "#43C6DB",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true
                    });
                }
            });
            $('#payment_mode_btn').text('Update');
            $('#payment_mode_btn').attr('disabled',false);

        }

        function updateTimeSlots() {

            var data = $("#timeSlotsCreate").serialize();

            $.ajax({
                url:'/storetimeslot',
                type:'POST',
                data:data,
                success:function(data){

                    if ( data.status == 'success' ) {
                        successErrorMessage('TimeSlots has been udpated.','success');
                    } else if ( data.status == 'error' ) {
                        successErrorMessage('There is some error ocurred, Please try again later.','error');
                    } else {
                        successErrorMessage('Please fill all the field.','error');
                    }
                }
            });

        }

        function updateAppView() {

            var data = $("#applayout").serialize();

            $.ajax({
                url:'/storelayout',
                type:'POST',
                data:data,
                success:function(data){

                    if ( data.status == 'success' ) {
                        successErrorMessage(data.msg,'success');
                    } else if ( data.status == 'error' ) {
                        successErrorMessage(data.msg,'error');
                    } else {
                        successErrorMessage('Please fill all the field.','error');
                    }
                }
            });

        }

        function getSittings() {

            //$('#SettingsTable td input').val('');
            var outlet_id = $('#outlet_id').val();

            $.ajax({
                url:'/getSettings',
                Type:'POST',
                dataType:'json',
                data:'outlet_id='+outlet_id,
                success:function(data){
                    for(var i=0;i<data.length;i++){
                        //alert(data[i].id);
                        $('#'+data[i].id).val(data[i].setting_value);
                    }
                }
            });

        }

        function updateMasterSettings() {

            var bad = 0;
            $('.form :text').each(function ()
            {
                if ($.trim(this.value) == "") bad++;
            });
            if (bad > 0) {
                alert('All fields must be filled.');
                return;
            }

            var outlet_id = $('#outlet_id').val();

            $('#add-btn').text('Processing...');
            $('#add-btn').prop('disabled',true);
            var data = $('#updateMasterSetting').serialize();
            //console.log(data);return;
            $.ajax({
                url:'/update-settings',
                Type:'POST',
                data:data,
                success:function(data){
                    $('#add-btn').text('Add');
                    $('#add-btn').prop('disabled',false);

                    if ( data.status == 'success' ) {
                        successErrorMessage('Settings has been udpate.','success');
                    } else if ( data.status == 'error' ) {
                        successErrorMessage('There is some error ocurred, Please try again later.','error');
                    } else {
                        successErrorMessage('Please fill all the field.','error');
                    }
                }
            });
        }

        function checkSlots() {
            var o_id = $('#outlet_id').val();
            var someText='';
            $.ajax({
                url: '/checktimeslots',
                Type: 'POST',
                dataType: 'json',
                data: 'outlet_id=' + o_id ,
                success: function (data) {
                    $("#appendtimeslots").html('');
                    $('#countf').val(data.length);
                    for(var i=0;i<data.length;i++){
                        var x=z++;  //when add new features increase id
                        someText = '<div class="">' +
                                '<div class="" id="control'+x+'">'+
                                '<div class="col-md-12 form" style="padding-left:0px;">' +
                                '<div class="col-md-2 timepicker-label">'+
                                '<label for="closing_time" class="control-label">Session</label>'+
                                '</div>'+
                                '<div class="col-md-2">'+
                                '<input name="slot_name'+x+'" id="slot_name'+x+'" class="form-control add-on" type="text" value="'+data[i].slot_name+'" placeholder="Name">'+
                                '</div>'+
                                '<div class="col-md-1">'+
                                '<label for="closing_time" class="control-label">{{ trans('Restaurant_Form.From') }}</label>'+
                                '</div>'+
                                '<div class="col-md-2 bootstrap-timepicker">' +
                                '<input name="opening_time'+x+'" id="opening_time'+x+'" class="timepicker form-control" type="text" value="'+data[i].from_time+'" placeholder="From">'+
                                '<span class="add-on"><i class="fa fa-clock-o"></i></span>' +
                                '</div>'+
                                '<div class="col-md-1 timepicker-label">'+
                                '<label for="closing_time" class="control-label">{{ trans('Restaurant_Form.To') }}</label>'+
                                '</div>'+
                                '<div class="col-md-2 bootstrap-timepicker">'+
                                '<input name="closing_time'+x+'" id="closing_time'+x+'" class="timepicker form-control" type="text"  value="'+data[i].to_time+'" placeholder="To">'+
                                '<span class="add-on"><i class="fa fa-clock-o"></i></span> ' +
                                '</div>'+
                                '<a href="/timeslots/'+data[i].id+'/destroy"><input type="button" class="btn btn-primary mr5" onclick="control'+x+'.remove()" value="{{ trans('Restaurant_Form.Delete') }}"><br/>'+
                                '</div>'+

                                '<input type=hidden name="count" value='+x+'>' +
                                '</div>' +
                                '</div>';
                        var newDiv = $("<div class='col-md-12'>").append(someText);
                        $("#appendtimeslots").append(newDiv);

                        $('#opening_time'+x).timepicker({
                            showMeridian:false
                        });
                        $('#closing_time'+x).timepicker({
                            showMeridian:false
                        });

                        $(".timepicker").timepicker({
                            showMeridian:false
                        });
                    }
                }
            });
        }

        function updateAppLayout() {
            var data = $("#outlet_status_form").serialize();

            $.ajax({
                url:'/store-outlet-status',
                type:'POST',
                data:data,
                success:function(data){

                    if ( data.status == 'success' ) {
                        successErrorMessage(data.msg,'success');
                    } else if ( data.status == 'error' ) {
                        successErrorMessage(data.msg,'error');
                    } else {
                        successErrorMessage('Please fill all the field.','error');
                    }
                }
            });
        }

    </script>
@stop