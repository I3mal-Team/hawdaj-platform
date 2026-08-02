<div class="modal fade" id="create_visitor_modal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('dashboard.add_visitor') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 100vh;">
                <div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group row">
                                <div class="col-8">
                                    <label for="">{{ __('dashboard.select_users') }}</label>
                                    <select class="form-control  select2" id="selectUsers" name="users[]" multiple>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <a href="#" id="addNewUser"
                                       class="btn btn-primary mt-6">
                                        <i class="flaticon2-plus" style="font-size: 1rem;"></i> {{ __('dashboard.add_visitor') }}
                                    </a>
                                    <a href="#" id="saveVisitors"
                                       class="btn btn-success mt-6">{{ __('dashboard.save') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" id="userForm" style="display:none;">
                            <div class="card">
                                <div class="card-body" style="padding: 0.25rem !important;">
                                    <div class="wizard wizard-6 d-flex flex-column flex-lg-row flex-column-fluid"
                                         id="kt_wizard" data-wizard-state="first">
                                        <!--begin::Container-->
                                        <div class="wizard-content d-flex flex-column mx-auto py-10 py-lg-5 w-100">
                                            <!--begin::Nav-->
                                            <div class="d-flex flex-column-auto flex-column px-10">
                                                <!--begin: Wizard Nav-->
                                                <div
                                                    class="wizard-nav pb-lg-10 pb-3 d-flex flex-column align-items-center align-items-md-start">
                                                    <!--begin::Wizard Steps-->
                                                    <div class="wizard-steps d-flex flex-column flex-md-row">

                                                        <!--begin::Wizard Step 1 Nav-->
                                                        <div class="wizard-step flex-grow-1 flex-basis-0"
                                                             data-wizard-type="step" data-wizard-state="current">
                                                            <div class="wizard-wrapper pr-lg-7 pr-5">
                                                                <div class="wizard-icon">
                                                                    <i class="wizard-check ki ki-check"></i>
                                                                    <span class="wizard-number">1</span>
                                                                </div>
                                                                <div class="wizard-label mr-3">
                                                                    <h3 class="wizard-title">{{ __('dashboard.basic_contact') }}</h3>
                                                                    <div class="wizard-desc">{{ __('dashboard.details_information') }}</div>
                                                                </div>
                                                                <span class="svg-icon">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                             width="24px" height="24px"
                                                                             viewBox="0 0 24 24" version="1.1">
																			<g stroke="none" stroke-width="1"
                                                                               fill="none" fill-rule="evenodd">
																				<polygon
                                                                                    points="0 0 24 0 24 24 0 24"></polygon>
																				<rect fill="#000000" opacity="0.3"
                                                                                      transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)"
                                                                                      x="7.5" y="7.5" width="2"
                                                                                      height="9" rx="1"></rect>
																				<path
                                                                                    d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z"
                                                                                    fill="#000000" fill-rule="nonzero"
                                                                                    transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)"></path>
																			</g>
																		</svg>
																	</span>
                                                            </div>
                                                        </div>
                                                        <!--end::Wizard Step 1 Nav-->

                                                        <!--begin::Wizard Step 2 Nav-->
                                                        <div class="wizard-step flex-grow-1 flex-basis-0"
                                                             data-wizard-type="step" data-wizard-state="pending">
                                                            <div class="wizard-wrapper pr-lg-7 pr-5">
                                                                <div class="wizard-icon">
                                                                    <i class="wizard-check ki ki-check"></i>
                                                                    <span class="wizard-number">2</span>
                                                                </div>
                                                                <div class="wizard-label mr-3">
                                                                    <h3 class="wizard-title">{{ __('dashboard.Vehicle_&_Materials') }} </h3>
                                                                    <div class="wizard-desc">{{ __('dashboard.information') }}</div>
                                                                </div>
                                                                <span class="svg-icon">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                             width="24px" height="24px"
                                                                             viewBox="0 0 24 24" version="1.1">
																			<g stroke="none" stroke-width="1"
                                                                               fill="none" fill-rule="evenodd">
																				<polygon
                                                                                    points="0 0 24 0 24 24 0 24"></polygon>
																				<rect fill="#000000" opacity="0.3"
                                                                                      transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)"
                                                                                      x="7.5" y="7.5" width="2"
                                                                                      height="9" rx="1"></rect>
																				<path
                                                                                    d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z"
                                                                                    fill="#000000" fill-rule="nonzero"
                                                                                    transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)"></path>
																			</g>
																		</svg>
																	</span>
                                                            </div>
                                                        </div>

                                                        <div class="wizard-step flex-grow-1 flex-basis-0"
                                                             data-wizard-type="step" data-wizard-state="pending">
                                                            <div class="wizard-wrapper pr-lg-7 pr-5">
                                                                <div class="wizard-icon">
                                                                    <i class="wizard-check ki ki-check"></i>
                                                                    <span class="wizard-number">3</span>
                                                                </div>
                                                                <div class="wizard-label mr-3">
                                                                    <h3 class="wizard-title">{{ __('dashboard.health_check') }}</h3>
                                                                    <div class="wizard-desc">{{ __('dashboard.fill_question') }}</div>
                                                                </div>
                                                                <span class="svg-icon">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                         xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                         width="24px" height="24px" viewBox="0 0 24 24"
                                                                         version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none"
                                                                           fill-rule="evenodd">
                                                                            <polygon
                                                                                points="0 0 24 0 24 24 0 24"></polygon>
                                                                            <rect fill="#000000" opacity="0.3"
                                                                                  transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)"
                                                                                  x="7.5" y="7.5" width="2" height="9"
                                                                                  rx="1"></rect>
                                                                            <path
                                                                                d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z"
                                                                                fill="#000000" fill-rule="nonzero"
                                                                                transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)"></path>
                                                                        </g>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <!--end::Wizard Step 2 Nav-->

                                                        <!--begin::Wizard Step 3 Nav-->
                                                        <div class="wizard-step flex-grow-1 flex-basis-0"
                                                             data-wizard-type="step" data-wizard-state="pending">
                                                            <div class="wizard-wrapper">
                                                                <div class="wizard-icon">
                                                                    <i class="wizard-check ki ki-check"></i>
                                                                    <span class="wizard-number">3</span>
                                                                </div>
                                                                <div class="wizard-label">
                                                                    <h3 class="wizard-title">{{__('dashboard.Complete')}}</h3>
                                                                    <div class="wizard-desc">{{ __('dashboard.submit_form') }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Wizard Step 3 Nav-->
                                                    </div>
                                                    <!--end::Wizard Steps-->
                                                </div>
                                                <!--end: Wizard Nav-->
                                            </div>
                                            <!--end::Nav-->
                                            <!--begin::Form-->
                                            <form class="px-10 fv-plugins-bootstrap fv-plugins-framework"
                                                  novalidate="novalidate" id="visitor_form">
                                                <!--begin: Wizard Step 1-->
                                                <div class="pb-5" data-wizard-type="step-content"
                                                     data-wizard-state="current">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group fv-plugins-icon-container">
                                                                <label class="text-dark">{{ __('dashboard.first_name') }}</label>
                                                                <span class="text-danger"> *</span>
                                                                <input type="text"
                                                                       class="form-control form-control-solid"
                                                                       name="first_name" placeholder="{{ __('dashboard.first_name') }}"
                                                                       value="">
                                                                <div class="fv-plugins-message-container"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group fv-plugins-icon-container">
                                                                <label class="text-dark">{{ __('dashboard.last_name') }}</label>
                                                                <input type="text"
                                                                       class="form-control form-control-solid"
                                                                       name="last_name" placeholder="{{ __('dashboard.last_name') }}"
                                                                       value="">
                                                                <div class="fv-plugins-message-container"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group fv-plugins-icon-container">
                                                                <label
                                                                    class="text-dark">{{ __('dashboard.id_type') }}</label>
                                                                <span class="text-danger"> *</span>
                                                                <input type="text"
                                                                       class="form-control form-control-solid"
                                                                       name="id_type" placeholder="{{ __('dashboard.id_type') }}" value="">

                                                                <div class="fv-plugins-message-container"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group fv-plugins-icon-container">
                                                                <label
                                                                    class="text-dark">{{ __('dashboard.id_number') }}</label>
                                                                <span class="text-danger"> *</span>
                                                                <input type="number"
                                                                       class="form-control form-control-solid"
                                                                       name="id_number"
                                                                       placeholder="{{ __('dashboard.id_number') }}"
                                                                       value="">
                                                                <div class="fv-plugins-message-container"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group fv-plugins-icon-container">
                                                                <label
                                                                    class="text-dark">{{ __('dashboard.mobile') }}</label>
                                                                <span class="text-danger"> *</span>
                                                                <input type="number"
                                                                       class="form-control form-control-solid"
                                                                       name="mobile"
                                                                       placeholder="{{ __('dashboard.mobile') }}"
                                                                       value="">
                                                                <div class="fv-plugins-message-container"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group fv-plugins-icon-container">
                                                                <label
                                                                    class="text-dark">{{ __('dashboard.email') }}</label>
                                                                <span class="text-danger"> *</span>
                                                                <input type="email"
                                                                       class="form-control form-control-solid"
                                                                       name="email"
                                                                       placeholder="{{ __('dashboard.email') }}"
                                                                       value="">
                                                                <div class="fv-plugins-message-container"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group fv-plugins-icon-container">
                                                                <label
                                                                    class="text-dark">{{ __('dashboard.gender') }}</label>
                                                                <select class="nice-select form-control" name="gender">
                                                                    <option value="male">{{ __('dashboard.male')}}</option>
                                                                    <option value="female">{{ __('dashboard.female')}}</option>
                                                                </select>
                                                                <div class="fv-plugins-message-container"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group fv-plugins-icon-container">
                                                                <label
                                                                    class="text-dark">{{ __('dashboard.nationally') }}</label>
                                                                <input type="text"
                                                                       class="form-control form-control-solid"
                                                                       name="nationality"
                                                                       placeholder="{{ __('dashboard.nationally') }}"
                                                                       value="">
                                                                <div class="fv-plugins-message-container"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group fv-plugins-icon-container">
                                                                <label
                                                                    class="text-dark">{{ __('dashboard.company') }}</label>
                                                                <span class="text-danger"> *</span>
                                                                <input type="text"
                                                                       class="form-control form-control-solid"
                                                                       name="company"
                                                                       placeholder="{{ __('dashboard.company') }}"
                                                                       value="">
                                                                <div class="fv-plugins-message-container"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group fv-plugins-icon-container">
                                                                <label
                                                                    class="text-dark">{{ __('dashboard.position') }}</label>
                                                                <input type="text"
                                                                       class="form-control form-control-solid"
                                                                       name="position"
                                                                       placeholder="{{ __('dashboard.position') }}"
                                                                       value="">
                                                                <div class="fv-plugins-message-container"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Form Group-->
                                                </div>
                                                <!--end: Wizard Step 1-->
                                                <!--begin: Wizard Step 2-->
                                                <div class="pb-5" data-wizard-type="step-content">
                                                    <div class="row">

                                                        <div class="col-6">
                                                            <div class="form-group fv-plugins-icon-container">
                                                                <label
                                                                    class="text-dark">{{ __('dashboard.vehicle_deal') }}</label>
                                                                <input type="text"
                                                                       class="form-control form-control-solid"
                                                                       name="vehicle_detail"
                                                                       placeholder="{{ __('dashboard.vehicle_deal') }}"
                                                                       value="">
                                                                <div class="fv-plugins-message-container"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group fv-plugins-icon-container">
                                                                <label
                                                                    class="text-dark">{{ __('dashboard.materials') }}</label>
                                                                <input type="text"
                                                                       class="form-control form-control-solid"
                                                                       name="vehicle_material"
                                                                       placeholder="{{ __('dashboard.materials') }}"
                                                                       value="">
                                                                <div class="fv-plugins-message-container"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group fv-plugins-icon-container">
                                                                <label
                                                                    class="text-dark">{{ __('dashboard.remarks') }}</label>
                                                                <input type="text"
                                                                       class="form-control form-control-solid"
                                                                       name="vehicle_remark"
                                                                       placeholder="{{ __('dashboard.remarks') }}"
                                                                       value="">
                                                                <div class="fv-plugins-message-container"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group row validated">
                                                                <div class="col-md-10">
                                                                    <label>{{__('dashboard.personal_photo')}}</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                                <span class="input-group-text">
                                                                                    <i class="flaticon2-image-file"></i>
                                                                                </span>
                                                                        </div>
                                                                        <input type="file" name="personal_photo"
                                                                               class="form-control file {{ $errors->has('personal_photo') ? 'is-invalid' : '' }}"
                                                                               placeholder="{{__('dashboard.enter')}} {{__('dashboard.personal_photo')}}">
                                                                        <div class="invalid-feedback">
                                                                            <strong>{{ $errors->has('personal_photo') ? $errors->first('personal_photo') : '' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 image">
                                                                    <div class="image_prev_form thumb-output">
                                                                        <img
                                                                            src="{{asset('dashboard_assets/media/blank.png')}}"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group row validated">
                                                                <div class="col-md-10">
                                                                    <label>{{__('dashboard.id_copy')}}</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                                <span class="input-group-text">
                                                                                    <i class="flaticon2-image-file"></i>
                                                                                </span>
                                                                        </div>
                                                                        <input type="file" name="id_copy"
                                                                               class="form-control file {{ $errors->has('id_copy') ? 'is-invalid' : '' }}"
                                                                               placeholder="{{__('dashboard.enter')}} {{__('dashboard.id_copy')}}">
                                                                        <div class="invalid-feedback">
                                                                            <strong>{{ $errors->has('id_copy') ? $errors->first('id_copy') : '' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 image">
                                                                    <div class="image_prev_form thumb-output">
                                                                        <img
                                                                            src="{{asset('dashboard_assets/media/blank.png')}}"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end: Wizard Step 2-->

                                                <div class="pb-5" data-wizard-type="step-content">
                                                    @include('dashboard.visits.activities.extra._questions')
                                                </div>

                                                <!--begin: Wizard Step 3-->
                                                <div class="pb-5" data-wizard-type="step-content">
                                                    <!--begin::Section-->
                                                    <h4 class="font-weight-bolder mb-3">{{ __('dashboard.visitor_complete') }}</h4>
                                                    <div class="text-dark-50 font-weight-bold line-height-lg mb-8">
                                                        <div>
                                                            {{ __('dashboard.Click_on_submit_to_save_this_visitor') }} !
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end: Wizard Step 3-->

                                                <!--begin: Wizard Actions-->
                                                <div class="d-flex justify-content-between pt-7">
                                                    <div class="mr-2">
                                                        <button type="button" class="btn btn-light-primary pr-8 wizard-btn-prev"
                                                                data-wizard-type="action-prev">
															<span class="svg-icon svg-icon-md mr-2">
																<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Left-2.svg-->
																<svg xmlns="http://www.w3.org/2000/svg"
                                                                     xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                     width="24px" height="24px" viewBox="0 0 24 24"
                                                                     version="1.1">
																	<g stroke="none" stroke-width="1" fill="none"
                                                                       fill-rule="evenodd">
																		<polygon points="0 0 24 0 24 24 0 24"></polygon>
																		<rect fill="#000000" opacity="0.3"
                                                                              transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000)"
                                                                              x="14" y="7" width="2" height="10"
                                                                              rx="1"></rect>
																		<path
                                                                            d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z"
                                                                            fill="#000000" fill-rule="nonzero"
                                                                            transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997)"></path>
																	</g>
																</svg>
                                                                <!--end::Svg Icon-->
															</span>{{__('dashboard.Previous')}}
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="btn btn-primary pl-8"
                                                                data-wizard-type="action-submit">{{ __('dashboard.submit') }}
                                                            <span class="svg-icon svg-icon-md ml-2">
																<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Right-2.svg-->
																<svg xmlns="http://www.w3.org/2000/svg"
                                                                     xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                     width="24px" height="24px" viewBox="0 0 24 24"
                                                                     version="1.1">
																	<g stroke="none" stroke-width="1" fill="none"
                                                                       fill-rule="evenodd">
																		<polygon points="0 0 24 0 24 24 0 24"></polygon>
																		<rect fill="#000000" opacity="0.3"
                                                                              transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)"
                                                                              x="7.5" y="7.5" width="2" height="9"
                                                                              rx="1"></rect>
																		<path
                                                                            d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z"
                                                                            fill="#000000" fill-rule="nonzero"
                                                                            transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)"></path>
																	</g>
																</svg>
                                                                <!--end::Svg Icon-->
															</span></button>
                                                        <button type="button" class="btn btn-primary pl-8 wizard-next-btn"
                                                                data-wizard-type="action-next">{{__('dashboard.next')}}
                                                            <span class="svg-icon svg-icon-md ml-2">
																<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Right-2.svg-->
																<svg xmlns="http://www.w3.org/2000/svg"
                                                                     xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                     width="24px" height="24px" viewBox="0 0 24 24"
                                                                     version="1.1">
																	<g stroke="none" stroke-width="1" fill="none"
                                                                       fill-rule="evenodd">
																		<polygon points="0 0 24 0 24 24 0 24"></polygon>
																		<rect fill="#000000" opacity="0.3"
                                                                              transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)"
                                                                              x="7.5" y="7.5" width="2" height="9"
                                                                              rx="1"></rect>
																		<path
                                                                            d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z"
                                                                            fill="#000000" fill-rule="nonzero"
                                                                            transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)"></path>
																	</g>
																</svg>
                                                                <!--end::Svg Icon-->
															</span></button>
                                                    </div>
                                                </div>
                                                <!--end: Wizard Actions-->

                                                <div></div>
                                                <div></div>
                                            </form>
                                            <!--end::Form-->
                                        </div>
                                        <!--end::Container-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
