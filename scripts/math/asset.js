$(document).ready(function () {
    doMath();

    $('#other-assets').on('change keyup paste', function () {
        doMath();
    });
    $('#life-insurance-csv').on('change keyup paste', function () {
        doMath();
    });
});

function doMath() {
    var total = 0;

    var otherAssets = $('#other-assets').val();
    var lifeInsuranceCsv = $('#life-insurance-csv').val();

    var checkingSavings = $('#checking-savings').val();
    var cashProperty = $('#cash-property').val();
    var cashInIras = $('#cash-in-iras').val();
    var marketableSecurities = $('#marketable-securities').val();
    var marketableSecuritiesIra = $('#marketable-securities-ira').val();
    var realEstateOwned = $('#real-estate-owned').val();
    var realEstatePartOwned = $('#real-estate-part-owned').val();
    var partnerships = $('#partnerships').val();
    var personalProperty = $('#personal-property').val();
    var vehicles = $('#vehicles').val();
    var notesReceivable = $('#notes-receivable').val();
    var nonReadilyMarketableSec = $('#non-readily-marketable-sec').val();
    var nonReadilyMarketableSecIra = $('#non-readily-marketable-sec-ira').val();

    if (otherAssets !== '')
        total += parseFloat(otherAssets);
    if (lifeInsuranceCsv !== '')
        total += parseFloat(lifeInsuranceCsv);

    total += parseFloat(checkingSavings);
    total += parseFloat(cashProperty);
    total += parseFloat(cashInIras);
    total += parseFloat(marketableSecurities);
    total += parseFloat(marketableSecuritiesIra);
    total += parseFloat(realEstateOwned);
    total += parseFloat(realEstatePartOwned);
    total += parseFloat(partnerships);
    total += parseFloat(personalProperty);
    total += parseFloat(vehicles);
    total += parseFloat(notesReceivable);
    total += parseFloat(nonReadilyMarketableSec);
    total += parseFloat(nonReadilyMarketableSecIra);

    $('#total-wrapper').text('$' + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
}