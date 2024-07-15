<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### INITIALIZE VARIABLES ###
$groupId = EscapeSql($_POST["hidGroupId"]);
$groupName = EscapeSql($_POST["tbGroupName"]);

// ### SET UP USER ALERT ###
$alertAction = ($groupId !== "") ? "edited" : "created";

if ($groupId !== "") {
    // ### MODIFY GROUP DATABASE RECORD ###
    Group::UpdateGroup($groupId, $groupName);
} else {
    // ### INSERT GROUP DATABASE RECORD ###
    Group::CreateGroup($groupName);
}

// ### ADD TO SYSTEM LOG AND USER ALERT ###
SystemLog::LogReport(1, "Group " . $groupName . " has been " . $alertAction, $_SESSION["userId"]);
SystemAlert::SetAlert("success", "Group " . $groupName . " has been " . $alertAction . " successfully..!");

// ### REDIRECT USER ###
header("Location: " . BASE_URL ."/admin/groups/index.php");
?>

