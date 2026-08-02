<?php

namespace App\Http\Requests\EnhancedTrip;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveEnhancedTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'prepare_token' => 'required|string',
            'items' => 'required|array',
            'items.*' => 'required|array|min:1',
            'items.*.*' => 'integer|exists:places,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('dashboard.validation.trip_name.required'),
            'name.string' => __('dashboard.validation.trip_name.string'),
            'name.max' => __('dashboard.validation.trip_name.max'),
            'prepare_token.required' => __('dashboard.validation.prepare_token.required'),
            'items.required' => __('dashboard.validation.items.required'),
            'items.array' => __('dashboard.validation.items.array'),
            'items.*.required' => __('dashboard.validation.items_day.required'),
            'items.*.array' => __('dashboard.validation.items_day.array'),
            'items.*.min' => __('dashboard.validation.items_day.min'),
            'items.*.*.integer' => __('dashboard.validation.place_id.integer'),
            'items.*.*.exists' => __('dashboard.validation.place_id.exists'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors()
        ], 422));
    }

    protected function prepareForValidation()
    {
        // Handle items if sent as string (JSON string)
        if ($this->has('items') && is_string($this->items)) {
            $this->merge([
                'items' => json_decode($this->items, true)
            ]);
        }
    }
}

