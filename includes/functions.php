<?php
function InitiateConnection() {
    $connection = mysqli_connect('127.0.0.1', 'root', '@H2rf36t4DMq', 'receipts_php');
    if (!$connection) {
        die("Database connection failed");
    } else {
        return $connection;
    }
}

function formatDbField($fieldString, $fieldType, $fieldAllowsNulls) {
    $fieldString = CheckNulls(RemoveSpecialChars($fieldString));
    if ($fieldType === "text") {
        if ($fieldAllowsNulls && trim($fieldString . "") === "") {
            return "NULL";
        } else {
            return "'" . str_replace("'", "''", $fieldString) . "'";
        }
    } elseif ($fieldType === "int") {
        if ($fieldAllowsNulls && (trim($fieldString . "") === "" || !is_numeric($fieldString))) {
            return "NULL";
        } else {
            return $fieldString;
        }
    } elseif ($fieldType === "datetime") {
        if ($fieldAllowsNulls && $fieldString === "") {
            return "NULL";
        } else {
            return "'" . str_replace("'", "''", $fieldString) . "'";
        }
    } elseif ($fieldType === "bit") {
        echo "fieldString3 = " . $fieldString . "</br>";
        if ($fieldString === "") {
            return "NULL";
        } elseif ($fieldString || $fieldString === "1") {
            return "1";
        } else {
            return "0";
        }
    } elseif ($fieldType === "decimal") {
        if ($fieldString === "") {
            return "NULL";
        } elseif (is_numeric($fieldString)) {
            return str_replace(",", "", $fieldString);
        } else {
            return "NULL";
        }
    }
}

function formatDbFieldAdd($fieldString, $fieldType, $fieldAllowsNulls) {
    $fieldString = CheckNulls(RemoveSpecialChars($fieldString));
    if ($fieldType === "text") {
        if ($fieldAllowsNulls && trim($fieldString . "") === "") {
            return NULL;
        } else {
            return str_replace("'", "''", $fieldString);
        }
    } elseif ($fieldType === "int") {
        if ($fieldAllowsNulls && (trim($fieldString . "") === "" || !is_numeric($fieldString))) {
            return NULL;
        } else {
            return $fieldString;
        }
    } elseif ($fieldType === "datetime") {
        if ($fieldAllowsNulls && trim($fieldString . "") === "") {
            return NULL;
        } else {
            return str_replace("'", "''", $fieldString);
        }
    } elseif ($fieldType === "bit") {
        if (trim($fieldString . "") === "") {
            return NULL;
        } elseif ($fieldString || $fieldString === "1") {
            return "1";
        } else {
            return "0";
        }
    } elseif ($fieldType === "decimal") {
        if (trim($fieldString . "") === "") {
            return NULL;
        } elseif (is_numeric($fieldString)) {
            return str_replace("'", "''", $fieldString);
        } else {
            return NULL;
        }
    }
}

function CheckNulls($str) {
    $strOut = $str;
    if (is_null($str)) {
        $strOut = "";
    }
    return $strOut;
}

function LogReport($logType, $logText, $logUserId) {
    if (trim($logUserId . "") === "") {
        return;
    }
    $logColumns = "LogType,LogText,LogUserId";
    $logValues = formatDbField($logType, "int", 0) . "," . formatDbField($logText, "text", false) . "," . formatDbField($logUserId, "int", false);
    InsertNewRecord("systemlog", $logColumns, $logValues);
}

function InsertNewRecord($tableName, $columns, $values) {
    global $db;
    $sql = "INSERT INTO " . $tableName . " (" . $columns . ") VALUES (" . $values . ")";
    //echo "SQL STATEMENT = " . $sql . "</br>";
    if ($db -> query($sql) === false) {
        echo "Error: " . $sql . "<br>" . $db -> error;
    } else {
        return $db -> insert_id;
    }
}

function CheckForValidLogin() {
    if (!$_SESSION["loggedIn"]) {
        header("Location: login.php");
    }
}

function RemoveSpecialChars($str) {
    $str = trim($str) . "";
    $OutStr = trim($str);
    $OutStr = str_replace("`", "", $OutStr);
    $OutStr = str_replace("^", "", $OutStr);
    $OutStr = str_replace("~", "", $OutStr);
    $OutStr = str_replace("|", "", $OutStr);
    $OutStr = str_replace("(", "", $OutStr);
    $OutStr = str_replace(")", "", $OutStr);
    $OutStr = str_replace("{", "", $OutStr);
    $OutStr = str_replace("}", "", $OutStr);
    return $OutStr;
}

function SetUserAlert($alertType, $alertMessage) {
    if (empty($alertType) || empty($alertMessage)) return;
    $_SESSION["hasAlert"] = true;
    $_SESSION["alertType"] = $alertType;
    $_SESSION["alertMessage"] = $alertMessage;
}

function FormatPostedDate($thisDate) {
    if (empty($thisDate)) return;
    return date("F", strtotime($thisDate)) . " " . date("d", strtotime($thisDate)) . ", " . date("Y", strtotime($thisDate));
}

function CreateDropmenu($fieldId, $fieldName, $tableName, $fieldOrder, $selectName, $selectPlaceholder, $currentValue) {
    $strOut = ""; $menuSQL = "";
    if (trim($fieldOrder . "" == "")) { $fieldOrder = $fieldName; }
    if (trim($selectPlaceholder . "" == "")) { $selectPlaceholder = "Select One..."; }

    global $db;
    $menuSQL = "SELECT " . $fieldId . ", " . $fieldName . " FROM `" . $tableName . "` ORDER BY " . $fieldOrder;
    $response = mysqli_query($db, $menuSQL);
    $row_cnt = mysqli_num_rows($response);

    $strOut = "<select name=\"dd" . str_replace("-", "", $selectName) . "\" id=\"" . strtolower($selectName) . "\">";
    if ($row_cnt === 0) {
        $strOut = $strOut . "<option value=\"\">No Records Found</option>";
    } else {
        $strOut = $strOut . "<option value=\"\">" . $selectPlaceholder . "</option>";
        while($menuRS = mysqli_fetch_assoc($response)) {
            if (trim($currentValue) !== "") {
                if ($menuRS[$fieldId] == $currentValue) {
                    $strOut = $strOut . "<option value=\"" . $menuRS[$fieldId] . "\" selected=\"selected\">" . $menuRS[$fieldName] . "</option>";
                } else {
                    $strOut = $strOut . "<option value=\"" . $menuRS[$fieldId] . "\">" . $menuRS[$fieldName] . "</option>";
                }
            } else {
                $strOut = $strOut . "<option value=\"" . $menuRS[$fieldId] . "\">" . $menuRS[$fieldName] . "</option>";
            }
        }
    }
    $strOut = $strOut . "</select>";
    return $strOut;
}

function ShowSectionBorder() {
    return "<tr style=\"height:8px\">
                <td colspan=\"2\" class=\"dotted-line\"></td>
            </tr>
            <tr style=\"height:8px\">
                <td colspan=\"2\"></td>
            </tr>";
}

function hasPermission($nSection, $nAction) {
    global $db;
    $boolPermission = false;
    $checkPermissionsSQL = "
        SELECT 1 FROM `UserGroup`
        WHERE
           (" . $nSection . " LIKE '%" . $nAction . "%' OR " . $nSection . " = 'full')
           AND `GroupId` = " . $_SESSION["userGroup"] . "";
    $response = mysqli_query($db, $checkPermissionsSQL);
    $row_cnt = mysqli_num_rows($response);

    if ($row_cnt > 0) $boolPermission = true;
    return $boolPermission;
}

function GetGroupName($nGroupId) {
    if (trim($nGroupId) . "" === "") {
        return;
    }
    global $db;
    $groupNameSQL = "SELECT `GroupName` FROM `UserGroup` WHERE `GroupId` = " . formatDbField($nGroupId, "int", false);
    $response = mysqli_query($db, $groupNameSQL);
    $row_cnt = mysqli_num_rows($response);
    if ($row_cnt !== 0) {
        $row = mysqli_fetch_assoc($response);
        $groupName = $row["GroupName"];
    } else {
        return;
    }
    return $groupName;
}

