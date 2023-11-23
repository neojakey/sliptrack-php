$(function () {
    /* DROPDOWN MENUS */
    $('#tax-regime-id').kendoDropDownList();
    $('#state-id').kendoDropDownList();

    /* MASKED TEXTBOXES */
    $('#phone').kendoMaskedTextBox({
        mask: '(000) 000-0000'
    });
});

function validate() {
    var hasError = false;

    hasError = validateText('issuer-rfc', 'Please enter RFC of the issuer.', hasError);
    hasError = validateText('issuer-name', 'Please enter name of the issuer.', hasError);
    hasError = validateText('tax-regime-id', 'Please select a tax regime for this issuer.', hasError, true);

    validateEnd('form-new-issuer', hasError);
}