<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<%
Dim groupId : groupId = Request.Form("hidGroupId")
Dim groupName : groupName = Request.Form("tbGroupName")

IF cToStr(groupId) <> "" THEN
    ' ### MODIFY GROUP DATABASE RECORD ###
    Dim strSQL : strSQL = _
        "UPDATE [Group] SET " & _
        "   [GroupName] = " & formatDbField(groupName, "text", false) & " " & _
        " WHERE " & _
        "   [GroupId] = " & formatDbField(groupId, "int", false) & ";"
    db.Execute(strSQL)

    ' ### ADD TO SYSTEM LOG AND USER ALERT ###
    Call LogReport(1, "Group [" & groupName & "] has been edited", Session("userId"))
    Call SetUserAlert("success", "Group [" & groupName & "] has been edited successfully..!")

    ' ### REDIRECT USER ###
    Response.Redirect("/admin/groups/")
ELSE
    ' ### INSERT GROUP DATABASE RECORD ###
    Dim saveGroup : Set saveGroup = Server.CreateObject("ADODB.Recordset")
    saveGroup.Open "[Group]", db, adOpenKeyset, adLockOptimistic
    saveGroup.AddNew
    saveGroup.Fields("GroupName") = formatDbFieldAdd(groupName, "text", false)
    saveGroup.Update
    groupId = saveGroup.Fields("GroupId")
    saveGroup.Close

    ' ### ADD TO SYSTEM LOG AND USER ALERT ###
    Call LogReport(1, "Group " & groupName & " has been created", Session("userId"))
    Call SetUserAlert("success", "Group [" & groupName & "] has been added successfully..!")

    ' ### REDIRECT USER ###
    Response.Redirect("/admin/groups/edit/?id=" & groupId)
END IF
%>
<!--#include virtual="/includes/closeconnection.asp" -->
