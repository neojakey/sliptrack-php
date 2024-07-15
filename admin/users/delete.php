<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
UserPermissions::HasAdminAccesss();

// ### DOES THE USER HAVE USER PERMISSIONS ###
if (!UserPermissions::GetUserPermission("Users", "delete")) {
    SystemAlert::SetPermissionAlert("users", "delete");
    header("Location: " . BASE_URL ."/admin/users/index.php");
}

// ### GET USER DATA ###
$userId = $_GET["id"];
$userFullName = User::GetUserFullName($userId);

if ($userFullName !== "") {
    // ### DELETE USER ###
    User::DeleteUser($userId);

    // ### LOG AND CREATE USER ALERT ###
    SystemLog::LogReport(1, "User '" . $userFullName . "' has been deleted", $_SESSION["userId"]);
    SystemAlert::SetAlert("success", "User deleted successfully");

    // ### REDIRECT USER ###
    header("Location: " . BASE_URL ."/admin/users/index.php");
}
?>