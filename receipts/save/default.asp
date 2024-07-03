<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<%
Dim receiptId : receiptId = Request.Form("hidReceiptId")
Dim receiptDate : receiptDate = Request.Form("tbReceiptDate")
Dim receiptFolio : receiptFolio = Request.Form("tbReceiptFolio")
Dim receiptNumber : receiptNumber = Request.Form("tbReceiptNumber")
Dim receiptDescription : receiptDescription = Request.Form("taReceiptDescription")
Dim issuerId : issuerId = Request.Form("ddIssuerId")
Dim cdfiTypeId : cdfiTypeId = Request.Form("ddCDFITypeId")
Dim paymentTypeId : paymentTypeId = Request.Form("ddPaymentTypeId")
Dim subTotal : subTotal = Request.Form("tbSubTotal")
Dim iva : iva = Request.Form("tbIVA")
Dim total : total = Request.Form("tbTotal")
Dim discount : discount = Request.Form("tbDiscount")

receiptNumber = cToStr(receiptNumber)
IF cToStr(iva) = "0" THEN iva = ""
IF cToStr(discount) = "0" THEN discount = ""

IF Trim(receiptId & "") <> "" THEN
    ' ### UPDATE ISSUER RECORD ###
    Dim strSQL : strSQL = _
        "UPDATE [Receipts] SET " & _
        "   [ReceiptDate] = " & formatDbField(receiptDate, "datetime", false) & ", " & _
        "   [ReceiptFolioNumber] = " & formatDbField(receiptFolio, "text", false) & ", " & _
        "   [ReceiptDescription] = " & formatDbField(receiptDescription, "text", false) & ", " & _
        "   [ReceiptNumber] = " & formatDbField(receiptNumber, "text", true) & ", " & _
        "   [IssuerId] = " & formatDbField(issuerId, "int", false) & ", " & _
        "   [CFDITypeId] = " & formatDbField(cdfiTypeId, "int", false) & ", " & _
        "   [PaymentTypeId] = " & formatDbField(paymentTypeId, "int", false) & ", " & _
        "   [SubTotal] = " & formatDbField(subTotal, "decimal", false) & ", " & _
        "   [IVA] = " & formatDbField(iva, "decimal", true) & ", " & _
        "   [Total] = " & formatDbField(total, "decimal", false) & ", " & _
        "   [Discount] = " & formatDbField(discount, "decimal", true) & ", " & _
        "   [LastUpdated] = CURRENT_TIMESTAMP() " & _
        " WHERE [ReceiptId] = " & formatDbField(receiptId, "int", false) & ";"
    db.Execute(strSQL)

    ' ### ADD TO SYSTEM LOG, WALL EVENT, AND USER ALERT ###
    Call LogReport(1, "The Receipt has been edited", $_SESSION["userId"))
    Call SetUserAlert("success", "Receipt edited successfully")
ELSE
    ' ### INSERT ISSUER RECORD ###
    Dim receiptColumns : receiptColumns = "ReceiptDate,ReceiptFolioNumber,ReceiptDescription,ReceiptNumber,IssuerId,CFDITypeId,PaymentTypeId,SubTotal,IVA,Total,Discount,UserId"
    Dim receiptValues : receiptValues = formatDbField(receiptDate, "datetime", false) & "," & formatDbField(receiptFolio, "text", false) & "," & formatDbField(receiptDescription, "text", false) & "," & formatDbField(receiptNumber, "text", true) & "," & formatDbField(issuerId, "int", false) & "," & formatDbField(cdfiTypeId, "int", false) & "," & formatDbField(paymentTypeId, "int", false) & "," & formatDbField(subTotal, "decimal", false) & "," & formatDbField(iva, "decimal", true) & "," & formatDbField(total, "decimal", false) & "," & formatDbField(discount, "decimal", true) & "," & formatDbField($_SESSION["userId"), "int", false)
    receiptId = InsertRecord("ReceiptId", "Receipts", receiptColumns, receiptValues)

    ' ### ADD TO SYSTEM LOG, WALL EVENT, AND USER ALERT ###
    Call LogReport(1, "The Receipt has been added", $_SESSION["userId"))
    Call SetUserAlert("success", "Receipt added successfully")
END IF

header("Location: " . BASE_URL ."/receipts/")
%>
<!-- #include virtual="/includes/closeconnection.asp" -->
