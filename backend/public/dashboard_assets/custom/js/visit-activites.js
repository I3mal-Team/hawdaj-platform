
"use strict";

$(document).on('click', '#AddVisitor', function (e) {

    var selectVisitors = $('#selectUsers').val();

    if (selectVisitors.length == 0) {
        getUsers();
    }
    $('#create_visitor_modal').modal();
    e.preventDefault();
});

$(document).on('click', '#addNewUser', function (e) {
    $("#userForm").css('display', 'block');
    e.preventDefault();
});

$(document).on('click', '#submitVisitForm', function (e) {
    e.preventDefault();

    var selectVisitors = $('#visitors_id').val();

    if (selectVisitors == null) {
        toastr.error(langs[LANG].you_have_to_choose_least_one_visitor)
    } else if (selectVisitors.length >= 1) {
        $("#visitForm").submit();
    } else {
        toastr.error(langs[LANG].you_have_to_choose_least_one_visitor);
    }
});


$(document).on('click', '.delete_visitor', function (e) {
    e.preventDefault();
    var item_id = $(this).data('visitor_id');
    $(`#visitorSelectedTable #row-${item_id}`).remove();
    $(`#visitors_id option[value=${item_id}]`).remove();
    $(`#selectUsers option[value=${item_id}]`).remove();
});


$(document).on('click', '#saveVisitors', function (e) {
    e.preventDefault();

    var selectUsers = $('#selectUsers').val();

    if (selectUsers.length >= 1) {
        selectUsers.forEach(item => {
            $("#visitors_id").append(
                `<option value=${parseInt(item)} selected></option>`
            );
        });

        $.get({
            url: `${HOST_URL}/${LANG}/dashboard/visitors-by-id`,
            data: {ids: selectUsers},
            method: 'GET',
            success: function (visitors) {
                if (visitors.length >= 1) {
                    $("#visitors_head").show();
                    $("#visitorSelectedTable").empty();
                    visitors.forEach(visitor => {
                        $("#visitorSelectedTable").append(
                            `<tr id="row-${visitor.id}">
                                <td>${visitor.full_name}</td>
                                <td>${visitor.company ?? '---'}</td>
                                <td>${visitor.id_number ?? '---'}</td>
                                <td>${visitor.email ?? '---'}</td>
                                <td>${visitor.nationality ?? '---'}</td>
                                <td><b style="color:green;">langs[LANG].approved</b></td>
                                <td style="cursor: pointer;" class="delete_visitor" data-visitor_id="${visitor.id}">
                                    <i class="fas fa-times-circle"></i>
                                </td>
                            </tr>`
                        );
                    });
                }
            }
        })

        toastr.success('Visitors Saved Successfully');

        setTimeout(() => {
            $('#create_visitor_modal').modal('hide');
        }, 200);

    } else {
        toastr.error(langs[LANG].you_have_to_choose_least_one_visitor);
    }

});

function getUsers() {
    let users;
    $.ajax({
        url: `${HOST_URL}/${LANG}/dashboard/get-visitor`,
        method: 'GET',
        success: function (data) {
            users = data.data;
            $("#selectUsers").html('');
            for (let i = 0; i < users.length; i++) {
                $("#selectUsers").append(
                    `<option value="${users[i].id}">${users[i].first_name} ${users[i].last_name}</option>`
                )
            }
        }
    })
}


// Class definition
var KTWizard6 = function () {
    // Base elements
    var _wizardEl;
    var _formEl;
    var _wizardObj;
    var _validations = [];

    // Private functions
    var _initWizard = function () {
        // Initialize form wizard
        _wizardObj = new KTWizard(_wizardEl, {
            startStep: 1, // initial active step number
            clickableSteps: false  // allow step clicking
        });

        // Validation before going to next page
        _wizardObj.on('change', function (wizard) {
            if (wizard.getStep() > wizard.getNewStep()) {
                return; // Skip if stepped back
            }

            // Validate form before change wizard step
            var validator = _validations[wizard.getStep() - 1]; // get validator for currnt step

            if (validator) {
                validator.validate().then(function (status) {
                    if (status == 'Valid') {
                        wizard.goTo(wizard.getNewStep());

                        KTUtil.scrollTop();
                    } else {
                        Swal.fire({
                            text: langs[LANG].sorry_you_cant_leave_required_input,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: langs[LANG].ok_got_it,
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light"
                            }
                        })
                    }
                });
            }

            return false;  // Do not change wizard step, further action will be handled by he validator
        });

        // Change event
        _wizardObj.on('changed', function (wizard) {
            KTUtil.scrollTop();
        });

        // Submit event
        _wizardObj.on('submit', function (wizard) {
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
                        url: `${HOST_URL}/${LANG}/dashboard/visitors`,
                        type: "POST",
                        data: $('#visitor_form').serializeArray(),
                        enctype: 'multipart/form-data',
                        success: function (data) {

                            toastr.success(data.message);

                            $("#selectUsers").append(
                                "<option value='" + data.id + "' selected>" + data.name + "</option>"
                            );

                            setTimeout((result) => {
                                $("#userForm").slideUp();

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
        });
    }

    var _initValidation = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        // Step 1
        _validations.push(FormValidation.formValidation(
            _formEl,
            {
                fields: {
                    first_name: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].first_name_required,
                            }
                        }
                    },
                    id_type: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].id_type_required,
                            }
                        }
                    },
                    id_number: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].id_number_required,
                            }
                        }
                    },
                    mobile: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].mobile_required,
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].email_is_required,
                            }
                        }
                    },
                    company: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].company_required
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap({
                        //eleInvalidClass: '',
                        eleValidClass: '',
                    })
                }
            }
        ));

        // Step 2
        _validations.push(FormValidation.formValidation(
            _formEl,
            {
                fields: {
                    country: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].country_required,

                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap({
                        //eleInvalidClass: '',
                        eleValidClass: '',
                    })
                }
            }
        ));

        _validations.push(FormValidation.formValidation(
            _formEl,
            {

            }
        ));
    }

    return {
        // public functions
        init: function () {
            _wizardEl = KTUtil.getById('kt_wizard');
            _formEl = KTUtil.getById('visitor_form');

            _initWizard();
            _initValidation();
        }
    };
}();

jQuery(document).ready(function () {
    KTWizard6.init();
});

