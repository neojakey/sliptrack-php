<!--#include virtual="/includes/adovbs.inc" -->
<!--#include virtual="/includes/functions.asp" -->
<!--#include virtual="/includes/common.asp" -->
<!--#include virtual="/includes/SqlVerify.asp" -->
<%
' ### DECLARE AND SET VARIABLES ###
Dim password : password = Request.Form("tbPassword")
Dim newSalt : newSalt = CreateSalt()

' ### UPDATE PASSWORD FOR USER ###
Dim strSQL : strSQL = _
    "UPDATE [User] SET " & _
    "   [Password] = '" & Hash(newSalt & password) & "', " & _
    "   [PasswordSalt] = '" & newSalt & "' " & _
    " WHERE [UserId] = " & formatDbField(Session("userId"), "int", false)
db.Execute(strSQL)

' ### LOG AND CREATE USER ALERT ###
Call LogReport(1, Session("userFullName") & " password has been changed", Session("userId"))
Call SetUserAlert("success", "User password has been updated successfully")

' ### REDIRECT USER ###
Response.Redirect("/admin/users/")
%>
