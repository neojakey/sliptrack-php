<%
FUNCTION makeScorecardCalculation(inCalc, calcType, doCalc)
    IF Trim(inCalc & "") = "" THEN EXIT FUNCTION
    Dim parts : parts = Split(inCalc, " ")
    Dim finalEquation : finalEquation = ""

    FOR EACH part IN parts
        IF IsSessionVariable(part) THEN
            finalEquation = finalEquation & GetSessionValue(part, doCalc) & " "
        ELSE
            finalEquation = finalEquation & part & " "
        END IF
    NEXT

    finalEquation = Left(finalEquation, Len(finalEquation)-1)

    IF doCalc THEN
        On Error Resume Next
        finalResult = Eval(finalEquation)
        IF Err.Number = 0 THEN
            IF Trim(calcType & "") = "%" THEN
                makeScorecardCalculation = FormatNumber(finalResult, 2) & "%"
            ELSEIF Trim(calcType & "") = "$" THEN
                makeScorecardCalculation = cToDbl(finalResult)
            END IF
        ELSE
            makeScorecardCalculation = "Error"
        END IF
    ELSE
        makeScorecardCalculation = finalEquation
    END IF
END FUNCTION

FUNCTION GetSessionValue(calcStr, doCalc)
    IF Trim(calcStr & "") = "" THEN EXIT FUNCTION
    Dim outStr : outStr = Replace(calcStr, "[[", "")
    outStr = Replace(outStr, "]]", "")
    IF NOT doCalc THEN
        IF outStr = "TOTAL-ASSETS" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Assets"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "TOTAL-LIABILITIES" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Liabilities"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "TOTAL-NETWORTH" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Net Worth"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "TOTAL-AGE-NETWORTH" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Age Based Net Worth"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "TOTAL-LIABILITIES/ASSETS" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Liabilities divided by Total Assets"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "RESIDENCE-MORTGAGE-DEBT" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Residence Mortgage Debt"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "RESIDENCE-MORTGAGE-DEBT/ASSETS" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Residence Mortgage Debt divided by Total Assets"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "CONSUMER-DEBT" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Consumer Debt"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "CONSUMER-DEBT/ASSETS" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Consumer Debt divided by Total Assets"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "TOTAL-INCOME" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Income"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "TOTAL-EXPENSES" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Expenses"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "TOTAL-EXPENSES/TOTAL-INCOME" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Expenses divided by Total Income"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "TOTAL-LIABILITIES/TOTAL-INCOME" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Liabilities divided by Total Income"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "CONSUMER-DEBT/TOTAL-INCOME" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Consumer Debt divided by Total Income"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "RESIDENCE-MORTGAGE-DEBT/TOTAL-INCOME" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Residence Mortgage Debt divided by Total Income"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "CREDITCARD-DEBT" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Credit Card Debt"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "TOTAL-DEBT" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Total Debt"">" & ShowAsCurrency(Session(outStr)) & "</span>"
        ELSEIF outStr = "MEMBER-AGE" THEN
            GetSessionValue = "<span class=""total-badge"" title=""Member Age"">" & Session(outStr) & "</span>"
        END IF
    ELSE
        GetSessionValue = Session(outStr)
    END IF
END FUNCTION

FUNCTION IsSessionVariable(calcStr)
    IF Trim(calcStr & "") = "" THEN EXIT FUNCTION
    Dim outBool : outBool = false
    calcStr = Trim(calcStr & "")

    IF InStr(calcStr, "[[") > 0 THEN
        outBool = true
    END IF
    IsSessionVariable = outBool
END FUNCTION
%>