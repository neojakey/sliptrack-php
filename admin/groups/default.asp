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

' ### DOES THE USER HAVE GROUP VIEW PERMISSION ###
Dim groupsAry : groupsAry = GetSectionPermission("prmGroups")
Dim canView : canView = GetActionPermission("view", groupsAry)
Dim canEdit : canEdit = GetActionPermission("edit", groupsAry)
Dim canDelete : canDelete = GetActionPermission("delete", groupsAry)
IF NOT canView THEN
    Call SetUserAlert("danger", "You do not have permission to access groups.")
    Response.Redirect("/admin/")
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
                <h1 class="page-title">User Groups</h1>
                <div class="breadcrumb">
                    <%=ADMIN_BREADCRUMB%>User Groups
                </div>
                <div class="add-button-wrapper">
                    <button type="button" class="primary-btn" onclick="location.href='/admin/groups/add/';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add Group</button>
                </div>
                <div id="alert-wrapper" style="display:none">
                    <div id="alert">
                        <div id="alert-icon"></div>
                    </div>
                </div>
                <%
                Dim UserGroupsRS : Set UserGroupsRS = Server.CreateObject("ADODB.Recordset")
                Dim UserGroupsSQL : UserGroupsSQL = _
                    "SELECT " & _
                    "   g.[GroupId], " & _
                    "   g.[GroupName], " & _
                    "   (SELECT COUNT([UserId]) FROM [User] AS u WHERE u.[GroupId] = g.[GroupId]) AS nUsersInGroup " & _
                    " FROM " & _
                    "   [Group] AS g " & _
                    " ORDER BY " & _
                    "   g.[GroupName]"
                UserGroupsRS.open UserGroupsSQL, db
                %>
                <table class="data-grid">
                    <% IF UserGroupsRS.EOF THEN %>
                    <tbody>
                        <tr class="h30">
                            <td colspan="7" class="fb tac">No groups have been created in the system</td>
                        </tr>
                    </tbody>
                    <% ELSE %>
                    <thead>
                        <tr>
                            <th style="width:624px">Group Name</th>
                            <th style="width:172px">User Accounts in Group</th>
                            <th style="width:80px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <%
                        UserGroupsRS.MoveFirst
                        DO WHILE NOT UserGroupsRS.EOF
                            %>
                            <tr>
                                <td><a href="/admin/groups/members/?id=<%=UserGroupsRS("GroupId")%>"><%=UserGroupsRS("GroupName")%></a></td>
                                <td><%=UserGroupsRS("nUsersInGroup")%></td>
                                <td>
                                    <div class="data-grid-icons">
                                        <% IF canEdit THEN %>
                                        <a href="/admin/groups/edit/?id=<%=UserGroupsRS("GroupID")%>" title="Edit Group"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                        <a href="/admin/groups/name/?id=<%=UserGroupsRS("GroupID")%>" title="Edit Group Name"><i class="fa fa-font fa-fw" aria-hidden="true"></i></a>
                                        <% END IF %>
                                        <% IF canDelete THEN %>
                                            <% IF cToInt(UserGroupsRS("nUsersInGroup")) > 0 THEN %>
                                            <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="This group is currently assigned and cannot be deleted, reassign users before deleting"></i>
                                            <% ELSE %>
                                            <a href="javascript:void(0);" onclick="ConfirmGroupDelete('<%=UserGroupsRS("GroupID")%>');" title="Delete"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                                            <% END IF %>
                                        <% END IF %>
                                    </div>
                                </td>
                            </tr>
                            <%
                            UserGroupsRS.movenext
                        LOOP
                        END IF
                        UserGroupsRS.Close
                        %>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
    <!-- #include virtual="/includes/footer.asp" -->
    <!-- #include virtual="/includes/javascripts.asp" -->
    <!-- #include virtual="/includes/kendo_includes.asp" -->
    <!-- #include virtual="/includes/alerts.asp" -->
    <script type="text/javascript" src="/admin/groups/scripts/default.js"></script>
</body>

</html>
<!--#include virtual="/includes/closeconnection.asp" -->
