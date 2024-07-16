<?php
class SystemLog {
    public static function LogReport($logType, $logText, $logUserId) {
        if (isset($logUserId)) {
            $logText = EscapeSql($logText);
            $logColumns = "LogType,LogText,LogUserId";
            $logValues = formatDbField($logType, "int", 0) . "," . formatDbField($logText, "text", false) . "," . formatDbField($logUserId, "int", false);
            InsertNewRecord("systemlog", $logColumns, $logValues);
        }
    }
}