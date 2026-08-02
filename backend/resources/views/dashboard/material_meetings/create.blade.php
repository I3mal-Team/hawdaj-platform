<!DOCTYPE html>
<html lang="{{ \LaravelLocalization::getCurrentLocale() }}" dir="{{ \LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <base href="{{url('dashboard')}}">
    <meta charset="utf-8"/>
    <title>{{isset($title)? __('dashboard.main_title') .' | '.$title : __('dashboard.main_title')}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Updates and statistics"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" type="text/css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cairo:400,700"/>
    <link rel="shortcut icon" href="{{asset('dashboard_assets/logo/Wakeb-icon.ico')}}"/>
    <link href="{{asset('dashboard_assets/plugins/global/plugins.bundle.'.getFileDir().'css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('dashboard_assets/plugins/custom/prismjs/prismjs.bundle.'.getFileDir().'css')}}" rel="stylesheet"/>
    <link href="{{asset('dashboard_assets/css/style.bundle.'.getFileDir().'css')}}" rel="stylesheet" />
    <link href="{{asset('dashboard_assets/plugins/custom/datatables/datatables.bundle.'.getFileDir().'css')}}" rel="stylesheet"/>
    <link href="{{asset('dashboard_assets/css/pages/wizard/wizard-6.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('dashboard_assets/custom/css/custom.css')}}" rel="stylesheet" type="text/css"/>


</head>
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled aside-enabled aside-fixed page-loading">
            <div class="card-body" id="VisitInfo" style="padding: 0.25rem !important;">
                <div class="wizard wizard-6 d-flex flex-column flex-lg-row flex-column-fluid" id="kt_wizard"
                     data-wizard-state="first">
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
                                                <h3 class="wizard-title">{{ __('dashboard.material_info') }}</h3>
                                                <div
                                                    class="wizard-desc">{{ __('dashboard.material_details') }}</div>
                                            </div>
                                            <span class="svg-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                         height="24px" viewBox="0 0 24 24" version="1.1">
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
                                                <h3 class="wizard-title">{{ __('dashboard.contact_info') }} </h3>
                                                <div
                                                    class="wizard-desc">{{ __('dashboard.contact_details') }}</div>
                                            </div>
                                            <span class="svg-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                         height="24px" viewBox="0 0 24 24" version="1.1">
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
                                                </span>
                                        </div>
                                    </div>
                                    <!--end::Wizard Step 2 Nav-->
                                </div>
                                <!--end::Wizard Steps-->
                            </div>
                            <!--end: Wizard Nav-->
                        </div>
                        <!--end::Nav-->
                        <!--begin::Form-->
                        <form class="px-10 fv-plugins-bootstrap fv-plugins-framework" novalidate="novalidate" id="material_form">
                            <!--begin: Wizard Step 1-->
                            <input type="hidden" class="form-control" name="material_id"
                                   value="{{ $materialOfRequest->material_id }}">
                            <input type="hidden" class="form-control" name="material_request_id"
                                   value="{{ $materialOfRequest->material_request_id }}">
                            <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                                @include('dashboard.material_meetings.steps.permission')
                            <!--end::Form Group-->
                            </div>

                            <!--begin: Wizard Step 2-->
                            <div class="pb-5" data-wizard-type="step-content">
                                @include('dashboard.material_meetings.steps.transporter')
                            </div>
                            <!--end: Wizard Step 2-->
                            <div class="d-flex justify-content-between pt-7">
                                <div class="mr-2">
                                    <button type="button" class="btn btn-light-primary pr-8"
                                            data-wizard-type="action-prev">
                                        <span class="svg-icon svg-icon-md mr-2">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Left-2.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                 height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                   fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                    <rect fill="#000000" opacity="0.3"
                                                          transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000)"
                                                          x="14" y="7" width="2" height="10" rx="1"></rect>
                                                    <path
                                                        d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z"
                                                        fill="#000000" fill-rule="nonzero"
                                                        transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997)"></path>
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>Previous
                                    </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary pl-8"
                                            data-wizard-type="action-submit">Submit
                                        <span class="svg-icon svg-icon-md ml-2">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Right-2.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                 height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                   fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                    <rect fill="#000000" opacity="0.3"
                                                          transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)"
                                                          x="7.5" y="7.5" width="2" height="9" rx="1"></rect>
                                                    <path
                                                        d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z"
                                                        fill="#000000" fill-rule="nonzero"
                                                        transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)"></path>
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span></button>
                                    <button type="button" class="btn btn-primary pl-8"
                                            data-wizard-type="action-next">Next
                                        <span class="svg-icon svg-icon-md ml-2">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Right-2.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                 height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                   fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                    <rect fill="#000000" opacity="0.3"
                                                          transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)"
                                                          x="7.5" y="7.5" width="2" height="9" rx="1"></rect>
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
                    </div>
                </div>
            </div>
