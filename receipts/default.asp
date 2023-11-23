<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/functions_security.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><%=SITE_NAME%> - Receipt</title>
    <!--#include virtual="/includes/stylesheets.asp" -->
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
                <h1 class="page-title">Receipts</h1>
                <div class="breadcrumb">
                    <a href="/">Home</a><%=SPACER%>Receipts
                </div>
                <div class="add-button-wrapper">
                    <button type="button" class="primary-btn" onclick="location.href='/receipts/add/';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add Receipt</button>
                </div>
                <div id="alert-wrapper" style="display:none">
                    <div id="alert">
                        <div id="alert-icon"></div>
                    </div>
                </div>
                <%
                Dim ReceiptsRS : Set ReceiptsRS = Server.CreateObject("ADODB.Recordset")
                Dim ReceiptsSQL : ReceiptsSQL = _
                    "SELECT " & _
                    "   r.[ReceiptId], " & _
                    "   r.[ReceiptDate], " & _
                    "   i.[IssuerName], " & _
                    "   r.[ReceiptDescription], " & _
                    "   r.[SubTotal], " & _
                    "   r.[IVA], " & _
                    "   r.[Total] " & _
                    " FROM " & _
                    "   [Receipts] AS r " & _
                    "   INNER JOIN [Issuers] AS i ON i.[IssuerId] = r.[IssuerId] " & _
                    " WHERE " & _
                    "   i.[UserId] = " & formatDbField(Session("userId"), "int", false) & " " & _
                    " ORDER BY " & _
                    "   r.[ReceiptDate] DESC"
                ReceiptsRS.open ReceiptsSQL, db
                %>
                <table class="data-grid">
                    <% IF ReceiptsRS.EOF THEN %>
                    <tbody>
                        <tr class="h30">
                            <td colspan="7" class="fb tac">No receipts have been created</td>
                        </tr>
                    </tbody>
                    <% ELSE %>
                    <thead>
                        <tr>
                            <th style="width:10%">Date</th>
                            <th style="width:20%">Issuer</th>
                            <th style="width:30%">Description</th>
                            <th style="width:10%">Sub-Total</th>
                            <th style="width:10%">IVA</th>
                            <th style="width:10%">Total</th>
                            <th style="width:10%">Tools</th>
                        </tr>
                    </thead>
                    <tbody>
                        <%
                        ReceiptsRS.MoveFirst
                        DO WHILE NOT ReceiptsRS.EOF
                            %>
                            <tr>
                                <td><%=ShowDateTime(ReceiptsRS("ReceiptDate"), 2)%></td>
                                <td><%=ReceiptsRS("IssuerName")%></td>
                                <td><%=ReceiptsRS("ReceiptDescription")%></td>
                                <td><%=ShowAsCurrency(ReceiptsRS("SubTotal"))%></td>
                                <td><%=ShowAsCurrency(ReceiptsRS("IVA"))%></td>
                                <td><%=ShowAsCurrency(ReceiptsRS("Total"))%></td>
                                <td>
                                    <div class="data-grid-icons">
                                        <a href="/receipts/edit/?id=<%=ReceiptsRS("ReceiptId")%>" title="Edit Receipt"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                        <a href="javascript:void(0);" onclick="ConfirmReceiptDelete('<%=ReceiptsRS("ReceiptId")%>');" title="Delete"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <%
                            ReceiptsRS.movenext
                        LOOP
                        END IF
                        ReceiptsRS.Close
                        %>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
    <!-- #include virtual="/includes/footer.asp" -->
    <!-- #include virtual="/includes/javascripts.asp" -->
    <!-- #include virtual="/includes/kendo_includes.asp" -->
    <!-- #include virtual="/includes/alerts.asp" -->
    <script type="text/javascript" src="/receipts/scripts/default.js"></script>
</body>

</html>
<!--#include virtual="/includes/closeconnection.asp" -->
