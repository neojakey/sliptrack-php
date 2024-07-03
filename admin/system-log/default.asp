<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/functions_security.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<%
' ### DOES THE USER HAVE ADMINSTRATION PERMISSION ###
Dim adminAry : adminAry = GetSectionPermission("prmAdmin")
Dim canViewAdmin : canViewAdmin = GetActionPermission("view", adminAry)
IF NOT canViewAdmin THEN
    Call SetUserAlert("danger", "You do not have permission to access administration.")
    header("Location: " . BASE_URL ."/")
END IF

' ### DOES THE USER HAVE GROUP VIEW PERMISSION ###
Dim systemLogAry : systemLogAry = GetSectionPermission("prmSystemLog")
Dim canView : canView = GetActionPermission("view", systemLogAry)
IF NOT canView THEN
    Call SetUserAlert("danger", "You do not have permission to access the system log.")
    header("Location: " . BASE_URL ."/admin/")
END IF

Dim nEntries : nEntries = 0
Dim canDelete : canDelete = hasPermission("prmSystemLog", "delete")

IF Request.Form("ddMonth") <> "" OR LEN(Request.Form("ddMonth")) > 0 THEN
    ThisYear = Request.Form("ddYear")
    ThisMonth = Request.Form("ddMonth")
    ThisDay = Request.Form("ddDay")
    bRefresh = "No"
ELSEIF Request("nMonth") <> "" OR LEN(Request("nMonth")) > 0 THEN
    ThisYear = Request("nYear")
    ThisMonth = Request("nMonth")
    ThisDay = Request("nDay")
    bRefresh = "No"
ELSE
    ThisMonth = Month(Now())
    ThisDay = Day(Now())
    ThisYear = Year(Now())
    bRefresh = "Yes"
END IF

xDate = ThisMonth & "/" & ThisDay & "/" & ThisYear
rsStart = ConvertToMySqlDate(xDate & " 00:00:00")
rsEnd = ConvertToMySqlDate(xDate & " 23:59:59")

IF IsDate(xDate) THEN
    Dim oRSbn : Set oRSbn = Server.CreateObject("ADODB.Recordset")
    Dim strSQLCount : strSQLCount = "SELECT COUNT(LogId) AS entry_count FROM SystemLog WHERE LogUserId = '" & Request("nUser") & "' AND LogDate BETWEEN CAST('" & rsStart & "' AS DATETIME) AND CAST('" & rsEnd & "' AS DATETIME);"

    IF Request("nUser") = "" OR LEN(Request("nUser")) = 0 THEN
        strSQLCount = "SELECT COUNT(LogId) AS entry_count FROM SystemLog WHERE LogDate BETWEEN CAST('" & rsStart & "' AS DATETIME) AND CAST('" & rsEnd & "' AS DATETIME);"
    END IF

    oRSbn.open strSQLCount, db
    nEntries = oRSbn("entry_count")
    oRSbn.Close
END IF

Set oRSbn = Server.CreateObject("ADODB.Recordset")
strSQLCount = "SELECT COUNT(LogId) AS entry_count FROM SystemLog;"
oRSbn.open strSQLCount, db
Dim nTotalEntries : nTotalEntries = oRSbn("entry_count")
oRSbn.Close
%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?=SITE_NAME?> - User Area</title>
    <?php include ROOT_PATH . "includes/stylesheets.php" ?>
    <link rel="stylesheet" type="text/css" href="/admin/system-log/css/default.css"/>
</head>

<body>

