<?php
class UserPermissions {
    public static function GetUserPermission($sectionName, $actionName) {
        $permissionsAry = GetSectionPermission("prm" . $sectionName, $_SESSION["userGroup"]);
        $response = GetActionPermission($actionName, $permissionsAry);
        return ($response === true) ? 1 : 0;
    }

    public static function HasAdminAccesss() {
        if (!UserPermissions::GetUserPermission("Admin", "view")) {
            SystemAlert::SetAdminAlert();
            header("Location: " . BASE_URL ."/index.php");
        }
    }

    public static function GetSectionAccess($sectionName) {
        return [
            "create" => UserPermissions::GetUserPermission($sectionName, "create"),
            "edit" => UserPermissions::GetUserPermission($sectionName, "edit"),
            "delete" => UserPermissions::GetUserPermission($sectionName, "delete"),
            "view" => UserPermissions::GetUserPermission($sectionName, "view")
        ];
    }
}