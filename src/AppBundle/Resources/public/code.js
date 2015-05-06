$(document).ready(function() {
    var randomColorFactor = function() {
        return Math.round(Math.random() * 255);
    };

    var doughnutData = [];

    $('.records_list tbody tr').each(function(idx, elem) {
        doughnutData.push({
            value: parseInt($(elem).find('.count').text()),
            color: 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',.7)',
            label: $(elem).find('.label').text()
        });
    });

    var ctx = document.getElementById("chart").getContext("2d");
    window.myDoughnut = new Chart(ctx).Doughnut(doughnutData, {
    });

});
