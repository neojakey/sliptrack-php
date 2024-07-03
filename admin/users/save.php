<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DEFINE AND ASSIGN VARIABLES ###
$userId = mysqli_real_escape_string($db, trim($_POST["hidUserId"]));
$firstName = mysqli_real_escape_string($db, trim($_POST["tbFirstName"]));
$lastName = mysqli_real_escape_string($db, trim($_POST["tbLastName"]));
$email = mysqli_real_escape_string($db, trim($_POST["tbEmail"]));
$userGroup = mysqli_real_escape_string($db, trim($_POST["ddUserGroup"]));
$darkMode = mysqli_real_escape_string($db, trim($_POST["ddSiteTheme"]));
$userName = mysqli_real_escape_string($db, strtolower(substr($firstName, 0, 1) . $lastName));
$passWord = mysqli_real_escape_string($db, trim($_POST["tbPassword"]));

if ($userId . "" !== "") {
    // ### UPDATE USER ###
    $strSQL = "
    UPDATE `User` SET
        `DarkMode` = " . formatDbField($darkMode, "bit", false) . ",
        `FirstName` = " . formatDbField($firstName, "text", false) . ",";
    if (trim($passWord . "") != "") {
        $strSQL = $strSQL . "   `Password` = " . formatDbField(password_hash($passWord, PASSWORD_DEFAULT), "text", false) . ",";
    }
    $strSQL = $strSQL . "   `LastName` = " . formatDbField($lastName, "text", false) . ",";
    if (hasPermission("prmGroups", "edit")) {
        $strSQL = $strSQL . "   `GroupId` = " . formatDbField($userGroup, "int", false) . ",";
    }
    $strSQL = $strSQL . "   `EmailAddress` = " . formatDbField($email, "text", false) . " WHERE `UserId` = " . formatDbField($userId, "int", false) . "";
    mysqli_query($db, $strSQL);

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "User " . $firstName . " " . $lastName . " has been edited", $_SESSION["userId"]);
    SetUserAlert("success", "User " . $firstName . " " . $lastName . " edited successfully");

    // ### UPDATE SESSION VARIABLES ###
    if ($_SESSION["userId"] = $userId) {
        $_SESSION["userFirstName"] = $firstName;
        $_SESSION["userLastName"] = $lastName;
        $_SESSION["userFullName"] = $firstName . " " . $lastName;
        $_SESSION["userEmail"] = $email;
        $_SESSION["userGroup"] = $userGroup;
        $_SESSION["darkMode"] = $darkMode;
    }
} else {
    // ### INSERT USER ###
    echo "darkMode = " . $darkMode . "</br>";
    $userColumns = "UserName,FirstName,LastName,EmailAddress,Password,GroupId,DarkMode";
    $userValues = formatDbField($userName, "text", false) . ",
              " . formatDbField($firstName, "text", false) . ",
              " . formatDbField($lastName, "text", false) . ",
              " . formatDbField($email, "text", false) . ",
              " . formatDbField(password_hash($passWord, PASSWORD_DEFAULT), "text", false) . ",
              " . formatDbField($userGroup, "int", false) . ",
              " . formatDbField($darkMode, "bit", false);
    InsertNewRecord("User", $userColumns, $userValues);

    //' ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "User " . $firstName . " " . $lastName . " has been created", $_SESSION["userId"]);
    SetUserAlert("success", "New user added successfully");
}

// ### REDIRECT USER ###
header("Location: " . BASE_URL ."/admin/users/index.php");
?>