<!--#include virtual="/includes/adovbs.inc" -->
<!--#include virtual="/includes/functions.asp" -->
<!--#include virtual="/includes/common.asp" -->
<%
' ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
Dim adminAry : adminAry = GetSectionPermission("prmAdmin")
Dim canViewAdmin : canViewAdmin = GetActionPermission("view", adminAry)
IF NOT canViewAdmin THEN
    Call SetUserAlert("danger", "You do not have permission to access administration.")
    Response.Redirect("/")
END IF

' ### DOES THE USER HAVE DROPDOWN EDIT PERMISSION ###
Dim dropdownAry : dropdownAry = GetSectionPermission("prmDropdowns")
Dim canEdit : canEdit = GetActionPermission("edit", dropdownAry)
IF NOT canEdit THEN
    Call SetUserAlert("danger", "You do not have permission to edit dropdown menus.")
    Response.Redirect("/admin/dropdown-menus/")
END IF

' ### GET DROPDOWN LIST ###
Dim dropdownListRS : Set dropdownListRS = Server.CreateObject("ADODB.Recordset")
Dim dropdownListSQL : dropdownListSQL = _
    "SELECT " & _
    "   DropDownParentName, " & _
    "   DropDownCode " & _
    "FROM " & _
    "   DropDownParent " & _
    "WHERE " & _
    "   DropDownParentId = " & formatDbField(Request("id"), "int", false)
dropdownListRS.open dropdownListSQL, db
IF dropdownListRS.EOF THEN
    Session("hasAlert") = true
    Session("alertType") = "info"
    Session("alertMessage") = "Dropdown List was not Found..!"
    Response.Redirect("/admin/dropdown-menus/")
ELSE
    Dim dropdownListName : dropdownListName = dropdownListRS("DropDownParentName")
    Dim dropDownCode : dropDownCode = dropdownListRS("DropDownCode")
END IF
dropdownListRS.Close
%>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>Edit Dropdown List | User Area | <%=SITE_NAME%></title>
<!--#include virtual="/includes/stylesheets.asp" -->
<style type="text/css">
.data-form tr > td:first-child {
    width: 220px;
}
</style>
</head>

<body>

<div id="page-wrapper">
    <%=GetHeader("Admin Panel")%>
    <div id="breadcrumb">
        <div>
            <ul class="breadcrumb-trail">
                <%=BreadcrumbHome()%>
                <li><a href="<%=DOMAIN%>/admin/">Admin</a></li>
                <%=BreadcrumbSpacer()%>
                <li><a href="<%=DOMAIN%>/admin/drop_down_menus/">Dropdown Lists</a></li>
                <%=BreadcrumbSpacer()%>
                <li>Edit Dropdown List</li>
            </ul>
        </div>
        <div>&nbsp;</div>
    </div>
    <div id="alert-wrapper">
        <div id="alert">
            <div id="alert-icon"></div>
        </div>
    </div>
    <div id="page-content">
        <div>
            <span class="header-text">Edit the details of the drop down list in the form below. When you are done click the "Save" button.</span>
            <form action="dropdownlist_save.asp" id="dropdownlist-form" method="post">
            <input type="hidden" name="hidDropdownListId" value="<%=Request("id")%>"/>
            <table border="0" class="data-form">
                <tr>
                    <td>Dropdown List Name <%=Application("REQUIRED")%>:</td>
                    <td><input type="text" class="k-textbox" id="dropdown-list-name" name="tbDropdownListName" value="<%=dropdownListName%>" maxlength="50"/></td>
                </tr>
                <tr>
                    <td>Dropdown Code <%=Application("REQUIRED")%>:</td>
                    <td><input type="text" class="k-textbox" name="tbDropdownCode" id="dropdown-code" value="<%=dropDownCode%>"/></td>
                </tr>
            </table>
            </form>
        </div>
    </div>
    <div id="save-panel">
        <div>
            <ul id="save-panel-buttons">
                <li><button type="button" class="primary-btn" onclick="validate()"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;Save</button></li>
                <li><button type="button" class="cancel-btn" onclick="LeavePage('/admin/drop_down_menus/');"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Cancel</button></li>
            </ul>
        </div>
    </div>
    <!-- #include virtual="/includes/footer.asp" -->
</div>
<!-- #include virtual="/includes/javascripts.asp" -->
<script type="text/javascript" src="<%=DOMAIN%>/admin/drop_down_menus/scripts/dropdownlist_addedit.js"></script>
</body>

</html>
<!--#include virtual="/includes/closeconnection.asp" -->
