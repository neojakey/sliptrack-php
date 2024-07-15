<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### INITIALIZE VARIABLES ###
$listId = EscapeSql($_POST["hidListId"]);
$listName = EscapeSql($_POST["tbListName"]);
$listCode = EscapeSql($_POST["tbListCode"]);

// ### SET UP USER ALERT ###
$alertAction = ($listId !== "") ? "edited" : "created";

if (trim($listId . "") <> "") {
    // ### UPDATE LIST RECORD ###
    ParentList::UpdateList($listId, $listName, $listCode);
} else {
    // ### CREATE LIST RECORD ###
    ParentList::CreateList($listName, $listCode);
}

// ### ADD TO SYSTEM LOG AND USER ALERT ###
SystemLog::LogReport(1, "The List '" . $listName . "' has been " . $alertAction, $_SESSION["userId"]);
SystemAlert::SetAlert("success", "List " . $alertAction . " successfully");

// ### REDIRECT USER ###
header("Location: " . BASE_URL ."/admin/lists/index.php");
?>