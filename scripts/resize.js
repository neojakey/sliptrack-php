function resize(minWidth) {
    var mobileWidth = eval($('#detail-bar-mobile').width() - 24);
    var pageWrapper = $('#page-wrapper').width();
    var windowWidth = $(window).width();

    $('.scrollable-wrapper').css('width', eval(pageWrapper - 311) + 'px');

    if (windowWidth < minWidth) {
        $('.scrollable-wrapper').css('width', mobileWidth + 'px');
    }
}