<?php require_once("../includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<?php
// ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
$articlesAry = GetSectionPermission("prmArticles");
$canView = GetActionPermission("view", $articlesAry);
if (!$canView) {
    SetUserAlert("danger", "You do not have permission to access articles.");
    header("Location: " . BASE_URL ."/index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?=SITE_NAME?> - Articles</title>
    <?php include ROOT_PATH . "includes/stylesheets.php" ?>
    <style type="text/css">
        .article-img {
            width: 60px;
            margin: 5px 0;
            border: 1px #626262 solid;
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
                <h1 class="page-title">Articles</h1>
                <div class="breadcrumb">
                    <a href="<?=BASE_URL?>/index.php">Home</a><?=SPACER?>Articles
                </div>
                <div class="add-button-wrapper">
                    <button type="button" class="primary-btn" onclick="location.href='<?=BASE_URL?>/articles/add.php';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add Article</button>
                </div>
                <div id="alert-wrapper" style="display:none">
                    <div id="alert">
                        <div id="alert-icon"></div>
                    </div>
                </div>
                <?php
                $recordsOnPage = 15;
                $pageCount = $db -> query("SELECT COUNT(*) FROM `Articles`") -> fetch_row()[0];
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                $calcPage = ($page - 1) * $recordsOnPage;

                $articlesSQL = "
                    SELECT
                       a.`ArticleId`,
                       a.`ArticleTitle`,
                       a.`ArticleUrl`,
                       a.`ArticleImageUrl`,
                       a.`ArticleSourceId`,
                       a.`ArticleDate`,
                       a.`ArticleClicks`,
                       a.`ArticleViews`,
                       s.`SourceUrl`,
                       s.`SourceName`
                    FROM
                       `Articles` AS a
                       INNER JOIN `Sources` AS s ON a.`ArticleSourceId` = s.`SourceId`
                    ORDER BY
                       a.`ArticleDate` DESC
                    LIMIT " . $calcPage . ", " . $recordsOnPage . "
                ";
                $response = mysqli_query($db, $articlesSQL);
                $row_cnt = mysqli_num_rows($response);
                ?>
                <table class="data-grid">
                    <?php if ($row_cnt === 0) { ?>
                    <tbody>
                        <tr class="h30">
                            <td colspan="7" class="fb tac">No articles have been added</td>
                        </tr>
                    </tbody>
                    <?php } else { ?>
                    <thead>
                        <tr>
                            <th style="width:5%"></th>
                            <th style="width:35%">Article Title</th>
                            <th style="width:20%">Site</th>
                            <th style="width:10%">Views</th>
                            <th style="width:10%">Clicks</th>
                            <th style="width:10%">CTR</th>
                            <th style="width:10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($articlesRS = mysqli_fetch_assoc($response)) {
                                ?>
                                <tr>
                                    <td><img src="<?=$articlesRS["ArticleImageUrl"]?>" class="article-img" alt=""/></td>
                                    <td><a href="<?=$articlesRS["ArticleUrl"]?>" target="_new"><?=$articlesRS["ArticleTitle"]?></a></td>
                                    <td><a href="<?=$articlesRS["SourceUrl"]?>" target="_new"><?=$articlesRS["SourceName"]?></a></td>
                                    <td><?=$articlesRS["ArticleViews"]?></td>
                                    <td><?=$articlesRS["ArticleClicks"]?></td>
                                    <td>??</td>
                                    <td>
                                        <div class="data-grid-icons">
                                            <a href="<?=BASE_URL?>/articles/edit.php?id=<?=$articlesRS["ArticleId"]?>" title="Edit Article"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                            <a href="javascript:void(0);" onclick="ConfirmArticleDelete('<?=$articlesRS["ArticleId"]?>');" title="Delete"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                    <?php } ?>
                </table>
            </section>
        </div>
    </div>
    <?php include ROOT_PATH . "includes/footer.php" ?>
    <?php include ROOT_PATH . "includes/javascripts.php" ?>
    <?php include ROOT_PATH . "includes/kendo_includes.php" ?>
    <?php include ROOT_PATH . "includes/alerts.php" ?>
    <script type="text/javascript" src="<?=BASE_URL?>/articles/scripts/default.js"></script>
</body>

</html>

