<?php
// ### CONSTANTS ###
define("SITE_NAME", "Slip Saver");
define("SPACER", "&nbsp;&nbsp;<i class=\"fa fa-caret-right\" style=\"color:#ABABAB\" aria-hidden=\"true\"></i>&nbsp;&nbsp;");
define("ADMIN_BREADCRUMB", "<a href=\"/\">Home</a>&nbsp;&nbsp;<i class=\"fa fa-caret-right\" style=\"color:#ABABAB\" aria-hidden=\"true\"></i>&nbsp;&nbsp;<a href=\"/admin/\">Administration</a>&nbsp;&nbsp;<i class=\"fa fa-caret-right\" style=\"color:#ABABAB\" aria-hidden=\"true\"></i>&nbsp;&nbsp;");
define("ROOT", "http://localhost:8080/sliptrack-php");

// ### GLOBAL VARIABLES ###
$db;
$base = "http://localhost:8080/sliptrack-php";

// ### SERVER VARIABLES ###
$host = $_SERVER['HTTP_HOST'];
$protocol = $_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';

// ### PAGE URLS ###
$nPageUrl = $protocol . "://" . $host . $_SERVER["SCRIPT_NAME"];

// ### START SESSION ###
session_start();

// ### INITIATE CONNECTION TO DATABASE ###
$db = InitiateConnection();

// ### CHECK FOR SESSION ###
if (!strpos($nPageUrl, "login.php")) {
    CheckForValidLogin();
}
?>