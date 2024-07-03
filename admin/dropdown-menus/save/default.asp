<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<%
' ### DECLARE AND SET PAGE VARIABLES ###
Dim dropdownListId : dropdownListId = Request.Form("hidDropdownListId")
Dim dropdownListName : dropdownListName = Request.Form("tbDropdownListName")
Dim dropdownCode : dropdownCode = Request.Form("tbDropdownCode")

IF Trim(dropdownListId & "") <> "" THEN
    ' ### UPDATE DROPDOWN LIST RECORD ###
    Dim strSQL : strSQL = _
        "UPDATE [DropDownParent] SET " & _
        "   [DropDownParentName] = " & formatDbField(dropdownListName, "text", false) & ", " & _
        "   [DropDownCode] = " & formatDbField(dropdownCode, "text", false) & " " & _
        "WHERE " & _
        "   [DropDownParentId] = " & formatDbField(dropdownListId, "int", false) & ";"
    db.Execute(strSQL)

    ' ### ADD TO SYSTEM LOG AND USER ALERT ###
    Call LogReport(1, "The Dropdown List '" & dropdownListName & "' has been edited", $_SESSION["userId"))
    Call SetUserAlert("success", "Dropdown list edited successfully")
ELSE
    ' ### ADD DROPDOWN LIST RECORD ###
    Dim dropDownListColumns : dropDownListColumns = "DropDownParentName,DropDownCode"
    Dim dropDownListValues : dropDownListValues = formatDbField(dropdownListName, "text", false) & "," & formatDbField(dropdownCode, "text", false)
    Call InsertNewRecord("DropDownParent", dropDownListColumns, dropDownListValues)

    ' ### ADD TO SYSTEM LOG AND USER ALERT ###
    Call LogReport(1, "The Dropdown List '" & dropdownListName & "' has been added", $_SESSION["userId"))
    Call SetUserAlert("success", "Dropdown list added successfully")
END IF

' ### REDIRECT USER ###
header("Location: " . BASE_URL ."/admin/dropdown-menus/")
%>
<!-- #include virtual="/includes/closeconnection.asp" -->
