<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<%
Dim checkSQL : checkSQL = ""
Dim result : result = false
Dim emailAddress : emailAddress = Request("value")
Dim userId : userId = Request("id")

Dim checkRS : Set checkRS = Server.CreateObject("ADODB.Recordset")
IF cToStr(userId) = "" THEN ' ### NEW USER, NO EXISTING USERID
    checkSQL = _
        "SELECT " & _
        "   [UserId] " & _
        "FROM " & _
        "   [User] " & _
        "WHERE " & _
        "   [EmailAddress] = '" & emailAddress & "';"
ELSE ' ### EXISTING USER WITH USERID
    checkSQL = _
        "SELECT " & _
        "   [UserId] " & _
        "FROM " & _
        "   [User] " & _
        "WHERE " & _
        "   [EmailAddress] = '" & emailAddress & "' AND [UserId] <> '" & userId & "';"
END IF
checkRS.open checkSQL, db
IF NOT checkRS.EOF THEN result = true
checkRS.Close

Response.Write result
%>
<!-- #include virtual="/includes/closeconnection.asp" -->
