﻿<?php require_once("includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?php echo SITE_NAME ?> - User Area</title>
    <?php include ROOT_PATH . "includes/stylesheets.php" ?>
</head>

<body>

<div id="page-wrapper">
    <div class="menu">
        <?php include ROOT_PATH . "includes/menu.php" ?>
    </div>
    <div class="main">
        <header>
            <div></div>
            <div class="notification-wrapper">
                <a href="javascript:void(0);"><i class="fa fa-bell" aria-hidden="true"></i></a>
                <a href="javascript:void(0);"><i class="fa fa-envelope" aria-hidden="true"></i></a>
            </div>
            <div class="user-wrapper" id="user-menu-link">
                <span id="desktop-user-menu-bars"><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>
                <span id="desktop-user-menu-name">Paul Jacobs</span>
                <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
            </div>
        </header>
        <section>
            <h1 class="page-title">Home Page</h1>
        </section>
    </div>
</div>
<?php include ROOT_PATH . "includes/footer.php" ?>
<?php include ROOT_PATH . "includes/javascripts.php" ?>
<?php include ROOT_PATH . "includes/kendo_includes.php" ?>
</body>

</html>