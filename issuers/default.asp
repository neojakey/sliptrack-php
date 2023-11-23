<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/functions_security.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><%=SITE_NAME%> - Issuer</title>
    <!--#include virtual="/includes/stylesheets.asp" -->
    <link type="text/css" rel="stylesheet" href="/css/pagination.css"/>
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
                <h1 class="page-title">Issuers</h1>
                <div class="breadcrumb">
                    <a href="/">Home</a><%=SPACER%>Issuers
                </div>
                <div class="add-button-wrapper">
                    <button type="button" class="primary-btn" onclick="location.href='/issuers/add/';"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;&nbsp;Add Issuer</button>
                </div>
                <div id="alert-wrapper" style="display:none">
                    <div id="alert">
                        <div id="alert-icon"></div>
                    </div>
                </div>
                <%
                Dim recordsOnPage : recordsOnPage = 15
                Dim pageNumber : pageNumber = Request("page")
                IF Trim(pageNumber & "") = "" THEN pageNumber = 1
                pageNumber = cInt(pageNumber)

                Dim IssuersRS : Set IssuersRS = Server.CreateObject("ADODB.Recordset")
                Dim IssuersSQL : IssuersSQL = _
                    "SELECT " & _
                    "   i.[IssuerId], " & _
                    "   i.[IssuerName], " & _
                    "   i.[RFC], " & _
                    "   ddf.[DropdownFieldName], " & _
                    "   (SELECT COUNT([ReceiptId]) FROM [Receipts] AS r WHERE r.[IssuerId] = i.[IssuerId]) AS nUsed " & _
                    " FROM " & _
                    "   [Issuers] AS i " & _
                    "   INNER JOIN [dropdownfields] AS ddf ON ddf.[DropdownFieldId] = i.[TaxRegimeId] " & _
                    " WHERE " & _
                    "   i.[UserId] = " & formatDbField(Session("userId"), "int", false) & " " & _
                    " ORDER BY " & _
                    "   i.[IssuerName]"
                IssuersRS.CursorLocation = 3
                IssuersRS.open IssuersSQL, db

                IssuersRS.PageSize = recordsOnPage
                Dim pageCount : pageCount = IssuersRS.PageCount
                IF pageNumber < 1 OR pageNumber > pageCount THEN pageNumber = 1
                recordsOnPage = pageNumber * recordsOnPage
                %>
                <table class="data-grid">
                    <% IF IssuersRS.EOF THEN %>
                    <tbody>
                        <tr class="h30">
                            <td colspan="7" class="fb tac">No issuers have been created</td>
                        </tr>
                    </tbody>
                    <% ELSE %>
                    <thead>
                        <tr>
                            <th style="width:15%">RFC</th>
                            <th style="width:75%">Issuer Name</th>
                            <th style="width:10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <%
                        IssuersRS.MoveFirst
                        IssuersRS.AbsolutePage = pageNumber
                        DO WHILE NOT (IssuersRS.EOF OR IssuersRS.AbsolutePage <> pageNumber)
                            %>
                            <tr>
                                <td><%=IssuersRS("RFC")%></td>
                                <td><%=IssuersRS("IssuerName")%></td>
                                <td>
                                    <div class="data-grid-icons">
                                        <a href="/issuers/edit/?id=<%=IssuersRS("IssuerId")%>" title="Edit Issuer"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                        <% IF cToInt(IssuersRS("nUsed")) > 0 THEN %>
                                        <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="This issuer is currently assigned and cannot be deleted"></i>
                                        <% ELSE %>
                                        <a href="javascript:void(0);" onclick="ConfirmIssuerDelete('<%=IssuersRS("IssuerId")%>');" title="Delete"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                                        <% END IF %>
                                    </div>
                                </td>
                            </tr>
                            <%
                            IssuersRS.MoveNext
                        LOOP
                        END IF
                        IssuersRS.Close
                        %>
                    </tbody>
                </table>
                <div class="pagination-wrapper">
                    <%=ShowPagination(pageNumber, pageCount, "/issuers/", "", "")%>
                </div>
            </section>
        </div>
    </div>
    <!-- #include virtual="/includes/footer.asp" -->
    <!-- #include virtual="/includes/javascripts.asp" -->
    <!-- #include virtual="/includes/kendo_includes.asp" -->
    <!-- #include virtual="/includes/alerts.asp" -->
    <script type="text/javascript" src="/issuers/scripts/default.js"></script>
</body>

</html>
<!--#include virtual="/includes/closeconnection.asp" -->