// ### FUNCTIONS AND SUBS ###
//FUNCTION StateDropmenu(nStateId, nFieldName)
//    IF Trim(nFieldName & "") = "" THEN nFieldName = "StateId"
//    Dim fieldId : fieldId = lCase(nFieldName)
//    Dim StateListRS : Set StateListRS = Server.CreateObject("ADODB.recordset")
//    Dim StateListSQL : StateListSQL = "SELECT StateId, StateName FROM State ORDER BY StateName"
//    StateListRS.open StateListSQL, db
//    IF NOT (StateListRS.BOF AND StateListRS.EOF) THEN
//        Dim paryStates : paryStates = StateListRS.GetRows()
//    END IF
//    StateListRS.Close
//
//    Dim strOut : strOut = "<select id=""" & fieldId & """ name=""dd" & Replace(nFieldName, "-", "") & """>" & vbCr _
//                        & "<option value="""">Select State</option>" & vbCr
//    IF IsArray(paryStates) THEN
//        FOR i = 0 TO uBound(paryStates,2)
//            IF paryStates(0,i) = nStateId THEN
//                strOut = strOut & "<option value=""" & paryStates(0,i) & """ selected=""selected"">" & paryStates(1,i) & "</option>" & vbCr
//            ELSE
//                strOut = strOut & "<option value=""" & paryStates(0,i) & """>" & paryStates(1,i) & "</option>" & vbCr
//            END IF
//        NEXT
//    END IF
//    strOut = strOut & "</select>"
//    StateDropmenu = strOut
//END FUNCTION
//
//
//SUB HasMemberPageAccess(url)
//    IF $_SESSION["userPaymentTierCode") = "Standard" THEN
//        $_SESSION["hasAlert") = true
//        $_SESSION["alertType") = "danger"
//        $_SESSION["alertMessage") = "Page Requested is Only Accessible as a Paying Member of CalcuTrack..!"
//        Response.Redirect(url)
//    END IF
//END SUB
//
//SUB HasReportAccess(url)
//    IF cToStr($_SESSION["activeApplicantId")) = "" THEN
//        $_SESSION["hasAlert") = true
//        $_SESSION["alertType") = "info"
//        $_SESSION["alertMessage") = "You must select an member before viewing report."
//        Response.Redirect(DOMAIN & "/")
//    ELSEIF $_SESSION["userPaymentTierType") = "ANY" THEN ' ### SET TO FRE IF YOU WANT FREE MEMBERS TO BE REPORT RESTRICTED
//        $_SESSION["hasAlert") = true
//        $_SESSION["alertType") = "danger"
//        $_SESSION["alertMessage") = "Report access is limited to paying members only. If you wish to upgrade your membership, please visit the <a href=""" & DOMAIN & "/profile/membership.asp"" style=""color:#A94442;text-decoration:underline"">membership</a> page."
//        Response.Redirect(url)
//    END IF
//END SUB
//
//FUNCTION CreateDropmenuApplicant(fieldId, fieldName, tableName, fieldOrder, selectName, selectPlaceholder, currentValue)
//    Dim strOut : strOut = ""
//    IF Trim(fieldOrder & "") = "" THEN fieldOrder = fieldName
//    IF Trim(selectPlaceholder & "") = "" THEN selectPlaceholder = "Select One..."
//    Dim menuRS : Set menuRS = Server.CreateObject("ADODB.Recordset")
//    Dim menuSQL : menuSQL = _
//        "SELECT " & fieldId & ", " & fieldName & " FROM " & tableName & " WHERE ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false) & " ORDER BY " & fieldOrder
//    menuRS.open menuSQL, db
//    strOut = "<select name=""dd" & Replace(selectName, "-", "") & """ id=""" & lCase(selectName) & """>" & vbCr
//    IF menuRS.EOF THEN
//        strOut = strOut & "<option value="""">No Records Found</option>" & vbCr
//    ELSE
//        strOut = strOut & "<option value="""">" & selectPlaceholder & "</option>" & vbCr
//        menuRS.MoveFirst
//        DO WHILE NOT menuRS.EOF
//            IF Trim(currentValue & "") <> "" THEN
//                IF cInt(menuRS(fieldId)) = cInt(currentValue) THEN
//                    strOut = strOut & "<option value=""" & menuRS(fieldId) & """ selected=""selected"">" & menuRS(fieldName) & "</option>" & vbCr
//                ELSE
//                    strOut = strOut & "<option value=""" & menuRS(fieldId) & """>" & menuRS(fieldName) & "</option>" & vbCr
//                END IF
//            ELSE
//                strOut = strOut & "<option value=""" & menuRS(fieldId) & """>" & menuRS(fieldName) & "</option>" & vbCr
//            END IF
//            menuRS.movenext
//        LOOP
//    END IF
//    menuRS.Close
//    strOut = strOut & "</select>"
//    CreateDropmenuApplicant = strOut
//END FUNCTION
//
//FUNCTION YesNoDropmenu(nFieldValue, nFieldName)
//    Dim nYes : nYes = "" : Dim nNo : nNo = ""
//    IF Trim(nFieldValue & "") <> "" THEN
//        IF nFieldValue THEN nYes = " selected=""selected"""
//        IF NOT nFieldValue THEN nNo = " selected=""selected"""
//    END IF
//    Dim fieldId : fieldId = lCase(nFieldName)
//    Dim strOut : strOut = _
//        "<select name=""dd" & Replace(nFieldName, "-", "") & """ id=""" & fieldId & """>" & vbCr _
//        & "<option value="""">Select One...</option>" & vbCr _
//        & "<option value=""0""" & nNo & ">No</option>" & vbCr _
//        & "<option value=""1""" & nYes & ">Yes</option>" & vbCr _
//        & "</select>"
//    YesNoDropmenu = strOut
//END FUNCTION
//
//FUNCTION GetDropdownFieldCode(id)
//    Dim thisCode : thisCode = ""
//    Dim codeRS : Set codeRS = Server.CreateObject("ADODB.Recordset")
//    Dim codeSQL : codeSQL = "SELECT DropdownFieldCode FROM DropDownFields WHERE DropdownFieldId = " & formatDbField(id, "int", false)
//    codeRS.open codeSQL, db
//    IF NOT codeRS.EOF THEN
//        thisCode = codeRS("DropdownFieldCode")
//    END IF
//    codeRS.Close
//    GetDropdownFieldCode = thisCode
//END FUNCTION
//
//FUNCTION getPercentage(item, total, showSymbol)
//    IF Trim(item & "") = "" THEN EXIT FUNCTION
//    IF Trim(total & "") = "" THEN EXIT FUNCTION
//
//    IF cToStr(total) = "0" THEN
//        getPercentage = "---"
//    ELSE
//        Dim result : result = (item / total) * 100
//        result = FormatNumber(result, 3)
//
//        IF showSymbol THEN
//            getPercentage = result & "%"
//        ELSE
//            getPercentage = result
//        END IF
//    END IF
//END FUNCTION
//
//FUNCTION ShowAsPercentage(thisValue, showSymbol, divideByHundred)
//    IF Trim(thisValue & "") = "" THEN EXIT FUNCTION
//    IF NOT IsNumeric(thisValue) THEN EXIT FUNCTION
//    Dim result : result = thisValue
//    IF divideByHundred THEN result = result / 100
//    result = FormatNumber(result, 3)
//    IF showSymbol THEN
//        ShowAsPercentage = result & "%"
//    ELSE
//        ShowAsPercentage = result
//    END IF
//END FUNCTION
//
//FUNCTION ShowAsCurrency(thisValue)
//    IF Trim(thisValue & "") = "" THEN ShowAsCurrency = "-"
//    IF (IsNumeric(thisValue) OR varType(thisValue) = 14) THEN
//        Dim symbol : symbol = "$"
//        thisValue = FormatNumber(cToDbl(thisValue), 2)
//        ShowAsCurrency = symbol & thisValue
//    END IF
//END FUNCTION
//
//FUNCTION GetMemberCurrency()
//    Dim memberCurrency : memberCurrency = Trim($_SESSION["memberCurrencyCode") & "")
//    IF memberCurrency = "USD" THEN
//        GetMemberCurrency = "$,USD,Dollars"
//    ELSEIF memberCurrency = "CHF" THEN
//        GetMemberCurrency = "&#8355;,CHF,Swiss Francs"
//    ELSEIF memberCurrency = "CAD" THEN
//        GetMemberCurrency = "C$,CAD,Canadian Dollars"
//    ELSEIF memberCurrency = "GBP" THEN
//        GetMemberCurrency = "£,GBP,British Pounds"
//    ELSEIF memberCurrency = "MXN" THEN
//        GetMemberCurrency = "$,MXN,Mexican Pesos"
//    END IF
//END FUNCTION
//
//FUNCTION ShowAsDollars(thisValue)
//    IF Trim(thisValue & "") = "" THEN ShowAsDollars = "-"
//    IF (IsNumeric(thisValue) OR varType(thisValue) = 14) THEN
//        ShowAsDollars = "$" & FormatNumber(cToDbl(thisValue), 2)
//    END IF
//END FUNCTION
//
//FUNCTION rndString(lengthString)
//    Dim strOut : strOut = ""
//    Randomize
//    Dim i : FOR i = 0 TO toOne(lengthString)
//        Dim intRndNbr : intRndNbr = Int((26) * Rnd + 65)
//        IF i MOD 2 = 0 THEN
//            strOut = strOut & chr(intRndNbr)
//        ELSE
//            strOut = strOut & intRndNbr
//        END IF
//    NEXT
//    rndString = strOut
//END FUNCTION
//
//FUNCTION toOne(val)
//    IF isNull(val) THEN
//        toOne = "1"
//        EXIT FUNCTION
//    END IF
//    IF Trim(val) = "" THEN
//        toOne = "1"
//        EXIT FUNCTION
//    END IF
//    IF Trim(val) = "0" THEN
//        toOne = "1"
//        EXIT FUNCTION
//    END IF
//    toOne = val
//END FUNCTION
//
//FUNCTION SumField(fieldName, tableName, entityId)
//    Dim total : total = 0
//
//    Dim entityFilter : entityFilter = "   AND EntityId IS NULL "
//    IF cToStr(entityId) <> "" THEN entityFilter = "   AND EntityId = " & formatDbField(entityId, "int", true) & " "
//
//    IF cToStr(tableName) = "Insurance" THEN
//        entityFilter = " "
//    END IF
//
//    IF Trim($_SESSION["activeApplicantId") & "") <> "" THEN
//        Dim totalRS : Set totalRS = Server.CreateObject("ADODB.Recordset")
//        Dim totalSQL : totalSQL = _
//            "SELECT " & _
//            "   SUM(IFNULL(" & fieldName & ", 0)) AS Amount " & _
//            " FROM " & _
//            tableName & _
//            " WHERE " & _
//            "   ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false) & _
//            entityFilter
//        totalRS.open totalSQL, db
//        IF NOT totalRS.EOF THEN
//            Dim tmpvalue : tmpvalue = totalRS("Amount")
//            IF Trim(tmpvalue & "") = "" THEN tmpvalue = 0
//            total = cToDbl(tmpvalue)
//        END IF
//        totalRS.Close
//        Set totalRS = Nothing
//    END IF
//    SumField = total
//END FUNCTION
//
//FUNCTION FormatDisplayDate(nDate)
//    IF Trim(nDate & "") = "" THEN EXIT FUNCTION
//
//    IF InStr(nDate, " ") > 0 THEN
//        Dim dtArray : dtArray = Split(nDate, " ")
//        nDate = dtArray(0)
//    END IF
//
//    Dim dateArray : dateArray = Split(nDate, "-")
//    Dim year : year = dateArray(2)
//    Dim month : month = dateArray(0)
//    Dim day : day = dateArray(1)
//
//    'IF Len(Trim(month)) = 1 THEN month = "0" & month
//    'IF Len(Trim(day)) = 1 THEN day = "0" & day
//
//    'FormatDisplayDate = year & "-" & month & "-" & day
//    FormatDisplayDate = month & "/" & day & "/" & year
//END FUNCTION
//
//FUNCTION SumSecuritiesIra(iraValue, entityId)
//    Dim total : total = 0
//    IF Trim($_SESSION["activeApplicantId") & "") <> "" THEN
//
//        Dim endQuery : endQuery = "IS NULL"
//        IF Trim(iraValue & "") = "1" THEN endQuery = "IS NOT NULL"
//
//        ' ### DECLARE AND SET 'SECURITY ENTITY FILTER' ###
//        Dim securityEntityFilter : securityEntityFilter = "   AND sa.EntityId IS NULL "
//        IF cToStr(entityId) <> "" THEN securityEntityFilter = "   AND sa.EntityId = " & formatDbField(entityId, "int", true) & " "
//
//        Dim totalRS : Set totalRS = Server.CreateObject("ADODB.Recordset")
//        Dim totalSQL : totalSQL = _
//            "SELECT " & _
//            "   SUM(s.CurrentMarketValue) AS Amount " & _
//            "FROM " & _
//            "   Security AS s " & _
//            "   LEFT OUTER JOIN SecurityAccount AS sa ON s.SecurityAccountId = sa.SecurityAccountId " & _
//            "WHERE " & _
//            "   s.ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false) & _
//            securityEntityFilter & _
//            "   AND sa.IraTypeId " & endQuery
//        totalRS.open totalSQL, db
//        IF NOT totalRS.EOF THEN
//            Dim tmpvalue : tmpvalue = totalRS("Amount")
//            IF Trim(tmpvalue & "") = "" THEN tmpvalue = 0
//            total = cToDbl(tmpvalue)
//        END IF
//        totalRS.Close
//    END IF
//    SumSecuritiesIra = total
//END FUNCTION
//
//FUNCTION SumFieldIra(fieldName, tableName, iraValue)
//    Dim total : total = 0
//    Dim totalRS : Set totalRS = Server.CreateObject("ADODB.Recordset")
//    Dim totalSQL : totalSQL = _
//        "SELECT " & _
//        "   SUM(" & fieldName & ") AS Amount " & _
//        "FROM " & _
//        tableName & " " & _
//        "WHERE " & _
//        "   Ira = " & iraValue & " " & _
//        "   AND ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false)
//    totalRS.open totalSQL, db
//    IF NOT totalRS.EOF THEN
//        Dim tmpvalue : tmpvalue = totalRS("Amount")
//        IF Trim(tmpvalue & "") = "" THEN tmpvalue = 0
//        total = cToDbl(tmpvalue)
//    END IF
//    totalRS.Close
//    SumFieldIra = total
//END FUNCTION
//
//FUNCTION SumFieldIraBit(fieldName, tableName, iraValue, bitField, bitValue)
//    Dim total : total = 0
//    Dim totalRS : Set totalRS = Server.CreateObject("ADODB.Recordset")
//    Dim totalSQL : totalSQL = _
//        "SELECT " & _
//        "   SUM(" & fieldName & ") AS Amount " & _
//        "FROM " & _
//           tableName & " " & _
//        "WHERE " & _
//           bitField & " = " & bitValue & " " & _
//        "  AND Ira = " & iraValue & " " & _
//        "  AND ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false)
//    totalRS.open totalSQL, db
//    IF NOT totalRS.EOF THEN
//        Dim tmpvalue : tmpvalue = totalRS("Amount")
//        IF Trim(tmpvalue & "") = "" THEN tmpvalue = 0
//        total = cToDbl(tmpvalue)
//    END IF
//    totalRS.Close
//    SumFieldIraBit = total
//END FUNCTION
//

//
//SUB CloseConnection()
//    db.Close
//    Set db = Nothing
//END SUB
//

