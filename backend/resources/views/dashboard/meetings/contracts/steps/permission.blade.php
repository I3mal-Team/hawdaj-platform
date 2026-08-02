<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.contract_name') }}</label>
            <select class="nice-select form-control" name="contract_name" value="{{ $contractorRequest->contractRequest->contract->id }}" disabled>
                <option value="{{$contractorRequest->contractRequest->contract->id }}">
                    {{$contractorRequest->contractRequest->contract->name}}
                </option>
            </select>
            <div class="invalid-feedback">
                <strong>{{ $errors->has('contract_name') ? $errors->first('contract_name') : '' }}</strong>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.contract_type') }}</label>
            <select class="nice-select form-control" name="contract_type_id" value="{{ $contractorRequest->contractRequest->contract->contract_type->id }}" disabled>
                <option value="{{$contractorRequest->contractRequest->contract->contract_type->id}}">
                    {{$contractorRequest->contractRequest->contract->contract_type->name}}
                </option>
            </select>
            <div class="invalid-feedback">
                <strong>{{ $errors->has('contract_type_id') ? $errors->first('contract_type_id') : '' }}</strong>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label>{{ __('dashboard.company') }}</label>
            <select name="company_id" class="nice-select form-control" disabled>
                <option value="{{$contractorRequest->contractRequest['company_id']}}">
                    {{$contractorRequest->contractRequest->company->name}}
                </option>
            </select>
            <div class="fv-plugins-message-container"></div>
        </div>
        <div class="invalid-feedback">
            <strong>{{ $errors->has('company_id') ? $errors->first('company_id') : '' }}</strong>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.site') }}</label>
            <select class="nice-select form-control" name="site_id" value="{{ $contractorRequest->contractRequest['site_id'] }}" disabled>
                <option value="{{$contractorRequest->contractRequest['site_id']}}">{{$contractorRequest->contractRequest->site->name}}</option>
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
                <option value="{{$contractorRequest->contractRequest->contract['department_id']}}" selected>
                    {{$contractorRequest->contractRequest->contract->department->name}}
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
            <select class="nice-select form-control" name="contract_manager_id"  disabled>
                <option value="{{$contractorRequest->contractRequest['contract_manager_id']}}">
                    {{$contractorRequest->contractRequest['contract_manager']->full_name}}
                </option>
            </select>
        </div>
        <div class="invalid-feedback">
            <strong>{{ $errors->has('contract_manager_id') ? $errors->first('contract_manager_id') : '' }}</strong>
        </div>
    </div>
    <div class="col-12">
        <h3 class="wizard-title">{{ __('dashboard.Schedule') }}</h3>
        <br>
    </div>

    <div class="col-6">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.visit_from') }}</label>
            <input type="date" class="form-control form-control-solid" name="from_date" placeholder="{{ __('dashboard.visit_from') }}" value="{{ $contractorRequest->contractRequest->contract['from_date'] }}" disabled>
        </div>
        <div class="invalid-feedback">
            <strong>{{ $errors->has('from_date') ? $errors->first('from_date') : '' }}</strong>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group fv-plugins-icon-container">
            <label class="text-dark">{{ __('dashboard.visit_to') }}</label>
            <input type="date" class="form-control form-control-solid" name="to_date" placeholder="{{ __('dashboard.visit_to') }}" value="{{ $contractorRequest->contractRequest->contract['to_date'] }}" disabled>
        </div>
        <div class="invalid-feedback">
            <strong>{{ $errors->has('to_date') ? $errors->first('to_date') : '' }}</strong>
        </div>
    </div>

</div>
