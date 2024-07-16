<?php
class ChildList {
    public static function CreateListItem($itemName, $itemCode, $itemDescription, $listId) {
        $itemColumns = "ListItemName,ListItemCode,ListItemDescription,ListId";
        $itemValues = formatDbField($itemName, "text", false) . ",
                  " . formatDbField($itemCode, "text", false) . ",
                  " . formatDbField($itemDescription, "text", true) . ",
                  " . formatDbField($listId, "int", false);
        $itemId = InsertNewRecord("ListItems", $itemColumns, $itemValues);
        return $itemId;
    }

    public static function UpdateListItem($itemId, $itemName, $itemCode, $itemDescription, $listId) {
        global $db;
        $strSQL = "
            UPDATE `ListItems` SET
               ListItemName = " . formatDbField($itemName, "text", false) . ",
               ListItemCode = " . formatDbField($itemCode, "text", false) . ",
               ListItemDescription = " . formatDbField($itemDescription, "text", true) . ",
               ListId = " . formatDbField($listId, "int", false) . "
            WHERE
               ListItemId = " . formatDbField($itemId, "int", false) . "
        ";
        mysqli_query($db, $strSQL);
    }
}