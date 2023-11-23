<%
FUNCTION GetSectionPermission(fieldName)
    IF cToStr(fieldName) = "" THEN EXIT FUNCTION

    Dim permissionField : permissionField = ""
    Dim permissionRS : Set permissionRS = Server.CreateObject("ADODB.Recordset")
    Dim permissionSQL : permissionSQL = "SELECT " & fieldName & " FROM [Group] WHERE [GroupId] = " & formatDbField(Session("userGroup"), "int", false)
    permissionRS.Open permissionSQL, db
    IF NOT permissionRS.EOF THEN
        permissionField = cToStr(permissionRS(fieldName))
    END IF
    permissionRS.Close

    Dim canView : canView = false : Dim canCreate : canCreate = false
    Dim canEdit : canEdit = false : Dim canDelete : canDelete = false

    IF permissionField = "full" THEN
        canView = true : canCreate = true : canEdit = true : canDelete = true
    ELSE
        IF InStr(permissionField, "view") > 0 THEN canView = true
        IF InStr(permissionField, "create") > 0 THEN canCreate = true
        IF InStr(permissionField, "edit") > 0 THEN canEdit = true
        IF InStr(permissionField, "delete") > 0 THEN canDelete = true
    END IF

    Dim permissionString : permissionString = canView & "|" & canCreate & "|" & canEdit & "|" & canDelete
    Dim permissionAry : permissionAry = Split(permissionString, "|")

    GetSectionPermission = permissionAry
END FUNCTION

FUNCTION GetActionPermission(actionName, ary)
    actionName = cToStr(actionName)
    IF actionName = "" THEN EXIT FUNCTION

    Dim boolOut : boolOut = false
    IF actionName = "view" THEN
        boolOut = ary(0)
    ELSEIF actionName = "create" THEN
        boolOut = ary(1)
    ELSEIF actionName = "edit" THEN
        boolOut = ary(2)
    ELSEIF actionName = "delete" THEN
        boolOut = ary(3)
    END IF
    GetActionPermission = boolOut
END FUNCTION
%>