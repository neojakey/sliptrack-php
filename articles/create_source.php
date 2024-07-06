<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php"; ?>
<?php include ROOT_PATH . "includes/functions_security.php"; ?>
<?php include ROOT_PATH . "includes/common.php"; ?>
<?php
global $db;

$nHost = EscapeSql($_GET["h"]);
$nOrigin = EscapeSql($_GET["o"]);

// ### INSERT SOURCE RECORD ###
$sourceColumns = "SourceName,SourceUrl";
$sourceValues = formatDbField($nHost, "text", false) . ",
            " . formatDbField($nOrigin, "text", false);
$sourceId = InsertNewRecord("Sources", $sourceColumns, $sourceValues);

// ### ADD TO SYSTEM LOG AND USER ALERT ###
LogReport(1, "Source has been added", $_SESSION["userId"]);

echo $sourceId;
?>