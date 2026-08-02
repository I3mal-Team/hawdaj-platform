var langs = {
    'en': {
        'record': 'Record'
    },
    'ar': {
        'record': 'عنصر'
    }
}

function barChart(series, locations, selector,unit,chart_details) {
    if (!series || !locations) {
        $(selector).closest(".chart-cont").hide();
        return;
    }

    $(selector).closest(".chart-cont").find(".chart-filter-cont .nav-link").on("click", function (e) {
        let selectedValue = $(this).data("value");
        $(selector).closest(".chart-cont").find(".chart-filter-cont .nav-link.active").removeClass("active");
        $(this).addClass("active")

        $(selector).html(`<div style='min-height: 450px; max-height: 550px'
              class=' w-100 d-flex justify-content-center align-items-center'>
            <div class='spinner spinner-primary spinner-lg'></div>
        </div>`);

        delete currentForm.time_range;

        $.ajax({
            url: `${HOST_URL}/en/dashboard/report/handle-report`,
            data: {
                chart_type: 'bar',
                time_type: 'dynamic',
                time_range: selectedValue,
                _token: $('meta[name="csrf-token"]').attr("content"),
                ...currentForm,
            },
            type: 'GET',
            dataType: 'json',
            timeout: 8000,
            success: function (response) {
                $(selector).empty();
                renderChart(response.data.bar.locations, response.data.bar.result,unit,chart_details);
            },
            error: function (jqXhr, textStatus, errorMessage) {
                toastr.error(errorMessage);
            }
        });
    })

    function renderChart(locations, series,unit,chart_details) {
        var options = {
            series: series,
            chart: {
                type: 'line',
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
                categories: locations,
                labels: {
                    rotate: LANG === "ar" ? 75 : -75
                }
            },
            yaxis: {
                title: {
                    text: ''
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val, elem) {
                        var full_unit = '';
                        if (unit == 'time') {
                            full_unit = chart_details['bar'].time_unit;
                        } else if (unit == 'mixed') {
                            full_unit ='';
                        } else {
                            full_unit = langs[LANG].record;
                        }
                        return val + " " + full_unit;
                    }
                }
            },
            colors: [SettingColor.primary_color, SettingColor.secondary_color, SettingColor.tertiary_color]
        };
        var chart1 = new ApexCharts(document.querySelector(selector), options);
        chart1.render();
    }

    renderChart(locations, series,unit,chart_details);
    // chartToggleHandler(selector)
}

function areaChart(data, selector,unit,chart_details) {
    $(selector).closest(".chart-cont").find(".chart-filter-cont .nav-link").on("click", function (e) {
        let selectedValue = $(this).data("value");
        $(selector).closest(".chart-cont").find(".chart-filter-cont .nav-link.active").removeClass("active")
        $(this).addClass("active")

        $(selector).html(`<div style='min-height: 450px; max-height: 550px'
              class=' w-100 d-flex justify-content-center align-items-center'>
            <div class='spinner spinner-primary spinner-lg'></div>
        </div>`);

        delete currentForm.time_range;

        $.ajax({
            url: `${HOST_URL}/en/dashboard/report/handle-report`,
            data: {
                chart_type: 'line',
                time_range: selectedValue,
                time_type: 'dynamic',
                _token: $('meta[name="csrf-token"]').attr("content"),
                ...currentForm,
            },
            type: 'GET',
            dataType: 'json',
            timeout: 8000,
            success: function (response) {
                $(selector).empty();
                renderChart(response.data.line,unit,chart_details);
            },
            error: function (jqXhr, textStatus, errorMessage) {
                toastr.error(errorMessage);
            }
        });
    })

    function renderChart(data,unit,chart_details) {
        var options2 = {
            chart: {
                type: 'area'
            },
            series: data,
            xaxis: {
                type: 'category',
                labels: {
                    rotate: LANG === "ar" ? 75 : -75
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        var full_unit = '';
                        if (unit == 'time') {
                            full_unit = chart_details['bar'].time_unit;
                        } else if (unit == 'mixed') {
                            full_unit = '';
                        } else {
                            full_unit = langs[LANG].record;
                        }
                        return val + " " + full_unit;
                    }
                }
            },
            colors: [SettingColor.primary_color, SettingColor.secondary_color, SettingColor.tertiary_color]

        }
        var chart2 = new ApexCharts(document.querySelector(selector), options2);
        chart2.render();
    }

    renderChart(data,unit,chart_details);
    // chartToggleHandler(selector)
}

