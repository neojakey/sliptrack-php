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

' ### DOES THE USER HAVE DROPDOWN VIEW PERMISSION ###
Dim dropdownAry : dropdownAry = GetSectionPermission("prmDropdowns")
Dim canView : canView = GetActionPermission("view", dropdownAry)
IF NOT canView THEN
    Call SetUserAlert("danger", "You do not have permission to view dropdown menus.")
    Response.Redirect("/admin/")
END IF
%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?=SITE_NAME?> - Group Area</title>
    <?php include ROOT_PATH . "includes/stylesheets.php" ?>
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
                    <span id="desktop-user-menu-name"><?=$_SESSION["userFullName"]?></span>
                    <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                </div>
            </header>
            <section>
                <h1 class="page-title">Dropdown Menus</h1>
                <div class="breadcrumb">
                    <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;Dropdown Menus
                </div>
                <div class="add-button-wrapper">
                    <button type="button" class="primary-btn" onclick="location.href='/admin/dropdown-menus/add-list/';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add Dropdown</button>
                </div>
                <div id="alert-wrapper" style="display:none">
                    <div id="alert">
                        <div id="alert-icon"></div>
                    </div>
                </div>
                <table class="data-grid">
                    <thead>
                        <tr>
                            <th style="width:50%">Name</th>
                            <th style="width:30%">Code</th>
                            <th style="width:10%">Entries</th>
                            <th style="width:10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <%
                        Dim dropdownListRS : Set dropdownListRS = Server.CreateObject("ADODB.Recordset")
                        Dim dropdownListSQL : dropdownListSQL = _
                            "SELECT " & _
                            "   ddp.DropDownParentId, " & _
                            "   ddp.DropDownParentName, " & _
                            "   ddp.DropDownCode, " & _
                            "   (SELECT COUNT(DropDownParentId) FROM DropDownFields WHERE DropDownParentId = ddp.DropDownParentId) AS entries " & _
                            "FROM " & _
                            "   DropDownParent AS ddp " & _
                            "ORDER BY " & _
                            "   DropDownParentName;"
                        dropdownListRS.open dropdownListSQL, db
                        %>
                        <% IF dropdownListRS.EOF THEN %>
                            <tr class="h30">
                                <td colspan="4" class="fb tac">No Dropdown Lists have been Created</td>
                            </tr>
                            <%
                        ELSE
                            dropdownListRS.MoveFirst
                            DO WHILE NOT dropdownListRS.EOF
                            %>
                            <tr>
                                <td><a href="/admin/dropdown-menus/list/?id=<%=dropdownListRS("DropDownParentId")%>" title="Manage List"><%=dropdownListRS("DropDownParentName")%></a></td>
                                <td><%=dropdownListRS("DropDownCode")%></td>
                                <td><%=dropdownListRS("entries")%></td>
                                <td>
                                    <div class="data-grid-icons">
                                        <% IF canEdit THEN %>
                                            <a href="dropdownlist_edit.asp?id=<%=dropdownListRS("DropDownParentId")%>" title="Edit"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                        <% END IF %>
                                        <% IF cToInt(dropdownListRS("entries")) > 0 THEN %>
                                            <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="This list is in use and cannot be deleted"></i>
                                        <% ELSE %>
                                            <% IF canDelete THEN %>
                                                <a href="javascript:ConfirmDelete('<%=dropdownListRS("DropDownParentId")%>');" title="Delete"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                                            <% ELSE %>
                                                <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="You do not have permission to delete this dropdown menu"></i>
                                            <% END IF %>
                                        <% END IF %>
                                    </div>
                                </td>
                            </tr>
                            <%
                            dropdownListRS.movenext
                            LOOP
                        END IF
                        dropdownListRS.Close
                        %>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
    <?php include ROOT_PATH . "includes/footer.php" ?>
    <?php include ROOT_PATH . "includes/javascripts.php" ?>
    <?php include ROOT_PATH . "includes/kendo_includes.php" ?>
    <?php include ROOT_PATH . "includes/alerts.php" ?>
</body>

</html>