</body>


<script>
    var HOST_URL = "{{ \URL::to('/') }}";
    var LANG = "{{ app()->getLocale() }}";
    var KTAppSettings = {
        "breakpoints": {
            "sm": 576,
            "md": 768,
            "lg": 992,
            "xl": 1200,
            "xxl": 1400
        },
        "colors": {
            "theme": {
                "base": {
                    "white": "#ffffff",
                    "primary": "#84548E",
                    "secondary": "#E5EAEE",
                    "success": "#1BC5BD",
                    "info": "#8950FC",
                    "warning": "#FFA800",
                    "danger": "#F64E60",
                    "light": "#E4E6EF",
                    "dark": "#181C32"
                },
                "light": {
                    "white": "#ffffff",
                    "primary": "#E1F0FF",
                    "secondary": "#EBEDF3",
                    "success": "#C9F7F5",
                    "info": "#EEE5FF",
                    "warning": "#FFF4DE",
                    "danger": "#FFE2E5",
                    "light": "#F3F6F9",
                    "dark": "#D6D6E0"
                },
                "inverse": {
                    "white": "#ffffff",
                    "primary": "#ffffff",
                    "secondary": "#3F4254",
                    "success": "#ffffff",
                    "info": "#ffffff",
                    "warning": "#ffffff",
                    "danger": "#ffffff",
                    "light": "#464E5F",
                    "dark": "#ffffff"
                }
            },
            "gray": {
                "gray-100": "#F3F6F9",
                "gray-200": "#EBEDF3",
                "gray-300": "#E4E6EF",
                "gray-400": "#D1D3E0",
                "gray-500": "#B5B5C3",
                "gray-600": "#7E8299",
                "gray-700": "#5E6278",
                "gray-800": "#3F4254",
                "gray-900": "#181C32"
            }
        },
        "font-family": "Poppins"
    };
</script>
<script src="{{asset('dashboard_assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('dashboard_assets/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
<script src="{{asset('dashboard_assets/js/scripts.bundle.js')}}"></script>
<script src="{{asset('dashboard_assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
@if(request()->segment(2) == 'dashboard' && request()->segment(3) == null)
@else
    <script src="{{asset('dashboard_assets/js/pages/widgets.js')}}"></script>
@endif
<script src="{{asset('dashboard_assets/js/pages/features/miscellaneous/toastr.js')}}"></script>
<script src="{{asset('dashboard_assets/js/pages/crud/forms/widgets/select2.js')}}"></script>
<script src="{{asset('dashboard_assets/custom/js/table.js')}}" type="text/javascript"></script>
<script src="{{asset('dashboard_assets/custom/js/custom.js')}}" type="text/javascript"></script>
<script src="{{asset('dashboard_assets/custom/js/delete-item.js')}}" type="text/javascript"></script>
<script src="{{asset('dashboard_assets/custom/js/material_metting_request.js')}}" type="text/javascript"></script>

<script>
file_excel = "{{__('dashboard.file_excel')}}";
file_pdf = "@lang('dashboard.file_pdf')";
file_csv = "@lang('dashboard.file_csv')";
copy_table = "@lang('dashboard.copy_table')";
custom_column = "@lang('dashboard.custom_column')";
action = "@lang('dashboard.action')";
locale = "{{ app()->getLocale() == 'ar' ? 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Arabic.json'
:'https://cdn.datatables.net/plug-ins/1.10.21/i18n/English.json' }}";
</script>
</html>
