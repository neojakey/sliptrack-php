function validate() {
    var hasError = false;

    hasError = validateText('source-name', 'Please enter name of the source.', hasError);
    hasError = validateText('source-url', 'Please enter url of the source.', hasError);

    validateEnd('form-new-source', hasError);
}