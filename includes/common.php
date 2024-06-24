<?php
// ### CONSTANTS ###
define("SITE_NAME", "Slip Saver");
define("SPACER", "&nbsp;&nbsp;<i class=\"fa fa-caret-right\" style=\"color:#ABABAB\" aria-hidden=\"true\"></i>&nbsp;&nbsp;");

// ### GLOBAL VARIABLES ###
$db;

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
echo "Page URL: " . $nPageUrl;
if (!strpos($nPageUrl, "login.php")) {
    CheckForValidLogin();
}
?>