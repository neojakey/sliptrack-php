function ShowAlert(boolShowAlert, strAlertType, strAlertMessage) {
    if (boolShowAlert) {
        setTimeout(function () {
            $('#alert').addClass('alert-' + strAlertType);
            $('#alert-icon').html(strAlertMessage).addClass('alert-' + strAlertType + '-icon');
            $('#alert-wrapper').slideDown('slow');
        }, 500);

        setTimeout(function() {
            $('#alert-wrapper').slideUp('slow');
        }, 7000);
    }
}
