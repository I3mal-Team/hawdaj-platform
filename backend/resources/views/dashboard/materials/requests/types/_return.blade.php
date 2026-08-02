<div class="row">
    <div class="col-md-6 col-sm-12 mb-2">
        <label>{{ __('dashboard.expected_return_date') }} <span class="text-danger">*</span> </label>
        <input value="{{ old('return_date') }}" type="date" class="form-control mb-3 {{ $errors->has('return_date') ? 'is-invalid' : '' }}" min="<?= date('Y-m-d') ?>" name="return_date">
        <div class="invalid-feedback">
            <strong>{{ $errors->has('return_date') ? $errors->first('return_date') : '' }}</strong>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="row">
            <div class="col-6">
                <label>{{ __('dashboard.from') }} <span class="text-danger">*</span> </label>
                <input type="time" class="form-control mb-3 {{ $errors->has('return_from_time') ? 'is-invalid' : '' }}"name="return_from_time" value="{{ old('return_from_time') }}">
                <div class="invalid-feedback">
                    <strong>{{ $errors->has('return_from_time') ? $errors->first('return_from_time') : '' }}</strong>
                </div>
            </div>
            <div class="col-6">
                <label>{{ __('dashboard.to') }} <span class="text-danger">*</span> </label>
                <input type="time" class="form-control mb-3 {{ $errors->has('return_to_time') ? 'is-invalid' : '' }}"name="return_to_time" value="{{ old('return_to_time') }}">
                <div class="invalid-feedback">
                    <strong>{{ $errors->has('return_to_time') ? $errors->first('return_to_time') : '' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
