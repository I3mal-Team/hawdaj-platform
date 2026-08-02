@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.stores')</h5>
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
        <a href="{{url('dashboard/setting/stores')}}" class="text-muted">@lang('dashboard.stores')</a>
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
                    <form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post" action="{{ route('dashboard.menu.store') }}" enctype="multipart/form-data">
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
                       

                    
                    <div class="col-md-6">
                        <div class="form-group validated">
                            <label>@lang('dashboard.description')</label>
                            <span class="text-danger"> * </span>
                            <div class="input-group">
                                <textarea class="form-control description {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" rows="5" placeholder="@lang('dashboard.enter') @lang('dashboard.description') ">{{old("description") ?? ''}}</textarea>

                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('description') ? $errors->first('categories') : '' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                        
                    <div class="col-md-6">
                        <div class="form-group validated">
                            <label>@lang('dashboard.price')</label>
                            <span class="text-danger"> * </span>
                            <div class="input-group">
                                <input type="text" name="price" value="{{old("price") ?? ''}}" class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.price') " aria-describedby="basic-addon1">

                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('price') ? $errors->first('price') : '' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group validated">
                            <label>@lang('dashboard.zad_elgadels')</label>
                            <span class="text-danger"> * </span>
                            <div class="input-group">
                                <select name="zad_id" class="form-control select2" id="">
                                    @foreach($zads as $zad)
                                    <option value="{{ $zad->id }}" {{in_array($zad->id, old("zad_id") ?: []) ? "selected": ""}}>{{ $zad->title ?? '---' }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('zad_id') ? $errors->first('zad_id') : '' }}</strong>
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

        </form>

    </div>
</div>

@endsection