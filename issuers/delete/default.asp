<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<%
Dim issuerId : issuerId = Request("id")

IF Trim(issuerId & "") <> "" THEN
    ' ### DELETE ISSUER RECORD ###
    db.Execute("DELETE FROM [Issuers] WHERE [UserId] = " & formatDbField($_SESSION["userId"), "int", false) & " AND [IssuerId] = " & formatDbField(issuerId, "int", false))

    ' ### ADD TO SYSTEM LOG AND USER ALERT ###
    Call LogReport(1, "Issuer has been deleted", $_SESSION["userId"))
    Call SetUserAlert("success", "Issuer deleted successfully")
END IF
header("Location: " . BASE_URL ."/issuers/")
%>
<!-- #include virtual="/includes/closeconnection.asp" -->
