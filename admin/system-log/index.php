<?php require_once("../../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
$adminAry = GetSectionPermission("prmAdmin");
$canViewAdmin = GetActionPermission("view", $adminAry);
if (!$canViewAdmin) {
    SetUserAlert("danger", "You do not have permission to access administration.");
    header("Location: " . BASE_URL ."/index.php");
}

// ### DOES THE USER HAVE LIST VIEW PERMISSION ###
$logAry = GetSectionPermission("prmSystemLog");
$canView = GetActionPermission("view", $logAry);
$canDelete = GetActionPermission("delete", $logAry);
if (!$canView) {
    SetUserAlert("danger", "You do not have permission to access the system log.");
    header("Location: " . BASE_URL ."/admin/index.php");
}

global $db;
$nEntries = 0;

if (isset($_POST["ddMonth"])) {
    $thisYear = $_POST["ddYear"];
    $thisMonth = $_POST["ddMonth"];
    $thisDay = $_POST["ddDay"];
    $bRefresh = "No";
} elseif (isset($_GET["nMonth"])) {
    $thisYear = $_GET["nYear"];
    $thisMonth = $_GET["nMonth"];
    $thisDay = $_GET["nDay"];
    $bRefresh = "No";
} else {
    $thisMonth = date("m");
    $thisDay = date("d");
    $thisYear = date("y");
    $bRefresh = "Yes";
}

$xDate = $thisYear . "-" . $thisMonth . "-" . $thisDay;
$rsStart = $xDate . " 00:00:00";
$rsEnd = $xDate . " 23:59:59";

$ddYearStart = START_YEAR;
$ddYearEnd = date("Y");

//echo "thisYear = " . $thisYear . "<br>";
//echo "thisMonth = " . $thisMonth . "<br>";
//echo "thisDay = " . $thisDay . "<br>";

if (isset($xDate)) {
    if (!isset($_GET["nUser"])) {
        $strSQLCount = "
            SELECT
                COUNT(`LogId`) AS `entry_count`
            FROM
                `systemlog`
            WHERE
                `LogDate` BETWEEN '" . $rsStart . "' AND '" . $rsEnd . "'
        ";
    } else {
        $strSQLCount = "
            SELECT
                COUNT(`LogId`) AS `entry_count`
            FROM
                `systemlog`
            WHERE
                `LogUserId` = " . $_GET["nUser"] . "
                AND `LogDate` BETWEEN '" . $rsStart . "' AND '" . $rsEnd . "'
        ";
    }
    //echo "strSQLCount = " . $strSQLCount . "<br>";
    $response = mysqli_query($db, $strSQLCount);
    $row_cnt = mysqli_num_rows($response);
    if ($row_cnt !== 0) {
        $row = mysqli_fetch_assoc($response);
        $nEntries = $row["entry_count"];
    }
}

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
                <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;System Log
            </div>
            <form action="<?=BASE_URL?>/admin/system-log/index.php" method="post" name="CreateReport">
                <div id="date-select-wrapper">
                    <span>Month:</span>
                    <span><select name="ddMonth" id="log-month" onchange="this.form.submit();">
                    <option value="01"<?php if ($thisMonth === "01") { ?> selected="selected"<?php } ?>>January</option>
                    <option value="02"<?php if ($thisMonth === "02") { ?> selected="selected"<?php } ?>>February</option>
                    <option value="03"<?php if ($thisMonth === "03") { ?> selected="selected"<?php } ?>>March</option>
                    <option value="04"<?php if ($thisMonth === "04") { ?> selected="selected"<?php } ?>>April</option>
                    <option value="05"<?php if ($thisMonth === "05") { ?> selected="selected"<?php } ?>>May</option>
                    <option value="06"<?php if ($thisMonth === "06") { ?> selected="selected"<?php } ?>>June</option>
                    <option value="07"<?php if ($thisMonth === "07") { ?> selected="selected"<?php } ?>>July</option>
                    <option value="08"<?php if ($thisMonth === "08") { ?> selected="selected"<?php } ?>>August</option>
                    <option value="09"<?php if ($thisMonth === "09") { ?> selected="selected"<?php } ?>>September</option>
                    <option value="10"<?php if ($thisMonth === "10") { ?> selected="selected"<?php } ?>>October</option>
                    <option value="11"<?php if ($thisMonth === "11") { ?> selected="selected"<?php } ?>>November</option>
                    <option value="12"<?php if ($thisMonth === "12") { ?> selected="selected"<?php } ?>>December</option>
                    </select></span>
                    <span>Day:</span>
                    <span><select name="ddDay" id="log-day" onchange="this.form.submit();">
                    <option value="01"<?php if ($thisDay === "01") { ?> selected="selected"<?php } ?>>01</option>
                    <option value="02"<?php if ($thisDay === "02") { ?> selected="selected"<?php } ?>>02</option>
                    <option value="03"<?php if ($thisDay === "03") { ?> selected="selected"<?php } ?>>03</option>
                    <option value="04"<?php if ($thisDay === "04") { ?> selected="selected"<?php } ?>>04</option>
                    <option value="05"<?php if ($thisDay === "05") { ?> selected="selected"<?php } ?>>05</option>
                    <option value="06"<?php if ($thisDay === "06") { ?> selected="selected"<?php } ?>>06</option>
                    <option value="07"<?php if ($thisDay === "07") { ?> selected="selected"<?php } ?>>07</option>
                    <option value="08"<?php if ($thisDay === "08") { ?> selected="selected"<?php } ?>>08</option>
                    <option value="09"<?php if ($thisDay === "09") { ?> selected="selected"<?php } ?>>09</option>
                    <option value="10"<?php if ($thisDay === "10") { ?> selected="selected"<?php } ?>>10</option>
                    <option value="11"<?php if ($thisDay === "11") { ?> selected="selected"<?php } ?>>11</option>
                    <option value="12"<?php if ($thisDay === "12") { ?> selected="selected"<?php } ?>>12</option>
                    <option value="13"<?php if ($thisDay === "13") { ?> selected="selected"<?php } ?>>13</option>
                    <option value="14"<?php if ($thisDay === "14") { ?> selected="selected"<?php } ?>>14</option>
                    <option value="15"<?php if ($thisDay === "15") { ?> selected="selected"<?php } ?>>15</option>
                    <option value="16"<?php if ($thisDay === "16") { ?> selected="selected"<?php } ?>>16</option>
                    <option value="17"<?php if ($thisDay === "17") { ?> selected="selected"<?php } ?>>17</option>
                    <option value="18"<?php if ($thisDay === "18") { ?> selected="selected"<?php } ?>>18</option>
                    <option value="19"<?php if ($thisDay === "19") { ?> selected="selected"<?php } ?>>19</option>
                    <option value="20"<?php if ($thisDay === "20") { ?> selected="selected"<?php } ?>>20</option>
                    <option value="21"<?php if ($thisDay === "21") { ?> selected="selected"<?php } ?>>21</option>
                    <option value="22"<?php if ($thisDay === "22") { ?> selected="selected"<?php } ?>>22</option>
                    <option value="23"<?php if ($thisDay === "23") { ?> selected="selected"<?php } ?>>23</option>
                    <option value="24"<?php if ($thisDay === "24") { ?> selected="selected"<?php } ?>>24</option>
                    <option value="25"<?php if ($thisDay === "25") { ?> selected="selected"<?php } ?>>25</option>
                    <option value="26"<?php if ($thisDay === "26") { ?> selected="selected"<?php } ?>>26</option>
                    <option value="27"<?php if ($thisDay === "27") { ?> selected="selected"<?php } ?>>27</option>
                    <option value="28"<?php if ($thisDay === "28") { ?> selected="selected"<?php } ?>>28</option>
                    <option value="29"<?php if ($thisDay === "29") { ?> selected="selected"<?php } ?>>29</option>
                    <option value="30"<?php if ($thisDay === "30") { ?> selected="selected"<?php } ?>>30</option>
                    <option value="31"<?php if ($thisDay === "31") { ?> selected="selected"<?php } ?>>31</option>
                    </select></span>
                    <span>Year:</span>
                    <span><select name="ddYear" id="log-year" onchange="this.form.submit();"><?php
                    for ($yearCount = $ddYearStart; $yearCount <= $ddYearEnd; $yearCount++) {
                        ?><option value="<?=$yearCount?>"<?php if (intval("20" . $thisYear) === intval($yearCount)) { ?> selected="selected"<?php } ?>><?=$yearCount?></option><?php
                    } ?></select></span>
                    <span><?php if (isset($_GET["nUser"])) { ?>&nbsp;&#149; <a href="<?=BASE_URL?>/admin/system-log/index.php?nMonth=<?=$thisMonth?>&nYear=<?=$thisYear?>&nDay=<?=$thisDay?>">No User</a><?php } ?></span>
                </div>
            </form>
            <div id="event-overview">Displaying <b><?=$nEntries?></b> Results&nbsp;|&nbsp;Total System Log Entries: <b><?=$nTotalEntries?></b></div>
            <table class="data-grid">
                <thead>
                    <tr>
                        <th class="tac" colspan="2">Date</th>
                        <th style="width:100px">Time</th>
                        <th style="width:449px">Description</th>
                        <th style="width:154px">User</th>
                        <th style="width:20px">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($xDate)) {
                        if (!isset($_GET["nUser"])) {
                            $strSQL = "
                                SELECT
                                    sl.`LogType`, sl.`LogDate`,
                                    sl.`LogText`, sl.`LogId`,
                                    sl.`LogUserId`, u.`FirstName`,
                                    u.`LastName`
                                FROM
                                    `SystemLog` AS sl
                                    INNER JOIN `User` AS u ON sl.`LogUserId` = u.`UserId`
                                WHERE
                                    `LogDate` BETWEEN '" . $rsStart . "' AND '" . $rsEnd . "'
                                ORDER BY
                                    `LogID` DESC
                            ";
                        } else {
                            $strSQL = "
                                SELECT
                                    sl.`LogType`, sl.`LogDate`,
                                    sl.`LogText`, sl.`LogId`,
                                    sl.`LogUserId`, u.`FirstName`,
                                    u.`LastName`
                                FROM
                                    `SystemLog` AS sl
                                    INNER JOIN `User` AS u ON sl.`LogUserId` = u.`UserId`
                                WHERE
                                    sl.`LogUserId` = '" . isset($_GET["nUser"]) . "'
                                    AND sl.`LogDate` BETWEEN '" . $rsStart . "' AND '" . $rsEnd . "'
                                ORDER BY
                                    sl.`LogID` DESC
                            ";
                        }
                        $response = mysqli_query($db, $strSQL);
                        $row_cnt = mysqli_num_rows($response);
                        if ($row_cnt === 0) {
                            ?><tr class="h30">
                                <td colspan="6" class="tac">No Results Found for your Criteria</td>
                            </tr><?php
                        } else {
                            while($logRS = mysqli_fetch_assoc($response)) {
                                $logType = "<i class=\"fa fa-info-circle\" style=\"color:#7AC1FF\" aria-hidden=\"true\"></i>";
                                if ($logRS["LogType"] === "2") {
                                    $logType = "<i class=\"fa fa-exclamation-triangle\" style=\"color:#F2D757\" aria-hidden=\"true\"></i>";
                                } elseif ($logRS["LogType"] === "3") {
                                    $logType = "<i class=\"fa fa-times-circle\" style=\"color:#F60F0F\" aria-hidden=\"true\"></i>";
                                } elseif ($logRS["LogType"] === "4") {
                                    $logType = "<i class=\"fa fa-sign-in\" style=\"color:#881280\" aria-hidden=\"true\"></i>";
                                } elseif ($logRS["LogType"] === "5") {
                                    $logType = "<i class=\"fa fa-sign-out\" style=\"color:#881280\" aria-hidden=\"true\"></i>";
                                }
                                $rsDateTimeArray = explode(" ", $logRS["LogDate"]);
                                $rsDateArray = explode("-", $rsDateTimeArray[0]);
                                ?>
                                <tr>
                                    <td style="text-align:center; width:20px"><?=$logType?></td>
                                    <td><?=$rsDateArray[0]?>/<?=$rsDateArray[1]?>/<?=$rsDateArray[2]?></td>
                                    <td><?=$rsDateTimeArray[1]?></td>
                                    <td><?=$logRS["LogText"]?></td>
                                    <td><a href="<?=BASE_URL?>/admin/system-log/index.php?nMonth=<?=$thisMonth?>&nYear=<?=$thisYear?>&nDay=<?=$thisDay?>&nUser=<?=$logRS["LogUserId"]?>"><?=$logRS["FirstName"] . " " . $logRS["LastName"]?></a></td>
                                    <td><?php if ($canDelete) { ?>
                                    <a href="javascript:void(0);" onclick="ConfirmEntryDelete('<?=$logRS["LogId"]?>');" title="Delete"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                                    <?php } else { ?>
                                    <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="You don't have permission to delete system log events."></i>
                                    <?php } ?></td>
                                </tr>
                                <?php
                            }
                        }
                    } else {
                        ?>
                        <tr>
                            <td class="tac" colspan="2">Date</td>
                            <td style="width:100px">Time</td>
                            <td style="width:449px">Description</td>
                            <td style="width:154px">User</td>
                            <td style="width:20px">&nbsp;</td>
                        </tr>
                        <tr class="h30">
                            <td style="text-align:center; color:darkred" class="tac" colspan="6">You have selected an invalid date.  Please re-select you required date.</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
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