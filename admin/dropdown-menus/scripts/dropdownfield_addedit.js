function validate() {
    var hasError = false;
    hasError = validateText('dropdown-field-name', 'Please enter the dropdown field name', hasError);
    hasError = validateText('dropdown-field-code', 'Please enter the dropdown field code', hasError);
    validateEnd('dropdown-field-form', hasError);
}

$(document).ready(function () {
    $('#dropdown-field-name').focus();
});