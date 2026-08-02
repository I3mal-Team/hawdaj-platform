@extends('layouts.dashboard.master')

@push('css')
    <link href="{{asset('dashboard_assets/plugins/custom/jstree/jstree.bundle.css')}}" rel="stylesheet"/>
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
            <a href="javascript:;" class="text-muted">{{handleTrans($title)}}</a>
        </li>
    </ul>


@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
                @forelse(app('models') as $index => $module)
                    <div class="col-md-6">
                        <div class="card card-custom model-builder-card gutter-b" >
                            <div class="card-body">
                                <!--begin::Top-->
                                <div class="d-flex">
                                    <!--begin::Pic-->
                                    <div style="border-bottom: transparent"
                                         class="card-header pl-0 report-header-info d-flex justify-content-between align-items-center py-1">
                                               <span class="icon"
                                                     >
                                                   <img
                                                        src="{{asset("dashboard_assets/custom/images/{$module}.png")}}"
                                                        alt="">
                                               </span>
                                    </div>
                                    <!--end::Pic-->
                                    <!--begin: Info-->
                                    <div class="flex-grow-1">
                                        <div
                                            class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                                            <div class="mr-3">
                                                <a href="#"
                                                   class="d-flex align-items-center text-dark text-hover-primary c-title  mr-3">
                                                    {{__("dashboard.".$module)}}
                                                </a>
                                            </div>

                                        </div>
                                        <div class="d-flex align-items-center flex-wrap justify-content-between">
                                            <div
                                                class="flex-grow-1 font-weight-bold text-dark-50 c-desc">
                                                {{trans('dashboard.you_can_show_specific_report_comparison',['title' =>$module])}}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="c-actions">
                                    <a href="{{url("dashboard/report/$module/files")}}"
                                       class="btn btn-sm btn-light-primary font-weight-bolder mr-2">
                                        @lang('dashboard.exported_file')
                                    </a>
                                    <a href="{{url("dashboard/report/$module/filter")}}"
                                       class="btn btn-sm btn-success font-weight-bolder">
                                        @lang('dashboard.build_report')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 justify-content-center">
                        @include('dashboard.includes._no-data-found')
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

