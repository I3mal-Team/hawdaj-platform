@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.sliders')</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('dashboard.sliders.index') }}" class="text-muted">@lang('dashboard.sliders')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <span class="text-muted">{{ $title }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    <form method="post" action="{{ route('dashboard.sliders.update', $slider->id) }}" enctype="multipart/form-data" class="kt-form kt-form--label-right">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.title')</label>
                                        <span class="text-danger"> * </span>
                                        <input type="text" name="title" value="{{ old('title', $slider->title) }}" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.title')">
                                        <div class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.link')</label>
                                        <input type="text" name="link" value="{{ old('link', $slider->link) }}" class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" placeholder="https://">
                                        <div class="invalid-feedback"><strong>{{ $errors->first('link') }}</strong></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.image')</label>
                                        <input type="file" name="image" class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}" accept="image/*">
                                        <small class="form-text text-muted">@lang('dashboard.slider_image_optional_hint')</small>
                                        <div class="invalid-feedback"><strong>{{ $errors->first('image') }}</strong></div>
                                        @if($slider->getFirstMediaUrl('image'))
                                            <div class="mt-3">
                                                <img src="{{ $slider->getFirstMediaUrl('image') }}" alt="" style="max-height: 120px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('dashboard.order_id')</label>
                                        <input type="number" name="order_id" value="{{ old('order_id', $slider->order_id) }}" class="form-control {{ $errors->has('order_id') ? 'is-invalid' : '' }}" min="0">
                                        <div class="invalid-feedback"><strong>{{ $errors->first('order_id') }}</strong></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mt-8">
                                        <label class="checkbox checkbox-lg">
                                            <input type="checkbox" name="active" value="1" {{ old('active', $slider->active) ? 'checked' : '' }}>
                                            <span></span>
                                            @lang('dashboard.active')
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('dashboard.update')</button>
                            <a href="{{ route('dashboard.sliders.index') }}" class="btn btn-secondary">@lang('dashboard.cancel')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
