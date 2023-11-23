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

' ### DOES THE USER HAVE DROPDOWN ADD PERMISSION ###
Dim dropdownAry : dropdownAry = GetSectionPermission("prmDropdowns")
Dim canAdd : canAdd = GetActionPermission("create", dropdownAry)
IF NOT canAdd THEN
    Call SetUserAlert("danger", "You do not have permission to add dropdown menus.")
    Response.Redirect("/admin/dropdown-menus/")
END IF
%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><%=SITE_NAME%> - Dropdown Area</title>
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
                <h1 class="page-title">Add New Dropdown Menu</h1>
                <div class="breadcrumb">
                    <%=ADMIN_BREADCRUMB%><a href="/admin/dropdown-menus/">Dropdown Menus</a><%=SPACER%>Add Dropdown Menu
                </div>
                <form action="/admin/dropdown-menus/save/" id="dropdownlist-form" method="post">
                    <table class="form-table">
                        <tr>
                            <td>Dropdown List Name <%=Application("REQUIRED")%>:</td>
                            <td><input type="text" class="k-textbox" id="dropdown-list-name" style="width:250px" name="tbDropdownListName" maxlength="50"/></td>
                        </tr>
                        <% Dim blockCode : blockCode = GetRandom(10) %>
                        <tr>
                            <td>Dropdown Code:</td>
                            <td><%=blockCode%><input type="hidden" name="tbDropdownCode" id="dropdown-code" value="<%=blockCode%>"/></td>
                        </tr>
                    </table>
                    <div class="button-wrapper">
                        <button type="button" onclick="validate();" class="primary-btn">Submit</button>
                        <button type="button" onclick="LeavePage('/admin/dropdown-menus/');" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <!-- #include virtual="/includes/footer.asp" -->
    <!-- #include virtual="/includes/javascripts.asp" -->
    <!-- #include virtual="/includes/kendo_includes.asp" -->
    <!-- #include virtual="/includes/alerts.asp" -->
    <script type="text/javascript" src="/admin/dropdown-menus/scripts/dropdownlist_addedit.js"></script>
</body>

</html>
