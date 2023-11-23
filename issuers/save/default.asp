<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<%
Dim issuerId : issuerId = Request.Form("hidIssuerId")
Dim issuerRFC : issuerRFC = Request.Form("tbIssuerRFC")
Dim issuerName : issuerName = Request.Form("tbIssuerName")
Dim taxRegimeId : taxRegimeId = Request.Form("ddTaxRegimeId")
Dim streetAddress : streetAddress = Request.Form("tbStreetAddress")
Dim colonia : colonia = Request.Form("tbColonia")
Dim city : city = Request.Form("tbCity")
Dim stateId : stateId = Request.Form("ddStateId")
Dim postCode : postCode = Request.Form("tbPostCode")
Dim country : country = Request.Form("tbCountry")
Dim phone : phone = Request.Form("tbPhone")
Dim email : email = Request.Form("tbEmail")
Dim website : website = Request.Form("tbWebsite")

IF Trim(issuerId & "") <> "" THEN
    ' ### UPDATE ISSUER RECORD ###
    Dim strSQL : strSQL = _
        "UPDATE [Issuers] SET " & _
        "   RFC = " & formatDbField(issuerRFC, "text", false) & ", " & _
        "   IssuerName = " & formatDbField(issuerName, "text", false) & ", " & _
        "   TaxRegimeId = " & formatDbField(taxRegimeId, "int", false) & ", " & _
        "   StreetAddress = " & formatDbField(streetAddress, "text", true) & ", " & _
        "   Colonia = " & formatDbField(colonia, "text", true) & ", " & _
        "   City = " & formatDbField(city, "text", true) & ", " & _
        "   StateId = " & formatDbField(stateId, "int", true) & ", " & _
        "   PostCode = " & formatDbField(postCode, "text", true) & ", " & _
        "   Country = " & formatDbField(country, "text", true) & ", " & _
        "   Phone = " & formatDbField(phone, "text", true) & ", " & _
        "   EmailAddress = " & formatDbField(email, "text", true) & ", " & _
        "   Url = " & formatDbField(website, "text", true) & ", " & _
        "   LastUpdated = CURRENT_TIMESTAMP() " & _
        " WHERE IssuerId = " & formatDbField(issuerId, "int", false) & ";"
    db.Execute(strSQL)

    ' ### ADD TO SYSTEM LOG, WALL EVENT, AND USER ALERT ###
    Call LogReport(1, "The Issuer has been edited", Session("userId"))
    Call SetUserAlert("success", "Issuer edited successfully")
ELSE
    ' ### INSERT ISSUER RECORD ###
    Dim issuerColumns : issuerColumns = "RFC,IssuerName,TaxRegimeId,StreetAddress,Colonia,City,StateId,PostCode,Country,Phone,EmailAddress,Url,UserId"
    Dim issuerValues : issuerValues = formatDbField(issuerRFC, "text", false) & "," & formatDbField(issuerName, "text", false) & "," & formatDbField(taxRegimeId, "int", false) & "," & formatDbField(streetAddress, "text", true) & "," & formatDbField(colonia, "text", true) & "," & formatDbField(city, "text", true) & "," & formatDbField(stateId, "int", true) & "," & formatDbField(postCode, "text", true) & "," & formatDbField(country, "text", true) & "," & formatDbField(phone, "text", true) & "," & formatDbField(email, "text", true) & "," & formatDbField(website, "text", true) & "," & formatDbField(Session("userId"), "int", false)
    issuerId = InsertRecord("IssuerId", "Issuers", issuerColumns, issuerValues)

    ' ### ADD TO SYSTEM LOG, WALL EVENT, AND USER ALERT ###
    Call LogReport(1, "The Issuer has been added", Session("userId"))
    Call SetUserAlert("success", "Issuer added successfully")
END IF

Response.Redirect("/issuers/")
%>
<!-- #include virtual="/includes/closeconnection.asp" -->
