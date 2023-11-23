<?php
// ### CONSTANTS ###
define("SITE_NAME", "Slip Saver");
define("SPACER", "&nbsp;&nbsp;<i class=\"fa fa-caret-right\" style=\"color:#ABABAB\" aria-hidden=\"true\"></i>&nbsp;&nbsp;");
define("ADMIN_BREADCRUMB", "<a href=\"/\">Home</a>&nbsp;&nbsp;<i class=\"fa fa-caret-right\" style=\"color:#ABABAB\" aria-hidden=\"true\"></i>&nbsp;&nbsp;<a href=\"/admin/\">Administration</a>&nbsp;&nbsp;<i class=\"fa fa-caret-right\" style=\"color:#ABABAB\" aria-hidden=\"true\"></i>&nbsp;&nbsp;");

// ### GLOBAL VARIABLES ###
$db;

// ### PAGE URL ###
$nPageUrl = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["SCRIPT_NAME"];

// ### START SESSION ###
session_start();

// ### INITIATE CONNECTION TO DATABASE ###
$db = InitiateConnection();

// ### CHECK FOR SESSION ###
if (!strpos($nPageUrl, "login.php")) {
    CheckForValidLogin();
}
?>