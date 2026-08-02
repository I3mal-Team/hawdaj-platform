<div class="row">
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.visitor_type') }}</label>
            <select class="nice-select form-control" name="visitor_type" value="{{ $visitorRequest->visitRequest['type'] }}" disabled>
                <option value="{{$visitorRequest->visitRequest['visitType']}}">
                    {{$visitorRequest->visitRequest['visitType']->name}}
                </option>
            </select>
            <div class="invalid-feedback">
                <strong>{{ $errors->has('visitor_type') ? $errors->first('visitor_type') : '' }}</strong>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label>{{ __('dashboard.visit_reason') }}</label>
            <select name="reason_id" class="nice-select form-control" disabled>
                <option value="{{$visitorRequest->visitRequest['reason']}}">
                    {{$visitorRequest->visitRequest['reason']->reason}}
                </option>
            </select>
            <div class="fv-plugins-message-container"></div>
        </div>
        <div class="invalid-feedback">
            <strong>{{ $errors->has('visit_reason') ? $errors->first('visit_reason') : '' }}</strong>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.sit') }}</label>
            <select class="nice-select form-control" name="site_id" value="{{ $visitorRequest->visitRequest['site_id'] }}" disabled>
                <option value="siteId">{{ __('dashboard.sites') }}</option>
            </select>
            <div class="invalid-feedback">
                <strong>{{ $errors->has('site_id') ? $errors->first('site_id') : '' }}</strong>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label>{{ __('dashboard.department') }}</label>
            <select name="department_id" class="nice-select form-control" disabled>
                <option value="{{$visitorRequest->visitRequest['department']}}" selected>
                    {{$visitorRequest->visitRequest['department']->name}}
                </option>
            </select>
        </div>
        <div class="invalid-feedback">
            <strong>{{ $errors->has('department_id') ? $errors->first('department_id') : '' }}</strong>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.hosts') }}</label>
            <select class="nice-select form-control" name="host_id"  disabled>
                <option value="{{$visitorRequest->visitRequest['host_id']}}">
                    {{$visitorRequest->visitRequest['host']->full_name}}
                </option>
            </select>
        </div>
        <div class="invalid-feedback">
            <strong>{{ $errors->has('host_id') ? $errors->first('host_id') : '' }}</strong>
        </div>
    </div>
    <div class="col-12">
        <h3 class="wizard-title">{{ __('dashboard.Schedule') }}</h3>
        <br>
    </div>

    <div class="col-6">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.visit_from') }}</label>
            <input type="date" class="form-control form-control-solid" name="from_date" placeholder="{{ __('dashboard.visit_from') }}" value="{{ $visitorRequest->visitRequest['from_date'] }}" disabled>
        </div>
        <div class="invalid-feedback">
            <strong>{{ $errors->has('from_date') ? $errors->first('from_date') : '' }}</strong>
        </div>
    </div>

    <div class="col-6">
        <div class="row">
            <div class="col-6">
                <div class="form-group fv-plugins-icon-container">
                    <label class="text-dark">{{ __('dashboard.from') }}</label>
                    <input type="time" class="form-control form-control-solid" name="from_fromtime" placeholder="{{ __('dashboard.visit_from') }}" value="{{ $visitorRequest->visitRequest['from_fromtime'] }}" disabled>
                </div>
                <div class="invalid-feedback">
                    <strong>{{ $errors->has('from_fromtime') ? $errors->first('from_fromtime') : '' }}</strong>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group fv-plugins-icon-container">
                    <label class="text-dark">{{ __('dashboard.to') }}</label>
                    <input type="time" class="form-control form-control-solid" name="from_totime" placeholder="{{ __('dashboard.visit_to') }}" value="{{ $visitorRequest->visitRequest['from_totime'] }}" disabled>
                </div>
                <div class="invalid-feedback">
                    <strong>{{ $errors->has('from_totime') ? $errors->first('from_totime') : '' }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.visit_to') }}</label>
            <input type="date" class="form-control form-control-solid" name="to_date" placeholder="{{ __('dashboard.visit_to') }}" value="{{ $visitorRequest->visitRequest['to_date'] }}" disabled>
        </div>
        <div class="invalid-feedback">
            <strong>{{ $errors->has('to_date') ? $errors->first('to_date') : '' }}</strong>
        </div>
    </div>

    <div class="col-6">
        <div class="row">
            <div class="col-6">
                <div class="form-group fv-plugins-icon-container">
                    <label class="text-dark">{{ __('dashboard.from') }}</label>
                    <input type="time" class="form-control form-control-solid" name="to_fromtime" placeholder="{{ __('dashboard.visit_from') }}" value="{{ $visitorRequest->visitRequest['to_fromtime'] }}" disabled>
                </div>
                <div class="invalid-feedback">
                    <strong>{{ $errors->has('to_fromtime') ? $errors->first('to_fromtime') : '' }}</strong>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group fv-plugins-icon-container">
                    <label class="text-dark">{{ __('dashboard.to') }}</label>
                    <input type="time" class="form-control form-control-solid" name="to_totime" placeholder="{{ __('dashboard.visit_to') }}" value="{{ $visitorRequest->visitRequest['to_totime'] }}" disabled>
                </div>
                <div class="invalid-feedback">
                    <strong>{{ $errors->has('to_totime') ? $errors->first('to_totime') : '' }}</strong>
                </div>
            </div>
        </div>
    </div>

</div>