<div id="page-wrapper">
    <div class="menu">
        <?php include ROOT_PATH . "includes/menu_admin.php" ?>
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
            <h1 class="page-title">System Log</h1>
            <div class="breadcrumb">
                <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;System Log
            </div>
            <form action="/admin/system-log/" method="post" name="CreateReport">
                <div id="date-select-wrapper">
                    <span>Month:</span>
                    <span><select name="ddMonth" id="log-month" onchange="this.form.submit();">
                    <option value="1"<% IF ThisMonth = 1 THEN %> selected="selected"<% END IF %>>January</option>
                    <option value="2"<% IF ThisMonth = 2 THEN %> selected="selected"<% END IF %>>February</option>
                    <option value="3"<% IF ThisMonth = 3 THEN %> selected="selected"<% END IF %>>March</option>
                    <option value="4"<% IF ThisMonth = 4 THEN %> selected="selected"<% END IF %>>April</option>
                    <option value="5"<% IF ThisMonth = 5 THEN %> selected="selected"<% END IF %>>May</option>
                    <option value="6"<% IF ThisMonth = 6 THEN %> selected="selected"<% END IF %>>June</option>
                    <option value="7"<% IF ThisMonth = 7 THEN %> selected="selected"<% END IF %>>July</option>
                    <option value="8"<% IF ThisMonth = 8 THEN %> selected="selected"<% END IF %>>August</option>
                    <option value="9"<% IF ThisMonth = 9 THEN %> selected="selected"<% END IF %>>September</option>
                    <option value="10"<% if ThisMonth = 10 then %> selected="selected"<% END IF %>>October</option>
                    <option value="11"<% if ThisMonth = 11 then %> selected="selected"<% END IF %>>November</option>
                    <option value="12"<% if ThisMonth = 12 then %> selected="selected"<% END IF %>>December</option>
                    </select></span>
                    <span>Day:</span>
                    <span><select name="ddDay" id="log-day" onchange="this.form.submit();">
                    <option value="1"<% IF ThisDay = 1 THEN %> selected="selected"<% END IF %>>01</option>
                    <option value="2"<% IF ThisDay = 2 THEN %> selected="selected"<% END IF %>>02</option>
                    <option value="3"<% IF ThisDay = 3 THEN %> selected="selected"<% END IF %>>03</option>
                    <option value="4"<% IF ThisDay = 4 THEN %> selected="selected"<% END IF %>>04</option>
                    <option value="5"<% IF ThisDay = 5 THEN %> selected="selected"<% END IF %>>05</option>
                    <option value="6"<% IF ThisDay = 6 THEN %> selected="selected"<% END IF %>>06</option>
                    <option value="7"<% IF ThisDay = 7 THEN %> selected="selected"<% END IF %>>07</option>
                    <option value="8"<% IF ThisDay = 8 THEN %> selected="selected"<% END IF %>>08</option>
                    <option value="9"<% IF ThisDay = 9 THEN %> selected="selected"<% END IF %>>09</option>
                    <option value="10"<% IF ThisDay = 10 THEN %> selected="selected"<% END IF %>>10</option>
                    <option value="11"<% IF ThisDay = 11 THEN %> selected="selected"<% END IF %>>11</option>
                    <option value="12"<% IF ThisDay = 12 THEN %> selected="selected"<% END IF %>>12</option>
                    <option value="13"<% IF ThisDay = 13 THEN %> selected="selected"<% END IF %>>13</option>
                    <option value="14"<% IF ThisDay = 14 THEN %> selected="selected"<% END IF %>>14</option>
                    <option value="15"<% IF ThisDay = 15 THEN %> selected="selected"<% END IF %>>15</option>
                    <option value="16"<% IF ThisDay = 16 THEN %> selected="selected"<% END IF %>>16</option>
                    <option value="17"<% IF ThisDay = 17 THEN %> selected="selected"<% END IF %>>17</option>
                    <option value="18"<% IF ThisDay = 18 THEN %> selected="selected"<% END IF %>>18</option>
                    <option value="19"<% IF ThisDay = 19 THEN %> selected="selected"<% END IF %>>19</option>
                    <option value="20"<% IF ThisDay = 20 THEN %> selected="selected"<% END IF %>>20</option>
                    <option value="21"<% IF ThisDay = 21 THEN %> selected="selected"<% END IF %>>21</option>
                    <option value="22"<% IF ThisDay = 22 THEN %> selected="selected"<% END IF %>>22</option>
                    <option value="23"<% IF ThisDay = 23 THEN %> selected="selected"<% END IF %>>23</option>
                    <option value="24"<% IF ThisDay = 24 THEN %> selected="selected"<% END IF %>>24</option>
                    <option value="25"<% IF ThisDay = 25 THEN %> selected="selected"<% END IF %>>25</option>
                    <option value="26"<% IF ThisDay = 26 THEN %> selected="selected"<% END IF %>>26</option>
                    <option value="27"<% IF ThisDay = 27 THEN %> selected="selected"<% END IF %>>27</option>
                    <option value="28"<% IF ThisDay = 28 THEN %> selected="selected"<% END IF %>>28</option>
                    <option value="29"<% IF ThisDay = 29 THEN %> selected="selected"<% END IF %>>29</option>
                    <option value="30"<% IF ThisDay = 30 THEN %> selected="selected"<% END IF %>>30</option>
                    <option value="31"<% IF ThisDay = 31 THEN %> selected="selected"<% END IF %>>31</option>
                    </select></span>
                    <span>Year:</span>
                    <span><select name="ddYear" id="log-year" onchange="this.form.submit();"><%
                    nYear = Application("START_YEAR")
                    FOR YearCount = Application("START_YEAR") TO Application("CURRENT_YEAR") %>
                    <option value="<%=nYear%>"<% IF cInt(ThisYear) = nYear THEN %> selected="selected"<% END IF %>><%=nYear%></option>
                    <% nYear = nYear + 1
                    NEXT %></select></span>
                    <span><% IF Request("nUser") <> "" OR LEN(Request("nUser")) > 0 THEN %>&nbsp;&#149; <a href="system_log.asp?nMonth=<%=ThisMonth%>&nYear=<%=ThisYear%>&nDay=<%=ThisDay%>">No User</a><% END IF %></span>
                </div>
            </form>
            <div id="event-overview">Displaying <b><%=nEntries%></b> Results&nbsp;|&nbsp;Total System Log Entries: <b><%=nTotalEntries%></b></div>
            <table class="data-grid">
                <thead>
                    <tr>
                        <th class="tac" colspan="2">Date</th>
                        <th style="width:100px">Time</th>
                        <th style="width:449px">Description</th>
                        <th style="width:154px">User</th>
                        <th style="width:20px">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <%
                    IF IsDate(xDate) THEN ' ## IS IT A VALID DATE?
                        Set oRSbt = Server.CreateObject("ADODB.Recordset")
                        IF Request("nUser") = "" OR LEN(Request("nUser")) = 0 THEN
                            strSQL = "SELECT " _
                                   & "  sl.[LogType], " _
                                   & "  sl.[LogDate], " _
                                   & "  sl.[LogText], " _
                                   & "  sl.[LogId], " _
                                   & "  sl.[LogUserId], " _
                                   & "  u.[FirstName], " _
                                   & "  u.[LastName] " _
                                   & "FROM " _
                                   & "  [SystemLog] AS sl" _
                                   & "  INNER JOIN [User] AS u ON sl.[LogUserId] = u.[UserId] " _
                                   & "WHERE " _
                                   & "  [LogDate] BETWEEN CAST('" & rsStart & "' AS DATETIME) AND CAST('" & rsEnd & "' AS DATETIME) " _
                                   & "ORDER BY " _
                                   & "  [LogID] DESC;"
                        ELSE
                            strSQL = "SELECT " _
                                   & "  sl.[LogType], " _
                                   & "  sl.[LogDate], " _
                                   & "  sl.[LogText], " _
                                   & "  sl.[LogId], " _
                                   & "  sl.[LogUserId], " _
                                   & "  u.[FirstName], " _
                                   & "  u.[LastName] " _
                                   & "FROM " _
                                   & "  [SystemLog] AS sl" _
                                   & "  INNER JOIN [User] AS u ON sl.[LogUserId] = u.[UserId] " _
                                   & "WHERE " _
                                   & "  sl.[LogUserId] = '" & Request("nUser") & "' " _
                                   & "  AND sl.[LogDate] BETWEEN CAST('" & rsStart & "' AS DATETIME) AND CAST('" & rsEnd & "' AS DATETIME) " _
                                   & "ORDER BY " _
                                   & "  sl.[LogID] DESC;"
                        END IF
                        oRSbt.open strSQL, db
                        IF oRSbt.EOF THEN
                            %><tr class="h30">
                                <td colspan="6" class="tac">No Results Found for your Criteria</td>
                            </tr><%
                        ELSE
                            oRSbt.MoveFirst
                            DO WHILE NOT oRSbt.EOF
                                Dim logType : logType = "<i class=""fa fa-info-circle"" style=""color:#7AC1FF"" aria-hidden=""true""></i>"
                                IF oRSbt("LogType") = "2" THEN
                                    logType = "<i class=""fa fa-exclamation-triangle"" style=""color:#F2D757"" aria-hidden=""true""></i>"
                                ELSEIF oRSbt("LogType") = "3" THEN
                                    logType = "<i class=""fa fa-times-circle"" style=""color:#F60F0F"" aria-hidden=""true""></i>"
                                ELSEIF oRSbt("LogType") = "4" THEN
                                    logType = "<i class=""fa fa-sign-in"" style=""color:#881280"" aria-hidden=""true""></i>"
                                ELSEIF oRSbt("LogType") = "5" THEN
                                    logType = "<i class=""fa fa-sign-out"" style=""color:#881280"" aria-hidden=""true""></i>"
                                END IF
                                rsDateTimeArray = Split(oRSbt("LogDate"), " ")
                                rsDateArray = Split(rsDateTimeArray(0), "/")
                                %>
                                <tr>
                                    <td style="text-align:center; width:20px"><%=logType%></td>
                                    <td><%=rsDateArray(0)%>/<%=rsDateArray(1)%>/<%=rsDateArray(2)%></td>
                                    <td><%=rsDateTimeArray(1)%>&nbsp;<%=rsDateTimeArray(2)%></td>
                                    <td><%=oRSbt("LogText")%></td>
                                    <td><a href="system_log.asp?nMonth=<%=ThisMonth%>&nYear=<%=ThisYear%>&nDay=<%=ThisDay%>&nUser=<%=oRSbt("LogUserId")%>"><%=oRSbt("FirstName") & " " & oRSbt("LastName")%></a></td>
                                    <td><% IF canDelete THEN %>
                                    <a href="javascript:void(0);" onclick="ConfirmEntryDelete('<%=oRSbt("LogId")%>');" title="Delete"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                                    <% ELSE %>
                                    <i class="fa fa-times fa-fw disabled" aria-hidden="true" title="You don't have permission to delete system log events."></i>
                                    <% END IF %></td>
                                </tr>
                                <%
                                oRSbt.movenext
                            LOOP
                        END IF
                        oRSbt.Close
                    ELSE
                        %>
                        <tr>
                            <td class="tac" colspan="2">Date</td>
                            <td style="width:100px">Time</td>
                            <td style="width:449px">Description</td>
                            <td style="width:154px">User</td>
                            <td style="width:20px">&nbsp;</td>
                        </tr>
                        <tr class="h30">
                            <td style="text-align:center; color:darkred" class="tac" colspan="6">You have selected an invalid date.  Please re-select you required date.</td>
                        </tr>
                        <%
                    END IF ' ### IF IsDate(xDate) 
                    %>
                </tbody>
            </table>
        </section>
    </div>
</div>
<?php include ROOT_PATH . "includes/footer.php" ?>
<?php include ROOT_PATH . "includes/javascripts.php" ?>
<?php include ROOT_PATH . "includes/kendo_includes.php" ?>
<?php include ROOT_PATH . "includes/alerts.php" ?>
<script type="text/javascript" src="/admin/system-log/scripts/systemlog_initialize.js"></script>
<script type="text/javascript">
    function ConfirmEntryDelete(LogNum) {
        var agree = confirm("Are you sure you wish to delete this log entry?\n");
        if (agree) {
            document.location.href = "delete.asp?id=" + LogNum;
        }
    }
</script>
</body>

</html>