//    IF NOT $_SESSION["loggedIn") THEN header("Location: " . BASE_URL ."/login.asp")
//END FUNCTION
//
//FUNCTION FormatMoney(nAmount)
//    ' ### USED DURING EDITING, REMOVES COMMAS ###
//    IF (Trim(nAmount & "") = "" OR NOT IsNumeric(Trim(nAmount))) THEN
//        FormatMoney = FormatNumber(0, 2)
//    ELSE
//        FormatMoney = Replace(FormatNumber(nAmount, 2), ",", "")
//    END IF
//END FUNCTION
//
//FUNCTION RecordsInTable(tableName)
//    Dim intOut : intOut = 0
//    Dim CountRecordsRS : Set CountRecordsRS = Server.CreateObject("ADODB.Recordset")
//    Dim CountRecordsSQL : CountRecordsSQL = "SELECT COUNT(*) AS thisCount FROM [" & tableName & "]"
//    CountRecordsRS.open CountRecordsSQL, db
//    intOut = CountRecordsRS("thisCount")
//    CountRecordsRS.Close
//    RecordsInTable = intOut
//END FUNCTION
//
//FUNCTION RecordsInTableUser(tableName, idField)
//    Dim intOut : intOut = 0
//    Dim CountRecordsRS : Set CountRecordsRS = Server.CreateObject("ADODB.Recordset")
//    Dim CountRecordsSQL : CountRecordsSQL = "SELECT COUNT(" & idField & ") AS thisCount FROM " & tableName & " WHERE ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false)
//    CountRecordsRS.open CountRecordsSQL, db
//    intOut = CountRecordsRS("thisCount")
//    CountRecordsRS.Close
//    RecordsInTableUser = intOut
//END FUNCTION
//
//FUNCTION GetApplicantRecordsInTable(tableName, primaryKey, showTick)
//    Dim isIgnored : isIgnored = false
//    Dim nModuleName : nModuleName = tableName
//
//    IF nModuleName = "Entity" THEN nModuleName = "Entities"
//    IF nModuleName = "Partnership" THEN nModuleName = "Businesses"
//    IF nModuleName = "Security" THEN nModuleName = "Securities"
//    IF nModuleName = "Vehicle" THEN nModuleName = "Vehicles"
//    IF nModuleName = "Property" THEN nModuleName = "OtherAssets"
//    IF nModuleName = "AccountPayable" THEN nModuleName = "NotesPayable"
//    IF nModuleName = "Incomes" THEN nModuleName = "AnnualIncome"
//
//    IF cToStr(tableName) <> "Insurance" THEN
//        isIgnored = IsModuleIgnored(nModuleName & "Ignore")
//    END IF
//
//    IF isIgnored THEN
//        GetApplicantRecordsInTable = "<span class=""count-badge-ignore"" title=""Module Ignored"">N&nbsp;/&nbsp;A</span>"
//    ELSE
//        Dim intOut : intOut = 0
//        Dim CountRecordsRS : Set CountRecordsRS = Server.CreateObject("ADODB.Recordset")
//        Dim CountRecordsSQL : CountRecordsSQL = "SELECT COUNT(" & primaryKey & ") AS thisCount FROM " & tableName & " WHERE ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false)
//        CountRecordsRS.open CountRecordsSQL, db
//        intOut = CountRecordsRS("thisCount")
//        CountRecordsRS.Close
//
//        IF cToInt(intOut) = 0 THEN EXIT FUNCTION
//        IF showTick THEN
//            GetApplicantRecordsInTable = "<span class=""count-badge-tick""><i class=""fa fa-check"" aria-hidden=""true""></i></span>"
//        ELSE
//            GetApplicantRecordsInTable = "<span class=""count-badge"">" & intOut & "</span>"
//        END IF
//    END IF
//END FUNCTION
//
//FUNCTION GetIncomeRecordsInTable()
//    Dim isIgnored : isIgnored = false
//    isIgnored = IsModuleIgnored("AnnualIncomeIgnore")
//
//    IF isIgnored THEN
//        GetIncomeRecordsInTable = "<span class=""count-badge-ignore"" title=""Module Ignored"">N&nbsp;/&nbsp;A</span>"
//    ELSE
//        Dim numEarned : numEarned = 0
//        Dim numReported : numReported = 0
//        Dim numIncome : numIncome = 0
//
//        ' ### GET INCOME RECORDS ###
//        Dim incomeRS : Set incomeRS = Server.CreateObject("ADODB.Recordset")
//        Dim incomeSQL : incomeSQL = _
//            "SELECT " & _
//            "   'Earned' AS IncomeName " & _
//            "FROM " & _
//            "   Incomes AS i " & _
//            "   INNER JOIN DropDownFields AS ddf ON i.incomeTypeId = ddf.DropdownFieldId " & _
//            "WHERE " & _
//            "   ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false) & " " & _
//            "   AND ddf.DropDownFieldName <> 'Reported Income' " & _
//            "UNION ALL " & _
//            "SELECT " & _
//            "   'Reported' AS IncomeName " & _
//            "FROM " & _
//            "   Incomes AS i " & _
//            "   INNER JOIN DropDownFields AS ddf ON i.incomeTypeId = ddf.DropdownFieldId " & _
//            "WHERE " & _
//            "   ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false) & " " & _
//            "   AND ddf.DropDownFieldName = 'Reported Income'"
//        incomeRS.open incomeSQL, db
//
//        ' ### LOOP THROUGH RECORDS TO GET COUNT ###
//        IF NOT incomeRS.EOF THEN
//            incomeRS.MoveFirst
//            DO WHILE NOT incomeRS.EOF
//                IF incomeRS("IncomeName") = "Earned" THEN
//                    numEarned = numEarned + 1
//                ELSEIF incomeRS("IncomeName") = "Reported" THEN
//                    numReported = numReported + 1
//                END IF
//                incomeRS.movenext
//            LOOP
//        END IF
//        numIncome = numEarned + numReported
//        IF $_SESSION["userPaymentTierType") <> "PRO" THEN numIncome = numEarned
//
//        IF numIncome > 0 THEN
//            GetIncomeRecordsInTable = "<span class=""count-badge-tick""><i class=""fa fa-check"" aria-hidden=""true""></i></span>"
//        ELSE
//            EXIT FUNCTION
//        END IF
//    END IF
//END FUNCTION
//
//FUNCTION GetRealEstateRecordsInTable(isResidence, showTick)
//    Dim intOut : intOut = 0
//    Dim CountRecordsRS : Set CountRecordsRS = Server.CreateObject("ADODB.Recordset")
//    Dim CountRecordsSQL : CountRecordsSQL = "SELECT COUNT(*) AS thisCount FROM RealEstate WHERE IsResidence = '" & isResidence & "' AND ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false)
//    CountRecordsRS.open CountRecordsSQL, db
//    intOut = CountRecordsRS("thisCount")
//    CountRecordsRS.Close
//    Dim isIgnored
//    IF isResidence = "1" THEN
//        isIgnored = IsModuleIgnored("ResidencesIgnore")
//    ELSEIF isResidence = "0" THEN
//        isIgnored = IsModuleIgnored("RealEstateIgnore")
//    END IF
//    IF isIgnored THEN
//        GetRealEstateRecordsInTable = "<span class=""count-badge-ignore"" title=""Module Ignored"">N&nbsp;/&nbsp;A</span>"
//    ELSE
//        IF cToInt(intOut) = 0 THEN EXIT FUNCTION
//        IF showTick THEN
//            GetRealEstateRecordsInTable = "<span class=""count-badge-tick""><i class=""fa fa-check"" aria-hidden=""true""></i></span>"
//        ELSE
//            GetRealEstateRecordsInTable = "<span class=""count-badge"">" & intOut & "</span>"
//        END IF
//    END IF
//END FUNCTION
//
//FUNCTION GetNumberofApplicants()
//    Dim intOut : intOut = 0
//    Dim CountRecordsRS : Set CountRecordsRS = Server.CreateObject("ADODB.Recordset")
//    Dim CountRecordsSQL : CountRecordsSQL = _
//        "SELECT " & _
//        "   COUNT(ApplicantId) AS thisCount " & _
//        "FROM " & _
//        "   Applicant " & _
//        "WHERE " & _
//        "   UserId = " & formatDbField($_SESSION["userId"), "int", false)
//    CountRecordsRS.open CountRecordsSQL, db
//    intOut = CountRecordsRS("thisCount")
//    CountRecordsRS.Close
//    GetNumberofApplicants = intOut
//END FUNCTION
//
//FUNCTION GetMemberName(hasLink)
//    Dim strOut : strOut = ""
//    Dim MemberRS : Set MemberRS = Server.CreateObject("ADODB.Recordset")
//    Dim MemberSQL : MemberSQL = _
//        "SELECT " & _
//        "   ApplicantFirstName, " & _
//        "   ApplicantLastName, " & _
//        "   ApplicantId " & _
//        "FROM " & _
//        "   Applicant " & _
//        "WHERE " & _
//        "   UserId = " & formatDbField($_SESSION["userId"), "int", false)
//    MemberRS.open MemberSQL, db
//    Dim cutFullName : cutFullName = ""
//    Dim fullName : fullName = MemberRS("ApplicantFirstName") & " " & MemberRS("ApplicantLastName")
//    IF LEN(fullName) > 13 THEN
//        cutFullName = Left(fullName, 13) & "..."
//    ELSE
//        cutFullName = fullName
//    END IF
//
//    IF hasLink THEN
//        strOut = "<a href=""member/member_edit.asp?id=" & MemberRS("ApplicantId") & """ title=""" & fullName & """>" & cutFullName & "</a>"
//    ELSE
//        strOut = "<b style=""color:#567A22"" title=""" & fullName & """>" & cutFullName & "</b>"
//    END IF
//    MemberRS.Close
//    GetMemberName = strOut
//END FUNCTION
//
//FUNCTION GetApplicantById()
//    IF $_SESSION["CurrentApplicantName") = "" THEN
//        Dim ApplicantRS : Set ApplicantRS = Server.CreateObject("ADODB.Recordset")
//        Dim ApplicantSQL : ApplicantSQL = "SELECT ApplicantFirstName, ApplicantLastName FROM Applicant WHERE ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false)
//        ApplicantRS.open ApplicantSQL, db
//        IF NOT ApplicantRS.EOF THEN
//            $_SESSION["CurrentApplicantName") = ApplicantRS("ApplicantFirstName") & " " & ApplicantRS("ApplicantLastName")
//        END IF
//        ApplicantRS.Close
//    END IF
//    GetApplicantById = "<b>" & $_SESSION["CurrentApplicantName") & "</b>"
//END FUNCTION
//
//FUNCTION GetBrokerNameById(nBrokerId)
//    IF Trim(nBrokerId & "") = "" THEN EXIT FUNCTION
//    Dim brokerName : brokerName = ""
//    Dim BrokerRS : Set BrokerRS = Server.CreateObject("ADODB.Recordset")
//    Dim BrokerSQL : BrokerSQL = _
//        "SELECT BrokerName FROM Broker WHERE BrokerId = " & formatDbField(nBrokerId, "int", false)
//    BrokerRS.open BrokerSQL, db
//    IF NOT BrokerRS.EOF THEN
//        brokerName = BrokerRS("BrokerName")
//    END IF
//    BrokerRS.Close
//    GetBrokerNameById = brokerName
//END FUNCTION
//
//SUB SetApplicantById()
//    IF $_SESSION["CurrentApplicantName") = "" THEN
//        Dim ApplicantRS : Set ApplicantRS = Server.CreateObject("ADODB.Recordset")
//        Dim ApplicantSQL : ApplicantSQL = "SELECT ApplicantFirstName, ApplicantLastName FROM Applicant WHERE ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false)
//        ApplicantRS.open ApplicantSQL, db
//        IF NOT ApplicantRS.EOF THEN
//            $_SESSION["CurrentApplicantName") = ApplicantRS("ApplicantFirstName") & " " & ApplicantRS("ApplicantLastName")
//        END IF
//        ApplicantRS.Close
//    END IF
//END SUB
//
//FUNCTION GetMemberId()
//    Dim strOut : strOut = ""
//    Dim MemberRS : Set MemberRS = Server.CreateObject("ADODB.Recordset")
//    Dim MemberSQL : MemberSQL = _
//        "SELECT " & _
//        "   ApplicantId " & _
//        "FROM " & _
//        "   Applicant " & _
//        "WHERE " & _
//        "   UserId = " & formatDbField($_SESSION["userId"), "int", false)
//    MemberRS.open MemberSQL, db
//    IF NOT MemberRS.EOF THEN
//        strOut = MemberRS("ApplicantId")
//    END IF
//    MemberRS.Close
//
//    GetMemberId = strOut
//END FUNCTION
//
//SUB GetApplicantDetails(nMemberId)
//    Dim strOut : strOut = ""
//    Dim ApplicantRS : Set ApplicantRS = Server.CreateObject("ADODB.Recordset")
//    Dim ApplicantSQL : ApplicantSQL = _
//        "SELECT " & _
//        "   ApplicantFirstName, ApplicantLastName, " & _
//        "   ApplicantAge, ApplicantCurrency " & _
//        "FROM " & _
//        "   Applicant " & _
//        "WHERE " & _
//        "   ApplicantId = " & formatDbField(nMemberId, "int", false)
//    ApplicantRS.open ApplicantSQL, db
//    IF NOT ApplicantRS.EOF THEN
//        $_SESSION["MEMBER-AGE") = ApplicantRS("ApplicantAge")
//        $_SESSION["memberCurrencyCode") = GetDropdownFieldCode(ApplicantRS("ApplicantCurrency"))
//        $_SESSION["activeApplicantName") = ApplicantRS("ApplicantFirstName") & " " & ApplicantRS("ApplicantLastName")
//    END IF
//    ApplicantRS.Close
//
//    ' ### LOAD CALCULATIONS INTO SESSION ###
//    'LoadCalculationsInSession()
//END SUB
//
//FUNCTION GetId(nField, nTable)
//    Dim strOut : strOut = ""
//    Dim GetIdRS : Set GetIdRS = Server.CreateObject("ADODB.Recordset")
//    Dim GetIdSQL : GetIdSQL = _
//        "SELECT " & nField & " FROM " & nTable & " WHERE ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false) & " LIMIT 1"
//    GetIdRS.open GetIdSQL, db
//    IF NOT GetIdRS.EOF THEN strOut = GetIdRS(nField)
//    GetIdRS.Close
//    GetId = strOut
//END FUNCTION
//
//FUNCTION GetFieldById(nField, nIdField, nIdValue, nTable)
//    ' ### GetFieldById("DropdownFieldName", "DropdownFieldId", cardTypeId, "DropDownFields")
//    Dim strOut : strOut = ""
//    Dim GetFieldRS : Set GetFieldRS = Server.CreateObject("ADODB.Recordset")
//    Dim GetFieldSQL : GetFieldSQL = _
//        "SELECT " & nField & " FROM " & nTable & " WHERE " & nIdField & " = " & formatDbField(nIdValue, "int", false)
//    GetFieldRS.open GetFieldSQL, db
//    IF NOT GetFieldRS.EOF THEN strOut = GetFieldRS(nField)
//    GetFieldRS.Close
//    GetFieldById = strOut
//END FUNCTION
//
//FUNCTION GetFieldByName(nField, nNameField, nNameValue, nTable)
//    ' ### GetFieldByName("DropdownFieldId", "DropdownFieldName", "Reported Income", "DropDownFields")
//    Dim strOut : strOut = ""
//    Dim GetFieldRS : Set GetFieldRS = Server.CreateObject("ADODB.Recordset")
//    Dim GetFieldSQL : GetFieldSQL = _
//        "SELECT " & nField & " FROM " & nTable & " WHERE " & nNameField & " = '" & nNameValue & "'"
//    GetFieldRS.open GetFieldSQL, db
//    IF NOT GetFieldRS.EOF THEN strOut = GetFieldRS(nField)
//    GetFieldRS.Close
//    GetFieldByName = strOut
//END FUNCTION
//
//FUNCTION HelpIcon(fnMessage, fnWidth)
//    HelpIcon = "<img src=""" & DOMAIN & "/images/icons/help.png"" title=""" & fnMessage & """ alt=""Help""/>"
//END FUNCTION
//
//FUNCTION GetContentBlock(code)
//    IF Trim(code & "") = "" THEN EXIT FUNCTION
//    Dim pStrOut : pStrOut = ""
//    Dim ContentBlockRS : Set ContentBlockRS = Server.CreateObject("ADODB.Recordset")
//    Dim ContentBlockSQL : ContentBlockSQL = _
//       "SELECT " & _
//       "   BlockContent " & _
//       "FROM " & _
//       "   Block " & _
//       "WHERE " & _
//       "   BlockCode = '" & code & "';"
//    ContentBlockRS.open ContentBlockSQL, db
//    IF NOT ContentBlockRS.EOF THEN
//        pStrOut = Replace(ContentBlockRS("BlockContent"), "''", "'")
//        IF cToStr(pStrOut) = "NULL" THEN
//            pStrOut = "Description required here [Content Block: " & code & "]"
//        END IF
//    ELSE
//        pStrOut = "Content block required here [" & code & "]"
//    END IF
//    ContentBlockRS.Close
//    GetContentBlock = pStrOut
//END FUNCTION
//
//FUNCTION GetFileTypeIcon(nExtension)
//    IF Trim(nExtension & "") = "" THEN EXIT FUNCTION
//    Dim iconType : iconType = ""
//    Dim fileType : fileType = ""
//    nExtension = lCase(nExtension)
//    IF nExtension = "png" THEN
//        iconType = "fa-file-image-o"
//        fileType = "image/png"
//    ELSEIF nExtension = "jpg" THEN
//        iconType = "fa-file-image-o"
//        fileType = "image/jpeg"
//    ELSEIF nExtension = "gif" THEN
//        iconType = "fa-file-image-o"
//        fileType = "image/gif"
//    ELSEIF nExtension = "bmp" THEN
//        iconType = "fa-file-image-o"
//        fileType = "image/bmp"
//    ELSEIF (nExtension = "xlsx" OR nExtension = "xls") THEN
//        iconType = "fa-file-excel-o"
//        fileType = "application/ms-excel"
//    ELSEIF (nExtension = "docx" OR nExtension = "doc") THEN
//        iconType = "fa-file-word-o"
//        fileType = "application/ms-word"
//    ELSEIF nExtension = "txt" THEN
//        iconType = "fa-file-text-o"
//        fileType = "text/plain"
//    ELSEIF nExtension = "pdf" THEN
//        iconType = "fa-file-pdf-o"
//        fileType = "application/pdf"
//    ELSEIF (nExtension = "pptx" OR nExtension = "ppt") THEN
//        iconType = "fa-file-powerpoint-o"
//        fileType = "application/ms-powerpoint"
//    ELSEIF nExtension <> "png" AND nExtension = "jpg" AND nExtension = "gif" AND nExtension = "bmp" THEN
//        iconType = "fa-file-o"
//        fileType = "file/unknown"
//    END IF
//    GetFileTypeIcon = "<i class=""fa " & iconType & """ aria-hidden=""true""></i>"
//END FUNCTION
//
//FUNCTION GetResourceBlock(code, openInNewTab)
//    IF Trim(code & "") = "" THEN EXIT FUNCTION
//    Dim pStrOut : pStrOut = 0
//    Dim ContentBlockRS : Set ContentBlockRS = Server.CreateObject("ADODB.Recordset")
//    Dim ContentBlockSQL : ContentBlockSQL = _
//        "SELECT " & _
//        "   BlockContent, " & _
//        "   BlockHyperlinkText, " & _
//        "   ddf.DropDownFieldName AS BlockTypeName " & _
//        "FROM " & _
//        "   Block AS b " & _
//        "   INNER JOIN DropDownFields AS ddf ON b.BlockType = ddf.DropDownFieldId " & _
//        "WHERE " & _
//        "   BlockCode = '" & code & "';"
//    ContentBlockRS.open ContentBlockSQL, db
//    IF NOT ContentBlockRS.EOF THEN
//        Dim extension : extension = ""
//        Dim blockTypeName : blockTypeName = lCase(ContentBlockRS("BlockTypeName"))
//        Dim fileName : fileName = Replace(ContentBlockRS("BlockContent"), "/Uploaded_Docs/", "")
//        IF blockTypeName = "document" THEN
//            extension = Right(fileName, Len(fileName) - InStrRev(fileName, "."))
//            IF openInNewTab THEN
//                pStrOut = "<a href=""" & ContentBlockRS("BlockContent") & """ title=""" & ContentBlockRS("BlockHyperlinkText") & """ target=""_blank"">" & GetFileTypeIcon(extension) & "</a><span class=""content-block"">&nbsp;&nbsp;<a href=""" & ContentBlockRS("BlockContent") & """ target=""_blank"">" & ContentBlockRS("BlockHyperlinkText") & "</a></span>"
//            ELSE
//                pStrOut = "<a href=""" & ContentBlockRS("BlockContent") & """ title=""" & ContentBlockRS("BlockHyperlinkText") & """>" & GetFileTypeIcon(extension) & "</a><span class=""content-block"">&nbsp;&nbsp;<a href=""" & ContentBlockRS("BlockContent") & """>" & ContentBlockRS("BlockHyperlinkText") & "</a></span>"
//            END IF
//        ELSEIF blockTypeName = "image" THEN
//            pStrOut = "<img src=""" & ContentBlockRS("BlockContent") & """ alt=""""/>"
//        END IF
//    END IF
//    ContentBlockRS.Close
//    GetResourceBlock = pStrOut
//END FUNCTION
//

