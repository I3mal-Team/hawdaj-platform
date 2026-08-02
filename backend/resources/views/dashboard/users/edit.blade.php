@extends('layouts.dashboard.master')


@section('page_header')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.users')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.edit_user')</a>
        </li>
    </ul>
@endsection

@push('js')
    <script>
        $(function() {
            $('#role').on('change', function() {
                let selected_option = $(this).find(':selected').data('name');

                if (selected_option == 'guard') {
                    $("#gate_select").slideDown('fast');

                } else {
                    $("#gate_select").slideUp('fast').val('');
                }
            });
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
                            <h3 class="card-title">@lang('dashboard.edit_user')</h3>
                            <div class="card-toolbar">
                                <a href="{{ url('dashboard/users') }}" class="btn btn-primary ">
                                    <i class="flaticon2-reply-1" style="font-size: 1rem;"></i> @lang('dashboard.back')
                                </a>
                            </div>
                        </div>
                        <form novalidate="novalidate" class="kt-form kt-form--label-right" method="post"
                            action="{{ route('dashboard.users.update', $user->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.first_name') </label> <span class="text-danger"> *</span>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-user"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="first_name" value="{{ $user->first_name }}"
                                                    class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                                    placeholder="@lang('dashboard.enter') @lang('dashboard.first_name') " />
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('first_name') ? $errors->first('first_name') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.last_name') </label> <span class="text-danger"> *</span>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-user"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="last_name" value="{{ $user->last_name }}"
                                                    class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                                                    placeholder="@lang('dashboard.enter') @lang('dashboard.last_name') " />
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('last_name') ? $errors->first('last_name') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.email')</label> <span class="text-danger"> *</span>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-envelope"></i>
                                                    </span>
                                                </div>
                                                <input type="email" name="email" value="{{ $user->email }}"
                                                    autocomplete="off"
                                                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                    placeholder="@lang('dashboard.enter') @lang('dashboard.email')">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('email') ? $errors->first('email') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('dashboard.department')</label>
                                            <span class="text-danger"> *</span>
                                            <select id="department" class="form-control nice-select" name="department_id">
                                                <option value="">@lang('dashboard.select_department')</option>
                                                @foreach ($departments ?? [] as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ $department->id == $user->department_id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('department_id') ? $errors->first('department_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('dashboard.region')</label>
{{--                                            <span class="text-danger"> *</span>--}}
                                            <select id="region" class="form-control select2" name="region_id">
                                                @foreach ($regions as $region)
                                                    <option value="{{ $region->id }}"
                                                        data-name="{{ $region->name }}"
                                                        {{ $region->id == $user->region_id ? 'selected' : '' }}>
                                                        {{ $region->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                style="margin-right: 6px !important; margin-top: 11px;">
                                                <strong>{{ $errors->has('region_id') ? $errors->first('region_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('dashboard.role')</label>
                                            <span class="text-danger"> *</span>
                                            <select id="role" class="form-control select2" name="role">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        data-name="{{ strtolower($role->name) }}"
                                                        {{ $role->id == $userRoleId ? 'selected' : '' }}>
                                                        {{ $role->label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                style="margin-right: 6px !important; margin-top: 11px;">
                                                <strong>{{ $errors->has('role') ? $errors->first('role') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="gate_select"
                                        @if ($user->hasRole('guard')) @else style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>@lang('dashboard.gates')</label> <span class="text-danger"> *</span>
                                            <select id="gates" class="form-control select2" name="gates[]" multiple>
                                                @foreach ($gates ?? [] as $gate)
                                                    <option value="{{ $gate->id }}"
                                                        {{ $user->gates->contains('id', $gate->id) ? 'selected' : '' }}>
                                                        {{ $gate->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('gates') ? $errors->first('gates') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('dashboard.sites')</label> <span class="text-danger"> *</span>
                                            <select id="sites" class="form-control select2" name="sites[]" multiple>
                                                @foreach ($sites ?? [] as $site)
                                                    <option value="{{ $site->id }}"
                                                        {{ $user->sites->contains('id', $site->id) ? 'selected' : '' }}>
                                                        {{ $site->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('sites.*') ? $errors->first('sites.*') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.phone')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="la la-exclamation-triangle la la-mobile"></i>
                                                    </span>
                                                </div>
                                                <input name="phone" type="number" value="{{ $user->phone }}"
                                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                    min=0 maxlength=15
                                                    class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                                    placeholder="@lang('dashboard.enter') @lang('dashboard.phone')"
                                                    aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('phone') ? $errors->first('phone') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.companies')</label>
                                            <div class="input-group">
                                                <select id="companies" class="form-control nice-select"
                                                    name="company_id">
                                                    <option value="">@lang('dashboard.select_company')</option>
                                                    @foreach ($companies ?? [] as $company)
                                                        <option value="{{ $company->id }}"
                                                            {{ $company->id == $user->company_id ? 'selected' : '' }}>
                                                            {{ $company->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('company_id') ? $errors->first('company_id') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row validated">
                                            <div class="col-md-10">
                                                <label>{{ __('dashboard.user_photo') }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="flaticon2-image-file"></i>
                                                        </span>
                                                    </div>
                                                    <input type="file" id="file" name="photo"
                                                        class="form-control {{ $errors->has('photo') ? 'is-invalid' : '' }}"
                                                        placeholder="{{ __('dashboard.enter') }} {{ __('dashboard.user_photo') }}"
                                                        aria-describedby="basic-addon1">
                                                    <div class="invalid-feedback">
                                                        <strong>{{ $errors->has('photo') ? $errors->first('photo') : '' }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 image">
                                                <div class="image_prev_form thumb-output">
                                                    <img src="{{ resolvePhoto($user->photo) }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.password')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="la la-exclamation-triangle flaticon-lock"></i>
                                                    </span>
                                                </div>
                                                <input type="password" value="" autocomplete="new-password"
                                                    name="password"
                                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                                    placeholder="@lang('dashboard.enter') @lang('dashboard.password')"
                                                    aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('password') ? $errors->first('password') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.password_confirm')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="la la-exclamation-triangle flaticon-lock"></i>
                                                    </span>
                                                </div>
                                                <input type="password" name="password_confirmation"
                                                    class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                                    placeholder="@lang('dashboard.enter') @lang('dashboard.password_confirm')"
                                                    aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('password_confirmation') ? $errors->first('password_confirmation') : '' }}</strong>
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
