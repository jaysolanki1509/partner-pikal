
function calculateDiscount( el, flag, disc_mode ) {

    if ( flag == 'process' ) {

        var order_id = $('#processBill #bill_order_id').val();
        var order_type = $('input[name="order_type"]:checked').val();
        var disc_type = $('#processBill #disc_type').val();
        var disc_val = $('#processBill #disc_value').val();
        var sub_total = $('#processBill #final_sub_total').val();
        var selected_tax = $('#processBill #outlet_tax').val();
        var delivery_charge = $('#processBill #new_delivery_value').val();

        if ( disc_mode == 'apply') {

            if ( disc_type == '') {
                successErrorMessage('Select Discount type','error');
                $('#processBill #disc_type').focus();
                return;
            }
            if ( disc_val == '' ) {
                successErrorMessage('Enter Discount Value','error');
                $('#processBill #disc_value').focus();
                return;
            }


            if ( disc_val != '') {

                if ( disc_type == 'fixed') {
                    if ( parseFloat(disc_val) > parseFloat(sub_total) ) {
                        successErrorMessage('Discount value can not be greater than sub total which include itemwise discount and itemwise tax if applicable','error');
                        $('#processBill #disc_value').focus();
                        return;
                    }
                } else if ( disc_type == 'percentage' ) {
                    if ( parseFloat(disc_val) > 100 ) {
                        successErrorMessage('Discount percentage can not be greater than 100','error');
                        $('#processBill #disc_value').focus();
                        return;
                    }
                }
            }

        }

    } else {

        var order_id = $('#editBill #edit_order_id').val();
        var disc_type = $('#editBill #edit_disc_type').val();
        var order_type = $('input[name="edit_order_type"]:checked').val();
        var disc_val = $('#editBill #edit_disc_value').val();
        var sub_total = $('#editBill #final_sub_total').val();
        var selected_tax = $('#editBill #outlet_tax').val();
        var delivery_charge = $('#editBill #edit_new_delivery_value').val();

        if ( disc_mode == 'apply') {

            if ( disc_type == '') {
                successErrorMessage('Select Discount type','error');
                $('#editBill #disc_type').focus();
                return;
            }
            if ( disc_val == '' ) {
                successErrorMessage('Enter Discount Value','error');
                $('#editBill #disc_value').focus();
                return;
            }


            if ( disc_val != '') {

                if ( disc_type == 'fixed') {
                    if ( parseFloat(disc_val) > parseFloat(sub_total) ) {
                        successErrorMessage('Discount value can not be greater than sub total which include itemwise discount and itemwise tax if applicable','error');
                        $('#editBill #disc_value').focus();
                        return;
                    }
                } else if ( disc_type == 'percentage' ) {
                    if ( parseFloat(disc_val) > 100 ) {
                        successErrorMessage('Discount percentage can not be greater than 100','error');
                        $('#editBill #disc_value').focus();
                        return;
                    }
                }
            }

        } else {
            $('#editBill #edit_disc_value').val("");
            $('#editBill #edit_disc_type').val("");
        }
    }

    $(el).prop('disabled',true);

    $.ajax({
        url: '/calculate-order-discount',
        type: "post",
        data: { flag: flag,disc_mode: disc_mode, order_id:order_id, order_type:order_type,disc_type:disc_type, disc_val:disc_val, selected_tax:selected_tax, delivery_charge:delivery_charge },
        success: function (data) {

            $(el).prop('disabled',false);

            if ( flag == 'process' ) {

                $('#processBill #tax_calculation_div').remove();
                $('#processBill #calculation').html(data);
                $('#processBill #outlet_tax').select2();

            } else {

                $('#editBill #tax_calculation_div').remove();
                $('#editBill #edit_calculation').html(data);
                $('#editBill #outlet_tax').select2();

                var tot = $("#edit_total").text();
                if ( $('#editBill .paid-value').length == 1 ) {
                    $('#editBill .paid-value:first').val(tot);
                }

            }

            if(flag == "edit" && disc_mode == 'remove'){
                $('#editBill #edit_disc_value').val("");
                $('#editBill #edit_disc_type').val("");
                $('#editBill #edit_disc_div').remove();
                $('#editBill #edit_disc_type').trigger('change.select2');
            }
            //reinitilize onchange function
            $('#editBill #outlet_tax').change(function(){

                var tax_name = $('#editBill #outlet_tax').val();
                var ord_type = $('input[name="edit_order_type"]:checked').val();
                var new_delivery = "";
                var edit_disc_type = $("#edit_disc_type").val();
                var edit_disc_value = $("#edit_disc_value").val();
                getInvoiceNo(ord_type,order_id,'edit',tax_name,new_delivery,edit_disc_type,edit_disc_value);

            });

            if(flag == "process" && disc_mode == 'remove'){

                $('#processBill #disc_value').val("");
                $('#processBill #disc_type').val("");
                $('#processBill #disc_type').trigger('change.select2');
                var tax_name = $('#processBill #outlet_tax').val();
                var ord_type = $('input[name="order_type"]:checked').val();
                var new_delivery = $("#new_delivery_value").val();
                var disc_type = $("#disc_type").val();
                var disc_value = $("#disc_value").val();
                getInvoiceNo(ord_type,order_id,'process',tax_name,new_delivery,disc_type,disc_value);

            }
            //reinitilize onchange function
            $('#processBill #outlet_tax').change(function(){

                var tax_name = $(this).val();
                var ord_type = $('input[name="order_type"]:checked').val();
                var new_delivery = "";
                var edit_disc_type = $("#disc_type").val();
                var edit_disc_value = $("#disc_value").val();
                getInvoiceNo(ord_type,order_id,'process',tax_name,new_delivery,edit_disc_type,edit_disc_value);

            });

        }
    });


}

