<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/functions_email.asp" -->
<!-- #include virtual="/includes/common_no_session.asp" -->
<%
' ### DECLARE AND SET VARIABLES ###
Dim userId : userId = Request.Form("hidUserId")
Dim fullName : fullName = Request.Form("tbFullName")
Dim emailAddress : emailAddress = Request.Form("tbEmailAddress")
Dim subject : subject = Request.Form("tbSubject")
Dim description : description = Request.Form("taDescription")
Dim paymentTier : paymentTier = Request.Form("hidPaymentTier")

' ### SEND CONTACT US EMAIL ###
Set thisMail = Server.CreateObject("CDO.Message")

' ### SEND VALIDATION EMAIL ###
Dim messageEmail : messageEmail = "neojakey@gmail.com"
Dim messageSubject : messageSubject = subject
Dim messageBody : messageBody = _
    "<html><body>" & _
    "<b>User:</b> " & fullName & " [" & userId & "]<br>" & _
    "<b>Email Address:</b> <a href=""mailto:" & emailAddress & """>" & emailAddress & "</a><br>" & _
    "<b>Payment Tier:</b> " & paymentTier & "<br><br>" & _
    "<b>Subject:</b> " & subject & "<br><br>" & _
    "<b>Description</b>: <br>" & _
    description & "<br><br>" & _
    "Visit Site: <a href=""http://www.calcutrack.com/"">http://www.calcutrack.com</a></body></html>"
Call SendEmail(messageEmail, messageSubject, messageBody)

'### REDIRECT USER TO SUCCESS PAGE ###
Response.Redirect(DOMAIN & "/contact-us/?sent=1")
%>