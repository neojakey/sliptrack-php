<?php
spl_autoload_register('myAutoLoader');

function myAutoLoader($className) {
    $path = "classes/";
    $ext = ".class.php";
    $fullPath = $path. $className. $ext;

    if (!file_exists(ROOT_PATH . $fullPath)) {
        return false;
    }

    require_once ROOT_PATH . $fullPath;
}