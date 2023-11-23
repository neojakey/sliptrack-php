function validate() {
    var hasError = false;
    hasError = validateText('dropdown-list-name', 'Please enter the dropdown list name', hasError);
    hasError = validateText('dropdown-code', 'Please enter the 10 character dropdown code', hasError);
    validateEnd('dropdownlist-form', hasError);
}

$(document).ready(function () {
    $('#dropdown-list-name').focus();
});