//
//FUNCTION ShowPagination(pageNumber, maxPage, pageUri, filter, filterValue)
//    Dim i, pstrOut
//    Dim page : page = cInt(pageNumber)
//    maxPage = cInt(maxPage)
//    IF maxPage <= 1 THEN EXIT FUNCTION
//
//    Dim pageUrl : pageUrl = ""
//    IF cToStr(filter) <> "" THEN
//        pageUrl = pageUri & "?" & filter & "=" & filterValue & "&"
//    ELSE
//        pageUrl = pageUri & "?"
//    END IF
//
//    pstrOut = pstrOut & "" _
//          & "<ul class=""pagination"">" & vbCr
//
//    IF page >= 2 THEN
//        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=1""><i class=""fa fa-caret-left"" aria-hidden=""true""></i>&nbsp;&nbsp;First</a></li>"
//    END IF
//    IF page >= 2 THEN
//        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & page - 1 & """><i class=""fa fa-caret-left"" aria-hidden=""true""></i>&nbsp;&nbsp;Previous</a></li>"
//    END IF
//    IF maxPage > 5 THEN
//        nPageT = page + 4
//        nPageCountDiff = maxPage - page
//        IF nPageCountDiff = 0 THEN
//            nPageCountDiff2 = 4
//        ELSEIF nPageCountDiff = 1 THEN
//            nPageCountDiff2 = 3
//        ELSEIF nPageCountDiff = 2 THEN
//            nPageCountDiff2 = 2
//        ELSE
//            nPageCountDiff2 = 1
//        END IF
//        IF nPageT > maxPage THEN
//            IF nPageCountDiff <= 3 THEN
//                FOR iPages = page - nPageCountDiff2 to maxPage
//                    IF ipages = page THEN
//                        pstrOut = pstrOut & "<li><b>" & iPages & "</b></li>"
//                    ELSE
//                        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & ipages & """>" & ipages & "</a></li>"
//                    END IF
//                NEXT
//            ELSE
//                FOR iPages = page to maxPage
//                    IF ipages = page THEN
//                        pstrOut = pstrOut & "<li><b>" & iPages & "</b></li>"
//                    ELSE
//                        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & ipages & """>" & ipages & "</a></li>"
//                    END IF
//                NEXT
//            END IF
//        ELSE
//            IF page = 1 THEN
//                FOR iPages = page to page + 4
//                    IF ipages = page THEN
//                        pstrOut = pstrOut & "<li><b>" & iPages & "</b></li>"
//                    ELSE
//                        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & ipages & """>" & ipages & "</a></li>"
//                    END IF
//                NEXT
//            ELSEIF page = 2 THEN
//                FOR iPages = page - 1 to page + 3
//                    IF ipages = page THEN
//                        pstrOut = pstrOut & "<li><b>" & iPages & "</b></li>"
//                    ELSE
//                        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & ipages & """>" & ipages & "</a></li>"
//                    END IF
//                NEXT
//            ELSE
//                FOR iPages = page - 2 to page + 2
//                    IF ipages = page THEN
//                        pstrOut = pstrOut & "<li><b>" & iPages & "</b></li>"
//                    ELSE
//                        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & ipages & """>" & ipages & "</a></li>"
//                    END IF
//                NEXT
//            END IF
//        END IF
//    ELSE
//        FOR iPages = 1 to maxPage
//            IF ipages = page THEN
//                pstrOut = pstrOut & "<li><b>" & ipages & "</b></li>"
//            ELSE
//                pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & ipages & """>" & ipages & "</a></li>"
//            END IF
//        NEXT
//    END IF
//    IF maxPage > 1 THEN
//        IF page <> maxPage THEN
//            pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & page+1 & """>Next&nbsp;&nbsp;<i class=""fa fa-caret-right"" aria-hidden=""true""></i></a></li>"
//        END IF
//    END IF
//    IF maxPage > 1 THEN
//        IF page <> maxPage THEN
//            pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & maxPage & """>Last&nbsp;&nbsp;<i class=""fa fa-caret-right"" aria-hidden=""true""></i></a></li>"
//        END IF
//    END IF
//
//    pstrOut = pstrOut & "</ul>" & vbCr
//    ShowPagination = pstrOut
//END FUNCTION
//
//FUNCTION HelpIconDb(helpCode)
//    IF Trim(helpCode & "") = "" THEN EXIT FUNCTION
//    Dim iconMessage : iconMessage = ""
//    Dim helpIconRS : Set helpIconRS = Server.CreateObject("ADODB.Recordset")
//    Dim helpIconSQL : helpIconSQL = "SELECT HelpMessage FROM HelpIcons WHERE HelpType = 'ICON' AND HelpCode = '" & helpCode & "';"
//    helpIconRS.open helpIconSQL, db
//    IF NOT helpIconRS.EOF THEN
//        iconMessage = helpIconRS("HelpMessage")
//    END IF
//    helpIconRS.Close
//    HelpIconDb = "<i class=""fa fa-info-circle display-info"" title=""" & iconMessage & """ aria-hidden=""true""></i>"
//END FUNCTION
//
//FUNCTION HelpRolloverDb(helpCode)
//    IF Trim(helpCode & "") = "" THEN EXIT FUNCTION
//    Dim rolloverMessage : rolloverMessage = ""
//    Dim helpType : helpType = ""
//    Dim helpRolloverRS : Set helpRolloverRS = Server.CreateObject("ADODB.Recordset")
//    Dim helpRolloverSQL : helpRolloverSQL = "SELECT HelpType, HelpMessage FROM HelpIcons WHERE (HelpType = 'ROLL' OR HelpType = 'KEND') AND HelpCode = '" & helpCode & "';"
//    helpRolloverRS.open helpRolloverSQL, db
//    IF NOT helpRolloverRS.EOF THEN
//        rolloverMessage = helpRolloverRS("HelpMessage")
//        helpType = helpRolloverRS("HelpType")
//    END IF
//    helpRolloverRS.Close
//    IF Trim(helpType & "") = "KEND" THEN
//        HelpRolloverDb = rolloverMessage & """ data-help=""" & helpCode
//    ELSE
//        HelpRolloverDb = rolloverMessage
//    END IF
//END FUNCTION
//
//
//FUNCTION isSuperUser()
//    Dim userGroup : userGroup = ""
//    Dim userRS : Set userRS = Server.CreateObject("ADODB.Recordset")
//    Dim userSQL : userSQL = _
//        "SELECT " & _
//        "   ug.GroupName " & _
//        "FROM " & _
//        "   User AS u " & _
//        "   LEFT OUTER JOIN Groups AS ug ON u.Groups = ug.GroupId " & _
//        "WHERE " & _
//        "   UserId = " & formatDbField($_SESSION["userId"), "int", false)
//    userRS.open userSQL, db
//    IF NOT userRS.EOF THEN
//        userGroup = userRS("GroupName")
//    END IF
//    userRS.Close
//
//    boolIsSuperUser = false
//    IF (Trim(userGroup & "") = "Super User" OR Trim(userGroup & "") = "Developer") THEN boolIsSuperUser = true
//
//    isSuperUser = boolIsSuperUser
//END FUNCTION
//
//FUNCTION hasDataInField(strIn)
//    Dim boolOut : boolOut = false
//    IF Trim(strIn & "") <> "" THEN boolOut = true
//    hasDataInField = boolOut
//END FUNCTION
//
//FUNCTION GetRandom(charCount)
//    Randomize
//    FOR r = 1 TO charCount
//        IF (Int((1 - 0 + 1) * Rnd + 0)) THEN
//            GetRandom = GetRandom & Chr(Int((90 - 65 + 1) * Rnd + 65))
//        ELSE
//            GetRandom = GetRandom & Chr(Int((57 - 48 + 1) * Rnd + 48))
//        END IF
//    NEXT
//END FUNCTION
//
//SUB SaveProfilePaymentHistory(nPaymentTierId, nUser, nOutcome, dAmount, sAuthorizationId, sCaptureId, bIsTrialPeriod, sAmazonOrderReferenceId, sAmazonBillingAgreementId)
//    IF Trim(nPaymentTierId & "") = "" OR Trim(nUser & "") = "" THEN EXIT SUB
//
//    ' ### GET PAYMENT TIER DETAILS FOR PAYMENT HISTORY RECORD ###
//    Dim PaymentNameRS : Set PaymentNameRS = Server.CreateObject("ADODB.Recordset")
//    Dim PaymentNameSQL : PaymentNameSQL = _
//        "SELECT " & _
//        "   PaymentTierCode, " & _
//        "   PaymentTierYearlyCost " & _
//        "FROM " & _
//        "   PaymentTier " & _
//        "WHERE " & _
//        "   PaymentTierId = " & formatDbField(nPaymentTierId, "int", false)
//    PaymentNameRS.Open PaymentNameSQL, db
//    IF NOT PaymentNameRS.EOF THEN
//        Dim tierCode : tierCode = PaymentNameRS("PaymentTierCode")
//
//        Dim tierYearCost
//        IF cToStr(dAmount) = "" THEN
//            tierYearCost = PaymentNameRS("PaymentTierYearlyCost")
//        ELSE
//            tierYearCost = dAmount
//        END IF
//
//        Dim bIsCaptured : bIsCaptured = "1"
//
//        Dim bTrialPeriod : bTrialPeriod = "1"
//        If bIsTrialPeriod = true THEN bTrialPeriod = "0"
//    END IF
//    PaymentNameRS.Close
//
//    ' ### SAVE PAYMENT HISTORY ###
//
//    Dim strSQL : strSQL = _
//        "INSERT INTO PaymentHistory(PaymentUserId,PaymentDescription,PaymentDate,PaymentAmount,PaymentOutcome,IsCaptured,AmazonAuthorizationId,AmazonCaptureId,PaymentCaptureDate,AmazonOrderReferenceId,AmazonBillingAgreementId) " & _
//        "VALUES(" & formatDbField(nUser, "int", false) & _
//        "," & formatDbField("Change of Membership to " & tierCode, "text", false) & _
//        ",CurDate()" & _
//        "," & formatDbField(tierYearCost, "decimal", false) & _
//        "," & formatDbField(nOutcome, "text", false) & _
//        "," & formatDbField(bTrialPeriod, "bit", false)
//
//        IF NOT (bIsTrialPeriod) Then
//            strSQL = strSQL + "," & formatDbField(sAuthorizationId, "text", false) & _
//                "," & formatDbField(sCaptureId, "text", false) & _
//                ",NULL" & _
//                "," & formatDbField(sAmazonOrderReferenceId, "text", false) & _
//                "," & formatDbField(sAmazonBillingAgreementId, "text", false) & ")"
//        ELSE
//            strSQL = strSQL +  ",NULL,NULL,NULL" & _
//                "," & formatDbField(sAmazonOrderReferenceId, "text", false) & _
//                "," & formatDbField(sAmazonBillingAgreementId, "text", false) & ")"
//        END IF
//
//    db.Execute(strSQL)
//END SUB
//
//SUB SaveUserPayment(nPaymentTierId, nUser)
//    IF Trim(nUser & "") = "" THEN EXIT SUB
//    Dim strSQL : strSQL = _
//        "UPDATE User SET " & _
//        "   PaymentTierId = " & formatDbField(nPaymentTierId, "text", false) & ", " & _
//        "   PaymentConfirmed = " & formatDbField("1", "bit", false) & ", " & _
//        "   PaymentExpirationDate = dateadd(year, 1, CurDate()), " & _
//        "   FreeExpirationDate = NULL " & _
//        " WHERE UserId = " & formatDbField(nUser, "int", false) & ";"
//    db.Execute(strSQL)
//    $_SESSION["FreeExpirationDate") = ""
//
//    $_SESSION["userPaymentTierId") = nPaymentTierId
//
//    Dim PaymentNameRS : Set PaymentNameRS = Server.CreateObject("ADODB.Recordset")
//    Dim PaymentNameSQL : PaymentNameSQL = "SELECT PaymentTierCode, PaymentTierType FROM PaymentTier WHERE PaymentTierId = " & formatDbField(nPaymentTierId, "int", false)
//    PaymentNameRS.Open PaymentNameSQL, db
//    IF NOT PaymentNameRS.EOF THEN
//        $_SESSION["userPaymentTierCode") = PaymentNameRS("PaymentTierCode")
//        $_SESSION["userPaymentTierType") = PaymentNameRS("PaymentTierType")
//    END IF
//    PaymentNameRS.Close
//END SUB
//
//FUNCTION GetDropdownListName(id)
//    IF Trim(id & "") = "" THEN EXIT FUNCTION
//    Dim dropDownParentName : dropDownParentName = ""
//    Dim dropdownListRS : Set dropdownListRS = Server.CreateObject("ADODB.Recordset")
//    Dim dropdownListSQL : dropdownListSQL = _
//        "SELECT " & _
//        "   DropDownParentName " & _
//        " FROM " & _
//        "   DropDownParent " & _
//        " WHERE " & _
//        "   DropDownParentId = " & formatDbField(id, "int", false)
//    dropdownListRS.open dropdownListSQL, db
//    IF NOT dropdownListRS.EOF THEN
//        dropDownParentName = dropdownListRS("DropDownParentName")
//    END IF
//    dropdownListRS.Close
//    GetDropdownListName = dropDownParentName
//END FUNCTION
//
//FUNCTION CreateDropdown(dropdownCode, selectPlaceholder, currentValue, selectName)
//    Dim strOut : strOut = ""
//    IF Trim(selectPlaceholder & "") = "" THEN selectPlaceholder = "Select One..."
//
//    Dim menuRS : Set menuRS = Server.CreateObject("ADODB.Recordset")
//    Dim menuSQL : menuSQL = _
//        "SELECT " & _
//        "   ddf.DropdownFieldId, " & _
//        "   ddf.DropdownFieldName " & _
//        " FROM " & _
//        "   DropDownFields AS ddf " & _
//        "   INNER JOIN DropDownParent AS ddp ON ddf.DropDownParentId = ddp.DropDownParentId " & _
//        " WHERE " & _
//        "   ddp.DropDownCode = '" & dropdownCode & "' " & _
//        " ORDER BY " & _
//        "   DropdownOrder"
//    menuRS.open menuSQL, db
//    strOut = "<select name=""dd" & Replace(selectName, "-", "") & """ id=""" & lCase(selectName) & """>" & vbCr
//    IF menuRS.EOF THEN
//        strOut = strOut & "<option value="""">No Records Found</option>" & vbCr
//    ELSE
//        strOut = strOut & "<option value="""">" & selectPlaceholder & "</option>" & vbCr
//        menuRS.MoveFirst
//        DO WHILE NOT menuRS.EOF
//            IF Trim(currentValue & "") <> "" THEN
//                IF cInt(menuRS("DropdownFieldId")) = cInt(currentValue) THEN
//                    strOut = strOut & "<option value=""" & menuRS("DropdownFieldId") & """ selected=""selected"">" & menuRS("DropdownFieldName") & "</option>" & vbCr
//                ELSE
//                    strOut = strOut & "<option value=""" & menuRS("DropdownFieldId") & """>" & menuRS("DropdownFieldName") & "</option>" & vbCr
//                END IF
//            ELSE
//                strOut = strOut & "<option value=""" & menuRS("DropdownFieldId") & """>" & menuRS("DropdownFieldName") & "</option>" & vbCr
//            END IF
//            menuRS.movenext
//        LOOP
//    END IF
//    menuRS.Close
//    strOut = strOut & "</select>"
//    CreateDropdown = strOut
//END FUNCTION
//
//FUNCTION CreateSalt()
//    Dim i, n, chars
//    CreateSalt = ""
//    Randomize
//    chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"
//    FOR i = 1 TO 8
//        n = int(Len(chars) * Rnd()) + 1
//        CreateSalt = CreateSalt & Mid(chars, n, 1)
//    NEXT
//END FUNCTION
//
//FUNCTION Hash(str)
//    Hash = SecureHash(str)
//END FUNCTION
//
//SUB AddJoinedIncome(incomeDetails, fieldId, fieldValue, entityId)
//    IF Trim(incomeDetails & "") = "" THEN EXIT SUB
//    Dim incomeId : incomeId = ""
//    Dim incomeType : incomeType = GetFieldByName("DropdownFieldId", "DropdownFieldName", "Reported Income", "DropDownFields")
//
//    Dim getIncomeIdRS : Set getIncomeIdRS = Server.CreateObject("ADODB.Recordset")
//    Dim getIncomeIdSQL : getIncomeIdSQL = _
//        "SELECT " & _
//        "   IncomeId " & _
//        "FROM " & _
//        "   Incomes " & _
//        "WHERE " & fieldId & " = " & formatDbField(fieldValue, "int", false) & " " & _
//        "  AND ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false) & " " & _
//        "LIMIT 1"
//    getIncomeIdRS.open getIncomeIdSQL, db
//    IF NOT getIncomeIdRS.EOF THEN incomeId = getIncomeIdRS("IncomeId")
//    getIncomeIdRS.Close
//
//    IF Trim(incomeId & "") <> "" THEN
//        Dim strSQL : strSQL = _
//            "UPDATE Incomes SET " & _
//            "   IncomeTypeId = " & formatDbField(incomeType, "int", false) & ", " & _
//            "   IncomeDetails = " & formatDbField(incomeDetails, "text", false) & ", " & _
//            "   EntityId = " & formatDbField(entityId, "int", true) & ", " & _
//            "   LastUpdated = CURRENT_TIMESTAMP() " & _
//            " WHERE " & _
//            "   IncomeId = " & formatDbField(incomeId, "int", false) & _
//            "   AND " & fieldId & " = " & formatDbFieldAdd(fieldValue, "int", true) & ";"
//        db.Execute(strSQL)
//    ELSE
//        Dim incomeColumns : incomeColumns = "ApplicantId,IncomeTypeId,IncomeDetails," & fieldId & ",EntityId"
//        Dim incomeValues : incomeValues = $_SESSION["activeApplicantId") & "," & formatDbField(incomeType, "int", false) & "," & formatDbField(incomeDetails, "text", false) & "," & formatDbField(fieldValue, "int", true) & "," & formatDbField(entityId, "int", true)
//        Call InsertNewRecord("Incomes", incomeColumns, incomeValues)
//    END IF
//END SUB
//
//FUNCTION ThisOrThat(LogicalExpression, ValueIfTrue, ValueIfFalse)
//    IF LogicalExpression THEN
//        ThisOrThat = ValueIfTrue
//    ELSE
//        ThisOrThat = ValueIfFalse
//    END IF
//END FUNCTION
//

