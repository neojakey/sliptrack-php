var bustcachevar = 1;
var loadedobjects = '';
var rootdomain = 'http://' + window.location.hostname;
var bustcacheparameter = '';

function ajaxpage(url, containerid) {
    var page_request = false;
    document.getElementById(containerid).innerHTML = '<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td align="center"><table cellspacing="0" cellpadding="0" border="0"></tr><td style="padding-right:6px"><img src="Images/tabs/fetching_32.gif"></td><td class="fb" align="center" valign="middle" height="150">Fetching data...</td></tr></table></td></tr></table>';
    if (window.XMLHttpRequest)
        page_request = new XMLHttpRequest();
    else if (window.ActiveXObject) {
        try {
            page_request = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            try {
                page_request = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {
                //alert('error');
            }
        }
    }
    else
        return false;
    page_request.onreadystatechange = function () {
        loadpage(page_request, containerid);
    };
    if (bustcachevar)
        bustcacheparameter = (url.indexOf("?") != -1) ? "&" + new Date().getTime() : "?" + new Date().getTime();
    page_request.open('GET', url + bustcacheparameter, true);
    page_request.send(null);
}

function loadpage(page_request, containerid) {
    if (page_request.readyState == 4 && (page_request.status == 200 || window.location.href.indexOf("http") == -1))
        document.getElementById(containerid).innerHTML = page_request.responseText;
}

function loadobjs() {
    if (!document.getElementById)
        return;
    for (i = 0; i < arguments.length; i++) {
        var file = arguments[i];
        var fileref = "";
        if (loadedobjects.indexOf(file) == -1) {
            if (file.indexOf(".js") != -1) {
                fileref = document.createElement('script');
                fileref.setAttribute("type", "text/javascript");
                fileref.setAttribute("src", file);
            }
            else if (file.indexOf(".css") != -1) {
                fileref = document.createElement("link");
                fileref.setAttribute("rel", "stylesheet");
                fileref.setAttribute("type", "text/css");
                fileref.setAttribute("href", file);
            }
        }
        if (fileref != "") {
            document.getElementsByTagName("head").item(0).appendChild(fileref);
            loadedobjects += file + " ";
        }
    }
}
