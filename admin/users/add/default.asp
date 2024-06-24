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

' ### DOES THE USER HAVE USER ADD PERMISSION ###
Dim usersAry : usersAry = GetSectionPermission("prmUsers")
Dim canAdd : canAdd = GetActionPermission("create", usersAry)
IF NOT canAdd THEN
    Call SetUserAlert("danger", "You do not have permission to add users.")
    Response.Redirect("/admin/users/")
END IF
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
            <?php include ROOT_PATH . "includes/menu_admin.php" ?>
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
                <h1 class="page-title">Add New User</h1>
                <div class="breadcrumb">
                    <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="/admin/users/">User Management</a><%=SPACER%>Add User
                </div>
                <div class="add-button-wrapper">
                    <button type="button" class="primary-btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add User</button>
                </div>
                <form action="/admin/users/save/" id="user-form" method="post" name="frmNewUser">
                    <input type="hidden" name="hidUserId" id="hid-userid" value=""/>
                    <input type="hidden" name="orgEmailAddress" id="org-email-address" value=""/>
                    <input type="hidden" id="form-action" value="ADD"/>
                    <table class="form-table">
                        <tr>
                            <td><%=Application("REQUIRED")%>&nbsp;&nbsp;First Name:</td>
                            <td><input type="text" class="k-textbox" maxlength="50" style="width:250px" id="first-name" name="tbFirstName"/></td>
                        </tr>
                        <tr>
                            <td><%=Application("REQUIRED")%>&nbsp;&nbsp;Last Name:</td>
                            <td><input type="text" class="k-textbox" maxlength="50" style="width:250px" id="last-name" name="tbLastName"/></td>
                        </tr>
                        <%=ShowSectionBorder()%>
                        <tr>
                            <td><%=Application("REQUIRED")%>&nbsp;&nbsp;Email Address:</td>
                            <td><input type="text" class="k-textbox" maxlength="50" style="width:300px" id="email" name="tbEmail"/></td>
                        </tr>
                        <tr>
                            <td><%=Application("REQUIRED")%>&nbsp;&nbsp;Password:</td>
                            <td><input type="text" class="k-textbox" maxlength="50" style="width:200px" id="password" name="tbPassword"/></td>
                        </tr>
                        <%=ShowSectionBorder()%>
                        <tr>
                            <td><%=Application("REQUIRED")%>&nbsp;&nbsp;User Group:</td>
                            <td><%=CreateDropmenu("GroupId", "GroupName", "Group", "GroupName", "User-Group", "", "")%></td>
                        </tr>
                        <%=ShowSectionBorder()%>
                        <tr>
                            <td>Site Theme:</td>
                            <td><select name="ddSiteTheme" id="site-theme">
                            <option value="0">Light</option>
                            <option value="1">Dark</option>
                            </select></td>
                        </tr>
                    </table>
                    <div class="button-wrapper">
                        <button type="button" onclick="validate();" class="primary-btn">Submit</button>
                        <button type="button" onclick="LeavePage('/users/');" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <?php include ROOT_PATH . "includes/footer.php" ?>
    <?php include ROOT_PATH . "includes/javascripts.php" ?>
    <?php include ROOT_PATH . "includes/kendo_includes.php" ?>
    <?php include ROOT_PATH . "includes/alerts.php" ?>
    <script type="text/javascript" src="/admin/users/scripts/user_addedit.js"></script>
</body>

</html>

