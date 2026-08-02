@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.vendors')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.vendors')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.create_vendor')</a>
        </li>
    </ul>
@endsection

@push('js')
    <script>
        $(function(){
            $('#role').on('change', function () {
                let selected_option = $(this).find(':selected').data('name');

                if (selected_option == 'guard') {
                    $("#gate_select").slideDown('fast');

                } else  {
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
                            <h3 class="card-title">@lang('dashboard.create_vendor')</h3>
                            <div class="card-toolbar">
                                <a href="{{url('dashboard/vendors')}}"
                                   class="btn btn-primary ">
                                    <i class="flaticon2-reply-1" style="font-size: 1rem;"></i> @lang('dashboard.back')
                                </a>
                            </div>
                        </div>
                        <form novalidate="novalidate" class="kt-form kt-form--label-right" method="post"
                              action="{{ route('dashboard.vendors.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.first_name') </label> <span class="text-danger"> *</span>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="la la-exclamation-triangle flaticon-vendor"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="first_name" value="{{old("first_name")}}"
                                                       class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                                       placeholder="@lang('dashboard.enter') @lang('dashboard.first_name') "
                                                       aria-describedby="basic-addon1"
                                                />
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
                                            <span class="input-group-text" id="basic-addon1">
                                                <i class="la la-exclamation-triangle flaticon-vendor"></i>
                                            </span>
                                                </div>
                                                <input type="text" name="last_name" value="{{old("last_name")}}"
                                                       class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                                                       placeholder="@lang('dashboard.enter') @lang('dashboard.last_name') "
                                                       aria-describedby="basic-addon1"
                                                />
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
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="la la-exclamation-triangle flaticon-envelope"></i>
                                                    </span>
                                                </div>
                                                <input type="email" name="email" value="{{old("email")}}"
                                                       autocomplete="off"
                                                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                       placeholder="@lang('dashboard.enter') @lang('dashboard.email')"
                                                       aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('email') ? $errors->first('email') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang("dashboard.role")</label>
                                            <span class="text-danger"> *</span>
                                            <select id="role" class="form-control nice-select " name="role">
                                                <option value="">@lang('dashboard.select_role')</option>
                                                @foreach($roles as $role)
                                                    <option value="{{$role->id}}" data-name="{{strtolower($role->name)}}"
                                                        {{ $role->id == old("role") ? 'selected' : '' }}>
                                                        {{$role->label}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('role') ? $errors->first('role') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.phone')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="la la-mobile"></i>
                                                    </span>
                                                </div>
                                                <input name="phone" type="number" value="{{old("phone")}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) {
                                                           this.value = this.value.slice(0, this.maxLength);
                                                       }"
                                                       min=9 maxlength=11
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
                                        <div class="form-group row validated">
                                            <div class="col-md-10">
                                                <label>{{__('dashboard.vendor_photo')}}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="flaticon2-image-file"></i>
                                                        </span>
                                                    </div>
                                                    <input type="file" name="photo"
                                                           class="form-control file {{ $errors->has('photo') ? 'is-invalid' : '' }}"
                                                           placeholder="{{__('dashboard.enter')}} {{__('dashboard.vendor_photo')}}"
                                                           aria-describedby="basic-addon1">
                                                    <div class="invalid-feedback">
                                                        <strong>{{ $errors->has('photo') ? $errors->first('photo') : '' }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 image">
                                                <div class="image_prev_form thumb-output">
                                                    <img src="{{asset('dashboard_assets/media/vendors/default.jpg')}}"/>
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
                                                <input type="password" value=""
                                                       autocomplete="new-password"
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
                                                       placeholder="@lang('dashboard.password_again')"
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


