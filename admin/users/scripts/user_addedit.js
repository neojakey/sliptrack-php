/* PAGE INITIALIZATION */
$(function () {
    $('#user-group').kendoDropDownList();
    $('#site-theme').kendoDropDownList();
});

function validate() {
    var action = $('#form-action').val();
    var userId = $('#hid-userid').val();
    var orgEmail = $('#org-email-address').val().toLowerCase();
    var newEmail = $('#email').val().toLowerCase();
    var hasError = false;

    hasError = validateText('first-name', 'Please enter the first name.', hasError);
    hasError = validateText('last-name', 'Please enter the last name.', hasError);
    hasError = validateText('email', 'Please enter the email address.', hasError);
    if (!hasError) {
        /* CHECK IF EMAIL ADDRESS HAS CHANGED */
        if (orgEmail !== newEmail) {
            /* CHECK EMAIL ADDRESS IS CORRECTLY FORMATTED */
            if (echeck(newEmail) === false) {
                alert('Please enter a valid email address.');
                $('#email').focus();
                hasError = true;
            }
        }
    }

    if (!hasError) {
        /* CHECK IF EMAIL ADDRESS HAS CHANGED */
        if (orgEmail !== newEmail) {
            var promise = existenceCheck(userId, newEmail);
            if (orgEmail !== newEmail) {
                promise.then(function (data) {
                    if (data === 'True') {
                        alert('A user already exists with this email address');
                        $('#email').focus();
                    } else {
                        if (action === 'ADD') {
                            hasError = validateText('password', 'Please enter the password.', hasError);
                        }
                        hasError = validateText('payment-tier', 'Please select a payment tier.', hasError);
                        hasError = validateText('user-group', 'Please select a user group.', hasError);
                        validateEnd('user-form', hasError);
                    }
                }, function (xhr) {
                    hasError = true;
                    console.log(xhr);
                    alert('An error was thrown during the existence check. Consult the JS console, or contact your web developer');
                });
            }
        } else {
            if (action === 'ADD') {
                hasError = validateText('password', 'Please enter the password.', hasError);
            }
            hasError = validateText('payment-tier', 'Please select a payment tier.', hasError);
            hasError = validateText('user-group', 'Please select a user group.', hasError);
            validateEnd('user-form', hasError);
        }
    }
}

function existenceCheck(thisUserId, thisEmail) {
    var promiseObj = new Promise(function (resolve, reject) {
        $.ajax({
            url: '/admin/users/existence_check.asp?id=' + thisUserId + '&value=' + thisEmail,
            method: 'GET',
            cache: false,
            async: false
        }).done(function (data) {
            resolve(data);
        }).fail(function (xhr) {
            reject(xhr);
        });
    });
    return promiseObj;
}
