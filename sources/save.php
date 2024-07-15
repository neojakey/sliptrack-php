<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### INITIALIZE VARIABLES ###
$sourceId = EscapeSql($_POST["hidSourceId"]);
$sourceName = EscapeSql($_POST["tbSourceName"]);
$sourceUrl = EscapeSql($_POST["tbSourceUrl"]);
$sourceLogo = EscapeSql($_POST["tbSourceLogo"]);
$alertAction = ($sourceId !== "") ? "edited" : "created";

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
} else {
    // ### CREATE SOURCE RECORD ###
    $sourceColumns = "SourceName,SourceUrl,SourceLogo";
    $sourceValues = formatDbField($sourceName, "text", false) . ",
                " . formatDbField($sourceUrl, "text", false) . ",
                " . formatDbField($sourceLogo, "text", true);
    InsertNewRecord("Sources", $sourceColumns, $sourceValues);
}

// ### ADD TO SYSTEM LOG AND USER ALERT ###
SystemLog::LogReport(1, "Source has been " . $alertAction, $_SESSION["userId"]);
SystemAlert::SetAlert("success", "Source " . $alertAction . " successfully");

header("Location: " . BASE_URL ."/sources/index.php");
?>