@extends('layouts.dashboard.master')

@push('css')
    <style>
        .home_filter {
            background: #fff !important;
        }

        .home_filter.active {
            color: #fff !important;
            background-color: #1bc5bd !important;
        }
    </style>
@endpush

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">{{ __('dashboard.project_title') }}</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-md">
        <li class="breadcrumb-item">
            <a href="{{ url('/') }}" class="text-muted">{{ $title ?? __('dashboard.home') }}</a>
        </li>
    </ul>
@endsection
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 mb-2 mb-lg-0 mt-2">
                    <div class="status-card card">
                        <div class="card-header py-5">
                            <div class="card-label">
                                <h4>{{ __('dashboard.total_stores') }}</h4>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-angle-double-up fa-2x" style="color: green"></i>
                            </div>
                        </div>
                        <div class="card-body pb-8">
                            <div class="hours">
                                <span class="num mr-3 bold text-dark-90">
                                    {{ $data['stores'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-2 mb-lg-0 mt-2">
                    <div class="status-card card">
                        <div class="card-header py-5">
                            <div class="card-label">
                                <h4>{{ __('dashboard.total_places') }}</h4>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-angle-double-up fa-2x" style="color: green"></i>
                            </div>
                        </div>
                        <div class="card-body pb-8">
                            <div class="hours">
                                <span class="num mr-3 bold text-dark-90">
                                    {{ $data['places'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-2 mb-lg-0 mt-2">
                    <div class="status-card card">
                        <div class="card-header py-5">
                            <div class="card-label">
                                <h4>{{ __('dashboard.site_pages') }}</h4>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-angle-double-up fa-2x" style="color: green"></i>
                            </div>
                        </div>
                        <div class="card-body pb-8">
                            <div class="hours">
                                <span class="num mr-3 bold text-dark-90">
                                    10 ~ 30
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-2 mb-lg-0 mt-2">
                    <div class="status-card card">
                        <div class="card-header py-5">
                            <div class="card-label">
                                <h4>{{ __('dashboard.total_visits') }}</h4>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-angle-double-up fa-2x" style="color: green"></i>
                            </div>
                        </div>
                        <div class="card-body pb-8">
                            <div class="hours">
                                <span class="num mr-3 bold text-dark-90">
                                    {{ $data['visits'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row-->
@endsection

@push('js')
    <script>
        $(".home_filter").on('click', function(e) {
            e.preventDefault();
            let time_range = $(this).data("value")
            let url = `${HOST_URL}/${LANG}/dashboard`;
            let inputs = [];

            inputs += `<input name="time_range" value=${time_range} >`;

            $(`<form action=${url}>${inputs}</form>`).appendTo('body').submit().remove();
        });
    </script>
@endpush
