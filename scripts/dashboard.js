$(document).ready(function () {
    /* SET DEFAULT DASH IF NONE FOUND */
    if (currentDash + '' === '') {
        currentDash = 'home';
    }

    /* CLICK EVENT: DISPLAY AND HIDE TOOLTIPS */
    $('.display-info').click(function () {
        $('.title').remove();

        var $title = $(this).find('.title');
        if (!$title.length) {
            $(this).append('<span class="title">' + $(this).attr('title') + '</span>');
        } else {
            $title.remove();
        }
    });

    /* CLICK EVENT: DISPLAY AND HIDE TOOLTIPS ON ITEM ADDED TO ADD AFTER INITIAL PAGE LOAD */
    $('body').on('click', '.display-info', function () {
        $('.title').remove();

        var $title = $(this).find('.title');
        if (!$title.length) {
            $(this).append('<span class="title">' + $(this).attr('title') + '</span>');
        } else {
            $title.remove();
        }
    });

    /* DOES USER HAVE 1 LOGIN */
    if (parseInt(numLogins) === 1) {
        /* LOAD THE NEW MEMBER PAGE */
        loadTab('new_member_tab.asp', currentDash);
    } else if (launchWizard === '1') {
        loadTab('questions_tab.asp', 'questions');
    } else {
        /* LOAD DASHBOARD */
        loadTab(currentDash + '_tab.asp', currentDash);
    }

    /* NO MEMBERS - ADD BOUNCE ANNIMATION */
    if (intNumberMembers === 0) {
        Bounce($('#bounce'), 2, '20px', 400);
    }

    var hideTimer = null;

    $('a[id^="tab-link-"]').bind('mouseenter', function () {
        if (hideTimer !== null) {
            clearTimeout(hideTimer);
        }

        $('ul[id$="-submenu"]').each(function (i, obj) {
            $(this).hide();
        });

        var tabId = $(this).prop('id').replace('tab-link-', '');
        $('#' + tabId + '-submenu').show();
    });

    $('.tab-submenu').bind('mouseenter', function () {
        if (hideTimer !== null) {
            clearTimeout(hideTimer);
        }
    });

    $('.tab-submenu').bind('mouseleave', function () {
        hideTimer = setTimeout(function () {
            $('ul[id$="-submenu"]').each(function (i, obj) {
                $(this).hide();
            });
        }, 200);
    });
    
    $('a[id^="tab-link-"]').bind('mouseleave', function () {
        hideTimer = setTimeout(function () {
            $('ul[id$="-submenu"]').each(function (i, obj) {
                $(this).hide();
            });
        }, 200);
    });

    /* CLICK EVENT: SUB MENUS ICONS */
    $('i[id^="tab-icon-"]').click(function () {
        $('ul[id$="-submenu"]').each(function (i, obj) {
            $(this).hide();
        });
        var tabId = $(this).prop('id').replace('tab-icon-', '');
        $('#' + tabId + '-submenu').show();
    });

    /* FADE IN COMPLETION CHECK MARK IF PRESENT */
    $('#completion-check').fadeIn('slow');

    /* CLICK EVENT: SET MEMBER */
    $('#applicant').click(function () {
        var id = $(this).data('applicant-guid');
        location.href = 'set_applicant.asp?id=' + id;
    });

    /* CLICK EVENT: DISABLED TAB SUBMENU ITEMS */
    $('.tier-disable').click(function () {
        var url = $(this).data('url');
        if (url !== undefined) {
            location.href = url;
        }
    });

    /* CLICK EVENT: SIDE MENU ITEMS */
    $('.side-menu li').click(function () {
        if (intNumberMembers > 0) {
            var url = $(this).data('url');
            if (url !== undefined) {
                location.href = url;
            }
        } else {
            Bounce($('#bounce'), 2, '20px', 400);
        }
    });

    /* SET CLICK EVENTS FOR MOBILE SIDE MENU ITEMS */
    $('li.mobile-sm').click(function () {
        var url = $(this).data('url');
        if (url !== undefined) {
            location.href = url;
        }
    });

    /* SHOW COMPLETION CHART IF ACTIVE MEMBER EXISTS */
    if (activeMember !== '') {
        completionChart(notComplete, isComplete);
    }

    /* CREATE SCORECARD ARRAY */
    createScoreCardArray();

    /* ASSETS DASHBOARD PDF EXPORT */
    $('body').on('click', '#create-assets-pdf', function () {
        var pdfOptions = {
            element: $('#assets-dashboard-pdf-wrapper'),
            fileName: 'assets_dashboard.pdf',
            title: 'Assets Dashboard',
            scale: 0.5
        };
        createPdf(pdfOptions);
    });

    /* GET MEMBER CURRENCY DATA */
    var memberCurrencyArray = memberCurrencyData.split(',');
    currency = {
        name: memberCurrencyArray[2],
        symbol: memberCurrencyArray[0],
        code: memberCurrencyArray[1]
    };

    /* SHOW ALERT IF A NEW SIGNUP */
    if (isSignupComplete) {
        ShowAlert(true, 'success', 'Account activition has been completed successfully..!');
    }

    $('#mobile-nw').click(function () {
        $('.mobile-cf, .mobile-sc, .mobile-ao').slideUp('fast');
        $('#mobile-cf-caret, #mobile-sc-caret, #mobile-ao-caret').removeClass('fa-caret-down').addClass('fa-caret-right');
        if ($('.mobile-nw').css('display') === 'none' || $('.mobile-nw').css("visibility") === "hidden") {
            $('.mobile-nw').slideDown('fast');
            $('#mobile-nw-caret').removeClass('fa-caret-right').addClass('fa-caret-down');
        } else {
            $('.mobile-nw').slideUp('fast');
            $('#mobile-nw-caret').removeClass('fa-caret-down').addClass('fa-caret-right');
        }
    });

    $('#mobile-cf').click(function () {
        $('.mobile-nw, .mobile-sc, .mobile-ao').slideUp('fast');
        $('#mobile-nw-caret, #mobile-sc-caret, #mobile-ao-caret').removeClass('fa-caret-down').addClass('fa-caret-right');
        if ($('.mobile-cf').css('display') === 'none' || $('.mobile-cf').css("visibility") === "hidden") {
            $('.mobile-cf').slideDown('fast');
            $('#mobile-cf-caret').removeClass('fa-caret-right').addClass('fa-caret-down');
        } else {
            $('.mobile-cf').slideUp('fast');
            $('#mobile-cf-caret').removeClass('fa-caret-down').addClass('fa-caret-right');
        }
    });

    $('#mobile-sc').click(function () {
        $('.mobile-nw, .mobile-cf, .mobile-ao').slideUp('fast');
        $('#mobile-nw-caret, #mobile-cf-caret, #mobile-ao-caret').removeClass('fa-caret-down').addClass('fa-caret-right');
        if ($('.mobile-sc').css('display') === 'none' || $('.mobile-sc').css("visibility") === "hidden") {
            $('.mobile-sc').slideDown('fast');
            $('#mobile-sc-caret').removeClass('fa-caret-right').addClass('fa-caret-down');
        } else {
            $('.mobile-sc').slideUp('fast');
            $('#mobile-sc-caret').removeClass('fa-caret-down').addClass('fa-caret-right');
        }
    });

    $('#mobile-ao').click(function () {
        $('.mobile-nw, .mobile-cf, .mobile-sc').slideUp('fast');
        $('#mobile-nw-caret, #mobile-cf-caret, #mobile-sc-caret').removeClass('fa-caret-down').addClass('fa-caret-right');
        if ($('.mobile-ao').css('display') === 'none' || $('.mobile-ao').css("visibility") === "hidden") {
            $('.mobile-ao').slideDown('fast');
            $('#mobile-ao-caret').removeClass('fa-caret-right').addClass('fa-caret-down');
        } else {
            $('.mobile-ao').slideUp('fast');
            $('#mobile-ao-caret').removeClass('fa-caret-down').addClass('fa-caret-right');
        }
    });

    /* DISPLAY OR HIDE THE MOBILE TAB MENU */
    $('#mobile-tab-menu-icon').click(function () {
        if ($('#mobile-tab-menu').css('display') === 'none' || $('#mobile-tab-menu').css("visibility") === "hidden") {
            $('#mobile-tab-menu').slideDown('fast');
            closeSideMenu();
        } else {
            $('#mobile-tab-menu').slideUp('fast');
        }
    });

    /* DISPLAY OR HIDE THE MOBILE SIDE MENU */
    $('#showhide-side-menu-wrapper').click(function () {
        var isSideMenuHidden = $('#mobile-side-menu').css('display') === 'none';

        if (isSideMenuHidden) {
            openSideMenu();
            $('#mobile-tab-menu').hide();
            event.stopPropagation();
        } else {
            closeSideMenu();
            event.stopPropagation();
        }
    });

    /* IF A USER EXPANDS THE WINDOW FROM MOBILE WIDTH TO DESKTOP WIDTH
    WE NEED TO ENSURE THE MOBILE TAB AND SIDE MENUS ARE CLOSED */
    $(window).resize(function () {
        var windowWidth = $(window).width();

        if (windowWidth > 883) {
            $('#mobile-tab-menu').hide();
            closeSideMenu();
        }
    });
});

