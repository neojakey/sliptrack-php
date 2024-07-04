<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DEFINE AND ASSIGN VARIABLES ###
$itemId = EscapeSql($_POST["hidListItemId"]);
$itemName = EscapeSql($_POST["tbItemName"]);
$itemCode = EscapeSql($_POST["tbItemCode"]);
$itemDescription = EscapeSql($_POST["tbItemDescription"]);
$listId = EscapeSql($_POST["hidListId"]);

if ($itemId . "" <> "") {
    // ### UPDATE LIST RECORD ###
    $strSQL = "
        UPDATE `ListItems` SET
           ListItemName = " . formatDbField($itemName, "text", false) . ",
           ListItemCode = " . formatDbField($itemCode, "text", false) . ",
           ListItemDescription = " . formatDbField($itemDescription, "text", true) . ",
           ListId = " . formatDbField($listId, "int", false) . "
        WHERE
           ListItemId = " . formatDbField($itemId, "int", false) . "
    ";
    mysqli_query($db, $strSQL);

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "The List Item '" . $itemName . "' has been edited", $_SESSION["userId"]);
    SetUserAlert("success", "Dropdown field edited successfully");
} else {
    // ### INSERT DROPDOWN RECORD ###
    $itemColumns = "ListItemName,ListItemCode,ListItemDescription,ListId";
    $itemValues = formatDbField($itemName, "text", false) . ",
              " . formatDbField($itemCode, "text", false) . ",
              " . formatDbField($itemDescription, "text", true) . ",
              " . formatDbField($listId, "int", false);
    InsertNewRecord("ListItems", $itemColumns, $itemValues);

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "The List Item '" . $itemName . "' has been added", $_SESSION["userId"]);
    SetUserAlert("success", "Dropdown field added successfully");
}

// ### REDIRECT USER ###
header("Location: " . BASE_URL ."/admin/lists/list-items.php?id=" . $listId);
?>