function pieChart(data, selector,unit,chart_details) {
    $(selector).closest(".chart-cont").find(".chart-filter-cont .nav-link").on("click", function (e) {
        let selectedValue = $(this).data("value");
        $(selector).closest(".chart-cont").find(".chart-filter-cont .nav-link.active").removeClass("active")
        $(this).addClass("active");

        $(selector).html(`<div style='min-height: 450px; max-height: 550px'
              class=' w-100 d-flex justify-content-center align-items-center'>
            <div class='spinner spinner-primary spinner-lg'></div>
        </div>`);

        $(this).closest('div.chart-cont').find(selector).html(`<div style='min-height: 450px; max-height: 550px'
              class=' w-100 d-flex justify-content-center align-items-center'>
            <div class='spinner spinner-primary spinner-lg'></div>
        </div>`);

        delete currentForm.time_range;

        $.ajax({

            url: `${HOST_URL}/en/dashboard/report/handle-report`,
            data: {
                chart_type: 'pie',
                time_type: 'dynamic',
                time_range: selectedValue,
                _token: $('meta[name="csrf-token"]').attr("content"),
                ...currentForm,
            },
            type: 'GET',
            dataType: 'json',
            timeout: 8000,

            success: function (response, status) {
                console.log(status)
                $(selector).html('');
                var data = response.data.pie[selector.slice(1)];
                renderChart(selector, data,unit,chart_details);
            },
            error: function (jqXhr, textStatus, errorMessage) {
                toastr.error(errorMessage);
            }
        });
    })

    function renderChart(selector, result,unit,chart_details) {
        let options3 = {
            chart: {
                toolbar: {
                    show: true
                },
                type: 'pie',
                width: "65%"
            },
            series: result.value,
            labels: result.name,
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        var full_unit = '';
                        if (unit == 'time') {
                            full_unit = chart_details['bar'].time_unit;
                        } else if (unit == 'mixed') {
                            full_unit ='';
                        } else {
                            full_unit = langs[LANG].record;
                        }
                        return val + " " + full_unit;
                    }
                }
            },
            colors: [SettingColor.secondary_color, SettingColor.primary_color, SettingColor.tertiary_color]
        }

        if (result.value != 'undfined') {
            let chart3 = new ApexCharts(document.querySelector(selector), options3);
            chart3.render();
        }
    }

    renderChart(selector, data,unit,chart_details);

    // chartToggleHandler(selector, true)
}

function chartToggleHandler(selector, isPieChart) {
    let dropdown = $(`${selector}`).closest(".chart-cont").find(".chart-toggle");
    $(selector).find(".apexcharts-legend-series").each(function () {
        let seriesName = $(this).attr("seriesname").trim()
        let text = $(this).find('.apexcharts-legend-text').text()
        let newLi = document.createElement("li");
        newLi.setAttribute("class", "navi-item")

        $(newLi).html(`<label class="checkbox checkbox-success navi-link"><input type="checkbox" ${!isPieChart && 'checked'} name=${text}/><span class="mr-4"></span>${text}</label>`)
        dropdown.append(newLi)
        $(newLi).find(".navi-link").on("click", function (e) {
            e.preventDefault();
            $(this).find("input").toggle(function () {
                if (isPieChart) {
                    let that;
                    dropdown.find("input[type='checkbox']").each(function () {
                        if ($(this).prop("checked")) {
                            that = this
                        }
                        $(this).removeAttr('checked');
                    })
                    if (that === this) {
                        return;
                    }

                }
                if ($(this).attr('checked')) {

                    $(this).removeAttr('checked')
                    return
                }
                $(this).attr('checked', true)

            })

            $(selector + " " + ".apexcharts-legend-series[seriesname='" + seriesName.replace(' ', 'x') + "'] .apexcharts-legend-marker").trigger('click');
        })
    })
    dropdown.on("click", (e) => e.stopPropagation())

}

$(document).ready(function () {
    $(".reports-filter-cont .dropdown-menu").on("click", (e) => e.stopPropagation());

    $(".status").on('click', function () {
        let value = $(this).attr("data-serias");
        $(this).toggleClass("active");
        $(".apexcharts-legend-series[seriesname='" + value + "'] .apexcharts-legend-marker").trigger('click');
    });

    $(".date_filter").on('click', function (e) {
        e.preventDefault();

        let url = `${HOST_URL}/en/dashboard/report/handle-report`;
        let selectedValue = $(this).data("value");
        let token = $('meta[name="csrf-token"]').attr("content");
        let inputs = `<input name="_token" value="${token}">`;

        delete currentForm.chart_type;
        delete currentForm.time_range;
        delete currentForm.time_type;

        for (var key of Object.keys(currentForm)) {
            inputs += `<input name=${key} value=${currentForm[key] ?? ''} >`;
        }
        currentCharts.forEach((item) => {
            inputs += `<input name="chart_type[]" value=${item} >`;
        });

        inputs += `<input name="time_range" value=${selectedValue} >`;
        inputs += `<input name="time_type" value=dynamic >`;

        $(`<form action=${url}>${inputs}</form>`).appendTo('body').submit().remove();
    });

    $("#aside-toggle").on("click", function () {
        let aside = $(".filter-aside");
        let backdrop = $(".filter-aside-backdrop");
        $('body').addClass('overflow-hidden');
        backdrop.addClass("active")
        aside.addClass('active')
    })

    function closeAsideHandler() {
        $('body').removeClass('overflow-hidden');
        $('.filter-aside-backdrop').removeClass("active");
        $(".filter-aside").removeClass("active")
    }

    $(".filter-aside-backdrop").on("click", closeAsideHandler)
    $(".filter-aside .aside-header .close-btn").on("click", closeAsideHandler);

    $("#select_filter").on('change', function (e) {
        let type = $(this).val();
        if (type === 'specific' || type === 'comparison') {
            $.get(`${HOST_URL}/${LANG}/dashboard/report/get_sites`, {type: type}, function (data, textStatus, jqXHR) {
                $("#select_container").html('').append(data);
                $('.nice-select').select2({
                    minimumResultsForSearch: -1
                });
            });
            $("#select_container").slideDown();
        } else {
            $("#select_container").html('').slideUp();
        }
    });
});
