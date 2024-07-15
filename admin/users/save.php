<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### INITIALIZE VARIABLES ###
$userId = EscapeSql($_POST["hidUserId"]);
$firstName = EscapeSql($_POST["tbFirstName"]);
$lastName = EscapeSql($_POST["tbLastName"]);
$email = EscapeSql($_POST["tbEmail"]);
$groupId = EscapeSql($_POST["ddUserGroup"]);
$darkMode = EscapeSql($_POST["ddSiteTheme"]);
$username = EscapeSql(strtolower(substr($firstName, 0, 1) . $lastName));
$password = EscapeSql($_POST["tbPassword"]);

// ### SET UP USER ALERT ###
$alertAction = ($userId !== "") ? "edited" : "created";

if ($userId . "" !== "") {
    // ### UPDATE USER ###
    User::UpdateUser($userId, $firstName, $lastName, $email, $groupId, $password, $darkMode);
} else {
    // ### CREATE USER ###
    User::CreateUser($username, $firstName, $lastName, $email, $password, $groupId, $darkMode);
}

// ### ADD TO SYSTEM LOG AND USER ALERT ###
SystemLog::LogReport(1, "User " . $firstName . " " . $lastName . " has been " . $alertAction, $_SESSION["userId"]);
SystemAlert::SetAlert("success", "User " . $firstName . " " . $lastName . " " . $alertAction . " successfully");

// ### REDIRECT USER ###
header("Location: " . BASE_URL ."/admin/users/index.php");
?>