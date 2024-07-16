<?php
class Group {
    public static function CreateGroup($groupName) {
        $groupColumns = "GroupName";
        $groupValues = formatDbField($groupName, "text", false);
        $groupId = InsertNewRecord("UserGroup", $groupColumns, $groupValues);
        return $groupId;
    }

    public static function UpdateGroup($groupId, $groupName) {
        global $db;
        $strSQL = "
            UPDATE `UserGroup` SET
               `GroupName` = " . formatDbField($groupName, "text", false) . "
            WHERE
               `GroupId` = " . formatDbField($groupId, "int", false) . "";
        mysqli_query($db, $strSQL);
    }

    public static function DeleteGroup($groupId) {
        global $db;
        mysqli_query($db, "DELETE FROM `UserGroup` WHERE `GroupID` = " . formatDbField($groupId, "int", false));
    }

    public static function GetGroupName($groupId) {
        if (trim($groupId) . "" === "") return;
        global $db;
        $groupNameSQL = "SELECT `GroupName` FROM `UserGroup` WHERE `GroupId` = " . formatDbField($groupId, "int", false);
        $response = mysqli_query($db, $groupNameSQL);
        $row_cnt = mysqli_num_rows($response);
        if ($row_cnt !== 0) {
            $row = mysqli_fetch_assoc($response);
            return $row["GroupName"];
        } else {
            return;
        }
    }
}
