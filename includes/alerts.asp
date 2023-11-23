<% IF Trim(Session("hasAlert") & "") = "" THEN Session("hasAlert") = false %>
<% IF Session("hasAlert") THEN %>
<script type="text/javascript">
$(function () {

    ShowAlert(<%=lCase(Session("hasAlert"))%>, '<%=Session("alertType")%>', '<%=Replace(Session("alertMessage"), "'", "\'")%>');
});
</script>
<%
END IF
Session("hasAlert") = false
Session("alertType") = ""
Session("alertMessage") = ""
%>