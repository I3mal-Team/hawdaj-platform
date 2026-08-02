@extends('layouts.dashboard.master')

@push('js')
    <script>

        $('#ios').css('display', 'none');
        $('#android').css('display', 'none');
        $('#web').css('display', 'none');

        $("#app_type").change(
            function() {
                var typeValue = $("#app_type option:selected").val();

                if (typeValue === 'web') {
                    $('#web').css('display', 'block');
                    $('#android').css('display', 'none');
                    $('#ios').css('display', 'none');
                } else if (typeValue === 'app') {
                    $('#web').css('display', 'none');
                    $('#android').css('display', 'block');
                    $('#ios').css('display', 'block');
                }
            });
    </script>

@endpush

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.stores')</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{url('dashboard/setting/stores')}}" class="text-muted">{{ $title }}</a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom gutter-b ">
                    <div class="card-header">
                        <h3 class="card-title">{{$title}}</h3>
                        <div class="card-toolbar">
                            <div class="example-tools justify-content-center">
                            </div>
                        </div>
                    </div>
                    <form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post" action="{{ route('dashboard.applications.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.title')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <input type="text" name="title" value="{{old("title") ?? ''}}" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.title') " aria-describedby="basic-addon1">
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('title') ? $errors->first('title') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.description')</label>
                                        <div class="input-group">
                                            <textarea class="form-control description {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" rows="5" placeholder="@lang('dashboard.enter') @lang('dashboard.description') ">{{old("description") ?? ''}}</textarea>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.categories')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <select name="categories[]" class="form-control select2" id="" multiple>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{in_array($category->id, old("categories") ?: []) ? "selected": ""}}>{{ $category->name ?? '---' }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('categories.*') ? $errors->first('categories.*') : '' }}</strong>
                                                <strong>{{ $errors->has('categories') ? $errors->first('categories') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.type')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <select id="app_type" class="form-control select2" name="type">
                                                <option value="">{{ __('dashboard.select_type') }}</option>
                                                <option value="app" {{ old('type') == 'app' ? 'selected ' : '' }}>
                                                    {{ __('dashboard.app_type') }}</option>
                                                <option value="web" {{ old('type') == 'web' ? 'selected ' : '' }}>
                                                    {{ __('dashboard.web') }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('type') ? $errors->first('type') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="web" class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.application_link')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <input type="text" name="link" value="{{old("link") ?? ''}}" class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.application_link') " aria-describedby="basic-addon1">
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('link') ? $errors->first('link') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="ios" class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.application_ios_link')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <input type="text" name="ios_link" value="{{old("ios_link") ?? ''}}" class="form-control {{ $errors->has('ios_link') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.application_link') " aria-describedby="basic-addon1">
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('ios_link') ? $errors->first('ios_link') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="android" class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.application_android_link')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <input type="text" name="android_link" value="{{old("android_link") ?? ''}}" class="form-control {{ $errors->has('android_link') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.application_link') " aria-describedby="basic-addon1">
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('android_link') ? $errors->first('android_link') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-4">
                        <div class="form-group row validated">
                            <div class="col-md-10">
                                <label>{{__('dashboard.image')}}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="flaticon2-image-file"></i>
                                        </span>
                                    </div>
                                    <input type="file" name="image" accept=".png , .jpg, .jpeg" class="form-control file {{ $errors->has('image') ? 'is-invalid' : '' }}" placeholder="{{__('dashboard.enter')}} {{__('dashboard.image')}}">
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->has('image') ? $errors->first('image') : '' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 image">
                                <div class="image_prev_form thumb-output">
                                    <img src="{{asset('dashboard_assets/media/blank.png')}}" />
                                </div>
                            </div>
                        </div>
                    </div>

                                @include('dashboard.includes.partials._order_id_field')

                                <div class="card-footer">
                                    <div class="kt-form__actions">
                                        <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
                                    </div>
                                </div>

                             </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
