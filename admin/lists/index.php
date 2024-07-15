<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
UserPermissions::HasAdminAccesss();

// ### DOES THE USER HAVE LIST VIEW PERMISSION ###
$listPermission = UserPermissions::GetSectionAccess("Lists");
if (!$listPermission["view"]) {
    SystemAlert::SetPermissionAlert("lists", "view");
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
                <h1 class="page-title">Lists</h1>
                <div class="breadcrumb">
                    <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;Lists
                </div>
                <?php if ($listPermission["create"]) { ?>
                <div class="add-button-wrapper">
                    <button type="button" class="primary-btn" onclick="location.href='<?=BASE_URL?>/admin/lists/add_list.php';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add List</button>
                </div>
                <?php } ?>
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
                        <?php
                        global $db;
                        $listsSQL = "
                            SELECT
                               ls.`ListId`,
                               ls.`ListName`,
                               ls.`ListCode`,
                               (SELECT COUNT(`ListItemId`) FROM `ListItems` WHERE `ListId` = ls.`ListId`) AS entries
                            FROM
                               `List` AS ls
                            ORDER BY
                               ls.`ListName` ASC
                        ";
                        $response = mysqli_query($db, $listsSQL);
                        $row_cnt = mysqli_num_rows($response);
                        ?>
                        <?php if ($row_cnt === 0) { ?>
                            <tr class="h30">
                                <td colspan="4" class="fb tac">No Lists have been Created</td>
                            </tr>
                            <?php
                        } else {
                            while($ListRS = mysqli_fetch_assoc($response)) {
                            ?>
                            <tr>
                                <td><a href="<?=BASE_URL?>/admin/lists/list-items.php?id=<?=$ListRS["ListId"]?>" title="Manage List"><?=$ListRS["ListName"]?></a></td>
                                <td><?=$ListRS["ListCode"]?></td>
                                <td><?=$ListRS["entries"]?></td>
                                <td>
                                    <div class="data-grid-icons">
                                        <?php if ($listPermission["edit"]) { ?>
                                            <a href="<?=BASE_URL?>/admin/lists/edit_list.php?id=<?=$ListRS["ListId"]?>" title="Edit"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                        <?php } ?>
                                        <?php if (intval($ListRS["entries"]) > 0) { ?>
                                            <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="This list is in use and cannot be deleted"></i>
                                        <?php } else { ?>
                                            <?php if ($listPermission["delete"]) { ?>
                                                <a href="javascript:ConfirmDelete('<?=$ListRS["ListId"]?>');" title="Delete"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                                            <?php } else { ?>
                                                <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="You do not have permission to delete this list"></i>
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
</body>

</html>