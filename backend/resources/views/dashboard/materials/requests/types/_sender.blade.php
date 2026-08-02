<div class="col-md-6 col-sm-12 mb-2 " id="sender_info_inside" style="display:none;">
    <div class="container border pt-7 bg-gray-100">
        <div class="row">
            <div class="col-12">
                <h3>{{ __('dashboard.sender_info') }}</h3>
                <br>
            </div>
            <div class="col-md-12 col-sm-12 mb-2">
                <div class="form-group validated">
                    <label>{{ __('dashboard.meeting_location') }}</label>
                    <select class="nice-select form-control row" name="sender_site_id">
                        <option value="">@lang('dashboard.select_site')</option>
                        @foreach($data['sites'] as $site)
                            <option value="{{ $site->id }}">{{ handleTrans($site->name) }}</option>
                        @endforeach
                    </select>
                    <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                        <strong>{{ $errors->has('sender_site_id') ? $errors->first('sender_site_id') : '' }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 mb-2">
                <div class="form-group validated">
                    <label>{{ __('dashboard.department') }}</label>
                    <select name="sender_department_id" class="nice-select form-control">
                        <option value="">@lang('dashboard.select_department')</option>
                        @foreach($data['departments'] as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                        <strong>{{ $errors->has('sender_department_id') ? $errors->first('sender_department_id') : '' }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 mb-2">
                <div class="form-group validated">
                    <label>{{ __('dashboard.employee_name') }}</label>
                    <select class="select2 form-control row" name="sender_host_id">
                        <option value="">@lang('dashboard.select_host')</option>
                        @foreach($data['users'] as $user)
                            <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                        @endforeach
                    </select>
                    <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px;">
                        <strong>{{ $errors->has('sender_host_id') ? $errors->first('sender_host_id') : '' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