function processBill( order_id,flag ) {

    if ( flag == 'open') {

        $(":radio[value=dine_in]").prop('checked', true);
        $('#mobile').val('');
        $('#name').val('');
        $('#disc_value').val('');
        $('#invoice_no').val('');
        $('#disc_type').val('');
        $('#payment_type').val('cod');
        $('#new_delivery_value').val('');

        $.ajax({
            url: '/processbill',
            type: "get",
            data: {order_id: order_id},
            success: function (data) {

                $('#processBill #calculation').html(data.html);
                $('#processBill #invoice_no').val(data.inv_no);
                $('#processBill #bill_order_id').val(data.order_id);
                $('#processBill').modal('show');

                $('#processBill #disc_type').change(function () {
                    $('#disc_value').val('');
                });
                $('#processBill #mobile').val(data.order_info.user_mobile_number);

                if(data.order_info.order_type == "home_delivery"){
                    $("#home_delivery").prop('checked', true);
                    $('#address_div').removeClass('hide');
                    $('#address_div #address').val(data.order_info.address);
                    $('#add_delivery_value').removeClass("hide");
                }else{
                    $('#address_div').addClass('hide');
                    $('#add_delivery_value').addClass("hide");
                }
                if(data.order_info.order_type == "take_away"){
                    $("#take_away").prop('checked', true);
                    $('#mobile').val(data.order_info.user_mobile_number);
                }

                if(data.order_info.order_type == "dine_in"){
                    $('#mobile').val(data.order_info.user_mobile_number);
                }

                $('#processBill #paid_type').select2();
                $('#processBill #outlet_tax').select2();
                if($('#mobile').val() == "")
                    $('#processBill #mobile').val(data.consumer.mobile_number);
                if($('#name').val() == "")
                    $('#processBill #name').val(data.consumer.first_name);
                $('#processBill #outlet_tax').change(function(){

                    var tax_name = $(this).val();
                    var ord_type = $('input[name="order_type"]:checked').val();
                    var new_delivery = "";
                    getInvoiceNo(ord_type,order_id,'process',tax_name,new_delivery);

                });

                $('.date_time').datetimepicker({
                    format: "YYYY-MM-DD HH:mm:ss"
                });

                $('.date').DatePicker({
                    format: "yyyy-mm-dd",
                    orientation: "auto",
                    autoclose: true,
                    todayHighlight: true
                });
            }
        });

    } else {

        var ord_id = $('#bill_order_id').val();
        var inv_no = $('#processBill #invoice_no').val();
        var discount = $('#processBill #discount').text();
        var item_discount = $('#processBill #item_discount').text();
        var discount_type = $('#processBill #disc_type').val();
        var mobile = $('#processBill #mobile').val();
        var name = $('#processBill #name').val();
        var sub_total = $('#processBill #sub_total').text();
        var total = $('#processBill #total').text();
        var round_off = $('#processBill #round_off').text();
        var type = $('input[name="order_type"]:checked').val();
        var address = $('#processBill #address').val();
        var source = $('#processBill #paid_type').find(':selected').data('source');
        var paid_type = $('#processBill #paid_type').val();
        var delivery_charge = $('#processBill #delivery_charge').text();
        var discount_text = "";
        var tax_id = $("#outlet_tax").val();

        if(discount_type == "percentage"){
            discount_text = $("#disc_value").val()+"%";
        }else if(discount_type == "fixed"){
            discount_text = "fixed";
        }

        //check payment mode amount
        var payment_mode_total = 0;
        $('#processBill .paid-value').each(function () {
            payment_mode_total += parseFloat($(this).val());
        });

        if ( payment_mode_total < parseFloat(total) ) {

            $('#processBill .paid-value').css('border-color','red');
            successErrorMessage('Payment mode amount total can not be less than bill amount.','error');
            return;

        } else if ( payment_mode_total > parseFloat(total) ) {

            $('#processBill .paid-value').css('border-color','red');
            successErrorMessage('Payment mode amount total can not be greater than bill amount','error');
            return;
        }

        //get payment modes value
        var py_source_ids = []; var py_option_ids = []; var py_amount =[];var trn_ids = [];

        var check_repeat_mode = 0;
        var modes_count = $('#processBill .paid-value').length;
        for( var i=0; i< modes_count; i++ ) {

            //check repeat payment mode
            if ( py_option_ids.length > 0 ) {

                for ( j=0; j< py_option_ids.length; j++ ) {

                    var check_py_option_id = 0;var check_source_id = 0;

                    if ( $('#processBill .paid-type').eq(i).val() == py_option_ids[j] ) {
                        check_py_option_id = 1;
                    }

                    if ( $('#processBill .paid-type').eq(i).find(':selected').data('source') == py_source_ids[j] ) {
                        check_source_id = 1;
                    }

                    if ( check_py_option_id == 1 && check_source_id == 1 ) {
                        check_repeat_mode = 1;
                    }
                }

            }

            py_option_ids.push($('#processBill .paid-type').eq(i).val());
            py_source_ids.push($('#processBill .paid-type').eq(i).find(':selected').data('source'));
            py_amount.push($('#processBill .paid-value').eq(i).val());
            trn_ids.push($('#processBill .transaction-id').eq(i).val());

        }

        if ( check_repeat_mode == 1 ) {
            successErrorMessage('Same Payment mode cant be add multiple time','error');
            return;
        }

        var tax = {};

        if ( inv_no == '' ) {
            successErrorMessage('Please enter invoice number','error');
            $('#processBill #invoice_no').focus();
            return;
        }

        var custom_field = $('#custom_form').serializeArray();

        if ( $('#processBill #tax_calc .tax_name').length > 0 ) {

            for(var i=0; i<$('#processBill #tax_calc .tax_name').length; i++ ) {
//
                var tax_name = $('#processBill #tax_name_'+i).text().trim();
                var name_wdt = tax_name.length;

                var tax_par = $('#processBill #tax_perc_'+i).text().trim();
                var par_wdt = tax_par.length;

                if( tax_par == '0' ) {
                    par_wdt = par_wdt - 1;
                }

                var len = name_wdt - par_wdt;
                tax_name = tax_name.substring(0,len - 1).trim();

                var tax_val = $('#processBill #tax_val_'+i).text();
                tax[tax_name] = {calc_tax:tax_val,percent:tax_par};

            }

        } else {
            tax = '';
        }

        $('#processBill #process_btn').attr('disabled',true);
        $.ajax({
            url: '/processbillfinal',
            type: "post",
            dataType:'json',
            data: {
                order_id: ord_id,
                invoice_no:inv_no,
                mobile:mobile,
                name:name,
                s_total:sub_total,
                total:total,
                round_off:round_off,
                ord_type:type,
                tax:tax,
                discount_type:discount_text,
                discount:discount,
                item_discount:item_discount,
                address:address,
                source_id:source,
                paid_type:paid_type,
                delivery_charge:delivery_charge,
                custom_fields:custom_field,
                payment_option_ids:py_option_ids,
                source_ids:py_source_ids,
                payment_mode_amount:py_amount,
                trn_ids:trn_ids,
                tax_id:tax_id

            },
            success: function (data) {
                $('#processBill #process_btn').attr('disabled',false);
                if ( data.status == 'success' ) {
                    $('#processBill').modal('hide');
                    openOrderView(ord_id,'refresh');
                    $('.modal').css('overflow-y','scroll');
                    //$('.modal').css('position','absolute');
                } else {
                    successErrorMessage('There is some error ocurred','error');
                }

            }
        });

    }


}

