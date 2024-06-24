        <?php
        $cssSelected = "";
        $permissionsAry = GetSectionPermission("prmAdmin");
        $canViewAdmin = GetActionPermission("view", $permissionsAry);
        $siteUrl = $_SERVER["REQUEST_URI"];

        if ($siteUrl = "index.php") { $cssSelected = "selected"; } else { $cssSelected = ""; }
        ?>
        <div>
            <a href="<?=BASE_URL?>/index.php" title="Home Page" class="<%=cssSelected%>">
                <i class="fa fa-home fa-fw" aria-hidden="true"></i>
                <span>Home</span>
            </a>
        </div>
        <?php
        if ($canViewAdmin) { ?>
        <div>
            <a href="<?=BASE_URL?>/admin/index.php" title="Administration Section">
                <i class="fa fa-cogs fa-fw" aria-hidden="true"></i>
                <span>Admin</span>
            </a>
        </div>
        <?php } ?>
        <?php if (strpos($siteUrl, "/issuers/") > -1) { $cssSelected = "selected"; } else { $cssSelected = ""; } ?>
        <div>
            <a href="<?=BASE_URL?>/issuers/index.php" title="Manage Issuers" class="<%=cssSelected%>">
                <i class="fa fa-id-card-o fa-fw" aria-hidden="true"></i>
                <span>Issuers</span>
            </a>
        </div>
        <?php if (strpos($siteUrl, "/receipts/") > -1) { $cssSelected = "selected"; } else { $cssSelected = ""; } ?>
        <div>
            <a href="<?=BASE_URL?>/receipts/index.php" title="Manage Receipts" class="<%=cssSelected%>">
                <i class="fa fa-ticket fa-fw" aria-hidden="true"></i>
                <span>Receipts</span>
            </a>
        </div>
