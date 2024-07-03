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

Dim dropdownListId : dropdownListId = Request("id")
Dim dropdownListName : dropdownListName = GetDropdownListName(dropdownListId)
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
                    <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>admin/dropdown-menus/">Dropdown Menus</a><?=SPACER?><a href="<?=BASE_URL?>/admin/dropdown-menus/list/?id=<%=dropdownListId%>"><%=dropdownListName%></a><?=SPACER?>Add New List Field
                </div>
                <form action="/admin/dropdown-menus/save-field/" id="dropdown-field-form" method="post">
                    <input type="hidden" name="hidParentId" value="<%=dropdownListId%>"/>
                    <table class="form-table">
                        <tr>
                            <td style="width:200px">Dropdown Field Name <?=REQUIRED?>:</td>
                            <td><input type="text" class="k-textbox" id="dropdown-field-name" name="tbDropdownFieldName" style="width:500px" maxlength="200"/></td>
                        </tr>
                        <tr>
                            <td>Dropdown Field Code <?=REQUIRED?>:</td>
                            <td><input type="text" class="k-textbox" id="dropdown-field-code" style="width:120px" name="tbDropdownFieldCode" maxlength="10"/></td>
                        </tr>
                        <tr>
                            <td>Dropdown Field Description:</td>
                            <td><textarea class="k-textbox" id="dropdown-field-description" maxlength="255" style="width:450px; height:80px; border: 1px #CCC solid" name="tbDropdownFieldDescription"></textarea></td>
                        </tr>
                    </table>
                    <div class="button-wrapper">
                        <button type="button" onclick="validate();" class="primary-btn">Submit</button>
                        <button type="button" onclick="LeavePage('/admin/dropdown-menus/list/?id=<%=dropdownListId%>');" class="cancel-btn">Cancel</button>
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