$('input[name="order_type"]').click(function() {

    if( $(this).attr("value") == "home_delivery" ){
        $('#address_div').removeClass('hide');
        $('#add_delivery_value').removeClass('hide');
    } else {
        $('#address_div').addClass('hide');
        $('#add_delivery_value').addClass('hide');
    }
    //var tax_name = $(this).closest('.modal-body').find('#outlet_tax').val();
    var tax_name = "";
    var ord_id = $('#bill_order_id').val();
    var new_delivery = "";
    getInvoiceNo($(this).attr("value"),ord_id,'process',tax_name,new_delivery);

});

//checkPaymentOption
function checkPaymentOption(e) {

    var option = $(e).find("option:selected").text();

    if ( option == 'Online - UPI') {
        $('#upi_block').removeClass('hide');
    } else {
        $('#upi_block').addClass('hide');
    }
}

function delivery_apply(flag,req_type) {
    if(req_type == "process") {

        var disc_type = $('#processBill #disc_type').val();
        var disc_val = $('#processBill #disc_value').val();
        if (flag == "apply") {
            var order_type = $('input[name="order_type"]:checked').val();
            var new_delivery = $("#new_delivery_value").val();
            var ord_id = $('#bill_order_id').val();
            var tax_name = $("#outlet_tax").val();

            getInvoiceNo(order_type, ord_id, req_type, tax_name, new_delivery, disc_type, disc_val);
        } else if (flag == "remove") {
            var order_type = $('input[name="order_type"]:checked').val();
            var ord_id = $('#bill_order_id').val();
            var tax_name = $("#outlet_tax").val();
            $("#new_delivery_value").val("");

            getInvoiceNo(order_type, ord_id, req_type, tax_name, "0.00", disc_type, disc_val);
        }
    }else{
        var disc_type = $('#editBill #edit_disc_type').val();
        var disc_val = $('#editBill #edit_disc_value').val();
        if (flag == "apply") {
            var order_type = $('input[name="edit_order_type"]:checked').val();
            var new_delivery = $("#edit_new_delivery_value").val();
            var ord_id = $('#edit_order_id').val();
            var tax_name = $("#editBill #outlet_tax").val();

            getInvoiceNo(order_type, ord_id, req_type, tax_name, new_delivery, disc_type, disc_val);
        } else if (flag == "remove") {
            var order_type = $('input[name="edit_order_type"]:checked').val();
            var ord_id = $('#edit_order_id').val();
            var tax_name = $("#editBill #outlet_tax").val();
            $("#edit_new_delivery_value").val("");

            getInvoiceNo(order_type, ord_id, req_type, tax_name, "0.00", disc_type, disc_val);
        }
    }
}

