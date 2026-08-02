@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">{{ $title }}</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('dashboard.users_places.index') }}" class="text-muted">@lang('dashboard.user_places')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <span class="text-muted">@lang('dashboard.show')</span>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">{{ $place->title ?? '---' }}</h3>
                <div class="card-toolbar">
                    <a href="{{ route('dashboard.users_places.index') }}" class="btn btn-light-primary">@lang('dashboard.back')</a>
                </div>
            </div>
            <div class="card-body">
                @php
                    $imageUrl = null;
                    if ($place->hasMedia('image')) {
                        $imageUrl = $place->getFirstMediaUrl('image');
                    } elseif ($place->image) {
                        $imageUrl = resolvePhoto($place->image);
                    } else {
                        $descriptionImage = extractImageFromHtml($place->description ?? '');
                        if ($descriptionImage) {
                            $imageUrl = $descriptionImage;
                        }
                    }
                @endphp

                <div class="row">
                    <div class="col-md-4 mb-6">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" class="img-fluid rounded" alt="{{ $place->title }}">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 250px;">
                                <span class="text-muted">No image</span>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <strong>@lang('dashboard.type'):</strong>
                                <div>{{ trans('dashboard.' . $place->type) ?? '---' }}</div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <strong>@lang('dashboard.status'):</strong>
                                <div>
                                    @if(!$place->active)
                                        <span class="badge badge-warning">بانتظار القبول</span>
                                    @else
                                        <span class="badge badge-success">مقبول</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <strong>@lang('dashboard.address'):</strong>
                                <div>{{ $place->address ?? '---' }}</div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <strong>@lang('dashboard.address_type'):</strong>
                                <div>{{ $place->address_type ?? '---' }}</div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <strong>@lang('dashboard.description'):</strong>
                                <div>{!! $place->description ?? '---' !!}</div>
                            </div>
                            <div class="col-md-12">
                                <strong>@lang('dashboard.user_name'):</strong>
                                <div>
                                    {{ ($place->user->first_name ?? '') . ' ' . ($place->user->last_name ?? '') }}
                                    @if($place->user && $place->user->phone)
                                        - {{ $place->user->phone }}
                                    @endif
                                    @if($place->user && $place->user->email)
                                        <br>{{ $place->user->email }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('dashboard.users_places.index') }}" class="btn btn-secondary">@lang('dashboard.back')</a>
                @if(!$place->active)
                    <form method="POST" action="{{ route('dashboard.users_places.approve', $place->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success">قبول الطلب</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
