$(document).ready(function () {
    doMath();

    $('#salary').on('change keyup paste', function () {
        doMath();
    });
    $('#bonus').on('change keyup paste', function () {
        doMath();
    });
    $('#dividends').on('change keyup paste', function () {
        doMath();
    });
    $('#capital-gains').on('change keyup paste', function () {
        doMath();
    });
    $('#other-income').on('change keyup paste', function () {
        doMath();
    });
});

function doMath() {
    var total = 0;
    var salary = $('#salary').val();
    var bonus = $('#bonus').val();
    var dividends = $('#dividends').val();
    var realEstate = $('#real-estate').val();
    var capitalGains = $('#capital-gains').val();
    var otherIncome = $('#other-income').val();

    if (salary !== '')
        total += parseFloat(salary);
    if (bonus !== '')
        total += parseFloat(bonus);
    if (dividends !== '')
        total += parseFloat(dividends);
    if (capitalGains !== '')
        total += parseFloat(capitalGains);
    if (otherIncome !== '')
        total += parseFloat(otherIncome);
    total += parseFloat(realEstate);
    $('#total-wrapper').text('$' + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
    $('#total-income').val(parseFloat(total, 10).toFixed(2).toString());
}