//get invoice when sleect order type
function getInvoiceNo(val,ord_id,req_type,tax_name,new_delivery,discount_type,discount_val) {

    $('#tip_text').remove();
    $('#process_btn').attr('disabled',true);

    $.ajax({
        url: '/getinvoiceno',
        type: "get",
        data: {type: val,ord_id:ord_id,req_type:req_type,tax_name:tax_name,new_delivery:new_delivery,discount_type:discount_type, discount_val:discount_val},
        success: function (data) {
            $('#process_btn').attr('disabled',false);
            if ( req_type == 'edit') {

                $('#edit_invoice_no').val(data.invoice_no);

                $('#edit_calculation').html(data.tax_view);
                $('#editBill #outlet_tax').select2();

                $('#editBill #outlet_tax').change(function(){

                    var tax_name = $(this).val();
                    var ord_type = $('input[name="edit_order_type"]:checked').val();
                    if(ord_type == "home_delivery") {
                        var new_delivery = $("edit_new_delivery_value").val();
                        getInvoiceNo(ord_type, ord_id, 'edit', tax_name, new_delivery,discount_type,discount_val);
                    }else{
                        var new_delivery = "";
                        getInvoiceNo(ord_type, ord_id, 'edit', tax_name, new_delivery,discount_type,discount_val);
                    }

                });

            } else if (req_type == 'reset') {
                $("input:radio[name=edit_order_type][value ="+data.order_type+"]").prop('checked', true);
                $('#settle_value').val('');
                //$('#edit_invoice_no').val(data.invoice_no);
                $('#edit_calculation').html(data.tax_view);
                $('#editBill #outlet_tax').select2();

                $('#editBill #outlet_tax').change(function(){

                    var tax_name = $(this).val();
                    var ord_type = $('input[name="edit_order_type"]:checked').val();
                    var new_delivery = "";
                    getInvoiceNo(ord_type,ord_id,'edit',tax_name,new_delivery,discount_type,discount_val);

                });
            }else {

                //$('#processBill #disc_value').val('');
                //$('#processBill #disc_type').val('').change();

                $('#invoice_no').val(data.invoice_no);
                $('#processBill #tax_calculation_div').html(data.tax_view);

                $('#processBill #outlet_tax').select2();

                $('#processBill #outlet_tax').change(function(){

                    var tax_name = $(this).val();
                    var ord_type = $('input[name="order_type"]:checked').val();
                    if(ord_type == "home_delivery"){
                        var new_delivery = "";
                        getInvoiceNo(ord_type, ord_id, 'process', tax_name, new_delivery,discount_type,discount_val);
                    }else {
                        var new_delivery = "";
                        getInvoiceNo(ord_type, ord_id, 'process', tax_name, new_delivery,discount_type,discount_val);
                    }
                });
            }

            var tot = $("#edit_total").text();
            if ( $('#editBill .paid-value').length == 1 ) {
                $('#editBill .paid-value:first').val(tot);
            }

        },
        error:function(error) {
            successErrorMessage('There is some error ocurred','error');
        }
    });
}
//aply discount in process bill
function applyDisc( flag ) {

    if ( flag == 'remove' ) {
        $('#processBill #disc_type').val('').change();
        $('#processBill #disc_value').val('');
    }

    var sub_total = parseFloat($('#processBill #sub_total').text());
    var disc_type = $('#processBill #disc_type').val();
    var disc_val = $('#processBill #disc_value').val();
    var type = $('#processBill input[name="order_type"]:checked').val();



    if ( flag == 'apply' ) {

        if ( disc_type == '') {
            successErrorMessage('Select Discount type','error');
            $('#processBill #disc_type').focus();
            return;
        }
        if ( disc_val == '' ) {
            successErrorMessage('Enter Discount Value','error');
            $('#processBill #disc_value').focus();
            return;
        }

    }


    if ( disc_val != '') {

        if ( disc_type == 'fixed') {
            if ( parseFloat(disc_val) > parseFloat(sub_total) ) {
                successErrorMessage('Discount value can not be greater than sub total','error');
                $('#processBill #disc_value').focus();
                return;
            }
        } else if ( disc_type == 'percentage' ) {
            if ( parseFloat(disc_val) > 100 ) {
                successErrorMessage('Discount percentage can not be greater than 100','error');
                $('#processBill #disc_value').focus();
                return;
            }
        }
    }



    $('#processBill #disc_div').remove();
    if ( disc_type != '' && disc_val != '' && $("#discount_after_tax").val() == 0) {

        var parce_val = disc_val;
        if (disc_type == 'fixed') {
            sub_total = sub_total - disc_val;
        } else {
            parce_val = sub_total * disc_val / 100;
            sub_total = sub_total - parce_val;
        }

        $("<div id='disc_div' class='col-md-12' style='width: 100%; float: left;'><input type='hidden' id='itemwise_disc_block' value='0'><span class='col-md-6' style='float:left;'>Discount</span><span class='col-md-6' style='text-align: right; float:right;'>&#8377; <span id='discount'>" + parseFloat(parce_val).toFixed(2) + "</span></span></div>").insertAfter('#processBill #sub_total_div');
    }

    var total = sub_total;

    if( $('#processBill #tax_calc').length > 0) {
        $('#processBill #tax_calc').removeClass('hide');
        for(var i=0; i<$('#processBill #tax_calc .tax_name').length; i++ ) {

            if ( $("#discount_after_tax").val() == 0 ) {

                var tax_perc = parseFloat($('#processBill #tax_perc_'+i).text());
                tax_perc = tax_perc.toFixed(2);

                var new_tax = parseFloat(sub_total * tax_perc / 100);
                new_tax = new_tax.toFixed(2);

                $('#processBill #tax_val_'+i).text(new_tax);

            } else {
                var new_tax = $('#processBill #tax_val_'+i).text();
            }

            total += parseFloat(new_tax);

        }

    } else {
        $('#processBill #tax_calc').addClass('hide');
    }

    if ( disc_type != '' && disc_val != '' && $("#discount_after_tax").val() == 1) {

        var parce_val = disc_val;
        if (disc_type == 'fixed') {
            total = total - disc_val;
        } else {
            parce_val = total * disc_val / 100;
            total = total - parce_val;
        }

        var after_elem = '';
        if( $('#processBill #tax_calc').length > 0) {
            after_elem = 'tax_calc';
        }else{
            after_elem = 'sub_total_div';
        }
        $("<div id='disc_div' class='col-md-12' style='width: 100%; float: left;'><input type='hidden' id='itemwise_disc_block' value='0'><span class='col-md-6' style='float:left;'>Discount</span><span class='col-md-6' style='text-align: right; float:right;'>&#8377; <span id='discount'>" + parseFloat(parce_val).toFixed(2) + "</span></span></div>").insertAfter('#processBill #'+after_elem);
    }

    if( $('#processBill #delivery_charge').length > 0) {
        var delivery_charge = $('#processBill #delivery_charge').text();
        total = parseFloat(total) + parseFloat(delivery_charge);
    }

    total = total.toFixed(2);

    var round_total = Math.round(total);

    var round_off = Math.abs(total - round_total).toFixed(2);

    $('#processBill #round_off').text(round_off);
    $('#processBill #total').text(round_total.toFixed(2));

    if ( $('#processBill .paid-value').length == 1 ) {
        $('#processBill .paid-value:first').val(round_total.toFixed(2));
    }
    $('#processBill #disc_value').val();

}
//udupihome
function processBilludupihome( order_id,flag ) {

    if ( flag == 'open') {

        $(":radio[value=dine_in]").prop('checked', true);
        $('#mobile').val('');
        $('#name').val('');
        $('#disc_value').val('');
        $('#invoice_no').val('');
        $('#disc_type').val('');
        $('#payment_type').val('cod');
        $('#new_delivery_value').val('');

        $.ajax({
            url: '/processbill',
            type: "get",
            data: {order_id: order_id},
            success: function (data) {

                $('#processBilludupi #calculation').html(data.html);
                $('#processBilludupi #invoice_no').val(data.inv_no);
                $('#processBilludupi #bill_order_id').val(data.order_id);
                // $('#processBill').modal('show');

                $('#processBilludupi #disc_type').change(function () {
                    $('#disc_value').val('');
                });
                $('#processBilludupi #mobile').val(data.order_info.user_mobile_number);

                if(data.order_info.order_type == "home_delivery"){
                    $("#home_delivery").prop('checked', true);
                    $('#address_div').removeClass('hide');
                    $('#address_div #address').val(data.order_info.address);
                    $('#add_delivery_value').removeClass("hide");
                }else{
                    $('#address_div').addClass('hide');
                    $('#add_delivery_value').addClass("hide");
                }
                if(data.order_info.order_type == "take_away"){
                    $("#take_away").prop('checked', true);
                    $('#mobile').val(data.order_info.user_mobile_number);
                }

                if(data.order_info.order_type == "dine_in"){
                    $('#mobile').val(data.order_info.user_mobile_number);
                }

                $('#processBilludupi #paid_type').select2();
                $('#processBilludupi #outlet_tax').select2();
                if($('#mobile').val() == "")
                    $('#processBilludupi #mobile').val(data.consumer.mobile_number);
                if($('#name').val() == "")
                    $('#processBilludupi #name').val(data.consumer.first_name);
                $('#processBilludupi #outlet_tax').change(function(){

                    var tax_name = $(this).val();
                    var ord_type = $('input[name="order_type"]:checked').val();
                    var new_delivery = "";
                    getInvoiceNo(ord_type,order_id,'process',tax_name,new_delivery);

                });

                $('.date_time').datetimepicker({
                    format: "YYYY-MM-DD HH:mm:ss"
                });

                $('.date').DatePicker({
                    format: "yyyy-mm-dd",
                    orientation: "auto",
                    autoclose: true,
                    todayHighlight: true
                });
                setTimeout(function(){
                    processBilludupihome(0,'process')
                }, 1000);
            }
        });

    } else {

        var ord_id = $('#bill_order_id').val();
        var inv_no = $('#processBilludupi #invoice_no').val();
        var discount = $('#processBilludupi #discount').text();
        var item_discount = $('#processBilludupi #item_discount').text();
        var discount_type = $('#processBilludupi #disc_type').val();
        var mobile = $('#processBilludupi #mobile').val();
        var name = $('#processBilludupi #name').val();
        var sub_total = $('#processBilludupi #sub_total').text();
        var total = $('#processBilludupi #total').text();
        var round_off = $('#processBilludupi #round_off').text();
        var type = $('input[name="order_type"]:checked').val();
        var address = $('#processBilludupi #address').val();
        var source = $('#processBilludupi #paid_type').find(':selected').data('source');
        var paid_type = $('#processBilludupi #paid_type').val();
        var delivery_charge = $('#processBilludupi #delivery_charge').text();
        var discount_text = "";
        var tax_id = $("#outlet_tax").val();

        if(discount_type == "percentage"){
            discount_text = $("#disc_value").val()+"%";
        }else if(discount_type == "fixed"){
            discount_text = "fixed";
        }

        //check payment mode amount
        var payment_mode_total = 0;
        $('#processBilludupi .paid-value').each(function () {
            payment_mode_total += parseFloat($(this).val());
        });

        if ( payment_mode_total < parseFloat(total) ) {

            $('#processBilludupi .paid-value').css('border-color','red');
            successErrorMessage('Payment mode amount total can not be less than bill amount.','error');
            return;

        } else if ( payment_mode_total > parseFloat(total) ) {

            $('#processBilludupi .paid-value').css('border-color','red');
            successErrorMessage('Payment mode amount total can not be greater than bill amount','error');
            return;
        }

        //get payment modes value
        var py_source_ids = []; var py_option_ids = []; var py_amount =[];var trn_ids = [];

        var check_repeat_mode = 0;
        var modes_count = $('#processBilludupi .paid-value').length;
        for( var i=0; i< modes_count; i++ ) {

            //check repeat payment mode
            if ( py_option_ids.length > 0 ) {

                for ( j=0; j< py_option_ids.length; j++ ) {

                    var check_py_option_id = 0;var check_source_id = 0;

                    if ( $('#processBilludupi .paid-type').eq(i).val() == py_option_ids[j] ) {
                        check_py_option_id = 1;
                    }

                    if ( $('#processBilludupi .paid-type').eq(i).find(':selected').data('source') == py_source_ids[j] ) {
                        check_source_id = 1;
                    }

                    if ( check_py_option_id == 1 && check_source_id == 1 ) {
                        check_repeat_mode = 1;
                    }
                }

            }

            py_option_ids.push($('#processBilludupi .paid-type').eq(i).val());
            py_source_ids.push($('#processBilludupi .paid-type').eq(i).find(':selected').data('source'));
            py_amount.push($('#processBilludupi .paid-value').eq(i).val());
            trn_ids.push($('#processBilludupi .transaction-id').eq(i).val());

        }

        if ( check_repeat_mode == 1 ) {
            successErrorMessage('Same Payment mode cant be add multiple time','error');
            return;
        }

        var tax = {};

        if ( inv_no == '' ) {
            successErrorMessage('Please enter invoice number','error');
            $('#processBilludupi #invoice_no').focus();
            return;
        }

        var custom_field = $('#custom_form').serializeArray();

        if ( $('#processBilludupi #tax_calc .tax_name').length > 0 ) {

            for(var i=0; i<$('#processBilludupi #tax_calc .tax_name').length; i++ ) {

                var tax_name = $('#processBilludupi #tax_name_'+i).text().trim();
                var name_wdt = tax_name.length;

                var tax_par = $('#processBilludupi #tax_perc_'+i).text().trim();
                var par_wdt = tax_par.length;

                if( tax_par == '0' ) {
                    par_wdt = par_wdt - 1;
                }

                var len = name_wdt - par_wdt;
                tax_name = tax_name.substring(0,len - 1).trim();

                var tax_val = $('#processBilludupi #tax_val_'+i).text();
                tax[tax_name] = {calc_tax:tax_val,percent:tax_par};

            }

        } else {
            tax = '';
        }

        $('#processBilludupi #process_btn').attr('disabled',true);
        $.ajax({
            url: '/processbillfinal',
            type: "post",
            dataType:'json',
            data: {
                order_id: ord_id,
                invoice_no:inv_no,
                mobile:mobile,
                name:name,
                s_total:sub_total,
                total:total,
                round_off:round_off,
                ord_type:type,
                tax:tax,
                discount_type:discount_text,
                discount:discount,
                item_discount:item_discount,
                address:address,
                source_id:source,
                paid_type:paid_type,
                delivery_charge:delivery_charge,
                custom_fields:custom_field,
                payment_option_ids:py_option_ids,
                source_ids:py_source_ids,
                payment_mode_amount:py_amount,
                trn_ids:trn_ids,
                tax_id:tax_id

            },
            success: function (data) {
                $('#processBilludupi #process_btn').attr('disabled',false);
                if ( data.status == 'success' ) {
                    $('#processBilludupi').modal('hide');
                    openOrderViewudpuhome(ord_id,'refresh');
                    $('.modal').css('overflow-y','scroll');
                    //$('.modal').css('position','absolute');
                } else {
                    successErrorMessage('There is some error ocurred','error');
                }

            }
        });

    }
}
var values = '';
//udupihome
function openOrderViewudpuhome( order_id, flag ,value ) {
    values = value;
    if(values == 'true'){
        $('#printModal').modal('show');
        $('#printModal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');
    }else{
        // $('#printModal').modal('show');
        // $('#printModal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');
    }
    

    $.ajax({
        url: '/printorder',
        type: "GET",
        data: {order_id : order_id},
        success: function (data) {
            $('#printModal .modal-body').html(data);
            $('#printModal #close_type').val(flag);
            if(values == 'true'){

            }else{
                setTimeout(function(){
                    printkotorder();
                }, 1000);
            }
        },
        error:function(error) {
            successErrorMessage('There is some error ocurred','error');
        }

    });

}


