<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<%
Dim receiptId : receiptId = Request("id")

IF Trim(receiptId & "") <> "" THEN
    ' ### DELETE ISSUER RECORD ###
    db.Execute("DELETE FROM [Receipts] WHERE [UserId] = " & formatDbField(Session("userId"), "int", false) & " AND [ReceiptId] = " & formatDbField(receiptId, "int", false))

    ' ### ADD TO SYSTEM LOG AND USER ALERT ###
    Call LogReport(1, "Receipt has been deleted", Session("userId"))
    Call SetUserAlert("success", "Receipt deleted successfully")
END IF
Response.Redirect("/receipts/")
%>
<!-- #include virtual="/includes/closeconnection.asp" -->
