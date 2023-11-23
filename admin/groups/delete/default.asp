<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/functions_security.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<%
' ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
Dim adminAry : adminAry = GetSectionPermission("prmAdmin")
Dim canViewAdmin : canViewAdmin = GetActionPermission("view", adminAry)
IF NOT canViewAdmin THEN
    Call SetUserAlert("danger", "You do not have permission to access administration.")
    Response.Redirect("/")
END IF

' ### DOES THE USER HAVE GROUP DELETE PERMISSION ###
Dim groupsAry : groupsAry = GetSectionPermission("prmGroups")
Dim canDelete : canDelete = GetActionPermission("delete", groupsAry)
IF NOT canDelete THEN
    Call SetUserAlert("danger", "You do not have permission to delete groups.")
    Response.Redirect("/admin/groups/")
END IF

' ### GET GROUP DATA ###
Dim groupId : groupId = Request("id")
Dim GroupRS : Set GroupRS = Server.CreateObject("ADODB.Recordset")
Dim GroupSQL : GroupSQL = "SELECT [GroupName] FROM [Group] WHERE [GroupId] = " & formatDbField(groupId, "int", false)
GroupRS.open GroupSQL, db
Dim groupName : groupName = GroupRS("GroupName")
GroupRS.Close

' ### DELETE GROUP ###
db.Execute("DELETE FROM [Group] WHERE [GroupID] = " & formatDbField(groupId, "int", false))

' ### LOG AND CREATE USER ALERT ###
Call LogReport(1, "Group " & groupName & " has been deleted", Session("userId"))
Call SetUserAlert("success", "Group deleted successfully")

' ### REDIRECT USER ###
Response.Redirect("/admin/groups/")
%>
<!--#include virtual="/includes/closeconnection.asp" -->
