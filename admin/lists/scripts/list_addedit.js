function validate() {
    var hasError = false;
    hasError = validateText('list-name', 'Please enter the list name', hasError);
    hasError = validateText('list-code', 'Please enter the 10 character list code', hasError);
    validateEnd('list-form', hasError);
}

$(document).ready(function () {
    $('#list-name').focus();
});
