/* USER MENU */
$(function () {
    $('.item.profile').click(function () {
        document.location.href = '/profile/';
    });

    $('.item.logout').click(function () {
        document.location.href = 'logout.php';
    });
});


function LeavePage(strUrl) {
    var agree = confirm('Are you sure you want to cancel?\n\nClick OK to confirm. Any unsaved changes you have made will be lost.');
    if (agree) {
        document.location.href = strUrl;
    }
}

function LeavePageSpecial(strUrl, strMessage) {
    var agree = confirm(strMessage + '\n\nClick OK to confirm. Any unsaved changes you have made will be lost.');
    if (agree) {
        document.location.href = strUrl;
    }
}

function echeck(str) {
    var at = '@';
    var dot = '.';
    var lat = str.indexOf(at);
    var lstr = str.length;
    if (str.indexOf(at) === -1) {
        return false;
    }
    if (str.indexOf(at) === -1 || str.indexOf(at) === 0 || str.indexOf(at) === lstr) {
        return false;
    }
    if (str.indexOf(dot) === -1 || str.indexOf(dot) === 0 || str.indexOf(dot) === lstr) {
        return false;
    }
    if (str.indexOf(at, lat + 1) !== -1) {
        return false;
    }
    if (str.substring(lat - 1, lat) === dot || str.substring(lat + 1, lat + 2) === dot) {
        return false;
    }
    if (str.indexOf(dot, lat + 2) === -1) {
        return false;
    }
    if (str.indexOf(' ') !== -1) {
        return false;
    }
    return true;
}
