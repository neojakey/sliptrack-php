<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><%=SITE_NAME%> - Issuer Area</title>
    <!--#include virtual="/includes/stylesheets.asp" -->
    <link rel="stylesheet" type="text/css" href="/issuers/css/issuer_addedit.css"/>
</head>

<body>
    <div id="page-wrapper">
        <div class="menu">
            <!--#include virtual="/includes/menu.asp" -->
        </div>
        <div class="main">
            <header>
                <div></div>
                <div class="notification-wrapper">
                    <a href="javascript:void(0);"><i class="fa fa-bell" aria-hidden="true"></i></a>
                    <a href="javascript:void(0);"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                </div>
                <div class="user-wrapper" id="user-menu-link">
                    <span id="desktop-user-menu-bars"><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>
                    <span id="desktop-user-menu-name"><%=Session("userFullName")%></span>
                    <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                </div>
            </header>
            <section>
                <h1 class="page-title">Add New Issuer</h1>
                <div class="breadcrumb">
                    <a href="/">Home</a><%=SPACER%><a href="/issuers/">Issuers</a><%=SPACER%>Add New Issuer
                </div>
                <form action="/issuers/save/" method="post" id="form-new-issuer" name="frmNewIssuer">
                    <table class="form-table">
                        <tr>
                            <td>Issuer RFC <%=Application("REQUIRED")%>:</td>
                            <td><input type="text" class="k-textbox" name="tbIssuerRFC" id="issuer-rfc" maxlength="15" style="width:150px"/></td>
                        </tr>
                        <tr>
                            <td>Issuer Name <%=Application("REQUIRED")%>:</td>
                            <td><input type="text" class="k-textbox" name="tbIssuerName" id="issuer-name" maxlength="80" style="width:500px"/></td>
                        </tr>
                        <%=ShowSectionBorder()%>
                        <tr>
                            <td>Tax Regime <%=Application("REQUIRED")%>:</td>
                            <td class="tax-regime-wrapper"><%=CreateDropdown("64AFG03R01", "", "", "Tax-Regime-Id")%></td>
                        </tr>
                        <%=ShowSectionBorder()%>
                        <tr>
                            <td>Street Address:</td>
                            <td><input type="text" class="k-textbox" name="tbStreetAddress" id="street-address" maxlength="60" style="width:350px"/></td>
                        </tr>
                        <tr>
                            <td>Colonia:</td>
                            <td><input type="text" class="k-textbox" name="tbColonia" id="colonia" maxlength="60" style="width:400px"/></td>
                        </tr>
                        <tr>
                            <td>City:</td>
                            <td><input type="text" class="k-textbox" name="tbCity" id="city" maxlength="60" style="width:200px"/></td>
                        </tr>
                        <tr>
                            <td>State:</td>
                            <td class="state-wrapper"><%=CreateDropdown("C7D87PMG2E", "", "", "State-Id")%></td>
                        </tr>
                        <tr>
                            <td>Post Code:</td>
                            <td><input type="text" class="k-textbox" name="tbPostCode" id="post-code" maxlength="10" style="width:200px"/></td>
                        </tr>
                        <tr>
                            <td>Country:</td>
                            <td><input type="text" class="k-textbox" name="tbCountry" id="country" value="Mexico" maxlength="50" style="width:200px"/></td>
                        </tr>
                        <%=ShowSectionBorder()%>
                        <tr>
                            <td>Phone Number:</td>
                            <td><input type="tel" maxlength="20" id="phone" name="tbPhone" style="width:220px"/></td>
                        </tr>
                        <tr>
                            <td>Email Address:</td>
                            <td><input type="text" class="k-textbox" name="tbEmail" id="email" placeholder="help@walmart.com.mx" maxlength="50" style="width:220px"/></td>
                        </tr>
                        <tr>
                            <td>Website:</td>
                            <td><input type="text" class="k-textbox" name="tbWebsite" id="website" placeholder="www.google.com" maxlength="60" style="width:300px"/></td>
                        </tr>
                    </table>
                    <div class="button-wrapper">
                        <button type="button" onclick="validate();" class="primary-btn">Submit</button>
                        <button type="button" onclick="LeavePage('/issuers/');" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <?php include ROOT_PATH . "includes/footer.php" ?>
    <?php include ROOT_PATH . "includes/javascripts.php" ?>
    <?php include ROOT_PATH . "includes/kendo_includes.php" ?>
    <?php include ROOT_PATH . "includes/alerts.php" ?>
    <script type="text/javascript" src="/issuers/scripts/issuer_addedit.js"></script>
</body>

</html>

