        <%
        Dim siteUrl : siteUrl = Request.ServerVariables("URL")
        IF siteUrl = "/Default.asp" THEN cssSelected = "selected" ELSE cssSelected = "" END IF
        %>
        <div>
            <a href="/" title="Home Page" class="<%=cssSelected%>">
                <i class="fa fa-home fa-fw" aria-hidden="true"></i>
                <span>Home</span>
            </a>
        </div>
        <% IF siteUrl = "/admin/Default.asp" THEN cssSelected = "selected" ELSE cssSelected = "" END IF %>
        <div>
            <a href="/admin/" title="Administration Section" class="<%=cssSelected%>">
                <i class="fa fa-cogs fa-fw" aria-hidden="true"></i>
                <span>Admin</span>
            </a>
        </div>
        <% IF InStr(siteUrl, "/admin/users/") > 0 THEN cssSelected = "selected" ELSE cssSelected = "" END IF %>
        <div>
            <a href="/admin/users/" title="Manage Users" class="<%=cssSelected%>">
                <i class="fa fa-user fa-fw" aria-hidden="true"></i>
                <span>Users</span>
            </a>
        </div>
        <% IF InStr(siteUrl, "/admin/groups/") > 0 THEN cssSelected = "selected" ELSE cssSelected = "" END IF %>
        <div>
            <a href="/admin/groups/" title="Manage User Groups" class="<%=cssSelected%>">
                <i class="fa fa-users fa-fw" aria-hidden="true"></i>
                <span>Groups</span>
            </a>
        </div>
        <% IF InStr(siteUrl, "/admin/dropdown-menus/") > 0 THEN cssSelected = "selected" ELSE cssSelected = "" END IF %>
        <div>
            <a href="/admin/dropdown-menus/" title="Manage Dropdown Menus" class="<%=cssSelected%>">
                <i class="fa fa-list fa-fw" aria-hidden="true"></i>
                <span>Menus</span>
            </a>
        </div>
        <% IF InStr(siteUrl, "/admin/system-log/") > 0 THEN cssSelected = "selected" ELSE cssSelected = "" END IF %>
        <div>
            <a href="/admin/system-log/" title="View System Log" class="<%=cssSelected%>">
                <i class="fa fa-table fa-fw" aria-hidden="true"></i>
                <span>Log</span>
            </a>
        </div>
