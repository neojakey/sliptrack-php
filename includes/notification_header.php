<?php if ($_SESSION["darkMode"]) : ?>
<a href="<?=BASE_URL?>/includes/change_colour.php?colour=0">
    <i class="fa fa-sun-o" title="Click to change to light mode" aria-hidden="true"></i>
</a>
<?php else : ?>
<a href="<?=BASE_URL?>/includes/change_colour.php?colour=1">
    <i class="fa fa-moon-o" title="Click to change to dark mode" aria-hidden="true"></i>
</a>
<?php endif; ?>
<a href="javascript:void(0);"><i class="fa fa-bell" aria-hidden="true"></i></a>