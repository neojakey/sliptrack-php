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

// ### DOES THE USER HAVE USER PERMISSIONS ###
$permissionsAry = GetSectionPermission("prmUsers");
$canDelete = GetActionPermission("delete", $permissionsAry);
if (!$canDelete) {
    SetUserAlert("danger", "You do not have permission to delete users in the system.");
    header("Location: " . BASE_URL ."/admin/users/index.php");
}

// ### GET USER DATA ###
$userSQL = "SELECT `FirstName`, `LastName` FROM `User` WHERE `UserId` = " . formatDbField($_GET["id"], "int", false);
$response = mysqli_query($db, $userSQL);
$row_cnt = mysqli_num_rows($response);
if ($row_cnt !== 0) {
    $row = mysqli_fetch_row($response);
    $fullName = $row["FirstName"] . " " . $row["LastName"];

    // ### DELETE USER ###
    mysqli_query($db, "DELETE FROM `User` WHERE `UserId` = " . formatDbField($_GET["id"], "int", false));

    // ### LOG AND CREATE USER ALERT ###
    LogReport(1, "User '" . $fullName . "' has been deleted", $_SESSION["userId"]);
    SetUserAlert("success", "User deleted successfully");

    // ### REDIRECT USER ###
    header("Location: " . BASE_URL ."/admin/users/index.php");
}
?>