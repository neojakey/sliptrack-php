<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DECLARE AND SET VARIABLES ###
$password = EscapeSql($_POST["tbPassword"]);

// ### UPDATE PASSWORD FOR USER ###
$strSQL = "
    UPDATE `User` SET
        `Password` = " . formatDbField(password_hash($password, PASSWORD_DEFAULT), "text", false) . "
    WHERE
        `UserId` = " . formatDbField($_SESSION["userId"], "int", false);
mysqli_query($db, $strSQL);

// ### LOG AND CREATE USER ALERT ###
LogReport(1, $_SESSION["userFullName"] . " password has been changed", $_SESSION["userId"]);
SetUserAlert("success", "User password has been updated successfully");

// ### REDIRECT USER ###
header("Location: " . BASE_URL ."/admin/users/index.php");
?>