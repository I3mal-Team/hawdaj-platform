// slick carousel
$('.item-section.slider').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: true,
    nextArrow: '<a href="#" class=" left"><i class="fas fa-chevron-right"></i></a>',
    prevArrow: '<a href="#" class=" right"><i class="fas fa-chevron-left"></i></a>',
    responsive: [{
        breakpoint: 1400,
        settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
            arrows: true,
        }
    },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
                arrows: true,
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }
    ]
});

// handle toggling setting and screenshot tabs
$(".setting-icon-sho").on("click", () => {
    let wrapper = $(".row.wrapper");
    if (!wrapper.hasClass("right-100")) {
        $(".show-image-icon-sho").click()
    }
    wrapper.toggleClass("left-100")
    $('.area-section.slider').slick('refresh')
});

$(".show-image-icon-sho").on("click", () => {
    let wrapper = $(".row.wrapper");
    if (!wrapper.hasClass("left-100")) {
        $(".setting-icon-sho").click()
    }
    wrapper.toggleClass("right-100");
    $('.area-section.slider').slick('refresh');
    $('.show-image .new-img').css("display", "none")
})

$('#car_table_data').on('draw.dt', function (e) {
    $(".action_cars").on("click", confirmationFunction);
    $(".view_invitaion").on('click', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        let plate = $(this).data('plate');
        let url = `${HOST_URL}/${LANG}/dashboard/search-car`;

        $.ajax({
            url: url,
            type: 'GET',
            data: {plate: plate},

            beforeSend: function () {
                $("#carInvitation").html('')
            },
            success: function (data) {
                $("#carInvitation").append(data)
                $("#carInvitation").modal('show');
            },
        });
    });
    $('#car_table_data tr').on("click", openModalPhoto)
});

let filtered = false;
let camId = null;
let status = null;

// Change notification number to 0
$(".notification-btn").on("click", function (e) {
    let notificationNumber = parseInt($(this).find(".notification-number").text().trim(0));
    if (notificationNumber && notificationNumber > 0) {
        $(this).find(".notification-number").html(0)
    }
})

var dataTable = $('#car_table_data').DataTable({
    createdRow: function (row, data, dataIndex) {
        $(row).attr('id', 'cat-' + data.id);
        $(row).attr('title', langs[LANG].click_to_show_image);
        $(row).attr('data-toggle', 'modal');
        $(row).attr('data-target', '#imagePreviewModal');
        $(row).attr('data-car_image', data.car_image);
        $(row).attr('data-plate_image', data.plate_image);
        $(row).addClass('data-record');
    },
    language: {
        url: locale,
        search: langs[LANG].search,
        searchPlaceholder: langs[LANG].search_placeholder,
    },
    "searching": false,
    "processing": true,
    "serverSide": true,
    "bPaginate": true,
    ajax: {
        url: `${HOST_URL}/${LANG}/dashboard/car-table/${siteID}?redirect_id=${redirect_id}`,
        type: "GET",
        data: function (d) {
            d.car_export = exportType;
            d.camera = $('#camera-select :selected').val()
            d.status = $('#status-select :selected').val()
            d.invitation_status = $('#invitation_status :selected').val()
            d.detection_status = $('#detection_status :selected').val()
        }
    },
    columns: [
        {
            data: 'date', render: function (data, data2, row, type) {
                let locale = LANG === "ar" ? "ar-EG" : "en-GB"

                return new Date(row.created_at).toLocaleDateString(locale, {
                    day: "numeric",
                    month: "long",
                    year: "numeric"
                });
            }
        },
        {
            data: 'time', render: function (data, data2, row, type) {
                let locale = LANG === "ar" ? "ar-EG" : "en-GB"

                return new Date(row.created_at).toLocaleTimeString(locale, {
                    hour: "2-digit",
                    minute: "2-digit"
                });
            }
        },
        {data: 'plate_en', name: 'plate_en'},
        {data: 'plate_ar', name: 'plate_ar'},
        {
            data: 'notice_time', className: "status-td", render: function (data, data2, row, type) {
                if (row.notice_time != null) {
                    return `<span class="badge badge-success">${langs[LANG].noticed}</span>`
                } else {
                    return `<span class="badge badge-danger">${langs[LANG].not_noticed}</span>`
                }
            }
        },
        {
            data: 'status', className: "invitation_status-td", render: function (data, data2, row, type) {
                if (row.status) {
                    return `<span class="badge badge-success">${langs[LANG].has_invitation}</span>`
                } else {
                    return `<span class="badge badge-danger">${langs[LANG].no_invitation}</span>`
                }
            }
        },
        {
            data: 'detection_status', className: "detection_status-td", render: function (data, data2, type, row) {
                if (data == 'pending') {
                    return `<span class="badge badge-warning" style="color:white">${langs[LANG].pending}</span>`
                } else if (data == 'success') {
                    return `<span class="badge badge-success">${langs[LANG].success}</span>`
                } else if (data == 'error') {
                    return `<span class="badge badge-danger">${langs[LANG].error}</span>`
                }
            }
        },
        {data: 'camID', name: 'camID'},
        {
            data: 'id', render: function (data, data2, row, type) {
                if (row.notice_time != null) {
                    let result = `<button class="btn btn-sm btn-primary" disabled >${langs[LANG].change_status}</button>`;
                    if (row.status) {
                        result += `<button data-plate="${row.plate_en}" class="ml-1 mt-1 btn btn-sm btn-success view_invitaion">${langs[LANG].view_invitaion}</button>`;
                    }
                    return result;
                } else {
                    let result = `<button data-id='${data}' data-status="${row.status}" href="javascript:void(0);" class="btn btn-sm btn-primary action_cars">${langs[LANG].change_status}</button>`;
                    if (row.status) {
                        result += `<button data-plate="${row.plate_en}"  class="ml-1 mt-1 btn btn-sm btn-success view_invitaion">${langs[LANG].view_invitaion}</button>`;
                    }
                    return result;
                }
            }
        }
    ]
});

