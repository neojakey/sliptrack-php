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
    var section = str.toLowerCase();

    if ($('#create-' + section).prop('checked') && $('#edit-' + section).prop('checked') && $('#delete-' + section).prop('checked') && $('#view-' + section).prop('checked')) {
        $('#full-' + section).prop('checked', true);
    }

    if ($('#create-' + section).prop('checked') || $('#edit-' + section).prop('checked') || $('#delete-' + section).prop('checked')) {
        $('#view-' + section).prop('checked', true);
    }

    SaveData(str, id);
}

function PermToggle(str) {
    var id = $('#hid-group-id').val();
    var section = str.toLowerCase();
    
    if ($('#full-' + section).prop('checked')) {
        $('#create-' + section).prop('checked', true).attr('disabled', true);
        $('#edit-' + section).prop('checked', true).attr('disabled', true);
        $('#delete-' + section).prop('checked', true).attr('disabled', true);
        $('#view-' + section).prop('checked', true).attr('disabled', true);
    } else {
        $('#create-' + section).prop('checked', false).removeAttr('disabled');
        $('#edit-' + section).prop('checked', false).removeAttr('disabled');
        $('#delete-' + section).prop('checked', false).removeAttr('disabled');
        $('#view-' + section).prop('checked', false).removeAttr('disabled');
    }

    SaveData(str, id);
}

function SaveData(strSection, strID) {
    var nSaving = 'Saving' + strSection;
    var section = strSection.toLowerCase();

    var strFullControl = '', strCreate = '', strEdit = '', strDelete = '', strView = '';

    if ($('#full-' + section).prop('checked')) { strFullControl = 'full'; }
    if ($('#create-' + section).prop('checked')) { strCreate = 'create'; }
    if ($('#edit-' + section).prop('checked')) { strEdit = 'edit'; }
    if ($('#delete-' + section).prop('checked')) { strDelete = 'delete'; }
    if ($('#view-' + section).prop('checked')) { strView = 'view'; }

    var xmlHttp = GetXmlHttpObject();
    var url = $base + '/admin/groups/save_permissions.php?section=' + strSection + '&id=' + strID + '&fullcontrol=' + strFullControl + '&create=' + strCreate + '&edit=' + strEdit + '&delete=' + strDelete + '&view=' + strView;

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

