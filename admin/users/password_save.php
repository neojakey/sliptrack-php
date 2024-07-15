<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DECLARE AND SET VARIABLES ###
$password = EscapeSql($_POST["tbPassword"]);

// ### UPDATE PASSWORD FOR USER ###
User::UpdatePassword($_SESSION["userId"], $password);

// ### LOG AND CREATE USER ALERT ###
SystemLog::LogReport(1, $_SESSION["userFullName"] . " password has been changed", $_SESSION["userId"]);
SystemAlert::SetAlert("success", "User password has been updated successfully");

// ### REDIRECT USER ###
header("Location: " . BASE_URL ."/admin/users/index.php");
?>