$(document).ready(function () {
    $('#register-button, #register-button-top').click(function () {
        document.location.href = 'register/';
    });

    $('#sign-in-top').click(function () {
        $([document.documentElement, document.body]).animate({
            scrollTop: $('#login-element').offset().top
        }, 2000);
    });
});

function validate()
{
    var ErrorFound = 0;
    if (document.login.tbEmailAddress.value === '') {
         alert('Please type your email address.');
         ErrorFound++;
    }
    if (ErrorFound === 0) {
         if (document.login.tbPassword.value === '') {
              alert('Please type your password.');
              login.tbPassword.focus();
              ErrorFound++;
         }
    }
    if (ErrorFound === 0) {
         document.login.submit();
    }
}