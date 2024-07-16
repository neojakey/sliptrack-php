<?php
class ParentList {
    public static function CreateList($listName, $listCode) {
        $listColumns = "ListName,ListCode";
        $listValues = formatDbField($listName, "text", false) . ",". formatDbField($listCode, "text", false);
        $listId = InsertNewRecord("List", $listColumns, $listValues);
        return $listId;
    }

    public static function UpdateList($listId, $listName, $listCode) {
        global $db;
        $strSQL = "
            UPDATE `List` SET
               `ListName` = " . formatDbField($listName, "text", false) . ",
               `ListCode` = " . formatDbField($listCode, "text", false) . "
            WHERE
               `ListId` = " . formatDbField($listId, "int", false) . "
        ";
        mysqli_query($db, $strSQL);
    }
}