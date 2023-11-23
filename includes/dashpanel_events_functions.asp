<%
FUNCTION DisplayEventsWall(numberOfRecords)
    Dim outStr : outStr = ""
    IF cToStr(Session("activeApplicantId")) = "" THEN ' ### NO MEMBER AVAILABLE
        outStr = "<p><b>No active member found.</b><br/><br/>The user events are inactive because no member has been added to the system. Please create one above.</p>"
    ELSE
        Dim eventRS : Set eventRS = Server.CreateObject("ADODB.Recordset")
        Dim eventSQL : eventSQL = _
            "SELECT " & _
            "   EventId, " & _
            "   EventTitle, " & _
            "   EventDescription, " & _
            "   EventDate, " & _
            "   EventType " & _
            "FROM " & _
            "   Events " & _
            "WHERE " & _
            "   ApplicantId = " & formatDbField(Session("activeApplicantId"), "int", false) & " " & _
            "ORDER BY " & _
            "   EventDate DESC " & _
            "   LIMIT " & numberOfRecords
        eventRS.open eventSQL, db
        IF eventRS.EOF THEN
            outStr = "<p>No events are currently available for " & Year(Now()) & "</p>"
        ELSE
            eventRS.MoveFirst
            DO WHILE NOT eventRS.EOF
                outStr = outStr & _
                    "<div class=""event-panel"">" & vbCrLf & _
                    "    <ul>" & vbCrLf & _
                    "        <li>" & vbCrLf & _
                    "            <i class=""fa " & GetEventIcon(eventRS("EventType")) & """ aria-hidden=""true""></i>" & vbCrLf & _
                    "        </li>" & vbCrLf & _
                    "        <li>" & vbCrLf & _
                    "            <h4>" & eventRS("EventTitle") & "</h4>" & vbCrLf & _
                    "            <p>" & eventRS("EventDescription") & "</p>" & vbCrLf & _
                    "        </li>" & vbCrLf & _
                    "    </ul>" & vbCrLf & _
                    "    <div class=""event-date"">" & HowLongAgo(ShowDateTime(eventRS("EventDate"), "")) & "</div>" & vbCrLf & _
                    "</div>" & vbCrLf & _
                eventRS.movenext
            LOOP
        END IF
        eventRS.Close
    END IF ' ### IF cToStr(Session("activeApplicantId")) = ""
    DisplayEventsWall = outStr
END FUNCTION

FUNCTION GetEventIcon(eventType)
    evenType = cToStr(eventType)
    IF eventType = "" THEN EXIT FUNCTION
    Dim outIcon : outIcon = ""
    IF eventType = "VEHIC" THEN
        outIcon = "fa-car"
    ELSEIF eventType = "SECUR" THEN
        outIcon = "fa-shield"
    ELSEIF eventType = "SECAC" THEN
        outIcon = "fa-tasks"
    ELSEIF eventType = "SECIC" THEN
        outIcon = "fa-briefcase"
    ELSEIF eventType = "BANKC" THEN
        outIcon = "fa-money"
    ELSEIF eventType = "BANKS" THEN
        outIcon = "fa-money"
    ELSEIF eventType = "CRDCA" THEN
        outIcon = "fa-credit-card"
    ELSEIF eventType = "RESID" THEN
        outIcon = "fa-home"
    ELSEIF eventType = "REALE" THEN
        outIcon = "fa-home purple"
    ELSEIF eventType = "OTHAS" THEN
        outIcon = "fa-shopping-bag"
    ELSEIF eventType = "BUSIN" THEN
        outIcon = "fa-building"
    ELSEIF eventType = "ENTIT" THEN
        outIcon = "fa-university"
    ELSEIF eventType = "PAYAB" THEN
        outIcon = "fa-sticky-note"
    ELSEIF eventType = "RECEI" THEN
        outIcon = "fa-sticky-note teal"
    ELSEIF eventType = "INVES" THEN
        outIcon = "fa-line-chart"
    ELSEIF eventType = "INSUR" THEN
        outIcon = "fa-heartbeat"
    END IF
    GetEventIcon = outIcon
END FUNCTION

SUB AddToEventWall(recordId, eventType, eventAction)
    IF Trim(recordId & "") = "" OR Trim(eventType & "") = "" THEN EXIT SUB

    Dim getRecordDetailsRS, getRecordDetailsSQL

    Dim fields : fields = ""
    Dim fieldIdName : fieldIdName = ""
    Dim table : table = ""
    Dim title : title = ""
    Dim description : description = ""
    Dim action : action = cToStr(eventAction)

    IF eventType = "VEHIC" THEN ' ### VEHICLE
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   VehicleMake, VehicleModel " & _
            "FROM " & _
            "   Vehicle " & _
            "WHERE " & _
            "   VehicleId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim vehicleMake : vehicleMake = getRecordDetailsRS("VehicleMake")
            Dim vehicleModel : vehicleModel = getRecordDetailsRS("VehicleModel")
            Dim vehicleMakeModel : vehicleMakeModel = vehicleMake & " " & vehicleModel
            IF cToStr(vehicleMakeModel) = "" THEN vehicleMakeModel = "Unknown Make &amp; Model"
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Vehicle added"
            description = "<a href=""/vehicle/vehicle_edit.asp?id=" & recordId & """>" & vehicleMakeModel & "</a> was added to assets."
        ELSEIF action = "EDIT" THEN
            title = "Vehicle modified"
            description = "<a href=""/vehicle/vehicle_edit.asp?id=" & recordId & """>" & vehicleMakeModel & "</a> was modified in assets."
        ELSEIF action = "DELETE" THEN
            title = "Vehicle deleted"
            description = "<i>" & vehicleMakeModel & "</i> was deleted from assets."
        END IF
    ELSEIF eventType = "SECUR" THEN ' ### SECURITY
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   s.Description, sa.AccountType " & _
            "FROM " & _
            "   Security AS s " & _
            "   INNER JOIN SecurityAccount AS sa ON s.SecurityAccountId = sa.SecurityAccountId " & _
            "WHERE " & _
            "   s.SecurityId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim securityDesc : securityDesc = getRecordDetailsRS("Description")
            Dim securityType : securityType = getRecordDetailsRS("AccountType")
            Dim securityTypeName : securityTypeName = "Retirement"
            IF cToStr(securityType) = "REG" THEN securityTypeName = "Marketable"
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Security added"
            description = securityTypeName & " Security <a href=""" & DOMAIN & "/securities/security_edit.asp?id=" & recordId & """>" & securityDesc & "</a> was added to assets."
        ELSEIF action = "EDIT" THEN
            title = "Security modified"
            description = securityTypeName & " Security <a href=""" & DOMAIN & "/securities/security_edit.asp?id=" & recordId & """>" & securityDesc & "</a> was modified in assets."
        ELSEIF action = "DELETE" THEN
            title = "Security deleted"
            description = securityTypeName & " Security <i>" & securityDesc & "</i> was deleted from assets."
        END IF
    ELSEIF eventType = "SECAC" THEN ' ### SECURITY ACCOUNT
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   AccountName, AccountType " & _
            "FROM " & _
            "   SecurityAccount " & _
            "WHERE " & _
            "   SecurityAccountId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim accountName : accountName = getRecordDetailsRS("AccountName")
            Dim securityAccType : securityAccType = getRecordDetailsRS("AccountType")
            Dim securityAccTypeName : securityAccTypeName = "Retirement"
            IF cToStr(securityAccType) = "REG" THEN securityAccTypeName = "Marketable"
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Security Account added"
            description = securityAccTypeName & " Security <a href=""" & DOMAIN & "/securities/account_edit.asp?id=" & recordId & """>" & accountName & "</a> was added to assets."
        ELSEIF action = "EDIT" THEN
            title = "Security Account modified"
            description = securityAccTypeName & " Security Account <a href=""" & DOMAIN & "/securities/account_edit.asp?id=" & recordId & """>" & accountName & "</a> was modified in assets."
        ELSEIF action = "DELETE" THEN
            title = "Security Account deleted"
            description = securityAccTypeName & " Security Account <i>" & accountName & "</i> was deleted from assets."
        END IF
    ELSEIF eventType = "SECIC" THEN ' ### SECURITY INVESTMENT COMPANY
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   BrokerName " & _
            "FROM " & _
            "   Broker " & _
            "WHERE " & _
            "   BrokerId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim brokerName : brokerName = getRecordDetailsRS("BrokerName")
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Investment Company added"
            description = "<a href=""" & DOMAIN & "/securities/broker_edit.asp?id=" & recordId & """>" & brokerName & "</a> Investment Company was added to assets."
        ELSEIF action = "EDIT" THEN
            title = "Investment Company modified"
            description = "<a href=""" & DOMAIN & "/securities/broker_edit.asp?id=" & recordId & """>" & brokerName & "</a> Investment Company was modified in assets."
        ELSEIF action = "DELETE" THEN
            title = "Investment Company deleted"
            description = "<i>" & brokerName & "</i> Investment Company was deleted from assets."
        END IF
    ELSEIF eventType = "CRDCA" THEN ' ### CREDIT CARD
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   c.CardName, " & _
            "   c.Last4Digits, " & _
            "   ddf.DropDownFieldName AS CardTypeName " & _
            "FROM " & _
            "   CreditCard AS c " & _
            "   INNER JOIN DropDownFields AS ddf ON c.CardTypeId = ddf.DropDownFieldId " & _
            "WHERE " & _
            "   c.CreditCardId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim cardName : cardName = getRecordDetailsRS("CardName")
            Dim last4Digits : last4Digits = getRecordDetailsRS("Last4Digits")
            Dim cardTypeName : cardTypeName = getRecordDetailsRS("CardTypeName")
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Credit Card added"
            IF cToStr(last4Digits) = "" THEN
                description = cardTypeName & " <a href=""" & DOMAIN & "/credit-card/creditcard_edit.asp?id=" & recordId & """>" & cardName & "</a> Credit Card was added to liabilities."
            ELSE
                description = cardTypeName & " [" & last4Digits & "] <a href=""" & DOMAIN & "/credit-card/creditcard_edit.asp?id=" & recordId & """>" & cardName & "</a> Credit Card was added to liabilities."
            END IF
        ELSEIF action = "EDIT" THEN
            title = "Credit Card modified"
            IF cToStr(last4Digits) = "" THEN
                description = cardTypeName & " <a href=""" & DOMAIN & "/credit-card/creditcard_edit.asp?id=" & recordId & """>" & cardName & "</a> Credit Card was modified in liabilities."
            ELSE
                description = cardTypeName & " [" & last4Digits & "] <a href=""" & DOMAIN & "/credit-card/creditcard_edit.asp?id=" & recordId & """>" & cardName & "</a> Credit Card was modified in liabilities."
            END IF
        ELSEIF action = "DELETE" THEN
            title = "Credit Card deleted"
            IF cToStr(last4Digits) = "" THEN
                description = cardTypeName & " <i>" & cardName & "</i> Credit Card was deleted from liabilities."
            ELSE
                description = cardTypeName & " [" & last4Digits & "] <i>" & cardName & "</i> Credit Card was deleted from liabilities."
            END IF
        END IF
    ELSEIF eventType = "BANKC" THEN ' ### CHECKING BANK ACCOUNT
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   BankName, " & _
            "   AccountNumber " & _
            "FROM " & _
            "   CheckingAccount " & _
            "WHERE " & _
            "   CheckingId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim bankName : bankName = getRecordDetailsRS("BankName")
            IF cToStr(bankName) = "" THEN bankName = "Unknown Bank"
            Dim accountNumber : accountNumber = getRecordDetailsRS("AccountNumber")
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Checking Account added"
            IF cToStr(accountNumber) = "" THEN
                description = "Checking Account <a href=""" & DOMAIN & "/accounts/account_edit.asp?at=C&id=" & recordId & """>" & BankName & "</a> was added to assets."
            ELSE
                description = "Checking Account <a href=""" & DOMAIN & "/accounts/account_edit.asp?at=C&id=" & recordId & """>" & BankName & "</a> [" & accountNumber & "] was added to assets."
            END IF
        ELSEIF action = "EDIT" THEN
            title = "Checking Account modified"
            IF cToStr(accountNumber) = "" THEN
                description = "Checking Account <a href=""" & DOMAIN & "/accounts/account_edit.asp?at=C&id=" & recordId & """>" & BankName & "</a> was modified in assets."
            ELSE
                description = "Checking Account <a href=""" & DOMAIN & "/accounts/account_edit.asp?at=C&id=" & recordId & """>" & BankName & "</a> [" & accountNumber & "] was modified in assets."
            END IF
        ELSEIF action = "DELETE" THEN
            title = "Checking Account deleted"
            IF cToStr(accountNumber) = "" THEN
                description = "Checking Account <i>" & BankName & "</i> was deleted from assets."
            ELSE
                description = "Checking Account <i>" & BankName & "</i> [" & accountNumber & "] was deleted from assets."
            END IF
        END IF
    ELSEIF eventType = "BANKS" THEN ' ### SAVINGS BANK ACCOUNT
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   BankName, " & _
            "   AccountNumber " & _
            "FROM " & _
            "   SavingAccount " & _
            "WHERE " & _
            "   SavingId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim savingsBankName : savingsBankName = getRecordDetailsRS("BankName")
            IF cToStr(savingsBankName) = "" THEN savingsBankName = "Unknown Bank"
            Dim savingsAccountNumber : savingsAccountNumber = getRecordDetailsRS("AccountNumber")
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Savings Account added"
            IF cToStr(savingsAccountNumber) = "" THEN
                description = "Savings Account <a href=""" & DOMAIN & "/accounts/account_edit.asp?at=S&id=" & recordId & """>" & savingsBankName & "</a> was added to assets."
            ELSE
                description = "Savings Account <a href=""" & DOMAIN & "/accounts/account_edit.asp?at=S&id=" & recordId & """>" & savingsBankName & "</a> [" & savingsAccountNumber & "] was added to assets."
            END IF
        ELSEIF action = "EDIT" THEN
            title = "Savings Account modified"
            IF cToStr(savingsAccountNumber) = "" THEN
                description = "Savings Account <a href=""" & DOMAIN & "/accounts/account_edit.asp?at=S&id=" & recordId & """>" & savingsBankName & "</a> was modified in assets."
            ELSE
                description = "Savings Account <a href=""" & DOMAIN & "/accounts/account_edit.asp?at=S&id=" & recordId & """>" & savingsBankName & "</a> [" & savingsAccountNumber & "] was modified in assets."
            END IF
        ELSEIF action = "DELETE" THEN
            title = "Savings Account deleted"
            IF cToStr(savingsAccountNumber) = "" THEN
                description = "Savings Account <i>" & savingsBankName & "</i> was deleted from assets."
            ELSE
                description = "Savings Account <i>" & savingsBankName & "</i> [" & savingsAccountNumber & "] was deleted from assets."
            END IF
        END IF
    ELSEIF eventType = "RESID" THEN ' ### RESIDENCE
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   re.StreetAddress, " & _
            "   ddf.DropDownFieldName AS PropertyTypeName " & _
            "FROM " & _
            "   RealEstate AS re " & _
            "   INNER JOIN DropDownFields AS ddf ON re.TypeProperty = ddf.DropDownFieldId " & _
            "WHERE " & _
            "   re.realEstateId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim streetAddress : streetAddress = getRecordDetailsRS("StreetAddress")
            Dim propertyTypeName : propertyTypeName = getRecordDetailsRS("PropertyTypeName")
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Residence added"
            description = propertyTypeName & " Residence <a href=""" & DOMAIN & "/residences/residence_edit.asp?id=" & recordId & """>" & streetAddress & "</a> was added to assets."
        ELSEIF action = "EDIT" THEN
            title = "Residence modified"
            description = "Residence <a href=""" & DOMAIN & "/residences/residence_edit.asp?id=" & recordId & """>" & streetAddress & "</a> was modified in assets."
        ELSEIF action = "DELETE" THEN
            title = "Residence deleted"
            description = "Residence <i>" & streetAddress & "</i> was deleted from assets."
        END IF
    ELSEIF eventType = "REALE" THEN ' ### RESIDENCE
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   re.StreetAddress, " & _
            "   ddf.DropDownFieldName AS PropertyTypeName " & _
            "FROM " & _
            "   RealEstate AS re " & _
            "   INNER JOIN DropDownFields AS ddf ON re.TypeProperty = ddf.DropDownFieldId " & _
            "WHERE " & _
            "   re.realEstateId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim reStreetAddress : reStreetAddress = getRecordDetailsRS("StreetAddress")
            Dim rePropertyTypeName : rePropertyTypeName = getRecordDetailsRS("PropertyTypeName")
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Real Estate added"
            description = rePropertyTypeName & " Real Estate <a href=""" & DOMAIN & "/real_estate/real_estate_edit.asp?id=" & recordId & """>" & reStreetAddress & "</a> was added to assets."
        ELSEIF action = "EDIT" THEN
            title = "Real Estate modified"
            description = "Real Estate <a href=""" & DOMAIN & "/real_estate/real_estate_edit.asp?id=" & recordId & """>" & reStreetAddress & "</a> was modified in assets."
        ELSEIF action = "DELETE" THEN
            title = "Real Estate deleted"
            description = "Real Estate <i>" & reStreetAddress & "</i> was deleted from assets."
        END IF
    ELSEIF eventType = "OTHAS" THEN ' ### OTHER ASSETS
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   PropertyName " & _
            "FROM " & _
            "   Property " & _
            "WHERE " & _
            "   PropertyId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim propertyName : propertyName = getRecordDetailsRS("PropertyName")
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Other Asset added"
            description = "Other Asset <a href=""" & DOMAIN & "/other-assets/other_asset_edit.asp?id=" & recordId & """>" & propertyName & "</a> was added to assets."
        ELSEIF action = "EDIT" THEN
            title = "Other Asset modified"
            description = "Other Asset <a href=""" & DOMAIN & "/other-assets/other_asset_edit.asp?id=" & recordId & """>" & propertyName & "</a> was modified in assets."
        ELSEIF action = "DELETE" THEN
            title = "Other Asset deleted"
            description = "Other Asset <i>" & propertyName & "</i> was deleted from assets."
        END IF
    ELSEIF eventType = "BUSIN" THEN ' ### BUSINESSES
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   CompanyName " & _
            "FROM " & _
            "   Partnership " & _
            "WHERE " & _
            "   PartnershipId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim companyName : companyName = getRecordDetailsRS("CompanyName")
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Business added"
            description = "Business <a href=""" & DOMAIN & "/business/business_edit.asp?id=" & recordId & """>" & companyName & "</a> was added to assets."
        ELSEIF action = "EDIT" THEN
            title = "Business modified"
            description = "Business <a href=""" & DOMAIN & "/business/business_edit.asp?id=" & recordId & """>" & companyName & "</a> was modified in assets."
        ELSEIF action = "DELETE" THEN
            title = "Business deleted"
            description = "Business <i>" & companyName & "</i> was deleted from assets."
        END IF
    ELSEIF eventType = "ENTIT" THEN ' ### ENTITY
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   EntityName " & _
            "FROM " & _
            "   Entity " & _
            "WHERE " & _
            "   EntityId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim entityName : entityName = getRecordDetailsRS("EntityName")
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Entity added"
            description = "Entity <a href=""" & DOMAIN & "/entities/entity_edit.asp?id=" & recordId & """>" & entityName & "</a> was added."
        ELSEIF action = "EDIT" THEN
            title = "Entity modified"
            description = "Entity <a href=""" & DOMAIN & "/entities/entity_edit.asp?id=" & recordId & """>" & entityName & "</a> was modified."
        ELSEIF action = "DELETE" THEN
            title = "Entity deleted"
            description = "Entity <i>" & entityName & "</i> was deleted."
        END IF
    ELSEIF eventType = "PAYAB" THEN ' ### NOTE AND ACCOUNTS PAYABLE
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   DueTo, " & _
            "   IsNote " & _
            "FROM " & _
            "   AccountPayable " & _
            "WHERE " & _
            "   AccountPayableId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim dueTo : dueTo = getRecordDetailsRS("DueTo")
            Dim isNote : isNote = getRecordDetailsRS("IsNote")
            Dim payableType : payableType = "Account"
            IF isNote THEN payableType = "Note"
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New " & payableType & " Payable added"
            description = payableType & " Payable due to <a href=""" & DOMAIN & "/payable/payable_edit.asp?id=" & recordId & """>" & dueTo & "</a> was added to liabilities."
        ELSEIF action = "EDIT" THEN
            title = payableType & " Payable modified"
            description = payableType & " Payable due to <a href=""" & DOMAIN & "/payable/payable_edit.asp?id=" & recordId & """>" & dueTo & "</a> was modified in liabilities."
        ELSEIF action = "DELETE" THEN
            title = payableType & " Payable deleted"
            description = payableType & " Payable due to <i>" & dueTo & "</i> was deleted from liabilities."
        END IF
    ELSEIF eventType = "RECEI" THEN ' ### NOTE RECEIVABLE
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   Description " & _
            "FROM " & _
            "   NotesReceivable " & _
            "WHERE " & _
            "   NoteReceivableId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim receivableDescription : receivableDescription = getRecordDetailsRS("Description")
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Note Receivable added"
            description = "Note Receivable from <a href=""" & DOMAIN & "/notes/note_edit.asp?id=" & recordId & """>" & receivableDescription & "</a> was added to assets."
        ELSEIF action = "EDIT" THEN
            title = "Note Receivable modified"
            description = "Note Receivable from <a href=""" & DOMAIN & "/notes/note_edit.asp?id=" & recordId & """>" & receivableDescription & "</a> was modified in assets."
        ELSEIF action = "DELETE" THEN
            title = "Note Receivable deleted"
            description = "Note Receivable from <i>" & receivableDescription & "</i> was deleted from assets."
        END IF
    ELSEIF eventType = "INVES" THEN ' ### ALTERNATIVE INVESTMENTS
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   Description " & _
            "FROM " & _
            "   Investment " & _
            "WHERE " & _
            "   InvestmentId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim investmentDescription : investmentDescription = getRecordDetailsRS("Description")
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Alternative Investment added"
            description = "Alternative Investment <a href=""" & DOMAIN & "/investment/investment_edit.asp?id=" & recordId & """>" & investmentDescription & "</a> was added to assets."
        ELSEIF action = "EDIT" THEN
            title = "Alternative Investment modified"
            description = "Alternative Investment <a href=""" & DOMAIN & "/investment/investment_edit.asp?id=" & recordId & """>" & investmentDescription & "</a> was modified in assets."
        ELSEIF action = "DELETE" THEN
            title = "Alternative Investment deleted"
            description = "Alternative Investment <i>" & investmentDescription & "</i> was deleted from assets."
        END IF
    ELSEIF eventType = "INSUR" THEN ' ### LIFE INSURANCE
        ' ### GET RECORD DATA ###
        Set getRecordDetailsRS = Server.CreateObject("ADODB.Recordset")
        getRecordDetailsSQL = _
            "SELECT " & _
            "   InsurancePolicyNumber, " & _
            "   NameOfInsuranceCompany " & _
            "FROM " & _
            "   insurance " & _
            "WHERE " & _
            "   InsuranceId = " & formatDbField(recordId, "int", false)
        getRecordDetailsRS.open getRecordDetailsSQL, db
        IF NOT (getRecordDetailsRS.BOF AND getRecordDetailsRS.EOF) THEN
            Dim insurancePolicyNumber : insurancePolicyNumber = getRecordDetailsRS("InsurancePolicyNumber")
            Dim nameOfInsuranceCompany : nameOfInsuranceCompany = getRecordDetailsRS("NameOfInsuranceCompany")
        END IF
        getRecordDetailsRS.Close

        ' ### CONSTRUCT EVENT DATA ###
        IF action = "ADD" THEN
            title = "New Life Insurance policy added"
            description = nameOfInsuranceCompany & " life insurance policy <a href=""/insurance/insurance_edit.asp?id=" & recordId & """>" & insurancePolicyNumber & "</a> was added to assets."
        ELSEIF action = "EDIT" THEN
            title = "Life Insurance policy modified"
            description = nameOfInsuranceCompany & " life insurance policy <a href=""/insurance/insurance_edit.asp?id=" & recordId & """>" & insurancePolicyNumber & "</a> was modified in assets."
        ELSEIF action = "DELETE" THEN
            title = "Life Insurance policy deleted"
            description = nameOfInsuranceCompany & " life insurance policy <i>" & insurancePolicyNumber & "</i> was deleted from assets."
        END IF
    END IF

    Dim eventsColumns : eventsColumns = "ApplicantId,EventTitle,EventDescription,EventType"
    Dim eventsValues : eventsValues = Session("activeApplicantId") & "," & formatDbField(title, "text", false) & "," & formatDbField(description, "text", false) & "," & formatDbField(eventType, "text", false)
    Call InsertNewRecord("Events", eventsColumns, eventsValues)
END SUB
%>