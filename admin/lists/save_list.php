<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DECLARE AND SET PAGE VARIABLES ###
$listId = EscapeSql($_POST["hidListId"]);
$listName = EscapeSql($_POST["tbListName"]);
$listCode = EscapeSql($_POST["tbListCode"]);

if (trim($listId . "") <> "") {
    // ### UPDATE LIST RECORD ###
    $strSQL = "
        UPDATE `List` SET
           `ListName` = " . formatDbField($listName, "text", false) . ",
           `ListCode` = " . formatDbField($listCode, "text", false) . "
        WHERE
           `ListId` = " . formatDbField($listId, "int", false) . "
    ";
    mysqli_query($db, $strSQL);

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "The List '" . $listName . "' has been edited", $_SESSION["userId"]);
    SetUserAlert("success", "List edited successfully");
} else {
    // ### ADD LIST RECORD ###
    $listColumns = "ListName,ListCode";
    $listValues = formatDbField($listName, "text", false) . ",". formatDbField($listCode, "text", false);
    $listId = InsertNewRecord("List", $listColumns, $listValues);

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "The List '" . $listName . "' has been added", $_SESSION["userId"]);
    SetUserAlert("success", "List added successfully");
}

// ### REDIRECT USER ###
header("Location: " . BASE_URL ."/admin/lists/index.php");
?>