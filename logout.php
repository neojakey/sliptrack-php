<?php require_once("includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
if (!$_SESSION["loggedIn"]) {
    header("Location: " . BASE_URL . "/login.php");
} else {
    LogReport(5, $_SESSION["userFullName"] . " has logged out", $_SESSION["userId"]);
    session_unset();
    session_destroy();
    header("Location: " . BASE_URL . "/login.php");
}
?>