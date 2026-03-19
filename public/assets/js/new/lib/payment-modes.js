function addPaymentMode(model_id) {

    var error = 0;

    //check if any payment value is blank or not
    $(model_id+' .paid-value').each(function () {

        if ( $(this).val() == '' ) {
            $(this).css('border-color','red');
            error = 1;
        } else {
            $(this).css('border-color','#cccccc');
        }
    })

    if ( error == 1 ) {
        successErrorMessage('Please insert amount','error');
        return;
    }


    var $destroy_select = $(model_id+" .paid-type:first").select2();
    $destroy_select.select2('destroy');

    $(model_id+' .payment_div:first').clone().insertAfter(model_id+' .payment_div:last');
    $destroy_select.select2();
    $(model_id+" .paid-type:last").select2();
    $(model_id+" .paid-value:last").val('');

    $(model_id+" .transaction-id:last").val('');
    $(model_id+" .transaction-id:last").parent().addClass('hide');

    $(model_id+" .payment_div .btn-danger").removeClass('hide');
    $(model_id+" .payment_div .paid-value").removeAttr('readonly');

    //
}

function removePaymentMode(ele) {

    var model_id = '#'+$(ele).closest('.modal').attr('id');

    $(ele).closest('.payment_div').remove();

    //if only one payment mode than remove btn for remove payment mode
    if ( $(model_id+' .payment_div').length == 1 ) {

        $(model_id+" .payment_div .btn-danger").addClass('hide');
        $(model_id+" .payment_div .paid-value").prop('readonly',true);

        if ( model_id == '#processBill') {
            $(model_id+" .payment_div .paid-value").val($('#total').text());
        } else if ( model_id == '#editBill') {
            $(model_id+" .payment_div .paid-value").val($('#edit_total').text());
        }

    }
}

//checkPaymentOption
function checkPaymentOption(e) {

    var option = $(e).find("option:selected").text();

    if ( option == 'Online - UPI') {
        $('#upi_block').removeClass('hide');
    } else {
        $('#upi_block').addClass('hide');
    }

    //search if payment mode is cash than hide transaction id field
    var search = option.search("Cash");
    var search1 = option.search("UnPaid");
    var search2 = option.search("Complimentary");

    if ( search != 0 && search1 != 0 && search2 != 0 ) {
        $(e).parent().parent().find('.transaction-id').parent().removeClass('hide');
        $(e).parent().parent().find('.transaction-id').val('');
    } else {
        $(e).parent().parent().find('.transaction-id').parent().addClass('hide');
    }
}