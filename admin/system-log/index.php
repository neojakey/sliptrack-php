<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
UserPermissions::HasAdminAccesss();

// ### DOES THE USER HAVE LIST VIEW PERMISSION ###
$logPermission = UserPermissions::GetSectionAccess("SystemLog");
if (!$logPermission["view"]) {
    SystemAlert::SetPermissionAlert("view", "System Log");
    header("Location: " . BASE_URL ."/admin/index.php");
}

global $db;
$recordsOnPage = 15;

$strSQLCount = "SELECT COUNT(`LogId`) AS `entry_count` FROM `SystemLog`";
$response = mysqli_query($db, $strSQLCount);
$row_cnt = mysqli_num_rows($response);
if ($row_cnt !== 0) {
    $row = mysqli_fetch_assoc($response);
    $nTotalEntries = $row["entry_count"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?=SITE_NAME?> - User Area</title>
    <?php include ROOT_PATH . "includes/stylesheets.php" ?>
    <link type="text/css" rel="stylesheet" href="<?=BASE_URL?>/css/pagination.css"/>
    <link rel="stylesheet" type="text/css" href="<?=BASE_URL?>/admin/system-log/css/default.css"/>
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
            <h1 class="page-title">System Log</h1>
            <div class="breadcrumb">
                <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a><?=SPACER?>System Log
            </div>
            <div id="event-overview">Displaying <b><?=$recordsOnPage?></b> Results&nbsp;|&nbsp;Total System Log Entries: <b><?=$nTotalEntries?></b></div>
            <table class="data-grid">
                <thead>
                    <tr>
                        <th style="width:5%"></th>
                        <th class="tac">Date</th>
                        <th>Time</th>
                        <th>Description</th>
                        <th style="width:13%">User</th>
                        <th style="width:5%">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pageCount = $db -> query("SELECT COUNT(*) FROM `SystemLog`") -> fetch_row()[0];
                    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                    $calcPage = ($page - 1) * $recordsOnPage;
                    $strSQL = "
                        SELECT
                            sl.`LogType`, sl.`LogDate`,
                            sl.`LogText`, sl.`LogId`,
                            sl.`LogUserId`, u.`FirstName`,
                            u.`LastName`
                        FROM
                            `SystemLog` AS sl
                            INNER JOIN `User` AS u ON sl.`LogUserId` = u.`UserId`
                        ORDER BY
                            sl.`LogID` DESC
                        LIMIT " . $calcPage . ", " . $recordsOnPage . "
                    ";
                    $response = mysqli_query($db, $strSQL);
                    $row_cnt = mysqli_num_rows($response);
                    if ($row_cnt === 0) {
                        ?><tr class="h30">
                            <td colspan="6" class="tac">No Results Found for your Criteria</td>
                        </tr><?php
                    } else {
                        while($logRS = mysqli_fetch_assoc($response)) {
                            $logType = "<i class=\"fa fa-info-circle fa-fw\" style=\"color:#7AC1FF\" aria-hidden=\"true\"></i>";
                            if ($logRS["LogType"] === "2") {
                                $logType = "<i class=\"fa fa-exclamation-triangle fa-fw\" style=\"color:#F2D757\" aria-hidden=\"true\"></i>";
                            } elseif ($logRS["LogType"] === "3") {
                                $logType = "<i class=\"fa fa-times-circle fa-fw\" style=\"color:#F60F0F\" aria-hidden=\"true\"></i>";
                            } elseif ($logRS["LogType"] === "4") {
                                $logType = "<i class=\"fa fa-sign-in fa-fw\" style=\"color:#881280\" aria-hidden=\"true\"></i>";
                            } elseif ($logRS["LogType"] === "5") {
                                $logType = "<i class=\"fa fa-sign-out fa-fw\" style=\"color:#881280\" aria-hidden=\"true\"></i>";
                            }

                            $rsDateTimeArray = explode(" ", $logRS["LogDate"]);
                            $rsDateArray = explode("-", $rsDateTimeArray[0]);
                            ?>
                            <tr>
                                <td style="text-align:center"><?=$logType?></td>
                                <td><a href="<?=BASE_URL?>/admin/system-log/date_logs.php?nDay=<?=$rsDateArray[2]?>&nMonth=<?=$rsDateArray[1]?>&nYear=<?=$rsDateArray[0]?>"><?=date("F j, Y", strtotime($logRS["LogDate"]))?></td>
                                <td><?=date("g:i a", strtotime($logRS["LogDate"]))?></td>
                                <td><?=$logRS["LogText"]?></td>
                                <td><a href="<?=BASE_URL?>/admin/system-log/index.php?nMonth=<?=$thisMonth?>&nYear=<?=$thisYear?>&nDay=<?=$thisDay?>&nUser=<?=$logRS["LogUserId"]?>"><?=$logRS["FirstName"] . " " . $logRS["LastName"]?></a></td>
                                <td><?php if ($logPermission["delete"]) { ?>
                                <a href="javascript:void(0);" onclick="ConfirmEntryDelete('<?=$logRS["LogId"]?>');" title="Delete"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                                <?php } else { ?>
                                <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="You don't have permission to delete system log events."></i>
                                <?php } ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div class="pagination-wrapper">
                <?=ShowPagination($page, $pageCount, $recordsOnPage, BASE_URL . "/admin/system-log/all.php")?>
            </div>
        </section>
    </div>
</div>
<?php include ROOT_PATH . "includes/footer.php" ?>
<?php include ROOT_PATH . "includes/javascripts.php" ?>
<?php include ROOT_PATH . "includes/kendo_includes.php" ?>
<?php include ROOT_PATH . "includes/alerts.php" ?>
<script type="text/javascript" src="<?=BASE_URL?>/admin/system-log/scripts/systemlog_initialize.js"></script>
<script type="text/javascript">
    function ConfirmEntryDelete(LogNum) {
        var agree = confirm("Are you sure you wish to delete this log entry?\n");
        if (agree) {
            document.location.href = "<?=BASE_URL?>/admin/system-log/delete.php?id=" + LogNum;
        }
    }
</script>
</body>

</html>