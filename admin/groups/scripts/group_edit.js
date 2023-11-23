$(document).ready(function () {
    setTimeout(function () {
        kendo.ui.progress($('body'), true);
        initializeKendoTabStrip('tabstrip');
    }, 50);
});

function initializeKendoTabStrip(element) {
    $('#' + element).kendoTabStrip({
        animation: {
            open: {
                effects: 'fadeIn'
            }
        }
    });
    $('#tabstrip').show();
    kendo.ui.progress($('body'), false);
}

function FullControlCheck(str) {
    var id = $('#hid-group-id').val();
    var strFullControl = "cbFullControl_" + str;
    var strCreate = "cbCreate_" + str;
    var strEdit = "cbEdit_" + str;
    var strDelete = "cbDelete_" + str;
    var strView = "cbView_" + str;

    if (document.PermissionsEdit[strCreate].checked == true && document.PermissionsEdit[strEdit].checked == true && document.PermissionsEdit[strDelete].checked == true && document.PermissionsEdit[strView].checked == true) {
        document.PermissionsEdit[strCreate].checked = false;
        document.PermissionsEdit[strEdit].checked = false;
        document.PermissionsEdit[strDelete].checked = false;
        document.PermissionsEdit[strView].checked = false;
        document.PermissionsEdit[strFullControl].checked = true;
    }

    if (document.PermissionsEdit[strCreate].checked == true || document.PermissionsEdit[strEdit].checked == true || document.PermissionsEdit[strDelete].checked == true && document.PermissionsEdit[strView].checked == false) {
        document.PermissionsEdit[strView].checked = true;
    } else if (document.PermissionsEdit[strFullControl].checked == true) {
        document.PermissionsEdit[strFullControl].checked = false;
    }

    SaveData(str, id);
}

function PermToggle(str) {
    var id = $('#hid-group-id').val();
    var strFullControl = "cbFullControl_" + str;
    var strCreate = "cbCreate_" + str;
    var strEdit = "cbEdit_" + str;
    var strDelete = "cbDelete_" + str;
    var strView = "cbView_" + str;

    if (document.PermissionsEdit[strFullControl].checked == true) {
        document.PermissionsEdit[strCreate].checked = false;
        document.PermissionsEdit[strCreate].disabled = true;
        document.PermissionsEdit[strEdit].checked = false;
        document.PermissionsEdit[strEdit].disabled = true;
        document.PermissionsEdit[strDelete].checked = false;
        document.PermissionsEdit[strDelete].disabled = true;
        document.PermissionsEdit[strView].checked = false;
        document.PermissionsEdit[strView].disabled = true;
    } else {
        document.PermissionsEdit[strCreate].disabled = false;
        document.PermissionsEdit[strEdit].disabled = false;
        document.PermissionsEdit[strDelete].disabled = false;
        document.PermissionsEdit[strView].disabled = false;
    }

    SaveData(str, id);
}

function SaveData(strSection, strID) {
    var nSaving = 'Saving' + strSection;
    var nFullControl = 'cbFullControl_' + strSection;
    var nCreate = 'cbCreate_' + strSection;
    var nEdit = 'cbEdit_' + strSection;
    var nDelete = 'cbDelete_' + strSection;
    var nView = 'cbView_' + strSection;

    var strFullControl = '';
    var strCreate = '';
    var strEdit = '';
    var strDelete = '';
    var strView = '';

    var nFullControlChecked = document.PermissionsEdit[nFullControl].checked;
    if (nFullControlChecked) {
        strFullControl = document.PermissionsEdit[nFullControl].value;
    }

    var nCreateChecked = document.PermissionsEdit[nCreate].checked;
    if (nCreateChecked) {
        strCreate = document.PermissionsEdit[nCreate].value;
    }

    var nEditChecked = document.PermissionsEdit[nEdit].checked;
    if (nEditChecked) {
        strEdit = document.PermissionsEdit[nEdit].value;
    }

    var nDeleteChecked = document.PermissionsEdit[nDelete].checked;
    if (nDeleteChecked) {
        strDelete = document.PermissionsEdit[nDelete].value;
    }

    var nViewChecked = document.PermissionsEdit[nView].checked;
    if (nViewChecked) {
        strView = document.PermissionsEdit[nView].value;
    }

    var xmlHttp = GetXmlHttpObject();
    var url = '/admin/groups/save-permissions/?section=' + strSection + '&id=' + strID + '&fullcontrol=' + strFullControl + '&create=' + strCreate + '&edit=' + strEdit + '&delete=' + strDelete + '&view=' + strView;

    if (!xmlHttp) {
    alert('Browser does not support HTTP Request');
        return;
    }
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState === 1) {
            document.getElementById(nSaving).innerHTML = '<i class="fa fa-cog fa-spin fa-fw" style="color:#1B5700"></i>';
        }
        if (xmlHttp.readyState === 4 || xmlHttp.readyState === 'complete') {
            document.getElementById(nSaving).innerHTML = '&nbsp;';
        }
    };
    xmlHttp.open('GET', url, true);
    xmlHttp.send(null);
}

function GetXmlHttpObject() {
    var objXmlHttp = null;
    if (window.XMLHttpRequest) {
        objXmlHttp = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        objXmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
    }
    return objXmlHttp;
}

