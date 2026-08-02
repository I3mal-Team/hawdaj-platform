<div class="row">
    <div class="col-md-6 col-sm-12 mb-2">
        <label>{{ __('dashboard.expected_delivery_date') }} <span class="text-danger">*</span> </label>
        <input type="date" min="<?= date('Y-m-d') ?>" value="{{ old('delivery_date') }}" class="form-control {{ $errors->has('delivery_date') ? 'is-invalid' : '' }}"name="delivery_date">
        <div class="invalid-feedback">
            <strong>{{ $errors->has('delivery_date') ? $errors->first('delivery_date') : '' }}</strong>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="row">
            <div class="col-6">
                <label>{{ __('dashboard.from') }} <span class="text-danger">*</span> </label>
                <input type="time" class="form-control {{ $errors->has('delivery_from_time') ? 'is-invalid' : '' }}"name="delivery_from_time" value="{{ old('delivery_from_time') }}">
                <div class="invalid-feedback">
                    <strong>{{ $errors->has('delivery_from_time') ? $errors->first('delivery_from_time') : '' }}</strong>
                </div>
            </div>
            <div class="col-6">
                <label>{{ __('dashboard.to') }} <span class="text-danger">*</span> </label>
                <input type="time" class="form-control {{ $errors->has('delivery_to_time') ? 'is-invalid' : '' }}"name="delivery_to_time" value="{{ old('delivery_to_time') }}">
                <div class="invalid-feedback">
                    <strong>{{ $errors->has('delivery_to_time') ? $errors->first('delivery_to_time') : '' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
