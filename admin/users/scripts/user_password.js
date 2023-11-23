function validate() {
    var ErrorFound = 0
    if ($('#password-1').val() == '') {
        alert('Please type your new password.');
        $('#password-1').focus();
        ErrorFound++;
    }
    if (ErrorFound == 0) {
        if ($('#password-2').val() == '') {
            alert('Please repeat your new password.');
            $('#password-2').focus();
            ErrorFound++;
        }
    }
    if (ErrorFound == 0) {
        if ($('#password-1').val() != $('#password-2').val()) {
            alert('The two new password fields do not match, please try again.');
            $('#password-2').focus();
            ErrorFound++;
        }
    }
    if (ErrorFound == 0) {
        $('#change-password-form').submit();
    }
}