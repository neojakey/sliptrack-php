<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
UserPermissions::HasAdminAccesss();

// ### DOES THE USER HAVE LIST EDIT PERMISSION ###
$canEdit = UserPermissions::GetUserPermission("Lists", "edit");
if (!$canEdit) {
    SystemAlert::SetPermissionAlert("lists", "edit");
    header("Location: " . BASE_URL ."/admin/lists/index.php");
}

// ### GET LIST ITEM ###
$listItemId = $_GET["id"];
global $db;

$listItemSQL = "
    SELECT
       `ListItemName`,
       `ListItemCode`,
       `ListItemDescription`,
       `ListId`
    FROM
       `ListItems`
    WHERE
       `ListItemId` = " . formatDbField($listItemId, "int", false) . "
";
$response = mysqli_query($db, $listItemSQL);
$row_cnt = mysqli_num_rows($response);

if ($row_cnt === 0) {
    SystemAlert::SetAlert("The List Item was not Found..!", "info");
    header("Location: " . BASE_URL ."/admin/lists/index.php");
} else {
    $listItemRS = mysqli_fetch_assoc($response);
    $listItemName = $listItemRS["ListItemName"];
    $listItemCode = $listItemRS["ListItemCode"];
    $listId = $listItemRS["ListId"];
    $listItemDescription = $listItemRS["ListItemDescription"];
}

$listName = GetListName($listId);
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
                <h1 class="page-title">Edit List Item</h1>
                <div class="breadcrumb">
                    <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/lists/index.php">Lists</a><?=SPACER?><a href="<?=BASE_URL?>/admin/lists/list-items.php?id=<?=$listId?>"><?=$listName?></a><?=SPACER?>Edit List Item
                </div>
                <form action="<?=BASE_URL?>/admin/lists/save_item.php" id="item-form" method="post">
                    <input type="hidden" name="hidListItemId" value="<?=$listItemId?>"/>
                    <input type="hidden" name="hidListId" value="<?=$listId?>"/>
                    <table class="form-table">
                        <tr>
                            <td>Item Name <?=REQUIRED?>:</td>
                            <td><input type="text" class="k-textbox" id="item-name" name="tbItemName" value="<?=$listItemName?>" style="width:500px" maxlength="200"/></td>
                        </tr>
                        <tr>
                            <td>Item Code <?=REQUIRED?>:</td>
                            <td><input type="text" class="k-textbox" id="item-code" style="width:120px" name="tbItemCode" value="<?=$listItemCode?>" maxlength="10"/></td>
                        </tr>
                        <tr>
                            <td>Item Description:</td>
                            <td><textarea class="k-textbox" id="item-description" maxlength="255" style="width:450px; height:100px; border: 1px #CCC solid" name="tbItemDescription"><?=$listItemDescription?></textarea></td>
                        </tr>
                    </table>
                    <div class="button-wrapper">
                        <button type="button" onclick="validate();" class="primary-btn">Submit</button>
                        <button type="button" onclick="LeavePage('<?=BASE_URL?>/admin/lists/list-items.php?id=<?=$listId?>');" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <?php include ROOT_PATH . "includes/footer.php" ?>
    <?php include ROOT_PATH . "includes/javascripts.php" ?>
    <?php include ROOT_PATH . "includes/kendo_includes.php" ?>
    <?php include ROOT_PATH . "includes/alerts.php" ?>
    <script type="text/javascript" src="<?=BASE_URL?>/admin/lists/scripts/listitem_addedit.js"></script>
</body>

</html>
