@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.features')</h5>
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
                    <a href="{{url('setting/features')}}" class="text-muted">@lang('dashboard.features')</a>
                </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
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
                        <form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post"
                              action="{{ route('dashboard.features.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.feature_name')</label>
                                            <span class="text-danger"> * </span>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="name" value="{{old("name") ?? ''}}"
                                                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                                       placeholder="@lang('dashboard.enter') @lang('dashboard.feature_name') "
                                                       aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('name') ? $errors->first('name') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.description')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                                                    </span>
                                                </div>
                                                <textarea class="form-control description {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                                          name="description" rows="5"
                                                          placeholder="@lang('dashboard.enter') @lang('dashboard.description') ">{{old("description") ?? ''}}</textarea>
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
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

