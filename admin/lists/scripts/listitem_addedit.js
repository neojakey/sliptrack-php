function validate() {
    var hasError = false;
    hasError = validateText('item-name', 'Please enter the list item name', hasError);
    hasError = validateText('item-code', 'Please enter the list item code', hasError);
    validateEnd('item-form', hasError);
}

$(document).ready(function () {
    $('#item-name').focus();
});