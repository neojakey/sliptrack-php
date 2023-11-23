<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/functions_security.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<%
' ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
Dim adminAry : adminAry = GetSectionPermission("prmAdmin")
Dim canViewAdmin : canViewAdmin = GetActionPermission("view", adminAry)
IF NOT canViewAdmin THEN
    Call SetUserAlert("danger", "You do not have permission to access administration.")
    Response.Redirect("/")
END IF

' ### DOES THE USER HAVE GROUP ADD PERMISSION ###
Dim groupsAry : groupsAry = GetSectionPermission("prmGroups")
Dim canAdd : canAdd = GetActionPermission("create", groupsAry)
IF NOT canAdd THEN
    Call SetUserAlert("danger", "You do not have permission to add groups.")
    Response.Redirect("/admin/groups/")
END IF
%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><%=SITE_NAME%> - Group Area</title>
    <!--#include virtual="/includes/stylesheets.asp" -->
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
                <h1 class="page-title">Add New Group</h1>
                <div class="breadcrumb">
                    <%=ADMIN_BREADCRUMB%><a href="/admin/groups/">User Groups</a><%=SPACER%>Add New Group
                </div>
                <form action="/admin/groups/save/" method="post" id="form-new-group" name="frmNewGroup">
                    <table class="form-table">
                        <tr>
                            <td>Group Name <%=Application("REQUIRED")%>:</td>
                            <td><input type="text" class="k-textbox" name="tbGroupName" id="group-name" maxlength="50" style="width:400px"/></td>
                        </tr>
                    </table>
                    <div class="button-wrapper">
                        <button type="button" onclick="validate();" class="primary-btn">Submit</button>
                        <button type="button" onclick="LeavePage('/groups/');" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <!-- #include virtual="/includes/footer.asp" -->
    <!-- #include virtual="/includes/javascripts.asp" -->
    <!-- #include virtual="/includes/kendo_includes.asp" -->
    <!-- #include virtual="/includes/alerts.asp" -->
    <script type="text/javascript" src="/admin/groups/scripts/group_add.js"></script>
</body>

</html>
<!--#include virtual="/includes/closeconnection.asp" -->
