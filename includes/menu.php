        <?php
        $cssSelected = "";
        $permissionsAry = GetSectionPermission("prmAdmin");
        $canViewAdmin = GetActionPermission("view", $permissionsAry);

        if ($siteUrl === BASE_URL . "/index.php") { $cssSelected = "selected"; } else { $cssSelected = ""; }
        ?>
        <div>
            <a href="<?=BASE_URL?>/index.php" title="Home Page" class="<?=$cssSelected?>">
                <i class="fa fa-home fa-fw" aria-hidden="true"></i>
                <span>Home</span>
            </a>
        </div>
        <?php
        if ($canViewAdmin) : ?>
        <div>
            <a href="<?=BASE_URL?>/admin/index.php" title="Administration Section">
                <i class="fa fa-cogs fa-fw" aria-hidden="true"></i>
                <span>Admin</span>
            </a>
        </div>
        <?php endif; ?>
        <?php if (strpos($siteUrl, "/sources/") > -1) { $cssSelected = "selected"; } else { $cssSelected = ""; } ?>
        <div>
            <a href="<?=BASE_URL?>/sources/index.php" title="Manage Sources" class="<?=$cssSelected?>">
                <i class="fa fa-id-card-o fa-fw" aria-hidden="true"></i>
                <span>Sources</span>
            </a>
        </div>
        <?php if (strpos($siteUrl, "/articles/") > -1) { $cssSelected = "selected"; } else { $cssSelected = ""; } ?>
        <div>
            <a href="<?=BASE_URL?>/articles/index.php" title="Manage Articles" class="<?=$cssSelected?>">
                <i class="fa fa-newspaper-o fa-fw" aria-hidden="true"></i>
                <span>Articles</span>
            </a>
        </div>
