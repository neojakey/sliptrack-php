        <!-- #include virtual="/includes/functions_security.asp" -->
        <%
        Dim cssSelected : cssSelected = ""
        Dim permissionsAry : permissionsAry = GetSectionPermission("prmAdmin")
        Dim canViewAdmin : canViewAdmin = GetActionPermission("view", permissionsAry)
        Dim siteUrl : siteUrl = Request.ServerVariables("URL")

        IF siteUrl = "/Default.asp" THEN cssSelected = "selected" ELSE cssSelected = "" END IF
        %>
        <div>
            <a href="/" title="Home Page" class="<%=cssSelected%>">
                <i class="fa fa-home fa-fw" aria-hidden="true"></i>
                <span>Home</span>
            </a>
        </div>
        <% IF canViewAdmin THEN %>
        <div>
            <a href="/admin/" title="Administration Section">
                <i class="fa fa-cogs fa-fw" aria-hidden="true"></i>
                <span>Admin</span>
            </a>
        </div>
        <% END IF %>
        <% IF InStr(siteUrl, "/issuers/") > 0 THEN cssSelected = "selected" ELSE cssSelected = "" END IF %>
        <div>
            <a href="/issuers/" title="Manage Issuers" class="<%=cssSelected%>">
                <i class="fa fa-id-card-o fa-fw" aria-hidden="true"></i>
                <span>Issuers</span>
            </a>
        </div>
        <% IF InStr(siteUrl, "/receipts/") > 0 THEN cssSelected = "selected" ELSE cssSelected = "" END IF %>
        <div>
            <a href="/receipts/" title="Manage Receipts" class="<%=cssSelected%>">
                <i class="fa fa-ticket fa-fw" aria-hidden="true"></i>
                <span>Receipts</span>
            </a>
        </div>
