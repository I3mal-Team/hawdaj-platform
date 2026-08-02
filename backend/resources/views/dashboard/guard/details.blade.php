@extends('layouts.dashboard.master')
@push('js')
    <script src="{{asset('dashboard_assets/custom/js/guard.js')}}" type="text/javascript"></script>
@endpush

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.guard')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="{{url('dashboard/guard')}}" class="text-muted">@lang('dashboard.visit_search')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

@section('content')
<!--begin::page-->
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-12 col-md-3">
                <ul class="nav nav-pills scroll-sm-nav-pills flex-md-column rounded bg-white" id="myTab1"
                    role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab-1" data-toggle="tab" href="#home-1">
                                                <span class="nav-icon">
                                                    <!-- <i class="flaticon2-user"></i> -->
                                                    <i class="flaticon2-user"></i>
                                                </span>
                            <span class="nav-text">{{ __('dashboard.contact') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab-1" data-toggle="tab" href="#contact-1" aria-controls="contact">
                                                <span class="nav-icon">
                                                    <!-- <i class="flaticon-black"></i> -->
                                                    <i class="flaticon2-contract"></i>
                                                </span>
                            <span class="nav-text">{{ __('dashboard.health') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-md-9">
                <div class="card-body rounded bg-white">
                    <div class="tab-content " id="myTabContent1">
                        <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab-1">
                            <!-- first tab form -->
                        @include('dashboard.guard.tabs.contact')
                        <!-- end first tab form -->
                        </div>
                        <!-- permission tab -->
                        <div class="tab-pane fade" id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">
                            @include('dashboard.guard.tabs.permission')
                        </div>
                        <!-- end permission tab -->
                        <!-- questions tab -->
                        <div class="tab-pane fade" id="contact-1" role="tabpanel" aria-labelledby="contact-tab-1">
                            @include('dashboard.guard.tabs.questions')
                        </div>
                        <!-- end questions tab -->
                    </div>
                    <!-- End tabs -->
                    <!-- take action -->
                    {{--                                @if($visitorData->status === "confirmed")--}}
                    <div class="mt-4" >
                        <a type="button" id="takeAction" class="btn btn-success">{{ __('dashboard.take_action') }}</a>
                    </div>
                {{--                                @endif--}}
                <!-- end take action -->
                </div>
            </div>
        </div>

    </div>
</div>


{{--    <div class="d-flex flex-column-fluid">--}}
{{--        <div class="container-fluid">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-12 bg-white">--}}
{{--                    <div class="row justify-content-md-center">--}}
{{--                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
{{--                            <div class="bg-white rounded p-10">--}}
{{--                                    <!-- steps -->--}}
{{--                                <div class="example-preview">--}}
{{--                                    <ul class="nav nav-pills" id="myTab1" role="tablist">--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a class="nav-link active" id="home-tab-1" data-toggle="tab" href="#home-1">--}}
{{--                                                <span class="nav-icon">--}}
{{--                                                    <!-- <i class="flaticon2-user"></i> -->--}}
{{--                                                    <i class="flaticon2-check-mark text-success"></i>--}}
{{--                                                </span>--}}
{{--                                                <span class="nav-text">{{ __('dashboard.contact') }}</span>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                        <!-- <li class="nav-item">--}}
{{--                                            <a class="nav-link" id="profile-tab-1" data-toggle="tab" href="#profile-1" aria-controls="profile">--}}
{{--                                                <span class="nav-icon">--}}
{{--                                                    <i class="flaticon2-check-mark text-success"></i>--}}
{{--                                                </span>--}}
{{--                                                <span class="nav-text">{{ __('dashboard.permission') }}</span>--}}
{{--                                            </a>--}}
{{--                                        </li> -->--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a class="nav-link" id="contact-tab-1" data-toggle="tab" href="#contact-1" aria-controls="contact">--}}
{{--                                                <span class="nav-icon">--}}
{{--                                                    <!-- <i class="flaticon-black"></i> -->--}}
{{--                                                    <i class="flaticon2-check-mark text-success"></i>--}}
{{--                                                </span>--}}
{{--                                                <span class="nav-text">{{ __('dashboard.health') }}</span>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                    <div class="tab-content mt-5" id="myTabContent1">--}}
{{--                                        <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab-1">--}}
{{--                                            <!-- first tab form -->--}}
{{--                                            @include('dashboard.guard.tabs.contact')--}}
{{--                                            <!-- end first tab form -->--}}
{{--                                        </div>--}}
{{--                                        <!-- permission tab -->--}}
{{--                                        <div class="tab-pane fade" id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">--}}
{{--                                            @include('dashboard.guard.tabs.permission')--}}
{{--                                        </div>--}}
{{--                                        <!-- end permission tab -->--}}
{{--                                        <!-- questions tab -->--}}
{{--                                        <div class="tab-pane fade" id="contact-1" role="tabpanel" aria-labelledby="contact-tab-1">--}}
{{--                                            @include('dashboard.guard.tabs.questions')--}}
{{--                                        </div>--}}
{{--                                        <!-- end questions tab -->--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <!-- End tabs -->--}}
{{--                                <!-- take action -->--}}
{{--                                @if($visitorData->status === "confirmed")--}}
{{--                                    <div class="text-center" style="margin-top: -30px">--}}
{{--                                        <a type="button" id="takeAction" class="btn btn-outline-success">{{ __('dashboard.take_action') }}</a>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                                <!-- end take action -->--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
<!-- begin::page -->

<!-- Modal-->
@include('dashboard.guard.guardAction')
<!-- end Modal -->
@endsection