//open print popup
function openOrderView( order_id, flag ) {

    $('#printModal').modal('show');
    $('#printModal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');

    $.ajax({
        url: '/printorder',
        type: "GET",
        data: {order_id : order_id},
        success: function (data) {
            $('#printModal .modal-body').html(data);
            $('#printModal #close_type').val(flag);
        },
        error:function(error) {
            successErrorMessage('There is some error ocurred','error');
        }

    });

}

//print order detail
function print() {

    var docprint=window.open("about:blank", "_blank");
    var oTable = $("#printModal .modal-body").html();

    docprint.document.open();
    docprint.document.write('<html><head><title>');
    docprint.document.write('Invoice');
    docprint.document.write('</title>');
    docprint.document.write('</head><body><center>');
    docprint.document.write(oTable);
    docprint.document.write('</center></body></html>');
    docprint.document.close();
    docprint.print();
    docprint.close();
}


//open kot print popup
function printKot( order_id,flag,item_id ) {
    if ( flag == 'open') {
        // $('#printKotModal').modal('show');
        // $('#printKotModal .modal-body').html('<div style="text-align:center;"><img src="/loader.gif" /></div>');
    
        $.ajax({
            url: '/printkot',
            type: "GET",
            data: {order_id : order_id,item_id : item_id},
            success: function (data) {
                $('#printKotModal .modal-body').html(data);
                $('#printKotModal #close_type').val(flag);
                setTimeout(function(){
                    printkotorder();
                }, 1000);
            },
            error:function(error) {
                successErrorMessage('There is some error ocurred','error');
            }
    
        });

    } 
    // 
}