function openSideMenu() {
    $('#mobile-side-menu').show();
    $('#showhide-side-menu-wrapper > div').css('left', '227px');
    $('#showhide-side-menu-icon').removeClass('fa-chevron-right').addClass('fa-chevron-left');
}

function closeSideMenu() {
    $('#mobile-side-menu').hide();
    $('#showhide-side-menu-wrapper > div').css('left', '0');
    $('#showhide-side-menu-icon').removeClass('fa-chevron-left').addClass('fa-chevron-right');
}

function RemoveAll() {
    $('[id^="assets"]').addClass('hide-link');
    $('[id^="liabilities"]').addClass('hide-link');
    $('[id^="cashflow"]').addClass('hide-link');
    $('[id^="other"]').addClass('hide-link');
}

function Bounce(ele, times, distance, speed) {
    for (i = 0; i < times; i++) {
        ele.animate({
            marginLeft: '-=' + distance
        }, speed).animate({
            marginLeft: '+=' + distance
        }, speed);
    }
}

function selecttab(tabName) {
    var tabGroupName = tabName;

    RemoveAll();

    if (tabName === 'assets') {
        $('[id^="assets"]').removeClass('hide-link');
    } else if (tabName === 'liabilities') {
        $('#assets-2a').removeClass('hide-link'); // SHOW RESIDENCES AS IT CONTAINS LIABILITIES
        $('#assets-6').removeClass('hide-link'); // SHOW VEHICLE AS IT CONTAINS LIABILITIES
        $('#assets-2b').removeClass('hide-link'); // SHOW REAL ESTATE AS IT CONTAINS LIABILITIES
        $('#assets-3').removeClass('hide-link'); // SHOW BUSINESS AS IT CONTAINS LIABILITIES
        $('[id^="liabilities"]').removeClass('hide-link');
    } else if (tabName === 'cashflow') {
        $('[id^="cashflow"]').removeClass('hide-link');
    } else {
        $('[id^="assets"]').removeClass('hide-link');
        $('[id^="liabilities"]').removeClass('hide-link');
        $('[id^="cashflow"]').removeClass('hide-link');
        $('[id^="other"]').removeClass('hide-link');
    }

    /* HIGHLIGHT TAB WHERE PAGE DISPLAYED IS FROM - ALTER THIS LIST IS ALTERATIONS ARE MADE */
    if (tabName === 'assets' || tabName === 'liabilities' || tabName === 'networth' || tabName === 'dashnw' || tabName === 'globalreturn' || tabName === 'returnyear') {
        tabGroupName = 'networth';
    } else if (tabName === 'income' || tabName === 'expense' || tabName === 'cashflow' || tabName === 'dashei' || tabName === 'returnincome') {
        tabGroupName = 'cashflow';
    } else if (tabName === 'scorecard') {
        tabGroupName = 'scorecard';
    } else if (tabName === 'disclosures' || tabName === 'nwstatements_borrower' || tabName === 'nwstatements_investor' || tabName === 'eol_worksheet' || tabName === 'eolplan' || tabName === 'eol_repo') {
        tabGroupName = 'advanced';
    } else if (tabName === 'reports') {
        tabGroupName = 'reports';
    }

    $('[id^="tab-"]').each(function (index) {
        $(this).removeClass('active');
    });

    $('#tab-' + tabGroupName).addClass('active');
}

