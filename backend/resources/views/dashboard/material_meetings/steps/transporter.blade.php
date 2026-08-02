<div class="row">

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.company') }}</label>
            <input type="text" name="company"
                    class="form-control {{ $errors->has('company') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('dashboard.company') }}"
                    value="{{ $materialOfRequest->transporter['company'] }}"/>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.contact_person') }}</label>
            <input type="text" name="contact_person"
                    class="form-control {{ $errors->has('contact_person') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('dashboard.contact_person') }}"
                    value="{{ $materialOfRequest->transporter['contact_person'] }}"/>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.id_number') }}</label>
            <input type="number" name="id_number"
                    class="form-control {{ $errors->has('id_number') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('dashboard.id_number') }}"
                    value="{{ $materialOfRequest->transporter['id_number'] }}"/>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.phone') }}</label>
            <input type="text" name="phone"
                    class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('dashboard.phone') }}"
                    value="{{ $materialOfRequest->transporter['phone'] }}"/>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.people_count') }}</label>
            <input type="number" name="people_count"
                    class="form-control {{ $errors->has('people_count') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('dashboard.people_count') }}"
                    value="{{ $materialOfRequest->transporter['people_count'] }}"/>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.vehicle_details') }}</label>
            <input type="text" name="vehicle_details"
                    class="form-control {{ $errors->has('vehicle_details') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('dashboard.vehicle_details') }}"
                    value="{{ $materialOfRequest->transporter['vehicle_details'] }}"/>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.materials') }}</label>
            <input type="text" name="materials"
                    class="form-control {{ $errors->has('materials') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('dashboard.materials') }}"
                    value="{{ $materialOfRequest->transporter['materials'] }}"/>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.remarks') }}</label>
            <textarea name="remarks" cols="30" rows="4"
                class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}"
                placeholder="{{ __('dashboard.commit_for_reception') }}">{{$materialOfRequest->transporter['remarks']}}</textarea>
        </div>
    </div>

</div>