//
//
//FUNCTION ArrayToJSON(dataArray, dataFields, dataArrayName)
//    IF NOT IsArray(dataArray) THEN EXIT FUNCTION
//    ReDim arrObj(uBound(dataArray, 2) - 1)
//    Dim i, j
//    Dim pstrOut : pstrOut = ""
//    FOR i = 0 TO uBound(dataArray, 2)
//        pstrOut = pstrOut & "{"
//        ReDim arrProp(uBound(dataArray, 1))
//        FOR j = 0 TO uBound(dataArray, 1)
//            pstrOut = pstrOut & """" & dataFields(j) & """:""" & EscapeCharactersJSON(dataArray(j, i)) & ""","
//        NEXT
//        pstrOut = Left(pstrOut, Len(pstrOut) - 1) & "},"
//    NEXT
//    ArrayToJSON = "{""" & dataArrayName & """:[" + Left(pstrOut,  Len(pstrOut) - 1) + "]}"
//END FUNCTION
//
//FUNCTION EscapeCharactersJSON(strIn)
//    IF Trim(strIn & "") = "" THEN EXIT FUNCTION
//    Dim strOut : strOut = ""
//    strOut = Replace(strIn, "\", "\\")
//    strOut = Replace(strOut, "{", "")
//    strOut = Replace(strOut, "}", "")
//    EscapeCharactersJSON = strOut
//END FUNCTION
//
//FUNCTION LineCutOff(orgString, orgStringLen, hasEllipsis)
//    Dim pstrOut : pstrOut = ""
//    Dim Dots : Dots = ""
//    IF hasEllipsis THEN Dots = "..."
//    IF LEN(orgString) > orgStringLen THEN
//        pstrOut = "<span title=""" & orgString & """>" & Left(orgString, orgStringLen) & Dots & "</span>"
//    ELSE
//        pstrOut = orgString
//    END IF
//    LineCutOff = pstrOut
//END FUNCTION
//
//FUNCTION RemoveHTML(strText)
//    If Trim(strText & "") = "" THEN EXIT FUNCTION
//    Dim RegEx : Set RegEx = New RegExp
//    Dim pstrOut
//    RegEx.Pattern = "<[^>]*>"
//    RegEx.Global = True
//    pstrOut = RegEx.Replace(strText, "")
//    RegEx.Pattern = "&lt;[^&gt;]*&gt;"
//    RegEx.Global = True
//    pstrOut = RegEx.Replace(pstrOut, "")
//    pstrOut = replace(pstrOut,vbLf,"")
//    pstrOut = replace(pstrOut,vbCrLf,"")
//    pstrOut = replace(pstrOut,vbCr,"")
//    pstrOUt = replace(pstrOut,"&nbsp;"," ")
//    pstrOut = replace(pstrOut," & "," &amp; ")
//    RemoveHTML = pstrOut
//END FUNCTION
//
//SUB IsRecordOwner(nApplicantId, url)
//    IF cStr($_SESSION["UserId")) <> cStr(nApplicantId) THEN
//        $_SESSION["hasAlert") = true
//        $_SESSION["alertType") = "danger"
//        $_SESSION["alertMessage") = "You do not have permission to view this record..!"
//        IF Trim(url & "") = "" THEN
//            header("Location: " . BASE_URL ."/")
//        ELSE
//            header("Location: " . BASE_URL ."/" & url & "/")
//        END IF
//    END IF
//END SUB
//
//SUB recordNotFound(url)
//    $_SESSION["hasAlert") = true
//    $_SESSION["alertType") = "danger"
//    $_SESSION["alertMessage") = "Sorry, this record was not found."
//    IF cToStr(url) = "" THEN
//        header("Location: " . BASE_URL ."/")
//    ELSE
//        header("Location: " . BASE_URL ."/" & url & "/")
//    END IF
//END SUB
//
//SUB NoValidRecordPassed(url)
//    $_SESSION["hasAlert") = true
//    $_SESSION["alertType") = "danger"
//    $_SESSION["alertMessage") = "Sorry, a valid field id must be passed."
//    IF cToStr(url) = "" THEN
//        header("Location: " . BASE_URL ."/")
//    ELSE
//        header("Location: " . BASE_URL ."/" & url & "/")
//    END IF
//END SUB
//
//FUNCTION AddItem(arr, val)
//    ReDim Preserve arr(uBound(arr) + 1)
//    arr(uBound(arr)) = val
//    AddItem = arr
//END FUNCTION
//
//FUNCTION GetScorecardZones()
//    Dim scoreCardArray : scoreCardArray = ""
//    Dim ScorecardRS : Set ScorecardRS = Server.CreateObject("ADODB.Recordset")
//    Dim ScorecardSQL : ScorecardSQL = _
//        "SELECT " & _
//        "   ScorecardId, RedZone, OrangeZone, YellowZone, GreenZone " & _
//        "FROM " & _
//        "   Scorecard " & _
//        "WHERE " & _
//        "   PaymentTierType = '" & $_SESSION["userPaymentTierType") & "' " & _
//        "   AND IsVisible = 1 " & _
//        "ORDER BY " & _
//        "   RowOrder;"
//    ScorecardRS.open ScorecardSQL, db
//    IF NOT ScorecardRS.EOF THEN
//        DO WHILE NOT ScorecardRS.EOF
//            scoreCardArray = scoreCardArray & ScorecardRS("ScorecardId") & ","
//            scoreCardArray = scoreCardArray & ScorecardRS("RedZone") & ","
//            scoreCardArray = scoreCardArray & ScorecardRS("OrangeZone") & ","
//            scoreCardArray = scoreCardArray & ScorecardRS("YellowZone") & ","
//            scoreCardArray = scoreCardArray & ScorecardRS("GreenZone") & "||"
//            ScorecardRS.movenext
//        LOOP
//        scoreCardArray = Left(scoreCardArray, Len(scoreCardArray) - 2)
//    END IF
//    GetScorecardZones = scoreCardArray
//END FUNCTION
//
//' ### COERCION FUNCTION ###
//FUNCTION cToStr(inStr)
//    cToStr = Trim(inStr & "")
//END FUNCTION
//
//' ### ENSURE A VALUE IS AVAILABLE BEFORE CONVERTION ###
//FUNCTION cToInt(inStr)
//    IF Trim(inStr & "") = "" THEN inStr = 0
//    cToInt = cInt(inStr)
//END FUNCTION
//
//' ### ENSURE A VALUE IS AVAILABLE BEFORE CONVERTION ###
//FUNCTION cToLng(inStr)
//    IF Trim(inStr & "") = "" THEN inStr = 0
//    cToLng = cLng(inStr)
//END FUNCTION
//
//' ### ENSURE A VALUE IS AVAILABLE BEFORE CONVERTION ###
//FUNCTION cToDbl(inStr)
//    IF Trim(inStr & "") = "" THEN inStr = 0
//    cToDbl = cDbl(inStr)
//END FUNCTION
//
//FUNCTION GetModuleName(moduleId)
//    IF Trim(moduleId & "") = "" THEN EXIT FUNCTION
//    Dim moduleName : moduleName = ""
//    Dim moduleRS : Set moduleRS = Server.CreateObject("ADODB.Recordset")
//    Dim moduleSQL : moduleSQL = _
//        "SELECT ModuleName FROM Module WHERE ModuleId = " & formatDbField(moduleId, "int", false)
//    moduleRS.open moduleSQL, db
//    IF NOT moduleRS.EOF THEN
//        moduleName = moduleRS("ModuleName")
//    END IF
//    moduleRS.Close
//    GetModuleName = moduleName
//END FUNCTION
//
//FUNCTION BreadcrumbHome()
//    $_SESSION["CurrentDash") = "home"
//    BreadcrumbHome = _
//        "<li><a href=""/""><i class=""fa fa-home fa-fw"" title=""Home"" aria-hidden=""true""></i></a></li>" & vbCr & _
//        "<li id=""bcrmb-home""><a href=""/"">Home</a></li>" & vbCr & _
//        "<li><i class=""fa fa-caret-right"" aria-hidden=""true""></i></li>" & vbCr
//END FUNCTION
//
//FUNCTION BreadcrumbSpacer()
//    BreadcrumbSpacer = "<li><i class=""fa fa-caret-right"" aria-hidden=""true""></i></li>" & vbCr
//END FUNCTION
//
//FUNCTION CalcPercentage(valueOne, valueTwo)
//    ' ### NOTE: USE cToDbl FUNCTION FOR PASSED IN VALUES ###
//    IF IsNumeric(valueOne) AND IsNumeric(valueTwo) THEN
//        IF (cToStr(valueTwo) <> "" AND cToDbl(valueTwo) <> cToDbl("0")) THEN
//            CalcPercentage = cToDbl(valueOne) / cToDbl(valueTwo)
//        ELSE
//            CalcPercentage = cToDbl(0)
//        END IF
//    ELSE
//        CalcPercentage = cToDbl(0)
//    END IF
//END FUNCTION
//
//FUNCTION RemoveCommas(strIn)
//    IF cToStr(strIn) = "" THEN EXIT FUNCTION
//    RemoveCommas = Replace(strIn, ",", "")
//END FUNCTION
//
//FUNCTION TierBadge(tierCode)
//    Dim tierName : tierName = ""
//    IF cToStr(tierCode) = "PER" THEN
//        tierName = "Personal"
//    ELSEIF cToStr(tierCode) = "PRO" THEN
//        tierName = "Professional"
//    END IF
//    IF cToStr(tierName) <> "" THEN
//        TierBadge = "<span class=""tier-badge"" title=""" & tierName & " membership required"">" & tierCode & "</span>"
//    ELSE
//        EXIT FUNCTION
//    END IF
//END FUNCTION
//
//SUB AdvanceWizardCounter(moduleName)
//    IF cToStr(moduleName) = "" THEN EXIT SUB
//    Dim currentAmount : currentAmount = cToInt($_SESSION["wizard." & moduleName & ".Count"))
//    Dim newAmount : newAmount = currentAmount + 1
//    $_SESSION["wizard." & moduleName & ".Count") = newAmount
//END SUB
//
//FUNCTION ShowPaginationExtend(pageNumber, maxPage, pageUri, filter, filterValue, filter2, filter2Value, filter3, filter3Value)
//    Dim i, pstrOut
//    Dim pageUrl : pageUrl = ""
//    Dim filterOut : filterOut = ""
//    Dim filter2Out : filter2Out = ""
//    Dim filter3Out : filter3Out = ""
//    Dim page : page = cInt(pageNumber)
//
//    maxPage = cInt(maxPage)
//    IF maxPage <= 1 THEN EXIT FUNCTION
//
//    ' ### PROCESS FILTERS AND ADD TO URL ###
//    IF cToStr(filterValue) <> "" THEN
//        filterOut = filter & "=" & filterValue & "&"
//    END IF
//
//    IF cToStr(filter2Value) <> "" THEN
//        filter2Out = filter2 & "=" & filter2Value & "&"
//    END IF
//
//    IF cToStr(filter3Value) <> "" THEN
//        filter3Out = filter3 & "=" & filter3Value & "&"
//    END IF
//
//    ' ### CONSTRUCT THE URL ###
//    pageUrl = pageUri & "?" & filterOut & filter2Out & filter3Out
//
//    pstrOut = pstrOut & "" _
//          & "<ul class=""pagination"">" & vbCr
//
//    IF page >= 2 THEN
//        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=1""><i class=""fa fa-caret-left"" aria-hidden=""true""></i>&nbsp;&nbsp;First</a></li>"
//    END IF
//    IF page >= 2 THEN
//        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & page - 1 & """><i class=""fa fa-caret-left"" aria-hidden=""true""></i>&nbsp;&nbsp;Previous</a></li>"
//    END IF
//    IF maxPage > 5 THEN
//        nPageT = page + 4
//        nPageCountDiff = maxPage - page
//        IF nPageCountDiff = 0 THEN
//            nPageCountDiff2 = 4
//        ELSEIF nPageCountDiff = 1 THEN
//            nPageCountDiff2 = 3
//        ELSEIF nPageCountDiff = 2 THEN
//            nPageCountDiff2 = 2
//        ELSE
//            nPageCountDiff2 = 1
//        END IF
//        IF nPageT > maxPage THEN
//            IF nPageCountDiff <= 3 THEN
//                FOR iPages = page - nPageCountDiff2 to maxPage
//                    IF ipages = page THEN
//                        pstrOut = pstrOut & "<li><b>" & iPages & "</b></li>"
//                    ELSE
//                        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & ipages & """>" & ipages & "</a></li>"
//                    END IF
//                NEXT
//            ELSE
//                FOR iPages = page to maxPage
//                    IF ipages = page THEN
//                        pstrOut = pstrOut & "<li><b>" & iPages & "</b></li>"
//                    ELSE
//                        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & ipages & """>" & ipages & "</a></li>"
//                    END IF
//                NEXT
//            END IF
//        ELSE
//            IF page = 1 THEN
//                FOR iPages = page to page + 4
//                    IF ipages = page THEN
//                        pstrOut = pstrOut & "<li><b>" & iPages & "</b></li>"
//                    ELSE
//                        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & ipages & """>" & ipages & "</a></li>"
//                    END IF
//                NEXT
//            ELSEIF page = 2 THEN
//                FOR iPages = page - 1 to page + 3
//                    IF ipages = page THEN
//                        pstrOut = pstrOut & "<li><b>" & iPages & "</b></li>"
//                    ELSE
//                        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & ipages & """>" & ipages & "</a></li>"
//                    END IF
//                NEXT
//            ELSE
//                FOR iPages = page - 2 to page + 2
//                    IF ipages = page THEN
//                        pstrOut = pstrOut & "<li><b>" & iPages & "</b></li>"
//                    ELSE
//                        pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & ipages & """>" & ipages & "</a></li>"
//                    END IF
//                NEXT
//            END IF
//        END IF
//    ELSE
//        FOR iPages = 1 to maxPage
//            IF ipages = page THEN
//                pstrOut = pstrOut & "<li><b>" & ipages & "</b></li>"
//            ELSE
//                pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & ipages & """>" & ipages & "</a></li>"
//            END IF
//        NEXT
//    END IF
//    IF maxPage > 1 THEN
//        IF page <> maxPage THEN
//            pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & page+1 & """>Next&nbsp;&nbsp;<i class=""fa fa-caret-right"" aria-hidden=""true""></i></a></li>"
//        END IF
//    END IF
//    IF maxPage > 1 THEN
//        IF page <> maxPage THEN
//            pstrOut = pstrOut & "<li><a href=""" & pageUrl & "page=" & maxPage & """>Last&nbsp;&nbsp;<i class=""fa fa-caret-right"" aria-hidden=""true""></i></a></li>"
//        END IF
//    END IF
//
//    pstrOut = pstrOut & "</ul>" & vbCr
//    ShowPaginationExtend = pstrOut
//END FUNCTION
//
//' ### RETRIEVES THE FASTLINK BEARER TOKEN (JWT) NEEDED TO LAUNCH THE FASTLINK ACCOUNT MANAGEMENT APPLICATION.
//' NOTE: THE ENDPOINT WILL REGISTER THE USER WITH YODLEE IF THEY HAVE NEVER REGISTERED. 
//' THUS, IF THE ENDPOINT IS ACTIVE AND THE APPLICANT PASSED IN IS VALID, THEN THIS ENDPOINT WILL 
//' ALWAYS RETURN A BEARER TOKEN. ###
//FUNCTION GetFastLinkToken()
//    GetFastLinkToken = "error"
//
//    Response.LCID = 1033
//    Dim URI : URI = INTEGRATION_ROOT_URL & "fastLinkToken/" & $_SESSION["activeApplicantId")
//
//    Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
//    srvXmlHttp.Open "GET", URI, False
//    srvXmlHttp.Send
//
//    Dim jsonObj : Set jsonObj = New JSONobject
//
//    Dim jsonOutput : Set jsonOutput = jsonObj.Parse(srvXmlHttp.responseText)
//
//    IF jsonObj.value("success") THEN isRecaptchaSuccess = true
//
//    IF jsonObj.Value("token") <> "[]" THEN
//        GetFastLinkToken = jsonObj.Value("token")
//	END IF
//
//    Set srvXmlHttp = Nothing
//END FUNCTION
//
//' ### UPDATES ALL EXTERNAL ACCOUNTS PERTAINING TO THE ACTIVE APPLICANT TO BE CALLED WHEN FASTLINK IS CLOSED ###
//SUB UpdateExternalAccounts
//    Response.LCID = 1033
//    Dim URI : URI = INTEGRATION_ROOT_URL & "refreshExternalAccountsForApplicant/" & $_SESSION["activeApplicantId")
//
//    Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
//    srvXmlHttp.Open "PATCH", URI, False
//	srvXmlHttp.Send
//	
//	If srvXmlHttp.waitForResponse(8) Then
//        Call LogReport(1, "Refreshed external accounts for applicant", $_SESSION["userId"))
//	ELSE
//		Call LogReport(1, "Request timeout - Unable to refresh external accounts for applicant", $_SESSION["userId"))
//    End If
//END SUB
//
//' ### UPDATES ALL APPLICANT'S EXTERNAL ACCOUNTS PERTAINING TO THE USER TO BE CALLED WHEN THE USER LOGS IN ###
//SUB UpdateExternalAccountsAtLogin
//    Response.LCID = 1033
//    Dim URI : URI = INTEGRATION_ROOT_URL & "refreshExternalAccountsForUser/" & $_SESSION["userId") & "?async=true"
//
//    Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
//    srvXmlHttp.Open "PATCH", URI, True
//    srvXmlHttp.Send
//	
//	If srvXmlHttp.waitForResponse(8) Then
//        Call LogReport(1, "Refreshed external accounts for user", $_SESSION["userId"))
//	ELSE
//		Call LogReport(1, "Request timeout - Unable to refresh external accounts for user", $_SESSION["userId"))
//    End If
//END SUB
//
//' ### UNREGISTERS AN ACCOUNT FROM THE YODLEE DATABASE TO BE CALLED WHEN THE USER DELETES AN ACCOUNT ###
//SUB UnlinkIndividualAccount(accountType, accountId)
//    Response.LCID = 1033
//    Dim URI : URI = INTEGRATION_ROOT_URL & "unlinkExternalAccount/" & $_SESSION["activeApplicantId") & "?accountType=" & accountType & "&accountId=" & accountId
//
//    Response.AppendToLog URI
//    Dim srvXmlHttp : Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
//    srvXmlHttp.Open "PATCH", URI, True
//	srvXmlHttp.Send
//	
//	If srvXmlHttp.waitForResponse(8) Then
//        Call LogReport(1, "Unlinked " & accountType & " account with ID " & accountId, $_SESSION["userId"))
//	ELSE
//    Response.Write("Accounts were not unlinked")
//		Call LogReport(1, "Request timeout - Unable to unlink " & accountType & " account with ID " & accountId, $_SESSION["userId"))
//    End If
//END SUB
//
//' ### RECONCILES ACCOUNT PROVIDERS BETWEEN CALCUTRACK AND YODLEE ###
//SUB ReconcileAccountProviders
//    Response.LCID = 1033
//    Dim URI : URI = INTEGRATION_ROOT_URL & "reconcileAccountProviders/" & $_SESSION["activeApplicantId")
//
//    Response.AppendToLog URI
//    Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
//    srvXmlHttp.Open "PATCH", URI, True
//	srvXmlHttp.Send
//	
//	If srvXmlHttp.waitForResponse(8) Then
//        Call LogReport(1, "Reconciled providers for applicant", $_SESSION["userId"))
//	ELSE
//		Call LogReport(1, "Request timeout - Unable to reconcile providers for applicant", $_SESSION["userId"))
//    End If
//END SUB
//
//' Deletes a provider associated to an applicant and reverts all underlying accounts to internal
//' The YodleeProviderId is left to help with deleting accounts but the YodleeAccountId is removed
//' This function DOES NOT delete underlying accounts, it just unlinks them from Yodlee
//FUNCTION DeleteProviderAccount(providerId)
//	Response.LCID = 1033
//    Dim URI : URI = INTEGRATION_ROOT_URL & "applicant/" & $_SESSION["activeApplicantId") & "/provider/" & providerId
//
//    Response.AppendToLog URI
//    Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
//    srvXmlHttp.Open "DELETE", URI, True
//	srvXmlHttp.Send
//	
//	If srvXmlHttp.waitForResponse(8) Then
//        Call LogReport(1, "Deleted provider for applicant", $_SESSION["userId"))
//	ELSE
//		Call LogReport(1, "Request timeout - Unable to get providers for applicant", $_SESSION["userId"))
//    End If
//END FUNCTION
//
//FUNCTION GetUnsupportedAccountsForApplicant()
//    Response.LCID = 1033
//    Dim URI : URI = INTEGRATION_ROOT_URL & "applicant/" & $_SESSION["activeApplicantId") & "/externalAccounts?supported=NOT_SUPPORTED"
//
//    Response.AppendToLog URI
//    Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
//    srvXmlHttp.Open "GET", URI, False
//	srvXmlHttp.Send
//
//    Set jsonObj = New JSONobject
//	IF srvXmlHttp.status = 200 THEN
//		Dim jsonOutput : Set jsonOutput = jsonObj.Parse(srvXmlHttp.responseText)
//	END IF
//	
//	Set srvXmlHttp = Nothing
//	Set GetUnsupportedAccountsForApplicant = jsonObj
//END FUNCTION
//
//FUNCTION ReportFastlinkError(code, title, action, message, fnToCall)
//    Response.LCID = 1033
//    Dim URI : URI = INTEGRATION_ROOT_URL & "statistics/applicant/" & $_SESSION["activeApplicantId") & "/fastLinkErrors"
//
//    Dim body : Set body = New JSONobject
//    body.Add "code", code
//    body.Add "title", title
//    body.Add "action", action
//    body.Add "message", message
//    body.Add "fnToCall", fnToCall
//
//    Response.AppendToLog URI
//    Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
//    srvXmlHttp.Open "POST", URI, False
//    srvXmlHttp.setRequestHeader "Content-Type", "application/json"
//
//	srvXmlHttp.Send body.Serialize()
//	
//	Set srvXmlHttp = Nothing
//END FUNCTION
//
//FUNCTION AddDividers()
//    AddDividers = _
//        "<div class=""divider"">" & vbCrLf & _
//        "    <div></div>" & vbCrLf & _
//        "    <div></div>" & vbCrLf & _
//        "</div>" & vbCrLf
//END FUNCTION
//
//FUNCTION CountUserIntegrations(userId)
//    Dim intOut : intOut = 0
//    Dim CountRecordsRS : Set CountRecordsRS = Server.CreateObject("ADODB.Recordset")
//    Dim CountRecordsSQL : CountRecordsSQL = _
//        "SELECT " & _
//        "   COUNT(*) AS numIntegrations " & _
//        "FROM " & _
//        "   Integrations AS inte " & _
//        "   INNER JOIN Applicant AS app ON inte.ApplicantId = app.ApplicantId " & _
//        "WHERE " & _
//        "   app.UserId = " & formatDbField(userId, "int", false)
//    CountRecordsRS.open CountRecordsSQL, db
//    intOut = CountRecordsRS("numIntegrations")
//    CountRecordsRS.Close
//    CountUserIntegrations = intOut
//END FUNCTION
//
//FUNCTION GetApplicantProviders()
//    Response.LCID = 1033
//    Dim URI : URI = INTEGRATION_ROOT_URL & "applicant/" & $_SESSION["activeApplicantId") & "/providers/"
//
//    Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
//    srvXmlHttp.Open "GET", URI, True
//	srvXmlHttp.Send
//	
//    Set jsonObj = New JSONobject
//	If srvXmlHttp.waitForResponse(8) Then
//        Call LogReport(1, "Got providers for applicant", $_SESSION["userId"))
//        IF srvXmlHttp.status = 200 THEN
//            Dim jsonOutput : Set jsonOutput = jsonObj.Parse(srvXmlHttp.responseText)
//        END IF
//	ELSE
//		Call LogReport(1, "Request timeout - Unable to get providers for applicant", $_SESSION["userId"))
//    End If
//
//    Set srvXmlHttp = Nothing
//    Set GetApplicantProviders = jsonObj
//END FUNCTION
//
//FUNCTION TrackFastLinkError(code, title, message, action, fnToCall)
//    Response.LCID = 1033
//    Dim URI : URI = INTEGRATION_ROOT_URL & "statistics/applicant/" & $_SESSION["activeApplicantId") & "/fastLinkErrors/"
//
//    ' instantiate the class
//    Dim JSON : Set JSON = New JSONobject
//
//    ' add properties
//    JSON.Add "code", code
//    JSON.Add "title", title
//    JSON.Add "message", message
//    JSON.Add "action", action
//    JSON.Add "fnToCall", fnToCall
//
//    Response.AppendToLog URI
//    Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
//    srvXmlHttp.Open "POST", URI, True
//	srvXmlHttp.Send JSON.Serialize()
//
//    If srvXmlHttp.waitForResponse(8) Then
//        Call LogReport(1, "Got providers for applicant", $_SESSION["userId"))
//	ELSE
//		Call LogReport(1, "Request timeout - Unable to get track fastlink error for applicant", $_SESSION["userId"))
//    End If
//END FUNCTION
//
//FUNCTION UnregisterYodleeApplicant(applicantId)
//    Response.LCID = 1033
//    Dim URI : URI = INTEGRATION_ROOT_URL & "applicant/" & applicantId
//
//    Set srvXmlHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
//    srvXmlHttp.Open "DELETE", URI, True
//	srvXmlHttp.Send
//	
//    Set jsonObj = New JSONobject
//	If srvXmlHttp.waitForResponse(8) Then
//        IF srvXmlHttp.status = 204 THEN
//            Call LogReport(1, "Unregistered Applicant from Yodlee", $_SESSION["userId"))
//        END IF
//	ELSE
//		Call LogReport(1, "Request timeout - Unable to get providers for applicant", $_SESSION["userId"))
//    End If
//
//    Set srvXmlHttp = Nothing
//END FUNCTION
//
//FUNCTION GetArrayValues(inArray)
//    Dim fieldsDict : Set fieldsDict = CreateObject("Scripting.Dictionary")
//    Dim breakdownAry : breakdownAry = Split(inArray, ",")
//
//    FOR i = 0 TO uBound(breakdownAry)
//        Dim fieldAry : fieldAry = Split(breakdownAry(i), "|")
//        Dim position : position = cToInt(fieldAry(0))
//        fieldsDict(position) = fieldAry(1)
//    NEXT
//
//    Dim strOut : strOut = fieldsDict(1) & "|" & fieldsDict(2) & "|" & fieldsDict(3) & "|" & fieldsDict(4)
//    Set fieldsDict = Nothing
//
//    GetArrayValues = Split(strOut, "|")
//END FUNCTION
//
//FUNCTION InsertRecord(key, tableName, columns, values)
//    ' ### INSERT NEW RECORD ###
//    db.Execute("INSERT INTO " & tableName & " (" & columns & ") VALUES (" & values & ")")
//
//    ' ### GET LAST INSERTED ID ###
//    Dim getPrimaryKey : getPrimaryKey = db.Execute("SELECT SCOPE_IDENTITY();")
//    primaryId = Trim(getPrimaryKey(0))
//    Set getPrimaryKey = Nothing
//
//    ' ### RETURN PRIMARY KEY ID ###
//    InsertRecord = primaryId
//END FUNCTION
//

