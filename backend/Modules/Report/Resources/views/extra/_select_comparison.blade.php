<div class="form-group validated">
    <label>@lang('dashboard.sites_to_comparisons')</label>
    <span class="text-danger"> * </span>
    <select class="form-control nice-select {{ inputError('site_ids')}}" name="site_ids[]" multiple>
        @foreach($sites as $site)
            <option value="{{$site->id}}">{{__("dashboard.".$site->name)}}</option>
        @endforeach
    </select>
    <div class="invalid-feedback">
        <strong>{{ msgError('site_ids')}}</strong>
    </div>
</div>
