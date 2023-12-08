<?php include "../../includes/functions.php" ?>
<?php include "../../includes/functions_security.php" ?>
<?php include "../../includes/common.php" ?>
<?php
// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
$adminAry = GetSectionPermission("prmAdmin");
$canViewAdmin = GetActionPermission("view", $adminAry);
if (!$canViewAdmin) {
    SetUserAlert("danger", "You do not have permission to access administration.");
    header("Location: login.php");
}

// ### DOES THE USER HAVE USER PERMISSIONS ###
$permissionsAry = GetSectionPermission("prmUsers");
$canView = GetActionPermission("view", $permissionsAry);
$canAdd = GetActionPermission("create", $permissionsAry);
$canEdit = GetActionPermission("edit", $permissionsAry);
$canDelete = GetActionPermission("delete", $permissionsAry);
if (!$canView) { header("Location: /admin/index.php"); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?php echo SITE_NAME ?> - User Area</title>
    <?php include "../../includes/stylesheets.php" ?>
</head>

<body>

<div id="page-wrapper">
    <div class="menu">
        <?php include "../../includes/menu_admin.php" ?>
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
            <h1 class="page-title">User Management</h1>
            <div class="breadcrumb">
                <?=ADMIN_BREADCRUMB?>User Management
            </div>
            <div class="add-button-wrapper">
                <button type="button" class="primary-btn" onclick="location.href='/admin/users/add/';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add User</button>
            </div>
            <div id="alert-wrapper" style="display:none">
                <div id="alert">
                    <div id="alert-icon"></div>
                </div>
            </div>
            <table class="data-grid">
                <thead>
                    <?php
                    global $db;
                    $usersSQL = <<<SQL
                        SELECT
                           u.`FirstName`, u.`LastName`, u.`EmailAddress`,
                           u.`Created`, u.`UserId`, ug.`GroupName`, ug.`GroupId`
                         FROM
                           `User` AS u
                           INNER JOIN `userGroup` AS ug ON u.`GroupId` = ug.`GroupId`
                         ORDER BY
                           u.`LastName` ASC, u.`FirstName` ASC
                    SQL;
                    $response = mysqli_query($db, $usersSQL);
                    $row_cnt = mysqli_num_rows($response);
                    ?>
                    <tr>
                        <th style="width:19%">Full Name</th>
                        <th style="width:14%">Email</th>
                        <th style="width:8%">Groups</th>
                        <th style="width:15%">Created</th>
                        <th style="width:8%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($row_cnt === 0) { ?>
                    <tr>
                        <td colspan="10" class="tac" style="height:40px">No Users Found</td>
                    </tr>
                    <?php
                    } else {
                        while($usersRS = mysqli_fetch_assoc($response)) {
                            ?>
                            <tr>
                                <td><?=$usersRS["LastName"] . ", " . $usersRS["FirstName"]?></td>
                                <td><a href="mailto:<?=$usersRS["EmailAddress"]?>"><?=$usersRS["EmailAddress"]?></a></td>
                                <td><a href="/admin/groups/members/?id=<?=$usersRS["GroupId"]?>"><?=$usersRS["GroupName"]?></a></td>
                                <td><?=HowLongAgo($usersRS["Created"])?></td>
                                <td>
                                    <div class="data-grid-icons">
                                        <?php
                                        if ($canEdit) {
                                            echo "<a href=\"/admin/users/edit/?id=" . $usersRS["UserId"] . "\" title=\"Edit\"><i class=\"fa fa-pencil fa-fw\" aria-hidden=\"true\"></i></a>";
                                        } else {
                                            if (intval($_SESSION["userId"]) == intval($usersRS["UserId"])) {
                                                echo "<a href=\"/admin/users/edit/?id=" . $usersRS["UserId"] . "\" title=\"Edit\"><i class=\"fa fa-pencil fa-fw\" aria-hidden=\"true\"></i></a>";
                                            } else {
                                                echo "<i class=\"fa fa-pencil fa-fw disabled\" aria-hidden=\"true\" title=\"You do not have permission to edit\"></i>";
                                            }
                                        }
                                        if ($canDelete) {
                                            if (intval($_SESSION["userId"]) == intval($usersRS["UserId"])) {
                                                echo "<i class=\"fa fa-times fa-fw disabled\" aria-hidden=\"true\" title=\"You can't delete your own account\"></i>";
                                            } else {
                                                echo "<a href=\"javascript:void(0);\" onclick=\"ConfirmUserDelete('" . $usersRS("UserId") . "');\" title=\"Delete\"><i class=\"fa fa-times fa-fw\" aria-hidden=\"true\"></i></a>";
                                            }
                                        } else {
                                            echo "<i class=\"fa fa-times fa-fw disabled\" aria-hidden=\"true\" title=\"You do not have permission to delete\"></i>";
                                        }
                                        if (intval($_SESSION["userId"]) == intval($usersRS["UserId"])) {
                                            echo "<a href=\"/admin/users/password/\" title=\"Change Password\"><i class=\"fa fa-shield fa-fw\" aria-hidden=\"true\"></i></a>";
                                        } else {
                                            echo "<i class=\"fa fa-shield fa-fw disabled\" aria-hidden=\"true\"></i>";
                                        }
                                        ?>
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
<?php include "../../includes/footer.php" ?>
<?php include "../../includes/javascripts.php" ?>
<?php include "../../includes/kendo_includes.php" ?>
<?php include "../../includes/alerts.php" ?>
<script type="text/javascript">
    $(function () {
        $('.item.profile').click(function () {
            document.location.href = '/profile/index.php';
        });

        $('.item.logout').click(function () {
            document.location.href = '/logout.php';
        });
    });

    function ConfirmUserDelete(userId) {
        var agree = confirm('Are you sure you wish to delete this user?\n');
        if (agree) {
            document.location.href = '/admin/users/delete/?id=' + userId;
        }
    }
</script>
</body>

</html>
<!--#include virtual="/includes/closeconnection.asp" -->
