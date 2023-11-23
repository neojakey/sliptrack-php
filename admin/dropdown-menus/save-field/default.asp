<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<%
Dim dropdownFieldId : dropdownFieldId = Request.Form("hidDropdownFieldId")
Dim dropdownFieldName : dropdownFieldName = Request.Form("tbDropdownFieldName")
Dim dropdownFieldCode : dropdownFieldCode = Request.Form("tbDropdownFieldCode")
Dim dropdownFieldDescription : dropdownFieldDescription = Request.Form("tbDropdownFieldDescription")
Dim dropdownFieldParent : dropdownFieldParent = Request.Form("hidParentId")

IF Trim(dropdownFieldId & "") <> "" THEN
    ' ### UPDATE DROPDOWN RECORD ###
    Dim strSQL : strSQL = _
        "UPDATE DropDownFields SET " & _
        "   DropdownFieldName = " & formatDbField(dropdownFieldName, "text", false) & ", " & _
        "   DropdownFieldCode = " & formatDbField(dropdownFieldCode, "text", false) & ", " & _
        "   DropdownFieldDescription = " & formatDbField(dropdownFieldDescription, "text", true) & ", " & _
        "   DropDownParentId = " & formatDbField(dropdownFieldParent, "int", false) & " " & _
        "WHERE " & _
        "   DropdownFieldId = " & formatDbField(dropdownFieldId, "int", false) & ";"
    db.Execute(strSQL)

    ' ### ADD TO SYSTEM LOG AND USER ALERT ###
    Call LogReport(1, "The Dropdown Field '" & dropdownFieldName & "' has been edited", Session("userId"))
    Call SetUserAlert("success", "Dropdown field edited successfully")
ELSE
    ' ### INSERT DROPDOWN RECORD ###
    Dim dropDownColumns : dropDownColumns = "DropdownFieldName,DropdownFieldCode,DropdownFieldDescription,DropDownParentId"
    Dim dropDownValues : dropDownValues = formatDbField(dropdownFieldName, "text", false) & "," & formatDbField(dropdownFieldCode, "text", false) & "," & formatDbField(dropdownFieldDescription, "text", true) & "," & formatDbField(dropdownFieldParent, "int", false)
    Call InsertNewRecord("DropDownFields", dropDownColumns, dropDownValues)

    ' ### ADD TO SYSTEM LOG AND USER ALERT ###
    Call LogReport(1, "The Dropdown Field '" & dropdownFieldName & "' has been added", Session("userId"))
    Call SetUserAlert("success", "Dropdown field added successfully")
END IF

' ### REDIRECT USER ###
Response.Redirect("/admin/dropdown-menus/list/?id=" & dropdownFieldParent)
%>
<!-- #include virtual="/includes/closeconnection.asp" -->
