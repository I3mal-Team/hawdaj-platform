{{-- Optional: $orderIdModel (e.g. $data, $application) for edit forms; omit on create --}}
@php
    $orderIdDefault = isset($orderIdModel) ? (int) ($orderIdModel->order_id ?? 0) : 0;
@endphp
<div class="col-md-3">
    <div class="form-group validated">
        <label>@lang('dashboard.order_id')</label>
        <input type="number" name="order_id" class="form-control {{ $errors->has('order_id') ? 'is-invalid' : '' }}"
            value="{{ old('order_id', $orderIdDefault) }}" min="0" step="1">
        <div class="invalid-feedback"><strong>{{ $errors->first('order_id') }}</strong></div>
    </div>
</div>
