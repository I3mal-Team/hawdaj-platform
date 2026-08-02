@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.cars_request')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.requests')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('dashboard.car-requests.index')}}" class="">@lang('dashboard.cars_request')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b mb-5">
                <div class="card-header">
                    <h3 class="card-title">{{$title}}</h3>
                    <div class="card-toolbar">
                        <a href="{{url('dashboard/cars-request')}}"
                           class="btn btn-primary ">
                            <i class="flaticon2-reply-1" style="font-size: 1rem;"></i> @lang('dashboard.back')
                        </a>
                    </div>
                </div>
                <form novalidate="novalidate" id="carForm" class="kt-form kt-form--label-right"
                      method="post" action="{{ route('dashboard.car-requests.store') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="">
                                    <h3>{{ __('dashboard.receiver_info') }}</h3>
                                    <br>
                                </label>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <div class="form-group validated">
                                    <label>{{ __('dashboard.branch_location') }}</label>
                                    <span class="text-danger">*</span>
                                    <select class="nice-select form-control row" name="site_id">
                                        <option value="">@lang('dashboard.select_site')</option>
                                        @foreach($data['sites'] as $site)
                                            <option value="{{ $site->id }}"
                                                {{session('site_id') == $site->id ? 'selected':''}}>{{ $site->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger"
                                         style="margin-right: 6px !important; margin-top: 11px; ">
                                        <strong>{{ $errors->has('site_id') ? $errors->first('site_id') : '' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 mb-2">
                                <div class="form-group validated">
                                    <label>{{ __('dashboard.department') }}</label>
                                    <span class="text-danger">*</span>
                                    <select name="department_id" class="nice-select form-control">
                                        <option value="">@lang('dashboard.select_department')</option>
                                        @foreach($data['departments'] as $department)
                                            <option value="{{ $department->id }}"
                                                {{auth()->user()->department->id == $department->id ? 'selected':''}}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger"
                                         style="margin-right: 6px !important; margin-top: 11px; ">
                                        <strong>{{ $errors->has('department_id') ? $errors->first('department_id') : '' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 mb-2">
                                <div class="form-group validated">
                                    <label>{{ __('dashboard.employee_dispatch') }}</label>
                                    <span class="text-danger">*</span>
                                    <select class="select2 form-control row" name="host_id">
                                        <option value="">@lang('dashboard.select_host')</option>
                                        @foreach($data['users'] as $user)
                                            <option value="{{ $user->id }}"
                                                {{auth()->id() == $user->id ? 'selected':''}}>{{ $user->full_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger"
                                         style="margin-right: 6px !important; margin-top: 11px; ">
                                        <strong>{{ $errors->has('host_id') ? $errors->first('host_id') : '' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="">
                                    <h3>{{ __('dashboard.driver_details') }}</h3>
                                    <br>
                                </label>
                            </div>

                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.id_number') }}</label>
                                <span class="text-danger">*</span>
                                <input type="number"
                                       class="form-control {{ $errors->has('id_number') ? 'is-invalid' : '' }}"
                                       name="id_number" value="{{ old('id_number') }}"
                                       placeholder="{{ __('dashboard.id_number') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('id_number') ? $errors->first('id_number') : '' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.contact_person_name') }}</label>
                                <span class="text-danger">*</span>
                                <input type="text"
                                       class="form-control {{ $errors->has('contact_person_name') ? 'is-invalid' : '' }}"
                                       name="contact_person_name" value="{{ old('contact_person_name') }}"
                                       placeholder="{{ __('dashboard.contact_person_name') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('contact_person_name') ? $errors->first('contact_person_name') : '' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.mobile_no') }}</label>
                                <span class="text-danger">*</span>
                                <input type="number"
                                       class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                       name="phone" value="{{ old('phone') }}"
                                       placeholder="{{ __('dashboard.phone') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('phone') ? $errors->first('phone') : '' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.email') }}</label>
                                <span class="text-danger">*</span>
                                <input type="email" value="{{ old('email') }}"
                                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                       name="email" placeholder="{{ __('dashboard.email') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('email') ? $errors->first('email') : '' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.vehicle_deal') }}</label>
                                <input type="text"
                                       class="form-control {{ $errors->has('vehicle_deal') ? 'is-invalid' : '' }}"
                                       name="vehicle_details"
                                       value="{{ old('contact_vehicle_details') }}"
                                       placeholder="{{ __('dashboard.vehicle_deal') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('vehicle_deal') ? $errors->first('vehicle_deal') : '' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.licence') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control {{ $errors->has('licence') ? 'is-invalid' : '' }}"
                                       name="licence" value="{{ old('licence') }}"
                                       placeholder="{{ __('dashboard.licence') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('licence') ? $errors->first('licence') : '' }}</strong>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr>
                            </div>

                            <div class="col-12">
                                @include('dashboard.cars.types._delivery')
                            </div>

                            <div class="col-12"><hr></div>

                            <div class="col-12">
                                <label for="">{{ __('dashboard.remarks') }}</label>
                                <textarea name="remarks" id="" cols="30" rows="4" class="form-control"></textarea>
                            </div>

                            <div class="col-12">
                                <br>
                                <hr>
                            </div>
                            <div class="col-10 mb-6"><h3>@lang('dashboard.car_details')</h3></div>
                            <hr>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.plate_ar') }}</label>
                                <span class="text-danger">*</span>
                                <input type="text" required
                                       class="form-control {{ $errors->has('plate_ar') ? 'is-invalid' : '' }}"
                                       name="plate_ar" value="{{ old('plate_ar') }}"
                                       placeholder="{{ __('dashboard.plate_ar') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('plate_ar') ? $errors->first('plate_ar') : '' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.plate_en') }}</label>
                                <span class="text-danger">*</span>
                                <input type="text" required
                                       class="form-control {{ $errors->has('plate_en') ? 'is-invalid' : '' }}"
                                       name="plate_en" value="{{ old('plate_en') }}"
                                       placeholder="{{ __('dashboard.plate_en') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('plate_en') ? $errors->first('plate_en') : '' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.licence') }}</label>
                                <span class="text-danger">*</span>
                                <input type="text" required
                                       class="form-control {{ $errors->has('licence') ? 'is-invalid' : '' }}"
                                       name="licence" value="{{ old('licence') }}"
                                       placeholder="{{ __('dashboard.licence') }}">
                            </div>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.type') }}</label>
                                <input type="text"
                                       class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}"
                                       name="type" value="{{ old('type') }}"
                                       placeholder="{{ __('dashboard.type') }}">
                            </div>

                            <div class="col-12">
                                <label for="">{{ __('dashboard.description') }}</label>
                                <textarea name="description" value="{{ old('description') }}" id=""
                                          cols="30" rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" id="submitRequestForm"
                                class="btn btn-primary">@lang('dashboard.submit')</button>
                        <button type="reset" class="btn btn-secondary">@lang('dashboard.cancel')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
