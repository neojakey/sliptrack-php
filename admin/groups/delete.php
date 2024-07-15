<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
UserPermissions::HasAdminAccesss();

// ### DOES THE USER HAVE GROUP DELETE PERMISSION ###
$canDelete = UserPermissions::GetUserPermission("Groups", "delete");
if (!$canDelete) {
    SystemAlert::SetPermissionAlert("groups", "delete");
    header("Location: " . BASE_URL ."/admin/groups/index.php");
}

// ### GET GROUP DATA ###
$groupId = $_GET["id"];
$groupName = Group::GetGroupName($groupId);

// ### DELETE GROUP ###
Group::DeleteGroup($groupId);

// ### LOG AND CREATE USER ALERT ###
SystemAlert::SetAlert("danger", "Group deleted successfully");
SystemLog::LogReport(1, "Group " . $groupName . " has been deleted", $_SESSION["userId"]);

// ### REDIRECT USER ###
header("Location: " . BASE_URL ."/admin/groups/index.php");
?>