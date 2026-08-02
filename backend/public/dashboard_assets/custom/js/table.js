let file_excel = "";
let file_pdf = "";
let file_csv = "";
let custom_column = "";
let copy_table = "";
let locale = "";
let action = "";

var KTDatatablesBasicPaginations = function () {
    var initTable1 = function () {
        var table = $('#dataTable');

        table.DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            buttons: [
                {
                    extend: 'excel',
                    text: `<a href="" class="btn btn-brand btn-elevate btn-icon-sm">
                            <img src="${HOST_URL}/dashboard_assets/media/svg/files/xml.svg" alt="" style="width: 18px; height: 18px">
                       ${file_excel}
                    </a>`,
                },
                {
                    extend: 'pdf',
                    text: `<a href="" class="btn btn-brand btn-elevate btn-icon-sm">
                            <img src="${HOST_URL}/dashboard_assets/media/svg/files/pdf.svg" alt="" style="width: 18px; height: 18px">
                       ${file_pdf}
                    </a>`,
                },
                {
                    extend: 'csv',
                    text: `<a href="" class="btn btn-brand btn-elevate btn-icon-sm">
                            <img src="${HOST_URL}/dashboard_assets/media/svg/files/csv.svg" alt="" style="width: 18px; height: 18px">
                       ${file_csv}
                    </a>`,
                }, {
                    extend: 'copy',
                    text: `<a href="" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="flaticon-clipboard"></i>
                       ${copy_table}
                    </a>`,
                }, {
                    extend: 'colvis',
                    text: `<a href="" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="flaticon-list-2"></i>
                       ${custom_column}
                    </a>`,
                },
            ],
            responsive: true,
            columnDefs: [
                {
                    targets: -1,
                    orderable: false,
                },
            ],
            language: {
                url: locale
            },
        },
        );
    };
    var initTable2 = function () {
        var table2 = $('#dataTable2');

        table2.DataTable({
                dom: 'Bfrtip',
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                buttons: [
                    {
                        extend: 'excel',
                        text: `<a href="" class="btn btn-brand btn-elevate btn-icon-sm">
                            <img src="${HOST_URL}/dashboard_assets/media/svg/files/xml.svg" alt="" style="width: 18px; height: 18px">
                       ${file_excel}
                    </a>`,
                    },
                    {
                        extend: 'pdf',
                        text: `<a href="" class="btn btn-brand btn-elevate btn-icon-sm">
                            <img src="${HOST_URL}/dashboard_assets/media/svg/files/pdf.svg" alt="" style="width: 18px; height: 18px">
                       ${file_pdf}
                    </a>`,
                    },
                    {
                        extend: 'csv',
                        text: `<a href="" class="btn btn-brand btn-elevate btn-icon-sm">
                            <img src="${HOST_URL}/dashboard_assets/media/svg/files/csv.svg" alt="" style="width: 18px; height: 18px">
                       ${file_csv}
                    </a>`,
                    }, {
                        extend: 'copy',
                        text: `<a href="" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="flaticon-clipboard"></i>
                       ${copy_table}
                    </a>`,
                    }, {
                        extend: 'colvis',
                        text: `<a href="" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="flaticon-list-2"></i>
                       ${custom_column}
                    </a>`,
                    },
                ],
                responsive: true,
                columnDefs: [
                    {
                        targets: -1,
                        orderable: false,
                    },
                ],
                language: {
                    url: locale
                },
            },
        );
    };
    return {
        //main function to initiate the module
        init: function () {
            initTable1();
            initTable2();
        },
    };
}();

jQuery(document).ready(function () {
    KTDatatablesBasicPaginations.init();
});