//Filter Option
$('#camera-select').on("change", function (e) {
    dataTable.ajax.reload();
});
$('#status-select').on("change", function (e) {
    dataTable.ajax.reload();
});
$('#invitation_status').on("change", function (e) {
    dataTable.ajax.reload();
});
$('#detection_status').on("change", function (e) {
    dataTable.ajax.reload();
});

if (redirect_id != '') {
    setTimeout(() => {
        $(`tr#cat-${redirect_id}`).trigger('click');
    }, 500);
}

if (redirect_id != '') {
    setTimeout(() => {
        $(`tr#cat-${redirect_id}`).trigger('click');
    }, 100);
}

if (redirect_id == '' && exportType == '') {
    window.Echo.channel(`cars.${siteID}`).listen('.CarEvent', ({data}) => {
        playSound();

        $('.dataTables_empty').hide();
        let table = $("#car_table");
        dataTable.ajax.reload();

        let {camID, car_plate, id, created_at, plate_en} = data;
        let locale = LANG === "ar" ? "ar-EG" : "en-GB"
        let image = car_image;
        let created_at_date = new Date(created_at).toLocaleDateString(locale, {
            day: "numeric",
            month: "long",
            year: "numeric"
        });

        let created_at_time = new Date(created_at).toLocaleTimeString("en-US", {
            hour: "2-digit",
            minute: "2-digit"
        }).replace("AM", "am").replace("PM", "pm");

        if (LANG === "ar") {
            created_at_time = created_at_time.replace("am", "ص").replace("pm", "م");
        }

        // handle new notification
        let notificationElm = $(".notification-number");
        notificationElm.html(+notificationElm.text().trim() + 1)
        if ($(".notification-img").length !== 0) {
            $(".notification-img").parent().remove();
            $(".notification-footer").css("display", "block");
        }

        let notificationMarkup = ` <div data-Modal-id="#cat-${id}" href="javascript:;" class="d-flex align-items-center mb-6 new-notification">
                <div class="d-flex flex-column font-weight-bold" style="width: 100% !important;">
                    <a href="javascript:;" class="text-dark text-hover-primary mb-1 font-size-lg"> Car Detection</a>
                    <span class="text-muted">New Car waiting with plate ${plate_en}</span>
                        <span class="d-flex justify-content-between align-items-center">
                            <span class="text-dark-50  text-right mt-1">${created_at_date}</span>
                            <span class="text-dark-50  text-right mt-1">${created_at_time}</span>
                        </span>
                    </span>
                </div>
            </div>`;

        $('body').delegate('.topbar-item .dropdown-item', 'click', function () {
            let id = $(this).attr('data-Modal-id');
            $("tr" + id).trigger('click');
        });

        let no_invitation_count = parseInt($('#no_invitation').html());
        let invitation_count = parseInt($('#invitation').html());

        if (data.status) {
            $("#invitation").text(invitation_count + 1);
        } else {
            $("#no_invitation").text(no_invitation_count + 1);

            if (!$(".car-card").hasClass("risk")) {
                $(".car-card").addClass("risk")
                $(".item-status .status-cont-text").html('<span class="text-danger text">Cars Waiting</span>');
            }
        }

        if ($(".notification-dropdown .dropdown-items-container").children().length >= 15) {
            $(".notification-dropdown .dropdown-items-container").children().last().remove()
        }
        $(".notification-dropdown .dropdown-items-container").prepend(notificationMarkup)

        //handle new image to screenshot
        $('.show-image .new-img').css("display", "block")
        if ($(`.cameras-nav #home-tab-${camID}`).length) {
            if ($(`#home-${camID} .screenshoot-content .img-cont img`).length >= 15) {
                $(`#home-${camID} .screenshoot-content .img-cont`).children().last().remove()
            }

            $(`#home-${camID} .screenshoot-content .img-cont`).prepend(`<div class="img" id="img-${id}">
                    <img src="${image}" >
                        <div class="date">
                            <p>${created_at_date} ${created_at_time}</p>
                        </div>
                    </div>`);
        } else {
            $(".cameras-nav").prepend(` <li class="nav-item">
                    <a class="nav-link"
                       href="#home-${camID}"
                       id="home-tab-${camID}" data-toggle="tab"
                       role="tab" aria-controls="home-${camID}"
                       aria-selected="true">Camera ${camID}</a>
                 </li>`);

            $("#myCameraTabContent").prepend(` <div class="tab-pane fade"
               id="home-${camID}" role="tabpanel"
                aria-labelledby="home-tab-${camID}">
                <div class="screenshoot-content">
                    <div class="img-cont ">
                        <div class="img" id="img-${id}">
                        <img  src="${image}">
                         <div class="date">
                            <p>${created_at_date} ${created_at_time}</p>
                        </div>
                    </div>
                </div>
            </div>
            </div>`)
        }

        $(`.cameras-nav #home-tab-${camID}`).click()

        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": false,
            "positionClass": LANG === "ar" ? "toast-bottom-left" : "toast-bottom-right",
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        toastr.options.onclick = function () {
            $("tr#cat-" + id).trigger("click");
            let num = +$(".notification-number").text().trim()
            if (num > 0) {
                $(".notification-number").html(num - 1)
            }
        };
        toastr.error(`New Car waiting with plate ${plate_en}`, `${langs[LANG].toastr_error_car_detect}`);
    });
}

