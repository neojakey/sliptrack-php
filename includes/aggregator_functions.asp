<%
FUNCTION makeAggregatorCalculation(cashFlowType, nMonth)
    IF Trim(cashFlowType & "") = "" THEN EXIT FUNCTION
    IF Trim(nMonth & "") = "" THEN EXIT FUNCTION
    Dim nMonthName : nMonthName = MonthName(nMonth, true)
    Dim monthlyActualsRS, monthlyActualsSQL

    IF Trim(cashFlowType & "") = "expense" THEN
        Set monthlyActualsRS = Server.CreateObject("ADODB.Recordset")
        monthlyActualsSQL = _
            "SELECT " & _
            "   SUM(IFNULL(ExpenseActual" & nMonthName & ",0)) AS expenseTotal " & _
            "FROM " & _
            "   Expenses " & _
            "WHERE " & _
            "   ApplicantId = " & formatDbField(Session("activeApplicantId"), "int", false) & ";"
        monthlyActualsRS.open monthlyActualsSQL, db
        IF NOT monthlyActualsRS.EOF THEN
            Dim expenseTotal : expenseTotal = monthlyActualsRS("expenseTotal")
        END IF
        monthlyActualsRS.Close
        makeAggregatorCalculation = expenseTotal
    ELSEIF Trim(cashFlowType & "") = "income" THEN
        Set monthlyActualsRS = Server.CreateObject("ADODB.Recordset")
        monthlyActualsSQL = _
            "SELECT " & _
            "   SUM(IFNULL(IncomeActual" & nMonthName & ",0)) AS incomeTotal " & _
            "FROM " & _
            "   Incomes " & _
            "WHERE " & _
            "   ApplicantId = " & formatDbField(Session("activeApplicantId"), "int", false) & ";"
        monthlyActualsRS.open monthlyActualsSQL, db
        IF NOT monthlyActualsRS.EOF THEN
            Dim incomeTotal : incomeTotal = monthlyActualsRS("incomeTotal")
        END IF
        monthlyActualsRS.Close
        makeAggregatorCalculation = incomeTotal
    END IF
END FUNCTION
%>