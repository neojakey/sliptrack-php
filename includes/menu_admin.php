        <?php
        $cssSelected = "";
        $siteUrl = $_SERVER["REQUEST_URI"];
        if ($siteUrl == "/index.php") { $cssSelected = "selected"; } else { $cssSelected = ""; }
        ?>
        <div>
            <a href="<?=BASE_URL?>/index.php" title="Home Page" class="<?=$cssSelected?>">
                <i class="fa fa-home fa-fw" aria-hidden="true"></i>
                <span>Home</span>
            </a>
        </div>
        <?php if ($siteUrl == "/admin/index.php") { $cssSelected = "selected"; } else { $cssSelected = ""; } ?>
        <div>
            <a href="<?=BASE_URL?>/admin/index.php" title="Administration Section" class="<?=$cssSelected?>">
                <i class="fa fa-cogs fa-fw" aria-hidden="true"></i>
                <span>Admin</span>
            </a>
        </div>
        <?php if (strpos($siteUrl, "/admin/users/index.php") > -1) { $cssSelected = "selected"; } else { $cssSelected = ""; } ?>
        <div>
            <a href="<?=BASE_URL?>/admin/users/index.php" title="Manage Users" class="<?=$cssSelected?>">
                <i class="fa fa-user fa-fw" aria-hidden="true"></i>
                <span>Users</span>
            </a>
        </div>
        <?php if (strpos($siteUrl, "/admin/groups/index.php") > -1) { $cssSelected = "selected"; } else { $cssSelected = ""; } ?>
        <div>
            <a href="<?=BASE_URL?>/admin/groups/index.php" title="Manage User Groups" class="<?=$cssSelected?>">
                <i class="fa fa-users fa-fw" aria-hidden="true"></i>
                <span>Groups</span>
            </a>
        </div>
        <?php if (strpos($siteUrl, "/admin/dropdown-menus/index.php") > -1) { $cssSelected = "selected"; } else { $cssSelected = ""; } ?>
        <div>
            <a href="<?=BASE_URL?>/admin/dropdown-menus/index.php" title="Manage Dropdown Menus" class="<?=$cssSelected?>">
                <i class="fa fa-list fa-fw" aria-hidden="true"></i>
                <span>Menus</span>
            </a>
        </div>
        <?php if (strpos($siteUrl, "/admin/system-log/index.php") > -1) { $cssSelected = "selected"; } else { $cssSelected = ""; } ?>
        <div>
            <a href="<?=BASE_URL?>/admin/system-log/index.php" title="View System Log" class="<?=$cssSelected?>">
                <i class="fa fa-table fa-fw" aria-hidden="true"></i>
                <span>Log</span>
            </a>
        </div>
