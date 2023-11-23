function validate() {
    var hasError = false;
    hasError = validateText('full-name', 'Please enter your full name.', hasError);
    hasError = validateText('email-address', 'Please enter your email address.', hasError);
    if (!hasError) {
        if (echeck($('#email-address').val()) === false) {
            alert('Please enter a valid email address');
            $('#email-address').focus();
            hasError = true;
        }
    }
    hasError = validateText('subject', 'Please enter the subject line.', hasError);
    hasError = validateText('description', 'Please enter the description.', hasError);
    validateEnd('contact-us-form', hasError);
}
