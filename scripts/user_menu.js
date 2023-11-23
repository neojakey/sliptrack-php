$(document).ready(function () {
    $('#user-menu-link').click(function () {
        $('#user-menu-link').css('backgroundColor', '#78C335');
        $('#desktop-user-menu-name').css('color', '#FFF');
        $('header .fa.fa-caret-down').css('color', '#FFF');
        $('#desktop-user-menu-bars').css('color', '#FFF');
        $('#user-menu-wrapper').slideDown('fast');
    });
});

$(document).click(function (e) {
    if (!$(e.target).closest('#user-menu-link').length) {
        $('#user-menu-wrapper').slideUp('fast');
        $('#user-menu-link').css('backgroundColor', '');
        $('#desktop-user-menu-name').css('color', '#444');
        $('header .fa.fa-caret-down').css('color', '#686868');
        $('#desktop-user-menu-bars').css('color', '#D9D9D9');
    }
});
