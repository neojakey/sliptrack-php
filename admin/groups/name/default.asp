<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<%
Dim groupId : groupId = Request("id")

Dim groupRS : Set groupRS = Server.CreateObject("ADODB.Recordset")
Dim groupSQL : groupSQL = _
    "SELECT " & _
    "   [GroupName] " & _
    "FROM " & _
    "   [Group] " & _
    "WHERE " & _
    "   [GroupId] = " & formatDbField(groupId, "int", false)
groupRS.open groupSQL, db
IF groupRS.EOF THEN
    recordNotFound("groups")
ELSE
    Dim groupName : groupName = groupRS("GroupName")
END IF
groupRS.Close
%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><%=SITE_NAME%> - Group Area</title>
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
                <h1 class="page-title">Add New Group</h1>
                <div class="breadcrumb">
                <a href="<?=BASE_URL?>/">Home</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="<?=BASE_URL?>/admin/">Administration</a>&nbsp;&nbsp;<i class="fa fa-caret-right" style="color:#ABABAB" aria-hidden="true"></i>&nbsp;&nbsp;<a href="/admin/groups/">User Groups</a><%=SPACER%>Edit Group Name
                </div>
                <form action="/admin/groups/save/" method="post" id="form-new-group" name="frmNewGroup">
                    <input type="hidden" name="hidGroupId" value="<%=groupId%>"/>
                    <table class="form-table">
                        <tr>
                            <td>Group Name <%=Application("REQUIRED")%>:</td>
                            <td><input type="text" class="k-textbox" name="tbGroupName" id="group-name" value="<%=groupName%>" maxlength="50" style="width:400px"/></td>
                        </tr>
                    </table>
                    <div class="button-wrapper">
                        <button type="button" onclick="validate();" class="primary-btn">Submit</button>
                        <button type="button" onclick="LeavePage('/admin/groups/');" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <?php include ROOT_PATH . "includes/footer.php" ?>
    <?php include ROOT_PATH . "includes/javascripts.php" ?>
    <?php include ROOT_PATH . "includes/kendo_includes.php" ?>
    <?php include ROOT_PATH . "includes/alerts.php" ?>
    <script type="text/javascript" src="/admin/groups/scripts/group_add.js"></script>
</body>

</html>

