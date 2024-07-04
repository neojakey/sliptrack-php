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

// ### DOES THE USER HAVE GROUP VIEW PERMISSION ###
$groupsAry = GetSectionPermission("prmGroups");
$canView = GetActionPermission("view", $groupsAry);
$canEdit = GetActionPermission("edit", $groupsAry);
$canDelete = GetActionPermission("delete", $groupsAry);
if (!$canView) {
    SetUserAlert("danger", "You do not have permission to access groups.");
    header("Location: " . BASE_URL ."/admin/index.php");
}
?>
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
                <h1 class="page-title">User Groups</h1>
                <div class="breadcrumb">
                    <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;User Groups
                </div>
                <div class="add-button-wrapper">
                    <button type="button" class="primary-btn" onclick="location.href='<?=BASE_URL?>/admin/groups/add.php';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add Group</button>
                </div>
                <div id="alert-wrapper" style="display:none">
                    <div id="alert">
                        <div id="alert-icon"></div>
                    </div>
                </div>
                <?php
                global $db;
                $userGroupsSQL = "
                    SELECT
                       g.`GroupId`,
                       g.`GroupName`,
                       (SELECT COUNT(`UserId`) FROM `User` AS u WHERE u.`GroupId` = g.`GroupId`) AS nUsersInGroup
                     FROM
                       `usergroup` AS g
                     ORDER BY
                       g.`GroupName`";
                $response = mysqli_query($db, $userGroupsSQL);
                $row_cnt = mysqli_num_rows($response);
                ?>
                <table class="data-grid">
                    <thead>
                        <tr>
                            <th style="width:624px">Group Name</th>
                            <th style="width:172px">User Accounts in Group</th>
                            <th style="width:80px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($row_cnt === 0) { ?>
                        <tr class="h30">
                            <td colspan="7" class="fb tac">No groups have been created in the system</td>
                        </tr>
                        <?php
                    } else {
                        while($userGroupsRS = mysqli_fetch_assoc($response)) {
                            ?>
                                <tr>
                                    <td><a href="<?=BASE_URL?>/admin/groups/members/?id=<?=$userGroupsRS["GroupId"]?>"><?=$userGroupsRS["GroupName"]?></a></td>
                                    <td><?=$userGroupsRS["nUsersInGroup"]?></td>
                                    <td>
                                        <div class="data-grid-icons">
                                            <?php if ($canEdit) { ?>
                                                <a href="<?=BASE_URL?>/admin/groups/edit.php?id=<?=$userGroupsRS["GroupId"]?>" title="Edit Group"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                                <a href="<?=BASE_URL?>/admin/groups/name.php?id=<?=$userGroupsRS["GroupId"]?>" title="Edit Group Name"><i class="fa fa-font fa-fw" aria-hidden="true"></i></a>
                                            <?php } ?>
                                            <?php if ($canDelete) { ?>
                                                <?php if (intval($userGroupsRS["nUsersInGroup"]) > 0) { ?>
                                                    <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="This group is currently assigned and cannot be deleted, reassign users before deleting"></i>
                                                <?php } else { ?>
                                                    <a href="javascript:void(0);" onclick="ConfirmGroupDelete('<?=$userGroupsRS["GroupId"]?>');" title="Delete"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                                                <?php } ?>
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
    <script type="text/javascript" src="<?=BASE_URL?>/admin/groups/scripts/default.js"></script>
</body>

</html>