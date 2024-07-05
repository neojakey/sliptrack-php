<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DOES THE USER HAVE SOURCE PERMISSIONS ###
$permissionsAry = GetSectionPermission("prmSources");
$canDelete = GetActionPermission("delete", $permissionsAry);
if (!$canDelete) {
    SetUserAlert("danger", "You do not have permission to delete sources.");
    header("Location: " . BASE_URL ."/sources/index.php");
}

$sourceId = $_GET["id"];

if (trim($sourceId . "") !== "") {
    // ### DELETE ISSUER RECORD ###
    mysqli_query($db, "DELETE FROM `Sources` WHERE `SourceId` = " . formatDbField($sourceId, "int", false));

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "Source has been deleted", $_SESSION["userId"]);
    SetUserAlert("success", "Issuer deleted successfully");
}
header("Location: " . BASE_URL ."/sources/index.php");
?>