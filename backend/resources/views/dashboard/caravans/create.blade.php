@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.caravans')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.setting')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.data_entry')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="{{url('dashboard/setting/caravans')}}" class="text-muted">@lang('dashboard.caravans')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('.description'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush

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
                        <form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post"
                              action="{{ route('dashboard.caravans.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.name')</label>
                                            <span class="text-danger"> * </span>
                                            <div class="input-group">
                                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('name') ? $errors->first('name') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.description')</label>
                                            <span class="text-danger"> * </span>
                                            <textarea
                                                class="description form-control {{ $errors->has('content') ? 'is-invalid' : '' }}"
                                                name="description">{{ $template->content ?? '' }}</textarea>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('content') ? $errors->first('content') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group row validated">
                                            <div class="col-md-10">
                                                <label>{{__('dashboard.image')}}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                                                <span class="input-group-text">
                                                                                    <i class="flaticon2-image-file"></i>
                                                                                </span>
                                                    </div>
                                                    <input type="file" name="image"
                                                           accept=".png , .jpg, .jpeg"
                                                           class="form-control file {{ $errors->has('image') ? 'is-invalid' : '' }}"
                                                           placeholder="{{__('dashboard.enter')}} {{__('dashboard.image')}}">
                                                    <div class="invalid-feedback">
                                                        <strong>{{ $errors->has('image') ? $errors->first('image') : '' }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 image">
                                                <div class="image_prev_form thumb-output">
                                                    <img
                                                        src="{{asset('dashboard_assets/media/blank.png')}}"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.facebook')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-facebook-letter-logo"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="facebook_link" value="{{old("facebook_link") ?? ''}}"
                                                       class="form-control {{ $errors->has('facebook') ? 'is-invalid' : '' }}"
                                                       placeholder="@lang('dashboard.enter') @lang('dashboard.facebook') "
                                                       aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('facebook') ? $errors->first('facebook') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.whatsapp')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-whatsapp"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="whatsapp" value="{{old("whatsapp") ?? ''}}"
                                                       class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                                                       placeholder="@lang('dashboard.enter') @lang('dashboard.whatsapp') "
                                                       aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('whatsapp') ? $errors->first('whatsapp') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.Instagram_link')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-instagram-logo"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="Instagram_link" value="{{old("Instagram_link") ?? ''}}"
                                                       class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                                                       placeholder="@lang('dashboard.enter') @lang('dashboard.Instagram_link') "
                                                       aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('Instagram_link') ? $errors->first('Instagram_link') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.website_link')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon2-world"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="website_link" value="{{old("website_link") ?? ''}}"
                                                       class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                                                       placeholder="@lang('dashboard.enter') @lang('dashboard.website_link') "
                                                       aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('website_link') ? $errors->first('website_link') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-3">
                                        <span class="switch switch-outline switch-icon switch-success">
                                            <label>
                                                <input type="checkbox" checked="checked" name="active" value="true"/>
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="kt-form__actions">
                                    <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
                                    <button type="reset" class="btn btn-secondary">@lang('dashboard.cancel')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

