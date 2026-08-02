@extends('layouts.dashboard.master')

@push('css')
    <link href="{{asset('dashboard_assets/plugins/custom/jstree/jstree.bundle.css')}}" rel="stylesheet"/>
    <style>
        .date_filter span{
            color: #3f4254 !important;
        }
    </style>
@endpush

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">
        <a href="{{url('dashboard/report/builder')}}">@lang('dashboard.report')</a>
    </h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.reports')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{url('dashboard/report/pinned')}}" class="text-muted">@lang('dashboard.pinned_reports')</a>
        </li>
        <li class="breadcrumb-item text-muted">

            <a href="javascript:;" class="text-muted">{{handleTrans($title)}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card model-card">
                        <div class="card-header custom-header border-bottom">
                            <div class="img-cont" style="background-image: url('{{asset("dashboard_assets/custom/images/{$filter['model_type']}.png")}}')">
                            </div>
                            <div class="content-cont">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="c-title">
                                        {{__("dashboard.{$filter['model_type']}_report")}}

                                        ({{__("dashboard.{$filter['report_list']}")}})
                                    </h3>
                                    @if(!$filter['draft'])
                                        <ul data-type="column"
                                            class="chart-filter-cont nav nav-pills  nav-dark-75">
                                            <li class="nav-item">
                                                <a class="nav-link btn btn-outline-secondary date_filter {{request('time_range') == '17'  && request('time_type') != 'specific' ? 'active' :''}}"
                                                   data-value="17">
                                                    <span class="nav-text">{{__('dashboard.this_year')}}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link btn btn-outline-secondary date_filter {{request('time_range') == '16' && request('time_type') != 'specific' ? 'active' :''}}"
                                                   data-value="16">
                                                    <span class="nav-text">{{__('dashboard.this_month')}}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link btn btn-outline-secondary date_filter {{request('time_range') == '14'  && request('time_type') != 'specific' ? 'active' :''}}"
                                                   data-value="14">
                                                    <span class="nav-text ">{{__('dashboard.this_week')}}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link   date_filter btn btn-outline-secondary {{request('time_range') == 'today' && request('time_type') != 'specific'? 'active' :''}}"
                                                   data-value="today">
                                                            <span
                                                                class="nav-text font-size-sm">{{__('dashboard.today')}}</span>
                                                </a>
                                            </li>

                                            <li class="nav-item">
                                                <button id="draft_icon" data-placement="top"
                                                        class="btn btn-icon btn-outline-secondary pointer draft"
                                                        data-toggle="tooltip"
                                                        title="{{__('dashboard.draft_this_report')}}"
                                                >
                                                    <i class="fas fa-thumbtack"></i>
                                                </button>
                                            </li>

                                            <li class="nav-item">
                                                <button id="aside-toggle"
                                                        class="btn btn-outline btn-outline-secondary"
                                                        type="button"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#offcanvasWithBothOptions"
                                                        aria-controls="offcanvasWithBothOptions"
                                                        data-placement="top"
                                                        data-toggle="tooltip"
                                                        title="{{__('dashboard.filter')}}">
                                                    <i title="Filter" class="fas fa-filter p-0"></i>
                                                </button>
                                            </li>

                                        </ul>
                                    @endif
                                </div>

                                <div>
                                    <strong style="font-size: 14px" class="text-dark-90">
                                        {{__("dashboard.{$filter['type']}_report_for")}}
                                    </strong>&nbsp; &nbsp;
                                    @foreach(\App\Models\Site::find($filter['type'] == 'specific' ? [$filter['site_id']] : $filter['site_ids']) as $item)

                                        <span style="font-size: 14px">{{$item->name}}</span>

                                        @if (!$loop->last)
                                            <strong>&nbsp;-&nbsp;</strong>
                                        @endif
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        <div class="card-body multi-content">
                            <div class="item-desc">
                                <h5 class="i-title">{{__('dashboard.start_date')}}</h5>
                                <p class="num">{{$filter['start']??__('dashboard.first_date')}}</p>
                            </div>
                            <div class="item-desc">
                                <h5 class="i-title">{{__('dashboard.end_date')}}</h5>
                                <p class="num">{{$filter['end']??dateFormat(carbon()->now())}}</p>
                            </div>
                            <div class="item-desc">
                                <h5 class="i-title">{{__('dashboard.time_type')}}</h5>
                                <p class="num">{{__('dashboard.'.$filter['time_type'])}}</p>
                            </div>
                            <div class="item-desc">
                                <h5 class="i-title">{{__('dashboard.list_type')}}</h5>
                                <p class="num">{{__('dashboard.'.$filter['groupBy'])}}</p>
                            </div>

                        </div>
                    </div>
                    <div class="mt-12 mb-10">
                        <div class="row ">
                            <div class="col-12">
                                <div class="card card-custom card-stretch extra-cards-cont ">
                                    <div class="card-header">
                                        <div class="card-title pt-1 d-flex justify-content-between w-100">
                                            <h3 class="card-label">
                                                <span class="d-block text-dark font-weight-bolder">
                                                    {{__('dashboard.extra_cards')}}
                                                </span>
                                                <div class="add-pinned-cont position-relative">

                                                </div>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="card-body row">
                                        @if($filter['has_card'] && in_array('card',$charts))
                                            @include("report::extra._card")
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row mt-4 ">
                            @foreach($charts as $chart)
                                @includeIf("report::charts._$chart")
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="filter-aside-backdrop"></div>
        <div class="filter-aside" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
             aria-labelledby="offcanvasWithBothOptionsLabel">
            <div class="aside-header">
                <div class="container py-5 d-flex justify-content-between align-items-center">
                    <h5 class="offcanvas-title"
                        id="offcanvasWithBothOptionsLabel">{{__('dashboard.filter_reports')}}</h5>
                    <button type="button" class="close-btn btn btn-icon" data-bs-dismiss="offcanvas" aria-label="Close">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="aside-body mt-6">
                <div class="container">
                    @if(!$filter['draft'])
                        @include('report::extra._filter_option')
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('report::extra._draft_confirm')

    <div id="add_to_pinned" class="modal fade" style="margin-top: 1%">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="font-size: 16px">{{trans('dashboard.add_chart_to_pinned')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body ">
                    <div class="pinned_section">
                        <div class="mt-5 text-center">
                            <img alt="Logo" class="max-h-75px max-w-75px"
                                 src="{{asset('dashboard_assets/logo/new_logo.png')}}"/>
                            <div class="spinner spinner-primary"
                                 style="margin-left: 47%;margin-top: 20px;margin-bottom: 41px;"></div>
                        </div>
                    </div>
                    <div id="pinned_repeater" style="display:none;">
                        <div class="form-group row mb-1">
                            <div data-repeater-list="titles" class="col-lg-12">
                                <div data-repeater-item class="form-group row mb-2">
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control titles" name="titles[]"
                                               placeholder="@lang('dashboard.enter_new_pinned_title')"/>
                                    </div>
                                    <div class="col-lg-2">
                                        <a href="javascript:;" data-repeater-delete=""
                                           class="btn btn-sm  font-weight-bold btn-danger btn-icon">
                                            <i class="la la-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-2">
                            <div class="col">
                                <div data-repeater-create="" class="btn font-weight-bold btn-sm btn-success">
                                    <i class="la la-plus"></i>
                                    @lang('dashboard.new_pinned')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="confrim_pinned">
                        {{trans('dashboard.confirm')}}
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{trans('dashboard.close')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    $messgError = $errors->first();
@endphp

@push("js")
    <script src="{{asset('dashboard_assets/js/pages/features/charts/apexcharts1036.js')}}"></script>
    <script src="{{asset('dashboard_assets/custom/js/report/reportService.js')}}"></script>
    <script>
        var currentForm = @json(request()->query());
        var currentCharts = @json($charts);

        @isset($data['bar'])
        barChart(@json($data['bar']['result']),  @json($data['bar']['sites']), "#chart1", "{{$filter['unit']}}", @json($details))
        @endisset

        @isset($data['line'])
        areaChart(@json($data['line']), "#chart2", "{{$filter['unit']}}",@json($details))
        @endisset

        @isset($data['pie'])
        @foreach($filter['columns']['data'] as $column)
        pieChart(@json($data['pie'][$column]), "#{{$column}}", "{{$filter['unit']}}", @json($details))
        @endforeach
            @endisset

        if ("{{$messgError}}" !== "") {
            toastr.error("{{$messgError}}");
        }

        $(".draft").on('click', function () {
            $("#confirm_draft").modal('show');
        });

        $("#report-title").on("keyup", function () {
            $(this).removeClass("is-invalid")
        })

        $("#confirm-button").on('click', function () {
            let reportTitleElm = $("#report-title");
            let title = reportTitleElm.val().trim();
            if (!title) {
                reportTitleElm.addClass("is-invalid")
                return;
            }
            $.ajax({
                url: "{{url('dashboard/report/draft-this')}}",
                data: {
                    ...currentForm,
                    title: title,
                    unit: '{{$filter['unit']}}',
                    report_list: '{{$filter['report_list']}}',
                    columns: '@json($filter['columns']['data'])',
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                type: 'POST',
                dataType: 'json',
                timeout: 2500,
                success: function (data) {
                    toastr.success(data.message);
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    toastr.error(jqXhr.responseJSON.message);
                },
                complete: function () {
                    $("#confirm_draft").modal("hide")
                    $("#draft_icon").fadeOut();
                }
            });
        });

        $(".add_pinned").on('click', function () {
            var chart_id = $(this).data('chart_id');

            $("#add_to_pinned .pinned_section").html('').append(`<div class="mt-5 text-center">
                <img alt="Logo" class="max-h-75px max-w-75px"
                     src="{{asset('dashboard_assets/logo/new_logo.png')}}"/>
                <div class="spinner spinner-primary"
                     style="margin-left: 47%;margin-top: 20px;margin-bottom: 41px;"></div>
            </div>`);

            $("#add_to_pinned").modal('show').on('hidden.bs.modal', function (e) {
                $("#add_to_pinned .pinned_section").html('').append(`<div class="mt-5 text-center">
                    <img alt="Logo" class="max-h-75px max-w-75px"
                         src="{{asset('dashboard_assets/logo/new_logo.png')}}"/>
                    <div class="spinner spinner-primary"
                         style="margin-left: 47%;margin-top: 20px;margin-bottom: 41px;"></div>
                </div>`);

                $("#add_to_pinned #pinned_repeater").html('').append(
                    `<div class="form-group row mb-1">
                        <div data-repeater-list="titles" class="col-lg-12">
                            <div data-repeater-item class="form-group row mb-2">
                                <div class="col-lg-10">
                                    <input type="text" class="form-control titles" name="titles[]"
                                           placeholder="@lang('dashboard.enter_new_pinned_title')"
                                    />
                                </div>
                                <div class="col-lg-2">
                                    <a href="javascript:;" data-repeater-delete=""
                                       class="btn btn-sm  font-weight-bold btn-danger btn-icon">
                                        <i class="la la-remove"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <div class="col">
                            <div data-repeater-create="" class="btn font-weight-bold btn-sm btn-success">
                                <i class="la la-plus"></i>
                                @lang('dashboard.new_pinned')
                    </div>
                </div>
            </div>
        </div>`).hide();

                $('#pinned_repeater').repeater({
                    initEmpty: false,

                    show: function () {
                        $(this).slideDown();
                    },
                    hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);
                    }
                });
            });
            $.get("{{url('dashboard/report/pinned/get-related-draft')}}", {chart_id: chart_id}, function (data, textStatus, jqXHR) {
                $("#add_to_pinned #pinned_repeater").fadeIn('fast');
                $("#add_to_pinned .pinned_section").html('').append(data);
            });
        });

        $("#confrim_pinned").on('click', function () {
            var titles = [];
            var pinned_ids = [];
            document.querySelectorAll('#add_to_pinned input.titles').forEach((item) => {
                titles.push(item.value)
            });
            document.querySelectorAll('#add_to_pinned input.pinned_ids:checked').forEach((item) => {
                pinned_ids.push(item.value)
            });
            let chart_id = $("#add_to_pinned input[name=chart_id]").val();

            $.ajax({
                url: "{{url('dashboard/report/pinned/add-draft')}}",
                data: {
                    titles: titles,
                    pinned_ids: pinned_ids,
                    chart_id: chart_id,
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                type: 'POST',
                dataType: 'json',
                timeout: 2500,
                success: function (data) {
                    toastr.success(data.message);
                    $("#add_to_pinned").modal('hide');
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    toastr.error(errorMessage);
                },
                complete: function () {
                    $("#confirm_draft").modal("hide")
                    $("#draft_icon").fadeOut();
                }
            });
        });

        $('#pinned_repeater').repeater({
            initEmpty: false,

            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    </script>
@endpush

