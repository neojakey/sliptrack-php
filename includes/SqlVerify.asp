<%
Dim s
Dim nRFString : nRFString = ""
Dim nRQString : nRQString = ""

FOR EACH s IN Request.Form
    nRFString = nRFString & " | " & Request.Form(s)
NEXT
FOR EACH s IN Request.QueryString
  nRQString = nRQString & " | " & Request.QueryString(s)
NEXT

Dim BlackList : BlackList = _
    Array("@@", _
    "char", "nchar", "varchar", "nvarchar", _
    "alter", "begin", "cast", "cursor", _
    "declare", "delete", "drop", "end", "exec", _
    "execute", "fetch", "insert", "kill", _
    "sys", "sysobjects", "syscolumns")
Dim ErrorPage : ErrorPage = "fire_email.asp?rq=" & nRQString & "&rf=" & nRFString

Function CheckStringForSQL(str) 
    On Error Resume Next 
    Dim lstr
    Dim nCount : nCount = 0
    IF (IsEmpty(str)) THEN
        CheckStringForSQL = false
        EXIT FUNCTION
    ELSEIF (StrComp(str, "") = 0) THEN
        CheckStringForSQL = false
        EXIT FUNCTION
    END IF
    lstr = lCase(str)
    FOR EACH s IN BlackList
        IF (InStr(lstr, s) <> 0 ) THEN
            nCount = nCount + 1
        END IF
    NEXT
    IF nCount > 4 THEN
        CheckStringForSQL = true
    ELSE
        CheckStringForSQL = false
    END IF
END FUNCTION

FOR EACH s IN Request.Form
    IF (CheckStringForSQL(Request.Form(s))) THEN
        Response.Redirect(ErrorPage)
    END IF
NEXT

FOR EACH s IN Request.QueryString
    IF (CheckStringForSQL(Request.QueryString(s))) THEN
        Response.Redirect(ErrorPage)
    END IF
NEXT

FOR EACH s IN Request.Cookies
    IF (CheckStringForSQL(Request.Cookies(s))) THEN
        Response.Redirect(ErrorPage)
    END IF
NEXT
%>