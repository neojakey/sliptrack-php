<?php require_once("../includes/config.php"); ?>
<?php header('Content-Type: text/plain'); ?>
<?php include ROOT_PATH . "includes/functions.php"; ?>
<?php include ROOT_PATH . "includes/functions_security.php"; ?>
<?php include ROOT_PATH . "includes/common.php"; ?>
<?php
global $db;

$nHost = EscapeSql($_GET["h"]);
$nOrigin = EscapeSql($_GET["o"]);

$checkSQL = "SELECT `SourceId` FROM `Sources` WHERE `SourceUrl` LIKE '%" . $nOrigin . "%'";
$response = mysqli_query($db, $checkSQL);
$row_cnt = mysqli_num_rows($response);
if ($row_cnt > 0) {
    $row = mysqli_fetch_assoc($response);
    echo $row["SourceId"];
} else {
    echo "";
}
?>