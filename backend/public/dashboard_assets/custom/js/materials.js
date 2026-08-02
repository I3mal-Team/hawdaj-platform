$(document).on("change", "#req_type", function () {
    let type = $(this).val()
    if (type == 'inward_non-returnable') {
        $("#delivery").css('display', 'block')
        $("#return").css('display', 'none')
        $("#dispatch").css('display', 'none')

        $("#sender_info").css('display', 'block')
        $("#sender_info_inside").css('display', 'none')
    } else if (type == 'inward_returnable') {

        $("#delivery").css('display', 'block')
        $("#return").css('display', 'block')
        $("#dispatch").css('display', 'none')

        $("#sender_info").css('display', 'block')
        $("#sender_info_inside").css('display', 'none')
    } else if (type == 'outward_non-returnable') {

        $("#delivery").css('display', 'none')
        $("#return").css('display', 'none')
        $("#dispatch").css('display', 'block')

        $("#sender_info").css('display', 'block')
        $("#sender_info_inside").css('display', 'none')
    } else if (type == 'outward_returnable') {

        $("#dispatch").css('display', 'block')
        $("#return").css('display', 'block')
        $("#delivery").css('display', 'none')

        $("#sender_info").css('display', 'block')
        $("#sender_info_inside").css('display', 'none')
    } else if (type == 'outward_returnable') {

        $("#dispatch").css('display', 'block')
        $("#return").css('display', 'block')
        $("#delivery").css('display', 'none')

        $("#sender_info").css('display', 'block')
        $("#sender_info_inside").css('display', 'none')
    } else if (type == 'between_sites') {

        $("#dispatch").css('display', 'block')
        $("#return").css('display', 'none')
        $("#delivery").css('display', 'block')

        $("#sender_info").css('display', 'none')
        $("#sender_info_inside").css('display', 'block');




    } else if (type == 'personal_request') {

        $("#dispatch").css('display', 'none')
        $("#return").css('display', 'none')
        $("#delivery").css('display', 'block')
        $("#sender_info").css('display', 'none')
        $("#sender_info_inside").css('display', 'none')
    }



    if(type == 'between_sites'){
        toggleReceiverCardSize();

    }
    else{
        if(cardSize === 'changed'){
            toggleReceiverCardSize();
            cardSize = 'not_changed'
        }
    }
})
let cardSize = 'not_changed';
function toggleReceiverCardSize(){
    cardSize = 'changed';
    let receiverCardElm = $('#receiver_info');
    let formGroupsElms = receiverCardElm.find('.form-group-cont');
    // toggle main cards classes
    receiverCardElm.toggleClass('col-md-6');
    receiverCardElm.toggleClass('col-md-12');
    // toggle select sizes
    formGroupsElms.toggleClass('col-md-12')
    formGroupsElms.toggleClass('col-md-6')


}


function receiverAndSender(){
    let receiverCardElm = $('#receiver_info');
    let senderInfoElm = $('#sender_info_inside');

    let recMeetingSiteSelect  = receiverCardElm.find('select[name="site_id"]');
    let recEmpNameSelect = receiverCardElm.find('select[name="host_id"]');

    let sendMeetingSiteSelect  = senderInfoElm.find('select[name="sender_site_id"]');
    let sendEmpNameSelect = senderInfoElm.find('select[name="sender_host_id"]');



    sendMeetingSiteSelect.find(`option[value="${recMeetingSiteSelect.val()}"]`).attr('disabled', 'disabled');

    recMeetingSiteSelect.on('change', function() {


        sendMeetingSiteSelect.find("option[disabled='disabled']").removeAttr('disabled')
        if($(this).val()){
            sendMeetingSiteSelect.find(`option[value="${$(this).val()}"]`).attr('disabled', 'disabled')
        }

    })

    sendEmpNameSelect.find(`option[value="${recEmpNameSelect.val()}"]`).attr('disabled', 'disabled')
    recEmpNameSelect.on('change', function() {

        sendEmpNameSelect.find("option[disabled='disabled']").removeAttr('disabled')
        if($(this).val()){
            sendEmpNameSelect.find(`option[value="${$(this).val()}"]`).attr('disabled', 'disabled')
        }

    })



}
$(document).ready(function(){
    receiverAndSender();
})


