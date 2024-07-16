<?php
class SystemAlert {
    public static function SetAlert($alertType, $alertMessage) : array {
        $_SESSION["alertActive"] = true;
        $_SESSION["alertType"] = $alertType;
        $_SESSION["alertMessage"] = $alertMessage;

        return [
            "alertActive" => true,
            "alertType" => $alertType,
            "alertMessage" => $alertMessage
        ];
    }
    public static function ClearAlert() : array {
        $_SESSION["alertActive"] = false;
        $_SESSION["alertType"] = '';
        $_SESSION["alertMessage"] = '';

        return [
            "alertActive" => false,
            "alertType" => '',
            "alertMessage" => ''
        ];
    }

    public static function SetPermissionAlert($alertSection, $alertAction) : array {
        return self::SetAlert("danger", "You do not have permission to " . $alertAction . " " . $alertSection . ".");
    }

    public static function SetAdminAlert() : array {
        return self::SetAlert("danger", "You do not have permission to access administration..");
    }
}