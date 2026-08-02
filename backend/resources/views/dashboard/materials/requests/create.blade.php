@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.materials_requests')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.requests')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('dashboard.material-requests.index')}}">@lang('dashboard.materials_requests')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

@push('js')
    <script src="{{asset('dashboard_assets/custom/js/materials.js')}}" type="text/javascript"></script>
@endpush

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b mb-5">
                <div class="card-header">
                    <h3 class="card-title">{{$title}}</h3>
                    <div class="card-toolbar">
                        <a href="{{url('dashboard/materials')}}"
                           class="btn btn-primary ">
                            <i class="flaticon2-reply-1" style="font-size: 1rem;"></i> @lang('dashboard.back')
                        </a>
                    </div>
                </div>
                <form novalidate="novalidate" id="materialForm" class="kt-form kt-form--label-right"
                      method="post" action="{{ route('dashboard.material-requests.store') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h3>{{ __('dashboard.req_type') }}</h3>
                                <br>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12 ">
                                <div class="form-group validated">

                                    <label>{{ __('dashboard.select_req_type') }}</label>
                                    <select class="nice-select form-control row" name="type" id="req_type">
                                        <option value="inward_non-returnable"
                                                selected>{{ __('dashboard.inward_non-returnable') }}</option>
                                        <option value="inward_returnable">
                                            {{ __('dashboard.inward_returnable') }}
                                        </option>
                                        <option value="outward_non-returnable">
                                            {{ __('dashboard.outward_non-returnable') }}
                                        </option>
                                        <option value="outward_returnable">
                                            {{ __('dashboard.outward_returnable') }}
                                        </option>
                                        <option value="between_sites">
                                            {{ __('dashboard.between_sites') }}
                                        </option>
                                        <option value="personal_request">
                                            {{ __('dashboard.personal_request') }}
                                        </option>
                                    </select>
                                    <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px;">
                                        <strong>{{ $errors->has('type') ? $errors->first('type') : '' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"></div>

                            <div class="col-md-12 col-sm-12 mb-2 " id="receiver_info">
                                <div class="container border pt-7 bg-gray-100">
                                    <div class=" row">
                                        <div class="col-12">
                                            <h3>{{ __('dashboard.receiver_info') }}</h3>
                                            <br>
                                        </div>
                                        <div class="form-group-cont col-md-6 col-sm-12 mb-2">
                                            <div class="form-group validated">
                                                <label>{{ __('dashboard.meeting_location') }}</label>
                                                <select class="nice-select form-control row" name="site_id">
                                                    <option value="">@lang('dashboard.select_site')</option>
                                                    @foreach($data['sites'] as $site)
                                                        <option value="{{ $site->id }}"
                                                            {{session('site_id') == $site->id ? 'selected':''}}>{{ handleTrans($site->name) }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="text-danger"
                                                     style="margin-right: 6px !important; margin-top: 11px;">
                                                    <strong>{{ $errors->has('site_id') ? $errors->first('site_id') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-cont col-md-6 col-sm-12 mb-2">
                                            <div class="form-group validated">
                                                <label>{{ __('dashboard.department') }}</label>
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
                                        <div class="form-group-cont col-md-6 col-sm-12 mb-2">
                                            <div class="form-group validated">
                                                <label>{{ __('dashboard.employee_name') }}</label>
                                                <select class="select2 form-control row" name="host_id">
                                                    <option value="">@lang('dashboard.select_host')</option>
                                                    @foreach($data['users'] as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{auth()->id() == $user->id ? 'selected':''}}>{{ $user->full_name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="text-danger"
                                                     style="margin-right: 6px !important; margin-top: 11px;">
                                                    <strong>{{ $errors->has('host_id') ? $errors->first('host_id') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @include('dashboard.materials.requests.types._sender')

                            <div class="col-12" id="sender_info">
                                <div class="row">
                                    <div class="col-12">
                                        <hr>
                                        <h3>{{ __('dashboard.sender_info') }}</h3>
                                        <br>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-2">
                                        <label>{{ __('dashboard.company') }}</label>
                                        <input type="text"
                                               class="form-control mb-3 {{ $errors->has('company') ? 'is-invalid' : '' }}"
                                               name="company" value="{{ old('company') }}"
                                               placeholder="{{ __('dashboard.company') }}">
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->has('company') ? $errors->first('company') : '' }}</strong>
                                        </div>

                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-2">
                                        <label>{{ __('dashboard.contact_person_name') }}</label>
                                        <input type="text"
                                               class="form-control mb-3 {{ $errors->has('contact_person') ? 'is-invalid' : '' }}"
                                               name="contact_person" value="{{ old('contact_person') }}"
                                               placeholder="{{ __('dashboard.contact_person') }}">
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->has('contact_person') ? $errors->first('contact_person') : '' }}</strong>
                                        </div>

                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-2">
                                        <label>{{ __('dashboard.mobile_no') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control mb-3 {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                               name="phone" value="{{ old('phone') }}"
                                               placeholder="{{ __('dashboard.phone') }}">
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->has('phone') ? $errors->first('phone') : '' }}</strong>
                                        </div>

                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-2">
                                        <label>{{ __('dashboard.email') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" value="{{ old('email') }}"
                                               class="form-control mb-3 {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                               name="email" placeholder="{{ __('dashboard.email') }}">
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->has('email') ? $errors->first('email') : '' }}</strong>
                                        </div>

                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-2">
                                        <label>{{ __('dashboard.department') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control mb-3 {{ $errors->has('department') ? 'is-invalid' : '' }}"
                                               name="department" value="{{ old('department') }}"
                                               placeholder="{{ __('dashboard.department') }}">
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->has('department') ? $errors->first('department') : '' }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-2">
                                        <label>{{ __('dashboard.address') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control mb-3 {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                               name="address" value="{{ old('address') }}"
                                               placeholder="{{ __('dashboard.address') }}">
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->has('address') ? $errors->first('address') : '' }}</strong>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                <div class="col-12">
                                    <hr>
                                    <h3>{{ __('dashboard.visit_info') }}</h3>
                                    <br>
                                </div>

                                <div class="col-md-12" id="personal" style="display:none">
                                    @includeIf('dashboard.materials.types._personal')
                                </div>

                                <div class="col-md-12" id="visit_info">
                                    <div class="col-12" style="display:none" id="dispatch">
                                        @include('dashboard.materials.requests.types._dispatch')
                                    </div>

                                    <div class="col-12" id="delivery">
                                        @include('dashboard.materials.requests.types._delivery')
                                    </div>

                                    <div class="col-12" style="display:none" id="return">
                                        @include('dashboard.materials.requests.types._return')
                                    </div>

                                    <div class="col-12">
                                        <label for="">{{ __('dashboard.notes') }}</label>
                                        <textarea name="remarks" id="" cols="30" rows="4"
                                                  class="form-control mb-3"></textarea>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-12">
                                <br>
                                <hr>
                            </div>

                            <div class="col-10 my-6">
                                <h3>@lang('dashboard.materials')</h3>
                            </div>

                            <div class="col-2 my-6">
                                <a href="#" id="addMaterials" class="btn btn-block btn-outline-primary">
                                    <i class="flaticon-plus"></i> {{ __('dashboard.add_materials') }}
                                </a>
                            </div>

                            <div class="col-12">
                                <table class="table">
                                    <thead id="materials_head" class="thead-light" style="display: none;">
                                    <tr>
                                        <th>{{ __('dashboard.name') }}</th>
                                        <th>{{ __('dashboard.description') }}</th>
                                        <th>{{ __('dashboard.qty') }}</th>
                                        <th>{{ __('dashboard.status') }}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="materialsSelectedTable">
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-12">
                                <hr>
                                <br>
                            </div>
                            <div class="col-10 mb-6">
                                <h3>{{ __('dashboard.transporter_info') }}</h3>
                            </div>

                            <hr>

                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.company') }}</label>
                                <input type="text" required
                                       class="form-control mb-3 {{ $errors->has('company') ? 'is-invalid' : '' }}"
                                       name="contact_company" value="{{ old('contact_company') }}"
                                       placeholder="{{ __('dashboard.company') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('company') ? $errors->first('company') : '' }}</strong>
                                </div>

                            </div>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.contact_person_name') }}</label>
                                <input type="text" required
                                       class="form-control mb-3 {{ $errors->has('contact_person_name') ? 'is-invalid' : '' }}"
                                       name="contact_person_name" value="{{ old('contact_person_name') }}"
                                       placeholder="{{ __('dashboard.contact_person') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('contact_person_name') ? $errors->first('contact_person_name') : '' }}</strong>
                                </div>

                            </div>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.email') }}</label>
                                <input type="text" required
                                       class="form-control mb-3 {{ $errors->has('contact_person_email') ? 'is-invalid' : '' }}"
                                       name="contact_person_email" value="{{ old('contact_person_email') }}"
                                       placeholder="{{ __('dashboard.email') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('contact_person_email') ? $errors->first('contact_person_email') : '' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.id_type') }}</label>
                                <select class="nice-select form-control row" name="contact_id_type">
                                    <option value="type1">{{__('dashboard.mobile')}}</option>
                                    <option value="type2">{{__('dashboard.pid')}}</option>
                                </select>
                                <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                                    <strong>{{ $errors->has('id_type') ? $errors->first('id_type') : '' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.id_number') }}</label>
                                <input type="number"
                                       class="form-control mb-3 {{ $errors->has('id_number') ? 'is-invalid' : '' }}"
                                       name="contact_id_number" value="{{ old('contact_id_number') }}"
                                       placeholder="{{ __('dashboard.id_number') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('id_number') ? $errors->first('id_number') : '' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.phone') }}</label>
                                <input type="text"
                                       class="form-control mb-3 {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                       name="contact_phone" value="{{ old('contact_phone') }}"
                                       placeholder="{{ __('dashboard.phone') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('phone') ? $errors->first('phone') : '' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.p_count') }}</label>
                                <input type="number"
                                       class="form-control mb-3 {{ $errors->has('p_count') ? 'is-invalid' : '' }}"
                                       name="contact_people_count" value="{{ old('contact_people_count') }}"
                                       placeholder="{{ __('dashboard.p_count') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('p_count') ? $errors->first('p_count') : '' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.vehicle_deal') }}</label>
                                <input type="text"
                                       class="form-control mb-3 {{ $errors->has('vehicle_deal') ? 'is-invalid' : '' }}"
                                       name="contact_vehicle_details"
                                       value="{{ old('contact_vehicle_details') }}"
                                       placeholder="{{ __('dashboard.vehicle_deal') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('vehicle_deal') ? $errors->first('vehicle_deal') : '' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>{{ __('dashboard.materials') }}</label>
                                <input type="text"
                                       class="form-control mb-3 {{ $errors->has('materials') ? 'is-invalid' : '' }}"
                                       name="contact_materials" value="{{ old('contact_materials') }}"
                                       placeholder="{{ __('dashboard.materials') }}">
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('materials') ? $errors->first('materials') : '' }}</strong>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="">{{ __('dashboard.remarks') }}</label>
                                <textarea name="contact_remarks" value="{{ old('contact_remarks') }}" id=""
                                          cols="30" rows="4" class="form-control mb-3"></textarea>
                            </div>
                        </div>
                    </div>
                    <select name="materials_id[]" id="materials_id" multiple hidden>
                    </select>
                    <div class="card-footer">
                        <button type="submit" id="submitRequestForm"
                                class="btn btn-primary">@lang('dashboard.submit')</button>
                        <button type="reset" class="btn btn-secondary">@lang('dashboard.cancel')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('dashboard.materials.requests.extra._create_material')
@endsection
