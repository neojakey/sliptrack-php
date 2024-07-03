<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?=SITE_NAME?> - Receipt Area</title>
    <?php include ROOT_PATH . "includes/stylesheets.php" ?>
    <link rel="stylesheet" type="text/css" href="/receipts/css/receipt_addedit.css"/>
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
                    <span id="desktop-user-menu-name"><?=$_SESSION["userFullName"]?></span>
                    <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                </div>
            </header>
            <section>
                <h1 class="page-title">Add New Receipt</h1>
                <div class="breadcrumb">
                    <a href="/">Home</a><?=SPACER?><a href="/receipts/">Receipts</a><?=SPACER?>Add New Receipt
                </div>
                <form action="/receipts/save/" method="post" id="form-new-receipt" name="frmNewReceipt">
                    <table class="form-table">
                        <tr>
                            <td>Date <?=REQUIRED?>:</td>
                            <td><input type="date" name="tbReceiptDate" id="receipt-date"/></td>
                        </tr>
                        <tr>
                            <td>Folio Number <?=REQUIRED?>:</td>
                            <td><input type="text" class="k-textbox" name="tbReceiptFolio" id="receipt-folio" maxlength="80" style="width:400px"/></td>
                        </tr>
                        <tr>
                            <td>Receipt Number:</td>
                            <td><input type="text" class="k-textbox" name="tbReceiptNumber" id="receipt-number" maxlength="30" style="width:240px"/></td>
                        </tr>
                        <%=ShowSectionBorder()%>
                        <tr>
                            <td>Receipt Description <?=REQUIRED?>:</td>
                            <td><textarea class="k-textbox" name="taReceiptDescription" rows="4" id="receipt-description" maxlength="300" style="width:400px" placeholder="Description of Receipt"></textarea></td>
                        </tr>
                        <%=ShowSectionBorder()%>
                        <tr>
                            <td>Issuer: <?=REQUIRED?></td>
                            <td class="issuer-wrapper"><%=CreateDropmenu("IssuerId", "IssuerName", "Issuers", "", "Issuer-Id", "", "")%></td>
                        </tr>
                        <tr>
                            <td>CDFI Type <?=REQUIRED?>:</td>
                            <td class="cdfi-wrapper"><%=CreateDropdown("B4Y6797994", "", "", "CDFI-Type-Id")%></td>
                        </tr>
                        <tr>
                            <td>Payment Type <?=REQUIRED?>:</td>
                            <td class="payment-type-wrapper"><%=CreateDropdown("33JE90W645", "", "", "Payment-Type-Id")%></td>
                        </tr>
                        <%=ShowSectionBorder()%>
                        <tr>
                            <td>Sub-Total <?=REQUIRED?>:</td>
                            <td><input type="number" name="tbSubTotal" id="sub-total"/></td>
                        </tr>
                        <tr>
                            <td>IVA:</td>
                            <td><input type="number" name="tbIVA" id="iva"/></td>
                        </tr>
                        <tr>
                            <td>Total <?=REQUIRED?>:</td>
                            <td><input type="number" name="tbTotal" id="total"/></td>
                        </tr>
                        <tr>
                            <td>Discount:</td>
                            <td><input type="number" name="tbDiscount" id="discount"/></td>
                        </tr>
                    </table>
                    <div class="button-wrapper">
                        <button type="button" onclick="validate();" class="primary-btn">Submit</button>
                        <button type="button" onclick="LeavePage('/receipts/');" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <?php include ROOT_PATH . "includes/footer.php" ?>
    <?php include ROOT_PATH . "includes/javascripts.php" ?>
    <?php include ROOT_PATH . "includes/kendo_includes.php" ?>
    <?php include ROOT_PATH . "includes/alerts.php" ?>
    <script type="text/javascript" src="/receipts/scripts/receipt_addedit.js"></script>
</body>

</html>

