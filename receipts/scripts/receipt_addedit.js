$(function () {
    /* DATE PICKERS */
    $('#receipt-date').kendoDatePicker();

    /* DROPDOWN MENUS */
    $('#issuer-id').kendoDropDownList();
    $('#cdfi-type-id').kendoDropDownList();
    $('#payment-type-id').kendoDropDownList();

    /* NUMERICAL TEXTBOXES */
    $('#sub-total').kendoNumericTextBox();
    $('#iva').kendoNumericTextBox();
    $('#total').kendoNumericTextBox();
    $('#discount').kendoNumericTextBox();
});

function validate() {
    var hasError = false;

    hasError = validateDate('receipt-date', 'Please enter the receipt date', hasError, true);
    hasError = validateText('receipt-folio', 'Please enter folio number of receipt.', hasError);
    hasError = validateText('receipt-description', 'Please enter description of the receipt.', hasError);
    hasError = validateText('issuer-id', 'Please select an issuer.', hasError);
    hasError = validateText('cdfi-type-id', 'Please select a CDFI type.', hasError);
    hasError = validateText('payment-type-id', 'Please select a payment type.', hasError);
    hasError = validateNumber('sub-total', 'Please enter the sub total.', hasError, true);
    hasError = validateNumber('total', 'Please enter the total.', hasError, true);

    validateEnd('form-new-receipt', hasError);
}