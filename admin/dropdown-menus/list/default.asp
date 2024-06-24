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
Dim canAdd : canAdd = GetActionPermission("create", dropdownAry)
Dim canDelete : canDelete = GetActionPermission("delete", dropdownAry)
Dim canEdit : canEdit = GetActionPermission("edit", dropdownAry)
IF NOT canView THEN
    Call SetUserAlert("danger", "You do not have permission to view dropdown menus.")
    Response.Redirect("/admin/")
END IF

' ### PAGE DECLARATIONS ###
Dim dropdownListId : dropdownListId = Request("id")
Dim dropdownListName : dropdownListName = GetDropdownListName(dropdownListId)
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
                <h1 class="page-title">Dropdown Menus</h1>
                <div class="breadcrumb">
                    <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="/admin/dropdown-menus/">Dropdown Menus</a><%=SPACER%><%=dropdownListName%>
                </div>
                <% IF canAdd THEN %>
                <div class="add-button-wrapper">
                    <button type="button" class="primary-btn" onclick="location.href='/admin/dropdown-menus/add-field/?id=<%=dropdownListId%>';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add Dropdown Field</button>
                </div>
                <% END IF %>
                <div id="alert-wrapper" style="display:none">
                    <div id="alert">
                        <div id="alert-icon"></div>
                    </div>
                </div>
                <table class="data-grid">
                    <thead>
                        <tr>
                            <th style="width:5%">Order</th>
                            <th style="width:10%">Code</th>
                            <th style="width:75%">Name</th>
                            <th style="width:10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <%
                        Dim dropdownFieldsRS : Set dropdownFieldsRS = Server.CreateObject("ADODB.Recordset")
                        Dim dropdownFieldsSQL : dropdownFieldsSQL = _
                            "SELECT " & _
                            "   DropdownFieldId, " & _
                            "   DropdownFieldName, " & _
                            "   DropdownFieldCode, " & _
                            "   DropdownOrder " & _
                            "FROM " & _
                            "   DropDownFields " & _
                            "WHERE " & _
                            "   DropDownParentId = " & formatDbField(dropdownListId, "int", false) & " " & _
                            "ORDER BY " & _
                            "   DropdownOrder;"
                        dropdownFieldsRS.open dropdownFieldsSQL, db, adOpenStatic, adCmdText
                        IF dropdownFieldsRS.EOF THEN
                            %>
                            <tr class="h30">
                                <td colspan="4" class="fb tac">No Dropdown Fields have been Created with '<%=dropdownListName%>'</td>
                            </tr>
                            <%
                        ELSE
                            Dim loopCount : loopCount = 1
                            Dim numberOfRecords : numberOfRecords = cToInt(dropdownFieldsRS.RecordCount)
                            dropdownFieldsRS.MoveFirst
                            DO WHILE NOT dropdownFieldsRS.EOF
                                %>
                                <tr>
                                    <td><%=dropdownFieldsRS("DropdownOrder")%></td>
                                    <td><%=dropdownFieldsRS("DropdownFieldCode")%></td>
                                    <td><%=dropdownFieldsRS("DropdownFieldName")%></td>
                                    <td>
                                        <div class="data-grid-icons">
                                            <% IF canEdit THEN %>
                                                <a href="/admin/dropdown-menus/edit-field/?id=<%=dropdownFieldsRS("DropdownFieldId")%>" title="Edit"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                            <% END IF %>
                                            <% IF canDelete THEN %>
                                                <% IF 1 = 1 THEN 'dropdownFieldsRS("count") > 0 THEN %>
                                                    <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="This list is in use and cannot be deleted"></i>
                                                <% ELSE %>
                                                    <a href="javascript:ConfirmDelete('<%=dropdownFieldsRS("DropdownFieldId")%>');" title="Delete"><img src="/images/icons/cross.png" alt=""/></a>
                                                <% END IF %>
                                            <% ELSE %>
                                                <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="You do not have permission to delete this dropdown menu item"></i>
                                            <% END IF %>
                                        </div>
                                    </td>
                                </tr>
                                <%
                                dropdownFieldsRS.movenext
                                loopCount = loopCount + 1
                            LOOP
                        END IF
                        dropdownFieldsRS.Close
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

