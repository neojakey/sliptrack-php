<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### INITIALIZE VARIABLES ###
$itemId = EscapeSql($_POST["hidListItemId"]);
$itemName = EscapeSql($_POST["tbItemName"]);
$itemCode = EscapeSql($_POST["tbItemCode"]);
$itemDescription = EscapeSql($_POST["tbItemDescription"]);
$listId = EscapeSql($_POST["hidListId"]);

// ### SET UP USER ALERT ###
$alertAction = ($itemId !== "") ? "edited" : "created";

if ($itemId . "" <> "") {
    // ### UPDATE LIST ITEM RECORD ###
    ChildList::UpdateListItem($itemId, $itemName, $itemCode, $itemDescription, $listId);
} else {
    // ### CREATE LIST ITEM RECORD ###
    ChildList::CreateListItem($itemName, $itemCode, $itemDescription, $listId);
}

// ### ADD TO SYSTEM LOG AND USER ALERT ###
SystemLog::LogReport(1, "The List Item '" . $itemName . "' has been " . $alertAction, $_SESSION["userId"]);
SystemAlert::SetAlert("success", "List item " . $alertAction . " successfully");

// ### REDIRECT USER ###
header("Location: " . BASE_URL ."/admin/lists/list-items.php?id=" . $listId);
?>