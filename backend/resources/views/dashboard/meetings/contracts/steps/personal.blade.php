<div class="row">

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.first_name') }} <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-solid" name="first_name" placeholder="{{ __('dashboard.first_name') }}" value="{{ $contractorRequest->contractor['first_name'] }}">
            <div class="fv-plugins-message-container"></div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.last_name') }} <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-solid" name="last_name" placeholder="{{ __('dashboard.last_name') }}" value="{{ $contractorRequest->contractor['last_name'] }}">
            <div class="fv-plugins-message-container"></div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.id_type') }} <span class="text-danger"> *</span></label>
            <input type="text" class="form-control form-control-solid" name="id_type" placeholder="{{ __('dashboard.id_type') }}" value="{{ $contractorRequest->contractor['id_type'] }}">
            <div class="fv-plugins-message-container"></div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.id_number') }} <span class="text-danger"> *</span></label>
            <input type="text" class="form-control form-control-solid" name="id_number" placeholder="{{ __('dashboard.id_number') }}" value="{{ $contractorRequest->contractor['id_number'] }}">
            <div class="fv-plugins-message-container"></div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.mobile') }} <span class="text-danger"> *</span></label>
            <input type="text" class="form-control form-control-solid" name="mobile" placeholder="{{ __('dashboard.mobile') }}" value="{{ $contractorRequest->contractor['mobile'] }}">
            <div class="fv-plugins-message-container"></div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.email') }} <span class="text-danger"> *</span></label>
            <input type="email" class="form-control form-control-solid" name="email" placeholder="{{ __('dashboard.email') }}" value="{{ $contractorRequest->contractor['email'] }}">
            <div class="fv-plugins-message-container"></div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.gender') }} <span class="text-danger"> *</span></label>
            <select class="nice-select form-control" value="{{ $contractorRequest->contractor['gender'] }}" name="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <div class="fv-plugins-message-container"></div>
        </div>
    </div>

{{--    <div class="col-12">--}}
{{--        <h3>{{ __('dashboard.company_&_contact_info') }}</h3>--}}
{{--        <br>--}}
{{--    </div>--}}

{{--    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">--}}
{{--        <div class="form-group fv-plugins-icon-container">--}}
{{--            <label class="text-dark">{{ __('dashboard.company') }} <span class="text-danger"> *</span></label>--}}
{{--            <input type="text" class="form-control form-control-solid" name="company"--}}
{{--                   placeholder="{{ __('dashboard.company') }}" value="{{ optional($contractorRequest->contractor->company)['name'] }}">--}}
{{--            <div class="fv-plugins-message-container"></div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">--}}
{{--        <div class="form-group fv-plugins-icon-container">--}}
{{--            <label class="text-dark">{{ __('dashboard.position') }} <span class="text-danger"> *</span></label>--}}
{{--            <input type="text" class="form-control form-control-solid" name="position"--}}
{{--                   placeholder="{{ __('dashboard.position') }}"--}}
{{--                   value="{{ optional($contractorRequest->contractor->company)['position'] }}">--}}
{{--            <div class="fv-plugins-message-container"></div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    --}}
{{--    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">--}}
{{--        <div class="form-group fv-plugins-icon-container">--}}
{{--            <label class="text-dark">{{ __('dashboard.mobile') }}</label>--}}
{{--            <input type="text" class="form-control form-control-solid" name="company_mobile"--}}
{{--                   placeholder="{{ __('dashboard.mobile') }}"  value="{{optional($contractorRequest->contractor->company)['mobile']}}">--}}
{{--            <div class="fv-plugins-message-container"></div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">--}}
{{--        <div class="form-group fv-plugins-icon-container">--}}
{{--            <label class="text-dark">{{ __('dashboard.email') }}</label>--}}
{{--            <input type="text" class="form-control form-control-solid" name="company_email"--}}
{{--                   placeholder="{{ __('dashboard.email') }}" value="{{optional($contractorRequest->contractor->company)['email']}}">--}}
{{--            <div class="fv-plugins-message-container"></div>--}}
{{--        </div>--}}
{{--    </div>--}}


    <div class="col-12">
        <h3 class="wizard-title">{{ __('dashboard.otherInfo') }}</h3>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.vehicle_detail') }}</label>
            <input type="text" class="form-control form-control-solid" name="vehicle_detail"
                   placeholder="{{ __('dashboard.vehicle_detail') }}" value="{{$contractorRequest->contractor['vehicle_detail']}}">
            <div class="fv-plugins-message-container"></div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="text-dark">{{ __('dashboard.vehicle_material') }}</label>
        <div class="form-group fv-plugins-icon-container">
            <input type="text" class="form-control form-control-solid" name="vehicle_material"
                   placeholder="{{ __('dashboard.vehicle_material') }}" value="{{$contractorRequest->contractor['vehicle_material']}}">
            <div class="fv-plugins-message-container"></div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group row validated">
            <div class="col-md-10">
                <label>{{__('dashboard.personal_photo')}} <span class="text-danger"> *</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="flaticon2-image-file"></i>
                            </span>
                    </div>
                    <input type="file"  name="personal_photo"
                            class="form-control file {{ $errors->has('personal_photo') ? 'is-invalid' : '' }}"
                            placeholder="{{__('dashboard.enter')}} {{__('dashboard.personal_photo')}}">
                    <div class="invalid-feedback">
                        <strong>{{ $errors->has('personal_photo') ? $errors->first('personal_photo') : '' }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-2 image">
        <div class="image_prev_form thumb-output">
                    <img src="{{resolvePhoto($contractorRequest->contractor['personal_photo'])}}"/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group row validated">
            <div class="col-md-10">
                <label>{{__('dashboard.id_copy')}} <span class="text-danger"> *</span> </label>
                <div class="input-group">
                    <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="flaticon2-image-file"></i>
                            </span>
                    </div>
                    <input type="file"  name="id_copy"
                            class="form-control file {{ $errors->has('id_copy') ? 'is-invalid' : '' }}"
                            placeholder="{{__('dashboard.enter')}} {{__('dashboard.id_copy')}}">
                    <div class="invalid-feedback">
                        <strong>{{ $errors->has('id_copy') ? $errors->first('id_copy') : '' }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-2 image">
        <div class="image_prev_form thumb-output">
            <img src="{{resolvePhoto($contractorRequest->contractor['id_copy'])}}"/>
                </div>
            </div>
        </div>
    </div>
</div>
