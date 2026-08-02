<div class="row">
    <div class="col-md-6 col-sm-12 mb-2">
        <label>{{ __('dashboard.expected_dispatch_date') }} <span class="text-danger">*</span> </label>
        <input type="date" class="form-control mb-3 {{ $errors->has('dispatch_date') ? 'is-invalid' : '' }}" min="<?= date('Y-m-d') ?>" name="dispatch_date" value="{{ old('dispatch_date') }}">
        <div class="invalid-feedback">
            <strong>{{ $errors->has('dispatch_date') ? $errors->first('dispatch_date') : '' }}</strong>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="row">
            <div class="col-6">
                <label>{{ __('dashboard.from') }} <span class="text-danger">*</span> </label>
                <input type="time" class="form-control mb-3 {{ $errors->has('dispatch_from_time') ? 'is-invalid' : '' }}"name="dispatch_from_time" value="{{ old('dispatch_from_time') }}">
                <div class="invalid-feedback">
                    <strong>{{ $errors->has('dispatch_from_time') ? $errors->first('dispatch_from_time') : '' }}</strong>
                </div>
            </div>
            <div class="col-6">
                <label>{{ __('dashboard.to') }} <span class="text-danger">*</span> </label>
                <input type="time" class="form-control mb-3 {{ $errors->has('dispatch_to_time') ? 'is-invalid' : '' }}"name="dispatch_to_time" value="{{ old('dispatch_to_time') }}">
                <div class="invalid-feedback">
                    <strong>{{ $errors->has('dispatch_to_time') ? $errors->first('dispatch_to_time') : '' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
