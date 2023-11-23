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

' ### DOES THE USER HAVE USER PERMISSIONS ###
Dim permissionsAry : permissionsAry = GetSectionPermission("prmUsers")
Dim canView : canView = GetActionPermission("view", permissionsAry)
Dim canAdd : canAdd = GetActionPermission("create", permissionsAry)
Dim canEdit : canEdit = GetActionPermission("edit", permissionsAry)
Dim canDelete : canDelete = GetActionPermission("delete", permissionsAry)
IF NOT canView THEN Response.Redirect("/admin/")
%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><%=SITE_NAME%> - User Area</title>
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
            <h1 class="page-title">User Management</h1>
            <div class="breadcrumb">
                <%=ADMIN_BREADCRUMB%>User Management
            </div>
            <div class="add-button-wrapper">
                <button type="button" class="primary-btn" onclick="location.href='/admin/users/add/';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add User</button>
            </div>
            <div id="alert-wrapper" style="display:none">
                <div id="alert">
                    <div id="alert-icon"></div>
                </div>
            </div>
            <table class="data-grid">
                <thead>
                    <%
                    Dim UsersRS : Set UsersRS = Server.CreateObject("ADODB.Recordset")
                    Dim UsersSQL : UsersSQL = _
                        "SELECT " & _
                        "   u.[FirstName], u.[LastName], u.[EmailAddress], " & _
                        "   u.[Created], u.[UserId], ug.[GroupName], ug.[GroupId] " & _
                        " FROM " & _
                        "   [User] AS u " & _
                        "   INNER JOIN [Group] AS ug ON u.[GroupId] = ug.[GroupId] " & _
                        " ORDER BY " & _
                        "   u.[LastName] ASC, u.[FirstName] ASC"
                    UsersRS.open UsersSQL, db
                    %>
                    <tr>
                        <th style="width:19%">Full Name</th>
                        <th style="width:14%">Email</th>
                        <th style="width:8%">Groups</th>
                        <th style="width:15%">Created</th>
                        <th style="width:8%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <% IF UsersRS.EOF THEN %>
                    <tr>
                        <td colspan="10" class="tac" style="height:40px">
                            <% IF Trim(userSearchString & "") <> "" THEN %>
                                No results found for &#39;<%=userSearchString%>&#39;
                            <% ELSE %>
                                No Users Found
                            <% END IF %>
                        </td>
                    </tr>
                    <%
                    ELSE
                        DO WHILE NOT UsersRS.EOF
                            %>
                            <tr>
                                <td><%=UsersRS("LastName") & ", " & UsersRS("FirstName")%></td>
                                <td><a href="mailto:<%=UsersRS("EmailAddress")%>"><%=UsersRS("EmailAddress")%></a></td>
                                <td><a href="/admin/groups/members/?id=<%=UsersRS("GroupId")%>"><%=UsersRS("GroupName")%></a></td>
                                <td><%=HowLongAgo(UsersRS("Created"))%></td>
                                <td>
                                    <div class="data-grid-icons">
                                        <% IF canEdit THEN %>
                                        <a href="/admin/users/edit/?id=<%=UsersRS("UserId")%>" title="Edit"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                        <% ELSE %>
                                            <% IF cInt(Session("userId")) = cInt(UsersRS("UserId")) THEN %>
                                            <a href="/admin/users/edit/?id=<%=UsersRS("UserId")%>" title="Edit"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                            <% ELSE %>
                                            <i class="fa fa-pencil fa-fw disabled" aria-hidden="true" title="You do not have permission to edit"></i>
                                            <% END IF %>
                                        <% END IF %>
                                        <% IF canDelete THEN %>
                                            <% IF cInt(Session("userId")) = cInt(UsersRS("UserId")) THEN %>
                                            <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="You can't delete your own account"></i>
                                            <% ELSE %>
                                            <a href="javascript:void(0);" onclick="ConfirmUserDelete('<%=UsersRS("UserId")%>');" title="Delete"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                                            <% END IF %>
                                        <% ELSE %>
                                            <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="You do not have permission to delete"></i>
                                        <% END IF %>
                                        <% IF cInt(Session("userId")) = cInt(UsersRS("UserId")) THEN %>
                                            <a href="/admin/users/password/" title="Change Password"><i class="fa fa-shield fa-fw" aria-hidden="true"></i></a>
                                        <% ELSE %>
                                            <i class="fa fa-shield fa-fw disabled" aria-hidden="true"></i>
                                        <% END IF %>
                                    </div>
                                </td>
                            </tr>
                            <%
                            UsersRS.movenext
                        LOOP
                    END IF
                    UsersRS.Close
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
<script type="text/javascript">
    $(function () {
        $('.item.profile').click(function () {
            document.location.href = '/profile/';
        });

        $('.item.logout').click(function () {
            document.location.href = 'logout.asp';
        });
    });

    function ConfirmUserDelete(userId) {
        var agree = confirm('Are you sure you wish to delete this user?\n');
        if (agree) {
            document.location.href = '/admin/users/delete/?id=' + userId;
        }
    }
</script>
</body>

</html>
<!--#include virtual="/includes/closeconnection.asp" -->
