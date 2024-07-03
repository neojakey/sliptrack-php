<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php"; ?>
<?php include ROOT_PATH . "includes/functions_security.php"; ?>
<?php include ROOT_PATH . "includes/common.php"; ?>
<?php
global $db;
$doesExist = "NOT-EXIST";

$emailAddress = mysqli_real_escape_string($db, $_GET["value"]);
$userId = mysqli_real_escape_string($db, $_GET["id"]);

if ($userId == "") { // ### NEW USER, NO EXISTING USERID
    $checkSQL = "SELECT `UserId` FROM `User` WHERE `EmailAddress` = '" . $emailAddress . "';";
} else { // ### EXISTING USER WITH USERID
    $checkSQL = "SELECT `UserId` FROM `User` WHERE `EmailAddress` = '" . $emailAddress . "' AND `UserId` <> '" . $userId . "';";
}

$response = mysqli_query($db, $checkSQL);
$row_cnt = mysqli_num_rows($response);
if ($row_cnt > 0) $doesExist = "EXISTS";

echo $doesExist;
?>