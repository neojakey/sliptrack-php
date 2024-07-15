<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
UserPermissions::HasAdminAccesss();

// ### DOES THE USER HAVE LIST EDIT PERMISSION ###
$canEdit = UserPermissions::GetUserPermission("Articles", "edit");
if (!$canEdit) {
    SystemAlert::SetPermissionAlert("articles", "edit");
    header("Location: " . BASE_URL ."/admin/lists/index.php");
}

// ### GET LIST ###
$listId = $_GET["id"];
global $db;

$listSQL = "SELECT `ListName`, `ListCode` FROM `List` WHERE `ListId` = " . formatDbField($listId, "int", false) . "";
$response = mysqli_query($db, $listSQL);
$row_cnt = mysqli_num_rows($response);

if ($row_cnt === 0) {
    SystemAlert::SetAlert("The List was not Found..!", "info");
    header("Location: " . BASE_URL ."/admin/lists/index.php");
} else {
    $listRS = mysqli_fetch_assoc($response);
    $listName = $listRS["ListName"];
    $listCode = $listRS["ListCode"];
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
                <h1 class="page-title">Add New List</h1>
                <div class="breadcrumb">
                    <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/lists/index.php">Lists</a><?=SPACER?>Add List
                </div>
                <form action="<?=BASE_URL?>/admin/lists/save_list.php" id="list-form" method="post">
                    <input type="hidden" name="hidListId" value="<?=$listId?>"/>
                    <table class="form-table">
                        <tr>
                            <td>List Name <?=REQUIRED?>:</td>
                            <td><input type="text" class="k-textbox" name="tbListName" id="list-name" value="<?=$listName?>" maxlength="50" style="width:250px"/></td>
                        </tr>
                        <tr>
                            <td>List Code <?=REQUIRED?>:</td>
                            <td><input type="text" class="k-textbox" name="tbListCode" id="list-code" value="<?=$listCode?>" maxlength="10"/></td>
                        </tr>
                    </table>
                    <div class="button-wrapper">
                        <button type="button" onclick="validate();" class="primary-btn">Submit</button>
                        <button type="button" onclick="LeavePage('<?=BASE_URL?>/admin/lists/index.php');" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <?php include ROOT_PATH . "includes/footer.php" ?>
    <?php include ROOT_PATH . "includes/javascripts.php" ?>
    <?php include ROOT_PATH . "includes/kendo_includes.php" ?>
    <?php include ROOT_PATH . "includes/alerts.php" ?>
    <script type="text/javascript" src="<?=BASE_URL?>/admin/lists/scripts/list_addedit.js"></script>
</body>

</html>

