<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

// ### INITIALIZE VARIABLES
$sourceId = EscapeSql($_POST["hidSourceId"]);
$sourceName = EscapeSql($_POST["tbSourceName"]);
$sourceUrl = EscapeSql($_POST["tbSourceUrl"]);
$sourceLogo = $_FILES["ulSourceLogo"]["name"];
$mimeTypes = ["image/gif", "image/jpeg", "image/png", "image/svg+xml"];

// ### CHECK FOR LOGO UPLOAD ERRORS
if ($_FILES["ulSourceLogo"]["error"] === UPLOAD_ERR_OK) {
    if (!in_array($_FILES["ulSourceLogo"]["type"], $mimeTypes)) {
        // ### IF FILE IS NOT AN IMAGE
        SystemAlert::SetAlert("danger", "The logo you selected is not a valid image file and was not uploaded.");
        $sourceLogo = "";
    } else {
        // ### SAVE THE LOGO
        $fileName = $_FILES["ulSourceLogo"]["name"];
        $destination = __DIR__ . "\\logos\\" . $fileName;
        if (!move_uploaded_file($_FILES["ulSourceLogo"]["tmp_name"], $destination)) {
            SystemAlert::SetAlert("danger", "The logo you uploaded could not be saved.");
        }
    }
}

$alertAction = ($sourceId !== "") ? "edited" : "created";

if (trim($sourceId . "") !== "") {
    // ### CHECK FOR EXISTING SOURCE LOGO
    $existingLogo = $db -> query("SELECT `SourceLogo` FROM `Sources` WHERE `SourceId` = " . formatDbField($sourceId, "int", false)) -> fetch_row()[0];
    if ($sourceLogo === "" && $existingLogo !== "") {
        $sourceLogo = $existingLogo;
    }
    // ### UPDATE SOURCE RECORD
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
    // ### CREATE SOURCE RECORD
    $sourceColumns = "SourceName,SourceUrl,SourceLogo";
    $sourceValues = formatDbField($sourceName, "text", false) . ",
                " . formatDbField($sourceUrl, "text", false) . ",
                " . formatDbField($sourceLogo, "text", true);
    InsertNewRecord("Sources", $sourceColumns, $sourceValues);
}

// ### ADD TO SYSTEM LOG AND USER ALERT
SystemLog::LogReport(1, "Source has been " . $alertAction, $_SESSION["userId"]);
if ($_SESSION["alertActive"] === false) {
    SystemAlert::SetAlert("success", "Source " . $alertAction . " successfully");
}
header("Location: " . BASE_URL ."/sources/index.php");
?>