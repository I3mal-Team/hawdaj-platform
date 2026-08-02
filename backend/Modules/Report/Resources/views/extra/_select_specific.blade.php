<div class="form-group validated">
    <label>@lang('dashboard.specific_site')</label>
    <span class="text-danger"> * </span>
    <select class="form-control nice-select {{ inputError('site_id')}}" name="site_id">
        <option value="">@lang('dashboard.select_site')</option>
        @foreach($sites as $site)
            <option value="{{$site->id}}">{{__("dashboard.".$site->name)}}</option>
        @endforeach
    </select>
    <div class="invalid-feedback">
        <strong>{{ msgError('site_id')}}</strong>
    </div>
</div>
