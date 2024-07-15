<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### INITIALIZE VARIABLES ###
$articleId = EscapeSql($_POST["hidArticleId"]);
$articleTitle = EscapeSql($_POST["tbArticleTitle"]);
$articleUrl = EscapeSql($_POST["tbArticleUrl"]);
$articleImageUrl = EscapeSql($_POST["tbArticleImageUrl"]);
$articleSourceId = EscapeSql($_POST["ddSource"]);
$alertAction = ($articleId !== "") ? "edited" : "created";

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
} else {
    // ### CREATE ARTICLE RECORD ###
    $articleColumns = "ArticleTitle,ArticleUrl,ArticleImageUrl,ArticleSourceId";
    $articleValues = formatDbField($articleTitle, "text", false) . ",
                " . formatDbField($articleUrl, "text", false) . ",
                " . formatDbField($articleImageUrl, "text", true) . ",
                " . formatDbField($articleSourceId, "int", false);
    InsertNewRecord("Articles", $articleColumns, $articleValues);
}

// ### ADD TO SYSTEM LOG AND USER ALERT ###
SystemLog::LogReport(1, "The Article has been " . $alertAction, $_SESSION["userId"]);
SystemAlert::SetAlert("success", "Article " . $alertAction . " successfully");

header("Location: " . BASE_URL ."/articles/index.php");
?>