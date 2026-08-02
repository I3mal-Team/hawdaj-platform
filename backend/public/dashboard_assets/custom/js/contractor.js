"use strict"

$(document).on('change', '#companyId', function (e) {
    e.preventDefault();
    var companyId = $(this).val();
    getContractors(companyId)
});

function getContractors(companyId) {
    $.ajax({
        url: `${HOST_URL}/${LANG}/dashboard/get-contracts/` + companyId,
        type: "GET",
        enctype: 'multipart/form-data',
        success: function (result) {
            var data = result.data;
            $("#selectContracts").html(`<option value="">${langs[LANG].select_contacts}</option>`);
            data.forEach(el => {
                $("#selectContracts").append(
                    "<option value='" + el.id + "'>" + el.name + "</option>"
                );
            });

        },
        error: function () {
            $("#selectContracts").html(`<option value="">${langs[LANG].select_contacts}</option>`);
            // Swal.fire({
            //     text: langs[LANG].sorry_looks_like_some_errors_detected_try_again,
            //     icon: "error",
            //     buttonsStyling: false,
            //     confirmButtonText: langs[LANG].ok_got_it,
            //     customClass: {
            //         confirmButton: "btn font-weight-bold btn-light"
            //     }
            // })
        }
    })
}


$(document).on('change', '#selectContracts', function (e) {
    e.preventDefault();
    var contractId = $(this).val();
    getContractDates(contractId)
});

function getContractDates(contractId) {
    $.ajax({
        url: `${HOST_URL}/${LANG}/dashboard/get-contract-dates/` + contractId,
        type: "GET",
        enctype: 'multipart/form-data',
        success: function (result) {
            var data = result.data;
            $("#fromdate").val(data.from_date)
            $("#todate").val(data.to_date)
        },
        error: function () {
            // Swal.fire({
            //     text: langs[LANG].sorry_looks_like_some_errors_detected_try_again,
            //     icon: "error",
            //     buttonsStyling: false,
            //     confirmButtonText: langs[LANG].ok_got_it,
            //     customClass: {
            //         confirmButton: "btn font-weight-bold btn-light"
            //     }
            // })
        }
    })
}
