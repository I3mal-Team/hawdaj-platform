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
                        url: `${HOST_URL}/${LANG}/dashboard/meeting-confirmation`,
                        type: "POST",
                        data: $('#visitor_form').serializeArray(),
                        enctype: 'multipart/form-data',
                        success: function (data) {

                            toastr.success(data.message);

                            $("#VisitInfo").fadeOut();

                            $("#VisitConfirm").show();

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
                    visitor_type: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].id_type_required,
                            }
                        }
                    },
                    visit_reason: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].visit_reason_req
                            }
                        }
                    },
                    site_id: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].site_required
                            }
                        }
                    },
                    department_id: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].department_required
                            }
                        }
                    },
                    host_id: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].host_required
                            }
                        }
                    },
                    from_date: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].from_date_required
                            },
                        }
                    },
                    to_date: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].to_date_required,
                            },
                        }
                    },
                    from_fromtime: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].from_time_required
                            },
                        }
                    },
                    from_totime: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].to_time_required,
                            },
                        }
                    },
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
                fields: {
                    first_name: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].first_name_required,
                            }
                        }
                    },
                    last_name: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].last_name_required,
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
                    gender: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].gender_req,
                            }
                        }
                    },
                    company: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].company_required,
                            }
                        }
                    },
                    position: {
                        validators: {
                            notEmpty: {
                                message: langs[LANG].position_required,
                            }
                        }
                    },
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
