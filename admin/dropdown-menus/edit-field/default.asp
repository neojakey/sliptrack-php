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

' ### DOES THE USER HAVE DROPDOWN EDIT PERMISSION ###
Dim dropdownAry : dropdownAry = GetSectionPermission("prmDropdowns")
Dim canEdit : canEdit = GetActionPermission("edit", dropdownAry)
IF NOT canEdit THEN
    Call SetUserAlert("danger", "You do not have permission to edit dropdown menus.")
    Response.Redirect("/admin/")
END IF

' ### GET DROPDOWN FIELD ###
Dim dropdownFieldId : dropdownFieldId = Request("id")
Dim dropdownFieldRS : Set dropdownFieldRS = Server.CreateObject("ADODB.Recordset")
Dim dropdownFieldSQL : dropdownFieldSQL = _
    "SELECT " & _
    "   DropdownFieldName, " & _
    "   DropdownFieldCode, " & _
    "   DropdownFieldDescription, " & _
    "   DropDownParentId " & _
    "FROM " & _
    "   DropDownFields " & _
    "WHERE " & _
    "   DropdownFieldId = " & formatDbField(dropdownFieldId, "int", false)
dropdownFieldRS.open dropdownFieldSQL, db
IF dropdownFieldRS.EOF THEN
    Session("hasAlert") = true
    Session("alertType") = "info"
    Session("alertMessage") = "Dropdown Field was not Found..!"
    Response.Redirect("/admin/dropdown-menus/")
ELSE
    Dim dropdownFieldName : dropdownFieldName = dropdownFieldRS("DropdownFieldName")
    Dim dropdownFieldCode : dropdownFieldCode = dropdownFieldRS("DropdownFieldCode")
    Dim dropdownFieldParentId : dropdownFieldParentId = dropdownFieldRS("DropDownParentId")
    Dim dropdownFieldDescription : dropdownFieldDescription = dropdownFieldRS("DropdownFieldDescription")
END IF
dropdownFieldRS.Close

Dim dropdownListName : dropdownListName = GetDropdownListName(dropdownFieldParentId)
%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?=SITE_NAME?> - Group Area</title>
    <?php include ROOT_PATH . "includes/stylesheets.php" ?>
    <style type="text/css">
        .button-wrapper {
            padding: 12px 12px 12px 212px;
        }
    </style>
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
                <h1 class="page-title">Add New Dropdown Menu Field</h1>
                <div class="breadcrumb">
                    <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="/admin/dropdown-menus/">Dropdown Menus</a><?=SPACER?><a href="/admin/dropdown-menus/list/?id=<%=dropdownListId%>"><%=dropdownListName%></a><?=SPACER?>Edit Dropdown Field
                </div>
                <form action="/admin/dropdown-menus/save-field/" id="dropdown-field-form" method="post">
                    <input type="hidden" name="hidDropdownFieldId" value="<%=dropdownFieldId%>"/>
                    <input type="hidden" name="hidParentId" value="<%=dropdownFieldParentId%>"/>
                    <table class="form-table">
                        <tr>
                            <td>Dropdown Field Name <?=REQUIRED?>:</td>
                            <td><input type="text" class="k-textbox" id="dropdown-field-name" name="tbDropdownFieldName" value="<%=dropdownFieldName%>" style="width:500px" maxlength="200"/></td>
                        </tr>
                        <tr>
                            <td>Dropdown Field Code <?=REQUIRED?>:</td>
                            <td><input type="text" class="k-textbox" id="dropdown-field-code" style="width:120px" name="tbDropdownFieldCode" value="<%=dropdownFieldCode%>" maxlength="10"/></td>
                        </tr>
                        <tr>
                            <td>Dropdown Field Description:</td>
                            <td><textarea class="k-textbox" id="dropdown-field-description" maxlength="255" style="width:450px; height:80px; border: 1px #CCC solid" name="tbDropdownFieldDescription"><%=dropdownFieldDescription%></textarea></td>
                        </tr>
                    </table>
                    <div class="button-wrapper">
                        <button type="button" onclick="validate();" class="primary-btn">Submit</button>
                        <button type="button" onclick="LeavePage('/admin/dropdown-menus/list/?id=<%=dropdownFieldParentId%>');" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <?php include ROOT_PATH . "includes/footer.php" ?>
    <?php include ROOT_PATH . "includes/javascripts.php" ?>
    <?php include ROOT_PATH . "includes/kendo_includes.php" ?>
    <?php include ROOT_PATH . "includes/alerts.php" ?>
    <script type="text/javascript" src="/admin/dropdown-menus/scripts/dropdownfield_addedit.js"></script>
</body>

</html>
