<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Contact Us - <?=SITE_NAME?></title>
    <?php include ROOT_PATH . "includes/stylesheets.php" ?>
    <link rel="stylesheet" type="text/css" href="/content/Styles/responsive.css"/>
    <link rel="stylesheet" type="text/css" href="/contact-us/css/default.css"/>
    <link rel="stylesheet" type="text/css" href="/content/Styles/social-media-bar.css"/>
</head>

<body>

<div id="page-wrapper">
    <%=GetHeader("Contact Us")%>
    <div id="breadcrumb">
        <div>
            <ul class="breadcrumb-trail">
                <%=BreadcrumbHome()%>
                <li>Contact Us</li>
            </ul>
        </div>
        <div>&nbsp;</div>
    </div>
    <div id="alert-wrapper">
        <div id="alert">
            <div id="alert-icon"></div>
        </div>
    </div>
    <div id="email-form">
        <div>
            <h2 style="margin-top:0">Let Us Know</h2>
            <p>Please use the form on this page for any questions or comments related to 
            our product. Many thanks.</p>
            <h2 style="margin-top: 26px">Follow Us on Social Media</h2>
            <p>Please follow us on our social media channels and get regular updates about 
            development of the site and insights into the future of VCA.</p>
            <div id="social-media-bar">
                <ul>
                    <li>
                        <a href="https://www.facebook.com/virtualclaimsadjuster/" target="_blank">
                            <i class="fa wp-icon fa-facebook-f fa-lg" id="J0UL0ZIEKS"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.linkedin.com/company/virtual-claims-adjuster" target="_blank">
                            <i class="fa wp-icon fa-linkedin fa-lg" id="BPOG0C9700"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com/virtualclaims" target="_blank">
                            <i class="fa wp-icon fa-twitter fa-lg"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        <div>
            <h1 class="tab-header">Contact Us</h1>
            <% IF Request("sent") = "1" THEN %>
            <div id="sent-message-wrapper">
                Your email was sent successfully. Many thanks for your inquiry, we will endevour to contact you within 48 hours.<br><br>
                <a href="<%=DOMAIN%>">Go Back to Home Page</a>
            </div>
            <% ELSE %>
            <form method="post" action="send-email.asp" id="contact-us-form">
                <input type="hidden" name="hidUserId" id="hid-user-id" value="<%=Session("userId")%>"/>
                <input type="hidden" name="hidPaymentTier" id="hid-payment-tier" value="<%=Session("userPaymentTierCode")%>"/>
                <table border="0" class="data-form">
                    <tr>
                        <td>Full Name <?=REQUIRED?>:</td>
                    </tr>
                    <tr>
                        <td><input type="text" class="k-textbox" name="tbFullName" id="full-name" value="<?=$_SESSION["userFullName"]?>" maxlength="200"/></td>
                    </tr>
                    <tr class="height6">
                        <td></td>
                    </tr>
                    <tr>
                        <td>Email Address <?=REQUIRED?>:</td>
                    </tr>
                    <tr>
                        <td><input type="text" class="k-textbox" name="tbEmailAddress" id="email-address" value="<%=Session("userEmail")%>" maxlength="200"/></td>
                    </tr>
                    <tr class="height6">
                        <td></td>
                    </tr>
                    <tr>
                        <td>Subject <?=REQUIRED?>:</td>
                    </tr>
                    <tr>
                        <td><input type="text" class="k-textbox" name="tbSubject" id="subject" maxlength="80"/></td>
                    </tr>
                    <tr class="height6">
                        <td></td>
                    </tr>
                    <tr>
                        <td>Description <?=REQUIRED?>:</td>
                    </tr>
                    <tr>
                        <td><textarea class="k-textbox" name="taDescription" id="description" maxlength="2000"></textarea></td>
                    </tr>
                    <tr class="height15">
                        <td></td>
                    </tr>
                    <tr>
                        <td class="form-button-wrapper">
                            <ul id="save-panel-buttons">
                                <li><button type="button" class="primary-btn" tabindex="6" onclick="validate()"><i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;&nbsp;Send Email</button></li>
                                <li><button type="button" class="cancel-btn"tabindex="7" onclick="LeavePage('/');"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Cancel</button></li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </form>
            <% END IF %>
        </div>
    </div>
    <?php include ROOT_PATH . "includes/footer.php" ?>
</div>
<?php include ROOT_PATH . "includes/javascripts.php" ?>
<script type="text/javascript" src="<%=DOMAIN%>/contact-us/scripts/validate.js"></script>
<?php include ROOT_PATH . "includes/alerts.php" ?>
<script type="text/javascript">
    $(function () {
        $('#subject').focus();
    })
</script>
</body>

</html>

