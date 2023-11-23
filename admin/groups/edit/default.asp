<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/functions_security.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<%
' ### DECLARE AND RESOLVE USER PERMISSIONS ###
Dim adminAry : adminAry = GetSectionPermission("prmAdmin")
Dim canViewAdmin : canViewAdmin = GetActionPermission("view", adminAry)
IF NOT canViewAdmin THEN
    Call SetUserAlert("danger", "You do not have permission to access administration.")
    Response.Redirect("/")
END IF

Dim permissionsAry : permissionsAry = GetSectionPermission("prmGroups")
Dim canEdit : canEdit = GetActionPermission("edit", permissionsAry)
IF NOT canEdit THEN
    Call SetUserAlert("danger", "You do not have permission to edit groups.")
    Response.Redirect("/admin/groups/")
END IF

' ### PAGE DECLARATIONS ###
Dim groupId : groupId = Request("id")
Dim groupName : groupName = GetGroupName(groupId)
%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><%=SITE_NAME%> - User Area</title>
    <!--#include virtual="/includes/stylesheets.asp" -->
    <link rel="stylesheet" href="/admin/groups/css/group_edit.css"/>
</head>

<body>

<div id="page-wrapper">
    <div class="menu">
        <!--#include virtual="/includes/menu_admin.asp" -->
    </div>
    <div class="main">
        <header>
            <div></div>
            <div class="notification-wrapper">
                <a href="javascript:void(0);"><i class="fa fa-bell" aria-hidden="true"></i></a>
                <a href="javascript:void(0);"><i class="fa fa-envelope" aria-hidden="true"></i></a>
            </div>
            <div class="user-wrapper" id="user-menu-link">
                <span id="desktop-user-menu-bars"><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>
                <span id="desktop-user-menu-name"><%=Session("userFullName")%></span>
                <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
            </div>
        </header>
        <section>
            <h1 class="page-title"><%=groupName%> Group Members</h1>
            <div class="breadcrumb">
                <%=ADMIN_BREADCRUMB%><a href="/admin/groups/">User Groups</a><%=SPACER%><%=groupName%> Group Permissions
            </div>
            <div class="add-button-wrapper">
                <button type="button" class="primary-btn" onclick="location.href='/admin/groups/add/';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add Group</button>
            </div>
            <div id="alert-wrapper" style="display:none">
                <div id="alert">
                    <div id="alert-icon"></div>
                </div>
            </div>
            <form action="group_edit_new2.asp" method="post" name="PermissionsEdit">
                <input type="hidden" name="hidGroupId" id="hid-group-id" value="<%=Request("id")%>"/>
                <div id="tabstrip">
                    <ul>
                        <li class="k-state-active">Account</li>
                        <li>System</li>
                    </ul>
                    <div class="tabstrip-content-wrapper">
                        <h3>Account Permissions</h3>
                        <table border="0" class="permission-grid">
                            <tr>
                                <td>Section</td>
                                <td></td>
                                <td>Full Control</td>
                                <td>Create</td>
                                <td>Edit</td>
                                <td>Delete</td>
                                <td>View</td>
                            </tr>
                            <%
                            Dim oRSbn : Set oRSbn = Server.CreateObject("ADODB.Recordset")
                            strSQL = "SELECT [prmAdmin] FROM [Group] WHERE [GroupId] = " & formatDbField(groupId, "int", false)
                            oRSbn.open strSQL, db
                            nSavedSection = oRSbn("prmAdmin")
                            oRSbn.Close
                            %>
                            <tr>
                                <td>Administrators</td>
                                <td id="SavingAdmin">&nbsp;</td>
                                <td id="cbFullControl_Admin"><input type="checkbox" class="k-checkbox" name="cbFullControl_Admin" id="full-admin" value="full" onclick="PermToggle('Admin')"<% IF Instr(nSavedSection, "full") <> 0 THEN %> checked="checked"<% END IF %>/><label for="full-admin" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="create-admin" name="cbCreate_Admin" value="create" onclick="FullControlCheck('Admin')"<% IF Instr(nSavedSection, "create") <> 0 THEN %> checked="checked"<% END IF %><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF %>/><label for="create-admin" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="edit-admin" name="cbEdit_Admin" value="edit" onclick="FullControlCheck('Admin')"<% IF Instr(nSavedSection, "edit") <> 0 THEN %> checked="checked"<% END IF %><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF %>/><label for="edit-admin" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="delete-admin" name="cbDelete_Admin" value="delete" onclick="FullControlCheck('Admin')"<% IF Instr(nSavedSection, "delete") <> 0 THEN %> checked="checked"<% END IF %><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF %>/><label for="delete-admin" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="view-admin" name="cbView_Admin" value="view" onclick="FullControlCheck('Admin')"<% IF Instr(nSavedSection, "view") <> 0 THEN %> checked="checked"<% END IF %><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF %>/><label for="view-admin" class="k-checkbox-label"></label></td>
                            </tr>
                            <%
                            Set oRSbn = Server.CreateObject("ADODB.Recordset")
                            strSQL = "SELECT [prmGroups] FROM [Group] WHERE [GroupId] = " & formatDbField(groupId, "int", false)
                            oRSbn.open strSQL, db
                            nSavedSection = oRSbn("prmGroups")
                            oRSbn.Close
                            %>
                            <tr>
                                <td class="pl">Groups</td>
                                <td id="SavingGroups">&nbsp;</td>
                                <td><input type="checkbox" class="k-checkbox" name="cbFullControl_Groups" id="full-groups" value="full" onclick="PermToggle('Groups');"<% IF Instr(nSavedSection, "full") <> 0 THEN %> checked="checked"<% END IF %>/><label for="full-groups" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbCreate_Groups" id="create-groups" value="create" onclick="FullControlCheck('Groups')"<% IF Instr(nSavedSection, "create") <> 0 THEN %> checked="checked"<% END IF %><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF %>/><label for="create-groups" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbEdit_Groups" id="edit-groups" value="edit" onclick="FullControlCheck('Groups')"<% IF Instr(nSavedSection, "edit") <> 0 THEN %> checked="checked"<% END IF %><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF %>/><label for="edit-groups" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbDelete_Groups" id="delete-groups" value="delete" onclick="FullControlCheck('Groups')"<% IF Instr(nSavedSection, "delete") <> 0 THEN %> checked="checked"<% END IF %><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF %>/><label for="delete-groups" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbView_Groups" id="view-groups" value="view" onclick="FullControlCheck('Groups')"<% IF Instr(nSavedSection, "view") <> 0 THEN %> checked="checked"<% END IF %><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF %>/><label for="view-groups" class="k-checkbox-label"></label></td>
                            </tr>
                            <%
                            Set oRSbn = Server.CreateObject("ADODB.Recordset")
                            strSQL = "SELECT [prmUsers] FROM [Group] WHERE [GroupId] = " & formatDbField(groupId, "int", false)
                            oRSbn.open strSQL, db
                            nSavedSection = oRSbn("prmUsers")
                            oRSbn.Close
                            %>
                            <tr>
                                <td>Users</td>
                                <td id="SavingUsers">&nbsp;</td>
                                <td id="cbFullControl_Users"><input type="checkbox" class="k-checkbox" name="cbFullControl_Users" id="full-users" value="full" onclick="PermToggle('Users')"<% IF Instr(nSavedSection, "full") <> 0 THEN %> checked="checked"<% END IF %>/><label for="full-users" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbCreate_Users" id="create-users" value="create" onclick="FullControlCheck('Users')"<% IF Instr(nSavedSection, "create") <> 0 THEN %> checked="checked"<% END IF %><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF %>/><label for="create-users" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbEdit_Users" id="edit-users" value="edit" onclick="FullControlCheck('Users')"<% IF Instr(nSavedSection, "edit") <> 0 THEN %> checked="checked"<% END IF %><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF %>/><label for="edit-users" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbDelete_Users" id="delete-users" value="delete" onclick="FullControlCheck('Users')"<% IF Instr(nSavedSection, "delete") <> 0 THEN %> checked="checked"<% END IF %><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF %>/><label for="delete-users" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbView_Users" id="view-users" value="view" onclick="FullControlCheck('Users')"<% IF Instr(nSavedSection, "view") <> 0 THEN %> checked="checked"<% END IF %><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF %>/><label for="view-users" class="k-checkbox-label"></label></td>
                            </tr>
                        </table>
                    </div>
                    <div class="tabstrip-content-wrapper">
                        <h3>System Permissions</h3>
                        <table class="permission-grid">
                            <tr>
                                <td>Section</td>
                                <td></td>
                                <td>Full Control</td>
                                <td>Create</td>
                                <td>Edit</td>
                                <td>Delete</td>
                                <td>View</td>
                            </tr>
                            <%
                            Set oRSbn = Server.CreateObject("ADODB.Recordset")
                            strSQL = "SELECT [prmSystemLog] FROM [Group] WHERE [GroupId] = " & formatDbField(groupId, "int", false)
                            oRSbn.open strSQL, db
                            nSavedSection = oRSbn("prmSystemLog")
                            oRSbn.Close
                            %>
                            <tr>
                                <td>System Log</td>
                                <td id="SavingSystemLog">&nbsp;</td>
                                <td><input type="checkbox" class="k-checkbox" id="full-systemlog" name="cbFullControl_SystemLog" value="full" onclick="PermToggle('SystemLog');"<% IF Instr(nSavedSection, "full") <> 0 THEN %> checked="checked"<% END IF%>/><label for="full-systemlog" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="create-systemlog" name="cbCreate_SystemLog" value="create" onclick="FullControlCheck('SystemLog')"<% IF Instr(nSavedSection, "create") <> 0 THEN %> checked="checked"<% END IF%><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF%>/><label for="create-systemlog" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="edit-systemlog" name="cbEdit_SystemLog" value="edit" onclick="FullControlCheck('SystemLog')"<% IF Instr(nSavedSection, "edit") <> 0 THEN %> checked="checked"<% END IF%><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF%>/><label for="edit-systemlog" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="delete-systemlog" name="cbDelete_SystemLog" value="delete" onclick="FullControlCheck('SystemLog')"<% IF Instr(nSavedSection, "delete") <> 0 THEN %> checked="checked"<% END IF%><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF%>/><label for="delete-systemlog" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="view-systemlog" name="cbView_SystemLog" value="view" onclick="FullControlCheck('SystemLog')"<% IF Instr(nSavedSection, "view") <> 0 THEN %> checked="checked"<% END IF%><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF%>/><label for="view-systemlog" class="k-checkbox-label"></label></td>
                            </tr>
                            <%
                            Set oRSbn = Server.CreateObject("ADODB.Recordset")
                            strSQL = "SELECT [prmDropdowns] FROM [Group] WHERE [GroupId] = " & formatDbField(groupId, "int", false)
                            oRSbn.open strSQL, db
                            nSavedSection = oRSbn("prmDropdowns")
                            oRSbn.Close
                            %>
                            <tr>
                                <td>Dropdown Menus</td>
                                <td id="SavingDropdowns">&nbsp;</td>
                                <td><input type="checkbox" class="k-checkbox" id="full-dropdowns" name="cbFullControl_Dropdowns" value="full" onclick="PermToggle('Dropdowns');"<% IF Instr(nSavedSection, "full") <> 0 THEN %> checked="checked"<% END IF%>/><label for="full-dropdowns" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="create-dropdowns" name="cbCreate_Dropdowns" value="create" onclick="FullControlCheck('Dropdowns')"<% IF Instr(nSavedSection, "create") <> 0 THEN %> checked="checked"<% END IF%><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF%>/><label for="create-dropdowns" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="edit-dropdowns" name="cbEdit_Dropdowns" value="edit" onclick="FullControlCheck('Dropdowns')"<% IF Instr(nSavedSection, "edit") <> 0 THEN %> checked="checked"<% END IF%><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF%>/><label for="edit-dropdowns" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="delete-dropdowns" name="cbDelete_Dropdowns" value="delete" onclick="FullControlCheck('Dropdowns')"<% IF Instr(nSavedSection, "delete") <> 0 THEN %> checked="checked"<% END IF%><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF%>/><label for="delete-dropdowns" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="view-dropdowns" name="cbView_Dropdowns" value="view" onclick="FullControlCheck('Dropdowns')"<% IF Instr(nSavedSection, "view") <> 0 THEN %> checked="checked"<% END IF%><% IF Instr(nSavedSection, "full") <> 0 THEN %> disabled="disabled"<% END IF%>/><label for="view-dropdowns" class="k-checkbox-label"></label></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
<!-- #include virtual="/includes/footer.asp" -->
<!-- #include virtual="/includes/javascripts.asp" -->
<script src="/scripts/kendo_ui/js/kendo.all.min.js"></script>
<!-- #include virtual="/includes/alerts.asp" -->
<script src="/admin/groups/scripts/group_edit.js"></script>
<script type="text/javascript">
    $(function () {
        $('.item.profile').click(function () {
            document.location.href = '/profile/';
        });

        $('.item.logout').click(function () {
            document.location.href = 'logout.asp';
        });
    });
</script>
</body>

</html>
<!--#include virtual="/includes/closeconnection.asp" -->
