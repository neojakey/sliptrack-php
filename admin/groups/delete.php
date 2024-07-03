<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
$adminAry = GetSectionPermission("prmAdmin");
$canViewAdmin = GetActionPermission("view", $adminAry);
if (!$canViewAdmin) {
    SetUserAlert("danger", "You do not have permission to access administration.");
    header("Location: " . BASE_URL ."/index.php");
}

// ### DOES THE USER HAVE GROUP DELETE PERMISSION ###
$permissionsAry = GetSectionPermission("prmGroups");
$canDelete = GetActionPermission("delete", $permissionsAry);
if (!$canDelete) {
    SetUserAlert("danger", "You do not have permission to delete groups.");
    header("Location: " . BASE_URL ."/admin/groups/index.php");
}

// ### GET GROUP DATA ###
$groupId = $_GET["id"];
$groupName = GetGroupName($groupId);

// ### DELETE GROUP ###
mysqli_query($db, "DELETE FROM `UserGroup` WHERE `GroupID` = " . formatDbField($groupId, "int", false));

// ### LOG AND CREATE USER ALERT ###
LogReport(1, "Group " . $groupName . " has been deleted", $_SESSION["userId"]);
SetUserAlert("success", "Group deleted successfully");

// ### REDIRECT USER ###
header("Location: " . BASE_URL ."/admin/groups/index.php");
?>