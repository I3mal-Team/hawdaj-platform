@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.material-activities')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.activities')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

@push('js')
    <script src="{{asset('dashboard_assets/custom/js/activities.js')}}" type="text/javascript"></script>
@endpush

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid ">

            <div class="card">
                <div class="card-body">
                    <div class="example-preview custom-nav  mb-15">
                        <ul class="nav nav-tabs custom-nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="visitor-tab" data-toggle="tab" href="#visitor">
                        <span class="nav-icon">
                            <i class="flaticon-users-1"></i>
                        </span>
                                    <span class="nav-text">{{ __('dashboard.active_permission') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="material-tab" data-toggle="tab" href="#material"
                                   aria-controls="material">
                                <span class="nav-icon">
                                    <i class="flaticon2-box-1"></i>
                                </span>
                                    <span class="nav-text">{{ __('dashboard.completed') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content mt-5" id="myTabContent1">
                        <div class="tab-pane fade show active" id="visitor" role="tabpanel" aria-labelledby="visitor-tab">
                            @include('dashboard.materials.activities.active_permission')
                        </div>
                        <div class="tab-pane fade" id="material" role="tabpanel" aria-labelledby="material-tab">
                            @include('dashboard.materials.activities.completed')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="activitiesModal" tabindex="-1" role="dialog" aria-hidden="true">

        </div>

    </div>
@endsection
