<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common_no_session.asp" -->
<%
Dim result : result = false
Dim checkRS : Set checkRS = Server.CreateObject("ADODB.Recordset")
Dim checkSQL : checkSQL = _
    "SELECT " & _
    "   [UserId] " & _
    "FROM " & _
    "   [User] " & _
    "WHERE " & _
    "   [UserEmail] = '" & Request("value") & "';"
checkRS.open checkSQL, db
IF NOT checkRS.EOF THEN result = true
checkRS.Close
Response.Write result
%>
<!-- #include virtual="/includes/closeconnection.asp" -->
