<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DOES THE USER HAVE ARTICLE PERMISSIONS ###
$canDelete = UserPermissions::GetUserPermission("Articles", "delete");
if (!$canDelete) {
    SystemAlert::SetPermissionAlert("delete", "articles");
    header("Location: " . BASE_URL ."/articles/index.php");
}

$articleId = $_GET["id"];

if (trim($articleId . "") <> "") {
    // ### DELETE ARTICLE RECORD ###
    mysqli_query($db, "DELETE FROM `Articles` WHERE `ArticleId` = " . formatDbField($articleId, "int", false));

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    SystemLog::LogReport(1, "Article " . $articleId . " has been deleted", $_SESSION["userId"]);
    SystemAlert::SetAlert("success", "Article deleted successfully");
}
header("Location: " . BASE_URL ."/articles/index.php");
?>