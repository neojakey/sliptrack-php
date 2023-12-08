<?php
function GetSectionPermission($fieldName) {
    if (empty($fieldName)) return;
    global $db;
    $permissionField = "";
    $permissionSQL = "SELECT " . $fieldName . " FROM userGroup WHERE GroupId = " . formatDbField($_SESSION["userGroup"], "int", false);
    $response = mysqli_query($db, $permissionSQL);
    $row_cnt = mysqli_num_rows($response);
    $permissionRS = mysqli_fetch_assoc($response);
    if ($row_cnt !== 0) {
        $permissionField = $permissionRS[$fieldName] . "";
    }

    $canView = false; $canCreate = false;
    $canEdit = false; $canDelete = false;

    if ($permissionField == "full") {
        $canView = true; $canCreate = true; $canEdit = true; $canDelete = true;
    } else {
        if (strpos($permissionField, "view") > -1) $canView = true;
        if (strpos($permissionField, "create") > -1) $canCreate = true;
        if (strpos($permissionField, "edit") > -1) $canEdit = true;
        if (strpos($permissionField, "delete") > -1) $canDelete = true;
    }

    $permissionAry = [$canView, $canCreate, $canEdit, $canDelete];

    return $permissionAry;
}

function GetActionPermission($actionName, $ary) {
    $actionName = $actionName . "";
    if (empty($actionName)) return;

    if ($actionName == "view") {
        return $ary[0];
    } elseif ($actionName == "create") {
        return $ary[1];
    } elseif ($actionName == "edit") {
        return $ary[2];
    } elseif ($actionName == "delete") {
        return $ary[3];
    }
}
?>