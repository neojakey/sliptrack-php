<?php include "includes/functions.php" ?>
<?php include "includes/common.php" ?>
<?php
if (!$_SESSION["loggedIn"]) {
    header("Location: login.php");
} else {
    LogReport(5, $_SESSION["userFullName"] . " has logged out", $_SESSION["userId"]);
    session_unset();
    session_destroy();

    header("Location: login.php");
}
?>