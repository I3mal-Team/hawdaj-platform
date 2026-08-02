@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.categories')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.zad_elgadels')</a>
        </li>

                <li class="breadcrumb-item text-muted">
                    <a href="{{url('setting/zad_elgadel-categories')}}" class="text-muted">@lang('dashboard.categories')</a>
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
                              action="{{ route('dashboard.zad_elgadel-categories.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group row validated">
                                            <div class="col-md-11">
                                                <label>{{__('dashboard.icon')}}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="flaticon2-image-file"></i>
                                                        </span>
                                                    </div>
                                                    <input type="file" name="icon"
                                                           class="form-control file {{ $errors->has('icon') ? 'is-invalid' : '' }}"
                                                           placeholder="{{__('dashboard.enter')}} {{__('dashboard.icon')}}"
                                                           aria-describedby="basic-addon1">
                                                    <div class="invalid-feedback">
                                                        <strong>{{ $errors->has('icon') ? $errors->first('icon') : '' }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1 image">
                                                <div class="image_prev_form thumb-output">
                                                    <img src="{{asset('dashboard_assets/media/users/default.jpg')}}"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.category_name')</label>
                                            <span class="text-danger"> * </span>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="name" value="{{old("name") ?? ''}}"
                                                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                                       placeholder="@lang('dashboard.enter') @lang('dashboard.category_name') "
                                                       aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('name') ? $errors->first('name') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.notes')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                                                    </span>
                                                </div>
                                                <textarea type="text" class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}"
                                                          name="notes" cols="30" rows="10"
                                                          placeholder="@lang('dashboard.enter') @lang('dashboard.notes') ">{{old("notes") ?? ''}}</textarea>
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('notes') ? $errors->first('notes') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang("dashboard.category")</label>
                                            <select id="category" class="form-control select2" name="parent_id">
                                                <option value="">@lang('dashboard.select_category')</option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}"
                                                        {{ $category->id == old("parent_id") ? 'selected' : '' }}>
                                                        {{$category->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('parent_id') ? $errors->first('parent_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    @include('dashboard.includes.partials._order_id_field')

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

