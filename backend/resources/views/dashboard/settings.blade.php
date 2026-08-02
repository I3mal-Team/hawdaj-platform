@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.dashboard')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="/" class="text-muted">{{$title ?? 'settings'}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid" >
        <div class="container-fluid">
                <div class="example-preview">
                    <ul class="nav nav-pills" id="myTab1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="site-tab-1" data-toggle="tab" href="#site-1">
                                                <span class="nav-icon">
                                                    <!-- <i class="flaticon2-user"></i> -->
                                                    <i class="flaticon-settings text-muted"></i>
                                                </span>
                                <span class="nav-text">{{ __('dashboard.site_&_right') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="general-tab-1" data-toggle="tab" href="#general-1">
                                                <span class="nav-icon">
                                                    <!-- <i class="flaticon2-user"></i> -->
                                                    <i class="flaticon-interface-6 text-muted"></i>
                                                </span>
                                <span class="nav-text">{{ __('dashboard.general') }}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mt-5" id="myTabContent1">
                        <div class="tab-pane fade show active" id="site-1" role="tabpanel" aria-labelledby="site-tab-1">
                            <!-- first tab form -->
                        @include('dashboard.settings.sites')
                        <!-- end first tab form -->
                        </div>
                        <div class="tab-pane fade show" id="general-1" role="tabpanel" aria-labelledby="general-tab-1">
                            <!-- first tab form -->
                        @include('dashboard.settings.general')
                        <!-- end first tab form -->
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection
