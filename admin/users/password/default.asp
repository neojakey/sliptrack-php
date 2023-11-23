<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
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
                <h1 class="page-title">Change User Password</h1>
                <div class="breadcrumb">
                    <%=ADMIN_BREADCRUMB%><a href="/admin/users/">User Management</a><%=SPACER%>Change User Password
                </div>
                <div class="add-button-wrapper">
                    <button type="button" class="primary-btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add User</button>
                </div>
                <form action="/admin/users/password-save/" method="post" id="change-password-form" name="frmChangePassword">
                    <table class="form-table">
                        <tr>
                            <td>User Name:</td>
                            <td class="fb"><%=Session("userFullName")%></td>
                        </tr>
                        <tr>
                            <td>Password <%=Application("REQUIRED")%>:</td>
                            <td><input type="password" id="password-1" name="tbPassword" class="k-textbox" style="width: 400px"/></td>
                        </tr>
                        <tr>
                            <td>Repeat Password <%=Application("REQUIRED")%>:</td>
                            <td><input type="password" id="password-2" name="tbPassword2" class="k-textbox" style="width: 400px"/></td>
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
    <!-- #include virtual="/includes/footer.asp" -->
    <!-- #include virtual="/includes/javascripts.asp" -->
    <!-- #include virtual="/includes/kendo_includes.asp" -->
    <!-- #include virtual="/includes/alerts.asp" -->
    <script type="text/javascript" src="/users/scripts/user_password.js"></script>
</body>

</html>
<!--#include virtual="/includes/closeconnection.asp" -->
