<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
// ### DOES THE USER HAVE SOURCE PERMISSIONS ###
$canCreate = UserPermissions::GetUserPermission("Articles", "create");
if (!$canCreate) {
    SystemAlert::SetPermissionAlert("articles", "create");
    header("Location: " . BASE_URL ."/articles/index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?=SITE_NAME?> - Articles Area</title>
    <?php include ROOT_PATH . "includes/stylesheets.php" ?>
    <link rel="stylesheet" type="text/css" href="<?=BASE_URL?>/articles/css/article_addedit.css"/>
</head>

<body>
    <div id="page-wrapper">
        <div class="menu">
            <?php include ROOT_PATH . "includes/menu.php" ?>
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
                <h1 class="page-title">Add New Article</h1>
                <div class="breadcrumb">
                    <a href="<?=BASE_URL?>/index.php">Home</a><?=SPACER?><a href="<?=BASE_URL?>/articles/index.php">Articles</a><?=SPACER?>Add New Article
                </div>
                <form action="<?=BASE_URL?>/articles/save.php" method="post" id="form-new-article" name="frmNewArticle">
                    <input type="hidden" name="hidArticleId" value=""/>
                    <table class="form-table">
                        <tr>
                            <td>Article Url <?=REQUIRED?>:</td>
                            <td><input type="text" class="k-textbox" name="tbArticleUrl" id="article-url" maxlength="500" style="width:600px"/></td>
                        </tr>
                        <?=ShowSectionBorder()?>
                        <tr>
                            <td>Article Title <?=REQUIRED?>:</td>
                            <td><input type="text" class="k-textbox" name="tbArticleTitle" id="article-title" maxlength="100" style="width:600px"/></td>
                        </tr>
                        <tr>
                            <td>Article Image Url:</td>
                            <td><input type="text" class="k-textbox" name="tbArticleImageUrl" id="article-imageurl" maxlength="500" style="width:600px"/></td>
                        </tr>
                        <?=ShowSectionBorder()?>
                        <tr>
                            <td>Article Source <?=REQUIRED?>:</td>
                            <td class="source-wrapper"><?=CreateDropmenu("SourceId", "SourceName", "Sources", "", "Source", "", "")?></td>
                        </tr>
                    </table>
                    <div class="button-wrapper">
                        <button type="button" onclick="validate();" class="primary-btn">Submit</button>
                        <button type="button" onclick="LeavePage('/receipts/');" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <?php include ROOT_PATH . "includes/footer.php" ?>
    <?php include ROOT_PATH . "includes/javascripts.php" ?>
    <?php include ROOT_PATH . "includes/kendo_includes.php" ?>
    <?php include ROOT_PATH . "includes/alerts.php" ?>
    <script type="text/javascript" src="<?=BASE_URL?>/articles/scripts/article_addedit.js"></script>
</body>

</html>

