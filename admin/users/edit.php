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

// ### DOES THE USER HAVE USER PERMISSIONS ###
$permissionsAry = GetSectionPermission("prmUsers");
$canView = GetActionPermission("view", $permissionsAry);
$canEdit = GetActionPermission("edit", $permissionsAry);
if (!$canEdit) {
    SetUserAlert("danger", "You do not have permission to edit users.");
    header("Location: " . BASE_URL ."/admin/users/index.php");
}

// ### GET USER DATA ###
$userId = $_GET["id"];
global $db;
$userSQL = "
    SELECT
        `FirstName`, `LastName`, `EmailAddress`,
        `GroupId`, `DarkMode`
    FROM
        `User` AS u
    WHERE
        `UserId` = " . $userId . "";
$response = mysqli_query($db, $userSQL);
$row_cnt = mysqli_num_rows($response);

if ($row_cnt === 0) {
    header("Location: /admin/users/index.php");
} else {
    $userRS = mysqli_fetch_assoc($response);
    $firstName = $userRS["FirstName"];
    $lastName = $userRS["LastName"];
    $email = $userRS["EmailAddress"];
    $groupId = $userRS["GroupId"];
    $darkMode = $userRS["DarkMode"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?php echo SITE_NAME ?> - User Area</title>
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
                <h1 class="page-title">Edit User</h1>
                <div class="breadcrumb">
                    <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/users/">User Management</a><?=SPACER?>Edit User
                </div>
                <div class="add-button-wrapper">
                    <button type="button" class="primary-btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add User</button>
                </div>
                <form action="<?=BASE_URL?>/admin/users/save.php" id="user-form" method="post" name="frmNewUser">
                    <input type="hidden" name="hidUserId" id="hid-userid" value="<?=$userId?>"/>
                    <input type="hidden" name="orgEmailAddress" id="org-email-address" value="<?=$email?>"/>
                    <input type="hidden" id="form-action" value="EDIT"/>
                    <table class="form-table">
                        <tr>
                            <td><?=REQUIRED?>&nbsp;&nbsp;First Name:</td>
                            <td><input type="text" class="k-textbox" maxlength="50" style="width:250px" id="first-name" name="tbFirstName" value="<?=$firstName?>"/></td>
                        </tr>
                        <tr>
                            <td><?=REQUIRED?>&nbsp;&nbsp;Last Name:</td>
                            <td><input type="text" class="k-textbox" maxlength="50" style="width:250px" id="last-name" name="tbLastName" value="<?=$lastName?>"/></td>
                        </tr>
                        <?=ShowSectionBorder()?>
                        <tr>
                            <td><?=REQUIRED?>&nbsp;&nbsp;Email Address:</td>
                            <td><input type="text" class="k-textbox" maxlength="50" style="width:300px" id="email" name="tbEmail" value="<?=$email?>"/></td>
                        </tr>
                        <tr>
                            <td><?=REQUIRED?>&nbsp;&nbsp;Password:</td>
                            <td><input type="text" class="k-textbox" maxlength="50" style="width:200px" id="password" name="tbPassword"/></td>
                        </tr>
                        <?=ShowSectionBorder()?>
                        <tr>
                            <td><?=REQUIRED?>&nbsp;&nbsp;User Group:</td>
                            <td><?=CreateDropmenu("GroupId", "GroupName", "usergroup", "GroupName", "User-Group", "", $groupId)?></td>
                        </tr>
                        <?=ShowSectionBorder()?>
                        <tr>
                            <td><?=REQUIRED?>&nbsp;&nbsp;Site Theme:</td>
                            <td><select name="ddSiteTheme" id="site-theme">
                            <option value="0"<?php if (!$darkMode) { ?> selected="selected"<?php } ?>>Light</option>
                            <option value="1"<?php if ($darkMode) { ?> selected="selected"<?php } ?>>Dark</option>
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
    <script type="text/javascript" src="<?=BASE_URL?>/admin/users/scripts/user_addedit.js"></script>
</body>

</html>

