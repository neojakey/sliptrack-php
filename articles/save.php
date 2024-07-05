<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DEFINE AND ASSIGN VARIABLES ###
$articleId = EscapeSql($_POST["hidArticleId"]);
$articleTitle = EscapeSql($_POST["tbArticleTitle"]);
$articleUrl = EscapeSql($_POST["tbArticleUrl"]);
$articleImageUrl = EscapeSql($_POST["tbArticleImageUrl"]);
$articleSourceId = EscapeSql($_POST["ddSource"]);

if (trim($articleId . "") !== "") {
    // ### UPDATE ARTICLE RECORD ###
    $strSQL = "
        UPDATE `Articles` SET
           `ArticleTitle` = " . formatDbField($articleTitle, "text", false) . ",
           `ArticleUrl` = " . formatDbField($articleUrl, "text", false) . ",
           `ArticleImageUrl` = " . formatDbField($articleImageUrl, "text", true) . ",
           `ArticleSourceId` = " . formatDbField($articleSourceId, "int", false) . "
        WHERE 
           `ArticleId` = " . formatDbField($articleId, "int", false) . "
    ";
    mysqli_query($db, $strSQL);

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "The Article has been edited", $_SESSION["userId"]);
    SetUserAlert("success", "Article edited successfully");
} else {
    // ### INSERT ARTICLE RECORD ###
    $articleColumns = "ArticleTitle,ArticleUrl,ArticleImageUrl,ArticleSourceId";
    $articleValues = formatDbField($articleTitle, "text", false) . ",
                " . formatDbField($articleUrl, "text", false) . ",
                " . formatDbField($articleImageUrl, "text", true) . ",
                " . formatDbField($articleSourceId, "int", false);
    InsertNewRecord("Articles", $articleColumns, $articleValues);

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "The Article has been added", $_SESSION["userId"]);
    SetUserAlert("success", "Article added successfully");
}

header("Location: " . BASE_URL ."/articles/index.php");
?>