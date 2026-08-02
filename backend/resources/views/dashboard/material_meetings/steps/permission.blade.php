<div class="row">

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group fv-plugins-icon-container">
                <label class="text-dark">{{ __('dashboard.type') }}</label>
                <select class="nice-select form-control" name="material_type" value="{{ $materialOfRequest->materialRequest['type'] }}" disabled>
                    <option value="{{$materialOfRequest->materialRequest['type']}}">
                        {{$materialOfRequest->materialRequest['type']}}
                    </option>
                </select>
                <div class="invalid-feedback">
                    <strong>{{ $errors->has('material_type') ? $errors->first('material_type') : '' }}</strong>
                </div>
            </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.sit') }}</label>
            <select class="nice-select form-control" name="site_id" value="{{ $materialOfRequest->materialRequest->site['name'] }}" disabled>
                <option value="siteId">{{ $materialOfRequest->materialRequest->site['name'] }}</option>
            </select>
            <div class="invalid-feedback">
                <strong>{{ $errors->has('site_id') ? $errors->first('site_id') : '' }}</strong>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.department') }}</label>
            <select class="nice-select form-control" name="department_id" value="{{ $materialOfRequest->materialRequest->department }}" disabled>
                <option value="departmentId">{{ $materialOfRequest->materialRequest->department }}</option>
            </select>
            <div class="invalid-feedback">
                <strong>{{ $errors->has('department_id') ? $errors->first('department_id') : '' }}</strong>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.host') }}</label>
            <select class="nice-select form-control" name="host_id" value="{{ $materialOfRequest->materialRequest->host->full_name }}" disabled>
                <option value="hostId">{{ $materialOfRequest->materialRequest->host->first_name }}</option>
            </select>
            <div class="invalid-feedback">
                <strong>{{ $errors->has('host_id') ? $errors->first('host_id') : '' }}</strong>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.phone') }}</label>
                <input type="text" name="phone"
                    class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('dashboard.purpose_phone') }}"
                    value="{{ $materialOfRequest->materialRequest->phone }}" disabled/>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.email') }}</label>
                <input type="text" name="email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('dashboard.purpose_email') }}"
                    value="{{ $materialOfRequest->materialRequest->email }}" disabled/>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.contact_person') }}</label>
                <input type="text" name="contact_person"
                    class="form-control {{ $errors->has('contact_person') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('dashboard.purpose_contact_person') }}"
                    value="{{ $materialOfRequest->materialRequest->contact_person }}" disabled/>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.address') }}</label>
                <input type="text" name="address"
                    class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('dashboard.purpose_address') }}"
                    value="{{ $materialOfRequest->materialRequest->address }}" disabled/>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.delivery_date') }}</label>
                <input type="text" name="delivery_date"
                    class="form-control {{ $errors->has('delivery_date') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('dashboard.purpose_delivery_date') }}"
                    value="{{ $materialOfRequest->materialRequest->delivery_date }}" disabled/>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-6">
                <div class="form-group fv-plugins-icon-container">
                    <label class="text-dark">{{ __('dashboard.from') }}</label>
                        <input type="text" name="delivery_from_time"
                            class="form-control {{ $errors->has('delivery_from_time') ? 'is-invalid' : '' }}"
                            placeholder="{{ __('dashboard.purpose_delivery_from_time') }}"
                            value="{{ $materialOfRequest->materialRequest->delivery_from_time }}" disabled/>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group fv-plugins-icon-container">
                    <label class="text-dark">{{ __('dashboard.to') }}</label>
                        <input type="text" name="delivery_to_time"
                            class="form-control {{ $errors->has('delivery_to_time') ? 'is-invalid' : '' }}"
                            placeholder="{{ __('dashboard.purpose_delivery_to_time') }}"
                            value="{{ $materialOfRequest->materialRequest->delivery_to_time }}" disabled/>
                </div>
            </div>
        </div>
    </div>

</div>
