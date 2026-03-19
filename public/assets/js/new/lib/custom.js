
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function onlyNumbersWithDot(e) {
    var charCode;
    if (e.keyCode > 0) {
        charCode = e.which || e.keyCode;
    }
    else if (typeof (e.charCode) != "undefined") {
        charCode = e.which || e.keyCode;
    }
    if (charCode == 46)
        return true
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function processBtn(ele,flag,text) {
    if( flag == 'add' ) {
        $('#'+ele).text(text);
        $('#'+ele).prop('disabled',true);
        $('#'+ele).addClass('processing');
    } else {
        $('#'+ele).text(text);
        $('#'+ele).prop('disabled',false);
        $('#'+ele).removeClass('processing');
    }

}

function successErrorMessage(message, type) {

    if ($.noty) {

        if ( type == 'success' ) {
            message = '<div class="activity-item"> <i class="zmdi zmdi-check-all"></i> <div class="activity">'+message+'</div> </div>';
            type = 'success';
        } else {
            message = '<div class="activity-item"> <i class="zmdi zmdi-check-all"></i> <div class="activity">'+message+'</div> </div>';
            type = 'error';
        }

        var n = noty({
            text: message,
            type: type,
            dismissQueue: true,
            layout: 'topCenter',
            closeWith: ['click'],
            theme: 'ThemeNoty',
            maxVisible: 10,
            animation: {
                open: 'noty_animated bounceInDown',
                close: 'noty_animated fadeOut',
                easing: 'swing',
                speed: 500
            }
        });
        setTimeout(function () {
            n.close();
        }, 5000);


    }

}

function check30daysDiff(from_date, to_date) {

    var diff = moment(to_date).diff(moment(from_date), 'days');
    if(diff > 32){

        swal({
            title: "Caution!",
            text: "Date difference cannot be more than 30 days!",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "I am changing Date!",
            closeOnConfirm: false
        });
        return true;
    }
    return false;
}