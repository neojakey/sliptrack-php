<?php include "includes/functions.php" ?>
<?php include "includes/common.php" ?>
<?php
if (!$_SESSION["loggedIn"]) {
    header("Location: login.php");
} else {
    //Call LogReport(5, Session("userFullName") & " has logged out", Session("userId"))
    session_unset();
    session_destroy();

    header("Location: login.php");
}
?>