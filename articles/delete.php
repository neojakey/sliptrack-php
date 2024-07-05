<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DOES THE USER HAVE SOURCE PERMISSIONS ###
$permissionsAry = GetSectionPermission("prmArticles");
$canDelete = GetActionPermission("delete", $permissionsAry);
if (!$canDelete) {
    SetUserAlert("danger", "You do not have permission to delete articles.");
    header("Location: " . BASE_URL ."/articles/index.php");
}

$articleId = $_GET["id"];

if (trim($articleId . "") <> "") {
    // ### DELETE ARTICLE RECORD ###
    mysqli_query($db, "DELETE FROM `Articles` WHERE `ArticleId` = " . formatDbField($articleId, "int", false));

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "Article has been deleted", $_SESSION["userId"]);
    SetUserAlert("success", "Article deleted successfully");
}
header("Location: " . BASE_URL ."/articles/index.php");
?>