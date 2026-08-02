@extends('layouts.dashboard.master')


@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.contracts')</h5>
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
            <a href="{{url('dashboard/settings/contracts')}}" class="text-muted">@lang('dashboard.contracts')</a>
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
                                <a href="{{url('dashboard/setting/contracts')}}"
                                   class="btn btn-primary ">
                                    <i class="flaticon2-reply-1" style="font-size: 1rem;"></i> @lang('dashboard.back')
                                </a>
                            </div>
                        </div>
                        <form novalidate="novalidate" class="kt-form kt-form--label-right" method="post"
                              action="{{ route('dashboard.contracts.update',$contract->id) }}"
                              enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.contract_name')</label>
                                            <span class="text-danger"> * </span>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="name" value="{{$contract->name}}"
                                                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                                       placeholder="@lang('dashboard.enter') @lang('dashboard.contract_name') "
                                                       aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('name') ? $errors->first('name') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang("dashboard.company")</label>
                                            <span class="text-danger"> *</span>
                                            <select class="form-control nice-select " name="company_id">
                                                <option value="">@lang('dashboard.select_company')</option>
                                                @foreach($companies as $company)
                                                    <option value="{{$company->id}}" data-name="{{strtolower($company->name)}}"
                                                        {{ $company->id == $contract->company_id ? 'selected' : '' }}>
                                                        {{$company->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('company_id') ? $errors->first('company_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang("dashboard.supervisor")</label>
                                            <span class="text-danger"> *</span>
                                            <select class="form-control nice-select " name="supervisor_id">
                                                <option value="">@lang('dashboard.select_supervisor')</option>
                                                @foreach($supervisors as $supervisor)
                                                    <option value="{{$supervisor->id}}" data-name="{{strtolower($supervisor->name)}}"
                                                        {{ $supervisor->id == $contract->supervisor_id ? 'selected' : '' }}>
                                                        {{$supervisor->fullname}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('supervisor_id') ? $errors->first('supervisor_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang("dashboard.department")</label>
                                            <span class="text-danger"> *</span>
                                            <select class="form-control nice-select " name="department_id">
                                                <option value="">@lang('dashboard.select_department')</option>
                                                @foreach($departments as $department)
                                                    <option value="{{$department->id}}" data-name="{{strtolower($department->name)}}"
                                                        {{ $department->id == $contract->department_id ? 'selected' : '' }}>
                                                        {{$department->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('department_id') ? $errors->first('department_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang("dashboard.contract_manager")</label>
                                            <span class="text-danger"> *</span>
                                            <select class="form-control nice-select " name="contract_manager_id">
                                                <option value="">@lang('dashboard.select_contract_manager')</option>
                                                @foreach($contract_managers as $contract_manager)
                                                    <option value="{{$contract_manager->id}}" data-name="{{strtolower($contract_manager->name)}}"
                                                        {{ $contract_manager->id == $contract->contract_manager_id ? 'selected' : '' }}>
                                                        {{$contract_manager->full_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('contract_manager_id') ? $errors->first('contract_manager_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang("dashboard.contract_type")</label>
                                            <span class="text-danger"> *</span>
                                            <select class="form-control nice-select " name="contract_type_id">
                                                <option value="">@lang('dashboard.select_contract_type')</option>
                                                @foreach($contract_types as $contract_type)
                                                    <option value="{{$contract_type->id}}" data-name="{{strtolower($contract_type->name)}}"
                                                        {{ $contract_type->id == $contract->contract_type_id ? 'selected' : '' }}>
                                                        {{$contract_type->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('contract_type_id') ? $errors->first('contract_type_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang("dashboard.from_date")</label>
                                            <span class="text-danger"> *</span>
                                            <input value="{{ $contract->from_date }}" type="date" class="form-control {{ $errors->has('from_date') ? 'is-invalid' : '' }}" name="from_date">
                                            <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('from_date') ? $errors->first('from_date') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang("dashboard.to_date")</label>
                                            <span class="text-danger"> *</span>
                                            <input value="{{ $contract->to_date }}" type="date" class="form-control {{ $errors->has('to_date') ? 'is-invalid' : '' }}" name="to_date">
                                            <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('to_date') ? $errors->first('to_date') : '' }}</strong>
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
                                                          placeholder="@lang('dashboard.enter') @lang('dashboard.description') ">{{$contract->description}}</textarea>
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
                                <button type="reset" class="btn btn-secondary">@lang('dashboard.cancel')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