//print kot detail
function printkotorder() {

    var docprint=window.open("about:blank", "_blank");
    var oTable = $("#printKotModal .modal-body").html();
    docprint.document.open();
    docprint.document.write('<html><head><title>');
    docprint.document.write('Kot');
    docprint.document.write('</title>');
    docprint.document.write('</head><body>');
    docprint.document.write(oTable);
    docprint.document.write('</body></html>');
    docprint.document.close();
    docprint.print();
    docprint.close();
    window.location.href = "/table_index";
}

var val = '' ;
//udupihome
function processDisBilludupihome( order_id,flag,value ) {

    val = value;
    if ( flag == 'open') {

        $(":radio[value=dine_in]").prop('checked', true);
        $('#mobile').val('');
        $('#name').val('');
        $('#disc_value').val('');
        $('#invoice_no').val('');
        $('#disc_type').val('');
        $('#payment_type').val('cod');
        $('#new_delivery_value').val('');

        $.ajax({
            url: '/processbill',
            type: "get",
            data: {order_id: order_id},
            success: function (data) {

                $('#processBilludupi #calculation').html(data.html);
                $('#processBilludupi #invoice_no').val(data.inv_no);
                $('#processBilludupi #bill_order_id').val(data.order_id);
                if(val == 'true'){
                    console.log(val);
                    // $('#processBilludupi').modal('show');
                }else{
                    console.log("here process mod else");
                    $('#processBilludupi').modal('show');
                }
                

                $('#processBilludupi #disc_type').change(function () {
                    $('#disc_value').val('');
                });
                $('#processBilludupi #mobile').val(data.order_info.user_mobile_number);

                if(data.order_info.order_type == "home_delivery"){
                    $("#home_delivery").prop('checked', true);
                    $('#address_div').removeClass('hide');
                    $('#address_div #address').val(data.order_info.address);
                    $('#add_delivery_value').removeClass("hide");
                }else{
                    $('#address_div').addClass('hide');
                    $('#add_delivery_value').addClass("hide");
                }
                if(data.order_info.order_type == "take_away"){
                    $("#take_away").prop('checked', true);
                    $('#mobile').val(data.order_info.user_mobile_number);
                }

                if(data.order_info.order_type == "dine_in"){
                    $('#mobile').val(data.order_info.user_mobile_number);
                }

                $('#processBilludupi #paid_type').select2();
                $('#processBilludupi #outlet_tax').select2();
                if($('#mobile').val() == "")
                    $('#processBilludupi #mobile').val(data.consumer.mobile_number);
                if($('#name').val() == "")
                    $('#processBilludupi #name').val(data.consumer.first_name);
                $('#processBilludupi #outlet_tax').change(function(){

                    var tax_name = $(this).val();
                    var ord_type = $('input[name="order_type"]:checked').val();
                    var new_delivery = "";
                    getInvoiceNo(ord_type,order_id,'process',tax_name,new_delivery);

                });

                // $('.date_time').datetimepicker({
                //     format: "YYYY-MM-DD HH:mm:ss"
                // });

                // $('.date').DatePicker({
                //     format: "yyyy-mm-dd",
                //     orientation: "auto",
                //     autoclose: true,
                //     todayHighlight: true
                // });
                // if(val == 'true'){
                //     console.log("here in time out");
                //      setTimeout(function(){
                //         processDisBilludupihome(0,'process')
                //     }, 1000);
                // }else{
                //     console.log("here in time out else");
                //     // setTimeout(function(){
                //     //     processDisBilludupihome(0,'process')
                //     // }, 1000);
                // }
               
            }
        });

    } else {

        var ord_id = $('#bill_order_id').val();
        var inv_no = $('#processBilludupi #invoice_no').val();
        var discount = $('#processBilludupi #discount').text();
        var item_discount = $('#processBilludupi #item_discount').text();
        var discount_type = $('#processBilludupi #disc_type').val();
        var mobile = $('#processBilludupi #mobile').val();
        var name = $('#processBilludupi #name').val();
        var sub_total = $('#processBilludupi #sub_total').text();
        var total = $('#processBilludupi #total').text();
        var round_off = $('#processBilludupi #round_off').text();
        var type = $('input[name="order_type"]:checked').val();
        var address = $('#processBilludupi #address').val();
        var source = $('#processBilludupi #paid_type').find(':selected').data('source');
        var paid_type = $('#processBilludupi #paid_type').val();
        var delivery_charge = $('#processBilludupi #delivery_charge').text();
        var discount_text = "";
        var tax_id = $("#outlet_tax").val();

        if(discount_type == "percentage"){
            discount_text = $("#disc_value").val()+"%";
        }else if(discount_type == "fixed"){
            discount_text = "fixed";
        }

        //check payment mode amount
        var payment_mode_total = 0;
        $('#processBilludupi .paid-value').each(function () {
            payment_mode_total += parseFloat($(this).val());
        });

        if ( payment_mode_total < parseFloat(total) ) {

            $('#processBilludupi .paid-value').css('border-color','red');
            successErrorMessage('Payment mode amount total can not be less than bill amount.','error');
            return;

        } else if ( payment_mode_total > parseFloat(total) ) {

            $('#processBilludupi .paid-value').css('border-color','red');
            successErrorMessage('Payment mode amount total can not be greater than bill amount','error');
            return;
        }

        //get payment modes value
        var py_source_ids = []; var py_option_ids = []; var py_amount =[];var trn_ids = [];

        var check_repeat_mode = 0;
        var modes_count = $('#processBilludupi .paid-value').length;
        for( var i=0; i< modes_count; i++ ) {

            //check repeat payment mode
            if ( py_option_ids.length > 0 ) {

                for ( j=0; j< py_option_ids.length; j++ ) {

                    var check_py_option_id = 0;var check_source_id = 0;

                    if ( $('#processBilludupi .paid-type').eq(i).val() == py_option_ids[j] ) {
                        check_py_option_id = 1;
                    }

                    if ( $('#processBilludupi .paid-type').eq(i).find(':selected').data('source') == py_source_ids[j] ) {
                        check_source_id = 1;
                    }

                    if ( check_py_option_id == 1 && check_source_id == 1 ) {
                        check_repeat_mode = 1;
                    }
                }

            }

            py_option_ids.push($('#processBilludupi .paid-type').eq(i).val());
            py_source_ids.push($('#processBilludupi .paid-type').eq(i).find(':selected').data('source'));
            py_amount.push($('#processBilludupi .paid-value').eq(i).val());
            trn_ids.push($('#processBilludupi .transaction-id').eq(i).val());

        }

        if ( check_repeat_mode == 1 ) {
            successErrorMessage('Same Payment mode cant be add multiple time','error');
            return;
        }

        var tax = {};

        if ( inv_no == '' ) {
            successErrorMessage('Please enter invoice number','error');
            $('#processBilludupi #invoice_no').focus();
            return;
        }

        var custom_field = $('#custom_form').serializeArray();

        if ( $('#processBilludupi #tax_calc .tax_name').length > 0 ) {

            for(var i=0; i<$('#processBilludupi #tax_calc .tax_name').length; i++ ) {
//
                var tax_name = $('#processBilludupi #tax_name_'+i).text().trim();
                var name_wdt = tax_name.length;

                var tax_par = $('#processBilludupi #tax_perc_'+i).text().trim();
                var par_wdt = tax_par.length;

                if( tax_par == '0' ) {
                    par_wdt = par_wdt - 1;
                }

                var len = name_wdt - par_wdt;
                tax_name = tax_name.substring(0,len - 1).trim();

                var tax_val = $('#processBilludupi #tax_val_'+i).text();
                tax[tax_name] = {calc_tax:tax_val,percent:tax_par};

            }

        } else {
            tax = '';
        }

        $('#processBilludupi #process_btn').attr('disabled',true);
        $.ajax({
            url: '/processbillfinal',
            type: "post",
            dataType:'json',
            data: {
                order_id: ord_id,
                invoice_no:inv_no,
                mobile:mobile,
                name:name,
                s_total:sub_total,
                total:total,
                round_off:round_off,
                ord_type:type,
                tax:tax,
                discount_type:discount_text,
                discount:discount,
                item_discount:item_discount,
                address:address,
                source_id:source,
                paid_type:paid_type,
                delivery_charge:delivery_charge,
                custom_fields:custom_field,
                payment_option_ids:py_option_ids,
                source_ids:py_source_ids,
                payment_mode_amount:py_amount,
                trn_ids:trn_ids,
                tax_id:tax_id

            },
            success: function (data) {
                $('#processBilludupi #process_btn').attr('disabled',false);
                if ( data.status == 'success' ) {
                    $('#processBilludupi').modal('hide');
                    openOrderViewudpuhome(ord_id,'refresh','true');
                    $('.modal').css('overflow-y','scroll');
                    //$('.modal').css('position','absolute');
                } else {
                    successErrorMessage('There is some error ocurred','error');
                }

            }
        });

    }
}

