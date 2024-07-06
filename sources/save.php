<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### DEFINE AND ASSIGN VARIABLES ###
$sourceId = EscapeSql($_POST["hidSourceId"]);
$sourceName = EscapeSql($_POST["tbSourceName"]);
$sourceUrl = EscapeSql($_POST["tbSourceUrl"]);
$sourceLogo = EscapeSql($_POST["tbSourceLogo"]);

if (trim($sourceId . "") !== "") {
    // ### UPDATE SOURCE RECORD ###
    $strSQL = "
        UPDATE `Sources` SET
            `SourceName` = " . formatDbField($sourceName, "text", false) . ",
            `SourceUrl` = " . formatDbField($sourceUrl, "text", false) . ",
            `SourceLogo` = " . formatDbField($sourceLogo, "text", true) . "
        WHERE
            `SourceId` = " . formatDbField($sourceId, "int", false) . "
    ";
    mysqli_query($db, $strSQL);

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "Source has been edited", $_SESSION["userId"]);
    SetUserAlert("success", "Source edited successfully");
} else {
    // ### INSERT SOURCE RECORD ###
    $sourceColumns = "SourceName,SourceUrl,SourceLogo";
    $sourceValues = formatDbField($sourceName, "text", false) . ",
                " . formatDbField($sourceUrl, "text", false) . ",
                " . formatDbField($sourceLogo, "text", true);
    InsertNewRecord("Sources", $sourceColumns, $sourceValues);

    // ### ADD TO SYSTEM LOG AND USER ALERT ###
    LogReport(1, "Source has been added", $_SESSION["userId"]);
    SetUserAlert("success", "Source added successfully");
}

header("Location: " . BASE_URL ."/sources/index.php");
?>