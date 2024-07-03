<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
global $db;

$nSaveString = "";
$nId = $_GET["id"];
$nSection = $_GET["section"];
$nGroup = "prm" . $_GET["section"];
$nFullControl = $_GET["fullcontrol"];
$nCreate = $_GET["create"];
$nEdit = $_GET["edit"];
$nDelete = $_GET["delete"];
$nView = $_GET["view"];

if ($nFullControl === "full") {
    $nSaveString = "full";
} elseif ($nFullControl === "") {
    if ($nCreate === "" && $nEdit === "" && $nDelete === "" && $nView === "") {
        $nSaveString = "";
    } else {
        if ($nCreate !== "") {
            $nSaveString = $nSaveString . $nCreate . ",";
        }
        if ($nEdit !== "") {
            $nSaveString = $nSaveString . $nEdit . ",";
        }
        if ($nDelete !== "") {
            $nSaveString = $nSaveString . $nDelete . ",";
        }
        if ($nView !== "") {
            $nSaveString = $nSaveString . $nView . ",";
        }
    }
}
// ### REMOVE LAST COMMA
$nSaveString = rtrim($nSaveString, ',');

// ### SAVE PERMISSIONS TO DATABASE
global $db;

$strSQL = "UPDATE `UserGroup` SET";
if ($nSaveString === "") {
    $strSQL = $strSQL . " `" . $nGroup . "` = NULL";
} else {
    $strSQL = $strSQL . " `" . $nGroup . "` = '" . $nSaveString . "'";
}
$strSQL = $strSQL . " WHERE `GroupID` = " . formatDbField($nId, "int", false);
echo $strSQL;

mysqli_query($db, $strSQL);
?>