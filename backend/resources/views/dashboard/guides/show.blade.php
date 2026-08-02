@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.guides')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">{{__('dashboard.guide_details')}}</h3>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-custom">
                                <div class="card-body">

                                    <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.image'):</div>
                                        <div class="col-md-9">
                                            <img src="{{ $guide->getFirstMediaUrl('image') ?: asset('images/default-avatar.png') }}"
                                                 alt="Guide Image"
                                                 class="img-thumbnail"
                                                 style="max-width: 150px;">
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.name'):</div>
                                        <div class="col-md-9">{{ $guide->name ?? '-' }}</div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.nickName'):</div>
                                        <div class="col-md-9">{{ $guide->nickName ?? '-' }}</div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.description'):</div>
                                        <div class="col-md-9">{{ $guide->description ?? '-' }}</div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.experience'):</div>
                                        <div class="col-md-9">{{ $guide->experience ?? '-' }}</div>
                                    </div>

                                    {{-- <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.facebook'):</div>
                                        <div class="col-md-9"><a href="{{ $guide->facebook }}">{{ $guide->facebook ?? '-' }}</a></div>
                                    </div> --}}

                                    <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.x'):</div>
                                        <div class="col-md-9"><a href="{{ $guide->x }}">{{ $guide->x ?? '-' }}</a></div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.twitter'):</div>
                                        <div class="col-md-9"><a href="{{ $guide->twitter }}">{{ $guide->twitter ?? '-' }}</a></div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.linkedin'):</div>
                                        <div class="col-md-9"><a href="{{ $guide->linkedin }}">{{ $guide->linkedin ?? '-' }}</a></div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.instagram'):</div>
                                        <div class="col-md-9"><a href="{{ $guide->instagram }}">{{ $guide->instagram ?? '-' }}</a></div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.personal_account'):</div>
                                        <div class="col-md-9"><a href="{{ $guide->personal_account }}">{{ $guide->personal_account ?? '-' }}</a></div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.regions'):</div>
                                        <div class="col-md-9">
                                            @if($guide->allRegions() && $guide->allRegions()->count())
                                                <ul class="mb-0">
                                                    @foreach($guide->allRegions() as $region)
                                                        <li>{{ $region->name }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.languages'):</div>
                                        <div class="col-md-9">
                                            @if($guide->allLanguages() && $guide->allLanguages()->count())
                                                <ul class="mb-0">
                                                    @foreach($guide->allLanguages() as $language)
                                                        <li>{{ $language->name }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3 font-weight-bold text-muted">@lang('dashboard.created_at'):</div>
                                        <div class="col-md-9">{{ $guide->created_at?->format('Y-m-d') ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
