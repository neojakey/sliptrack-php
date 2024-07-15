<?php require_once("includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
if (isset($_POST["submit"])) {
    session_start();
    global $db;

    // ### GET FORM DATA
    $userName = EscapeSql($_POST["tbUsername"]);
    $password = EscapeSql($_POST["tbPassword"]);

    // ### LOG IN THE USER
    $user = new User($userName, $password);
    $user->userLogin();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?php echo SITE_NAME ?> - User Area</title>
    <?php include "includes/stylesheets.php" ?>
    <link rel="stylesheet" type="text/css" href="css/login.css"/>
</head>

<body>

<div id="page-wrapper">
    <div class="vertical-title">Codename: Black Castle</div>
    <div class="menu"></div>
    <div class="main">
        <form name="login" id="login-form" action="login.php" method="post" onsubmit="return validate();">
            <input name="action" type="hidden" value="submitted"/>
            <table class="form-table">
                <tr style="height:initial">
                    <td colspan="2">
                        <div id="alert-wrapper" style="display:none">
                            <div id="alert">
                                <div id="alert-icon"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="form-title">Username:</td>
                    <?php
                    if (isset($_POST['submit'])) {
                        $action = $_POST["action"];
                        $nUsername = $_POST["tbUsername"];
                        $nPassword = $_POST["tbPassword"];
                    } else {
                        $nUsername = "";
                    }
                    ?>
                    <td><input type="text" class="k-textbox" maxlength="20" name="tbUsername" id="username" value="<?php echo $nUsername ?>"/></td>
                </tr>
                <tr style="height:5px">
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td class="form-title">Password:</td>
                    <td><input type="password" class="k-textbox" maxlength="50" name="tbPassword" id="password"/></td>
                </tr>
                <tr style="height:5px">
                    <td colspan="2"></td>
                </tr>
                <tr style="height:60px">
                    <td></td>
                    <td>
                    <table style="border:0">
                        <tr class="f5">
                            <td><button type="submit" name="submit" class="primary-btn">Log In</button></td>
                            <td style="width:8px"></td>
                            <td class="mainlink"><a href="forgot_pw.asp">Forgot your password?</a></td>
                        </tr>
                    </table>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php include ROOT_PATH . "includes/javascripts.php" ?>
<?php include ROOT_PATH . "includes/kendo_includes.php" ?>
<script type="text/javascript">
    function validate() {
        if ($('#username').val() === '') {
            alert('Please type your user name.');
            $('#username').focus();
            return false;
        }
        if ($('#password').val() === '') {
            alert('Please type your password.');
            $('#password').focus();
            return false;
        }
        return true;
    }

    document.onkeydown = function (evt) {
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            validate();
        }
    }
</script>
<?php
if (isset($_SESSION["hasAlert"])) {
    if ($_SESSION["hasAlert"]) { ?>
        <script type="text/javascript">
            $(function () {
                ShowAlert(true, '<?php echo $_SESSION["alertType"] ?>', '<?php echo $_SESSION["alertMessage"] ?>');
            });
        </script>
        <?php
        $_SESSION["hasAlert"] = false;
    }
}
?>
</body>

</html>