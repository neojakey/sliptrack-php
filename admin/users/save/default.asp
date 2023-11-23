<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<%
' ### DEFINE AND ASSIGN VARIABLES ###
Dim userId : userId = Request.Form("hidUserId")
Dim firstName : firstName = Request.Form("tbFirstName")
Dim lastName : lastName = Request.Form("tbLastName")
Dim email : email = Request.Form("tbEmail")
Dim passWord : passWord = Request.Form("tbPassword")
IF Trim(passWord & "") <> "" THEN
    Dim newSalt : newSalt = CreateSalt()
END IF
Dim userGroup : userGroup = Request.Form("ddUserGroup")
Dim darkMode : darkMode = Request.Form("ddSiteTheme")
Dim userName : userName = lCase(Left(firstName, 1) & lastName)

IF cToStr(userId) <> "" THEN
    Dim strSQL : strSQL = _
        "UPDATE [User] SET" & _
        "   [DarkMode] = " & formatDbField(darkMode, "bit", false) & "," & _
        "   [FirstName] = " & formatDbField(firstName, "text", false) & ","
        IF Trim(passWord & "") <> "" THEN
            strSQL = strSQL & "   [Password] = '" & Hash(newSalt & passWord) & "'," _
                            & "   [PasswordSalt] = '" & newSalt & "',"
        END IF
        strSQL = strSQL & "   [LastName] = " & formatDbField(lastName, "text", false) & ","
        IF hasPermission("prmGroups", "edit") THEN
            strSQL = strSQL & "   [GroupId] = " & formatDbField(userGroup, "int", false) & ","
        END IF
        strSQL = strSQL & "   [EmailAddress] = " & formatDbField(email, "text", false) & " WHERE [UserId] = " & formatDbField(userId, "int", false)
    db.Execute(strSQL)

    ' ### ADD TO SYSTEM LOG AND USER ALERT ###
    Call LogReport(1, "User " & firstName & " " & lastName & " has been edited", Session("userId"))
    Call SetUserAlert("success", "User " & firstName & " " & lastName & " edited successfully")
ELSE
    ' ### INSERT USER ###
    Dim saveUser : Set saveUser = Server.CreateObject("ADODB.Recordset")

    saveUser.Open "[User]", db, adOpenKeyset, adLockOptimistic
    saveUser.AddNew
    saveUser.Fields("FirstName") = formatDbFieldAdd(firstName, "text", false)
    saveUser.Fields("LastName") = formatDbFieldAdd(lastName, "text", false)
    saveUser.Fields("UserName") = formatDbFieldAdd(userName, "text", false)
    saveUser.Fields("EmailAddress") = formatDbFieldAdd(email, "text", false)
    saveUser.Fields("Password") = formatDbFieldAdd(Hash(newSalt & passWord), "text", false)
    saveUser.Fields("PasswordSalt") = formatDbFieldAdd(newSalt, "text", false)
    saveUser.Fields("GroupId") = formatDbFieldAdd(userGroup, "int", false)
    saveUser.Fields("DarkMode") = formatDbFieldAdd(darkMode, "bit", false)
    saveUser.Update
    userId = saveUser.Fields("UserId")
    saveUser.Close

    ' ### ADD TO SYSTEM LOG AND USER ALERT ###
    Call LogReport(1, "User " & firstName & " " & lastName & " has been created", Session("userId"))
    Call SetUserAlert("success", "New user added successfully")

END IF ' ### IF cToStr(userId) <> ""

' ### SET NEW DARK MODE ###
IF cToStr(Session("userId")) = cToStr(userId) THEN
    Session("darkMode") = darkMode
END IF

' ### REDIRECT USER ###
Response.Redirect("/admin/users/")
%>
<!--#include virtual="/includes/closeconnection.asp" -->
