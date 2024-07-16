<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
UserPermissions::HasAdminAccesss();

// ### DOES THE USER HAVE GROUP VIEW PERMISSION ###
$groupPermission = UserPermissions::GetSectionAccess("Groups");
if (!$groupPermission["view"]) {
    SystemAlert::SetPermissionAlert("groups", "view");
    header("Location: " . BASE_URL ."/admin/index.php");
}

// ### PAGE DECLARATIONS ###
$groupId = $_GET["id"];
$groupName = Group::GetGroupName($groupId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?=SITE_NAME?> - User Area</title>
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
            <h1 class="page-title">&#39;<?=$groupName?>&#39; Group Members</h1>
            <div class="breadcrumb">
                <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/index.php">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/groups/index.php">User Groups</a><?=SPACER?>&#39;<?=$groupName?>&#39; Group Members
            </div>
            <?php if ($groupPermission["create"]) { ?>
            <div class="add-button-wrapper">
                <button type="button" class="primary-btn" onclick="location.href='<?=BASE_URL?>/admin/groups/add.php';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add Group</button>
            </div>
            <?php } ?>
            <div id="alert-wrapper" style="display:none">
                <div id="alert">
                    <div id="alert-icon"></div>
                </div>
            </div>
            <table class="data-grid">
                <?php
                global $db;
                $UsersSQL = "
                    SELECT
                       u.`FirstName`, u.`LastName`, u.`EmailAddress`,
                       u.`Created`, u.`UserId`, ug.`GroupName`, ug.`GroupId`
                    FROM
                       `User` AS u
                       INNER JOIN `UserGroup` AS ug ON u.`GroupId` = ug.`GroupId`
                    WHERE
                       u.`GroupId` = " . $groupId . "
                    ORDER BY
                       u.`LastName` ASC, u.`FirstName` ASC";
                $response = mysqli_query($db, $UsersSQL);
                $row_cnt = mysqli_num_rows($response);
                ?>
                <thead>
                    <tr>
                        <th style="width:15%">Full Name</th>
                        <th style="width:14%">Email</th>
                        <th style="width:12%">Groups</th>
                        <th style="width:15%">Created</th>
                        <th style="width:8%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($row_cnt === 0) { ?>
                    <tr>
                        <td colspan="10" class="tac" style="height:40px">No users found in &#39;<?=$groupName?>&#39;</td>
                    </tr>
                    <?php
                    } else {
                        while($usersRS = mysqli_fetch_assoc($response)) {
                            ?>
                            <tr>
                                <td><?=$usersRS["LastName"]?>, <?=$usersRS["FirstName"]?></td>
                                <td><a href="mailto:<?=$usersRS["EmailAddress"]?>"><?=$usersRS["EmailAddress"]?></a></td>
                                <td><?=$usersRS["GroupName"]?></td>
                                <td><?=date("F j, Y, g:i a", strtotime($usersRS["Created"]))?></td>
                                <td>
                                    <div class="data-grid-icons">
                                        <?php if ($groupPermission["edit"]) { ?>
                                        <a href="<?=BASE_URL?>/admin/users/edit.php?id=<?=$usersRS["UserId"]?>" title="Edit"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                        <?php } else { ?>
                                            <?php if ($_SESSION["userId"] === $usersRS["UserId"]) { ?>
                                            <a href="<?=BASE_URL?>/admin/users/edit.php?id=<?=$usersRS["UserId"]?>" title="Edit"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                            <?php } else { ?>
                                            <i class="fa fa-pencil fa-fw disabled" aria-hidden="true" title="You do not have permission to edit"></i>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if ($groupPermission["delete"]) { ?>
                                            <?php if ($_SESSION["userId"] === $usersRS["UserId"]) { ?>
                                            <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="You can't delete your own account"></i>
                                            <?php } else { ?>
                                            <a href="javascript:void(0);" onclick="ConfirmUserDelete('<?=$usersRS["UserId"]?>');" title="Delete"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="You do not have permission to delete"></i>
                                        <?php } ?>
                                        <?php if ($_SESSION["userId"] === $usersRS["UserId"]) { ?>
                                            <a href="<?=BASE_URL?>/admin/users/password.php" title="Change Password"><i class="fa fa-shield fa-fw" aria-hidden="true"></i></a>
                                        <?php } else { ?>
                                            <i class="fa fa-shield fa-fw disabled" aria-hidden="true"></i>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
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