$(document).on('click', '#addMaterials', function (e) {
    var selectMaterials = $('#selectmaterials').val();

    if (selectMaterials.length == 0) {
        getMaterials();
    }

    $('#create_visitor_modal').modal();

    e.preventDefault();
});

function getMaterials() {
    let material;
    $.ajax({
        url: `${HOST_URL}/${LANG}/dashboard/get-material`,
        method: 'GET',
        success: function (data) {
            material = data.data;
            console.log('data is :', material)
            $("#selectmaterials").html('');
            for (let i = 0; i < material.length; i++) {
                $("#selectmaterials").append(
                    `<option value="${material[i].id}">${material[i].name}</option>`
                )
            }
        }
    })
}

$(document).on('click', '#submitRequestForm', function (e) {
    e.preventDefault();

    var selectmaterials = $('#materials_id').val();
    const errorMsg = langs[LANG].chooseMat
    if (selectmaterials == null) {
        toastr.error(errorMsg)
    } else if (selectmaterials.length >= 1) {
        $("#materialForm").submit();
    } else {
        toastr.error(errorMsg)
    }
});

$(document).on('click', '#addNewMaterial', function (e) {
    $("#materialNewForm").css('display', 'block');
    e.preventDefault();
});

$(document).on("click", "#storeMaterialForm", function (e) {
    Swal.fire({
        text: langs[LANG].all_good_please_confirm_form,
        icon: "success",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: langs[LANG].yes_submit,
        cancelButtonText: langs[LANG].no_cancel,
        customClass: {
            confirmButton: "btn font-weight-bold btn-primary",
            cancelButton: "btn font-weight-bold btn-default"
        }
    }).then(function (result) {
        if (result.value) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: `${HOST_URL}/${LANG}/dashboard/materials`,
                type: "POST",
                data: $('#material_form').serializeArray(),
                enctype: 'multipart/form-data',
                success: function (data) {
                    toastr.success(data.message);

                    $("#selectmaterials").append(
                        "<option value='" + data.id + "' selected>" + data.name + "</option>"
                    );

                    setTimeout((result) => {
                        $("#materialNewForm").slideUp();

                        result.goFirst();

                    }, 200);

                },
                error: function () {
                    Swal.fire({
                        text: langs[LANG].sorry_looks_like_some_errors_detected_try_again,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: langs[LANG].ok_got_it,
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light"
                        }
                    })
                }
            })
        } else if (result.dismiss === 'cancel') {
            Swal.fire({
                text: langs[LANG].your_form_has_been_submitted,
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: langs[LANG].ok_got_it,
                customClass: {
                    confirmButton: "btn font-weight-bold btn-primary",
                }
            });
        }
    });
})

$(document).on('click', '#saveMaterials', function (e) {
    e.preventDefault();

    var selectmaterials = $('#selectmaterials').val();

    if (selectmaterials.length >= 1) {
        selectmaterials.forEach(item => {
            $("#materials_id").append(
                `<option value=${parseInt(item)} selected></option>`
            );
        });

        $.get({
            url: `${HOST_URL}/${LANG}/dashboard/materials-by-id`,
            data: {ids: selectmaterials},
            method: 'GET',
            success: function (materials) {
                if (materials.length >= 1) {
                    $("#materials_head").show();
                    $("#materialsSelectedTable").empty();
                    materials.forEach(material => {
                        $("#materialsSelectedTable").append(
                            `<tr id="row-${material.id}">
                                <td>${material.name}</td>
                                <td>${material.description ?? '---'}</td>
                                <td>${material.quantity ?? '---'}</td>
                                <td><b style="color:green;">${langs[LANG].approved}</b></td>
                                <td style="cursor: pointer;" class="delete_material" data-material_id="${material.id}">
                                    <i class="fas fa-times-circle"></i>
                                </td>
                            </tr>`
                        );
                    });
                }
            }
        })

        toastr.success(langs[LANG].material_saved_successfully);

        setTimeout(() => {
            $('#create_visitor_modal').modal('hide');
        }, 200);

    } else {
        toastr.error(langs[LANG].choose_one_material);
    }

});
