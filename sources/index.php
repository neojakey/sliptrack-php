<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
$sourcesPermissions = UserPermissions::GetSectionAccess("sources");
if (!$sourcesPermissions["view"]) {
    SystemAlert::SetPermissionAlert("sources", "view");
    header("Location: " . BASE_URL ."/index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?=SITE_NAME?> - Sources</title>
    <?php include ROOT_PATH . "includes/stylesheets.php" ?>
    <link type="text/css" rel="stylesheet" href="<?=BASE_URL?>/css/pagination.css"/>
    <style type="text/css">
        .source-logo {
            width: 24px;
            height: 24px;
            border-radius: 2px;
        }
    </style>
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
                    <span id="desktop-user-menu-name"><?=$_SESSION["userFullName"]?></span>
                    <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                </div>
            </header>
            <section>
                <h1 class="page-title">Issuers</h1>
                <div class="breadcrumb">
                    <a href="/">Home</a><?=SPACER?>Sources
                </div>
                <?php if ($sourcesPermissions["create"]) { ?>
                <div class="add-button-wrapper">
                    <button type="button" class="primary-btn" onclick="location.href='<?=BASE_URL?>/sources/add.php';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add Source</button>
                </div>
                <?php } ?>
                <div id="alert-wrapper" style="display:none">
                    <div id="alert">
                        <div id="alert-icon"></div>
                    </div>
                </div>
                <?php
                global $db;
                $recordsOnPage = 15;
                $pageCount = $db -> query("SELECT COUNT(*) FROM `Sources`") -> fetch_row()[0];
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                $calcPage = ($page - 1) * $recordsOnPage;

                $sourcesSQL = "
                    SELECT
                       s.`SourceId`,
                       s.`SourceName`,
                       s.`SourceUrl`,
                       s.`SourceLogo`,
                       (SELECT COUNT(`ArticleId`) FROM `Articles` WHERE `ArticleSourceId` = s.`SourceId`) AS nUsed
                    FROM
                       `Sources` AS s
                    ORDER BY
                       s.`SourceName` ASC
                    LIMIT " . $calcPage . ", " . $recordsOnPage . "
                ";
                $response = mysqli_query($db, $sourcesSQL);
                $row_cnt = mysqli_num_rows($response);
                ?>
                <table class="data-grid">
                <?php if ($row_cnt === 0) { ?>
                    <tbody>
                        <tr class="h30">
                            <td colspan="5" class="fb tac">No sources have been created</td>
                        </tr>
                    </tbody>
                <?php } else { ?>
                    <thead>
                        <tr>
                            <th style="width:5%">&nbsp;</th>
                            <th>Source Name</th>
                            <th>Source Url</th>
                            <th style="width:10%">Articles</th>
                            <th style="width:8%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($sourceRS = mysqli_fetch_assoc($response)) {
                            ?>
                            <tr>
                                <?php if ($sourceRS["SourceLogo"] !== null) { ?>
                                <td><img src="<?=BASE_URL?>/sources/logos/<?=$sourceRS["SourceLogo"]?>" alt="<?=$sourceRS["SourceName"]?>" title="<?=$sourceRS["SourceName"]?>" class="source-logo"/></td>
                                <?php } else { ?>
                                <td>&nbsp;</td>
                                <?php } ?>
                                <td><?=$sourceRS["SourceName"]?></td>
                                <td><a href="<?=$sourceRS["SourceUrl"]?>" target="_blank"><?=$sourceRS["SourceUrl"]?></a></td>
                                <td><?=$sourceRS["nUsed"]?></td>
                                <td>
                                    <div class="data-grid-icons">
                                        <a href="<?=BASE_URL?>/sources/edit.php?id=<?=$sourceRS["SourceId"]?>" title="Edit Source"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                        <?php if (intval($sourceRS["nUsed"]) > 0) { ?>
                                        <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="This source is currently in use and cannot be deleted"></i>
                                        <?php } else { ?>
                                        <a href="javascript:void(0);" onclick="ConfirmSourceDelete('<?=$sourceRS["SourceId"]?>');" title="Delete"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        } ?>
                    </tbody>
                <?php } ?>
                </table>
                <div class="pagination-wrapper">
                    <?=ShowPagination($page, $pageCount, $recordsOnPage, BASE_URL . "/sources/index.php");?>
                </div>
            </section>
        </div>
    </div>
    <?php include ROOT_PATH . "includes/footer.php" ?>
    <?php include ROOT_PATH . "includes/javascripts.php" ?>
    <?php include ROOT_PATH . "includes/kendo_includes.php" ?>
    <?php include ROOT_PATH . "includes/alerts.php" ?>
    <script type="text/javascript" src="<?=BASE_URL?>/sources/scripts/default.js"></script>
</body>

</html>

