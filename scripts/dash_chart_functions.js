function dashPanelLoadChart(element, chartType) {
    /* GET CHART NAMES AND CREATE ARRAY */
    var chartNames = $('#' + element).data('names');
    var chartNameArray = createDataArray(chartNames);

    /* GET CHART VALUES AND CREATE ARRAY */
    var chartValues = $('#' + element).data('values');
    var chartValuesArray = createDataArray(chartValues);

    /* GET CHART COLORS AND CREATE ARRAY */
    var chartColors = $('#' + element).data('colors');
    var chartColorsArray = createDataArray(chartColors);

    /* CREATE DATASET AND DISPLAY CHART */
    var data = dashPanelCreateArray(chartNameArray, chartValuesArray, chartColorsArray);

    /* DISPLAY CHART */
    if (chartType === 'bar') {
        dashPanelBarChart(data, element);
    }
}

function dashPanelBarChart(chartData, element) {
    $('#' + element).kendoChart({
        dataSource: {
            data: chartData
        },
        series: [{
            field: 'value',
            categoryField: 'name',
            colorField: 'color'
        }],
        tooltip: {
            visible: true,
            template: '#=category#<br />Value: #= formatCurrency(value) #'
        },
        categoryAxis: {
            labels: {
                rotation: 45,
                font: '8px sans-serif'
            }
        },
        valueAxis: {
            name: currency.name,
            title: {
                text: currency.name
            },
            labels: {
                format: currency.symbol + '{0}'
            },
            line: {
                visible: false
            },
            axisCrossingValue: 0
        }
    });
}

function dashPanelCreateArray(names, values, colors) {
    var data = [], i;

    if (colors[0] === undefined) {
        colors = ['#1F77B4', '#AEC7E8', '#FF7F0E', '#FFBB78', '#2CA02C', '#D62728', '#FF9896', '#9467BD', '#C5B0D5', '#8C564B', '#C49C94', '#E377C2', '#F7B6D2', '#7F7F7F', '#C7C7C7', '#BCBD22', '#DBDB8D', '#17BECF', '#9EDAE5'];
    }

    if (colors.length === 1) {
        for (i = 0; i < names.length; i++) {
            data.push({
                name: names[i],
                value: values[i],
                color: colors[0]
            });
        }
    } else {
        for (i = 0; i < names.length; i++) {
            data.push({
                name: names[i],
                value: values[i],
                color: colors[i]
            });
        }
    }
    return data;
}
