<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/functions.asp" -->
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

' ### DOES THE USER HAVE USER DELETE PERMISSION ###
Dim usersAry : usersAry = GetSectionPermission("prmUsers")
Dim canDelete : canDelete = GetActionPermission("delete", usersAry)
IF NOT canDelete THEN
    Call SetUserAlert("danger", "You do not have permission to delete users.")
    Response.Redirect("/admin/users/")
END IF

' ### GET USER DATA ###
Dim UserRS : Set UserRS = Server.CreateObject("ADODB.Recordset")
Dim UserSQL : UserSQL = _
    "SELECT " & _
    "   [FirstName], " & _
    "   [LastName] " & _
    "FROM " & _
    "   [User] " & _
    "WHERE " & _
    "   [UserId] = " & formatDbField(Request("id"), "int", false)
UserRS.open UserSQL, db
Dim fullName : fullName = UserRS("FirstName") & " " & UserRS("LastName")
UserRS.Close

' ### DELETE USER ###
db.Execute("DELETE FROM [User] WHERE [UserId] = " & formatDbField(Request("id"), "int", false))

' ### LOG AND CREATE USER ALERT ###
Call LogReport(1, "User '" & fullName & "' has been deleted", Session("userId"))
Call SetUserAlert("success", "User deleted successfully")

' ### REDIRECT USER ###
Response.Redirect("/admin/users/")
%>
<!--#include virtual="/includes/closeconnection.asp" -->
