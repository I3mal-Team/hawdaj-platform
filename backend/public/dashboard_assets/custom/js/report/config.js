$(function () {
    $(".chart-option").on("click", function (e) {
        e.stopPropagation()
    });

    $(".chart-option .checkbox input").on("change", function () {
        let key = $(this).closest(".chart-option").data("key");
        let value = $(this).closest(".chart-option").data("value") || null;
        let active = this.checked ? 1 : 0;
        let view = $(this).attr("name");


        $.ajax({
            url: HOST_URL + '/en/dashboard/config/update',
            type: 'POST',
            data: {
                key: key ?? 'chart',
                value: value ?? key,
                view: view ?? 0,
                active: active,
                _token: $('meta[name="csrf-token"]').attr("content")
            },
            dataType: "JSON",
            success: function (result) {
                toastr.success('Setting has been changed successfully');
            }
        });
    });
});

function renderColumnChart() {

    var options = {
        series: [{
            data: ['2003', '2043', '1398', '1655'],
            name: langs[LANG].risk_duaration || 'Risk Duaration'
        }, {data: ['9498', '9199', '3871', '4007'],
            name: langs[LANG].no_risk_duration || "No Risk Duration"

        }],
        chart: {
            type: 'bar',
        },

        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: ["Factory1", "Factory2", "Factory3", "Factory4"],
        },
        yaxis: {
            title: {
                text:  langs[LANG].hours || 'Hours'
            }
        },
        fill: {
            opacity: 1
        },
        colors: [SettingColor.primary_color, SettingColor.secondary_color, SettingColor.tertiary_color]
    };
    var chart1 = new ApexCharts(document.querySelector('#col-chart-cont'), options);
    chart1.render();
}

function renderAreaChart() {
    var options2 = {
        chart: {
            type: 'area'
        },
        series: [
            {
                data: [
                    {x: 'Factory1', y: '2003'}, {x: 'Factory2', y: '2043'}, {x: 'Factory3', y: '1398'},
                    {x: 'Factory4', y: '1655'}
                ],
                // name:  "Risk Duration"
                name: langs[LANG].risk_duaration || 'Risk Duaration'
            },
            {
                data: [{x: 'Factory1', y: '9498'}, {x: 'Factory2', y: '9199'}, {
                    x: 'Factory3',
                    y: '3871'
                }, {x: 'Factory4', y: '4007'}],
                name: langs[LANG].no_risk_duration || "No Risk Duration"
            }
        ],
        xaxis: {
            type: 'category'
        },
        colors: [SettingColor.primary_color, SettingColor.secondary_color, SettingColor.tertiary_color]

    }
    var chart2 = new ApexCharts(document.querySelector("#area-chart-cont"), options2);
    chart2.render();

}

function renderPieChart() {
    let options3 = {
        chart: {
            type: 'pie',
            width: "65%",

        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%'
                }
            }
        },
        series: [2003, 2043, 1398, 1655],
        labels: ['Factory1', 'Factory2', 'Factory3', 'Factory4'],
        colors: [SettingColor.secondary_color, SettingColor.primary_color, SettingColor.tertiary_color]

    }

    let chart3 = new ApexCharts(document.querySelector("#pie-chart-cont"), options3);
    chart3.render();
    setTimeout(() => {
        $(`#pie-chart-cont.apexcharts-canvas.apexcharts-theme-light`).css('margin', '0 auto');
    }, 10);
}
