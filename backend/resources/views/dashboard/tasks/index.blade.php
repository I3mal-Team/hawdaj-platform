@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.tasks')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>

        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

@push('js')
    <script src="{{asset('dashboard_assets/custom/js/tasks.js')}}" type="text/javascript"></script>
@endpush

@section('content')
    @php
        $type = 'visitors';
        $filter = array();
        if (app('request')->input('type')){
            $type = app('request')->input('type');
        }
        if(app('request')->input('text')) {
            $filter['text'] = app('request')->input('text');
        }
        if(app('request')->input('date_from')) {
            $filter['date_from'] = app('request')->input('date_from');
        }
        if(app('request')->input('date_to')) {
            $filter['date_to'] = app('request')->input('date_to');
        }
        if(app('request')->input('status')) {
            $filter['status'] = app('request')->input('status');
        }
    @endphp
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid ">
            <div class="card card-custom gutter-b">

                <div class="card-body custom-nav">
                    <ul class="nav nav-tabs custom-nav-tabs" id="myTab1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link @if($type == 'visitors') active @endif" id="visitor-tab"
                               href="{{ route('dashboard.tasks',['type' => 'visitors']) }}">
                    <span class="nav-icon">
                        <i class="flaticon-users-1"></i>
                    </span>
                                <span class="nav-text">{{ __('dashboard.visitors') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($type == 'materials') active @endif" id="material-tab"
                               href="{{ route('dashboard.tasks',['type' => 'materials']) }}"
                               aria-controls="material">
                    <span class="nav-icon">
                        <i class="flaticon2-box-1"></i>
                    </span>
                                <span class="nav-text">{{ __('dashboard.materials') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($type == 'cars') active @endif" id="car-tab"
                               href="{{ route('dashboard.tasks',['type' => 'cars']) }}"
                               aria-controls="car">
                    <span class="nav-icon">
                        <i class="flaticon2-delivery-truck "></i>

                    </span>
                                <span class="nav-text">{{ __('dashboard.cars') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($type == 'contractor') active @endif" id="contractor-tab"
                               href="{{ route('dashboard.tasks',['type' => 'contractor']) }}"
                               aria-controls="contractor">
                    <span class="nav-icon">
                        <i class="flaticon-users-1"></i>
                    </span>
                                <span class="nav-text">{{ __('dashboard.contractors') }}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mt-7" id="myTabContent1">
                        <div class="tab-pane fade show active" id="visitor" role="tabpanel" aria-labelledby="visitor-tab">
                            <!-- end search bar -->
                            @if($type == 'visitors')
                                @include('dashboard.tasks.main_tabs.visitors')
                            @elseif($type == 'materials')
                                @include('dashboard.tasks.main_tabs.materials')
                            @elseif($type == 'cars')
                                @include('dashboard.tasks.main_tabs.cars')
                            @else
                                @include('dashboard.tasks.main_tabs.contractors')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
                 aria-hidden="true">
            </div>

            <div class="modal fade" id="materialTaskModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
                 aria-hidden="true">

            </div>

            <div class="modal fade" id="carTaskModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
                 aria-hidden="true">

            </div>

            <div class="modal fade" id="contractorTaskModal" tabindex="-1" role="dialog"
                 aria-labelledby="staticBackdrop"
                 aria-hidden="true">

            </div>

        </div>
    </div>
@endsection
