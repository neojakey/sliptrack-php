<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

$groupId = EscapeSql($_POST["hidGroupId"]);
$groupName = EscapeSql($_POST["tbGroupName"]);

if ($groupId !== "") {
    // ### MODIFY GROUP DATABASE RECORD ###
    $strSQL = "
        UPDATE `UserGroup` SET
           `GroupName` = " . formatDbField($groupName, "text", false) . "
        WHERE
           `GroupId` = " . formatDbField($groupId, "int", false) . "";
    mysqli_query($db, $strSQL);

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "Group " . $groupName . " has been edited", $_SESSION["userId"]);
    SetUserAlert("success", "Group " . $groupName . " has been edited successfully..!");

    // ### REDIRECT USER ###
    header("Location: " . BASE_URL ."/admin/groups/index.php");
} else {
    // ### INSERT GROUP DATABASE RECORD ###
    $groupColumns = "GroupName";
    $groupValues = formatDbField($groupName, "text", false);
    $groupId = InsertNewRecord("UserGroup", $groupColumns, $groupValues);

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "Group " . $groupName . " has been created", $_SESSION["userId"]);
    SetUserAlert("success", "Group [" . $groupName . "] has been added successfully..!");

    // ### REDIRECT USER ###
    header("Location: " . BASE_URL ."/admin/groups/index.php");
}
?>

