<?php require_once("includes/config.php"); ?>
<?php include ROOT_PATH . "includes/functions.php" ?>
<?php include ROOT_PATH . "includes/functions_security.php" ?>
<?php include ROOT_PATH . "includes/common.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?php echo SITE_NAME ?> - User Area</title>
    <?php include ROOT_PATH . "includes/stylesheets.php" ?>
    <link type="text/css" rel="stylesheet" href="<?=BASE_URL?>/css/images.css"/>
    <style type="text/css">
        .more-link {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: flex-end;
            align-items: center;
            padding: 10px;
        }

        .more-link a {
            text-decoration: none;
            color: #000;
            background-color: var(--hyperlink);
            padding: 2px 5px;
            border-radius: 4px;
        }

        .more-link a:hover {
            background-color: var(--hyperlink-hover);
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
                <span id="desktop-user-menu-name">Paul Jacobs</span>
                <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
            </div>
        </header>
        <section>
            <h1 class="page-title">Home Page</h1>
            <h3>Latest Articles</h3>
            <div class="add-button-wrapper">
                    <button type="button" class="primary-btn" onclick="location.href='<?=BASE_URL?>/articles/add.php';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add Article</button>
                </div>
            <?php
            global $db;
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
                LIMIT 6
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
                            $ctr = CalculateCTR($articlesRS["ArticleClicks"], $articlesRS["ArticleViews"]);
                            ?>
                            <tr>
                                <?php if ($articlesRS["ArticleImageUrl"] == "") { ?>
                                    <td><i class="fa fa-picture-o no-image" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                    <td><img src="<?=$articlesRS["ArticleImageUrl"]?>" class="article-img" alt="" onerror="this.src='<?=$articlesRS["ArticleImageUrl"]?>';"/></td>
                                <?php } ?>
                                <td><a href="<?=$articlesRS["ArticleUrl"]?>" target="_new"><?=$articlesRS["ArticleTitle"]?></a></td>
                                <td><a href="<?=BASE_URL?>/articles/source.php?id=<?=$articlesRS["ArticleSourceId"]?>"><?=$articlesRS["SourceName"]?></a></td>
                                <td><?=$articlesRS["ArticleViews"]?></td>
                                <td><?=$articlesRS["ArticleClicks"]?></td>
                                <td><?=$ctr?></td>
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
            <div class="more-link">
                <a href="<?=BASE_URL?>/articles/index.php">More&nbsp;&nbsp;<i class="fa fa-caret-right" aria-hidden="true"></i></a>
            </div>
            <h3>Active Sources</h3>
            <?php
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
                LIMIT 4
            ";
            $response = mysqli_query($db, $sourcesSQL);
            $row_cnt = mysqli_num_rows($response);
            ?>
            <table class="data-grid">
            <?php if ($row_cnt === 0) { ?>
                <tbody>
                    <tr class="h30">
                        <td colspan="4" class="fb tac">No sources have been created</td>
                    </tr>
                </tbody>
            <?php } else { ?>
                <thead>
                    <tr>
                        <th style="width:40%">Source Name</th>
                        <th style="width:40%">Source Url</th>
                        <th style="width:10%">Articles</th>
                        <th style="width:10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($sourceRS = mysqli_fetch_assoc($response)) {
                        ?>
                        <tr>
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
            <div class="more-link">
                <a href="<?=BASE_URL?>/sources/index.php">More&nbsp;&nbsp;<i class="fa fa-caret-right" aria-hidden="true"></i></a>
            </div>
        </section>
    </div>
</div>
<?php include ROOT_PATH . "includes/footer.php" ?>
<?php include ROOT_PATH . "includes/javascripts.php" ?>
<?php include ROOT_PATH . "includes/kendo_includes.php" ?>
</body>

</html>