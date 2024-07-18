<?php require_once("config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
User::UpdateUserColorMode($_SESSION["userId"], $_GET["colour"]);

header("Location: " . BASE_URL . "/index.php");
?>