function createScoreCardArray() {
    if (scoreCard + '' !== '') {
        let cardArray = scoreCard.split('||');
        cardArray.forEach(function (card) {
            let part = card.split(',');
            let id = part[0];
            let ranges = [ part[1], part[2], part[3], part[4] ];

            let cardObj = {
                scorecardId: id,
                scoreCardArray: ranges
            };
            scores.push(cardObj);
        });
    }
}

function loadTab(pageName, tabName) {
    // CLOSE ALL HOVER TAB SUB MENUS
    $('ul[id$="-submenu"]').each(function () {
        $(this).hide();
    });

    // DISPLAY PROGRESS ANIMATION
    kendo.ui.progress($('body'), true);
    $('.k-loading-mask').css('height', $(document).height() + 'px');

    // CALL AND RENDER REQUESTED PAGE
    $.ajax({
        url: domain + '/summary/' + pageName,
        async: true,
        success: function (data) {
            // POPULATE ELEMENT WITH RETURNED CODE
            $('#summary-wrapper').html(data);

            // IF QUESTIONS THEN DISPLAY THE WIZARD
            if (tabName !== 'questions') {
                selecttab(tabName);
            }

            // PLOT CHARTS - TABS THAT HAVE CHARTS
            if (tabName === 'income' || tabName === 'networth' || tabName === 'liabilities' || tabName === 'assets' || tabName === 'expense' || tabName === 'cashflow') {
                loadChart(tabName + '-chart-wrapper');

                // ENABLE RESIZING ON ALL KENDO CHARTS
                $(window).resize(function () {
                    $('div[id$="-chart-wrapper"]').each(function () {
                        $(this).data('kendoChart').refresh();
                    });
                });
            }

            if (tabName === 'dashnw') {
                loadChart('assets-chart-wrapper');
                loadChart('liabilities-chart-wrapper');
                loadChart('networth-chart-wrapper');

                if (!$('#dashnw-no-results').length) {
                    displayNetworthBarChart('network-stack-chart-wrapper');
                    displayAssetPieChart('asset-pie-chart-wrapper');
                    displayLiabilityPieChart('liability-pie-chart-wrapper');
                }

                // ENABLE RESIZING ON ALL KENDO CHARTS
                $(window).resize(function () {
                    $('div[id$="-chart-wrapper"]').each(function () {
                        $(this).data('kendoChart').refresh();
                    });
                });
            }

            if (tabName === 'dashei') {
                loadChart('income-chart-wrapper');
                loadChart('expense-chart-wrapper');
                loadChart('cashflow-chart-wrapper');

                if (!$('#dashei-no-results').length) {
                    displayExpenseIncomeLineChart('income-expense-chart-wrapper');
                    displayExpenseBarChart('expense-chart2-wrapper');
                    displayTotalDebtChart('total-debt-chart-wrapper');
                    expensePieChart('expense-pie-chart-wrapper');
                }

                // ENABLE RESIZING ON ALL KENDO CHARTS
                $(window).resize(function () {
                    $('div[id$="-chart-wrapper"]').each(function () {
                        $(this).data('kendoChart').refresh();
                    });
                });
            }

            // HIDE PROGRESS ANIMATION
            kendo.ui.progress($('body'), false);
        },
        error: function (xhr) {
            // DISPLAY ERROR
            var errorHtml = '';
            var isProduction = false;  // CHANGE THIS FOR DEVELOPER MODE
            var errorResponse = xhr.responseText.replace(/\ face="Arial"/g, '');

            if (isProduction) {
                errorHtml = '<main>' +
                    '    <div style="text-align:center;padding-top:50px">' +
                    '        <h1>Uh-Oh, Houston, we have a problem..!</h1>' +
                    '        <img src="/Images/thinking.gif" alt="Problem detected"/>' +
                    '        <p>An unexpected error has occurred. <a href="/contact-us/" target="_new">Please contact our administrators</a></p>' +
                    '    </div>' +
                    '</main>';
                console.log(xhr);
            } else {
                errorHtml = '<main>' +
                    '    <div class="summary-tab-header top">' +
                    '        <div><h1><i class="fa fa-rocket" aria-hidden="true"></i>&nbsp;&nbsp;Uh-Oh, Houston, we have a problem..!</h1></div>' +
                    '        <div>&nbsp;</div>' +
                    '    </div> ' +
                    '    <div>' +
                    '        <p style="color:#999">An error has been detected:&nbsp;&nbsp;<b style="color:#000">' + xhr.status + ' - ' + xhr.statusText + '</b></p>' +
                    '    </div>' +
                    '    <div class="code">' + errorResponse + '</div>' +
                    '</main>';
            }
            $('#summary-wrapper').html(errorHtml);

            // HIDE PROGRESS ANIMATION
            kendo.ui.progress($('body'), false);
        }
    });
}
