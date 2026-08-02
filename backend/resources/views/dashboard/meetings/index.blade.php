@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.confirm_visit')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

@push('css')
    <link href="{{asset('dashboard_assets/css/pages/wizard/wizard-6.css')}}" rel="stylesheet" type="text/css"/>
@endpush

@push('js')
    <script src="{{asset('dashboard_assets/custom/js/visitor.js')}}" type="text/javascript"></script>
@endpush

@section('content')
<div class="">
    <div class="card card-custom gutter-b">
     <div class="card-body border">
        <form class="px-10" novalidate="novalidate" id="kt_form">
            <!--begin: Wizard Step 1-->
            <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                <!--begin::Title-->
                <div class="pb-10 pb-lg-12 text-center text-md-left">
                    <h3 class="font-weight-bolder text-dark font-size-h2">{{ __('dashboard.visit_info') }}</h3>
                </div>
                </div>
                <!--begin::Title-->
                <!--begin::Form Group-->
                <div class="form-group">
                    <label>{{ __('dashboard.visitor_type') }}</label>
                    <select class="form-control form-control-solid">
                    <option value="type1">{{ __('dashboard.type1') }}</option>
                    <option value="type2">{{ __('dashboard.type2') }}</option>
                    <option value="type3">{{ __('dashboard.type3') }}</option>
                    <option value="type4">{{ __('dashboard.type4') }}</option>
                    </select>
                </div>
                <!--end::Form Group-->
                <!--begin::Form Group-->
                <div class="form-group">
                    <label>{{ __('dashboard.visit_reason') }}</label>
                    <select class="form-control form-control-solid">
                    <option value="reson1">{{ __('dashboard.reson') }}</option>
                    </select>
                </div>
                <!--end::Form Group-->

                <!--begin::Form Group-->
                <div class="form-group">
                    <label>{{ __('dashboard.sit') }}</label>
                    <select class="form-control form-control-solid">
                    <option value="siteId">{{ __('dashboard.sites') }}</option>
                    </select>
                </div>
                <!--end::Form Group-->

                <!--begin::Form Group-->
                <div class="form-group">
                    <label>{{ __('dashboard.department') }}</label>
                    <select class="form-control form-control-solid">
                    <option value="departmentId">{{ __('dashboard.department1') }}</option>
                    </select>
                </div>
                <!--end::Form Group-->

                <!--begin::Form Group-->
                <div class="form-group">
                    <label>{{ __('dashboard.hosts') }}</label>
                    <select class="form-control form-control-solid">
                    <option value="hostId">{{ __('dashboard.host1') }}</option>
                    </select>
                </div>
                <!--end::Form Group-->
                <!--begin::Form Group-->
                <div class="form-group">
                    <label>{{ __('dashboard.visit_from') }}</label>
                    <input type="date" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6" name="" placeholder="Address Line 1" value="Address Line 1" />
                </div>
                <!--end::Form Group-->

                <!--begin::Form Group-->
                <div class="form-group">
                    <label>{{ __('dashboard.visit_to') }}</label>
                    <input type="date" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6" name="" placeholder="Address Line 1" value="Address Line 1" />
                </div>
                <!--end::Form Group-->

                <div class="row">
                    <div class="col-6">
                        <!--begin::Form Group-->
                        <div class="form-group">
                            <label>{{ __('dashboard.visit_from') }}</label>
                            <input type="time" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6" name="" placeholder="Address Line 1" value="Address Line 1" />
                        </div>
                        <!--end::Form Group-->
                    </div>
                    <div class="col-6">
                        <!--begin::Form Group-->
                        <div class="form-group">
                            <label>{{ __('dashboard.visit_to') }}</label>
                            <input type="time" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6" name="" placeholder="Address Line 1" value="Address Line 1" />
                        </div>
                        <!--end::Form Group-->
                    </div>
                </div>

            </div>
            <!--end: Wizard Step 1-->
            <!--begin: Wizard Step 2-->
            <div class="pb-5" data-wizard-type="step-content">
                <!--begin::Title-->
                <div class="pt-lg-0 pt-5 pb-15 text-center text-md-left">
                    <h3 class="font-weight-bolder text-dark font-size-h2">{{ __('dashboard.personal_info') }}</h3>
                </div>
                <!--begin::Title-->
                    <!--begin::Input-->
                    <div class="form-group">
                        <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.first_name') }}</label>
                        <input type="text" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6" name="first_name" required placeholder="{{ __('dashboard.first_name') }}" />
                        <span class="form-text text-muted">{{ __('dashboard.first_name_required') }}.</span>
                    </div>
                    <!--end::Input-->
                    <!--begin::Input-->
                    <div class="form-group">
                        <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.last_name') }}</label>
                        <input type="text" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6" name="last_name" required placeholder="{{ __('dashboard.last_name') }}"/>
                        <span class="form-text text-muted">{{ __('dashboard.last_name_required') }}.</span>
                    </div>
                    <!--end::Input-->
                    <!--begin::Input-->
                    <div class="form-group">
                        <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.id_type') }}</label>
                        <select class="form-control form-control-solid" name="id_type" required>
                            <option value="type1">{{ __('dashboard.type1') }}</option>
                            <option value="type2">{{ __('dashboard.type2') }}</option>
                            <option value="type3">{{ __('dashboard.type3') }}</option>
                            <option value="type4">{{ __('dashboard.type4') }}</option>
                        </select>
                        <span class="form-text text-muted">{{ __('dashboard.id_type') }}.</span>
                    </div>
                    <!--end::Input-->
                    <!--begin::Input-->
                    <div class="form-group">
                        <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.id_number') }}</label>
                        <input type="number" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6" name="id_number" required placeholder="{{ __('dashboard.id_number') }}" />
                        <span class="form-text text-muted">{{ __('dashboard.id_number') }}.</span>
                    </div>
                    <!--end::Input-->
                    <!--begin::Input-->
                    <div class="form-group">
                        <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.nationality') }}</label>
                        <select class="form-control form-control-solid" name="nationality" required>
                            <option value="saudi">{{ __('dashboard.saudi') }}</option>
                            <option value="egyption">{{ __('dashboard.egyption') }}</option>
                        </select>
                        <span class="form-text text-muted">{{ __('dashboard.nationality_required') }}.</span>
                    </div>
                    <!--end::Input-->
                    <!--begin::Input-->
                    <div class="form-group">
                        <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.gender') }}</label>
                        <select class="form-control form-control-solid" name="gender" required>
                            <option value="male">{{ __('dashboard.male') }}</option>
                            <option value="female">{{ __('dashboard.female') }}</option>
                        </select>
                        <span class="form-text text-muted">{{ __('dashboard.gender_required') }}.</span>
                    </div>
                    <!--end::Input-->
                    <div class="form-group">
                        <h3>{{ __('dashboard.company_&_contact_info') }}</h3>
                    </div>
                    <!--begin::Input-->
                    <div class="form-group">
                        <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.company') }}</label>
                        <input type="text" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6" name="company" required placeholder="{{ __('dashboard.company') }}" />
                        <span class="form-text text-muted">{{ __('dashboard.company_required') }}.</span>
                    </div>
                    <!--end::Input-->
                    <!--begin::Input-->
                    <div class="form-group">
                        <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.position') }}</label>
                        <input type="text" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6" name="position" required placeholder="{{ __('dashboard.position') }}" />
                        <span class="form-text text-muted">{{ __('dashboard.position_required') }}.</span>
                    </div>
                    <!--end::Input-->
                    <!--begin::Input-->
                    <div class="form-group">
                        <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.mobile') }}</label>
                        <input type="text" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6" name="mobile" required placeholder="{{ __('dashboard.mobile') }}" />
                        <span class="form-text text-muted">{{ __('dashboard.mobile_required') }}.</span>
                    </div>
                    <!--end::Input-->
                    <!--begin::Input-->
                    <div class="form-group">
                        <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.email') }}</label>
                        <input type="email" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6" name="email" required placeholder="{{ __('dashboard.email') }}" />
                        <span class="form-text text-muted">{{ __('dashboard.email_required') }}.</span>
                    </div>
                    <!--end::Input-->
                </div>
            <!--end: Wizard Step 2-->
            <!--begin: Wizard Step 3-->
            <div class="pb-5" data-wizard-type="step-content">
                <!--begin::Title-->
                <div class="pt-lg-0 pt-5 pb-15 text-center text-md-left">
                    <h3 class="font-weight-bolder text-dark font-size-h2">{{ __('dashboard.other_info') }}</h3>
                </div>
                <!--end::Title-->
                <!-- vehicle_detail  -->
                <div class="form-group">
                    <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.vehicle_detail') }}</label>
                    <textarea name="vehicle_detail" id="" cols="30" rows="4" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6"></textarea>
                </div>
                <!-- end vehicle_detail  -->
                <!-- vehicle_material  -->
                <div class="form-group">
                    <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.vehicle_material') }}</label>
                    <textarea name="vehicle_material" id="" cols="30" rows="4" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6"></textarea>
                </div>
                <!-- end vehicle_material  -->
                <!-- personal_photo  -->
                <div class="form-group">
                    <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.personal_photo') }}</label>
                    <input type="file" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6" name="personal_photo" required placeholder="{{ __('dashboard.personal_photo') }}" />
                    <span class="form-text text-muted">{{ __('dashboard.personal_photo') }}.</span>
                </div>
                <!-- end personal_photo  -->
                <!-- id_copy  -->
                <div class="form-group">
                    <label class="font-size-h6 font-weight-bolder text-dark">{{ __('dashboard.id_copy') }}</label>
                    <input type="file" class="form-control form-control-solid h-auto p-5 border-0 rounded-lg font-size-h6" name="id_copy" required placeholder="{{ __('dashboard.id_copy') }}" />
                    <span class="form-text text-muted">{{ __('dashboard.id_copy') }}.</span>
                </div>
                <!-- end id_copy  -->
            </div>
            <!--end: Wizard Step 3-->
            <!--begin: Wizard Step 4 questions-->
            <div class="pb-5" data-wizard-type="step-content">
                <div class="pt-lg-0 pt-5 pb-15 text-center text-md-left">
                    <h3 class="font-weight-bolder text-dark font-size-h2">{{ __('dashboard.health_check') }}</h3>
                </div>
                <div class="form-group">

                </div>
            </div>
            <!--begin: Wizard Step 4 questions-->
            <!--begin: Wizard Actions-->
            <div class="d-flex justify-content-between pt-7">
                <div class="mr-2">
                    <button type="button" class="btn btn-light-primary font-weight-bolder font-size-h6 pr-8 pl-6 py-4 my-3 mr-3" data-wizard-type="action-prev">
                    <span class="svg-icon svg-icon-md mr-2">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Left-2.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24" />
                                <rect fill="#000000" opacity="0.3" transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000)" x="14" y="7" width="2" height="10" rx="1" />
                                <path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997)" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>Previous</button>
                </div>
                <div>
                    <button type="button" class="btn btn-primary font-weight-bolder font-size-h6 pl-8 pr-4 py-4 my-3" data-wizard-type="action-submit">Submit
                    <span class="svg-icon svg-icon-md ml-2">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Right-2.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24" />
                                <rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)" x="7.5" y="7.5" width="2" height="9" rx="1" />
                                <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span></button>
                    <button type="button" class="btn btn-primary font-weight-bolder font-size-h6 pl-8 pr-4 py-4 my-3" data-wizard-type="action-next">Next
                    <span class="svg-icon svg-icon-md ml-2">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Right-2.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24" />
                                <rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)" x="7.5" y="7.5" width="2" height="9" rx="1" />
                                <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span></button>
                </div>
            </div>
            <!--end: Wizard Actions-->
        </form>
    </div>
</div>
@endsection