//change status button handler
function confirmationFunction(e) {
    e.stopImmediatePropagation();
    $("#notesTextArea").val('');
    $("#file").val('');
    $('input:radio[name="detection_status"]').prop('checked', false);

    $("#confirmationModal").modal("show").on('hidden.bs.modal', function (e) {
        $("#confirmationModal .confirm-btn").off();
    });

    let actionBtn = $(this);
    let id = $(this).data("id");
    let currentTr = $(this).closest("tr");

    $("#confirmationModal .confirm-btn").on("click", function (e) {
        e.stopImmediatePropagation();
        let uploadedFile = $('#file')[0].files[0];
        let textNotes = $("#notesTextArea").val();
        let detection_status = 'success';
        if ($(this).data('type') == 'error') {
            detection_status = 'error';
        }

        let fd = new FormData();
        uploadedFile && fd.append("file", uploadedFile);
        textNotes && fd.append('textNotes', textNotes);
        detection_status && fd.append('detection_status', detection_status);
        fd.append('_token', $('meta[name="csrf-token"]').attr("content"));
        actionBtn.prop("disabled", true)
        currentTr.find(".status-td").html('<div class="spinner spinner-primary"></div>')
        currentTr.find(".detection_status-td").html('<div class="spinner spinner-primary ml-30"></div>')

        actionBtn.prop('disabled', true)
        toastr.clear();
        toastr.options = {
            "positionClass": LANG === "ar" ? "toast-top-left" : "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
        }

        $.ajax({
            url: `${HOST_URL}/${LANG}/dashboard/cars/takeAction/${id}`,
            type: 'post',
            processData: false,
            contentType: false,
            cache: false,
            data: fd,
            enctype: 'multipart/form-data',
            success: function (data) {
                $("#notesTextArea").val('');
                $("#file").val('');
                $("#confirmationModal").modal("hide")
                $("#no_risk").text(data.no_risk_duration);
                $("#risk_duration").text(data.risk_duration);
                $('input:radio[name="detection_status"]').prop('checked', false);

                toastr.options.onclick = function () {
                    window.location.href = `${HOST_URL}/${LANG}/dashboard/car_notes?site_id=${siteID}`;
                };
                toastr.success(`${langs[LANG].toastr_success_update_state}`);

                setTimeout(function () {
                    currentTr.find(".status-td").html(`<span class="badge" style="background: green; color:#fff">${langs[LANG].noticed}</span>`)
                    currentTr.find(".detection_status-td").html(`<span class="badge ${detection_status == 'error' ? 'badge-danger' : 'badge-success'}">${langs[LANG][detection_status]}</span>`)

                    if (data.car_status) {
                        $(".car-card").addClass("risk")
                        $(".item-status .status-cont-text").html(`<span class="text-danger text">${langs[LANG].car_waiting}</span>`);
                    } else {
                        $(".car-card").removeClass("risk")
                        $(".item-status .status-cont-text").html(`<span class="text" style="color: green">${langs[LANG].no_car_waiting}</span>`);
                    }
                }, 500);

                setTimeout(function () {
                    if (redirect_id == '' && exportType == '' && $('#status-select :selected').val() == 'not_noticed') {
                        currentTr.remove();
                        let img = $(`#img-${id}`);
                        if (img) {
                            img.remove()
                        }
                    }
                }, 4000);

                $("#confirmationModal .confirm-btn").off();
            },
            error: function (data) {
                $("#confirmationModal").modal("hide");
                actionBtn.prop("disabled", false);
                currentTr.find(".status-td").html(`<span class="badge badge-danger">${langs[LANG].failed}</span>`)
                currentTr.find(".detection_status-td").html(`<span class="badge badge-danger">${langs[LANG].failed}</span>`)

                $("#confirmationModal .confirm-btn").off();
                toastr.error(`${langs[LANG].toastr_error_fail_update_state}`);
            }
        })
    })
}

