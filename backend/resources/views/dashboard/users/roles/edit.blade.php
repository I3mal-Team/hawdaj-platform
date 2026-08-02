@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.roles')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.users')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="{{url('roles')}}" class="text-muted">@lang('dashboard.roles')</a>
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
                            <h3 class="card-title">{{ $title }}</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                </div>
                            </div>
                        </div>
                        <form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post"  action="{{ route('dashboard.roles.update' , $role->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.role_name')</label> <span class="text-danger"> *</span>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="name" value="{{$role->name ?? ''}}"
                                                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                                       placeholder="@lang('dashboard.enter') @lang('dashboard.role_name') "
                                                       aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('name') ? $errors->first('name') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.display_name')</label>
                                            <span class="text-danger"> *</span>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="label" value="{{$role->label ?? ''}}"
                                                       class="form-control {{ $errors->has('label') ? 'is-invalid' : '' }}"
                                                       placeholder="@lang('dashboard.enter') @lang('dashboard.display_name') ">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('label') ? $errors->first('label') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        @foreach($permissions as $model => $permission)
                                            <div class=" col-lg-3" style="margin-top: 10px">
                                                <label class="col-form-label">
                                                    {{ucfirst(__('dashboard.'.$model)) }}
                                                </label>
                                                <select class="form-control select2 {{ $errors->has("permissions") ? 'is-invalid' : '' }}"
                                                        name="permissions[]"  multiple="multiple">
                                                    @foreach($permission as $operation)
                                                        <option value="{{ $operation->id }}"
                                                            {{ in_array($operation->id, old('permissions') ?? [])
                                                               || $role->permissions->contains('id', $operation->id)
                                                               ? 'selected' : ''}}
                                                        >{{__('dashboard.'.explode(' ',lcfirst($operation->label))[0]) ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="kt-form__actions">
                                    <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
                                    <button type="reset"  class="btn btn-secondary">@lang('dashboard.cancel')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