//

//
//FUNCTION ConvertToMySqlDate(thisDate)
//    ' ### "11/22/2020 00:00:00" to "2020-11-22 00:00:00"
//    IF cToStr(thisDate) = "" THEN EXIT FUNCTION
//    Dim dateTimeAry : dateTimeAry = Split(thisDate, " ")
//    Dim dateAry : dateAry = Split(dateTimeAry(0), "/")
//    Dim dateDay : dateDay = dateAry(1)
//    Dim dateMonth : dateMonth = dateAry(0)
//
//    IF Len(dateDay) = 1 THEN dateDay = "0" & dateDay
//    IF Len(dateMonth) = 1 THEN dateMonth = "0" & dateMonth
//    ConvertToMySqlDate = dateAry(2) & "-" & dateMonth & "-" & dateDay & " " & dateTimeAry(1)
//END FUNCTION
//
//FUNCTION ShowDateTime(thisDate, dateFormat)
//    IF cToStr(thisDate) = "" THEN EXIT FUNCTION
//    Dim offSet : offSet = $_SESSION["timeZoneOffset") * -1
//    Dim displayDate : displayDate = DateAdd("h", offSet, thisDate)
//    IF cToStr(dateFormat) <> "" THEN displayDate = FormatDateTime(displayDate, dateFormat)
//    ShowDateTime = displayDate
//END FUNCTION
//
//FUNCTION ShowNowDate()
//    ShowNowDate = FormatDateTime(Now(), 2)
//END FUNCTION
//
//FUNCTION GetEntitiesDataset()
//    Dim paryEntities : Const ENTITY_ID = 0 : Const ENTITY_NAME = 1
//    Dim entityListRS : Set entityListRS = Server.CreateObject("ADODB.Recordset")
//    Dim entityListSQL : entityListSQL = ""
//    IF $_SESSION["userPaymentTierType") = "PRO" THEN
//        ' ### IF PROFESSIONAL TIER ###
//        entityListSQL = _
//            "SELECT " & _
//            "   EntityId, " & _
//            "   EntityName " & _
//            " FROM ( " & _
//            "       SELECT " & _
//            "           '' AS EntityId, " & _
//            "           '" & $_SESSION["CurrentApplicantName") & "' AS EntityName " & _
//            "       FROM " & _
//            "          Entity " & _
//            "       UNION  " & _
//            "       SELECT " & _
//            "          EntityId, " & _
//            "          EntityName " & _
//            "       FROM " & _
//            "          Entity " & _
//            "       WHERE " & _
//            "          ApplicantId = " & formatDbField($_SESSION["activeApplicantId"), "int", false) & _
//            " ) AS entities " & _
//            " ORDER BY " & _
//            "    EntityId ASC"
//    ELSE
//        ' ### IF FREE OR PERSONAL TIER ###
//        entityListSQL = _
//            "SELECT " & _
//            "   '' AS EntityId, " & _
//            "   '" & $_SESSION["CurrentApplicantName") & "' AS EntityName "
//    END IF
//    entityListRS.open entityListSQL, db
//    IF NOT (entityListRS.BOF AND entityListRS.EOF) THEN
//        paryEntities = entityListRS.Getrows()
//    END IF
//    entityListRS.Close
//    Set entityListRS = Nothing
//    GetEntitiesDataset = paryEntities
//END FUNCTION
//
//FUNCTION getNumberClass(value, posClass, negClass)
//    ' ### DETERMINE IF A NUMBER IS NEGATIVE AND ADD CSS CLASS TO CHANGE COLOR TO RED ###
//    Dim outClass : outClass = posClass
//    IF cToDbl(value) < cToDbl(secondNum) THEN outClass = negClass
//    getNumberClass = outClass
//END FUNCTION
//
//FUNCTION addZeroPadding(inStr, totalLength)
//    IF totalLength > LEN(inStr) THEN
//        Dim zerosReq : zerosReq = totalLength - LEN(inStr)
//        IF zerosReq = 1 THEN
//            addZeroPadding = "0" & inStr
//        ELSEIF zerosReq = 2 THEN
//            addZeroPadding = "00" & inStr
//        ELSEIF zerosReq = 3 THEN
//            addZeroPadding = "000" & inStr
//        END IF
//    ELSE
//        addZeroPadding = inStr
//    END IF
//END FUNCTION

?>