function ExportFile() {
    var type = $('input[name="radios5"]:checked').val();
    var time = $('#car_export :selected').val();
    var last_child = $('#site_id').val();
    if (time == '') {
        toastr.error(langs[LANG].select_date);
        return;
    }
    $.ajax({
        url: `${HOST_URL}/${LANG}/dashboard/export_file_car`,
        type: 'post',
        data: {
            "_token": $('meta[name="csrf-token"]').attr("content"),
            "type": type,
            "time": time,
            "last_child": last_child,
        },
        success: function (data) {
            toastr.options.onclick = function () {
                window.location.href = `${HOST_URL}/${LANG}/dashboard/report/carModel/files?site_id=${siteID}`;
            };
            toastr.success(data.message);
        },
        error: function (data) {
            toastr.error("Failed To Export File");
        }
    })
}

let selectedRow = null;

function openModalPhoto() {
    let rowData = $(this).find('td');
    selectedRow = $(this);

    $("#imagePreviewModal .car_image").attr("src", $(this).data('car_image'));
    $("#imagePreviewModal .plate_image").attr("src", $(this).data('plate_image'));

    $("#imagePreviewModal .data-cont").html(`<div>
           <h6>${langs[LANG].date}</h6>
            <p>${rowData[0].innerHTML}</p>
        </div>
       <div>
           <h6>${langs[LANG].timing}</h6>
           <p>${rowData[1].innerHTML}</p>
       </div>
       <div>
          <h6>${langs[LANG].plate_en}</h6>
          ${rowData[2].innerHTML}
       </div>
       <div>
          <h6>${langs[LANG].plate_ar}</h6>
          ${rowData[3].innerHTML}
       </div>
       <div>
          <h6>${langs[LANG].detection_status}</h6>
           <p>${rowData[6].innerHTML}</p>
       </div>
       <div>
          <h6>${langs[LANG].status}</h6>
           <p>${rowData[5].innerHTML}</p>
       </div>
        <div>
          <h6>${langs[LANG].id_camera}</h6>
           <p>${rowData[7].innerHTML}</p>
       </div>
       <div>
          <h6>${langs[LANG].manage}</h6>
           <div class="modal-action">${rowData[8].innerHTML}</div>
       </div>
      `)
}

$(".takeAction").on("click", confirmationFunction)

$("#imagePreviewModal").on("shown.bs.modal", function (e) {
    $("#imagePreviewModal .data-cont .modal-action").on("click", () => {
        selectedRow.find(".action_cars").click()
        $("#imagePreviewModal").modal("hide");
    });
})

$("#imagePreviewModal").on("hide.bs.modal", function (e) {
    selectedRow = null;
    scale = 1;
    $('#imagePreviewModal.show .img-cont').css('transform', 'scale(' + scale + ')');
})

$('.settingImagesControle .fa-ellipsis-v').on('click', function () {
    $('.menuSettings').toggle();
});

$('.menuSettings .fa-times').on('click', function () {
    $('.menuSettings').hide();
})

function playSound() {
    let soundUrl = `${HOST_URL}/dashboard_assets/media/sound/Notification.mp3`;
    var audio = new Audio(soundUrl);
    audio.play();
}

$('.imagesThumb img').on('click', function () {
    var id = $(this).attr('id');
    $('#imgPreviwParent #' + id).addClass('active').siblings('img').removeClass('active');
    $(this).addClass('active').siblings('img').removeClass('active');
})