function calculateUdupiDiscount( el, flag, disc_mode ) {

    if ( flag == 'process' ) {

        var order_id = $('#processBilludupi #bill_order_id').val();
        var order_type = $('input[name="order_type"]:checked').val();
        var disc_type = $('#processBilludupi #disc_type').val();
        var disc_val = $('#processBilludupi #disc_value').val();
        var sub_total = $('#processBilludupi #final_sub_total').val();
        var selected_tax = $('#processBilludupi #outlet_tax').val();
        var delivery_charge = $('#processBilludupi #new_delivery_value').val();

        if ( disc_mode == 'apply') {

            if ( disc_type == '') {
                successErrorMessage('Select Discount type','error');
                $('#processBilludupi #disc_type').focus();
                return;
            }
            if ( disc_val == '' ) {
                successErrorMessage('Enter Discount Value','error');
                $('#processBilludupi #disc_value').focus();
                return;
            }


            if ( disc_val != '') {

                if ( disc_type == 'fixed') {
                    if ( parseFloat(disc_val) > parseFloat(sub_total) ) {
                        successErrorMessage('Discount value can not be greater than sub total which include itemwise discount and itemwise tax if applicable','error');
                        $('#processBilludupi #disc_value').focus();
                        return;
                    }
                } else if ( disc_type == 'percentage' ) {
                    if ( parseFloat(disc_val) > 100 ) {
                        successErrorMessage('Discount percentage can not be greater than 100','error');
                        $('#processBilludupi #disc_value').focus();
                        return;
                    }
                }
            }

        }

    } else {

        var order_id = $('#editBill #edit_order_id').val();
        var disc_type = $('#editBill #edit_disc_type').val();
        var order_type = $('input[name="edit_order_type"]:checked').val();
        var disc_val = $('#editBill #edit_disc_value').val();
        var sub_total = $('#editBill #final_sub_total').val();
        var selected_tax = $('#editBill #outlet_tax').val();
        var delivery_charge = $('#editBill #edit_new_delivery_value').val();

        if ( disc_mode == 'apply') {

            if ( disc_type == '') {
                successErrorMessage('Select Discount type','error');
                $('#editBill #disc_type').focus();
                return;
            }
            if ( disc_val == '' ) {
                successErrorMessage('Enter Discount Value','error');
                $('#editBill #disc_value').focus();
                return;
            }


            if ( disc_val != '') {

                if ( disc_type == 'fixed') {
                    if ( parseFloat(disc_val) > parseFloat(sub_total) ) {
                        successErrorMessage('Discount value can not be greater than sub total which include itemwise discount and itemwise tax if applicable','error');
                        $('#editBill #disc_value').focus();
                        return;
                    }
                } else if ( disc_type == 'percentage' ) {
                    if ( parseFloat(disc_val) > 100 ) {
                        successErrorMessage('Discount percentage can not be greater than 100','error');
                        $('#editBill #disc_value').focus();
                        return;
                    }
                }
            }

        } else {
            $('#editBill #edit_disc_value').val("");
            $('#editBill #edit_disc_type').val("");
        }
    }

    $(el).prop('disabled',true);

    $.ajax({
        url: '/calculate-order-discount',
        type: "post",
        data: { flag: flag,disc_mode: disc_mode, order_id:order_id, order_type:order_type,disc_type:disc_type, disc_val:disc_val, selected_tax:selected_tax, delivery_charge:delivery_charge },
        success: function (data) {

            $(el).prop('disabled',false);

            if ( flag == 'process' ) {

                $('#processBilludupi #tax_calculation_div').remove();
                $('#processBilludupi #calculation').html(data);
                $('#processBilludupi #outlet_tax').select2();

            } else {

                $('#editBill #tax_calculation_div').remove();
                $('#editBill #edit_calculation').html(data);
                $('#editBill #outlet_tax').select2();

                var tot = $("#edit_total").text();
                if ( $('#editBill .paid-value').length == 1 ) {
                    $('#editBill .paid-value:first').val(tot);
                }

            }

            if(flag == "edit" && disc_mode == 'remove'){
                $('#editBill #edit_disc_value').val("");
                $('#editBill #edit_disc_type').val("");
                $('#editBill #edit_disc_div').remove();
                $('#editBill #edit_disc_type').trigger('change.select2');
            }
            //reinitilize onchange function
            $('#editBill #outlet_tax').change(function(){

                var tax_name = $('#editBill #outlet_tax').val();
                var ord_type = $('input[name="edit_order_type"]:checked').val();
                var new_delivery = "";
                var edit_disc_type = $("#edit_disc_type").val();
                var edit_disc_value = $("#edit_disc_value").val();
                getInvoiceNo(ord_type,order_id,'edit',tax_name,new_delivery,edit_disc_type,edit_disc_value);

            });

            if(flag == "process" && disc_mode == 'remove'){

                $('#processBilludupi #disc_value').val("");
                $('#processBilludupi #disc_type').val("");
                $('#processBilludupi #disc_type').trigger('change.select2');
                var tax_name = $('#processBilludupi #outlet_tax').val();
                var ord_type = $('input[name="order_type"]:checked').val();
                var new_delivery = $("#new_delivery_value").val();
                var disc_type = $("#disc_type").val();
                var disc_value = $("#disc_value").val();
                getInvoiceNo(ord_type,order_id,'process',tax_name,new_delivery,disc_type,disc_value);

            }
            //reinitilize onchange function
            $('#processBilludupi #outlet_tax').change(function(){

                var tax_name = $(this).val();
                var ord_type = $('input[name="order_type"]:checked').val();
                var new_delivery = "";
                var edit_disc_type = $("#disc_type").val();
                var edit_disc_value = $("#disc_value").val();
                getInvoiceNo(ord_type,order_id,'process',tax_name,new_delivery,edit_disc_type,edit_disc_value);

            });

        }
    });


}