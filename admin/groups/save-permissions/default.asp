<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<%
Response.Expires = -1

Dim nSaveString : nSaveString = ""
Dim nSection : nSection = Request("section")
Dim nGroup : nGroup = "prm" & Request("section")
Dim nFullControl : nFullControl = Request("fullcontrol")
Dim nCreate : nCreate = Request("create")
Dim nEdit : nEdit = Request("edit")
Dim nDelete : nDelete = Request("delete")
Dim nView : nView = Request("view")

IF nFullControl = "full" THEN
    nSaveString = "full"
ELSEIF nFullControl = "" THEN
    IF nCreate = "" AND nEdit = "" AND nDelete = "" AND nView = "" THEN
        nSaveString = ""
    ELSE
        IF nCreate <> "" THEN nSaveString = nSaveString & nCreate & ","
        IF nEdit <> "" THEN nSaveString = nSaveString & nEdit & ","
        IF nDelete <> "" THEN nSaveString = nSaveString & nDelete & ","
        IF nView <> "" THEN nSaveString = nSaveString & nView & ","
    END IF
END IF
IF Right(nSaveString,1) = "," THEN
    strLength = LEN(nSaveString)
    nSaveString = Left(nSaveString,strLength-1)
END IF

strSQL = "UPDATE [Group] SET"
    IF nSaveString = "" THEN
        strSQL = strSQL & " " & nGroup & " = NULL"
    ELSE
        strSQL = strSQL & " " & nGroup & " = '" & nSaveString & "'"
    END IF
strSQL = strSQL & " WHERE [GroupID] = " & formatDbField(Request("id"), "int", false) & ";"
Response.Write strSQL
db.Execute(strSQL)
%>
<!--#include virtual="/includes/closeconnection.asp" -->
