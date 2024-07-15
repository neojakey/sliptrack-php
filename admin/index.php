<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
UserPermissions::HasAdminAccesss();
?>
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
        <?php include ROOT_PATH . "includes/menu_admin.php" ?>
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
                <span id="desktop-user-menu-name"><?=$_SESSION["userFullName"]?></span>
                <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
            </div>
        </header>
        <section>
                <h1 class="page-title">Administration Area</h1>
                <div class="breadcrumb">
                    <a href="<?=BASE_URL?>/">Home</a><?=SPACER?>Administration
                </div>
                <div id="alert-wrapper" style="display:none">
                    <div id="alert">
                        <div id="alert-icon"></div>
                    </div>
                </div>
        </section>
    </div>
</div>
<?php include ROOT_PATH . "includes/footer.php" ?>
<?php include ROOT_PATH . "includes/javascripts.php" ?>
<?php include ROOT_PATH . "includes/kendo_includes.php" ?>
<?php include ROOT_PATH . "includes/alerts.php" ?>
<script type="text/javascript">
    $(function () {
        $('#search-category').kendoDropDownList();
    });

    function validate() {
        var ErrorFound = 0;
        if ($('#search-box').val() === '') {
            alert('Please enter a user search query');
            $('#search-box').focus();
            ErrorFound++;
        }
        if (ErrorFound === 0) {
            var search = encodeURI($('#search-box').val());
            var searchCategory = encodeURI($('#search-category').data('kendoDropDownList').value());
            var url = location.protocol + '//' + location.host + '/admin/users/';
            var urlProperties = location.search;

            if (urlProperties === '') {
                document.location.href = url + '?search=' + search + '&cat=' + searchCategory;
            } else {
                urlProperties = urlProperties.replace('?', '&');
                document.location.href = url + '?search=' + search + '&cat=' + searchCategory + urlProperties;
            }
        }
    }
</script>
</body>

</html>