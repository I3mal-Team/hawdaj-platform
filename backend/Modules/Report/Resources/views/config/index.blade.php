@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">
        <a href="{{url('dashboard/config')}}">{{trans('dashboard.report_config')}}</a>
    </h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.reports')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="javascript:;" class="text-muted">{{handleTrans($title)}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class=" config-container">
{{--                <div class="card-header">--}}
{{--                    <h3 style="font-size: 18px !important;">{{__('dashboard.configure_showing_report')}}</h3>--}}
{{--                </div>--}}
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 col-md-12 mb-4">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h4 class="mb-0">
                                                {{__('dashboard.cards')}}
                                                <span class="edit-details-btn pointer ml-1" data-toggle="tooltip"
                                                      data-placement="top" data-type="card"
                                                      title="{{__('dashboard.edit_details')}}">
                                                    <i class="far fa-edit" style="color: #777"></i>
                                                </span>
                                            </h4>
                                            <div class="card-toolbar">
                                                <div class="dropdown dropdown-inline" data-toggle="tooltip"
                                                     title="{{__('dashboard.card_setting')}}"
                                                     data-placement="top">
                                                    <a href="javascript:;"
                                                       class="btn btn-icon-primary btn-clean btn-sm btn-icon"
                                                       data-toggle="dropdown" aria-haspopup="true"
                                                       aria-expanded="false">
                                                            <span class="svg-icon svg-icon-lg">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </span>
                                                    </a>
                                                    <div class=" dropdown-menu dropdown-menu-md dropdown-menu-right"
                                                         style="">
                                                        <!--begin::Navigation-->
                                                        <ul class="chart-option navi navi-hover py-0" data-key="card">
                                                            <li class="navi-item">
                                                                <label
                                                                    class="checkbox checkbox-success navi-link"><input
                                                                        type="checkbox"
                                                                        @if(in_array("report", $config['card']['card'])) checked
                                                                        @endif  name='report'/><span
                                                                        class="mr-4"></span>{{__('dashboard.show_in_report_page')}}
                                                                </label>
                                                            </li>
                                                        </ul>
                                                        <!--end::Navigation-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row justify-content-center">
                                                <div class="col-12 col-md-6 col-lg-6 mb-4 mb-lg-0">
                                                    <div class="status-card card">
                                                        <div class="card-header py-5">
                                                            <div class="card-label">
                                                                <h4>{{__('dashboard.risk_duration')}}</h4>
                                                                <p class="mb-0 text-muted">{{__('dashboard.total_risk_duration')}}</p>
                                                            </div>
                                                            <div class="card-icon">
                                                                <i class="fas fa-angle-double-up fa-2x"
                                                                   style="color: green"></i>
                                                            </div>
                                                        </div>
                                                        <div class="card-body pb-8">
                                                            <div class="hours">
                                                                <img
                                                                    src="{{url('/')}}/dashboard_assets/media/svg/icons/Home/Timer.svg"/>
                                                                <span class="num mr-3 bold text-dark-90">
                                                            2000
                                                        </span>
                                                                <span>{{__('dashboard.minutes')}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-6 mb-4 mb-lg-0">
                                                    <div class="status-card card">
                                                        <div class="card-header py-5">
                                                            <div class="card-label">
                                                                <h4>{{__('dashboard.no_risk_duration')}}</h4>
                                                                <p class="mb-0 text-muted">{{__('dashboard.total_no_risk_duration')}}</p>
                                                            </div>
                                                            <div class="card-icon">
                                                                <i class="fas fa-angle-double-up fa-2x"
                                                                   style="color: green"></i>
                                                            </div>
                                                        </div>
                                                        <div class="card-body pb-8">
                                                            <div class="hours">
                                                                <img
                                                                    src="{{url('/')}}/dashboard_assets/media/svg/icons/Home/Timer.svg"/>
                                                                <span class="num mr-3 bold text-dark-90">
                                                                    2000
                                                                </span>
                                                                <span>{{__('dashboard.minutes')}}</span>
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
                        <div class="col-12 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="title">
                                        <h4 class="name">
                                            @php($title = \Str::snake($details['line']['title']))
                                            {{ $title ? __("dashboard.$title"): __('dashboard.bar_chart') }}
                                        </h4>
                                        <span class="edit-details-btn pointer ml-1" data-toggle="tooltip"
                                              data-placement="top" data-type="bar"
                                              title="{{__('dashboard.edit_details')}}">
                                            <i class="far fa-edit" style="color: #777"></i>
                                        </span>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="dropdown dropdown-inline" data-toggle="tooltip"
                                             title="{{__('dashboard.chart_setting')}}"
                                             data-placement="top">
                                            <a href="javascript:;"
                                               class="btn btn-icon-primary btn-clean btn-sm btn-icon"
                                               data-toggle="dropdown" aria-haspopup="true"
                                               aria-expanded="false">
                                                <span class="svg-icon svg-icon-lg">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </span>
                                            </a>
                                            <div class=" dropdown-menu dropdown-menu-md dropdown-menu-right">
                                                <!--begin::Navigation-->
                                                <ul class="chart-option navi navi-hover py-0" data-key="chart"
                                                    data-value="bar">
                                                    <li class="navi-item">
                                                        <label class="checkbox checkbox-success navi-link">
                                                            <input type="checkbox" name='report'
                                                                   @if(in_array("report", $config['chart']['bar'])) checked
                                                                @endif />
                                                            <span class="mr-4"></span>
                                                            {{__('dashboard.show_in_report_page')}}
                                                        </label>
                                                    </li>
                                                </ul>
                                                <!--end::Navigation-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="col-chart-cont">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="title">
                                        <h4 class="name">
                                            @php($title = \Str::snake($details['line']['title']))
                                            {{ $title ? __("dashboard.$title"): __('dashboard.area_chart') }}

                                        </h4>
                                        <span class="edit-details-btn pointer ml-1" data-toggle="tooltip"
                                              data-placement="top" data-type="line"
                                              title="{{__('dashboard.edit_details')}}">
                                            <i class="far fa-edit" style="color: #777"></i>
                                        </span>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="dropdown dropdown-inline" data-toggle="tooltip"
                                             title="{{__('dashboard.chart_setting')}}" data-placement="top">
                                            <a href="javascript:;"
                                               class="btn btn-icon-primary btn-clean btn-sm btn-icon"
                                               data-toggle="dropdown" aria-haspopup="true"
                                               aria-expanded="false">
                                                <span class="svg-icon svg-icon-lg">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </span>
                                            </a>
                                            <div class=" dropdown-menu dropdown-menu-md dropdown-menu-right"
                                                 style="">
                                                <!--begin::Navigation-->
                                                <ul class="chart-option navi navi-hover py-0" data-key="chart"
                                                    data-value="line">
                                                    <li class="navi-item">
                                                        <label class="checkbox checkbox-success navi-link">
                                                            <input type="checkbox" name='report'
                                                                   @if(in_array("report", $config['chart']['line'])) checked
                                                                @endif />
                                                            <span class="mr-4"></span>
                                                            {{__('dashboard.show_in_report_page')}}
                                                        </label>
                                                    </li>
                                                </ul>
                                                <!--end::Navigation-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="area-chart-cont">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="title">
                                        <h4 class="name">
                                            @php($title = \Str::snake($details['pie']['title']))
                                            {{ $title ? __("dashboard.$title"): __('dashboard.pie_chart') }}

                                        </h4>
                                        <span class="edit-details-btn pointer ml-1" data-toggle="tooltip"
                                              data-placement="top" data-type="pie"
                                              title="{{__('dashboard.edit_details')}}">
                                            <i class="far fa-edit" style="color: #777"></i>
                                        </span>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="dropdown dropdown-inline" data-toggle="tooltip"
                                             title="{{__('dashboard.chart_setting')}}"
                                             data-placement="left">
                                            <a href="javascript:;"
                                               class="btn btn-icon-primary btn-clean btn-sm btn-icon"
                                               data-toggle="dropdown" aria-haspopup="true"
                                               aria-expanded="false">
                                                <span class="svg-icon svg-icon-lg">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </span>
                                            </a>
                                            <div class=" dropdown-menu dropdown-menu-md dropdown-menu-right"
                                                 style="">
                                                <!--begin::Navigation-->
                                                <ul class="chart-option navi navi-hover py-0" data-key="chart"
                                                    data-value="pie">
                                                    <li class="navi-item">
                                                        <label class="checkbox checkbox-success navi-link">
                                                            <input type="checkbox" name='report'
                                                                   @if(in_array("report", $config['chart']['pie'])) checked
                                                                @endif />
                                                            <span class="mr-4"></span>
                                                            {{__('dashboard.show_in_report_page')}}
                                                        </label>
                                                    </li>
                                                </ul>
                                                <!--end::Navigation-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="pie-chart-cont">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="title">
                                        <h4 class="mb-0 name">
                                            @php($title = \Str::snake($details['table']['title']))
                                            {{ $title ? __("dashboard.$title"): __('dashboard.table_chart') }}

                                        </h4>
                                        <span class="edit-details-btn pointer ml-1" data-toggle="tooltip"
                                              data-placement="top" data-type="table"
                                              title="{{__('dashboard.edit_details')}}">
                                            <i class="far fa-edit" style="color: #777"></i>
                                        </span>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="dropdown dropdown-inline" data-toggle="tooltip"
                                             title="{{__('dashboard.table_setting')}}"
                                             data-placement="top">
                                            <a href="javascript:;"
                                               class="btn btn-icon-primary btn-clean btn-sm btn-icon"
                                               data-toggle="dropdown" aria-haspopup="true"
                                               aria-expanded="false">
                                                <span class="svg-icon svg-icon-lg">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </span>
                                            </a>
                                            <div class=" dropdown-menu dropdown-menu-md dropdown-menu-right"
                                                 style="">
                                                <!--begin::Navigation-->
                                                <ul class="chart-option navi navi-hover py-0" data-key="chart">
                                                    <li class="navi-item">
                                                        <label class="checkbox checkbox-success navi-link"><input
                                                                type="checkbox"
                                                                @if(in_array("report", $config['chart']['table'])) checked
                                                                @endif  name='report'/><span
                                                                class="mr-4"></span>{{__('dashboard.show_in_report_page')}}
                                                        </label>
                                                    </li>
                                                </ul>
                                                <!--end::Navigation-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="table-cont">
                                        <table class="table ">
                                            <thead>
                                            <tr>
                                                <th class="th-sm">{{__('dashboard.site_name')}}</th>
                                                <th class="th-sm">{{__('dashboard.risk_duration')}}</th>
                                                <th class="th-sm">{{__('dashboard.no_risk_duration')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr style="cursor: pointer;" class="data-record" data-toggle="modal">
                                                <td>Site 1</td>
                                                <td>1398</td>
                                                <td>3871</td>
                                            </tr>
                                            <tr style="cursor: pointer;" class="data-record" data-toggle="modal">
                                                <td>Site 2</td>
                                                <td>1398</td>
                                                <td>3871</td>
                                            </tr>
                                            <tr style="cursor: pointer;" class="data-record" data-toggle="modal">
                                                <td>Site 3</td>
                                                <td>1398</td>
                                                <td>3871</td>
                                            </tr>
                                            <tr style="cursor: pointer;" class="data-record" data-toggle="modal">
                                                <td>Site 4</td>
                                                <td>1398</td>
                                                <td>3871</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row-->

    {{--    Edit Chart description modal--}}
    <div class="modal fade" id="chartDescriptionModal" tabindex="-1" role="dialog"
         aria-labelledby="chartDescriptionModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chartDescriptionModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="display: block">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form class="form" novalidate>
                        <div class="form-group">
                            <label for="chart-title" class="col-form-label">@lang('dashboard.title')</label>
                            <input type="text" class="form-control" id="chart-title">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="chart-desc" class="col-form-label">@lang('dashboard.description')</label>
                            <textarea class="form-control" id="chart-desc"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="chart-timeUnit" class="col-form-label">@lang('dashboard.time_unit')</label>
                            <select id="chart-timeUnit" name="time-unit" class="form-control nice-select">
                                <option value="minute">@lang('dashboard.minute')</option>
                                <option value="hour">@lang('dashboard.hour')</option>
                                <option value="day">@lang('dashboard.day')</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">@lang('dashboard.close')</button>
                    <button type="button" class="btn btn-primary submit">@lang('dashboard.save')</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="{{asset('dashboard_assets/custom/js/report/config.js')}}"></script>
    <script>
        renderColumnChart();
        renderAreaChart();
        renderPieChart();

        let selectedCardTitle = null;
        let chartType = null;
        let newDetails = null;

        let details = @json($details);

        $(".edit-details-btn").on('click', function (e) {
            chartType = $(this).data('type');
            if (newDetails != null) {
                details = newDetails;
            }

            if (!chartType) return;

            selectedCardTitle = $(this).closest(".title").find(".name");

            let oldTitle,oldDesc,oldTime = '';
            if(details[chartType] != undefined) {
                oldTitle = details[chartType].title ?? '';
                oldDesc = details[chartType].description ?? '';
                oldTime = details[chartType].time_unit ?? '';
            }
            let chartModal = $("#chartDescriptionModal");
            let chartTypeText = `${chartType}_chart_details`
            chartModal.find("#chartDescriptionModalTitle").text(`${langs[LANG][chartTypeText]}`);
            chartModal.find("#chart-title").val(oldTitle);
            chartModal.find("#chart-desc").val(oldDesc);
            chartModal.find("#chart-timeUnit").val(oldTime).change();

            chartModal.modal('show');
        })

        $("#chartDescriptionModal .modal-footer .submit").on('click', () => {
            $("#chartDescriptionModal .form").submit();
        })

        $("#chartDescriptionModal .form").on('submit', function (e) {
            e.preventDefault();
            let chartTitle = $("#chartDescriptionModal #chart-title").val().trim();
            let chartDesc = $("#chartDescriptionModal #chart-desc").val().trim();
            let chartTimeUnit = $("#chartDescriptionModal #chart-timeUnit").val().trim();

            if (!chartTitle) {
                $(this).find("#chart-title").addClass("is-invalid");
                return;
            }

            $.ajax({
                url: "{{url('dashboard/config/chart-details')}}",
                data: {
                    type: chartType,
                    title: chartTitle,
                    description: chartDesc,
                    time_unit: chartTimeUnit,
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                type: 'POST',
                dataType: 'json',
                timeout: 2500,
                success: function (data) {
                    toastr.success("Chart Details has been updated");
                    selectedCardTitle.text(chartTitle);
                    newDetails = data.details;
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    toastr.error(errorMessage);
                },
                complete: function () {
                    $("#chartDescriptionModal").modal("hide")
                    selectedCardTitle = null;
                    chartType = null;
                }
            });
        });

        $("#chartDescriptionModal #chart-title").on("keyup", function () {
            $(this).removeClass("is-invalid")
        })
    </script>
@endpush

