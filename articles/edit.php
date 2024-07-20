<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
// ### DOES THE USER HAVE SOURCE PERMISSIONS ###
$canEdit = UserPermissions::GetUserPermission("Articles", "edit");
if (!$canEdit) {
    SystemAlert::SetPermissionAlert("articles", "edit");
    header("Location: " . BASE_URL ."/articles/index.php");
}

$articleId = $_GET["id"];

// ### REDIRECT IF NO ID PASSED ###
if (!isset($articleId)) {
    NoValidRecordPassed("articles");
}

global $db;

$articleSQL = "
    SELECT
       `ArticleTitle`,
       `ArticleUrl`,
       `ArticleImageUrl`,
       `ArticleSourceId`
    FROM
       `Articles`
    WHERE
       `ArticleId` = " . formatDbField($articleId, "int", false) . "
";
$response = mysqli_query($db, $articleSQL);
$row_cnt = mysqli_num_rows($response);
if ($row_cnt === 0) {
    RecordNotFound("articles");
} else {
    $row = mysqli_fetch_assoc($response);
    $articleTitle = $row["ArticleTitle"];
    $articleUrl = $row["ArticleUrl"];
    $articleImageUrl = $row["ArticleImageUrl"];
    $articleSourceId = $row["ArticleSourceId"];

    // ### GET ARTICLE KEYWORDS ###
    $articleKeywordsSQL = "SELECT `ListItemId` FROM `ArticleKeyword` WHERE `ArticleId` = " . formatDbField($articleId, "int", false);
    $articleKeywords = mysqli_query($db, $articleKeywordsSQL);
    $keywordsArray = array();
    while($row = mysqli_fetch_assoc($articleKeywords)) {
        $keywordsArray[] = $row["ListItemId"];
    }

    // ### GET ALL KEYWORDS ###
    $keywordsSQL = "
        SELECT
           `ListItemId`,
           `ListItemName`
        FROM
           `ListItems` AS li
           INNER JOIN `List` AS l  ON li.`ListId` = l.`ListId`
        WHERE
           l.`ListCode` = " . formatDbField('yT1wOCJPK7', "text", false) . "
    ";
    $responseKeywords = mysqli_query($db, $keywordsSQL);
    $keywords_cnt = mysqli_num_rows($responseKeywords);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?=SITE_NAME?> - Receipt Area</title>
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
                <h1 class="page-title">Edit Article</h1>
                <div class="breadcrumb">
                <a href="<?=BASE_URL?>/index.php">Home</a><?=SPACER?><a href="<?=BASE_URL?>/articles/index.php">Articles</a><?=SPACER?>Edit Article
                </div>
                <form action="<?=BASE_URL?>/articles/save.php" method="post" id="form-new-article" name="frmNewArticle">
                    <input type="hidden" name="hidArticleId" value="<?=$articleId?>"/>
                    <input type="hidden" name="hidSaveKeywords" id="save-keywords" value=""/>
                    <table class="form-table">
                        <tr>
                            <td>Article Title <?=REQUIRED?>:</td>
                            <td><input type="text" class="k-textbox" value="<?=$articleTitle?>" name="tbArticleTitle" id="article-title" maxlength="100" style="width:600px"/></td>
                        </tr>
                        <tr>
                            <td>Article Url <?=REQUIRED?>:</td>
                            <td><input type="text" class="k-textbox" value="<?=$articleUrl?>" name="tbArticleUrl" id="article-url" maxlength="500" style="width:600px"/></td>
                        </tr>
                        <tr>
                            <td>Article Image Url:</td>
                            <td><input type="text" class="k-textbox" value="<?=$articleImageUrl?>" name="tbArticleImageUrl" id="article-imageurl" maxlength="500" style="width:600px"/></td>
                        </tr>
                        <?=ShowSectionBorder()?>
                        <tr>
                            <td>Article Source <?=REQUIRED?>:</td>
                            <td class="source-wrapper"><?=CreateDropmenu("SourceId", "SourceName", "Sources", "", "Source", "", $articleSourceId)?></td>
                        </tr>
                        <?php if ($keywords_cnt > 0) : ?>
                        <tr>
                            <td>Article Keywords:</td>
                            <td class="keyword-wrapper">
                                <?php while($articlesKeywordRS = mysqli_fetch_assoc($responseKeywords)) : ?>
                                    <?php if (in_array($articlesKeywordRS["ListItemId"], $keywordsArray)) : ?>
                                        <span data-listid="<?=$articlesKeywordRS["ListItemId"]?>" class="selected"><?=$articlesKeywordRS["ListItemName"]?></span>
                                    <?php else : ?>
                                        <span data-listid="<?=$articlesKeywordRS["ListItemId"]?>"><?=$articlesKeywordRS["ListItemName"]?></span>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    <div class="button-wrapper">
                        <button type="button" onclick="validate();" class="primary-btn">Submit</button>
                        <button type="button" onclick="LeavePage('<?=BASE_URL?>/articles/index.php');" class="cancel-btn">Cancel</button>
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

