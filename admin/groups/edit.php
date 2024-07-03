<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
$adminAry = GetSectionPermission("prmAdmin");
$canViewAdmin = GetActionPermission("view", $adminAry);
if (!$canViewAdmin) {
    SetUserAlert("danger", "You do not have permission to access administration.");
    header("Location: " . BASE_URL ."/index.php");
}

// ### DOES THE USER HAVE GROUP PERMISSIONS ###
$permissionsAry = GetSectionPermission("prmGroups");
$canEdit = GetActionPermission("edit", $permissionsAry);
if (!$canEdit) {
    SetUserAlert("danger", "You do not have permission to edit groups.");
    header("Location: " . BASE_URL ."/admin/groups/index.php");
}

// ### PAGE DECLARATIONS ###
$groupId = $_GET["id"];
$groupName = GetGroupName($groupId);

global $db;
$sectionsSQL = "
    SELECT
        `prmAdmin`, `prmGroups`, `prmUsers`, `prmSystemLog`, `prmLists`
    FROM
        `UserGroup`
    WHERE
        `GroupId` = " . formatDbField($groupId, "int", false) . "";
$response = mysqli_query($db, $sectionsSQL);
$row_cnt = mysqli_num_rows($response);
if ($row_cnt !== 0) {
    $row = mysqli_fetch_assoc($response);
    $adminSection = $row["prmAdmin"];
    $groupsSection = $row["prmGroups"];
    $usersSection = $row["prmUsers"];
    $systemLogSection = $row["prmSystemLog"];
    $listsSection = $row["prmLists"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?=SITE_NAME?> - User Area</title>
    <?php include ROOT_PATH . "includes/stylesheets.php" ?>
    <link rel="stylesheet" href="<?=BASE_URL?>/admin/groups/css/group_edit.css"/>
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
            <h1 class="page-title">&#39;<?=$groupName?>&#39; Group Members</h1>
            <div class="breadcrumb">
                <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/groups/index.php">User Groups</a><?=SPACER?>&#39;<?=$groupName?>&#39; Group Permissions
            </div>
            <div class="add-button-wrapper">
                <button type="button" class="primary-btn" onclick="location.href='<?=BASE_URL?>/admin/groups/add.php';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add Group</button>
            </div>
            <div id="alert-wrapper" style="display:none">
                <div id="alert">
                    <div id="alert-icon"></div>
                </div>
            </div>
            <form action="#" method="post" name="PermissionsEdit">
                <input type="hidden" name="hidGroupId" id="hid-group-id" value="<?=$groupId?>"/>
                <div id="tabstrip">
                    <ul>
                        <li class="k-state-active">Account</li>
                        <li>System</li>
                    </ul>
                    <div class="tabstrip-content-wrapper">
                        <h3>Account Permissions</h3>
                        <table class="permission-grid">
                            <tr>
                                <td>Section</td>
                                <td></td>
                                <td>Full Control</td>
                                <td>Create</td>
                                <td>Edit</td>
                                <td>Delete</td>
                                <td>View</td>
                            </tr>
                            <tr>
                                <td>Administrators--<?=$listsSection?></td>
                                <td id="SavingAdmin">&nbsp;</td>
                                <td id="cbFullControl_Admin"><input type="checkbox" class="k-checkbox" name="cbFullControl_Admin" id="full-admin" value="full" onclick="PermToggle('Admin')"<?php if ($adminSection === "full") { ?> checked="checked"<?php } ?>/><label for="full-admin" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="create-admin" name="cbCreate_Admin" value="create" onclick="FullControlCheck('Admin')"<?php if ($adminSection === "full" || str_contains($adminSection, "create")) { ?> checked="checked"<?php } ?><?php if (strpos($adminSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="create-admin" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="edit-admin" name="cbEdit_Admin" value="edit" onclick="FullControlCheck('Admin')"<?php if ($adminSection === "full" || str_contains($adminSection, "edit")) { ?> checked="checked"<?php } ?><?php if (strpos($adminSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="edit-admin" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="delete-admin" name="cbDelete_Admin" value="delete" onclick="FullControlCheck('Admin')"<?php if ($adminSection === "full" || str_contains($adminSection, "delete")) { ?> checked="checked"<?php } ?><?php if (strpos($adminSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="delete-admin" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="view-admin" name="cbView_Admin" value="view" onclick="FullControlCheck('Admin')"<?php if ($adminSection === "full" || str_contains($adminSection, "view")) { ?> checked="checked"<?php } ?><?php if (strpos($adminSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="view-admin" class="k-checkbox-label"></label></td>
                            </tr>
                            <tr>
                                <td class="pl">Groups</td>
                                <td id="SavingGroups">&nbsp;</td>
                                <td><input type="checkbox" class="k-checkbox" name="cbFullControl_Groups" id="full-groups" value="full" onclick="PermToggle('Groups');"<?php if ($groupsSection === "full") { ?> checked="checked"<?php } ?>/><label for="full-groups" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbCreate_Groups" id="create-groups" value="create" onclick="FullControlCheck('Groups')"<?php if ($groupsSection === "full" || str_contains($groupsSection, "create")) { ?> checked="checked"<?php } ?><?php if (strpos($groupsSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="create-groups" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbEdit_Groups" id="edit-groups" value="edit" onclick="FullControlCheck('Groups')"<?php if ($groupsSection === "full" || str_contains($groupsSection, "edit")) { ?> checked="checked"<?php } ?><?php if (strpos($groupsSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="edit-groups" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbDelete_Groups" id="delete-groups" value="delete" onclick="FullControlCheck('Groups')"<?php if ($groupsSection === "full" || str_contains($groupsSection, "delete")) { ?> checked="checked"<?php } ?><?php if (strpos($groupsSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="delete-groups" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbView_Groups" id="view-groups" value="view" onclick="FullControlCheck('Groups')"<?php if ($groupsSection === "full" || str_contains($groupsSection, "view")) { ?> checked="checked"<?php } ?><?php if (strpos($groupsSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="view-groups" class="k-checkbox-label"></label></td>
                            </tr>
                            <tr>
                                <td>Users</td>
                                <td id="SavingUsers">&nbsp;</td>
                                <td id="cbFullControl_Users"><input type="checkbox" class="k-checkbox" name="cbFullControl_Users" id="full-users" value="full" onclick="PermToggle('Users')"<?php if ($usersSection === "full") { ?> checked="checked"<?php } ?>/><label for="full-users" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbCreate_Users" id="create-users" value="create" onclick="FullControlCheck('Users')"<?php if ($usersSection === "full" || str_contains($usersSection, "create")) { ?> checked="checked"<?php } ?><?php if (strpos($usersSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="create-users" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbEdit_Users" id="edit-users" value="edit" onclick="FullControlCheck('Users')"<?php if ($usersSection === "full" || str_contains($usersSection, "edit")) { ?> checked="checked"<?php } ?><?php if (strpos($usersSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="edit-users" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbDelete_Users" id="delete-users" value="delete" onclick="FullControlCheck('Users')"<?php if ($usersSection === "full" || str_contains($usersSection, "delete")) { ?> checked="checked"<?php } ?><?php if (strpos($usersSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="delete-users" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" name="cbView_Users" id="view-users" value="view" onclick="FullControlCheck('Users')"<?php if ($usersSection === "full" || str_contains($usersSection, "view")) { ?> checked="checked"<?php } ?><?php if (strpos($usersSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="view-users" class="k-checkbox-label"></label></td>
                            </tr>
                        </table>
                    </div>
                    <div class="tabstrip-content-wrapper">
                        <h3>System Permissions</h3>
                        <table class="permission-grid">
                            <tr>
                                <td>Section</td>
                                <td></td>
                                <td>Full Control</td>
                                <td>Create</td>
                                <td>Edit</td>
                                <td>Delete</td>
                                <td>View</td>
                            </tr>
                            <tr>
                                <td>System Log</td>
                                <td id="SavingSystemLog">&nbsp;</td>
                                <td><input type="checkbox" class="k-checkbox" id="full-systemlog" name="cbFullControl_SystemLog" value="full" onclick="PermToggle('SystemLog');"<?php if ($systemLogSection === "full") { ?> checked="checked"<?php } ?>/><label for="full-systemlog" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="create-systemlog" name="cbCreate_SystemLog" value="create" onclick="FullControlCheck('SystemLog')"<?php if ($systemLogSection === "full" || str_contains($systemLogSection, "create")) { ?> checked="checked"<?php } ?><?php if (strpos($systemLogSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="create-systemlog" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="edit-systemlog" name="cbEdit_SystemLog" value="edit" onclick="FullControlCheck('SystemLog')"<?php if ($systemLogSection === "full" || str_contains($systemLogSection, "edit")) { ?> checked="checked"<?php } ?><?php if (strpos($systemLogSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="edit-systemlog" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="delete-systemlog" name="cbDelete_SystemLog" value="delete" onclick="FullControlCheck('SystemLog')"<?php if ($systemLogSection === "full" || str_contains($systemLogSection, "delete")) { ?> checked="checked"<?php } ?><?php if (strpos($systemLogSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="delete-systemlog" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="view-systemlog" name="cbView_SystemLog" value="view" onclick="FullControlCheck('SystemLog')"<?php if ($systemLogSection === "full" || str_contains($systemLogSection, "view")) { ?> checked="checked"<?php } ?><?php if (strpos($systemLogSection, "full") !== 0) { ?> disabled="disabled"<?php } ?>/><label for="view-systemlog" class="k-checkbox-label"></label></td>
                            </tr>
                            <tr>
                                <td>Lists</td>
                                <td id="SavingLists">&nbsp;</td>
                                <td><input type="checkbox" class="k-checkbox" id="full-lists" name="cbFullControl_Lists" value="full" onclick="PermToggle('Lists');"<?php if ($listsSection === "full") { ?> checked="checked"<?php } ?>/><label for="full-lists" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="create-lists" name="cbCreate_Lists" value="create" onclick="FullControlCheck('Lists')"<?php if ($listsSection === "full" || str_contains($listsSection, "create")) { ?> checked="checked"<?php } ?><?php if ($listsSection === "full") { ?> disabled="disabled"<?php } ?>/><label for="create-lists" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="edit-lists" name="cbEdit_Lists" value="edit" onclick="FullControlCheck('Lists')"<?php if ($listsSection === "full" || str_contains($listsSection, "edit")) { ?> checked="checked"<?php } ?><?php if ($listsSection === "full") { ?> disabled="disabled"<?php } ?>/><label for="edit-lists" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="delete-lists" name="cbDelete_Lists" value="delete" onclick="FullControlCheck('Lists')"<?php if ($listsSection === "full" || str_contains($listsSection, "delete")) { ?> checked="checked"<?php } ?><?php if ($listsSection === "full") { ?> disabled="disabled"<?php } ?>/><label for="delete-lists" class="k-checkbox-label"></label></td>
                                <td><input type="checkbox" class="k-checkbox" id="view-lists" name="cbView_Lists" value="view" onclick="FullControlCheck('Lists')"<?php if ($listsSection === "full" || str_contains($listsSection, "view")) { ?> checked="checked"<?php } ?><?php if ($listsSection === "full") { ?> disabled="disabled"<?php } ?>/><label for="view-lists" class="k-checkbox-label"></label></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
<?php include ROOT_PATH . "includes/footer.php" ?>
<?php include ROOT_PATH . "includes/javascripts.php" ?>
<script src="<?=BASE_URL?>/scripts/kendo_ui/js/kendo.all.min.js"></script>
<?php include ROOT_PATH . "includes/alerts.php" ?>
<script src="<?=BASE_URL?>/admin/groups/scripts/group_edit.js"></script>
</body>

</html>
