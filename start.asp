<!--#include virtual="/includes/adovbs.inc" -->
<!--#include virtual="/includes/functions.asp" -->
<!--#include virtual="/includes/common_no_session.asp" -->
<!--#include virtual="/includes/payment_service_functions.asp" -->
<%
Dim nEmailAddress : nEmailAddress = Request("tbEmailAddress")
Dim nPassword : nPassword = Request("tbPassword")

IF Trim(nEmailAddress & "") <> "" THEN
    Dim loginRS : Set loginRS = Server.CreateObject("ADODB.RecordSet")
    Dim loginSQL : loginSQL = _
        "SELECT " & _
        "   u.UserId, u.FirstName, u.LastName, " & _
        "   u.UserEmail, u.Created, TimeZoneOffset, " & _
        "   pt.PaymentTierCode, pt.PaymentTierType, " & _
        "   u.Groups, u.NumberLogins, u.Salt, " & _
        "   u.UserPassword, u.Confirmed, u.FreeExpirationDate, " & _
        "   d.LayoutId, d.Panel1, d.Panel2, d.Panel3, " & _
        "   d.Panel4, d.Panel5, d.Panel6, pt.PaymentTierId " & _
        "FROM " & _
        "   User AS u " & _
        "   LEFT OUTER JOIN Dashboard AS d ON u.UserId = d.UserId " & _
        "   LEFT OUTER JOIN PaymentTier AS pt ON u.PaymentTierId = pt.PaymentTierId " & _
        "WHERE " & _
        "   UserEmail = " & formatDbField(nEmailAddress, "text", false) & " " & _
        "ORDER BY " & _
        "   u.UserId"
    loginRS.Open loginSQL, db
    IF loginRS.EOF THEN
        ' ### HANDLER: USER NOT FOUND ###
        Session("hasAlert") = true
        Session("alertType") = "danger"
        Session("alertMessage") = "User account email or password is incorrect."
        Response.Redirect(DOMAIN & "/start.asp")
    ELSEIF Hash(loginRS("Salt") & nPassword) <> loginRS("UserPassword") THEN
        ' ### HANDLER: USER FOUND, PASSWORD INCORRECT ###
        Session("hasAlert") = true
        Session("alertType") = "danger"
        Session("alertMessage") = "User account email or password is incorrect."
        Response.Redirect(DOMAIN & "/start.asp")
    ELSE
        IF loginRS("Confirmed") = 0 THEN
            ' ### HANDLER: USER FOUND, PASSWORD CORRECT, ACCOUNT NOT CONFIRMED VIA EMAIL ###
            Dim userId : userId = loginRS("UserId")
            Session("hasAlert") = true
            Session("alertType") = "danger"
            Session("alertMessage") = "Your account is not activated. <a href=""register/resend.asp?id=" & userId & """ style=""text-decoration:underline;color:#A94442"">Resend Email</a>"
            Call LogReport(2, "Login to unconfirmed account detected...", loginRS("UserId"))
            Response.Redirect(DOMAIN & "/start.asp")
        ELSE
            ' ### HANDLER: LOGIN SUCCESSFUL - NOW PUT USER DETAILS IN SESSION ###
            Session("userId")              = loginRS("UserId")
            Session("userFirstName")       = loginRS("FirstName")
            Session("userLastName")        = loginRS("LastName")
            Session("userFullName")        = loginRS("FirstName") & " " & loginRS("LastName")
            Session("userEmail")           = loginRS("UserEmail")
            Session("userSignUp")          = loginRS("Created")
            Session("FreeExpirationDate")  = loginRS("FreeExpirationDate")
            Session("userGroup")           = loginRS("Groups")
            Session("layoutId")            = loginRS("LayoutId")
            Session("panel1")              = loginRS("Panel1")
            Session("panel2")              = loginRS("Panel2")
            Session("panel3")              = loginRS("Panel3")
            Session("panel4")              = loginRS("Panel4")
            Session("panel5")              = loginRS("Panel5")
            Session("panel6")              = loginRS("Panel6")
            Session("loggedIn")            = true
            Session("hasAlert")            = false
            Session("alertType")           = ""
            Session("alertMessage")        = ""
            Session("activeApplicantId")   = GetMemberId()
            Session("isSuperUser")         = isSuperUser()
            Session("timeZoneOffset")      = loginRS("TimeZoneOffset")
            Session("userPaymentTierId")   = loginRS("PaymentTierId")

            Dim isLocalHost : isLocalHost = InStr(nPageUrl, "localhost") <> "0"
            Session("isLocalHost") = isLocalHost

            ' ### GET THE STRIPE PROFILE OF A USER - THIS WILL REPLACE CALLS TO CHECK PAYMENTTIER EVENTUALLY ###
            IF isLocalHost THEN
                Session("userPaymentTierId")   = loginRS("PaymentTierId")
                Session("userPaymentTierCode") = loginRS("PaymentTierCode")
                Session("userPaymentTierType") = loginRS("PaymentTierType")
            ELSE
                LoadProfileForCustomer(loginRS("UserId"))

                IF cToStr(Session("userPaymentTierId")) <> cToStr(loginRS("PaymentTierId")) THEN
                    db.Execute("UPDATE User Set PaymentTierId = " & Session("userPaymentTierId") & " WHERE UserId = " & formatDbField(Session("userId"), "int", false))
                END IF

                IF Session("userProfileStatus") = "past_due" THEN
                    Session("hasAlert") = true
                    Session("alertType") = "danger"
                    Session("alertMessage") = "Your subscription renewal failed, please update your payment information before your subscription is canceled!"
                END IF
            END IF

            ' ### ADVANCE THE LOGIN COUNT BY ONE ###
            Dim nSessions : nSessions = cInt(loginRS("NumberLogins"))
            nSessions = nSessions + 1
            Session("numberLogins") = nSessions
            loginRS.Close

            ' ### STORE NUMBER OF LOGINS IN DATABASE ###
            db.Execute("UPDATE User Set NumberLogins = " & nSessions & ", LastLogin = CURRENT_TIMESTAMP() WHERE UserId = " & formatDbField(Session("userId"), "int", false))

            ' ### SET SESSION TIMEOUT ###
            Session.Timeout = 120

            ' ### LOG THE LOGIN ###
            Call LogReport(4, Session("userFullName") & " has logged in", Session("userId"))

            ' ### GET NUMBER OF INTEGRATIONS FOR CURRENT MEMBER ###
            Dim numIntegrations : numIntegrations = CountUserIntegrations(Session("userId"))
            numIntegrations = cToInt(numIntegrations)

            ' ### UPDATE EXTERNAL ACCOUNTS ###
            IF NOT isLocalHost THEN
                IF numIntegrations > 0 THEN
                    Call UpdateExternalAccountsAtLogin
                END IF
            END IF

            ' ### REDIRECT USER TO HOME PAGE ###
            Response.Redirect("/")
        END IF
    END IF
END IF
%>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title><%=SITE_NAME%> - User Area</title>
<!--#include virtual="/includes/stylesheets.asp" -->
<link rel="stylesheet" type="text/css" href="/content/Styles/start.css"/>
</head>

<body>

<div id="login-wrapper">
    <div>
        <span id="top-button-wrapper">
            <button id="register-button-top">Join now</button>
            <button id="sign-in-top">Sign in</button>
        </span>
        <div>
            <div id="welcome-title">
                <img src="Images/logo_login.png" class="logo" alt="CalcuTrack"/>
                <div class="touts">
                    <div class="article">
                        <i class="fa fa-pencil-square-o" title="Build A Complete Financial History" aria-hidden="true"></i><p></p>
                        <div>
                            <%=GetContentBlock("PD2685FF2D")%>
                        </div>
                    </div>
                    <div class="article">
                        <i class="fa fa-bar-chart" title="Get Graphical Feedback on your Finances" aria-hidden="true"></i><p></p>
                        <div>
                            <%=GetContentBlock("4H218T5194")%>
                        </div>
                    </div>
                    <div class="article">
                        <i class="fa fa-share-alt-square" title="Share Your Financial Data with Major Institutions." aria-hidden="true"></i><p></p>
                        <div>
                            <%=GetContentBlock("Y0ZQE7D1ZX")%>
                        </div>
                    </div>
                </div>
                <p>Not registered? Join now and allow us to help you grow..!</p>
                <div id="register">
                    <button id="register-button">Join now</button>
                </div>
            </div>
        </div>
        <div id="login-element">
            <div id="login-title">
                <h1>Sign in to CalcuTrack&trade;</h1>
                <p>Enter your details below.</p>
            </div>
            <div id="alert-wrapper">
                <div id="alert">
                    <div id="alert-icon"></div>
                </div>
            </div>
            <form name="login" action="start.asp" method="post">
                <div id="login-body">
                    <div class="input-group">
                        <label>Email Address:</label>
                        <div><input type="text" maxlength="50" value="<%=Session("newUserEmail")%>" name="tbEmailAddress"/></div>
                    </div>
                    <div class="input-group">
                        <label>Password:</label>
                        <div><input type="password" maxlength="50" name="tbPassword"/></div>
                    </div>
                </div>
                <div id="login-footer">
                    <button onclick="validate();">Login</button>
                    <p id="forgot-password"><a href="password-reset/">Forgot your password?</a></p>
                </div>
            </form>
            <div id="copyright">
                <div>Copyright&nbsp;&copy;&nbsp;CalcuTrack&trade; 2017-<%=Year(Now())%></div>
            </div>
        </div>
    </div>
    <span id="mobile-copyright-footer">Copyright&nbsp;&copy;&nbsp;CalcuTrack&trade; 2017-<%=Year(Now())%></span>
</div>
<% Session("newUserEmail") = "" %>
<!-- #include virtual="/includes/javascripts.asp" -->
<script type="text/javascript" src="scripts/login.js"></script>
<% IF Request("s") = "1" THEN %>
    <script type="text/javascript">
        $(function () {
            ShowAlert(true, 'success', 'Registration completed successfully!');
        });
    </script>
<% END IF %>
<% IF Request("s") = "2" THEN %>
    <script type="text/javascript">
        $(function () {
            ShowAlert(true, 'success', 'Activation Email has been sent. Check your email!');
        });
    </script>
<% END IF %>
<% IF Session("hasAlert") THEN %>
    <script type="text/javascript">
        $(function () {
            ShowAlert(true, '<%=Session("alertType")%>', '<%=Session("alertMessage")%>');
        });
    </script>
    <% Session("hasAlert") = false %>
<% END IF %>
<script type="text/javascript">
    $(document).ready(function () {
        if ($(document).find('#summary-wrapper').length > 0)
            top.location.reload(true);
    });
</script>
</body>

</html>
<!--#include virtual="/includes/closeconnection.asp" -->
