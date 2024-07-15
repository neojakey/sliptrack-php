<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DOES THE USER HAVE SOURCE PERMISSIONS ###
$canDelete = UserPermissions::GetUserPermission("Groups", "delete");
if (!$canDelete) {
    SystemAlert::SetPermissionAlert("delete", "sources");
    header("Location: " . BASE_URL ."/sources/index.php");
}

$sourceId = $_GET["id"];

if (trim($sourceId . "") !== "") {
    // ### DELETE ISSUER RECORD ###
    mysqli_query($db, "DELETE FROM `Sources` WHERE `SourceId` = " . formatDbField($sourceId, "int", false));

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    SystemLog::LogReport(1, "Source has been deleted", $_SESSION["userId"]);
    SystemAlert::SetAlert("success", "Source deleted successfully");
}
header("Location: " . BASE_URL ."/sources/index.php");
?>