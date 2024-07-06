<?php require_once("../includes/config.php"); ?>
<?php header('Content-Type: text/plain'); ?>
<?php include ROOT_PATH . "includes/functions.php"; ?>
<?php include ROOT_PATH . "includes/functions_security.php"; ?>
<?php include ROOT_PATH . "includes/common.php"; ?>
<?php
global $db;

$articleUrl = EscapeSql($_GET["nurl"]);

$checkSQL = "SELECT `ArticleId` FROM `Articles` WHERE `ArticleUrl` = " . formatDbField($articleUrl, "text", false) . "";
$response = mysqli_query($db, $checkSQL);
$row_cnt = mysqli_num_rows($response);
if ($row_cnt > 0) {
    echo "EXISTS";
} else {
    echo "NOT-EXISTS";
}
?>