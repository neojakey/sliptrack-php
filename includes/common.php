<?php
// ### CONSTANTS ###
define("SITE_NAME", "Slip Saver");
define("SPACER", "&nbsp;&nbsp;<i class=\"fa fa-caret-right\" style=\"color:#ABABAB\" aria-hidden=\"true\"></i>&nbsp;&nbsp;");
define("REQUIRED", "<img src=\"" . BASE_URL . "/images/required.gif\" title=\"Required Field\" alt=\"Required\"/>");
define("START_YEAR", "2023");

// ### GLOBAL VARIABLES ###
$db;

// ### SERVER VARIABLES ###
$host = $_SERVER['HTTP_HOST'];
$protocol = $_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';

// ### PAGE URLS ###
$siteUrl = $protocol . "://" . $host . $_SERVER["SCRIPT_NAME"];

// ### START SESSION ###
session_start();

// ### INITIATE CONNECTION TO DATABASE ###
$db = InitiateConnection();

// ### CHECK FOR SESSION ###
if (!strpos($siteUrl, "login.php")) {
    CheckForValidLogin();
}
?>