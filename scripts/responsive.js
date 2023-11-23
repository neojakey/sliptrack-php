$(function () {
    /* PREVENT ENTITY CONTENT FROM BREAKING LAYOUT WIDTH */
    var windowWidth = $(window).width();

    if (windowWidth < 883) {
        /* GET WIDTH OF BREADCRUMB AND REMOVE PADDING */
        var entityWidth = eval($('#breadcrumb').width() - 28);
        /* SET MAX WIDTH OF DROPDOWNLIST */
        $('#entity-wrapper').css('max-width', entityWidth + 'px');
        $('#entity-id-list .k-list-scroller').css('width', entityWidth + 'px');
    }

    /* DISPLAY AND HIDE TOOLTIPS */
    $('.display-info').click(function () {

        $('.title').remove();

        var $title = $(this).find('.title');
        if (!$title.length) {
            $(this).append('<span class="title">' + $(this).attr('title') + '</span>');
        } else {
            $title.remove();
        }
    });

    /* DISPLAY OR HIDE THE OPTIONAL FIELDS */
    $('#optional-button-wrapper').click(function () {
        var $wrapper = $('#optional-fields-wrapper');
        var $icon = $('#optional-button-icon');
        var $text = $('#optional-button-text');

        if ($wrapper.css('display') === 'none' || $wrapper.css('visibility') === 'hidden') {
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            $wrapper.css('display', 'table');
            $text.html('Hide optional fields');
        } else {
            $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            $wrapper.css('display', 'none');
            $text.html('Show optional fields');
        }
    });

    /* ENABLE LABEL CLICK FOCUS FOR KENDO NUMERICAL TEXTBOXES */
    $('label').click(function (e) {
        var label = $(this);
        var id = label.attr("for");
        var widget;

        if (id) {
            widget = kendo.widgetInstance($("#" + id), kendo.ui);

            if (widget) {
                e.preventDefault();
                widget.focus();
            }
        }
    });
});

/* DISPLAY OR HIDE THE MOBILE TAB MENU */
$('#mobile-tab-menu-icon').click(function () {
    if ($('#mobile-tab-menu').css('display') === 'none' || $('#mobile-tab-menu').css("visibility") === "hidden") {
        $('#mobile-tab-menu').slideDown('fast');
    } else {
        $('#mobile-tab-menu').slideUp('fast');
    }
});

/* IF A USER EXPANDS THE WINDOW FROM MOBILE WIDTH TO DESKTOP WIDTH
WE NEED TO ENSURE THE MOBILE TAB AND SIDE MENUS ARE CLOSED */
$(window).resize(function () {
    var windowWidth = $(window).width();

    if (windowWidth > 883) {
        $('#mobile-tab-menu').hide();
    }
});
